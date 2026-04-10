<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FinalizePositionColumn extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // 1. ดึงข้อมูลตำแหน่งทั้งหมดมาสร้าง Map [ชื่อ => ID]
        $positions = $db->table('Tb_Positions')->get()->getResultArray();
        $posMap = [];
        foreach ($positions as $p) {
            $posMap[trim($p['pos_name'])] = $p['pos_id'];
        }

        // 2. อัปเดต u_pos_id ให้กับพนักงานที่ชื่อตำแหน่งตรงกัน
        $users = $db->table('Tb_Users')->get()->getResultArray();
        foreach ($users as $u) {
            $textPos = trim($u['u_position']);
            if (isset($posMap[$textPos])) {
                $db->table('Tb_Users')
                   ->where('u_id', $u['u_id'])
                   ->update(['u_pos_id' => $posMap[$textPos]]);
            }
        }

        // 3. ลบคอลัมน์ u_position เดิม (ที่เป็น Text) ออก
        $this->forge->dropColumn('Tb_Users', 'u_position');

        // 4. เปลี่ยนชื่อ u_pos_id ให้เป็น u_position (และเป็น INT)
        $fields = [
            'u_pos_id' => [
                'name' => 'u_position',
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('Tb_Users', $fields);
    }

    public function down()
    {
        // ย้อนกลับ
        $fields = [
            'u_position' => [
                'name' => 'u_pos_id',
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('Tb_Users', $fields);
        $this->forge->addColumn('Tb_Users', [
            'u_position' => ['type' => 'TEXT', 'null' => true]
        ]);
    }
}
