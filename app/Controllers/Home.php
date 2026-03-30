<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;


class Home extends Controller
{
    public function index()
    {


        $newsModel = new \App\Models\NewsModel();
        $data['title'] = 'กองการศึกษา ศาสนา และวัฒนธรรม - องค์การบริหารส่วนจังหวัดนครสวรรค์';
        
        // Fetch Top 6 Latest Published News
        $data['latest_news'] = $newsModel->where('news_status', 'published')
                                         ->orderBy('news_created_at', 'DESC')
                                         ->limit(6)
                                         ->findAll();

        $schModel = new \App\Models\ScholarshipModel();
        // Fetch Top 4 Latest Published Scholarships
        $data['latest_scholarships'] = $schModel->where('sch_status', 'published')
                                               ->orderBy('sch_created_at', 'DESC')
                                               ->limit(4)
                                               ->findAll();
                                         
        return view('user/index', $data);
    }

    public function newsAll()
    {
        $newsModel = new \App\Models\NewsModel();
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');

        $query = $newsModel->where('news_status', 'published');

        if ($search) {
            $query->groupStart()
                  ->like('news_title', $search)
                  ->orLike('news_content', $search)
                  ->groupEnd();
        }

        if ($category) {
            $query->where('news_category', $category);
        }

        $data['title'] = "คลังข่าวสารและกิจกรรม | อบจ.นครสวรรค์";
        $data['news'] = $query->orderBy('news_created_at', 'DESC')->paginate(12);
        $data['pager'] = $newsModel->pager;
        $data['search'] = $search;
        $data['category_active'] = $category;

        return view('user/news_all', $data);
    }

    public function newsDetail($slug)
    {
        $newsModel = new \App\Models\NewsModel();
        $galleryModel = new \App\Models\NewsGalleryModel();
        
        $news = $newsModel->where('news_slug', $slug)
                          ->where('news_status', 'published')
                          ->first();
                          
        if (!$news) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Increment View Count
        $newsModel->update($news['news_id'], [
            'news_view_count' => $news['news_view_count'] + 1
        ]);

        $data['title'] = $news['news_title'] . " | อบจ.นครสวรรค์";
        $data['news'] = $news;
        $data['gallery'] = $galleryModel->where('gal_news_id', $news['news_id'])->findAll();
        
        return view('user/news_detail', $data);
    }

    public function personnel()
    {
        $model = new UserModel();
        // Fetch active staff
        $users = $model->where('u_status', 'active')->findAll();

        // Custom sort logic:
        // 1. Primary: Manual Sort Order (u_sort). Treat 0, NULL, or empty as 9999 (last place)
        // 2. Secondary: Role Hierarchy (Director > Head > Staff)
        // 3. Tertiary: Fullname (fallback)
        usort($users, function($a, $b) {
            $getSortVal = function($val) {
                return (isset($val) && is_numeric($val) && (int)$val > 0) ? (int)$val : 9999;
            };

            $sortA = $getSortVal($a['u_sort'] ?? null);
            $sortB = $getSortVal($b['u_sort'] ?? null);

            if ($sortA != $sortB) {
                return $sortA <=> $sortB;
            }

            // Secondary: Role Rank
            $getRoleRank = function($roleStr) {
                $roleStr = strtolower($roleStr ?? '');
                if (strpos($roleStr, 'superadmin') !== false || strpos($roleStr, 'director') !== false) return 1000;
                if (strpos($roleStr, 'head') !== false) return 500;
                return 100;
            };

            $rankA = $getRoleRank($a['u_role'] ?? '');
            $rankB = $getRoleRank($b['u_role'] ?? '');

            if ($rankA != $rankB) {
                return $rankB <=> $rankA; 
            }

            // Tertiary: Fullname
            return strcmp($a['u_fullname'] ?? '', $b['u_fullname'] ?? '');
        });

        $data['title'] = 'ทำเนียบ - กองการศึกษา องค์การบริหารส่วนจังหวัดนครสวรรค์';
        
        // Structure the data for hierarchical organization chart
        $executives = array_values(array_filter($users, fn($u) => $u['u_division'] == 'ผู้บริหาร'));
        
        $divisions = [];
        $divisionNames = [
            'ฝ่ายบริหาร' => 'ฝ่ายบริหารงานทั่วไป',
            'ฝ่ายส่งเสริม' => 'ฝ่ายส่งเสริมการศึกษาฯ'
        ];

        foreach ($divisionNames as $key => $displayName) {
            $divUsers = array_values(array_filter($users, fn($u) => $u['u_division'] == $key));
            
            // Separate ALL Heads from general Staff
            $heads = [];
            $staff = [];
            
            foreach ($divUsers as $u) {
                if (strpos($u['u_role'] ?? '', 'head') !== false || strpos($u['u_role'] ?? '', 'director') !== false) {
                    $heads[] = $u;
                } else {
                    $staff[] = $u;
                }
            }

            if (!empty($heads) || !empty($staff)) {
                $divisions[] = [
                    'name' => $displayName,
                    'heads' => $heads, // Multiple heads allowed
                    'staff' => $staff
                ];
            }
        }

        $data['personnel'] = [
            'executives' => $executives,
            'divisions'  => $divisions,
            'other'      => array_filter($users, fn($u) => empty($u['u_division']) || !in_array($u['u_division'], ['ผู้บริหาร', 'ฝ่ายบริหาร', 'ฝ่ายส่งเสริม'])),
        ];

        return view('user/personnel', $data);
    }

    // ================================================================
    // 📅 SCHOLARSHIP BOOKING (PUBLIC / FRONTEND)
    // ================================================================

    /**
     * หน้าจองคิวทุนการศึกษา (Public)
     */
    public function scholarshipBooking($schSlug)
    {
        $schModel  = new \App\Models\ScholarshipModel();
        $slotModel = new \App\Models\ScholarshipSlotModel();

        $scholarship = $schModel->where('sch_slug', $schSlug)
                                ->where('sch_status', 'published')
                                ->first();

        if (!$scholarship) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['title'] = "จองคิว - {$scholarship['sch_title']} | อบจ.นครสวรรค์";
        $data['scholarship'] = $scholarship;
        $data['available_dates'] = $slotModel->getAvailableDates($scholarship['sch_id']);

        return view('user/booking/user_sch_index', $data);
    }

    /**
     * AJAX: ดึงสล็อตตามวันที่
     */
    public function scholarshipBookingSlots($schId)
    {
        $date = $this->request->getGet('date');
        if (!$date) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุวันที่']);
        }

        $slotModel = new \App\Models\ScholarshipSlotModel();
        $slots = $slotModel->getSlotsWithBookingCount($schId, $date);

        return $this->response->setJSON([
            'status' => 'success',
            'slots'  => $slots
        ]);
    }

    /**
     * AJAX: เช็คความว่างของสล็อต (Real-time check)
     */
    public function scholarshipBookingCheckSlot($slotId)
    {
        $slotModel = new \App\Models\ScholarshipSlotModel();
        $bookingModel = new \App\Models\ScholarshipBookingModel();

        $slot = $slotModel->find($slotId);
        if (!$slot) return $this->response->setJSON(['status' => 'error']);

        $bookedCount = $bookingModel->countBookedInSlot($slotId);
        $remaining = $slot['slot_max'] - $bookedCount;

        return $this->response->setJSON([
            'status'    => 'success',
            'is_full'   => ($remaining <= 0),
            'remaining' => ($remaining > 0 ? $remaining : 0)
        ]);
    }

    /**
     * บันทึกการจอง (Public POST)
     */
    public function scholarshipBookingStore()
    {
        $bookingModel = new \App\Models\ScholarshipBookingModel();
        $slotModel    = new \App\Models\ScholarshipSlotModel();

        $rules = [
            'slot_id'     => 'required|integer',
            'bk_fullname' => 'required|min_length[3]',
            'bk_phone'    => 'required|min_length[9]',
            'bk_school'   => 'required',
            'bk_grade'    => 'required',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $slotId = $this->request->getPost('slot_id');
        $db     = \Config\Database::connect();

        $db->transStart(); // 🛡️ เริ่ม Transaction ป้องกันจองพร้อมกัน

        // 1. ดึงข้อมูลสล็อตและล็อกแถวไว้ (SELECT FOR UPDATE) เพื่อป้องกันคนอื่นแก้อะไรรหว่างเราประมวลผล
        $slot = $db->table('Tb_Scholarship_Slots')
                   ->where('slot_id', $slotId)
                   ->get()->getRowArray();

        if (!$slot || !$slot['slot_is_active']) {
            $db->transRollback();
            $msg = 'สล็อตนี้ไม่พร้อมให้บริการ หรือเพิ่งถูกปิดไป';
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->with('error', $msg);
        }

        // 2. เช็คจำนวนคนจองล่าสุด ณ วินาทีนี้ (นับใหม่ใน Transaction)
        $bookedCount = $db->table('Tb_Scholarship_Bookings')
                          ->where('bk_slot_id', $slotId)
                          ->where('bk_status !=', 'cancelled')
                          ->countAllResults();

        if ($bookedCount >= $slot['slot_max']) {
            $db->transRollback();
            $msg = 'ขออภัย คิวเต็มแล้ว! มีผู้จองตัดหน้าท่านไปเพียงเสี้ยววินาที';
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->with('error', $msg);
        }

        // 3. เช็คซ้ำเบอร์โทรในทุนเดียวกัน
        $existing = $db->table('Tb_Scholarship_Bookings as b')
            ->join('Tb_Scholarship_Slots as s', 's.slot_id = b.bk_slot_id')
            ->where('b.bk_phone', $this->request->getPost('bk_phone'))
            ->where('s.slot_scholarship_id', $slot['slot_scholarship_id'])
            ->where('b.bk_status !=', 'cancelled')
            ->get()->getRow();

        if ($existing) {
            $db->transRollback();
            $msg = 'เบอร์โทรศัพท์นี้ได้จองคิวรับทุนนี้ไปแล้ว';
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->with('error', $msg);
        }

        // 4. บันทึกข้อมูล
        $queueNumber = $bookedCount + 1;
        $bookingData = [
            'bk_slot_id'      => $slotId,
            'bk_fullname'     => $this->request->getPost('bk_fullname'),
            'bk_phone'        => $this->request->getPost('bk_phone'),
            'bk_school'       => $this->request->getPost('bk_school'),
            'bk_grade'        => $this->request->getPost('bk_grade'),
            'bk_queue_number' => $queueNumber,
            'bk_status'       => 'confirmed',
            'bk_created_at'   => date('Y-m-d H:i:s'),
        ];
        
        $db->table('Tb_Scholarship_Bookings')->insert($bookingData);
        $bookingId = $db->insertID();

        $db->transComplete(); // ✅ จบ Transaction

        if ($db->transStatus() === false) {
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึก กรุณาลองใหม่']) : redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึก');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'   => 'success',
                'message'  => 'จองคิวสำเร็จ!',
                'redirect' => base_url("booking/success/{$bookingId}")
            ]);
        }

        return redirect()->to(base_url("booking/success/{$bookingId}"));
    }

    /**
     * หน้าใบนัดหมาย (E-Ticket)
     */
    public function scholarshipBookingSuccess($bkId)
    {
        $bookingModel = new \App\Models\ScholarshipBookingModel();
        $db = \Config\Database::connect();

        $booking = $db->table('Tb_Scholarship_Bookings as b')
            ->select('b.*, s.slot_date, s.slot_start_time, s.slot_end_time, sch.sch_title, sch.sch_slug')
            ->join('Tb_Scholarship_Slots as s', 's.slot_id = b.bk_slot_id')
            ->join('Tb_Scholarships as sch', 'sch.sch_id = s.slot_scholarship_id')
            ->where('b.bk_id', $bkId)
            ->get()->getRowArray();

        if (!$booking) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['title'] = "ใบนัดหมาย | อบจ.นครสวรรค์";
        $data['booking'] = $booking;

        return view('user/booking/user_sch_success', $data);
    }

    public function migrate()
    {
        $migrate = \Config\Services::migrations();
        try {
            $migrate->latest();
            return 'Migrations complete.';
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
