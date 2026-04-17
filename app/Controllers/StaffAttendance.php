<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\UserModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class StaffAttendance extends Controller
{
    /**
     * Dashboard สรุปการลงเวลา
     */
    public function index()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'personnel') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบนี้');
        }

        $model = new AttendanceModel();
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        
        $data['title'] = "จัดการการลงเวลา (Excel) | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        
        $data['attendance'] = $model->select('Tb_Attendance.*, Tb_Users.u_fullname, Tb_Users.u_prefix, Tb_Users.u_photo, Tb_Users.u_division, Tb_Positions.pos_name')
                                    ->join('Tb_Users', 'Tb_Users.u_id = Tb_Attendance.atd_user_id', 'left')
                                    ->join('Tb_Positions', 'Tb_Positions.pos_id = Tb_Users.u_position', 'left')
                                    ->where('atd_date', $date)
                                    ->where('atd_type', 'excel_import')
                                    ->orderBy('Tb_Users.u_fullname', 'ASC')
                                    ->findAll();
        
        $data['selected_date'] = $date;

        // Fetch Users for manual addition
        $userModel = new UserModel();
        $data['users'] = $userModel->select('Tb_Users.*, Tb_Positions.pos_name')
                                    ->join('Tb_Positions', 'Tb_Positions.pos_id = Tb_Users.u_position', 'left')
                                    ->orderBy('u_fullname', 'ASC')
                                    ->findAll();

        return view('staff/attendance_admin/index', $data);
    }

    /**
     * หน้าตั้งค่ารหัสสแกนนิ้ว (Mapping)
     */
    public function users()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'personnel') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบนี้');
        }

        $userModel = new UserModel();
        $data['title'] = "ตั้งค่ารหัสสแกนนิ้วบุคลากร | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        
        $data['users'] = $userModel->select('Tb_Users.*, Tb_Positions.pos_name')
                                    ->join('Tb_Positions', 'Tb_Positions.pos_id = Tb_Users.u_position', 'left')
                                    ->orderBy('Tb_Users.u_fullname', 'ASC')
                                    ->findAll();

        return view('staff/attendance_admin/users', $data);
    }

    /**
     * บันทึกการตั้งค่ารหัสสแกนนิ้ว
     */
    public function saveUserMapping()
    {
        $userModel = new UserModel();
        $userId = $this->request->getPost('u_id');
        $fingerId = $this->request->getPost('finger_id');

        $userModel->update($userId, ['u_fingerprint_id' => $fingerId]);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function upload()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'personnel') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบนี้');
        }

        $data['title'] = "อัปโหลดไฟล์ลงเวลา | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        
        return view('staff/attendance_admin/upload', $data);
    }

    public function process()
    {
        $file = $this->request->getFile('excel_file');
        
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'กรุณาเลือกไฟล์ที่ถูกต้อง');
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $dataRows = $sheet->toArray();

            $atdModel = new AttendanceModel();
            $userModel = new UserModel();

            $successCount = 0;
            $failCount = 0;
            $errors = [];
            $redirectDate = date('Y-m-d'); // Default if empty

            foreach ($dataRows as $index => $row) {
                // ข้ามหัวตาราง (แถวที่ 1)
                if ($index == 0) continue; 

                $fingerprintId = trim($row[1] ?? ''); 
                $excelDateStr  = trim($row[3] ?? ''); 
                $clockInTime   = trim($row[4] ?? ''); 
                $remarkFromExcel = trim($row[8] ?? ''); 

                if (empty($fingerprintId)) continue;

                // ตรวจสอบและแปลงวันที่ให้แม่นยำ
                $itemDate = date('Y-m-d'); 
                if (!empty($excelDateStr)) {
                    if (is_numeric($excelDateStr)) {
                        $itemDate = date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($excelDateStr));
                    } else {
                        $trimmedDate = str_replace(['/', ' '], '-', $excelDateStr);
                        $parts = explode('-', $trimmedDate);
                        if (count($parts) == 3) {
                            $year = strlen($parts[2]) == 2 ? '20'.$parts[2] : $parts[2];
                            $itemDate = $year . '-' . sprintf('%02d', $parts[1]) . '-' . sprintf('%02d', $parts[0]);
                        } else {
                            $timestamp = strtotime($excelDateStr);
                            if ($timestamp) $itemDate = date('Y-m-d', $timestamp);
                        }
                    }
                }
                
                // เก็บวันที่ไว้ใช้เป็นตัว Redirect (ใช้จากแถวแรกที่มีข้อมูล)
                if ($successCount == 0) $redirectDate = $itemDate;

                // ค้นหาบุคลากรจาก u_fingerprint_id
                $user = $userModel->where('u_fingerprint_id', $fingerprintId)->first();
                
                if ($user) {
                    $status = 'มา';

                    if (empty($clockInTime)) {
                        if (!empty($remarkFromExcel)) {
                            if (strpos($remarkFromExcel, 'ลาป่วย') !== false) $status = 'ลาป่วย';
                            elseif (strpos($remarkFromExcel, 'ลากิจ') !== false) $status = 'ลากิจ';
                            elseif (strpos($remarkFromExcel, 'ลาพักผ่อน') !== false) $status = 'ลาพักผ่อน';
                            elseif (strpos($remarkFromExcel, 'ลา') !== false) $status = 'ลา';
                            elseif (strpos($remarkFromExcel, 'ราชการ') !== false) $status = 'ไปราชการ';
                            elseif (strpos($remarkFromExcel, 'ขาด') !== false) $status = 'ขาด';
                            else $status = 'ขาด';
                        } else {
                            $status = 'ขาด';
                        }
                    } else {
                        $inTime = strtotime($clockInTime);
                        $threshold = strtotime('08:30:00');
                        if ($inTime > $threshold) $status = 'สาย';
                    }

                    $data = [
                        'atd_user_id'   => $user['u_id'],
                        'atd_date'      => $itemDate,
                        'atd_status'    => $status,
                        'atd_type'      => 'excel_import',
                        'atd_timestamp' => $itemDate . ' ' . ($clockInTime ?: '00:00:00'),
                        'atd_note'      => $remarkFromExcel
                    ];

                    $existing = $atdModel->where([
                        'atd_user_id' => $user['u_id'], 
                        'atd_date'    => $itemDate,
                        'atd_type'    => 'excel_import'
                    ])->first();

                    if ($existing) {
                        $atdModel->update($existing['atd_id'], $data);
                    } else {
                        $atdModel->insert($data);
                    }
                    $successCount++;
                } else {
                    $failCount++;
                    $errors[] = "ไม่พบรหัสเครื่อง [$fingerprintId]: " . ($row[2] ?? 'Unknown');
                }
            }

            $msg = "ประมวลผลสำเร็จ $successCount รายการ (วันที่ในไฟล์: $redirectDate)";
            if ($failCount > 0) $msg .= ", ไม่สำเร็จ $failCount รายการ (ต้องตั้งค่ารหัสสแกนนิ้วก่อน)";
            
            return redirect()->to(base_url('staff/attendance-admin?date='.$redirectDate))->with('success', $msg)->with('import_errors', $errors);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการอ่านไฟล์: ' . $e->getMessage());
        }
    }

    /**
     * บันทึกการมาทำงานแบบรายบุคคล (Manual Entry)
     */
    public function saveManual()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'personnel') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบนี้');
        }

        $atdModel = new AttendanceModel();
        
        $userId = $this->request->getPost('user_id');
        $date   = $this->request->getPost('date');
        $status = $this->request->getPost('status');
        $time   = $this->request->getPost('time');
        $note   = $this->request->getPost('note');

        // ตรวจสอบเงื่อนไขสายอัตโนมัติ (เกิน 08:30)
        if ($status == 'มา' && !empty($time)) {
            $inTime = strtotime($time);
            $threshold = strtotime('08:30:00');
            if ($inTime > $threshold) {
                $status = 'สาย';
            }
        }

        $data = [
            'atd_user_id'   => $userId,
            'atd_date'      => $date,
            'atd_status'    => $status,
            'atd_type'      => 'excel_import', // Use excel_import type to show in the same dashboard
            'atd_timestamp' => $date . ' ' . ($time ?: '00:00:00'),
            'atd_note'      => $note
        ];

        // UPSERT
        $existing = $atdModel->where([
            'atd_user_id' => $userId, 
            'atd_date'    => $date,
            'atd_type'    => 'excel_import'
        ])->first();

        if ($existing) {
            $atdModel->update($existing['atd_id'], $data);
        } else {
            $atdModel->insert($data);
        }

        return redirect()->to(base_url('staff/attendance-admin?date='.$date))->with('success', 'บันทึกข้อมูลสำเร็จ');
    }

    /**
     * หน้ารวมรายงาน (Report Hub)
     */
    public function report()
    {
        $data['title'] = "สรุปรายงานการปฏิบัติงาน | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        return view('staff/attendance_admin/report_hub', $data);
    }

    /**
     * รายงานสรุปการมาทำงานรายปี (ปีงบประมาณ)
     */
    public function annualReport()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'personnel') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบนี้');
        }

        $atdModel = new AttendanceModel();
        $userModel = new UserModel();

        // เลือกปีงบประมาณ และ รอบการประเมิน
        $fiscalYear = $this->request->getGet('year') ?: (date('n') >= 10 ? date('Y') + 1 : date('Y'));
        $round = $this->request->getGet('round') ?: 'all'; // all, 1 (Oct-Mar), 2 (Apr-Sep)
        
        $startYear = $fiscalYear - 1;
        $endYear = $fiscalYear;

        // กำหนดช่วงเดือนตามรอบ
        if ($round == '1') {
            $monthsOrder = [10, 11, 12, 1, 2, 3];
            $startDate = "$startYear-10-01";
            $endDate = "$endYear-03-31";
        } elseif ($round == '2') {
            $monthsOrder = [4, 5, 6, 7, 8, 9];
            $startDate = "$endYear-04-01";
            $endDate = "$endYear-09-30";
        } else {
            $monthsOrder = [10, 11, 12, 1, 2, 3, 4, 5, 6, 7, 8, 9];
            $startDate = "$startYear-10-01";
            $endDate = "$endYear-09-30";
        }

        $data['title'] = "รายงานสรุปการมาทำงานประจำปีงบประมาณ $fiscalYear | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        $data['fiscal_year'] = $fiscalYear;
        $data['selected_round'] = $round;

        // ดึงรายชื่อบุคลากรทั้งหมด
        $users = $userModel->orderBy('u_fullname', 'ASC')->findAll();

        // ดึงข้อมูลการเข้างานในช่วงที่เลือก
        $attendanceData = $atdModel->where('atd_date >=', $startDate)
                                    ->where('atd_date <=', $endDate)
                                    ->where('atd_type', 'excel_import')
                                    ->findAll();

        // จัดกลุ่มข้อมูล
        $reportData = [];
        foreach ($attendanceData as $row) {
            $month = (int)date('n', strtotime($row['atd_date']));
            $userId = $row['atd_user_id'];
            $status = $row['atd_status'];

            if (!isset($reportData[$userId][$month][$status])) {
                $reportData[$userId][$month][$status] = 0;
            }
            $reportData[$userId][$month][$status]++;
        }

        $data['users'] = $users;
        $data['report_data'] = $reportData;
        $data['months_order'] = $monthsOrder;
        $data['thai_months'] = [
            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.',
            7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
        ];

        return view('staff/attendance_admin/report', $data);
    }

    /**
     * รายงานสรุปประจำเดือน (Monthly Timesheet)
     */
    public function monthlyReport()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'personnel') === false) {
            return redirect()->to(base_url('staff'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงระบบนี้');
        }

        $atdModel = new AttendanceModel();
        $userModel = new UserModel();

        // เลือกเดือนและปี (ค่าตั้งต้นคือเดือนปัจจุบัน)
        $month = $this->request->getGet('month') ?: date('n');
        $year = $this->request->getGet('year') ?: date('Y');

        $daysInMonth = (int)date('t', strtotime("$year-$month-01"));
        $startDate = "$year-" . sprintf('%02d', $month) . "-01";
        $endDate = "$year-" . sprintf('%02d', $month) . "-$daysInMonth";

        $data['title'] = "รายงานสรุปประจำเดือน | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        $data['month'] = $month;
        $data['year'] = $year;
        $data['days_in_month'] = $daysInMonth;

        // ดึงรายชื่อบุคลากร
        $users = $userModel->orderBy('u_fullname', 'ASC')->findAll();

        // ดึงข้อมูลการเข้างาน
        $attendanceData = $atdModel->where('atd_date >=', $startDate)
                                    ->where('atd_date <=', $endDate)
                                    ->where('atd_type', 'excel_import')
                                    ->findAll();

        // จัดกลุ่มข้อมูล [user_id][day] = status
        $reportGrid = [];
        foreach ($attendanceData as $row) {
            $day = (int)date('j', strtotime($row['atd_date']));
            $reportGrid[$row['atd_user_id']][$day] = [
                'status' => $row['atd_status'],
                'note'   => $row['atd_note']
            ];
        }

        $data['users'] = $users;
        $data['report_grid'] = $reportGrid;
        
        $data['thai_months_full'] = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
            7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        $data['thai_days_short'] = ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'];

        return view('staff/attendance_admin/report_monthly', $data);
    }

    /**
     * ส่งออกสรุปประจำเดือนเป็น Excel
     */
    public function exportMonthlyExcel()
    {
        $month = $this->request->getGet('month') ?: date('n');
        $yearArr = $this->request->getGet('year') ?: date('Y');
        
        $atdModel = new AttendanceModel();
        $userModel = new UserModel();

        $daysInMonth = (int)date('t', strtotime("$yearArr-$month-01"));
        $startDate = "$yearArr-" . sprintf('%02d', $month) . "-01";
        $endDate = "$yearArr-" . sprintf('%02d', $month) . "-$daysInMonth";

        $thaiMonthsFull = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
            7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        $thaiDaysShort = ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'];

        $users = $userModel->orderBy('u_fullname', 'ASC')->findAll();
        $attendanceData = $atdModel->where('atd_date >=', $startDate)
                                    ->where('atd_date <=', $endDate)
                                    ->where('atd_type', 'excel_import')
                                    ->findAll();

        $reportGrid = [];
        foreach ($attendanceData as $row) {
            $day = (int)date('j', strtotime($row['atd_date']));
            $reportGrid[$row['atd_user_id']][$day] = $row['atd_status'];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('สรุปประจำเดือน ' . $month);

        // Header Title
        $sheet->setCellValue('A1', 'สรุปบัญชีการมาปฏิบัติงานประจำเดือน ' . $thaiMonthsFull[$month] . ' ' . ($yearArr + 543));
        $sheet->mergeCells('A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($daysInMonth + 9) . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Main Headers (Row 3-4)
        $sheet->setCellValue('A3', 'ที่'); $sheet->mergeCells('A3:A4');
        $sheet->setCellValue('B3', 'ชื่อ-นามสกุล'); $sheet->mergeCells('B3:B4');
        
        // Days and Initials
        $col = 3;
        for($d = 1; $d <= $daysInMonth; $d++) {
            $colStr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $dayOfWeek = date('w', strtotime("$yearArr-$month-$d"));
            $sheet->setCellValue($colStr . '3', $thaiDaysShort[$dayOfWeek]);
            $sheet->setCellValue($colStr . '4', $d);
            
            // Shading Weekends
            if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $sheet->getStyle($colStr . '3:' . $colStr . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');
            }
            $col++;
        }

        // Summary Headers
        $summaryStart = $col;
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '3', 'สาย');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . '4', 'วัน');
        
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '3', 'ลาพักผ่อน');
        $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '3:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col+1) . '3');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . '4', 'ครั้ง');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . '4', 'วัน');

        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '3', 'ลากิจ');
        $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '3:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col+1) . '3');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . '4', 'ครั้ง');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . '4', 'วัน');

        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '3', 'ลาป่วย');
        $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '3:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col+1) . '3');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . '4', 'ครั้ง');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . '4', 'วัน');

        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '3', 'หมายเหตุ');
        $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '3:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '4');

        // Styles for Header
        $headerRange = 'A3:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '4';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Data Rows
        $rowIdx = 5;
        $no = 1;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $rowIdx, $no++);
            $sheet->setCellValue('B' . $rowIdx, $user['u_prefix'] . $user['u_fullname']);
            
            $col = 3;
            $sum = ['สาย' => 0, 'ลาพักผ่อน' => 0, 'ลากิจ' => 0, 'ลาป่วย' => 0];
            for($d = 1; $d <= $daysInMonth; $d++) {
                $colStr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $status = $reportGrid[$user['u_id']][$d] ?? '';
                $display = '';
                if ($status == 'สาย') { $display = 'ส'; $sum['สาย']++; }
                elseif ($status == 'ลาพักผ่อน') { $display = 'พ'; $sum['ลาพักผ่อน']++; }
                elseif ($status == 'ลากิจ') { $display = 'ก'; $sum['ลากิจ']++; }
                elseif ($status == 'ลาป่วย') { $display = 'ป'; $sum['ลาป่วย']++; }
                elseif ($status == 'ขาด') { $display = 'ข'; }
                
                $sheet->setCellValue($colStr . $rowIdx, $display);
                
                // Color Saturdays/Sundays
                $dayOfWeek = date('w', strtotime("$yearArr-$month-$d"));
                if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                    $sheet->getStyle($colStr . $rowIdx)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');
                }
                $col++;
            }
            
            // Summary Data
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $sum['สาย'] ?: '');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $sum['ลาพักผ่อน'] ?: '');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $sum['ลาพักผ่อน'] ?: '');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $sum['ลากิจ'] ?: '');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $sum['ลากิจ'] ?: '');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $sum['ลาป่วย'] ?: '');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $sum['ลาป่วย'] ?: '');
            
            $rowIdx++;
        }

        // Final Styles
        $dataRange = 'A5:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . ($rowIdx - 1);
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A5:A' . ($rowIdx - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . ($rowIdx - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Auto width
        $sheet->getColumnDimension('B')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Attendance_Report_' . $month . '_' . $yearArr . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    /**
     * ส่งออกสรุปรายปีเป็น Excel
     */
    public function exportAnnualExcel()
    {
        $fiscalYear = $this->request->getGet('year') ?: (date('n') >= 10 ? date('Y') + 1 : date('Y'));
        $round = $this->request->getGet('round') ?: 'all';
        $atdModel = new AttendanceModel();
        $userModel = new UserModel();

        $startYear = $fiscalYear - 1;
        $endYear = $fiscalYear;

        if ($round == '1') {
            $monthsOrder = [10, 11, 12, 1, 2, 3];
            $startDate = "$startYear-10-01";
            $endDate = "$endYear-03-31";
        } elseif ($round == '2') {
            $monthsOrder = [4, 5, 6, 7, 8, 9];
            $startDate = "$endYear-04-01";
            $endDate = "$endYear-09-30";
        } else {
            $monthsOrder = [10, 11, 12, 1, 2, 3, 4, 5, 6, 7, 8, 9];
            $startDate = "$startYear-10-01";
            $endDate = "$endYear-09-30";
        }

        $thaiMonths = [
            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.',
            7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
        ];

        $users = $userModel->orderBy('u_fullname', 'ASC')->findAll();
        $attendanceData = $atdModel->where('atd_date >=', $startDate)
                                    ->where('atd_date <=', $endDate)
                                    ->where('atd_type', 'excel_import')
                                    ->findAll();

        $reportData = [];
        foreach ($attendanceData as $row) {
            $month = (int)date('n', strtotime($row['atd_date']));
            $reportData[$row['atd_user_id']][$month][$row['atd_status']] = ($reportData[$row['atd_user_id']][$month][$row['atd_status']] ?? 0) + 1;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('สรุปรายปี ' . $fiscalYear);

        // Header Title
        $sheet->setCellValue('A1', 'รายงานสรุปการมาทำงานประจำปีงบประมาณ ' . $fiscalYear . ($round != 'all' ? ' (รอบที่ '.$round.')' : ''));
        $totalCols = (count($monthsOrder) * 7) + 1;
        $lastColStr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCols);
        $sheet->mergeCells('A1:' . $lastColStr . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Headers Row 3, 4, 5
        $sheet->setCellValue('A3', 'ชื่อ-นามสกุล'); $sheet->mergeCells('A3:A5');
        
        $col = 2;
        foreach($monthsOrder as $m) {
            $startColStr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $endColStr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 6);
            
            // Month Header
            $sheet->setCellValue($startColStr . '3', $thaiMonths[$m] . '-' . ($m >= 10 ? ($fiscalYear-1)%100 : $fiscalYear%100));
            $sheet->mergeCells($startColStr . '3:' . $endColStr . '3');
            
            // Status Headers
            $sheet->setCellValue($startColStr . '4', 'สาย');
            $sheet->mergeCells($startColStr . '4:' . $startColStr . '5');
            
            $nextCol = $col + 1;
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol) . '4', 'ลาพักผ่อน');
            $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol) . '4:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol+1) . '4');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol) . '5', 'ครั้ง');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol+1) . '5', 'วัน');
            
            $nextCol += 2;
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol) . '4', 'ลากิจ');
            $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol) . '4:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol+1) . '4');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol) . '5', 'ครั้ง');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol+1) . '5', 'วัน');

            $nextCol += 2;
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol) . '4', 'ลาป่วย');
            $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol) . '4:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol+1) . '4');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol) . '5', 'ครั้ง');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nextCol+1) . '5', 'วัน');

            $col += 7;
        }

        // Header Styling
        $headerRange = 'A3:' . $lastColStr . '5';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Data Rows
        $rowIdx = 6;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $rowIdx, $user['u_prefix'] . $user['u_fullname']);
            
            $col = 2;
            foreach ($monthsOrder as $m) {
                $uData = $reportData[$user['u_id']][$m] ?? [];
                
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $uData['สาย'] ?? '');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $uData['ลาพักผ่อน'] ?? '');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $uData['ลาพักผ่อน'] ?? '');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $uData['ลากิจ'] ?? '');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $uData['ลากิจ'] ?? '');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $uData['ลาป่วย'] ?? '');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $rowIdx, $uData['ลาป่วย'] ?? '');
            }
            $rowIdx++;
        }

        // Final Data Styling
        $dataRange = 'A6:' . $lastColStr . ($rowIdx - 1);
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getStyle('B6:' . $lastColStr . ($rowIdx - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Annual_Report_' . $fiscalYear . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function updateNote()
    {
        if ($this->request->isAJAX()) {
            $json = $this->request->getJSON();
            $atdModel = new AttendanceModel();
            
            $atdModel->update($json->id, [
                'atd_note' => $json->note
            ]);

            return $this->response->setJSON(['status' => 'success']);
        }
        return $this->response->setJSON(['status' => 'error'], 400);
    }
}
