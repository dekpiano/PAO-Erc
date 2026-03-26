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
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');
        $userId = session()->get('u_id');
        $newsModel = new \App\Models\NewsModel();
        
        $data['title'] = "จัดการข่าวสาร | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        $data['news'] = $newsModel->where('news_created_by', $userId)
                                 ->orderBy('news_created_at', 'DESC')
                                 ->findAll();
                                 
        return view('staff/news/index', $data);
    }

    public function newsCreate()
    {
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');
        $data['title'] = "เพิ่มข่าวสารใหม่ | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        return view('staff/news/create', $data);
    }

    public function newsStore()
    {
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');
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
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');
        $userId = session()->get('u_id');
        $newsModel = new \App\Models\NewsModel();
        $galleryModel = new \App\Models\NewsGalleryModel();
        
        $news = $newsModel->where('news_id', $id)->where('news_created_by', $userId)->first();
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
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');
        $newsModel = new \App\Models\NewsModel();
        $galleryModel = new \App\Models\NewsGalleryModel();
        $userId = session()->get('u_id');
        
        $news = $newsModel->where('news_id', $id)->where('news_created_by', $userId)->first();
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
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');
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
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');
        $newsModel = new \App\Models\NewsModel();
        $galleryModel = new \App\Models\NewsGalleryModel();
        $userId = session()->get('u_id');
        
        $news = $newsModel->where('news_id', $id)->where('news_created_by', $userId)->first();
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
    // 👥 PERSONNEL MANAGEMENT
    // ================================================================
    
    public function personnel()
    {
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');
        $model = new \App\Models\UserModel();
        $data['title'] = "จัดการบุคลากร | อบจ.นครสวรรค์";
        $data['users'] = $model->orderBy('u_sort', 'ASC')->findAll();
        return view('staff/personnel/index', $data);
    }

    public function personnelSave()
    {
        if (strpos(session()->get('u_role') ?? '', 'admin') === false) return redirect()->to(base_url('staff'))->with('error', 'ไม่มีสิทธิ์เข้าถึง');


        $model = new \App\Models\UserModel();
        $id = $this->request->getPost('u_id');
        $email = $this->request->getPost('u_email');
        $roles = $this->request->getPost('u_role');
        
        $roleStr = is_array($roles) ? implode(',', $roles) : ($roles ?: 'user');

        $data = [
            'u_prefix'   => $this->request->getPost('u_prefix'),
            'u_fullname' => $this->request->getPost('u_fullname'),
            'u_email'    => $email,
            'u_position' => $this->request->getPost('u_position'),
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
