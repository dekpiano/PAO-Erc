<?= $this->extend('sports/public/layout/main') ?>

<?= $this->section('content') ?>
<?php
$sysSettings     = isset($systemSettings) && is_array($systemSettings) ? $systemSettings : [];
$effectiveStatus = $sysSettings['effective_status'] ?? 'open';
$isSystemOpen    = ($effectiveStatus === 'open');
$statusNotice    = $sysSettings['status_notice'] ?? '';
$hasAnnouncement = (!empty($sysSettings['system_announcement_active']) && $sysSettings['system_announcement_active'] === '1' && !empty($sysSettings['system_announcement']));
$annType         = $sysSettings['system_announcement_type'] ?? 'info';
?>

<div class="min-h-screen pb-24 pt-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="p-4 sm:p-5 bg-emerald-50/90 backdrop-blur-md border border-emerald-200 text-emerald-900 rounded-3xl text-xs sm:text-sm font-bold flex items-center gap-3 shadow-md shadow-emerald-500/5">
                <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="check" class="w-4 h-4"></i>
                </div>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="p-4 sm:p-5 bg-rose-50/90 backdrop-blur-md border border-rose-200 text-rose-900 rounded-3xl text-xs sm:text-sm font-bold flex items-center gap-3 shadow-md shadow-rose-500/5">
                <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                </div>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Announcement Banner (If Enabled) -->
        <?php if ($hasAnnouncement): ?>
            <div class="p-4 sm:p-5 rounded-3xl border shadow-sm flex items-start sm:items-center justify-between gap-4 transition-all <?= $annType === 'warning' ? 'bg-gradient-to-r from-amber-50 via-amber-50/90 to-amber-100/70 border-amber-200 text-amber-950 shadow-amber-500/5' : ($annType === 'success' ? 'bg-gradient-to-r from-emerald-50 via-teal-50/90 to-emerald-100/70 border-emerald-200 text-emerald-950 shadow-emerald-500/5' : 'bg-gradient-to-r from-blue-50 via-indigo-50/90 to-sky-100/70 border-blue-200 text-blue-950 shadow-blue-500/5') ?>">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 font-bold <?= $annType === 'warning' ? 'bg-amber-400 text-slate-950 shadow-md shadow-amber-300/40' : ($annType === 'success' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-300/40' : 'bg-blue-600 text-white shadow-md shadow-blue-300/40') ?>">
                        <i data-lucide="<?= $annType === 'warning' ? 'bell-ring' : ($annType === 'success' ? 'sparkles' : 'megaphone') ?>" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider block <?= $annType === 'warning' ? 'text-amber-800' : ($annType === 'success' ? 'text-emerald-800' : 'text-blue-800') ?>">
                            📢 ข่าวสาร & ประกาศสำคัญ
                        </span>
                        <p class="text-xs sm:text-sm font-bold leading-snug">
                            <?= esc($sysSettings['system_announcement']) ?>
                        </p>
                    </div>
                </div>
                <div class="hidden sm:block shrink-0">
                    <span class="px-3 py-1 rounded-full text-[11px] font-black border <?= $annType === 'warning' ? 'bg-amber-200/60 text-amber-900 border-amber-300' : ($annType === 'success' ? 'bg-emerald-200/60 text-emerald-900 border-emerald-300' : 'bg-blue-200/60 text-blue-900 border-blue-300') ?>">
                        ข้อมูลล่าสุด
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <!-- System Status Alert Notice Banner (When NOT OPEN) -->
        <?php if (!$isSystemOpen): ?>
            <div class="rounded-3xl p-6 sm:p-8 border shadow-lg space-y-3 <?= $effectiveStatus === 'closed' ? 'bg-gradient-to-br from-rose-600 via-rose-700 to-rose-800 text-white border-rose-500 shadow-rose-900/15' : ($effectiveStatus === 'maintenance' ? 'bg-gradient-to-br from-amber-500 via-amber-600 to-amber-700 text-white border-amber-400 shadow-amber-900/15' : 'bg-gradient-to-br from-sky-600 via-blue-700 to-indigo-800 text-white border-sky-500 shadow-blue-900/15') ?>">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center font-black text-white shrink-0 shadow-inner">
                            <i data-lucide="<?= $effectiveStatus === 'closed' ? 'lock' : ($effectiveStatus === 'maintenance' ? 'wrench' : 'calendar-clock') ?>" class="w-7 h-7"></i>
                        </div>
                        <div class="space-y-1">
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-white/20 backdrop-blur-sm">
                                <span class="w-2 h-2 rounded-full <?= $effectiveStatus === 'closed' ? 'bg-rose-200' : ($effectiveStatus === 'maintenance' ? 'bg-amber-200' : 'bg-sky-200') ?>"></span>
                                <?= $effectiveStatus === 'closed' ? 'ระบบปิดรับสมัคร' : ($effectiveStatus === 'maintenance' ? 'ปิดปรับปรุงระบบชั่วคราว' : 'ยังไม่ถึงกำหนดเปิดรับสมัคร') ?>
                            </span>
                            <h2 class="text-xl sm:text-2xl font-black tracking-tight">
                                <?= $effectiveStatus === 'closed' ? 'ขณะนี้ระบบปิดรับสมัครการแข่งขันกีฬาแล้ว' : ($effectiveStatus === 'maintenance' ? 'ระบบกำลังอยู่ระหว่างการปิดปรับปรุงข้อมูล' : 'ระบบยังไม่เปิดรับสมัครในขณะนี้') ?>
                            </h2>
                        </div>
                    </div>

                    <!-- Action buttons that still work -->
                    <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
                        <a href="<?= base_url('sports/status') ?>" class="px-4 py-2.5 bg-white text-slate-900 hover:bg-slate-100 rounded-xl font-black text-xs flex items-center gap-1.5 shadow-md transition-all">
                            <i data-lucide="search" class="w-4 h-4 text-emerald-600"></i>
                            <span>ตรวจสอบสถานะ</span>
                        </a>
                        <a href="<?= base_url('sports/certificate') ?>" class="px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 backdrop-blur-md transition-all">
                            <i data-lucide="award" class="w-4 h-4 text-amber-300"></i>
                            <span>เกียรติบัตร</span>
                        </a>
                    </div>
                </div>

                <p class="text-white/90 text-xs sm:text-sm leading-relaxed max-w-4xl pt-1">
                    <?= esc($statusNotice ?: ($effectiveStatus === 'closed' ? 'ขอขอบคุณทุกโรงเรียนและสถานศึกษาที่ให้ความสนใจส่งทีมเข้าร่วมการแข่งขัน ทั้งนี้ โรงเรียนที่ลงทะเบียนแล้วยังคงสามารถตรวจสอบสถานะการสมัครและดาวน์โหลดเกียรติบัตรได้ตามปกติ' : 'ขออภัยในความไม่สะดวก กรุณาติดตามประกาศกำหนดการเปิดระบบใหม่อีกครั้ง')) ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-emerald-700 via-teal-800 to-slate-900 rounded-3xl p-8 sm:p-12 text-white shadow-2xl overflow-hidden border border-emerald-600/30">
            <!-- Decorative Graphics -->
            <div class="absolute -right-16 -bottom-16 opacity-10 pointer-events-none">
                <i data-lucide="trophy" class="w-[28rem] h-[28rem]"></i>
            </div>
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-3xl space-y-5">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/15 backdrop-blur-md rounded-full text-xs sm:text-sm font-semibold border border-white/20">
                    <i data-lucide="sparkles" class="w-4 h-4 text-amber-300"></i>
                    <span>ระบบลงทะเบียนการแข่งขันกีฬา อบจ.นครสวรรค์ เกมส์ ประจำปี <?= esc($activeCompYear ?? '') ?></span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
                    <?= $isSystemOpen ? 'เปิดรับสมัครเข้าร่วมการแข่งขันกีฬา' : 'การแข่งขันกีฬา' ?> <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-emerald-200 to-teal-100">
                        อบจ.นครสวรรค์ เกมส์ <?= esc($activeCompYear ?? '') ?>
                    </span>
                </h1>

                <p class="text-emerald-100/90 text-sm sm:text-base leading-relaxed">
                    ขอเชิญโรงเรียนและสถานศึกษาในจังหวัดนครสวรรค์ ส่งทีมนักกีฬาเข้าร่วมแข่งขันในชนิดกีฬาและรุ่นอายุต่าง ๆ
                    พร้อมระบบตรวจสอบสถานะ ประกาศผล และดาวน์โหลดเกียรติบัตรออนไลน์
                </p>

                <!-- Action Button Group -->
                <div class="pt-3 flex flex-wrap items-center gap-3">
                    <?php if ($isSystemOpen): ?>
                        <a href="#sports-list"
                            class="px-6 py-3.5 bg-amber-400 hover:bg-amber-500 text-slate-950 rounded-2xl font-extrabold text-sm flex items-center gap-2 shadow-lg shadow-amber-400/20 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            <span>เลือกลงทะเบียนกีฬา</span>
                        </a>
                    <?php else: ?>
                        <a href="#sports-list"
                            class="px-6 py-3.5 bg-white/20 hover:bg-white/30 text-white rounded-2xl font-bold text-sm flex items-center gap-2 transition-all">
                            <i data-lucide="layers" class="w-4 h-4"></i>
                            <span>ดูรายการกีฬา & รุ่น</span>
                        </a>
                    <?php endif; ?>

                    <a href="<?= base_url('sports/status') ?>"
                        class="px-5 py-3.5 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/25 rounded-2xl font-bold text-sm flex items-center gap-2 transition-all">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>ตรวจสอบสถานะการสมัคร</span>
                    </a>

                    <a href="<?= base_url('sports/certificate') ?>"
                        class="px-5 py-3.5 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/25 rounded-2xl font-bold text-sm flex items-center gap-2 transition-all">
                        <i data-lucide="award" class="w-4 h-4 text-amber-300"></i>
                        <span>ค้นหาเกียรติบัตร</span>
                    </a>

                    <a href="<?= base_url('sports/results') ?>"
                        class="px-5 py-3.5 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/25 rounded-2xl font-bold text-sm flex items-center gap-2 transition-all">
                        <i data-lucide="medal" class="w-4 h-4 text-emerald-300"></i>
                        <span>ผลการแข่งขัน</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 3 Quick Registration Steps Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-all space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black text-lg">
                    1
                </div>
                <h3 class="font-black text-slate-900 text-base">เลือกรุ่น & เช็คคุณสมบัติ</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    ตรวจสอบเกณฑ์อายุ จำนวนผู้เล่น และระเบียบการแข่งขันของแต่ละชนิดกีฬาที่สถานศึกษาต้องการส่งเข้าร่วม
                </p>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-all space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-black text-lg">
                    2
                </div>
                <h3 class="font-black text-slate-900 text-base">กรอกข้อมูล & ส่งใบสมัคร</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    กรอกข้อมูลโรงเรียน ข้อมูลผู้ประสานงาน และรายชื่อนักกีฬา/ผู้ฝึกสอนผ่านระบบออนไลน์ได้ทันที
                </p>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-all space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-black text-lg">
                    3
                </div>
                <h3 class="font-black text-slate-900 text-base">รับรหัสทีม & ติดตามผล</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    รับรหัสติดตามใบสมัคร (Team Code) พิมพ์ใบสมัครเข้าร่วม และดาวน์โหลดเกียรติบัตรออนไลน์หลังจบการแข่งขัน
                </p>
            </div>
        </div>

        <!-- Sports Category List & Interactive Filter Section -->
        <div id="sports-list" class="space-y-6 pt-4">
            
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 flex items-center gap-2.5">
                        <i data-lucide="medal" class="w-6 h-6 text-emerald-600"></i>
                        <span>รายการกีฬาและรุ่นการแข่งขัน (ประจำปี <?= esc($activeCompYear ?? '') ?>)</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        <?= $isSystemOpen ? 'เลือกชนิดกีฬาและรุ่นอายุที่โรงเรียนต้องการส่งเข้าร่วมแข่งขัน' : 'รายการชนิดกีฬาและรุ่นอายุประจำปีการแข่งขันนี้' ?>
                    </p>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
                    <?php if (!empty($availableYears) && count($availableYears) > 1): ?>
                        <div class="flex items-center gap-1.5 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold">
                            <span class="text-slate-500">ปีการแข่งขัน:</span>
                            <select onchange="window.location.href='<?= base_url('sports?year=') ?>' + this.value" class="bg-transparent font-black text-slate-800 outline-none cursor-pointer">
                                <?php foreach ($availableYears as $yr): ?>
                                    <option value="<?= $yr ?>" <?= $yr == $activeCompYear ? 'selected' : '' ?>>
                                        ปี <?= $yr ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div id="categoryCountBadge" class="text-xs font-black <?= $isSystemOpen ? 'text-emerald-800 bg-emerald-50 border-emerald-200' : 'text-slate-700 bg-slate-100 border-slate-200' ?> border px-3.5 py-1.5 rounded-xl">
                        <?= $isSystemOpen ? 'เปิดรับสมัคร ' . count($categories) . ' รายการ' : 'ทั้งหมด ' . count($categories) . ' รายการ' ?>
                    </div>
                </div>
            </div>

            <?php if (empty($categories)): ?>
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto text-slate-400 mb-4">
                        <i data-lucide="calendar-x" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">ยังไม่มีรายการกีฬาในขณะนี้</h3>
                    <p class="text-xs text-slate-400 mt-1">กรุณาติดตามข่าวสารประกาศการรับสมัครเร็ว ๆ นี้</p>
                </div>
            <?php else: ?>
                <?php
                // Group categories by sport_name
                $sportsGrouped = [];
                foreach ($categories as $cat) {
                    $sName = !empty($cat['sport_name']) ? trim($cat['sport_name']) : 'กีฬาอื่นๆ';
                    $sportsGrouped[$sName][] = $cat;
                }
                ?>

                <!-- Interactive Instant Search & Gender/Type Filter Toolbar -->
                <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                        
                        <!-- Search Box -->
                        <div class="sm:col-span-6 relative">
                            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                            <input type="text" id="categorySearchInput" oninput="applyFilters()" 
                                   placeholder="พิมพ์ค้นหาชื่อกีฬา, รุ่นอายุ (เช่น ฟุตซอล, 12 ปี, หญิง)..." 
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                        </div>

                        <!-- Gender Filter -->
                        <div class="sm:col-span-3">
                            <select id="genderFilterSelect" onchange="applyFilters()" 
                                    class="w-full px-4 py-3 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white cursor-pointer">
                                <option value="all">เพศ: ทั้งหมด</option>
                                <option value="male">เพศ: ชาย</option>
                                <option value="female">เพศ: หญิง</option>
                                <option value="mixed">เพศ: ผสม</option>
                            </select>
                        </div>

                        <!-- Type Filter -->
                        <div class="sm:col-span-3">
                            <select id="typeFilterSelect" onchange="applyFilters()" 
                                    class="w-full px-4 py-3 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white cursor-pointer">
                                <option value="all">ประเภท: ทั้งหมด</option>
                                <option value="team">ประเภททีม</option>
                                <option value="pair">ประเภทคู่</option>
                                <option value="single">ประเภทเดี่ยว</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sport Filter Tabs -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none pt-1 border-t border-slate-100">
                        <button type="button" onclick="filterSportTab('all', this)" 
                                class="sport-tab-btn px-4 py-2 rounded-2xl font-black text-xs transition-all whitespace-nowrap bg-emerald-600 text-white shadow-md shadow-emerald-200 cursor-pointer">
                            ทั้งหมด (<?= count($categories) ?>)
                        </button>
                        <?php foreach ($sportsGrouped as $sName => $sCats): ?>
                            <button type="button" onclick="filterSportTab('<?= esc($sName, 'js') ?>', this)" 
                                    class="sport-tab-btn px-4 py-2 rounded-2xl font-bold text-xs transition-all whitespace-nowrap bg-slate-50 text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 border border-slate-200/80 cursor-pointer">
                                <?= esc($sName) ?> (<?= count($sCats) ?>)
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Grouped Sport Sections Container -->
                <div class="space-y-10" id="sports-container">
                    <?php foreach ($sportsGrouped as $sName => $sCats): ?>
                        <div class="sport-group-section space-y-4" data-sport="<?= esc($sName) ?>">
                            
                            <!-- Sport Category Banner Header -->
                            <div class="bg-gradient-to-r from-emerald-800 via-teal-800 to-slate-900 rounded-2xl p-4 sm:p-5 text-white shadow-md flex items-center justify-between gap-3 border-l-4 border-amber-400">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md text-amber-300 flex items-center justify-center font-black shadow-xs shrink-0">
                                        <i data-lucide="trophy" class="w-5 h-5"></i>
                                    </div>
                                    <div class="truncate">
                                        <span class="text-[10px] font-bold text-emerald-200 uppercase tracking-wider block">ชนิดกีฬา</span>
                                        <h3 class="text-lg sm:text-xl font-black text-white tracking-tight truncate">
                                            <?= esc($sName) ?>
                                        </h3>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="px-3 py-1 bg-white/10 text-emerald-100 rounded-xl text-xs font-black border border-white/20">
                                        <?= count($sCats) ?> รุ่นการแข่งขัน
                                    </span>
                                </div>
                            </div>

                            <!-- Cards Grid for this Sport -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                                <?php foreach ($sCats as $cat): ?>
                                    <?php
                                    $isFull       = ($cat['max_teams'] > 0 && $cat['registered_teams'] >= $cat['max_teams']);
                                    $today        = date('Y-m-d');
                                    $isExpired    = (!empty($cat['reg_end_date']) && $today > $cat['reg_end_date']);
                                    $isNotStarted = (!empty($cat['reg_start_date']) && $today < $cat['reg_start_date']);
                                    $isClosed     = ($cat['status'] === 'closed');
                                    $isDraft      = ($cat['status'] === 'draft');
                                    $quotaPercent = ($cat['max_teams'] > 0) ? min(100, round(($cat['registered_teams'] / $cat['max_teams']) * 100)) : 0;
                                    ?>
                                    <div class="category-card bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-xs hover:shadow-xl hover:border-emerald-300 transition-all flex flex-col justify-between group relative"
                                         data-sport-name="<?= esc($cat['sport_name']) ?>"
                                         data-cat-name="<?= esc($cat['category_name']) ?>"
                                         data-gender="<?= esc($cat['category_gender']) ?>"
                                         data-type="<?= esc($cat['category_type']) ?>">
                                        
                                        <div class="space-y-4">
                                            <!-- Prominent Sport Name & Gender Badge -->
                                            <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-3">
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-gradient-to-r from-emerald-700 to-teal-700 text-white font-black text-xs tracking-wide shadow-xs">
                                                    <i data-lucide="award" class="w-3.5 h-3.5 text-amber-300 shrink-0"></i>
                                                    <span><?= esc($cat['sport_name']) ?></span>
                                                </div>
                                                <span class="px-2.5 py-1 rounded-xl text-[11px] font-black shrink-0 <?= $cat['category_gender'] === 'female' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($cat['category_gender'] === 'mixed' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200') ?>">
                                                    <?= $cat['category_gender'] === 'female' ? 'หญิง' : ($cat['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>
                                                </span>
                                            </div>

                                            <!-- Prominent Category Name -->
                                            <div>
                                                <h4 class="text-base sm:text-lg font-black text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug">
                                                    <?= (mb_strpos(trim($cat['category_name']), 'รุ่น') === 0 ? '' : 'รุ่น ') . esc($cat['category_name']) ?>
                                                </h4>
                                                <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mt-1">
                                                    <span class="inline-flex items-center gap-1">
                                                        <i data-lucide="tag" class="w-3 h-3 text-slate-400"></i>
                                                        <span><?= $cat['category_type'] === 'team' ? 'ประเภททีม' : ($cat['category_type'] === 'pair' ? 'ประเภทคู่' : 'ประเภทเดี่ยว') ?></span>
                                                    </span>
                                                    <?php if ($cat['age_min'] > 0 || ($cat['age_max'] > 0 && $cat['age_max'] < 99)): ?>
                                                        <span>•</span>
                                                        <span>อายุ <?= $cat['age_min'] ?> - <?= $cat['age_max'] ?> ปี</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Quota Stats Box -->
                                            <div class="py-3 px-3.5 bg-slate-50 rounded-2xl space-y-2 border border-slate-100">
                                                <div class="grid grid-cols-2 gap-2 text-xs text-slate-600 font-medium">
                                                    <div class="flex items-center gap-1.5 truncate">
                                                        <i data-lucide="users" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                                                        <span class="truncate">ผู้เล่น: <strong class="text-slate-900 font-bold"><?= $cat['min_players'] ?>-<?= $cat['max_players'] ?></strong> คน</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5 truncate">
                                                        <i data-lucide="user-check" class="w-3.5 h-3.5 text-teal-600 shrink-0"></i>
                                                        <span class="truncate">โค้ช: <strong class="text-slate-900 font-bold"><?= $cat['min_coaches'] ?>-<?= $cat['max_coaches'] ?></strong> คน</span>
                                                    </div>
                                                </div>

                                                <!-- Quota Progress Bar -->
                                                <?php if ($cat['max_teams'] > 0): ?>
                                                    <div class="space-y-1 pt-1 border-t border-slate-200/60">
                                                        <div class="flex justify-between text-[11px] font-bold">
                                                            <span class="text-slate-500">จำนวนที่รับ:</span>
                                                            <span class="<?= $isFull ? 'text-rose-600 font-black' : 'text-slate-800' ?>">
                                                                <?= number_format($cat['registered_teams']) ?> / <?= $cat['max_teams'] ?> ทีม (<?= $quotaPercent ?>%)
                                                            </span>
                                                        </div>
                                                        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                                            <div class="h-full <?= $isFull ? 'bg-rose-500' : ($quotaPercent > 80 ? 'bg-amber-500' : 'bg-emerald-500') ?> rounded-full transition-all" style="width: <?= $quotaPercent ?>%"></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Rules preview button (if rules exist) -->
                                            <?php if (!empty($cat['rules_detail']) || !empty($cat['rules_file'])): ?>
                                                <button type="button" onclick="showCategoryRules(<?= htmlspecialchars(json_encode($cat), ENT_QUOTES, 'UTF-8') ?>)" 
                                                        class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 hover:underline cursor-pointer">
                                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                                    <span>อ่านระเบียบการและกติกา</span>
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Card Footer & Action Button -->
                                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                                            <div class="text-xs">
                                                <?php if (!$isSystemOpen): ?>
                                                    <span class="text-slate-500 font-bold bg-slate-100 px-2.5 py-1 rounded-lg">
                                                        <?= $effectiveStatus === 'closed' ? 'ปิดรับสมัคร' : ($effectiveStatus === 'maintenance' ? 'ปรับปรุงระบบ' : 'ยังไม่เปิดรับ') ?>
                                                    </span>
                                                <?php elseif ($isClosed): ?>
                                                    <span class="text-rose-600 font-bold bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200">
                                                        ปิดรับสมัครแล้ว
                                                    </span>
                                                <?php elseif ($isDraft): ?>
                                                    <span class="text-slate-500 font-bold bg-slate-100 px-2.5 py-1 rounded-lg">
                                                        แบบร่าง (ยังไม่เปิด)
                                                    </span>
                                                <?php elseif ($isFull): ?>
                                                    <span class="text-rose-600 font-bold bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200 flex items-center gap-1">
                                                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                                        <span>เต็มโควตา</span>
                                                    </span>
                                                <?php elseif ($isExpired): ?>
                                                    <span class="text-slate-500 font-bold bg-slate-100 px-2.5 py-1 rounded-lg">
                                                        ปิดรับสมัคร
                                                    </span>
                                                <?php elseif ($isNotStarted): ?>
                                                    <span class="text-amber-600 font-bold bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">
                                                        เปิด <?= date('d/m/Y', strtotime($cat['reg_start_date'])) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-slate-500 font-medium">
                                                        สมัครแล้ว <strong class="text-emerald-700 font-black text-sm"><?= number_format($cat['registered_teams']) ?></strong> ทีม
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <?php if (!$isSystemOpen): ?>
                                                <button disabled class="px-4 py-2 bg-slate-100 text-slate-400 rounded-xl font-bold text-xs cursor-not-allowed">
                                                    <?= $effectiveStatus === 'closed' ? 'ระบบปิดรับแล้ว' : ($effectiveStatus === 'maintenance' ? 'ปิดปรับปรุง' : 'ยังไม่เปิด') ?>
                                                </button>
                                            <?php elseif ($isClosed || $isDraft || $isFull || $isExpired): ?>
                                                <button disabled
                                                    class="px-4 py-2 bg-slate-100 text-slate-400 rounded-xl font-bold text-xs cursor-not-allowed">
                                                    <?= $isClosed ? 'ปิดรับแล้ว' : ($isDraft ? 'แบบร่าง' : ($isFull ? 'เต็มแล้ว' : 'ปิดรับ')) ?>
                                                </button>
                                            <?php elseif ($isNotStarted): ?>
                                                <button disabled
                                                    class="px-4 py-2 bg-slate-100 text-amber-600 rounded-xl font-bold text-xs cursor-not-allowed">
                                                    เร็วๆ นี้
                                                </button>
                                            <?php else: ?>
                                                <a href="<?= base_url('sports/register/' . $cat['category_id']) ?>"
                                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black text-xs flex items-center gap-1.5 shadow-sm shadow-emerald-200 hover:scale-105 active:scale-95 transition-all cursor-pointer">
                                                    <span>สมัครแข่งขัน</span>
                                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="noResultsFoundBox" class="hidden bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm space-y-3">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto text-slate-400">
                        <i data-lucide="search-x" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-700">ไม่พบรายการกีฬาที่ตรงกับเงื่อนไขการค้นหา</h3>
                    <p class="text-xs text-slate-400">กรุณาลองปรับเปลี่ยนคำค้นหา หรือล้างตัวกรองเพื่อดูรายการทั้งหมด</p>
                    <button type="button" onclick="clearFilters()" class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 rounded-xl text-xs font-bold transition-colors">
                        ล้างตัวกรองทั้งหมด
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- FAQ & Guidelines Section -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="text-xs font-black text-emerald-600 uppercase tracking-wider block mb-1">คำถามที่พบบ่อย & ข้อแนะนำ</span>
                <h3 class="text-xl sm:text-2xl font-black text-slate-900">คำถามที่พบบ่อยในการสมัครแข่งขัน</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                    <h4 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <i data-lucide="help-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <span>นักกีฬา 1 คน สมัครได้กี่ประเภท?</span>
                    </h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        นักกีฬาสามารถลงแข่งขันได้หลายชนิดกีฬา แต่ไม่อนุญาตให้ลงแข่งขันซ้ำในรุ่นอายุเดียวกันของชนิดกีฬาเดียวกัน
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                    <h4 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <i data-lucide="help-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <span>หลังจากส่งใบสมัครแล้ว ต้องทำอย่างไรต่อ?</span>
                    </h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        บันทึกรหัสติดตามทีม (Team Code) และพิมพ์ใบสมัครพร้อมแนบหลักฐานเอกสารเพื่อนำส่งในวันรายงานตัวหรือตามที่ฝ่ายจัดการแข่งขันกำหนด
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                    <h4 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <i data-lucide="help-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <span>ตรวจสอบผลการแข่งขันได้ที่ไหน?</span>
                    </h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        สามารถตรวจสอบผลคะแนนและอันดับรางวัลได้ที่เมนู "ผลการแข่งขัน" บนหน้าเว็บไซต์ได้ตลอด 24 ชั่วโมง
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                    <h4 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <i data-lucide="help-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <span>เกียรติบัตรออนไลน์ดาวน์โหลดได้เมื่อไหร่?</span>
                    </h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        เมื่อฝ่ายจัดการแข่งขันทำการตรวจสอบและอนุมัติผลการแข่งขันเรียบร้อยแล้ว นักกีฬาและสถานศึกษาสามารถค้นหาชื่อและดาวน์โหลดเกียรติบัตร PDF ได้ทันที
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Coordinator Info Card -->
        <?php if (!empty($sysSettings['contact_name']) || !empty($sysSettings['contact_phone']) || !empty($sysSettings['contact_line'])): ?>
            <div class="bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6 border border-emerald-700/50">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center font-bold shrink-0 text-amber-300 shadow-inner">
                        <i data-lucide="headphones" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-black text-emerald-300 uppercase tracking-wider block">ช่องทางติดต่อสอบถาม & ประสานงาน</span>
                        <h3 class="text-lg font-black text-white">
                            <?= esc($sysSettings['contact_name'] ?: 'ฝ่ายจัดการแข่งขันกีฬา อบจ.นครสวรรค์') ?>
                        </h3>
                        <p class="text-xs text-emerald-200/80 mt-0.5">สอบถามข้อมูลเพิ่มเติมเกี่ยวกับระเบียบการแข่งขันและการส่งเอกสารหลักฐาน</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <?php if (!empty($sysSettings['contact_phone'])): ?>
                        <a href="tel:<?= esc($sysSettings['contact_phone']) ?>" class="px-5 py-3 bg-white text-slate-900 hover:bg-slate-100 rounded-2xl text-xs font-black flex items-center gap-2 shadow-md transition-all hover:scale-105">
                            <i data-lucide="phone-call" class="w-4 h-4 text-emerald-600"></i>
                            <span><?= esc($sysSettings['contact_phone']) ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($sysSettings['contact_line'])): ?>
                        <div class="px-5 py-3 bg-emerald-600 text-white rounded-2xl text-xs font-black flex items-center gap-2 shadow-md">
                            <i data-lucide="message-circle" class="w-4 h-4 text-amber-300"></i>
                            <span>Line: <?= esc($sysSettings['contact_line']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Category Rules Modal -->
<div id="rulesModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 id="modalRulesTitle" class="font-black text-slate-900 text-base">ระเบียบการแข่งขัน</h3>
                    <p id="modalRulesSubtitle" class="text-xs text-slate-400"></p>
                </div>
            </div>
            <button type="button" onclick="closeRulesModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-50 cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div id="modalRulesContent" class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
            <!-- Rules Text Dynamic -->
        </div>

        <div id="modalRulesFileSection" class="hidden pt-2">
            <a id="modalRulesFileLink" href="#" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black flex items-center justify-center gap-2 transition-all">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>ดาวน์โหลดไฟล์ระเบียบการ (PDF)</span>
            </a>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" onclick="closeRulesModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-colors cursor-pointer">
                ปิดหน้าต่าง
            </button>
        </div>
    </div>
</div>

<script>
let currentSportFilter = 'all';

function filterSportTab(sportName, btn) {
    currentSportFilter = sportName;
    document.querySelectorAll('.sport-tab-btn').forEach(b => {
        b.className = 'sport-tab-btn px-4 py-2 rounded-2xl font-bold text-xs transition-all whitespace-nowrap bg-slate-50 text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 border border-slate-200/80 cursor-pointer';
    });
    btn.className = 'sport-tab-btn px-4 py-2 rounded-2xl font-black text-xs transition-all whitespace-nowrap bg-emerald-600 text-white shadow-md shadow-emerald-200 cursor-pointer';
    applyFilters();
}

function applyFilters() {
    const searchVal  = (document.getElementById('categorySearchInput')?.value || '').trim().toLowerCase();
    const genderVal  = document.getElementById('genderFilterSelect')?.value || 'all';
    const typeVal    = document.getElementById('typeFilterSelect')?.value || 'all';

    let totalVisible = 0;

    document.querySelectorAll('.sport-group-section').forEach(sec => {
        const groupSportName = sec.getAttribute('data-sport') || '';
        let groupHasVisibleCards = false;

        const isSportMatched = (currentSportFilter === 'all' || groupSportName === currentSportFilter);

        sec.querySelectorAll('.category-card').forEach(card => {
            if (!isSportMatched) {
                card.style.display = 'none';
                return;
            }

            const cSport  = (card.getAttribute('data-sport-name') || '').toLowerCase();
            const cName   = (card.getAttribute('data-cat-name') || '').toLowerCase();
            const cGender = card.getAttribute('data-gender') || '';
            const cType   = card.getAttribute('data-type') || '';

            const matchSearch = (!searchVal || cSport.includes(searchVal) || cName.includes(searchVal));
            const matchGender = (genderVal === 'all' || cGender === genderVal);
            const matchType   = (typeVal === 'all' || cType === typeVal);

            if (matchSearch && matchGender && matchType) {
                card.style.display = 'flex';
                groupHasVisibleCards = true;
                totalVisible++;
            } else {
                card.style.display = 'none';
            }
        });

        if (isSportMatched && groupHasVisibleCards) {
            sec.style.display = 'block';
        } else {
            sec.style.display = 'none';
        }
    });

    const noResultsBox = document.getElementById('noResultsFoundBox');
    if (noResultsBox) {
        if (totalVisible === 0) {
            noResultsBox.classList.remove('hidden');
        } else {
            noResultsBox.classList.add('hidden');
        }
    }
}

function clearFilters() {
    const search = document.getElementById('categorySearchInput');
    const gender = document.getElementById('genderFilterSelect');
    const type   = document.getElementById('typeFilterSelect');
    if (search) search.value = '';
    if (gender) gender.value = 'all';
    if (type) type.value = 'all';

    const firstTab = document.querySelector('.sport-tab-btn');
    if (firstTab) {
        filterSportTab('all', firstTab);
    } else {
        applyFilters();
    }
}

function showCategoryRules(cat) {
    var modal = document.getElementById('rulesModal');
    var title = document.getElementById('modalRulesTitle');
    var sub   = document.getElementById('modalRulesSubtitle');
    var text  = document.getElementById('modalRulesContent');
    var fileSec = document.getElementById('modalRulesFileSection');
    var fileLink = document.getElementById('modalRulesFileLink');

    if (!modal) return;

    if (title) title.textContent = 'ระเบียบการ: ' + (cat.sport_name || '');
    if (sub) sub.textContent = (cat.category_name || '') + ' (' + (cat.category_gender === 'female' ? 'หญิง' : (cat.category_gender === 'mixed' ? 'ผสม' : 'ชาย')) + ')';
    if (text) text.textContent = cat.rules_detail || 'ไม่มีรายละเอียดเพิ่มเติม';

    if (cat.rules_file && fileSec && fileLink) {
        fileLink.href = '<?= base_url('uploads/rules/') ?>/' + cat.rules_file;
        fileSec.classList.remove('hidden');
    } else if (fileSec) {
        fileSec.classList.add('hidden');
    }

    modal.classList.remove('hidden');
    if (window.lucide) lucide.createIcons();
}

function closeRulesModal() {
    var modal = document.getElementById('rulesModal');
    if (modal) modal.classList.add('hidden');
}
</script>
<?= $this->endSection() ?>