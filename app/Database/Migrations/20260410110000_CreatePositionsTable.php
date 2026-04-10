<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePositionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pos_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                'comment'        => 'รหัสตำแหน่ง (Primary Key)',
            ],
            'pos_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'comment'    => 'ชื่อตำแหน่งเต็ม (เช่น นักวิชาการศึกษาปฏิบัติการ, ผู้ช่วยเจ้าพนักงานธุรการ)',
            ],
            'pos_type' => [
                'type'       => 'ENUM',
                'constraint' => ['civil_servant', 'mission_based', 'general_contract'],
                'default'    => 'civil_servant',
                'comment'    => 'ประเภทบุคลากร: civil_servant(ข้าราชการ), mission_based(พนักงานจ้างตามภารกิจ), general_contract(พนักงานจ้างทั่วไป)',
            ],
            'pos_level' => [
                'type'       => 'ENUM',
                'constraint' => ['academic', 'general', 'executive', 'administrative'],
                'default'    => 'general',
                'comment'    => 'แท่งงาน/กลุ่มระดับ: academic(วิชาการ), general(ทั่วไป), executive(อำนวยการ/หัวหน้าฝ่าย), administrative(บริหาร/ปลัด)',
            ],
            'pos_is_head' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => 'สถานะระดับหัวหน้า: 1=เป็นหัวหน้า(มีอำนาจตรวจ/อนุมัติ), 0=ไม่ใช่',
            ],
            'pos_created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'pos_updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('pos_id', true);
        $this->forge->createTable('Tb_Positions');
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Positions');
    }
}
