<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<style>
    .report-table th, .report-table td {
        border: 1px solid #e2e8f0;
        text-align: center;
        padding: 4px;
        font-size: 11px;
    }
    .report-table thead th {
        background-color: #f8fafc;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .bg-month-1 { background-color: #fef9c3; } /* Yellowish */
    .bg-month-2 { background-color: #dcfce7; } /* Greenish */
    .bg-month-3 { background-color: #fce7f3; } /* Pinkish */
    .bg-month-4 { background-color: #dbeafe; } /* Bluish */
</style>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">รายงานสรุปประจำปีงบประมาณ <?= $fiscal_year ?></h2>
        <p class="text-slate-500 mt-1 font-medium">สถิติการ มาสาย และการพักผ่อน/ลากิจ/ลาป่วย แยกรายเดือน</p>
    </div>
    <div class="flex items-center gap-4">
        <form action="" method="get" class="flex items-center gap-3">
            <select name="year" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold text-sm outline-none focus:ring-2 focus:ring-blue-100 transition-all shadow-sm">
                <?php 
                $currentY = date('n') >= 10 ? date('Y') + 1 : date('Y');
                for($i = $currentY; $i >= $currentY - 5; $i--): 
                ?>
                <option value="<?= $i ?>" <?= $i == $fiscal_year ? 'selected' : '' ?>>ปีงบประมาณ <?= $i ?></option>
                <?php endfor; ?>
            </select>

            <select name="round" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold text-sm outline-none focus:ring-2 focus:ring-blue-100 transition-all shadow-sm">
                <option value="all" <?= $selected_round == 'all' ? 'selected' : '' ?>>ทั้งปีงบประมาณ</option>
                <option value="1" <?= $selected_round == '1' ? 'selected' : '' ?>>รอบที่ 1 (ต.ค. - มี.ค.)</option>
                <option value="2" <?= $selected_round == '2' ? 'selected' : '' ?>>รอบที่ 2 (เม.ย. - ก.ย.)</option>
            </select>
        </form>
        <div class="flex items-center gap-2">
            <a href="<?= base_url('staff/attendance-admin/report/annual/export?year=' . $fiscal_year . '&round=' . $selected_round) ?>" class="px-6 py-2 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700 transition-all flex items-center gap-2 shadow-lg shadow-emerald-100">
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
        <table class="w-full report-table border-collapse">
            <thead>
                <tr>
                    <th rowspan="3" class="min-w-[200px] bg-white text-left px-6">ชื่อ-นามสกุล</th>
                    <?php 
                    $colorIndex = 1;
                    foreach($months_order as $m): 
                        $bgClass = "bg-month-" . $colorIndex;
                        $colorIndex = ($colorIndex % 4) + 1;
                    ?>
                    <th colspan="7" class="<?= $bgClass ?> py-2"><?= $thai_months[$m] ?>-<?= ($m >= 10 ? ($fiscal_year - 1) % 100 : $fiscal_year % 100) ?></th>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <?php foreach($months_order as $m): ?>
                    <th rowspan="2">สาย</th>
                    <th colspan="2">ลาพักผ่อน</th>
                    <th colspan="2">ลากิจ</th>
                    <th colspan="2">ลาป่วย</th>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <?php foreach($months_order as $m): ?>
                    <th>ครั้ง</th><th>วัน</th>
                    <th>ครั้ง</th><th>วัน</th>
                    <th>ครั้ง</th><th>วัน</th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="text-left px-6 py-3 font-bold text-slate-700"><?= $user['u_prefix'] . $user['u_fullname'] ?></td>
                    <?php foreach($months_order as $m): ?>
                    <?php 
                        $uData = $report_data[$user['u_id']][$m] ?? [];
                        $late = $uData['สาย'] ?? '';
                        $vacation = $uData['ลาพักผ่อน'] ?? '';
                        $personal = $uData['ลากิจ'] ?? '';
                        $sick = $uData['ลาป่วย'] ?? '';
                    ?>
                    <td class="bg-slate-50/30"><?= $late ?></td>
                    <td><?= $vacation ?></td><td class="bg-slate-50/50"><?= $vacation ?></td>
                    <td><?= $personal ?></td><td class="bg-slate-50/50"><?= $personal ?></td>
                    <td><?= $sick ?></td><td class="bg-slate-50/50"><?= $sick ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8 p-6 bg-blue-50 rounded-3xl border border-blue-100">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white flex-shrink-0">
            <i data-lucide="info" class="w-6 h-6"></i>
        </div>
        <div>
            <h4 class="font-bold text-blue-900 mb-1">คำแนะนำการใช้งานรายงาน</h4>
            <p class="text-sm text-blue-700 leading-relaxed">
                รายงานนี้จะสรุปผลอัตโนมัติจากไฟล์ Excel ที่อัปโหลดในหน้า "จัดการการเข้างาน" 
                โดยระบบจะนับจำนวนครั้งและจำนวนวันจากการปรากฏของสถิตินั้นๆ ในแต่ละเดือน 
                หากต้องการให้การแยกประเภทลาแม่นยำขึ้น โปรดตรวจสอบว่าในไฟล์ Excel (คอลัมน์ I) มีคำว่า "ลาป่วย", "ลากิจ" หรือ "ลาพักผ่อน" ระบุไว้อย่างชัดเจน
            </p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
