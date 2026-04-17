<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'not_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                'comment'        => 'รหัสการแจ้งเตือน',
            ],
            'not_user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'comment'        => 'รหัสผู้รับแจ้งเตือน (Tb_Users)',
            ],
            'not_title' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'comment'        => 'หัวข้อการแจ้งเตือน',
            ],
            'not_message' => [
                'type'           => 'TEXT',
                'null'           => true,
                'comment'        => 'เนื้อหาหรือรายละเอียดการแจ้งเตือน',
            ],
            'not_link' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
                'comment'        => 'ลิงก์สำหรับคลิกไปดูรายละเอียด',
            ],
            'not_is_read' => [
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
                'comment'        => 'สถานะการอ่าน (0=ยังไม่อ่าน, 1=อ่านแล้ว)',
            ],
            'not_created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันที่สร้างรายการ',
            ],
            'not_updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันที่แก้ไขล่าสุด',
            ],
        ]);

        $this->forge->addKey('not_id', true);
        $this->forge->addKey('not_user_id');
        $this->forge->createTable('Tb_Notifications', true);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Notifications', true);
    }
}
