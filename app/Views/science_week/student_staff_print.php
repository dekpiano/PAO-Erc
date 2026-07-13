<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <!-- Google Fonts: Sarabun -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* ตั้งค่าแบบอักษรให้เหมือนหนังสือราชการ (TH Sarabun New / PSK) */
        @font-face {
            font-family: 'THSarabunNew';
            src: url('https://cdn.jsdelivr.net/gh/lazywasabi/thai-web-fonts@7/fonts/Sarabun/Sarabun-Regular.woff2') format('woff2');
        }

        body {
            font-family: 'THSarabunNew', 'Sarabun', 'TH Sarabun New', 'TH Sarabun PSK', sans-serif;
            background: #e2e8f0; /* พื้นหลังสีเทา */
            margin: 0;
            padding: 20px;
            line-height: 1.2;
        }

        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm 15mm;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            box-sizing: border-box;
            color: #000;
            font-size: 16pt;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* จัดรูปแบบหัวกระดาษ */
        .doc-title {
            font-size: 20pt; 
            font-weight: bold;
            margin-bottom: 0;
        }
        .doc-subtitle {
            font-size: 18pt;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        
        /* ตาราง (ระเบียบราชการ) */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
            font-size: 16pt;
        }
        th {
            text-align: center;
            font-weight: bold;
            background-color: transparent !important; 
        }
        
        .col-no { width: 8%; text-align: center; }
        .col-name { width: 32%; text-align: left; }
        .col-class { width: 20%; text-align: center; }
        .col-role { width: 20%; text-align: center; }
        .col-sign { width: 20%; text-align: center; }

        /* ไม่แสดงองค์ประกอบเหล่านี้ตอนพิมพ์ และล้างรูปแบบ A4 */
        @media print {
            body { 
                background: #fff; 
                padding: 0; 
                margin: 0;
            }
            .a4-page {
                width: auto;
                min-height: auto;
                padding: 0;
                margin: 0;
                box-shadow: none;
            }
            @page { margin: 1.5cm; size: A4 portrait; }
            .no-print { display: none !important; }
        }
        
        /* ปุ่มคำสั่งสำหรับหน้าจอ (ไม่พิมพ์) */
        .btn-print {
            display: inline-block;
            padding: 8px 20px;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 20px;
            cursor: pointer;
            border: none;
            font-family: 'Sarabun', sans-serif;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .btn-close {
            background-color: #64748b;
            margin-left: 10px;
        }
        .btn-print:hover { background-color: #1d4ed8; }
        .btn-close:hover { background-color: #475569; }
    </style>
</head>
<body>

    <div class="no-print text-center mb-6">
        <button class="btn-print" onclick="window.print()">🖨️ พิมพ์เอกสาร</button>
        <button class="btn-print btn-close" onclick="window.close()">ปิดหน้าต่าง</button>
    </div>

    <!-- จำลองกระดาษ A4 -->
    <div class="a4-page">
        <!-- หัวเอกสารแบบเป็นทางการ -->
        <div class="text-center" style="position: relative; margin-bottom: 20px;">
        <!-- ข้อความมุมบนขวา (ใส่หรือไม่ใส่ก็ได้) -->
        <div class="text-right" style="position: absolute; right: 0; top: 0; font-size: 14pt;">
            <!-- สามารถใส่เอกสารแนบที่ ๑ ได้ตรงนี้ถ้าต้องการ -->
        </div>

        <div class="doc-title">ใบลงลายมือชื่อนักเรียนและบุคคลทั่วไปผู้ช่วยปฏิบัติงาน</div>
        <div class="doc-subtitle">งานสัปดาห์วิทยาศาสตร์ ประจำปี <?= esc($selected_year) ?></div>
        
        <?php if(!empty($compType_active)): ?>
            <div style="margin-top: 10px;">
                <strong>รายการ / ฝ่ายงาน:</strong> <?= esc($compType_active) ?>
            </div>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">ลำดับที่</th>
                <th class="col-name">ชื่อ - นามสกุล</th>
                <th class="col-class">ระดับชั้น / สถานะ</th>
                <th class="col-role">ฝ่ายงาน / รายการ</th>
                <th class="col-sign">ลายมือชื่อ</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($student_staff)): ?>
                <tr>
                    <td colspan="5" class="text-center" style="padding: 30px;">- ไม่มีข้อมูลผู้ช่วยงาน -</td>
                </tr>
            <?php else: ?>
                <?php $i = 1; foreach($student_staff as $st): ?>
                    <tr>
                        <td class="col-no"><?= $i++ ?></td>
                        <td class="col-name" style="padding-left: 15px;"><?= esc($st['staff_prefix'].$st['staff_firstname'].' '.$st['staff_lastname']) ?></td>
                        <td class="col-class"><?= esc($st['staff_class']) ?></td>
                        <td class="col-role"><?= esc($st['staff_competition_type']) ?></td>
                        <td class="col-sign"></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>

    <script>
        // เริ่มสั่งพิมพ์อัตโนมัติเมื่อเปิดหน้าต่างขึ้นมา
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
