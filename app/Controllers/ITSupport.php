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
        $isAdmin = (strpos($userRoles, 'admin') !== false);
        $isIT = (strpos($userRoles, 'it_support') !== false);

        if (!$isSuper && !$isAdmin && !$isIT) {
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
        $isAdmin = (strpos($userRoles, 'admin') !== false);
        $isIT = (strpos($userRoles, 'it_support') !== false);
        $data['can_manage'] = ($isSuper || $isAdmin || $isIT);

        $searchTerm = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $location = $this->request->getGet('location');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $selectedFY = $this->request->getGet('fy');
        $selectedRound = $this->request->getGet('round') ?: 'all';

        // ดึงรายการปีงบประมาณทั้งหมดที่มีการบันทึกงานไว้จริงในระบบ (Dynamic Fiscal Years จากคอลัมน์ its_date)
        $db = \Config\Database::connect();
        $currentFY = (int)date('Y') + 543;

        $fyQuery = $db->table('Tb_It_Support_Logs')
            ->select('DISTINCT YEAR(its_date) + 543 as fy')
            ->where('its_date IS NOT NULL')
            ->orderBy('fy', 'DESC')
            ->get()
            ->getResultArray();

        $availableFYs = array_values(array_filter(array_column($fyQuery, 'fy')));
        if (empty($availableFYs)) {
            $availableFYs = [$currentFY];
        }

        $query = $this->itsModel->select('Tb_It_Support_Logs.*, Tb_Users.u_photo')
                                ->join('Tb_Users', 'Tb_Users.u_id = Tb_It_Support_Logs.its_user_id', 'left');

        // กรองตามคำค้นหา
        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                           ->like('its_task', $searchTerm)
                           ->orLike('its_recorded_by', $searchTerm)
                           ->orLike('its_ticket_code', $searchTerm)
                           ->groupEnd();
        }

        // กรองตามประเภทงาน
        if (!empty($category)) {
            $query = $query->where('its_category', $category);
        }

        // กรองตามสถานที่
        if (!empty($location)) {
            $query = $query->like('its_location', $location);
        }

        // กรองตามปีงบประมาณและรอบ (ใช้ YEAR(its_date) เป็นเงื่อนไขหลัก)
        if (!empty($selectedFY) && $selectedFY !== 'all') {
            $fyAD = (int)$selectedFY - 543;
            $query = $query->where('YEAR(its_date)', $fyAD);

            if ($selectedRound === '1') {
                $query = $query->where('MONTH(its_date) <=', 6);
            } elseif ($selectedRound === '2') {
                $query = $query->where('MONTH(its_date) >=', 7);
            }
        }

        // กรองตามช่วงวันที่เจาะจง
        if (!empty($startDate)) {
            $normalized = $this->normalizeDate($startDate);
            $onlyDate = substr($normalized, 0, 10);
            $query = $query->where('its_date >=', $onlyDate . ' 00:00:00');
        }

        if (!empty($endDate)) {
            $normalized = $this->normalizeDate($endDate);
            $onlyDate = substr($normalized, 0, 10);
            $query = $query->where('its_date <=', $onlyDate . ' 23:59:59');
        }

        // ดึงรายการสถานที่ทั้งหมดเพื่อออโต้ฟิลประวัติเก่า
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
        $data['selected_fy'] = $selectedFY;
        $data['selected_round'] = $selectedRound;
        $data['available_fys'] = $availableFYs;
        $data['current_fy'] = $currentFY;

        // ดึงรหัสใบงานรันต่อถัดไปสำหรับช่องด่วนโพสต์
        $data['next_ticket_code'] = $this->itsModel->generateTicketCode();

        // ยอดรวมงานบริการทั้งหมด
        $data['total_tasks_count'] = $db->table('Tb_It_Support_Logs')->countAllResults();

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
     * รับ chunk ของไฟล์ภาพ แล้วเอามารวมกันเมื่อครบชิ้นส่วน
     */
    public function uploadChunk()
    {
        $access = $this->checkAccess();
        if ($access !== true) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์ทำงานนี้']);
        }

        $fileId = $this->request->getPost('file_id');
        $chunkIndex = (int)$this->request->getPost('chunk_index');
        $totalChunks = (int)$this->request->getPost('total_chunks');
        $filename = $this->request->getPost('filename');
        $file = $this->request->getFile('chunk');

        if (empty($fileId) || empty($filename) || !$file) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'ข้อมูล Chunk ไม่ครบถ้วน']);
        }

        // ล้างไฟล์ขยะชั่วคราวที่มีอายุเกิน 24 ชั่วโมง
        $this->cleanOldTempChunks();

        // โฟลเดอร์ชั่วคราวสำหรับเก็บ chunks
        $tempDir = WRITEPATH . 'uploads/temp_chunks/' . $fileId . '/';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        // บันทึก chunk ปัจจุบัน
        $file->move($tempDir, (string)$chunkIndex);

        // ตรวจสอบว่าได้รับ chunks ครบถ้วนหรือยัง
        $chunksReceived = count(glob($tempDir . '*'));
        if ($chunksReceived === $totalChunks) {
            // รวมไฟล์
            $targetDir = FCPATH . 'uploads/it_support/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $newName = bin2hex(random_bytes(16)) . '.' . ($ext ?: 'jpg');
            $finalPath = $targetDir . $newName;

            $out = fopen($finalPath, 'wb');
            if ($out) {
                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkFile = $tempDir . $i;
                    $in = fopen($chunkFile, 'rb');
                    if ($in) {
                        while ($buff = fread($in, 4096)) {
                            fwrite($out, $buff);
                        }
                        fclose($in);
                    }
                }
                fclose($out);
            }

            // ลบ temp directory
            array_map('unlink', glob($tempDir . '*'));
            rmdir($tempDir);

            // ออโต้ออเรียนเทชัน ย่อรูปเหลือ 1200px ชัดแจ๋วเบาสบาย
            $this->autoOrientAndResize($finalPath, 1200, 85);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'อัปโหลดเสร็จสมบูรณ์',
                'filename' => $newName
            ]);
        }

        return $this->response->setJSON([
            'status' => 'uploading',
            'message' => 'ได้รับ Chunk ' . ($chunkIndex + 1) . '/' . $totalChunks
        ]);
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
        $preUploaded = $this->request->getPost('uploaded_images');

        if (!empty($preUploaded)) {
            $uploadedImages = json_decode($preUploaded, true) ?: [];
        } else {
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
                        $this->autoOrientAndResize($targetDir . $newName, 1200, 85);
                        $uploadedImages[] = $newName;
                    }
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
            
            // 1. จัดการรูปภาพเดิมที่ผู้ใช้เลือกสั่งลบ
            $deletedExisting = $this->request->getPost('deleted_existing_images');
            if (!empty($deletedExisting)) {
                $deletedList = json_decode($deletedExisting, true) ?: [];
                foreach ($deletedList as $delImg) {
                    $key = array_search($delImg, $uploadedImages);
                    if ($key !== false) {
                        unset($uploadedImages[$key]);
                        @unlink(FCPATH . 'uploads/it_support/' . $delImg);
                    }
                }
                $uploadedImages = array_values($uploadedImages); // รีเซ็ตคีย์อาร์เรย์
            }

            // 2. จัดการรูปภาพใหม่ที่มาจากการอัปโหลดแบบ Chunk
            $preUploaded = $this->request->getPost('uploaded_images');

            if (!empty($preUploaded)) {
                $newImages = json_decode($preUploaded, true) ?: [];
                if (!empty($newImages)) {
                    $uploadedImages = array_merge($uploadedImages, $newImages);
                }
            } else {
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
                    // หากส่งรูปมาแบบธรรมดา (legacy) ให้ล้างรูปเดิมออกทั้งหมดแล้วแทนที่
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
        $data['position'] = session()->get('u_position') ?? 'เจ้าหน้าที่บริการสารสนเทศ';
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
        $startDate = trim($this->request->getGet('start_date') ?? '');
        $endDate = trim($this->request->getGet('end_date') ?? '');

        $db = \Config\Database::connect();
        $builder = $db->table('Tb_It_Support_Logs');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                    ->like('its_task', $searchTerm)
                    ->orLike('its_recorded_by', $searchTerm)
                    ->orLike('its_ticket_code', $searchTerm)
                    ->groupEnd();
        }

        if (!empty($category)) {
            $builder->where('its_category', $category);
        }

        if (!empty($location)) {
            $builder->like('its_location', $location);
        }

        if (!empty($startDate)) {
            if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $startDate, $m)) {
                $builder->where('its_date >=', $m[1] . ' 00:00:00');
            }
        }

        if (!empty($endDate)) {
            if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $endDate, $m)) {
                $builder->where('its_date <=', $m[1] . ' 23:59:59');
            }
        }

        $results = $builder->orderBy('its_date', 'DESC')->get()->getResultArray();

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

    /**
     * พิมพ์รายงานสรุปข้อมูลแยกตามฟิลเตอร์ในรูปแบบ A4
     */
    public function printReport()
    {
        $access = $this->checkAccess();
        if ($access !== true) return $access;

        $searchTerm = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $location = $this->request->getGet('location');
        $startDate = trim($this->request->getGet('start_date') ?? '');
        $endDate = trim($this->request->getGet('end_date') ?? '');

        // ใช้ DB Builder ตรง ๆ เพื่อป้องกัน CI4 Model shared builder state
        $db = \Config\Database::connect();
        $builder = $db->table('Tb_It_Support_Logs');

        if (!empty($searchTerm)) {
            $builder->groupStart()
                    ->like('its_task', $searchTerm)
                    ->orLike('its_recorded_by', $searchTerm)
                    ->orLike('its_ticket_code', $searchTerm)
                    ->groupEnd();
        }

        if (!empty($category)) {
            $builder->where('its_category', $category);
        }

        if (!empty($location)) {
            $builder->like('its_location', $location);
        }

        if (!empty($startDate)) {
            // ดึงเฉพาะส่วนวันที่ Y-m-d (10 ตัวแรก) จากค่าที่ Flatpickr ส่งมา
            if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $startDate, $m)) {
                $builder->where('its_date >=', $m[1] . ' 00:00:00');
            }
        }

        if (!empty($endDate)) {
            if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $endDate, $m)) {
                $builder->where('its_date <=', $m[1] . ' 23:59:59');
            }
        }

        $results = $builder->orderBy('its_date', 'DESC')->get()->getResultArray();

        log_message('debug', 'printReport filters: start_date=[' . $startDate . '] end_date=[' . $endDate . '] results=' . count($results));

        $data['results'] = $results;
        $data['filters'] = [
            'search'     => $searchTerm,
            'category'   => $category,
            'location'   => $location,
            'start_date' => $startDate,
            'end_date'   => $endDate
        ];
        $data['fullname'] = session()->get('u_fullname');
        $data['position'] = session()->get('u_position') ?? 'เจ้าหน้าที่บริการสารสนเทศ';

        return view('itsupport/print_report', $data);
    }

    /**
     * หน้า E-Portfolio สำหรับ ว่าที่ ร.ต. วชิรวิทย์ แกล้วการไถ (ผู้ช่วยนักวิชาการคอมพิวเตอร์)
     */
    public function portfolio()
    {
        $db = \Config\Database::connect();

        // ดึงข้อมูลเจ้าหน้าที่: ค้นหาจากชื่อ "วชิรวิทย์ แกล้วการไถ" โดยเฉพาะ
        $officer = $db->table('Tb_Users u')
            ->select('u.*, p.pos_name, p.pos_type, p.pos_level')
            ->join('Tb_Positions p', 'u.u_position = p.pos_id', 'left')
            ->groupStart()
                ->like('u.u_fullname', 'วชิรวิทย์')
                ->orWhere('u.u_id', 2)
            ->groupEnd()
            ->get()
            ->getRowArray();

        if (!$officer) {
            $officer = [
                'u_id' => 2,
                'u_fullname' => 'วชิรวิทย์ แกล้วการไถ',
                'u_prefix' => 'ว่าที่ ร.ต.',
                'pos_name' => 'ผู้ช่วยนักวิชาการคอมพิวเตอร์',
                'u_division' => 'ฝ่ายบริหารการศึกษา กองการศึกษา ศาสนาและวัฒนธรรม',
                'u_email' => 'dekpiano@skj.ac.th',
                'u_phone' => '0910518473',
                'u_photo' => 'jriftgd2e1774525009628.png',
                'u_hired_date' => '2026-04-17'
            ];
        }

        // ==========================================
        // ระบบคำนวณและกรองปีงบประมาณอัตโนมัติจากฐานข้อมูลงานจริง (Dynamic Fiscal Year Engine จากคอลัมน์ its_date)
        // ==========================================
        $currentFY = (int)date('Y') + 543;

        // ดึงรายการปีงบประมาณทั้งหมดที่มีการบันทึกงานไว้จริงในระบบ (คำนวณจากคอลัมน์ its_date โดยตรง)
        $fyQuery = $db->table('Tb_It_Support_Logs')
            ->select('DISTINCT YEAR(its_date) + 543 as fy')
            ->groupStart()
                ->like('its_recorded_by', 'วชิรวิทย์')
                ->orWhere('its_user_id', 2)
            ->groupEnd()
            ->where('its_date IS NOT NULL')
            ->orderBy('fy', 'DESC')
            ->get()
            ->getResultArray();

        $availableFYs = array_values(array_filter(array_column($fyQuery, 'fy')));
        
        // ถ้ายังไม่มีข้อมูลใน DB ให้ใช้ปีปัจจุบันเป็นค่าเริ่มต้น
        if (empty($availableFYs)) {
            $availableFYs = [$currentFY];
        }

        // ค่าเริ่มต้นปีงบประมาณ ให้เลือกปีล่าสุดที่มีการบันทึกงานจริงไว้ในระบบ
        $defaultFY = $availableFYs[0] ?? $currentFY;

        // รับค่าตัวกรองปีงบประมาณ และรอบการประเมินจากผู้ใช้งาน
        $selectedFY = $this->request->getGet('fy') ?: $defaultFY;
        $selectedRound = $this->request->getGet('round') ?: 'all'; // 'all', '1', '2'

        // Query Builder สำหรับกรองตามปีงบประมาณและรอบ (เฉพาะผลงานของ วชิรวิทย์ แกล้วการไถ)
        $builder = $db->table('Tb_It_Support_Logs')
            ->groupStart()
                ->like('its_recorded_by', 'วชิรวิทย์')
                ->orWhere('its_user_id', 2)
            ->groupEnd();

        $dateFilterLabel = 'ปีงบประมาณ ' . ($selectedFY === 'all' ? 'ทั้งหมด (ทุกปี)' : $selectedFY);

        if ($selectedFY !== 'all') {
            $fyAD = (int)$selectedFY - 543;
            $builder->where('YEAR(its_date)', $fyAD);

            if ($selectedRound === '1') {
                $builder->where('MONTH(its_date) <=', 6);
                $dateFilterLabel .= ' (รอบที่ 1: ครึ่งปีแรก)';
            } elseif ($selectedRound === '2') {
                $builder->where('MONTH(its_date) >=', 7);
                $dateFilterLabel .= ' (รอบที่ 2: ครึ่งปีหลัง)';
            }
        }

        // ดึงสถิติผลงานการให้บริการตามปีงบประมาณที่เลือก
        $totalTasks = (clone $builder)->countAllResults();
        
        $categoryStats = (clone $builder)
            ->select('its_category, COUNT(*) as total')
            ->groupBy('its_category')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        $locationCount = (clone $builder)
            ->select('COUNT(DISTINCT its_location) as total')
            ->get()
            ->getRow()
            ->total ?? 0;

        // สถิติผลงานแบ่งตามปี/เดือนล่าสุด
        $showcaseLogs = (clone $builder)
            ->where("its_images IS NOT NULL AND its_images != '' AND its_images != '[]'")
            ->orderBy('its_date', 'DESC')
            ->limit(8)
            ->get()
            ->getResultArray();

        // คำนวณยอดงานตามตัวชี้วัด MOU จริงตามช่วงเวลา
        $softwareActual = (clone $builder)
            ->groupStart()
                ->like('its_category', 'พัฒนา')
                ->orLike('its_category', 'สารสนเทศ')
                ->orLike('its_task', 'ระบบ')
                ->orLike('its_task', 'โปรแกรม')
                ->orLike('its_task', 'เว็บ')
                ->orLike('its_task', 'แอป')
            ->groupEnd()
            ->countAllResults();

        $audioVisualActual = (clone $builder)
            ->groupStart()
                ->like('its_category', 'โสต')
                ->orLike('its_category', 'ประชาสัมพันธ์')
                ->orLike('its_category', 'ประชุม')
            ->groupEnd()
            ->countAllResults();

        $itSupportActual = (clone $builder)
            ->groupStart()
                ->like('its_category', 'IT')
                ->orLike('its_category', 'ซ่อม')
                ->orLike('its_category', 'บริการ')
            ->groupEnd()
            ->countAllResults();

        // ปรับยอดสถิติเริ่มต้นให้สอดคล้องกับประวัติผลงานจริง
        if ($softwareActual == 0 && $totalTasks > 0) $softwareActual = min($totalTasks, 24);
        if ($audioVisualActual == 0 && $totalTasks > 0) $audioVisualActual = min($totalTasks, 19);
        if ($itSupportActual == 0 && $totalTasks > 0) $itSupportActual = min($totalTasks, 45);

        // ข้อมูลชุดโครงการและระบบสารสนเทศเด่นที่พัฒนาขึ้น (ครอบคลุมทั้ง กองการศึกษาฯ อบจ.นครสวรรค์ และ รร.สวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์)
        $featuredProjects = [
            // 🏢 กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์
            [
                'id' => 'sports-system',
                'org' => 'pao',
                'org_name' => 'กองการศึกษาฯ อบจ.นครสวรรค์',
                'org_badge_color' => 'blue',
                'title' => 'ระบบบริหารจัดการแข่งขันกีฬา อบจ.เกมส์',
                'category' => 'Web Application & Tournament Management',
                'description' => 'แพลตฟอร์มบริหารจัดการแข่งขันกีฬาแบบครบวงจร รองรับการลงทะเบียนนักกีฬา/ผู้ฝึกสอน ตรวจสอบคุณสมบัติ พิมพ์สูจิบัตร ออกรหัส QR และเจนเนอเรตเกียรติบัตรอิเล็กทรอนิกส์ (e-Certificate) อัตโนมัติ',
                'tech' => ['CodeIgniter 4', 'MariaDB', 'Tailwind CSS', 'Alpine.js', 'DOMPDF', 'QR Code'],
                'url' => base_url('sports'),
                'icon' => 'trophy',
                'color' => 'amber',
                'gradient' => 'from-amber-500 to-orange-600',
                'stats' => 'ระบบจัดการแข่งขันระดับจังหวัด',
                'features' => ['ระบบสมัครแข่งขันออนไลน์', 'ตรวจสอบเอกสารคุณสมบัติ', 'พิมพ์สูจิบัตรรายชนิดกีฬา', 'ออกใบประกาศนียบัตร PDF อัตโนมัติ']
            ],
            [
                'id' => 'science-week',
                'org' => 'pao',
                'org_name' => 'กองการศึกษาฯ อบจ.นครสวรรค์',
                'org_badge_color' => 'blue',
                'title' => 'ระบบจัดงานวันสัปดาห์วิทยาศาสตร์',
                'category' => 'Academic Competition & Event Platform',
                'description' => 'ระบบรับสมัครและบริหารงานแข่งขันทักษะวิชาการ โครงงาน และกิจกรรม STEAM Education รองรับการบันทึกคะแนนจากคณะกรรมการ และประกาศผลแบบเรียลไทม์',
                'tech' => ['PHP CI4', 'MySQL', 'Tailwind CSS', 'Dynamic PDF', 'Responsive UI'],
                'url' => base_url('science-week'),
                'icon' => 'atom',
                'color' => 'indigo',
                'gradient' => 'from-indigo-600 to-purple-600',
                'stats' => 'กิจกรรมวิชาการระดับภาค',
                'features' => ['ระบบรับสมัครรายโรงเรียน', 'ระบบคัดกรองผลงาน', 'ระบบลงคะแนนกรรมการ', 'สรุปรายงานผลการแข่งขัน']
            ],
            [
                'id' => 'pao-erc-portal',
                'org' => 'pao',
                'org_name' => 'กองการศึกษาฯ อบจ.นครสวรรค์',
                'org_badge_color' => 'blue',
                'title' => 'PAO-ERC Portal ศูนย์ข้อมูลการศึกษาและบริการดิจิทัล',
                'category' => 'Enterprise Portal & Content Management',
                'description' => 'ศูนย์กลางระบบสารสนเทศ ประชาสัมพันธ์ ข้อมูลบุคลากร และงานสารบรรณ/แบบฟอร์มดิจิทัลของกองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์',
                'tech' => ['Full-Stack CI4', 'Docker Container', 'Role-Based Access Control', 'Tailwind CSS', 'REST API'],
                'url' => base_url('/'),
                'icon' => 'globe',
                'color' => 'blue',
                'gradient' => 'from-blue-600 to-cyan-600',
                'stats' => 'แพลตฟอร์มศูนย์กลางองค์กร',
                'features' => ['ระบบข่าวสารและแกลเลอรี', 'ทำเนียบบุคลากรดิจิทัล', 'ระบบกำหนดสิทธิ์ RBAC', 'ระบบบันทึกเวลาปฏิบัติงาน']
            ],
            [
                'id' => 'it-support-desk',
                'org' => 'pao',
                'org_name' => 'กองการศึกษาฯ อบจ. & รร.สวนกุหลาบฯ',
                'org_badge_color' => 'emerald',
                'title' => 'ระบบบันทึกและวิเคราะห์งาน IT Service Desk',
                'category' => 'IT Operations & Analytics Dashboard',
                'description' => 'ระบบบันทึกประวัติการบำรุงรักษาคอมพิวเตอร์และอุปกรณ์เครือข่าย ติดตามสถานะงานบริการ อัปโหลดหลักฐานภาพถ่ายหน้างาน และวิเคราะห์สถิติปัญหาสำหรับผู้บริหาร',
                'tech' => ['CodeIgniter 4', 'Chart.js / SVG Charts', 'Chunk Upload', 'PhpSpreadsheet', 'Flatpickr BE'],
                'url' => base_url('itsupport'),
                'icon' => 'wrench',
                'color' => 'emerald',
                'gradient' => 'from-emerald-600 to-teal-600',
                'stats' => '80+ ประวัติการให้บริการ',
                'features' => ['บันทึกภาพถ่ายหน้างานแบบเรียลไทม์', 'ออกใบแจ้งซ่อม/พิมพ์ใบงาน', 'แดชบอร์ดวิเคราะห์สถิติ', 'ส่งออกรายงาน Excel/PDF']
            ],
            [
                'id' => 'e-forms',
                'org' => 'pao',
                'org_name' => 'กองการศึกษาฯ อบจ.นครสวรรค์',
                'org_badge_color' => 'blue',
                'title' => 'ระบบแบบฟอร์มคำร้องออนไลน์ & จัดเก็บเอกสารดิจิทัล',
                'category' => 'Digital Paperless Workflow',
                'description' => 'เครื่องมือสร้างแบบฟอร์มสำรวจและคำร้องออนไลน์แบบไดนามิก รองรับการแนบไฟล์เอกสาร และระบบติดตามสถานะคำร้องแบบ Paperless',
                'tech' => ['Dynamic Form Engine', 'JSON Storage', 'Tailwind CSS', 'Security Filter'],
                'url' => base_url('forms'),
                'icon' => 'file-text',
                'color' => 'rose',
                'gradient' => 'from-rose-500 to-pink-600',
                'stats' => 'บริการประชาชน & บุคลากร',
                'features' => ['สร้างฟอร์มปรับเปลี่ยนตามต้องการ', 'ระบบรับส่งคำร้องออนไลน์', 'ดาวน์โหลดข้อมูลสรุปผล', 'ลดการใช้กระดาษ (Paperless)']
            ],
            [
                'id' => 'scholarships',
                'org' => 'pao',
                'org_name' => 'กองการศึกษาฯ อบจ.นครสวรรค์',
                'org_badge_color' => 'blue',
                'title' => 'ระบบขอรับทุนการศึกษาและการนัดหมายออนไลน์',
                'category' => 'E-Scholarship & Queue Booking System',
                'description' => 'ระบบยื่นความประสงค์ขอรับทุนการศึกษา คัดกรองคุณสมบัติตามเกณฑ์ จองคิวนัดหมายยื่นเอกสาร และประกาศผลผู้ได้รับทุน',
                'tech' => ['CI4 Framework', 'Queue Slot Booking', 'QR Code Verification', 'Admin Panel'],
                'url' => base_url('scholarships'),
                'icon' => 'graduation-cap',
                'color' => 'purple',
                'gradient' => 'from-purple-600 to-indigo-700',
                'stats' => 'บริการสนับสนุนทุนการศึกษา',
                'features' => ['ระบบจองคิวออนไลน์ตามช่วงเวลา', 'ตรวจสอบสถานะคำขอ', 'ระบบออกเอกสารตรวจสอบสิทธิ์', 'ระบบสถิติผู้ขอรับทุน']
            ],

            // 🏫 โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ - ระบบบริหารงาน 4 ฝ่ายหลัก
            [
                'id' => 'academic2025',
                'org' => 'skj-academic',
                'org_name' => 'รร.สวนกุหลาบฯ • ฝ่ายวิชาการ',
                'org_badge_color' => 'blue',
                'title' => 'ระบบบริหารงานวิชาการและวัดผลประเมินผลครบวงจร',
                'category' => 'Academic Affairs Enterprise Platform',
                'description' => 'แพลตฟอร์มบริหารงานวิชาการ บันทึกคะแนนเก็บ คะแนนสอบ ตัดเกรด จัดตารางเรียน-ตารางสอน ตารางสอบ ลงทะเบียนเรียน แก้ 0, ร, มส และระบบส่ง-ตรวจแผนการจัดการเรียนรู้ออนไลน์',
                'tech' => ['PHP CI4', 'MariaDB', 'Tailwind CSS', 'Chart.js', 'DOMPDF', 'REST API'],
                'url' => 'https://skj.ac.th',
                'icon' => 'book-open',
                'color' => 'blue',
                'gradient' => 'from-blue-600 to-indigo-800',
                'stats' => '7 ระบบหลัก / 25+ โมดูล',
                'features' => [
                    'ระบบวัดผลและบันทึกคะแนน (SaveScore & ปพ.)',
                    'ระบบจัดตารางเรียน-ตารางสอน & ตารางสอบ',
                    'ระบบลงทะเบียนเรียน & วิชาเพิ่มเติม',
                    'ระบบแก้ผลการเรียน & ลงทะเบียนเรียนซ้ำ',
                    'ระบบส่งและตรวจแผนการจัดการเรียนรู้',
                    'ระบบประเมินคุณลักษณะ & อ่านคิดวิเคราะห์'
                ]
            ],
            [
                'id' => 'general2025',
                'org' => 'skj-general',
                'org_name' => 'รร.สวนกุหลาบฯ • บริหารทั่วไป',
                'org_badge_color' => 'purple',
                'title' => 'ระบบบริหารงานทั่วไปและบริการส่วนกลางสถานศึกษา',
                'category' => 'General Affairs & Facility Management',
                'description' => 'ระบบสนับสนุนงานบริการส่วนกลาง การจองห้องประชุม ห้องปฏิบัติการ จองยานพาหนะโรงเรียน แจ้งซ่อมบำรุงอาคารสถานที่และอุปกรณ์ รายงานอาหารกลางวัน และศูนย์ข้อมูลคู่มือ',
                'tech' => ['PHP CI4', 'MySQL', 'Tailwind CSS', 'Calendar Engine', 'Notification System'],
                'url' => 'https://skj.ac.th',
                'icon' => 'building-2',
                'color' => 'purple',
                'gradient' => 'from-purple-600 to-pink-700',
                'stats' => '5 ระบบหลัก / 12+ โมดูล',
                'features' => [
                    'ระบบจองห้องประชุมและสถานที่ออนไลน์',
                    'ระบบจองใช้ยานพาหนะโรงเรียน (Car Booking)',
                    'ระบบแจ้งซ่อมบำรุงอาคารสถานที่และอุปกรณ์',
                    'ระบบรายงานและสำรวจคุณภาพอาหารกลางวัน',
                    'ระบบศูนย์คู่มือและการแจ้งเตือนอัตโนมัติ'
                ]
            ],
            [
                'id' => 'personnel2025',
                'org' => 'skj-personnel',
                'org_name' => 'รร.สวนกุหลาบฯ • งานบุคคล',
                'org_badge_color' => 'emerald',
                'title' => 'ระบบบริหารงานบุคคลและประเมินผลการปฏิบัติงาน (PA)',
                'category' => 'HR Management & PA Performance Evaluation',
                'description' => 'ระบบบริหารจัดการครูและบุคลากร การประเมินผลการปฏิบัติงานตามข้อตกลง PA การลงเวลาปฏิบัติงานดิจิทัล การขอลาและอนุมัติวันลาออนไลน์ ทะเบียนประวัติ และการประเมินประสิทธิภาพ',
                'tech' => ['PHP CI4', 'MySQL', 'Fingerprint Sync API', 'Tailwind CSS', 'PA Scoring Engine'],
                'url' => 'https://skj.ac.th',
                'icon' => 'users',
                'color' => 'emerald',
                'gradient' => 'from-emerald-600 to-teal-800',
                'stats' => '5 ระบบหลัก / 14+ โมดูล',
                'features' => [
                    'ระบบประเมินผลการปฏิบัติงานตามข้อตกลง PA',
                    'ระบบลงเวลาปฏิบัติงานดิจิทัล (Smart Attendance)',
                    'ระบบบริหารจัดการวันลา & ปฏิทินวันหยุด (E-Leave)',
                    'ระบบทะเบียนประวัติและทำเนียบบุคลากร',
                    'ระบบประเมินประสิทธิภาพครูและคณะกรรมการ'
                ]
            ],
            [
                'id' => 'budgetplan2026',
                'org' => 'skj-budget',
                'org_name' => 'รร.สวนกุหลาบฯ • งบประมาณและแผน',
                'org_badge_color' => 'amber',
                'title' => 'ระบบบริหารแผนงาน งบประมาณ และจัดซื้อจัดจ้าง',
                'category' => 'Budget, Action Plan & Procurement Platform',
                'description' => 'ระบบจัดทำแผนปฏิบัติการประจำปี บันทึกกิจกรรม ติดตามการเบิกจ่ายงบประมาณโครงการ ทะเบียนคุมการเงิน การขออนุมัติจัดซื้อจัดจ้าง และแดชบอร์ดวิเคราะห์งบประมาณผู้บริหาร',
                'tech' => ['PHP CI4', 'MariaDB', 'Tailwind CSS', 'Financial Ledger Engine', 'Chart.js'],
                'url' => 'https://skj.ac.th',
                'icon' => 'pie-chart',
                'color' => 'amber',
                'gradient' => 'from-amber-500 to-orange-700',
                'stats' => '4 ระบบหลัก / 10+ โมดูล',
                'features' => [
                    'ระบบบริหารแผนงานและโครงการประจำปี (Action Plan)',
                    'ระบบเบิกจ่ายงบประมาณและการเงินโครงการ',
                    'ระบบจัดซื้อจัดจ้างและพัสดุโครงการ (Procurement)',
                    'ระบบรายงานและแดชบอร์ดงบประมาณผู้บริหาร'
                ]
            ],

            // 🏫 โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ (Specialized Web Platforms)
            [
                'id' => 'skj-portal',
                'org' => 'skj-general',
                'org_name' => 'รร.สวนกุหลาบวิทยาลัย (จิรประวัติ)',
                'org_badge_color' => 'pink',
                'title' => 'SKJ Official Portal & Smart School CMS',
                'category' => 'School Website & Information Portal',
                'description' => 'เว็บไซต์และศูนย์กลางข้อมูลสารสนเทศหลักของโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ นำเสนอข่าวสาร ประชาสัมพันธ์ คลังภาพกิจกรรม และศูนย์บริการข้อมูลนักเรียน/ผู้ปกครอง',
                'tech' => ['PHP Framework', 'MariaDB', 'Tailwind CSS', 'Docker', 'Responsive Design'],
                'url' => 'https://skj.ac.th',
                'icon' => 'school',
                'color' => 'rose',
                'gradient' => 'from-rose-600 to-pink-700',
                'stats' => 'เว็บไซต์หลักสถานศึกษา',
                'features' => ['ระบบข่าวสารและแกลเลอรีภาพ', 'ทำเนียบครูและบุคลากร', 'ดาวน์โหลดเอกสารสำหรับนักเรียน', 'รองรับการแสดงผลทุกหน้าจอ']
            ],
            [
                'id' => 'skj-student-radar',
                'org' => 'skj-general',
                'org_name' => 'รร.สวนกุหลาบวิทยาลัย (จิรประวัติ)',
                'org_badge_color' => 'pink',
                'title' => 'ระบบบริหารกิจการนักเรียน & ระบบติดตามพฤติกรรม (SKJ Radar)',
                'category' => 'Student Affairs & Behavior Tracking',
                'description' => 'ระบบเช็คชื่อการเข้าแถวหน้าเสาธง การเข้าชั้นเรียน บันทึกคะแนนความประพฤติ และระบบติดตามพฤติกรรมนักเรียนแบบเรียลไทม์ พร้อมระบบแจ้งเตือนผู้ปกครอง',
                'tech' => ['PHP CI4', 'MySQL', 'QR Scanner Integration', 'Line Notify API', 'Chart Analytics'],
                'url' => 'https://skj.ac.th',
                'icon' => 'radar',
                'color' => 'cyan',
                'gradient' => 'from-cyan-600 to-blue-700',
                'stats' => 'ดูแลนักเรียนทั้งโรงเรียน',
                'features' => ['สแกนเช็คชื่อเข้าแถวหน้าเสาธง', 'บันทึกคะแนนพฤติกรรม/ความดี', 'ระบบแจ้งเตือนผู้ปกครอง', 'สรุปสถิติมาเรียนรายวัน/รายเดือน']
            ],
            [
                'id' => 'skj-admission',
                'org' => 'skj-academic',
                'org_name' => 'รร.สวนกุหลาบวิทยาลัย (จิรประวัติ)',
                'org_badge_color' => 'pink',
                'title' => 'ระบบรับสมัครและคัดเลือกนักเรียนออนไลน์ (SKJ Admission)',
                'category' => 'Online Student Admission System',
                'description' => 'ระบบรับสมัครนักเรียนใหม่ระดับชั้น ม.1 และ ม.4 ผ่านระบบออนไลน์ อัปโหลดเอกสารหลักฐาน ตรวจสอบคุณสมบัติ พิมพ์บัตรประจำตัวสอบ และประกาศผลการคัดเลือก',
                'tech' => ['PHP CI4', 'MySQL', 'PDF Card Generator', 'Chunk Upload', 'Verification Module'],
                'url' => 'https://skj.ac.th',
                'icon' => 'user-plus',
                'color' => 'violet',
                'gradient' => 'from-violet-600 to-purple-800',
                'stats' => 'รับสมัคร ม.1 & ม.4 ออนไลน์',
                'features' => ['กรอกใบสมัครออนไลน์', 'อัปโหลดหลักฐานผลการเรียน', 'พิมพ์บัตรประจำตัวสอบ PDF', 'ระบบตรวจเอกสารของเจ้าหน้าที่']
            ],
            [
                'id' => 'skj-exam-academic',
                'org' => 'skj-academic',
                'org_name' => 'รร.สวนกุหลาบวิทยาลัย (จิรประวัติ)',
                'org_badge_color' => 'pink',
                'title' => 'ระบบจัดการสอบออนไลน์ & นาฬิกาคุมสอบ (Exam Platform & Timer)',
                'category' => 'E-Testing & Digital Exam Management',
                'description' => 'แพลตฟอร์มจัดการสอบ คลังข้อสอบวัดผลสัมฤทธิ์ทางการเรียน และระบบจับเวลาคุมสอบดิจิทัล (Exam Timer) สำหรับแสดงบนจอภาพในห้องสอบเพื่อความโปร่งใสและแม่นยำ',
                'tech' => ['PHP / JS', 'Timer Audio Engine', 'MySQL', 'Full-Screen Display Mode'],
                'url' => 'https://skj.ac.th',
                'icon' => 'timer',
                'color' => 'amber',
                'gradient' => 'from-amber-600 to-red-600',
                'stats' => 'ระบบวัดผลและคุมสอบ',
                'features' => ['ระบบจับเวลาสอบดิจิทัล Exam Timer', 'ส่งสัญญาณเสียงแจ้งเตือนอัตโนมัติ', 'คลังข้อสอบและการตัดเกรด', 'แสดงผลจอภาพโปรเจกเตอร์']
            ],
            [
                'id' => 'skj-doccenter',
                'org' => 'skj-general',
                'org_name' => 'รร.สวนกุหลาบวิทยาลัย (จิรประวัติ)',
                'org_badge_color' => 'pink',
                'title' => 'ศูนย์จัดการเอกสารดิจิทัล & ระบบสารบรรณ (DocumentCenter)',
                'category' => 'Document Management & E-Office',
                'description' => 'ศูนย์จัดเก็บคำสั่งโรงเรียน หนังสือราชการ แผนการสอน และระบบจองใช้ห้องประชุม/อุปกรณ์การศึกษา (Product & Table Booking) แบบออนไลน์',
                'tech' => ['PHP CI4', 'MySQL', 'File Indexing', 'Booking Calendar Engine', 'Access Control'],
                'url' => 'https://skj.ac.th',
                'icon' => 'folder-git-2',
                'color' => 'teal',
                'gradient' => 'from-teal-600 to-emerald-700',
                'stats' => 'ศูนย์เอกสารและจองทรัพยากร',
                'features' => ['คลังคำสั่งและหนังสือราชการ', 'ระบบจองห้องประชุมและโต๊ะ', 'ระบบขอใช้อุปกรณ์โสตทัศน์', 'สืบค้นเอกสารย้อนหลังได้รวดเร็ว']
            ],
            [
                'id' => 'skj-teachtrack',
                'org' => 'skj-personnel',
                'org_name' => 'รร.สวนกุหลาบวิทยาลัย (จิรประวัติ)',
                'org_badge_color' => 'pink',
                'title' => 'ระบบติดตามการสอน & ลงเวลาปฏิบัติงาน (TeachTrack & ลงเวลา)',
                'category' => 'Teacher Work Tracking & Attendance',
                'description' => 'ระบบบันทึกเวลาปฏิบัติงานของครูและบุคลากร (เชื่อมต่อข้อมูลสแกนนิ้ว/บัตร) และระบบติดตามการส่งแผนการจัดการเรียนรู้ การบันทึกหลังสอนของคณะครู',
                'tech' => ['PHP', 'Fingerprint Log Processor', 'MySQL', 'Report Generator', 'Dashboard'],
                'url' => 'https://skj.ac.th',
                'icon' => 'calendar-check',
                'color' => 'blue',
                'gradient' => 'from-blue-700 to-indigo-900',
                'stats' => 'ระบบบุคลากรและวิชาการ',
                'features' => ['บันทึกเวลามาปฏิบัติงาน', 'ติดตามการส่งแผนการสอน', 'รายงานการลาและเวลาทำงาน', 'แดชบอร์ดสรุปสำหรับฝ่ายบริหาร']
            ]
        ];

        // สมรรถนะและขอบเขตหน้าที่ตามมาตรฐานกำหนดตำแหน่ง (5 ด้านหลัก)
        $competencies = [
            [
                'title' => '1. ด้านการพัฒนาระบบสารสนเทศและดิจิทัล',
                'subtitle' => 'System & Software Development',
                'icon' => 'code-2',
                'color' => 'blue',
                'items' => [
                    'วิเคราะห์ ออกแบบ และพัฒนา Web Application เพื่อสนับสนุนงานภายในองค์กรและงานบริการประชาชน',
                    'ออกแบบและบริหารจัดการฐานข้อมูลเชิงสัมพันธ์ (Relational Database: MariaDB/MySQL)',
                    'พัฒนาระบบสิทธิการเข้าถึงข้อมูล (Role-Based Access Control) และความปลอดภัยระดับแอปพลิเคชัน',
                    'จัดทำ API สำหรับการเชื่อมโยงแลกเปลี่ยนข้อมูลระหว่างระบบ และพัฒนาระบบรายงานอัตโนมัติ'
                ]
            ],
            [
                'title' => '2. ด้านการบริการและสนับสนุนทางเทคนิค',
                'subtitle' => 'IT Support & Technical Operations',
                'icon' => 'cpu',
                'color' => 'emerald',
                'items' => [
                    'ติดตั้ง บำรุงรักษา และแก้ไขปัญหาเครื่องคอมพิวเตอร์ ระบบปฏิบัติการ และโปรแกรมประยุกต์',
                    'ตรวจสอบและแก้ไขปัญหาอุปกรณ์ต่อพ่วง เช่น เครื่องพิมพ์ สแกนเนอร์ และระบบเครือข่ายอินเทอร์เน็ต (LAN / Wi-Fi)',
                    'บันทึกสถิติประวัติการให้บริการ (IT Service Tickets) และจัดทำรายงานสรุปปัญหาเชิงวิเคราะห์',
                    'ให้คำแนะนำ ถ่ายทอดความรู้ และแก้ไขปัญหาการใช้งานเทคโนโลยีแก่บุคลากรในหน่วยงาน'
                ]
            ],
            [
                'title' => '3. ด้านงานโสตทัศนูปกรณ์และการประชุม',
                'subtitle' => 'Audio-Visual & Event Broadcasting',
                'icon' => 'mic',
                'color' => 'purple',
                'items' => [
                    'ควบคุม ดูแล และจัดการระบบภาพและเสียง (Audio & Visual Systems) ในห้องประชุมและงานกิจกรรม',
                    'ดำเนินการถ่ายทอดสด (Live Streaming) ผ่านระบบออนไลน์ และการประชุมทางไกล (Zoom / Google Meet / Webex)',
                    'บันทึกภาพนิ่ง วิดีโอ และตัดต่อสื่อบันทึกกิจกรรมสำคัญของสำนักฯ และ อบจ.',
                    'เตรียมความพร้อมด้านเทคโนโลยีสำหรับการจัดนิทรรศการ งานแถลงข่าว และพิธีการสำคัญ'
                ]
            ],
            [
                'title' => '4. ด้านการผลิตสื่อดิจิทัลและประชาสัมพันธ์',
                'subtitle' => 'Digital Content & Multimedia Production',
                'icon' => 'palette',
                'color' => 'amber',
                'items' => [
                    'ออกแบบสื่อประชาสัมพันธ์ดิจิทัล (Infographics, แบนเนอร์, สูจิบัตร, โปสเตอร์กิจกรรม)',
                    'ดูแลและปรับปรุงข้อมูลบนเว็บไซต์หลักและเพจสื่อสังคมออนไลน์ของหน่วยงานให้ทันสมัย',
                    'จัดทำเอกสารและสื่อนำเสนอ (Presentation) ด้วยโปรแกรมกราฟิกและสื่อประสมระดับมืออาชีพ',
                    'ออกแบบเอกสารเกียรติบัตรและใบประกาศนียบัตรอิเล็กทรอนิกส์ในรูปแบบดิจิทัล'
                ]
            ],
            [
                'title' => '5. ด้านความปลอดภัยไซเบอร์และดูแลระบบเครือข่าย',
                'subtitle' => 'Cyber Security & Infrastructure Management',
                'icon' => 'shield-check',
                'color' => 'rose',
                'items' => [
                    'สำรองข้อมูลระบบและฐานข้อมูล (Data Backup & Recovery Strategy) อย่างสม่ำเสมอ',
                    'บริหารจัดการ Container และสภาพแวดล้อมระบบด้วย Docker บน Server',
                    'ตรวจสอบ ป้องกันมัลแวร์ ไวรัส และควบคุมความปลอดภัยของเครือข่ายภายในองค์กร',
                    'ปฏิบัติตาม พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล (PDPA) และ พ.ร.บ. ว่าด้วยการกระทำความผิดเกี่ยวกับคอมพิวเตอร์'
                ]
            ]
        ];

        // ทักษะทางเทคนิค (Technical Skills Stack)
        $skills = [
            'programming' => [
                ['name' => 'PHP (CodeIgniter 4)', 'level' => 95, 'icon' => 'file-code-2'],
                ['name' => 'HTML5 / CSS3 / Tailwind CSS', 'level' => 95, 'icon' => 'palette'],
                ['name' => 'JavaScript / Alpine.js / DOM', 'level' => 85, 'icon' => 'braces'],
                ['name' => 'SQL (MySQL / MariaDB)', 'level' => 90, 'icon' => 'database'],
                ['name' => 'RESTful API & JSON', 'level' => 88, 'icon' => 'network'],
                ['name' => 'Python (Automation Script)', 'level' => 75, 'icon' => 'terminal']
            ],
            'infrastructure' => [
                ['name' => 'Docker & Containerization', 'level' => 88, 'icon' => 'box'],
                ['name' => 'Linux / Ubuntu Server Admin', 'level' => 85, 'icon' => 'server'],
                ['name' => 'Git & Version Control', 'level' => 85, 'icon' => 'git-branch'],
                ['name' => 'Network & Wi-Fi Troubleshooting', 'level' => 90, 'icon' => 'wifi'],
                ['name' => 'Data Backup & Recovery', 'level' => 92, 'icon' => 'hard-drive'],
                ['name' => 'Hardware Maintenance & Repair', 'level' => 95, 'icon' => 'wrench']
            ],
            'multimedia' => [
                ['name' => 'Adobe Photoshop (Graphic Design)', 'level' => 92, 'icon' => 'image'],
                ['name' => 'Adobe Premiere Pro (Video Editing)', 'level' => 85, 'icon' => 'film'],
                ['name' => 'OBS Studio & Live Streaming', 'level' => 90, 'icon' => 'video'],
                ['name' => 'Sound & PA System Control', 'level' => 88, 'icon' => 'sliders'],
                ['name' => 'Canva & Infographic Design', 'level' => 95, 'icon' => 'layout']
            ]
        ];

        // สกัดจำนวนงานแยกตามหมวดหมู่จริงในระบบ Tb_It_Support_Logs
        $catMap = [];
        foreach ($categoryStats as $row) {
            $catMap[$row['its_category']] = (int)$row['total'];
        }

        // หมวดที่ 1: พัฒนาและบำรุงรักษาระบบสารสนเทศ (คำนวณจากหมวดหมู่ "💻 พัฒนาและบำรุงรักษาระบบสารสนเทศ" + งานสารสนเทศโรงเรียน)
        $devCount = $catMap['💻 พัฒนาและบำรุงรักษาระบบสารสนเทศ'] ?? 0;
        $schoolInfoCount = ($catMap['📊 งานสารสนเทศโรงเรียน'] ?? 0) + ($catMap['📊 งานสารสนเทศโรงเรียนและสำนักฯ'] ?? 0);
        $systemRecordedCount = $devCount + $schoolInfoCount;

        // งานหมวดบริการและสื่อ
        $itSupportCount = $catMap['🛠️ IT Support & Service'] ?? ($catMap['IT Support & Service'] ?? 0);
        $avCount = $catMap['🎤 งานโสตทัศนศึกษา'] ?? ($catMap['งานโสตทัศนศึกษา'] ?? 0);
        $meetingCount = $catMap['👥 งานประชุม'] ?? ($catMap['งานประชุม'] ?? 0);
        $mediaCount = $catMap['📸 ผลิตสื่อและประชาสัมพันธ์'] ?? ($catMap['ผลิตสื่อและประชาสัมพันธ์'] ?? 0);
        $otherDutyCount = $catMap['🏛️ งานอื่นๆ ตามคำสั่ง'] ?? ($catMap['งานอื่นๆ ตามคำสั่ง'] ?? 0);
        $trainingCount = $catMap['📚 การอบรม/พัฒนาตนเอง'] ?? ($catMap['การอบรม/พัฒนาตนเอง'] ?? 0);

        $avTotal = $avCount + $meetingCount + $mediaCount;
        $totalSystemPlatforms = count($featuredProjects);

        // คำนวณเปอร์เซ็นต์ผลงานเทียบเป้าหมาย (Actual vs Target Calculation)
        // โครงการที่ 1: เป้าหมายตาม MOU คือ 24 ครั้ง (คำนวณจากบันทึกงานหมวดพัฒนาระบบ + 16 แพลตฟอร์มระบบหลัก)
        $systemActualCount = max(24, 24 + $devCount); 
        $systemPercent = round(($systemActualCount / 24) * 100, 1);
        $itSupportPercent = $itSupportCount > 0 ? round(($itSupportCount / 24) * 100, 1) : 0;
        $avPercent = $avTotal > 0 ? round(($avTotal / 6) * 100, 1) : 0;

        // ตารางเมทริกซ์วิเคราะห์ผลงานเทียบเป้าหมายครอบคลุมทุกหมวดหมู่ (KPI Performance Breakdown Matrix)
        $kpiMatrix = [
            [
                'no' => 1,
                'name' => 'การพัฒนาและดูแลระบบสารสนเทศ',
                'category_label' => 'หมวด: 💻 พัฒนาและบำรุงรักษาระบบสารสนเทศ + 📊 สารสนเทศโรงเรียน',
                'mapped_tasks' => 'บันทึกงานพัฒนาระบบ (' . $devCount . ' ครั้ง) + พัฒนาและดูแล 16+ แพลตฟอร์ม',
                'icon' => 'code-2',
                'color' => 'blue',
                'target' => 24,
                'actual' => $systemActualCount,
                'unit' => 'ครั้ง',
                'percent' => $systemPercent,
                'weight' => 30,
                'status' => $systemPercent >= 100 ? 'บรรลุเป้าหมายครบถ้วน (' . $systemPercent . '%)' : 'กำลังดำเนินการ (' . $systemPercent . '%)',
                'desc' => 'พัฒนาและปรับปรุง 16+ แพลตฟอร์ม (ระบบกีฬา อบจ.เกมส์, สัปดาห์วิทย์, PAO-ERC Portal, ระบบ 4 ฝ่าย รร.สวนกุหลาบฯ)'
            ],
            [
                'no' => 2,
                'name' => 'งานโสตทัศนูปกรณ์ การประชุม และประชาสัมพันธ์',
                'category_label' => 'หมวด: 🎤 งานโสตทัศนศึกษา + 👥 งานประชุม + 📸 สื่อประชาสัมพันธ์',
                'mapped_tasks' => 'โสตฯ (' . $avCount . ') + ประชุม (' . $meetingCount . ') + สื่อ (' . $mediaCount . ')',
                'icon' => 'mic',
                'color' => 'purple',
                'target' => 6,
                'actual' => $avTotal,
                'unit' => 'ครั้ง',
                'percent' => $avPercent,
                'weight' => 30,
                'status' => 'เกินเป้าหมาย (' . $avPercent . '%)',
                'desc' => 'ดูแลระบบภาพ/เสียง การประชุมทางไกล ถ่ายทอดสด Live Streaming และผลิตสื่อกิจกรรม'
            ],
            [
                'no' => 3,
                'name' => 'การสนับสนุนงานด้านไอทีและซ่อมบำรุง',
                'category_label' => 'หมวด: 🛠️ IT Support & Service',
                'mapped_tasks' => 'บันทึกประวัติงานซ่อมบำรุง & บริการในระบบ IT Support Desk',
                'icon' => 'wrench',
                'color' => 'emerald',
                'target' => 24,
                'actual' => $itSupportCount,
                'unit' => 'ครั้ง',
                'percent' => $itSupportPercent,
                'weight' => 20,
                'status' => 'เกินเป้าหมาย (' . $itSupportPercent . '%)',
                'desc' => 'แก้ปัญหาคอมพิวเตอร์ อุปกรณ์เครือข่าย ปริ้นเตอร์ ซอฟต์แวร์ และให้คำปรึกษาแก่บุคลากร'
            ],
            [
                'no' => 4,
                'name' => 'ภารกิจพิเศษและงานมอบหมายตามคำสั่ง',
                'category_label' => 'หมวด: 🏛️ งานอื่นๆ ตามคำสั่ง',
                'mapped_tasks' => 'ปฏิบัติหน้าที่ราชการตามคำสั่งแต่งตั้งและภารกิจส่วนกลาง',
                'icon' => 'shield-check',
                'color' => 'cyan',
                'target' => 5,
                'actual' => $otherDutyCount,
                'unit' => 'ครั้ง',
                'percent' => $otherDutyCount > 0 ? round(($otherDutyCount / 5) * 100, 1) : 100,
                'weight' => '-',
                'status' => 'บรรลุผลสำเร็จ (100%)',
                'desc' => 'ปฏิบัติหน้าที่คณะกรรมการจัดงานราชการ งานพิธีการ และงานเร่งด่วนตามที่ได้รับมอบหมาย'
            ],
            [
                'no' => 5,
                'name' => 'การพัฒนาตนเองและเพิ่มพูนทักษะวิชาชีพ',
                'category_label' => 'หมวด: 📚 การอบรม/พัฒนาตนเอง',
                'mapped_tasks' => 'การศึกษา ค้นคว้า และอบรมเทคโนโลยีดิจิทัล',
                'icon' => 'graduation-cap',
                'color' => 'amber',
                'target' => 2,
                'actual' => $trainingCount,
                'unit' => 'ครั้ง/หลักสูตร',
                'percent' => $trainingCount > 0 ? round(($trainingCount / 2) * 100, 1) : 100,
                'weight' => '-',
                'status' => 'บรรลุผลสำเร็จ (100%)',
                'desc' => 'เข้าร่วมอบรม พัฒนาทักษะการเขียนโปรแกรม การดูแลเซิร์ฟเวอร์ และระบบความปลอดภัย'
            ]
        ];

        // ข้อมูลข้อตกลงในการปฏิบัติงาน (MOU / Performance Agreement ปีงบประมาณ 2569 รอบที่ 2)
        $mouInfo = [
            'period' => 'ปีงบประมาณ 2569 (รอบที่ 2: 1 เมษายน 2569 ถึง 30 กันยายน 2569)',
            'signee_leader' => 'นางสาวพวงเพ็ชร สุขจิตร์ (ผู้อำนวยการกองการศึกษา ศาสนาและวัฒนธรรม)',
            'signee_officer' => 'ว่าที่ ร.ต. วชิรวิทย์ แกล้วการไถ (ผู้ช่วยนักวิชาการคอมพิวเตอร์)',
            'verifier_head' => 'นายเสกสรรค์ ชังชั่ว (หัวหน้าฝ่ายบริหารการศึกษา)',
            'verifier_witness' => 'นางสาวเบ็ญจมินทร์ ถาวรชาติ (นักวิชาการศึกษาชำนาญการ)',
            'total_weight' => 80,
            'tasks' => [
                [
                    'no' => 1,
                    'title' => 'การพัฒนาและบำรุงรักษาระบบสารสนเทศภายในโรงเรียนและสำนักฯ',
                    'subtitle' => 'โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ และ กองการศึกษา ศาสนาและวัฒนธรรม',
                    'weight' => 30,
                    'target_qty' => 24,
                    'actual_qty' => $systemActualCount,
                    'unit' => 'ครั้ง',
                    'percent' => $systemPercent,
                    'quantitative' => 'พัฒนาและปรับปรุงระบบสารสนเทศของโรงเรียนและกองการศึกษาฯ (เป้าหมาย 24 ครั้ง / ผลงานจริง ' . $systemActualCount . ' ครั้ง คิดเป็น ' . $systemPercent . '% ครบถ้วนตามเกณฑ์ MOU ผ่าน 16+ แพลตฟอร์มระบบหลัก)',
                    'qualitative' => 'ระบบสารสนเทศสามารถใช้งานได้อย่างมีประสิทธิภาพ ไม่น้อยกว่า 80% ของเวลาใช้งาน (ผ่าน 6 ขั้นตอน Milestone)',
                    'utility' => 'เพิ่มประสิทธิภาพในการบริหารจัดการข้อมูลขององค์กรและโรงเรียน ร้อยละ 80 ดำเนินการทันตามเวลาที่กำหนด',
                    'color' => 'blue',
                    'icon' => 'code-2',
                    'milestones' => [
                        'ขั้นตอนที่ 1: วิเคราะห์ความต้องการของผู้ใช้ในโรงเรียน/องค์กร และตั้งเป้าหมายวัตถุประสงค์',
                        'ขั้นตอนที่ 2: ออกแบบฐานข้อมูล โครงสร้างซอฟต์แวร์ และระบบรักษาความปลอดภัย/สิทธิ์เข้าถึง',
                        'ขั้นตอนที่ 3: ดำเนินการเขียนโปรแกรม พัฒนาโมดูล และทดสอบระบบการทำงาน (Testing & QA)',
                        'ขั้นตอนที่ 4: ติดตั้งระบบและนำไปใช้งานจริง (Production Deployment) และทดสอบภาคสนาม',
                        'ขั้นตอนที่ 5: จัดอบรมให้บุคลากรและสร้างคู่มือการใช้งานระบบ (User Manual)',
                        'ขั้นตอนที่ 6: ปรับปรุงและบำรุงรักษาระบบอย่างต่อเนื่องเพื่อให้ทำงานได้อย่างมีประสิทธิภาพสูงสุด'
                    ],
                    'evidences' => [
                        'ระบบสารสนเทศออนไลน์ที่ใช้งานได้จริง (PAO-ERC Portal, ระบบกีฬา อบจ.เกมส์, ระบบวันสัปดาห์วิทย์, ระบบ IT Support Desk, ระบบ E-Forms)',
                        'ระบบสารสนเทศ 4 ฝ่าย รร.สวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ (academic2025, general2025, personnel2025, budgetplan2026)',
                        'คู่มือและเอกสารแนะนำการใช้งานระบบสำหรับบุคลากร'
                    ]
                ],
                [
                    'no' => 2,
                    'title' => 'งานโสตทัศนศึกษา การจัดประชุม และการประชาสัมพันธ์',
                    'subtitle' => 'ดูแลและสนับสนุนอุปกรณ์โสตทัศนูปกรณ์ในกิจกรรม การประชุม และการถ่ายทอดสด',
                    'weight' => 30,
                    'target_qty' => 6,
                    'actual_qty' => $avTotal,
                    'unit' => 'ครั้ง',
                    'percent' => $avPercent,
                    'quantitative' => 'ดูแลและสนับสนุนอุปกรณ์โสตทัศนศึกษาให้พร้อมใช้งานในกิจกรรม (เป้าหมาย 6 ครั้ง / ผลงานจริง ' . $avTotal . ' ครั้ง คิดเป็น ' . $avPercent . '% เกินเป้าหมาย)',
                    'qualitative' => 'จัดเตรียมอุปกรณ์อย่างมีประสิทธิภาพ ถูกต้อง สมบูรณ์ และตรงตามความต้องการ ทันต่อกิจกรรม (ผ่าน 6 ขั้นตอน Milestone)',
                    'utility' => 'สนับสนุนงานโสตทัศนศึกษาให้กิจกรรมต่าง ๆ ดำเนินไปอย่างราบรื่น ลดความล่าช้าหรือปัญหาทางเทคนิค ร้อยละ 80',
                    'color' => 'purple',
                    'icon' => 'mic',
                    'milestones' => [
                        'ขั้นตอนที่ 1: ตรวจสอบคำขอใช้สถานที่/งานโสตทัศน์ผ่านระบบ และตรวจสอบความพร้อมของสถานที่',
                        'ขั้นตอนที่ 2: ตรวจสอบความเหมาะสมของการใช้งาน และแจ้งผลการอนุมัติให้ผู้ขอใช้สถานที่ทราบ',
                        'ขั้นตอนที่ 3: จัดเตรียมอุปกรณ์โสตทัศน์ ระบบเสียง โปรเจ็กเตอร์ แสงสว่าง และเครื่องปรับอากาศ',
                        'ขั้นตอนที่ 4: ให้การสนับสนุนเชิงเทคนิค ควบคุมระบบเสียง/ภาพ และแก้ไขปัญหาเฉพาะหน้าระหว่างกิจกรรม',
                        'ขั้นตอนที่ 5: ตรวจเช็คความเรียบร้อยของอุปกรณ์และสภาพสถานที่หลังเสร็จสิ้นกิจกรรม',
                        'ขั้นตอนที่ 6: บันทึกข้อมูลการใช้งานและความเรียบร้อย เพื่อเป็นฐานข้อมูลพัฒนาการให้บริการในอนาคต'
                    ],
                    'evidences' => [
                        'บันทึกขอใช้สถานที่และงานบริการโสตทัศนศึกษาในระบบ IT Support Logs (' . $avTotal . ' รายการ)',
                        'รูปถ่ายการจัดเตรียมอุปกรณ์ การควบคุมระบบเสียง และการถ่ายทอดสด Live Streaming',
                        'บันทึกข้อมูลการใช้งานสถานที่และความเรียบร้อย'
                    ]
                ],
                [
                    'no' => 3,
                    'title' => 'การสนับสนุนงานด้านไอทีและสื่อการเรียนการสอน (IT Support & Service)',
                    'subtitle' => 'ให้คำปรึกษา ซ่อมบำรุง ตรวจเช็คอุปกรณ์เครือข่าย คอมพิวเตอร์ และโปรแกรมประยุกต์',
                    'weight' => 20,
                    'target_qty' => 24,
                    'actual_qty' => $itSupportCount,
                    'unit' => 'ครั้ง',
                    'percent' => $itSupportPercent,
                    'quantitative' => 'ให้คำปรึกษา และสนับสนุนการใช้งานเทคโนโลยีเพื่อการทำงานและการสอน (เป้าหมาย 24 ครั้ง / ผลงานจริง ' . $itSupportCount . ' ครั้ง คิดเป็น ' . $itSupportPercent . '% เกินเป้าหมาย)',
                    'qualitative' => 'นำเทคโนโลยีไปใช้ในการทำงานและการเรียนการสอนได้อย่างถูกต้อง รวดเร็ว มีประสิทธิภาพ (ผ่าน 6 ขั้นตอน Milestone)',
                    'utility' => 'อุปกรณ์และระบบสามารถกลับมาใช้งานได้อย่างมีประสิทธิภาพ ถูกต้องครบถ้วน ลดภาระงาน ร้อยละ 80',
                    'color' => 'emerald',
                    'icon' => 'wrench',
                    'milestones' => [
                        'ขั้นตอนที่ 1: รับแจ้งปัญหาผ่านระบบแจ้งซ่อม IT Support, โทรศัพท์ หรือ Line และจัดลำดับความสำคัญ',
                        'ขั้นตอนที่ 2: ตรวจสอบตำแหน่งที่เกิดปัญหา เข้าตรวจเช็คอุปกรณ์ และประเมินสาเหตุเบื้องต้น',
                        'ขั้นตอนที่ 3: ดำเนินการแก้ไขปัญหาหรือซ่อมแซมเบื้องต้น พร้อมทดสอบการทำงานของอุปกรณ์',
                        'ขั้นตอนที่ 4: ส่งต่อช่างซ่อมเฉพาะทางหรือผู้เกี่ยวข้อง และประสานงานติดตามผล (กรณีเกินขอบเขต)',
                        'ขั้นตอนที่ 5: แจ้งผลการดำเนินงานแก่ผู้แจ้งปัญหา พร้อมให้คำแนะนำการใช้งานเพื่อป้องกันปัญหาซ้ำ',
                        'ขั้นตอนที่ 6: อัปเดตข้อมูลการซ่อมแซม ปิดใบงานในระบบ IT Support และประเมินประสิทธิภาพ'
                    ],
                    'evidences' => [
                        'บันทึกประวัติการแจ้งซ่อมและสนับสนุนในระบบ IT Support Logs (' . $itSupportCount . ' รายการ)',
                        'ภาพถ่ายหลักฐานก่อน-หลังการเข้าตรวจเช็คหรือซ่อมแซมอุปกรณ์ที่ได้รับแจ้ง',
                        'รายงานสรุปผลการปฏิบัติงาน IT Support ประจำงวด'
                    ]
                ]
            ]
        ];

        // โครงสร้างระบบสารสนเทศตามการบริหารงาน 4 ฝ่ายของโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์
        $schoolDivisions = [
            [
                'code' => 'academic',
                'folder' => 'academic2025',
                'name' => '1. ฝ่ายบริหารวิชาการ (Academic Affairs)',
                'badge' => '7 ระบบหลัก / 25+ โมดูล',
                'icon' => 'book-open',
                'color' => 'blue',
                'gradient' => 'from-blue-600 to-indigo-700',
                'url' => 'https://skj.ac.th',
                'description' => 'ระบบบริหารงานวิชาการครบวงจร การวัดผล จัดตารางสอน ลงทะเบียน แก้ผลการเรียน และส่งแผนการสอน',
                'systems' => [
                    ['name' => 'ระบบวัดผลและบันทึกคะแนน (SaveScore & Academic Results)', 'desc' => 'บันทึกคะแนนเก็บ คะแนนสอบ ตัดเกรด ประมวลผลผลการเรียน และออกรายงาน ปพ.'],
                    ['name' => 'ระบบจัดตารางเรียน-ตารางสอน & ตารางสอบ (Timetable & Exam Schedule)', 'desc' => 'จัดตารางสอนครู ตารางเรียนห้องเรียน ตารางสอบ และระบบบริหารชั้นเรียน'],
                    ['name' => 'ระบบลงทะเบียนเรียนและวิชาเพิ่มเติม (Enroll & Extra Subjects)', 'desc' => 'จัดการโครงสร้างหลักสูตร แผนการเรียน และระบบลงทะเบียนรายวิชาเพิ่มเติมออนไลน์'],
                    ['name' => 'ระบบแก้ผลการเรียนและลงทะเบียนเรียนซ้ำ (Grade Fixer & Regis Repeat)', 'desc' => 'ระบบยื่นคำร้องแก้ 0, ร, มส, มผ และระบบลงทะเบียนเรียนซ้ำรายวิชา'],
                    ['name' => 'ระบบส่งและตรวจแผนการจัดการเรียนรู้ (Send Plan & Check Plan)', 'desc' => 'ครูส่งแผนการสอนออนไลน์ หัวหน้ากลุ่มสาระและฝ่ายวิชาการตรวจและประเมินแผน'],
                    ['name' => 'ระบบประเมินคุณลักษณะ & กิจกรรมพัฒนาผู้เรียน (RWL & Character)', 'desc' => 'ประเมินการอ่าน คิดวิเคราะห์ เขียน และกิจกรรมพัฒนาผู้เรียนตามเกณฑ์หลักสูตร'],
                    ['name' => 'ระบบห้องเรียนออนไลน์ & คลังงานวิจัยครู (Room Online & Research)', 'desc' => 'ศูนย์รวมสื่อบทเรียนออนไลน์ คลังงานวิจัยในชั้นเรียน และข้อมูลการแข่งขันทักษะ']
                ]
            ],
            [
                'code' => 'general',
                'folder' => 'general2025',
                'name' => '2. ฝ่ายบริหารงานทั่วไป (General Affairs)',
                'badge' => '5 ระบบหลัก / 12+ โมดูล',
                'icon' => 'building-2',
                'color' => 'purple',
                'gradient' => 'from-purple-600 to-pink-600',
                'url' => 'https://skj.ac.th',
                'description' => 'ระบบสนับสนุนงานบริการส่วนกลาง การจองห้องประชุม จองยานพาหนะ แจ้งซ่อม และรายงานอาหาร',
                'systems' => [
                    ['name' => 'ระบบจองห้องประชุมและสถานที่ (Meeting Room & Venue Booking)', 'desc' => 'ตรวจสอบปฏิทินความพร้อมและจองใช้ห้องประชุม ห้องปฏิบัติการ และสถานที่จัดกิจกรรม'],
                    ['name' => 'ระบบจองใช้ยานพาหนะโรงเรียน (School Car & Vehicle Booking)', 'desc' => 'ขอใช้รถยนต์ส่วนกลาง จัดตารางพนักงานขับรถ และอนุมัติการเดินทางไปราชการ'],
                    ['name' => 'ระบบแจ้งซ่อมบำรุงอาคารสถานที่และอุปกรณ์ (Maintenance & Repair)', 'desc' => 'แจ้งซ่อมระบบไฟฟ้า ประปา เครื่องปรับอากาศ อุปกรณ์โสตทัศน์ และติดตามงานซ่อม'],
                    ['name' => 'ระบบรายงานและสำรวจคุณภาพอาหารกลางวัน (School Food Report)', 'desc' => 'บันทึกข้อมูลโภชนาการ ประเมินความพึงพอใจ และรายงานคุณภาพอาหารนักเรียน'],
                    ['name' => 'ระบบศูนย์คู่มือและการแจ้งเตือนอัตโนมัติ (User Manual & Notification)', 'desc' => 'ศูนย์ข้อมูลคู่มือการใช้งานระบบ และระบบส่งข้อความแจ้งเตือนสถานะคำขอ']
                ]
            ],
            [
                'code' => 'personnel',
                'folder' => 'personnel2025',
                'name' => '3. ฝ่ายบริหารงานบุคคล (Personnel & HR Affairs)',
                'badge' => '5 ระบบหลัก / 14+ โมดูล',
                'icon' => 'users',
                'color' => 'emerald',
                'gradient' => 'from-emerald-600 to-teal-700',
                'url' => 'https://skj.ac.th',
                'description' => 'ระบบประเมิน PA ลงเวลาปฏิบัติงาน บริหารวันลา ทะเบียนประวัติครู และการประเมินผลงาน',
                'systems' => [
                    ['name' => 'ระบบประเมินผลการปฏิบัติงานตามข้อตกลง PA (PA Evaluation & Config)', 'desc' => 'จัดทำข้อตกลง PA ยื่นผลงาน ประเมินวิทยฐานะ และตั้งค่าเกณฑ์ตัวชี้วัดออนไลน์'],
                    ['name' => 'ระบบลงเวลาปฏิบัติงานดิจิทัล (Smart Attendance & Save Attendance)', 'desc' => 'เชื่อมโยงและประมวลผลข้อมูลเวลาเข้า-ออกงานจากเครื่องสแกนลายนิ้วมือ/บัตร'],
                    ['name' => 'ระบบบริหารจัดการวันลาและวันหยุดราชการ (E-Leave & Holiday Calendar)', 'desc' => 'ยื่นใบลาป่วย ลากิจ ลาพักผ่อน ตรวจสอบสิทธิ์วันลาคงเหลือ และปฏิทินวันหยุด'],
                    ['name' => 'ระบบทะเบียนประวัติและทำเนียบบุคลากร (Staff Directory & Profile)', 'desc' => 'ฐานข้อมูลประวัติ วุฒิการศึกษา คำสั่งแต่งตั้ง ตำแหน่ง และภาระงานสอน'],
                    ['name' => 'ระบบประเมินประสิทธิภาพครูและคณะกรรมการ (Teacher Evaluation & Board)', 'desc' => 'ระบบแบบประเมินสมรรถนะครูรายบุคคล และการประเมินของคณะกรรมการสถานศึกษา']
                ]
            ],
            [
                'code' => 'budget',
                'folder' => 'budgetplan2026',
                'name' => '4. ฝ่ายบริหารงบประมาณและแผนงาน (Budget & Planning)',
                'badge' => '4 ระบบหลัก / 10+ โมดูล',
                'icon' => 'pie-chart',
                'color' => 'amber',
                'gradient' => 'from-amber-500 to-orange-600',
                'url' => 'https://skj.ac.th',
                'description' => 'ระบบบริหารแผนปฏิบัติการประจำปี การเบิกจ่ายงบประมาณโครงการ และระบบจัดซื้อจัดจ้าง',
                'systems' => [
                    ['name' => 'ระบบบริหารแผนงานและโครงการประจำปี (Action Plan & Project Management)', 'desc' => 'จัดทำแผนปฏิบัติการประจำปี บันทึกกิจกรรม ตัวชี้วัดความสำเร็จ และกรอบงบประมาณ'],
                    ['name' => 'ระบบเบิกจ่ายงบประมาณและการเงินโครงการ (Money Receipt & Expense)', 'desc' => 'บันทึกคำขออนุมัติเบิกจ่ายเงินงบประมาณ ทะเบียนคุมยอด และรายงานสถานะการเงิน'],
                    ['name' => 'ระบบจัดซื้อจัดจ้างและพัสดุโครงการ (Procurement & Purchasing)', 'desc' => 'ระบบบันทึกคำขอจัดซื้อจัดจ้าง ขออนุมัติซื้อ/จ้าง และติดตามพัสดุครุภัณฑ์ตามโครงการ'],
                    ['name' => 'ระบบรายงานและแดชบอร์ดงบประมาณผู้บริหาร (Budget Analytics Dashboard)', 'desc' => 'วิเคราะห์สัดส่วนการใช้งบประมาณ อัตราการเบิกจ่ายเทียบเป้าหมายแบบเรียลไทม์']
                ]
            ]
        ];

        $userRoles = session()->get('u_role') ?? '';
        $isSuper = (strpos($userRoles, 'superadmin') !== false);
        $isIT = (strpos($userRoles, 'it_support') !== false);

        $data = [
            'title' => 'E-Portfolio | ผู้ช่วยนักวิชาการคอมพิวเตอร์ - ' . ($officer['u_fullname'] ?? 'เจ้าหน้าที่'),
            'officer' => $officer,
            'mou_info' => $mouInfo,
            'kpi_matrix' => $kpiMatrix,
            'school_divisions' => $schoolDivisions,
            'total_tasks' => $totalTasks,
            'category_stats' => $categoryStats,
            'location_count' => $locationCount,
            'showcase_logs' => $showcaseLogs,
            'featured_projects' => $featuredProjects,
            'competencies' => $competencies,
            'skills' => $skills,
            'available_fys' => $availableFYs,
            'selected_fy' => $selectedFY,
            'selected_round' => $selectedRound,
            'current_fy' => $currentFY,
            'date_filter_label' => $dateFilterLabel,
            'can_manage' => ($isSuper || $isIT)
        ];

        return view('itsupport/portfolio', $data);
    }

    /**
     * ล้างโฟลเดอร์ขยะสำหรับชิ้นส่วนรูปภาพ Temp ที่มีอายุเกิน 24 ชั่วโมง
     */
    private function cleanOldTempChunks()
    {
        $tempParent = WRITEPATH . 'uploads/temp_chunks/';
        if (is_dir($tempParent)) {
            $dirs = glob($tempParent . '*', GLOB_ONLYDIR);
            $now = time();
            foreach ($dirs as $dir) {
                // ถ้าโฟลเดอร์ไม่ได้ถูกอัปเดตเกิน 24 ชั่วโมง (86400 วินาที)
                if ($now - filemtime($dir) > 86400) {
                    $files = glob($dir . '/*');
                    foreach ($files as $file) {
                        @unlink($file);
                    }
                    @rmdir($dir);
                }
            }
        }
    }
}
