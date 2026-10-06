<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ITSupportModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ITSupportApiController extends BaseController
{
    protected $itsModel;
    protected $userModel;

    public function __construct()
    {
        $this->itsModel = new ITSupportModel();
        $this->userModel = new UserModel();
        helper('thai_date');
    }

    /**
     * ดึงรายการงาน IT Support ทั้งหมด พร้อมฟิลเตอร์และการแบ่งหน้า (Pagination)
     * GET /api/itsupport
     */
    public function index(): ResponseInterface
    {
        $request = $this->request;

        // Pagination params
        $page = max(1, (int) ($request->getGet('page') ?: 1));
        $limit = min(100, max(1, (int) ($request->getGet('limit') ?: $request->getGet('per_page') ?: 20)));
        $offset = ($page - 1) * $limit;

        // Filter params
        $search = trim((string) ($request->getGet('search') ?: $request->getGet('q') ?: ''));
        $category = trim((string) ($request->getGet('category') ?: ''));
        $location = trim((string) ($request->getGet('location') ?: ''));
        $recordedBy = trim((string) ($request->getGet('recorded_by') ?: ''));
        $userId = $request->getGet('user_id');
        $startDate = $request->getGet('start_date');
        $endDate = $request->getGet('end_date');
        $year = $request->getGet('year');
        $fiscalYear = $request->getGet('fiscal_year') ?: $request->getGet('fy');

        // Sorting
        $allowedSort = ['its_id', 'its_ticket_code', 'its_date', 'its_category', 'its_location', 'its_created_at'];
        $sortBy = in_array($request->getGet('sort_by'), $allowedSort, true) ? $request->getGet('sort_by') : 'its_date';
        $sortOrder = strtoupper((string) $request->getGet('sort_order') ?: $request->getGet('sort_dir') ?: 'DESC');
        if (!in_array($sortOrder, ['ASC', 'DESC'], true)) {
            $sortOrder = 'DESC';
        }

        // Query Builder
        $builder = $this->itsModel->builder();

        // Search Filter
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('its_task', $search)
                    ->orLike('its_ticket_code', $search)
                    ->orLike('its_location', $search)
                    ->orLike('its_recorded_by', $search)
                    ->orLike('its_category', $search)
                    ->groupEnd();
        }

        // Category Filter
        if (!empty($category) && $category !== 'all') {
            $builder->where('its_category', $category);
        }

        // Location Filter
        if (!empty($location)) {
            $builder->like('its_location', $location);
        }

        // Recorded By Filter
        if (!empty($recordedBy)) {
            $builder->like('its_recorded_by', $recordedBy);
        }

        // User ID Filter
        if (!empty($userId)) {
            $builder->where('its_user_id', (int) $userId);
        }

        // Date Range Filter
        if (!empty($startDate)) {
            $builder->where('DATE(its_date) >=', date('Y-m-d', strtotime($startDate)));
        }
        if (!empty($endDate)) {
            $builder->where('DATE(its_date) <=', date('Y-m-d', strtotime($endDate)));
        }

        // Fiscal Year Filter (เช่น 2569 / 2026 -> 1 ต.ค. ปีก่อนหน้า ถึง 30 ก.ย. ปีนั้น)
        if (!empty($fiscalYear) && $fiscalYear !== 'all') {
            $fyInt = (int) $fiscalYear;
            $fyAD = ($fyInt > 2400) ? ($fyInt - 543) : $fyInt;
            $startFY = ($fyAD - 1) . '-10-01 00:00:00';
            $endFY   = $fyAD . '-09-30 23:59:59';
            $builder->where('its_date >=', $startFY);
            $builder->where('its_date <=', $endFY);
        } elseif (!empty($year)) {
            $yInt = (int) $year;
            $yAD = ($yInt > 2400) ? ($yInt - 543) : $yInt;
            $builder->where('YEAR(its_date)', $yAD);
        }

        // นับจำนวนแถวทั้งหมด
        $totalRows = $builder->countAllResults(false);
        $totalPages = (int) ceil($totalRows / $limit);

        // ดึงข้อมูลพร้อมเรียงลำดับและแบ่งหน้า
        $builder->orderBy($sortBy, $sortOrder);
        $builder->limit($limit, $offset);
        $rows = $builder->get()->getResultArray();

        // แปลงข้อมูลและแนบ Full URL รูปภาพ
        $data = array_map([$this, 'formatTicket'], $rows);

        return $this->response->setJSON([
            'status'  => 200,
            'success' => true,
            'meta'    => [
                'page'        => $page,
                'limit'       => $limit,
                'total_rows'  => $totalRows,
                'total_pages' => $totalPages,
                'has_next'    => $page < $totalPages,
                'has_prev'    => $page > 1,
            ],
            'data'    => $data,
        ]);
    }

    /**
     * ดึงข้อมูลรายละเอียดงานรายใบงาน (ตาม ID หรือ Ticket Code เช่น IT-202605-0001)
     * GET /api/itsupport/{id} หรือ /api/itsupport/ticket/{ticket_code}
     */
    public function show($id = null): ResponseInterface
    {
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 400,
                'success' => false,
                'error'   => 'Bad Request',
                'message' => 'Missing ticket ID or ticket code parameter'
            ]);
        }

        // ตรวจสอบว่าเป็น ID ตัวเลข หรือ รหัส Ticket Code
        if (is_numeric($id)) {
            $log = $this->itsModel->find((int) $id);
        } else {
            $log = $this->itsModel->where('its_ticket_code', trim($id))->first();
        }

        if (!$log) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 404,
                'success' => false,
                'error'   => 'Not Found',
                'message' => 'Ticket not found'
            ]);
        }

        $formatted = $this->formatTicket($log, true);

        // ดึงข้อมูลเจ้าหน้าที่ผู้บันทึกเพิ่มเติม (ถ้ามี)
        if (!empty($log['its_user_id'])) {
            $user = $this->userModel->find($log['its_user_id']);
            if ($user) {
                $formatted['user'] = [
                    'id'        => (int) $user['u_id'],
                    'fullname'  => $user['u_fullname'] ?? $log['its_recorded_by'],
                    'position'  => $user['u_position'] ?? null,
                    'department'=> $user['u_department'] ?? null,
                    'avatar'    => !empty($user['u_avatar']) ? base_url('uploads/avatars/' . $user['u_avatar']) : null,
                ];
            }
        }

        return $this->response->setJSON([
            'status'  => 200,
            'success' => true,
            'data'    => $formatted
        ]);
    }

    /**
     * สรุปสถิติงาน IT Support (Dashboard / Overview Stats)
     * GET /api/itsupport/stats
     */
    public function stats(): ResponseInterface
    {
        $db = \Config\Database::connect();
        $request = $this->request;

        $fiscalYear = $request->getGet('fiscal_year') ?: $request->getGet('fy');
        $year = $request->getGet('year');

        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        $currentMonth = date('Y-m');
        $currentYear = date('Y');

        // Total All Time
        $totalAll = $this->itsModel->countAllResults();

        // Total Today
        $totalToday = $this->itsModel->where('DATE(its_date)', $today)->countAllResults();

        // Total This Month
        $totalMonth = $this->itsModel->where('DATE_FORMAT(its_date, "%Y-%m")', $currentMonth)->countAllResults();

        // Base query สำหรับสถิติแบบกรองตามปี (ถ้ามี)
        $statQuery = $db->table('Tb_It_Support_Logs');
        if (!empty($fiscalYear) && $fiscalYear !== 'all') {
            $fyInt = (int) $fiscalYear;
            $fyAD = ($fyInt > 2400) ? ($fyInt - 543) : $fyInt;
            $startFY = ($fyAD - 1) . '-10-01 00:00:00';
            $endFY   = $fyAD . '-09-30 23:59:59';
            $statQuery->where('its_date >=', $startFY)->where('its_date <=', $endFY);
            $yearLabel = "ปีงบประมาณ $fiscalYear";
        } elseif (!empty($year)) {
            $yInt = (int) $year;
            $yAD = ($yInt > 2400) ? ($yInt - 543) : $yInt;
            $statQuery->where('YEAR(its_date)', $yAD);
            $yearLabel = "ปี $year";
        } else {
            $statQuery->where('YEAR(its_date)', $currentYear);
            $yearLabel = "ปีปัจจุบัน (" . ((int)$currentYear + 543) . ")";
        }

        // Clone เพื่อหา Total ในรอบปีที่เลือก
        $totalSelectedYear = (clone $statQuery)->countAllResults();

        // หมวดหมู่งาน (Group By Category)
        $categoriesQuery = (clone $statQuery)
            ->select('its_category as category, COUNT(*) as count')
            ->groupBy('its_category')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResultArray();

        $categories = array_map(function($item) use ($totalSelectedYear) {
            $cnt = (int) $item['count'];
            $pct = $totalSelectedYear > 0 ? round(($cnt / $totalSelectedYear) * 100, 1) : 0;
            return [
                'category'   => $item['category'] ?: 'อื่นๆ',
                'count'      => $cnt,
                'percentage' => $pct
            ];
        }, $categoriesQuery);

        // แนวโน้มรายเดือน (Monthly Breakdown 12 เดือน)
        $monthlyQuery = (clone $statQuery)
            ->select('DATE_FORMAT(its_date, "%Y-%m") as ym, MONTH(its_date) as month_num, COUNT(*) as count')
            ->groupBy('ym, month_num')
            ->orderBy('ym', 'ASC')
            ->get()
            ->getResultArray();

        $thaiMonths = [
            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
            5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
            9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
        ];

        $monthly = array_map(function($item) use ($thaiMonths) {
            $mNum = (int) $item['month_num'];
            return [
                'year_month' => $item['ym'],
                'month_num'  => $mNum,
                'month_name' => $thaiMonths[$mNum] ?? '',
                'count'      => (int) $item['count']
            ];
        }, $monthlyQuery);

        // Top 5 สถานที่ปฏิบัติงานยอดนิยม
        $locations = (clone $statQuery)
            ->select('its_location as location, COUNT(*) as count')
            ->where("its_location IS NOT NULL AND its_location != ''")
            ->groupBy('its_location')
            ->orderBy('count', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // Top เจ้าหน้าที่ผู้บันทึก
        $recorders = (clone $statQuery)
            ->select('its_recorded_by as name, COUNT(*) as count')
            ->where("its_recorded_by IS NOT NULL AND its_recorded_by != ''")
            ->groupBy('its_recorded_by')
            ->orderBy('count', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // 5 งานล่าสุด
        $latestRows = $this->itsModel->orderBy('its_date', 'DESC')->limit(5)->findAll();
        $recentJobs = array_map([$this, 'formatTicket'], $latestRows);

        return $this->response->setJSON([
            'status'  => 200,
            'success' => true,
            'data'    => [
                'summary' => [
                    'total_all'           => $totalAll,
                    'total_today'         => $totalToday,
                    'total_this_month'    => $totalMonth,
                    'total_selected_year' => $totalSelectedYear,
                    'year_label'          => $yearLabel,
                ],
                'by_category' => $categories,
                'by_monthly'  => $monthly,
                'top_locations' => $locations,
                'top_recorders' => $recorders,
                'recent_jobs' => $recentJobs
            ]
        ]);
    }

    /**
     * ดึงรายการหมวดหมู่ทั้งหมดในระบบพร้อมจำนวน
     * GET /api/itsupport/categories
     */
    public function categories(): ResponseInterface
    {
        $db = \Config\Database::connect();
        $categories = $db->table('Tb_It_Support_Logs')
            ->select('its_category as name, COUNT(*) as count')
            ->where("its_category IS NOT NULL AND its_category != ''")
            ->groupBy('its_category')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status'  => 200,
            'success' => true,
            'data'    => array_map(function($c) {
                return [
                    'name'  => $c['name'],
                    'count' => (int) $c['count']
                ];
            }, $categories)
        ]);
    }

    /**
     * Format Record รายการใบงานให้อยู่ในโครงสร้างมาตรฐาน JSON พร้อม Full Image URLs
     */
    private function formatTicket(array $ticket, bool $detailed = false): array
    {
        $rawImages = !empty($ticket['its_images']) ? json_decode($ticket['its_images'], true) : [];
        if (!is_array($rawImages)) {
            $rawImages = [];
        }

        $images = [];
        foreach ($rawImages as $img) {
            if (!empty($img)) {
                $imgUrl = base_url('uploads/it_support/' . $img);
                if (strpos($imgUrl, 'localhost:9000') !== false) {
                    $imgUrl = str_replace('http://localhost:9000', 'https://localhost:9443', $imgUrl);
                }
                $images[] = [
                    'file_name'    => $img,
                    'url'          => $imgUrl,
                    'relative_url' => '/uploads/it_support/' . $img,
                ];
            }
        }

        $dateTimestamp = !empty($ticket['its_date']) ? strtotime($ticket['its_date']) : null;

        // ดึงตำแหน่งของเจ้าหน้าที่ผู้บันทึกจาก Tb_Users + Tb_Positions
        $positionName = 'ผู้ช่วยนักวิชาการคอมพิวเตอร์';
        $userPrefix = '';
        $userDivision = 'กองการศึกษา ศาสนา และวัฒนธรรม';

        $db = \Config\Database::connect();
        $userQuery = $db->table('Tb_Users')
            ->select('Tb_Users.u_prefix, Tb_Users.u_fullname, Tb_Users.u_division, Tb_Positions.pos_name')
            ->join('Tb_Positions', 'Tb_Users.u_position = Tb_Positions.pos_id', 'left');

        if (!empty($ticket['its_user_id'])) {
            $userQuery->where('Tb_Users.u_id', (int) $ticket['its_user_id']);
        } elseif (!empty($ticket['its_recorded_by'])) {
            $userQuery->like('Tb_Users.u_fullname', trim($ticket['its_recorded_by']));
        }
        $userData = $userQuery->get()->getRowArray();

        if ($userData) {
            if (!empty($userData['pos_name'])) {
                $positionName = $userData['pos_name'];
            }
            if (!empty($userData['u_prefix'])) {
                $userPrefix = $userData['u_prefix'];
            }
            if (!empty($userData['u_division'])) {
                $userDivision = $userData['u_division'];
            }
        }

        $result = [
            'id'                   => (int) $ticket['its_id'],
            'ticket_code'          => $ticket['its_ticket_code'] ?? null,
            'task'                 => $ticket['its_task'] ?? '',
            'category'             => $ticket['its_category'] ?? '',
            'location'             => $ticket['its_location'] ?? '',
            'recorded_by'          => $ticket['its_recorded_by'] ?? '',
            'recorded_by_prefix'   => $userPrefix,
            'recorded_by_position' => $positionName,
            'position'             => $positionName,
            'division'             => $userDivision,
            'user_id'              => !empty($ticket['its_user_id']) ? (int) $ticket['its_user_id'] : null,
            'date'                 => $ticket['its_date'] ?? null,
            'date_formatted'       => $dateTimestamp ? thai_date($ticket['its_date'], false) : null,
            'date_full_thai'       => $dateTimestamp ? thai_date($ticket['its_date'], true) : null,
            'date_iso'             => $dateTimestamp ? date('c', $dateTimestamp) : null,
            'image_count'          => count($images),
            'first_image'          => count($images) > 0 ? $images[0]['url'] : null,
            'images'               => $images,
            'ticket_url'           => base_url('itsupport/view/' . $ticket['its_id']),
            'created_at'           => $ticket['its_created_at'] ?? null,
            'updated_at'           => $ticket['its_updated_at'] ?? null,
        ];

        return $result;
    }
}
