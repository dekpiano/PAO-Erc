<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'เล่มรายงานสรุปผลการจัดงานสัปดาห์วิทยาศาสตร์' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo-pao.png') ?>">
    
    <!-- Google Fonts: Sarabun & Prompt -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700;800&family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }

        h1, h2, h3, h4, h5, h6, .heading-font {
            font-family: 'Prompt', sans-serif;
        }

        /* A4 Page Styling */
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm 20mm 20mm 20mm;
            margin: 15mm auto;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 4px;
            position: relative;
            box-sizing: border-box;
        }

        @media print {
            body {
                background: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .a4-page {
                width: 100% !important;
                min-height: 100vh !important;
                margin: 0 !important;
                padding: 15mm 15mm 15mm 15mm !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                page-break-after: always !important;
                break-after: page !important;
            }

            .a4-page:last-child {
                page-break-after: avoid !important;
                break-after: avoid !important;
            }

            .page-break {
                page-break-before: always !important;
                break-before: page !important;
            }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Top Action Bar (Fixed on Web View) -->
    <div class="no-print fixed top-0 inset-x-0 bg-slate-900/90 backdrop-blur-md border-b border-slate-800 text-white z-50 px-6 py-3 shadow-xl">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="<?= base_url('science-week/staff/report') ?>" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors flex items-center gap-1.5 text-xs font-bold">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับหน้าแดชบอร์ด
                </a>
                <span class="text-xs text-slate-400 border-l border-slate-700 pl-3 hidden sm:inline">
                    เอกสารเล่มรายงานสรุปผลงาน ประจำปีการศึกษา <?= esc($selected_year) ?>
                </span>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs sm:text-sm flex items-center gap-2 shadow-lg shadow-indigo-900/50 transition-all hover:scale-105 cursor-pointer">
                    <i data-lucide="printer" class="w-4 h-4"></i> สั่งพิมพ์ / บันทึกเป็น PDF (Print)
                </button>
            </div>
        </div>
    </div>

    <div class="pt-16 no-print"></div>

    <!-- ==================== PAGE 1: COVER PAGE (หน้าปกเล่มรายงาน) ==================== -->
    <div class="a4-page flex flex-col justify-between items-center text-center relative overflow-hidden border-8 border-indigo-900/10">
        <!-- Background Decor -->
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-indigo-50 rounded-full blur-3xl -z-10"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-purple-50 rounded-full blur-3xl -z-10"></div>

        <!-- Cover Header -->
        <div class="w-full pt-10">
            <div class="w-28 h-28 mx-auto mb-6 flex items-center justify-center p-3 bg-white rounded-3xl shadow-md border border-slate-100">
                <img src="<?= base_url('assets/images/logo-pao.png') ?>" alt="อบจ.นครสวรรค์" class="max-h-full max-w-full object-contain">
            </div>
            <h3 class="text-base sm:text-lg font-bold text-indigo-900 tracking-wider uppercase">องค์การบริหารส่วนจังหวัดนครสวรรค์</h3>
            <p class="text-sm text-slate-500 mt-1">Nakhon Sawan Provincial Administrative Organization</p>
        </div>

        <!-- Cover Title -->
        <div class="w-full py-12 px-6 bg-gradient-to-b from-indigo-50/50 via-white to-indigo-50/30 rounded-3xl border border-indigo-100">
            <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-600 text-white text-xs font-bold tracking-widest uppercase mb-4 shadow">
                เล่มรายงานสรุปผลการดำเนินงาน
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug">
                โครงการจัดงานสัปดาห์วิทยาศาสตร์<br>
                <span class="text-indigo-600">ประจำปีการศึกษา <?= esc($selected_year) ?></span>
            </h1>
            <div class="w-24 h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500 mx-auto mt-6 rounded-full"></div>
            <p class="text-sm text-slate-600 mt-6 max-w-md mx-auto leading-relaxed">
                รายงานผลการจัดการแข่งขัน, สถิติผู้เข้าร่วมกิจกรรม<br>
                และการประเมินความพึงพอใจในการจัดกิจกรรม
            </p>
        </div>

        <!-- Cover Footer -->
        <div class="w-full pb-10 text-xs sm:text-sm text-slate-600">
            <p class="font-bold text-slate-800 text-sm">กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์</p>
        </div>
    </div>

    <!-- ==================== PAGE 2: EXECUTIVE SUMMARY & STATS ==================== -->
    <div class="a4-page flex flex-col justify-between">
        <div>
            <!-- Header on each inner page -->
            <div class="flex justify-between items-center pb-4 mb-6 border-b border-slate-200 text-xs text-slate-400">
                <span class="font-bold text-indigo-900">รายงานสรุปผลโครงการสัปดาห์วิทยาศาสตร์ ประจำปีการศึกษา <?= esc($selected_year) ?></span>
                <span>ส่วนที่ 1: บทสรุปภาพรวมสถิติ</span>
            </div>

            <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">1</span>
                บทสรุปภาพรวมการดำเนินงาน (Executive Summary)
            </h2>

            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed indent-8 mb-6 text-justify">
                การจัดโครงการสัปดาห์วิทยาศาสตร์ ประจำปีการศึกษา <?= esc($selected_year) ?> โดยองค์การบริหารส่วนจังหวัดนครสวรรค์ 
                มีวัตถุประสงค์เพื่อส่งเสริมทักษะทางวิทยาศาสตร์ เทคโนโลยี และนวัตกรรมให้แก่นักเรียน นักศึกษา และประชาชนทั่วไป 
                ผลการดำเนินงานและการจัดเก็บข้อมูลสถิติ มีรายละเอียดสรุปสำคัญดังนี้:
            </p>

            <!-- 6 Summary Stat Boxes: ผู้มีส่วนร่วม, เกียรติบัตร, ผู้ประเมิน, นักเรียนแข่ง, ครูผู้ฝึกสอน, นักเรียนช่วยงาน -->
            <div class="grid grid-cols-3 gap-2.5 mb-4">
                <div class="p-2.5 rounded-xl bg-indigo-50 border border-indigo-200 text-center">
                    <p class="text-[10px] font-bold text-indigo-950 uppercase">ยอดรวมคนทั้งหมด</p>
                    <p class="text-xl font-black text-indigo-700 font-mono mt-0.5"><?= number_format($summary_overview['grand_total_people']) ?> <span class="text-[10px] font-normal text-slate-600">คน</span></p>
                    <p class="text-[9px] text-slate-500">รวมทุกภาคส่วน</p>
                </div>
                <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-center">
                    <p class="text-[10px] font-bold text-emerald-950 uppercase">เกียรติบัตรรวมทุกประเภท</p>
                    <p class="text-xl font-black text-emerald-700 font-mono mt-0.5"><?= number_format($summary_overview['total_certificates_all']) ?> <span class="text-[10px] font-normal text-slate-600">ใบ</span></p>
                    <p class="text-[9px] text-slate-500">ประเมิน+แข่ง+โค้ช+Staff</p>
                </div>
                <div class="p-2.5 rounded-xl bg-purple-50 border border-purple-200 text-center">
                    <p class="text-[10px] font-bold text-purple-950 uppercase">ผู้ตอบแบบประเมิน</p>
                    <p class="text-xl font-black text-purple-700 font-mono mt-0.5"><?= number_format($summary_overview['total_evaluations']) ?> <span class="text-[10px] font-normal text-slate-600">ชุด</span></p>
                    <p class="text-[9px] text-purple-700">เคลม <?= number_format($summary_overview['total_eval_claimed']) ?> ราย</p>
                </div>
                <div class="p-2.5 rounded-xl bg-cyan-50 border border-cyan-200 text-center">
                    <p class="text-[10px] font-bold text-cyan-950 uppercase">นักเรียนเข้าแข่งขัน</p>
                    <p class="text-xl font-black text-cyan-700 font-mono mt-0.5"><?= number_format($summary_overview['total_competitors']) ?> <span class="text-[10px] font-normal text-slate-600">คน</span></p>
                    <p class="text-[9px] text-slate-500"><?= number_format($summary_overview['total_teams']) ?> ทีม (<?= $summary_overview['total_competitions'] ?> รายการ)</p>
                </div>
                <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-center">
                    <p class="text-[10px] font-bold text-amber-950 uppercase">ครูผู้ฝึกสอน / โค้ช</p>
                    <p class="text-xl font-black text-amber-700 font-mono mt-0.5"><?= number_format($summary_overview['total_coaches']) ?> <span class="text-[10px] font-normal text-slate-600">ท่าน</span></p>
                    <p class="text-[9px] text-slate-500">อาจารย์ผู้ควบคุมทีม</p>
                </div>
                <div class="p-2.5 rounded-xl bg-pink-50 border border-pink-200 text-center">
                    <p class="text-[10px] font-bold text-pink-950 uppercase">นักเรียนช่วยงาน (Staff)</p>
                    <p class="text-xl font-black text-pink-700 font-mono mt-0.5"><?= number_format($summary_overview['total_student_staff']) ?> <span class="text-[10px] font-normal text-slate-600">คน</span></p>
                    <p class="text-[9px] text-slate-500">ฝ่ายปฏิบัติการจัดงาน</p>
                </div>
            </div>

            <!-- Charts Section on Book Page 2 -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- Bar Chart: Question Ratings -->
                <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/50">
                    <h3 class="text-xs font-bold text-slate-800 mb-2 flex items-center gap-1.5">
                        <i data-lucide="bar-chart-3" class="w-3.5 h-3.5 text-indigo-600"></i> แผนภูมิแท่ง: คะแนนความพึงพอใจรายข้อ
                    </h3>
                    <div class="h-44 w-full">
                        <canvas id="bookBarChart"></canvas>
                    </div>
                </div>

                <!-- Donut Chart: Rating Level Distribution -->
                <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/50">
                    <h3 class="text-xs font-bold text-slate-800 mb-2 flex items-center gap-1.5">
                        <i data-lucide="pie-chart" class="w-3.5 h-3.5 text-purple-600"></i> แผนภูมิวงกลม: สัดส่วนระดับความพึงพอใจ
                    </h3>
                    <div class="h-44 w-full flex items-center justify-center">
                        <canvas id="bookDonutChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Demographics Highlights Table -->
            <h3 class="text-xs font-bold text-slate-800 mb-1.5">ตารางสรุปข้อมูลประชากรผู้ร่วมประเมิน (Demographics)</h3>
            <table class="w-full text-left border-collapse text-[11px] mb-2 border border-slate-200">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300 text-slate-700 font-bold">
                        <th class="py-1.5 px-3">ข้อมูลประชากร</th>
                        <th class="py-1.5 px-3 text-center">จำแนกกลุ่ม</th>
                        <th class="py-1.5 px-3 text-center w-20">จำนวน (คน)</th>
                        <th class="py-1.5 px-3 text-center w-20">ร้อยละ (%)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <!-- Gender -->
                    <?php 
                        $totalGen = array_sum($evaluations['gender_counts']) ?: 1;
                        $gIdx = 0;
                        foreach($evaluations['gender_counts'] as $gName => $gCnt):
                            $gPct = round(($gCnt / $totalGen) * 100, 2);
                    ?>
                        <tr>
                            <?php if ($gIdx == 0): ?>
                                <td rowspan="<?= count($evaluations['gender_counts']) ?>" class="py-1 px-3 font-bold text-slate-700 align-top bg-slate-50/50 border-r border-slate-200">เพศ</td>
                            <?php endif; ?>
                            <td class="py-1 px-3"><?= esc($gName) ?></td>
                            <td class="py-1 px-3 text-center font-mono"><?= number_format($gCnt) ?></td>
                            <td class="py-1 px-3 text-center font-mono"><?= number_format($gPct, 2) ?>%</td>
                        </tr>
                    <?php $gIdx++; endforeach; ?>

                    <!-- Top Occupations -->
                    <?php 
                        $totalOcc = array_sum($evaluations['occupation_counts']) ?: 1;
                        $topOccList = array_slice($evaluations['occupation_counts'], 0, 3, true);
                        $oIdx = 0;
                        foreach($topOccList as $oName => $oCnt):
                            $oPct = round(($oCnt / $totalOcc) * 100, 2);
                    ?>
                        <tr>
                            <?php if ($oIdx == 0): ?>
                                <td rowspan="<?= count($topOccList) ?>" class="py-1 px-3 font-bold text-slate-700 align-top bg-slate-50/50 border-r border-slate-200">อาชีพ</td>
                            <?php endif; ?>
                            <td class="py-1 px-3 truncate max-w-[150px]"><?= esc($oName) ?></td>
                            <td class="py-1 px-3 text-center font-mono"><?= number_format($oCnt) ?></td>
                            <td class="py-1 px-3 text-center font-mono"><?= number_format($oPct, 2) ?>%</td>
                        </tr>
                    <?php $oIdx++; endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer Page Number -->
        <div class="pt-4 border-t border-slate-200 flex justify-between text-[11px] text-slate-400">
            <span>กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์</span>
            <span>หน้า 1</span>
        </div>
    </div>

    <!-- ==================== PAGE 3: EVALUATION STATISTICAL TABLE ==================== -->
    <div class="a4-page flex flex-col justify-between page-break">
        <div>
            <!-- Header -->
            <div class="flex justify-between items-center pb-4 mb-6 border-b border-slate-200 text-xs text-slate-400">
                <span class="font-bold text-indigo-900">รายงานสรุปผลโครงการสัปดาห์วิทยาศาสตร์ ประจำปีการศึกษา <?= esc($selected_year) ?></span>
                <span>ส่วนที่ 2: สถิติผลการประเมิน</span>
            </div>

            <h2 class="text-xl font-bold text-slate-900 mb-2 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">2</span>
                ผลการวิเคราะห์ข้อมูลแบบประเมินความพึงพอใจ
            </h2>
            <p class="text-xs text-slate-500 mb-4">
                เกณฑ์การประเมิน: 4.50-5.00 (มากที่สุด), 3.50-4.49 (มาก), 2.50-3.49 (ปานกลาง), 1.50-2.49 (น้อย), 1.00-1.49 (น้อยที่สุด)
            </p>

            <!-- Detailed Table -->
            <table class="w-full text-left border-collapse text-xs border border-slate-300 mb-6">
                <thead>
                    <tr class="bg-indigo-900 text-white font-bold text-[11px]">
                        <th class="py-2.5 px-2 text-center w-10 border border-indigo-950">ข้อที่</th>
                        <th class="py-2.5 px-3 border border-indigo-950">ประเด็นการประเมินความพึงพอใจ</th>
                        <th class="py-2.5 px-2 text-center w-16 border border-indigo-950">ผู้ตอบ (N)</th>
                        <th class="py-2.5 px-2 text-center w-16 border border-indigo-950">$\bar{X}$</th>
                        <th class="py-2.5 px-2 text-center w-16 border border-indigo-950">S.D.</th>
                        <th class="py-2.5 px-2 text-center w-16 border border-indigo-950">ร้อยละ (%)</th>
                        <th class="py-2.5 px-3 text-center w-24 border border-indigo-950">ระดับคุณภาพ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    <?php 
                        $qNum = 1;
                        foreach($evaluations['question_stats'] as $qs):
                    ?>
                        <tr class="<?= $qNum % 2 == 0 ? 'bg-slate-50' : 'bg-white' ?>">
                            <td class="py-2 px-2 text-center font-mono text-slate-600 font-bold border-r border-slate-300"><?= $qNum++ ?></td>
                            <td class="py-2 px-3 text-slate-800 border-r border-slate-300 leading-tight"><?= esc($qs['label']) ?></td>
                            <td class="py-2 px-2 text-center font-mono text-slate-600 border-r border-slate-300"><?= number_format($qs['count']) ?></td>
                            <td class="py-2 px-2 text-center font-mono font-bold text-indigo-950 border-r border-slate-300"><?= number_format($qs['mean'], 2) ?></td>
                            <td class="py-2 px-2 text-center font-mono text-slate-600 border-r border-slate-300"><?= number_format($qs['sd'], 2) ?></td>
                            <td class="py-2 px-2 text-center font-mono font-bold text-emerald-700 border-r border-slate-300"><?= number_format($qs['percentage'], 2) ?>%</td>
                            <td class="py-2 px-3 text-center font-semibold text-slate-800"><?= $qs['quality'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <!-- Grand Total Summary Row -->
                    <tr class="bg-indigo-50 border-t-2 border-indigo-900 font-bold text-indigo-950">
                        <td colspan="2" class="py-3 px-4 text-right border-r border-indigo-200">
                            เฉลี่ยรวมทุกประเด็น
                        </td>
                        <td class="py-3 px-2 text-center font-mono border-r border-indigo-200"><?= number_format($evaluations['total_count']) ?></td>
                        <td class="py-3 px-2 text-center font-mono text-sm text-indigo-900 border-r border-indigo-200"><?= number_format($evaluations['grand_mean'], 2) ?></td>
                        <td class="py-3 px-2 text-center font-mono border-r border-indigo-200"><?= number_format($evaluations['grand_sd'], 2) ?></td>
                        <td class="py-3 px-2 text-center font-mono text-sm text-emerald-800 border-r border-indigo-200"><?= number_format($evaluations['grand_percentage'], 2) ?>%</td>
                        <td class="py-3 px-3 text-center text-sm font-bold text-indigo-900"><?= $evaluations['grand_quality']['text'] ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Interpretation Narrative -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-2 text-justify leading-relaxed">
                <p>
                    <strong>การแปลผลข้อมูล:</strong> จากตารางสรุปผลการประเมินความพึงพอใจของผู้เข้าร่วมโครงการสัปดาห์วิทยาศาสตร์ 
                    ประจำปีการศึกษา <?= esc($selected_year) ?> จำนวนทั้งสิ้น <strong><?= number_format($evaluations['total_count']) ?></strong> ชุด 
                    พบว่าผู้เข้าร่วมกิจกรรมมีความพึงพอใจในภาพรวมอยู่ในระดับ <strong>"<?= $evaluations['grand_quality']['text'] ?>"</strong> 
                    โดยมีค่าเฉลี่ยรวมเท่ากับ <strong><?= number_format($evaluations['grand_mean'], 2) ?></strong> (S.D. = <?= number_format($evaluations['grand_sd'], 2) ?>) 
                    คิดเป็นร้อยละ <strong><?= number_format($evaluations['grand_percentage'], 2) ?>%</strong>
                </p>
            </div>
        </div>

        <!-- Footer Page Number -->
        <div class="pt-4 border-t border-slate-200 flex justify-between text-[11px] text-slate-400">
            <span>กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์</span>
            <span>หน้า 2</span>
        </div>
    </div>

    <!-- ==================== PAGE 4: COMPETITIONS & FEEDBACK ==================== -->
    <div class="a4-page flex flex-col justify-between page-break">
        <div>
            <!-- Header -->
            <div class="flex justify-between items-center pb-4 mb-6 border-b border-slate-200 text-xs text-slate-400">
                <span class="font-bold text-indigo-900">รายงานสรุปผลโครงการสัปดาห์วิทยาศาสตร์ ประจำปีการศึกษา <?= esc($selected_year) ?></span>
                <span>ส่วนที่ 3: สถิติการแข่งขัน & ข้อเสนอแนะ</span>
            </div>

            <h2 class="text-xl font-bold text-slate-900 mb-2 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">3</span>
                สถิติการแข่งขันและข้อเสนอแนะเพื่อการพัฒนา
            </h2>

            <!-- Competitions & Demographics Charts on Section 3 -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- Donut Chart: Level Breakdown -->
                <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/50">
                    <h4 class="text-xs font-bold text-slate-800 mb-2 flex items-center gap-1.5">
                        <i data-lucide="pie-chart" class="w-3.5 h-3.5 text-indigo-600"></i> แผนภูมิวงกลม: สัดส่วนผู้สมัครตามระดับชั้น
                    </h4>
                    <div class="h-40 w-full flex items-center justify-center">
                        <canvas id="bookLevelChart"></canvas>
                    </div>
                </div>

                <!-- Bar Chart: Top Provinces -->
                <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/50">
                    <h4 class="text-xs font-bold text-slate-800 mb-2 flex items-center gap-1.5">
                        <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-emerald-600"></i> แผนภูมิแท่ง: 5 อันดับจังหวัดที่เข้าร่วมมากที่สุด
                    </h4>
                    <div class="h-40 w-full">
                        <canvas id="bookProvinceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Competitions Table -->
            <h3 class="text-xs font-bold text-slate-800 mb-1.5">3.1 สถิติการสมัครเข้าร่วมการแข่งขัน</h3>
            <table class="w-full text-left border-collapse text-[11px] border border-slate-300 mb-4">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300 text-slate-700 font-bold">
                        <th class="py-1.5 px-2 text-center w-8">ที่</th>
                        <th class="py-1.5 px-3">รายการแข่งขัน</th>
                        <th class="py-1.5 px-2 text-center w-24">ระดับ</th>
                        <th class="py-1.5 px-2 text-center w-16">ทีมสมัคร</th>
                        <th class="py-1.5 px-2 text-center w-16">อนุมัติ</th>
                        <th class="py-1.5 px-2 text-center w-16">นักเรียน</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php 
                        $cIdx = 1;
                        foreach(array_slice($competitions['list_stats'], 0, 7) as $cs):
                    ?>
                        <tr>
                            <td class="py-1 px-2 text-center font-mono text-slate-500"><?= $cIdx++ ?></td>
                            <td class="py-1 px-3 text-slate-800 truncate max-w-[200px]"><?= esc($cs['name']) ?></td>
                            <td class="py-1 px-2 text-center text-slate-600"><?= esc($cs['level'] ?: 'ทุกระดับ') ?></td>
                            <td class="py-1 px-2 text-center font-mono font-semibold"><?= number_format($cs['total_teams']) ?></td>
                            <td class="py-1 px-2 text-center font-mono text-emerald-700 font-semibold"><?= number_format($cs['approved_teams']) ?></td>
                            <td class="py-1 px-2 text-center font-mono"><?= number_format($cs['students']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="bg-slate-100 font-bold text-slate-900 border-t border-slate-300">
                        <td colspan="3" class="py-1.5 px-3 text-right">รวมทั้งสิ้น:</td>
                        <td class="py-1.5 px-2 text-center font-mono"><?= number_format($competitions['total_teams']) ?></td>
                        <td class="py-1.5 px-2 text-center font-mono text-emerald-700"><?= number_format($competitions['approved_teams']) ?></td>
                        <td class="py-1.5 px-2 text-center font-mono"><?= number_format($competitions['total_students']) ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Comments & Suggestions (Randomized Feedback) -->
            <h3 class="text-xs font-bold text-slate-800 mb-2 flex items-center gap-1.5">
                <i data-lucide="message-square-quote" class="w-3.5 h-3.5 text-indigo-600"></i> 3.2 สรุปข้อเสนอแนะและข้อคิดเห็นจากผู้เข้าร่วมกิจกรรม
            </h3>
            <div class="space-y-1.5 mb-4">
                <?php 
                    $allCms = $evaluations['comments'] ?? [];
                    if (!empty($allCms)) {
                        shuffle($allCms); // สุ่มข้อคิดเห็นใหม่ทุกครั้งที่เปิด/รีเฟรชหน้า
                    }
                    $sampleComments = array_slice($allCms, 0, 10);

                    if (empty($sampleComments)):
                ?>
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center text-xs text-slate-400 italic">
                        ไม่มีข้อเสนอแนะเพิ่มเติมที่บันทึกไว้ในระบบ
                    </div>
                <?php else: 
                    $cNo = 1;
                    foreach($sampleComments as $cm):
                ?>
                    <div class="py-2 px-3 rounded-xl bg-slate-50 border border-slate-200 text-[11px] leading-relaxed flex items-start gap-2">
                        <span class="w-4 h-4 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold flex items-center justify-center shrink-0 mt-0.5"><?= $cNo++ ?></span>
                        <p class="text-slate-800 font-medium leading-normal"><?= esc($cm['comment']) ?></p>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <div>
            <!-- Footer Page Number -->
            <div class="pt-4 border-t border-slate-200 flex justify-between text-[11px] text-slate-400">
                <span>กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์</span>
                <span>หน้า 3</span>
            </div>
        </div>
    </div>

    <!-- ==================== PAGE 5: EXECUTIVE PROJECT CONCLUSION (บทสรุปผลการดำเนินงานโครงการทั้งหมด) ==================== -->
    <div class="a4-page flex flex-col justify-between page-break">
        <div>
            <!-- Header -->
            <div class="flex justify-between items-center pb-4 mb-6 border-b border-slate-200 text-xs text-slate-400">
                <span class="font-bold text-indigo-900">รายงานสรุปผลโครงการสัปดาห์วิทยาศาสตร์ ประจำปีการศึกษา <?= esc($selected_year) ?></span>
                <span>ส่วนที่ 4: บทสรุปผลการดำเนินงานทั้งหมด</span>
            </div>

            <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">4</span>
                บทสรุปผลการดำเนินงานโครงการทั้งหมด (Project Conclusion & Key Results)
            </h2>

            <!-- 4 Key Result Highlights Cards on Book -->
            <div class="grid grid-cols-2 gap-3.5 mb-5">
                <!-- 1. ยอดผู้มีส่วนร่วมทั้งหมด -->
                <div class="p-3.5 rounded-2xl bg-indigo-50 border border-indigo-200">
                    <p class="text-xs font-bold text-indigo-950 flex items-center gap-1.5 mb-1">
                        <i data-lucide="users" class="w-4 h-4 text-indigo-600"></i> ยอดผู้มีส่วนร่วมทั้งหมดในโครงการ
                    </p>
                    <p class="text-2xl font-black text-indigo-700 font-mono"><?= number_format($summary_overview['grand_total_people']) ?> <span class="text-xs font-normal text-slate-600">คน</span></p>
                    <p class="text-[11px] text-slate-600 mt-1">รวมผู้ทำประเมิน (<?= number_format($summary_overview['total_evaluations']) ?>), ผู้รับเกียรติบัตร (<?= number_format($summary_overview['total_eval_claimed']) ?>), นร.แข่ง (<?= number_format($summary_overview['total_competitors']) ?>), ครู (<?= number_format($summary_overview['total_coaches']) ?>) และ Staff (<?= number_format($summary_overview['total_student_staff']) ?> คน)</p>
                </div>

                <!-- 2. คะแนนความพึงพอใจและร้อยละ -->
                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200">
                    <p class="text-xs font-bold text-emerald-950 flex items-center gap-1.5 mb-1">
                        <i data-lucide="star" class="w-4 h-4 text-emerald-600"></i> ผลการประเมินความพึงพอใจ
                    </p>
                    <p class="text-2xl font-black text-emerald-700 font-mono"><?= number_format($evaluations['grand_mean'], 2) ?> <span class="text-xs font-normal text-slate-600">/ 5.00</span></p>
                    <p class="text-[11px] text-emerald-800 font-medium mt-1">คิดเป็นร้อยละ <strong><?= number_format($evaluations['grand_percentage'], 2) ?>%</strong> (ระดับ<?= $evaluations['grand_quality']['text'] ?>)</p>
                </div>

                <!-- 3. การออกเกียรติบัตร -->
                <div class="p-3.5 rounded-2xl bg-purple-50 border border-purple-200">
                    <p class="text-xs font-bold text-purple-950 flex items-center gap-1.5 mb-1">
                        <i data-lucide="award" class="w-4 h-4 text-purple-600"></i> การออกเกียรติบัตรทุกประเภท
                    </p>
                    <p class="text-2xl font-black text-purple-700 font-mono"><?= number_format($summary_overview['total_certificates_all']) ?> <span class="text-xs font-normal text-slate-600">ใบ</span></p>
                    <p class="text-[11px] text-slate-600 mt-1">ผ่านระบบ E-Certificate ออนไลน์ (เคลมผ่านประเมิน <?= number_format($summary_overview['total_eval_claimed']) ?> ราย)</p>
                </div>

                <!-- 4. กิจกรรมการแข่งขัน -->
                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200">
                    <p class="text-xs font-bold text-amber-950 flex items-center gap-1.5 mb-1">
                        <i data-lucide="trophy" class="w-4 h-4 text-amber-600"></i> กิจกรรมการแข่งขันทั้งหมด
                    </p>
                    <p class="text-2xl font-black text-amber-700 font-mono"><?= number_format($summary_overview['total_competitions']) ?> <span class="text-xs font-normal text-slate-600">รายการ</span></p>
                    <p class="text-[11px] text-slate-600 mt-1">ทีมสมัคร <?= number_format($summary_overview['total_teams']) ?> ทีม (อนุมัติเข้าแข่ง <?= number_format($summary_overview['approved_teams']) ?> ทีม)</p>
                </div>
            </div>

            <!-- Structured Executive Conclusion Narrative -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-700 leading-relaxed space-y-3 text-justify mb-5">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2 border-b border-slate-200 pb-2">
                    <i data-lucide="file-check-2" class="w-4 h-4 text-indigo-600"></i> สรุปผลสัมฤทธิ์และข้อสรุปโครงการ
                </h3>
                <p class="indent-8">
                    โครงการจัดงานสัปดาห์วิทยาศาสตร์ ประจำปีการศึกษา <strong><?= esc($selected_year) ?></strong> ดำเนินการโดย 
                    <strong>กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์</strong> ได้บรรลุตามวัตถุประสงค์และเป้าหมายที่กำหนดไว้อย่างมีประสิทธิภาพ 
                    โดยมีผู้มีส่วนร่วมในโครงการรวมทั้งสิ้น <strong><?= number_format($summary_overview['grand_total_people']) ?></strong> คน 
                    ซึ่งครอบคลุมทั้งนักเรียนผู้เข้าแข่งขัน <strong><?= number_format($summary_overview['total_competitors']) ?></strong> คน, 
                    ครูผู้ฝึกสอน <strong><?= number_format($summary_overview['total_coaches']) ?></strong> ท่าน, 
                    นักเรียนช่วยงาน (Student Staff) <strong><?= number_format($summary_overview['total_student_staff']) ?></strong> คน 
                    และผู้ร่วมทำแบบประเมินความพึงพอใจ <strong><?= number_format($summary_overview['total_evaluations']) ?></strong> คน
                </p>
                <p class="indent-8">
                    ด้านผลการประเมินความพึงพอใจในการจัดกิจกรรม ผู้เข้าร่วมโครงการมีความพึงพอใจในภาพรวมอยู่ในระดับ 
                    <strong>"<?= $evaluations['grand_quality']['text'] ?>"</strong> มีคะแนนเฉลี่ยเท่ากับ 
                    <strong><?= number_format($evaluations['grand_mean'], 2) ?></strong> จากคะแนนเต็ม 5.00 (S.D. = <?= number_format($evaluations['grand_sd'], 2) ?>) 
                    คิดเป็นร้อยละ <strong><?= number_format($evaluations['grand_percentage'], 2) ?>%</strong> 
                    และมีการออกเกียรติบัตรออนไลน์ผ่านระบบ E-Certificate รวมทั้งสิ้น <strong><?= number_format($summary_overview['total_certificates_all']) ?></strong> ใบ
                </p>
            </div>
        </div>

        <div>
            <!-- Footer Page Number -->
            <div class="pt-4 border-t border-slate-200 flex justify-between text-[11px] text-slate-400">
                <span>กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์</span>
                <span>หน้า 4</span>
            </div>
        </div>
    </div>

    <!-- Initialize Lucide Icons & Chart.js for Book Report -->
    <script>
        lucide.createIcons();

        document.addEventListener('DOMContentLoaded', () => {
            // 1. Question Bar Chart for Book
            const qLabels = <?= json_encode(array_map(fn($q) => mb_substr($q['label'], 0, 18) . (mb_strlen($q['label']) > 18 ? '...' : ''), $evaluations['question_stats'])) ?>;
            const qMeans = <?= json_encode(array_column($evaluations['question_stats'], 'mean')) ?>;

            const ctxB = document.getElementById('bookBarChart')?.getContext('2d');
            if (ctxB) {
                new Chart(ctxB, {
                    type: 'bar',
                    data: {
                        labels: qLabels,
                        datasets: [{
                            label: 'คะแนนเฉลี่ย',
                            data: qMeans,
                            backgroundColor: 'rgba(79, 70, 229, 0.85)',
                            borderColor: '#4338ca',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false, // disable animation for clean printing
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                ticks: { font: { size: 9, family: 'Sarabun' }, color: '#475569' },
                                grid: { display: false }
                            },
                            y: {
                                min: 0,
                                max: 5,
                                ticks: { font: { size: 9, family: 'Sarabun' }, color: '#475569', stepSize: 1 },
                                grid: { color: '#e2e8f0' }
                            }
                        }
                    }
                });
            }

            // 2. Rating Distribution Donut Chart for Book
            const distData = <?= json_encode(array_values([
                $evaluations['rating_dist'][5] ?? 0,
                $evaluations['rating_dist'][4] ?? 0,
                $evaluations['rating_dist'][3] ?? 0,
                $evaluations['rating_dist'][2] ?? 0,
                $evaluations['rating_dist'][1] ?? 0
            ])) ?>;

            const ctxD = document.getElementById('bookDonutChart')?.getContext('2d');
            if (ctxD) {
                new Chart(ctxD, {
                    type: 'doughnut',
                    data: {
                        labels: ['มากที่สุด', 'มาก', 'ปานกลาง', 'น้อย', 'น้อยที่สุด'],
                        datasets: [{
                            data: distData,
                            backgroundColor: [
                                '#10b981', // 5
                                '#4f46e5', // 4
                                '#f59e0b', // 3
                                '#ea580c', // 2
                                '#e11d48'  // 1
                            ],
                            borderWidth: 1,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 10,
                                    font: { size: 9, family: 'Sarabun' },
                                    color: '#334155'
                                }
                            }
                        }
                    }
                });
            }

            // 3. Competition Level Breakdown Donut Chart
            <?php 
                $lvlLabels = array_keys($competitions['level_stats']);
                $lvlValues = array_map(fn($v) => $v['teams'], array_values($competitions['level_stats']));
            ?>
            const lvlLabels = <?= json_encode($lvlLabels) ?>;
            const lvlValues = <?= json_encode($lvlValues) ?>;
            const ctxL = document.getElementById('bookLevelChart')?.getContext('2d');
            if (ctxL) {
                new Chart(ctxL, {
                    type: 'doughnut',
                    data: {
                        labels: lvlLabels,
                        datasets: [{
                            data: lvlValues,
                            backgroundColor: ['#6366f1', '#a855f7', '#ec4899', '#06b6d4', '#f59e0b'],
                            borderWidth: 1,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        cutout: '60%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 10,
                                    font: { size: 8.5, family: 'Sarabun' },
                                    color: '#334155'
                                }
                            }
                        }
                    }
                });
            }

            // 4. Top Provinces Bar Chart
            <?php 
                $top5Provs = array_slice($evaluations['top_provinces'], 0, 5, true);
                $provLabels = array_keys($top5Provs);
                $provValues = array_values($top5Provs);
            ?>
            const provLabels = <?= json_encode($provLabels) ?>;
            const provValues = <?= json_encode($provValues) ?>;
            const ctxP = document.getElementById('bookProvinceChart')?.getContext('2d');
            if (ctxP) {
                new Chart(ctxP, {
                    type: 'bar',
                    data: {
                        labels: provLabels,
                        datasets: [{
                            label: 'จำนวน (ชุด)',
                            data: provValues,
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            borderColor: '#059669',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        indexAxis: 'y', // Horizontal bar chart
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                ticks: { font: { size: 8.5, family: 'Sarabun' }, color: '#475569' },
                                grid: { color: '#e2e8f0' }
                            },
                            y: {
                                ticks: { font: { size: 8.5, family: 'Sarabun' }, color: '#475569' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
