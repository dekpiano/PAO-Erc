<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // --- ระดับบริหาร/อำนวยการ ---
            [
                'pos_name'    => 'ผู้อำนวยการกองการศึกษา ศาสนา และวัฒนธรรม',
                'pos_type'    => 'civil_servant',
                'pos_level'   => 'administrative',
                'pos_is_head' => 1,
            ],
            [
                'pos_name'    => 'หัวหน้าฝ่ายบริหารงานทั่วไป',
                'pos_type'    => 'civil_servant',
                'pos_level'   => 'executive',
                'pos_is_head' => 1,
            ],
            [
                'pos_name'    => 'หัวหน้าฝ่ายแผนงานและโครงการ',
                'pos_type'    => 'civil_servant',
                'pos_level'   => 'executive',
                'pos_is_head' => 1,
            ],
            [
                'pos_name'    => 'หัวหน้าฝ่ายส่งเสริมการศึกษา ศาสนา และวัฒนธรรม',
                'pos_type'    => 'civil_servant',
                'pos_level'   => 'executive',
                'pos_is_head' => 1,
            ],

            // --- ข้าราชการ (สายวิชาการ/ทั่วไป) ---
            [
                'pos_name'    => 'นักวิชาการศึกษาปฏิบัติการ',
                'pos_type'    => 'civil_servant',
                'pos_level'   => 'academic',
                'pos_is_head' => 0,
            ],
            [
                'pos_name'    => 'นักวิเคราะห์นโยบายและแผนปฏิบัติการ',
                'pos_type'    => 'civil_servant',
                'pos_level'   => 'academic',
                'pos_is_head' => 0,
            ],
            [
                'pos_name'    => 'นักจัดการงานทั่วไปปฏิบัติการ',
                'pos_type'    => 'civil_servant',
                'pos_level'   => 'academic',
                'pos_is_head' => 0,
            ],
            [
                'pos_name'    => 'นักวิชาการเงินและบัญชีปฏิบัติการ',
                'pos_type'    => 'civil_servant',
                'pos_level'   => 'academic',
                'pos_is_head' => 0,
            ],
            [
                'pos_name'    => 'เจ้าพนักงานธุรการปฏิบัติงาน',
                'pos_type'    => 'civil_servant',
                'pos_level'   => 'general',
                'pos_is_head' => 0,
            ],

            // --- พนักงานจ้างตามภารกิจ ---
            [
                'pos_name'    => 'ผู้ช่วยนักวิชาการศึกษา',
                'pos_type'    => 'mission_based',
                'pos_level'   => 'academic',
                'pos_is_head' => 0,
            ],
            [
                'pos_name'    => 'ผู้ช่วยนักวิชาการคอมพิวเตอร์',
                'pos_type'    => 'mission_based',
                'pos_level'   => 'academic',
                'pos_is_head' => 0,
            ],
            [
                'pos_name'    => 'ผู้ช่วยนักจัดการงานทั่วไป',
                'pos_type'    => 'mission_based',
                'pos_level'   => 'academic',
                'pos_is_head' => 0,
            ],
            [
                'pos_name'    => 'ผู้ช่วยเจ้าพนักงานธุรการ',
                'pos_type'    => 'mission_based',
                'pos_level'   => 'general',
                'pos_is_head' => 0,
            ],
            [
                'pos_name'    => 'พนักงานขับรถยนต์',
                'pos_type'    => 'mission_based',
                'pos_level'   => 'general',
                'pos_is_head' => 0,
            ],

            // --- พนักงานจ้างทั่วไป ---
            [
                'pos_name'    => 'ภารโรง',
                'pos_type'    => 'general_contract',
                'pos_level'   => 'general',
                'pos_is_head' => 0,
            ],
            [
                'pos_name'    => 'คนงาน',
                'pos_type'    => 'general_contract',
                'pos_level'   => 'general',
                'pos_is_head' => 0,
            ],
        ];

        // ล้างข้อมูลเก่าก่อนเพื่อป้องกันข้อมูลซ้ำ
        $this->db->table('Tb_Positions')->truncate();

        // วนลูปบันทึกข้อมูล
        foreach ($data as $item) {
            $this->db->table('Tb_Positions')->insert(array_merge($item, [
                'pos_created_at' => date('Y-m-d H:i:s'),
                'pos_updated_at' => date('Y-m-d H:i:s'),
            ]));
        }
    }
}
