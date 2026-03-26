<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
        $data['title'] = "กองการศึกษา ศาสนา และวัฒนธรรม | อบจ.นครสวรรค์";
        return view('home/index', $data);
    }
}
