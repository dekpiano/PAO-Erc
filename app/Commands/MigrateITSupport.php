<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\ITSupportModel;

class MigrateITSupport extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Database';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'itsupport:migrate';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Migrate old IT Support logs from D:\\SkjSystem\\work\\data\\logs.json and copy uploads.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'itsupport:migrate';

    /**
     * Actually run a command
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $jsonPath = WRITEPATH . 'logs.json';
        $targetUploadsDir = ROOTPATH . 'public/uploads/it_support/';

        CLI::write("🔄 เริ่มกระบวนการย้ายข้อมูล IT Support...", 'cyan');
        CLI::write("📍 ROOTPATH: " . ROOTPATH, 'yellow');
        CLI::write("📍 FCPATH: " . FCPATH, 'yellow');
        CLI::write("📁 Target Uploads Folder: {$targetUploadsDir}", 'yellow');

        if (!file_exists($jsonPath)) {
            CLI::error("❌ ไม่พบไฟล์ข้อมูล logs.json ในตำแหน่ง: {$jsonPath}");
            return;
        }

        $jsonContent = file_get_contents($jsonPath);
        $logs = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            CLI::error("❌ ไม่สามารถถอดรหัสไฟล์ JSON ได้: " . json_last_error_msg());
            return;
        }

        $totalLogs = count($logs);
        CLI::write("📊 พบข้อมูลในไฟล์ JSON ทั้งหมด: {$totalLogs} รายการ", 'green');

        // กลับอาร์เรย์เพื่อบันทึกประวัติที่เก่าที่สุดลงฐานข้อมูลก่อน เพื่อให้รันเลข ID และ Ticket Code ชิปตามเวลาจริง
        $logs = array_reverse($logs);

        // ตรวจสอบและสร้างโฟลเดอร์ปลายทาง
        if (!is_dir($targetUploadsDir)) {
            if (mkdir($targetUploadsDir, 0777, true)) {
                CLI::write("📁 สร้างโฟลเดอร์เก็บรูปภาพปลายทางสำเร็จ: {$targetUploadsDir}", 'green');
            } else {
                CLI::error("❌ ไม่สามารถสร้างโฟลเดอร์ปลายทางได้!");
                return;
            }
        }

        $db = \Config\Database::connect();
        
        // เคลียร์ตารางเดิมเพื่อหลีกเลี่ยงข้อมูลซ้ำซ้อน
        CLI::write("🧹 กำลังเคลียร์ข้อมูลเดิมในตาราง Tb_It_Support_Logs...", 'yellow');
        $db->table('Tb_It_Support_Logs')->truncate();
        CLI::write("✅ เคลียร์ข้อมูลเรียบร้อยแล้ว!", 'green');

        $itsModel = new ITSupportModel();
        
        $successCount = 0;
        $imageCopyCount = 0;
        $counters = [];

        foreach ($logs as $index => $log) {
            $dateStr = date('Y-m-d H:i:s', strtotime($log['date']));
            $ym = date('Ym', strtotime($log['date']));

            // ค้นหาหรือจัดการเลขรัน Ticket Code รายเดือน
            if (!isset($counters[$ym])) {
                $prefix = 'IT-' . $ym . '-';
                $lastTicket = $db->table('Tb_It_Support_Logs')
                                 ->like('its_ticket_code', $prefix . '%')
                                 ->orderBy('its_id', 'DESC')
                                 ->get()
                                 ->getRowArray();
                if ($lastTicket) {
                    $parts = explode('-', $lastTicket['its_ticket_code']);
                    $counters[$ym] = (int) end($parts);
                } else {
                    $counters[$ym] = 0;
                }
            }
            $counters[$ym]++;
            $ticketCode = 'IT-' . $ym . '-' . str_pad($counters[$ym], 4, '0', STR_PAD_LEFT);

            // ค้นหา u_id ของคนลงบันทึกในตารางผู้ใช้เพื่อความถูกต้อง
            $recordedBy = trim($log['recordedBy'] ?? '');
            $userId = 1; // Default
            if (!empty($recordedBy)) {
                $firstName = explode(' ', $recordedBy)[0];
                $user = $db->table('Tb_Users')
                           ->like('u_fullname', $firstName)
                           ->get()
                           ->getRowArray();
                if ($user) {
                    $userId = $user['u_id'];
                }
            }

            // จัดการภาพประกอบหน้างาน (คัดลอกไว้เรียบร้อยแล้วบนโฮสต์ ตรวจเช็คว่ามีอยู่จริง)
            $migratedImages = [];
            if (isset($log['images']) && is_array($log['images'])) {
                foreach ($log['images'] as $img) {
                    $filename = basename($img);
                    $targetFile = $targetUploadsDir . $filename;

                    if (file_exists($targetFile)) {
                        $migratedImages[] = $filename;
                        $imageCopyCount++;
                    }
                }
            }

            // บันทึกลงตารางจริง
            $insertData = [
                'its_ticket_code' => $ticketCode,
                'its_date'        => $dateStr,
                'its_category'    => $log['category'] ?: '🛠️ IT Support & Service',
                'its_location'    => $log['location'] ?: 'ศูนย์เทคโนโลยีสารสนเทศ',
                'its_task'        => $log['task'],
                'its_recorded_by' => $log['recordedBy'] ?: 'ผู้ช่วยนักวิชาการคอมพิวเตอร์',
                'its_user_id'     => $userId,
                'its_images'      => !empty($migratedImages) ? json_encode($migratedImages) : null,
                'its_created_at'  => isset($log['timestamp']) ? date('Y-m-d H:i:s', strtotime($log['timestamp'])) : $dateStr,
                'its_updated_at'  => isset($log['updatedAt']) ? date('Y-m-d H:i:s', strtotime($log['updatedAt'])) : $dateStr
            ];

            if ($itsModel->insert($insertData)) {
                $successCount++;
            }

            // แสดงแถบความคืบหน้าบนหน้าจอ CLI
            if (($index + 1) % 50 === 0 || ($index + 1) === $totalLogs) {
                CLI::showProgress($index + 1, $totalLogs);
            }
        }

        CLI::write("\n🎉 ย้ายข้อมูลสำเร็จแล้ว!", 'green');
        CLI::write("✅ บันทึกใบงานลงระบบสำเร็จ: {$successCount} / {$totalLogs} รายการ", 'green');
        CLI::write("📸 คัดลอกและย้ายไฟล์รูปภาพหน้างานสำเร็จ: {$imageCopyCount} ไฟล์", 'green');
    }
}
