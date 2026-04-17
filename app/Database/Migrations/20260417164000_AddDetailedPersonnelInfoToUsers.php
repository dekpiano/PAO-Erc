<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailedPersonnelInfoToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'u_id_card' => [
                'type'       => 'VARCHAR',
                'constraint' => '13',
                'null'       => true,
                'comment'    => 'เลขประจำตัวประชาชน',
                'after'      => 'u_prefix'
            ],
            'u_birthday' => [
                'type'    => 'DATE',
                'null'    => true,
                'comment' => 'วัน/เดือน/ปีเกิด',
                'after'   => 'u_fullname'
            ],
            'u_blood_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
                'comment'    => 'หมู่เลือด',
                'after'      => 'u_birthday'
            ],
            'u_religion' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'ศาสนา',
                'after'      => 'u_blood_type'
            ],
            'u_nationality' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'สัญชาติ',
                'after'      => 'u_religion'
            ],
            'u_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'comment'    => 'เบอร์โทรศัพท์มือถือ',
                'after'      => 'u_email'
            ],
            'u_address' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'ที่อยู่ตามทะเบียนบ้าน',
                'after'   => 'u_phone'
            ],
            'u_current_address' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'ที่อยู่ปัจจุบัน',
                'after'   => 'u_address'
            ],
            'u_emergency_contact' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'ข้อมูลผู้ติดต่อฉุกเฉิน (ชื่อ-โทร)',
                'after'   => 'u_current_address'
            ],
            'u_hired_date' => [
                'type'    => 'DATE',
                'null'    => true,
                'comment' => 'วันที่เริ่มบรรจุ/เริ่มงาน',
                'after'   => 'u_emergency_contact'
            ],
        ];

        $this->forge->addColumn('Tb_Users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Users', [
            'u_id_card', 'u_birthday', 'u_blood_type', 'u_religion', 'u_nationality',
            'u_phone', 'u_address', 'u_current_address', 'u_emergency_contact', 'u_hired_date'
        ]);
    }
}
