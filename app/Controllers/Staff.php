<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use CodeIgniter\Controller;

class Staff extends Controller
{
    public function index()
    {
        $userId = session()->get('u_id');
        $atdModel = new AttendanceModel();
        
        $data['title'] = "Dashboard บุคลากร | อบจ.นครสวรรค์";
        $data['fullname'] = session()->get('u_fullname');
        
        // Stats for current user
        $data['total_attendance'] = $atdModel->where('atd_user_id', $userId)->countAllResults();
        $data['today_checkin'] = $atdModel->where('atd_user_id', $userId)
                                          ->where('atd_type', 'check_in')
                                          ->where('DATE(atd_timestamp)', date('Y-m-d'))
                                          ->first();
                                          
        return view('staff/dashboard', $data);
    }
}
