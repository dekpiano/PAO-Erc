<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">การลางาน</h1>
        <p class="text-slate-500 text-sm mt-1">ประวัติและสถานะการขออนุมัติลาของคุณ</p>
    </div>
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
        <form action="<?= base_url('staff/leave') ?>" method="GET" id="filterForm" class="flex items-center gap-2">
            <label for="f_year" class="text-xs font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">ปีงบฯ</label>
            <select name="f_year" id="f_year" onchange="document.getElementById('filterForm').submit()" class="bg-white border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2 px-4 pr-10 font-bold transition-all appearance-none cursor-pointer">
                <?php foreach ($fiscalYears as $fy): ?>
                    <option value="<?= $fy ?>" <?= ($fYearBE == $fy) ? 'selected' : '' ?>>พ.ศ. <?= $fy ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <a href="<?= base_url('staff/leave/create') ?>" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-blue-200">
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

<!-- Overview Cards for Admin -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white border border-slate-200 rounded-[2rem] p-8 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
        <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform">
            <i data-lucide="files" class="w-32 h-32 text-slate-900"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">ใบลาทั้งหมดในปีงบฯ</p>
            <div class="flex items-end gap-3">
                <span class="text-4xl font-black text-slate-900"><?= number_format($stats['total']) ?></span>
                <span class="text-xs font-bold text-slate-400 mb-1.5 uppercase">รายการ</span>
            </div>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-100 rounded-[2rem] p-8 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
        <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform">
            <i data-lucide="clock" class="w-32 h-32 text-amber-600"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-amber-600 mb-2">รอการอนุมัติ</p>
            <div class="flex items-end gap-3">
                <span class="text-4xl font-black text-amber-700"><?= number_format($stats['pending']) ?></span>
                <span class="text-xs font-bold text-amber-600 mb-1.5 uppercase">รายการ</span>
            </div>
            <?php if($stats['pending'] > 0): ?>
                <div class="mt-4 inline-flex items-center gap-2 bg-amber-500 text-white text-[9px] font-black px-3 py-1 rounded-full animate-bounce">
                    <i data-lucide="alert-circle" class="w-3 h-3"></i> ACTION REQUIRED
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-emerald-50 border border-emerald-100 rounded-[2rem] p-8 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
        <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform">
            <i data-lucide="check-circle" class="w-32 h-32 text-emerald-600"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600 mb-2">อนุมัติเรียบร้อย</p>
            <div class="flex items-end gap-3">
                <span class="text-4xl font-black text-emerald-700"><?= number_format($stats['approved']) ?></span>
                <span class="text-xs font-bold text-emerald-600 mb-1.5 uppercase">รายการ</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-xs border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 hidden sm:table-cell">#อ้างอิง</th>
                    <th class="px-6 py-4">ประเภท/เหตุผล</th>
                    <th class="px-6 py-4">ผู้ขอลา</th>
                    <th class="px-6 py-4 hidden md:table-cell">ช่วงเวลาที่ลา</th>
                    <th class="px-6 py-4 text-center hidden lg:table-cell">จำนวนวัน</th>
                    <th class="px-6 py-4 text-center">สถานะ</th>
                    <th class="px-6 py-4 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($leaves)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="font-medium">ยังไม่มีรายการใบลาให้จัดการ</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($leaves as $index => $leave): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-slate-500 hidden sm:table-cell">
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
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-700"><?= $leave['u_prefix'] ?><?= $leave['u_fullname'] ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            <span class="text-slate-700 font-medium"><?= date('d/m/', strtotime($leave['leave_from_date'])) . (date('Y', strtotime($leave['leave_from_date'])) + 543) ?></span> 
                            <span class="text-slate-400 text-xs mx-1">ถึง</span> 
                            <span class="text-slate-700 font-medium"><?= date('d/m/', strtotime($leave['leave_to_date'])) . (date('Y', strtotime($leave['leave_to_date'])) + 543) ?></span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-700 hidden lg:table-cell">
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
                            <div class="flex items-center justify-end gap-2">
                                <?php if($leave['leave_status'] == 'pending'): ?>
                                    <button onclick="updateStatus(<?= $leave['leave_id'] ?>, 'approved')" class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-lg transition-all" title="อนุมัติ">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="updateStatus(<?= $leave['leave_id'] ?>, 'rejected')" class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg transition-all" title="ไม่อนุมัติ">
                                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    </button>
                                <?php endif; ?>
                                <a href="<?= base_url('staff/leave/export/'.$leave['leave_id']) ?>" class="w-8 h-8 flex items-center justify-center bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-lg transition-all" title="พิมพ์ใบลา">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
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
<script>
    async function updateStatus(id, status) {
        const text = status === 'approved' ? 'คุณต้องการอนุมัติใบลาใบนี้ใช่หรือไม่?' : 'คุณต้องการปฏิเสธใบลาใบนี้ใช่หรือไม่?';
        const confirmButtonColor = status === 'approved' ? '#10b981' : '#f43f5e';

        const result = await Swal.fire({
            title: 'ยืนยันการดำเนินการ',
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            customClass: {
                popup: 'rounded-[2rem]',
            }
        });

        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('status', status);

            try {
                const response = await fetch('<?= base_url('staff/leave/update-status') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500,
                        customClass: {
                            popup: 'rounded-[2rem]',
                        }
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ล้มเหลว',
                        text: data.message,
                        customClass: {
                            popup: 'rounded-[2rem]',
                        }
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้',
                    customClass: {
                        popup: 'rounded-[1.5rem]',
                    }
                });
            }
        }
    }
</script>
<?= $this->endSection() ?>
