<?= $this->extend('itsupport/layout/main') ?>

<?= $this->section('content') ?>
<!-- Breadcrumb / Header Navigation & Balanced Proportional Filter Toolbar -->
<div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 mb-6">
    <!-- Left: Title & Live Badge -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('itsupport') ?>" class="w-11 h-11 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-blue-600 hover:border-blue-300 dark:hover:border-blue-600 transition-all shadow-sm flex items-center justify-center shrink-0">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                    Official E-Portfolio & MOU KPIs
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700 flex items-center gap-1">
                    <i data-lucide="calendar" class="w-3 h-3"></i>
                    <span><?= esc($date_filter_label) ?></span>
                </span>
            </div>
            <h1 class="text-lg sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-1">
                แฟ้มผลงานทางวิชาการและข้อตกลงการปฏิบัติงาน (MOU <?= esc($selected_fy == 'all' ? 'ทุกปีงบประมาณ' : $selected_fy) ?>)
            </h1>
        </div>
    </div>

    <!-- Right: Balanced Filter Controls & Actions -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full lg:w-auto">
        <!-- Fiscal Year & Round Form (Proportioned Grid on Mobile, Flex on Desktop) -->
        <form method="GET" action="<?= base_url('itsupport/portfolio') ?>" class="grid grid-cols-2 sm:flex sm:items-center gap-2 p-1.5 bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm w-full sm:w-auto">
            <!-- FY Select -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-blue-500">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <select name="fy" onchange="this.form.submit()" class="w-full pl-8 pr-7 py-2 text-xs font-black rounded-xl bg-slate-100 dark:bg-slate-900 border-none text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 cursor-pointer h-10 appearance-none">
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

        <!-- Actions: Print, MOU, ต่อสัญญา & IT Support Portal -->
        <div class="grid grid-cols-2 sm:flex sm:items-center gap-2">
            <button onclick="window.print()" class="h-10 px-3.5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-300 text-slate-700 dark:text-slate-300 font-bold text-xs shadow-sm hover:shadow transition-all flex items-center justify-center gap-1.5">
                <i data-lucide="printer" class="w-3.5 h-3.5 text-blue-600"></i>
                <span>พิมพ์ PDF</span>
            </button>
            <a href="<?= base_url('itsupport/mou') ?>" class="h-10 px-3.5 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800 hover:border-indigo-300 text-indigo-700 dark:text-indigo-300 font-bold text-xs shadow-sm hover:shadow transition-all flex items-center justify-center gap-1.5">
                <i data-lucide="file-signature" class="w-3.5 h-3.5 text-indigo-500"></i>
                <span>ข้อตกลง MOU</span>
            </a>
            <a href="<?= base_url('itsupport/self-report') ?>" class="h-10 px-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 hover:border-amber-300 text-amber-700 dark:text-amber-300 font-bold text-xs shadow-sm hover:shadow transition-all flex items-center justify-center gap-1.5">
                <i data-lucide="clipboard-check" class="w-3.5 h-3.5 text-amber-500"></i>
                <span>แบบรายงานตนเอง</span>
            </a>
            <?php if (session()->get('isLoggedIn')): ?>
            <a href="<?= base_url('itsupport/contract-renewal') ?>" class="h-10 px-3.5 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 hover:border-rose-300 text-rose-700 dark:text-rose-300 font-bold text-xs shadow-sm hover:shadow transition-all flex items-center justify-center gap-1.5">
                <i data-lucide="file-text" class="w-3.5 h-3.5 text-rose-500"></i>
                <span>ต่อสัญญาจ้าง</span>
            </a>
            <?php endif; ?>
            <a href="<?= base_url('itsupport') ?>" class="h-10 px-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-1.5">
                <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                <span>IT Support</span>
            </a>
        </div>
    </div>
</div>

<!-- HERO PROFILE SECTION -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 text-white p-6 sm:p-10 shadow-2xl border border-blue-500/20 mb-8">
    <!-- Ambient Background Lighting -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col lg:flex-row items-center lg:items-start gap-8">
        <!-- Officer Photo with Premium Glow Badge -->
        <div class="relative shrink-0 group">
            <div class="w-36 h-36 sm:w-44 sm:h-44 rounded-3xl p-1 bg-gradient-to-tr from-blue-400 via-cyan-300 to-indigo-500 shadow-xl shadow-blue-500/30">
                <div class="w-full h-full rounded-[22px] overflow-hidden bg-slate-800 relative">
                    <?php if (!empty($officer['u_photo'])): ?>
                        <img src="<?= base_url('uploads/personnel/' . $officer['u_photo']) ?>" alt="<?= esc($officer['u_fullname']) ?>" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-400">
                            <i data-lucide="user" class="w-16 h-16"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Online / Active Status Badge -->
            <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white text-[11px] font-black px-3 py-1 rounded-full border-2 border-slate-900 flex items-center gap-1.5 shadow-lg">
                <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                <span>พร้อมปฏิบัติหน้าที่</span>
            </div>
        </div>

        <!-- Officer Details -->
        <div class="flex-1 text-center lg:text-left space-y-4">
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2">
                <span class="px-3 py-1 rounded-xl bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-black tracking-wide flex items-center gap-1.5">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-blue-400"></i>
                    พนักงานจ้างตามภารกิจ / สายงาน IT
                </span>
                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-black flex items-center gap-1.5">
                    <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    ปฏิบัติงาน 2 หน่วยงานหลัก (กองการศึกษาฯ อบจ. & รร.สวนกุหลาบฯ)
                </span>
                <span class="px-3 py-1 rounded-xl bg-cyan-500/20 border border-cyan-400/30 text-cyan-300 text-xs font-black flex items-center gap-1.5">
                    <i data-lucide="file-signature" class="w-3.5 h-3.5"></i>
                    MOU 2569 รอบที่ 2
                </span>
            </div>

            <div>
                <h2 class="text-2xl sm:text-4xl font-black tracking-tight text-white flex flex-wrap items-center justify-center lg:justify-start gap-3">
                    <span><?= esc(($officer['u_prefix'] ?? '') . ' ' . $officer['u_fullname']) ?></span>
                </h2>
                <div class="mt-2 text-base sm:text-lg font-bold text-cyan-300 flex items-center justify-center lg:justify-start gap-2">
                    <i data-lucide="badge" class="w-5 h-5 text-cyan-400"></i>
                    <span>ตำแหน่ง: <?= esc($officer['pos_name'] ?? 'ผู้ช่วยนักวิชาการคอมพิวเตอร์') ?></span>
                </div>
                <!-- Dual Workplaces Info -->
                <div class="mt-2 space-y-1 text-xs sm:text-sm text-slate-300">
                    <p class="flex items-center justify-center lg:justify-start gap-1.5">
                        <i data-lucide="building" class="w-4 h-4 text-blue-400 shrink-0"></i>
                        <span><strong>สังกัดหลัก:</strong> ฝ่ายบริหารการศึกษา กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์</span>
                    </p>
                    <p class="flex items-center justify-center lg:justify-start gap-1.5">
                        <i data-lucide="school" class="w-4 h-4 text-pink-400 shrink-0"></i>
                        <span><strong>สถานที่ปฏิบัติงานร่วม:</strong> โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</span>
                    </p>
                </div>
            </div>

            <!-- Vision / Motto -->
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md max-w-3xl">
                <p class="text-xs sm:text-sm text-slate-200 leading-relaxed italic flex items-start gap-2">
                    <i data-lucide="quote" class="w-4 h-4 text-cyan-400 shrink-0 mt-0.5"></i>
                    <span>
                        "มุ่งมั่นประยุกต์ใช้นวัตกรรมและเทคโนโลยีดิจิทัล พัฒนาระบบสารสนเทศเพื่อการศึกษาและการบริการภาครัฐ 
                        สนับสนุนการทำงานที่มีประสิทธิภาพ รวดเร็ว ปลอดภัย และสร้างประโยชน์สูงสุดแก่ทั้ง กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์ และ โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์"
                    </span>
                </p>
            </div>

            <!-- Contact & Social Badges -->
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 pt-2">
                <?php if (!empty($officer['u_email'])): ?>
                    <a href="mailto:<?= esc($officer['u_email']) ?>" class="px-3.5 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-800 border border-slate-700 text-xs font-semibold text-slate-200 hover:text-white transition-all flex items-center gap-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-cyan-400"></i>
                        <span><?= esc($officer['u_email']) ?></span>
                    </a>
                <?php endif; ?>
                <?php if (!empty($officer['u_phone'])): ?>
                    <a href="tel:<?= esc($officer['u_phone']) ?>" class="px-3.5 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-800 border border-slate-700 text-xs font-semibold text-slate-200 hover:text-white transition-all flex items-center gap-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span><?= esc($officer['u_phone']) ?></span>
                    </a>
                <?php endif; ?>
                <div class="px-3.5 py-1.5 rounded-xl bg-slate-800/80 border border-slate-700 text-xs font-semibold text-slate-300 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-400"></i>
                    <span>กองการศึกษาฯ อบจ.นครสวรรค์ & รร.สวนกุหลาบฯ จิรประวัติ</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KEY PERFORMANCE METRICS & LIVE STATS -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
    <!-- Stat 1: Total Resolved Tickets -->
    <div class="glass-card rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 relative overflow-hidden group hover:border-blue-400 transition-all shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <i data-lucide="wrench" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider text-blue-600 bg-blue-50 dark:bg-blue-950/50 dark:text-blue-300 px-2 py-0.5 rounded-md">Live Data</span>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mb-1">
            <?= number_format($total_tasks) ?>+
        </div>
        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">งานบริการ & ซ่อมบำรุงสำเร็จ</p>
        <p class="text-[10px] text-slate-400 mt-1">บันทึกประวัติการทำงานจริงในระบบ</p>
    </div>

    <!-- Stat 2: Developed Platforms (Total PAO + SKJ) -->
    <div class="glass-card rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 relative overflow-hidden group hover:border-indigo-400 transition-all shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 dark:bg-indigo-950/50 dark:text-indigo-300 px-2 py-0.5 rounded-md">PAO & SKJ</span>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mb-1">
            <?= count($featured_projects) ?>+
        </div>
        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">ระบบสารสนเทศหลักที่พัฒนา</p>
        <p class="text-[10px] text-slate-400 mt-1">กองการศึกษาฯ อบจ. & รร.สวนกุหลาบฯ</p>
    </div>

    <!-- Stat 3: Service Locations -->
    <div class="glass-card rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 relative overflow-hidden group hover:border-emerald-400 transition-all shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <i data-lucide="map-pinned" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600 bg-emerald-50 dark:bg-emerald-950/50 dark:text-emerald-300 px-2 py-0.5 rounded-md">Coverage</span>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mb-1">
            <?= number_format($location_count) ?>+
        </div>
        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">จุดบริการและหน่วยงานที่ดูแล</p>
        <p class="text-[10px] text-slate-400 mt-1">ครอบคลุมทุกฝ่ายและสถานที่จัดกิจกรรม</p>
    </div>

    <!-- Stat 4: MOU KPI Score Weight -->
    <div class="glass-card rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 relative overflow-hidden group hover:border-amber-400 transition-all shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                <i data-lucide="file-check-2" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider text-amber-600 bg-amber-50 dark:bg-amber-950/50 dark:text-amber-300 px-2 py-0.5 rounded-md">MOU Target</span>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mb-1">
            80 คะแนน
        </div>
        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">น้ำหนักภารกิจตาม MOU</p>
        <p class="text-[10px] text-slate-400 mt-1">ครบถ้วน 3 โครงการหลัก 100%</p>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: OFFICIAL MOU EXECUTIVE SUMMARY & SHORTCUT (ข้อตกลงการปฏิบัติงานตาม MOU) -->
<!-- ========================================================================= -->
<div class="mb-12">
    <div class="rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-950 text-white p-6 sm:p-8 border border-indigo-500/30 shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 text-cyan-300 text-xs font-black uppercase tracking-widest mb-1.5">
                    <i data-lucide="file-signature" class="w-4 h-4"></i>
                    <span>Official Performance Agreement (MOU)</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                    ข้อตกลงการปฏิบัติงานราชการ ประจำ<?= esc($date_filter_label) ?>
                </h2>
                <p class="text-xs sm:text-sm text-blue-200 mt-1 max-w-2xl">
                    ผลการปฏิบัติงานบรรลุเป้าหมายครบถ้วนทั้ง 3 โครงการหลักตามข้อตกลง (น้ำหนักรวม 80 คะแนน) ประเมินผลผ่านเกณฑ์ร้อยละ 100
                </p>
            </div>

            <!-- Action Button to Dedicated MOU Page -->
            <a href="<?= base_url('itsupport/mou' . ($selected_fy !== 'all' ? '?fy=' . esc($selected_fy) : '')) ?>" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-500/25 border border-white/20 transition-all flex items-center gap-2 shrink-0 hover:scale-105">
                <i data-lucide="file-signature" class="w-4 h-4"></i>
                <span>เปิดดูข้อตกลง MOU ฉบับเต็ม & เกณฑ์การประเมิน</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <!-- 3 Core Projects Mini Grid Summary -->
        <div class="relative z-10 mt-6 pt-6 border-t border-white/10 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <?php foreach ($mou_info['tasks'] as $task): 
                $progressPercent = $task['target_qty'] > 0 ? min(round(($task['actual_qty'] / $task['target_qty']) * 100), 500) : 100;
            ?>
                <a href="<?= base_url('itsupport/mou') ?>" class="p-4 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-indigo-400/40 transition-all block group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-0.5 rounded-md bg-<?= $task['color'] ?>-500/20 text-<?= $task['color'] ?>-300 text-[10px] font-black uppercase">
                            โครงการที่ <?= $task['no'] ?>
                        </span>
                        <span class="text-[10px] font-bold text-amber-300">
                            น้ำหนัก <?= $task['weight'] ?> คะแนน
                        </span>
                    </div>
                    <h4 class="text-xs font-black text-white line-clamp-1 group-hover:text-cyan-300 transition-colors">
                        <?= esc($task['title']) ?>
                    </h4>
                    <div class="mt-3 flex items-center justify-between text-[11px]">
                        <span class="text-slate-300 font-mono"><?= $task['actual_qty'] ?> / <?= $task['target_qty'] ?> <?= $task['unit'] ?></span>
                        <span class="font-black text-emerald-300 flex items-center gap-1">
                            <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-400"></i>
                            <?= $progressPercent ?>%
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: FEATURED DEVELOPED SYSTEMS & APPLICATIONS (ระบบสารสนเทศทั้งหมด) -->
<!-- ========================================================================= -->
<div class="mb-12">
    <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest">
                <i data-lucide="cpu" class="w-4 h-4"></i>
                <span>Developed Systems & Software Architecture</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-1">
                ผลงานระบบสารสนเทศและแอปพลิเคชันที่พัฒนาขึ้นจริง
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                พัฒนาและดูแลระบบครอบคลุมทั้ง 2 หน่วยงานหลัก (กองการศึกษา ศาสนาและวัฒนธรรม อบจ.นครสวรรค์ & โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์)
            </p>
        </div>

        <!-- Filter Tabs for Organizations & School Divisions (Mobile-First Proportioned Smooth Horizontal Scroll) -->
        <div class="flex items-center gap-1.5 p-1.5 bg-slate-100 dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700 self-stretch md:self-auto overflow-x-auto no-scrollbar scroll-smooth">
            <button onclick="filterProjects('all')" id="tab-all" class="project-tab active h-9 px-3.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm shrink-0 whitespace-nowrap">
                <i data-lucide="grid" class="w-3.5 h-3.5"></i>
                <span>ทั้งหมด (<?= count($featured_projects) ?>)</span>
            </button>
            <button onclick="filterProjects('pao')" id="tab-pao" class="project-tab h-9 px-3.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-blue-600 shrink-0 whitespace-nowrap">
                <i data-lucide="building" class="w-3.5 h-3.5"></i>
                <span>กองการศึกษาฯ อบจ.</span>
            </button>
            <button onclick="filterProjects('skj')" id="tab-skj" class="project-tab h-9 px-3.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-pink-600 shrink-0 whitespace-nowrap">
                <i data-lucide="school" class="w-3.5 h-3.5 text-pink-500"></i>
                <span>รร.สวนกุหลาบฯ</span>
            </button>
            <div class="w-px h-5 bg-slate-300 dark:bg-slate-700 mx-1 shrink-0"></div>
            <button onclick="filterProjects('skj-academic')" id="tab-skj-academic" class="project-tab h-9 px-3 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-blue-600 shrink-0 whitespace-nowrap">
                <i data-lucide="book-open" class="w-3.5 h-3.5 text-blue-500"></i>
                <span>วิชาการ</span>
            </button>
            <button onclick="filterProjects('skj-general')" id="tab-skj-general" class="project-tab h-9 px-3 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-purple-600 shrink-0 whitespace-nowrap">
                <i data-lucide="building-2" class="w-3.5 h-3.5 text-purple-500"></i>
                <span>บริหารทั่วไป</span>
            </button>
            <button onclick="filterProjects('skj-personnel')" id="tab-skj-personnel" class="project-tab h-9 px-3 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-emerald-600 shrink-0 whitespace-nowrap">
                <i data-lucide="users" class="w-3.5 h-3.5 text-emerald-500"></i>
                <span>บุคลากร</span>
            </button>
            <button onclick="filterProjects('skj-budget')" id="tab-skj-budget" class="project-tab h-9 px-3 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 text-slate-600 dark:text-slate-400 hover:text-amber-600 shrink-0 whitespace-nowrap">
                <i data-lucide="pie-chart" class="w-3.5 h-3.5 text-amber-500"></i>
                <span>งบประมาณ</span>
            </button>
        </div>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="projects-container">
        <?php foreach ($featured_projects as $proj): ?>
            <div class="project-card glass-card rounded-3xl border border-slate-200/80 dark:border-slate-800 overflow-hidden hover:shadow-2xl hover:border-blue-400 dark:hover:border-blue-600 transition-all duration-300 flex flex-col justify-between group" data-org="<?= esc($proj['org']) ?>">
                <!-- Card Header Banner -->
                <div class="p-6 bg-gradient-to-r <?= $proj['gradient'] ?> text-white relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                    
                    <div class="flex items-start justify-between gap-2 relative z-10">
                        <div class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white shadow-inner">
                            <i data-lucide="<?= $proj['icon'] ?>" class="w-5 h-5"></i>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <!-- Organization Tag -->
                            <span class="px-2.5 py-0.5 rounded-full bg-white/25 backdrop-blur-md text-[9px] font-black tracking-wider text-white">
                                <?= esc($proj['org_name']) ?>
                            </span>
                            <span class="text-[9px] font-bold text-white/80">
                                <?= esc($proj['stats']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 relative z-10">
                        <span class="text-[10px] font-bold text-white/80 uppercase tracking-widest block truncate">
                            <?= esc($proj['category']) ?>
                        </span>
                        <h3 class="text-base sm:text-lg font-black text-white leading-snug mt-0.5 line-clamp-2">
                            <?= esc($proj['title']) ?>
                        </h3>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                    <div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4 line-clamp-3">
                            <?= esc($proj['description']) ?>
                        </p>

                        <!-- Key Features Checklist -->
                        <div class="space-y-1.5 mb-4 bg-slate-50 dark:bg-slate-900/40 p-3 rounded-2xl border border-slate-100 dark:border-slate-800/80">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">ฟีเจอร์เด่นของระบบ</div>
                            <?php foreach ($proj['features'] as $feat): ?>
                                <div class="text-[11px] font-medium text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500 shrink-0"></i>
                                    <span class="truncate"><?= esc($feat) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Tech Stack Tags -->
                        <div class="flex flex-wrap gap-1.5">
                            <?php foreach ($proj['tech'] as $tech): ?>
                                <span class="px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700">
                                    <?= esc($tech) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Open Button / Main Portal Link -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                        <a href="<?= esc($proj['url']) ?>" <?= strpos($proj['url'], 'http') === 0 ? 'target="_blank"' : '' ?> class="w-full py-2.5 px-4 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 text-slate-700 dark:text-slate-200 font-bold text-xs transition-all flex items-center justify-center gap-2 group-hover:bg-blue-600 group-hover:text-white shadow-sm">
                            <i data-lucide="globe" class="w-3.5 h-3.5"></i>
                            <span>เข้าสู่หน้าหลักระบบงาน</span>
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: SCHOOL 4-DIVISION DIGITAL ARCHITECTURE (บริหารงาน 4 ฝ่าย รร.สวนกุหลาบฯ) -->
<!-- ========================================================================= -->
<div class="mb-12">
    <div class="rounded-3xl bg-gradient-to-r from-slate-900 via-rose-950 to-indigo-950 text-white p-6 sm:p-8 border border-pink-500/20 shadow-xl mb-6 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-pink-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-pink-300 text-xs font-black uppercase tracking-widest mb-1">
                    <i data-lucide="school" class="w-4 h-4"></i>
                    <span>School 4-Division Digital Transformation</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                    โครงสร้างระบบสารสนเทศตามการบริหารงาน 4 ฝ่าย
                </h2>
                <p class="text-xs sm:text-sm text-pink-200 mt-1">
                    โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ • สถาปัตยกรรมระบบครอบคลุม 4 เสาหลักของสถานศึกษา
                </p>
            </div>

            <a href="https://skj.ac.th" target="_blank" class="px-4 py-2 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-xs font-black text-pink-300 shrink-0 flex items-center gap-2 transition-all">
                <i data-lucide="globe" class="w-4 h-4 text-pink-400"></i>
                <span>เว็บหลัก skj.ac.th</span>
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>

    <!-- 4 Division Cards Grid Styled as Full Featured Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($school_divisions as $div): ?>
            <div class="glass-card rounded-3xl border border-slate-200/80 dark:border-slate-800 overflow-hidden hover:shadow-2xl hover:border-<?= $div['color'] ?>-400 transition-all duration-300 flex flex-col justify-between group">
                <!-- Division Card Header Banner -->
                <div class="p-6 bg-gradient-to-r <?= $div['gradient'] ?> text-white relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>

                    <div class="flex items-start justify-between gap-3 relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white shadow-inner group-hover:scale-105 transition-transform">
                            <i data-lucide="<?= $div['icon'] ?>" class="w-6 h-6"></i>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="px-2.5 py-1 rounded-full bg-white/25 backdrop-blur-md text-[10px] font-black tracking-wider text-white">
                                <?= esc($div['badge']) ?>
                            </span>
                            <a href="<?= esc($div['url'] ?? 'https://skj.ac.th') ?>" target="_blank" class="px-2 py-0.5 rounded-lg bg-black/20 hover:bg-black/30 text-[9px] font-bold text-white transition-colors flex items-center gap-1">
                                <span>skj.ac.th</span>
                                <i data-lucide="external-link" class="w-2.5 h-2.5"></i>
                            </a>
                        </div>
                    </div>

                    <div class="mt-4 relative z-10">
                        <span class="text-[10px] font-bold text-white/80 uppercase tracking-widest block truncate">
                            สถาปัตยกรรมระบบสารสนเทศสถานศึกษา
                        </span>
                        <h3 class="text-lg sm:text-xl font-black text-white leading-snug mt-0.5">
                            <?= esc($div['name']) ?>
                        </h3>
                        <p class="text-xs text-white/90 mt-1 font-medium leading-relaxed">
                            <?= esc($div['description']) ?>
                        </p>
                    </div>
                </div>

                <!-- Division Systems Checklist Body -->
                <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                    <div class="space-y-2.5">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i data-lucide="list-checks" class="w-3.5 h-3.5 text-<?= $div['color'] ?>-500"></i>
                            <span>โมดูลและระบบย่อยที่พัฒนาขึ้นในฝ่ายงาน</span>
                        </div>
                        <?php foreach ($div['systems'] as $sys): ?>
                            <div class="p-3 rounded-2xl bg-slate-50/80 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/80 hover:bg-white dark:hover:bg-slate-800/60 transition-colors">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-<?= $div['color'] ?>-500 shrink-0"></i>
                                    <h4 class="text-xs font-black text-slate-800 dark:text-white">
                                        <?= esc($sys['name']) ?>
                                    </h4>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 pl-6 leading-relaxed">
                                    <?= esc($sys['desc']) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Card Footer: Link to Main Portal -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                        <a href="<?= esc($div['url'] ?? 'https://skj.ac.th') ?>" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-<?= $div['color'] ?>-600 hover:text-white dark:hover:bg-<?= $div['color'] ?>-600 text-slate-700 dark:text-slate-200 font-bold text-xs transition-all flex items-center justify-between group-hover:bg-<?= $div['color'] ?>-600 group-hover:text-white shadow-sm">
                            <span class="flex items-center gap-2">
                                <i data-lucide="globe" class="w-4 h-4"></i>
                                <span>เข้าสู่หน้าหลักระบบสารสนเทศ (skj.ac.th)</span>
                            </span>
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: REAL-TIME SERVICE BREAKDOWN & SHOWCASE GALLERY -->
<!-- ========================================================================= -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
    <!-- Category Distribution Breakdown -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 text-xs font-black uppercase tracking-widest">
                    <i data-lucide="pie-chart" class="w-4 h-4"></i>
                    <span>Service Metrics</span>
                </div>
                <span class="text-[10px] font-bold text-slate-400"><?= number_format($total_tasks) ?> รายการทั้งหมด</span>
            </div>
            <h3 class="text-base font-black text-slate-800 dark:text-white mb-4">
                สัดส่วนงานบริการตามหมวดหมู่
            </h3>

            <div class="space-y-3">
                <?php 
                $catColors = [
                    '💻 พัฒนาและบำรุงรักษาระบบสารสนเทศ' => 'indigo',
                    '🛠️ IT Support & Service' => 'blue',
                    '🎤 งานโสตทัศนศึกษา' => 'purple',
                    '📸 ผลิตสื่อและประชาสัมพันธ์' => 'pink',
                    '👥 งานประชุม' => 'amber',
                    '📚 การอบรม/พัฒนาตนเอง' => 'emerald',
                    '🏛️ งานอื่นๆ ตามคำสั่ง' => 'slate',
                    '📊 งานสารสนเทศโรงเรียน' => 'indigo',
                    '📊 งานสารสนเทศโรงเรียนและสำนักฯ' => 'indigo',
                    '🤝 สนับสนุนงานฝ่าย/อาคาร' => 'cyan'
                ];
                foreach ($category_stats as $c): 
                    $percent = $total_tasks > 0 ? round(($c['total'] / $total_tasks) * 100, 1) : 0;
                    $color = $catColors[$c['its_category']] ?? 'blue';
                ?>
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-700 dark:text-slate-300 truncate max-w-[200px]">
                                <?= esc($c['its_category']) ?>
                            </span>
                            <span class="text-slate-500 font-mono"><?= $c['total'] ?> เคส (<?= $percent ?>%)</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                            <div class="bg-<?= $color ?>-500 h-2 rounded-full transition-all duration-500" style="width: <?= $percent ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
            <a href="<?= base_url('itsupport') ?>" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center justify-between">
                <span>เปิดดูไทม์ไลน์บันทึกงานทั้งหมด</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>

    <!-- Showcase Activity Gallery (ภาพถ่ายการปฏิบัติงานจริง) -->
    <div class="lg:col-span-2 glass-card rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 text-xs font-black uppercase tracking-widest">
                    <i data-lucide="camera" class="w-4 h-4"></i>
                    <span>Verified Activities</span>
                </div>
                <span class="text-[10px] font-bold text-slate-400">ภาพถ่ายหน้างานจริง</span>
            </div>
            <h3 class="text-base font-black text-slate-800 dark:text-white mb-4">
                ตัวอย่างภาพถ่ายการปฏิบัติงาน & การให้บริการทางเทคนิค
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <?php 
                $showcaseCount = 0;
                foreach ($showcase_logs as $log): 
                    $images = json_decode($log['its_images'] ?? '[]', true);
                    if (is_array($images) && !empty($images)):
                        $firstImg = $images[0];
                        $showcaseCount++;
                ?>
                    <div class="group relative rounded-2xl overflow-hidden aspect-square bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 shadow-sm">
                        <img loading="lazy" src="<?= base_url('uploads/it_support/' . $firstImg) ?>" alt="<?= esc($log['its_task']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-2.5 flex flex-col justify-end">
                            <span class="text-[9px] font-bold text-cyan-300 truncate"><?= esc($log['its_category']) ?></span>
                            <p class="text-[10px] font-bold text-white line-clamp-2 leading-tight"><?= esc($log['its_task']) ?></p>
                            <span class="text-[8px] text-slate-300 mt-0.5"><?= date('d/m/Y', strtotime($log['its_date'])) ?></span>
                        </div>
                    </div>
                <?php 
                    endif;
                    if ($showcaseCount >= 8) break;
                endforeach; 
                ?>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <span class="text-xs text-slate-400 font-medium">บันทึกรูปภาพและเอกสารรายงานทุกขั้นตอน</span>
            <a href="<?= base_url('itsupport') ?>" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                <span>ดูภาพและใบงานทั้งหมด</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: TECHNICAL SKILLS & PROFICIENCY -->
<!-- ========================================================================= -->
<div class="mb-12">
    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-2 mb-6">
        <div>
            <div class="flex items-center gap-2 text-cyan-600 dark:text-cyan-400 text-xs font-black uppercase tracking-widest">
                <i data-lucide="layers" class="w-4 h-4"></i>
                <span>Technical Stack</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-1">
                ทักษะและความเชี่ยวชาญทางเทคโนโลยี (Technical Skills)
            </h2>
        </div>
        <span class="text-xs text-slate-500">เครื่องมือและเทคโนโลยีที่ใช้งานในการปฏิบัติงานจริง</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Programming & Web Stack -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <i data-lucide="code" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white">Software & Web Stack</h3>
                    <p class="text-[10px] text-slate-400">การพัฒนาโปรแกรมและฐานข้อมูล</p>
                </div>
            </div>

            <div class="space-y-3.5">
                <?php foreach ($skills['programming'] as $s): ?>
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                <i data-lucide="<?= $s['icon'] ?>" class="w-3.5 h-3.5 text-blue-500"></i>
                                <?= esc($s['name']) ?>
                            </span>
                            <span class="text-blue-600 font-mono text-[11px]"><?= $s['level'] ?>%</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full" style="width: <?= $s['level'] ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Infrastructure & Server -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="server" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white">Infrastructure & Hardware</h3>
                    <p class="text-[10px] text-slate-400">เซิร์ฟเวอร์ เครือข่าย และอุปกรณ์</p>
                </div>
            </div>

            <div class="space-y-3.5">
                <?php foreach ($skills['infrastructure'] as $s): ?>
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                <i data-lucide="<?= $s['icon'] ?>" class="w-3.5 h-3.5 text-emerald-500"></i>
                                <?= esc($s['name']) ?>
                            </span>
                            <span class="text-emerald-600 font-mono text-[11px]"><?= $s['level'] ?>%</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-2 rounded-full" style="width: <?= $s['level'] ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Multimedia & Live Broadcasting -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <i data-lucide="video" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white">Media & Broadcasting</h3>
                    <p class="text-[10px] text-slate-400">โสตทัศน์ กราฟิก และการถ่ายทอดสด</p>
                </div>
            </div>

            <div class="space-y-3.5">
                <?php foreach ($skills['multimedia'] as $s): ?>
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                <i data-lucide="<?= $s['icon'] ?>" class="w-3.5 h-3.5 text-purple-500"></i>
                                <?= esc($s['name']) ?>
                            </span>
                            <span class="text-purple-600 font-mono text-[11px]"><?= $s['level'] ?>%</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-600 h-2 rounded-full" style="width: <?= $s['level'] ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: CONTACT & SERVICE REQUEST (ช่องทางการติดต่อ / ขอรับบริการ) -->
<!-- ========================================================================= -->
<div class="rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 text-white p-8 sm:p-10 shadow-2xl relative overflow-hidden mb-12">
    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
        <div class="space-y-2 max-w-2xl">
            <span class="px-3 py-1 rounded-full bg-white/20 text-[11px] font-black uppercase tracking-wider inline-block">
                IT Service & Collaboration
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                ต้องการคำปรึกษาทางเทคนิค หรือแจ้งขอรับบริการ IT Support?
            </h2>
            <p class="text-xs sm:text-sm text-blue-100">
                พร้อมให้บริการและสนับสนุนการปฏิบัติงานของทุกฝ่าย ทั้งการแก้ไขปัญหาอุปกรณ์ ระบบเครือข่าย พัฒนาระบบสารสนเทศ และงานโสตทัศนูปกรณ์
            </p>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3 shrink-0">
            <a href="<?= base_url('itsupport') ?>" class="px-6 py-3.5 rounded-2xl bg-white hover:bg-blue-50 text-blue-700 font-black text-xs sm:text-sm shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>ไปที่ IT Support Portal</span>
            </a>
            <?php if (!empty($officer['u_phone'])): ?>
                <a href="tel:<?= esc($officer['u_phone']) ?>" class="px-5 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/30 text-white font-bold text-xs sm:text-sm transition-all flex items-center gap-2">
                    <i data-lucide="phone-call" class="w-4 h-4"></i>
                    <span>โทรติดต่อ</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- FOOTER INFO -->
<div class="text-center pb-8 text-xs text-slate-400 space-y-1">
    <p class="font-bold">แฟ้มสะสมผลงานอิเล็กทรอนิกส์ (E-Portfolio) & ข้อตกลงการปฏิบัติงาน (MOU 2569)</p>
    <p>กองการศึกษา ศาสนาและวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์ • โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
</div>

<!-- Custom CSS for Mobile Touch & Print -->
<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
/* Hide scrollbar for IE, Edge and Firefox */
.no-scrollbar {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}

@media print {
    body {
        background: #ffffff !important;
        color: #000000 !important;
        font-size: 12pt !important;
    }
    aside, header, #theme-toggle, button, a[href*="logout"], .btn-action, .project-tab {
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

<!-- Tab Filtering Script -->
<script>
function filterProjects(org) {
    const cards = document.querySelectorAll('.project-card');
    const tabs = document.querySelectorAll('.project-tab');

    tabs.forEach(tab => {
        tab.classList.remove('bg-white', 'dark:bg-slate-900', 'text-blue-600', 'dark:text-blue-400', 'shadow-sm', 'text-pink-600', 'text-emerald-600', 'text-purple-600', 'text-amber-600');
        tab.classList.add('text-slate-600', 'dark:text-slate-400');
    });

    const activeTab = document.getElementById('tab-' + org);
    if (activeTab) {
        activeTab.classList.remove('text-slate-600', 'dark:text-slate-400');
        activeTab.classList.add('bg-white', 'dark:bg-slate-900', 'shadow-sm');
        
        if (org === 'skj' || org === 'skj-general') {
            activeTab.classList.add('text-pink-600');
        } else if (org === 'skj-personnel') {
            activeTab.classList.add('text-emerald-600');
        } else if (org === 'skj-budget') {
            activeTab.classList.add('text-amber-600');
        } else {
            activeTab.classList.add('text-blue-600', 'dark:text-blue-400');
        }
    }

    cards.forEach(card => {
        const cardOrg = card.getAttribute('data-org') || '';
        if (org === 'all') {
            card.style.display = 'flex';
        } else if (org === 'skj') {
            card.style.display = cardOrg.startsWith('skj') ? 'flex' : 'none';
        } else if (cardOrg === org) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
<?= $this->endSection() ?>
