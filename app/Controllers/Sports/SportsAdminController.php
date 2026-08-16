<?php

namespace App\Controllers\Sports;

use App\Controllers\BaseController;
use App\Models\Sports\SportsCategoryModel;
use App\Models\Sports\SportsTeamModel;
use App\Models\Sports\SportsMemberModel;
use App\Models\Sports\SportsCertificateModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SportsAdminController extends BaseController
{
    protected $catModel;
    protected $teamModel;
    protected $memberModel;
    protected $certModel;

    public function __construct()
    {
        $this->catModel    = new SportsCategoryModel();
        $this->teamModel   = new SportsTeamModel();
        $this->memberModel = new SportsMemberModel();
        $this->certModel   = new SportsCertificateModel();

        $this->initTables();
    }

    private function initTables()
    {
        try {
            $db = \Config\Database::connect();

            // 1. Categories Table
            if (!$db->tableExists('Tb_Sports_Categories')) {
                $db->query("CREATE TABLE IF NOT EXISTS Tb_Sports_Categories (
                    category_id INT AUTO_INCREMENT PRIMARY KEY,
                    sport_name VARCHAR(100) NOT NULL,
                    category_name VARCHAR(150) NOT NULL,
                    category_gender VARCHAR(20) NOT NULL DEFAULT 'male',
                    category_type VARCHAR(20) NOT NULL DEFAULT 'team',
                    age_min INT NULL DEFAULT 0,
                    age_max INT NULL DEFAULT 99,
                    min_players INT NOT NULL DEFAULT 1,
                    max_players INT NOT NULL DEFAULT 20,
                    min_coaches INT NOT NULL DEFAULT 1,
                    max_coaches INT NOT NULL DEFAULT 5,
                    max_teams INT NOT NULL DEFAULT 0,
                    reg_start_date DATE NULL,
                    reg_end_date DATE NULL,
                    comp_year INT NOT NULL DEFAULT 2569,
                    rules_file VARCHAR(255) NULL,
                    rules_detail TEXT NULL,
                    status VARCHAR(20) NOT NULL DEFAULT 'open',
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            }

            // 2. Teams Table
            if (!$db->tableExists('Tb_Sports_Teams')) {
                $db->query("CREATE TABLE IF NOT EXISTS Tb_Sports_Teams (
                    team_id INT AUTO_INCREMENT PRIMARY KEY,
                    team_code VARCHAR(50) NOT NULL UNIQUE,
                    category_id INT NOT NULL,
                    school_name VARCHAR(255) NOT NULL,
                    team_name VARCHAR(255) NULL,
                    district VARCHAR(100) NULL,
                    province VARCHAR(100) NULL DEFAULT 'นครสวรรค์',
                    contact_name VARCHAR(255) NOT NULL,
                    contact_phone VARCHAR(50) NOT NULL,
                    contact_email VARCHAR(100) NULL,
                    contact_line_id VARCHAR(100) NULL,
                    status VARCHAR(20) NOT NULL DEFAULT 'pending',
                    award_level VARCHAR(50) NOT NULL DEFAULT 'none',
                    admin_note TEXT NULL,
                    token VARCHAR(64) NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL,
                    INDEX idx_cat (category_id),
                    INDEX idx_school (school_name)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            } else {
                // Ensure award_level column exists on existing table
                $fields = $db->getFieldNames('Tb_Sports_Teams');
                if (!in_array('award_level', $fields)) {
                    $db->query("ALTER TABLE Tb_Sports_Teams ADD COLUMN award_level VARCHAR(50) NOT NULL DEFAULT 'none' AFTER status");
                }
            }

            // 3. Members Table (Athletes & Coaches)
            if (!$db->tableExists('Tb_Sports_Members')) {
                $db->query("CREATE TABLE IF NOT EXISTS Tb_Sports_Members (
                    member_id INT AUTO_INCREMENT PRIMARY KEY,
                    team_id INT NOT NULL,
                    category_id INT NOT NULL,
                    member_type VARCHAR(20) NOT NULL DEFAULT 'athlete',
                    prefix VARCHAR(50) NOT NULL,
                    first_name VARCHAR(100) NOT NULL,
                    last_name VARCHAR(100) NOT NULL,
                    id_card VARCHAR(20) NOT NULL,
                    birth_date DATE NULL,
                    age INT NULL,
                    gender VARCHAR(20) NOT NULL DEFAULT 'male',
                    jersey_number VARCHAR(10) NULL,
                    position VARCHAR(100) NULL,
                    photo_path VARCHAR(255) NULL,
                    doc_id_card_path VARCHAR(255) NULL,
                    doc_student_card_path VARCHAR(255) NULL,
                    doc_medical_path VARCHAR(255) NULL,
                    award_level VARCHAR(50) NOT NULL DEFAULT 'none',
                    cert_number VARCHAR(100) NULL,
                    cert_issue_date DATE NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL,
                    INDEX idx_team (team_id),
                    INDEX idx_idcard (id_card)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            }

            // 4. Certificates Table
            if (!$db->tableExists('Tb_Sports_Certificates')) {
                $db->query("CREATE TABLE IF NOT EXISTS Tb_Sports_Certificates (
                    cert_id INT AUTO_INCREMENT PRIMARY KEY,
                    category_id INT NULL DEFAULT 0,
                    target_type VARCHAR(20) NOT NULL DEFAULT 'all',
                    award_level VARCHAR(50) NOT NULL DEFAULT 'all',
                    cert_title VARCHAR(255) NOT NULL,
                    cert_template VARCHAR(255) NULL,
                    cert_config TEXT NULL,
                    cert_prefix VARCHAR(50) NULL DEFAULT 'PAO-SP-2569/',
                    signatory_name_1 VARCHAR(255) NULL,
                    signatory_pos_1 VARCHAR(255) NULL,
                    signatory_img_1 VARCHAR(255) NULL,
                    signatory_name_2 VARCHAR(255) NULL,
                    signatory_pos_2 VARCHAR(255) NULL,
                    signatory_img_2 VARCHAR(255) NULL,
                    is_active TINYINT(1) NOT NULL DEFAULT 1,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            }
        } catch (\Throwable $e) {
            log_message('error', 'Sports module initTables error: ' . $e->getMessage());
        }
    }

    private function checkAccess()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'กรุณาเข้าสู่ระบบก่อนใช้งาน');
        }

        $roles = session()->get('u_role') ?? '';
        $isSuper = (strpos($roles, 'superadmin') !== false);
        $isAdmin = (strpos($roles, 'admin') !== false) || $isSuper;
        $hasSports = (strpos($roles, 'sports') !== false);

        if (!$isAdmin && !$hasSports) {
            return redirect()->to(base_url('staff/dashboard'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบจัดการแข่งขันกีฬา');
        }

        return true;
    }

    // Dashboard
    public function index()
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) {
            return $chk;
        }

        $db = \Config\Database::connect();

        // Statistics
        $totalCategories = $this->catModel->countAllResults();
        $totalTeams      = $this->teamModel->countAllResults();
        $pendingTeams    = $this->teamModel->where('status', 'pending')->countAllResults();
        $approvedTeams   = $this->teamModel->where('status', 'approved')->countAllResults();
        $totalAthletes   = $this->memberModel->where('member_type', 'athlete')->countAllResults();
        $totalCoaches    = $this->memberModel->where('member_type !=', 'athlete')->countAllResults();

        // Recent registrations
        $recentTeams = $db->table('Tb_Sports_Teams as t')
                          ->select('t.*, c.sport_name, c.category_name, c.category_gender')
                          ->join('Tb_Sports_Categories as c', 'c.category_id = t.category_id', 'left')
                          ->orderBy('t.created_at', 'DESC')
                          ->limit(10)
                          ->get()
                          ->getResultArray();

        // Summary per sport
        $sportsSummary = $db->table('Tb_Sports_Categories as c')
                            ->select('c.sport_name, COUNT(DISTINCT c.category_id) as total_cats, COUNT(DISTINCT t.team_id) as total_teams')
                            ->join('Tb_Sports_Teams as t', 't.category_id = c.category_id', 'left')
                            ->groupBy('c.sport_name')
                            ->get()
                            ->getResultArray();

        $data = [
            'title'           => 'ระบบจัดการแข่งขันกีฬา อบจ.นครสวรรค์ เกมส์',
            'totalCategories' => $totalCategories,
            'totalTeams'      => $totalTeams,
            'pendingTeams'    => $pendingTeams,
            'approvedTeams'   => $approvedTeams,
            'totalAthletes'   => $totalAthletes,
            'totalCoaches'    => $totalCoaches,
            'recentTeams'     => $recentTeams,
            'sportsSummary'   => $sportsSummary
        ];

        return view('sports/admin/dashboard', $data);
    }

    // Categories Management
    public function categories()
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $db = \Config\Database::connect();
        $categories = $db->table('Tb_Sports_Categories as c')
                         ->select('c.*, COUNT(t.team_id) as team_count')
                         ->join('Tb_Sports_Teams as t', 't.category_id = c.category_id', 'left')
                         ->groupBy('c.category_id')
                         ->orderBy('c.sport_name', 'ASC')
                         ->orderBy('c.category_name', 'ASC')
                         ->get()
                         ->getResultArray();

        $data = [
            'title'      => 'จัดการชนิดกีฬาและรุ่นการแข่งขัน',
            'categories' => $categories
        ];

        return view('sports/admin/categories', $data);
    }

    public function categoryStore()
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $rules = [
            'sport_name'      => 'required',
            'category_name'   => 'required',
            'category_gender' => 'required',
            'min_players'     => 'required|numeric',
            'max_players'     => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้อง');
        }

        $data = [
            'sport_name'      => trim($this->request->getPost('sport_name')),
            'category_name'   => trim($this->request->getPost('category_name')),
            'category_gender' => $this->request->getPost('category_gender'),
            'category_type'   => $this->request->getPost('category_type') ?: 'team',
            'age_min'         => (int)$this->request->getPost('age_min') ?: 0,
            'age_max'         => (int)$this->request->getPost('age_max') ?: 99,
            'min_players'     => (int)$this->request->getPost('min_players'),
            'max_players'     => (int)$this->request->getPost('max_players'),
            'min_coaches'     => (int)$this->request->getPost('min_coaches') ?: 1,
            'max_coaches'     => (int)$this->request->getPost('max_coaches') ?: 5,
            'max_teams'       => (int)$this->request->getPost('max_teams') ?: 0,
            'reg_start_date'  => $this->request->getPost('reg_start_date') ?: null,
            'reg_end_date'    => $this->request->getPost('reg_end_date') ?: null,
            'comp_year'       => (int)$this->request->getPost('comp_year') ?: 2569,
            'rules_detail'    => $this->request->getPost('rules_detail'),
            'status'          => $this->request->getPost('status') ?: 'open'
        ];

        $this->catModel->insert($data);
        return redirect()->to(base_url('staff/sports/categories'))->with('success', 'เพิ่มชนิดกีฬาและรุ่นการแข่งขันสำเร็จ');
    }

    public function categoryUpdate($id)
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $cat = $this->catModel->find($id);
        if (!$cat) {
            return redirect()->to(base_url('staff/sports/categories'))->with('error', 'ไม่พบข้อมูลรุ่นการแข่งขัน');
        }

        $data = [
            'sport_name'      => trim($this->request->getPost('sport_name')),
            'category_name'   => trim($this->request->getPost('category_name')),
            'category_gender' => $this->request->getPost('category_gender'),
            'category_type'   => $this->request->getPost('category_type') ?: 'team',
            'age_min'         => (int)$this->request->getPost('age_min') ?: 0,
            'age_max'         => (int)$this->request->getPost('age_max') ?: 99,
            'min_players'     => (int)$this->request->getPost('min_players'),
            'max_players'     => (int)$this->request->getPost('max_players'),
            'min_coaches'     => (int)$this->request->getPost('min_coaches') ?: 1,
            'max_coaches'     => (int)$this->request->getPost('max_coaches') ?: 5,
            'max_teams'       => (int)$this->request->getPost('max_teams') ?: 0,
            'reg_start_date'  => $this->request->getPost('reg_start_date') ?: null,
            'reg_end_date'    => $this->request->getPost('reg_end_date') ?: null,
            'comp_year'       => (int)$this->request->getPost('comp_year') ?: 2569,
            'rules_detail'    => $this->request->getPost('rules_detail'),
            'status'          => $this->request->getPost('status') ?: 'open'
        ];

        $this->catModel->update($id, $data);
        return redirect()->to(base_url('staff/sports/categories'))->with('success', 'บันทึกการแก้ไขสำเร็จ');
    }

    public function categoryDelete($id)
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $this->catModel->delete($id);
        return redirect()->to(base_url('staff/sports/categories'))->with('success', 'ลบข้อมูลสำเร็จ');
    }

    // Toggle Category Registration Open / Closed
    public function categoryToggleStatus($id)
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $cat = $this->catModel->find($id);
        if (!$cat) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลรุ่นการแข่งขัน']);
        }

        $newStatus = ($cat['status'] === 'open') ? 'closed' : 'open';
        $this->catModel->update($id, ['status' => $newStatus]);

        return $this->response->setJSON([
            'status'     => 'success',
            'new_status' => $newStatus,
            'message'    => ($newStatus === 'open') ? 'เปิดรับสมัครเรียบร้อยแล้ว' : 'ปิดรับสมัครเรียบร้อยแล้ว'
        ]);
    }

    // Teams List & Filter
    public function teams()
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $categoryId = $this->request->getGet('category_id');
        $status     = $this->request->getGet('status');
        $search     = $this->request->getGet('search');

        $db = \Config\Database::connect();
        $builder = $db->table('Tb_Sports_Teams as t')
                      ->select('t.*, c.sport_name, c.category_name, c.category_gender, COUNT(m.member_id) as total_members')
                      ->join('Tb_Sports_Categories as c', 'c.category_id = t.category_id', 'left')
                      ->join('Tb_Sports_Members as m', 'm.team_id = t.team_id', 'left')
                      ->groupBy('t.team_id')
                      ->orderBy('t.created_at', 'DESC');

        if (!empty($categoryId)) {
            $builder->where('t.category_id', $categoryId);
        }
        if (!empty($status)) {
            $builder->where('t.status', $status);
        }
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('t.school_name', $search)
                    ->orLike('t.team_name', $search)
                    ->orLike('t.team_code', $search)
                    ->orLike('t.contact_name', $search)
                    ->groupEnd();
        }

        $teams = $builder->get()->getResultArray();
        $categories = $this->catModel->orderBy('sport_name', 'ASC')->findAll();

        $data = [
            'title'      => 'รายการทีมและนักกีฬาที่ลงทะเบียน',
            'teams'      => $teams,
            'categories' => $categories,
            'filters'    => [
                'category_id' => $categoryId,
                'status'      => $status,
                'search'      => $search
            ]
        ];

        return view('sports/admin/teams', $data);
    }

    // Team Detail
    public function teamDetail($teamId)
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $db = \Config\Database::connect();
        $team = $db->table('Tb_Sports_Teams as t')
                   ->select('t.*, c.sport_name, c.category_name, c.category_gender, c.min_players, c.max_players')
                   ->join('Tb_Sports_Categories as c', 'c.category_id = t.category_id', 'left')
                   ->where('t.team_id', $teamId)
                   ->get()
                   ->getRowArray();

        if (!$team) {
            return redirect()->to(base_url('staff/sports/teams'))->with('error', 'ไม่พบข้อมูลทีม');
        }

        $members = $this->memberModel->where('team_id', $teamId)
                                     ->orderBy('member_type', 'ASC')
                                     ->orderBy('jersey_number', 'ASC')
                                     ->findAll();

        $data = [
            'title'   => 'รายละเอียดทีม - ' . ($team['team_name'] ?: $team['school_name']),
            'team'    => $team,
            'members' => $members
        ];

        return view('sports/admin/team_detail', $data);
    }

    // Update Team Status
    public function teamUpdateStatus($teamId)
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $status    = $this->request->getPost('status');
        $adminNote = $this->request->getPost('admin_note');

        $this->teamModel->update($teamId, [
            'status'     => $status,
            'admin_note' => $adminNote
        ]);

        return redirect()->back()->with('success', 'อัปเดตสถานะทีมสำเร็จ');
    }

    // Results & Awards Management
    public function results()
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $categoryId = $this->request->getGet('category_id');
        $categories = $this->catModel->orderBy('sport_name', 'ASC')->orderBy('category_name', 'ASC')->findAll();

        if (empty($categoryId) && !empty($categories)) {
            $categoryId = $categories[0]['category_id'];
        }

        $selectedCategory = $categoryId ? $this->catModel->find($categoryId) : null;
        $teams = [];

        if ($categoryId) {
            $db = \Config\Database::connect();
            $teams = $db->table('Tb_Sports_Teams as t')
                        ->select('t.*')
                        ->where('t.category_id', $categoryId)
                        ->where('t.status', 'approved')
                        ->orderBy('t.award_level', 'ASC')
                        ->orderBy('t.school_name', 'ASC')
                        ->get()
                        ->getResultArray();

            foreach ($teams as &$team) {
                $team['members'] = $this->memberModel->where('team_id', $team['team_id'])
                                                     ->orderBy('member_type', 'ASC')
                                                     ->orderBy('jersey_number', 'ASC')
                                                     ->findAll();
            }
        }

        $data = [
            'title'            => 'บันทึกผลการแข่งขันและรางวัล - ระบบกีฬา อบจ.',
            'categories'       => $categories,
            'selectedCategory' => $selectedCategory,
            'teams'            => $teams,
            'categoryId'       => $categoryId
        ];

        return view('sports/admin/results', $data);
    }

    // Save Team Award
    public function saveTeamAward()
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $teamId     = $this->request->getPost('team_id');
        $awardLevel = $this->request->getPost('award_level');

        $this->teamModel->update($teamId, ['award_level' => $awardLevel]);

        // Auto update members in the team to match the team award
        $this->memberModel->where('team_id', $teamId)->set(['award_level' => $awardLevel])->update();

        return redirect()->back()->with('success', 'บันทึกรางวัลประจำทีมสำเร็จ');
    }

    // Save Member Award
    public function saveMemberAward()
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $memberId   = $this->request->getPost('member_id');
        $awardLevel = $this->request->getPost('award_level');

        $this->memberModel->update($memberId, ['award_level' => $awardLevel]);

        return redirect()->back()->with('success', 'บันทึกรางวัลรายบุคคลสำเร็จ');
    }

    // Certificates List & Overview
    public function certificates()
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $db = \Config\Database::connect();
        $certs = $db->table('Tb_Sports_Certificates as cert')
                    ->select('cert.*, c.sport_name, c.category_name')
                    ->join('Tb_Sports_Categories as c', 'c.category_id = cert.category_id', 'left')
                    ->orderBy('cert.cert_id', 'DESC')
                    ->get()
                    ->getResultArray();

        $categories = $this->catModel->orderBy('sport_name', 'ASC')->orderBy('category_name', 'ASC')->findAll();

        $data = [
            'title'      => 'ระบบจัดการและออกเกียรติบัตร - กีฬา อบจ.',
            'certs'      => $certs,
            'categories' => $categories
        ];

        return view('sports/admin/certificates', $data);
    }

    // Visual Certificate Coordinate Designer
    public function certDesign($certId)
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $db = \Config\Database::connect();
        $cert = $db->table('Tb_Sports_Certificates')->where('cert_id', $certId)->get()->getRowArray();

        if (!$cert) {
            return redirect()->to(base_url('staff/sports/certificates'))->with('error', 'ไม่พบข้อมูลเกียรติบัตร');
        }

        $category = null;
        if (!empty($cert['category_id'])) {
            $category = $this->catModel->find($cert['category_id']);
        }

        $certConfig = !empty($cert['cert_config']) ? json_decode($cert['cert_config'], true) : [];

        $data = [
            'title'       => 'ตั้งค่าพิกัดเกียรติบัตร - ' . $cert['cert_title'],
            'cert'        => $cert,
            'category'    => $category,
            'cert_config' => $certConfig
        ];

        return view('sports/admin/cert_design', $data);
    }

    // Save Certificate Design & Coordinates
    public function saveCertDesign($certId)
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $db = \Config\Database::connect();
        $cert = $db->table('Tb_Sports_Certificates')->where('cert_id', $certId)->get()->getRowArray();
        if (!$cert) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลเกียรติบัตร']);
        }

        $postData = $this->request->getPost();
        $currentConfig = !empty($cert['cert_config']) ? json_decode($cert['cert_config'], true) : [];

        // Handle Template Background Image Upload
        $file = $this->request->getFile('cert_template');
        $templatePath = $cert['cert_template'];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/sports/certificates';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newName = 'cert_template_' . $certId . '_' . time() . '.' . $file->getExtension();
            $file->move($uploadDir, $newName);
            $templatePath = 'uploads/sports/certificates/' . $newName;
            $currentConfig['bg_image'] = $templatePath;
        }

        // Explicitly handle all cert field checkboxes (if not present in post, set to 0)
        $certFields = ['name', 'award', 'category', 'model', 'school', 'date', 'code'];
        foreach ($certFields as $f) {
            $currentConfig["enabled_{$f}"] = $this->request->getPost("enabled_{$f}") == '1' ? 1 : 0;
            $currentConfig["parent_{$f}"]  = $this->request->getPost("parent_{$f}") ?: 'none';
        }

        // Merge incoming coordinate configuration
        foreach ($postData as $k => $v) {
            if ($k === 'csrf_test_name' || $k === 'cert_template') continue;
            $currentConfig[$k] = $v;
        }

        $db->table('Tb_Sports_Certificates')->where('cert_id', $certId)->update([
            'cert_template' => $templatePath,
            'cert_config'   => json_encode($currentConfig, JSON_UNESCAPED_UNICODE),
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status'   => 'success',
            'message'  => 'บันทึกการตั้งค่าพิกัดเกียรติบัตรสำเร็จ',
            'bg_image' => $templatePath ? base_url($templatePath) : ''
        ]);
    }

    // Create New Certificate Template
    public function certCreate()
    {
        $chk = $this->checkAccess();
        if ($chk instanceof \CodeIgniter\HTTP\ResponseInterface) return $chk;

        $db = \Config\Database::connect();
        $certTitle   = trim($this->request->getPost('cert_title'));
        $categoryId  = (int) $this->request->getPost('category_id');
        $targetType  = $this->request->getPost('target_type') ?: 'all';
        $awardLevel  = $this->request->getPost('award_level') ?: 'all';
        $certPrefix  = trim($this->request->getPost('cert_prefix') ?: 'PAO-SP-2569/');

        // Default layout coordinates
        $defaultConfig = [
            'enabled_name'       => 1,
            'x_name'             => 960,
            'y_name'             => 520,
            'size_name'          => 42,
            'align_name'         => 'center',
            'weight_name'        => 'bold',
            'color_name'         => '#0f172a',

            'enabled_school'     => 1,
            'x_school'           => 960,
            'y_school'           => 580,
            'size_school'        => 28,
            'align_school'       => 'center',
            'weight_school'      => 'regular',
            'color_school'       => '#1e293b',

            'enabled_award'      => 1,
            'x_award'            => 960,
            'y_award'            => 640,
            'size_award'         => 34,
            'align_award'        => 'center',
            'weight_award'       => 'bold',
            'color_award'        => '#b45309',

            'enabled_event'      => 1,
            'x_event'            => 960,
            'y_event'            => 700,
            'size_event'         => 24,
            'align_event'        => 'center',
            'weight_event'       => 'regular',
            'color_event'        => '#334155',

            'enabled_date'       => 1,
            'x_date'             => 960,
            'y_date'             => 780,
            'size_date'          => 22,
            'align_date'         => 'center',
            'weight_date'        => 'regular',
            'color_date'         => '#475569',

            'enabled_code'       => 1,
            'x_code'             => 1700,
            'y_code'             => 140,
            'size_code'          => 18,
            'align_code'         => 'right',
            'weight_code'        => 'regular',
            'color_code'         => '#64748b'
        ];

        $insertId = $db->table('Tb_Sports_Certificates')->insert([
            'category_id' => $categoryId,
            'target_type' => $targetType,
            'award_level' => $awardLevel,
            'cert_title'  => $certTitle,
            'cert_prefix' => $certPrefix,
            'cert_config' => json_encode($defaultConfig, JSON_UNESCAPED_UNICODE),
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s')
        ]);

        $newCertId = $db->insertID();
        return redirect()->to(base_url("staff/sports/certificates/design/{$newCertId}"))->with('success', 'สร้างเทมเพลตเกียรติบัตรสำเร็จ');
    }

    // Demo Certificate Generator (Preview with Sample Data)
    public function certDemo($certId)
    {
        $db = \Config\Database::connect();
        $cert = $db->table('Tb_Sports_Certificates')->where('cert_id', $certId)->get()->getRowArray();
        if (!$cert) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบเกียรติบัตร');

        $config = !empty($cert['cert_config']) ? json_decode($cert['cert_config'], true) : [];
        $bgPath = $config['bg_image'] ?? $cert['cert_template'];

        if (!empty($bgPath) && file_exists(FCPATH . $bgPath)) {
            $fullPath = FCPATH . $bgPath;
            $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $image = ($ext === 'png') ? @imagecreatefrompng($fullPath) : @imagecreatefromjpeg($fullPath);
        }

        if (empty($image)) {
            $image = imagecreatetruecolor(1920, 1080);
            $bgCol = imagecolorallocate($image, 248, 250, 252);
            imagefill($image, 0, 0, $bgCol);
            $borderCol = imagecolorallocate($image, 5, 150, 105);
            imagerectangle($image, 40, 40, 1880, 1040, $borderCol);
        }

        $fontBold = FCPATH . 'assets/fonts/Niramit-Bold.ttf';
        $fontReg  = FCPATH . 'assets/fonts/Niramit-Regular.ttf';
        if (!file_exists($fontBold)) $fontBold = FCPATH . 'assets/fonts/THSarabunNew-Bold.ttf';
        if (!file_exists($fontReg))  $fontReg  = FCPATH . 'assets/fonts/THSarabunNew.ttf';

        $samples = [
            'name'     => 'นายสมชาย รักดี',
            'award'    => 'ได้รับรางวัล ชนะเลิศ',
            'category' => 'กีฬาฟุตบอล (ประเภททีม)',
            'model'    => 'ประเภท รุ่นอายุไม่เกิน 15 ปี (ชาย)',
            'school'   => 'โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์',
            'date'     => 'ให้ไว้ ณ วันที่ 14 สิงหาคม พ.ศ. 2569',
            'code'     => ($cert['cert_prefix'] ?: 'PAO-SP-2569/') . 'DEMO-001'
        ];

        // Find consumed fields (fields appended to other fields)
        $consumed = [];
        foreach ($samples as $k => $v) {
            $parent = $config["parent_{$k}"] ?? 'none';
            if ($parent !== 'none') $consumed[] = $parent;
        }

        $getSampleText = function($k, &$self, $visited = []) use ($samples, $config) {
            if (in_array($k, $visited)) return '';
            $visited[] = $k;

            $text = $samples[$k] ?? '';
            $appendKey = $config["parent_{$k}"] ?? 'none';
            if ($appendKey !== 'none') {
                $text .= '   ' . $self($appendKey, $self, $visited);
            }
            return $text;
        };

        foreach ($samples as $k => $dummy) {
            $enabled = (int) ($config["enabled_{$k}"] ?? 1);
            if (!$enabled || in_array($k, $consumed)) continue;

            $text = $getSampleText($k, $getSampleText);
            if (empty($text)) continue;

            $x = (int) ($config["x_{$k}"] ?? 960);
            $y = (int) ($config["y_{$k}"] ?? 540);
            $size = (int) ($config["size_{$k}"] ?? 32);
            $align = $config["align_{$k}"] ?? 'center';
            $weight = $config["weight_{$k}"] ?? 'bold';
            $hex = $config["color_{$k}"] ?? '#000000';

            $rgb = $this->hexToRgb($hex);
            $color = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
            $font = ($weight === 'regular') ? $fontReg : $fontBold;

            if (file_exists($font)) {
                $bbox = imagettfbbox($size, 0, $font, $text);
                $drawX = $x;
                if ($align === 'center') $drawX = $x - ($bbox[2] + $bbox[0]) / 2;
                elseif ($align === 'right') $drawX = $x - $bbox[2];
                else $drawX = $x - $bbox[0];

                $drawY = $y - ($bbox[7] + $bbox[1]) / 2;
                imagettftext($image, $size, 0, (int)$drawX, (int)$drawY, $color, $font, $text);
            }
        }

        ob_start();
        imagepng($image);
        $data = ob_get_clean();
        imagedestroy($image);

        return $this->response->setHeader('Content-Type', 'image/png')->setBody($data);
    }

    private function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
}
