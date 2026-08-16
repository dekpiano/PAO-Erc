<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'ใบรายชื่อการแข่งขัน (Match Sheet)') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .page-sheet {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 auto !important;
                max-width: 100% !important;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 15mm 12mm 15mm;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8">

    <!-- Top Action Bar (hidden when printing) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <button onclick="window.close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>ปิดหน้านี้</span>
            </button>
            <span class="text-xs text-slate-500 font-medium hidden sm:inline">ตรวจสอบความถูกต้องก่อนสั่งพิมพ์</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-extrabold text-xs flex items-center gap-2 shadow-md shadow-indigo-200 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>พิมพ์เอกสารนี้ (Print / PDF)</span>
            </button>
        </div>
    </div>

    <!-- Match Sheet Page -->
    <div class="page-sheet max-w-4xl mx-auto bg-white p-8 sm:p-10 rounded-2xl border border-slate-200 shadow-md space-y-5 text-slate-800">
        
        <!-- Header -->
        <div class="text-center space-y-1 border-b-2 border-slate-800 pb-4">
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">ใบรายงานตัวและส่งรายชื่อนักกีฬาเข้าร่วมการแข่งขัน (Match Sheet)</h1>
            <h2 class="text-base sm:text-lg font-bold text-slate-700">การแข่งขันกีฬา อบจ. นครสวรรค์ เกมส์</h2>
            <p class="text-xs text-slate-500 font-medium">องค์การบริหารส่วนจังหวัดนครสวรรค์</p>
        </div>

        <!-- Team Information Header -->
        <div class="bg-slate-50 border border-slate-300 rounded-xl p-4 text-xs space-y-2">
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-12 sm:col-span-8 flex items-center gap-1.5">
                    <span class="font-bold text-slate-600">โรงเรียน / สังกัด:</span>
                    <span class="font-extrabold text-slate-900 text-sm"><?= esc($team['school_name']) ?></span>
                    <?php if (!empty($team['team_name']) && $team['team_name'] !== $team['school_name']): ?>
                        <span class="text-slate-500">(<?= esc($team['team_name']) ?>)</span>
                    <?php endif; ?>
                </div>
                <div class="col-span-12 sm:col-span-4 flex items-center gap-1.5 sm:justify-end">
                    <span class="font-bold text-slate-600">รหัสทีม:</span>
                    <span class="font-mono font-black text-slate-900 px-2 py-0.5 bg-white border border-slate-300 rounded"><?= esc($team['team_code']) ?></span>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-2 pt-1 border-t border-slate-200">
                <div class="col-span-12 sm:col-span-6 flex items-center gap-1.5">
                    <span class="font-bold text-slate-600">ชนิดกีฬา / รุ่น:</span>
                    <span class="font-bold text-indigo-900">
                        <?= esc($team['sport_name']) ?> - <?= esc($team['category_name']) ?> (<?= $team['category_gender'] === 'female' ? 'หญิง' : ($team['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>)
                    </span>
                </div>
                <div class="col-span-12 sm:col-span-6 flex items-center gap-1.5 sm:justify-end">
                    <span class="font-bold text-slate-600">ที่ตั้ง:</span>
                    <span class="font-medium text-slate-800"><?= esc($team['district'] ? 'อ.' . $team['district'] . ' ' : '') ?>จ.<?= esc($team['province'] ?: 'นครสวรรค์') ?></span>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-2 pt-1 border-t border-slate-200">
                <div class="col-span-12 sm:col-span-6 flex items-center gap-1.5">
                    <span class="font-bold text-slate-600">ผู้ประสานงาน / ผู้ควบคุมทีม:</span>
                    <span class="font-semibold text-slate-800"><?= esc($team['contact_name']) ?></span>
                </div>
                <div class="col-span-12 sm:col-span-6 flex items-center gap-1.5 sm:justify-end">
                    <span class="font-bold text-slate-600">เบอร์โทรศัพท์:</span>
                    <span class="font-medium text-slate-800"><?= esc($team['contact_phone']) ?></span>
                </div>
            </div>
        </div>

        <!-- 1. Athletes Table -->
        <div class="space-y-2 pt-1">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wide">
                    1. รายชื่อนักกีฬาประจำทีม (จำนวน <?= count($athletes) ?> คน)
                </h3>
            </div>

            <table class="w-full text-left text-xs border-collapse border border-slate-400">
                <thead class="bg-slate-100 text-slate-800 font-bold text-center">
                    <tr>
                        <th class="border border-slate-400 px-2 py-1.5 w-10">ลำดับ</th>
                        <th class="border border-slate-400 px-3 py-1.5 text-left">ชื่อ - นามสกุล นักกีฬา</th>
                        <th class="border border-slate-400 px-2 py-1.5 w-24 text-center">ระดับชั้น</th>
                        <th class="border border-slate-400 px-2 py-1.5 w-28 text-center">วันเกิด (พ.ศ.)</th>
                        <th class="border border-slate-400 px-2 py-1.5 w-14 text-center">อายุ</th>
                        <th class="border border-slate-400 px-2 py-1.5 w-16 text-center">เบอร์เสื้อ</th>
                        <th class="border border-slate-400 px-3 py-1.5 w-32 text-center">ลายมือชื่อ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    <?php if (empty($athletes)): ?>
                        <tr>
                            <td colspan="7" class="border border-slate-400 px-3 py-4 text-center text-slate-400">ไม่มีข้อมูลนักกีฬา</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($athletes as $m): ?>
                            <?php
                            $bDateStr = '-';
                            if (!empty($m['birth_date']) && $m['birth_date'] !== '0000-00-00') {
                                $bTime = strtotime($m['birth_date']);
                                if ($bTime) {
                                    $thaiYear = date('Y', $bTime) + 543;
                                    $bDateStr = date('d/m/', $bTime) . $thaiYear;
                                }
                            }
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="border border-slate-400 px-2 py-1 text-center font-bold text-slate-600"><?= $no++ ?></td>
                                <td class="border border-slate-400 px-3 py-1 font-bold text-slate-900">
                                    <?= esc($m['prefix']) ?><?= esc($m['first_name']) ?> <?= esc($m['last_name']) ?>
                                </td>
                                <td class="border border-slate-400 px-2 py-1 text-center font-medium">
                                    <?= !empty($m['jersey_number']) ? esc($m['jersey_number']) : '-' ?>
                                </td>
                                <td class="border border-slate-400 px-2 py-1 text-center font-mono">
                                    <?= $bDateStr ?>
                                </td>
                                <td class="border border-slate-400 px-2 py-1 text-center font-bold">
                                    <?= $m['age'] ? $m['age'] : '-' ?>
                                </td>
                                <td class="border border-slate-400 px-2 py-1 text-center">
                                    <!-- Empty box for match jersey number if handwritten -->
                                </td>
                                <td class="border border-slate-400 px-2 py-1 text-center">
                                    <!-- Signature field -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- 2. Coaches & Staff Table -->
        <div class="space-y-2 pt-2">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wide">
                    2. รายชื่อผู้ฝึกสอนและผู้ควบคุมทีม (จำนวน <?= count($coaches) ?> คน)
                </h3>
            </div>

            <table class="w-full text-left text-xs border-collapse border border-slate-400">
                <thead class="bg-slate-100 text-slate-800 font-bold text-center">
                    <tr>
                        <th class="border border-slate-400 px-2 py-1.5 w-10">ลำดับ</th>
                        <th class="border border-slate-400 px-3 py-1.5 w-40 text-left">ตำแหน่งในทีม</th>
                        <th class="border border-slate-400 px-3 py-1.5 text-left">ชื่อ - นามสกุล</th>
                        <th class="border border-slate-400 px-3 py-1.5 w-32 text-center">เบอร์โทรศัพท์</th>
                        <th class="border border-slate-400 px-3 py-1.5 w-32 text-center">ลายมือชื่อ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    <?php if (empty($coaches)): ?>
                        <tr>
                            <td colspan="5" class="border border-slate-400 px-3 py-4 text-center text-slate-400">ไม่มีข้อมูลผู้ฝึกสอน</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($coaches as $m): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="border border-slate-400 px-2 py-1 text-center font-bold text-slate-600"><?= $no++ ?></td>
                                <td class="border border-slate-400 px-3 py-1 font-bold text-slate-800">
                                    <?= !empty($m['position']) ? esc($m['position']) : 'ผู้ฝึกสอน / ผู้ควบคุมทีม' ?>
                                </td>
                                <td class="border border-slate-400 px-3 py-1 font-bold text-slate-900">
                                    <?= esc($m['prefix']) ?><?= esc($m['first_name']) ?> <?= esc($m['last_name']) ?>
                                </td>
                                <td class="border border-slate-400 px-3 py-1 text-center font-mono">
                                    <?= !empty($m['jersey_number']) ? esc($m['jersey_number']) : '-' ?>
                                </td>
                                <td class="border border-slate-400 px-2 py-1 text-center">
                                    <!-- Signature field -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Sign-off Block -->
        <div class="pt-6 grid grid-cols-2 gap-8 text-xs text-center border-t border-slate-300">
            <div class="space-y-8">
                <p class="font-bold text-slate-700">ขอรับรองว่าข้อมูลข้างต้นเป็นความจริงทุกประการ</p>
                <div class="space-y-1">
                    <p class="text-slate-400">ลงชื่อ ................................................................</p>
                    <p class="font-bold text-slate-800">( <?= esc($team['contact_name']) ?> )</p>
                    <p class="text-slate-500 text-[11px]">ผู้ควบคุมทีม / ผู้แทนสถานศึกษา</p>
                    <p class="text-slate-400 text-[11px]">วันที่ ........ / ........ / <?= date('Y') + 543 ?></p>
                </div>
            </div>

            <div class="space-y-8">
                <p class="font-bold text-slate-700">ตรวจสอบและรับรายงานตัวเรียบร้อยแล้ว</p>
                <div class="space-y-1">
                    <p class="text-slate-400">ลงชื่อ ................................................................</p>
                    <p class="font-bold text-slate-800">( ................................................................ )</p>
                    <p class="text-slate-500 text-[11px]">เจ้าหน้าที่ / กรรมการฝ่ายจัดการแข่งขัน</p>
                    <p class="text-slate-400 text-[11px]">วันที่ ........ / ........ / <?= date('Y') + 543 ?></p>
                </div>
            </div>
        </div>

    </div>

    <script>
        if (window.lucide) {
            lucide.createIcons();
        }
    </script>
</body>
</html>
