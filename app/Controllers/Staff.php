<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use CodeIgniter\Controller;

class Staff extends Controller
{
    public function index()
    {
        $userId = session()->get('u_id');
        $atdModel = new \App\Models\AttendanceModel();
        
        $data['title'] = "Dashboard บุคลากร | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        
        // Stats for current user
        $data['total_attendance'] = $atdModel->where('atd_user_id', $userId)->countAllResults();
        $data['today_checkin'] = $atdModel->where('atd_user_id', $userId)
                                          ->where('atd_type', 'check_in')
                                          ->where('DATE(atd_timestamp)', date('Y-m-d'))
                                          ->first();
                                          
        return view('staff/dashboard', $data);
    }

    public function news()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'news') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบจัดการข่าวสาร');
        }
        
        $newsModel = new \App\Models\NewsModel();
        
        $data['title'] = "จัดการข่าวสาร | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        $data['news'] = $newsModel->select('Tb_News.*, Tb_Users.u_fullname as author_name')
                                 ->join('Tb_Users', 'Tb_Users.u_id = Tb_News.news_created_by', 'left')
                                 ->orderBy('news_created_at', 'DESC')
                                 ->findAll();
                                 
        return view('staff/news/index', $data);
    }

    public function newsCreate()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'news') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบจัดการข่าวสาร');
        }
        $data['title'] = "เพิ่มข่าวสารใหม่ | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        return view('staff/news/create', $data);
    }

    public function newsStore()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'news') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบจัดการข่าวสาร');
        }
        $newsModel = new \App\Models\NewsModel();
        $galleryModel = new \App\Models\NewsGalleryModel();
        $userId = session()->get('u_id');
        
        $rules = [
            'title' => 'required|min_length[5]',
            'content' => 'required',
            'category' => 'required'
        ];

        // Add optional validation for cover
        $coverFile = $this->request->getFile('cover');
        if ($coverFile && $coverFile->isValid()) {
            $rules['cover'] = 'is_image[cover]|max_size[cover,2048]';
        }

        if ($this->request->isAJAX()) {
            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors()
                ]);
            }
        } else {
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        $title = $this->request->getPost('title');
        $slug = $newsModel->generateSlug($title);
        
        // Release session lock to prevent 504 during image processing
        session_write_close();
        
        // Handle Cover Image (Normal or Chunked)
        $coverName = null;
        $tempCover = $this->request->getPost('temp_cover');
        
        if ($tempCover) {
            $coverName = $tempCover;
            $tempPath = WRITEPATH . 'uploads/temp/' . $tempCover;
            if (file_exists($tempPath)) {
                $targetDir = FCPATH . 'uploads/news/covers/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                rename($tempPath, $targetDir . $coverName);
            }
        } elseif ($coverFile && $coverFile->isValid() && !$coverFile->hasMoved()) {
            $coverName = $coverFile->getRandomName();
            $coverFile->move(FCPATH . 'uploads/news/covers/', $coverName);
        }

        // Save Main News
        try {
            $newsId = $newsModel->insert([
                'news_title' => $title,
                'news_slug' => $slug,
                'news_content' => $this->request->getPost('content'),
                'news_category' => $this->request->getPost('category'),
                'news_cover' => $coverName,
                'news_status' => $this->request->getPost('status') ?? 'published',
                'news_created_by' => $userId,
                'news_created_at' => $this->request->getPost('created_at') ?: date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            // Error cleanup (optional)
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'ไม่สามารถบันทึกข้อมูลได้: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'ไม่สามารถบันทึกข้อมูลได้: ' . $e->getMessage());
        }

        // Handle Gallery Images (Normal or Chunked)
        $tempGallery = $this->request->getPost('temp_gallery');
        if ($tempGallery && is_array($tempGallery)) {
            foreach ($tempGallery as $tempName) {
                $tempPath = WRITEPATH . 'uploads/temp/' . $tempName;
                if (file_exists($tempPath)) {
                    $targetDir = FCPATH . 'uploads/news/gallery/';
                    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                    rename($tempPath, $targetDir . $tempName);
                    
                    $galleryModel->insert([
                        'gal_news_id' => $newsId,
                        'gal_image' => $tempName
                    ]);
                }
            }
        }
        
        $imageFiles = $this->request->getFiles();
        if (isset($imageFiles['gallery'])) {
            foreach ($imageFiles['gallery'] as $img) {
                if ($img->isValid() && !$img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move(FCPATH . 'uploads/news/gallery/', $newName);
                    
                    $galleryModel->insert([
                        'gal_news_id' => $newsId,
                        'gal_image' => $newName
                    ]);
                }
            }
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'บันทึกข่าวสารพร้อมรูปภาพแกลเลอรีเรียบร้อยแล้ว',
                'redirect' => base_url('staff/news')
            ]);
        }

        return redirect()->to(base_url('staff/news'))->with('success', 'บันทึกข่าวสารพร้อมรูปภาพแกลเลอรีเรียบร้อยแล้ว');
    }

    private function processImage($path, $maxWidth)
    {
        try {
            $image = \Config\Services::image();
            $image->withFile($path)
                  ->resize($maxWidth, $maxWidth, true, 'width')
                  ->save($path, 80);
        } catch (\Exception $e) {
            log_message('error', 'Image processing failed: ' . $e->getMessage());
        }
    }

    public function newsEdit($id)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'news') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบจัดการข่าวสาร');
        }
        $userId = session()->get('u_id');
        $newsModel = new \App\Models\NewsModel();
        $galleryModel = new \App\Models\NewsGalleryModel();
        
        $news = $newsModel->where('news_id', $id)->first();
        if (!$news) {
            return redirect()->to(base_url('staff/news'))->with('error', 'ไม่พบข้อมูลที่ต้องการแก้ไข');
        }

        $data['title'] = "แก้ไขข่าวสาร | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        $data['news'] = $news;
        $data['gallery'] = $galleryModel->where('gal_news_id', $id)->findAll();

        return view('staff/news/edit', $data);
    }

    public function newsUpdate($id)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'news') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบจัดการข่าวสาร');
        }
        $newsModel = new \App\Models\NewsModel();
        $galleryModel = new \App\Models\NewsGalleryModel();
        $userId = session()->get('u_id');
        
        $news = $newsModel->where('news_id', $id)->first();
        if (!$news) {
            return redirect()->to(base_url('staff/news'))->with('error', 'ไม่พบข้อมูลที่ต้องการแก้ไข');
        }

        $rules = [
            'title' => 'required|min_length[5]',
            'content' => 'required',
            'category' => 'required'
        ];

        $coverFile = $this->request->getFile('cover');
        if ($coverFile && $coverFile->isValid()) {
            $rules['cover'] = 'is_image[cover]|max_size[cover,2048]';
        }

        if ($this->request->isAJAX()) {
            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors()
                ]);
            }
        } else {
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        $updateData = [
            'news_title' => $this->request->getPost('title'),
            'news_content' => $this->request->getPost('content'),
            'news_category' => $this->request->getPost('category'),
            'news_status' => $this->request->getPost('status') ?? 'published',
            'news_created_at' => $this->request->getPost('created_at')
        ];

        // Release session lock
        session_write_close();

        // Replace Cover (Normal or Chunked)
        $tempCover = $this->request->getPost('temp_cover');
        if ($tempCover) {
            if ($news['news_cover']) {
                @unlink(FCPATH . 'uploads/news/covers/' . $news['news_cover']);
            }
            $coverName = $tempCover;
            $tempPath = WRITEPATH . 'uploads/temp/' . $tempCover;
            if (file_exists($tempPath)) {
                $targetDir = FCPATH . 'uploads/news/covers/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                rename($tempPath, $targetDir . $coverName);
                $updateData['news_cover'] = $coverName;
            }
        } elseif ($coverFile && $coverFile->isValid() && !$coverFile->hasMoved()) {
            if ($news['news_cover']) {
                @unlink(FCPATH . 'uploads/news/covers/' . $news['news_cover']);
            }
            $coverName = $coverFile->getRandomName();
            $coverFile->move(FCPATH . 'uploads/news/covers/', $coverName);
            $updateData['news_cover'] = $coverName;
        }

        try {
            $newsModel->update($id, $updateData);
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'ไม่สามารถปรับปรุงข้อมูลได้: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'ไม่สามารถปรับปรุงข้อมูลได้: ' . $e->getMessage());
        }

        // Add more Gallery Images (Normal or Chunked)
        $tempGallery = $this->request->getPost('temp_gallery');
        if ($tempGallery && is_array($tempGallery)) {
            foreach ($tempGallery as $tempName) {
                $tempPath = WRITEPATH . 'uploads/temp/' . $tempName;
                if (file_exists($tempPath)) {
                    $targetDir = FCPATH . 'uploads/news/gallery/';
                    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                    rename($tempPath, $targetDir . $tempName);
                    
                    $galleryModel->insert(['gal_news_id' => $id, 'gal_image' => $tempName]);
                }
            }
        }

        $imageFiles = $this->request->getFiles();
        if (isset($imageFiles['gallery'])) {
            foreach ($imageFiles['gallery'] as $img) {
                if ($img->isValid() && !$img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move(FCPATH . 'uploads/news/gallery/', $newName);
                    
                    $galleryModel->insert(['gal_news_id' => $id, 'gal_image' => $newName]);
                }
            }
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'ปรับปรุงข่าวสารเรียบร้อยแล้ว',
                'redirect' => base_url('staff/news')
            ]);
        }

        return redirect()->to(base_url('staff/news'))->with('success', 'ปรับปรุงข่าวสารเรียบร้อยแล้ว');
    }

    public function newsDeleteImage($galId)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'news') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบจัดการข่าวสาร');
        }
        $galleryModel = new \App\Models\NewsGalleryModel();
        $item = $galleryModel->find($galId);
        if ($item) {
            @unlink(FCPATH . 'uploads/news/gallery/' . $item['gal_image']);
            $galleryModel->delete($galId);
        }
        return redirect()->back()->with('success', 'ลบรูปภาพเรียบร้อยแล้ว');
    }

    public function newsDelete($id)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'news') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบจัดการข่าวสาร');
        }
        $newsModel = new \App\Models\NewsModel();
        $galleryModel = new \App\Models\NewsGalleryModel();
        $userId = session()->get('u_id');
        
        $news = $newsModel->where('news_id', $id)->first();
        if ($news) {
            // Delete Cover
            if ($news['news_cover']) {
                @unlink(FCPATH . 'uploads/news/covers/' . $news['news_cover']);
            }

            // Delete Gallery Images
            $gallery = $galleryModel->where('gal_news_id', $id)->findAll();
            foreach ($gallery as $item) {
                @unlink(FCPATH . 'uploads/news/gallery/' . $item['gal_image']);
            }
            $galleryModel->where('gal_news_id', $id)->delete();

            // Delete News record
            $newsModel->delete($id);
            return redirect()->to(base_url('staff/news'))->with('success', 'ลบข่าวสารและแกลเลอรีเรียบร้อยแล้ว');
        }
        
        return redirect()->to(base_url('staff/news'))->with('error', 'ไม่สามารถลบข่าวสารได้');
    }

    // ================================================================
    // 🎓 SCHOLARSHIP MANAGEMENT
    // ================================================================

    public function scholarships()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบทุนการศึกษา');
        }
        $schModel = new \App\Models\ScholarshipModel();
        
        $data['title'] = "จัดการทุนการศึกษา | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        $data['scholarships'] = $schModel->orderBy('sch_created_at', 'DESC')->findAll();
        
        return view('staff/scholarships/staff_sch_index', $data);
    }

    public function scholarshipBookingIndex()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบผู้จองทุน');
        }
        
        $model = new \App\Models\ScholarshipModel();
        $bookingModel = new \App\Models\ScholarshipBookingModel();
        
        // Show only active scholarships for booking management
        $scholarships = $model->where('sch_status', 'published')->orderBy('sch_created_at', 'DESC')->findAll();
        
        foreach ($scholarships as &$sch) {
            // นับจำนวนการจอง (ทั้งหมด) สำหรับทุนนี้
            $sch['total_bookings'] = $bookingModel->join('Tb_Scholarship_Slots as s', 's.slot_id = bk_slot_id')
                                                 ->where('s.slot_scholarship_id', $sch['sch_id'])
                                                 ->countAllResults();

            // นับจำนวนการจองที่ค้างอยู่ (Pending) สำหรับทุนนี้
            $sch['pending_bookings'] = $bookingModel->join('Tb_Scholarship_Slots as s2', 's2.slot_id = bk_slot_id')
                                                   ->where(['s2.slot_scholarship_id' => $sch['sch_id'], 'bk_status' => 'pending'])
                                                   ->countAllResults();
        }

        $data['scholarships'] = $scholarships;
        $data['title'] = 'จัดการคิวจองทุน';
        $data['fullname'] = session()->get('u_fullname');
        return view('staff/scholarships/staff_sch_booking_index', $data);
    }

    public function scholarshipCreate()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบทุนการศึกษา');
        }
        $data['title'] = "เพิ่มทุนการศึกษาใหม่ | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        return view('staff/scholarships/staff_sch_create', $data);
    }

    public function scholarshipStore()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบทุนการศึกษา');
        }
        $schModel = new \App\Models\ScholarshipModel();
        $userId = session()->get('u_id');
        
        $rules = [
            'sch_title' => 'required|min_length[5]',
            'sch_content' => 'required',
        ];

        $coverFile = $this->request->getFile('sch_cover');
        if ($coverFile && $coverFile->isValid()) {
            $rules['sch_cover'] = 'is_image[sch_cover]|max_size[sch_cover,2048]';
        }
        
        $attachmentFile = $this->request->getFile('sch_attachment');
        if ($attachmentFile && $attachmentFile->isValid()) {
            $rules['sch_attachment'] = 'uploaded[sch_attachment]|max_size[sch_attachment,5120]|ext_in[sch_attachment,pdf,doc,docx,xls,xlsx,ppt,pptx,zip]';
        }

        if (!$this->validate($rules)) {
            log_message('error', 'Scholarship Store Validation Failed: ' . json_encode($this->request->getPost()));
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('sch_title');
        $slug = $this->generateSlug($title, 'Tb_Scholarships', 'sch_slug');
        
        session_write_close();

        $coverName = null;
        $attachmentName = null;
        $targetDirCovers = FCPATH . 'uploads/scholarships/covers/';
        $targetDirAttach = FCPATH . 'uploads/scholarships/attachments/';
        
        if (!is_dir($targetDirCovers)) mkdir($targetDirCovers, 0777, true);
        if (!is_dir($targetDirAttach)) mkdir($targetDirAttach, 0777, true);
        
        // Handle Cover
        $tempCover = $this->request->getPost('temp_cover');
        if ($tempCover) {
            $coverName = $tempCover;
            $tempPath = WRITEPATH . 'uploads/temp/' . $tempCover;
            if (file_exists($tempPath)) rename($tempPath, $targetDirCovers . $coverName);
        } elseif ($coverFile && $coverFile->isValid() && !$coverFile->hasMoved()) {
            $coverName = $coverFile->getRandomName();
            $coverFile->move($targetDirCovers, $coverName);
        }

        // Handle Attachment
        if ($attachmentFile && $attachmentFile->isValid() && !$attachmentFile->hasMoved()) {
            $attachmentName = $attachmentFile->getRandomName();
            $attachmentFile->move($targetDirAttach, $attachmentName);
        }

        try {
            $schModel->insert([
                'sch_title' => $title,
                'sch_slug' => $slug,
                'sch_content' => $this->request->getPost('sch_content'),
                'sch_amount' => $this->request->getPost('sch_amount') ?: null,
                'sch_deadline' => $this->request->getPost('sch_deadline') ?: null,
                'sch_cover' => $coverName,
                'sch_attachment' => $attachmentName,
                'sch_status' => $this->request->getPost('sch_status') ?: 'published',
                'sch_created_by' => $userId,
            ]);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกทุนการศึกษาเรียบร้อยแล้ว', 'redirect' => base_url('staff/scholarships')]);
            }
            return redirect()->to(base_url('staff/scholarships'))->with('success', 'บันทึกทุนการศึกษาเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้: ' . $e->getMessage()]);
            }
            return redirect()->back()->withInput()->with('error', 'ไม่สามารถบันทึกข้อมูลได้: ' . $e->getMessage());
        }
    }

    public function scholarshipEdit($id)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบทุนการศึกษา');
        }
        $schModel = new \App\Models\ScholarshipModel();
        $scholarship = $schModel->find($id);
        
        if (!$scholarship) {
            return redirect()->to(base_url('staff/scholarships'))->with('error', 'ไม่พบข้อมูลที่ต้องการแก้ไข');
        }

        $data['title'] = "แก้ไขทุนการศึกษา | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        $data['sch'] = $scholarship;

        return view('staff/scholarships/staff_sch_edit', $data);
    }

    public function scholarshipUpdate($id)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบทุนการศึกษา');
        }
        $schModel = new \App\Models\ScholarshipModel();
        $scholarship = $schModel->find($id);
        
        if (!$scholarship) {
            return redirect()->to(base_url('staff/scholarships'))->with('error', 'ไม่พบข้อมูล');
        }

        $rules = [
            'sch_title' => 'required|min_length[5]',
            'sch_content' => 'required',
        ];

        $coverFile = $this->request->getFile('sch_cover');
        if ($coverFile && $coverFile->isValid()) {
            $rules['sch_cover'] = 'is_image[sch_cover]|max_size[sch_cover,2048]';
        }

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'sch_title' => $this->request->getPost('sch_title'),
            'sch_content' => $this->request->getPost('sch_content'),
            'sch_amount' => $this->request->getPost('sch_amount'),
            'sch_deadline' => $this->request->getPost('sch_deadline') ?: null,
            'sch_status' => $this->request->getPost('sch_status') ?? 'published',
        ];

        session_write_close();

        $targetDirCovers = FCPATH . 'uploads/scholarships/covers/';
        $targetDirAttach = FCPATH . 'uploads/scholarships/attachments/';
        if (!is_dir($targetDirCovers)) mkdir($targetDirCovers, 0777, true);
        if (!is_dir($targetDirAttach)) mkdir($targetDirAttach, 0777, true);

        // Handle Cover Update
        $tempCover = $this->request->getPost('temp_cover');
        if ($tempCover) {
            if ($scholarship['sch_cover'] && file_exists($targetDirCovers . $scholarship['sch_cover'])) @unlink($targetDirCovers . $scholarship['sch_cover']);
            $coverName = $tempCover;
            $tempPath = WRITEPATH . 'uploads/temp/' . $tempCover;
            if (file_exists($tempPath)) {
                rename($tempPath, $targetDirCovers . $coverName);
                $updateData['sch_cover'] = $coverName;
            }
        } elseif ($coverFile && $coverFile->isValid() && !$coverFile->hasMoved()) {
            if ($scholarship['sch_cover'] && file_exists($targetDirCovers . $scholarship['sch_cover'])) @unlink($targetDirCovers . $scholarship['sch_cover']);
            $coverName = $coverFile->getRandomName();
            $coverFile->move($targetDirCovers, $coverName);
            $updateData['sch_cover'] = $coverName;
        }

        // Handle Attachment Update
        $attachmentFile = $this->request->getFile('sch_attachment');
        if ($attachmentFile && $attachmentFile->isValid() && !$attachmentFile->hasMoved()) {
            if ($scholarship['sch_attachment'] && file_exists($targetDirAttach . $scholarship['sch_attachment'])) @unlink($targetDirAttach . $scholarship['sch_attachment']);
            $attachmentName = $attachmentFile->getRandomName();
            $attachmentFile->move($targetDirAttach, $attachmentName);
            $updateData['sch_attachment'] = $attachmentName;
        }

        try {
            $schModel->update($id, $updateData);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'ปรับปรุงทุนการศึกษาเรียบร้อยแล้ว', 'redirect' => base_url('staff/scholarships')]);
            }
            return redirect()->to(base_url('staff/scholarships'))->with('success', 'ปรับปรุงทุนการศึกษาเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
            }
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function scholarshipDelete($id)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบทุนการศึกษา');
        }
        $schModel = new \App\Models\ScholarshipModel();
        $scholarship = $schModel->find($id);
        
        if ($scholarship) {
            // ลบคิวจองและสล็อต
            $slotModel = new \App\Models\ScholarshipSlotModel();
            $bookingModel = new \App\Models\ScholarshipBookingModel();
            $slots = $slotModel->where('slot_scholarship_id', $id)->findAll();
            foreach ($slots as $s) {
                $bookingModel->where('bk_slot_id', $s['slot_id'])->delete();
            }
            $slotModel->where('slot_scholarship_id', $id)->delete();

            // ลบไฟล์
            if ($scholarship['sch_cover'] && file_exists(FCPATH . 'uploads/scholarships/covers/' . $scholarship['sch_cover'])) {
                @unlink(FCPATH . 'uploads/scholarships/covers/' . $scholarship['sch_cover']);
            }
            if ($scholarship['sch_attachment'] && file_exists(FCPATH . 'uploads/scholarships/attachments/' . $scholarship['sch_attachment'])) {
                @unlink(FCPATH . 'uploads/scholarships/attachments/' . $scholarship['sch_attachment']);
            }

            $schModel->delete($id);
        }
        return redirect()->to(base_url('staff/scholarships'))->with('success', 'ลบทุนการศึกษาเรียบร้อยแล้ว');
    }

    // ================================================================
    // 📅 SCHOLARSHIP BOOKING / SLOT MANAGEMENT
    // ================================================================

    /**
     * หน้าจัดการตารางเวลา (Slot Management)
     */
    public function scholarshipSlots($schId)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบสล็อตจองทุน');
        }
        
        $schModel  = new \App\Models\ScholarshipModel();
        $slotModel = new \App\Models\ScholarshipSlotModel();
        
        $scholarship = $schModel->find($schId);
        if (!$scholarship) {
            return redirect()->to(base_url('staff/scholarships'))->with('error', 'ไม่พบข้อมูลทุนการศึกษา');
        }

        $filterDate = $this->request->getGet('date');

        $data['title'] = "ตารางจองคิว - {$scholarship['sch_title']} | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        $data['scholarship'] = $scholarship;
        $data['slots'] = $slotModel->getSlotsWithBookingCount($schId, $filterDate);
        $data['available_dates'] = $slotModel->getAvailableDates($schId);
        $data['filter_date'] = $filterDate;

        return view('staff/scholarships/staff_sch_slots', $data);
    }

    /**
     * สร้างสล็อตเวลาอัตโนมัติ (Batch Generate)
     */
    public function scholarshipSlotGenerate($schId)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบสล็อตจองทุน');
        }

        $schModel  = new \App\Models\ScholarshipModel();
        $slotModel = new \App\Models\ScholarshipSlotModel();

        $scholarship = $schModel->find($schId);
        if (!$scholarship) {
            return redirect()->to(base_url('staff/scholarships'))->with('error', 'ไม่พบข้อมูลทุนการศึกษา');
        }

        $rules = [
            'slot_date'     => 'required|valid_date',
            'start_time'    => 'required',
            'end_time'      => 'required',
            'duration'      => 'required|integer|greater_than[0]',
            'max_per_slot'  => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $slotDate    = $this->request->getPost('slot_date');
        $startTime   = strtotime($this->request->getPost('start_time'));
        $endTime     = strtotime($this->request->getPost('end_time'));
        $duration    = (int) $this->request->getPost('duration');
        $maxPerSlot  = (int) $this->request->getPost('max_per_slot');

        if ($startTime >= $endTime) {
            return redirect()->back()->withInput()->with('error', 'เวลาเริ่มต้นต้องน้อยกว่าเวลาสิ้นสุด');
        }

        // ตรวจสอบว่ามีสล็อตของวันที่เลือกอยู่แล้วหรือไม่
        $existingSlots = $slotModel->where(['slot_scholarship_id' => $schId, 'slot_date' => $slotDate])->orderBy('slot_start_time', 'ASC')->findAll();
        
        if ($existingSlots) {
            $bookingModel = new \App\Models\ScholarshipBookingModel();
            $slotIds = array_column($existingSlots, 'slot_id');
            $hasBookings = $bookingModel->whereIn('bk_slot_id', $slotIds)->countAllResults();

            // หาเวลาเริ่มและเวลาสิ้นสุดปัจจุบัน
            $currentStart = strtotime($existingSlots[0]['slot_start_time']);
            $currentEnd = strtotime(end($existingSlots)['slot_end_time']);

            // กรณีมีคนจองแล้ว: เราจะทำการ "Smart Update/Extend"
            if ($hasBookings > 0) {
                // ตรวจสอบว่า เวลาเริ่มต้น และ ระยะเวลา (Duration) ตรงกันไหม (เพื่อไม่ให้ตารางเดิมพัง)
                // เราเช็คแค่รอบแรกก็พอ ถ้าไม่ตรงกัน แสดงว่าความตั้งใจคือการเปลี่ยนโครงสร้างใหม่ทั้งหมด ซึ่งทำไม่ได้ถ้ามีคนจอง
                $firstSlotStart = strtotime($existingSlots[0]['slot_start_time']);
                $firstSlotEnd = strtotime($existingSlots[0]['slot_end_time']);
                $currentDuration = ($firstSlotEnd - $firstSlotStart) / 60;

                if ($startTime != $firstSlotStart || $duration != $currentDuration) {
                    return redirect()->back()->withInput()->with('error', "ไม่สามารถเปลี่ยน 'เวลาเริ่มต้น' หรือ 'ระยะเวลาต่อรอบ' ได้ เนื่องจากมีผู้สมัครจองคิวในตารางเดิมไปแล้ว กรุณาจัดการรายการจองออกก่อน หรือเตรียมตารางให้ตรงกับของเดิม");
                }

                // สิทธิ์ในการอัปเดต Slot Max และการขยายเวลา
                // 1. อัปเดต slot_max ของเดิม
                $slotModel->whereIn('slot_id', $slotIds)->set(['slot_max' => $maxPerSlot])->update();

                // 2. ถ้าเวลาสิ้นสุดใหม่ ยาวกว่าของเดิม ให้เตรียมสร้างรอบต่อท้าย
                if ($endTime > $currentEnd) {
                    $startTime = $currentEnd; // เริ่มต้นสร้างต่อจากรอบสุดท้ายที่มีอยู่
                } else {
                    // ถ้าไม่ได้ขยายเวลา ก็ถือว่าอัปเดตแค่ slot_max เสร็จแล้ว
                    return redirect()->to(base_url("staff/scholarship/{$schId}/slots?date={$slotDate}"))
                        ->with('success', "อัปเดตจำนวนรับสูงสุดของวันที่ $slotDate เรียบร้อยแล้ว");
                }
            } else {
                // กรณี "ยังไม่มีคนจอง": ลบของเก่าเพื่อสร้างใหม่ตามปกติ
                $slotModel->where(['slot_scholarship_id' => $schId, 'slot_date' => $slotDate])->delete();
            }
        }

        $count = 0;
        $current = $startTime;
        while ($current < $endTime) {
            $slotStart = date('H:i:s', $current);
            $current += ($duration * 60);
            if ($current > $endTime) break;
            $slotEnd = date('H:i:s', $current);

            $slotModel->insert([
                'slot_scholarship_id' => $schId,
                'slot_date'           => $slotDate,
                'slot_start_time'     => $slotStart,
                'slot_end_time'       => $slotEnd,
                'slot_max'            => $maxPerSlot,
                'slot_is_active'      => 1,
                'slot_created_at'     => date('Y-m-d H:i:s'),
            ]);
            $count++;
        }

        return redirect()->to(base_url("staff/scholarship/{$schId}/slots?date={$slotDate}"))
            ->with('success', "สร้างสล็อตเรียบร้อย จำนวน {$count} รอบ");
    }

    /**
     * เปิด/ปิดสล็อต (Toggle Active)
     */
    public function scholarshipSlotToggle($slotId)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบสล็อตจองทุน');
        }

        $slotModel = new \App\Models\ScholarshipSlotModel();
        $slot = $slotModel->find($slotId);

        if ($slot) {
            $slotModel->update($slotId, [
                'slot_is_active' => $slot['slot_is_active'] ? 0 : 1
            ]);
        }

        return redirect()->back()->with('success', 'อัปเดตสถานะสล็อตเรียบร้อยแล้ว');
    }

    /**
     * ลบสล็อตทั้งวัน
     */
    public function scholarshipSlotDeleteDay($schId)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบสล็อตจองทุน');
        }

        $date = $this->request->getGet('date');
        if (!$date) {
            return redirect()->back()->with('error', 'กรุณาระบุวันที่');
        }

        $slotModel = new \App\Models\ScholarshipSlotModel();
        $bookingModel = new \App\Models\ScholarshipBookingModel();

        // หา slot_ids ที่ต้องการลบ
        $slots = $slotModel->where('slot_scholarship_id', $schId)->where('slot_date', $date)->findAll();
        foreach ($slots as $s) {
            // ลบ bookings ที่เกี่ยวข้อง
            $bookingModel->where('bk_slot_id', $s['slot_id'])->delete();
        }
        // ลบ slots
        $slotModel->where('slot_scholarship_id', $schId)->where('slot_date', $date)->delete();

        return redirect()->to(base_url("staff/scholarship/{$schId}/slots"))
            ->with('success', "ลบสล็อตวันที่ {$date} เรียบร้อยแล้ว");
    }

    /**
     * หน้าดูรายชื่อผู้จอง (Booking Tracker)
     */
    public function scholarshipBookings($schId)
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'scholarships') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบข้อมูลผู้จองทุน');
        }

        $schModel     = new \App\Models\ScholarshipModel();
        $bookingModel = new \App\Models\ScholarshipBookingModel();
        $slotModel    = new \App\Models\ScholarshipSlotModel();

        $scholarship = $schModel->find($schId);
        if (!$scholarship) {
            return redirect()->to(base_url('staff/scholarships'))->with('error', 'ไม่พบข้อมูลทุนการศึกษา');
        }

        $filterDate = $this->request->getGet('date') ?: date('Y-m-d');

        $data['title'] = "รายชื่อผู้จอง - {$scholarship['sch_title']} | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        $data['scholarship'] = $scholarship;
        $data['bookings'] = $bookingModel->getBookingsWithDetails($schId, $filterDate);
        $data['available_dates'] = $slotModel->getAvailableDates($schId);
        $data['filter_date'] = $filterDate;

        // สถิติ
        $allBookings = $bookingModel->getBookingsWithDetails($schId, $filterDate);
        $data['stats'] = [
            'total'      => count($allBookings),
            'pending'    => count(array_filter($allBookings, fn($b) => $b['bk_status'] === 'pending')),
            'confirmed'  => count(array_filter($allBookings, fn($b) => $b['bk_status'] === 'confirmed')),
            'checked_in' => count(array_filter($allBookings, fn($b) => $b['bk_status'] === 'checked_in')),
            'cancelled'  => count(array_filter($allBookings, fn($b) => $b['bk_status'] === 'cancelled')),
        ];

        return view('staff/scholarships/staff_sch_bookings', $data);
    }

    /**
     * อัปเดตสถานะการจอง (check-in / confirm)
     */
    public function scholarshipBookingStatus($bkId)
    {
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');

        $bookingModel = new \App\Models\ScholarshipBookingModel();
        $status = $this->request->getGet('status');

        if (in_array($status, ['pending', 'confirmed', 'checked_in', 'cancelled'])) {
            $bookingModel->update($bkId, ['bk_status' => $status]);
        }

        return redirect()->back()->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
    }

    /**
     * AJAX: อัปเดตการเปิดรับระดับชั้น (Settings)
     */
    public function scholarshipUpdateGrades()
    {
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่มีสิทธิ์เข้าถึง']);
        }

        $schId = $this->request->getPost('sch_id');
        $grades = $this->request->getPost('grades'); // Array of values
        $gradeStr = is_array($grades) ? implode(',', $grades) : '';

        $schModel = new \App\Models\ScholarshipModel();
        if ($schModel->update($schId, ['sch_allowed_grades' => $gradeStr])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกการตั้งค่าระดับชั้นเรียบร้อยแล้ว']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
    }

    // ================================================================
    // 👥 PERSONNEL MANAGEMENT
    // ================================================================
    
    public function personnel()
    {
        $roles = session()->get('u_role') ?? '';
        if (strpos($roles, 'admin') === false && strpos($roles, 'personnel') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');
        }
        
        $model = new \App\Models\UserModel();
        $posModel = new \App\Models\PositionModel();

        $data['title'] = "จัดการบุคลากร | อบจ.นครสวรรค์";
        // Join Tb_Positions to get pos_name
        $data['users'] = $model->select('Tb_Users.*, p.pos_name as position_name')
                              ->join('Tb_Positions as p', 'p.pos_id = Tb_Users.u_position', 'left')
                              ->orderBy('u_sort', 'ASC')
                              ->findAll();
        
        $data['positions'] = $posModel->orderBy('pos_name', 'ASC')->findAll();
        
        return view('staff/personnel/index', $data);
    }

    public function personnelSave()
    {
        $roles = session()->get('u_role') ?? '';
        if (strpos($roles, 'admin') === false && strpos($roles, 'personnel') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');
        }


        $model = new \App\Models\UserModel();
        $id = $this->request->getPost('u_id');
        $email = $this->request->getPost('u_email');
        $roles = $this->request->getPost('u_role');
        
        $roleStr = is_array($roles) ? implode(',', $roles) : ($roles ?: 'user');

        $data = [
            'u_prefix'   => $this->request->getPost('u_prefix'),
            'u_fullname' => $this->request->getPost('u_fullname'),
            'u_email'    => $email,
            'u_position' => $this->request->getPost('u_pos_id') ?: null, // ใช้ u_position เก็บ ID แล้ว
            'u_level'    => $this->request->getPost('u_level'),
            'u_division' => $this->request->getPost('u_division'),
            'u_phone'    => $this->request->getPost('u_phone'),
            'u_sort'     => $this->request->getPost('u_sort') ?: 99,
            'u_status'   => $this->request->getPost('u_status') ?: 'active',
            'u_role'     => $roleStr,
        ];

        // Release session lock to prevent 504 during image processing
        session_write_close();

        // Ensure username exists and generates randomly if not set via Google yet
        if (!$id) {
            $data['u_username'] = uniqid('user_');
            $data['u_password'] = password_hash(uniqid(), PASSWORD_DEFAULT);
        }

        // Handle Photo Upload (Normal or Chunked)
        $tempPhoto = $this->request->getPost('temp_photo');
        if ($tempPhoto) {
            $photoName = $tempPhoto;
            $tempPath = WRITEPATH . 'uploads/temp/' . $tempPhoto;
            if (file_exists($tempPath)) {
                $targetDir = FCPATH . 'uploads/personnel/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                
                // Cleanup old photo if updating
                if ($id) {
                    $oldUser = $model->find($id);
                    if ($oldUser && !empty($oldUser['u_photo'])) {
                        @unlink(FCPATH . 'uploads/personnel/' . $oldUser['u_photo']);
                    }
                }

                rename($tempPath, $targetDir . $photoName);
                $data['u_photo'] = $photoName;
            }
        } else {
            $photoFile = $this->request->getFile('u_photo');
            if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
                if ($id) {
                    $oldUser = $model->find($id);
                    if ($oldUser && !empty($oldUser['u_photo'])) {
                        @unlink(FCPATH . 'uploads/personnel/' . $oldUser['u_photo']);
                    }
                }
                $newName = $photoFile->getRandomName();
                $targetDir = FCPATH . 'uploads/personnel/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $photoFile->move($targetDir, $newName);
                $data['u_photo'] = $newName;
            }
        }

        try {
            if ($id) {
                $model->update($id, $data);
                $message = 'อัปเดตข้อมูลบุคลากรเรียบร้อยแล้ว';
            } else {
                $model->insert($data);
                $message = 'เพิ่มบุคลากรใหม่เรียบร้อยแล้ว';
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => $message,
                    'redirect' => base_url('staff/personnel')
                ]);
            }

            return redirect()->to(base_url('staff/personnel'))->with('success', $message);
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'ไม่สามารถบันทึกข้อมูลได้: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'ไม่สามารถบันทึกข้อมูลได้: ' . $e->getMessage());
        }
    }

    public function uploadChunk()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'This route requires a POST request. (Method: ' . $this->request->getMethod() . ')']);
        }
        
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $file = $this->request->getFile('file');
        $filename = $this->request->getPost('filename');
        $chunkIndex = (int)$this->request->getPost('chunkIndex');
        $totalChunks = (int)$this->request->getPost('totalChunks');
        $fileId = $this->request->getPost('fileId'); // Unique ID for this file upload session

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid file chunk']);
        }

        $tempDir = WRITEPATH . 'uploads/chunks/' . $fileId;
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $file->move($tempDir, $chunkIndex);

        // Check if all chunks are uploaded
        $uploadedChunks = count(glob($tempDir . '/*'));
        if ($uploadedChunks === $totalChunks) {
            // Assemble file
            $finalPath = WRITEPATH . 'uploads/temp/' . $filename;
            if (!is_dir(dirname($finalPath))) {
                mkdir(dirname($finalPath), 0777, true);
            }

            $out = fopen($finalPath, 'wb');
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = $tempDir . '/' . $i;
                $in = fopen($chunkPath, 'rb');
                stream_copy_to_stream($in, $out);
                fclose($in);
                @unlink($chunkPath);
            }
            fclose($out);
            @rmdir($tempDir);

            return $this->response->setJSON([
                'status' => 'completed',
                'temp_file' => $filename
            ]);
        }

        return $this->response->setJSON([
            'status' => 'progress',
            'uploaded' => $uploadedChunks,
            'total' => $totalChunks
        ]);
    }

    public function personnelDelete($id)
    {
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');
        $model = new \App\Models\UserModel();
        $user = $model->find($id);
        if ($user && !empty($user['u_photo']) && file_exists(FCPATH . 'uploads/personnel/' . $user['u_photo'])) {
            @unlink(FCPATH . 'uploads/personnel/' . $user['u_photo']);
        }
        $model->delete($id);
        return redirect()->to(base_url('staff/personnel'))->with('success', 'ลบข้อมูลบุคลากรเรียบร้อยแล้ว');
    }
}
