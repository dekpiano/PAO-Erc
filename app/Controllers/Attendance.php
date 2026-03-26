<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\SettingsModel;
use CodeIgniter\Controller;

class Attendance extends Controller
{
    public function index()
    {
        helper('thai_date');

        $model = new AttendanceModel();
        $sModel = new SettingsModel();
        
        $userId = session()->get('u_id');
        
        // Fetch last 5 records
        $data['history'] = $model->where('atd_user_id', $userId)
                                 ->orderBy('atd_timestamp', 'DESC')
                                 ->limit(5)
                                 ->find();
        
        $data['fullname'] = session()->get('u_fullname');
        
        // Fetch settings for Geofencing
        $data['office_location'] = $sModel->where('s_key', 'office_location')->first()['s_value'] ?? '0,0';
        $data['max_distance'] = $sModel->where('s_key', 'max_distance')->first()['s_value'] ?? '500';

        return view('staff/attendance', $data);
    }

    public function submit()
    {
        $model = new AttendanceModel();
        $type = $this->request->getPost('type');
        $location = $this->request->getPost('location');
        
        $data = [
            'atd_user_id'   => session()->get('u_id'),
            'atd_type'      => $type,
            'atd_timestamp' => date('Y-m-d H:i:s'),
            'atd_ip'        => $this->request->getIPAddress(),
            'atd_location'  => $location,
        ];
        
        $model->insert($data);
        
        return redirect()->to(base_url('staff/attendance'))->with('status', 'บันทึกเวลาเรียบร้อยแล้ว');
    }
}
