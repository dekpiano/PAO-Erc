<?= $this->extend('itsupport/layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Navigation & Action Toolbar -->
<div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 mb-6">
    <!-- Left: Title & Badge -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('itsupport/mou') ?>" class="w-11 h-11 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-indigo-600 hover:border-indigo-300 dark:hover:border-indigo-600 transition-all shadow-sm flex items-center justify-center shrink-0" title="กลับหน้า MOU">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-700 flex items-center gap-1">
                    <i data-lucide="clipboard-check" class="w-3 h-3"></i>
                    Official Self-Report Form
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700 flex items-center gap-1">
                    <i data-lucide="user-check" class="w-3 h-3"></i>
                    ผู้รับการประเมิน: <?= esc($officer['u_prefix'] . ' ' . $officer['u_fullname']) ?>
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700 flex items-center gap-1">
                    <i data-lucide="calendar" class="w-3 h-3"></i>
                    รอบที่ <?= esc($selectedRound) ?> ปีงบประมาณ <?= esc($selectedFY) ?> (<?= esc($periodLabel) ?>)
                </span>
            </div>
            <h1 class="text-lg sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-1">
                แบบรายงานตนเอง (รอบ <?= esc($selectedRound) ?>) ปีงบประมาณ <?= esc($selectedFY) ?>
            </h1>
        </div>
    </div>

    <!-- Right: View Mode Toggle & Print/Export Actions -->
    <div class="flex items-center gap-2.5 flex-wrap">
        <!-- Switch View Mode Buttons -->
        <div class="p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center border border-slate-200 dark:border-slate-700 shadow-sm">
            <button type="button" id="btn-view-official" onclick="switchReportTab('official')" class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                <span>แบบฟอร์มกระดาษ A4 แนวนอน</span>
            </button>
            <button type="button" id="btn-view-modern" onclick="switchReportTab('modern')" class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-indigo-600">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
                <span>ภาพรวมและหลักฐาน</span>
            </button>
        </div>

        <!-- Export Word Document Button -->
        <a href="<?= base_url('itsupport/self-report-export') ?>" class="px-4 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i data-lucide="download" class="w-4 h-4"></i>
            <span>ดาวน์โหลดไฟล์ Word (.docx)</span>
        </a>

        <!-- Print Official Form Button -->
        <button type="button" onclick="printOfficialDoc()" class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white font-black text-xs shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>พิมพ์แบบรายงาน (A4 แนวนอน)</span>
        </button>
    </div>
</div>

<!-- TOP HERO / OFFICER IDENTITY SUMMARY -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 shadow-2xl border border-indigo-900/50 mb-8">
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-6">
        <!-- Officer Info & Profile -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-5">
            <div class="relative shrink-0">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl p-1 bg-gradient-to-tr from-indigo-500 via-purple-500 to-rose-500 shadow-xl">
                    <img src="<?= base_url('uploads/profiles/' . ($officer['u_photo'] ?? 'jriftgd2e1774525009628.png')) ?>" 
                         alt="<?= esc($officer['u_fullname'] ?? 'เจ้าหน้าที่') ?>"
                         onerror="this.src='https://ui-avatars.com/api/?name=Wachirawit+Klaewkarnthai&background=4f46e5&color=fff&size=200';"
                         class="w-full h-full object-cover rounded-[22px]">
                </div>
                <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-emerald-500 border-2 border-slate-900 flex items-center justify-center text-white shadow-md" title="สถานะ: พร้อมรับการประเมิน">
                    <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                </div>
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        พนักงานจ้างตามภารกิจ
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-500/20 text-rose-300 border border-rose-500/30">
                        แบบรายงานผลสัมฤทธิ์ของงาน (รอบ <?= esc($selectedRound) ?>)
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        ปีงบประมาณ <?= esc($selectedFY) ?>
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white">
                    <?= esc($officer['u_prefix'] . ' ' . $officer['u_fullname']) ?>
                </h2>
                <p class="text-sm font-bold text-indigo-300">
                    ตำแหน่ง <?= esc($officer['pos_name'] ?? 'ผู้ช่วยนักวิชาการคอมพิวเตอร์') ?>
                </p>
                <p class="text-xs text-slate-300 flex items-center justify-center sm:justify-start gap-1.5">
                    <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span><?= esc($officer['u_division'] ?? 'ฝ่ายบริหารการศึกษา กองการศึกษา ศาสนาและวัฒนธรรม') ?></span>
                </p>
                <p class="text-xs text-emerald-400 font-semibold flex items-center justify-center sm:justify-start gap-1.5">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    <span>รอบระยะเวลาการประเมิน: <strong><?= esc($periodLabel) ?></strong></span>
                </p>
            </div>
        </div>

        <!-- Quick Summary Metrics -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 w-full lg:w-auto shrink-0">
            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm text-center">
                <span class="text-[10px] font-black uppercase tracking-wider text-indigo-300 block mb-1">ผลงานในรอบที่ <?= esc($selectedRound) ?></span>
                <span class="text-2xl font-black text-white"><?= number_format($totalRoundLogs) ?></span>
                <span class="text-[10px] text-slate-400 block">รายการจริง</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm text-center">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-300 block mb-1">ภารกิจหลัก</span>
                <span class="text-2xl font-black text-white">3 / 3</span>
                <span class="text-[10px] text-slate-400 block">เกินเป้า 100%</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm text-center">
                <span class="text-[10px] font-black uppercase tracking-wider text-purple-300 block mb-1">สมรรถนะหลัก</span>
                <span class="text-2xl font-black text-white">5 ด้าน</span>
                <span class="text-[10px] text-slate-400 block">ระดับคะแนน 2</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm text-center">
                <span class="text-[10px] font-black uppercase tracking-wider text-rose-300 block mb-1">สมรรถนะสายงาน</span>
                <span class="text-2xl font-black text-white">3 ด้าน</span>
                <span class="text-[10px] text-slate-400 block">ระดับคะแนน 2</span>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- VIEW MODE 1: OFFICIAL WHITE A4 PAPER VIEW -->
<!-- ========================================== -->
<div id="view-section-official" class="space-y-8 bg-slate-100/90 dark:bg-slate-900/60 p-4 sm:p-6 lg:p-8 rounded-3xl border border-slate-200 dark:border-slate-800">
    <div class="text-center mb-2 no-print flex flex-col items-center gap-1.5">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
            <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
            รูปแบบกระดาษมาตรฐาน A4 แนวนอน (Landscape) สีขาว
        </span>
        <p class="text-xs text-slate-500 dark:text-slate-400">
            จำลองรูปแบบกระดาษมาตรฐาน A4 แนวนอน สีขาว อ้างอิงตามไฟล์ <strong>แบบรายงานตนเอง (รอบ <?= esc($selectedRound) ?>) ปี 69.docx</strong> ครบถ้วนทุกตารางและแบบบันทึกพฤติกรรม
        </p>
    </div>

    <!-- PAGE 1: ผลสัมฤทธิ์ของงาน ข้อ 1 -->
    <div class="a4-sheet bg-white text-slate-900 shadow-xl mx-auto p-6 sm:p-10 rounded-2xl border border-slate-300 relative font-sarabun text-[13.5pt] leading-relaxed">
        <div class="text-center font-bold mb-4">
            <p class="text-[16pt]">(แบบรายงานตนเอง)</p>
            <p class="text-[18pt] font-black mt-1">แบบรายงานผลสัมฤทธิ์ของงาน</p>
        </div>

        <div class="overflow-x-auto my-4">
            <table class="official-table w-full border-collapse border border-black text-[13pt]">
                <thead>
                    <tr class="text-center font-bold bg-slate-50">
                        <th rowspan="2" class="border border-black p-2.5 w-[25%] align-middle">โครงการ/งาน/กิจกรรม</th>
                        <th colspan="3" class="border border-black p-2 align-middle">ผลการปฏิบัติงานตามเป้าหมายที่กำหนด</th>
                        <th rowspan="2" class="border border-black p-2 w-[6%] align-middle">หมายเหตุ</th>
                    </tr>
                    <tr class="text-center font-bold bg-slate-50">
                        <th class="border border-black p-2 w-[23%]">เชิงปริมาณ</th>
                        <th class="border border-black p-2 w-[23%]">เชิงคุณภาพ</th>
                        <th class="border border-black p-2 w-[23%]">เชิงประโยชน์</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black p-3 align-top font-bold">
                            1.การพัฒนาและบำรุงรักษาระบบสารสนเทศภายในโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์(จำนวน 24 ครั้ง)
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-semibold underline">พัฒนาและปรับปรุงระบบสารสนเทศของโรงเรียน ร้อยละ 80</p>
                            <p class="mt-2 text-[12pt] leading-normal">
                                ระบบสารสนเทศได้รับการพัฒนาและปรับปรุงตามแผนที่กำหนดสามารถดำเนินการได้ครบตามจำนวนครั้งที่กำหนด (26 ครั้ง) คิดเป็นร้อยละ 108 เกินกว่าเป้าหมายที่กำหนด (รายละเอียดตามเอกสารหลักฐานที่แนบ)
                            </p>
                            <p class="mt-2 font-bold text-indigo-900">คะแนนที่ได้รับ 3 คะแนน</p>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-semibold underline">ระบบสารสนเทศของโรงเรียนสามารถใช้งานได้อย่างมีประสิทธิภาพ ร้อยละ 80</p>
                            <p class="mt-2 text-[12pt] leading-normal">
                                ระบบที่พัฒนาขึ้นมีความเสถียรและใช้งานได้จริง คิดเป็นร้อยละ 80 เกินกว่าเป้าหมายที่กำหนด (รายละเอียดตามเอกสารหลักฐานที่แนบ)
                            </p>
                            <p class="mt-2 font-bold text-indigo-900">คะแนนที่ได้รับ 3 คะแนน</p>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-semibold underline">เพิ่มประสิทธิภาพในการบริหารจัดการข้อมูลของโรงเรียน ร้อยละ 80</p>
                            <p class="mt-2 text-[12pt] leading-normal">
                                ระบบสารสนเทศที่พัฒนาขึ้นมาใหม่ช่วยให้ครูและเจ้าหน้าที่สามารถจัดการข้อมูลได้ตามที่ต้องการคิดเป็นร้อยละ 80 เกินกว่าเป้าหมายที่กำหนด (รายละเอียดตามเอกสารหลักฐานที่แนบ)
                            </p>
                            <p class="mt-2 font-bold text-indigo-900">คะแนนที่ได้รับ 4 คะแนน</p>
                        </td>
                        <td class="border border-black p-3 align-top text-center text-xs">
                            -
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Signature Block -->
        <div class="mt-8 sm:mt-10 flex justify-end">
            <div class="text-center w-72 space-y-1">
                <p>ว่าที่ ร.ต...................................................ผู้รับการประเมิน</p>
                <p class="font-bold">(วชิรวิทย์  แกล้วการไถ)</p>
                <p class="text-[12pt]">ผู้ช่วยนักวิชาการคอมพิวเตอร์</p>
            </div>
        </div>

        <div class="text-center text-xs text-slate-400 mt-6 border-t pt-2 no-print">
            - หน้า 1 / 8 -
        </div>
    </div>

    <!-- PAGE 2: ผลสัมฤทธิ์ของงาน ข้อ 2 -->
    <div class="a4-sheet bg-white text-slate-900 shadow-xl mx-auto p-6 sm:p-10 rounded-2xl border border-slate-300 relative font-sarabun text-[13.5pt] leading-relaxed page-break">
        <div class="flex justify-between items-center mb-4">
            <span class="text-xs font-mono text-slate-400">-2-</span>
            <div class="text-center font-bold flex-1">
                <p class="text-[16pt]">(แบบรายงานตนเอง)</p>
                <p class="text-[18pt] font-black mt-1">แบบรายงานผลสัมฤทธิ์ของงาน</p>
            </div>
            <span class="w-6"></span>
        </div>

        <div class="overflow-x-auto my-4">
            <table class="official-table w-full border-collapse border border-black text-[13pt]">
                <thead>
                    <tr class="text-center font-bold bg-slate-50">
                        <th rowspan="2" class="border border-black p-2.5 w-[25%] align-middle">โครงการ/งาน/กิจกรรม</th>
                        <th colspan="3" class="border border-black p-2 align-middle">ผลการปฏิบัติงานตามเป้าหมายที่กำหนด</th>
                        <th rowspan="2" class="border border-black p-2 w-[6%] align-middle">หมายเหตุ</th>
                    </tr>
                    <tr class="text-center font-bold bg-slate-50">
                        <th class="border border-black p-2 w-[23%]">เชิงปริมาณ</th>
                        <th class="border border-black p-2 w-[23%]">เชิงคุณภาพ</th>
                        <th class="border border-black p-2 w-[23%]">เชิงประโยชน์</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black p-3 align-top font-bold">
                            2. งานโสตทัศนศึกษา (จำนวน 6 ครั้ง)
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-semibold underline">ดูแลและจัดเตรียมอุปกรณ์โสตทัศนูปกรณ์สำหรับกิจกรรม ร้อยละ 80</p>
                            <p class="mt-2 text-[12pt] leading-normal">
                                ได้ดำเนินการดูแลและจัดเตรียมอุปกรณ์โสตทัศนูปกรณ์สำหรับกิจกรรมต่างๆ ครบถ้วนตามจำนวนที่กำหนด (32 ครั้ง) คิดเป็นร้อยละ 533 เกินกว่าเป้าหมายที่กำหนด (รายละเอียดตามเอกสารหลักฐานที่แนบ)
                            </p>
                            <p class="mt-2 font-bold text-indigo-900">คะแนนที่ได้รับ 3 คะแนน</p>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-semibold underline">การจัดเตรียมอุปกรณ์เป็นไปอย่างมีประสิทธิภาพและตรงตามความต้องการทันต่อกิจกรรม ร้อยละ 80</p>
                            <p class="mt-2 text-[12pt] leading-normal">
                                อุปกรณ์โสตทัศนูปกรณ์ที่จัดเตรียมมีความพร้อมใช้งาน และสามารถตอบสนองความต้องการของกิจกรรมได้อย่างเหมาะสม คิดเป็นร้อยละ 80 เกินกว่าเป้าหมายที่กำหนด (รายละเอียดตามเอกสารหลักฐานที่แนบ)
                            </p>
                            <p class="mt-2 font-bold text-indigo-900">คะแนนที่ได้รับ 3 คะแนน</p>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-semibold underline">ลดความล่าช้าหรือปัญหาทางเทคนิคที่เกิดขึ้นระหว่างกิจกรรม ร้อยละ 80</p>
                            <p class="mt-2 text-[12pt] leading-normal">
                                ทำให้กิจกรรมต่างๆ สามารถเริ่มต้นและดำเนินไปได้อย่างตรงเวลา คิดเป็นร้อยละ 80 เกินกว่าเป้าหมายที่กำหนด (รายละเอียดตามเอกสารหลักฐานที่แนบ)
                            </p>
                            <p class="mt-2 font-bold text-indigo-900">คะแนนที่ได้รับ 4 คะแนน</p>
                        </td>
                        <td class="border border-black p-3 align-top text-center text-xs">
                            -
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Signature Block -->
        <div class="mt-8 sm:mt-10 flex justify-end">
            <div class="text-center w-72 space-y-1">
                <p>ว่าที่ ร.ต...................................................ผู้รับการประเมิน</p>
                <p class="font-bold">(วชิรวิทย์  แกล้วการไถ)</p>
                <p class="text-[12pt]">ผู้ช่วยนักวิชาการคอมพิวเตอร์</p>
            </div>
        </div>

        <div class="text-center text-xs text-slate-400 mt-6 border-t pt-2 no-print">
            - หน้า 2 / 8 -
        </div>
    </div>

    <!-- PAGE 3: ผลสัมฤทธิ์ของงาน ข้อ 3 -->
    <div class="a4-sheet bg-white text-slate-900 shadow-xl mx-auto p-6 sm:p-10 rounded-2xl border border-slate-300 relative font-sarabun text-[13.5pt] leading-relaxed page-break">
        <div class="flex justify-between items-center mb-4">
            <span class="text-xs font-mono text-slate-400">-3-</span>
            <div class="text-center font-bold flex-1">
                <p class="text-[16pt]">(แบบรายงานตนเอง)</p>
                <p class="text-[18pt] font-black mt-1">แบบรายงานผลสัมฤทธิ์ของงาน</p>
            </div>
            <span class="w-6"></span>
        </div>

        <div class="overflow-x-auto my-4">
            <table class="official-table w-full border-collapse border border-black text-[13pt]">
                <thead>
                    <tr class="text-center font-bold bg-slate-50">
                        <th rowspan="2" class="border border-black p-2.5 w-[25%] align-middle">โครงการ / งาน / กิจกรรม</th>
                        <th colspan="3" class="border border-black p-2 align-middle">ผลการปฏิบัติงานตามเป้าหมายที่กำหนด</th>
                        <th rowspan="2" class="border border-black p-2 w-[6%] align-middle">หมายเหตุ</th>
                    </tr>
                    <tr class="text-center font-bold bg-slate-50">
                        <th class="border border-black p-2 w-[23%]">เชิงปริมาณ</th>
                        <th class="border border-black p-2 w-[23%]">เชิงคุณภาพ</th>
                        <th class="border border-black p-2 w-[23%]">เชิงประโยชน์</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black p-3 align-top font-bold">
                            3. การสนับสนุนงานด้านไอทีและสื่อการเรียนการสอนจำนวน 24 ครั้งปีงบประมาณ 2569
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-semibold underline">สนับสนุนการใช้โปรแกรมหรือแพลตฟอร์มดิจิทัล และอุปกรณ์ต่อพ่วงต่าง ๆ ร้อยละ 80</p>
                            <p class="mt-2 text-[12pt] leading-normal">
                                ได้ดำเนินการสนับสนุนการใช้เทคโนโลยีและสื่อการเรียนการสอนให้กับครูและบุคลากรครบถ้วนตามจำนวนที่กำหนด (34 ครั้ง) คิดเป็นร้อยละ 142 เกินกว่าเป้าหมายที่กำหนด (รายละเอียดตามเอกสารหลักฐานที่แนบ)
                            </p>
                            <p class="mt-2 font-bold text-indigo-900">คะแนนที่ได้รับ 3 คะแนน</p>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-semibold underline">นำเทคโนโลยีไปใช้ในการเรียนการสอนได้อย่างมีประสิทธิภาพ ร้อยละ 80</p>
                            <p class="mt-2 text-[12pt] leading-normal">
                                การสนับสนุนเป็นไปอย่างรวดเร็วและตรงจุด ทำให้ครูสามารถนำเทคโนโลยีไปใช้ในการเรียนการสอนได้โดยไม่มีอุปสรรค คิดเป็นร้อยละ 80 เกินกว่าเป้าหมายที่กำหนด (รายละเอียดตามเอกสารหลักฐานที่แนบ)
                            </p>
                            <p class="mt-2 font-bold text-indigo-900">คะแนนที่ได้รับ 3 คะแนน</p>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-semibold underline">ใช้เทคโนโลยีในการสอนได้อย่างมีประสิทธิภาพและลดภาระงาน ร้อยละ 80</p>
                            <p class="mt-2 text-[12pt] leading-normal">
                                ช่วยลดภาระงานของครูในการจัดเตรียมสื่อการสอนแบบเดิม และทำให้การทำงานด้านข้อมูลเป็นไปอย่างรวดเร็ว คิดเป็นร้อยละ 80 เกินกว่าเป้าหมายที่กำหนด (รายละเอียดตามเอกสารหลักฐานที่แนบ)
                            </p>
                            <p class="mt-2 font-bold text-indigo-900">คะแนนที่ได้รับ 4 คะแนน</p>
                        </td>
                        <td class="border border-black p-3 align-top text-center text-xs">
                            -
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Signature Block -->
        <div class="mt-8 sm:mt-10 flex justify-end">
            <div class="text-center w-72 space-y-1">
                <p>ว่าที่ ร.ต...................................................ผู้รับการประเมิน</p>
                <p class="font-bold">(วชิรวิทย์  แกล้วการไถ)</p>
                <p class="text-[12pt]">ผู้ช่วยนักวิชาการคอมพิวเตอร์</p>
            </div>
        </div>

        <div class="text-center text-xs text-slate-400 mt-6 border-t pt-2 no-print">
            - หน้า 3 / 8 -
        </div>
    </div>

    <!-- PAGE 4: เอกสารแนบท้าย ข้อ 1 -->
    <div class="a4-sheet bg-white text-slate-900 shadow-xl mx-auto p-6 sm:p-10 rounded-2xl border border-slate-300 relative font-sarabun text-[13.5pt] leading-relaxed page-break">
        <div class="text-center font-bold mb-3">
            <p class="text-[15pt]">***เอกสารแนบท้าย (แบบรายงานผลสัมฤทธิ์ของงาน)*****</p>
            <p class="text-[15pt] font-black mt-1">1. การพัฒนาและบำรุงรักษาระบบสารสนเทศภายในโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ (จำนวน 24 ครั้ง)</p>
        </div>

        <div class="overflow-x-auto my-4">
            <table class="official-table w-full border-collapse border border-black text-[12pt]">
                <thead>
                    <tr class="text-center font-bold bg-slate-50">
                        <th class="border border-black p-2.5 w-1/3">เชิงปริมาณ</th>
                        <th class="border border-black p-2.5 w-1/3">เชิงคุณภาพ</th>
                        <th class="border border-black p-2.5 w-1/3">เชิงประโยชน์</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black p-3 align-top">
                            <p class="font-bold underline mb-1">พัฒนาและปรับปรุงระบบสารสนเทศของโรงเรียน คิดเป็นร้อยละ 80</p>
                            <p class="font-semibold text-slate-700">พิจารณาจากผลการปฏิบัติงานจริงในระบบ (รอบ <?= esc($selectedRound) ?>/<?= esc($selectedFY) ?>):</p>
                            <div class="space-y-1.5 mt-1 text-[11.5pt] leading-relaxed">
                                <p><strong>1. งานพัฒนาระบบสารสนเทศและเว็บแอปพลิเคชัน:</strong></p>
                                <ul class="list-disc pl-5 space-y-0.5">
                                    <li>พัฒนาระบบการลางานออนไลน์ สำหรับบุคลากรกองการศึกษา ศาสนาและวัฒนธรรม อบจ.นครสวรรค์ ให้สามารถยื่นและอนุมัติการลาผ่านระบบดิจิทัลได้อย่างสะดวกรวดเร็ว</li>
                                    <li>ออกแบบและจัดทำเว็บไซต์งานสวนพฤกษศาสตร์โรงเรียน ร่วมกับคณะครูและกองการศึกษาฯ เพื่อเป็นแหล่งเรียนรู้ดิจิทัล</li>
                                    <li>ปรับปรุงและบำรุงรักษาระบบงานทะเบียน-วัดผล สำหรับการประมวลผลและการพิมพ์ผลการเรียนซ้ำของนักเรียน</li>
                                    <li>เข้าร่วมประชุมบูรณาการและหารือการเชื่อมโยงระบบ Big Data ด้านการศึกษา ร่วมกับกองการศึกษา ศาสนาและวัฒนธรรม อบจ.นครสวรรค์</li>
                                </ul>
                                <p><strong>2. การบำรุงรักษาโครงสร้างพื้นฐานระบบเครือข่ายและระบบความปลอดภัย:</strong></p>
                                <ul class="list-disc pl-5 space-y-0.5">
                                    <li>ตรวจสอบและเดินสายสัญญาณเครือข่ายคอมพิวเตอร์ (LAN) ใหม่ทดแทนสายเดิมที่ชำรุด ณ ห้องธุรการ และจุดบริการฝ่ายต่าง ๆ</li>
                                    <li>ตรวจสอบและขยายจุดกระจายสัญญาณ Wi-Fi / Access Point (AP) เพิ่มเติม ณ ห้องหมวดสุขศึกษาและพลศึกษา, ห้องดนตรีไทย และห้องพักครู</li>
                                    <li>ตรวจสอบและตั้งค่าระบบสแกนลายนิ้วมือ และเครื่องสแกนใบหน้าลงเวลาปฏิบัติราชการ ณ ประตูหน้าโรงเรียนและห้องบุคลากร ให้เชื่อมโยงกับฐานข้อมูลได้อย่างเสถียร</li>
                                    <li>ตรวจสอบและตั้งค่าระบบกล้องวงจรปิด (CCTV) เพื่อดูแลความปลอดภัย ณ อาคารกีฬาและจุดสำคัญภายในโรงเรียน</li>
                                </ul>
                            </div>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-bold underline mb-1">ระบบสารสนเทศของโรงเรียนสามารถใช้งานได้อย่างมีประสิทธิภาพ คิดเป็นร้อยละ 80</p>
                            <p class="font-semibold text-slate-700">พิจารณาจากผลการปฏิบัติงานจริงในระบบ:</p>
                            <div class="space-y-1.5 mt-1 text-[11.5pt] leading-relaxed">
                                <p><strong>1.</strong> ระบบสารสนเทศและเว็บแอปพลิเคชันที่พัฒนาขึ้นมีความเสถียรและพร้อมใช้งานสูง รองรับการทำงานจริงได้อย่างถูกต้อง ไม่เกิดปัญหาขัดข้อง</p>
                                <p><strong>2.</strong> ระบบการลางานออนไลน์และเว็บไซต์สวนพฤกษศาสตร์ใช้งานได้ตามวัตถุประสงค์ ช่วยให้การบริหารจัดการข้อมูลขององค์กรมีความทันสมัยและเป็นระเบียบ</p>
                                <p><strong>3.</strong> จุดกระจายสัญญาณ Wi-Fi และเครือข่ายอินเทอร์เน็ตครอบคลุมพื้นที่การทำงานของครูและนักเรียน บุคลากรสามารถเข้าถึงได้อย่างสะดวกรวดเร็วและปลอดภัย</p>
                                <p><strong>4.</strong> มีการเฝ้าระวังและบำรุงรักษาระบบโครงข่ายเชิงรุก ตรวจสอบแก้ไขปัญหาทางเทคนิคได้อย่างทันท่วงที</p>
                            </div>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-bold underline mb-1">เพิ่มประสิทธิภาพในการบริหารจัดการข้อมูลของโรงเรียน คิดเป็นร้อยละ 80</p>
                            <p class="font-semibold text-slate-700">พิจารณาจากผลการปฏิบัติงานจริงในระบบ:</p>
                            <div class="space-y-1.5 mt-1 text-[11.5pt] leading-relaxed">
                                <p><strong>1.</strong> ช่วยลดขั้นตอน ลดความซ้ำซ้อน และลดการใช้กระดาษ (Paperless) เช่น ระบบการลางานออนไลน์ และระบบทะเบียนผลการเรียนซ้ำ</p>
                                <p><strong>2.</strong> การบริหารจัดการข้อมูลทางการศึกษาเชื่อมโยงกับกองการศึกษา อบจ.นครสวรรค์ ได้อย่างถูกต้องแม่นยำ พร้อมต่อยอดสู่ระบบ Big Data</p>
                                <p><strong>3.</strong> บุคลากรทางการศึกษา คณะครู และนักเรียน มีระบบเทคโนโลยีสารสนเทศที่เสถียร รองรับการเรียนรู้และการปฏิบัติงานในยุคดิจิทัลอย่างเต็มศักยภาพ</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Signature Block -->
        <div class="mt-8 sm:mt-10 flex justify-end">
            <div class="text-center w-72 space-y-1">
                <p>ว่าที่ ร.ต...................................................ผู้รับการประเมิน</p>
                <p class="font-bold">(วชิรวิทย์  แกล้วการไถ)</p>
                <p class="text-[12pt]">ผู้ช่วยนักวิชาการคอมพิวเตอร์</p>
            </div>
        </div>

        <div class="text-center text-xs text-slate-400 mt-6 border-t pt-2 no-print">
            - หน้า 4 / 8 -
        </div>
    </div>

    <!-- PAGE 5: เอกสารแนบท้าย ข้อ 2 -->
    <div class="a4-sheet bg-white text-slate-900 shadow-xl mx-auto p-6 sm:p-10 rounded-2xl border border-slate-300 relative font-sarabun text-[13.5pt] leading-relaxed page-break">
        <div class="flex justify-between items-center mb-3">
            <span class="text-xs font-mono text-slate-400">-2-</span>
            <div class="text-center font-bold flex-1">
                <p class="text-[15pt]">***เอกสารแนบท้าย (แบบรายงานผลสัมฤทธิ์ของงาน)*****</p>
                <p class="text-[15pt] font-black mt-1">ข้อ 2. งานโสตทัศนศึกษา (จำนวน 6 ครั้ง)</p>
            </div>
            <span class="w-6"></span>
        </div>

        <div class="overflow-x-auto my-4">
            <table class="official-table w-full border-collapse border border-black text-[12pt]">
                <thead>
                    <tr class="text-center font-bold bg-slate-50">
                        <th class="border border-black p-2.5 w-1/3">เชิงปริมาณ</th>
                        <th class="border border-black p-2.5 w-1/3">เชิงคุณภาพ</th>
                        <th class="border border-black p-2.5 w-1/3">เชิงประโยชน์</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black p-3 align-top">
                            <p class="font-bold underline mb-1">ดูแลและจัดเตรียมอุปกรณ์โสตทัศนูปกรณ์สำหรับกิจกรรม คิดเป็นร้อยละ 80</p>
                            <p class="font-semibold text-slate-700">พิจารณาจากผลการปฏิบัติงานจริงในระบบ (รอบ <?= esc($selectedRound) ?>/<?= esc($selectedFY) ?> รวม 32 ภารกิจ):</p>
                            <div class="space-y-1.5 mt-1 text-[11.5pt] leading-relaxed">
                                <p><strong>1. การควบคุมระบบเสียง จอ LED และระบบภาพในกิจกรรมสำคัญ ณ อาคารเจ้าพระยา:</strong></p>
                                <ul class="list-disc pl-5 space-y-0.5">
                                    <li>กิจกรรมเตรียมวันละอ่อน สำหรับนักเรียนชั้น ม.1 และ ม.4 ประจำปีการศึกษา 2569</li>
                                    <li>โครงการอบรมภาษาอังกฤษเพื่อใช้ในชีวิตประจำวัน</li>
                                    <li>การประชุมและอบรมนโยบาย โครงการ "นครสวรรค์ยั่งยืน"</li>
                                    <li>พิธีมอบทุนการศึกษาของเหล่ากาชาดจังหวัดนครสวรรค์</li>
                                    <li>พิธีไหว้ครู "กุหลาบน้อมกราบวันทา บูชาพระคุณครู" ประจำปีการศึกษา 2569</li>
                                    <li>โครงการรณรงค์ป้องกันและแก้ไขปัญหาโรคติดต่อและเอดส์</li>
                                    <li>กิจกรรมแนะแนวการศึกษาต่อระดับอุดมศึกษาสำหรับนักเรียนชั้น ม.6</li>
                                    <li>งานประชุมสัมมนาของหน่วยงานตำรวจและภาคีเครือข่าย</li>
                                </ul>
                                <p><strong>2. การจัดเตรียมอุปกรณ์โสตทัศนูปกรณ์ จอ LED และแบนเนอร์ ณ ห้อง 72 พรรษา:</strong></p>
                                <ul class="list-disc pl-5 space-y-0.5">
                                    <li>การอบรมเชิงปฏิบัติการด้าน AI สำหรับการจัดการเรียนรู้</li>
                                    <li>กิจกรรมคุณธรรมและจริยธรรมในการใช้งานเทคโนโลยีดิจิทัลอย่างปลอดภัย</li>
                                    <li>โครงการครู D.A.R.E. ให้ความรู้ด้านกฎหมายและการป้องกันยาเสพติด</li>
                                    <li>โครงการส่งเสริมทักษะผู้เรียนและกิจกรรม "กุหลาบปั้นฝัน ปันความรู้สู่ชุมชน"</li>
                                    <li>การอำนวยความสะดวกโสตทัศนูปกรณ์ในการสอบสัมภาษณ์พยาบาลวิชาชีพ และตำแหน่งผู้ช่วยนักวิชาการ อบจ.นครสวรรค์</li>
                                    <li>กิจกรรมฝึกอบรมการช่วยฟื้นคืนชีพขั้นพื้นฐาน (CPR)</li>
                                </ul>
                                <p><strong>3. การดูแลระบบเสียงและจอภาพในกิจกรรมระดับองค์กรและกิจกรรมกลางแจ้ง:</strong></p>
                                <ul class="list-disc pl-5 space-y-0.5">
                                    <li>การประชุมผู้ปกครองนักเรียน ประจำปีการศึกษา 2569 ณ อาคารโดมเอนกประสงค์ บึงบอระเพ็ด (จัดเตรียมสื่อและไฟล์นำเสนอ)</li>
                                    <li>กิจกรรมทำบุญตักบาตรถวายภัตตาหารเช้าและพิธีวันแม่แห่งชาติ 2569 ณ อาคารโดมเอนกประสงค์</li>
                                    <li>ดำเนินการปรับปรุง ติดตั้งลำโพงใหม่ 4 ตัว พร้อมเดินสายสัญญาณเสียง ณ อาคารโดมเอนกประสงค์ เพื่อเพิ่มคุณภาพเสียงให้ครอบคลุม</li>
                                    <li>ดูแลระบบเสียงและจอภาพในกิจกรรมการแข่งขันกีฬาสี ประจำปีการศึกษา 2569 ณ สนามกีฬาโรงเรียน</li>
                                </ul>
                            </div>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-bold underline mb-1">การจัดเตรียมอุปกรณ์เป็นไปอย่างมีประสิทธิภาพและตรงตามความต้องการทันต่อกิจกรรม ร้อยละ 80</p>
                            <p class="font-semibold text-slate-700">พิจารณาจากผลการปฏิบัติงานจริงในระบบ:</p>
                            <div class="space-y-1.5 mt-1 text-[11.5pt] leading-relaxed">
                                <p><strong>1.</strong> มีการจัดเตรียมและทดสอบอุปกรณ์ล่วงหน้า ทำให้ทุกกิจกรรมเริ่มต้นได้ตรงตามกำหนดการ 100% ไม่มีความล่าช้า</p>
                                <p><strong>2.</strong> ระบบภาพบนจอ LED มีความคมชัด และระบบเสียงมีความชัดเจน ปราศจากเสียงหวีดหอนตลอดพิธีการ</p>
                                <p><strong>3.</strong> แผงควบคุมและสายสัญญาณจัดวางอย่างเป็นระเบียบ ปลอดภัยต่อผู้ร่วมงานและสะดวกต่อการปฏิบัติงาน</p>
                                <p><strong>4.</strong> มีการบำรุงรักษาเชิงรุก เช่น การเปลี่ยนลำโพงใหม่ ณ อาคารโดมเอนกประสงค์ ช่วยเพิ่มประสิทธิภาพการกระจายเสียงในกิจกรรมขนาดใหญ่</p>
                            </div>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-bold underline mb-1">ลดความล่าช้าหรือปัญหาทางเทคนิคที่เกิดขึ้นระหว่างกิจกรรม คิดเป็นร้อยละ 80</p>
                            <p class="font-semibold text-slate-700">พิจารณาจากผลการปฏิบัติงานจริงในระบบ:</p>
                            <div class="space-y-1.5 mt-1 text-[11.5pt] leading-relaxed">
                                <p><strong>1.</strong> ลดปัญหาข้อขัดข้องทางเทคนิคระหว่างดำเนินกิจกรรมลงได้มากกว่าร้อยละ 95 ทำให้กิจกรรมดำเนินไปอย่างราบรื่นและสมเกียรติ</p>
                                <p><strong>2.</strong> เสริมสร้างภาพลักษณ์ที่ดี น่าเชื่อถือ และเป็นมืออาชีพให้แก่โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ และ อบจ.นครสวรรค์</p>
                                <p><strong>3.</strong> ลดความเสียหายของอุปกรณ์โสตทัศนูปกรณ์ และประหยัดงบประมาณในการจ้างช่างภายนอก โดยการติดตั้งและซ่อมบำรุงด้วยตนเอง</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Signature Block -->
        <div class="mt-8 sm:mt-10 flex justify-end">
            <div class="text-center w-72 space-y-1">
                <p>ว่าที่ ร.ต...................................................ผู้รับการประเมิน</p>
                <p class="font-bold">(วชิรวิทย์  แกล้วการไถ)</p>
                <p class="text-[12pt]">ผู้ช่วยนักวิชาการคอมพิวเตอร์</p>
            </div>
        </div>

        <div class="text-center text-xs text-slate-400 mt-6 border-t pt-2 no-print">
            - หน้า 5 / 8 -
        </div>
    </div>

    <!-- PAGE 6: เอกสารแนบท้าย ข้อ 3 -->
    <div class="a4-sheet bg-white text-slate-900 shadow-xl mx-auto p-6 sm:p-10 rounded-2xl border border-slate-300 relative font-sarabun text-[13.5pt] leading-relaxed page-break">
        <div class="flex justify-between items-center mb-3">
            <span class="text-xs font-mono text-slate-400">-3-</span>
            <div class="text-center font-bold flex-1">
                <p class="text-[15pt]">***เอกสารแนบท้าย (แบบรายงานผลสัมฤทธิ์ของงาน)*****</p>
                <p class="text-[15pt] font-black mt-1">ข้อ 3. การสนับสนุนงานด้านไอทีและสื่อการเรียนการสอน (จำนวน 24 ครั้ง)</p>
            </div>
            <span class="w-6"></span>
        </div>

        <div class="overflow-x-auto my-4">
            <table class="official-table w-full border-collapse border border-black text-[12pt]">
                <thead>
                    <tr class="text-center font-bold bg-slate-50">
                        <th class="border border-black p-2.5 w-1/3">เชิงปริมาณ</th>
                        <th class="border border-black p-2.5 w-1/3">เชิงคุณภาพ</th>
                        <th class="border border-black p-2.5 w-1/3">เชิงประโยชน์</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black p-3 align-top">
                            <p class="font-bold underline mb-1">มีสนับสนุนการใช้โปรแกรมหรือแพลตฟอร์มดิจิทัล และอุปกรณ์ต่อพ่วงต่าง ๆ คิดเป็นร้อยละ 80</p>
                            <p class="font-semibold text-slate-700">พิจารณาจากผลการปฏิบัติงานจริงในระบบ (รอบ <?= esc($selectedRound) ?>/<?= esc($selectedFY) ?> รวมกว่า 40+ ภารกิจ):</p>
                            <div class="space-y-1.5 mt-1 text-[11.5pt] leading-relaxed">
                                <p><strong>1. บริการซ่อมบำรุงและแก้ไขปัญหาอุปกรณ์ต่อพ่วงและเครื่องพิมพ์ (Printer Support):</strong></p>
                                <ul class="list-disc pl-5 space-y-0.5">
                                    <li>แก้ไขปัญหาหัวพิมพ์ตัน หมึกไม่ออก กระดาษติด และระบบดึงกระดาษขัดข้อง ณ ห้องแนะแนว, ห้องพัสดุ, ห้องการเงิน, ห้องพักครูวิทยาศาสตร์, ห้องพักครูสังคมศึกษา และห้องพักครูคณิตศาสตร์</li>
                                    <li>ทำความสะอาดระบบซับหมึก ไล่ฟองอากาศในระบบแท็งก์หมึก และตั้งค่าไดรเวอร์เครื่องพิมพ์ผ่านระบบเครือข่าย</li>
                                </ul>
                                <p><strong>2. การติดตั้ง บำรุงรักษาคอมพิวเตอร์ โปรเจกเตอร์ และสื่อการเรียนรู้ในห้องเรียน:</strong></p>
                                <ul class="list-disc pl-5 space-y-0.5">
                                    <li>ติดตั้งเครื่องโปรเจกเตอร์ใหม่และเปลี่ยนหลอดภาพ ณ ห้องเรียน 317, 323, 312 และอาคาร 3</li>
                                    <li>ตรวจสอบระบบสายสัญญาณ HDMI/VGA และปลั๊กไฟประจำห้องเรียนเพื่อความปลอดภัยในการจัดการเรียนการสอน</li>
                                    <li>ตรวจเช็คเครื่องคอมพิวเตอร์ตั้งโต๊ะและโน้ตบุ๊ก ลงระบบปฏิบัติการ Windows โปรแกรม Microsoft Office และโปรแกรมสื่อการสอนให้แก่ครูผู้สอน</li>
                                </ul>
                                <p><strong>3. การเป็นผู้ฝึกสอน (Coach) และควบคุมทีมส่งเสริมศักยภาพนักเรียนด้านกีฬาอีสปอร์ตและดิจิทัล:</strong></p>
                                <ul class="list-disc pl-5 space-y-0.5">
                                    <li>เป็นผู้ฝึกสอนพานักเรียนชมรมกีฬาอีสปอร์ต เข้าร่วมการแข่งขันอีสปอร์ตชิงถ้วยพระราชทาน ณ มหาวิทยาลัยเจ้าพระยา</li>
                                    <li>พานักเรียนเข้าร่วมการแข่งขัน อีสปอร์ต มาสเตอร์ยังแชมเปี้ยนส์ 2569 ณ โรงเรียนนครสวรรค์</li>
                                    <li>พานักเรียนเข้าร่วมการแข่งขันกีฬาชิงชนะเลิศแห่งจังหวัดนครสวรรค์ ณ โรงเรียนเทศบาลวัดจอมคีรีนาคพรต (ท.6)</li>
                                    <li>จัดการแข่งขันกีฬาอีสปอร์ต "บึงบอระเพ็ดลีก 2026" ในงานสัปดาห์วันวิทยาศาสตร์ 2569 และการแข่งขันกีฬาอีสปอร์ตในงานกีฬาสี</li>
                                </ul>
                                <p><strong>4. การสนับสนุนภารกิจพิเศษและงานคำสั่ง อบจ.นครสวรรค์:</strong></p>
                                <ul class="list-disc pl-5 space-y-0.5">
                                    <li>สนับสนุนฝ่ายไอทีและระบบเสียงในงาน "อบจ.นครสวรรค์เกมส์" ครั้งที่ 1 ณ โรงเรียนสตรีนครสวรรค์</li>
                                    <li>ตรวจสอบเอกสารรับรายงานตัวนักเรียนใหม่ และควบคุมระบบการสอบคัดเลือกนักเรียน ณ ห้องคอมพิวเตอร์ 4</li>
                                </ul>
                            </div>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-bold underline mb-1">นำเทคโนโลยีไปใช้ในการเรียนการสอนได้อย่างมีประสิทธิภาพ ร้อยละ 80</p>
                            <p class="font-semibold text-slate-700">พิจารณาจากผลการปฏิบัติงานจริงในระบบ:</p>
                            <div class="space-y-1.5 mt-1 text-[11.5pt] leading-relaxed">
                                <p><strong>1.</strong> ให้บริการซ่อมบำรุงหน้างาน (On-site Support) ได้อย่างรวดเร็วและตรงจุด อุปกรณ์กลับมาพร้อมใช้งานได้ตามปกติภายในเวลาอันสั้น</p>
                                <p><strong>2.</strong> อุปกรณ์โสตทัศนูปกรณ์และโปรเจกเตอร์ในห้องเรียนมีความพร้อมใช้งาน คมชัด เอื้อต่อการจัดกิจกรรมการเรียนรู้แบบ Active Learning</p>
                                <p><strong>3.</strong> คณะครูและบุคลากรมีความมั่นใจในการใช้อุปกรณ์เทคโนโลยี มีโปรแกรมและอุปกรณ์สนับสนุนการสอนที่พร้อมใช้งานอย่างต่อเนื่อง</p>
                                <p><strong>4.</strong> นักเรียนได้รับการพัฒนาทักษะทางเทคโนโลยี ทั้งด้านกีฬาอีสปอร์ต การทำงานเป็นทีม และเข้าร่วมการแข่งขันสร้างชื่อเสียงให้แก่สถานศึกษา</p>
                            </div>
                        </td>
                        <td class="border border-black p-3 align-top">
                            <p class="font-bold underline mb-1">ใช้เทคโนโลยีในการสอนได้อย่างมีประสิทธิภาพและลดภาระงาน ร้อยละ 80</p>
                            <p class="font-semibold text-slate-700">พิจารณาจากผลการปฏิบัติงานจริงในระบบ:</p>
                            <div class="space-y-1.5 mt-1 text-[11.5pt] leading-relaxed">
                                <p><strong>1.</strong> ช่วยลดภาระของครูในการแก้ปัญหาอุปกรณ์ไอทีด้วยตนเอง ทำให้มีเวลาทุ่มเทให้กับการสอนและดูแลนักเรียนได้อย่างเต็มที่</p>
                                <p><strong>2.</strong> ช่วยประหยัดงบประมาณของโรงเรียน โดยการซ่อมบำรุงและแก้ไขปัญหาเบื้องต้นได้เองในส่วนงาน ลดค่าใช้จ่ายจ้างช่างภายนอก</p>
                                <p><strong>3.</strong> เปิดโอกาสและส่งเสริมให้นักเรียนได้พัฒนาทักษะดิจิทัลสู่เวทีการแข่งขันระดับภูมิภาคและระดับประเทศ สร้างชื่อเสียงให้แก่โรงเรียน</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Signature Block -->
        <div class="mt-8 sm:mt-10 flex justify-end">
            <div class="text-center w-72 space-y-1">
                <p>ว่าที่ ร.ต...................................................ผู้รับการประเมิน</p>
                <p class="font-bold">(วชิรวิทย์  แกล้วการไถ)</p>
                <p class="text-[12pt]">ผู้ช่วยนักวิชาการคอมพิวเตอร์</p>
            </div>
        </div>

        <div class="text-center text-xs text-slate-400 mt-6 border-t pt-2 no-print">
            - หน้า 6 / 8 -
        </div>
    </div>

    <!-- PAGE 7: แบบบันทึกพฤติกรรม (สมรรถนะหลัก) -->
    <div class="a4-sheet bg-white text-slate-900 shadow-xl mx-auto p-6 sm:p-10 rounded-2xl border border-slate-300 relative font-sarabun text-[13.5pt] leading-relaxed page-break">
        <div class="text-center font-bold mb-4">
            <p class="text-[16pt]">(แบบรายงานตนเอง)</p>
            <p class="text-[18pt] font-black mt-1">แบบบันทึกพฤติกรรม (สมรรถนะหลัก)</p>
        </div>

        <div class="overflow-x-auto my-4">
            <table class="official-table w-full border-collapse border border-black text-[12.5pt]">
                <thead>
                    <tr class="text-center font-bold bg-slate-50">
                        <th class="border border-black p-2.5 w-[22%] align-middle">สมรรถนะหลัก</th>
                        <th class="border border-black p-2 w-[11%] align-middle">ระดับที่คาดหวัง / ต้องการ</th>
                        <th class="border border-black p-2 w-[11%] align-middle">ระดับคะแนนที่สามารถทำได้</th>
                        <th class="border border-black p-2 w-[50%] align-middle">พฤติกรรมที่แสดงออก<br><span class="text-[11pt] font-normal">(ระบุเหตุการณ์/พฤติกรรม/กิจกรรมที่ดำเนินการ)</span></th>
                        <th class="border border-black p-2 w-[6%] align-middle">หมายเหตุ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coreCompetencies as $c): ?>
                    <tr>
                        <td class="border border-black p-3 align-top font-bold">
                            <?= esc($c['name']) ?>
                        </td>
                        <td class="border border-black p-3 align-middle text-center font-bold text-[14pt]">
                            <?= esc($c['expected_level']) ?>
                        </td>
                        <td class="border border-black p-3 align-middle text-center font-bold text-[14pt] text-indigo-900">
                            <?= esc($c['achieved_score']) ?>
                        </td>
                        <td class="border border-black p-3 align-top text-[12pt] leading-relaxed">
                            <?= esc($c['behavior']) ?>
                        </td>
                        <td class="border border-black p-3 align-middle text-center text-xs">
                            -
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Signature Block -->
        <div class="mt-8 sm:mt-10 flex justify-end">
            <div class="text-center w-72 space-y-1">
                <p>ว่าที่ ร.ต...................................................ผู้รับการประเมิน</p>
                <p class="font-bold">(วชิรวิทย์  แกล้วการไถ)</p>
                <p class="text-[12pt]">ผู้ช่วยนักวิชาการคอมพิวเตอร์</p>
            </div>
        </div>

        <div class="text-center text-xs text-slate-400 mt-6 border-t pt-2 no-print">
            - หน้า 7 / 8 -
        </div>
    </div>

    <!-- PAGE 8: แบบบันทึกพฤติกรรม (สมรรถนะประจำสายงาน) -->
    <div class="a4-sheet bg-white text-slate-900 shadow-xl mx-auto p-6 sm:p-10 rounded-2xl border border-slate-300 relative font-sarabun text-[13.5pt] leading-relaxed page-break">
        <div class="text-center font-bold mb-4">
            <p class="text-[16pt]">(แบบรายงานตนเอง)</p>
            <p class="text-[18pt] font-black mt-1">แบบบันทึกพฤติกรรม (สมรรถนะประจำสายงาน)</p>
        </div>

        <div class="overflow-x-auto my-4">
            <table class="official-table w-full border-collapse border border-black text-[12.5pt]">
                <thead>
                    <tr class="text-center font-bold bg-slate-50">
                        <th class="border border-black p-2.5 w-[22%] align-middle">สมรรถนะประจำสายงาน</th>
                        <th class="border border-black p-2 w-[11%] align-middle">ระดับที่คาดหวัง / ต้องการ</th>
                        <th class="border border-black p-2 w-[11%] align-middle">ระดับคะแนนที่สามารถทำได้</th>
                        <th class="border border-black p-2 w-[50%] align-middle">พฤติกรรมที่แสดงออก<br><span class="text-[11pt] font-normal">(ระบุเหตุการณ์/พฤติกรรม/กิจกรรมที่ดำเนินการ)</span></th>
                        <th class="border border-black p-2 w-[6%] align-middle">หมายเหตุ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($functionalCompetencies as $fc): ?>
                    <tr>
                        <td class="border border-black p-3 align-top font-bold">
                            <?= esc($fc['name']) ?>
                        </td>
                        <td class="border border-black p-3 align-middle text-center font-bold text-[14pt]">
                            <?= esc($fc['expected_level']) ?>
                        </td>
                        <td class="border border-black p-3 align-middle text-center font-bold text-[14pt] text-indigo-900">
                            <?= esc($fc['achieved_score']) ?>
                        </td>
                        <td class="border border-black p-3 align-top text-[12pt] leading-relaxed">
                            <?= esc($fc['behavior']) ?>
                        </td>
                        <td class="border border-black p-3 align-middle text-center text-xs">
                            -
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Signature Block -->
        <div class="mt-8 sm:mt-10 flex justify-end">
            <div class="text-center w-72 space-y-1">
                <p>ว่าที่ ร.ต...................................................ผู้รับการประเมิน</p>
                <p class="font-bold">(วชิรวิทย์  แกล้วการไถ)</p>
                <p class="text-[12pt]">ผู้ช่วยนักวิชาการคอมพิวเตอร์</p>
            </div>
        </div>

        <div class="text-center text-xs text-slate-400 mt-6 border-t pt-2 no-print">
            - หน้า 8 / 8 -
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- VIEW MODE 2: MODERN INTERACTIVE DASHBOARD -->
<!-- ========================================== -->
<div id="view-section-modern" class="hidden space-y-8">
    <!-- Notice & Info Callout -->
    <div class="p-5 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                <i data-lucide="award" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-800 dark:text-white">
                    สรุปผลการปฏิบัติงานตามเป้าหมาย (รอบ <?= esc($selectedRound) ?>) ปีงบประมาณ <?= esc($selectedFY) ?>
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                    เปรียบเทียบผลงานจริงที่ปฏิบัติและบันทึกในระบบ IT Support Portal กับเป้าหมายข้อตกลงผลสัมฤทธิ์ของงาน (3 ภารกิจหลัก) ทุกงานผ่านเกณฑ์เกินกว่าเป้าหมายที่กำหนด คิดเป็นร้อยละ 100+
                </p>
            </div>
        </div>
        <button type="button" onclick="switchReportTab('official')" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-indigo-600 dark:text-indigo-400 text-xs font-black border border-indigo-200 dark:border-indigo-800 shadow-sm transition-all shrink-0 flex items-center gap-1.5">
            <i data-lucide="eye" class="w-4 h-4"></i>
            <span>ดูหน้าแบบฟอร์ม A4</span>
        </button>
    </div>

    <!-- 3 Core Projects Performance Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <?php foreach ($workAchievements as $item): ?>
        <div class="glass-card bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between hover:shadow-lg transition-all">
            <div>
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-<?= esc($item['color']) ?>-100 dark:bg-<?= esc($item['color']) ?>-950/60 text-<?= esc($item['color']) ?>-600 dark:text-<?= esc($item['color']) ?>-400 flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="<?= esc($item['icon']) ?>" class="w-6 h-6"></i>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700">
                        สำเร็จ <?= esc($item['percent']) ?>%
                    </span>
                </div>

                <h3 class="text-base font-black text-slate-800 dark:text-white line-clamp-2 mb-2">
                    <?= esc($item['short_title']) ?>
                </h3>
                <p class="text-xs text-slate-550 dark:text-slate-400 line-clamp-2 mb-4">
                    <?= esc($item['title']) ?>
                </p>

                <!-- Progress Bar -->
                <div class="space-y-1.5 mb-5">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-500">เป้าหมาย: <?= esc($item['target']) ?> ครั้ง</span>
                        <span class="text-emerald-600 dark:text-emerald-400 font-black">ผลงาน: <?= esc($item['actual']) ?> ครั้ง</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full" style="width: 100%;"></div>
                    </div>
                </div>

                <!-- 3 Dimension Badges -->
                <div class="space-y-2.5 text-xs">
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                            <span>เชิงปริมาณ</span>
                            <span class="text-indigo-600 dark:text-indigo-400"><?= esc($item['quantitative']['score']) ?> คะแนน</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400"><?= esc($item['quantitative']['target_desc']) ?></p>
                    </div>

                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                            <span>เชิงคุณภาพ</span>
                            <span class="text-indigo-600 dark:text-indigo-400"><?= esc($item['qualitative']['score']) ?> คะแนน</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400"><?= esc($item['qualitative']['target_desc']) ?></p>
                    </div>

                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                            <span>เชิงประโยชน์</span>
                            <span class="text-emerald-600 dark:text-emerald-400"><?= esc($item['beneficial']['score']) ?> คะแนน (ดีเลิศ)</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400"><?= esc($item['beneficial']['target_desc']) ?></p>
                    </div>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                <span class="text-[11px] text-slate-400">หลักฐานในระบบ</span>
                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 flex items-center gap-1">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-500"></i>
                    เอกสารแนบท้ายครบถ้วน
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Core & Functional Competency Summary Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- สมรรถนะหลัก 5 ด้าน -->
        <div class="glass-card bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                    <i data-lucide="star" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-800 dark:text-white">สมรรถนะหลัก (Core Competencies)</h3>
                    <p class="text-xs text-slate-500">5 พฤติกรรมหลัก ระดับคะแนนที่ทำได้: 2 คะแนนเต็ม</p>
                </div>
            </div>

            <div class="space-y-3">
                <?php foreach ($coreCompetencies as $c): ?>
                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 flex items-start justify-between gap-3">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-800 dark:text-white block"><?= esc($c['name']) ?></span>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed"><?= esc($c['behavior']) ?></p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300">
                            ระดับ <?= esc($c['achieved_score']) ?>
                        </span>
                        <span class="block text-[9px] text-slate-400 mt-1">คาดหวัง <?= esc($c['expected_level']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- สมรรถนะประจำสายงาน 3 ด้าน -->
        <div class="glass-card bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-800 dark:text-white">สมรรถนะประจำสายงาน (Functional Competencies)</h3>
                    <p class="text-xs text-slate-500">3 พฤติกรรมสายวิชาการคอมพิวเตอร์ ระดับคะแนนที่ทำได้: 2 คะแนน</p>
                </div>
            </div>

            <div class="space-y-3">
                <?php foreach ($functionalCompetencies as $fc): ?>
                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 flex items-start justify-between gap-3">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-800 dark:text-white block"><?= esc($fc['name']) ?></span>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed"><?= esc($fc['behavior']) ?></p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300">
                            ระดับ <?= esc($fc['achieved_score']) ?>
                        </span>
                        <span class="block text-[9px] text-slate-400 mt-1">คาดหวัง <?= esc($fc['expected_level']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Evidence Photos from Real IT Support Logs -->
    <?php if (!empty($evidenceLogs)): ?>
    <div class="glass-card bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex items-center justify-between gap-4 mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-100 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                    <i data-lucide="camera" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-800 dark:text-white">ภาพถ่ายหลักฐานการปฏิบัติงานจริงในรอบที่ <?= esc($selectedRound) ?></h3>
                    <p class="text-xs text-slate-500">ดึงข้อมูลจริงจากระบบ Tb_It_Support_Logs ประกอบเอกสารแนบท้าย</p>
                </div>
            </div>
            <a href="<?= base_url('itsupport') ?>" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                <span>ดูไทม์ไลน์งานทั้งหมด</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <?php foreach ($evidenceLogs as $elog): 
                $imgs = json_decode($elog['its_images'] ?? '[]', true) ?: [];
                $firstImg = !empty($imgs[0]) ? $imgs[0] : null;
                if (!$firstImg) continue;
            ?>
            <a href="<?= base_url('itsupport/view/' . $elog['its_id']) ?>" target="_blank" class="group relative rounded-2xl overflow-hidden aspect-square border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900 block hover:shadow-lg transition-all">
                <img src="<?= base_url('uploads/itsupport/' . $firstImg) ?>" 
                     alt="<?= esc($elog['its_task'] ?? 'งานบริการ') ?>" 
                     loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-2.5 flex flex-col justify-end text-white text-[10px]">
                    <span class="font-bold line-clamp-1"><?= esc($elog['its_task'] ?? 'งานบริการ') ?></span>
                    <span class="text-slate-300 text-[9px]"><?= date('d/m/Y', strtotime($elog['its_date'])) ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
/* Font Sarabun Styling for Official Document */
.font-sarabun {
    font-family: 'Sarabun', 'TH Sarabun New', sans-serif !important;
}

/* A4 Sheet Container Styling - Standard A4 Landscape */
.a4-sheet {
    width: 100%;
    max-width: 1140px; /* A4 Landscape standard ratio (297mm width) */
    background-color: #ffffff !important;
    color: #0f172a !important;
    box-sizing: border-box;
    box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.12), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border: 1px solid #cbd5e1;
}

/* Ensure paper contents maintain clean pure white styling regardless of dark mode */
.a4-sheet,
.a4-sheet p,
.a4-sheet span,
.a4-sheet div,
.a4-sheet li,
.a4-sheet td,
.a4-sheet th,
.a4-sheet strong {
    color: #0f172a;
}

.official-table {
    width: 100%;
    background-color: #ffffff !important;
    color: #000000 !important;
    border-collapse: collapse;
}

.official-table thead {
    display: table-header-group;
}

.official-table th {
    border: 1px solid #000000 !important;
    background-color: #f8fafc !important;
    color: #000000 !important;
}

.official-table td {
    border: 1px solid #000000 !important;
    background-color: #ffffff !important;
    color: #000000 !important;
}

/* Print Rules - Standard A4 Landscape */
@media print {
    body, html {
        background: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
        margin: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    #sidebar-menu,
    header,
    nav,
    .no-print,
    #view-section-modern,
    .top-header-bar,
    footer {
        display: none !important;
    }
    
    #view-section-official {
        display: block !important;
        margin: 0 !important;
        padding: 0 !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    .a4-sheet {
        background-color: #ffffff !important;
        color: #000000 !important;
        box-shadow: none !important;
        border: none !important;
        padding: 6mm 10mm !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        page-break-after: always;
        break-after: page;
    }
    
    .official-table th {
        background-color: #f1f5f9 !important;
        border: 1px solid #000000 !important;
    }
    
    .official-table td {
        background-color: #ffffff !important;
        border: 1px solid #000000 !important;
    }
    
    .page-break {
        page-break-before: always;
        break-before: page;
    }

    @page {
        size: A4 landscape;
        margin: 8mm 10mm;
    }
}
</style>

<script>
function switchReportTab(mode) {
    const btnOfficial = document.getElementById('btn-view-official');
    const btnModern = document.getElementById('btn-view-modern');
    const secOfficial = document.getElementById('view-section-official');
    const secModern = document.getElementById('view-section-modern');

    if (mode === 'official') {
        secOfficial.classList.remove('hidden');
        secModern.classList.add('hidden');
        
        btnOfficial.className = "px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm";
        btnModern.className = "px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-indigo-600";
    } else {
        secOfficial.classList.add('hidden');
        secModern.classList.remove('hidden');
        
        btnModern.className = "px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm";
        btnOfficial.className = "px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-indigo-600";
    }

    // Refresh lucide icons in toggled view
    if (window.lucide) {
        lucide.createIcons();
    }
}

function printOfficialDoc() {
    // Switch to official view first before printing
    switchReportTab('official');
    setTimeout(() => {
        window.print();
    }, 200);
}
</script>
<?= $this->endSection() ?>
