<?php

namespace App\Controllers\Sports;

use App\Controllers\BaseController;
use App\Models\Sports\SportsCategoryModel;
use App\Models\Sports\SportsTeamModel;
use App\Models\Sports\SportsMemberModel;
use App\Models\Sports\SportsCertificateModel;

class SportsPublicController extends BaseController
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
    }

    private function generateTeamCode()
    {
        $db = \Config\Database::connect();
        do {
            $code = 'SPT-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $exists = $db->query("SELECT team_id FROM Tb_Sports_Teams WHERE team_code = ?", [$code])->getRow();
        } while (!empty($exists));

        return $code;
    }

    // Public Home
    public function index()
    {
        $db = \Config\Database::connect();
        $categories = $db->table('Tb_Sports_Categories as c')
                         ->select('c.*, COUNT(t.team_id) as registered_teams')
                         ->join('Tb_Sports_Teams as t', 't.category_id = c.category_id AND t.status != "cancelled"', 'left')
                         ->where('c.status !=', 'draft')
                         ->groupBy('c.category_id')
                         ->orderBy('c.sport_name', 'ASC')
                         ->orderBy('c.category_name', 'ASC')
                         ->get()
                         ->getResultArray();

        $data = [
            'title'      => 'ระบบลงทะเบียนแข่งขันกีฬา อบจ.นครสวรรค์ เกมส์',
            'categories' => $categories
        ];

        return view('sports/public/index', $data);
    }

    // Register Form
    public function register($categoryId)
    {
        $category = $this->catModel->find($categoryId);
        if (!$category || $category['status'] !== 'open') {
            return redirect()->to(base_url('sports'))->with('error', 'รุ่นการแข่งขันนี้ปิดรับสมัครแล้ว หรือไม่พบข้อมูล');
        }

        // 0. Check category status (open/closed/draft)
        if ($category['status'] === 'closed') {
            return redirect()->to(base_url('sports'))->with('error', '❌ ขออภัย รุ่นการแข่งขันนี้ปิดรับสมัครแล้ว');
        }
        if ($category['status'] === 'draft') {
            return redirect()->to(base_url('sports'))->with('error', '❌ รายการนี้ยังไม่เปิดให้ลงทะเบียน');
        }

        // 1. Check if category is within registration period
        $today = date('Y-m-d');
        if (!empty($category['reg_start_date']) && $today < $category['reg_start_date']) {
            return redirect()->to(base_url('sports'))->with('error', 'รุ่นการแข่งขันนี้ยังไม่เปิดรับสมัคร (เปิดรับ ' . date('d/m/Y', strtotime($category['reg_start_date'])) . ')');
        }
        if (!empty($category['reg_end_date']) && $today > $category['reg_end_date']) {
            return redirect()->to(base_url('sports'))->with('error', 'รุ่นการแข่งขันนี้สิ้นสุดระยะเวลารับสมัครแล้ว');
        }

        // 2. Check team quota limit (เกินกำหนดจำนวนทีมที่รับ)
        $currentTeams = $this->teamModel->where('category_id', $categoryId)
                                        ->where('status !=', 'cancelled')
                                        ->countAllResults();

        if (!empty($category['max_teams']) && $category['max_teams'] > 0 && $currentTeams >= $category['max_teams']) {
            return redirect()->to(base_url('sports'))->with('error', '❌ ขออภัย รุ่นการแข่งขันนี้มีผู้สมัครครบเต็มจำนวนโควตาที่กำหนดแล้ว (' . $category['max_teams'] . ' ทีม)');
        }

        $data = [
            'title'        => 'ลงทะเบียนแข่งขัน - ' . $category['sport_name'] . ' (' . $category['category_name'] . ')',
            'category'     => $category,
            'currentTeams' => $currentTeams
        ];

        return view('sports/public/register', $data);
    }

    // Submit Registration
    public function submit()
    {
        $categoryId = $this->request->getPost('category_id');
        $category   = $this->catModel->find($categoryId);

        if (!$category) {
            return redirect()->to(base_url('sports'))->with('error', 'ไม่พบข้อมูลรุ่นการแข่งขัน');
        }

        // Check if category is closed
        if ($category['status'] === 'closed' || $category['status'] === 'draft') {
            return redirect()->to(base_url('sports'))->with('error', '❌ ขออภัย รุ่นการแข่งขันนี้ปิดรับสมัครแล้ว');
        }

        // Check team quota limit on submit
        $currentTeams = $this->teamModel->where('category_id', $categoryId)
                                        ->where('status !=', 'cancelled')
                                        ->countAllResults();

        if (!empty($category['max_teams']) && $category['max_teams'] > 0 && $currentTeams >= $category['max_teams']) {
            return redirect()->to(base_url('sports'))->with('error', '❌ ขออภัย ขณะนี้มีผู้สมัครครบเต็มจำนวนโควตาแล้ว (' . $category['max_teams'] . ' ทีม)');
        }

        $schoolName   = trim($this->request->getPost('school_name'));
        $teamName     = trim($this->request->getPost('team_name')) ?: $schoolName;
        $district     = trim($this->request->getPost('district'));
        $province     = trim($this->request->getPost('province')) ?: 'ชลบุรี';
        $contactName  = trim($this->request->getPost('contact_name'));
        $contactPhone = trim($this->request->getPost('contact_phone'));
        $contactEmail = trim($this->request->getPost('contact_email'));
        $contactLine  = trim($this->request->getPost('contact_line_id'));

        if (empty($schoolName) || empty($contactName) || empty($contactPhone)) {
            return redirect()->back()->withInput()->with('error', 'กรุณากรอกข้อมูลทีมและผู้ประสานงานให้ครบถ้วน');
        }

        // Count athlete and coach members
        $members = $this->request->getPost('members') ?: [];
        $athleteCount = 0;
        $coachCount = 0;

        foreach ($members as $m) {
            if (empty($m['first_name']) || empty($m['last_name'])) continue;
            if (($m['member_type'] ?? 'athlete') === 'athlete') {
                $athleteCount++;
            } else {
                $coachCount++;
            }
        }

        // Validation for minimum and maximum athletes
        if ($athleteCount < $category['min_players']) {
            return redirect()->back()->withInput()->with('error', '⚠️ จำนวนนักกีฬาต้องมีอย่างน้อย ' . $category['min_players'] . ' คน (ปัจจุบันกรอก ' . $athleteCount . ' คน)');
        }
        if ($category['max_players'] > 0 && $athleteCount > $category['max_players']) {
            return redirect()->back()->withInput()->with('error', '⚠️ จำนวนนักกีฬาเกินกำหนด (อนุญาตสูงสุด ' . $category['max_players'] . ' คน, ปัจจุบันกรอก ' . $athleteCount . ' คน)');
        }

        // Validation for minimum and maximum coaches
        if ($coachCount < $category['min_coaches']) {
            return redirect()->back()->withInput()->with('error', '⚠️ จำนวนผู้ฝึกสอน/เจ้าหน้าที่ต้องมีอย่างน้อย ' . $category['min_coaches'] . ' คน (ปัจจุบันกรอก ' . $coachCount . ' คน)');
        }
        if ($category['max_coaches'] > 0 && $coachCount > $category['max_coaches']) {
            return redirect()->back()->withInput()->with('error', '⚠️ จำนวนผู้ฝึกสอน/เจ้าหน้าที่เกินกำหนด (อนุญาตสูงสุด ' . $category['max_coaches'] . ' คน, ปัจจุบันกรอก ' . $coachCount . ' คน)');
        }

        $teamCode = $this->generateTeamCode();
        $token    = bin2hex(random_bytes(16));

        $teamData = [
            'team_code'       => $teamCode,
            'category_id'     => $categoryId,
            'school_name'     => $schoolName,
            'team_name'       => $teamName,
            'district'        => $district,
            'province'        => $province,
            'contact_name'    => $contactName,
            'contact_phone'   => $contactPhone,
            'contact_email'   => $contactEmail,
            'contact_line_id' => $contactLine,
            'status'          => 'pending',
            'token'           => $token
        ];

        $teamId = $this->teamModel->insert($teamData);

        // Members (Athletes & Staff)
        $members = $this->request->getPost('members');
        if (is_array($members)) {
            foreach ($members as $m) {
                if (empty($m['first_name']) || empty($m['last_name'])) continue;

                $birthDate = !empty($m['birth_date']) ? $m['birth_date'] : null;
                $age = null;

                // Auto calculate age from birth date if provided
                if ($birthDate) {
                    $bTime = strtotime($birthDate);
                    if ($bTime) {
                        $age = date('Y') - date('Y', $bTime);
                        if (date('md') < date('md', $bTime)) {
                            $age--;
                        }
                    }
                } elseif (!empty($m['age'])) {
                    $age = (int)$m['age'];
                }

                $memberType = $m['member_type'] ?? 'athlete';

                // Extract age from category_name if age_max is not set
                $targetAgeMax = (int)($category['age_max'] ?? 99);
                $targetAgeMin = (int)($category['age_min'] ?? 0);
                if (($targetAgeMax === 99 || $targetAgeMax === 0) && preg_match('/\d+/', $category['category_name'], $matches)) {
                    $targetAgeMax = (int)$matches[0];
                }

                // Server-side validation for athlete age
                if ($memberType === 'athlete' && $age !== null) {
                    $effectiveMin = $targetAgeMin > 0 ? $targetAgeMin : 5;
                    if ($age <= 0) {
                        $this->teamModel->delete($teamId);
                        return redirect()->back()->withInput()->with('error', '⚠️ นักกีฬา ' . $m['first_name'] . ' ' . $m['last_name'] . ' วันเกิดไม่ถูกต้อง (คำนวณอายุได้ 0 ปี)');
                    }
                    if ($age < $effectiveMin) {
                        // Rollback team
                        $this->teamModel->delete($teamId);
                        return redirect()->back()->withInput()->with('error', '⚠️ นักกีฬา ' . $m['first_name'] . ' ' . $m['last_name'] . ' มีอายุ ' . $age . ' ปี ซึ่งน้อยกว่าเกณฑ์ขั้นต่ำของการแข่งขัน (' . $effectiveMin . ' ปี)');
                    }
                    if ($targetAgeMax < 99 && $age > $targetAgeMax) {
                        // Rollback team
                        $this->teamModel->delete($teamId);
                        return redirect()->back()->withInput()->with('error', '⚠️ นักกีฬา ' . $m['first_name'] . ' ' . $m['last_name'] . ' มีอายุ ' . $age . ' ปี ซึ่งเกินเกณฑ์สูงสุดของรุ่น ' . $category['category_name'] . ' (กำหนดอายุไม่เกิน ' . $targetAgeMax . ' ปี)');
                    }
                }

                $this->memberModel->insert([
                    'team_id'       => $teamId,
                    'category_id'   => $categoryId,
                    'member_type'   => $memberType,
                    'prefix'        => $m['prefix'] ?? 'นาย',
                    'first_name'    => trim($m['first_name']),
                    'last_name'     => trim($m['last_name']),
                    'id_card'       => trim($m['id_card'] ?? ''),
                    'birth_date'    => $birthDate,
                    'age'           => $age,
                    'gender'        => $m['gender'] ?? 'male',
                    'jersey_number' => trim($m['jersey_number'] ?? ''),
                    'position'      => trim($m['position'] ?? ''),
                    'award_level'   => 'none'
                ]);
            }
        }

        return redirect()->to(base_url('sports/success/' . $teamCode))->with('success', 'ลงทะเบียนสำเร็จ');
    }

    // Success Page
    public function success($teamCode)
    {
        $db = \Config\Database::connect();
        $team = $db->table('Tb_Sports_Teams as t')
                   ->select('t.*, c.sport_name, c.category_name, c.category_gender')
                   ->join('Tb_Sports_Categories as c', 'c.category_id = t.category_id', 'left')
                   ->where('t.team_code', $teamCode)
                   ->get()
                   ->getRowArray();

        if (!$team) {
            return redirect()->to(base_url('sports'))->with('error', 'ไม่พบข้อมูลใบสมัคร');
        }

        $members = $this->memberModel->where('team_id', $team['team_id'])->findAll();

        $data = [
            'title'   => 'ลงทะเบียนสำเร็จ - ' . $team['team_code'],
            'team'    => $team,
            'members' => $members
        ];

        return view('sports/public/success', $data);
    }

    // Public Competition Results Announcement Page
    public function results()
    {
        $db = \Config\Database::connect();
        $categoryId = $this->request->getGet('category_id');

        $categories = $db->table('Tb_Sports_Categories')
                         ->orderBy('sport_name', 'ASC')
                         ->orderBy('category_name', 'ASC')
                         ->get()
                         ->getResultArray();

        $selectedCategory = null;
        $teamsWithAwards = null;

        if (!empty($categoryId) && !empty($categories)) {
            foreach ($categories as $cat) {
                if ($cat['category_id'] == $categoryId) {
                    $selectedCategory = $cat;
                    break;
                }
            }

            if ($selectedCategory) {
                $teamsWithAwards = $db->table('Tb_Sports_Teams as t')
                                      ->select('t.*')
                                      ->where('t.category_id', $categoryId)
                                      ->where('t.status', 'approved')
                                      ->where('t.award_level !=', 'none')
                                      ->orderBy("FIELD(t.award_level, 'champion', 'runner_up_1', 'runner_up_2', 'runner_up_3', 'participation')", '', false)
                                      ->get()
                                      ->getResultArray();

                foreach ($teamsWithAwards as &$team) {
                    $team['members'] = $db->table('Tb_Sports_Members')
                                          ->where('team_id', $team['team_id'])
                                          ->orderBy('member_type', 'ASC')
                                          ->orderBy('jersey_number', 'ASC')
                                          ->get()
                                          ->getResultArray();
                }
            }
        }

        $data = [
            'title'            => 'ประกาศผลการแข่งขันกีฬา - อบจ.นครสวรรค์ เกมส์ 2569',
            'categories'       => $categories,
            'selectedCategory' => $selectedCategory,
            'teams'            => $teamsWithAwards,
            'categoryId'       => $categoryId
        ];

        return view('sports/public/results', $data);
    }

    // Status Tracking Page
    public function status()
    {
        $data = [
            'title'   => 'ตรวจสอบสถานะการสมัคร - กีฬา อบจ.นครสวรรค์ เกมส์',
            'results' => null
        ];
        return view('sports/public/status', $data);
    }

    // Search Status Process
    public function searchStatus()
    {
        $keyword = trim($this->request->getPost('keyword'));
        if (empty($keyword)) {
            return redirect()->to(base_url('sports/status'))->with('error', 'กรุณากรอกคำค้นหา (ชื่อโรงเรียน, รหัสทีม, หรือเลขบัตร ปชช.)');
        }

        $db = \Config\Database::connect();
        $results = $db->table('Tb_Sports_Teams as t')
                      ->select('t.*, c.sport_name, c.category_name, c.category_gender, COUNT(m.member_id) as total_members')
                      ->join('Tb_Sports_Categories as c', 'c.category_id = t.category_id', 'left')
                      ->join('Tb_Sports_Members as m', 'm.team_id = t.team_id', 'left')
                      ->groupStart()
                          ->like('t.school_name', $keyword)
                          ->orLike('t.team_name', $keyword)
                          ->orLike('t.team_code', $keyword)
                          ->orLike('m.id_card', $keyword)
                          ->orLike('m.first_name', $keyword)
                      ->groupEnd()
                      ->groupBy('t.team_id')
                      ->get()
                      ->getResultArray();

        $data = [
            'title'   => 'ผลการค้นหาสถานะการสมัคร - ' . $keyword,
            'results' => $results,
            'keyword' => $keyword
        ];

        return view('sports/public/status', $data);
    }

    // Public Certificate Search Page
    public function certificate()
    {
        $data = [
            'title'   => 'ค้นหาและดาวน์โหลดเกียรติบัตร - กีฬา อบจ.นครสวรรค์ เกมส์',
            'results' => null,
            'keyword' => ''
        ];
        return view('sports/public/certificate', $data);
    }

    // Search Certificate Process
    public function searchCertificate()
    {
        $keyword = trim($this->request->getPost('keyword'));
        if (empty($keyword)) {
            return redirect()->to(base_url('sports/certificate'))->with('error', 'กรุณากรอกชื่อ-นามสกุล, เลขบัตรประชาชน หรือชื่อโรงเรียน');
        }

        $db = \Config\Database::connect();
        $results = $db->table('Tb_Sports_Members as m')
                      ->select('m.*, t.team_code, t.school_name, t.team_name, t.status as team_status, t.award_level as team_award, c.sport_name, c.category_name, c.category_gender')
                      ->join('Tb_Sports_Teams as t', 't.team_id = m.team_id', 'left')
                      ->join('Tb_Sports_Categories as c', 'c.category_id = m.category_id', 'left')
                      ->where('t.status', 'approved')
                      ->groupStart()
                          ->like('m.first_name', $keyword)
                          ->orLike('m.last_name', $keyword)
                          ->orLike('m.id_card', $keyword)
                          ->orLike('t.school_name', $keyword)
                          ->orLike('t.team_name', $keyword)
                          ->orLike('t.team_code', $keyword)
                      ->groupEnd()
                      ->orderBy('m.member_id', 'DESC')
                      ->get()
                      ->getResultArray();

        $data = [
            'title'   => 'ผลการค้นหาเกียรติบัตร - ' . $keyword,
            'results' => $results,
            'keyword' => $keyword
        ];

        return view('sports/public/certificate', $data);
    }

    // Download / View Member Certificate
    public function downloadCert($memberId)
    {
        $db = \Config\Database::connect();
        $member = $db->table('Tb_Sports_Members as m')
                     ->select('m.*, t.team_code, t.school_name, t.team_name, t.award_level as team_award, c.sport_name, c.category_name, c.category_gender, c.comp_year')
                     ->join('Tb_Sports_Teams as t', 't.team_id = m.team_id', 'left')
                     ->join('Tb_Sports_Categories as c', 'c.category_id = m.category_id', 'left')
                     ->where('m.member_id', $memberId)
                     ->get()
                     ->getRowArray();

        if (!$member) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบข้อมูลผู้รับเกียรติบัตร');
        }

        // Determine matching certificate template
        $cert = $db->table('Tb_Sports_Certificates')
                   ->where('category_id', $member['category_id'])
                   ->where('is_active', 1)
                   ->get()
                   ->getRowArray();

        if (!$cert) {
            // Fallback to general template
            $cert = $db->table('Tb_Sports_Certificates')
                       ->where('category_id', 0)
                       ->where('is_active', 1)
                       ->get()
                       ->getRowArray();
        }

        if (!$cert) {
            // If none exists, get the latest one
            $cert = $db->table('Tb_Sports_Certificates')->orderBy('cert_id', 'DESC')->get()->getRowArray();
        }

        if (!$cert) {
            return redirect()->back()->with('error', 'ยังไม่มีการตั้งค่าเทมเพลตเกียรติบัตรในระบบ');
        }

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

        // Format award text
        $finalAward = $member['award_level'] !== 'none' ? $member['award_level'] : $member['team_award'];
        $awardMap = [
            'champion'      => 'ได้รับรางวัล  ชนะเลิศ',
            'runner_up_1'   => 'ได้รับรางวัล  รองชนะเลิศอันดับ 1',
            'runner_up_2'   => 'ได้รับรางวัล  รองชนะเลิศอันดับ 2',
            'runner_up_3'   => 'ได้รับรางวัล  รองชนะเลิศอันดับ 3',
            'participation' => 'ได้เข้าร่วมการแข่งขัน',
            'none'          => 'ได้เข้าร่วมการแข่งขัน'
        ];
        $awardText = $awardMap[$finalAward] ?? 'ได้เข้าร่วมการแข่งขัน';

        $genderText = $member['category_gender'] === 'female' ? 'หญิง' : ($member['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย');
        $certCode = $member['cert_number'] ?: (($cert['cert_prefix'] ?: 'PAO-SP-2569/') . str_pad($member['member_id'], 5, '0', STR_PAD_LEFT));

        $thaiMonths = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        $dateText = 'ให้ไว้ ณ วันที่ ' . date('j') . ' ' . $thaiMonths[(int)date('n')] . ' พ.ศ. ' . (date('Y') + 543);

        $catName = trim($member['category_name']);
        // Format model text avoiding duplicate 'รุ่น'
        if (mb_strpos($catName, 'รุ่น') === 0) {
            $modelStr = 'ประเภท  ' . $catName;
        } elseif (preg_match('/^\d+/', $catName)) {
            $modelStr = 'ประเภท  รุ่นอายุไม่เกิน ' . $catName . ' ปี';
        } else {
            $modelStr = 'ประเภท  รุ่น ' . $catName;
        }
        if (!empty($genderText) && mb_strpos($modelStr, $genderText) === false) {
            $modelStr .= ' (' . $genderText . ')';
        }

        $samples = [
            'name'     => trim(($member['prefix'] ?? '') . $member['first_name'] . ' ' . $member['last_name']),
            'award'    => $awardText,
            'category' => 'กีฬา' . $member['sport_name'],
            'model'    => $modelStr,
            'school'   => $member['school_name'] . ($member['team_name'] && $member['team_name'] !== $member['school_name'] ? ' (' . $member['team_name'] . ')' : ''),
            'date'     => $dateText,
            'code'     => $certCode
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

        return $this->response
                    ->setHeader('Content-Type', 'image/png')
                    ->setHeader('Content-Disposition', 'inline; filename="certificate_' . str_replace('/', '_', $certCode) . '.png"')
                    ->setBody($data);
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
