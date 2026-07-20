<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบส่งมอบงาน IT Support - <?= $log['its_ticket_code'] ?></title>
    <style>
        body {
            font-family: 'Sarabun', 'Helvetica Neue', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 30px;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        .subtitle {
            font-size: 13px;
            color: #666;
            margin: 0;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .meta-table th, .meta-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .meta-table th {
            background-color: #f5f5f5;
            width: 25%;
            font-weight: bold;
        }
        .content-box {
            border: 1px solid #ddd;
            padding: 20px;
            min-height: 180px;
            margin-bottom: 30px;
            border-radius: 4px;
            white-space: pre-wrap;
        }
        .content-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-size: 15px;
        }
        .images-section {
            margin-bottom: 40px;
        }
        .images-title {
            font-weight: bold;
            margin-bottom: 15px;
        }
        .image-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .image-card {
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            aspect-ratio: 4/3;
        }
        .image-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .signature-section {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }
        .signature-section td {
            width: 50%;
            text-align: center;
            padding: 20px;
        }
        .signature-line {
            width: 70%;
            border-bottom: 1px dashed #333;
            margin: 40px auto 10px auto;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button (Hidden in actual printout) -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #2563eb; border: none; color: white; font-weight: bold; cursor: pointer; border-radius: 4px;">🖨️ สั่งพิมพ์ใบงาน</button>
    </div>

    <!-- Header Section -->
    <div class="header">
        <h1 class="title">ใบรายงานการส่งมอบงานบริการคอมพิวเตอร์และเทคโนโลยี</h1>
        <p class="subtitle">ฝ่ายเทคโนโลยีสารสนเทศ องค์การบริหารส่วนจังหวัดนครสวรรค์</p>
    </div>

    <!-- Meta Details Grid -->
    <table class="meta-table">
        <tr>
            <th>รหัสใบงานบริการ</th>
            <td style="font-family: monospace; font-weight: bold; font-size: 15px; color: #2563eb;"><?= $log['its_ticket_code'] ?></td>
            <th>วันที่ปฏิบัติงาน</th>
            <td><?= date('d/m/Y H:i', strtotime($log['its_date'])) ?> น.</td>
        </tr>
        <tr>
            <th>หมวดหมู่ประเภทงาน</th>
            <td><?= $log['its_category'] ?></td>
            <th>สถานที่ปฏิบัติงาน</th>
            <td>📍 <?= esc($log['its_location']) ?></td>
        </tr>
        <tr>
            <th>ผู้ให้บริการ (เจ้าหน้าที่)</th>
            <td colspan="3">👤 <?= esc($log['its_recorded_by']) ?></td>
        </tr>
    </table>

    <!-- Task Content Box -->
    <div class="content-box">
        <div class="content-title">🛠️ รายละเอียดการปฏิบัติงานซ่อมบำรุง / บริการเทคนิค</div>
        <div><?= esc($log['its_task']) ?></div>
    </div>

    <!-- Images Section -->
    <?php 
        $images = !empty($log['its_images']) ? json_decode($log['its_images'], true) : [];
    ?>
    <?php if(!empty($images)): ?>
        <div class="images-section">
            <div class="images-title">📷 ภาพถ่ายประกอบผลงานการเข้าบริการ</div>
            <div class="image-grid">
                <?php foreach($images as $img): ?>
                    <div class="image-card">
                        <img loading="lazy" src="<?= base_url('uploads/it_support/' . $img) ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Signature Columns -->
    <table class="signature-section">
        <tr>
            <td>
                <p>ลงชื่อผู้ให้บริการ (เจ้าหน้าที่ IT)</p>
                <div class="signature-line"></div>
                <p>( <?= esc($log['its_recorded_by']) ?> )</p>
                <p>ตำแหน่ง: <?= esc($position) ?></p>
            </td>
            <td>
                <p>ลงชื่อผู้รับบริการ (หัวหน้างาน/พนักงาน)</p>
                <div class="signature-line"></div>
                <p>( ............................................................ )</p>
                <p>ตำแหน่ง: ............................................................</p>
            </td>
        </tr>
    </table>

    <script>
        // Trigger print popup on page load
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
