<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Header & Actions -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 w-full">
    <div>
        <div class="flex items-center gap-2">
            <div class="p-2 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400">
                <i data-lucide="bar-chart-2" class="w-6 h-6"></i>
            </div>
            <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">
                รายงานสรุปผลงาน & แดชบอร์ดสถิติ
            </h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
            สรุปภาพรวมสถิติการจัดงาน, ผู้เข้าร่วมแข่งขัน, ค่าร้อยละ & คะแนนแบบประเมินความพึงพอใจ ประจำปีการศึกษา <span class="text-indigo-400 font-bold"><?= esc($selected_year) ?></span>
        </p>
    </div>
    
    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <!-- Print / Book View Button -->
        <a href="<?= base_url('science-week/staff/report/book') ?>" target="_blank" class="w-full sm:w-auto justify-center px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:from-indigo-600 hover:to-pink-600 text-white font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 shadow-lg shadow-indigo-950/30 hover:scale-[1.02]">
            <i data-lucide="book-open" class="w-4 h-4 text-amber-300"></i> เปิดดูเล่มรายงานฉบับพิมพ์ (Print Book)
        </a>
        <a href="<?= base_url('science-week/staff/evaluations') ?>" class="w-full sm:w-auto justify-center px-4 py-3 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs sm:text-sm transition-all flex items-center gap-2 border border-slate-700">
            <i data-lucide="list" class="w-4 h-4"></i> ข้อมูลแบบประเมิน
        </a>
    </div>
</div>

<!-- ==================== GRAND TOTAL PARTICIPANT STATS ==================== -->
<div class="glass-card rounded-3xl p-5 mb-6 bg-gradient-to-r from-indigo-950/70 via-slate-900/90 to-purple-950/70 border border-indigo-500/30 shadow-2xl">
    <div class="flex items-center justify-between mb-4 border-b border-indigo-500/20 pb-3">
        <div class="flex items-center gap-2.5">
            <div class="p-2 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 text-white shadow-md">
                <i data-lucide="layers" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-base sm:text-lg font-black text-white tracking-wide">
                    สถิติรวมผู้มีส่วนร่วมทั้งหมดในโครงการ (Grand Total Statistics)
                </h3>
                <p class="text-xs text-indigo-300">รวบรวมข้อมูลทุกภาคส่วน: ผู้ประเมิน, ผู้รับเกียรติบัตร, ผู้เข้าแข่งขัน, ครูผู้ฝึกสอน และนักเรียนช่วยงาน</p>
            </div>
        </div>
        <span class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 font-mono font-bold text-xs">
            ประจำปี <?= esc($selected_year) ?>
        </span>
    </div>

    <!-- 6 Columns Stat Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <!-- 1. รวมผู้มีส่วนร่วมทั้งหมด -->
        <div class="bg-gradient-to-b from-indigo-900/40 to-slate-950/80 p-3.5 rounded-2xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all text-center shadow-lg">
            <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto mb-1.5">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">ยอดรวมคนทั้งหมด</p>
            <p class="text-xl font-black text-white font-mono mt-0.5"><?= number_format($summary_overview['grand_total_people']) ?></p>
            <p class="text-[9px] text-indigo-300 font-bold mt-0.5">รวมทุกภาคส่วน</p>
        </div>

        <!-- 2. ผู้รับเกียรติบัตรทุกประเภท -->
        <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-emerald-500/30 hover:border-emerald-400/60 transition-all text-center">
            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto mb-1.5">
                <i data-lucide="award" class="w-4 h-4"></i>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">เกียรติบัตรรวม</p>
            <p class="text-xl font-black text-emerald-400 font-mono mt-0.5"><?= number_format($summary_overview['total_certificates_all']) ?></p>
            <p class="text-[9px] text-emerald-300 font-medium mt-0.5">ใบ/รายชื่อ</p>
        </div>

        <!-- 3. ผู้ทำแบบประเมิน & เคลมสิทธิ์ -->
        <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-purple-500/30 hover:border-purple-400/60 transition-all text-center">
            <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center mx-auto mb-1.5">
                <i data-lucide="clipboard-check" class="w-4 h-4"></i>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ทำแบบประเมิน</p>
            <p class="text-xl font-black text-purple-300 font-mono mt-0.5"><?= number_format($summary_overview['total_evaluations']) ?></p>
            <p class="text-[9px] text-purple-400 font-medium mt-0.5">เคลม <?= number_format($summary_overview['total_eval_claimed']) ?> ราย</p>
        </div>

        <!-- 4. นักเรียนเข้าแข่งขัน -->
        <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-cyan-500/30 hover:border-cyan-400/60 transition-all text-center">
            <div class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center mx-auto mb-1.5">
                <i data-lucide="trophy" class="w-4 h-4"></i>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">นักเรียนแข่งขัน</p>
            <p class="text-xl font-black text-cyan-300 font-mono mt-0.5"><?= number_format($summary_overview['total_competitors']) ?></p>
            <p class="text-[9px] text-cyan-400 font-medium mt-0.5"><?= number_format($summary_overview['total_teams']) ?> ทีม</p>
        </div>

        <!-- 5. ครูผู้ฝึกสอน / โค้ช -->
        <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-amber-500/30 hover:border-amber-400/60 transition-all text-center">
            <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center mx-auto mb-1.5">
                <i data-lucide="user-check" class="w-4 h-4"></i>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ครูผู้ฝึกสอน/โค้ช</p>
            <p class="text-xl font-black text-amber-300 font-mono mt-0.5"><?= number_format($summary_overview['total_coaches']) ?></p>
            <p class="text-[9px] text-amber-400 font-medium mt-0.5">ท่าน</p>
        </div>

        <!-- 6. นักเรียนช่วยงาน Staff -->
        <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-pink-500/30 hover:border-pink-400/60 transition-all text-center">
            <div class="w-8 h-8 rounded-xl bg-pink-500/20 text-pink-400 flex items-center justify-center mx-auto mb-1.5">
                <i data-lucide="user-cog" class="w-4 h-4"></i>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">นักเรียนช่วยงาน</p>
            <p class="text-xl font-black text-pink-300 font-mono mt-0.5"><?= number_format($summary_overview['total_student_staff']) ?></p>
            <p class="text-[9px] text-pink-400 font-medium mt-0.5">คน (Staff)</p>
        </div>
    </div>
</div>

<!-- ==================== EXECUTIVE KPI CARDS ==================== -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Card 1: Total Evaluations & Percentage -->
    <div class="glass-card rounded-3xl p-5 relative overflow-hidden bg-gradient-to-br from-slate-900/80 to-indigo-950/40 border border-indigo-500/20 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">ผู้ทำแบบประเมิน</p>
                <h3 class="text-3xl font-black text-white mt-1 font-mono">
                    <?= number_format($evaluations['total_count']) ?>
                    <span class="text-xs text-indigo-300 font-normal">ชุด</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                <i data-lucide="clipboard-check" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center justify-between text-[11px] text-indigo-300/80">
            <span>เคลมเกียรติบัตรแล้ว</span>
            <span class="font-bold text-emerald-400 font-mono"><?= number_format($evaluations['total_claimed']) ?> ราย</span>
        </div>
    </div>

    <!-- Card 2: Overall Mean & S.D. -->
    <div class="glass-card rounded-3xl p-5 relative overflow-hidden bg-gradient-to-br from-slate-900/80 to-amber-950/40 border border-amber-500/20 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">คะแนนความพึงพอใจเฉลี่ย ($\bar{X}$)</p>
                <h3 class="text-3xl font-black text-white mt-1 font-mono flex items-baseline gap-1.5">
                    <?= number_format($evaluations['grand_mean'], 2) ?>
                    <span class="text-xs text-amber-300 font-normal">/ 5.00</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                <i data-lucide="star" class="w-6 h-6 fill-amber-400"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center justify-between text-[11px]">
            <span class="text-slate-400">S.D. = <strong class="text-slate-200 font-mono"><?= number_format($evaluations['grand_sd'], 2) ?></strong></span>
            <span class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold <?= $evaluations['grand_quality']['bg'] ?>">
                <?= $evaluations['grand_quality']['text'] ?>
            </span>
        </div>
    </div>

    <!-- Card 3: Overall Percentage -->
    <div class="glass-card rounded-3xl p-5 relative overflow-hidden bg-gradient-to-br from-slate-900/80 to-emerald-950/40 border border-emerald-500/20 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">ร้อยละความพึงพอใจรวม</p>
                <h3 class="text-3xl font-black text-emerald-400 mt-1 font-mono">
                    <?= number_format($evaluations['grand_percentage'], 2) ?><span class="text-lg font-bold">%</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                <i data-lucide="percent" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center justify-between text-[11px] text-emerald-300/80">
            <span>เกณฑ์การประเมิน</span>
            <span class="font-bold text-white">อยู่ในระดับดีเยี่ยม</span>
        </div>
    </div>

    <!-- Card 4: Competition Teams & Students -->
    <div class="glass-card rounded-3xl p-5 relative overflow-hidden bg-gradient-to-br from-slate-900/80 to-cyan-950/40 border border-cyan-500/20 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">ทีมแข่งขัน & นักเรียน</p>
                <h3 class="text-3xl font-black text-white mt-1 font-mono">
                    <?= number_format($competitions['total_teams']) ?>
                    <span class="text-xs text-cyan-300 font-normal">ทีม (<?= number_format($competitions['total_students']) ?> คน)</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                <i data-lucide="trophy" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center justify-between text-[11px] text-cyan-300/80">
            <span>ครูผู้ควบคุม</span>
            <span class="font-bold text-white font-mono"><?= number_format($competitions['total_teachers']) ?> ท่าน</span>
        </div>
    </div>
</div>

<!-- ==================== CHARTS SECTION ==================== -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Chart 1: Mean & Percentage per Question -->
    <div class="lg:col-span-2 glass-card rounded-3xl p-6 bg-slate-900/60 border border-slate-800">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
            <div>
                <h3 class="text-base font-extrabold text-white flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-indigo-400"></i>
                    ค่าเฉลี่ยและร้อยละความพึงพอใจรายข้อ
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">เปรียบเทียบคะแนนเฉลี่ย ($\bar{X}$) และคิดเป็นร้อยละ (%) ในแต่ละหัวข้อ</p>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="inline-flex items-center gap-1.5 text-indigo-300">
                    <span class="w-3 h-3 rounded-full bg-indigo-500"></span> คะแนนเฉลี่ย (เต็ม 5.0)
                </span>
            </div>
        </div>
        <div class="h-72 w-full">
            <canvas id="questionStatsChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Rating Distribution Donut -->
    <div class="glass-card rounded-3xl p-6 bg-slate-900/60 border border-slate-800 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-extrabold text-white flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-5 h-5 text-purple-400"></i>
                    สัดส่วนระดับความพึงพอใจ
                </h3>
            </div>
            <div class="h-56 w-full flex items-center justify-center">
                <canvas id="ratingDistChart"></canvas>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-800/80 space-y-1.5 text-xs">
            <?php 
                $distLabels = [
                    5 => ['มากที่สุด (4.50 - 5.00)', 'text-emerald-400'],
                    4 => ['มาก (3.50 - 4.49)', 'text-indigo-400'],
                    3 => ['ปานกลาง (2.50 - 3.49)', 'text-amber-400'],
                    2 => ['น้อย (1.50 - 2.49)', 'text-orange-400'],
                    1 => ['น้อยที่สุด (< 1.50)', 'text-rose-400'],
                ];
                $totalDist = array_sum($evaluations['rating_dist']) ?: 1;
                foreach([5, 4, 3, 2, 1] as $r):
                    $c = $evaluations['rating_dist'][$r] ?? 0;
                    $p = round(($c / $totalDist) * 100, 1);
            ?>
                <div class="flex justify-between items-center text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full <?= str_replace('text-', 'bg-', $distLabels[$r][1]) ?>"></span>
                        <?= $distLabels[$r][0] ?>
                    </span>
                    <span class="font-mono font-bold <?= $distLabels[$r][1] ?>"><?= $c ?> ชุด (<?= $p ?>%)</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ==================== DETAILED EVALUATION TABLE ==================== -->
<div class="glass-card rounded-3xl overflow-hidden bg-slate-900/60 border border-slate-800 mb-6 shadow-xl">
    <div class="p-5 sm:p-6 border-b border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h3 class="text-base font-extrabold text-white flex items-center gap-2">
                <i data-lucide="table" class="w-5 h-5 text-cyan-400"></i>
                ตารางวิเคราะห์ข้อมูลความพึงพอใจรายข้อ (Mean, S.D., ร้อยละ, ระดับคุณภาพ)
            </h3>
            <p class="text-xs text-slate-400 mt-1">สรุปการแจกแจงทางสถิติตามแบบฟอร์มประเมิน</p>
        </div>
        <a href="<?= base_url('science-week/staff/report/book') ?>" target="_blank" class="px-4 py-2 bg-indigo-600/30 hover:bg-indigo-600/50 border border-indigo-500/40 text-indigo-200 text-xs font-bold rounded-xl flex items-center gap-2 transition-all">
            <i data-lucide="printer" class="w-4 h-4"></i> พิมพ์ตารางรายงาน
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs sm:text-sm">
            <thead>
                <tr class="bg-slate-950/70 border-b border-slate-800 text-slate-400 uppercase text-[11px] font-bold">
                    <th class="px-4 py-3.5 text-center w-12">ลำดับ</th>
                    <th class="px-4 py-3.5">ประเด็นการประเมิน</th>
                    <th class="px-4 py-3.5 text-center w-24">จำนวนผู้ตอบ (N)</th>
                    <th class="px-4 py-3.5 text-center w-24">ค่าเฉลี่ย ($\bar{X}$)</th>
                    <th class="px-4 py-3.5 text-center w-24">ส่วนเบี่ยงเบน (S.D.)</th>
                    <th class="px-4 py-3.5 text-center w-28">ร้อยละ (%)</th>
                    <th class="px-4 py-3.5 text-center w-32">ระดับความพึงพอใจ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                <?php 
                    $no = 1;
                    foreach($evaluations['question_stats'] as $qs):
                ?>
                    <tr class="hover:bg-slate-800/30 transition-colors">
                        <td class="px-4 py-3 text-center font-mono text-slate-500 font-bold"><?= $no++ ?></td>
                        <td class="px-4 py-3 text-slate-200 font-medium"><?= esc($qs['label']) ?></td>
                        <td class="px-4 py-3 text-center font-mono text-slate-400"><?= number_format($qs['count']) ?></td>
                        <td class="px-4 py-3 text-center font-mono font-bold text-amber-400"><?= number_format($qs['mean'], 2) ?></td>
                        <td class="px-4 py-3 text-center font-mono text-slate-300"><?= number_format($qs['sd'], 2) ?></td>
                        <td class="px-4 py-3 text-center font-mono font-bold text-emerald-400"><?= number_format($qs['percentage'], 2) ?>%</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold <?= $qs['quality_info']['bg'] ?>">
                                <?= $qs['quality'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <!-- Summary Row -->
                <tr class="bg-indigo-950/40 border-t-2 border-indigo-500/40 font-bold text-white">
                    <td colspan="2" class="px-4 py-4 text-right tracking-wide text-indigo-300">
                        เฉลี่ยรวมทุกประเด็นการประเมิน
                    </td>
                    <td class="px-4 py-4 text-center font-mono text-indigo-200"><?= number_format($evaluations['total_count']) ?></td>
                    <td class="px-4 py-4 text-center font-mono text-base text-amber-300"><?= number_format($evaluations['grand_mean'], 2) ?></td>
                    <td class="px-4 py-4 text-center font-mono text-indigo-200"><?= number_format($evaluations['grand_sd'], 2) ?></td>
                    <td class="px-4 py-4 text-center font-mono text-base text-emerald-300"><?= number_format($evaluations['grand_percentage'], 2) ?>%</td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-block px-3.5 py-1.5 rounded-full text-xs font-black shadow <?= $evaluations['grand_quality']['bg'] ?>">
                            <?= $evaluations['grand_quality']['text'] ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ==================== DEMOGRAPHICS & COMPETITION BREAKDOWN ==================== -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Demographics: Occupations -->
    <div class="glass-card rounded-3xl p-6 bg-slate-900/60 border border-slate-800">
        <h3 class="text-base font-extrabold text-white flex items-center gap-2 mb-4">
            <i data-lucide="briefcase" class="w-5 h-5 text-indigo-400"></i>
            สัดส่วนตามกลุ่มอาชีพ
        </h3>
        <div class="space-y-3">
            <?php 
                $totalOcc = array_sum($evaluations['occupation_counts']) ?: 1;
                foreach($evaluations['occupation_counts'] as $occName => $occCnt):
                    $pct = round(($occCnt / $totalOcc) * 100, 1);
            ?>
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-300 font-medium truncate"><?= esc($occName) ?></span>
                        <span class="font-mono text-indigo-400 font-bold"><?= $occCnt ?> คน (<?= $pct ?>%)</span>
                    </div>
                    <div class="w-full h-2 bg-slate-950 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-cyan-400 rounded-full" style="width: <?= $pct ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Demographics: Gender & Age -->
    <div class="glass-card rounded-3xl p-6 bg-slate-900/60 border border-slate-800 flex flex-col justify-between">
        <div>
            <h3 class="text-base font-extrabold text-white flex items-center gap-2 mb-4">
                <i data-lucide="users" class="w-5 h-5 text-purple-400"></i>
                สัดส่วนเพศและช่วงอายุ
            </h3>

            <div class="mb-4">
                <p class="text-xs font-bold text-slate-400 mb-2">จำแนกตามเพศ:</p>
                <div class="grid grid-cols-2 gap-2">
                    <?php 
                        $totalGen = array_sum($evaluations['gender_counts']) ?: 1;
                        foreach($evaluations['gender_counts'] as $gName => $gCnt):
                            $gPct = round(($gCnt / $totalGen) * 100, 1);
                    ?>
                        <div class="bg-slate-950/60 p-3 rounded-2xl border border-slate-800/60">
                            <div class="text-slate-400 text-xs truncate"><?= esc($gName) ?></div>
                            <div class="text-lg font-black text-white font-mono mt-0.5"><?= $gCnt ?> <span class="text-xs font-normal text-slate-500">(<?= $gPct ?>%)</span></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold text-slate-400 mb-2">จำแนกตามช่วงอายุ:</p>
                <div class="space-y-2">
                    <?php 
                        $totalAge = array_sum($evaluations['age_counts']) ?: 1;
                        foreach(array_slice($evaluations['age_counts'], 0, 4, true) as $aName => $aCnt):
                            $aPct = round(($aCnt / $totalAge) * 100, 1);
                    ?>
                        <div class="flex justify-between items-center text-xs bg-slate-950/40 px-3 py-2 rounded-xl">
                            <span class="text-slate-300 truncate"><?= esc($aName) ?></span>
                            <span class="font-mono text-purple-400 font-bold"><?= $aCnt ?> คน (<?= $aPct ?>%)</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Demographics: Top Provinces -->
    <div class="glass-card rounded-3xl p-6 bg-slate-900/60 border border-slate-800">
        <h3 class="text-base font-extrabold text-white flex items-center gap-2 mb-4">
            <i data-lucide="map-pin" class="w-5 h-5 text-emerald-400"></i>
            จังหวัดที่มีผู้เข้าร่วมสูงสุด
        </h3>
        <div class="space-y-2">
            <?php 
                $rank = 1;
                $topProvs = $evaluations['top_provinces'];
                if(empty($topProvs)):
            ?>
                <p class="text-xs text-slate-500">ไม่มีข้อมูลจังหวัด</p>
            <?php else: 
                foreach($topProvs as $pName => $pCnt):
            ?>
                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-slate-950/40 border border-slate-800/40">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-mono font-bold flex items-center justify-center">
                            <?= $rank++ ?>
                        </span>
                        <span class="text-xs text-slate-200 font-semibold"><?= esc($pName) ?></span>
                    </div>
                    <span class="font-mono text-xs font-bold text-emerald-400"><?= $pCnt ?> ชุด</span>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>

<!-- ==================== COMPETITIONS SUMMARY TABLE ==================== -->
<div class="glass-card rounded-3xl overflow-hidden bg-slate-900/60 border border-slate-800 mb-6 shadow-xl">
    <div class="p-5 sm:p-6 border-b border-slate-800">
        <h3 class="text-base font-extrabold text-white flex items-center gap-2">
            <i data-lucide="award" class="w-5 h-5 text-amber-400"></i>
            สถิติการรับสมัครเข้าร่วมการแข่งขันแยกตามรายการ
        </h3>
        <p class="text-xs text-slate-400 mt-1">สรุปจำนวนทีม, จำนวนนักเรียน และครูผู้ฝึกสอนในแต่ละรายการแข่งขัน</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs sm:text-sm">
            <thead>
                <tr class="bg-slate-950/70 border-b border-slate-800 text-slate-400 uppercase text-[11px] font-bold">
                    <th class="px-4 py-3.5 text-center w-12">ลำดับ</th>
                    <th class="px-4 py-3.5">รายการแข่งขัน</th>
                    <th class="px-4 py-3.5 text-center w-36">ระดับชั้น</th>
                    <th class="px-4 py-3.5 text-center w-28">ทีมสมัคร (ทีม)</th>
                    <th class="px-4 py-3.5 text-center w-28">ทีมอนุมัติ (ทีม)</th>
                    <th class="px-4 py-3.5 text-center w-28">นักเรียน (คน)</th>
                    <th class="px-4 py-3.5 text-center w-28">ครูผู้สอน (คน)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                <?php 
                    $cNo = 1;
                    foreach($competitions['list_stats'] as $cs):
                ?>
                    <tr class="hover:bg-slate-800/30 transition-colors">
                        <td class="px-4 py-3 text-center font-mono text-slate-500 font-bold"><?= $cNo++ ?></td>
                        <td class="px-4 py-3 text-slate-200 font-semibold"><?= esc($cs['name']) ?></td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 text-xs">
                                <?= esc($cs['level'] ?: 'ทุกระดับ') ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-mono font-bold text-cyan-400"><?= number_format($cs['total_teams']) ?></td>
                        <td class="px-4 py-3 text-center font-mono font-bold text-emerald-400"><?= number_format($cs['approved_teams']) ?></td>
                        <td class="px-4 py-3 text-center font-mono text-slate-300"><?= number_format($cs['students']) ?></td>
                        <td class="px-4 py-3 text-center font-mono text-slate-400"><?= number_format($cs['teachers']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <!-- Total Row -->
                <tr class="bg-slate-950/80 border-t-2 border-slate-800 font-bold text-white">
                    <td colspan="3" class="px-4 py-3.5 text-right text-slate-400">รวมทุกรายการแข่งขัน:</td>
                    <td class="px-4 py-3.5 text-center font-mono text-cyan-300"><?= number_format($competitions['total_teams']) ?></td>
                    <td class="px-4 py-3.5 text-center font-mono text-emerald-300"><?= number_format($competitions['approved_teams']) ?></td>
                    <td class="px-4 py-3.5 text-center font-mono text-amber-300"><?= number_format($competitions['total_students']) ?></td>
                    <td class="px-4 py-3.5 text-center font-mono text-purple-300"><?= number_format($competitions['total_teachers']) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ==================== EXECUTIVE PROJECT CONCLUSION (บทสรุปผลการดำเนินงานทั้งหมด) ==================== -->
<div class="glass-card rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-slate-900/90 via-indigo-950/40 to-slate-900/90 border border-indigo-500/30 shadow-2xl mb-8">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-indigo-500/20">
        <div class="p-2.5 rounded-2xl bg-indigo-500/20 border border-indigo-500/40 text-indigo-300">
            <i data-lucide="check-check" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="text-lg sm:text-xl font-extrabold text-white">
                บทสรุปผลการดำเนินงานโครงการทั้งหมด (Project Conclusion & Key Results)
            </h3>
            <p class="text-xs sm:text-sm text-slate-400 mt-0.5">
                สรุปภาพรวมความสำเร็จและผลลัพธ์ของโครงการจัดงานสัปดาห์วิทยาศาสตร์ ประจำปีการศึกษา <?= esc($selected_year) ?>
            </p>
        </div>
    </div>

    <!-- 4 Key Result Highlights -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- 1. ผู้มีส่วนร่วมรวม -->
        <div class="p-4 rounded-2xl bg-slate-950/60 border border-indigo-500/20">
            <p class="text-xs text-indigo-300 font-bold flex items-center gap-1.5 mb-1">
                <i data-lucide="users" class="w-4 h-4"></i> ยอดผู้มีส่วนร่วมทั้งหมด
            </p>
            <p class="text-2xl font-black text-white font-mono"><?= number_format($summary_overview['grand_total_people']) ?> <span class="text-xs font-normal text-slate-400">คน</span></p>
            <p class="text-[11px] text-slate-400 mt-1">รวมผู้ทำประเมิน (<?= number_format($summary_overview['total_evaluations']) ?>), ผู้รับเกียรติบัตร (<?= number_format($summary_overview['total_eval_claimed']) ?>), นร.แข่ง (<?= number_format($summary_overview['total_competitors']) ?>), ครู (<?= number_format($summary_overview['total_coaches']) ?>) และ Staff (<?= number_format($summary_overview['total_student_staff']) ?> คน)</p>
        </div>

        <!-- 2. อัตราความพึงพอใจ -->
        <div class="p-4 rounded-2xl bg-slate-950/60 border border-emerald-500/20">
            <p class="text-xs text-emerald-300 font-bold flex items-center gap-1.5 mb-1">
                <i data-lucide="star" class="w-4 h-4"></i> คะแนนความพึงพอใจรวม
            </p>
            <p class="text-2xl font-black text-emerald-400 font-mono"><?= number_format($evaluations['grand_mean'], 2) ?> <span class="text-xs font-normal text-slate-400">/ 5.00</span></p>
            <p class="text-[11px] text-emerald-300/80 mt-1">คิดเป็น <strong><?= number_format($evaluations['grand_percentage'], 2) ?>%</strong> (ระดับ<?= $evaluations['grand_quality']['text'] ?>)</p>
        </div>

        <!-- 3. เกียรติบัตรรวม -->
        <div class="p-4 rounded-2xl bg-slate-950/60 border border-purple-500/20">
            <p class="text-xs text-purple-300 font-bold flex items-center gap-1.5 mb-1">
                <i data-lucide="award" class="w-4 h-4"></i> การออกเกียรติบัตร
            </p>
            <p class="text-2xl font-black text-purple-300 font-mono"><?= number_format($summary_overview['total_certificates_all']) ?> <span class="text-xs font-normal text-slate-400">ใบ</span></p>
            <p class="text-[11px] text-slate-400 mt-1">ผ่านระบบ E-Certificate เคลมสิทธิ์ผ่านแบบประเมิน <?= number_format($summary_overview['total_eval_claimed']) ?> ราย</p>
        </div>

        <!-- 4. จำนวนกิจกรรมการแข่งขัน -->
        <div class="p-4 rounded-2xl bg-slate-950/60 border border-amber-500/20">
            <p class="text-xs text-amber-300 font-bold flex items-center gap-1.5 mb-1">
                <i data-lucide="trophy" class="w-4 h-4"></i> กิจกรรมการแข่งขัน
            </p>
            <p class="text-2xl font-black text-amber-300 font-mono"><?= number_format($summary_overview['total_competitions']) ?> <span class="text-xs font-normal text-slate-400">รายการ</span></p>
            <p class="text-[11px] text-slate-400 mt-1">มีทีมสมัครทั้งสิ้น <?= number_format($summary_overview['total_teams']) ?> ทีม (อนุมัติเข้าแข่ง <?= number_format($summary_overview['approved_teams']) ?> ทีม)</p>
        </div>
    </div>

    <!-- Narrative Summary -->
    <div class="p-4 sm:p-5 rounded-2xl bg-slate-950/80 border border-slate-800 text-xs sm:text-sm text-slate-300 leading-relaxed space-y-2 text-justify">
        <p>
            <strong>สรุปภาพรวมโครงการ:</strong> การจัดงานสัปดาห์วิทยาศาสตร์ ประจำปีการศึกษา <strong><?= esc($selected_year) ?></strong> ประสบความสำเร็จตามวัตถุประสงค์ทุกประการ โดยมีผู้มีส่วนร่วมในโครงการรวมทั้งสิ้น <strong><?= number_format($summary_overview['grand_total_people']) ?></strong> คน 
            แบ่งเป็นนักเรียนเข้าร่วมการแข่งขัน <strong><?= number_format($summary_overview['total_competitors']) ?></strong> คน (<strong><?= number_format($summary_overview['total_teams']) ?></strong> ทีม จาก <strong><?= number_format($summary_overview['total_competitions']) ?></strong> รายการแข่งขัน) 
            ครูผู้ฝึกสอน <strong><?= number_format($summary_overview['total_coaches']) ?></strong> ท่าน และนักเรียนช่วยงาน (Student Staff) <strong><?= number_format($summary_overview['total_student_staff']) ?></strong> คน
        </p>
        <p>
            ในส่วนของการประเมินผล มีผู้ตอบแบบประเมินความพึงพอใจจำนวน <strong><?= number_format($summary_overview['total_evaluations']) ?></strong> ชุด ได้คะแนนเฉลี่ยภาพรวม <strong><?= number_format($evaluations['grand_mean'], 2) ?></strong> จาก 5.00 คะแนน (S.D. = <?= number_format($evaluations['grand_sd'], 2) ?>) คิดเป็นร้อยละ <strong><?= number_format($evaluations['grand_percentage'], 2) ?>%</strong> ซึ่งจัดอยู่ในเกณฑ์ระดับ <strong>"<?= $evaluations['grand_quality']['text'] ?>"</strong> และมีการออกเกียรติบัตรออนไลน์รวมทุกประเภทรวม <strong><?= number_format($summary_overview['total_certificates_all']) ?></strong> ใบ
        </p>
    </div>
</div>

<!-- ==================== SCRIPT FOR CHARTS ==================== -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Question Bar Chart
    const qLabels = <?= json_encode(array_map(fn($q) => mb_substr($q['label'], 0, 25) . (mb_strlen($q['label']) > 25 ? '...' : ''), $evaluations['question_stats'])) ?>;
    const qMeans = <?= json_encode(array_column($evaluations['question_stats'], 'mean')) ?>;
    const qPcts = <?= json_encode(array_column($evaluations['question_stats'], 'percentage')) ?>;

    const ctxQ = document.getElementById('questionStatsChart')?.getContext('2d');
    if (ctxQ) {
        new Chart(ctxQ, {
            type: 'bar',
            data: {
                labels: qLabels,
                datasets: [
                    {
                        label: 'คะแนนเฉลี่ย (เต็ม 5.0)',
                        data: qMeans,
                        backgroundColor: 'rgba(99, 102, 241, 0.85)',
                        borderColor: '#6366f1',
                        borderWidth: 1,
                        borderRadius: 8,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            afterLabel: (ctx) => `คิดเป็นร้อยละ: ${qPcts[ctx.dataIndex]}%`
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#94a3b8', font: { size: 11 } },
                        grid: { display: false }
                    },
                    y: {
                        min: 0,
                        max: 5,
                        ticks: { color: '#94a3b8', stepSize: 1 },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    }
                }
            }
        });
    }

    // 2. Rating Distribution Donut Chart
    const distData = <?= json_encode(array_values([
        $evaluations['rating_dist'][5] ?? 0,
        $evaluations['rating_dist'][4] ?? 0,
        $evaluations['rating_dist'][3] ?? 0,
        $evaluations['rating_dist'][2] ?? 0,
        $evaluations['rating_dist'][1] ?? 0
    ])) ?>;

    const ctxDist = document.getElementById('ratingDistChart')?.getContext('2d');
    if (ctxDist) {
        new Chart(ctxDist, {
            type: 'doughnut',
            data: {
                labels: ['มากที่สุด', 'มาก', 'ปานกลาง', 'น้อย', 'น้อยที่สุด'],
                datasets: [{
                    data: distData,
                    backgroundColor: [
                        '#10b981', // 5
                        '#6366f1', // 4
                        '#f59e0b', // 3
                        '#f97316', // 2
                        '#ef4444'  // 1
                    ],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>

<?= $this->endSection() ?>
