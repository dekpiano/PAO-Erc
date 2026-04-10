<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PositionModel;

class Position extends BaseController
{
    protected $positionModel;

    public function __construct()
    {
        $this->positionModel = new PositionModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        // ตรวจสอบว่าเข้าระบบและเป็นแอดมินหรือไม่ (สมมติว่าใช้ u_role)
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $role = session()->get('u_role');
        if (strpos($role, 'admin') === false && strpos($role, 'superadmin') === false) {
             return redirect()->to('/staff/leave')->with('error', 'คุณไม่มีสิทธิเข้าถึงหน้านี้');
        }

        $data = [
            'title' => 'จัดการตำแหน่งงาน',
            'positions' => $this->positionModel->orderBy('pos_id', 'DESC')->findAll()
        ];

        return view('staff/position/index', $data);
    }

    public function store()
    {
        $rules = [
            'pos_name' => 'required|min_length[3]',
            'pos_type' => 'required',
            'pos_level' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'ข้อมูลไม่ถูกต้อง');
        }

        $data = [
            'pos_name' => $this->request->getVar('pos_name'),
            'pos_type' => $this->request->getVar('pos_type'),
            'pos_level' => $this->request->getVar('pos_level'),
            'pos_is_head' => $this->request->getVar('pos_is_head') ? 1 : 0,
        ];

        $this->positionModel->insert($data);
        return redirect()->to('/admin/position')->with('success', 'เพิ่มตำแหน่งเรียบร้อยแล้ว');
    }

    public function update($id)
    {
        $data = [
            'pos_name' => $this->request->getVar('pos_name'),
            'pos_type' => $this->request->getVar('pos_type'),
            'pos_level' => $this->request->getVar('pos_level'),
            'pos_is_head' => $this->request->getVar('pos_is_head') ? 1 : 0,
        ];

        $this->positionModel->update($id, $data);
        return redirect()->to('/admin/position')->with('success', 'แก้ไขตำแหน่งเรียบร้อยแล้ว');
    }

    public function delete($id)
    {
        // ตรวจสอบก่อนว่ามีคนใช้ตำแหน่งนี้อยู่ไหม (ถ้ามีไม่ควรลบ)
        $db = \Config\Database::connect();
        $userCount = $db->table('Tb_Users')->where('u_pos_id', $id)->countAllResults();

        if ($userCount > 0) {
            return redirect()->to('/admin/position')->with('error', 'ไม่สามารถลบได้ เนื่องจากมีพนักงานใช้งานตำแหน่งนี้อยู่');
        }

        $this->positionModel->delete($id);
        return redirect()->to('/admin/position')->with('success', 'ลบตำแหน่งเรียบร้อยแล้ว');
    }
}
