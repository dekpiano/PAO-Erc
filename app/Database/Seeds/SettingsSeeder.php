<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                's_key'         => 'work_start_time',
                's_value'       => '08:30',
                's_description' => 'เวลาเริ่มงานปกติ (ใช้คำนวณการมาสาย)',
            ],
            [
                's_key'         => 'work_end_time',
                's_value'       => '16:30',
                's_description' => 'เวลาเลิกงานปกติ',
            ],
            [
                's_key'         => 'office_location',
                's_value'       => '13.7563,100.5018',
                's_description' => 'พิกัดที่ตั้งสำนักงานกลาง',
            ],
            [
                's_key'         => 'company_name',
                's_value'       => 'ลองเวลา (LongWela)',
                's_description' => 'ชื่อบริษัท / หน่วยงาน',
            ],
        ];

        $this->db->table('Tb_Settings')->insertBatch($data);
    }
}
