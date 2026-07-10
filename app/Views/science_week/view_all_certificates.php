<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo-pao.png') ?>">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Sarabun:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', 'Sarabun', sans-serif;
            background-color: #0b0f19;
            color: #cbd5e1;
        }
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            body {
                background: white !important;
                color: black !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
                margin: 0 !important;
                padding: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
            }
            /* Reset all wrapping layouts */
            .max-w-4xl, .space-y-12, .space-y-8, [class*="mt-"], [class*="py-"], [class*="px-"] {
                margin: 0 !important;
                padding: 0 !important;
                max-width: none !important;
                width: 100% !important;
            }
            .space-y-12 > :not([hidden]) ~ :not([hidden]),
            .space-y-8 > :not([hidden]) ~ :not([hidden]) {
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }
            .print-page-break {
                page-break-after: always;
                page-break-inside: avoid;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
                width: 100vw;
                height: 100vh;
                display: flex !important;
                align-items: center;
                justify-content: center;
                box-sizing: border-box;
            }
            .print-page-break > div {
                border: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                background: transparent !important;
                box-shadow: none !important;
                width: 100% !important;
                height: 100% !important;
            }
            .print-img {
                border-radius: 0 !important;
                box-shadow: none !important;
                width: 100vw !important;
                height: 100vh !important;
                object-fit: contain !important;
                margin: 0 !important;
                padding: 0 !important;
                display: block !important;
            }
        }
    </style>
</head>
<body class="min-h-screen pb-16">
    <!-- Header Area (no-print) -->
    <div class="no-print bg-slate-900/60 border-b border-indigo-500/15 backdrop-blur-md sticky top-0 z-50 px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-indigo-500/10 text-indigo-400">
                <i data-lucide="award" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="text-sm font-bold text-white">เกียรติบัตรทั้งหมดสำหรับผู้สมัคร</h1>
                <p class="text-[10px] text-slate-400 mt-0.5">ทีมรหัส: <span class="font-mono text-indigo-400 font-bold"><?= esc($code) ?></span> | สามารถกดดู สั่งพิมพ์ หรือบันทึก PDF</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-650 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-lg shadow-indigo-600/20">
                <i data-lucide="printer" class="w-4 h-4"></i> สั่งพิมพ์ / บันทึก PDF ทั้งหมด
            </button>
            <button onclick="window.close()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                ปิดหน้านี้
            </button>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 mt-8">
        <div class="no-print mb-6 p-4 rounded-2xl bg-indigo-500/5 border border-indigo-500/15 text-xs text-indigo-300 flex items-start gap-2.5">
            <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5 text-indigo-400"></i>
            <div>
                <p class="font-bold">เคล็ดลับการดาวน์โหลดเกียรติบัตร:</p>
                <ul class="list-disc pl-4 mt-1 space-y-1 text-slate-450 leading-relaxed font-semibold">
                    <li><strong>บันทึกเป็นรูปภาพทีละใบ:</strong> คลิกขวาที่รูปเกียรติบัตรแล้วเลือก "บันทึกรูปภาพเป็น... (Save Image As...)"</li>
                    <li><strong>สั่งพิมพ์ / บันทึก PDF:</strong> คลิกปุ่ม "สั่งพิมพ์ / บันทึก PDF ทั้งหมด" ด้านบนเพื่อประมวลผลจัดหน้าอัตโนมัติ</li>
                </ul>
            </div>
        </div>

        <!-- Certificates list -->
        <div class="space-y-12">
            <!-- Student Certificates Section -->
            <div class="space-y-8">
                <?php if (!empty($advisors)): ?>
                    <h2 class="no-print text-sm font-extrabold text-indigo-400 border-l-4 border-indigo-500 pl-3">เกียรติบัตรนักเรียน (สมาชิกผู้เข้าแข่งขัน)</h2>
                <?php endif; ?>
                
                <?php foreach ($members as $m): ?>
                    <div class="print-page-break p-4 sm:p-6 rounded-3xl bg-slate-900/40 border border-slate-800/80 shadow-xl space-y-4">
                        <div class="no-print flex justify-between items-center border-b border-slate-800/80 pb-3">
                            <span class="text-xs font-bold text-slate-200 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                นักเรียน: <?= esc($m) ?>
                            </span>
                            <a href="<?= base_url("science-week/certificate/download/{$type}/{$code}") ?>?name=<?= urlencode($m) ?>&preview=1" target="_blank" class="px-3 py-1.5 bg-slate-850 hover:bg-slate-800 border border-slate-750 text-[10px] font-bold text-slate-350 rounded-lg transition-all flex items-center gap-1">
                                <i data-lucide="download" class="w-3.5 h-3.5 text-indigo-400"></i> ดาวน์โหลดรูปภาพเดี่ยว
                            </a>
                        </div>
                        
                        <!-- Certificate Image Rendering -->
                        <div class="relative overflow-hidden rounded-xl border border-slate-800/80">
                            <img src="<?= base_url("science-week/certificate/download/{$type}/{$code}") ?>?name=<?= urlencode($m) ?>&preview=1" class="print-img w-full h-auto" alt="เกียรติบัตรสำหรับ <?= esc($m) ?>">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Advisor Certificates Section -->
            <?php if (!empty($advisors)): ?>
                <div class="space-y-8">
                    <h2 class="no-print text-sm font-extrabold text-emerald-400 border-l-4 border-emerald-500 pl-3">เกียรติบัตรครู / อาจารย์ผู้ควบคุมทีม</h2>
                    
                    <?php foreach ($advisors as $a): ?>
                        <div class="print-page-break p-4 sm:p-6 rounded-3xl bg-slate-900/40 border border-slate-800/80 shadow-xl space-y-4">
                            <div class="no-print flex justify-between items-center border-b border-slate-800/80 pb-3">
                                <span class="text-xs font-bold text-slate-200 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    ครูผู้ควบคุม: <?= esc($a) ?>
                                </span>
                                <a href="<?= base_url("science-week/certificate/download/trainer/{$code}") ?>?name=<?= urlencode($a) ?>&preview=1" target="_blank" class="px-3 py-1.5 bg-slate-850 hover:bg-slate-800 border border-slate-750 text-[10px] font-bold text-slate-350 rounded-lg transition-all flex items-center gap-1">
                                    <i data-lucide="download" class="w-3.5 h-3.5 text-emerald-400"></i> ดาวน์โหลดรูปภาพเดี่ยว
                                </a>
                            </div>
                            
                            <!-- Certificate Image Rendering -->
                            <div class="relative overflow-hidden rounded-xl border border-slate-800/80">
                                <img src="<?= base_url("science-week/certificate/download/trainer/{$code}") ?>?name=<?= urlencode($a) ?>&preview=1" class="print-img w-full h-auto" alt="เกียรติบัตรสำหรับ <?= esc($a) ?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Init Lucide Icons
        lucide.createIcons();
    </script>
</body>
</html>
