<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLeaveRequestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'leave_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                'comment'        => 'รหัสการลา (Primary Key)',
            ],
            'leave_user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'รหัสผู้ใช้งานที่ทำการลา อ้างอิงจาก Tb_Users',
            ],
            'leave_type' => [
                'type'       => 'ENUM',
                'constraint' => ['sick', 'personal', 'maternity', 'paternity', 'vacation', 'ordination', 'military', 'study', 'international_org', 'spouse_follow', 'rehabilitation'],
                'default'    => 'sick',
                'comment'    => 'ประเภทการลา 11 ประเภทตามระเบียบฯ: sick(ป่วย), personal(กิจส่วนตัว), maternity(คลอดบุตร), paternity(ช่วยภริยาคลอดบุตร), vacation(พักผ่อน), ordination(อุปสมบท/ฮัจย์), military(รับราชการทหาร), study(ศึกษา/ฝึกอบรม), international_org(ปฏิบัติงานองค์กรระหว่างประเทศ), spouse_follow(ติดตามคู่สมรส), rehabilitation(ฟื้นฟูอาชีพ)',
            ],
            'leave_reason' => [
                'type'    => 'TEXT',
                'comment' => 'สาเหตุหรือเหตุผลในการลา',
            ],
            'leave_from_date' => [
                'type'    => 'DATE',
                'comment' => 'ลาตั้งแต่วันที่',
            ],
            'leave_to_date' => [
                'type'    => 'DATE',
                'comment' => 'ลาถึงวันที่',
            ],
            'leave_days' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,1',
                'default'    => 1.0,
                'comment'    => 'จำนวนวันที่ลาทั้งหมด',
            ],
            // ข้อมูลการลาครั้งล่าสุด (ถ้ามี)
            'leave_last_from_date' => [
                'type'    => 'DATE',
                'null'    => true,
                'comment' => 'ลาครั้งสุดท้ายตั้งแต่วันที่',
            ],
            'leave_last_to_date' => [
                'type'    => 'DATE',
                'null'    => true,
                'comment' => 'ลาครั้งสุดท้ายถึงวันที่',
            ],
            'leave_last_days' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,1',
                'null'       => true,
                'comment'    => 'จำนานวันที่ลาครั้งสุดท้าย',
            ],
            'leave_contact' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'สถานที่หรือเบอร์โทรศัพท์ที่ติดต่อได้ระหว่างลา',
            ],
            'leave_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default'    => 'pending',
                'comment'    => 'สถานะการลา (pending=รออนุมัติ, approved=อนุมัติ, rejected=ไม่อนุมัติ)',
            ],
            'leave_approver_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'รหัสผู้ใช้งานของผู้อนุมัติการลา (เช่น ผอ. กองการศึกษา)',
            ],
            'leave_created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'วันที่ระบบสร้างรายการบันทึกนี้',
            ],
            'leave_updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'วันที่ระบบแก้ไขรายการบันทึกนี้ล่าสุด',
            ],
        ]);
        
        $this->forge->addKey('leave_id', true);
        $this->forge->createTable('Tb_Leave');
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Leave');
    }
}
