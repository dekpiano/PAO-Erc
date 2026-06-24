<?php

namespace App\Controllers;

use App\Models\ScienceWeekRegistrationModel;
use App\Models\ScienceWeekCompetitionModel;
use App\Models\ScienceWeekScheduleModel;
use App\Models\ScienceWeekEvaluationModel;
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
            if (!$db->fieldExists('comp_rule_file', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_rule_file VARCHAR(255) NULL AFTER comp_description");
            }
            if (!$db->fieldExists('comp_rule_link', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_rule_link VARCHAR(255) NULL AFTER comp_rule_file");
            }
            if (!$db->fieldExists('comp_custom_fields', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_custom_fields TEXT NULL AFTER comp_color");
            }
            if (!$db->fieldExists('comp_limit', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_limit INT NULL DEFAULT 0 AFTER comp_custom_fields");
            }
            if (!$db->fieldExists('comp_member_limit', 'Tb_ScienceWeek_Competitions')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Competitions ADD COLUMN comp_member_limit INT NULL DEFAULT 0 AFTER comp_limit");
            }
        }
        if ($db->tableExists('Tb_ScienceWeek_Registrations')) {
            if (!$db->fieldExists('reg_custom_fields', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_custom_fields TEXT NULL AFTER reg_status");
            }
            if (!$db->fieldExists('reg_school_province', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_school_province VARCHAR(100) NULL AFTER reg_school_name");
            }
            if (!$db->fieldExists('reg_score', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_score DECIMAL(5,2) NULL DEFAULT NULL AFTER reg_custom_fields");
            }
            if (!$db->fieldExists('reg_rank', 'Tb_ScienceWeek_Registrations')) {
                $db->query("ALTER TABLE Tb_ScienceWeek_Registrations ADD COLUMN reg_rank VARCHAR(100) NULL DEFAULT NULL AFTER reg_score");
            }
        }
        if (!$db->tableExists('Tb_ScienceWeek_Evaluations')) {
            $db->query("CREATE TABLE Tb_ScienceWeek_Evaluations (
                eval_id INT AUTO_INCREMENT PRIMARY KEY,
                eval_name VARCHAR(255) NOT NULL,
                eval_school VARCHAR(255) NULL,
                eval_province VARCHAR(100) NULL,
                eval_phone VARCHAR(20) NULL,
                eval_feedback TEXT NULL,
                eval_code VARCHAR(50) NOT NULL UNIQUE,
                eval_created_at DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    /**
     * หน้าประชาสัมพันธ์ (Landing Page) ธีมวิทยาศาสตร์
     */
    public function index()
    {
        $settingsModel = new \App\Models\SettingsModel();
        $targetDate = $settingsModel->getVal('science_week_countdown') ?: '2026-08-18T09:00:00';

        // Query real database stats
        $db = \Config\Database::connect();

        // 1. STEAM Branches count (distinct comp_color or fixed count of STEAM = 5)
        $steamCountQuery = $db->query("SELECT COUNT(DISTINCT comp_color) as total FROM Tb_ScienceWeek_Competitions");
        $steamCount = $steamCountQuery->getRowArray()['total'] ?? 0;
        if ($steamCount === 0)
            $steamCount = 5; // fallback to 5 STEAM branches

        // 2. Competition Types count
        $compCount = $this->compModel->countAllResults();

        // 3. Registered Teams count (where status is not rejected)
        $teamCount = $this->regModel->where('reg_status !=', 'rejected')->countAllResults();

        // 4. Participating Students count (sum of all members array size)
        $registrations = $this->regModel->where('reg_status !=', 'rejected')->findAll();
        $studentCount = 0;
        foreach ($registrations as $reg) {
            $members = json_decode($reg['reg_members'], true) ?: [];
            $studentCount += count($members);
        }

        $data['title'] = 'งานสัปดาห์วิทยาศาสตร์ 2026 - กองการศึกษา อบจ.นครสวรรค์ & โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ ';
        $data['countdown_date'] = $targetDate;
        $data['schedules'] = $this->schModel->orderBy('sch_id', 'ASC')->findAll();

        $data['stat_steam'] = $steamCount;
        $data['stat_comp'] = $compCount;
        $data['stat_team'] = $teamCount;
        $data['stat_student'] = $studentCount;

        return view('science_week/index', $data);
    }

    /**
     * หน้าหลักเลือกประเภทการแข่งขัน (การ์ดเลือก)
     */
    public function register()
    {
        $data['title'] = 'ประเภทการแข่งขันทั้งหมด | งานสัปดาห์วิทยาศาสตร์';
        $competitions = $this->compModel->orderBy('comp_id', 'ASC')->findAll();

        foreach ($competitions as &$comp) {
            $comp['reg_count'] = $this->regModel->where('reg_competition_type', $comp['comp_name'])
                ->where('reg_status !=', 'rejected')
                ->countAllResults();
        }

        $data['competitions'] = $competitions;
        return view('science_week/register', $data);
    }

    /**
     * หน้ากรอกรายละเอียดฟอร์มสมัครการแข่งขัน
     */
    public function registerForm()
    {
        $type = $this->request->getGet('type');
        if (empty($type)) {
            return redirect()->to(base_url('science-week/register'));
        }

        $comp = $this->compModel->where('comp_name', $type)->first();
        if (!$comp) {
            return redirect()->to(base_url('science-week/register'));
        }

        // Quota Limit Check
        $activeRegCount = $this->regModel->where('reg_competition_type', $comp['comp_name'])
            ->where('reg_status !=', 'rejected')
            ->countAllResults();
        if (!empty($comp['comp_limit']) && $comp['comp_limit'] > 0 && $activeRegCount >= $comp['comp_limit']) {
            return redirect()->to(base_url('science-week/register'))->with('error', 'ขออภัย การแข่งขันนี้มีผู้สมัครครบเต็มจำนวนโควตาแล้ว');
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
        $compType = $this->request->getPost('competition_type');
        $comp = $this->compModel->where('comp_name', $compType)->first();
        
        // Quota Limit Check
        $activeRegCount = $this->regModel->where('reg_competition_type', $compType)
            ->where('reg_status !=', 'rejected')
            ->countAllResults();
        if (!empty($comp['comp_limit']) && $comp['comp_limit'] > 0 && $activeRegCount >= $comp['comp_limit']) {
            $msg = 'ขออภัย การแข่งขันนี้มีผู้สมัครครบเต็มจำนวนโควตาแล้ว';
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->to(base_url('science-week/register'))->with('error', $msg);
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

        $members = [];
        foreach ($memberNamesRaw as $idx => $name) {
            $trimmedName = trim($name);
            if ($trimmedName !== '') {
                $prefix = trim($memberPrefixesRaw[$idx] ?? '');
                $members[] = ($prefix !== '' ? $prefix . ' ' : '') . $trimmedName;
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
        $comp = $this->compModel->where('comp_name', $compType)->first();
        if (!$comp) {
            $msg = 'ไม่พบข้อมูลประเภทการแข่งขัน';
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->withInput()->with('error', $msg);
        }

        // Quota Limit Check
        $activeRegCount = $this->regModel->where('reg_competition_type', $comp['comp_name'])
            ->where('reg_status !=', 'rejected')
            ->countAllResults();
        if (!empty($comp['comp_limit']) && $comp['comp_limit'] > 0 && $activeRegCount >= $comp['comp_limit']) {
            $msg = 'ขออภัย การแข่งขันนี้มีผู้สมัครครบเต็มจำนวนโควตาแล้ว';
            return $this->request->isAJAX() ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->to(base_url('science-week/register'))->with('error', $msg);
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
            'reg_code' => $regCode,
            'reg_competition_type' => $this->request->getPost('competition_type'),
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

    /**
     * หน้าบัตรยืนยันการสมัครแข่งขันสำเร็จ
     */
    public function success($regCode)
    {
        $registration = $this->regModel->where('reg_code', $regCode)->first();
        if (!$registration) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบข้อมูลการลงทะเบียน');
        }

        $data['title'] = "ใบสมัครสำเร็จ {$regCode} | งานสัปดาห์วิทยาศาสตร์";
        $data['reg'] = $registration;

        return view('science_week/success', $data);
    }

    /**
     * ตรวจสอบความถูกต้องของสิทธิ์การใช้งานของแอดมิน/เจ้าหน้าที่
     */
    private function checkAccess()
    {
        $u_id = session()->get('u_id');
        if (!$u_id) {
            return redirect()->to(base_url('auth/login'))->with('error', 'กรุณาเข้าสู่ระบบก่อนใช้งาน');
        }

        $roles = session()->get('u_role') ?? '';
        if (strpos($roles, 'superadmin') === false && strpos($roles, 'admin') === false && strpos($roles, 'science_week') === false) {
            return redirect()->to(base_url('/'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบสัปดาห์วิทยาศาสตร์');
        }
        return true;
    }

    /**
     * ระบบจัดการรายชื่อผู้สมัคร (Staff/Admin)
     */
    public function adminIndex()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $searchTerm = $this->request->getGet('search');
        $compType = $this->request->getGet('competition_type');
        $status = $this->request->getGet('status');

        $query = $this->regModel;

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

        $data['title'] = "จัดการผู้สมัครแข่งขัน งานสัปดาห์วิทยาศาสตร์ | อบจ.นครสวรรค์";
        $data['registrations'] = $query->orderBy('reg_created_at', 'DESC')->paginate(20, 'default');
        $data['pager'] = $this->regModel->pager;

        $data['search'] = $searchTerm;
        $data['compType_active'] = $compType;
        $data['status_active'] = $status;
        $data['fullname'] = session()->get('u_fullname');
        $data['competitions'] = $this->compModel->orderBy('comp_id', 'ASC')->findAll();

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

        $searchTerm = $this->request->getGet('search');
        $compType = $this->request->getGet('competition_type');

        // Only rank approved registrations
        $query = $this->regModel->where('reg_status', 'approved');

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

        $data['title'] = "จัดการผลคะแนนและอันดับรางวัล | อบจ.นครสวรรค์";
        $data['registrations'] = $query->orderBy('reg_competition_type', 'ASC')
                                       ->orderBy('CASE WHEN reg_rank IS NULL OR reg_rank = \'\' THEN 1 ELSE 0 END', 'ASC')
                                       ->orderBy('reg_score', 'DESC')
                                       ->orderBy('reg_id', 'ASC')
                                       ->findAll();

        $data['search'] = $searchTerm;
        $data['compType_active'] = $compType;
        $data['fullname'] = session()->get('u_fullname');
        $data['competitions'] = $this->compModel->orderBy('comp_id', 'ASC')->findAll();

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

        $status = $this->request->getPost('status');
        if (!in_array($status, ['pending', 'approved', 'rejected'])) {
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

        $members = [];
        foreach ($memberNamesRaw as $idx => $name) {
            $trimmedName = trim($name);
            if ($trimmedName !== '') {
                $prefix = trim($memberPrefixesRaw[$idx] ?? '');
                $members[] = ($prefix !== '' ? $prefix . ' ' : '') . $trimmedName;
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
            'reg_members' => json_encode(array_values($members), JSON_UNESCAPED_UNICODE),
            'reg_advisors' => json_encode(array_values($advisors), JSON_UNESCAPED_UNICODE),
            'reg_contact_phone' => $this->request->getPost('contact_phone'),
            'reg_contact_email' => $this->request->getPost('contact_email') ?: null,
            'reg_status' => $this->request->getPost('status'),
            'reg_custom_fields' => !empty($customFieldsAnswers) ? json_encode($customFieldsAnswers, JSON_UNESCAPED_UNICODE) : null
        ];

        if ($this->regModel->update($id, $dataUpdate)) {
            return redirect()->to(base_url('staff/science-week'))->with('success', 'แก้ไขข้อมูลผู้สมัครสำเร็จเรียบร้อยแล้ว');
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

        $query = $this->regModel;

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

        $results = $query->orderBy('reg_created_at', 'ASC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'รายชื่อผู้สมัครเข้าร่วมแข่งขันกิจกรรมวันสัปดาห์วิทยาศาสตร์');
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A2', 'พิมพ์เมื่อวันที่: ' . date('d/m/Y H:i') . ' น.');
        $sheet->mergeCells('A2:M2');

        $sheet->setCellValue('A4', 'ลำดับ');
        $sheet->setCellValue('B4', 'รหัสใบสมัคร');
        $sheet->setCellValue('C4', 'ประเภทการแข่งขัน');
        $sheet->setCellValue('D4', 'ชื่อโรงเรียน');
        $sheet->setCellValue('E4', 'จังหวัด');
        $sheet->setCellValue('F4', 'ชื่อทีม');
        $sheet->setCellValue('G4', 'สมาชิกในทีม');
        $sheet->setCellValue('H4', 'ครูผู้ควบคุม/ที่ปรึกษา');
        $sheet->setCellValue('I4', 'เบอร์โทรติดต่อ');
        $sheet->setCellValue('J4', 'สถานะการสมัคร');
        $sheet->setCellValue('K4', 'คะแนน');
        $sheet->setCellValue('L4', 'รางวัลที่ได้รับ');
        $sheet->setCellValue('M4', 'ข้อมูลเพิ่มเติม (Custom Fields)');

        $rowIdx = 5;
        $i = 1;
        foreach ($results as $reg) {
            $members = implode(', ', json_decode($reg['reg_members'], true) ?? []);
            $advisors = implode(', ', json_decode($reg['reg_advisors'], true) ?? []);

            $statusText = 'รอการตรวจสอบ';
            if ($reg['reg_status'] == 'approved')
                $statusText = 'อนุมัติแล้ว';
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
            $sheet->setCellValue('D' . $rowIdx, $reg['reg_school_name']);
            $sheet->setCellValue('E' . $rowIdx, $reg['reg_school_province'] ?: '-');
            $sheet->setCellValue('F' . $rowIdx, $reg['reg_team_name'] ?: '-');
            $sheet->setCellValue('G' . $rowIdx, $members);
            $sheet->setCellValue('H' . $rowIdx, $advisors);
            $sheet->setCellValue('I' . $rowIdx, $reg['reg_contact_phone']);
            $sheet->setCellValue('J' . $rowIdx, $statusText);
            $sheet->setCellValue('K' . $rowIdx, $reg['reg_score'] !== null ? $reg['reg_score'] : '-');
            $sheet->setCellValue('L' . $rowIdx, $reg['reg_rank'] ?: '-');
            $sheet->setCellValue('M' . $rowIdx, $customText ?: '-');
            $rowIdx++;
        }

        foreach (range('A', 'M') as $col) {
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
     * หน้าตรวจสอบสถานะการสมัครแข่งขัน (Public)
     */
    public function checkStatus()
    {
        $data['title'] = 'ตรวจสอบสถานะการสมัครแข่งขัน | งานสัปดาห์วิทยาศาสตร์';
        $data['search'] = '';
        $data['results'] = null;
        return view('science_week/check_status', $data);
    }

    /**
     * ค้นหาและตรวจสอบสถานะการสมัครแข่งขัน (Public)
     */
    public function searchStatus()
    {
        $search = $this->request->getGet('search');

        $results = null;
        if (!empty($search)) {
            $results = $this->regModel->groupStart()
                ->where('reg_code', trim($search))
                ->orLike('reg_school_name', trim($search))
                ->orLike('reg_team_name', trim($search))
                ->groupEnd()
                ->orderBy('reg_created_at', 'DESC')
                ->findAll();
        }

        $data['title'] = 'ผลการตรวจสอบสถานะการสมัครแข่งขัน | งานสัปดาห์วิทยาศาสตร์';
        $data['search'] = $search;
        $data['results'] = $results;

        return view('science_week/check_status', $data);
    }

    /**
     * รายการประเภทการแข่งขัน ทั้งหมด
     */
    public function compIndex()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $data['title'] = 'จัดการประเภทการแข่งขัน | งานสัปดาห์วิทยาศาสตร์';
        $data['competitions'] = $this->compModel->orderBy('comp_id', 'ASC')->findAll();
        $data['fullname'] = session()->get('u_fullname');

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
            'comp_rule_file' => 'max_size[comp_rule_file,10240]|ext_in[comp_rule_file,pdf,doc,docx,zip]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

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

        $dataInsert = [
            'comp_name' => $this->request->getPost('comp_name'),
            'comp_icon' => $this->request->getPost('comp_icon'),
            'comp_level' => $this->request->getPost('comp_level'),
            'comp_description' => $this->request->getPost('comp_description') ?: null,
            'comp_rule_file' => $ruleFilePath,
            'comp_rule_link' => $this->request->getPost('comp_rule_link') ?: null,
            'comp_color' => $this->request->getPost('comp_color'),
            'comp_custom_fields' => $customFieldsJson,
            'comp_limit' => (int) $this->request->getPost('comp_limit'),
            'comp_member_limit' => (int) $this->request->getPost('comp_member_limit')
        ];

        if ($this->compModel->insert($dataInsert)) {
            return redirect()->to(base_url('staff/science-week/competitions'))->with('success', 'เพิ่มประเภทการแข่งขันสำเร็จ');
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

        $data['title'] = 'แก้ไขประเภทการแข่งขัน | งานสัปดาห์วิทยาศาสตร์';
        $data['comp'] = $comp;
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

        $rules = [
            'comp_name' => 'required|min_length[3]|max_length[255]',
            'comp_icon' => 'required',
            'comp_level' => 'required',
            'comp_description' => 'permit_empty',
            'comp_color' => 'required',
            'comp_limit' => 'permit_empty|integer',
            'comp_member_limit' => 'permit_empty|integer',
            'comp_rule_file' => 'max_size[comp_rule_file,10240]|ext_in[comp_rule_file,pdf,doc,docx,zip]'
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

        $dataUpdate = [
            'comp_name' => $this->request->getPost('comp_name'),
            'comp_icon' => $this->request->getPost('comp_icon'),
            'comp_level' => $this->request->getPost('comp_level'),
            'comp_description' => $this->request->getPost('comp_description') ?: null,
            'comp_rule_file' => $ruleFilePath,
            'comp_rule_link' => $this->request->getPost('comp_rule_link') ?: null,
            'comp_color' => $this->request->getPost('comp_color'),
            'comp_custom_fields' => $customFieldsJson,
            'comp_limit' => (int) $this->request->getPost('comp_limit'),
            'comp_member_limit' => (int) $this->request->getPost('comp_member_limit')
        ];

        if ($this->compModel->update($id, $dataUpdate)) {
            return redirect()->to(base_url('staff/science-week/competitions'))->with('success', 'แก้ไขประเภทการแข่งขันสำเร็จ');
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

        if ($this->compModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'ลบประเภทการแข่งขันสำเร็จ'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }

    /**
     * หน้าจอตั้งค่าระบบนับถอยหลัง
     */
    public function adminSettings()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $settingsModel = new \App\Models\SettingsModel();
        $targetDate = $settingsModel->getVal('science_week_countdown') ?: '2026-08-18T09:00:00';

        $data['title'] = 'ตั้งค่าระบบนับถอยหลัง | งานสัปดาห์วิทยาศาสตร์';
        $data['countdown_date'] = $targetDate;
        $data['fullname'] = session()->get('u_fullname');

        return view('science_week/settings', $data);
    }

    /**
     * บันทึกข้อมูลการตั้งค่าระบบนับถอยหลัง
     */
    public function settingsSave()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $targetDate = $this->request->getPost('countdown_date');

        $settingsModel = new \App\Models\SettingsModel();
        $existing = $settingsModel->where('s_key', 'science_week_countdown')->first();

        $dataSave = [
            's_key' => 'science_week_countdown',
            's_value' => $targetDate,
            's_description' => 'วันเวลากำหนดการเริ่มงานสัปดาห์วิทยาศาสตร์ สำหรับระบบนับถอยหลัง'
        ];

        if ($existing) {
            $settingsModel->update($existing['s_id'], $dataSave);
        } else {
            $settingsModel->insert($dataSave);
        }

        return redirect()->to(base_url('staff/science-week/settings'))->with('success', 'บันทึกวันเวลานับถอยหลังเรียบร้อยแล้ว');
    }

    /**
     * รายการกำหนดการกิจกรรมทั้งหมด
     */
    public function schIndex()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $data['title'] = 'จัดการกำหนดการกิจกรรม | งานสัปดาห์วิทยาศาสตร์';
        $data['schedules'] = $this->schModel->orderBy('sch_id', 'ASC')->findAll();
        $data['fullname'] = session()->get('u_fullname');

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
            'sch_date' => $this->request->getPost('sch_date'),
            'sch_title' => $this->request->getPost('sch_title'),
            'sch_description' => $this->request->getPost('sch_description') ?: null,
            'sch_color' => $this->request->getPost('sch_color')
        ];

        if ($this->schModel->insert($dataInsert)) {
            return redirect()->to(base_url('staff/science-week/schedules'))->with('success', 'เพิ่มกำหนดการเรียบร้อยแล้ว');
        }

        return redirect()->back()->withInput()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    /**
     * หน้าจอแก้ไขกำหนดการ
     */
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
            return redirect()->to(base_url('staff/science-week/schedules'))->with('success', 'แก้ไขกำหนดการเรียบร้อยแล้ว');
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
        $compType = $this->request->getGet('competition_type');
        $searchTerm = $this->request->getGet('search');

        $query = $this->regModel->where('reg_status', 'approved'); // Only show approved/valid teams

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
        $data['competitions'] = $this->compModel->orderBy('comp_id', 'ASC')->findAll();
        $data['publish_results'] = $isPublished;

        return view('science_week/results', $data);
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

        $data['title'] = 'ตั้งค่าการออกเกียรติบัตร | งานสัปดาห์วิทยาศาสตร์';
        $data['comp_config'] = $compConfigJson ? json_decode($compConfigJson, true) : [];
        $data['trainer_config'] = $trainerConfigJson ? json_decode($trainerConfigJson, true) : [];
        $data['eval_config'] = $evalConfigJson ? json_decode($evalConfigJson, true) : [];

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
        if (!in_array($type, ['competition', 'trainer', 'evaluation'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ประเภทเกียรติบัตรไม่ถูกต้อง']);
        }

        $settingsModel = new \App\Models\SettingsModel();
        $configKey = "science_week_cert_{$type}_config";
        
        $currentConfigJson = $settingsModel->getVal($configKey);
        $config = $currentConfigJson ? json_decode($currentConfigJson, true) : [];

        // Handle File Upload
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

        // Handle Fields Coordinates
        $fields = ($type === 'competition' || $type === 'trainer')
            ? ['name', 'school', 'comp', 'rank', 'code']
            : ['name', 'text', 'date', 'code'];

        foreach ($fields as $field) {
            $config["enabled_{$field}"] = $this->request->getPost("enabled_{$field}") === '1';
            $config["x_{$field}"] = (int) $this->request->getPost("x_{$field}");
            $config["y_{$field}"] = (int) $this->request->getPost("y_{$field}");
            $config["size_{$field}"] = (int) $this->request->getPost("size_{$field}");
            $config["align_{$field}"] = $this->request->getPost("align_{$field}") ?: 'center';
            $config["color_{$field}"] = $this->request->getPost("color_{$field}") ?: '#000000';
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

    public function downloadCertificate($type, $code)
    {
        if (!in_array($type, ['competition', 'trainer', 'evaluation'])) {
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
        $compName = '';
        $rankName = '';
        $certCode = $code;
        $dateText = '';

        if ($type === 'competition' || $type === 'trainer') {
            if ($code === 'demo') {
                $recipientName = $this->request->getGet('name') ?: ($type === 'trainer' ? 'ครูสมหญิง ฝึกซ้อมดี' : 'นายสมศักดิ์ รักดี');
                $schoolName = 'โรงเรียนตัวอย่างวิทยาคม จังหวัดนครสวรรค์';
                $compName = 'การประกวด/แข่งขัน: การแข่งขันจรวดขวดน้ำประเภทสร้างสรรค์';
                $certCode = 'SW-COMP-DEMO';
                if ($type === 'trainer') {
                    $rankName = 'ในฐานะครูผู้ฝึกสอน/ควบคุมทีม ที่ได้รับรางวัลชนะเลิศ';
                } else {
                    $rankName = 'ได้รับรางวัล: รางวัลชนะเลิศ';
                }
            } else {
                $reg = $this->regModel->where('reg_code', $code)->where('reg_status', 'approved')->first();
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
                        $members = json_decode($reg['reg_members'], true) ?: [];
                        $recipientName = !empty($members) ? $members[0] : '';
                    }
                }

                $schoolName = $reg['reg_school_name'] ? "โรงเรียน{$reg['reg_school_name']}" : '';
                if (!empty($reg['reg_school_province']) && $reg['reg_school_province'] !== '-') {
                    $schoolName .= " จังหวัด{$reg['reg_school_province']}";
                }

                $compName = "การประกวด/แข่งขัน: " . $reg['reg_competition_type'];
                
                if ($type === 'trainer') {
                    if (!empty($reg['reg_rank'])) {
                        $rankName = "ในฐานะครูผู้ฝึกสอน/ควบคุมทีม ที่ได้รับ" . $reg['reg_rank'];
                    } else {
                        $rankName = "ในฐานะครูผู้ฝึกสอน/ควบคุมทีม ที่เข้าร่วมการแข่งขัน";
                    }
                } else {
                    if (!empty($reg['reg_rank'])) {
                        $rankName = "ได้รับรางวัล: " . $reg['reg_rank'];
                    } else {
                        $rankName = "ได้เข้าร่วมการประกวดและแข่งขัน";
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

                $recipientName = $eval['eval_name'];
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
            return $this->response->setBody('รองรับเฉพาะไฟล์รูปภาพ PNG หรือ JPEG เท่านั้น')->setStatusCode(400);
        }

        if (!$image) {
            return $this->response->setBody('ไม่สามารถสร้างรูปภาพเกียรติบัตรได้')->setStatusCode(500);
        }

        // Enable alpha blending and save alpha options
        imagealphablending($image, true);
        imagesavealpha($image, true);

        // Font Path - Use thsarabunnew_bold.ttf (supports PUA mapping for proper Thai vowel/tone mark rendering in GD)
        $fontPath = FCPATH . 'assets/fonts/thsarabunnew_bold.ttf';
        if (!file_exists($fontPath)) {
            return $this->response->setBody('ไม่พบไฟล์ฟอนต์เกียรติบัตรในระบบ')->setStatusCode(500);
        }

        // Verify the font is actually readable by GD
        $testBbox = @imagettfbbox(12, 0, $fontPath, 'test');
        if ($testBbox === false) {
            return $this->response->setBody('ไฟล์ฟอนต์เสียหาย ไม่สามารถใช้งานได้ กรุณาติดต่อผู้ดูแลระบบ')->setStatusCode(500);
        }

        // Draw Text Elements
        if ($type === 'competition' || $type === 'trainer') {
            $drawFields = [
                'name' => $recipientName,
                'school' => $schoolName,
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

        foreach ($drawFields as $field => $text) {
            $enabled = !isset($config["enabled_{$field}"]) || $config["enabled_{$field}"];
            if (!$enabled || empty($text)) {
                continue;
            }

            // GD imagettftext expects points, whereas CSS preview uses pixels.
            // 1 point is approx 96/72 = 1.33 pixels, so points = pixels * 0.75.
            $fontSize = ($config["size_{$field}"] ?? 24) * 0.75;
            $x = $config["x_{$field}"] ?? 500;
            $y = $config["y_{$field}"] ?? 500;
            $align = $config["align_{$field}"] ?? 'center';
            $hexColor = $config["color_{$field}"] ?? '#000000';

            $rgb = $this->hexToRgb($hexColor);
            $colorAlloc = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);

            // Adjust Thai text vowels and tone marks for GD rendering using thsarabunnew's PUA mappings to prevent overlapping/dropping
            $text = $this->adjustThaiText($text);

            // Calculate text offset for alignment
            $bbox = imagettfbbox($fontSize, 0, $fontPath, $text);
            $textWidth = abs($bbox[2] - $bbox[0]);

            if ($align === 'center') {
                $drawX = $x - ($textWidth / 2);
            } elseif ($align === 'right') {
                $drawX = $x - $textWidth;
            } else {
                $drawX = $x;
            }

            imagettftext($image, $fontSize, 0, $drawX, $y, $colorAlloc, $fontPath, $text);
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
            $members = json_decode($reg['reg_members'], true) ?: [];
            $advisors = json_decode($reg['reg_advisors'], true) ?: [];
        } else {
            $db = \Config\Database::connect();
            $eval = $db->table('Tb_ScienceWeek_Evaluations')->where('eval_code', $code)->get()->getRowArray();
            if (!$eval) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบรหัสผู้ทำแบบประเมิน');
            }
            $members = [$eval['eval_name']];
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
                ['key' => 'phone', 'label' => 'เบอร์โทรศัพท์ติดต่อ', 'placeholder' => '08XXXXXXXX', 'required' => true, 'type' => 'tel'],
                ['key' => 'school', 'label' => 'สถานศึกษา / สังกัด', 'placeholder' => 'โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์', 'required' => false, 'type' => 'text'],
                ['key' => 'province', 'label' => 'จังหวัด', 'placeholder' => 'นครสวรรค์', 'required' => false, 'type' => 'text']
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
        $data['title'] = 'แบบประเมินความพึงพอใจ | งานสัปดาห์วิทยาศาสตร์';
        $data['form_config'] = $this->getEvaluationConfig();
        return view('science_week/evaluation_form', $data);
    }

    /**
     * บันทึกแบบประเมินจากหน้าบ้าน (AJAX - Dynamic)
     */
    public function storeEvaluation()
    {
        $config = $this->getEvaluationConfig();

        // สร้างกฎการตรวจสอบความถูกต้องแบบไดนามิก
        $rules = [
            'fullname' => 'required|min_length[3]|max_length[255]',
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
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วนและถูกต้องตามรูปแบบ'
            ]);
        }

        $fullname = trim($this->request->getPost('fullname'));
        
        // ดึงข้อมูลฟิลด์ไดนามิก
        $fieldsPost = $this->request->getPost('fields') ?: [];
        $extractedFields = [];
        foreach ($config['fields'] as $field) {
            $extractedFields[$field['key']] = trim($fieldsPost[$field['key']] ?? '');
        }

        // ดึงคะแนนการประเมิน
        $ratingsPost = $this->request->getPost('ratings') ?: [];
        $extractedRatings = [];
        foreach ($config['questions'] as $q) {
            $extractedRatings[$q['key']] = (int)($ratingsPost[$q['key']] ?? 0);
        }

        $comments = trim($this->request->getPost('comments')) ?: '';

        // ค้นหาฟิลด์โทรศัพท์ โรงเรียน และจังหวัดเพื่อเก็บลงคอลัมน์หลักสำหรับค้นหาหลังบ้าน
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

        $evalCode = $this->evalModel->generateEvaluationCode();

        $dataInsert = [
            'eval_name'       => $fullname,
            'eval_school'     => $schoolVal,
            'eval_province'   => $provinceVal,
            'eval_phone'      => $phoneVal,
            'eval_feedback'   => json_encode($feedbackData, JSON_UNESCAPED_UNICODE),
            'eval_code'       => $evalCode,
            'eval_created_at' => date('Y-m-d H:i:s')
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
     * รายการแบบประเมินความพึงพอใจทั้งหมด (Staff/Admin)
     */
    public function evalIndex()
    {
        $access = $this->checkAccess();
        if ($access !== true)
            return $access;

        $searchTerm = $this->request->getGet('search');

        $query = $this->evalModel;

        if (!empty($searchTerm)) {
            $query = $query->groupStart()
                ->like('eval_name', $searchTerm)
                ->orLike('eval_school', $searchTerm)
                ->orLike('eval_province', $searchTerm)
                ->orLike('eval_phone', $searchTerm)
                ->orLike('eval_code', $searchTerm)
                ->groupEnd();
        }

        $data['title'] = "จัดการแบบประเมิน งานสัปดาห์วิทยาศาสตร์ | อบจ.นครสวรรค์";
        $data['evaluations'] = $query->orderBy('eval_created_at', 'DESC')->paginate(20, 'default');
        $data['pager'] = $this->evalModel->pager;
        $data['search'] = $searchTerm;

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

        return redirect()->to(base_url('staff/science-week/evaluations'))->with('success', 'บันทึกการตั้งค่าโครงสร้างฟอร์มประเมินเรียบร้อยแล้ว');
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
            return redirect()->to(base_url('staff/science-week/evaluations'))->with('error', 'ไม่พบข้อมูลแบบประเมิน');
        }

        // แกะข้อมูล JSON ของประเด็นตอบกลับ
        $feedback = json_decode($eval['eval_feedback'], true) ?: [];
        $eval['ratings'] = $feedback['ratings'] ?? [];
        $eval['comments'] = $feedback['comments'] ?? '';
        $eval['custom_fields'] = $feedback['custom_fields'] ?? [];

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
            return redirect()->to(base_url('staff/science-week/evaluations'))->with('error', 'ไม่พบข้อมูลแบบประเมิน');
        }

        $config = $this->getEvaluationConfig();

        $rules = [
            'fullname' => 'required|min_length[3]|max_length[255]',
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

        $fullname = trim($this->request->getPost('fullname'));
        
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

        $dataUpdate = [
            'eval_name'     => $fullname,
            'eval_school'   => $schoolVal,
            'eval_province' => $provinceVal,
            'eval_phone'    => $phoneVal,
            'eval_feedback' => json_encode($feedbackData, JSON_UNESCAPED_UNICODE)
        ];

        if ($this->evalModel->update($id, $dataUpdate)) {
            return redirect()->to(base_url('staff/science-week/evaluations'))->with('success', 'อัปเดตข้อมูลผู้ประเมินเรียบร้อยแล้ว');
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

        $userModel = new \App\Models\UserModel();

        // Check if email already exists
        $existingUser = $userModel->where('u_email', $email)->first();
        if ($existingUser) {
            $existingRoles = explode(',', $existingUser['u_role'] ?? '');
            $newRoles = explode(',', $role);
            $mergedRoles = array_filter(array_unique(array_merge($existingRoles, $newRoles)));

            $dataUpdate = [
                'u_role' => implode(',', $mergedRoles),
                'u_status' => 'active'
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
            'u_username' => $username,
            'u_email'    => $email,
            'u_fullname' => $fullname,
            'u_position' => null,
            'u_password' => password_hash($randomPassword, PASSWORD_DEFAULT),
            'u_role'     => $role,
            'u_status'   => 'active',
            'u_sort'     => 99
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

        // Check duplicates excluding self
        $dupEmail = $userModel->where('u_email', $email)->where('u_id !=', $id)->first();
        if ($dupEmail) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'อีเมลนี้มีในระบบแล้ว']);
        }

        $dataUpdate = [
            'u_email'    => $email,
            'u_fullname' => $fullname,
            'u_role'     => $role,
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
}
