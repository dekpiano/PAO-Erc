<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScienceWeekTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'reg_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                'comment'        => 'รหัสการสมัคร',
            ],
            'reg_code' => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'comment'        => 'รหัสใบสมัคร เช่น SCI-2026-00001',
            ],
            'reg_competition_type' => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'comment'        => 'ประเภทการแข่งขัน',
            ],
            'reg_school_name' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'comment'        => 'ชื่อโรงเรียน',
            ],
            'reg_team_name' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
                'comment'        => 'ชื่อทีม',
            ],
            'reg_members' => [
                'type'           => 'TEXT',
                'comment'        => 'รายชื่อสมาชิกในทีม (JSON string array)',
            ],
            'reg_advisors' => [
                'type'           => 'TEXT',
                'comment'        => 'รายชื่ออาจารย์ที่ปรึกษา (JSON string array)',
            ],
            'reg_contact_phone' => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'comment'        => 'เบอร์โทรติดต่อ',
            ],
            'reg_contact_email' => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => true,
                'comment'        => 'อีเมลติดต่อ',
            ],
            'reg_status' => [
                'type'           => 'VARCHAR',
                'constraint'     => '20',
                'default'        => 'pending',
                'comment'        => 'สถานะการสมัคร: pending, approved, rejected',
            ],
            'reg_created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันที่ยื่นสมัคร',
            ],
            'reg_updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันที่แก้ไขล่าสุด',
            ],
        ]);

        $this->forge->addKey('reg_id', true);
        $this->forge->createTable('Tb_ScienceWeek_Registrations', true);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_ScienceWeek_Registrations', true);
    }
}
