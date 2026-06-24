<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScienceWeekCompetitionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'comp_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                'comment'        => 'รหัสประเภทการแข่งขัน',
            ],
            'comp_name' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'comment'        => 'ชื่อประเภทการแข่งขัน',
            ],
            'comp_icon' => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'default'        => 'award',
                'comment'        => 'ชื่อไอคอน Lucide',
            ],
            'comp_level' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'comment'        => 'ระดับชั้นที่เปิดรับสมัคร',
            ],
            'comp_description' => [
                'type'           => 'TEXT',
                'null'           => true,
                'comment'        => 'รายละเอียด/คำอธิบายประเภทการแข่ง',
            ],
            'comp_color' => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'default'        => 'cyan',
                'comment'        => 'สีตกแต่งธีม เช่น cyan, purple, indigo, pink, amber',
            ],
            'comp_created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันที่สร้าง',
            ],
            'comp_updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
                'comment'        => 'วันที่แก้ไข',
            ],
        ]);

        $this->forge->addKey('comp_id', true);
        $this->forge->createTable('Tb_ScienceWeek_Competitions', true);

        // Seed 7 initial categories
        $db = \Config\Database::connect();
        $initialData = [
            [
                'comp_name'        => 'การแข่งขันจรวดขวดน้ำประเภทระยะทาง',
                'comp_icon'        => 'rocket',
                'comp_level'       => 'มัธยมศึกษาตอนต้น-ปลาย',
                'comp_description' => 'ท้าทายจินตนาการและหลักการแอโรไดนามิกส์ ออกแบบจรวดและยิงเพื่อพิชิตระยะทางสูงสุด',
                'comp_color'       => 'cyan',
                'comp_created_at'  => date('Y-m-d H:i:s'),
                'comp_updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'comp_name'        => 'การแข่งขันจรวดขวดน้ำประเภทความแม่นยำ',
                'comp_icon'        => 'target',
                'comp_level'       => 'มัธยมศึกษาตอนต้น-ปลาย',
                'comp_description' => 'ท้าทายความสามารถทางฟิสิกส์และการเล็งยิง คำนวณวิถีโค้งเพื่อลงเป้าหมายให้แม่นยำที่สุด',
                'comp_color'       => 'cyan',
                'comp_created_at'  => date('Y-m-d H:i:s'),
                'comp_updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'comp_name'        => 'การประกวดการแสดงทางวิทยาศาสตร์ (Science Show)',
                'comp_icon'        => 'atom',
                'comp_level'       => 'ประถมศึกษา - มัธยมศึกษา',
                'comp_description' => 'การประกวดแสดงโชว์ทางวิทยาศาสตร์สุดสร้างสรรค์ ถ่ายทอดการทดลองที่สนุกและตื่นตาตื่นใจ',
                'comp_color'       => 'purple',
                'comp_created_at'  => date('Y-m-d H:i:s'),
                'comp_updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'comp_name'        => 'การประกวดโครงงานวิทยาศาสตร์ประเภททดลอง',
                'comp_icon'        => 'cpu',
                'comp_level'       => 'มัธยมศึกษาตอนต้น-ปลาย',
                'comp_description' => 'การนำเสนอผลงานโครงงานวิทยาศาสตร์ประเภททดลองและตั้งข้อสังเกตอย่างเป็นระบบ',
                'comp_color'       => 'indigo',
                'comp_created_at'  => date('Y-m-d H:i:s'),
                'comp_updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'comp_name'        => 'การประกวดโครงงานวิทยาศาสตร์ประเภทสิ่งประดิษฐ์',
                'comp_icon'        => 'lightbulb',
                'comp_level'       => 'มัธยมศึกษาตอนต้น-ปลาย',
                'comp_description' => 'การประกวดโครงงานประดิษฐ์และนวัตกรรมเพื่อการประยุกต์ใช้งานและแก้ปัญหาจริง',
                'comp_color'       => 'indigo',
                'comp_created_at'  => date('Y-m-d H:i:s'),
                'comp_updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'comp_name'        => 'การประกวดวาดภาพการ์ตูนทางวิทยาศาสตร์',
                'comp_icon'        => 'palette',
                'comp_level'       => 'ทุกระดับชั้น',
                'comp_description' => 'การประกวดวาดภาพการ์ตูนแนววิทยาศาสตร์อวกาศและเทคโนโลยีแห่งอนาคต',
                'comp_color'       => 'pink',
                'comp_created_at'  => date('Y-m-d H:i:s'),
                'comp_updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'comp_name'        => 'การแข่งขันหุ่นยนต์อัตโนมัติ',
                'comp_icon'        => 'bot',
                'comp_level'       => 'มัธยมศึกษาตอนต้น-ปลาย',
                'comp_description' => 'การแข่งขันเขียนโปรแกรมควบคุมระบบเซ็นเซอร์หุ่นยนต์และบอร์ดสมองกลเพื่อพิชิตภารกิจ',
                'comp_color'       => 'amber',
                'comp_created_at'  => date('Y-m-d H:i:s'),
                'comp_updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $db->table('Tb_ScienceWeek_Competitions')->insertBatch($initialData);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_ScienceWeek_Competitions', true);
    }
}
