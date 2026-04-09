<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\UserModel;
use App\Models\SettingsModel;
use CodeIgniter\Controller;

class Admin extends Controller
{
    public function index()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'summary') === false) {
            return redirect()->to(base_url('/'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        helper('thai_date');
        $atdModel = new AttendanceModel();
        $sModel = new SettingsModel();
        
        $db      = \Config\Database::connect();
        $builder = $db->table('Tb_Attendance');
        $builder->select('Tb_Attendance.*, Tb_Users.u_fullname, Tb_Users.u_username, Tb_Users.u_position');
        $builder->join('Tb_Users', 'Tb_Users.u_id = Tb_Attendance.atd_user_id');
        
        // Date Filter
        $filter_date = $this->request->getGet('date') ?: date('Y-m-d');
        $builder->where('DATE(atd_timestamp)', $filter_date);
        
        $builder->orderBy('atd_timestamp', 'DESC');
        $query = $builder->get();
        
        $data['all_attendance'] = $query->getResultArray();
        $data['filter_date'] = $filter_date;
        $data['fullname'] = session()->get('u_fullname');
        $data['office_location'] = $sModel->where('s_key', 'office_location')->first()['s_value'] ?? '0,0';
        $data['work_start_time'] = $sModel->where('s_key', 'work_start_time')->first()['s_value'] ?? '08:30';
        $data['work_end_time'] = $sModel->where('s_key', 'work_end_time')->first()['s_value'] ?? '16:30';

        $today = date('Y-m-d');
        $data['stats'] = [
            'total_filter' => count($data['all_attendance']),
            'today_in' => $atdModel->where('atd_type', 'check_in')->where('DATE(atd_timestamp)', $filter_date)->countAllResults(),
            'today_out' => $atdModel->where('atd_type', 'check_out')->where('DATE(atd_timestamp)', $filter_date)->countAllResults(),
        ];
                                 
        return view('staff/admin_summary', $data);
    }

    // --------------------------------------------------------------------
    // 👥 STAFF MANAGEMENT
    // --------------------------------------------------------------------
    // --------------------------------------------------------------------
    // 🔐 PERMISSION MANAGEMENT
    // --------------------------------------------------------------------
    public function permissions()
    {
        if (strpos(session()->get('u_role') ?? '', 'superadmin') === false) {
            return redirect()->to(base_url('/'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $model = new UserModel();
        // เอาเฉพาะคนที่เป็นพนักงาน (ไม่พ้นสภาพ) มาตั้งค่าสิทธิ์
        $data['users'] = $model->where('u_status', 'active')->orderBy('u_sort', 'ASC')->findAll();
        $data['fullname'] = session()->get('u_fullname');
        
        // นิยามสิทธิ์ที่มีในระบบ
        $data['available_permissions'] = [
            'news'         => ['label' => 'จัดการข่าวสาร', 'icon' => 'newspaper', 'color' => 'text-rose-500'],
            'scholarships' => ['label' => 'จัดการทุนการศึกษา', 'icon' => 'graduation-cap', 'color' => 'text-amber-500'],
            'personnel'    => ['label' => 'จัดการบุคลากร', 'icon' => 'users', 'color' => 'text-blue-500'],
            'summary'      => ['label' => 'ดูสรุปเวลาปฏิบัติงาน', 'icon' => 'bar-chart-3', 'color' => 'text-indigo-500'],
            'settings'     => ['label' => 'ตั้งค่าระบบ', 'icon' => 'settings', 'color' => 'text-slate-500'],
        ];

        return view('staff/permissions', $data);
    }

    public function permissionsUpdate()
    {
        if (strpos(session()->get('u_role') ?? '', 'superadmin') === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $userId = $this->request->getPost('u_id');
        $perms = $this->request->getPost('permissions'); // Array of permission keys
        
        $model = new UserModel();
        $user = $model->find($userId);

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // เก็บสิทธิ์ superadmin ไว้เสมอถ้าเขามีอยู่แล้ว หรือถ้าตารางมีค่า superadmin
        $currentRoles = explode(',', $user['u_role'] ?? '');
        $newRoles = $perms ?: [];
        
        if (in_array('superadmin', $currentRoles)) {
            if (!in_array('superadmin', $newRoles)) {
                $newRoles[] = 'superadmin';
            }
        }

        // อัปเดต u_role
        $model->update($userId, [
            'u_role' => implode(',', array_filter(array_unique($newRoles)))
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'อัปเดตสิทธิ์การใช้งานเรียบร้อยแล้ว']);
    }

    public function users()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'personnel') === false) {
            return redirect()->to(base_url('/'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $model = new UserModel();
        $data['users'] = $model->findAll();
        $data['fullname'] = session()->get('u_fullname');
        return view('staff/personnel/index', $data);
    }

    public function userSave()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'personnel') === false) {
            return redirect()->to(base_url('/'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        try {
            $db = \Config\Database::connect();
            $db->query("ALTER TABLE Tb_Users MODIFY u_role VARCHAR(255) NULL");
        } catch (\Throwable $e) { }

        $model = new UserModel();
        $id = $this->request->getPost('u_id');

        $data = [
            'u_username' => $this->request->getPost('u_username'),
            'u_email'    => $this->request->getPost('u_email'),
            'u_fullname' => $this->request->getPost('u_fullname'),
            'u_prefix'   => $this->request->getPost('u_prefix'),
            'u_position' => $this->request->getPost('u_position'),
            'u_division' => $this->request->getPost('u_division'),
            'u_phone'    => $this->request->getPost('u_phone'),
            'u_sort'     => $this->request->getPost('u_sort') ?: 99,
            'u_status'   => $this->request->getPost('u_status') ?: 'active',
            'u_role'     => is_array($this->request->getPost('u_role')) ? implode(',', $this->request->getPost('u_role')) : $this->request->getPost('u_role'),
        ];

        // Handle Photo Upload
        $photoFile = $this->request->getFile('u_photo');
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            
            // Delete old photo if exists
            if ($id) {
                $oldUser = $model->find($id);
                if ($oldUser && $oldUser['u_photo'] && file_exists(FCPATH . 'uploads/personnel/' . $oldUser['u_photo'])) {
                    @unlink(FCPATH . 'uploads/personnel/' . $oldUser['u_photo']);
                }
            }

            $newName = $photoFile->getRandomName();
            if (!is_dir(FCPATH . 'uploads/personnel')) {
                mkdir(FCPATH . 'uploads/personnel', 0777, true);
            }
            $photoFile->move(FCPATH . 'uploads/personnel/', $newName);
            
            // Optimize image (Resize to 800px width max)
            $image = \Config\Services::image()
                ->withFile(FCPATH . 'uploads/personnel/' . $newName)
                ->resize(800, 800, true, 'height')
                ->save(FCPATH . 'uploads/personnel/' . $newName, 80);

            $data['u_photo'] = $newName;
        }

        // If password is provided, hash it
        $password = $this->request->getPost('u_password');
        if (!empty($password)) {
            $data['u_password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($id) {
            $model->update($id, $data);
            $msg = 'อัปเดตข้อมูลบุคลากรเรียบร้อยแล้ว';
        } else {
            $model->insert($data);
            $msg = 'เพิ่มบุคลากรใหม่เรียบร้อยแล้ว';
        }

        return redirect()->to(base_url('staff/personnel'))->with('status', $msg);
    }

    public function userDelete($id)
    {
        $model = new UserModel();
        $model->delete($id);
        return redirect()->to(base_url('staff/personnel'))->with('status', 'ลบข้อมูลพนักงานเรียบร้อยแล้ว');
    }

    // --------------------------------------------------------------------
    // ⚙️ SETTINGS
    // --------------------------------------------------------------------
    public function settings()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'settings') === false) {
            return redirect()->to(base_url('/'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $model = new SettingsModel();
        $settings = $model->findAll();
        
        // Convert to key-value
        $data['settings'] = [];
        foreach($settings as $s) {
            $data['settings'][$s['s_key']] = $s['s_value'];
        }

        $data['fullname'] = session()->get('u_fullname');
        return view('staff/settings', $data);
    }

    public function settingsUpdate()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'settings') === false) {
            return redirect()->to(base_url('/'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        $model = new SettingsModel();
        $posts = $this->request->getPost();

        foreach($posts as $key => $value) {
            $exists = $model->where('s_key', $key)->first();
            if ($exists) {
                $model->update($exists['s_id'], ['s_value' => $value]);
            } else {
                $model->insert(['s_key' => $key, 's_value' => $value]);
            }
        }

        return redirect()->to(base_url('staff/settings'))->with('status', 'บันทึกการตั้งค่าเรียบร้อยแล้ว');
    }
    public function exportExcel()
    {
        $userRoles = session()->get('u_role') ?? '';
        if (strpos($userRoles, 'superadmin') === false && strpos($userRoles, 'summary') === false) {
            return redirect()->to(base_url('/'))->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $date = $this->request->getGet('date') ?: date('Y-m-d');
        
        $db      = \Config\Database::connect();
        $builder = $db->table('Tb_Attendance');
        $builder->select('Tb_Attendance.*, Tb_Users.u_fullname, Tb_Users.u_username');
        $builder->join('Tb_Users', 'Tb_Users.u_id = Tb_Attendance.atd_user_id');
        $builder->where('DATE(atd_timestamp)', $date);
        $builder->orderBy('atd_timestamp', 'ASC');
        $query = $builder->get();
        $results = $query->getResultArray();

        $filename = "Attendance_Report_" . $date . ".csv";

        // Headers for CSV download
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; charset=UTF-8");

        $file = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel to open in correct encoding
        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        // Column headers
        $header = ['ลำดับ', 'ชื่อ-นามสกุล', 'Username', 'ตำแหน่ง', 'ประเภท', 'วันที่/เวลา', 'พิกัด', 'หมายเหตุ'];
        fputcsv($file, $header);

        $i = 1;
        foreach ($results as $row) {
            $type = ($row['atd_type'] == 'check_in') ? 'เข้างาน' : 'ออกงาน';
            $line = [
                $i++,
                $row['u_fullname'],
                $row['u_username'],
                $row['u_position'] ?: 'บุคลากร',
                $type,
                $row['atd_timestamp'],
                $row['atd_location'],
                $row['atd_note']
            ];
            fputcsv($file, $line);
        }

        fclose($file);
        exit;
    }
}
