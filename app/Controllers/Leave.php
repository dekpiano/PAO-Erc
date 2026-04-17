<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LeaveModel;

class Leave extends BaseController
{
    protected $leaveModel;

    public function __construct()
    {
        $this->leaveModel = new LeaveModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        // ตรวจสอบว่าเข้าระบบหรือยัง
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $userId = session()->get('u_id');
        $role = session()->get('u_role') ?? '';
        
        // --- ส่วนการจัดการปีงบประมาณ ---
        $now = time();
        $currentFY = (date('n', $now) >= 10) ? date('Y', $now) + 544 : date('Y', $now) + 543;
        
        // รับค่าปีงบประมาณจากตัวกรอง (ถ้าไม่มีให้ใช้ปีปัจจุบัน)
        $selectedFY = $this->request->getVar('f_year') ?: $currentFY;
        
        // คำนวณวันที่เริ่มและสิ้นสุดของปีงบประมาณที่เลือก (พ.ศ. -> ค.ศ.)
        $yearAD = $selectedFY - 543;
        $fStart = ($yearAD - 1) . '-10-01';
        $fEnd = $yearAD . '-09-30';

        // สร้างรายการปีงบประมาณให้เลือก (ย้อนหลัง 5 ปี)
        $fiscalYears = [];
        for ($i = 0; $i < 5; $i++) {
            $fiscalYears[] = $currentFY - $i;
        }

        // --- ดึงข้อมูลใบลา (หน้าทั่วไป: ดูแค่ของตัวเองเท่านั้น) ---
        $leaves = $this->leaveModel->where('leave_user_id', $userId)
                                    ->where('leave_from_date >=', $fStart)
                                    ->where('leave_from_date <=', $fEnd)
                                    ->orderBy('leave_created_at', 'DESC')
                                    ->findAll();

        // --- คำนวณสถิติ (เฉพาะของคนล็อกอินในฐานะ Staff) ---
        $db = \Config\Database::connect();
        $stats = $db->table('Tb_Leave')
                    ->select('leave_type, COUNT(leave_id) as count, SUM(leave_days) as total_days')
                    ->where('leave_user_id', $userId)
                    ->where('leave_from_date >=', $fStart)
                    ->where('leave_from_date <=', $fEnd)
                    ->where('leave_status', 'approved')
                    ->groupBy('leave_type')
                    ->get()->getResultArray();

        $statsSummary = [
            'sick' => ['count' => 0, 'days' => 0, 'label' => 'ลาป่วย'],
            'personal' => ['count' => 0, 'days' => 0, 'label' => 'ลากิจส่วนตัว'],
            'vacation' => ['count' => 0, 'days' => 0, 'label' => 'ลาพักผ่อน'],
        ];

        foreach ($stats as $s) {
            if (isset($statsSummary[$s['leave_type']])) {
                $statsSummary[$s['leave_type']]['count'] = $s['count'];
                $statsSummary[$s['leave_type']]['days'] = (float)$s['total_days'];
            }
        }

        $data = [
            'title' => 'การลางาน',
            'leaves' => $leaves,
            'statsSummary' => $statsSummary,
            'fYearBE' => $selectedFY,
            'fiscalYears' => $fiscalYears,
            'fStart' => date('d/m/', strtotime($fStart)) . (date('Y', strtotime($fStart)) + 543),
            'fEnd' => date('d/m/', strtotime($fEnd)) . (date('Y', strtotime($fEnd)) + 543)
        ];

        return view('staff/leave/index', $data);
    }

    public function create()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $userId = session()->get('u_id');
        // ดึงใบลาล่าสุดของผู้ใช้นี้ เพื่อไปแสดงเป็น "การลาครั้งสุดท้าย"
        $lastLeave = $this->leaveModel->where('leave_user_id', $userId)
                                      ->orderBy('leave_created_at', 'DESC')
                                      ->first();

        // ดึงรายชื่อบุคลากรทั้งหมด (ยกเว้นตัวเอง) เพื่อใช้ใน Dropdown ผู้รับมอบงาน
        $db = \Config\Database::connect();
        $users = $db->table('Tb_Users')
                    ->where('u_id !=', $userId)
                    ->where('u_status', 'active')
                    ->orderBy('u_fullname', 'ASC')
                    ->get()->getResultArray();

        $data = [
            'title' => 'เขียนใบลา',
            'lastLeave' => $lastLeave,
            'users' => $users
        ];

        return view('staff/leave/create', $data);
    }

    public function adminIndex()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $role = session()->get('u_role') ?? '';
        if (strpos($role, 'superadmin') === false && strpos($role, 'admin') === false && strpos($role, 'head') === false) {
            return redirect()->to('/staff/leave')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้าจัดการใบลา');
        }

        // --- ส่วนการจัดการปีงบประมาณ ---
        $now = time();
        $currentFY = (date('n', $now) >= 10) ? date('Y', $now) + 544 : date('Y', $now) + 543;
        $selectedFY = $this->request->getVar('f_year') ?: $currentFY;
        $yearAD = $selectedFY - 543;
        $fStart = ($yearAD - 1) . '-10-01';
        $fEnd = $yearAD . '-09-30';

        $fiscalYears = [];
        for ($i = 0; $i < 5; $i++) {
            $fiscalYears[] = $currentFY - $i;
        }

        // --- ดึงข้อมูลใบลาทั้งหมด (สำหรับแอดมิน) ---
        $leaves = $this->leaveModel->select('Tb_Leave.*, Tb_Users.u_fullname, Tb_Users.u_prefix')
                                    ->join('Tb_Users', 'Tb_Users.u_id = Tb_Leave.leave_user_id', 'left')
                                    ->where('leave_from_date >=', $fStart)
                                    ->where('leave_from_date <=', $fEnd)
                                    ->orderBy('leave_created_at', 'DESC')
                                    ->findAll();

        // --- คำนวณสถิติภาพรวม (สำหรับแอดมิน) ---
        $stats = [
            'total'   => count($leaves),
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0
        ];

        foreach ($leaves as $l) {
            if ($l['leave_status'] == 'pending') $stats['pending']++;
            if ($l['leave_status'] == 'approved') $stats['approved']++;
            if ($l['leave_status'] == 'rejected') $stats['rejected']++;
        }

        // --- ดึงข้อมูลบุคลากรทั้งหมด (สำหรับเลือกเวลาแอดมินลาให้) ---
        $db = \Config\Database::connect();
        $users_list = $db->table('Tb_Users')
                         ->where('u_status', 'active')
                         ->orderBy('u_fullname', 'ASC')
                         ->get()->getResultArray();

        $data = [
            'title' => 'จัดการการลางาน (Admin)',
            'leaves' => $leaves,
            'stats'  => $stats,
            'users_list' => $users_list,
            'fYearBE' => $selectedFY,
            'fiscalYears' => $fiscalYears,
            'fStart' => date('d/m/', strtotime($fStart)) . (date('Y', strtotime($fStart)) + 543),
            'fEnd' => date('d/m/', strtotime($fEnd)) . (date('Y', strtotime($fEnd)) + 543)
        ];

        return view('staff/leave/admin_index', $data);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $rules = [
            'leave_type' => 'required',
            'leave_from_date' => 'required',
            'leave_to_date' => 'required',
            'leave_days' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'กรุณากรอกข้อมูลให้ครบถ้วน');
        }

        // กำหนด User ID (ถ้าแอดมินทำรายการแทนคนอื่น ให้ใช้ ID ที่เลือกมา)
        $userId = session()->get('u_id');
        $userRoles = session()->get('u_role') ?? '';
        $isAdmin = (strpos($userRoles, 'admin') !== false || strpos($userRoles, 'superadmin') !== false || strpos($userRoles, 'head') !== false);
        
        $targetUserId = ($isAdmin && $this->request->getVar('leave_user_id')) ? $this->request->getVar('leave_user_id') : $userId;

        $data = [
            'leave_user_id' => $targetUserId,
            'leave_type' => $this->request->getVar('leave_type'),
            'leave_reason' => $this->request->getVar('leave_reason'),
            'leave_from_date' => $this->request->getVar('leave_from_date'),
            'leave_to_date' => $this->request->getVar('leave_to_date'),
            'leave_days' => $this->request->getVar('leave_days'),
            'leave_last_from_date' => $this->request->getVar('leave_last_from_date') ?: null,
            'leave_last_to_date' => $this->request->getVar('leave_last_to_date') ?: null,
            'leave_last_days' => $this->request->getVar('leave_last_days') ?: null,
            'leave_contact' => $this->request->getVar('leave_contact'),
            'leave_substitute_id' => $this->request->getVar('leave_substitute_id') ?: null,
            'leave_status' => 'pending' // เริ่มต้นสถานะรออนุมัติ
        ];

        $leaveId = $this->leaveModel->insert($data);
        
        $fullname = session()->get('u_fullname');
        $typeLabel = [
            'sick' => 'ลาป่วย',
            'personal' => 'ลากิจส่วนตัว',
            'vacation' => 'ลาพักผ่อน'
        ][$data['leave_type']] ?? $data['leave_type'];

        // 1. แจ้งเตือนแอดมิน
        $this->notifyAdmins(
            "มีคำขอลาใหม่จาก $fullname",
            "$fullname ได้ส่งคำขอ $typeLabel ตั้งแต่วันที่ {$data['leave_from_date']} ถึง {$data['leave_to_date']} จำนวน {$data['leave_days']} วัน",
            "staff/leave/admin"
        );

        // 2. แจ้งเตือนกลับไปหาคนลา
        $this->notify(
            $data['leave_user_id'],
            "ส่งใบลาเรียบร้อยแล้ว",
            "คุณได้ส่งคำขอ $typeLabel เรียบร้อยแล้ว ขณะนี้อยู่ระหว่างรอการพิจารณา",
            "staff/leave"
        );

        return redirect()->to('/staff/leave')->with('success', 'บันทึกใบลาและส่งการแจ้งเตือนเรียบร้อยแล้ว');
    }

    public function exportDocs($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $leave = $this->leaveModel->find($id);
        if (!$leave) {
            return redirect()->to('/staff/leave')->with('error', 'ไม่พบข้อมูลใบลา');
        }

        // ดึงข้อมูลผู้ใช้งาน (อ้างอิง Tb_Users)
        $db = \Config\Database::connect();
        $user = $db->table('Tb_Users as u')
                   ->select('u.*, p.pos_name as position_name')
                   ->join('Tb_Positions as p', 'p.pos_id = u.u_position', 'left')
                   ->where('u_id', $leave['leave_user_id'])
                   ->get()->getRowArray();
        
        $userFullName = $user ? trim(($user['u_prefix'] ?? '') . $user['u_fullname']) : session()->get('u_fullname');
        $fullname = $userFullName ?: 'ไม่ระบุชื่อ';
        $position = $user ? $user['position_name'] : session()->get('u_position');
        $division = $user ? $user['u_division'] : 'กองการศึกษา ศาสนา และวัฒนธรรม';

        // -------- ค้นหาหัวหน้าฝ่ายไดนามิกจากฐานข้อมูล --------
        $db = \Config\Database::connect();
        $headUser = $db->table('Tb_Users as u')
                       ->select('u.*, p.pos_name as position_name')
                       ->join('Tb_Positions as p', 'p.pos_id = u.u_position', 'left')
                       ->groupStart()
                           ->like('u.u_division', $division) // ฝ่ายเดียวกันกับคนล็อกอิน
                       ->groupEnd()
                       ->groupStart()
                           ->where('u.u_role', 'head') // สมมติว่า role ของหัวหน้าคือ head
                           ->orWhere('u.u_role', 'admin') // หรือกรณีเป็น admin
                           ->orWhere('p.pos_is_head', 1) // หรือเป็นตำแหน่งระดับหัวหน้าในฐานข้อมูลตำแหน่ง
                       ->groupEnd()
                       ->get()->getRowArray();
        
        $headName = $headUser ? trim(($headUser['u_prefix'] ?? '') . $headUser['u_fullname']) : '..............................................................';
        $headPosition = $headUser ? $headUser['position_name'] : '..............................................................';
        // ----------------------------------------------------

        // จำเป็นต้องมี Template (ให้ผู้ใช้สร้างไฟล์และใส่ตัวแปร)
        // สร้างโฟลเดอร์ template หากไม่มี
        if ($leave['leave_type'] === 'vacation') {
            $templatePath = ROOTPATH . 'public/uploads/templates/leave_vacation_template.docx';
        } else {
            $templatePath = ROOTPATH . 'public/uploads/templates/leave_template.docx';
        }
        
        if (!file_exists($templatePath)) {
            // ถ้าไม่เจอไฟล์แม่แบบให้แจ้งเตือน
            return redirect()->to('/staff/leave')->with('error', 'ไม่พบไฟล์แม่แบบใบลา (' . basename($templatePath) . ') กรุณานำไฟล์แม่แบบไปใส่ไว้ที่ public/uploads/templates/');
        }

        try {
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

            // คำนวณประเภทลา
            $types = [
                'sick' => 'ป่วย', 'personal' => 'กิจส่วนตัว', 'maternity' => 'คลอดบุตร',
                'paternity' => 'ไปช่วยเหลือภริยาที่คลอดบุตร', 'vacation' => 'พักผ่อน',
                'ordination' => 'อุปสมบท/ฮัจย์', 'military' => 'เข้ารับราชการทหาร',
                'study' => 'ศึกษา/ฝึกอบรม', 'international_org' => 'ปฏิบัติงานองค์กรระหว่างประเทศ',
                'spouse_follow' => 'ติดตามคู่สมรส', 'rehabilitation' => 'ฟื้นฟูอาชีพ'
            ];
            $leaveTypeStr = $types[$leave['leave_type']] ?? $leave['leave_type'];

            // แปลงวันที่ไทย (สำหรับแสดงผล)
            $months_th = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            $created_d = date('j', strtotime($leave['leave_created_at']));
            $created_m = $months_th[date('n', strtotime($leave['leave_created_at']))];
            $created_y = date('Y', strtotime($leave['leave_created_at'])) + 543;

            $from_d = date('j', strtotime($leave['leave_from_date']));
            $from_m = $months_th[date('n', strtotime($leave['leave_from_date']))];
            $from_y = date('Y', strtotime($leave['leave_from_date'])) + 543;

            $to_d = date('j', strtotime($leave['leave_to_date']));
            $to_m = $months_th[date('n', strtotime($leave['leave_to_date']))];
            $to_y = date('Y', strtotime($leave['leave_to_date'])) + 543;

            // ยัดข้อมูลลง Template
            $templateProcessor->setValue('d_d', $created_d);
            $templateProcessor->setValue('d_m', $created_m);
            $templateProcessor->setValue('d_y', $created_y);
            
            $templateProcessor->setValue('fn', $fullname);
            $templateProcessor->setValue('ps', $position);
            $templateProcessor->setValue('dv', $division);
            
            $templateProcessor->setValue('lt', $leaveTypeStr);
            $templateProcessor->setValue('lr', $leave['leave_reason'] ?? '-');
            
            $templateProcessor->setValue('f_d', $from_d);
            $templateProcessor->setValue('f_m', $from_m);
            $templateProcessor->setValue('f_y', $from_y);
            
            $templateProcessor->setValue('t_d', $to_d);
            $templateProcessor->setValue('t_m', $to_m);
            $templateProcessor->setValue('t_y', $to_y);
            
            $templateProcessor->setValue('ld', floatval($leave['leave_days']));
            $templateProcessor->setValue('lc', $leave['leave_contact'] ?? '-');

            // สำหรับ "ลาพักผ่อน" ที่มีช่องพิเศษ
            $subName = '-';
            $subPosition = '-';
            if (!empty($leave['leave_substitute_id'])) {
                $subUser = $db->table('Tb_Users as u')
                              ->select('u.*, p.pos_name as position_name')
                              ->join('Tb_Positions as p', 'p.pos_id = u.u_position', 'left')
                              ->where('u_id', $leave['leave_substitute_id'])
                              ->get()->getRowArray();
                if ($subUser) {
                    $subName = trim(($subUser['u_prefix'] ?? '') . $subUser['u_fullname']);
                    $subPosition = $subUser['position_name'] ?? '-';
                }
            }
            
            $templateProcessor->setValue('va', '-'); // vac_accumulated
            $templateProcessor->setValue('vy', '10'); // vac_this_year
            $templateProcessor->setValue('vt', '-'); // vac_total
            $templateProcessor->setValue('sn', $subName); // substitute_name
            $templateProcessor->setValue('sp', $subPosition); // substitute_position

            // กรณีลาครั้งก่อน
            if (!empty($leave['leave_last_from_date'])) {
                $last_from_d = date('j', strtotime($leave['leave_last_from_date']));
                $last_from_m = $months_th[date('n', strtotime($leave['leave_last_from_date']))];
                $last_from_y = date('Y', strtotime($leave['leave_last_from_date'])) + 543;
                
                $last_to_d = date('j', strtotime($leave['leave_last_to_date']));
                $last_to_m = $months_th[date('n', strtotime($leave['leave_last_to_date']))];
                $last_to_y = date('Y', strtotime($leave['leave_last_to_date'])) + 543;

                $templateProcessor->setValue('lf_d', $last_from_d);
                $templateProcessor->setValue('lf_m', $last_from_m);
                $templateProcessor->setValue('lf_y', $last_from_y);
                
                $templateProcessor->setValue('lt_d', $last_to_d);
                $templateProcessor->setValue('lt_m', $last_to_m);
                $templateProcessor->setValue('lt_y', $last_to_y);
                $templateProcessor->setValue('lds', floatval($leave['leave_last_days']));
            } else {
                $templateProcessor->setValue('lf_d', '-');
                $templateProcessor->setValue('lf_m', '-');
                $templateProcessor->setValue('lf_y', '-');
                $templateProcessor->setValue('lt_d', '-');
                $templateProcessor->setValue('lt_m', '-');
                $templateProcessor->setValue('lt_y', '-');
                $templateProcessor->setValue('lds', '-');
            }

            // --- คำนวณสถิติการลาในปีงบประมาณนี้ ---
            // ปีงบประมาณไทย เริ่ม 1 ต.ค. - 30 ก.ย.
            $leaveDate = strtotime($leave['leave_from_date']);
            $leaveMonth = (int)date('n', $leaveDate);
            $leaveYear = (int)date('Y', $leaveDate);

            if ($leaveMonth >= 10) {
                $fStart = $leaveYear . '-10-01';
                $fEnd = ($leaveYear + 1) . '-09-30';
            } else {
                $fStart = ($leaveYear - 1) . '-10-01';
                $fEnd = $leaveYear . '-09-30';
            }

            // ดึงสถิติย้อนหลัง (เฉพาะที่อนุมัติแล้ว หรือตามที่ตกลงกัน)
            // ในที่นี้ดึงเฉพาะ 'approved' และไม่รวมใบลาปัจจุบัน
            $stats = $db->table('Tb_Leave')
                        ->select('leave_type, SUM(leave_days) as total')
                        ->where('leave_user_id', $leave['leave_user_id'])
                        ->where('leave_from_date >=', $fStart)
                        ->where('leave_from_date <=', $fEnd)
                        ->where('leave_status', 'approved')
                        ->where('leave_id !=', $leave['leave_id'])
                        ->groupBy('leave_type')
                        ->get()->getResultArray();

            $sumStats = [
                'sick' => 0,
                'personal' => 0,
                'maternity' => 0,
                'vacation' => 0
            ];

            foreach ($stats as $s) {
                if (array_key_exists($s['leave_type'], $sumStats)) {
                    $sumStats[$s['leave_type']] = (float)$s['total'];
                }
            }

            // ยัดข้อมูลสถิติลง Template (ชื่อตัวแปรแบบสั้น เพื่อไม่ให้ตารางยืด)
            // 1 = ลามาแล้ว, 2 = ครั้งนี้, 3 = รวม
            // s = ป่วย, p = กิจ, m = คลอด, v = พักผ่อน
            $templateProcessor->setValue('s1', $sumStats['sick']);
            $templateProcessor->setValue('p1', $sumStats['personal']);
            $templateProcessor->setValue('m1', $sumStats['maternity']);
            $templateProcessor->setValue('v1', $sumStats['vacation']);

            $templateProcessor->setValue('s2', ($leave['leave_type'] == 'sick' ? (float)$leave['leave_days'] : '-'));
            $templateProcessor->setValue('p2', ($leave['leave_type'] == 'personal' ? (float)$leave['leave_days'] : '-'));
            $templateProcessor->setValue('m2', ($leave['leave_type'] == 'maternity' ? (float)$leave['leave_days'] : '-'));
            $templateProcessor->setValue('v2', ($leave['leave_type'] == 'vacation' ? (float)$leave['leave_days'] : '-'));

            $templateProcessor->setValue('s3', $sumStats['sick'] + ($leave['leave_type'] == 'sick' ? (float)$leave['leave_days'] : 0));
            $templateProcessor->setValue('p3', $sumStats['personal'] + ($leave['leave_type'] == 'personal' ? (float)$leave['leave_days'] : 0));
            $templateProcessor->setValue('m3', $sumStats['maternity'] + ($leave['leave_type'] == 'maternity' ? (float)$leave['leave_days'] : 0));
            $templateProcessor->setValue('v3', $sumStats['vacation'] + ($leave['leave_type'] == 'vacation' ? (float)$leave['leave_days'] : 0));

            // สำหรับ placeholder เดิม (เก็บไว้สำรอง)
            $templateProcessor->setValue('stat_sick', $sumStats['sick']);
            $templateProcessor->setValue('stat_personal', $sumStats['personal']);
            $templateProcessor->setValue('stat_maternity', $sumStats['maternity']);

            // เพิ่มค่าให้ส่วนของหัวหน้าฝ่าย
            $templateProcessor->setValue('hn', $headName); // head_name
            $templateProcessor->setValue('hp', $headPosition); // head_position

            $fileName = 'Leave_Request_' . $fullname . '_' . date('Ymd_His') . '.docx';
            
            // ใช้ Response Download ของ CodeIgniter 4 แก้ปัญหาไฟล์ 0 Byte (งียบกริบ)
            $tempFile = WRITEPATH . 'temp/' . $fileName;
            
            // สร้าง WRITEPATH.temp ถ้ายังไม่มี
            if (!is_dir(WRITEPATH . 'temp')) {
                mkdir(WRITEPATH . 'temp', 0777, true);
            }
            
            $templateProcessor->saveAs($tempFile);
            
            return $this->response->download($tempFile, null)
                                  ->setFileName($fileName);
            
        } catch (\Exception $e) {
            return redirect()->to('/staff/leave')->with('error', 'เกิดข้อผิดพลาดในการสร้างไฟล์ Word: ' . $e->getMessage());
        }
    }

    public function updateStatus()
    {
        $userId = session()->get('u_id');
        $role = session()->get('u_role') ?? '';

        // เช็คสิทธิ์แอดมินหรือหัวหน้า
        if (strpos($role, 'superadmin') === false && strpos($role, 'admin') === false && strpos($role, 'head') === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์ดำเนินการนี้']);
        }

        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        if (!$id || !$status) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
        }

        try {
            $this->leaveModel->update($id, [
                'leave_status' => $status,
                'leave_approver_id' => $userId
            ]);

            // แจ้งเตือนกลับไปหาคนลา
            $leave = $this->leaveModel->find($id);
            if ($leave) {
                $statusLabel = ($status == 'approved' ? 'อนุมัติ' : 'ไม่อนุมัติ');
                $this->notify(
                    $leave['leave_user_id'],
                    "ใบลาของคุณได้รับความเห็นชอบ: $statusLabel",
                    "ใบลาตั้งแต่วันที่ {$leave['leave_from_date']} ของคุณได้รับการ $statusLabel โดยผู้บริหารเรียบร้อยแล้ว",
                    "staff/leave"
                );
            }

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'ปรับปรุงสถานะใบลาเป็น "' . ($status == 'approved' ? 'อนุมัติ' : 'ไม่อนุมัติ') . '" และส่งการแจ้งเตือนแล้ว'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function getLastLeave($userId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $lastLeave = $this->leaveModel->where('leave_user_id', $userId)
                                     ->orderBy('leave_created_at', 'DESC')
                                     ->first();

        if ($lastLeave) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => [
                    'from_date' => $lastLeave['leave_from_date'],
                    'to_date' => $lastLeave['leave_to_date'],
                    'days' => floatval($lastLeave['leave_days'])
                ]
            ]);
        }

        return $this->response->setJSON(['status' => 'none']);
    }
}
