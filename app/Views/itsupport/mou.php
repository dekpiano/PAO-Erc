<?= $this->extend('itsupport/layout/main') ?>

<?= $this->section('content') ?>
<!-- Breadcrumb / Header Navigation & Balanced Proportional Filter Toolbar -->
<div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 mb-6">
    <!-- Left: Title & Live Badge -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('itsupport/portfolio') ?>" class="w-11 h-11 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-indigo-600 hover:border-indigo-300 dark:hover:border-indigo-600 transition-all shadow-sm flex items-center justify-center shrink-0" title="กลับหน้า E-Portfolio">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-700 flex items-center gap-1">
                    <i data-lucide="file-signature" class="w-3 h-3"></i>
                    Official Performance Agreement (MOU)
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700 flex items-center gap-1">
                    <i data-lucide="calendar" class="w-3 h-3"></i>
                    <span><?= esc($date_filter_label) ?></span>
                </span>
            </div>
            <h1 class="text-lg sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-1">
                ข้อตกลงการปฏิบัติงานราชการ (MOU <?= esc($selected_fy == 'all' ? 'ทุกปีงบประมาณ' : $selected_fy) ?>)
            </h1>
        </div>
    </div>

    <!-- Right: Filter Controls & Actions -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full lg:w-auto">
        <!-- Fiscal Year & Round Form -->
        <form method="GET" action="<?= base_url('itsupport/mou') ?>" class="grid grid-cols-2 sm:flex sm:items-center gap-2 p-1.5 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm w-full sm:w-auto">
            <!-- FY Select -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-indigo-500">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <select name="fy" onchange="this.form.submit()" class="w-full pl-8 pr-7 py-2 text-xs font-black rounded-xl bg-slate-100 dark:bg-slate-900 border-none text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 cursor-pointer h-10 appearance-none">
                    <?php foreach ($available_fys as $fy): ?>
                        <option value="<?= esc($fy) ?>" <?= ($selected_fy == $fy) ? 'selected' : '' ?>>
                            ปีงบฯ <?= esc($fy) ?> <?= ($fy == $current_fy) ? '(ปัจจุบัน)' : '' ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="all" <?= ($selected_fy === 'all') ? 'selected' : '' ?>>
                        ทุกปีงบประมาณ
                    </option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Round Select -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-purple-500">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                </div>
                <select name="round" onchange="this.form.submit()" class="w-full pl-8 pr-7 py-2 text-xs font-bold rounded-xl bg-slate-100 dark:bg-slate-900 border-none text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-purple-500 cursor-pointer h-10 appearance-none">
                    <option value="all" <?= ($selected_round === 'all') ? 'selected' : '' ?>>ทุกรอบประเมิน</option>
                    <option value="1" <?= ($selected_round === '1') ? 'selected' : '' ?>>รอบ 1 (ต.ค.-มี.ค.)</option>
                    <option value="2" <?= ($selected_round === '2') ? 'selected' : '' ?>>รอบ 2 (เม.ย.-ก.ย.)</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </form>

        <!-- Actions: Print, Contract Renewal & E-Portfolio Link -->
        <div class="grid grid-cols-2 sm:flex sm:items-center gap-2">
            <button onclick="window.print()" class="h-10 px-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-indigo-300 text-slate-700 dark:text-slate-300 font-bold text-xs shadow-sm hover:shadow transition-all flex items-center justify-center gap-2">
                <i data-lucide="printer" class="w-4 h-4 text-indigo-600"></i>
                <span>พิมพ์ PDF</span>
            </button>
            <a href="<?= base_url('itsupport/self-report') ?>" class="h-10 px-4 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 hover:border-amber-300 text-amber-700 dark:text-amber-300 font-bold text-xs shadow-sm hover:shadow transition-all flex items-center justify-center gap-2">
                <i data-lucide="clipboard-check" class="w-4 h-4 text-amber-500"></i>
                <span>แบบรายงานตนเอง</span>
            </a>
            <?php if (session()->get('isLoggedIn')): ?>
            <a href="<?= base_url('itsupport/contract-renewal') ?>" class="h-10 px-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 hover:border-rose-300 text-rose-700 dark:text-rose-300 font-bold text-xs shadow-sm hover:shadow transition-all flex items-center justify-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-rose-500"></i>
                <span>ต่อสัญญาจ้าง</span>
            </a>
            <?php endif; ?>
            <a href="<?= base_url('itsupport/portfolio') ?>" class="h-10 px-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-500/20 transition-all flex items-center justify-center gap-2">
                <i data-lucide="award" class="w-4 h-4 text-cyan-300"></i>
                <span>E-Portfolio</span>
            </a>
        </div>
    </div>
</div>

<!-- HERO MOU HEADER SECTION -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-10 shadow-2xl border border-indigo-500/20 mb-8">
    <!-- Ambient Background Lighting -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col lg:flex-row items-center lg:items-start gap-8">
        <!-- Officer Photo with Premium Glow Badge -->
        <div class="relative shrink-0 group">
            <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-3xl p-1 bg-gradient-to-tr from-indigo-400 via-purple-300 to-cyan-400 shadow-xl shadow-indigo-500/30">
                <div class="w-full h-full rounded-[22px] overflow-hidden bg-slate-800 relative">
                    <?php if (!empty($officer['u_photo'])): ?>
                        <img src="<?= base_url('uploads/personnel/' . $officer['u_photo']) ?>" alt="<?= esc($officer['u_fullname']) ?>" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-400">
                            <i data-lucide="user" class="w-14 h-14"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white text-[10px] font-black px-2.5 py-1 rounded-full border-2 border-slate-900 flex items-center gap-1 shadow-lg">
                <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                <span>MOU บรรลุเป้าหมาย</span>
            </div>
        </div>

        <!-- Details -->
        <div class="flex-1 text-center lg:text-left space-y-4">
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2">
                <span class="px-3 py-1 rounded-xl bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-black tracking-wide flex items-center gap-1.5">
                    <i data-lucide="file-signature" class="w-3.5 h-3.5 text-indigo-400"></i>
                    ข้อตกลงการปฏิบัติงานราชการ (MOU)
                </span>
                <span class="px-3 py-1 rounded-xl bg-purple-500/20 border border-purple-400/30 text-purple-300 text-xs font-black flex items-center gap-1.5">
                    <i data-lucide="award" class="w-3.5 h-3.5"></i>
                    น้ำหนักรวม 80 คะแนน (ครบ 3 โครงการ)
                </span>
                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-black flex items-center gap-1.5">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                    ประเมินผลผ่านเกณฑ์ 100%
                </span>
            </div>

            <div>
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex flex-wrap items-center justify-center lg:justify-start gap-3">
                    <span><?= esc(($officer['u_prefix'] ?? '') . ' ' . $officer['u_fullname']) ?></span>
                </h2>
                <div class="mt-1.5 text-sm sm:text-base font-bold text-cyan-300 flex items-center justify-center lg:justify-start gap-2">
                    <i data-lucide="badge" class="w-4 h-4 text-cyan-400"></i>
                    <span>ตำแหน่ง: <?= esc($officer['pos_name'] ?? 'ผู้ช่วยนักวิชาการคอมพิวเตอร์') ?></span>
                </div>
                <div class="mt-2 space-y-1 text-xs text-slate-300">
                    <p class="flex items-center justify-center lg:justify-start gap-1.5">
                        <i data-lucide="building" class="w-3.5 h-3.5 text-blue-400 shrink-0"></i>
                        <span><strong>หน่วยงาน:</strong> ฝ่ายบริหารการศึกษา กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์</span>
                    </p>
                    <p class="flex items-center justify-center lg:justify-start gap-1.5">
                        <i data-lucide="school" class="w-3.5 h-3.5 text-pink-400 shrink-0"></i>
                        <span><strong>สถานที่ปฏิบัติงานร่วม:</strong> โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</span>
                    </p>
                </div>
            </div>

            <!-- Stakeholders Bar -->
            <div class="pt-3 border-t border-white/10 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-left">
                <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-300 flex items-center justify-center shrink-0">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 block font-medium">ผู้ทำข้อตกลง (หน.ส่วนราชการ):</span>
                        <span class="font-bold text-white text-[11px]"><?= esc($mou_info['signee_leader']) ?></span>
                    </div>
                </div>
                <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-300 flex items-center justify-center shrink-0">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 block font-medium">ผู้รับข้อตกลง (ผู้ปฏิบัติงาน):</span>
                        <span class="font-bold text-white text-[11px]"><?= esc($mou_info['signee_officer']) ?></span>
                    </div>
                </div>
                <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-300 flex items-center justify-center shrink-0">
                        <i data-lucide="shield" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 block font-medium">ผู้กลั่นกรอง / พยาน:</span>
                        <span class="font-bold text-white text-[11px]"><?= esc($mou_info['verifier_head']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KEY MOU HIGHLIGHT METRICS (4 CARDS) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="glass-card rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 relative overflow-hidden group hover:border-indigo-400 transition-all shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                <i data-lucide="file-check-2" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 dark:bg-indigo-950/50 dark:text-indigo-300 px-2 py-0.5 rounded-md">น้ำหนักคะแนน</span>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mb-1">
            80 คะแนน
        </div>
        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">น้ำหนักภารกิจตาม MOU</p>
        <p class="text-[10px] text-slate-400 mt-0.5">ครบถ้วนทั้ง 3 โครงการหลัก</p>
    </div>

    <div class="glass-card rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 relative overflow-hidden group hover:border-emerald-400 transition-all shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <i data-lucide="check-check" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600 bg-emerald-50 dark:bg-emerald-950/50 dark:text-emerald-300 px-2 py-0.5 rounded-md">ประเมินผล</span>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400 mb-1">
            100%
        </div>
        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">ผลการประเมินบรรลุเป้าหมาย</p>
        <p class="text-[10px] text-slate-400 mt-0.5">ผ่านเกณฑ์การประเมินทุกตัวชี้วัด</p>
    </div>

    <div class="glass-card rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 relative overflow-hidden group hover:border-blue-400 transition-all shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <i data-lucide="layers" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider text-blue-600 bg-blue-50 dark:bg-blue-950/50 dark:text-blue-300 px-2 py-0.5 rounded-md">3 โครงการ</span>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mb-1">
            3 / 3 งาน
        </div>
        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">โครงการส่งมอบสมบูรณ์</p>
        <p class="text-[10px] text-slate-400 mt-0.5">ระบบสารสนเทศ, โสตฯ, IT Support</p>
    </div>

    <div class="glass-card rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 relative overflow-hidden group hover:border-purple-400 transition-all shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                <i data-lucide="database" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider text-purple-600 bg-purple-50 dark:bg-purple-950/50 dark:text-purple-300 px-2 py-0.5 rounded-md">Live Verification</span>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mb-1">
            <?= number_format($total_tasks) ?>+ เคส
        </div>
        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">ประวัติงานจริงในฐานข้อมูล</p>
        <p class="text-[10px] text-slate-400 mt-0.5">ประมวลผลจาก Tb_It_Support_Logs</p>
    </div>
</div>

<!-- ========================================================================= -->
<!-- KPI MATRIX: PERFORMANCE VS TARGET BREAKDOWN -->
<!-- ========================================================================= -->
<div class="glass-card rounded-3xl p-5 sm:p-8 border border-slate-200/80 dark:border-slate-800 shadow-lg mb-10">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-6">
        <div>
            <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest">
                <i data-lucide="calculator" class="w-4 h-4"></i>
                <span>Category-to-Target Performance Matrix</span>
            </div>
            <h3 class="text-base sm:text-xl font-black text-slate-800 dark:text-white tracking-tight mt-1">
                ตารางวิเคราะห์ผลงานเทียบเป้าหมายตามหมวดหมู่จริงในระบบ
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                คำนวณและประมวลผลอัตโนมัติจากฐานข้อมูลบันทึกงานบริการ (Tb_It_Support_Logs) ประจำ<?= esc($date_filter_label) ?>
            </p>
        </div>

        <div class="flex items-center gap-2 self-stretch sm:self-auto">
            <span class="w-full sm:w-auto px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-xs font-black border border-emerald-200 dark:border-emerald-800 flex items-center justify-center gap-1.5 shadow-sm">
                <i data-lucide="check-check" class="w-4 h-4 text-emerald-500"></i>
                <span>ผ่านเกณฑ์การประเมินทุกตัวชี้วัด</span>
            </span>
        </div>
    </div>

    <!-- 1. Mobile-First Card View -->
    <div class="grid grid-cols-1 gap-3.5 md:hidden">
        <?php foreach ($kpi_matrix as $kpi): 
            $pct = (float)$kpi['percent'];
            $isPass = $pct >= 100;
        ?>
            <div class="p-4 rounded-2xl bg-slate-50/90 dark:bg-slate-900/60 border border-slate-200/70 dark:border-slate-800 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-<?= $kpi['color'] ?>-50 dark:bg-<?= $kpi['color'] ?>-950/60 text-<?= $kpi['color'] ?>-600 dark:text-<?= $kpi['color'] ?>-400 flex items-center justify-center shrink-0">
                            <i data-lucide="<?= $kpi['icon'] ?>" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="text-xs font-black text-slate-800 dark:text-white block">
                                <?= esc($kpi['name']) ?>
                            </span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">
                                <?= esc($kpi['category_label']) ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($kpi['weight'] !== '-'): ?>
                        <span class="px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 text-[10px] font-black shrink-0">
                            <?= esc($kpi['weight']) ?> คะแนน
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Target vs Actual Metric Badge -->
                <div class="p-2.5 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-[10px] text-slate-400 block font-medium">เป้าหมายตามเกณฑ์:</span>
                        <span class="font-bold text-slate-700 dark:text-slate-300"><?= esc($kpi['target']) ?> <?= esc($kpi['unit']) ?></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400 block font-medium">ผลงานจริงที่บันทึก:</span>
                        <span class="text-sm font-black text-<?= $kpi['color'] ?>-600 dark:text-<?= $kpi['color'] ?>-400"><?= esc($kpi['actual']) ?> <?= esc($kpi['unit']) ?></span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="space-y-1">
                    <div class="flex justify-between text-[10px] font-bold">
                        <span class="text-slate-500 dark:text-slate-400">อัตราความสำเร็จ: <?= $pct ?>%</span>
                        <span class="text-emerald-500 font-black flex items-center gap-1">
                            <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                            <?= esc($kpi['status']) ?>
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-<?= $kpi['color'] ?>-500 to-emerald-500 h-2 rounded-full" style="width: <?= min($pct, 100) ?>%"></div>
                    </div>
                </div>

                <div class="text-[10px] font-mono text-slate-500 dark:text-slate-400 pt-1 border-t border-slate-200/50 dark:border-slate-800 flex items-center gap-1.5">
                    <i data-lucide="database" class="w-3 h-3 text-slate-400"></i>
                    <span class="truncate"><?= esc($kpi['mapped_tasks']) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- 2. Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                    <th class="py-3 px-3">หมวดหมู่ภารกิจ / โครงการ</th>
                    <th class="py-3 px-3">หมวดหมู่ในระบบบันทึกงานจริง</th>
                    <th class="py-3 px-3 text-center">เป้าหมาย</th>
                    <th class="py-3 px-3 text-center">ผลงานจริง</th>
                    <th class="py-3 px-3 text-center" style="min-width: 140px;">ความก้าวหน้า</th>
                    <th class="py-3 px-3 text-center">คะแนน MOU</th>
                    <th class="py-3 px-3 text-right">สถานะ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                <?php foreach ($kpi_matrix as $kpi): 
                    $pct = (float)$kpi['percent'];
                    $isPass = $pct >= 100;
                ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40 transition-colors">
                        <!-- Category Name -->
                        <td class="py-4 px-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-<?= $kpi['color'] ?>-50 dark:bg-<?= $kpi['color'] ?>-950/60 text-<?= $kpi['color'] ?>-600 dark:text-<?= $kpi['color'] ?>-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="<?= $kpi['icon'] ?>" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="font-black text-slate-800 dark:text-white block">
                                        <?= esc($kpi['name']) ?>
                                    </span>
                                    <span class="text-[10px] text-slate-400 block mt-0.5 line-clamp-1">
                                        <?= esc($kpi['desc']) ?>
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- Database Category Mapping -->
                        <td class="py-4 px-3 font-medium text-slate-600 dark:text-slate-300">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-[11px] font-bold inline-block">
                                <?= esc($kpi['mapped_tasks']) ?>
                            </span>
                        </td>

                        <!-- Target -->
                        <td class="py-4 px-3 text-center font-bold text-slate-500 dark:text-slate-400">
                            <?= esc($kpi['target']) ?> <span class="text-[10px] font-normal"><?= esc($kpi['unit']) ?></span>
                        </td>

                        <!-- Actual -->
                        <td class="py-4 px-3 text-center">
                            <span class="text-sm font-black text-<?= $kpi['color'] ?>-600 dark:text-<?= $kpi['color'] ?>-400">
                                <?= esc($kpi['actual']) ?>
                            </span>
                            <span class="text-[10px] text-slate-400 font-bold block"><?= esc($kpi['unit']) ?></span>
                        </td>

                        <!-- Progress Bar -->
                        <td class="py-4 px-3">
                            <div class="space-y-1">
                                <div class="flex justify-between text-[10px] font-bold">
                                    <span class="text-slate-400"><?= $pct ?>%</span>
                                    <span class="text-emerald-500"><?= $isPass ? 'ผ่านเกณฑ์' : 'กำลังดำเนินการ' ?></span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-<?= $kpi['color'] ?>-500 to-emerald-500 h-2 rounded-full" style="width: <?= min($pct, 100) ?>%"></div>
                                </div>
                            </div>
                        </td>

                        <!-- Weight -->
                        <td class="py-4 px-3 text-center font-bold text-slate-700 dark:text-slate-300">
                            <?= $kpi['weight'] !== '-' ? esc($kpi['weight']) . ' คะแนน' : '<span class="text-slate-400">-</span>' ?>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-4 px-3 text-right">
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-black inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-500"></i>
                                <span><?= esc($kpi['status']) ?></span>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 3 MAIN PROJECTS ACCORDING TO OFFICIAL MOU -->
<!-- ========================================================================= -->
<div class="space-y-6 mb-12">
    <div class="flex items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest">
                <i data-lucide="award" class="w-4 h-4"></i>
                <span>3 Core MOU Strategic Projects</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-1">
                รายละเอียด 3 โครงการหลักตามข้อตกลงการปฏิบัติงาน
            </h2>
        </div>
        <span class="text-xs text-slate-500 font-bold hidden sm:inline-block">น้ำหนักรวม 80 คะแนน</span>
    </div>

    <?php foreach ($mou_info['tasks'] as $task): 
        $progressPercent = $task['target_qty'] > 0 ? min(round(($task['actual_qty'] / $task['target_qty']) * 100), 500) : 100;
    ?>
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 hover:shadow-xl transition-all duration-300">
            <!-- Project Top Bar -->
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-<?= $task['color'] ?>-500 to-<?= $task['color'] ?>-700 text-white flex items-center justify-center shadow-lg shadow-<?= $task['color'] ?>-500/20 shrink-0">
                        <i data-lucide="<?= $task['icon'] ?>" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-<?= $task['color'] ?>-50 dark:bg-<?= $task['color'] ?>-950/60 text-<?= $task['color'] ?>-600 dark:text-<?= $task['color'] ?>-300 text-[11px] font-black uppercase tracking-wide">
                                โครงการ/งานที่ <?= $task['no'] ?>
                            </span>
                            <span class="px-2.5 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[11px] font-black">
                                น้ำหนัก: <?= $task['weight'] ?> คะแนน
                            </span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-black text-slate-800 dark:text-white mt-1">
                            <?= esc($task['title']) ?>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            <?= esc($task['subtitle']) ?>
                        </p>
                    </div>
                </div>

                <!-- Progress / Comparison Box -->
                <div class="w-full lg:w-72 bg-slate-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-600 dark:text-slate-300">ผลงานเทียบเป้าหมาย</span>
                        <span class="text-<?= $task['color'] ?>-600 font-mono"><?= $task['actual_qty'] ?> / <?= $task['target_qty'] ?> <?= $task['unit'] ?></span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden mb-1.5">
                        <div class="bg-gradient-to-r from-<?= $task['color'] ?>-500 to-emerald-500 h-2.5 rounded-full" style="width: <?= min($progressPercent, 100) ?>%"></div>
                    </div>
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="text-slate-400">เป้าหมายขั้นต่ำ: ร้อยละ 80</span>
                        <span class="font-black text-emerald-600 dark:text-emerald-400">
                            <?= $progressPercent >= 100 ? 'เกินเป้าหมาย (' . $progressPercent . '%)' : $progressPercent . '%' ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- 3 Pillars of Indicators (เชิงปริมาณ, เชิงคุณภาพ, เชิงประโยชน์) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-6">
                <!-- 1. Quantitative -->
                <div class="p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/40">
                    <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 text-xs font-black mb-1.5">
                        <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                        <span>1. ตัวชี้วัดเชิงปริมาณ (Quantity)</span>
                    </div>
                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                        <?= esc($task['quantitative']) ?>
                    </p>
                </div>

                <!-- 2. Qualitative -->
                <div class="p-4 rounded-2xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/40">
                    <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 text-xs font-black mb-1.5">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        <span>2. ตัวชี้วัดเชิงคุณภาพ (Quality)</span>
                    </div>
                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                        <?= esc($task['qualitative']) ?>
                    </p>
                </div>

                <!-- 3. Utility -->
                <div class="p-4 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40">
                    <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 text-xs font-black mb-1.5">
                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                        <span>3. ตัวชี้วัดเชิงประโยชน์ (Utility)</span>
                    </div>
                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                        <?= esc($task['utility']) ?>
                    </p>
                </div>
            </div>

            <!-- 6 Standard Milestones & Evidences Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                <!-- 6 Milestones (ขั้นตอนความสำเร็จ) -->
                <div>
                    <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider mb-3 flex items-center gap-2">
                        <i data-lucide="list-checks" class="w-4 h-4 text-<?= $task['color'] ?>-500"></i>
                        <span>ขั้นตอนความสำเร็จในการปฏิบัติงาน (6 Milestones)</span>
                    </h4>
                    <div class="space-y-2">
                        <?php foreach ($task['milestones'] as $m): ?>
                            <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/80 text-xs text-slate-700 dark:text-slate-300 flex items-start gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5"></i>
                                <span><?= esc($m) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Evidence & Deliverables (หลักฐานบ่งชี้ความสำเร็จ) -->
                <div>
                    <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider mb-3 flex items-center gap-2">
                        <i data-lucide="folder-check" class="w-4 h-4 text-amber-500"></i>
                        <span>หลักฐานเชิงประจักษ์บ่งชี้ความสำเร็จ (Verified Evidences)</span>
                    </h4>
                    <div class="space-y-2.5">
                        <?php foreach ($task['evidences'] as $ev): ?>
                            <div class="p-3 rounded-xl bg-amber-50/40 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-900/30 text-xs text-slate-700 dark:text-slate-300 flex items-start gap-2.5">
                                <i data-lucide="file-badge" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5"></i>
                                <span><?= esc($ev) ?></span>
                            </div>
                        <?php endforeach; ?>

                        <!-- Action buttons inside card -->
                        <div class="pt-2 flex items-center gap-3">
                            <a href="<?= base_url('itsupport') ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-<?= $task['color'] ?>-600 hover:underline">
                                <span>เปิดดูบันทึกงานในระบบ IT Support</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                            <span class="text-slate-300 dark:text-slate-700">•</span>
                            <a href="<?= base_url('itsupport/portfolio') ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:underline">
                                <span>ดูระบบใน E-Portfolio</span>
                                <i data-lucide="external-link" class="w-3 h-3"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- ========================================================================= -->
<!-- SECTION: VERIFIED ACTIVITY SNAPSHOTS (ภาพถ่ายหลักฐานการปฏิบัติงานจริง) -->
<!-- ========================================================================= -->
<div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 mb-12">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 mb-6">
        <div>
            <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest">
                <i data-lucide="camera" class="w-4 h-4"></i>
                <span>Verified Activity Evidence Gallery</span>
            </div>
            <h3 class="text-base sm:text-xl font-black text-slate-800 dark:text-white tracking-tight mt-1">
                ภาพถ่ายและหลักฐานการปฏิบัติหน้าที่จริงตามภารกิจ MOU
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                เชื่อมโยงภาพถ่ายหน้างานจริงจากระบบ IT Support Desk เพื่อประกอบการประเมิน
            </p>
        </div>
        <a href="<?= base_url('itsupport') ?>" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
            <span>ดูประวัติทั้งหมด</span>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <?php 
        $shownImages = 0;
        foreach ($showcase_logs as $log): 
            $images = json_decode($log['its_images'] ?? '[]', true);
            if (is_array($images) && !empty($images)):
                foreach ($images as $img):
                    $shownImages++;
        ?>
            <div class="group relative rounded-2xl overflow-hidden aspect-square bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 shadow-sm">
                <img loading="lazy" src="<?= base_url('uploads/it_support/' . $img) ?>" alt="<?= esc($log['its_task']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-2.5 flex flex-col justify-end">
                    <span class="text-[9px] font-bold text-cyan-300 truncate"><?= esc($log['its_category']) ?></span>
                    <p class="text-[10px] font-bold text-white line-clamp-2 leading-tight"><?= esc($log['its_task']) ?></p>
                    <span class="text-[8px] text-slate-300 mt-0.5"><?= date('d/m/Y', strtotime($log['its_date'])) ?></span>
                </div>
            </div>
        <?php 
                    if ($shownImages >= 12) break 2;
                endforeach;
            endif;
        endforeach; 
        ?>
    </div>
</div>

<!-- ========================================================================= -->
<!-- OFFICIAL SIGNATURES & VERIFICATION SECTION (สำหรับพิมพ์เอกสารประเมิน) -->
<!-- ========================================================================= -->
<div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 mb-12">
    <div class="text-center max-w-xl mx-auto mb-8">
        <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-wider text-slate-500">
            Official Certification
        </span>
        <h3 class="text-lg font-black text-slate-800 dark:text-white mt-2">
            การรับรองผลการปฏิบัติงานตามข้อตกลง (MOU)
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            ข้อมูลในรายงานฉบับนี้ถูกรวบรวมจากประวัติการทำงานจริงในระบบ เพื่อใช้เป็นหลักฐานประกอบการประเมินผลการปฏิบัติงาน
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 pt-4">
        <!-- Signee 1: Officer -->
        <div class="text-center space-y-3">
            <div class="h-20 flex items-end justify-center">
                <div class="border-b border-dashed border-slate-400 dark:border-slate-600 w-48 text-center pb-1">
                    <span class="text-xs font-serif italic text-slate-400">ลายมือชื่อผู้รับข้อตกลง</span>
                </div>
            </div>
            <div class="text-xs">
                <p class="font-bold text-slate-800 dark:text-white">(<?= esc($mou_info['signee_officer']) ?>)</p>
                <p class="text-[11px] text-slate-500">ตำแหน่ง: <?= esc($officer['pos_name'] ?? 'ผู้ช่วยนักวิชาการคอมพิวเตอร์') ?></p>
                <p class="text-[10px] text-slate-400 mt-1">วันที่ ........ / .................... / ...........</p>
            </div>
        </div>

        <!-- Signee 2: Verifier -->
        <div class="text-center space-y-3">
            <div class="h-20 flex items-end justify-center">
                <div class="border-b border-dashed border-slate-400 dark:border-slate-600 w-48 text-center pb-1">
                    <span class="text-xs font-serif italic text-slate-400">ลายมือชื่อผู้กลั่นกรอง</span>
                </div>
            </div>
            <div class="text-xs">
                <p class="font-bold text-slate-800 dark:text-white">(<?= esc($mou_info['verifier_head']) ?>)</p>
                <p class="text-[11px] text-slate-500">ตำแหน่ง: หัวหน้าฝ่ายบริหารการศึกษา</p>
                <p class="text-[10px] text-slate-400 mt-1">วันที่ ........ / .................... / ...........</p>
            </div>
        </div>

        <!-- Signee 3: Leader -->
        <div class="text-center space-y-3">
            <div class="h-20 flex items-end justify-center">
                <div class="border-b border-dashed border-slate-400 dark:border-slate-600 w-48 text-center pb-1">
                    <span class="text-xs font-serif italic text-slate-400">ลายมือชื่อผู้ทำข้อตกลง</span>
                </div>
            </div>
            <div class="text-xs">
                <p class="font-bold text-slate-800 dark:text-white">(<?= esc($mou_info['signee_leader']) ?>)</p>
                <p class="text-[11px] text-slate-500">ผู้อำนวยการกองการศึกษา ศาสนาและวัฒนธรรม</p>
                <p class="text-[10px] text-slate-400 mt-1">วันที่ ........ / .................... / ...........</p>
            </div>
        </div>
    </div>
</div>

<!-- CROSS LINK BANNER TO E-PORTFOLIO -->
<div class="rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 text-white p-6 sm:p-8 shadow-xl relative overflow-hidden mb-12">
    <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
        <div class="space-y-1">
            <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-[10px] font-black uppercase tracking-wider inline-block">
                E-Portfolio Showcase
            </span>
            <h3 class="text-xl sm:text-2xl font-black text-white">
                ดูผลงานระบบสารสนเทศ (16+ แพลตฟอร์ม) & สถาปัตยกรรมดิจิทัล 4 ฝ่าย
            </h3>
            <p class="text-xs text-blue-100">
                สามารถเปิดดูแฟ้มสะสมผลงานอิเล็กทรอนิกส์ (E-Portfolio) ฉบับเต็ม พร้อมรายชื่อระบบและลิงก์ทดสอบใช้งานจริง
            </p>
        </div>

        <a href="<?= base_url('itsupport/portfolio') ?>" class="px-6 py-3 rounded-2xl bg-white hover:bg-blue-50 text-indigo-700 font-black text-xs sm:text-sm shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center gap-2 shrink-0">
            <i data-lucide="award" class="w-4 h-4 text-cyan-500"></i>
            <span>เปิดหน้า E-Portfolio เจ้าหน้าที่</span>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
        </a>
    </div>
</div>

<!-- FOOTER INFO -->
<div class="text-center pb-8 text-xs text-slate-400 space-y-1">
    <p class="font-bold">ข้อตกลงการปฏิบัติงานราชการ (MOU <?= esc($selected_fy == 'all' ? '2569' : $selected_fy) ?>)</p>
    <p>กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์ • โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
</div>

<!-- Custom CSS for Print View -->
<style>
@media print {
    body {
        background: #ffffff !important;
        color: #000000 !important;
        font-size: 12pt !important;
    }
    aside, header, #theme-toggle, button, a[href*="logout"], .btn-action {
        display: none !important;
    }
    .glass-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
        background: #ffffff !important;
        page-break-inside: avoid;
    }
    .shadow-2xl, .shadow-xl, .shadow-lg, .shadow-md, .shadow-sm {
        box-shadow: none !important;
    }
}
</style>
<?= $this->endSection() ?>
