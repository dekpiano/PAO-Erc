<?php

namespace App\Controllers;

use App\Models\ScienceWeekRegistrationModel;
use App\Models\ScienceWeekCompetitionModel;
use App\Models\ScienceWeekScheduleModel;
use App\Models\ScienceWeekEvaluationModel;
use App\Models\ScienceWeekStudentStaffModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ScienceWeek extends BaseController
{
    protected $regModel;
    protected $compModel;
    protected $schModel;
    protected $evalModel;

    public function __construct()
    {
        $this->regModel = new ScienceWeekRegistrationModel();
        $this->compModel = new ScienceWeekCompetitionModel();
        $this->schModel = new ScienceWeekScheduleModel();
        $this->evalModel = new ScienceWeekEvaluationModel();

        // Automatically verify and update database table schema for rules, links, and custom fields
        $db = \Config\Database::connect();
        if ($db->tableExists('Tb_ScienceWeek_Competitions')) {
            if (!$db->fieldExists('comp_year', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_year INT NULL DEFAULT 2569 AFTER comp_id");
            }
            if (!$db->fieldExists('comp_banner', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_banner VARCHAR(255) NULL AFTER comp_icon");
            }
            if (!$db->fieldExists('comp_rule_file', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_rule_file VARCHAR(255) NULL AFTER comp_description");
            }
            if (!$db->fieldExists('comp_rule_link', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_rule_link VARCHAR(255) NULL AFTER comp_rule_file");
            }
            if (!$db->fieldExists('comp_group_link', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_group_link VARCHAR(255) NULL AFTER comp_rule_link");
            }
            if (!$db->fieldExists('comp_group_qr', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_group_qr VARCHAR(255) NULL AFTER comp_group_link");
            }
            if (!$db->fieldExists('comp_custom_fields', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_custom_fields TEXT NULL AFTER comp_color");
            }
            if (!$db->fieldExists('comp_member_custom_fields', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_member_custom_fields TEXT NULL AFTER comp_custom_fields");
            }
            if (!$db->fieldExists('comp_limit', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_limit INT NULL DEFAULT 0 AFTER comp_custom_fields");
            }
            if (!$db->fieldExists('comp_member_limit', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_member_limit INT NULL DEFAULT 0 AFTER comp_limit");
            }
            if (!$db->fieldExists('comp_level_limits', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_level_limits TEXT NULL AFTER comp_level");
            }
        }
        if ($db->tableExists('Tb_ScienceWeek_Registrations')) {
            if (!$db->fieldExists('reg_year', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_year INT NULL DEFAULT 2569 AFTER reg_id");
            }
            if (!$db->fieldExists('reg_custom_fields', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_custom_fields TEXT NULL AFTER reg_status");
            }
            if (!$db->fieldExists('reg_school_province', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_school_province VARCHAR(100) NULL AFTER reg_school_name");
            }
            if (!$db->fieldExists('reg_level', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_level VARCHAR(255) NULL AFTER reg_competition_type");
            }
            if (!$db->fieldExists('reg_score', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_score DECIMAL(5,2) NULL DEFAULT NULL AFTER reg_custom_fields");
            }
            if (!$db->fieldExists('reg_rank', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_rank VARCHAR(100) NULL DEFAULT NULL AFTER reg_score");
            }
            if (!$db->fieldExists('reg_checkin_status', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_checkin_status TINYINT(1) NOT NULL DEFAULT 0 AFTER reg_rank");
            }
            if (!$db->fieldExists('reg_checkin_time', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_checkin_time DATETIME NULL DEFAULT NULL AFTER reg_checkin_status");
            }
        }
        if ($db->tableExists('Tb_ScienceWeek_Schedules')) {
            if (!$db->fieldExists('sch_year', 'Tb_ScienceWeek_Schedules')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Schedules ADD COLUMN sch_year INT NULL DEFAULT 2569 AFTER sch_id");
            }
        }
        if (!$db->tableExists('Tb_ScienceWeek_Evaluations')) {
            $db->query("CREATE TABLE Tb_ScienceWeek_Evaluations (
                eval_id INT AUTO_INCREMENT PRIMARY KEY,
                eval_year INT NULL DEFAULT 2569,
                eval_name VARCHAR(255) NOT NULL,
                eval_students TEXT NULL,
                eval_gender VARCHAR(50) NULL DEFAULT NULL,
                eval_age VARCHAR(100) NULL DEFAULT NULL,
                eval_occupation VARCHAR(255) NULL DEFAULT NULL,
                eval_education_level VARCHAR(255) NULL DEFAULT NULL,
                eval_school VARCHAR(255) NULL,
                eval_province VARCHAR(100) NULL,
                eval_phone VARCHAR(20) NULL,
                eval_feedback TEXT NULL,
                eval_code VARCHAR(50) NOT NULL UNIQUE,
                eval_created_at DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        } else {
            if (!$db->fieldExists('eval_year', 'Tb_ScienceWeek_Evaluations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Evaluations ADD COLUMN eval_year INT NULL DEFAULT 2569 AFTER eval_id");
            }
            if (!$db->fieldExists('eval_students', 'Tb_ScienceWeek_Evaluations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Evaluations ADD COLUMN eval_students TEXT NULL AFTER eval_name");
            }
            if (!$db->fieldExists('eval_gender', 'Tb_ScienceWeek_Evaluations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Evaluations ADD COLUMN eval_gender VARCHAR(50) NULL DEFAULT NULL AFTER eval_students");
            }
            if (!$db->fieldExists('eval_age', 'Tb_ScienceWeek_Evaluations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Evaluations ADD COLUMN eval_age VARCHAR(100) NULL DEFAULT NULL AFTER eval_gender");
            }
            if (!$db->fieldExists('eval_occupation', 'Tb_ScienceWeek_Evaluations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Evaluations ADD COLUMN eval_occupation VARCHAR(255) NULL DEFAULT NULL AFTER eval_age");
            }
            if (!$db->fieldExists('eval_education_level', 'Tb_ScienceWeek_Evaluations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Evaluations ADD COLUMN eval_education_level VARCHAR(255) NULL DEFAULT NULL AFTER eval_occupation");
            }
        }
        
        if (!$db->tableExists('Tb_ScienceWeek_Announcements')) {
            $db->query("CREATE TABLE Tb_ScienceWeek_Announcements (
                ann_id INT AUTO_INCREMENT PRIMARY KEY,
                ann_year INT NULL DEFAULT 2569,
                ann_title VARCHAR(255) NOT NULL,
                ann_file VARCHAR(255) NOT NULL,
                ann_created_at DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    /**
     * ดึงปีการศึกษาปัจจุบันที่เปิดใช้งานอยู่
     */
    private function getActiveYear(): int
    {
        $settingsModel = new \App\Models\SettingsModel();
        return (int)($settingsModel->getVal('science_week_active_year') ?: 2569);
    }

    /**
     * ดึงปีการศึกษาที่แอดมินเลือกสลับข้อมูลดูอยู่ (สิทธิ์แอดมิน)
     */
    private function getSelectedYear(): int
    {
        $session = session();
        $selectedYear = $this->request->getGet('year');
        if ($selectedYear !== null && is_numeric($selectedYear)) {
            $selectedYear = (int)$selectedYear;
            $session->set('science_week_selected_year', $selectedYear);
        } else {
            $selectedYear = $session->get('science_week_selected_year');
        }

        if (empty($selectedYear)) {
            $selectedYear = $this->getActiveYear();
            $session->set('science_week_selected_year', $selectedYear);
        }

        return (int)$selectedYear;
    }

    /**
     * หน้าประชาสัมพันธ์ (Landing Page) ธีมวิทยาศาสตร์
     */
    public function index()
    {
        $settingsModel = new \App\Models\SettingsModel();
        $targetDate = $settingsModel->getVal('science_week_countdown') ?: '2026-08-18T09:00:00';
        $activeYear = $this->getActiveYear();

        // Query real database stats
        $db = \Config\Database::connect();

        // 1. STEAM Branches count (distinct comp_color or fixed count of STEAM = 5)
        $steamCountQuery = $db->query("SELECT COUNT(DISTINCT comp_color) as total FROM Tb_ScienceWeek_Competitions WHERE comp_year = ?", [$activeYear]);
        $steamCount = $steamCountQuery->getRowArray()['total'] ?? 0;
        if ($steamCount === 0)
            $steamCount = 5; // fallback to 5 STEAM branches

        // 2. Competition Types count
        $compCount = $this->compModel->where('comp_year', $activeYear)->countAllResults();

        // 3. Registered Teams count (where status is not rejected)
        $teamCount = $this->regModel->where('reg_year', $activeYear)->where('reg_status !=', 'rejected')->countAllResults();

        // 4. Participating Students count (sum of all members array size)
        $registrations = $this->regModel->where('reg_year', $activeYear)->where('reg_status !=', 'rejected')->findAll();
        $studentCount = 0;
        foreach ($registrations as $reg) {
            $members = json_decode($reg['reg_members'], true) ?: [];
            $studentCount += count($members);
        }

        $popularCompetitions = $db->query("
            SELECT c.*, COUNT(r.reg_id) AS reg_count
            FROM Tb_ScienceWeek_Competitions c
            LEFT JOIN Tb_ScienceWeek_Registrations r ON r.reg_competition_type = c.comp_name AND r.reg_status != 'rejected' AND r.reg_year = c.comp_year
            WHERE c.comp_year = ?
            GROUP BY c.comp_id
            ORDER BY reg_count DESC, c.comp_id ASC
            LIMIT 4
        ", [$activeYear])->getResultArray();

        $announcements = $db->table('Tb_ScienceWeek_Announcements')
                            ->where('ann_year', $activeYear)
                            ->orderBy('ann_created_at', 'DESC')
                            ->get()
                            ->getResultArray();

        $data['title'] = 'งานสัปดาห์วิทยาศาสตร์ 2026 - กองการศึกษา อบจ.นครสวรรค์ & โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ ';
        $data['countdown_date'] = $targetDate;
        $data['schedules'] = $this->schModel->where('sch_year', $activeYear)->orderBy('sch_id', 'ASC')->findAll();
        $data['announcements'] = $announcements;

        $data['stat_steam'] = $steamCount;
        $data['stat_comp'] = $compCount;
        $data['stat_team'] = $teamCount;
        $data['stat_student'] = $studentCount;
        $data['popular_competitions'] = $popularCompetitions;
        $data['active_year'] = $activeYear;

        return view('science_week/index', $data);
    }

    public function register()
    {
        $activeYear = $this->getActiveYear();
        $settingsModel = new \App\Models\SettingsModel();
        $data['title'] = 'ประเภทการแข่งขันทั้งหมด | งานสัปดาห์วิทยาศาสตร์';
        $data['registration_open'] = $settingsModel->getVal('science_week_registration_open') !== '0';
        $competitions = $this->compModel->where('comp_year', $activeYear)->orderBy('comp_id', 'ASC')->findAll();

        foreach ($competitions as &$comp) {
            $comp['reg_count'] = $this->regModel->where('reg_competition_type', $comp['comp_name'])
                ->where('reg_year', $activeYear)
                ->where('reg_status !=', 'rejected')
                ->countAllResults();
        }

        $data['competitions'] = $competitions;
        return view('science_week/register', $data);
    }

    public function registerForm()
    {
        $settingsModel = new \App\Models\SettingsModel();
        if ($settingsModel->getVal('science_week_registration_open') === '0') {
            return redirect()->to(base_url('science-week/register'))->with('error', 'ขออภัย ระบบปิดรับสมัครการแข่งขันแล้ว');
        }

        $activeYear = $this->getActiveYear();
        $type = $this->request->getGet('type');
        if (empty($type)) {
            return redirect()->to(base_url('science-week/register'));
        }

        $comp = $this->compModel->where('comp_name', $type)->where('comp_year', $activeYear)->first();
        if (!$comp) {
            return redirect()->to(base_url('science-week/register'));
        }

        // Quota Limit Check
        if ($comp && !empty($comp['comp_level_limits'])) {
            $levelLimits = json_decode($comp['comp_level_limits'], true) ?: [];
            if (!empty($levelLimits)) {
                $allFull = true;
                foreach ($levelLimits as $lvl) {
                    $activeRegLevelCount = $this->regModel->where('reg_competition_type', $comp['comp_name'])
                        ->where('reg_year', $activeYear)
                        ->where('reg_level', $lvl['level'])
                        ->where('reg_status !=', 'rejected')
                        ->countAllResults();
                    if ($lvl['limit'] == 0 || $activeRegLevelCount < $lvl['limit']) {
                        $allFull = false;
                        break;
                    }
                }
                if ($allFull) {
                    return redirect()->to(base_url('science-week/register'))->with('error', 'ขออภัย การแข่งขันนี้มีผู้สมัครครบเต็มจำนวนโควตาแล้ว (ทุกระดับชั้นเต็มแล้ว)');
                }
            }
        } else {
            $activeRegCount = $this->regModel->where('reg_competition_type', $comp['comp_name'])
                ->where('reg_year', $activeYear)
                ->where('reg_status !=', 'rejected')
                ->countAllResults();
            if (!empty($comp['comp_limit']) && $comp['comp_limit'] > 0 && $activeRegCount >= $comp['comp_limit']) {
                return redirect()->to(base_url('science-week/register'))->with('error', 'ขออภัย การแข่งขันนี้มีผู้สมัครครบเต็มจำนวนโควตาแล้ว');
            }
        }

        $data['title'] = 'ลงทะเบียนข้อมูลผู้สมัครแข่งขัน | งานสัปดาห์วิทยาศาสตร์';
        $data['competition_type'] = $type;
        $data['comp'] = $comp;
        return view('science_week/register_form', $data);
    }

    /**
     * บันทึกข้อมูลการสมัครแข่งขัน
     */
    public function store()
    {
        $settingsModel = new \App\Models\SettingsModel();
        if ($settingsModel->getVal('science_week_registration_open') === '0') {
            $msg = 'ขออภัย ระบบปิดรับสมัครการแข่งขันแล้ว';
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->to(base_url('science-week/register'))->with('error', $msg);
        }

        $activeYear = $this->getActiveYear();
        $compType = $this->request->getPost('competition_type');
        $comp = $this->compModel->where('comp_name', $compType)->where('comp_year', $activeYear)->first();
        
        // Quota Limit Check
        $selectedLevel = $this->request->getPost('reg_level');
        if ($comp && !empty($comp['comp_level_limits'])) {
            $levelLimits = json_decode($comp['comp_level_limits'], true) ?: [];
            if (!empty($levelLimits)) {
                if (empty($selectedLevel)) {
                    $msg = 'กรุณาเลือกระดับชั้นที่ต้องการสมัครแข่งขัน';
                    return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
                }
                $foundLevelLimit = null;
                foreach ($levelLimits as $lvl) {
                    if ($lvl['level'] === $selectedLevel) {
                        $foundLevelLimit = (int)$lvl['limit'];
                        break;
                    }
                }
                if ($foundLevelLimit !== null) {
                    $activeRegLevelCount = $this->regModel->where('reg_competition_type', $compType)
                        ->where('reg_year', $activeYear)
                        ->where('reg_level', $selectedLevel)
                        ->where('reg_status !=', 'rejected')
                        ->countAllResults();
                    if ($foundLevelLimit > 0 && $activeRegLevelCount >= $foundLevelLimit) {
                        $msg = "ขออภัย ระดับชั้น {$selectedLevel} มีผู้สมัครครบเต็มจำนวนโควตา ({$foundLevelLimit} ทีม) แล้ว";
                        return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
                    }
                }
            }
        } else {
            $activeRegCount = $this->regModel->where('reg_competition_type', $compType)
                ->where('reg_year', $activeYear)
                ->where('reg_status !=', 'rejected')
                ->countAllResults();
            if (!empty($comp['comp_limit']) && $comp['comp_limit'] > 0 && $activeRegCount >= $comp['comp_limit']) {
                $msg = 'ขออภัย การแข่งขันนี้มีผู้สมัครครบเต็มจำนวนโควตาแล้ว';
                return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->to(base_url('science-week/register'))->with('error', $msg);
            }
        }

        $rules = [
            'competition_type' => 'required',
            'school_name'      => 'required|min_length[3]',
            'school_province'  => 'required',
            'member_names'     => 'required',
            'advisor_names'    => 'required',
            'contact_phone'    => 'required|min_length[9]|max_length[15]',
            'contact_email'    => 'permit_empty|valid_email'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'ข้อมูลไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Clean and combine prefixes and names
        $memberPrefixesRaw = $this->request->getPost('member_prefixes') ?: [];
        $memberNamesRaw = $this->request->getPost('member_names') ?: [];
        $advisorPrefixesRaw = $this->request->getPost('advisor_prefixes') ?: [];
        $advisorNamesRaw = $this->request->getPost('advisor_names') ?: [];

        $memberCustomFieldsRaw = $this->request->getPost('member_custom_fields') ?: [];

        $members = [];
        foreach ($memberNamesRaw as $idx => $name) {
            $trimmedName = trim($name);
            if ($trimmedName !== '') {
                $prefix = trim($memberPrefixesRaw[$idx] ?? '');
                $mFields = $memberCustomFieldsRaw[$idx] ?? [];
                
                if (!empty($mFields)) {
                    $members[] = [
                        'prefix' => $prefix,
                        'name' => $trimmedName,
                        'custom_fields' => $mFields
                    ];
                } else {
                    $members[] = ($prefix !== '' ? $prefix . ' ' : '') . $trimmedName;
                }
            }
        }

        $advisors = [];
        foreach ($advisorNamesRaw as $idx => $name) {
            $trimmedName = trim($name);
            if ($trimmedName !== '') {
                $prefix = trim($advisorPrefixesRaw[$idx] ?? '');
                $advisors[] = ($prefix !== '' ? $prefix . ' ' : '') . $trimmedName;
            }
        }

        if (empty($members)) {
            $msg = 'กรุณาระบุรายชื่อสมาชิกผู้เข้าแข่งขันอย่างน้อย 1 คน';
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
        }

        if (empty($advisors)) {
            $msg = 'กรุณาระบุรายชื่ออาจารย์ที่ปรึกษาอย่างน้อย 1 คน';
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
        }

        $compType = $this->request->getPost('competition_type');
        $comp = $this->compModel->where('comp_name', $compType)->where('comp_year', $activeYear)->first();
        if (!$comp) {
            $msg = 'ไม่พบข้อมูลประเภทการแข่งขัน';
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
        }

        // Quota Limit Check
        $selectedLevel = $this->request->getPost('reg_level');
        if ($comp && !empty($comp['comp_level_limits'])) {
            $levelLimits = json_decode($comp['comp_level_limits'], true) ?: [];
            if (!empty($levelLimits)) {
                if (empty($selectedLevel)) {
                    $msg = 'กรุณาเลือกระดับชั้นที่ต้องการสมัครแข่งขัน';
                    return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
                }
                $foundLevelLimit = null;
                foreach ($levelLimits as $lvl) {
                    if ($lvl['level'] === $selectedLevel) {
                        $foundLevelLimit = (int)$lvl['limit'];
                        break;
                    }
                }
                if ($foundLevelLimit !== null) {
                    $activeRegLevelCount = $this->regModel->where('reg_competition_type', $compType)
                        ->where('reg_year', $activeYear)
                        ->where('reg_level', $selectedLevel)
                        ->where('reg_status !=', 'rejected')
                        ->countAllResults();
                    if ($foundLevelLimit > 0 && $activeRegLevelCount >= $foundLevelLimit) {
                        $msg = "ขออภัย ระดับชั้น {$selectedLevel} มีผู้สมัครครบเต็มจำนวนโควตา ({$foundLevelLimit} ทีม) แล้ว";
                        return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
                    }
                }
            }
        } else {
            $activeRegCount = $this->regModel->where('reg_competition_type', $compType)
                ->where('reg_year', $activeYear)
                ->where('reg_status !=', 'rejected')
                ->countAllResults();
            if (!empty($comp['comp_limit']) && $comp['comp_limit'] > 0 && $activeRegCount >= $comp['comp_limit']) {
                $msg = 'ขออภัย การแข่งขันนี้มีผู้สมัครครบเต็มจำนวนโควตาแล้ว';
                return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->to(base_url('science-week/register'))->with('error', $msg);
            }
        }

        // Member Count Limit Check
        if (!empty($comp['comp_member_limit']) && $comp['comp_member_limit'] > 0 && count($members) > $comp['comp_member_limit']) {
            $msg = "ขออภัย การแข่งขันนี้จำกัดจำนวนผู้เข้าแข่งขันได้สูงสุดไม่เกิน {$comp['comp_member_limit']} คนต่อทีม";
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
        }

        $customFieldsAnswers = [];
        if ($comp && !empty($comp['comp_custom_fields'])) {
            $customFieldsConfig = json_decode($comp['comp_custom_fields'], true) ?: [];
            $postCustomFields = $this->request->getPost('custom_fields') ?: [];

            foreach ($customFieldsConfig as $field) {
                $label = $field['label'];
                $type = $field['type'];
                $required = !empty($field['required']);

                if ($type === 'file') {
                    $file = $this->request->getFile("custom_fields_files.{$label}");
                    if ($file && $file->isValid() && !$file->hasMoved()) {
                        if (!is_dir(FCPATH . 'uploads/science_week/custom_files')) {
                            mkdir(FCPATH . 'uploads/science_week/custom_files', 0777, true);
                        }
                        $newName = $file->getRandomName();
                        $file->move(FCPATH . 'uploads/science_week/custom_files', $newName);
                        $customFieldsAnswers[$label] = 'uploads/science_week/custom_files/' . $newName;
                    } else {
                        if ($required) {
                            $msg = "กรุณาอัปโหลดไฟล์สำหรับคำถาม: {$label}";
                            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
                        }
                        $customFieldsAnswers[$label] = null;
                    }
                } else {
                    $answer = $postCustomFields[$label] ?? null;
                    if ($required && (is_null($answer) || trim($answer) === '')) {
                        $msg = "กรุณากรอกข้อมูลสำหรับคำถาม: {$label}";
                        return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
                    }
                    $customFieldsAnswers[$label] = !is_null($answer) ? trim($answer) : null;
                }
            }
        }

        $regCode = $this->regModel->generateRegistrationCode();

        $dataInsert = [
            'reg_year' => $activeYear,
            'reg_code' => $regCode,
            'reg_competition_type' => $this->request->getPost('competition_type'),
            'reg_level' => $this->request->getPost('reg_level') ?: null,
            'reg_school_name' => $this->request->getPost('school_name'),
            'reg_school_province' => $this->request->getPost('school_province'),
            'reg_team_name' => $this->request->getPost('team_name') ?: null,
            'reg_members' => json_encode(array_values($members), JSON_UNESCAPED_UNICODE),
            'reg_advisors' => json_encode(array_values($advisors), JSON_UNESCAPED_UNICODE),
            'reg_contact_phone' => $this->request->getPost('contact_phone'),
            'reg_contact_email' => $this->request->getPost('contact_email') ?: null,
            'reg_status' => 'pending',
            'reg_custom_fields' => !empty($customFieldsAnswers) ? json_encode($customFieldsAnswers, JSON_UNESCAPED_UNICODE) : null
        ];

        if ($this->regModel->insert($dataInsert)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'ลงทะเบียนสมัครแข่งขันสำเร็จ!',
                    'redirect' => base_url("science-week/success/{$regCode}")
                ]);
            }
            return redirect()->to(base_url("science-week/success/{$regCode}"))->with('success', 'ลงทะเบียนสมัครการแข่งขันสำเร็จแล้ว');
        }

        $msg = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง';
        return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
    }

    public function success($regCode)
    {
        $registration = $this->regModel->where('reg_code', $regCode)->first();
        if (!$registration) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบข้อมูลการลงทะเบียน');
        }

        $comp = $this->compModel->where('comp_name', $registration['reg_competition_type'])->first();

        $data['title'] = "ใบสมัครสำเร็จ {$regCode} | งานสัปดาห์วิทยาศาสตร์";
        $data['reg'] = $registration;
        $data['comp'] = $comp;

        return view('science_week/success', $data);
    }

    /**
     * ตรวจสอบความถูกต้องของสิทธิ์การใช้งานของแอดมิน/เจ้าหน้าที่
     */
    private function checkAccess($allowStudentStaffOnly = false)
    {
        $u_id = session()->get('u_id');
        if (!$u_id) {
            return redirect()->to(base_url('auth/login'))->with('error', 'กรุณาเข้าสู่ระบบก่อนใช้งาน');
        }

        $roles = session()->get('u_role') ?? '';
        
        $hasFullAccess = (strpos($roles, 'superadmin') !== false || strpos($roles, 'admin') !== false || (strpos($roles, 'science_week') !== false && strpos($roles, 'science_week_student_staff') === false));
        $hasStudentStaffOnly = (strpos($roles, 'science_week_student_staff') !== false);
        
        if ($allowStudentStaffOnly && ($hasFullAccess || $hasStudentStaffOnly)) {
            return true;
        }
        
        if (!$allowStudentStaffOnly && $hasFullAccess) {
            return true;
        }

        return redirect()->to(base_url('/'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงส่วนนี้');
    }

    /**
     * ตรวจสอบว่าเป็น superadmin หรือ admin หรือไม่
     */
    private function isFullAdmin(): bool
    {
        $roles = session()->get('u_role') ?? '';
        return strpos($roles, 'superadmin') !== false || strpos($roles, 'admin') !== false;
    }

    /**
     * ดึงรายชื่อประเภทการแข่งขันที่ผู้ใช้ปัจจุบันมีสิทธิ์เข้าถึง
     * คืนค่า null หากเป็น admin (เข้าถึงได้ทั้งหมด) หรือคืนค่า array ของชื่อรายการที่อนุญาต
     */
    private function getAllowedCompetitions(): ?array
    {
        if ($this->isFullAdmin()) {
            return null; // admin เข้าถึงได้ทั้งหมด
        }

        $uid = session()->get('u_id');
        $dbUser = (new \App\Models\UserModel())->find($uid);
        $allowedJson = $dbUser['u_science_week_competitions'] ?? '';
        $allowedComps = !empty($allowedJson) ? (json_decode($allowedJson, true) ?: []) : [];

        return $allowedComps;
    }

    /**
     * ตรวจสอบว่าผู้ใช้ปัจจุบันมีสิทธิ์เข้าถึงรายการแข่งขันนี้หรือไม่
     */
    private function canAccessCompetition(string $compName): bool
    {
        $generalRoles = [
            'ฝ่ายงานทั่วไป / ส่วนกลาง',
            'ฝ่ายลงทะเบียนและประเมินผล',
            'ฝ่ายสถานที่และโสตทัศนูปกรณ์',
            'ฝ่ายอาหารและเครื่องดื่ม',
            'ฝ่ายประชาสัมพันธ์และต้อนรับ',
            'ฝ่ายจัดนิทรรศการและกิจกรรมพิเศษ'
        ];
        if (in_array($compName, $generalRoles)) {
            return true;
        }

        // If the role is a custom manually typed helper role (not in official competitions table), allow it
        $db = \Config\Database::connect();
        $isExistingComp = $db->table('Tb_ScienceWeek_Competitions')
            ->where('comp_name', $compName)
            ->countAllResults() > 0;
            
        if (!$isExistingComp) {
            return true;
        }

        $allowed = $this->getAllowedCompetitions();
        if ($allowed === null) {
            return true; // admin เข้าถึงได้ทั้งหมด
        }
        if (empty($allowed)) {
            return true; // ไม่ได้จำกัดรายการ (ยังไม่ได้กำหนด = เข้าถึงได้ทั้งหมด)
        }
        return in_array($compName, $allowed);
    }

    /**
     * ระบบจัดการรายชื่อผู้สมัคร (Staff/Admin)
     */
    public function adminAnnouncements()
    {
        $access = $this->checkAccess();
        if ($access !== true) return $access;
        if (!$this->isFullAdmin()) {
            return redirect()->to(base_url('science-week/staff'))->with('error', 'เฉพาะผู้ดูแลระบบ (Admin) เท่านั้นที่สามารถจัดการไฟล์ประกาศได้');
        }

        $selectedYear = $this->getSelectedYear();
        $db = \Config\Database::connect();
        
        $announcements = $db->table('Tb_ScienceWeek_Announcements')
                            ->where('ann_year', $selectedYear)
                            ->orderBy('ann_created_at', 'DESC')
                            ->get()
                            ->getResultArray();

        $data = [
            'title' => "จัดการไฟล์ประกาศ | งานสัปดาห์วิทยาศาสตร์",
            'announcements' => $announcements,
            'fullname' => session()->get('u_fullname'),
            'selected_year' => $selectedYear
        ];

        return view('science_week/admin_announcements', $data);
    }

    public function adminAnnouncementStore()
    {
        $access = $this->checkAccess();
        if ($access !== true) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        if (!$this->isFullAdmin()) return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่มีสิทธิ์เข้าถึง']);

        $rules = [
            'ann_title' => 'required',
            'ann_file' => 'uploaded[ann_file]|max_size[ann_file,10240]|ext_in[ann_file,pdf,jpg,jpeg,png]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ข้อมูลไม่ถูกต้อง หรือไฟล์อาจมีขนาดเกิน 10MB (รองรับ PDF, JPG, PNG)']);
        }

        $title = $this->request->getPost('ann_title');
        $file = $this->request->getFile('ann_file');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!is_dir(FCPATH . 'uploads/science_week/announcements')) {
                mkdir(FCPATH . 'uploads/science_week/announcements', 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/science_week/announcements', $newName);
            
            $db = \Config\Database::connect();
            $db->table('Tb_ScienceWeek_Announcements')->insert([
                'ann_year' => $this->getSelectedYear(),
                'ann_title' => $title,
                'ann_file' => 'uploads/science_week/announcements/' . $newName,
                'ann_created_at' => date('Y-m-d H:i:s')
            ]);
            
            return $this->response->setJSON(['status' => 'success', 'message' => 'อัปโหลดไฟล์ประกาศสำเร็จ']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์']);
    }

    public function adminAnnouncementDelete($id)
    {
        $access = $this->checkAccess();
        if ($access !== true) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        if (!$this->isFullAdmin()) return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่มีสิทธิ์เข้าถึง']);

        $db = \Config\Database::connect();
        $ann = $db->table('Tb_ScienceWeek_Announcements')->where('ann_id', $id)->get()->getRowArray();
        
        if ($ann) {
            if (file_exists(FCPATH . $ann['ann_file'])) {
                unlink(FCPATH . $ann['ann_file']);
            }
            $db->table('Tb_ScienceWeek_Announcements')->where('ann_id', $id)->delete();
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบไฟล์ประกาศสำเร็จ']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบไฟล์ประกาศ']);
    }

    /**
     * ระบบสแกนเช็คอินหน้างาน (Fast Check-in)
     */
    public function checkinView($reg_code)
    {
        $access = $this->checkAccess();
        if ($access !== true) return $access;

        $reg = $this->regModel->where('reg_code', $reg_code)->first();
        if (!$reg) {
            return redirect()->to(base_url('science-week/staff'))->with('error', 'ไม่พบรหัสใบสมัครนี้');
        }

        $data['title'] = "ระบบเช็คอินหน้างาน | งานสัปดาห์วิทยาศาสตร์";
        $data['reg'] = $reg;
        $data['fullname'] = session()->get('u_fullname');

        return view('science_week/checkin', $data);
    }

    public function checkinProcess($reg_code)
    {
        $access = $this->checkAccess();
        if ($access !== true) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $reg = $this->regModel->where('reg_code', $reg_code)->first();
        if (!$reg) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้สมัคร']);
        }

        // สามารถทำ Toggle ได้ (ถ้าเช็คอินแล้วให้ยกเลิก / ถ้ายกเลิกแล้วให้เช็คอิน)
        // หรือจะรับค่าเป็นพารามิเตอร์ post ก็ได้ ในที่นี้จะรับ action หรือแค่ toggle
        $action = $this->request->getPost('action');
        
        $newStatus = ($action === 'cancel') ? 0 : 1;
        $newTime = ($action === 'cancel') ? null : date('Y-m-d H:i:s');

        $this->regModel->update($reg['reg_id'], [
            'reg_checkin_status' => $newStatus,
            'reg_checkin_time' => $newTime
        ]);

        $msg = ($newStatus === 1) ? 'บันทึกการรายงานตัวสำเร็จ' : 'ยกเลิกการรายงานตัวแล้ว';
        return $this->response->setJSON(['status' => 'success', 'message' => $msg, 'checkin_status' => $newStatus]);
    }

    public function adminIndex()
    {
        $roles = session()->get('u_role') ?? '';
        $hasFullAccess = (strpos($roles, 'superadmin') !== false || strpos($roles, 'admin') !== false || (strpos($roles, 'science_week') !== false && strpos($roles, 'science_week_student_staff') === false));
        $hasStudentStaffOnly = (strpos($roles, 'science_week_student_staff') !== false && !$hasFullAccess);

        if ($hasStudentStaffOnly) {
            return redirect()->to(base_url('science-week/staff/student-staff'));
        }

        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $selectedYear = $this->getSelectedYear();
        $searchTerm = $this->request->getGet('search');
        $compType = $this->request->getGet('competition_type');
        $status = $this->request->getGet('status');
        $level = $this->request->getGet('level');
        $sortBy = $this->request->getGet('sort_by') ?: 'reg_created_at';
        $sortOrder = $this->request->getGet('sort_order') ?: 'DESC';

        $allowedSortFields = ['reg_code', 'reg_school_name', 'reg_status', 'reg_checkin_status', 'reg_created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'reg_created_at';
        }
        if (!in_array(strtoupper($sortOrder), ['ASC', 'DESC'])) {
            $sortOrder = 'DESC';
        }

        $query = $this->regModel->where('reg_year', $selectedYear);

        // กรองตามสิทธิ์ของ staff (ไม่ใช่ admin)
        $allowedComps = $this->getAllowedCompetitions();
        if ($allowedComps !== null && !empty($allowedComps)) {
            $query = $query->whereIn('reg_competition_type', $allowedComps);
        }

        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                ->like('reg_school_name', $searchTerm)
                ->orLike('reg_team_name', $searchTerm)
                ->orLike('reg_code', $searchTerm)
                ->orLike('reg_members', $searchTerm)
                ->groupEnd();
        }

        if (!empty($compType)) {
            $query = $query->where('reg_competition_type', $compType);
        }

        if (!empty($status)) {
            $query = $query->where('reg_status', $status);
        }

        if (!empty($level)) {
            $query = $query->where('reg_level', $level);
        }

        // ดึงรายการแข่งขันเฉพาะที่ staff มีสิทธิ์ (สำหรับ dropdown ตัวกรอง)
        $allComps = $this->compModel->where('comp_year', $selectedYear)->orderBy('comp_id', 'ASC')->findAll();
        if ($allowedComps !== null && !empty($allowedComps)) {
            $allComps = array_values(array_filter($allComps, fn($c) => in_array($c['comp_name'], $allowedComps)));
        }

        // คำนวณจำนวนผู้สมัครของแต่ละประเภทการแข่งขันในปีปัจจุบัน โดยแยกตามระดับชั้น
        $compStats = [];
        $db = \Config\Database::connect();
        foreach ($allComps as $c) {
            $levels = [];
            
            // ตรวจสอบว่ากิจกรรมนี้มีการกำหนดระดับชั้นและโควตาเฉพาะระดับชั้นไว้หรือไม่ (จาก comp_level_limits หรือ comp_level)
            $levelLimits = !empty($c['comp_level_limits']) ? (json_decode($c['comp_level_limits'], true) ?: []) : [];
            
            if (!empty($levelLimits)) {
                // หากมีการกำหนด Level Limit แยกห้อง/ระดับชั้น
                foreach ($levelLimits as $lvl) {
                    $lvlName = $lvl['level'];
                    $lvlLimit = (int)$lvl['limit'];
                    
                    $totalReg = $db->table('Tb_ScienceWeek_Registrations')
                        ->where('reg_year', $selectedYear)
                        ->where('reg_competition_type', $c['comp_name'])
                        ->where('reg_level', $lvlName)
                        ->countAllResults();

                    $approvedReg = $db->table('Tb_ScienceWeek_Registrations')
                        ->where('reg_year', $selectedYear)
                        ->where('reg_competition_type', $c['comp_name'])
                        ->where('reg_level', $lvlName)
                        ->whereIn('reg_status', ['approved', 'approved_reserve'])
                        ->countAllResults();

                    $pendingReg = $db->table('Tb_ScienceWeek_Registrations')
                        ->where('reg_year', $selectedYear)
                        ->where('reg_competition_type', $c['comp_name'])
                        ->where('reg_level', $lvlName)
                        ->where('reg_status', 'pending')
                        ->countAllResults();
                        
                    $levels[] = [
                        'level_name' => $lvlName,
                        'total' => $totalReg,
                        'approved' => $approvedReg,
                        'pending' => $pendingReg,
                        'limit' => $lvlLimit
                    ];
                }
            } else {
                // หากไม่มีการแยกโควตาระดับชั้นในระบบ ให้ดึงข้อมูลกลุ่มภาพรวมรายการ
                $totalReg = $db->table('Tb_ScienceWeek_Registrations')
                    ->where('reg_year', $selectedYear)
                    ->where('reg_competition_type', $c['comp_name'])
                    ->countAllResults();

                $approvedReg = $db->table('Tb_ScienceWeek_Registrations')
                    ->where('reg_year', $selectedYear)
                    ->where('reg_competition_type', $c['comp_name'])
                    ->whereIn('reg_status', ['approved', 'approved_reserve'])
                    ->countAllResults();

                $pendingReg = $db->table('Tb_ScienceWeek_Registrations')
                    ->where('reg_year', $selectedYear)
                    ->where('reg_competition_type', $c['comp_name'])
                    ->where('reg_status', 'pending')
                    ->countAllResults();

                $levels[] = [
                    'level_name' => $c['comp_level'] ?: 'ทุกระดับชั้น',
                    'total' => $totalReg,
                    'approved' => $approvedReg,
                    'pending' => $pendingReg,
                    'limit' => $c['comp_limit'] ?? 0
                ];
            }

            $compStats[] = [
                'comp_name' => $c['comp_name'],
                'comp_color' => $c['comp_color'] ?: '#6366f1',
                'comp_icon' => $c['comp_icon'] ?: 'award',
                'levels' => $levels
            ];
        }

        // Get available levels for filter
        $levelsQuery = $db->table('Tb_ScienceWeek_Registrations')
            ->select('reg_level')
            ->where('reg_year', $selectedYear)
            ->where('reg_level IS NOT NULL')
            ->where('reg_level !=', '');

        // กรองตามสิทธิ์ของ staff (ไม่ใช่ admin)
        if ($allowedComps !== null && !empty($allowedComps)) {
            $levelsQuery = $levelsQuery->whereIn('reg_competition_type', $allowedComps);
        }

        if (!empty($compType)) {
            $levelsQuery = $levelsQuery->where('reg_competition_type', $compType);
        }

        $levelsQuery = $levelsQuery->groupBy('reg_level')
            ->orderBy('reg_level', 'ASC')
            ->get()
            ->getResultArray();
        $availableLevels = array_column($levelsQuery, 'reg_level');

        $data['title'] = "จัดการผู้สมัครแข่งขัน งานสัปดาห์วิทยาศาสตร์ | อบจ.นครสวรรค์";
        $data['registrations'] = $query->orderBy($sortBy, $sortOrder)->paginate(20, 'default');
        $data['pager'] = $this->regModel->pager;

        $data['search'] = $searchTerm;
        $data['compType_active'] = $compType;
        $data['status_active'] = $status;
        $data['level_active'] = $level;
        $data['available_levels'] = $availableLevels;
        $data['sort_by'] = $sortBy;
        $data['sort_order'] = $sortOrder;
        $data['fullname'] = session()->get('u_fullname');
        $data['competitions'] = $allComps;
        $data['competition_stats'] = $compStats;
        $data['selected_year'] = $selectedYear;

        return view('science_week/admin_index', $data);
    }

    /**
     * ระบบจัดการผลคะแนนและจัดอันดับรางวัล (Staff/Admin)
     */
    public function adminRanking()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $selectedYear = $this->getSelectedYear();
        $searchTerm = $this->request->getGet('search');
        $compType = $this->request->getGet('competition_type');

        // Only rank approved registrations of selected year
        $query = $this->regModel->where('reg_year', $selectedYear)->whereIn('reg_status', ['approved', 'approved_reserve']);

        // กรองตามสิทธิ์ของ staff
        $allowedComps = $this->getAllowedCompetitions();
        if ($allowedComps !== null && !empty($allowedComps)) {
            $query = $query->whereIn('reg_competition_type', $allowedComps);
        }

        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                ->like('reg_school_name', $searchTerm)
                ->orLike('reg_team_name', $searchTerm)
                ->orLike('reg_code', $searchTerm)
                ->orLike('reg_members', $searchTerm)
                ->groupEnd();
        }

        if (!empty($compType)) {
            $query = $query->where('reg_competition_type', $compType);
        }

        // ดึงรายการแข่งขันเฉพาะที่ staff มีสิทธิ์
        $allComps = $this->compModel->where('comp_year', $selectedYear)->orderBy('comp_id', 'ASC')->findAll();
        if ($allowedComps !== null && !empty($allowedComps)) {
            $allComps = array_values(array_filter($allComps, fn($c) => in_array($c['comp_name'], $allowedComps)));
        }

        $data['title'] = "จัดการผลคะแนนและอันดับรางวัล | อบจ.นครสวรรค์";
        $registrations = [];
        if (!empty($searchTerm) || !empty($compType)) {
            $registrations = $query->orderBy('reg_competition_type', 'ASC')
                                   ->orderBy('CASE WHEN reg_rank IS NULL OR reg_rank = \'\' THEN 1 ELSE 0 END', 'ASC')
                                   ->orderBy('reg_score', 'DESC')
                                   ->orderBy('reg_id', 'ASC')
                                   ->findAll();
        }
        $data['registrations'] = $registrations;

        $data['search'] = $searchTerm;
        $data['compType_active'] = $compType;
        $data['fullname'] = session()->get('u_fullname');
        $data['competitions'] = $allComps;
        $data['selected_year'] = $selectedYear;

        $settingsModel = new \App\Models\SettingsModel();
        $data['publish_results'] = $settingsModel->getVal('science_week_publish_results') === '1';

        return view('science_week/admin_ranking', $data);
    }

    /**
     * อัปเดตสถานะการสมัครแข่งขัน (เช่น อนุมัติ / ปฏิเสธ / รอตรวจสอบ)
     */
    public function updateStatus($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        // ตรวจสอบสิทธิ์ตามรายการแข่งขันที่ผู้ใช้ดูแล
        $reg = $this->regModel->find($id);
        if (!$reg) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้สมัครนี้']);
        }
        if (!$this->canAccessCompetition($reg['reg_competition_type'])) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์จัดการรายการแข่งขันนี้']);
        }

        $status = $this->request->getPost('status');
        if (!in_array($status, ['pending', 'approved', 'approved_reserve', 'rejected'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'สถานะไม่ถูกต้อง']);
        }

        if ($this->regModel->update($id, ['reg_status' => $status])) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'อัปเดตสถานะการสมัครเรียบร้อยแล้ว'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตข้อมูลได้']);
    }

    /**
     * หน้าจอแก้ไขข้อมูลผู้สมัคร (Staff/Admin)
     */
    public function adminEdit($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $reg = $this->regModel->find($id);
        if (!$reg) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบข้อมูลผู้สมัครนี้');
        }

        // ตรวจสอบสิทธิ์ตามรายการแข่งขัน
        if (!$this->canAccessCompetition($reg['reg_competition_type'])) {
            return redirect()->to(base_url('science-week/staff'))->with('error', 'คุณไม่มีสิทธิ์แก้ไขข้อมูลรายการแข่งขันนี้');
        }

        $comp = $this->compModel->where('comp_name', $reg['reg_competition_type'])->first();

        $data['title'] = "แก้ไขข้อมูลผู้สมัคร {$reg['reg_code']} | อบจ.นครสวรรค์";
        $data['reg'] = $reg;
        $data['comp'] = $comp;
        $data['fullname'] = session()->get('u_fullname');

        return view('science_week/admin_edit', $data);
    }

    /**
     * อัปเดตข้อมูลผู้สมัคร (Staff/Admin)
     */
    public function adminUpdate($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $reg = $this->regModel->find($id);
        if (!$reg) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบข้อมูลผู้สมัครนี้');
        }

        // ตรวจสอบสิทธิ์ตามรายการแข่งขัน
        if (!$this->canAccessCompetition($reg['reg_competition_type'])) {
            return redirect()->to(base_url('science-week/staff'))->with('error', 'คุณไม่มีสิทธิ์แก้ไขข้อมูลรายการแข่งขันนี้');
        }

        $rules = [
            'school_name'      => 'required|min_length[3]',
            'school_province'  => 'required',
            'member_names'     => 'required',
            'advisor_names'    => 'required',
            'contact_phone'    => 'required|min_length[9]|max_length[15]',
            'contact_email'    => 'permit_empty|valid_email',
            'status'           => 'required|in_list[pending,approved,rejected]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Clean and combine prefixes and names
        $memberPrefixesRaw = $this->request->getPost('member_prefixes') ?: [];
        $memberNamesRaw = $this->request->getPost('member_names') ?: [];
        $advisorPrefixesRaw = $this->request->getPost('advisor_prefixes') ?: [];
        $advisorNamesRaw = $this->request->getPost('advisor_names') ?: [];

        $memberCustomFieldsRaw = $this->request->getPost('member_custom_fields') ?: [];

        $members = [];
        foreach ($memberNamesRaw as $idx => $name) {
            $trimmedName = trim($name);
            if ($trimmedName !== '') {
                $prefix = trim($memberPrefixesRaw[$idx] ?? '');
                $mFields = $memberCustomFieldsRaw[$idx] ?? [];
                
                if (!empty($mFields)) {
                    $members[] = [
                        'prefix' => $prefix,
                        'name' => $trimmedName,
                        'custom_fields' => $mFields
                    ];
                } else {
                    $members[] = ($prefix !== '' ? $prefix . ' ' : '') . $trimmedName;
                }
            }
        }

        $advisors = [];
        foreach ($advisorNamesRaw as $idx => $name) {
            $trimmedName = trim($name);
            if ($trimmedName !== '') {
                $prefix = trim($advisorPrefixesRaw[$idx] ?? '');
                $advisors[] = ($prefix !== '' ? $prefix . ' ' : '') . $trimmedName;
            }
        }

        if (empty($members)) {
            return redirect()->back()->withInput()->with('error', 'กรุณาระบุรายชื่อสมาชิกผู้เข้าแข่งขันอย่างน้อย 1 คน');
        }

        if (empty($advisors)) {
            return redirect()->back()->withInput()->with('error', 'กรุณาระบุรายชื่ออาจารย์ที่ปรึกษาอย่างน้อย 1 คน');
        }

        $comp = $this->compModel->where('comp_name', $reg['reg_competition_type'])->first();

        // Member Count Limit Check
        if ($comp && !empty($comp['comp_member_limit']) && $comp['comp_member_limit'] > 0 && count($members) > $comp['comp_member_limit']) {
            return redirect()->back()->withInput()->with('error', "ขออภัย การแข่งขันนี้จำกัดจำนวนผู้เข้าแข่งขันได้สูงสุดไม่เกิน {$comp['comp_member_limit']} คนต่อทีม");
        }

        // Handle Custom Fields
        $customFieldsAnswers = [];
        if ($reg['reg_custom_fields']) {
            $customFieldsAnswers = json_decode($reg['reg_custom_fields'], true) ?: [];
        }

        if ($comp && !empty($comp['comp_custom_fields'])) {
            $customFieldsConfig = json_decode($comp['comp_custom_fields'], true) ?: [];
            $postCustomFields = $this->request->getPost('custom_fields') ?: [];

            foreach ($customFieldsConfig as $field) {
                $label = $field['label'];
                $type = $field['type'];
                $required = !empty($field['required']);

                if ($type === 'file') {
                    $file = $this->request->getFile("custom_fields_files.{$label}");
                    if ($file && $file->isValid() && !$file->hasMoved()) {
                        if (!is_dir(FCPATH . 'uploads/science_week/custom_files')) {
                            mkdir(FCPATH . 'uploads/science_week/custom_files', 0777, true);
                        }
                        $newName = $file->getRandomName();
                        $file->move(FCPATH . 'uploads/science_week/custom_files', $newName);
                        
                        // Delete old file if exists
                        $oldFile = $customFieldsAnswers[$label] ?? null;
                        if ($oldFile && file_exists(FCPATH . $oldFile)) {
                            @unlink(FCPATH . $oldFile);
                        }
                        
                        $customFieldsAnswers[$label] = 'uploads/science_week/custom_files/' . $newName;
                    }
                    // If no new file is uploaded, keep the old file path. If it's required and nothing is there:
                    elseif ($required && empty($customFieldsAnswers[$label])) {
                        return redirect()->back()->withInput()->with('error', "กรุณาอัปโหลดไฟล์สำหรับคำถาม: {$label}");
                    }
                } else {
                    $answer = $postCustomFields[$label] ?? null;
                    if ($required && (is_null($answer) || trim($answer) === '')) {
                        return redirect()->back()->withInput()->with('error', "กรุณากรอกข้อมูลสำหรับคำถาม: {$label}");
                    }
                    $customFieldsAnswers[$label] = !is_null($answer) ? trim($answer) : null;
                }
            }
        }

        $dataUpdate = [
            'reg_school_name' => $this->request->getPost('school_name'),
            'reg_school_province' => $this->request->getPost('school_province'),
            'reg_team_name' => $this->request->getPost('team_name') ?: null,
            'reg_level' => $this->request->getPost('reg_level') ?: null,
            'reg_members' => json_encode(array_values($members), JSON_UNESCAPED_UNICODE),
            'reg_advisors' => json_encode(array_values($advisors), JSON_UNESCAPED_UNICODE),
            'reg_contact_phone' => $this->request->getPost('contact_phone'),
            'reg_contact_email' => $this->request->getPost('contact_email') ?: null,
            'reg_status' => $this->request->getPost('status'),
            'reg_custom_fields' => !empty($customFieldsAnswers) ? json_encode($customFieldsAnswers, JSON_UNESCAPED_UNICODE) : null
        ];

        if ($this->regModel->update($id, $dataUpdate)) {
            $queryString = $this->request->getUri()->getQuery();
            return redirect()->to(base_url('science-week/staff' . ($queryString ? '?' . $queryString : '')) . '#reg-row-' . $id)->with('success', 'แก้ไขข้อมูลผู้สมัครสำเร็จเรียบร้อยแล้ว');
        }

        return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง');
    }

    /**
     * ส่งออกผู้สมัครเข้าร่วมการแข่งขันเป็น Excel
     */
    public function export()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $compType = $this->request->getGet('competition_type');
        $status = $this->request->getGet('status');
        $searchTerm = $this->request->getGet('search');
        $level = $this->request->getGet('level');

        $selectedYear = $this->getSelectedYear();
        $query = $this->regModel->where('reg_year', $selectedYear);

        // กรองตามสิทธิ์ของ staff
        $allowedComps = $this->getAllowedCompetitions();
        if ($allowedComps !== null && !empty($allowedComps)) {
            $query = $query->whereIn('reg_competition_type', $allowedComps);
        }

        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                ->like('reg_school_name', $searchTerm)
                ->orLike('reg_team_name', $searchTerm)
                ->orLike('reg_code', $searchTerm)
                ->groupEnd();
        }

        if (!empty($compType)) {
            $query = $query->where('reg_competition_type', $compType);
        }

        if (!empty($status)) {
            $query = $query->where('reg_status', $status);
        }

        if (!empty($level)) {
            $query = $query->where('reg_level', $level);
        }

        $results = $query->orderBy('reg_created_at', 'ASC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'รายชื่อผู้สมัครเข้าร่วมแข่งขันกิจกรรมวันสัปดาห์วิทยาศาสตร์');
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A2', 'พิมพ์เมื่อวันที่: ' . date('d/m/Y H:i') . ' น.');
        $sheet->mergeCells('A2:N2');

        $sheet->setCellValue('A4', 'ลำดับ');
        $sheet->setCellValue('B4', 'รหัสใบสมัคร');
        $sheet->setCellValue('C4', 'ประเภทการแข่งขัน');
        $sheet->setCellValue('D4', 'ระดับชั้น');
        $sheet->setCellValue('E4', 'ชื่อโรงเรียน');
        $sheet->setCellValue('F4', 'จังหวัด');
        $sheet->setCellValue('G4', 'ชื่อทีม');
        $sheet->setCellValue('H4', 'สมาชิกในทีม');
        $sheet->setCellValue('I4', 'ครูผู้ควบคุม/ที่ปรึกษา');
        $sheet->setCellValue('J4', 'เบอร์โทรติดต่อ');
        $sheet->setCellValue('K4', 'สถานะการสมัคร');
        $sheet->setCellValue('L4', 'คะแนน');
        $sheet->setCellValue('M4', 'รางวัลที่ได้รับ');
        $sheet->setCellValue('N4', 'ข้อมูลเพิ่มเติม (Custom Fields)');

        $rowIdx = 5;
        $i = 1;
        foreach ($results as $reg) {
            $memberData = json_decode($reg['reg_members'], true) ?? [];
            $memberNamesFormatted = [];
            foreach ($memberData as $m) {
                if (is_array($m)) {
                    $prefix = trim($m['prefix'] ?? '');
                    $name = trim($m['name'] ?? '');
                    $mText = ($prefix !== '' ? $prefix . ' ' : '') . $name;
                    if (!empty($m['custom_fields'])) {
                        $cfStr = [];
                        foreach ($m['custom_fields'] as $cfKey => $cfVal) {
                            if ($cfVal !== '') {
                                $cfStr[] = "{$cfKey}: {$cfVal}";
                            }
                        }
                        if (!empty($cfStr)) {
                            $mText .= ' (' . implode(', ', $cfStr) . ')';
                        }
                    }
                    $memberNamesFormatted[] = $mText;
                } else {
                    $memberNamesFormatted[] = $m;
                }
            }
            $members = implode(', ', $memberNamesFormatted);
            $advisors = implode(', ', json_decode($reg['reg_advisors'], true) ?? []);

            $statusText = 'รอการตรวจสอบ';
            if ($reg['reg_status'] == 'approved')
                $statusText = 'อนุมัติแล้ว (ทีมจริง)';
            if ($reg['reg_status'] == 'approved_reserve')
                $statusText = 'อนุมัติแล้ว (ทีมสำรอง)';
            if ($reg['reg_status'] == 'rejected')
                $statusText = 'ปฏิเสธ/ไม่ผ่าน';

            $customAnswers = json_decode($reg['reg_custom_fields'], true) ?: [];
            $customTextArr = [];
            foreach ($customAnswers as $q => $a) {
                if (!empty($a)) {
                    if (strpos($a, 'uploads/science_week/') === 0) {
                        $customTextArr[] = "{$q}: " . base_url($a);
                    } else {
                        $customTextArr[] = "{$q}: {$a}";
                    }
                }
            }
            $customText = implode(" | ", $customTextArr);

            $sheet->setCellValue('A' . $rowIdx, $i++);
            $sheet->setCellValue('B' . $rowIdx, $reg['reg_code']);
            $sheet->setCellValue('C' . $rowIdx, $reg['reg_competition_type']);
            $sheet->setCellValue('D' . $rowIdx, $reg['reg_level'] ?: '-');
            $sheet->setCellValue('E' . $rowIdx, $reg['reg_school_name']);
            $sheet->setCellValue('F' . $rowIdx, $reg['reg_school_province'] ?: '-');
            $sheet->setCellValue('G' . $rowIdx, $reg['reg_team_name'] ?: '-');
            $sheet->setCellValue('H' . $rowIdx, $members);
            $sheet->setCellValue('I' . $rowIdx, $advisors);
            $sheet->setCellValue('J' . $rowIdx, $reg['reg_contact_phone']);
            $sheet->setCellValue('K' . $rowIdx, $statusText);
            $sheet->setCellValue('L' . $rowIdx, $reg['reg_score'] !== null ? $reg['reg_score'] : '-');
            $sheet->setCellValue('M' . $rowIdx, $reg['reg_rank'] ?: '-');
            $sheet->setCellValue('N' . $rowIdx, $customText ?: '-');
            $rowIdx++;
        }

        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "ScienceWeek_Registrations_" . date('Ymd_His') . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }


    /**
     * competition index with role-based filter
     */
    public function compIndex()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $selectedYear = $this->getSelectedYear();

        $allComps = $this->compModel->where('comp_year', $selectedYear)->orderBy('comp_id', 'ASC')->findAll();

        // กรองตามสิทธิ์ของ staff (ใช้ helper method กลาง)
        $allowedComps = $this->getAllowedCompetitions();
        if ($allowedComps !== null && !empty($allowedComps)) {
            $allComps = array_values(array_filter($allComps, fn($c) => in_array($c['comp_name'], $allowedComps)));
        }

        $data['title']         = 'จัดการประเภทการแข่งขัน | งานสัปดาห์วิทยาศาสตร์';
        $data['competitions']  = $allComps;
        $data['is_admin']      = $this->isFullAdmin();
        $data['fullname']      = session()->get('u_fullname');
        $data['selected_year'] = $selectedYear;

        return view('science_week/competitions_index', $data);
    }


    /**
     * หน้าจอเพิ่มประเภทการแข่งขัน
     */
    public function compCreate()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $data['title'] = 'เพิ่มประเภทการแข่งขัน | งานสัปดาห์วิทยาศาสตร์';
        $data['comp'] = null;
        $data['selected_year'] = $this->getSelectedYear();
        $data['fullname'] = session()->get('u_fullname');

        return view('science_week/competitions_form', $data);
    }

    /**
     * บันทึกประเภทการแข่งขันใหม่
     */
    public function compStore()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $rules = [
            'comp_name' => 'required|min_length[3]|max_length[255]',
            'comp_icon' => 'required',
            'comp_level' => 'required',
            'comp_description' => 'permit_empty',
            'comp_color' => 'required',
            'comp_limit' => 'permit_empty|integer',
            'comp_member_limit' => 'permit_empty|integer',
            'comp_rule_file' => 'max_size[comp_rule_file,10240]|ext_in[comp_rule_file,pdf,doc,docx,zip]',
            'comp_group_qr' => 'max_size[comp_group_qr,10240]|is_image[comp_group_qr]',
            'comp_banner' => 'max_size[comp_banner,10240]|is_image[comp_banner]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $selectedYear = $this->getSelectedYear();

        $ruleFilePath = null;
        $ruleFile = $this->request->getFile('comp_rule_file');
        if ($ruleFile && $ruleFile->isValid() && !$ruleFile->hasMoved()) {
            if (!is_dir(FCPATH . 'uploads/science_week/rules')) {
                mkdir(FCPATH . 'uploads/science_week/rules', 0777, true);
            }
            $newName = $ruleFile->getRandomName();
            $ruleFile->move(FCPATH . 'uploads/science_week/rules', $newName);
            $ruleFilePath = 'uploads/science_week/rules/' . $newName;
        }

        $groupQrPath = null;
        $groupQrFile = $this->request->getFile('comp_group_qr');
        if ($groupQrFile && $groupQrFile->isValid() && !$groupQrFile->hasMoved()) {
            if (!is_dir(FCPATH . 'uploads/science_week/qr')) {
                mkdir(FCPATH . 'uploads/science_week/qr', 0777, true);
            }
            $newName = $groupQrFile->getRandomName();
            $groupQrFile->move(FCPATH . 'uploads/science_week/qr', $newName);
            $groupQrPath = 'uploads/science_week/qr/' . $newName;
        }

        $bannerPath = null;
        $bannerFile = $this->request->getFile('comp_banner');
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            if (!is_dir(FCPATH . 'uploads/science_week/banners')) {
                mkdir(FCPATH . 'uploads/science_week/banners', 0777, true);
            }
            $newName = $bannerFile->getRandomName();
            $tempPath = $bannerFile->getTempName();
            $targetFullPath = FCPATH . 'uploads/science_week/banners/' . $newName;
            
            // Resize and compress the banner image
            if (!$this->resizeImage($tempPath, $targetFullPath, 1000, 80)) {
                $bannerFile->move(FCPATH . 'uploads/science_week/banners', $newName);
            }
            $bannerPath = 'uploads/science_week/banners/' . $newName;
        }

        $customFields = $this->request->getPost('custom_fields');
        $customFieldsJson = null;
        if (!empty($customFields) && is_array($customFields)) {
            $cleanedFields = [];
            foreach ($customFields as $field) {
                if (!empty($field['label'])) {
                    $cleanedFields[] = [
                        'label' => trim($field['label']),
                        'type' => $field['type'] ?? 'text',
                        'options' => !empty($field['options']) ? trim($field['options']) : null,
                        'required' => isset($field['required']) && $field['required'] == '1'
                    ];
                }
            }
            $customFieldsJson = json_encode($cleanedFields, JSON_UNESCAPED_UNICODE);
        }

        $memberCustomFields = $this->request->getPost('member_custom_fields');
        $memberCustomFieldsJson = null;
        if (!empty($memberCustomFields) && is_array($memberCustomFields)) {
            $cleanedFields = [];
            foreach ($memberCustomFields as $field) {
                if (!empty($field['label'])) {
                    $cleanedFields[] = [
                        'label' => trim($field['label']),
                        'type' => $field['type'] ?? 'text',
                        'options' => !empty($field['options']) ? trim($field['options']) : null,
                        'required' => isset($field['required']) && $field['required'] == '1'
                    ];
                }
            }
            $memberCustomFieldsJson = json_encode($cleanedFields, JSON_UNESCAPED_UNICODE);
        }

        $levelLimits = $this->request->getPost('level_limits');
        $levelLimitsJson = null;
        if (!empty($levelLimits) && is_array($levelLimits)) {
            $cleanedLevels = [];
            foreach ($levelLimits as $lvl) {
                if (!empty($lvl['level'])) {
                    $cleanedLevels[] = [
                        'level' => trim($lvl['level']),
                        'limit' => isset($lvl['limit']) && trim($lvl['limit']) !== '' ? (int)$lvl['limit'] : 0
                    ];
                }
            }
            $levelLimitsJson = json_encode($cleanedLevels, JSON_UNESCAPED_UNICODE);
        }

        $dataInsert = [
            'comp_year' => $selectedYear,
            'comp_name' => $this->request->getPost('comp_name'),
            'comp_icon' => $this->request->getPost('comp_icon'),
            'comp_banner' => $bannerPath,
            'comp_level' => $this->request->getPost('comp_level'),
            'comp_level_limits' => $levelLimitsJson,
            'comp_description' => $this->request->getPost('comp_description') ?: null,
            'comp_rule_file' => $ruleFilePath,
            'comp_rule_link' => $this->request->getPost('comp_rule_link') ?: null,
            'comp_group_link' => $this->request->getPost('comp_group_link') ?: null,
            'comp_group_qr' => $groupQrPath,
            'comp_color' => $this->request->getPost('comp_color'),
            'comp_custom_fields' => $customFieldsJson,
            'comp_member_custom_fields' => $memberCustomFieldsJson,
            'comp_limit' => (int) $this->request->getPost('comp_limit'),
            'comp_member_limit' => (int) $this->request->getPost('comp_member_limit'),
            'comp_status' => $this->request->getPost('comp_status') ?: 'open',
            'comp_open_time' => $this->request->getPost('comp_open_time') ?: null,
            'comp_close_time' => $this->request->getPost('comp_close_time') ?: null
        ];

        if ($this->compModel->insert($dataInsert)) {
            return redirect()->to(base_url('science-week/staff/competitions'))->with('success', 'เพิ่มประเภทการแข่งขันสำเร็จ');
        }

        return redirect()->back()->withInput()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    /**
     * หน้าจอแก้ไขประเภทการแข่งขัน
     */
    public function compEdit($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $comp = $this->compModel->find($id);
        if (!$comp) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบประเภทการแข่งขันนี้');
        }

        // ตรวจสอบสิทธิ์ตามรายการแข่งขัน
        if (!$this->canAccessCompetition($comp['comp_name'])) {
            return redirect()->to(base_url('science-week/staff/competitions'))->with('error', 'คุณไม่มีสิทธิ์แก้ไขรายการแข่งขันนี้');
        }

        $data['title'] = 'แก้ไขประเภทการแข่งขัน | งานสัปดาห์วิทยาศาสตร์';
        $data['comp'] = $comp;
        $data['selected_year'] = $comp['comp_year'];
        $data['fullname'] = session()->get('u_fullname');

        return view('science_week/competitions_form', $data);
    }

    /**
     * อัปเดตประเภทการแข่งขัน
     */
    public function compUpdate($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $comp = $this->compModel->find($id);
        if (!$comp) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบประเภทการแข่งขันนี้');
        }

        // ตรวจสอบสิทธิ์ตามรายการแข่งขัน
        if (!$this->canAccessCompetition($comp['comp_name'])) {
            return redirect()->to(base_url('science-week/staff/competitions'))->with('error', 'คุณไม่มีสิทธิ์แก้ไขรายการแข่งขันนี้');
        }

        $rules = [
            'comp_name' => 'required|min_length[3]|max_length[255]',
            'comp_icon' => 'required',
            'comp_level' => 'required',
            'comp_description' => 'permit_empty',
            'comp_color' => 'required',
            'comp_limit' => 'permit_empty|integer',
            'comp_member_limit' => 'permit_empty|integer',
            'comp_rule_file' => 'max_size[comp_rule_file,10240]|ext_in[comp_rule_file,pdf,doc,docx,zip]',
            'comp_group_qr' => 'max_size[comp_group_qr,10240]|is_image[comp_group_qr]',
            'comp_banner' => 'max_size[comp_banner,10240]|is_image[comp_banner]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ruleFilePath = $comp['comp_rule_file'];

        // Handle deleting the file if requested
        if ($this->request->getPost('delete_rule_file') == '1') {
            if (!empty($ruleFilePath) && file_exists(FCPATH . $ruleFilePath)) {
                @unlink(FCPATH . $ruleFilePath);
            }
            $ruleFilePath = null;
        }

        $ruleFile = $this->request->getFile('comp_rule_file');
        if ($ruleFile && $ruleFile->isValid() && !$ruleFile->hasMoved()) {
            // Delete old file if exists
            if (!empty($comp['comp_rule_file']) && file_exists(FCPATH . $comp['comp_rule_file'])) {
                @unlink(FCPATH . $comp['comp_rule_file']);
            }
            if (!is_dir(FCPATH . 'uploads/science_week/rules')) {
                mkdir(FCPATH . 'uploads/science_week/rules', 0777, true);
            }
            $newName = $ruleFile->getRandomName();
            $ruleFile->move(FCPATH . 'uploads/science_week/rules', $newName);
            $ruleFilePath = 'uploads/science_week/rules/' . $newName;
        }

        $groupQrPath = $comp['comp_group_qr'];

        // Handle deleting the group QR code if requested
        if ($this->request->getPost('delete_group_qr') == '1') {
            if (!empty($groupQrPath) && file_exists(FCPATH . $groupQrPath)) {
                @unlink(FCPATH . $groupQrPath);
            }
            $groupQrPath = null;
        }

        $groupQrFile = $this->request->getFile('comp_group_qr');
        if ($groupQrFile && $groupQrFile->isValid() && !$groupQrFile->hasMoved()) {
            // Delete old file if exists
            if (!empty($comp['comp_group_qr']) && file_exists(FCPATH . $comp['comp_group_qr'])) {
                @unlink(FCPATH . $comp['comp_group_qr']);
            }
            if (!is_dir(FCPATH . 'uploads/science_week/qr')) {
                mkdir(FCPATH . 'uploads/science_week/qr', 0777, true);
            }
            $newName = $groupQrFile->getRandomName();
            $groupQrFile->move(FCPATH . 'uploads/science_week/qr', $newName);
            $groupQrPath = 'uploads/science_week/qr/' . $newName;
        }

        $bannerPath = $comp['comp_banner'];

        // Handle deleting the banner image if requested
        if ($this->request->getPost('delete_banner') == '1') {
            if (!empty($bannerPath) && file_exists(FCPATH . $bannerPath)) {
                @unlink(FCPATH . $bannerPath);
            }
            $bannerPath = null;
        }

        $bannerFile = $this->request->getFile('comp_banner');
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            // Delete old file if exists
            if (!empty($comp['comp_banner']) && file_exists(FCPATH . $comp['comp_banner'])) {
                @unlink(FCPATH . $comp['comp_banner']);
            }
            if (!is_dir(FCPATH . 'uploads/science_week/banners')) {
                mkdir(FCPATH . 'uploads/science_week/banners', 0777, true);
            }
            $newName = $bannerFile->getRandomName();
            $tempPath = $bannerFile->getTempName();
            $targetFullPath = FCPATH . 'uploads/science_week/banners/' . $newName;
            
            // Resize and compress the banner image
            if (!$this->resizeImage($tempPath, $targetFullPath, 1000, 80)) {
                $bannerFile->move(FCPATH . 'uploads/science_week/banners', $newName);
            }
            $bannerPath = 'uploads/science_week/banners/' . $newName;
        }

        $customFields = $this->request->getPost('custom_fields');
        $customFieldsJson = null;
        if (!empty($customFields) && is_array($customFields)) {
            $cleanedFields = [];
            foreach ($customFields as $field) {
                if (!empty($field['label'])) {
                    $cleanedFields[] = [
                        'label' => trim($field['label']),
                        'type' => $field['type'] ?? 'text',
                        'options' => !empty($field['options']) ? trim($field['options']) : null,
                        'required' => isset($field['required']) && $field['required'] == '1'
                    ];
                }
            }
            $customFieldsJson = json_encode($cleanedFields, JSON_UNESCAPED_UNICODE);
        }

        $memberCustomFields = $this->request->getPost('member_custom_fields');
        $memberCustomFieldsJson = null;
        if (!empty($memberCustomFields) && is_array($memberCustomFields)) {
            $cleanedFields = [];
            foreach ($memberCustomFields as $field) {
                if (!empty($field['label'])) {
                    $cleanedFields[] = [
                        'label' => trim($field['label']),
                        'type' => $field['type'] ?? 'text',
                        'options' => !empty($field['options']) ? trim($field['options']) : null,
                        'required' => isset($field['required']) && $field['required'] == '1'
                    ];
                }
            }
            $memberCustomFieldsJson = json_encode($cleanedFields, JSON_UNESCAPED_UNICODE);
        }

        $levelLimits = $this->request->getPost('level_limits');
        $levelLimitsJson = null;
        if (!empty($levelLimits) && is_array($levelLimits)) {
            $cleanedLevels = [];
            foreach ($levelLimits as $lvl) {
                if (!empty($lvl['level'])) {
                    $cleanedLevels[] = [
                        'level' => trim($lvl['level']),
                        'limit' => isset($lvl['limit']) && trim($lvl['limit']) !== '' ? (int)$lvl['limit'] : 0
                    ];
                }
            }
            $levelLimitsJson = json_encode($cleanedLevels, JSON_UNESCAPED_UNICODE);
        }

        $dataUpdate = [
            'comp_name' => $this->request->getPost('comp_name'),
            'comp_icon' => $this->request->getPost('comp_icon'),
            'comp_banner' => $bannerPath,
            'comp_level' => $this->request->getPost('comp_level'),
            'comp_level_limits' => $levelLimitsJson,
            'comp_description' => $this->request->getPost('comp_description') ?: null,
            'comp_rule_file' => $ruleFilePath,
            'comp_rule_link' => $this->request->getPost('comp_rule_link') ?: null,
            'comp_group_link' => $this->request->getPost('comp_group_link') ?: null,
            'comp_group_qr' => $groupQrPath,
            'comp_color' => $this->request->getPost('comp_color'),
            'comp_custom_fields' => $customFieldsJson,
            'comp_member_custom_fields' => $memberCustomFieldsJson,
            'comp_limit' => (int) $this->request->getPost('comp_limit'),
            'comp_member_limit' => (int) $this->request->getPost('comp_member_limit'),
            'comp_status' => $this->request->getPost('comp_status') ?: 'open',
            'comp_open_time' => $this->request->getPost('comp_open_time') ?: null,
            'comp_close_time' => $this->request->getPost('comp_close_time') ?: null
        ];

        $oldName = $comp['comp_name'];
        $newName = trim($this->request->getPost('comp_name') ?? '');

        if ($this->compModel->update($id, $dataUpdate)) {
            if ($oldName !== $newName && !empty($newName)) {
                // Update Tb_ScienceWeek_Registrations reference
                $db = \Config\Database::connect();
                $db->table('Tb_ScienceWeek_Registrations')
                   ->where('reg_competition_type', $oldName)
                   ->update(['reg_competition_type' => $newName]);

                // Update Tb_Users.u_science_week_competitions (allowed competitions) reference
                $userModel = new \App\Models\UserModel();
                $users = $userModel->like('u_science_week_competitions', $oldName)->findAll();
                foreach ($users as $user) {
                    $allowedJson = $user['u_science_week_competitions'] ?? '';
                    $allowedComps = json_decode($allowedJson, true) ?: [];
                    if (!empty($allowedComps)) {
                        $updatedComps = array_map(fn($val) => $val === $oldName ? $newName : $val, $allowedComps);
                        $userModel->update($user['u_id'], [
                            'u_science_week_competitions' => json_encode(array_values($updatedComps), JSON_UNESCAPED_UNICODE)
                        ]);
                    }
                }
            }
            return redirect()->to(base_url('science-week/staff/competitions'))->with('success', 'แก้ไขประเภทการแข่งขันสำเร็จ');
        }

        return redirect()->back()->withInput()->with('error', 'ไม่สามารถแก้ไขข้อมูลได้');
    }

    /**
     * ลบประเภทการแข่งขัน
     */
    public function compDelete($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        // ตรวจสอบสิทธิ์ตามรายการแข่งขัน
        $comp = $this->compModel->find($id);
        if ($comp && !$this->canAccessCompetition($comp['comp_name'])) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์ลบรายการแข่งขันนี้']);
        }

        if ($this->compModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'ลบประเภทการแข่งขันสำเร็จ'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }

    /**
     * หน้าจอตั้งค่าระบบนับถอยหลังและปีการศึกษา
     */
    public function adminSettings()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $settingsModel = new \App\Models\SettingsModel();
        $targetDate = $settingsModel->getVal('science_week_countdown') ?: '2026-08-18T09:00:00';
        $activeYear = $this->getActiveYear();

        $db = \Config\Database::connect();
        // ดึงรายการปีการศึกษาทั้งหมดที่มีในระบบ เพื่อทำตารางสถิติสรุป
        $yearsQuery = $db->query("
            SELECT DISTINCT year_val FROM (
                SELECT comp_year AS year_val FROM Tb_ScienceWeek_Competitions WHERE comp_year IS NOT NULL
                UNION
                SELECT reg_year AS year_val FROM Tb_ScienceWeek_Registrations WHERE reg_year IS NOT NULL
                UNION
                SELECT sch_year AS year_val FROM Tb_ScienceWeek_Schedules WHERE sch_year IS NOT NULL
                UNION
                SELECT eval_year AS year_val FROM Tb_ScienceWeek_Evaluations WHERE eval_year IS NOT NULL
            ) t ORDER BY year_val DESC
        ");
        $years = array_column($yearsQuery->getResultArray(), 'year_val');
        if (empty($years)) {
            $years = [$activeYear];
        } else if (!in_array($activeYear, $years)) {
            $years[] = $activeYear;
            rsort($years);
        }

        $yearStats = [];
        foreach ($years as $yr) {
            $compCount = $db->table('Tb_ScienceWeek_Competitions')->where('comp_year', $yr)->countAllResults();
            $regCount = $db->table('Tb_ScienceWeek_Registrations')->where('reg_year', $yr)->countAllResults();
            $approvedCount = $db->table('Tb_ScienceWeek_Registrations')->where('reg_year', $yr)->whereIn('reg_status', ['approved', 'approved_reserve'])->countAllResults();
            $evalCount = $db->table('Tb_ScienceWeek_Evaluations')->where('eval_year', $yr)->countAllResults();

            $yearStats[] = [
                'year' => $yr,
                'competitions' => $compCount,
                'registrations' => $regCount,
                'approved' => $approvedCount,
                'evaluations' => $evalCount
            ];
        }

        // Fetch Live Stats for Settings Hub
        $staffCount = $db->table('Tb_Users')
            ->like('u_role', 'science_week')
            ->where('u_status', 'active')
            ->countAllResults();

        $activeCompCount = $db->table('Tb_ScienceWeek_Competitions')->where('comp_year', $activeYear)->countAllResults();
        $activeSchCount = $db->table('Tb_ScienceWeek_Schedules')->where('sch_year', $activeYear)->countAllResults();

        // Eval Config Stats
        $evalConfig = $this->getEvaluationConfig();
        $evalFieldCount = count($evalConfig['fields'] ?? []);
        $evalQuestionCount = count($evalConfig['questions'] ?? []);

        // Cert Config Statuses
        $checkCertConfig = function($key) use ($settingsModel) {
            $configJson = $settingsModel->getVal($key);
            if ($configJson) {
                $config = json_decode($configJson, true);
                return !empty($config['bg_image']) && file_exists(FCPATH . $config['bg_image']);
            }
            return false;
        };

        $certCompConfigured = $checkCertConfig('science_week_cert_competition_config');
        $certTrainConfigured = $checkCertConfig('science_week_cert_trainer_config');
        $certEvalConfigured = $checkCertConfig('science_week_cert_evaluation_config');

        $data['title'] = 'ศูนย์รวมการตั้งค่าระบบ | งานสัปดาห์วิทยาศาสตร์';
        $data['countdown_date'] = $targetDate;
        $data['active_year'] = $activeYear;
        $data['registration_open'] = $settingsModel->getVal('science_week_registration_open') !== '0';
        $data['year_stats'] = $yearStats;
        $data['evaluation_claim_limit'] = (int)($settingsModel->getVal('science_week_evaluation_claim_limit') ?: 20);
        $data['evaluation_open'] = $settingsModel->getVal('science_week_evaluation_open') !== '0';
        $data['approved_list_open'] = $settingsModel->getVal('science_week_approved_list_open') !== '0';
        $data['fullname'] = session()->get('u_fullname');

        // Settings Hub specific variables
        $data['staff_count'] = $staffCount;
        $data['active_comp_count'] = $activeCompCount;
        $data['active_sch_count'] = $activeSchCount;
        $data['eval_field_count'] = $evalFieldCount;
        $data['eval_question_count'] = $evalQuestionCount;
        $data['cert_comp_configured'] = $certCompConfigured;
        $data['cert_train_configured'] = $certTrainConfigured;
        $data['cert_eval_configured'] = $certEvalConfigured;

        return view('science_week/settings', $data);
    }

    /**
     * บันทึกข้อมูลการตั้งค่าระบบ (นับถอยหลัง & ปีการศึกษา & เปิด-ปิดการสมัคร)
     */
    public function settingsSave()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $targetDate = $this->request->getPost('countdown_date');
        $activeYear = $this->request->getPost('active_year');
        $regOpenVal = $this->request->getPost('registration_open') === '1' ? '1' : '0';

        $settingsModel = new \App\Models\SettingsModel();

        // 1. Save countdown date
        $existingCountdown = $settingsModel->where('s_key', 'science_week_countdown')->first();
        $dataCountdown = [
            's_key' => 'science_week_countdown',
            's_value' => $targetDate,
            's_description' => 'วันเวลากำหนดการเริ่มงานสัปดาห์วิทยาศาสตร์ สำหรับระบบนับถอยหลัง'
        ];
        if ($existingCountdown) {
            $settingsModel->update($existingCountdown['s_id'], $dataCountdown);
        } else {
            $settingsModel->insert($dataCountdown);
        }

        // 2. Save active academic year
        if (!empty($activeYear) && is_numeric($activeYear)) {
            $existingYear = $settingsModel->where('s_key', 'science_week_active_year')->first();
            $dataYear = [
                's_key' => 'science_week_active_year',
                's_value' => (int)$activeYear,
                's_description' => 'ปีการศึกษาปัจจุบันที่เปิดใช้งานและรับสมัครงานวันวิทยาศาสตร์'
            ];
            if ($existingYear) {
                $settingsModel->update($existingYear['s_id'], $dataYear);
            } else {
                $settingsModel->insert($dataYear);
            }
        }

        // 3. Save registration open status
        $existingRegOpen = $settingsModel->where('s_key', 'science_week_registration_open')->first();
        $dataRegOpen = [
            's_key' => 'science_week_registration_open',
            's_value' => $regOpenVal,
            's_description' => 'สถานะการเปิดรับสมัครแข่งขัน (1 = เปิด, 0 = ปิด)'
        ];
        if ($existingRegOpen) {
            $settingsModel->update($existingRegOpen['s_id'], $dataRegOpen);
        } else {
            $settingsModel->insert($dataRegOpen);
        }

        // 4. Save evaluation claim limit
        $claimLimit = $this->request->getPost('evaluation_claim_limit');
        if (!empty($claimLimit) && is_numeric($claimLimit)) {
            $existingLimit = $settingsModel->where('s_key', 'science_week_evaluation_claim_limit')->first();
            $dataLimit = [
                's_key' => 'science_week_evaluation_claim_limit',
                's_value' => (int)$claimLimit,
                's_description' => 'จำนวนรายชื่อที่สามารถเคลมเกียรติบัตรการเข้าร่วมจากการประเมินได้สูงสุดต่อครั้ง'
            ];
            if ($existingLimit) {
                $settingsModel->update($existingLimit['s_id'], $dataLimit);
            } else {
                $settingsModel->insert($dataLimit);
            }
        }

        // 5. Save evaluation open status
        $evalOpenVal = $this->request->getPost('evaluation_open') === '1' ? '1' : '0';
        $existingEvalOpen = $settingsModel->where('s_key', 'science_week_evaluation_open')->first();
        $dataEvalOpen = [
            's_key' => 'science_week_evaluation_open',
            's_value' => $evalOpenVal,
            's_description' => 'สถานะการเปิดทำแบบประเมินความพึงพอใจ (1 = เปิด, 0 = ปิด)'
        ];
        if ($existingEvalOpen) {
            $settingsModel->update($existingEvalOpen['s_id'], $dataEvalOpen);
        } else {
            $settingsModel->insert($dataEvalOpen);
        }

        // 6. Save approved list open status
        $approvedListOpenVal = $this->request->getPost('approved_list_open') === '1' ? '1' : '0';
        $existingApprovedListOpen = $settingsModel->where('s_key', 'science_week_approved_list_open')->first();
        $dataApprovedListOpen = [
            's_key' => 'science_week_approved_list_open',
            's_value' => $approvedListOpenVal,
            's_description' => 'สถานะการเปิดประกาศรายชื่อผู้มีสิทธิ์เข้าแข่ง (1 = เปิด, 0 = ปิด)'
        ];
        if ($existingApprovedListOpen) {
            $settingsModel->update($existingApprovedListOpen['s_id'], $dataApprovedListOpen);
        } else {
            $settingsModel->insert($dataApprovedListOpen);
        }

        return redirect()->to(base_url('science-week/staff/settings'))->with('success', 'บันทึกข้อมูลการตั้งค่าระบบเรียบร้อยแล้ว');
    }

    /**
     * รายการกำหนดการกิจกรรมทั้งหมด
     */
    public function schIndex()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $selectedYear = $this->getSelectedYear();
        $data['title'] = 'จัดการกำหนดการกิจกรรม | งานสัปดาห์วิทยาศาสตร์';
        $data['schedules'] = $this->schModel->where('sch_year', $selectedYear)->orderBy('sch_id', 'ASC')->findAll();
        $data['fullname'] = session()->get('u_fullname');
        $data['selected_year'] = $selectedYear;

        return view('science_week/schedules_index', $data);
    }

    /**
     * หน้าจอเพิ่มกำหนดการใหม่
     */
    public function schCreate()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $data['title'] = 'เพิ่มกำหนดการ | งานสัปดาห์วิทยาศาสตร์';
        $data['sch'] = null;
        $data['selected_year'] = $this->getSelectedYear();
        $data['fullname'] = session()->get('u_fullname');

        return view('science_week/schedules_form', $data);
    }

    /**
     * บันทึกกำหนดการใหม่
     */
    public function schStore()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $selectedYear = $this->getSelectedYear();

        $rules = [
            'sch_date' => 'required|min_length[3]|max_length[255]',
            'sch_title' => 'required|min_length[3]|max_length[255]',
            'sch_description' => 'permit_empty',
            'sch_color' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataInsert = [
            'sch_year' => $selectedYear,
            'sch_date' => $this->request->getPost('sch_date'),
            'sch_title' => $this->request->getPost('sch_title'),
            'sch_description' => $this->request->getPost('sch_description') ?: null,
            'sch_color' => $this->request->getPost('sch_color')
        ];

        if ($this->schModel->insert($dataInsert)) {
            return redirect()->to(base_url('science-week/staff/schedules'))->with('success', 'เพิ่มกำหนดการเรียบร้อยแล้ว');
        }

        return redirect()->back()->withInput()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    public function schEdit($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $sch = $this->schModel->find($id);
        if (!$sch) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบกำหนดการกิจกรรมนี้');
        }

        $data['title'] = 'แก้ไขกำหนดการ | งานสัปดาห์วิทยาศาสตร์';
        $data['sch'] = $sch;
        $data['selected_year'] = $sch['sch_year'] ?? $this->getSelectedYear();
        $data['fullname'] = session()->get('u_fullname');

        return view('science_week/schedules_form', $data);
    }

    /**
     * อัปเดตกำหนดการกิจกรรม
     */
    public function schUpdate($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $rules = [
            'sch_date' => 'required|min_length[3]|max_length[255]',
            'sch_title' => 'required|min_length[3]|max_length[255]',
            'sch_description' => 'permit_empty',
            'sch_color' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'sch_date' => $this->request->getPost('sch_date'),
            'sch_title' => $this->request->getPost('sch_title'),
            'sch_description' => $this->request->getPost('sch_description') ?: null,
            'sch_color' => $this->request->getPost('sch_color')
        ];

        if ($this->schModel->update($id, $dataUpdate)) {
            return redirect()->to(base_url('science-week/staff/schedules'))->with('success', 'แก้ไขกำหนดการเรียบร้อยแล้ว');
        }

        return redirect()->back()->withInput()->with('error', 'ไม่สามารถแก้ไขข้อมูลได้');
    }

    /**
     * ลบกำหนดการกิจกรรม
     */
    public function schDelete($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        if ($this->schModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'ลบกำหนดการกิจกรรมสำเร็จ'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }

    /**
     * อัปเดตคะแนนและอันดับรางวัลผู้เข้าแข่งขัน (Staff/Admin)
     */
    public function updateRank($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        // ตรวจสอบสิทธิ์ตามรายการแข่งขัน
        $reg = $this->regModel->find($id);
        if (!$reg) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้สมัครนี้']);
        }
        if (!$this->canAccessCompetition($reg['reg_competition_type'])) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์จัดการรายการแข่งขันนี้']);
        }

        $score = $this->request->getPost('score');
        $rank = $this->request->getPost('rank');

        // Allow null/empty values
        $scoreValue = (trim($score) === '') ? null : floatval($score);
        $rankValue = (trim($rank) === '') ? null : trim($rank);

        $dataUpdate = [
            'reg_score' => $scoreValue,
            'reg_rank' => $rankValue
        ];

        if ($this->regModel->update($id, $dataUpdate)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'บันทึกคะแนนและอันดับรางวัลเรียบร้อยแล้ว'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตข้อมูลได้']);
    }

    /**
     * หน้าแสดงผลการประกวด/แข่งขัน และรางวัลสาธารณะ
     */
    public function publicResults()
    {
        $activeYear = $this->getActiveYear();
        $compType = $this->request->getGet('competition_type');
        $searchTerm = $this->request->getGet('search');

        $query = $this->regModel->where('reg_year', $activeYear)->whereIn('reg_status', ['approved', 'approved_reserve']); // Only show approved/valid teams of active year

        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                ->like('reg_school_name', trim($searchTerm))
                ->orLike('reg_team_name', trim($searchTerm))
                ->orLike('reg_members', trim($searchTerm))
                ->groupEnd();
        }

        if (!empty($compType)) {
            $query = $query->where('reg_competition_type', $compType);
        }

        // Sort by competition type first, then rank presence/value, then score descending
        // Award-winning teams first
        $results = $query->orderBy('reg_competition_type', 'ASC')
                         ->orderBy('CASE WHEN reg_rank IS NULL OR reg_rank = \'\' THEN 1 ELSE 0 END', 'ASC')
                         ->orderBy('reg_score', 'DESC')
                         ->orderBy('reg_id', 'ASC')
                         ->findAll();

        $settingsModel = new \App\Models\SettingsModel();
        $isPublished = $settingsModel->getVal('science_week_publish_results') === '1';

        $data['title'] = 'ประกาศผลรางวัลการแข่งขัน | งานสัปดาห์วิทยาศาสตร์';
        $data['results'] = $results;
        $data['search'] = $searchTerm;
        $data['compType_active'] = $compType;
        $data['competitions'] = $this->compModel->where('comp_year', $activeYear)->orderBy('comp_id', 'ASC')->findAll();
        $data['publish_results'] = $isPublished;

        return view('science_week/results', $data);
    }

    /**
     * หน้าประกาศรายชื่อผู้มีสิทธิ์เข้าร่วมแข่งขัน (Public Approved List)
     */
    public function publicApprovedList()
    {
        $activeYear = $this->getActiveYear();
        $compType = $this->request->getGet('competition_type');

        $hasSearched = ($compType !== null);
        $registrations = [];

        if ($hasSearched && !empty($compType)) {
            $query = $this->regModel->where('reg_year', $activeYear)->whereIn('reg_status', ['approved', 'approved_reserve']);
            $query = $query->where('reg_competition_type', $compType);

            $registrations = $query->orderBy('reg_competition_type', 'ASC')
                                   ->orderBy('reg_level', 'ASC')
                                   ->orderBy('reg_id', 'ASC')
                                   ->findAll();
        }

        $settingsModel = new \App\Models\SettingsModel();
        $approvedListOpen = $settingsModel->getVal('science_week_approved_list_open') !== '0';

        $data['title'] = 'ประกาศรายชื่อผู้มีสิทธิ์เข้าร่วมแข่งขัน | งานสัปดาห์วิทยาศาสตร์';
        $data['registrations'] = $registrations;
        $data['search'] = null;
        $data['compType_active'] = $compType;
        $data['competitions'] = $this->compModel->where('comp_year', $activeYear)->orderBy('comp_id', 'ASC')->findAll();
        $data['has_searched'] = $hasSearched;
        $data['approved_list_open'] = $approvedListOpen;

        return view('science_week/approved_list', $data);
    }

    /**
     * หน้าตรวจสอบสถานะการสมัครแข่งขัน (Public Check Status)
     */
    public function publicCheckStatus()
    {
        $activeYear = $this->getActiveYear();
        $searchTerm = $this->request->getGet('search');
        $registrations = [];

        if ($searchTerm !== null && trim($searchTerm) !== '') {
            $trimmedSearch = trim($searchTerm);
            $query = $this->regModel->where('reg_year', $activeYear);
            $query = $query->groupStart()
                ->like('reg_school_name', $trimmedSearch)
                ->orLike('reg_team_name', $trimmedSearch)
                ->orLike('reg_members', $trimmedSearch)
                ->orLike('reg_code', $trimmedSearch)
                ->orLike('reg_contact_phone', $trimmedSearch)
                ->groupEnd();
            
            $registrations = $query->orderBy('reg_id', 'DESC')->findAll();
        }

        $data['title'] = 'ตรวจสอบสถานะการสมัครแข่งขัน | งานสัปดาห์วิทยาศาสตร์';
        $data['registrations'] = $registrations;
        $data['search'] = $searchTerm;
        $data['competitions'] = $this->compModel->where('comp_year', $activeYear)->orderBy('comp_id', 'ASC')->findAll();

        return view('science_week/check_status', $data);
    }

    /**
     * เปิด-ปิดการประกาศผลรางวัลต่อสาธารณะ (Staff/Admin)
     */
    public function togglePublishResults()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $settingsModel = new \App\Models\SettingsModel();
        $current = $settingsModel->where('s_key', 'science_week_publish_results')->first();
        
        $newValue = ($current && $current['s_value'] === '1') ? '0' : '1';
        $dataSave = [
            's_key' => 'science_week_publish_results',
            's_value' => $newValue,
            's_description' => 'สถานะการประกาศผลรางวัลการแข่งขันสัปดาห์วิทยาศาสตร์ต่อสาธารณะ (1 = เปิด, 0 = ปิด)'
        ];

        if ($current) {
            $settingsModel->update($current['s_id'], $dataSave);
        } else {
            $settingsModel->insert($dataSave);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'published' => $newValue === '1',
            'message' => $newValue === '1' ? 'เปิดประกาศผลรางวัลต่อสาธารณะเรียบร้อยแล้ว' : 'ปิดการประกาศผลรางวัลเรียบร้อยแล้ว'
        ]);
    }

    /**
     * หน้าจอตั้งค่าการออกเกียรติบัตร (Staff/Admin)
     */
    public function adminCertificates()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $settingsModel = new \App\Models\SettingsModel();
        $compConfigJson = $settingsModel->getVal('science_week_cert_competition_config');
        $trainerConfigJson = $settingsModel->getVal('science_week_cert_trainer_config');
        $evalConfigJson = $settingsModel->getVal('science_week_cert_evaluation_config');
        $studentStaffConfigJson = $settingsModel->getVal('science_week_cert_student_staff_config');

        $data['title'] = 'ตั้งค่าการออกเกียรติบัตร | งานสัปดาห์วิทยาศาสตร์';
        $data['comp_config'] = $compConfigJson ? json_decode($compConfigJson, true) : [];
        $data['trainer_config'] = $trainerConfigJson ? json_decode($trainerConfigJson, true) : [];
        $data['eval_config'] = $evalConfigJson ? json_decode($evalConfigJson, true) : [];
        $data['student_staff_config'] = $studentStaffConfigJson ? json_decode($studentStaffConfigJson, true) : [];

        return view('science_week/admin_certificates', $data);
    }

    /**
     * บันทึกการตั้งค่าพิกัดและรูปภาพเกียรติบัตร (Staff/Admin)
     */
    public function saveCertConfig()
    {
        $access = $this->checkAccess();
        if ($access !== true) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $type = $this->request->getPost('cert_type');
        if (!in_array($type, ['competition', 'trainer', 'evaluation', 'student_staff'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ประเภทเกียรติบัตรไม่ถูกต้อง']);
        }

        $settingsModel = new \App\Models\SettingsModel();
        $configKey = "science_week_cert_{$type}_config";
        
        $currentConfigJson = $settingsModel->getVal($configKey);
        $config = $currentConfigJson ? json_decode($currentConfigJson, true) : [];

        // Handle File Upload
        $uploadedFilename = $this->request->getPost('bg_image_uploaded');
        if (!empty($uploadedFilename)) {
            $uploadedFilename = basename($uploadedFilename);
            $tempPath = WRITEPATH . 'uploads/temp_certs/' . $uploadedFilename;
            $targetDir = FCPATH . 'uploads/science_week/templates';
            
            if (file_exists($tempPath)) {
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                // Delete old template if exists
                if (!empty($config['bg_image']) && file_exists(FCPATH . $config['bg_image'])) {
                    @unlink(FCPATH . $config['bg_image']);
                }
                
                rename($tempPath, $targetDir . '/' . $uploadedFilename);
                $config['bg_image'] = 'uploads/science_week/templates/' . $uploadedFilename;
            }
        } else {
            $bgImage = $this->request->getFile('bg_image');
            if ($bgImage && $bgImage->isValid() && !$bgImage->hasMoved()) {
                if (!is_dir(FCPATH . 'uploads/science_week/templates')) {
                    mkdir(FCPATH . 'uploads/science_week/templates', 0777, true);
                }
                $newName = $bgImage->getRandomName();
                $bgImage->move(FCPATH . 'uploads/science_week/templates', $newName);
                
                // Delete old template if exists
                if (!empty($config['bg_image']) && file_exists(FCPATH . $config['bg_image'])) {
                    @unlink(FCPATH . $config['bg_image']);
                }
                $config['bg_image'] = 'uploads/science_week/templates/' . $newName;
            }
        }

        // Handle Fields Coordinates
        $fields = ($type === 'competition' || $type === 'trainer' || $type === 'student_staff')
            ? ['name', 'school', 'level', 'comp', 'rank', 'code']
            : ['name', 'text', 'date', 'code'];

        foreach ($fields as $field) {
            $config["enabled_{$field}"] = $this->request->getPost("enabled_{$field}") === '1';
            $config["x_{$field}"] = (int) $this->request->getPost("x_{$field}");
            $config["y_{$field}"] = (int) $this->request->getPost("y_{$field}");
            $config["size_{$field}"] = (int) $this->request->getPost("size_{$field}");
            $config["align_{$field}"] = $this->request->getPost("align_{$field}") ?: 'center';
            $config["color_{$field}"] = $this->request->getPost("color_{$field}") ?: '#000000';
            $config["parent_{$field}"] = $this->request->getPost("parent_{$field}") ?: 'none';
            $config["weight_{$field}"] = $this->request->getPost("weight_{$field}") ?: 'bold';
        }

        $s_description = "การตั้งค่าเกียรติบัตรประเภท {$type} (พิกัด ขนาดอักษร สีตัวหนังสือ ภาพพื้นหลัง)";
        
        $db = \Config\Database::connect();
        $exists = $db->table('Tb_Settings')->where('s_key', $configKey)->get()->getRowArray();

        $dataSave = [
            's_key' => $configKey,
            's_value' => json_encode($config, JSON_UNESCAPED_UNICODE),
            's_description' => $s_description
        ];

        if ($exists) {
            $settingsModel->update($exists['s_id'], $dataSave);
        } else {
            $settingsModel->insert($dataSave);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'บันทึกการตั้งค่าเกียรติบัตรเรียบร้อยแล้ว'
        ]);
    }

    /**
     * อัปโหลดไฟล์ภาพเทมเพลตเกียรติบัตรแบบ Chunked (ชิ้นส่วนย่อย) เพื่อหลีกเลี่ยงข้อจำกัด 413 Request Entity Too Large ของ Nginx
     */
    public function uploadCertChunk()
    {
        $access = $this->checkAccess();
        if ($access !== true) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $fileId = $this->request->getPost('file_id');
        $chunkIndex = (int)$this->request->getPost('chunk_index');
        $totalChunks = (int)$this->request->getPost('total_chunks');
        $filename = $this->request->getPost('filename');
        $file = $this->request->getFile('chunk');

        if (empty($fileId) || empty($filename) || !$file) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'ข้อมูล Chunk ไม่ครบถ้วน']);
        }

        // ล้างโฟลเดอร์ชั่วคราวที่มีอายุเกิน 24 ชั่วโมง
        $tempParent = WRITEPATH . 'uploads/temp_certs/';
        if (is_dir($tempParent)) {
            $dirs = glob($tempParent . '*', GLOB_ONLYDIR);
            $now = time();
            foreach ($dirs as $dir) {
                if ($now - filemtime($dir) > 86400) {
                    $files = glob($dir . '/*');
                    foreach ($files as $f) {
                        @unlink($f);
                    }
                    @rmdir($dir);
                }
            }
        }

        // โฟลเดอร์ชั่วคราวเก็บ chunks
        $tempDir = WRITEPATH . 'uploads/temp_certs/' . $fileId . '/';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        // บันทึก chunk
        $file->move($tempDir, (string)$chunkIndex);

        // ตรวจสอบว่า chunks ครบหรือยัง
        $chunksReceived = count(glob($tempDir . '*'));
        if ($chunksReceived === $totalChunks) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $newName = bin2hex(random_bytes(16)) . '.' . ($ext ?: 'png');
            $finalPath = WRITEPATH . 'uploads/temp_certs/' . $newName;

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

            // ลบ temp directory ของ chunks
            array_map('unlink', glob($tempDir . '*'));
            rmdir($tempDir);

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

    public function downloadCertificate($type, $code)
    {
        if (!in_array($type, ['competition', 'trainer', 'evaluation', 'student_staff'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ประเภทเกียรติบัตรไม่ถูกต้อง');
        }

        // 1. Fetch Configuration
        $settingsModel = new \App\Models\SettingsModel();
        $configJson = $settingsModel->getVal("science_week_cert_{$type}_config");
        if (!$configJson) {
            return $this->response->setBody('ขออภัย ระบบเกียรติบัตรยังไม่ได้เปิดใช้งาน หรือยังไม่ได้ตั้งค่าจากผู้ดูแลระบบ')->setStatusCode(400);
        }
        $config = json_decode($configJson, true);

        $bgImagePath = $config['bg_image'] ?? '';
        if (empty($bgImagePath) || !file_exists(FCPATH . $bgImagePath)) {
            return $this->response->setBody('ขออภัย ไม่พบไฟล์เทมเพลตเกียรติบัตร กรุณาติดต่อผู้ดูแลระบบ')->setStatusCode(500);
        }

        // 2. Fetch Recipient Data
        $recipientName = '';
        $schoolName = '';
        $levelName = '';
        $compName = '';
        $rankName = '';
        $certCode = $code;
        $dateText = '';

        if ($type === 'competition' || $type === 'trainer' || $type === 'student_staff') {
            if ($code === 'demo') {
                $recipientName = $this->request->getGet('name') ?: ($type === 'trainer' ? 'ครูสมหญิง ฝึกซ้อมดี' : ($type === 'student_staff' ? 'นายสมบัติ ทำดี' : 'นายสมศักดิ์ รักดี'));
                $schoolName = ($type === 'student_staff') ? 'โรงเรียนองค์การบริหารส่วนจังหวัดเชียงราย' : 'โรงเรียนตัวอย่างวิทยาคม จังหวัดนครสวรรค์';
                $levelName = ($type === 'student_staff') ? 'ชั้นมัธยมศึกษาปีที่ 5/1' : 'ระดับมัธยมศึกษาตอนต้น';
                $compName = ($type === 'student_staff') ? 'กิจกรรมประกวดภาพยนตร์สั้นวิทยาศาสตร์' : 'การแข่งขันจรวดขวดน้ำประเภทสร้างสรรค์';
                $certCode = ($type === 'student_staff') ? 'SW-STAFF-DEMO' : 'SW-COMP-DEMO';
                if ($type === 'trainer') {
                    $rankName = 'ผู้ควบคุมทีม ที่ได้รับรางวัลชนะเลิศ';
                } elseif ($type === 'student_staff') {
                    $rankName = 'ได้ปฏิบัติหน้าที่ คณะกรรมการดำเนินงานนักเรียนช่วยงาน';
                } else {
                    $rankName = 'ได้รับรางวัลชนะเลิศ';
                }
            } else {
                if ($type === 'student_staff') {
                    $staffModel = new ScienceWeekStudentStaffModel();
                    $st = $staffModel->find($code);
                    if (!$st) {
                        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบรหัสผู้ช่วยงาน');
                    }
                    $recipientName = $st['staff_prefix'] . $st['staff_firstname'] . ' ' . $st['staff_lastname'];
                    $schoolName = 'โรงเรียนองค์การบริหารส่วนจังหวัดเชียงราย';
                    $levelName = 'ชั้น' . $st['staff_class'];
                    $compName = $st['staff_competition_type'];
                    $certCode = 'SW-ST-' . str_pad($st['staff_id'], 4, '0', STR_PAD_LEFT);
                    $rankName = 'ได้ปฏิบัติหน้าที่ คณะกรรมการดำเนินงานนักเรียนช่วยงาน';
                } else {
                    $reg = $this->regModel->where('reg_code', $code)->whereIn('reg_status', ['approved', 'approved_reserve'])->first();
                    if (!$reg) {
                        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบรหัสผู้สมัคร หรือใบสมัครยังไม่ได้รับการอนุมัติ');
                    }

                    // Get selected name from query parameter
                    $selectedName = $this->request->getGet('name');
                    if (!empty($selectedName)) {
                        $recipientName = trim($selectedName);
                    } else {
                        if ($type === 'trainer') {
                            $advisors = json_decode($reg['reg_advisors'], true) ?: [];
                            $recipientName = !empty($advisors) ? $advisors[0] : '';
                        } else {
                            $membersRaw = json_decode($reg['reg_members'], true) ?: [];
                            $firstMember = !empty($membersRaw) ? $membersRaw[0] : '';
                            if (is_array($firstMember)) {
                                $prefix = trim($firstMember['prefix'] ?? '');
                                $name = trim($firstMember['name'] ?? '');
                                $recipientName = ($prefix !== '' ? $prefix . ' ' : '') . $name;
                            } else {
                                $recipientName = $firstMember;
                            }
                        }
                    }

                    $schoolName = $reg['reg_school_name'] ? "โรงเรียน{$reg['reg_school_name']}" : '';
                    if (!empty($reg['reg_school_province']) && $reg['reg_school_province'] !== '-') {
                        $schoolName .= " จังหวัด{$reg['reg_school_province']}";
                    }

                    $levelName = $reg['reg_level'] ?? '';
                    $compName = $reg['reg_competition_type'];
                    
                    if ($type === 'trainer') {
                        if (!empty($reg['reg_rank'])) {
                            $rankName = "ผู้ควบคุมทีม ที่ได้รับ" . $reg['reg_rank'];
                        } else {
                            $rankName = "ผู้ควบคุมทีม ที่เข้าร่วมการแข่งขัน";
                        }
                    } else {
                        if (!empty($reg['reg_rank'])) {
                            $rankName = "ได้รับ" . $reg['reg_rank'];
                        } else {
                            $rankName = "ได้เข้าร่วมการประกวดและแข่งขัน";
                        }
                    }
                }
            }
        } else {
            // Evaluation
            if ($code === 'demo') {
                $recipientName = 'นายสมศรี ดีเลิศ';
                $dateText = 'ให้ไว้ ณ วันที่ 18 สิงหาคม พ.ศ. 2569';
                $certCode = 'SW-EVAL-DEMO';
            } else {
                $db = \Config\Database::connect();
                $eval = $db->table('Tb_ScienceWeek_Evaluations')->where('eval_code', $code)->get()->getRowArray();
                if (!$eval) {
                    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบรหัสผู้ทำแบบประเมิน');
                }

                $selectedName = $this->request->getGet('name');
                if (!empty($selectedName)) {
                    $recipientName = trim($selectedName);
                } else {
                    $recipientName = $eval['eval_name'];
                }

                $dateText = "ให้ไว้ ณ วันที่ " . date('d', strtotime($eval['eval_created_at'])) . " " . 
                            ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'][date('n', strtotime($eval['eval_created_at']))-1] . " พ.ศ. " . 
                            (date('Y', strtotime($eval['eval_created_at'])) + 543);
            }
        }

        // 3. Load Image Resource (GD)
        $filePath = FCPATH . $bgImagePath;
        $info = getimagesize($filePath);
        $mime = $info['mime'] ?? '';

        if (strpos($mime, 'png') !== false) {
            $image = imagecreatefrompng($filePath);
        } elseif (strpos($mime, 'jpeg') !== false || strpos($mime, 'jpg') !== false) {
            $image = imagecreatefromjpeg($filePath);
        } else {
            $image = null;
        }

        if (!$image) {
            return $this->response->setBody('ไม่สามารถสร้างรูปภาพเกียรติบัตรได้')->setStatusCode(500);
        }

        // Enable alpha blending and save alpha options
        imagealphablending($image, true);
        imagesavealpha($image, true);

        // Font Path - Use Niramit-Bold.ttf
        $fontPath = FCPATH . 'assets/fonts/Niramit-Bold.ttf';
        if (!file_exists($fontPath)) {
            return $this->response->setBody('ไม่พบไฟล์ฟอนต์เกียรติบัตรในระบบ')->setStatusCode(500);
        }

        // Verify the font is actually readable by GD
        $testBbox = @imagettfbbox(12, 0, $fontPath, 'test');
        if ($testBbox === false) {
            return $this->response->setBody('ไฟล์ฟอนต์เสียหาย ไม่สามารถใช้งานได้ กรุณาติดต่อผู้ดูแลระบบ')->setStatusCode(500);
        }

        // Draw Text Elements
        if ($type === 'competition' || $type === 'trainer' || $type === 'student_staff') {
            $drawFields = [
                'name' => $recipientName,
                'school' => $schoolName,
                'level' => $levelName,
                'comp' => $compName,
                'rank' => $rankName,
                'code' => "เลขที่: " . $certCode
            ];
        } else {
            $drawFields = [
                'name' => $recipientName,
                'text' => "ได้ผ่านการประเมินความพึงพอใจการดำเนินงานสัปดาห์วิทยาศาสตร์ ประจำปี 2569",
                'date' => $dateText,
                'code' => "เลขที่: " . $certCode
            ];
        }

        // Helper closure to recursively construct concatenated texts
        $getFieldText = function($fieldKey) use (&$getFieldText, $drawFields, $config) {
            $text = $drawFields[$fieldKey] ?? '';
            foreach ($drawFields as $f => $val) {
                $parentVal = $config["parent_{$f}"] ?? 'none';
                $enabled = !isset($config["enabled_{$f}"]) || $config["enabled_{$f}"];
                if ($enabled && $parentVal === $fieldKey) {
                    $childText = $getFieldText($f);
                    if (!empty($childText)) {
                        $text .= " " . $childText;
                    }
                }
            }
            return $text;
        };

        foreach ($drawFields as $field => $text) {
            $enabled = !isset($config["enabled_{$field}"]) || $config["enabled_{$field}"];
            $parentVal = $config["parent_{$field}"] ?? 'none';

            // Only draw root fields (where parent is 'none')
            if (!$enabled || $parentVal !== 'none') {
                continue;
            }

            // Resolve full concatenated text
            $text = $getFieldText($field);
            if (empty(trim($text))) {
                continue;
            }

            $fontSize = ($config["size_{$field}"] ?? 24);
            $x = $config["x_{$field}"] ?? 500;
            $y = $config["y_{$field}"] ?? 500;
            $align = $config["align_{$field}"] ?? 'center';
            $hexColor = $config["color_{$field}"] ?? '#000000';

            $rgb = $this->hexToRgb($hexColor);
            $colorAlloc = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);

            // Adjust Thai text vowels and tone marks for GD rendering using thsarabunnew's PUA mappings to prevent overlapping/dropping
            $text = $this->adjustThaiText($text);

            $weight = $config["weight_{$field}"] ?? 'bold';
            $currentFontPath = ($weight === 'regular') 
                ? FCPATH . 'assets/fonts/Niramit-Regular.ttf' 
                : FCPATH . 'assets/fonts/Niramit-Bold.ttf';
            if (!file_exists($currentFontPath)) {
                $currentFontPath = $fontPath;
            }

            // Calculate text offset for precise alignment
            $bbox = imagettfbbox($fontSize, 0, $currentFontPath, $text);
            
            if ($align === 'center') {
                $drawX = $x - ($bbox[2] + $bbox[0]) / 2;
            } elseif ($align === 'right') {
                $drawX = $x - $bbox[2];
            } else {
                $drawX = $x - $bbox[0];
            }

            // Precisely center the text vertically around $y by offseting the baseline
            $drawY = $y - ($bbox[7] + $bbox[1]) / 2;

            // Simulate extra boldness by drawing text with offsets
            if ($weight === 'extrabold') {
                // 1px stroke around the text
                for ($dx = -1; $dx <= 1; $dx++) {
                    for ($dy = -1; $dy <= 1; $dy++) {
                        if ($dx !== 0 || $dy !== 0) {
                            imagettftext($image, (int)$fontSize, 0, (int)($drawX + $dx), (int)($drawY + $dy), $colorAlloc, $currentFontPath, $text);
                        }
                    }
                }
            } elseif ($weight === 'ultrabold') {
                // 2px stroke around the text
                for ($dx = -2; $dx <= 2; $dx++) {
                    for ($dy = -2; $dy <= 2; $dy++) {
                        if ($dx !== 0 || $dy !== 0) {
                            imagettftext($image, (int)$fontSize, 0, (int)($drawX + $dx), (int)($drawY + $dy), $colorAlloc, $currentFontPath, $text);
                        }
                    }
                }
            }

            // Draw the main text on top
            imagettftext($image, (int)$fontSize, 0, (int)$drawX, (int)$drawY, $colorAlloc, $currentFontPath, $text);
        }

        // 4. Output Image as PNG stream for download
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        $disposition = 'inline';

        return $this->response
            ->setHeader('Content-Type', 'image/png')
            ->setHeader('Content-Disposition', $disposition . '; filename="certificate_' . $certCode . '.png"')
            ->setBody($imageData);
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

    /**
     * Adjust Thai text vowels and tone marks to correct floating/overlapping positions in standard TrueType fonts
     */
    private function adjustThaiText($str)
    {
        if (empty($str)) return $str;

        // Tall consonants: ป, ฝ, ฟ, ฬ
        $tallConsonants = '([ปฝฟฬ])';
        // Normal consonants
        $normalConsonants = '([ก-ฆง-ชซ-ณด-ตถ-ผพภ-ยร-ลว-ฮ])';
        
        // Upper vowels: ั (U+0E31), ิ (U+0E34), ี (U+0E35), ึ (U+0E36), ื (U+0E37), ็ (U+0E47), ํ (U+0E4D)
        $upperVowels = '([ัิีึื็ํ])';
        // Tone marks & Karanth: ่ (U+0E48), ้ (U+0E49), ๊ (U+0E4A), ๋ (U+0E4B), ์ (U+0E4C)
        $toneMarks = '([่้๊๋์])';

        // Shifted-left upper vowels (over tall consonants)
        $shiftedUpperVowels = [
            'ั' => "\u{F710}",
            'ิ' => "\u{F705}",
            'ี' => "\u{F706}",
            'ึ' => "\u{F707}",
            'ื' => "\u{F708}",
            '็' => "\u{F712}",
            'ํ' => "\u{F711}"
        ];

        // Shifted-left tone marks directly over tall consonants
        $shiftedTonesDirect = [
            '่' => "\u{F70A}",
            '้' => "\u{F70B}",
            '๊' => "\u{F70C}",
            '๋' => "\u{F70D}",
            '์' => "\u{F70E}"
        ];

        // High level-2 tone marks over normal consonants + upper vowels
        $highTones = [
            '่' => "\u{F713}",
            '้' => "\u{F714}",
            '๊' => "\u{F715}",
            '๋' => "\u{F716}",
            '์' => "\u{F717}"
        ];

        // Shifted-left + high level-2 tone marks over tall consonants + upper vowels
        $shiftedHighTones = [
            '่' => "\u{F718}",
            '้' => "\u{F719}",
            '๊' => "\u{F71A}",
            '๋' => "\u{F71A}",
            '์' => "\u{F71C}"
        ];

        // 1. Tall consonant + Upper vowel + Tone mark
        $str = preg_replace_callback('/' . $tallConsonants . $upperVowels . $toneMarks . '/u', function($m) use ($shiftedUpperVowels, $shiftedHighTones) {
            $consonant = $m[1];
            $vowel = $shiftedUpperVowels[$m[2]] ?? $m[2];
            $tone = $shiftedHighTones[$m[3]] ?? $m[3];
            return $consonant . $vowel . $tone;
        }, $str);

        // 2. Normal consonant + Upper vowel + Tone mark
        $str = preg_replace_callback('/' . $normalConsonants . $upperVowels . $toneMarks . '/u', function($m) use ($highTones) {
            $consonant = $m[1];
            $vowel = $m[2];
            $tone = $highTones[$m[3]] ?? $m[3];
            return $consonant . $vowel . $tone;
        }, $str);

        // 3. Tall consonant + Upper vowel
        $str = preg_replace_callback('/' . $tallConsonants . $upperVowels . '/u', function($m) use ($shiftedUpperVowels) {
            return $m[1] . ($shiftedUpperVowels[$m[2]] ?? $m[2]);
        }, $str);

        // 4. Tall consonant + Tone mark
        $str = preg_replace_callback('/' . $tallConsonants . $toneMarks . '/u', function($m) use ($shiftedTonesDirect) {
            return $m[1] . ($shiftedTonesDirect[$m[2]] ?? $m[2]);
        }, $str);

        // 5. Lower vowels ุ, ู under ญ or ฐ
        $str = preg_replace_callback('/([ญฐ])([ุู])/u', function($m) {
            $consonant = $m[1] === 'ญ' ? "\u{F70F}" : "\u{F700}";
            return $consonant . $m[2];
        }, $str);

        return $str;
    }

    /**
     * แสดงหน้าพรีวิวและดาวน์โหลดเกียรติบัตรทั้งหมดสำหรับผู้สมัคร (Multi-member view)
     */
    public function viewAllCertificates($type, $code)
    {
        if (!in_array($type, ['competition', 'trainer', 'evaluation'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ประเภทเกียรติบัตรไม่ถูกต้อง');
        }

        $members = [];
        $advisors = [];

        if ($type === 'competition' || $type === 'trainer') {
            $reg = $this->regModel->where('reg_code', $code)->first();
            if (!$reg) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบรหัสผู้สมัคร');
            }
            $rawMembers = json_decode($reg['reg_members'], true) ?: [];
            $members = [];
            foreach ($rawMembers as $m) {
                if (is_array($m)) {
                    $prefix = trim($m['prefix'] ?? '');
                    $name = trim($m['name'] ?? '');
                    $members[] = ($prefix !== '' ? $prefix . ' ' : '') . $name;
                } else {
                    $members[] = $m;
                }
            }
            $advisors = json_decode($reg['reg_advisors'], true) ?: [];
        } else {
            $db = \Config\Database::connect();
            $eval = $db->table('Tb_ScienceWeek_Evaluations')->where('eval_code', $code)->get()->getRowArray();
            if (!$eval) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบรหัสผู้ทำแบบประเมิน');
            }
            if (!empty($eval['eval_students'])) {
                $members = json_decode($eval['eval_students'], true) ?: [$eval['eval_name']];
            } else {
                $members = [$eval['eval_name']];
            }
        }

        $data = [
            'title' => 'ดาวน์โหลดเกียรติบัตรทั้งหมด | งานสัปดาห์วิทยาศาสตร์',
            'type' => $type,
            'code' => $code,
            'members' => $members,
            'advisors' => $advisors
        ];

        return view('science_week/view_all_certificates', $data);
    }

    /**
     * ดึงค่าตั้งค่าโครงสร้างฟอร์มแบบประเมิน (Helper)
     */
    private function getEvaluationConfig()
    {
        $settingsModel = new \App\Models\SettingsModel();
        $configJson = $settingsModel->getVal('science_week_evaluation_config');
        if ($configJson) {
            $config = json_decode($configJson, true);
            if ($config) {
                return $config;
            }
        }

        // ค่าตั้งต้นมาตรฐานสำหรับแบบประเมินความพึงพอใจ
        return [
            'title' => 'แบบประเมินความพึงพอใจ',
            'subtitle' => 'ร่วมประเมินการจัดกิจกรรมสัปดาห์วิทยาศาสตร์เพื่อรับเกียรติบัตรเข้าร่วมกิจกรรม',
            'fields' => [
                ['key' => 'gender', 'label' => 'เพศ', 'placeholder' => 'ชาย / หญิง', 'required' => true, 'type' => 'text'],
                ['key' => 'age', 'label' => 'อายุ', 'placeholder' => 'เช่น 16 - 25 ปี', 'required' => true, 'type' => 'text'],
                ['key' => 'occupation', 'label' => 'อาชีพ', 'placeholder' => 'เช่น ครู บุคลากรทางการศึกษา', 'required' => true, 'type' => 'text'],
                ['key' => 'education_level', 'label' => 'ระดับการศึกษา', 'placeholder' => 'เช่น ปริญญาตรี', 'required' => true, 'type' => 'text'],
                ['key' => 'province', 'label' => 'จังหวัด', 'placeholder' => 'นครสวรรค์', 'required' => true, 'type' => 'text']
            ],
            'questions' => [
                ['key' => 'q1', 'label' => 'ด้านการประชาสัมพันธ์ข้อมูลกิจกรรมและการรับสมัครแข่งขัน'],
                ['key' => 'q2', 'label' => 'ด้านขั้นตอนและกระบวนการลงทะเบียนสมัครร่วมแข่งขันทางออนไลน์'],
                ['key' => 'q3', 'label' => 'ด้านสถานที่การดำเนินกิจกรรม สภาพแวดล้อม และการอำนวยความสะดวก'],
                ['key' => 'q4', 'label' => 'ด้านเวลา กำหนดการจัดงาน และระยะเวลาในแต่ละประเภทกิจกรรม'],
                ['key' => 'q5', 'label' => 'ประโยชน์ที่ได้รับจากการเข้าร่วมกิจกรรมและทักษะความรู้ใหม่ด้าน STEAM']
            ],
            'comment_label' => 'ข้อเสนอแนะอื่นๆ สำหรับการปรับปรุงครั้งต่อไป'
        ];
    }

    /**
     * หน้าแบบประเมินความพึงพอใจสำหรับผู้เข้าร่วม (Public Form - Dynamic)
     */
    public function publicEvaluation()
    {
        $settingsModel = new \App\Models\SettingsModel();
        $evaluationOpen = $settingsModel->getVal('science_week_evaluation_open') !== '0';
        if (!$evaluationOpen) {
            $data['title'] = 'ระบบแบบประเมินปิดชั่วคราว | งานสัปดาห์วิทยาศาสตร์';
            return view('science_week/evaluation_closed', $data);
        }

        $data['title'] = 'แบบประเมินความพึงพอใจ | งานสัปดาห์วิทยาศาสตร์';
        $data['form_config'] = $this->getEvaluationConfig();
        return view('science_week/evaluation_form', $data);
    }

    /**
     * บันทึกแบบประเมินจากหน้าบ้าน (AJAX - Dynamic)
     */
    public function storeEvaluation()
    {
        $settingsModel = new \App\Models\SettingsModel();
        $evaluationOpen = $settingsModel->getVal('science_week_evaluation_open') !== '0';
        if (!$evaluationOpen) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ระบบประเมินความพึงพอใจปิดให้บริการชั่วคราว'
            ]);
        }

        $config = $this->getEvaluationConfig();

        // กฎการตรวจสอบข้อมูลทั่วไป (ส่วนที่ 1)
        $rules = [
            'gender'          => 'required',
            'age'             => 'required',
            'occupation'      => 'required',
            'education_level' => 'required',
            'province'        => 'required'
        ];

        foreach ($config['questions'] as $q) {
            $rules["ratings.{$q['key']}"] = 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วนและถูกต้องตามรูปแบบ'
            ]);
        }

        $gender = $this->request->getPost('gender');
        $age = $this->request->getPost('age');
        $occupation = $this->request->getPost('occupation');
        if ($occupation === 'อื่นๆ' || $occupation === 'อื่น ๆ') {
            $occupationOther = $this->request->getPost('occupation_other');
            if (!empty($occupationOther)) {
                $occupation = 'อื่นๆ (' . trim($occupationOther) . ')';
            }
        }
        $educationLevel = $this->request->getPost('education_level');
        $provinceVal = trim($this->request->getPost('province'));

        // ดึงคะแนนการประเมิน
        $ratingsPost = $this->request->getPost('ratings') ?: [];
        $extractedRatings = [];
        foreach ($config['questions'] as $q) {
            $extractedRatings[$q['key']] = (int)($ratingsPost[$q['key']] ?? 0);
        }

        $comments = trim($this->request->getPost('comments')) ?: '';

        $feedbackData = [
            'ratings' => $extractedRatings,
            'comments' => $comments,
            'custom_fields' => [
                'gender' => $gender,
                'age' => $age,
                'occupation' => $occupation,
                'education_level' => $educationLevel,
                'province' => $provinceVal
            ]
        ];

        $activeYear = $this->getActiveYear();
        $evalCode = $this->evalModel->generateEvaluationCode();

        $dataInsert = [
            'eval_year'            => $activeYear,
            'eval_name'            => 'ผู้ประเมินทั่วไป',
            'eval_students'        => null,
            'eval_gender'          => $gender,
            'eval_age'             => $age,
            'eval_occupation'      => $occupation,
            'eval_education_level' => $educationLevel,
            'eval_school'          => null,
            'eval_province'        => $provinceVal,
            'eval_phone'           => null,
            'eval_feedback'        => json_encode($feedbackData, JSON_UNESCAPED_UNICODE),
            'eval_code'            => $evalCode,
            'eval_created_at'      => date('Y-m-d H:i:s')
        ];

        if ($this->evalModel->insert($dataInsert)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'บันทึกข้อมูลแบบประเมินเรียบร้อยแล้ว',
                'eval_code' => $evalCode
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง'
            ]);
        }
    }

    /**
     * หน้ากรอกชื่อผู้ลงชื่อเข้างาน / ผู้รับเกียรติบัตร (ขั้นตอนที่ 2)
     */
    public function claimCertificateForm($evalCode)
    {
        $eval = $this->evalModel->where('eval_code', $evalCode)->first();
        if (!$eval) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบข้อมูลแบบประเมินนี้');
        }

        $settingsModel = new \App\Models\SettingsModel();
        $claimLimit = (int)($settingsModel->getVal('science_week_evaluation_claim_limit') ?: 20);

        $data['title'] = 'ข้อมูลผู้ลงชื่อเข้างาน / ผู้รับเกียรติบัตร | งานสัปดาห์วิทยาศาสตร์';
        $data['eval'] = $eval;
        $data['claim_limit'] = $claimLimit;
        return view('science_week/claim_certificate_form', $data);
    }

    /**
     * บันทึกชื่อผู้ลงชื่อเข้างาน / ผู้รับเกียรติบัตร (AJAX)
     */
    public function storeClaimCertificate($evalCode)
    {
        $eval = $this->evalModel->where('eval_code', $evalCode)->first();
        if (!$eval) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลแบบประเมินนี้'
            ]);
        }

        $rules = [
            'eval_name' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'กรุณากรอกชื่อ-นามสกุลผู้เข้างานหลัก'
            ]);
        }

        $primaryName = trim($this->request->getPost('eval_name'));
        $phone = trim($this->request->getPost('eval_phone') ?: '');
        $school = trim($this->request->getPost('eval_school') ?: '');

        $studentNamesRaw = $this->request->getPost('student_names') ?: [];
        $studentNames = [];
        $studentNames[] = $primaryName; // The main visitor also gets a certificate

        foreach ($studentNamesRaw as $sName) {
            $trimmed = trim($sName);
            if ($trimmed !== '') {
                $studentNames[] = $trimmed;
            }
        }

        $settingsModel = new \App\Models\SettingsModel();
        $claimLimit = (int)($settingsModel->getVal('science_week_evaluation_claim_limit') ?: 20);
        if (count($studentNames) > $claimLimit) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'สามารถเคลมเกียรติบัตรได้สูงสุด ' . $claimLimit . ' คน (รวมผู้เข้างานหลัก)'
            ]);
        }

        $dataUpdate = [
            'eval_name'     => $primaryName,
            'eval_phone'    => !empty($phone) ? $phone : null,
            'eval_school'   => !empty($school) ? $school : null,
            'eval_students' => json_encode($studentNames, JSON_UNESCAPED_UNICODE)
        ];

        if ($this->evalModel->update($eval['eval_id'], $dataUpdate)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'บันทึกรายชื่อผู้รับเกียรติบัตรสำเร็จ!',
                'eval_code' => $evalCode
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง'
            ]);
        }
    }

    /**
     * รายการแบบประเมินความพึงพอใจทั้งหมด (Staff/Admin)
     */
    public function evalIndex()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $selectedYear = $this->getSelectedYear();
        $searchTerm = $this->request->getGet('search');

        $query = $this->evalModel->where('eval_year', $selectedYear);

        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                ->like('eval_name', $searchTerm)
                ->orLike('eval_students', $searchTerm)
                ->orLike('eval_school', $searchTerm)
                ->orLike('eval_province', $searchTerm)
                ->orLike('eval_phone', $searchTerm)
                ->orLike('eval_code', $searchTerm)
                ->orLike('eval_gender', $searchTerm)
                ->orLike('eval_age', $searchTerm)
                ->orLike('eval_occupation', $searchTerm)
                ->orLike('eval_education_level', $searchTerm)
                ->groupEnd();
        }

        $data['title'] = "จัดการแบบประเมิน งานสัปดาห์วิทยาศาสตร์ | อบจ.นครสวรรค์";
        $data['evaluations'] = $query->orderBy('eval_created_at', 'DESC')->paginate(20, 'default');
        $data['pager'] = $this->evalModel->pager;
        $data['search'] = $searchTerm;
        $data['selected_year'] = $selectedYear;

        return view('science_week/evaluations_index', $data);
    }

    /**
     * หน้าเพิ่ม/ตั้งค่าโครงสร้างฟอร์มประเมินโดยแอดมิน (Form Builder)
     */
    public function evalCreate()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $data['title'] = "ตั้งค่าโครงสร้างฟอร์มประเมิน | อบจ.นครสวรรค์";
        $data['form_config'] = $this->getEvaluationConfig();
        return view('science_week/evaluations_form', $data);
    }

    /**
     * บันทึกข้อมูลการตั้งค่าโครงสร้างฟอร์มประเมิน (Form Builder Save)
     */
    public function evalStore()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $title = trim($this->request->getPost('form_title') ?: 'แบบประเมินความพึงพอใจ');
        $subtitle = trim($this->request->getPost('form_subtitle') ?: '');
        $commentLabel = trim($this->request->getPost('comment_label') ?: 'ข้อเสนอแนะอื่นๆ สำหรับการปรับปรุงครั้งต่อไป');

        // ดึงรายการฟิลด์กรอกข้อมูล
        $fieldsPost = $this->request->getPost('fields') ?: [];
        $fields = [];
        if (is_array($fieldsPost)) {
            foreach ($fieldsPost as $f) {
                if (empty($f['label'])) continue;
                $key = trim($f['key'] ?: uniqid('field_'));
                $key = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
                
                $fields[] = [
                    'key' => $key,
                    'label' => trim($f['label']),
                    'placeholder' => trim($f['placeholder'] ?: ''),
                    'required' => !empty($f['required']),
                    'type' => trim($f['type'] ?: 'text')
                ];
            }
        }

        // ดึงรายการข้อคำถามประเมิน
        $questionsPost = $this->request->getPost('questions') ?: [];
        $questions = [];
        if (is_array($questionsPost)) {
            $idx = 1;
            foreach ($questionsPost as $q) {
                if (empty($q['label'])) continue;
                $key = trim($q['key'] ?: 'q' . $idx++);
                $key = preg_replace('/[^a-zA-Z0-9_]/', '', $key);

                $questions[] = [
                    'key' => $key,
                    'label' => trim($q['label'])
                ];
            }
        }

        $configData = [
            'title' => $title,
            'subtitle' => $subtitle,
            'fields' => $fields,
            'questions' => $questions,
            'comment_label' => $commentLabel
        ];

        $settingsModel = new \App\Models\SettingsModel();
        $existing = $settingsModel->where('s_key', 'science_week_evaluation_config')->first();

        $dataSave = [
            's_key' => 'science_week_evaluation_config',
            's_value' => json_encode($configData, JSON_UNESCAPED_UNICODE),
            's_description' => 'โครงสร้างและข้อความการตั้งค่าของฟอร์มประเมินความพึงพอใจและรับเกียรติบัตร'
        ];

        if ($existing) {
            $settingsModel->update($existing['s_id'], $dataSave);
        } else {
            $settingsModel->insert($dataSave);
        }

        return redirect()->to(base_url('science-week/staff/evaluations'))->with('success', 'บันทึกการตั้งค่าโครงสร้างฟอร์มประเมินเรียบร้อยแล้ว');
    }

    /**
     * หน้าแก้ไขข้อมูลผู้ประเมินแต่ละรายโดยแอดมิน (Staff/Admin)
     */
    public function evalEdit($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $eval = $this->evalModel->find($id);
        if (!$eval) {
            return redirect()->to(base_url('science-week/staff/evaluations'))->with('error', 'ไม่พบข้อมูลแบบประเมิน');
        }

        // แกะข้อมูล JSON ของประเด็นตอบกลับ
        $feedback = json_decode($eval['eval_feedback'], true) ?: [];
        $eval['ratings'] = $feedback['ratings'] ?? [];
        $eval['comments'] = $feedback['comments'] ?? '';
        $eval['custom_fields'] = $feedback['custom_fields'] ?? [];
        $eval['students'] = !empty($eval['eval_students']) ? (json_decode($eval['eval_students'], true) ?: [$eval['eval_name']]) : [$eval['eval_name']];

        $data['title'] = "แก้ไขข้อมูลผู้ประเมิน | อบจ.นครสวรรค์";
        $data['eval'] = $eval;
        $data['form_config'] = $this->getEvaluationConfig();

        return view('science_week/evaluations_edit_response', $data);
    }

    /**
     * อัปเดตข้อมูลผู้ประเมินแต่ละรายโดยแอดมิน (Staff/Admin)
     */
    public function evalUpdate($id)
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $eval = $this->evalModel->find($id);
        if (!$eval) {
            return redirect()->to(base_url('science-week/staff/evaluations'))->with('error', 'ไม่พบข้อมูลแบบประเมิน');
        }

        $config = $this->getEvaluationConfig();

        $rules = [
            'student_names' => 'required',
        ];

        foreach ($config['fields'] as $field) {
            $ruleStr = $field['required'] ? 'required' : 'permit_empty';
            if ($field['type'] === 'tel') {
                $ruleStr .= '|min_length[9]|max_length[20]';
            } else {
                $ruleStr .= '|max_length[255]';
            }
            $rules["fields.{$field['key']}"] = $ruleStr;
        }

        foreach ($config['questions'] as $q) {
            $rules["ratings.{$q['key']}"] = 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $studentNamesRaw = $this->request->getPost('student_names') ?: [];
        $studentNames = [];
        foreach ($studentNamesRaw as $sName) {
            $trimmed = trim($sName);
            if ($trimmed !== '') {
                $studentNames[] = $trimmed;
            }
        }

        if (empty($studentNames)) {
            return redirect()->back()->withInput()->with('error', 'กรุณากรอกรายชื่อผู้รับเกียรติบัตรอย่างน้อย 1 คน');
        }

        $fullname = $studentNames[0];
        
        $fieldsPost = $this->request->getPost('fields') ?: [];
        $extractedFields = [];
        foreach ($config['fields'] as $field) {
            $extractedFields[$field['key']] = trim($fieldsPost[$field['key']] ?? '');
        }

        $ratingsPost = $this->request->getPost('ratings') ?: [];
        $extractedRatings = [];
        foreach ($config['questions'] as $q) {
            $extractedRatings[$q['key']] = (int)($ratingsPost[$q['key']] ?? 0);
        }

        $comments = trim($this->request->getPost('comments')) ?: '';

        // ค้นหาฟิลด์โทรศัพท์ โรงเรียน และจังหวัดเพื่ออัปเดตลงคอลัมน์หลัก
        $phoneVal = $extractedFields['phone'] ?? $extractedFields['eval_phone'] ?? $extractedFields['tel'] ?? '-';
        if (empty($phoneVal) || $phoneVal === '-') {
            foreach ($extractedFields as $k => $v) {
                if (strpos($k, 'phone') !== false || strpos($k, 'tel') !== false) {
                    $phoneVal = $v;
                    break;
                }
            }
        }

        $schoolVal = $extractedFields['school'] ?? $extractedFields['eval_school'] ?? '-';
        if (empty($schoolVal) || $schoolVal === '-') {
            foreach ($extractedFields as $k => $v) {
                if (strpos($k, 'school') !== false || strpos($k, 'academy') !== false) {
                    $schoolVal = $v;
                    break;
                }
            }
        }

        $provinceVal = $extractedFields['province'] ?? $extractedFields['eval_province'] ?? '-';
        if (empty($provinceVal) || $provinceVal === '-') {
            foreach ($extractedFields as $k => $v) {
                if (strpos($k, 'province') !== false || strpos($k, 'city') !== false) {
                    $provinceVal = $v;
                    break;
                }
            }
        }

        $feedbackData = [
            'ratings' => $extractedRatings,
            'comments' => $comments,
            'custom_fields' => $extractedFields
        ];

        $gender = $extractedFields['gender'] ?? null;
        $age = $extractedFields['age'] ?? null;
        $occupation = $extractedFields['occupation'] ?? null;
        $educationLevel = $extractedFields['education_level'] ?? null;

        $dataUpdate = [
            'eval_name'            => $fullname,
            'eval_students'        => json_encode($studentNames, JSON_UNESCAPED_UNICODE),
            'eval_gender'          => $gender,
            'eval_age'             => $age,
            'eval_occupation'      => $occupation,
            'eval_education_level' => $educationLevel,
            'eval_school'          => $schoolVal,
            'eval_province'        => $provinceVal,
            'eval_phone'           => $phoneVal,
            'eval_feedback'        => json_encode($feedbackData, JSON_UNESCAPED_UNICODE)
        ];

        if ($this->evalModel->update($id, $dataUpdate)) {
            return redirect()->to(base_url('science-week/staff/evaluations'))->with('success', 'อัปเดตข้อมูลผู้ประเมินเรียบร้อยแล้ว');
        } else {
            return redirect()->back()->withInput()->with('error', 'ไม่สามารถอัปเดตข้อมูลได้ กรุณาลองใหม่อีกครั้ง');
        }
    }

    /**
     * ลบข้อมูลแบบประเมิน (Staff/Admin)
     */
    public function evalDelete($id)
    {
        $access = $this->checkAccess();
        if ($access !== true) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์เข้าถึงส่วนนี้']);
        }

        $eval = $this->evalModel->find($id);
        if (!$eval) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลแบบประเมิน']);
        }

        if ($this->evalModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบข้อมูลแบบประเมินเรียบร้อยแล้ว']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้ กรุณาลองใหม่อีกครั้ง']);
        }
    }

    /**
     * หน้าจอจัดการสิทธิ์เจ้าหน้าที่และผู้ใช้งาน (Staff/Admin)
     */
    public function usersIndex()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $db = \Config\Database::connect();
        $builder = $db->table('Tb_Users u');
        $builder->select('u.*, p.pos_name as position_name');
        $builder->join('Tb_Positions p', 'p.pos_id = u.u_position', 'left');
        $builder->like('u.u_role', 'science_week');
        $builder->where('u.u_status', 'active');
        $builder->orderBy('u.u_id', 'DESC');
        $data['users'] = $builder->get()->getResultArray();

        $selectedYear = $this->getSelectedYear();
        $data['competitions'] = $this->compModel->where('comp_year', $selectedYear)->orderBy('comp_id', 'ASC')->findAll();
        $data['selected_year'] = $selectedYear;
        $data['positions'] = $db->table('Tb_Positions')->orderBy('pos_name', 'ASC')->get()->getResultArray();
        $data['title'] = 'จัดการสิทธิ์เจ้าหน้าที่วิทยาศาสตร์ | งานสัปดาห์วิทยาศาสตร์';
        $data['fullname'] = session()->get('u_fullname');

        return view('science_week/users_index', $data);
    }

    /**
     * บันทึกข้อมูลเจ้าหน้าที่วิทยาศาสตร์ใหม่ (Staff/Admin)
     */
    public function usersStore()
    {
        $access = $this->checkAccess();
        if ($access !== true) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $email = trim($this->request->getPost('u_email') ?? '');
        $fullname = trim($this->request->getPost('u_fullname') ?? '');
        $role = $this->request->getPost('u_role') ?: 'science_week';

        if (empty($email) || empty($fullname)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน (ชื่อ-นามสกุล และ อีเมล)']);
        }

        // Process allowed competitions (only meaningful for staff role)
        $allowedComps = $this->request->getPost('allowed_competitions');
        $competitionsJson = null;
        if ($role === 'science_week' && !empty($allowedComps) && is_array($allowedComps)) {
            $competitionsJson = json_encode(array_values($allowedComps), JSON_UNESCAPED_UNICODE);
        }

        $userModel = new \App\Models\UserModel();

        // Check if email already exists
        $existingUser = $userModel->where('u_email', $email)->first();
        if ($existingUser) {
            $existingRoles = explode(',', $existingUser['u_role'] ?? '');
            $newRoles = explode(',', $role);
            $mergedRoles = array_filter(array_unique(array_merge($existingRoles, $newRoles)));

            $dataUpdate = [
                'u_role'                    => implode(',', $mergedRoles),
                'u_status'                  => 'active',
                'u_science_week_competitions' => $competitionsJson,
            ];

            if ($userModel->update($existingUser['u_id'], $dataUpdate)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'พบอีเมลนี้ในระบบหลักแล้ว ได้ทำการอัปเดตและเปิดสิทธิ์เข้าใช้งานระบบวิทยาศาสตร์เพิ่มเติมให้เรียบร้อยแล้ว']);
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการอัปเดตสิทธิ์ผู้ใช้เดิม']);
        }

        // Generate username from email prefix
        $emailParts = explode('@', $email);
        $baseUsername = preg_replace('/[^a-zA-Z0-9_\-]/', '', $emailParts[0]);
        $username = $baseUsername;
        $counter = 1;
        
        while ($userModel->where('u_username', $username)->first()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        // Generate a random secure password (since they will use Google Login, but we still need a valid hash)
        $randomPassword = bin2hex(random_bytes(16));

        $dataInsert = [
            'u_username'                  => $username,
            'u_email'                     => $email,
            'u_fullname'                  => $fullname,
            'u_position'                  => null,
            'u_password'                  => password_hash($randomPassword, PASSWORD_DEFAULT),
            'u_role'                      => $role,
            'u_status'                    => 'active',
            'u_sort'                      => 99,
            'u_science_week_competitions' => $competitionsJson,
        ];

        if ($userModel->insert($dataInsert)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'เพิ่มเจ้าหน้าที่วิทยาศาสตร์เรียบร้อยแล้ว (สามารถใช้งาน Google Login ด้วยอีเมลนี้ได้ทันที)']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
    }

    /**
     * อัปเดตข้อมูลเจ้าหน้าที่วิทยาศาสตร์ (Staff/Admin)
     */
    public function usersUpdate($id)
    {
        $access = $this->checkAccess();
        if ($access !== true) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้ใช้']);
        }

        $email = trim($this->request->getPost('u_email') ?? '');
        $fullname = trim($this->request->getPost('u_fullname') ?? '');
        $role = $this->request->getPost('u_role') ?: 'science_week';

        if (empty($email) || empty($fullname)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน (ชื่อ-นามสกุล และ อีเมล)']);
        }

        // Process allowed competitions (only meaningful for staff role)
        $allowedComps = $this->request->getPost('allowed_competitions');
        $competitionsJson = null;
        if ($role === 'science_week' && !empty($allowedComps) && is_array($allowedComps)) {
            $competitionsJson = json_encode(array_values($allowedComps), JSON_UNESCAPED_UNICODE);
        }

        // Check duplicates excluding self
        $dupEmail = $userModel->where('u_email', $email)->where('u_id !=', $id)->first();
        if ($dupEmail) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'อีเมลนี้มีในระบบแล้ว']);
        }

        $dataUpdate = [
            'u_email'                     => $email,
            'u_fullname'                  => $fullname,
            'u_role'                      => $role,
            'u_science_week_competitions' => $competitionsJson,
        ];

        // Also update username if email changed, to keep it clean
        if ($user['u_email'] !== $email) {
            $emailParts = explode('@', $email);
            $baseUsername = preg_replace('/[^a-zA-Z0-9_\-]/', '', $emailParts[0]);
            $username = $baseUsername;
            $counter = 1;
            
            while ($userModel->where('u_username', $username)->where('u_id !=', $id)->first()) {
                $username = $baseUsername . $counter;
                $counter++;
            }
            $dataUpdate['u_username'] = $username;
        }

        if ($userModel->update($id, $dataUpdate)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'อัปเดตข้อมูลเจ้าหน้าที่วิทยาศาสตร์เรียบร้อยแล้ว']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตข้อมูลได้']);
    }

    /**
     * ลบสิทธิ์/ลบบัญชีเจ้าหน้าที่วิทยาศาสตร์ (Staff/Admin)
     */
    public function usersDelete($id)
    {
        $access = $this->checkAccess();
        if ($access !== true) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้ใช้']);
        }

        // Prevent self-deletion
        if ($id == session()->get('u_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบบัญชีของตัวเองได้']);
        }

        $roles = explode(',', $user['u_role'] ?? '');
        $roles = array_filter(array_diff($roles, ['science_week']));

        if (empty($roles)) {
            // User only had science_week role, safe to delete completely
            if ($userModel->delete($id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'ลบบัญชีผู้ใช้งานเรียบร้อยแล้ว']);
            }
        } else {
            // User has other roles, just strip science_week role
            if ($userModel->update($id, ['u_role' => implode(',', $roles)])) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'ยกเลิกสิทธิ์การเข้าใช้งานระบบวิทยาศาสตร์ของพนักงานคนนี้แล้ว']);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }

    /**
     * ปรับขนาดรูปภาพเพื่อความเหมาะสมและความเร็วในการโหลด (คง Aspect Ratio และ บีบอัดไฟล์)
     */
    private function resizeImage($sourcePath, $targetPath, $maxWidth = 1000, $quality = 85)
    {
        list($width, $height, $type) = getimagesize($sourcePath);
        
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = ($height / $width) * $newWidth;
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $src = imagecreatefromgif($sourcePath);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagecreatefromwebp')) {
                    $src = imagecreatefromwebp($sourcePath);
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }

        if (!$src) {
            return false;
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);
        
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($dst, $targetPath, $quality);
                break;
            case IMAGETYPE_PNG:
                $pngQuality = ($quality - 100) / 11.1111;
                $pngQuality = round(abs($pngQuality));
                imagepng($dst, $targetPath, $pngQuality);
                break;
            case IMAGETYPE_GIF:
                imagegif($dst, $targetPath);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagewebp')) {
                    imagewebp($dst, $targetPath, $quality);
                } else {
                    imagejpeg($dst, $targetPath, $quality);
                }
                break;
        }

        imagedestroy($src);
        imagedestroy($dst);
        return true;
    }

    /**
     * ระบบจัดการนักเรียนช่วยงาน (Student Staff)
     */
    public function studentStaffIndex()
    {
        $access = $this->checkAccess(true);
        if ($access !== true)
            return $access;

        $selectedYear = $this->getSelectedYear();
        $searchTerm = $this->request->getGet('search');
        $compType = $this->request->getGet('competition_type');

        $staffModel = new ScienceWeekStudentStaffModel();
        $query = $staffModel->where('staff_year', $selectedYear);

        $roles = session()->get('u_role') ?? '';
        $hasFullAccess = (strpos($roles, 'superadmin') !== false || strpos($roles, 'admin') !== false || (strpos($roles, 'science_week') !== false && strpos($roles, 'science_week_student_staff') === false));
        $hasStudentStaffOnly = (strpos($roles, 'science_week_student_staff') !== false && !$hasFullAccess);
        if ($hasStudentStaffOnly) {
            $query = $query->where('staff_created_by', session()->get('u_id'));
        }

        // Filter based on allowed competitions
        $allowedComps = $this->getAllowedCompetitions();
        if ($allowedComps !== null && !empty($allowedComps)) {
            $generalRoles = [
                'ฝ่ายงานทั่วไป / ส่วนกลาง',
                'ฝ่ายลงทะเบียนและประเมินผล',
                'ฝ่ายสถานที่และโสตทัศนูปกรณ์',
                'ฝ่ายอาหารและเครื่องดื่ม',
                'ฝ่ายประชาสัมพันธ์และต้อนรับ',
                'ฝ่ายจัดนิทรรศการและกิจกรรมพิเศษ'
            ];
            $viewableRoles = array_merge($allowedComps, $generalRoles);
            $query = $query->whereIn('staff_competition_type', $viewableRoles);
        }

        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                ->like('staff_firstname', $searchTerm)
                ->orLike('staff_lastname', $searchTerm)
                ->orLike('staff_class', $searchTerm)
                ->groupEnd();
        }

        if (!empty($compType)) {
            $query = $query->where('staff_competition_type', $compType);
        }

        // Fetch competitions for dropdown selector
        $allComps = $this->compModel->where('comp_year', $selectedYear)->orderBy('comp_id', 'ASC')->findAll();
        if ($allowedComps !== null && !empty($allowedComps)) {
            $allComps = array_values(array_filter($allComps, fn($c) => in_array($c['comp_name'], $allowedComps)));
        }

        // Query all unique roles actually stored in the DB for filtering
        $db = \Config\Database::connect();
        $uniqueRolesQuery = $db->table('Tb_ScienceWeek_StudentStaff')
            ->select('staff_competition_type')
            ->where('staff_year', $selectedYear);
        if ($hasStudentStaffOnly) {
            $uniqueRolesQuery = $uniqueRolesQuery->where('staff_created_by', session()->get('u_id'));
        }
        $uniqueRolesQuery = $uniqueRolesQuery->distinct()
            ->get()
            ->getResultArray();
        
        $assignedRoles = array_column($uniqueRolesQuery, 'staff_competition_type');

        $data['title'] = "จัดการรายชื่อนักเรียนช่วยงาน | อบจ.นครสวรรค์";
        $data['student_staff'] = $query->orderBy('staff_created_at', 'DESC')->findAll();
        $data['search'] = $searchTerm;
        $data['compType_active'] = $compType;
        $data['competitions'] = $allComps;
        $data['assigned_roles'] = $assignedRoles; // Pass to view
        $data['selected_year'] = $selectedYear;
        $data['fullname'] = session()->get('u_fullname');

        return view('science_week/student_staff_index', $data);
    }

    public function studentStaffPrint()
    {
        $access = $this->checkAccess(true);
        if ($access !== true)
            return $access;

        $selectedYear = $this->getSelectedYear();
        $searchTerm = $this->request->getGet('search');
        $compType = $this->request->getGet('competition_type');

        $staffModel = new ScienceWeekStudentStaffModel();
        $query = $staffModel->where('staff_year', $selectedYear);

        $roles = session()->get('u_role') ?? '';
        $hasFullAccess = (strpos($roles, 'superadmin') !== false || strpos($roles, 'admin') !== false || (strpos($roles, 'science_week') !== false && strpos($roles, 'science_week_student_staff') === false));
        $hasStudentStaffOnly = (strpos($roles, 'science_week_student_staff') !== false && !$hasFullAccess);
        if ($hasStudentStaffOnly) {
            $query = $query->where('staff_created_by', session()->get('u_id'));
        }

        $allowedComps = $this->getAllowedCompetitions();
        if ($allowedComps !== null && !empty($allowedComps)) {
            $generalRoles = [
                'ฝ่ายงานทั่วไป / ส่วนกลาง',
                'ฝ่ายลงทะเบียนและประเมินผล',
                'ฝ่ายสถานที่และโสตทัศนูปกรณ์',
                'ฝ่ายอาหารและเครื่องดื่ม',
                'ฝ่ายประชาสัมพันธ์และต้อนรับ',
                'ฝ่ายจัดนิทรรศการและกิจกรรมพิเศษ'
            ];
            $viewableRoles = array_merge($allowedComps, $generalRoles);
            $query = $query->whereIn('staff_competition_type', $viewableRoles);
        }

        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                ->like('staff_firstname', $searchTerm)
                ->orLike('staff_lastname', $searchTerm)
                ->orLike('staff_class', $searchTerm)
                ->groupEnd();
        }

        if (!empty($compType)) {
            $query = $query->where('staff_competition_type', $compType);
        }

        $data['title'] = "พิมพ์ใบลงชื่อผู้ช่วยงาน | งานสัปดาห์วิทยาศาสตร์";
        // เรียงตามรายการแข่งขัน แล้วค่อยตามชื่อ
        $data['student_staff'] = $query->orderBy('staff_competition_type', 'ASC')->orderBy('staff_firstname', 'ASC')->findAll();
        $data['compType_active'] = $compType;
        $data['selected_year'] = $selectedYear;

        return view('science_week/student_staff_print', $data);
    }

    public function studentStaffExport()
    {
        $access = $this->checkAccess(true);
        if ($access !== true)
            return $access;

        $selectedYear = $this->getSelectedYear();
        $searchTerm = $this->request->getGet('search');
        $compType = $this->request->getGet('competition_type');

        $staffModel = new ScienceWeekStudentStaffModel();
        $query = $staffModel->where('staff_year', $selectedYear);

        $roles = session()->get('u_role') ?? '';
        $hasFullAccess = (strpos($roles, 'superadmin') !== false || strpos($roles, 'admin') !== false || (strpos($roles, 'science_week') !== false && strpos($roles, 'science_week_student_staff') === false));
        $hasStudentStaffOnly = (strpos($roles, 'science_week_student_staff') !== false && !$hasFullAccess);
        if ($hasStudentStaffOnly) {
            $query = $query->where('staff_created_by', session()->get('u_id'));
        }

        $allowedComps = $this->getAllowedCompetitions();
        if ($allowedComps !== null && !empty($allowedComps)) {
            $generalRoles = [
                'ฝ่ายงานทั่วไป / ส่วนกลาง',
                'ฝ่ายลงทะเบียนและประเมินผล',
                'ฝ่ายสถานที่และโสตทัศนูปกรณ์',
                'ฝ่ายอาหารและเครื่องดื่ม',
                'ฝ่ายประชาสัมพันธ์และต้อนรับ',
                'ฝ่ายจัดนิทรรศการและกิจกรรมพิเศษ'
            ];
            $viewableRoles = array_merge($allowedComps, $generalRoles);
            $query = $query->whereIn('staff_competition_type', $viewableRoles);
        }

        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                ->like('staff_firstname', $searchTerm)
                ->orLike('staff_lastname', $searchTerm)
                ->orLike('staff_class', $searchTerm)
                ->groupEnd();
        }

        if (!empty($compType)) {
            $query = $query->where('staff_competition_type', $compType);
        }

        $student_staff = $query->orderBy('staff_competition_type', 'ASC')->orderBy('staff_firstname', 'ASC')->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Page Setup to A4 Portrait and Fit to Page Width
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setRight(0.7);
        $sheet->getPageMargins()->setLeft(0.7);
        $sheet->getPageMargins()->setBottom(0.75);

        // Default style for the whole sheet
        $spreadsheet->getDefaultStyle()->getFont()->setName('TH SarabunPSK')->setSize(16);

        // Titles
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'รายชื่อนักเรียนและบุคคลทั่วไปช่วยปฏิบัติงาน');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', 'งานสัปดาห์วิทยาศาสตร์ ประจำปี ' . $selectedYear);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:E3');
        $compText = !empty($compType) ? $compType : '.........................................................';
        $sheet->setCellValue('A3', 'รายการแข่งขัน / ฝ่ายงาน : ' . $compText);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $startRow = 5;

        // Headers
        $headers = ['ลำดับที่', 'ชื่อ - นามสกุล', 'ระดับชั้น / สถานะ', 'ฝ่ายงาน / รายการ', 'หมายเหตุ (ลายมือชื่อ)'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $startRow, $header);
            $col++;
        }

        // Header styles
        $headerRange = 'A' . $startRow . ':E' . $startRow;
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Data
        $rowNum = $startRow + 1;
        $i = 1;
        if (empty($student_staff)) {
            $sheet->mergeCells('A' . $rowNum . ':E' . $rowNum);
            $sheet->setCellValue('A' . $rowNum, '- ไม่มีข้อมูลผู้ช่วยงาน -');
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $rowNum++;
        } else {
            foreach ($student_staff as $st) {
                $sheet->setCellValue('A' . $rowNum, $i++);
                $sheet->setCellValue('B' . $rowNum, $st['staff_prefix'] . $st['staff_firstname'] . ' ' . $st['staff_lastname']);
                $sheet->setCellValue('C' . $rowNum, $st['staff_class']);
                $sheet->setCellValue('D' . $rowNum, $st['staff_competition_type']);
                $sheet->setCellValue('E' . $rowNum, '');
                
                $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                
                $rowNum++;
            }
        }

        // Borders for all cells in the table
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A' . $startRow . ':E' . ($rowNum - 1))->applyFromArray($styleArray);

        // Set row heights for signatures
        for ($r = $startRow + 1; $r < $rowNum; $r++) {
            $sheet->getRowDimension($r)->setRowHeight(30);
        }

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(35);
        $sheet->getColumnDimension('E')->setWidth(25);

        $filename = "student_staff_" . date('Ymd_His') . ".xlsx";
        
        ob_end_clean(); // Clear any previous output buffers to avoid corrupt excel file
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function studentStaffStore()
    {
        $access = $this->checkAccess(true);
        if ($access !== true)
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $compType = $this->request->getPost('staff_competition_type');
        if (empty($compType)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาเลือกรายการแข่งขัน']);
        }

        // Check if user has permission to manage student staff for this competition
        if (!$this->canAccessCompetition($compType)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์จัดการข้อมูลรายการแข่งขันนี้']);
        }

        $staffList = $this->request->getPost('staff');
        if (empty($staffList) || !is_array($staffList)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลนักเรียนช่วยงานอย่างน้อย 1 คน']);
        }

        $staffModel = new ScienceWeekStudentStaffModel();
        $selectedYear = $this->getSelectedYear();
        $userId = session()->get('u_id');

        $insertedCount = 0;
        foreach ($staffList as $row) {
            $prefix    = trim($row['prefix'] ?? '');
            $firstname = trim($row['firstname'] ?? '');
            $lastname  = trim($row['lastname'] ?? '');
            $class     = trim($row['class'] ?? '');

            if ($prefix === '' || $firstname === '' || $lastname === '' || $class === '') {
                continue; // skip incomplete rows
            }

            $dataInsert = [
                'staff_year'             => $selectedYear,
                'staff_competition_type' => $compType,
                'staff_prefix'           => $prefix,
                'staff_firstname'        => $firstname,
                'staff_lastname'         => $lastname,
                'staff_class'            => $class,
                'staff_created_by'       => $userId
            ];
            $staffModel->insert($dataInsert);
            $insertedCount++;
        }

        if ($insertedCount > 0) {
            return $this->response->setJSON(['status' => 'success', 'message' => "เพิ่มข้อมูลนักเรียนช่วยงานสำเร็จ {$insertedCount} คน"]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลที่สมบูรณ์เพื่อบันทึก']);
    }

    public function studentStaffUpdate($id)
    {
        $access = $this->checkAccess(true);
        if ($access !== true)
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $staffModel = new ScienceWeekStudentStaffModel();
        $staff = $staffModel->find($id);
        if (!$staff) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลที่ต้องการแก้ไข']);
        }

        // Check original competition access
        if (!$this->canAccessCompetition($staff['staff_competition_type'])) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์จัดการข้อมูลรายการแข่งขันนี้']);
        }

        $newCompType = $this->request->getPost('staff_competition_type');
        if (empty($newCompType)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาเลือกรายการแข่งขัน']);
        }

        // Check new competition access
        if (!$this->canAccessCompetition($newCompType)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์จัดการข้อมูลรายการแข่งขันใหม่นี้']);
        }

        $staffList = $this->request->getPost('staff');
        $row = is_array($staffList) ? reset($staffList) : null;

        if (!$row || empty($row['firstname']) || empty($row['lastname']) || empty($row['prefix']) || empty($row['class'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        }

        $dataUpdate = [
            'staff_competition_type' => $newCompType,
            'staff_prefix'           => trim($row['prefix']),
            'staff_firstname'        => trim($row['firstname']),
            'staff_lastname'         => trim($row['lastname']),
            'staff_class'            => trim($row['class'])
        ];

        if ($staffModel->update($id, $dataUpdate)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'อัปเดตข้อมูลนักเรียนช่วยงานสำเร็จแล้ว']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
    }

    public function studentStaffDelete($id)
    {
        $access = $this->checkAccess(true);
        if ($access !== true)
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $staffModel = new ScienceWeekStudentStaffModel();
        $staff = $staffModel->find($id);
        if (!$staff) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลที่ต้องการลบ']);
        }

        // Check competition access
        if (!$this->canAccessCompetition($staff['staff_competition_type'])) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์จัดการข้อมูลรายการแข่งขันนี้']);
        }

        if ($staffModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบข้อมูลนักเรียนช่วยงานสำเร็จแล้ว']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล']);
    }

    public function searchStudentStaff()
    {
        $name = trim($this->request->getVar('name') ?? '');
        if (empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณากรอกชื่อหรือนามสกุลเพื่อค้นหา']);
        }

        $parts = preg_split('/\s+/', $name);
        $staffModel = new ScienceWeekStudentStaffModel();
        $query = $staffModel->where('staff_year', $this->getSelectedYear());

        if (count($parts) >= 2) {
            $firstname = $parts[0];
            $lastname = $parts[1];
            $query = $query->groupStart()
                ->like('staff_firstname', $firstname)
                ->like('staff_lastname', $lastname)
                ->groupEnd();
        } else {
            $query = $query->groupStart()
                ->like('staff_firstname', $name)
                ->orLike('staff_lastname', $name)
                ->groupEnd();
        }

        $results = $query->findAll();
        
        if (empty($results)) {
            return $this->response->setJSON(['status' => 'empty', 'message' => 'ไม่พบรายชื่อนักเรียนช่วยงานในระบบ']);
        }

        $data = [];
        foreach ($results as $r) {
            $data[] = [
                'id' => $r['staff_id'],
                'prefix' => $r['staff_prefix'],
                'firstname' => $r['staff_firstname'],
                'lastname' => $r['staff_lastname'],
                'class' => $r['staff_class'],
                'comp' => $r['staff_competition_type'],
                'download_url' => base_url("science-week/certificate/download/student_staff/{$r['staff_id']}")
            ];
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }
}


