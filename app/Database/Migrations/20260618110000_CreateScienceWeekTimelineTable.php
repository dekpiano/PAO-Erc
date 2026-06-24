<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScienceWeekTimelineTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'sch_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                'comment'        => 'รหัสกำหนดการ',
            ],
            'sch_date' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'comment'        => 'วันเวลาจัดกิจกรรม',
            ],
            'sch_title' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'comment'        => 'ชื่อกำหนดการ',
            ],
            'sch_description' => [
                'type'           => 'TEXT',
                'null'           => true,
                'comment'        => 'รายละเอียดข้อความ',
            ],
            'sch_color' => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'default'        => 'cyan',
                'comment'        => 'สีตกแต่งปุ่ม dot timeline',
            ],
            'sch_created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันที่สร้าง',
            ],
            'sch_updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันที่แก้ไข',
            ],
        ]);

        $this->forge->addKey('sch_id', true);
        $this->forge->createTable('Tb_ScienceWeek_Schedules', true);

        // Seed default items
        $db = \Config\Database::connect();
        $initialData = [
            [
                'sch_date'        => '1 - 31 กรกฎาคม 2026',
                'sch_title'       => 'เปิดรับสมัครแข่งขันออนไลน์',
                'sch_description' => 'รับสมัครผู้สนใจและทีมโรงเรียนลงทะเบียนสมัครรับคัดเลือกแบบเรียลไทม์ผ่านเว็บไซต์',
                'sch_color'       => 'cyan',
                'sch_created_at'  => date('Y-m-d H:i:s'),
                'sch_updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'sch_date'        => '10 สิงหาคม 2026',
                'sch_title'       => 'ประกาศรายชื่อผู้มีสิทธิ์เข้าแข่งขัน',
                'sch_description' => 'ประกาศรายชื่อทีมที่ได้รับสิทธิ์ยืนยันพร้อมลำดับการแข่งอย่างเป็นทางการ',
                'sch_color'       => 'indigo',
                'sch_created_at'  => date('Y-m-d H:i:s'),
                'sch_updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'sch_date'        => '18 - 20 สิงหาคม 2026',
                'sch_title'       => 'วันดำเนินกิจกรรม & การแข่งขันตัดสิน',
                'sch_description' => 'จัดงาน ณ ห้องโถงนวัตกรรม กองการศึกษา พร้อมการประกวดและมอบรางวัลแก่ผู้ชนะ',
                'sch_color'       => 'purple',
                'sch_created_at'  => date('Y-m-d H:i:s'),
                'sch_updated_at'  => date('Y-m-d H:i:s'),
            ]
        ];

        $db->table('Tb_ScienceWeek_Schedules')->insertBatch($initialData);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_ScienceWeek_Schedules', true);
    }
}
