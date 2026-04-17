<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<style>
    .monthly-table th, .monthly-table td {
        border: 1px solid #e2e8f0;
        text-align: center;
        padding: 4px 2px;
        font-size: 10px;
        min-width: 25px;
    }
    .monthly-table thead th {
        background-color: #f8fafc;
        font-weight: 800;
        color: #475569;
    }
    .bg-weekend { background-color: #f1f5f9; color: #94a3b8; }
    .status-late { color: #e11d48; font-weight: bold; }
    .status-leave { color: #2563eb; font-weight: bold; }
    .status-absent { color: #dc2626; font-weight: bold; }
</style>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">สรุปบัญชีการมาปฏิบัติงานประจำเดือน</h2>
        <p class="text-slate-500 mt-1 font-medium text-lg"><?= $thai_months_full[$month] ?> พ.ศ. <?= $year + 543 ?></p>
    </div>
    <div class="flex items-center gap-4">
        <form action="" method="get" class="flex items-center gap-3">
            <select name="month" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold text-sm outline-none shadow-sm">
                <?php foreach($thai_months_full as $mId => $mName): ?>
                <option value="<?= $mId ?>" <?= $mId == $month ? 'selected' : '' ?>><?= $mName ?></option>
                <?php endforeach; ?>
            </select>
            <select name="year" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold text-sm outline-none shadow-sm">
                <?php for($y = date('Y'); $y >= date('Y')-3; $y--): ?>
                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y + 543 ?></option>
                <?php endfor; ?>
            </select>
        </form>
        <div class="flex items-center gap-2">
            <a href="<?= base_url('staff/attendance-admin/report/monthly/export?month=' . $month . '&year=' . $year) ?>" class="px-6 py-2 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700 transition-all flex items-center gap-2 shadow-lg shadow-emerald-100">
                <i data-lucide="file-down" class="w-4 h-4"></i> ส่งออก Excel
            </a>
            <button onclick="window.print()" class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-all flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i> พิมพ์รายงาน
            </button>
        </div>
    </div>
</div>

<div class="glass-card rounded-[2.5rem] overflow-hidden border border-slate-200 bg-white shadow-xl shadow-slate-200/50">
    <div class="overflow-x-auto">
        <table class="w-full monthly-table border-collapse">
            <thead>
                <tr>
                    <th rowspan="2" class="min-w-[40px]">ที่</th>
                    <th rowspan="2" class="min-w-[180px] text-left px-4">ชื่อ-นามสกุล</th>
                    <?php for($d = 1; $d <= $days_in_month; $d++): 
                        $dayOfWeek = date('w', strtotime("$year-$month-$d"));
                        $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                    ?>
                    <th class="<?= $isWeekend ? 'bg-weekend' : '' ?>"><?= $thai_days_short[$dayOfWeek] ?></th>
                    <?php endfor; ?>
                    <th colspan="1">สาย</th>
                    <th colspan="2">ลาพักผ่อน</th>
                    <th colspan="2">ลากิจ</th>
                    <th colspan="2">ลาป่วย</th>
                    <th rowspan="2" class="min-w-[100px]">หมายเหตุ</th>
                </tr>
                <tr>
                    <?php for($d = 1; $d <= $days_in_month; $d++): ?>
                    <th><?= $d ?></th>
                    <?php endfor; ?>
                    <!-- Summary Headers -->
                    <th>วัน</th>
                    <th>ครั้ง</th><th>วัน</th>
                    <th>ครั้ง</th><th>วัน</th>
                    <th>ครั้ง</th><th>วัน</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($users as $user): 
                    $summary = ['สาย' => 0, 'ลาพักผ่อน' => 0, 'ลากิจ' => 0, 'ลาป่วย' => 0, 'ลาพักผ่อน_days' => 0, 'ลากิจ_days' => 0, 'ลาป่วย_days' => 0];
                ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td><?= $no++ ?></td>
                    <td class="text-left px-4 font-bold text-slate-700"><?= $user['u_prefix'] . $user['u_fullname'] ?></td>
                    <?php for($d = 1; $d <= $days_in_month; $d++): 
                        $dayOfWeek = date('w', strtotime("$year-$month-$d"));
                        $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                        $entry = $report_grid[$user['u_id']][$d] ?? null;
                        $display = '';
                        $class = '';

                        if ($entry) {
                            $st = $entry['status'];
                            if ($st == 'สาย') { $display = 'ส'; $class = 'status-late'; $summary['สาย']++; }
                            elseif ($st == 'ลาพักผ่อน') { $display = 'พ'; $class = 'status-leave'; $summary['ลาพักผ่อน']++; $summary['ลาพักผ่อน_days']++; }
                            elseif ($st == 'ลากิจ') { $display = 'ก'; $class = 'status-leave'; $summary['ลากิจ']++; $summary['ลากิจ_days']++; }
                            elseif ($st == 'ลาป่วย') { $display = 'ป'; $class = 'status-leave'; $summary['ลาป่วย']++; $summary['ลาป่วย_days']++; }
                            elseif ($st == 'ขาด') { $display = 'ข'; $class = 'status-absent'; }
                        }
                    ?>
                    <td class="<?= $isWeekend ? 'bg-weekend/30' : '' ?> <?= $class ?>"><?= $display ?></td>
                    <?php endfor; ?>

                    <!-- Summary Data -->
                    <td class="bg-red-50 font-bold"><?= $summary['สาย'] ?: '' ?></td>
                    <td class="bg-blue-50"><?= $summary['ลาพักผ่อน'] ?: '' ?></td><td class="bg-blue-50 font-bold"><?= $summary['ลาพักผ่อน_days'] ?: '' ?></td>
                    <td class="bg-emerald-50"><?= $summary['ลากิจ'] ?: '' ?></td><td class="bg-emerald-50 font-bold"><?= $summary['ลากิจ_days'] ?: '' ?></td>
                    <td class="bg-amber-50"><?= $summary['ลาป่วย'] ?: '' ?></td><td class="bg-amber-50 font-bold"><?= $summary['ลาป่วย_days'] ?: '' ?></td>
                    <td class="text-left text-[9px] px-2"></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="bg-white p-4 rounded-2xl border border-slate-200">
        <span class="text-[10px] font-black uppercase text-slate-400 block mb-1">ส = สาย</span>
        <div class="h-1 w-8 bg-red-500 rounded-full"></div>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200">
        <span class="text-[10px] font-black uppercase text-slate-400 block mb-1">พ = ลาพักผ่อน</span>
        <div class="h-1 w-8 bg-blue-500 rounded-full"></div>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200">
        <span class="text-[10px] font-black uppercase text-slate-400 block mb-1">ก = ลากิจ</span>
        <div class="h-1 w-8 bg-emerald-500 rounded-full"></div>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200">
        <span class="text-[10px] font-black uppercase text-slate-400 block mb-1">ป = ลาป่วย</span>
        <div class="h-1 w-8 bg-amber-500 rounded-full"></div>
    </div>
</div>

<?= $this->endSection() ?>
