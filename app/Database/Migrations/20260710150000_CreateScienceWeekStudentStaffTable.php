<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScienceWeekStudentStaffTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'staff_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                'comment'        => 'รหัสหลักนักเรียนช่วยงาน',
            ],
            'staff_year' => [
                'type'           => 'VARCHAR',
                'constraint'     => '4',
                'comment'        => 'ปีการศึกษา',
            ],
            'staff_competition_type' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'comment'        => 'ประเภทการแข่งขันที่ช่วยงาน',
            ],
            'staff_prefix' => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'comment'        => 'คำนำหน้าชื่อ',
            ],
            'staff_firstname' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'comment'        => 'ชื่อจริง',
            ],
            'staff_lastname' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'comment'        => 'นามสกุล',
            ],
            'staff_class' => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'comment'        => 'ชั้นเรียน (เช่น ม.3/1)',
            ],
            'staff_created_by' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
                'comment'        => 'ผู้สร้าง/เพิ่มชื่อ',
            ],
            'staff_created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันเวลาที่สร้าง',
            ],
            'staff_updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันเวลาที่แก้ไขล่าสุด',
            ],
        ]);

        $this->forge->addKey('staff_id', true);
        $this->forge->createTable('Tb_ScienceWeek_StudentStaff', true);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_ScienceWeek_StudentStaff', true);
    }
}
