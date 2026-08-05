<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span>จัดการข้อมูลแบบประเมิน</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">ภาพรวมสถิติและจัดการข้อมูลผู้ทำแบบประเมินความพึงพอใจ ประจำปีการศึกษา <?= esc($selected_year) ?></p>
    </div>
    
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="<?= base_url('science-week/staff/evaluations/create') ?>" class="w-full md:w-auto justify-center px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 shadow-lg shadow-indigo-950/20">
            <i data-lucide="settings" class="w-4 h-4 text-cyan-300"></i> ตั้งค่าโครงสร้างฟอร์มประเมิน
        </a>
    </div>
</div>

<!-- ==================== DASHBOARD & STATS SECTION ==================== -->
<!-- KPI Analytics Cards (4 Cards Grid - Clickable & Interactive) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Submissions Card -->
    <a href="<?= base_url('science-week/staff/evaluations') ?>#eval-table-section" class="glass-card rounded-3xl p-5 relative overflow-hidden bg-gradient-to-br from-slate-900/80 to-indigo-950/40 border border-indigo-500/20 hover:border-indigo-500/60 transition-all duration-300 hover:scale-[1.02] cursor-pointer group shadow-lg shadow-indigo-950/30">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-indigo-300 transition-colors">ผู้ทำแบบประเมินทั้งหมด</p>
                <h3 class="text-3xl font-extrabold text-white mt-1 font-mono"><?= number_format($stats['total_count'] ?? 0) ?> <span class="text-xs text-indigo-300 font-normal">ชุด</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400 group-hover:scale-110 group-hover:bg-indigo-500/20 transition-all">
                <i data-lucide="file-text" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center justify-between text-[10px] text-indigo-300 font-medium">
            <span class="flex items-center gap-1.5"><i data-lucide="activity" class="w-3.5 h-3.5 text-indigo-400"></i> ประจำปี <?= esc($selected_year) ?></span>
            <span class="font-bold group-hover:text-white flex items-center gap-1 underline">ดูทั้งหมด <i data-lucide="arrow-right" class="w-3 h-3"></i></span>
        </div>
    </a>

    <!-- Total Claimed Certificates Card -->
    <a href="<?= base_url('science-week/staff/evaluations?filter=claimed') ?>#eval-table-section" class="glass-card rounded-3xl p-5 relative overflow-hidden bg-gradient-to-br from-slate-900/80 to-emerald-950/40 border border-emerald-500/20 hover:border-emerald-500/60 transition-all duration-300 hover:scale-[1.02] cursor-pointer group shadow-lg shadow-emerald-950/30">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-emerald-300 transition-colors">ผู้รับเกียรติบัตร (เคลมสิทธิ์)</p>
                <h3 class="text-3xl font-extrabold text-white mt-1 font-mono"><?= number_format($stats['total_claimed'] ?? 0) ?> <span class="text-xs text-emerald-300 font-normal">รายชื่อ</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 group-hover:scale-110 group-hover:bg-emerald-500/20 transition-all">
                <i data-lucide="award" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center justify-between text-[10px] text-emerald-300 font-medium">
            <span class="flex items-center gap-1.5"><i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-400"></i> เคลมสิทธิ์แล้ว</span>
            <span class="font-bold group-hover:text-white flex items-center gap-1 underline">กรองตาราง <i data-lucide="filter" class="w-3 h-3"></i></span>
        </div>
    </a>

    <!-- Overall Avg Rating Card -->
    <button type="button" onclick="openQuestionScoresModal()" class="text-left w-full glass-card rounded-3xl p-5 relative overflow-hidden bg-gradient-to-br from-slate-900/80 to-amber-950/40 border border-amber-500/20 hover:border-amber-500/60 transition-all duration-300 hover:scale-[1.02] cursor-pointer group shadow-lg shadow-amber-950/30">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-amber-300 transition-colors">คะแนนความพึงพอใจเฉลี่ย</p>
                <h3 class="text-3xl font-extrabold text-white mt-1 font-mono flex items-baseline gap-1">
                    <?= esc($stats['overall_avg'] ?? '0.00') ?>
                    <span class="text-xs text-amber-300/80 font-normal">/ 5.00</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 group-hover:scale-110 group-hover:bg-amber-500/20 transition-all">
                <i data-lucide="star" class="w-6 h-6 fill-amber-400"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center justify-between text-[10px] text-amber-300 font-medium">
            <span class="flex items-center gap-1.5"><i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-400"></i> ระดับความพึงพอใจรวม</span>
            <span class="font-bold group-hover:text-white flex items-center gap-1 underline">ดูคะแนนรายข้อ <i data-lucide="info" class="w-3 h-3"></i></span>
        </div>
    </button>

    <!-- Total Comments Card -->
    <button type="button" onclick="openCommentsModal()" class="text-left w-full glass-card rounded-3xl p-5 relative overflow-hidden bg-gradient-to-br from-slate-900/80 to-cyan-950/40 border border-cyan-500/20 hover:border-cyan-500/60 transition-all duration-300 hover:scale-[1.02] cursor-pointer group shadow-lg shadow-cyan-950/30">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-cyan-300 transition-colors">ข้อเสนอแนะเพิ่มเติม</p>
                <h3 class="text-3xl font-extrabold text-white mt-1 font-mono"><?= number_format($stats['comments_count'] ?? 0) ?> <span class="text-xs text-cyan-300 font-normal">รายการ</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 group-hover:scale-110 group-hover:bg-cyan-500/20 transition-all">
                <i data-lucide="message-square" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center justify-between text-[10px] text-cyan-300 font-medium">
            <span class="flex items-center gap-1.5"><i data-lucide="message-circle" class="w-3.5 h-3.5 text-cyan-400"></i> ความคิดเห็นเพื่อปรับปรุง</span>
            <span class="font-bold group-hover:text-white flex items-center gap-1 underline">อ่านข้อเสนอแนะ <i data-lucide="external-link" class="w-3 h-3"></i></span>
        </div>
    </button>
</div>

<!-- Detailed Charts & Analytics Row 1 -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Chart 1: Rating per Question (Bar Chart) -->
    <div class="lg:col-span-2 glass-card rounded-3xl p-5 sm:p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-indigo-400"></i>
                    คะแนนความพึงพอใจแยกตามรายหัวข้อ (คะแนนเต็ม 5.0)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">คะแนนเฉลี่ยการประเมินในแต่ละด้านของการจัดงาน</p>
            </div>
        </div>
        <div class="h-64 sm:h-72 w-full">
            <canvas id="questionRatingChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Occupation Breakdown (Donut Chart) -->
    <div class="glass-card rounded-3xl p-5 sm:p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-5 h-5 text-purple-400"></i>
                    สัดส่วนตามกลุ่มอาชีพ
                </h3>
            </div>
            <div class="h-56 w-full flex items-center justify-center">
                <canvas id="occupationChart"></canvas>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-800/50 grid grid-cols-2 gap-2 text-center text-[11px]">
            <?php 
                $topOccs = array_slice($stats['occupation_counts'] ?? [], 0, 4, true);
                foreach($topOccs as $occName => $occCount):
            ?>
                <div class="bg-slate-950/40 p-2 rounded-xl border border-slate-800/40">
                    <div class="text-slate-400 truncate" title="<?= esc($occName) ?>"><?= esc($occName) ?></div>
                    <div class="text-indigo-400 font-extrabold font-mono text-sm"><?= number_format($occCount) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Analytics Row 2: Score Breakdown & Demographics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Score Distribution (5 Stars Breakdown) -->
    <div class="glass-card rounded-3xl p-5 sm:p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
        <h3 class="text-base font-extrabold text-slate-800 dark:text-white flex items-center gap-2 mb-4">
            <i data-lucide="star" class="w-5 h-5 text-amber-400"></i>
            การกระจายตัวของระดับความพึงพอใจ (5 ระดับ)
        </h3>
        
        <?php
            $dist = $stats['rating_dist'] ?? [5=>0,4=>0,3=>0,2=>0,1=>0];
            $totalRated = array_sum($dist) ?: 1;
            $starLabels = [
                5 => ['label' => 'มากที่สุด (4.5 - 5.0)', 'color' => 'from-emerald-500 to-teal-400'],
                4 => ['label' => 'มาก (3.5 - 4.49)', 'color' => 'from-indigo-500 to-cyan-400'],
                3 => ['label' => 'ปานกลาง (2.5 - 3.49)', 'color' => 'from-amber-500 to-yellow-400'],
                2 => ['label' => 'น้อย (1.5 - 2.49)', 'color' => 'from-orange-500 to-amber-500'],
                1 => ['label' => 'น้อยที่สุด (< 1.5)', 'color' => 'from-rose-500 to-red-400'],
            ];
        ?>
        
        <div class="space-y-3.5">
            <?php foreach([5, 4, 3, 2, 1] as $star): 
                $count = $dist[$star] ?? 0;
                $pct = round(($count / $totalRated) * 100, 1);
                $info = $starLabels[$star];
            ?>
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-bold text-slate-300 flex items-center gap-1.5">
                            <span class="inline-flex text-amber-400 font-mono font-extrabold"><?= $star ?> ★</span>
                            <span class="text-slate-400 text-[11px]"><?= $info['label'] ?></span>
                        </span>
                        <span class="font-mono text-slate-400 text-xs font-semibold">
                            <?= $count ?> ราย (<?= $pct ?>%)
                        </span>
                    </div>
                    <div class="w-full h-2.5 bg-slate-950/60 rounded-full overflow-hidden border border-slate-800/40 p-0.5">
                        <div class="h-full rounded-full bg-gradient-to-r <?= $info['color'] ?> transition-all duration-500" style="width: <?= $pct ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Demographics Highlights (Provinces & Gender & Age) -->
    <div class="glass-card rounded-3xl p-5 sm:p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 flex flex-col justify-between">
        <div>
            <h3 class="text-base font-extrabold text-slate-800 dark:text-white flex items-center gap-2 mb-4">
                <i data-lucide="map-pin" class="w-5 h-5 text-cyan-400"></i>
                ข้อมูลเชิงประชากร (Top จังหวัด & เพศ)
            </h3>

            <!-- Top 5 Provinces -->
            <div class="mb-4">
                <p class="text-xs font-bold text-slate-400 mb-2">5 อันดับจังหวัดที่มีผู้ประเมินสูงสุด:</p>
                <div class="flex flex-wrap gap-2">
                    <?php 
                        $topProvs = $stats['top_provinces'] ?? [];
                        if (empty($topProvs)):
                    ?>
                        <span class="text-xs text-slate-500">ไม่มีข้อมูลจังหวัด</span>
                    <?php else: 
                        $rank = 1;
                        foreach($topProvs as $provName => $provCount):
                    ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-2xl bg-cyan-950/40 border border-cyan-500/20 text-cyan-300 text-xs font-bold">
                            <span class="w-4 h-4 rounded-full bg-cyan-500/20 text-cyan-300 text-[10px] flex items-center justify-center font-mono font-extrabold"><?= $rank++ ?></span>
                            <?= esc($provName) ?>
                            <span class="text-[10px] text-cyan-400/70 font-mono">(<?= $provCount ?>)</span>
                        </span>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>

        <!-- Gender & Age Badges -->
        <div class="pt-4 border-t border-slate-800/50 grid grid-cols-2 gap-4">
            <!-- Gender -->
            <div>
                <p class="text-xs font-bold text-slate-400 mb-2 flex items-center gap-1">
                    <i data-lucide="users" class="w-3.5 h-3.5 text-indigo-400"></i> สัดส่วนเพศ:
                </p>
                <div class="space-y-1 text-xs">
                    <?php foreach($stats['gender_counts'] ?? [] as $gName => $gCount): ?>
                        <div class="flex justify-between items-center text-slate-300 bg-slate-950/30 px-2.5 py-1 rounded-xl">
                            <span><?= esc($gName) ?></span>
                            <span class="font-mono text-indigo-400 font-bold"><?= $gCount ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Age Groups -->
            <div>
                <p class="text-xs font-bold text-slate-400 mb-2 flex items-center gap-1">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-purple-400"></i> กลุ่มอายุ:
                </p>
                <div class="space-y-1 text-xs">
                    <?php 
                        $topAges = array_slice($stats['age_counts'] ?? [], 0, 3, true);
                        foreach($topAges as $aName => $aCount): 
                    ?>
                        <div class="flex justify-between items-center text-slate-300 bg-slate-950/30 px-2.5 py-1 rounded-xl">
                            <span class="truncate max-w-[100px]"><?= esc($aName) ?></span>
                            <span class="font-mono text-purple-400 font-bold"><?= $aCount ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ==================== END DASHBOARD SECTION ==================== -->

<!-- Search & Filter Card -->
<div id="eval-table-section" class="glass-card rounded-3xl p-4 sm:p-6 mb-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
    <form action="<?= base_url('science-week/staff/evaluations') ?>" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="ค้นหาตามชื่อ, โรงเรียน, จังหวัด, เบอร์โทร หรือรหัสแบบประเมิน..." class="w-full pl-12 pr-4 py-3 bg-slate-950/50 border border-slate-800 rounded-2xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-indigo-500 transition-colors">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-750 text-white font-bold rounded-2xl text-xs sm:text-sm transition-colors flex items-center gap-2">
                ค้นหา
            </button>
            <?php if (!empty($search) || !empty($filter_mode)): ?>
                <a href="<?= base_url('science-week/staff/evaluations') ?>" class="px-6 py-3 bg-rose-950/40 hover:bg-rose-900/60 border border-rose-500/30 text-rose-300 font-bold rounded-2xl text-xs sm:text-sm transition-colors flex items-center justify-center gap-1.5">
                    <i data-lucide="x-circle" class="w-4 h-4"></i> ล้างการกรอง <?= !empty($filter_mode) ? '('.($filter_mode === 'claimed' ? 'ผู้เคลมสิทธิ์' : 'มีข้อเสนอแนะ').')' : '' ?>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Evaluations Table -->
<div class="glass-card rounded-3xl overflow-hidden bg-white dark:bg-slate-900/60 shadow-xl border border-slate-200 dark:border-slate-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-[140px]">รหัสประเมิน</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">ผู้รับเกียรติบัตร (เคลมสิทธิ์)</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">ข้อมูลทั่วไปผู้ประเมิน</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">จังหวัด</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[120px]">คะแนนเฉลี่ย</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-[200px]">ข้อเสนอแนะ</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[160px]">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php if (empty($evaluations)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium bg-white dark:bg-transparent">
                            ไม่พบข้อมูลแบบประเมินในระบบ
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($evaluations as $eval): 
                        $feedback = json_decode($eval['eval_feedback'], true) ?: [];
                        $ratings = $feedback['ratings'] ?? [];
                        $comments = $feedback['comments'] ?? '-';
                        $sum = array_sum($ratings);
                        $count = count($ratings);
                        $avg = $count > 0 ? number_format($sum / $count, 2) : '-';

                        $students = json_decode($eval['eval_students'] ?? '', true) ?: [];
                        $claimName = !empty($students) ? implode(', ', $students) : null;

                        $cFields = $feedback['custom_fields'] ?? $feedback['fields'] ?? [];
                        $evalGender = !empty($eval['eval_gender']) ? $eval['eval_gender'] : ($cFields['gender'] ?? $cFields['eval_gender'] ?? '-');
                        $evalAge = !empty($eval['eval_age']) ? $eval['eval_age'] : ($cFields['age'] ?? $cFields['eval_age'] ?? '-');
                        $evalOccupation = !empty($eval['eval_occupation']) ? $eval['eval_occupation'] : ($cFields['occupation'] ?? $cFields['eval_occupation'] ?? '-');
                        $evalEducation = !empty($eval['eval_education_level']) ? $eval['eval_education_level'] : ($cFields['education_level'] ?? $cFields['eval_education_level'] ?? '-');
                        $evalProvince = !empty($eval['eval_province']) ? $eval['eval_province'] : ($cFields['province'] ?? $cFields['eval_province'] ?? '-');
                    ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-xs font-extrabold text-cyan-500 dark:text-cyan-400 block font-mono"><?= esc($eval['eval_code']) ?></span>
                                <span class="text-[9px] text-slate-500 block mt-1"><?= date('d/m/Y H:i', strtotime($eval['eval_created_at'])) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($claimName): ?>
                                    <div class="font-bold text-xs text-slate-850 dark:text-slate-200"><?= esc($claimName) ?></div>
                                    <span class="inline-flex mt-1 items-center gap-1 px-1.5 py-0.5 rounded text-[9px] bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 font-bold">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i> เคลมสิทธิ์แล้ว
                                    </span>
                                <?php else: ?>
                                    <div class="text-xs text-rose-450 dark:text-rose-400 font-semibold italic">ยังไม่ได้เคลมเกียรติบัตร</div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-800 dark:text-slate-200 font-semibold">
                                    เพศ: <?= esc($evalGender) ?> | อายุ: <?= esc($evalAge) ?>
                                </div>
                                <div class="text-[10px] text-slate-500 mt-1">อาชีพ: <?= esc($evalOccupation) ?></div>
                                <div class="text-[10px] text-slate-500 mt-0.5">การศึกษา: <?= esc($evalEducation) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-slate-700 dark:text-slate-350"><?= esc($evalProvince) ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-indigo-950/40 text-indigo-400 border border-indigo-900/30">
                                    <i data-lucide="star" class="w-3.5 h-3.5 fill-indigo-400 text-indigo-400"></i>
                                    <?= $avg ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed max-w-[200px]" title="<?= esc($comments) ?>">
                                    <?= esc($comments) ?>
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Print/View Certificate -->
                                    <a href="<?= base_url('science-week/certificate/view-all/evaluation/' . $eval['eval_code']) ?>" target="_blank" class="p-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white rounded-lg border border-emerald-100 dark:border-slate-800 transition-all" title="พิมพ์เกียรติบัตร">
                                        <i data-lucide="award" class="w-4 h-4"></i>
                                    </a>
                                    <!-- Edit -->
                                    <a href="<?= base_url('science-week/staff/evaluations/edit/' . $eval['eval_id']) ?>" class="p-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-lg border border-blue-100 dark:border-slate-800 transition-all" title="แก้ไข">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <!-- Delete -->
                                    <button onclick="deleteEvaluation(<?= $eval['eval_id'] ?>, '<?= esc($eval['eval_code']) ?>')" class="p-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-lg border border-rose-100 dark:border-slate-800 transition-all" title="ลบ">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Section -->
    <?php if (!empty($pager)): ?>
        <div class="px-6 py-4 bg-slate-900/40 dark:bg-slate-900/60 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-400 font-medium flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                <span>แสดงผลแบบประเมินรายการที่ <strong class="text-slate-200 font-mono"><?= number_format(count($evaluations)) ?></strong> รายการในหน้านี้</span>
            </div>
            <div class="w-full sm:w-auto">
                <?= $pager->links('default', 'sci_week_pager') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- ==================== MODALS SECTION ==================== -->
<!-- Modal: Question Scores Breakdown -->
<div id="modal-question-scores" class="hidden fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[100] flex items-center justify-center p-4">
    <div class="glass-card rounded-[2.5rem] bg-slate-900 border border-slate-800 w-full max-w-3xl overflow-hidden shadow-2xl animate-[fadeIn_0.2s_ease-out]">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-950/40">
            <h3 class="text-base sm:text-lg font-extrabold text-white flex items-center gap-2">
                <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                รายละเอียดคะแนนความพึงพอใจแยกตามรายข้อ
            </h3>
            <button type="button" onclick="closeModal('modal-question-scores')" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar space-y-4">
            <div class="flex items-center justify-between bg-amber-950/30 border border-amber-500/20 p-4 rounded-2xl">
                <div>
                    <span class="text-xs text-amber-300 font-bold block">คะแนนเฉลี่ยรวมทุกข้อ</span>
                    <span class="text-2xl font-black text-amber-400 font-mono"><?= esc($stats['overall_avg'] ?? '0.00') ?> / 5.00</span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 font-medium block">จำนวนผู้ประเมิน</span>
                    <span class="text-lg font-bold text-slate-200 font-mono"><?= number_format($stats['total_count'] ?? 0) ?> ชุด</span>
                </div>
            </div>

            <div class="space-y-3">
                <?php foreach($stats['question_stats'] ?? [] as $idx => $q): 
                    $avgScore = $q['avg'];
                    if ($avgScore >= 4.5) {
                        $levelText = 'มากที่สุด';
                        $levelClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30';
                    } else if ($avgScore >= 3.5) {
                        $levelText = 'มาก';
                        $levelClass = 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30';
                    } else if ($avgScore >= 2.5) {
                        $levelText = 'ปานกลาง';
                        $levelClass = 'bg-amber-500/10 text-amber-400 border-amber-500/30';
                    } else if ($avgScore >= 1.5) {
                        $levelText = 'น้อย';
                        $levelClass = 'bg-orange-500/10 text-orange-400 border-orange-500/30';
                    } else {
                        $levelText = 'น้อยที่สุด';
                        $levelClass = 'bg-rose-500/10 text-rose-400 border-rose-500/30';
                    }
                ?>
                    <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex-1">
                            <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider block font-mono">ข้อที่ <?= $idx + 1 ?></span>
                            <p class="text-xs text-slate-200 font-semibold mt-0.5"><?= esc($q['label']) ?></p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0 justify-between sm:justify-end">
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold border <?= $levelClass ?>">
                                <?= $levelText ?>
                            </span>
                            <span class="text-base font-extrabold font-mono text-amber-400 bg-amber-950/40 px-3 py-1 rounded-xl border border-amber-500/20">
                                <?= number_format($q['avg'], 2) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="p-4 border-t border-slate-800 text-right bg-slate-950/40">
            <button type="button" onclick="closeModal('modal-question-scores')" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition-colors">
                ปิดหน้าต่าง
            </button>
        </div>
    </div>
</div>

<!-- Modal: All Feedback Comments -->
<div id="modal-comments" class="hidden fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[100] flex items-center justify-center p-4">
    <div class="glass-card rounded-[2.5rem] bg-slate-900 border border-slate-800 w-full max-w-4xl overflow-hidden shadow-2xl animate-[fadeIn_0.2s_ease-out]">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-950/40">
            <h3 class="text-base sm:text-lg font-extrabold text-white flex items-center gap-2">
                <i data-lucide="message-square" class="w-5 h-5 text-cyan-400"></i>
                ข้อเสนอแนะเพิ่มเติมทั้งหมด (<?= count($all_comments ?? []) ?> รายการ)
            </h3>
            <button type="button" onclick="closeModal('modal-comments')" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar space-y-4">
            <?php if (empty($all_comments)): ?>
                <div class="text-center py-12 text-slate-400 text-xs font-medium">
                    ยังไม่มีข้อเสนอแนะในระบบ
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 gap-3">
                    <?php foreach($all_comments as $c): ?>
                        <div class="p-4 rounded-2xl bg-slate-950/50 border border-slate-800/80 hover:border-cyan-500/30 transition-all">
                            <div class="flex justify-between items-start mb-2 gap-2">
                                <div>
                                    <span class="font-extrabold text-xs text-cyan-400 font-mono">รหัสประเมิน: <?= esc($c['code']) ?></span>
                                </div>
                                <span class="text-[10px] text-slate-500 font-mono shrink-0"><?= esc($c['date']) ?></span>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed bg-slate-900/60 p-3 rounded-xl border border-slate-800/50">
                                "<?= esc($c['comment']) ?>"
                            </p>
                            <div class="mt-2 flex items-center gap-2 text-[10px] text-slate-500">
                                <span>อาชีพ: <?= esc($c['occupation']) ?></span>
                                <span>•</span>
                                <span>จังหวัด: <?= esc($c['province']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="p-4 border-t border-slate-800 flex justify-between items-center bg-slate-950/40">
            <a href="<?= base_url('science-week/staff/evaluations?filter=has_comments') ?>#eval-table-section" onclick="closeModal('modal-comments')" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 underline flex items-center gap-1">
                กรองแสดงเฉพาะรายการที่มีข้อเสนอแนะในตาราง
            </a>
            <button type="button" onclick="closeModal('modal-comments')" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition-colors">
                ปิดหน้าต่าง
            </button>
        </div>
    </div>
</div>

<script>
    function openQuestionScoresModal() {
        document.getElementById('modal-question-scores').classList.remove('hidden');
    }

    function openCommentsModal() {
        document.getElementById('modal-comments').classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // 1. Question Rating Bar Chart
        const qStats = <?= json_encode($stats['question_stats'] ?? []) ?>;
        const qLabels = qStats.map(q => q.label.length > 25 ? q.label.substring(0, 25) + '...' : q.label);
        const qFullLabels = qStats.map(q => q.label);
        const qScores = qStats.map(q => q.avg);

        const ctxQ = document.getElementById('questionRatingChart').getContext('2d');
        new Chart(ctxQ, {
            type: 'bar',
            data: {
                labels: qLabels,
                datasets: [{
                    label: 'คะแนนเฉลี่ย (เต็ม 5)',
                    data: qScores,
                    backgroundColor: 'rgba(99, 102, 241, 0.65)',
                    borderColor: '#6366f1',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(99, 102, 241, 0.9)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return qFullLabels[context[0].dataIndex];
                            },
                            label: function(context) {
                                return ' คะแนนเฉลี่ย: ' + context.parsed.y + ' / 5.00';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 5,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', font: { family: 'Sarabun' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#cbd5e1', font: { family: 'Sarabun', size: 11 } }
                    }
                }
            }
        });

        // 2. Occupation Donut Chart
        const occData = <?= json_encode($stats['occupation_counts'] ?? []) ?>;
        const occLabels = Object.keys(occData);
        const occValues = Object.values(occData);

        const ctxOcc = document.getElementById('occupationChart').getContext('2d');
        new Chart(ctxOcc, {
            type: 'doughnut',
            data: {
                labels: occLabels,
                datasets: [{
                    data: occValues,
                    backgroundColor: [
                        '#6366f1', '#a855f7', '#06b6d4', '#10b981', '#f59e0b', '#ec4899', '#64748b'
                    ],
                    borderWidth: 2,
                    borderColor: '#0f172a'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#cbd5e1', font: { family: 'Sarabun', size: 10 }, boxWidth: 12 }
                    }
                },
                cutout: '65%'
            }
        });
    });

    function deleteEvaluation(id, name) {
        Swal.fire({
            title: 'ยืนยันการลบข้อมูลแบบประเมิน?',
            text: `คุณกำลังจะลบรายการแบบประเมินของ "${name}" การดำเนินการนี้ไม่สามารถย้อนกลับได้!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ยืนยันการลบ',
            cancelButtonText: 'ยกเลิก',
            background: getSwalColors().bg,
            color: getSwalColors().text,
            customClass: {
                popup: 'glass-card rounded-[2rem]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();

                fetch(`<?= base_url('science-week/staff/evaluations/delete') ?>/${id}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบสำเร็จ',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#3b82f6',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#ef4444',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'ล้มเหลว',
                        text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้',
                        background: getSwalColors().bg,
                        color: getSwalColors().text,
                        confirmButtonColor: '#ef4444',
                        customClass: { popup: 'glass-card rounded-[2rem]' }
                    });
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
