<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">การลางาน</h1>
        <p class="text-slate-500 text-sm mt-1">ประวัติและสถานะการขออนุมัติลาของคุณ</p>
    </div>
    <div class="flex items-center gap-3">
        <form action="<?= base_url('staff/leave') ?>" method="GET" id="filterForm" class="flex items-center gap-2">
            <label for="f_year" class="text-xs font-bold text-slate-400 uppercase tracking-wider">ปีงบประมาณ</label>
            <select name="f_year" id="f_year" onchange="document.getElementById('filterForm').submit()" class="bg-white border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2 px-4 pr-10 font-bold transition-all appearance-none cursor-pointer">
                <?php foreach ($fiscalYears as $fy): ?>
                    <option value="<?= $fy ?>" <?= ($fYearBE == $fy) ? 'selected' : '' ?>>พ.ศ. <?= $fy ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <a href="<?= base_url('staff/leave/create') ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-blue-200">
            <i data-lucide="plus" class="w-5 h-5"></i> เขียนใบลาใหม่
        </a>
    </div>
</div>

<div class="mb-4 flex items-center gap-2 px-1">
    <div class="flex items-center gap-1.5 text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">
        <i data-lucide="calendar-range" class="w-3.5 h-3.5"></i>
        <span>ช่วงเวลาปีงบฯ: <?= $fStart ?> - <?= $fEnd ?></span>
    </div>
</div>
<!-- สถิติการลาประจำปีงบประมาณ -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <?php 
    $colors = [
        'sick' => ['bg' => 'bg-rose-50', 'icon' => 'text-rose-500', 'border' => 'border-rose-100'],
        'personal' => ['bg' => 'bg-amber-50', 'icon' => 'text-amber-500', 'border' => 'border-amber-100'],
        'vacation' => ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-500', 'border' => 'border-emerald-100']
    ];
    foreach ($statsSummary as $type => $stat): 
        $c = $colors[$type];
    ?>
    <div class="<?= $c['bg'] ?> <?= $c['border'] ?> border rounded-2xl p-6 transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-bold text-slate-600">สถิติ<?= $stat['label'] ?></span>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">ปีงบฯ <?= $fYearBE ?></span>
        </div>
        <div class="flex items-end justify-between">
            <div>
                <div class="text-3xl font-black text-slate-800"><?= $stat['days'] ?> <span class="text-sm font-bold text-slate-500">วัน</span></div>
                <div class="text-xs text-slate-500 mt-1 font-medium">รวมทั้งหมด <?= $stat['count'] ?> ครั้ง</div>
            </div>
            <div class="<?= $c['icon'] ?> opacity-20">
                <i data-lucide="<?= $type == 'sick' ? 'thermometer' : ($type == 'personal' ? 'briefcase' : 'palmtree') ?>" class="w-12 h-12"></i>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-xs border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">#อ้างอิง</th>
                    <th class="px-6 py-4">ประเภท/เหตุผล</th>
                    <th class="px-6 py-4">ช่วงเวลาที่ลา</th>
                    <th class="px-6 py-4 text-center">จำนวนวัน</th>
                    <th class="px-6 py-4 text-center">สถานะ</th>
                    <th class="px-6 py-4 text-right">จัดการ/ส่งออก</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($leaves)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="font-medium">ยังไม่มีประวัติการลา</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($leaves as $index => $leave): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-slate-500">
                            #LV-<?= str_pad($leave['leave_id'], 4, '0', STR_PAD_LEFT) ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">
                                <?php 
                                    $types = [
                                        'sick' => 'ลาป่วย', 'personal' => 'ลากิจส่วนตัว', 'maternity' => 'ลาคลอดบุตร',
                                        'paternity' => 'ลาไปช่วยเหลือภริยาที่คลอดบุตร', 'vacation' => 'ลาพักผ่อน',
                                        'ordination' => 'ลาอุปสมบท/ฮัจย์', 'military' => 'ลาเข้ารับราชการทหาร',
                                        'study' => 'ลาศึกษา/ฝึกอบรม', 'international_org' => 'ลาปฏิบัติงานองค์กรระหว่างประเทศ',
                                        'spouse_follow' => 'ลาติดตามคู่สมรส', 'rehabilitation' => 'ลาฟื้นฟูอาชีพ'
                                    ];
                                    echo $types[$leave['leave_type']] ?? $leave['leave_type'];
                                ?>
                            </div>
                            <div class="text-slate-500 text-xs mt-0.5 truncate max-w-[200px]" title="<?= esc($leave['leave_reason']) ?>">
                                <?= esc($leave['leave_reason']) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-700"><?= date('d/m/', strtotime($leave['leave_from_date'])) . (date('Y', strtotime($leave['leave_from_date'])) + 543) ?></span> 
                            <span class="text-slate-400 text-xs mx-1">ถึง</span> 
                            <span class="text-slate-700"><?= date('d/m/', strtotime($leave['leave_to_date'])) . (date('Y', strtotime($leave['leave_to_date'])) + 543) ?></span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-700">
                            <?= floatval($leave['leave_days']) ?> <span class="font-normal text-xs text-slate-500">วัน</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($leave['leave_status'] == 'approved'): ?>
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5"><i class="w-3 h-3 rounded-full bg-green-500"></i> อนุมัติ</span>
                            <?php elseif ($leave['leave_status'] == 'rejected'): ?>
                                <span class="bg-rose-100 text-rose-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5"><i class="w-3 h-3 rounded-full bg-rose-500"></i> ไม่อนุมัติ</span>
                            <?php else: ?>
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5"><i class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></i> รออนุมัติ</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= base_url('staff/leave/export/'.$leave['leave_id']) ?>" class="inline-flex items-center gap-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                <i data-lucide="printer" class="w-3.5 h-3.5"></i> พิมพ์ใบลา
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
