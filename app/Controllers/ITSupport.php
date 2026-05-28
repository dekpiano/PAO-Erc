<?php

namespace App\Controllers;

use App\Models\ITSupportModel;
use App\Models\UserModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ITSupport extends BaseController
{
    protected $itsModel;

    public function __construct()
    {
        $this->itsModel = new ITSupportModel();
    }

    /**
     * Normalize วันที่จากทุกรูปแบบ (iPhone Safari/flatpickr/Chrome)
     * ให้เป็น MySQL datetime format: Y-m-d H:i:s
     */
    private function normalizeDate(?string $dateStr): string
    {
        if (empty($dateStr)) {
            return date('Y-m-d H:i:s');
        }

        // ตัดช่องว่างหน้า-หลังและตัวอักษรที่ไม่จำเป็น
        $dateStr = trim($dateStr);

        // ลองแปลงตรงๆ ด้วย strtotime ก่อน (รองรับรูปแบบมาตรฐานส่วนใหญ่)
        $timestamp = strtotime($dateStr);
        if ($timestamp !== false && $timestamp > 0) {
            return date('Y-m-d H:i:s', $timestamp);
        }

        // ลองรูปแบบ dd/mm/yyyy HH:mm (Thai format จาก flatpickr altFormat)
        $formats = [
            'd/m/Y H:i',
            'd/m/Y H:i:s',
            'd-m-Y H:i',
            'd-m-Y H:i:s',
            'Y/m/d H:i:s',
            'Y/m/d H:i',
            'm/d/Y H:i:s',
            'm/d/Y H:i',
        ];

        foreach ($formats as $format) {
            $parsed = \DateTime::createFromFormat($format, $dateStr);
            if ($parsed !== false) {
                // ถ้าปีเกิน 2400 แสดงว่าเป็นปี พ.ศ. ต้องลบ 543
                $year = (int) $parsed->format('Y');
                if ($year > 2400) {
                    $parsed->modify('-543 years');
                }
                return $parsed->format('Y-m-d H:i:s');
            }
        }

        // ถ้าแปลงไม่ได้เลย คืนค่าวันเวลาปัจจุบัน
        return date('Y-m-d H:i:s');
    }

    /**
     * ออโต้ออเรียนเทชัน (แก้ไขรูปถ่ายกลับด้านจากกล้องมือถือ/iPhone) พร้อมทั้งย่อขนาดและบีบอัดไฟล์ภาพให้เบาสบายและแจ่มเหมือนเดิม
     */
    private function autoOrientAndResize(string $filePath, int $maxWidth = 1200, int $quality = 85)
    {
        // เพิ่มหน่วยความจำและเวลาประมวลผลชั่วคราว เพื่อรองรับภาพความละเอียดสูงมาก (เช่น 12MP-48MP จากมือถือ / iPhone)
        @ini_set('memory_limit', '512M');
        @set_time_limit(120);

        $mime = @mime_content_type($filePath);
        if ($mime !== 'image/jpeg' && $mime !== 'image/jpg') {
            // หากไม่ใช่ JPEG ข้ามขั้นตอนหมุนภาพ (EXIF) ไปปรับขนาดโดยตรง เพื่อความเสถียรและป้องกันการค้าง
            try {
                \Config\Services::image()
                    ->withFile($filePath)
                    ->resize($maxWidth, $maxWidth, true, 'width')
                    ->save($filePath, $quality);
            } catch (\Exception $e) { }
            return;
        }

        // 1. จัดการ Orientation สำหรับรูปภาพ JPEG จากมือถือ / iPhone (ตรวจสอบการมีอยู่ของฟังก์ชัน GD ป้องกัน Fatal Crash)
        if (function_exists('exif_read_data') && function_exists('imagecreatefromjpeg') && function_exists('imagerotate')) {
            $exif = @exif_read_data($filePath);
            if (!empty($exif['Orientation'])) {
                $image = @imagecreatefromjpeg($filePath);
                
                if ($image) {
                    $deg = 0;
                    switch ($exif['Orientation']) {
                        case 3:
                            $deg = 180;
                            break;
                        case 6:
                            $deg = 270; // 90 degrees clockwise (ทวนเข็ม 270)
                            break;
                        case 8:
                            $deg = 90; // 90 degrees counter-clockwise
                            break;
                    }
                    
                    if ($deg > 0) {
                        $rotated = @imagerotate($image, $deg, 0);
                        if ($rotated) {
                            @imagejpeg($rotated, $filePath, 95);
                            @imagedestroy($rotated);
                        }
                    }
                    @imagedestroy($image);
                }
            }
        }

        // 2. ปรับย่อขนาดด้วย Image Library ของ CI4 เพื่อให้รูปภาพมีขนาดเบาแต่แจ่มเหมือนเดิม
        try {
            \Config\Services::image()
                ->withFile($filePath)
                ->resize($maxWidth, $maxWidth, true, 'width')
                ->save($filePath, $quality);
        } catch (\Exception $e) { }
    }

    /**
     * ตรวจสอบสิทธิ์การเข้าถึงระบบ
     */
    private function checkAccess()
    {
        $userRoles = session()->get('u_role') ?? '';
        $isSuper = (strpos($userRoles, 'superadmin') !== false);
        $isIT = (strpos($userRoles, 'it_support') !== false);

        if (!$isSuper && !$isIT) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าใช้ระบบ IT Support');
        }
        return true;
    }

    /**
     * หน้าแรกหลัก: ไทม์ไลน์การทำงาน (Timeline Feed)
     * อิงตามดีไซน์ SKJ Work Journey ต้นแบบ
     */
    public function index()
    {
        $userRoles = session()->get('u_role') ?? '';
        $isSuper = (strpos($userRoles, 'superadmin') !== false);
        $isIT = (strpos($userRoles, 'it_support') !== false);
        $data['can_manage'] = ($isSuper || $isIT);

        $searchTerm = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $location = $this->request->getGet('location');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $query = $this->itsModel->select('Tb_It_Support_Logs.*, Tb_Users.u_photo')
                                ->join('Tb_Users', 'Tb_Users.u_id = Tb_It_Support_Logs.its_user_id', 'left');

        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                           ->like('its_task', $searchTerm)
                           ->orLike('its_recorded_by', $searchTerm)
                           ->orLike('its_ticket_code', $searchTerm)
                           ->groupEnd();
        }

        if (!empty($category)) {
            $query = $query->where('its_category', $category);
        }

        if (!empty($location)) {
            $query = $query->like('its_location', $location);
        }

        if (!empty($startDate)) {
            $query = $query->where('its_date >=', $startDate . ' 00:00:00');
        }

        if (!empty($endDate)) {
            $query = $query->where('its_date <=', $endDate . ' 23:59:59');
        }

        // ดึงรายการสถานที่ทั้งหมดเพื่อออโต้ฟิลประวัติเก่า
        $db = \Config\Database::connect();
        $data['locations'] = array_column(
            $db->table('Tb_It_Support_Logs')
               ->select('its_location')
               ->groupBy('its_location')
               ->get()
               ->getResultArray(), 
            'its_location'
        );

        $data['title'] = "ไทม์ไลน์การทำงาน IT Support | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        
        // ดึงข้อมูลล็อกทั้งหมดสำหรับแสดงแบบไทม์ไลน์
        $data['logs'] = $query->orderBy('its_date', 'DESC')->paginate(15, 'default');
        $data['pager'] = $this->itsModel->pager;
        
        // พารามิเตอร์การค้นหา/ฟิลเตอร์
        $data['search'] = $searchTerm;
        $data['category_active'] = $category;
        $data['location_active'] = $location;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;

        // ดึงรหัสใบงานรันต่อถัดไปสำหรับช่องด่วนโพสต์
        $data['next_ticket_code'] = $this->itsModel->generateTicketCode();

        return view('itsupport/index', $data);
    }

    /**
     * หน้าวิเคราะห์สถิติกราฟรายงาน (Stats & Charts Dashboard)
     */
    public function stats()
    {
        $access = $this->checkAccess();
        if ($access !== true) return $access;

        $data['title'] = "รายงานวิเคราะห์สถิติ IT Support | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');

        // สถิติยอดรวมงานทั้งหมด
        $data['total_tasks'] = $this->itsModel->countAllResults();

        // สถิติตามเดือนปัจจุบัน
        $currentMonthStart = date('Y-m-01 00:00:00');
        $currentMonthEnd = date('Y-m-t 23:59:59');
        $data['month_tasks'] = $this->itsModel->where('its_date >=', $currentMonthStart)
                                              ->where('its_date <=', $currentMonthEnd)
                                              ->countAllResults();

        // 8 กลุ่มหมวดหมู่หลัก
        $categories = [
            "🛠️ IT Support & Service", "🎤 งานโสตทัศนศึกษา", "📸 ผลิตสื่อและประชาสัมพันธ์", 
            "📊 งานสารสนเทศโรงเรียน", "🤝 สนับสนุนงานฝ่าย/อาคาร", "👥 งานประชุม", 
            "📚 การอบรม/พัฒนาตนเอง", "🏛️ งานอื่นๆ ตามคำสั่ง"
        ];

        $categoryStats = [];
        foreach ($categories as $cat) {
            $count = $this->itsModel->where('its_category', $cat)->countAllResults();
            $categoryStats[$cat] = $count;
        }
        $data['category_stats'] = $categoryStats;

        // ดึงบอร์ดจัดอันดับทีมไอที (Workload Leaderboard)
        $db = \Config\Database::connect();
        $builder = $db->table('Tb_It_Support_Logs');
        $builder->select('its_recorded_by, COUNT(its_id) as job_count, its_user_id');
        $builder->groupBy(['its_user_id', 'its_recorded_by']);
        $builder->orderBy('job_count', 'DESC');
        $data['leaderboard'] = $builder->get()->getResultArray();

        // ดึงรายการบันทึกผลงานล่าสุด 5 รายการ
        $data['recent_logs'] = $this->itsModel->orderBy('its_date', 'DESC')->findAll(5);

        return view('itsupport/dashboard', $data);
    }

    /**
     * ฟอร์มเพิ่มงานบริการ (หน้าต่างเฉพาะแยก)
     */
    public function create()
    {
        $access = $this->checkAccess();
        if ($access !== true) return $access;

        $db = \Config\Database::connect();
        $data['locations'] = array_column(
            $db->table('Tb_It_Support_Logs')
               ->select('its_location')
               ->groupBy('its_location')
               ->get()
               ->getResultArray(), 
            'its_location'
        );

        $data['title'] = "บันทึกงานบริการ IT Support ใหม่";
        $data['fullname'] = session()->get('u_fullname');
        $data['ticket_code'] = $this->itsModel->generateTicketCode();

        return view('itsupport/create', $data);
    }

    /**
     * บันทึกข้อมูลงานบริการใหม่ (Store Action)
     */
    public function store()
    {
        $access = $this->checkAccess();
        if ($access !== true) return $access;

        $rules = [
            'its_date'     => 'required',
            'its_category' => 'required',
            'its_task'     => 'required|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $uploadedImages = [];
        $imageFiles = $this->request->getFiles();

        if (isset($imageFiles['images'])) {
            $targetDir = FCPATH . 'uploads/it_support/';
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }

            foreach ($imageFiles['images'] as $img) {
                if ($img->isValid() && !$img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move($targetDir, $newName);

                    // ออโต้ออเรียนเทชัน ย่อรูปเหลือ 1200px ชัดแจ๋วเบาสบาย
                    $this->autoOrientAndResize($targetDir . $newName, 1200, 85);

                    $uploadedImages[] = $newName;
                }
            }
        }

        $ticketCode = $this->itsModel->generateTicketCode();

        $normalizedDate = $this->normalizeDate($this->request->getPost('its_date'));

        $this->itsModel->insert([
            'its_ticket_code' => $ticketCode,
            'its_date'        => $normalizedDate,
            'its_category'    => $this->request->getPost('its_category'),
            'its_location'    => $this->request->getPost('its_location') ?: 'ศูนย์เทคโนโลยีสารสนเทศ',
            'its_task'        => $this->request->getPost('its_task'),
            'its_recorded_by' => session()->get('u_fullname') ?: 'ผู้ช่วยนักวิชาการคอมพิวเตอร์',
            'its_user_id'     => session()->get('u_id') ?: 1,
            'its_images'      => !empty($uploadedImages) ? json_encode($uploadedImages) : null
        ]);

        return redirect()->to(base_url('itsupport'))->with('success', 'บันทึกประวัติการทำงานเรียบร้อยแล้ว');
    }

    /**
     * ฟอร์มแก้ไขข้อมูลบริการ
     */
    public function edit($id)
    {
        $access = $this->checkAccess();
        if ($access !== true) return $access;

        $log = $this->itsModel->find($id);
        if (!$log) {
            return redirect()->to(base_url('itsupport'))->with('error', 'ไม่พบข้อมูลที่ต้องการแก้ไข');
        }

        $db = \Config\Database::connect();
        $data['locations'] = array_column(
            $db->table('Tb_It_Support_Logs')
               ->select('its_location')
               ->groupBy('its_location')
               ->get()
               ->getResultArray(), 
            'its_location'
        );

        $data['title'] = "แก้ไขบันทึกงานบริการ " . $log['its_ticket_code'];
        $data['fullname'] = session()->get('u_fullname');
        $data['log'] = $log;

        return view('itsupport/edit', $data);
    }

    /**
     * อัปเดตข้อมูลบริการ
     */
    public function update($id)
    {
        // Debug: log all incoming data
        log_message('debug', 'ITSupport::update called. ID=' . $id . ' POST=' . json_encode($this->request->getPost()) . ' FILES=' . json_encode($this->request->getFiles()));

        try {
            $access = $this->checkAccess();
            if ($access !== true) return $access;

            $log = $this->itsModel->find($id);
            if (!$log) {
                return redirect()->to(base_url('itsupport'))->with('error', 'ไม่พบข้อมูล');
            }

            $rules = [
                'its_date'     => 'required',
                'its_category' => 'required',
                'its_task'     => 'required|min_length[5]'
            ];

            if (!$this->validate($rules)) {
                $errors = $this->validator->getErrors();
                log_message('debug', 'ITSupport::update validation failed: ' . json_encode($errors));
                // Return JSON for AJAX requests
                if ($this->request->isAJAX()) {
                    return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => implode(', ', $errors)]);
                }
                return redirect()->back()->withInput()->with('errors', $errors);
            }

            $uploadedImages = !empty($log['its_images']) ? json_decode($log['its_images'], true) : [];
            $imageFiles = $this->request->getFiles();

            // ★ ตรวจจับไฟล์รูปภาพใหม่อย่างปลอดภัย (รองรับทั้ง form submit ปกติ และ fetch+FormData)
            $hasNewImages = false;
            if (isset($imageFiles['images']) && is_array($imageFiles['images'])) {
                foreach ($imageFiles['images'] as $img) {
                    if ($img->isValid() && $img->getSize() > 0) {
                        $hasNewImages = true;
                        break;
                    }
                }
            }

            log_message('debug', 'ITSupport::update images check: hasNewImages=' . ($hasNewImages ? 'YES' : 'NO') . ' existingImages=' . json_encode($uploadedImages));

            if ($hasNewImages) {
                // ลบรูปเก่าออกจากดิสก์
                if (!empty($uploadedImages)) {
                    foreach ($uploadedImages as $oldImg) {
                        @unlink(FCPATH . 'uploads/it_support/' . $oldImg);
                    }
                }

                $uploadedImages = [];
                $targetDir = FCPATH . 'uploads/it_support/';

                // ★ สร้างโฟลเดอร์พร้อมตั้ง permission ให้ถูกต้อง (umask อาจบล็อก 0777 ใน mkdir)
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                    @chmod($targetDir, 0755);
                }
                // ★ ตรวจสอบสิทธิ์เขียนก่อนดำเนินการ
                if (!is_writable($targetDir)) {
                    @chmod($targetDir, 0755);
                    // ลองสร้าง parent ด้วย
                    @chmod(FCPATH . 'uploads/', 0755);
                    if (!is_writable($targetDir)) {
                        log_message('error', 'ITSupport::update uploads directory not writable: ' . $targetDir);
                        throw new \RuntimeException('โฟลเดอร์อัปโหลดไม่มีสิทธิ์เขียน กรุณาเรียก: sudo chown -R www-data:www-data ' . $targetDir);
                    }
                }

                foreach ($imageFiles['images'] as $img) {
                    if ($img->isValid() && !$img->hasMoved() && $img->getSize() > 0) {
                        $newName = $img->getRandomName();

                        // ★ ลอง move() ปกติก่อน ถ้าไม่ได้ให้ fallback ด้วย copy+unlink
                        try {
                            $img->move($targetDir, $newName);
                        } catch (\Throwable $moveErr) {
                            log_message('warning', 'ITSupport::update move() failed, trying copy fallback: ' . $moveErr->getMessage());
                            // Fallback: copy จาก temp file แล้วลบ temp
                            $tmpPath = $img->getTempName();
                            if ($tmpPath && file_exists($tmpPath)) {
                                if (!copy($tmpPath, $targetDir . $newName)) {
                                    throw new \RuntimeException('ไม่สามารถคัดลอกไฟล์ภาพไปยัง ' . $targetDir . ' ได้ — ตรวจสอบสิทธิ์โฟลเดอร์ด้วย: sudo chown -R www-data:www-data ' . FCPATH . 'uploads/');
                                }
                                @chmod($targetDir . $newName, 0644);
                            } else {
                                throw new \RuntimeException('ไม่พบไฟล์ temp ที่อัปโหลด: ' . ($tmpPath ?: 'null'));
                            }
                        }

                        // ออโต้ออเรียนเทชัน ย่อรูปเหลือ 1200px ชัดแจ๋วเบาสบาย
                        $this->autoOrientAndResize($targetDir . $newName, 1200, 85);

                        $uploadedImages[] = $newName;
                    }
                }
                log_message('debug', 'ITSupport::update new images saved: ' . json_encode($uploadedImages));
            }

            $normalizedDate = $this->normalizeDate($this->request->getPost('its_date'));

            $updateData = [
                'its_date'     => $normalizedDate,
                'its_category' => $this->request->getPost('its_category'),
                'its_location' => $this->request->getPost('its_location') ?: 'ศูนย์เทคโนโลยีสารสนเทศ',
                'its_task'     => $this->request->getPost('its_task'),
                'its_images'   => !empty($uploadedImages) ? json_encode($uploadedImages) : null
            ];

            log_message('debug', 'ITSupport::update data: ' . json_encode($updateData));

            $this->itsModel->update($id, $updateData);

            // Return JSON for AJAX requests
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'ปรับปรุงประวัติการซ่อมบำรุงเรียบร้อยแล้ว']);
            }

            return redirect()->to(base_url('itsupport'))->with('success', 'ปรับปรุงประวัติการซ่อมบำรุงเรียบร้อยแล้ว');

        } catch (\Throwable $e) {
            log_message('error', 'ITSupport::update EXCEPTION: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n" . $e->getTraceAsString());
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
            }
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * ลบประวัติบริการ
     */
    public function delete($id)
    {
        $access = $this->checkAccess();
        if ($access !== true) return $access;

        $log = $this->itsModel->find($id);
        if ($log) {
            $images = !empty($log['its_images']) ? json_decode($log['its_images'], true) : [];
            if (!empty($images)) {
                foreach ($images as $img) {
                    @unlink(FCPATH . 'uploads/it_support/' . $img);
                }
            }

            $this->itsModel->delete($id);
            return redirect()->to(base_url('itsupport'))->with('success', 'ลบบันทึกประวัติการทำงานเรียบร้อยแล้ว');
        }

        return redirect()->to(base_url('itsupport'))->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
    }

    /**
     * ดูประวัติเดี่ยวการ์ดพรีเมียม
     */
    public function view($id)
    {
        $userRoles = session()->get('u_role') ?? '';
        $isSuper = (strpos($userRoles, 'superadmin') !== false);
        $isIT = (strpos($userRoles, 'it_support') !== false);
        $data['can_manage'] = ($isSuper || $isIT);

        $log = $this->itsModel->select('Tb_It_Support_Logs.*, Tb_Users.u_photo')
                              ->join('Tb_Users', 'Tb_Users.u_id = Tb_It_Support_Logs.its_user_id', 'left')
                              ->find($id);
        if (!$log) {
            return redirect()->to(base_url('itsupport'))->with('error', 'ไม่พบข้อมูลใบงาน');
        }

        $data['title'] = "รายละเอียดใบงาน " . $log['its_ticket_code'];
        $data['fullname'] = session()->get('u_fullname');
        $data['log'] = $log;

        return view('itsupport/view', $data);
    }

    /**
     * พิมพ์ใบงานส่งมอบขนาด A4
     */
    public function printJob($id)
    {
        $access = $this->checkAccess();
        if ($access !== true) return $access;

        $log = $this->itsModel->find($id);
        if (!$log) {
            return "ไม่พบข้อมูลใบงานบริการนี้";
        }

        $data['log'] = $log;
        return view('itsupport/print', $data);
    }

    /**
     * ส่งออกรายงานสถิติงานซ่อมบำรุงทางเทคนิคเป็นไฟล์ Excel
     */
    public function exportExcel()
    {
        $access = $this->checkAccess();
        if ($access !== true) return $access;

        $searchTerm = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $location = $this->request->getGet('location');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $query = $this->itsModel;

        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                           ->like('its_task', $searchTerm)
                           ->orLike('its_recorded_by', $searchTerm)
                           ->orLike('its_ticket_code', $searchTerm)
                           ->groupEnd();
        }

        if (!empty($category)) {
            $query = $query->where('its_category', $category);
        }

        if (!empty($location)) {
            $query = $query->like('its_location', $location);
        }

        if (!empty($startDate)) {
            $query = $query->where('its_date >=', $startDate . ' 00:00:00');
        }

        if (!empty($endDate)) {
            $query = $query->where('its_date <=', $endDate . ' 23:59:59');
        }

        $results = $query->orderBy('its_date', 'DESC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'รายงานสรุปประวัติงานบริการคอมพิวเตอร์และเครือข่าย IT Support');
        $sheet->mergeCells('A1:G1');
        
        $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . date('d/m/Y H:i') . ' น.');
        $sheet->mergeCells('A2:G2');

        $sheet->setCellValue('A4', 'ลำดับ');
        $sheet->setCellValue('B4', 'รหัสใบงาน');
        $sheet->setCellValue('C4', 'วันที่ปฏิบัติงาน');
        $sheet->setCellValue('D4', 'หมวดหมู่การทำงาน');
        $sheet->setCellValue('E4', 'สถานที่ปฏิบัติงาน');
        $sheet->setCellValue('F4', 'รายละเอียดผลงานการแก้ไขทางเทคนิค');
        $sheet->setCellValue('G4', 'เจ้าหน้าที่ผู้ลงประวัติ');

        $rowIdx = 5;
        $i = 1;
        foreach ($results as $log) {
            $sheet->setCellValue('A' . $rowIdx, $i++);
            $sheet->setCellValue('B' . $rowIdx, $log['its_ticket_code']);
            $sheet->setCellValue('C' . $rowIdx, date('d/m/Y H:i', strtotime($log['its_date'])) . ' น.');
            $sheet->setCellValue('D' . $rowIdx, $log['its_category']);
            $sheet->setCellValue('E' . $rowIdx, $log['its_location']);
            $sheet->setCellValue('F' . $rowIdx, $log['its_task']);
            $sheet->setCellValue('G' . $rowIdx, $log['its_recorded_by']);
            $rowIdx++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "IT_Support_Report_" . date('Ymd_His') . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
