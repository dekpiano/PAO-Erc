<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateITSupportTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'its_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                'comment'        => 'รหัสบันทึก',
            ],
            'its_ticket_code' => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'comment'        => 'รหัสใบงานบริการ เช่น IT-202605-0001',
            ],
            'its_date' => [
                'type'           => 'DATETIME',
                'comment'        => 'วันเวลาที่ปฏิบัติงาน',
            ],
            'its_task' => [
                'type'           => 'TEXT',
                'comment'        => 'รายละเอียดงานบริการ/สิ่งที่ทำ',
            ],
            'its_category' => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'comment'        => 'หมวดหมู่ประเภทงานบริการ',
            ],
            'its_location' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'comment'        => 'สถานที่ปฏิบัติงาน',
            ],
            'its_recorded_by' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'comment'        => 'ชื่อเต็มของผู้บันทึกงาน',
            ],
            'its_user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'comment'        => 'รหัสผู้ใช้งานเจ้าหน้าที่ไอที (Tb_Users.u_id)',
            ],
            'its_images' => [
                'type'           => 'TEXT',
                'null'           => true,
                'comment'        => 'รูปภาพแนบในรูปแบบ JSON string array',
            ],
            'its_created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันที่สร้างบันทึก',
            ],
            'its_updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันที่แก้ไขล่าสุด',
            ],
        ]);

        $this->forge->addKey('its_id', true);
        $this->forge->addKey('its_user_id');
        $this->forge->createTable('Tb_It_Support_Logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_It_Support_Logs', true);
    }
}
