<?= $this->extend('itsupport/layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-white tracking-tight tech-glow">วิเคราะห์ข้อมูลและแดชบอร์ด</h2>
        <p class="text-sm text-slate-400 mt-1 font-medium">ภาพรวมภาระงาน สถิติงานบริการ และผู้นำการลงบันทึกข้อมูลไอที</p>
    </div>
    <div class="flex gap-3">
        <a href="<?= base_url('itsupport/create') ?>" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white font-bold text-sm shadow-lg shadow-cyan-500/20 hover:scale-105 transition-all duration-200 flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> เพิ่มบันทึกงานใหม่
        </a>
    </div>
</div>

<!-- Stats Counter Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <!-- Card 1 -->
    <div class="glass-card p-6 rounded-3xl relative overflow-hidden flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">งานบริการสะสม</span>
            <span class="text-4xl font-black text-white block mt-1 tracking-tight"><?= number_format($total_tasks) ?></span>
            <span class="text-xs font-semibold text-cyan-400 mt-2 block">รายการทั้งหมดในฐานข้อมูล</span>
        </div>
        <div class="w-14 h-14 bg-cyan-500/10 rounded-2xl border border-cyan-500/20 flex items-center justify-center text-cyan-400">
            <i data-lucide="database" class="w-7 h-7"></i>
        </div>
    </div>
    
    <!-- Card 2 -->
    <div class="glass-card p-6 rounded-3xl relative overflow-hidden flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">ผลงานในเดือนนี้</span>
            <span class="text-4xl font-black text-white block mt-1 tracking-tight"><?= number_format($month_tasks) ?></span>
            <span class="text-xs font-semibold text-indigo-400 mt-2 block">บันทึกในเดือน <?= date('F Y') ?></span>
        </div>
        <div class="w-14 h-14 bg-indigo-500/10 rounded-2xl border border-indigo-500/20 flex items-center justify-center text-indigo-400">
            <i data-lucide="calendar" class="w-7 h-7"></i>
        </div>
    </div>
    
    <!-- Card 3 -->
    <div class="glass-card p-6 rounded-3xl relative overflow-hidden flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">ทีมผู้บันทึกระบบ</span>
            <span class="text-4xl font-black text-white block mt-1 tracking-tight"><?= count($leaderboard) ?></span>
            <span class="text-xs font-semibold text-emerald-400 mt-2 block">เจ้าหน้าที่ซ่อมบำรุง/บริการ</span>
        </div>
        <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl border border-emerald-500/20 flex items-center justify-center text-emerald-400">
            <i data-lucide="users" class="w-7 h-7"></i>
        </div>
    </div>
</div>

<!-- Charts & Leaderboards Grid -->
<div class="grid grid-cols-1 lg:grid-cols-5 gap-8 mb-10">
    <!-- Pie Chart Container -->
    <div class="glass-card p-6 rounded-3xl lg:col-span-3 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                <i data-lucide="pie-chart" class="text-cyan-400 w-5 h-5"></i>
                สัดส่วนงานบริการแยกตามประเภทงาน (Category)
            </h3>
            <p class="text-xs text-slate-400 mb-6">กราฟแสดงอัตราส่วนของ 8 หมวดหมู่การให้บริการตามต้นแบบ SKJ Journey</p>
        </div>
        <div class="w-full h-[280px] flex items-center justify-center relative">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <!-- Leaderboard Container -->
    <div class="glass-card p-6 rounded-3xl lg:col-span-2 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                <i data-lucide="award" class="text-indigo-400 w-5 h-5"></i>
                จัดอันดับผลงาน (IT Workload)
            </h3>
            <p class="text-xs text-slate-400 mb-6">ตารางแสดงปริมาณการบันทึกประวัติการทำงานของทีมเจ้าหน้าที่</p>
        </div>
        <div class="space-y-4 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
            <?php if(empty($leaderboard)): ?>
                <div class="text-slate-500 text-center py-10 text-sm">ยังไม่มีข้อมูลการบันทึกผลงาน</div>
            <?php else: ?>
                <?php $maxJob = reset($leaderboard)['job_count'] ?: 1; ?>
                <?php foreach($leaderboard as $idx => $user): ?>
                    <?php 
                        $percentage = round(($user['job_count'] / $maxJob) * 100);
                        $bgColors = ['bg-cyan-500', 'bg-indigo-500', 'bg-emerald-500', 'bg-amber-500'];
                        $bgColor = $bgColors[$idx % count($bgColors)];
                    ?>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[10px] <?= $idx == 0 ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700/50' ?>">
                                    <?= $idx + 1 ?>
                                </span>
                                <span class="font-bold text-slate-300"><?= $user['its_recorded_by'] ?></span>
                            </div>
                            <span class="font-black text-cyan-400"><?= number_format($user['job_count']) ?> งาน</span>
                        </div>
                        <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full <?= $bgColor ?> rounded-full" style="width: <?= $percentage ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Logs List -->
<div class="glass-card p-6 rounded-3xl">
    <div class="flex justify-between items-center mb-6 border-b border-slate-900 pb-4">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <i data-lucide="list-video" class="text-emerald-400 w-5 h-5"></i>
            รายการบันทึกผลงานล่าสุด 5 รายการ
        </h3>
        <a href="<?= base_url('itsupport/logs') ?>" class="text-xs font-bold text-cyan-400 hover:underline flex items-center gap-1">
            ดูประวัติทั้งหมด <i data-lucide="arrow-right" class="w-3 h-3"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-900">
                    <th class="pb-3 pr-4">รหัสใบงาน</th>
                    <th class="pb-3 px-4">วันที่ทำงาน</th>
                    <th class="pb-3 px-4">หมวดหมู่งาน</th>
                    <th class="pb-3 px-4">สถานที่</th>
                    <th class="pb-3 px-4">รายละเอียดผลงาน</th>
                    <th class="pb-3 px-4">ผู้ให้บริการ</th>
                    <th class="pb-3 pl-4 text-right">ดำเนินการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-900/40 text-slate-300">
                <?php if(empty($recent_logs)): ?>
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-500">ยังไม่มีข้อมูลการบันทึกประวัติ</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($recent_logs as $log): ?>
                        <tr class="hover:bg-slate-900/20 transition-all">
                            <td class="py-4 pr-4 font-mono font-bold text-cyan-400"><?= $log['its_ticket_code'] ?></td>
                            <td class="py-4 px-4 whitespace-nowrap text-xs"><?= date('d/m/Y H:i', strtotime($log['its_date'])) ?> น.</td>
                            <td class="py-4 px-4 whitespace-nowrap text-xs">
                                <span class="px-3 py-1 rounded-full bg-slate-900 text-slate-300 border border-slate-800 text-[10px] font-extrabold uppercase">
                                    <?= $log['its_category'] ?>
                                </span>
                            </td>
                            <td class="py-4 px-4 text-xs font-bold text-amber-500 whitespace-nowrap">📍 <?= $log['its_location'] ?></td>
                            <td class="py-4 px-4 max-w-[250px] truncate text-slate-400" title="<?= esc($log['its_task']) ?>"><?= esc($log['its_task']) ?></td>
                            <td class="py-4 px-4 whitespace-nowrap text-xs font-bold"><?= $log['its_recorded_by'] ?></td>
                            <td class="py-4 pl-4 text-right whitespace-nowrap text-xs">
                                <div class="flex justify-end gap-2">
                                    <a href="<?= base_url('itsupport/view/' . $log['its_id']) ?>" class="p-2 bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 hover:text-white rounded-lg transition-all" title="ดูรายละเอียด">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="<?= base_url('itsupport/edit/' . $log['its_id']) ?>" class="p-2 bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/20 text-cyan-400 rounded-lg transition-all" title="แก้ไขข้อมูล">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Script for Loading Chart.js from CDN and drawing beautiful dashboard pie-chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        const statsData = <?= json_encode($category_stats) ?>;
        
        const labels = Object.keys(statsData);
        const dataValues = Object.values(statsData);

        // Curated HARMONIOUS custom dark tech palette
        const colors = [
            '#06b6d4', // cyan-500
            '#6366f1', // indigo-500
            '#10b981', // emerald-500
            '#f59e0b', // amber-500
            '#ec4899', // pink-500
            '#8b5cf6', // purple-500
            '#f43f5e', // rose-500
            '#64748b'  // slate-500
        ];

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#111928',
                    hoverOffset: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#94a3b8',
                            font: {
                                family: 'Sarabun',
                                size: 10,
                                weight: 'bold'
                            },
                            boxWidth: 10,
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: '#111928',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(255,255,255,0.08)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 12,
                        bodyFont: {
                            family: 'Sarabun',
                            size: 11
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
<?= $this->endSection() ?>
