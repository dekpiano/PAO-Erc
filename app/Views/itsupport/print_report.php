<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานผลการปฏิบัติงาน IT Support รายบุคคล</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700;800&display=swap');
        
        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 13px;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        
        .no-print {
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8fafc;
            padding: 12px 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        
        /* Page Break Structure */
        .task-page {
            page-break-after: always;
            box-sizing: border-box;
            border: 1px dashed #cbd5e1;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 16px;
            background-color: #fff;
            position: relative;
            min-height: 290mm; /* Close to A4 portrait height */
            display: flex;
            flex-direction: column;
        }
        
        .task-page:last-child {
            page-break-after: avoid;
            margin-bottom: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
        }
        
        .title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px 0;
        }
        
        .subtitle {
            font-size: 12px;
            color: #475569;
            margin: 0;
            font-weight: 500;
        }
        
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .meta-table th, .meta-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            text-align: left;
            font-size: 12px;
        }
        
        .meta-table th {
            background-color: #f8fafc;
            width: 20%;
            font-weight: 700;
            color: #334155;
        }
        
        .meta-table td {
            color: #0f172a;
        }
        
        .content-box {
            border: 1px solid #cbd5e1;
            padding: 15px;
            min-height: 120px;
            margin-bottom: 20px;
            border-radius: 8px;
            white-space: pre-wrap;
            background-color: #fcfcfc;
            font-size: 13px;
            color: #334155;
        }
        
        .content-title {
            font-weight: 700;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin-bottom: 10px;
            font-size: 13px;
            color: #0f172a;
        }
        
        .images-section {
            margin-bottom: 20px;
            flex-grow: 1; /* Pushes signature to the bottom */
        }
        
        .images-title {
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 12px;
            color: #334155;
        }
        
        .image-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        
        /* Auto scale columns based on image count */
        .image-grid.count-1 {
            grid-template-columns: minmax(200px, 450px);
            justify-content: center;
        }
        .image-grid.count-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .image-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            aspect-ratio: 4/3;
            background-color: #f8fafc;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .image-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .signature-section {
            width: 100%;
            border-collapse: collapse;
            margin-top: auto; /* Pin to bottom of task page */
            padding-top: 15px;
            border-top: 1px solid #cbd5e1;
        }
        
        .signature-section td {
            width: 50%;
            text-align: center;
            padding: 10px 15px 0 15px;
            font-size: 12px;
        }
        
        .signature-line {
            width: 60%;
            border-bottom: 1px dashed #475569;
            margin: 25px auto 8px auto;
        }
        
        @media print {
            body {
                padding: 0;
                background-color: transparent;
            }
            .no-print {
                display: none;
            }
            .task-page {
                border: none;
                padding: 0;
                margin-bottom: 0;
                border-radius: 0;
                min-height: auto;
            }
            .meta-table th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Print Header Controls (Hidden in actual printout) -->
    <div class="no-print">
        <span style="font-size: 12px; font-weight: bold; color: #475569;">📄 ตรวจสอบรายงานแบบแยกงานละหน้าพร้อมรูปประกอบ</span>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.close()" style="padding: 8px 16px; background-color: #fff; border: 1px solid #cbd5e1; color: #475569; font-weight: bold; cursor: pointer; border-radius: 8px; font-size: 12px;">ปิดหน้าต่าง</button>
            <button onclick="window.print()" style="padding: 8px 18px; background-color: #2563eb; border: none; color: white; font-weight: bold; cursor: pointer; border-radius: 8px; font-size: 12px; box-shadow: 0 4px 6px -1px rgba(37,99,235,0.2);">🖨️ สั่งพิมพ์รายงานแยกหน้า</button>
        </div>
    </div>

    <!-- Loop through each job card -->
    <?php if(empty($results)): ?>
        <div style="text-align: center; padding: 50px; border: 1px solid #cbd5e1; border-radius: 12px; color: #64748b;">
            <h2>ไม่พบข้อมูลบันทึกตามตัวกรองที่เลือก</h2>
        </div>
    <?php else: ?>
        <?php foreach($results as $idx => $row): ?>
            <?php 
                $images = !empty($row['its_images']) ? json_decode($row['its_images'], true) : [];
                $imgCount = count($images);
            ?>
            <div class="task-page">
                <!-- Header Section -->
                <div class="header">
                    <h1 class="title">ใบรายงานผลการดำเนินงานบริการ IT Support</h1>
                    <p class="subtitle">ฝ่ายเทคโนโลยีสารสนเทศ องค์การบริหารส่วนจังหวัดนครสวรรค์</p>
                </div>

                <!-- Meta Details Table -->
                <table class="meta-table">
                    <tr>
                        <th>รหัสใบงานบริการ</th>
                        <td style="font-family: monospace; font-weight: 700; color: #2563eb;"><?= $row['its_ticket_code'] ?></td>
                        <th>วันที่ดำเนินงาน</th>
                        <td><?= date('d/m/Y H:i', strtotime($row['its_date'])) ?> น.</td>
                    </tr>
                    <tr>
                        <th>หมวดหมู่ประเภทงาน</th>
                        <td><?= $row['its_category'] ?></td>
                        <th>สถานที่ปฏิบัติงาน</th>
                        <td>📍 <?= esc($row['its_location']) ?></td>
                    </tr>
                    <tr>
                        <th>ผู้รายงานปฏิบัติหน้าที่</th>
                        <td colspan="3">👤 <?= esc($row['its_recorded_by']) ?></td>
                    </tr>
                </table>

                <!-- Task Content Details Box -->
                <div class="content-box">
                    <div class="content-title">🛠️ รายละเอียดผลการดำเนินงานและแนวทางการแก้ไข</div>
                    <div><?= esc($row['its_task']) ?></div>
                </div>

                <!-- Images Section -->
                <div class="images-section">
                    <?php if(!empty($images)): ?>
                        <div class="images-title">📷 ภาพถ่ายหน้างานประกอบผลงานการเข้าบริการ (<?= $imgCount ?> รูป)</div>
                        <div class="image-grid count-<?= min($imgCount, 3) ?>">
                            <?php foreach($images as $img): ?>
                                <div class="image-card">
                                    <img loading="lazy" src="<?= base_url('uploads/it_support/' . $img) ?>" alt="ภาพถ่ายหน้างาน">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="font-size: 11px; color: #94a3b8; border: 1px dashed #cbd5e1; border-radius: 8px; text-align: center; padding: 20px;">
                            ไม่มีภาพถ่ายหน้างานประกอบรายงาน
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Signature Section at bottom of page -->
                <table class="signature-section">
                    <tr>
                        <td>
                            <p>ลงชื่อผู้รายงาน (เจ้าหน้าที่ IT)</p>
                            <div class="signature-line"></div>
                            <p>( <?= esc($row['its_recorded_by']) ?> )</p>
                            <p>ตำแหน่ง: <?= esc($position) ?></p>
                        </td>
                        <td>
                            <p>ลงชื่อผู้รับรองรายงาน (หัวหน้างาน)</p>
                            <div class="signature-line"></div>
                            <p>( ............................................................ )</p>
                            <p>ตำแหน่ง: ............................................................</p>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
        // Auto print dialog
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
