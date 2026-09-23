<?= $this->extend('itsupport/layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Navigation & Action Bar -->
<div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 mb-6">
    <!-- Left: Title & Badge -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('itsupport/mou') ?>" class="w-11 h-11 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-indigo-600 hover:border-indigo-300 dark:hover:border-indigo-600 transition-all shadow-sm flex items-center justify-center shrink-0" title="กลับหน้า MOU">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-700 flex items-center gap-1">
                    <i data-lucide="file-check-2" class="w-3 h-3"></i>
                    Official Contract Renewal
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700 flex items-center gap-1">
                    <i data-lucide="user-check" class="w-3 h-3"></i>
                    เฉพาะรายบุคคล: <?= esc($contractForm['staff_data']['prefix'] . ' ' . $contractForm['staff_data']['fullname']) ?>
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-700">
                    รอบสัญญา <?= esc($contractForm['staff_data']['fiscal_years']) ?>
                </span>
            </div>
            <h1 class="text-lg sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-1">
                แบบแจ้งความประสงค์การต่อสัญญาจ้างพนักงานจ้างตามภารกิจ
            </h1>
        </div>
    </div>

    <!-- Right: View Mode Toggle & Print Actions -->
    <div class="flex items-center gap-2.5 flex-wrap">
        <!-- Switch View Mode Buttons -->
        <div class="p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center border border-slate-200 dark:border-slate-700 shadow-sm">
            <button type="button" id="btn-view-official" onclick="switchTab('official')" class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                <span>แบบฟอร์มกระดาษ A4 สีขาว</span>
            </button>
            <button type="button" id="btn-view-modern" onclick="switchTab('modern')" class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-indigo-600">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
                <span>ภาพรวมระบบ</span>
            </button>
        </div>

        <!-- Self-Report Link -->
        <a href="<?= base_url('itsupport/self-report') ?>" class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-black text-xs shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
            <span>แบบรายงานตนเอง (รอบ 2)</span>
        </a>

        <!-- Print Official Form Button -->
        <button type="button" onclick="printOfficialDoc()" class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white font-black text-xs shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>พิมพ์แบบฟอร์มราชการ (แนวนอน A4)</span>
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
                <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-emerald-500 border-2 border-slate-900 flex items-center justify-center text-white shadow-md" title="สถานะ: พร้อมปฏิบัติหน้าที่">
                    <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                </div>
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        <?= esc($contractForm['staff_data']['staff_type']) ?>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-500/20 text-rose-300 border border-rose-500/30">
                        สัญญาจ้าง 3 ปี (<?= esc($contractForm['staff_data']['fiscal_years']) ?>)
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white">
                    <?= esc($contractForm['staff_data']['prefix'] . ' ' . $contractForm['staff_data']['fullname']) ?>
                </h2>
                <p class="text-sm font-bold text-indigo-300">
                    ตำแหน่ง <?= esc($contractForm['staff_data']['position']) ?>
                </p>
                <p class="text-xs text-slate-300 flex items-center justify-center sm:justify-start gap-1.5">
                    <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span><?= esc($contractForm['staff_data']['department']) ?></span>
                </p>
                <p class="text-xs text-emerald-400 font-semibold flex items-center justify-center sm:justify-start gap-1.5">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    <span>ระยะเวลาการจ้าง: <strong><?= esc($contractForm['staff_data']['contract_period']) ?></strong></span>
                </p>
            </div>
        </div>

        <!-- Quick Summary Metrics -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 w-full lg:w-auto shrink-0">
            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm text-center">
                <span class="text-[10px] font-black uppercase tracking-wider text-indigo-300 block mb-1">งานในระบบสะสม</span>
                <span class="text-2xl font-black text-white"><?= number_format($totalLogs) ?></span>
                <span class="text-[10px] text-slate-400 block">รายการ</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm text-center">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-300 block mb-1">ขอบเขตหน้าที่</span>
                <span class="text-2xl font-black text-white"><?= count($contractForm['responsibilities']) ?></span>
                <span class="text-[10px] text-slate-400 block">ข้อภารกิจ</span>
            </div>
            <div class="col-span-2 sm:col-span-1 p-3.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm text-center">
                <span class="text-[10px] font-black uppercase tracking-wider text-rose-300 block mb-1">นโยบาย/โครงการ</span>
                <span class="text-2xl font-black text-white"><?= count($contractForm['policies']) ?></span>
                <span class="text-[10px] text-slate-400 block">แผนงานหลัก</span>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- VIEW MODE 1: MODERN INTERACTIVE DASHBOARD -->
<!-- ========================================== -->
<div id="view-section-modern" class="hidden space-y-8">
    <!-- Notice & Info Callout -->
    <div class="p-4 sm:p-5 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                <i data-lucide="info" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-800 dark:text-white">
                    เอกสารประกอบการจัดทำความประสงค์ต่อสัญญาจ้างพนักงานจ้างตามภารกิจ
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5 leading-relaxed">
                    อ้างอิงตามบันทึกข้อความกองการเจ้าหน้าที่ ที่ นว 51029/ว 1225 ลงวันที่ 15 สิงหาคม 2568 จัดทำสำหรับตำแหน่ง <strong><?= esc($contractForm['staff_data']['position']) ?></strong> (ว่าที่ ร.ต. วชิรวิทย์ แกล้วการไถ) โดยเฉพาะ
                </p>
            </div>
        </div>
        <button type="button" onclick="switchTab('official')" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-indigo-600 dark:text-indigo-400 text-xs font-black border border-indigo-200 dark:border-indigo-800 shadow-sm transition-all shrink-0 flex items-center gap-1.5">
            <i data-lucide="eye" class="w-4 h-4"></i>
            <span>ดูหน้าแบบฟอร์มราชการ</span>
        </button>
    </div>

    <!-- 3 Core Sections Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 1. นโยบาย / แผนงาน / โครงการ -->
        <div class="glass-card bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-blue-600 dark:text-blue-400">คอลัมน์ที่ 2</span>
                        <h3 class="text-base font-black text-slate-800 dark:text-white">นโยบาย/แผนงาน/โครงการ</h3>
                    </div>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    งานตามภารกิจหลักของกองการศึกษาฯ และโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์
                </p>
                <div class="space-y-2.5">
                    <?php foreach ($contractForm['policies'] as $pIndex => $policy): ?>
                        <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-900/70 border border-slate-100 dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-lg bg-blue-600 text-white text-[10px] font-black flex items-center justify-center shrink-0 mt-0.5">
                                <?= $pIndex + 1 ?>
                            </span>
                            <span class="leading-relaxed"><?= esc(preg_replace('/^\d+\.\s*/', '', $policy)) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-slate-400">
                <span>จำนวน 5 โครงการ/แผนงาน</span>
                <span class="text-blue-600 font-bold">100% สอดคล้องภารกิจ</span>
            </div>
        </div>

        <!-- 2. ความจำเป็น / วัตถุประสงค์ -->
        <div class="glass-card bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <i data-lucide="target" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-amber-600 dark:text-amber-400">คอลัมน์ที่ 3</span>
                        <h3 class="text-base font-black text-slate-800 dark:text-white">ความจำเป็นและวัตถุประสงค์</h3>
                    </div>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    เหตุผลความจำเป็นในการจ้างต่อเพื่อความต่อเนื่องในการปฏิบัติราชการ
                </p>

                <div class="p-4 rounded-2xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-900/40 text-xs text-slate-700 dark:text-slate-200 leading-relaxed space-y-3">
                    <p>
                        <?= esc($contractForm['necessity_purpose']) ?>
                    </p>
                </div>

                <div class="mt-4 space-y-2 text-xs">
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <span>รองรับระบบสารสนเทศ 16+ แพลตฟอร์มที่ใช้งานจริง</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <span>ดูแลเครือข่ายความเร็วสูงและระบบคลาวด์/เซิร์ฟเวอร์</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <span>สนับสนุนงานประชุมสภา อบจ. พิธีการ และ Live Streaming</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-slate-400">
                <span>ความจำเป็นระดับ: <strong>จำเป็นยิ่ง</strong></span>
                <span class="text-amber-600 font-bold">ต่อเนื่อง 3 ปี</span>
            </div>
        </div>

        <!-- 3. ข้อมูลพนักงานและระยะเวลาการจ้าง -->
        <div class="glass-card bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                        <i data-lucide="badge-check" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-purple-600 dark:text-purple-400">คอลัมน์ที่ 5 & 6</span>
                        <h3 class="text-base font-black text-slate-800 dark:text-white">ผู้รับผิดชอบ & ระยะเวลา</h3>
                    </div>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    ข้อมูลผู้รับผิดชอบตามกรอบอัตรากำลังและสัญญาจ้าง
                </p>

                <div class="p-4 rounded-2xl bg-purple-50/50 dark:bg-purple-950/20 border border-purple-200/60 dark:border-purple-900/40 space-y-3">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">พนักงานที่รับผิดชอบ</span>
                        <p class="text-sm font-black text-slate-800 dark:text-white mt-0.5">
                            <?= esc($contractForm['responsible_officer']['name']) ?>
                        </p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 font-bold">
                            <?= esc($contractForm['responsible_officer']['position']) ?>
                        </p>
                    </div>

                    <div class="pt-2 border-t border-purple-100 dark:border-purple-900/40">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">ระยะเวลาการจ้าง</span>
                        <p class="text-sm font-black text-emerald-600 dark:text-emerald-400 mt-0.5 flex items-center gap-1.5">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span><?= esc($contractForm['period']) ?></span>
                        </p>
                        <span class="text-[10px] text-slate-500">รอบปีงบประมาณ <?= esc($contractForm['staff_data']['fiscal_years']) ?> (3 ปีเต็ม)</span>
                    </div>

                    <div class="pt-2 border-t border-purple-100 dark:border-purple-900/40">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">ผู้รับรองข้อมูล (ผู้อนุมัติ)</span>
                        <p class="text-xs font-bold text-slate-800 dark:text-white mt-0.5">
                            <?= esc($contractForm['memo']['approver_name']) ?>
                        </p>
                        <p class="text-[11px] text-slate-500">
                            <?= esc($contractForm['memo']['approver_position']) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-slate-400">
                <span>สถานะเอกสาร: <strong>พร้อมเสนอพิจารณา</strong></span>
                <span class="text-emerald-600 font-bold">ครบถ้วนสมบูรณ์</span>
            </div>
        </div>
    </div>

    <!-- หน้าที่ความรับผิดชอบ 12 ข้อแบบเต็ม (Full Responsibilities Matrix) -->
    <div class="glass-card bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                    <i data-lucide="list-checks" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400">คอลัมน์ที่ 4 (หน้าที่ความรับผิดชอบ)</span>
                    <h3 class="text-lg font-black text-slate-800 dark:text-white">
                        หน้าที่ความรับผิดชอบของผู้ช่วยนักวิชาการคอมพิวเตอร์ (12 ด้านงาน)
                    </h3>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300">
                <?= count($contractForm['responsibilities']) ?> รายการครบถ้วน
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
            <?php foreach ($contractForm['responsibilities'] as $idx => $resp): ?>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800 hover:border-indigo-200 dark:hover:border-indigo-800 transition-all flex items-start gap-3 group">
                    <div class="w-7 h-7 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 group-hover:bg-indigo-600 group-hover:text-white text-xs font-black flex items-center justify-center shrink-0 transition-colors mt-0.5">
                        <?= $idx + 1 ?>
                    </div>
                    <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                        <?= esc($resp) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ============================================================= -->
<!-- ============================================================= -->
<!-- VIEW MODE 2: OFFICIAL GOVERNMENT DOCUMENT VIEW (LANDSCAPE A4) -->
<!-- ============================================================= -->
<div id="view-section-official" class="space-y-6">
    <!-- Notice & Controls Bar -->
    <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
        <div class="flex items-center gap-2.5 text-slate-700 dark:text-slate-300">
            <span class="px-2.5 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-black text-[11px] flex items-center gap-1.5 border border-emerald-300 dark:border-emerald-800">
                <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                กระดาษ A4 สีขาว แนวนอน (Landscape)
            </span>
            <span class="text-slate-500 dark:text-slate-400">มาตราส่วนเอกสารมาตรฐาน 297 x 210 มม. • ตาราง 5 คอลัมน์</span>
        </div>
        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white font-black text-xs shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>พิมพ์เอกสารนี้ (Print Landscape A4)</span>
        </button>
    </div>

    <!-- AUTHENTIC DESK CANVAS (ให้กระดาษ A4 สีขาวเด่นชัดเสมือนวางบนโต๊ะทำงาน) -->
    <div class="w-full bg-slate-200/90 dark:bg-slate-950 p-2 sm:p-6 md:p-8 rounded-3xl border border-slate-300 dark:border-slate-800 flex justify-center overflow-x-auto shadow-inner">
        <!-- THE CRISP WHITE A4 PAPER SHEET (กระดาษ A4 สีขาว แนวนอน) -->
        <div id="official-doc-print-area" class="a4-paper-sheet bg-white text-black p-8 sm:p-12 w-full max-w-[297mm] min-h-[210mm] shadow-2xl mx-auto border border-slate-300/80 font-serif leading-relaxed text-[11.5pt] shrink-0" style="background-color: #ffffff !important; color: #000000 !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.08); border-radius: 2px;">

        <!-- ====================================================================== -->
        <!-- ตารางแบบแจ้งความประสงค์การต่อสัญญาจ้าง (5 คอลัมน์มาตรฐานราชการ แนวนอน) -->
        <!-- ====================================================================== -->
        <div class="official-page" style="font-family: 'Sarabun', 'TH Sarabun New', sans-serif;">
            <!-- Document Table Header -->
            <div class="text-center mb-5 space-y-1">
                <h3 class="text-base sm:text-xl font-bold text-black tracking-tight">
                    <?= esc($contractForm['table_header']['title']) ?>
                </h3>
                <h4 class="text-sm sm:text-base font-bold text-black">
                    <?= esc($contractForm['table_header']['org']) ?>
                </h4>
                <h4 class="text-sm sm:text-base font-bold text-black">
                    <?= esc($contractForm['table_header']['affiliation']) ?>
                </h4>
            </div>

            <!-- Official 5-Column Table (Landscape Optimized) -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-black text-xs sm:text-[13px] text-black" style="font-family: 'Sarabun', 'TH Sarabun New', sans-serif;">
                    <thead>
                        <tr class="bg-slate-100 text-center font-bold">
                            <th class="border border-black p-2.5 w-[6%]">ลำดับที่</th>
                            <th class="border border-black p-2.5 w-[21%]">นโยบาย/แผนงาน/โครงการ</th>
                            <th class="border border-black p-2.5 w-[20%]">ความจำเป็น/วัตถุประสงค์</th>
                            <th class="border border-black p-2.5 w-[33%]">หน้าที่ความรับผิดชอบ</th>
                            <th class="border border-black p-2.5 w-[12%]">พนักงานที่รับผิดชอบ</th>
                            <th class="border border-black p-2.5 w-[8%]">ระยะเวลา</th>
                        </tr>
                    </thead>
                    <tbody class="align-top">
                        <tr>
                            <!-- Col 1: ลำดับที่ -->
                            <td class="border border-black p-2.5 text-center font-bold leading-relaxed space-y-2">
                                <?php foreach ($contractForm['policies'] as $idx => $pol): ?>
                                    <p><?= ($idx + 1) ?>.</p>
                                <?php endforeach; ?>
                            </td>

                            <!-- Col 2: นโยบาย/แผนงาน/โครงการ -->
                            <td class="border border-black p-2.5 space-y-2 leading-relaxed">
                                <?php foreach ($contractForm['policies'] as $pol): ?>
                                    <p><?= esc(preg_replace('/^\d+\.\s*/', '', $pol)) ?></p>
                                <?php endforeach; ?>
                            </td>

                            <!-- Col 3: ความจำเป็น / วัตถุประสงค์ -->
                            <td class="border border-black p-2.5 text-justify leading-relaxed">
                                <p><?= esc($contractForm['necessity_purpose']) ?></p>
                            </td>

                            <!-- Col 4: หน้าที่ความรับผิดชอบ (12 ข้อเต็ม) -->
                            <td class="border border-black p-2.5 space-y-1.5 leading-relaxed">
                                <?php foreach ($contractForm['responsibilities'] as $rNum => $rItem): ?>
                                    <p class="pl-4 -indent-4">
                                        <?= ($rNum + 1) ?>. <?= esc($rItem) ?>
                                    </p>
                                <?php endforeach; ?>
                            </td>

                            <!-- Col 5: พนักงานที่รับผิดชอบ -->
                            <td class="border border-black p-2.5 text-center space-y-1 leading-relaxed">
                                <p class="font-bold"><?= esc($contractForm['responsible_officer']['name']) ?></p>
                                <p>ตำแหน่ง <?= esc($contractForm['responsible_officer']['position']) ?></p>
                                <span class="inline-block text-[11px] text-slate-500 mt-2">
                                    (พนักงานจ้างตามภารกิจ)
                                </span>
                            </td>

                            <!-- Col 6: ระยะเวลา -->
                            <td class="border border-black p-2.5 text-center font-semibold leading-relaxed">
                                <?= esc($contractForm['period']) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer: Signee / Verifier -->
            <div class="mt-12 flex justify-end">
                <div class="text-center w-80 space-y-4" style="font-family: 'Sarabun', 'TH Sarabun New', sans-serif;">
                    <div class="space-y-1 text-sm">
                        <p>(ลงชื่อ).........................................................................ผู้รับรองข้อมูล</p>
                        <p class="font-bold pt-2">( <?= esc($contractForm['memo']['approver_name']) ?> )</p>
                        <p><?= esc($contractForm['memo']['approver_position']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- ========================================== -->
<!-- BOTTOM NAVIGATION: LINKS TO MOU & PORTFOLIO -->
<!-- ========================================== -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-8">
    <a href="<?= base_url('itsupport/mou') ?>" class="p-5 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-indigo-400 dark:hover:border-indigo-600 shadow-sm hover:shadow-md transition-all flex items-center justify-between group">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <i data-lucide="file-signature" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400">ข้อตกลงราชการ</span>
                <h4 class="text-sm font-black text-slate-800 dark:text-white">ข้อตกลงการปฏิบัติงานราชการ (MOU)</h4>
                <p class="text-xs text-slate-500">ดูเกณฑ์ชี้วัด KPI 3 ด้าน และ Milestone 6 ขั้นตอน</p>
            </div>
        </div>
        <i data-lucide="arrow-right" class="w-5 h-5 text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all"></i>
    </a>

    <a href="<?= base_url('itsupport/portfolio') ?>" class="p-5 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-cyan-400 dark:hover:border-cyan-600 shadow-sm hover:shadow-md transition-all flex items-center justify-between group">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-cyan-50 dark:bg-cyan-900/50 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <i data-lucide="award" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-cyan-600 dark:text-cyan-400">ผลงานระบบสารสนเทศ</span>
                <h4 class="text-sm font-black text-slate-800 dark:text-white">แฟ้มสะสมผลงาน (E-Portfolio)</h4>
                <p class="text-xs text-slate-500">ดูรายการ 16+ แพลตฟอร์ม และสถาปัตยกรรม 4 ฝ่าย</p>
            </div>
        </div>
        <i data-lucide="arrow-right" class="w-5 h-5 text-slate-400 group-hover:text-cyan-600 group-hover:translate-x-1 transition-all"></i>
    </a>
</div>

<!-- FOOTER -->
<div class="text-center pb-8 text-xs text-slate-400 space-y-1">
    <p class="font-bold">แบบแจ้งความประสงค์การต่อสัญญาจ้างพนักงานจ้างตามภารกิจ ประจำปีงบประมาณ <?= esc($contractForm['staff_data']['fiscal_years']) ?></p>
    <p>กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์</p>
</div>

<!-- JAVASCRIPT & PRINT STYLES -->
<script>
function switchTab(mode) {
    const modernSec = document.getElementById('view-section-modern');
    const officialSec = document.getElementById('view-section-official');
    const btnModern = document.getElementById('btn-view-modern');
    const btnOfficial = document.getElementById('btn-view-official');

    if (mode === 'official') {
        modernSec.classList.add('hidden');
        officialSec.classList.remove('hidden');

        btnModern.className = "px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-indigo-600";
        btnOfficial.className = "px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm";
    } else {
        modernSec.classList.remove('hidden');
        officialSec.classList.add('hidden');

        btnModern.className = "px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm";
        btnOfficial.className = "px-3.5 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-indigo-600";
    }

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function printOfficialDoc() {
    // Switch to official doc tab first
    switchTab('official');
    setTimeout(() => {
        window.print();
    }, 200);
}
</script>

<style>
/* Screen Display - Authentic White A4 Paper Sheet (กระดาษ A4 สีขาว) */
.a4-paper-sheet,
#official-doc-print-area {
    background-color: #ffffff !important;
    color: #000000 !important;
    font-family: 'Sarabun', 'TH Sarabun New', sans-serif !important;
}

.a4-paper-sheet *,
#official-doc-print-area * {
    color: #000000 !important;
}

.a4-paper-sheet table,
#official-doc-print-area table {
    border: 1px solid #000000 !important;
    border-collapse: collapse !important;
    background-color: #ffffff !important;
}

.a4-paper-sheet th,
.a4-paper-sheet td,
#official-doc-print-area th,
#official-doc-print-area td {
    border: 1px solid #000000 !important;
    color: #000000 !important;
}

.a4-paper-sheet thead tr,
#official-doc-print-area thead tr,
.a4-paper-sheet thead th,
#official-doc-print-area thead th {
    background-color: #f1f5f9 !important;
    color: #000000 !important;
}

@page {
    size: A4 landscape;
    margin: 8mm 10mm 8mm 10mm;
}

@media print {
    /* Hide everything except official landscape doc */
    aside, header, #theme-toggle, button, a, nav, .btn-action, .p-4.rounded-2xl, #view-section-modern, .glass-card {
        display: none !important;
    }
    #view-section-official {
        display: block !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    body, html {
        background: #ffffff !important;
        color: #000000 !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        font-size: 10pt !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    #official-doc-print-area {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 auto !important;
        max-width: 100% !important;
        width: 100% !important;
    }
    .official-page {
        page-break-after: auto;
        break-after: auto;
    }
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        page-break-inside: auto;
    }
    th, td {
        border: 1px solid #000000 !important;
        color: #000000 !important;
        padding: 4px 6px !important;
    }
    tr {
        page-break-inside: avoid;
    }
}
</style>
<?= $this->endSection() ?>
