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
        <button onclick="openLeaveModal()" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-emerald-200">
            <i data-lucide="user-plus" class="w-5 h-5"></i> บันทึกการลาแทน
        </button>
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

    // --- ส่วนจัดการ Modal การลาแทน ---
    function openLeaveModal() {
        document.getElementById('leaveModal').classList.remove('hidden');
        initDatepickers();
    }

    function closeLeaveModal() {
        document.getElementById('leaveModal').classList.add('hidden');
    }

    function initDatepickers() {
        // ตั้งค่า Flatpickr ภาษาไทย + พ.ศ.
        const dateConfig = {
            locale: "th",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            onReady: function(selectedDates, dateStr, instance) {
                const adjustYear = () => {
                    if (instance.altInput) {
                        const value = instance.altInput.value;
                        if (value && value.includes('/')) {
                            const parts = value.split('/');
                            if (parts.length === 3 && parts[2].length === 4 && parseInt(parts[2]) < 2500) {
                                parts[2] = parseInt(parts[2]) + 543;
                                instance.altInput.value = parts.join('/');
                            }
                        }
                    }
                };
                instance.altInput.addEventListener('blur', adjustYear);
                instance.config.onChange.push(function(selDates, dateStr, inst) {
                    setTimeout(adjustYear, 10);
                    calculateDays();
                });
                adjustYear();
            }
        };

        flatpickr(".datepicker", dateConfig);
    }

    function calculateDays() {
        const fromDate = document.getElementById('leave_from_date').value;
        const toDate = document.getElementById('leave_to_date').value;
        
        if(fromDate && toDate) {
            const start = new Date(fromDate);
            const end = new Date(toDate);
            let diffTime = Math.abs(end - start);
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            if(diffDays > 0) {
                document.getElementById('leave_days').value = diffDays;
            }
        }
    }

    // Toggle ช่องผู้รับมอบงาน
    function toggleSubstitute() {
        const type = document.getElementById('leave_type').value;
        const container = document.getElementById('substitute_container');
        const select = document.getElementById('leave_substitute_id');
        if (type === 'vacation') {
            container.classList.remove('hidden');
            select.required = true;
        } else {
            container.classList.add('hidden');
            select.required = false;
        }
    }

    // ฟังก์ชันดึงประวัติการลาล่าสุดแบบอัตโนมัติ
    async function fetchLastLeave(userId) {
        if (!userId) return;

        // ล้างค่าเดิมก่อน
        document.getElementById('leave_last_from_date').value = '';
        document.getElementById('leave_last_to_date').value = '';
        document.getElementById('leave_last_days').value = '';

        try {
            const response = await fetch(`<?= base_url('staff/leave/get-last/') ?>${userId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const res = await response.json();

            if (res.status === 'success') {
                // อัปเดตค่าใน Field (ใช้ _flatpickr.setDate ถ้าจำเป็น แต่ในที่นี้เราใช้ Input ธรรมดาที่ซ่อน Datepicker ไว้)
                document.getElementById('leave_last_from_date')._flatpickr.setDate(res.data.from_date);
                document.getElementById('leave_last_to_date')._flatpickr.setDate(res.data.to_date);
                document.getElementById('leave_last_days').value = res.data.days;
                
                // แจ้งเตือนเล็กน้อย
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'โหลดประวัติการลาล่าสุดเรียบร้อย',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }
</script>

<!-- Add Proxy Leave Modal -->
<div id="leaveModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeLeaveModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl p-4">
        <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-xl font-black text-slate-900">บันทึกการลาแทนบุคลากร</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Admin Proxy Mode</p>
                </div>
                <button onclick="closeLeaveModal()" class="text-slate-400 hover:text-slate-600 transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>
            
            <form action="<?= base_url('staff/leave/store') ?>" method="POST" class="p-8 overflow-y-auto">
                <?= csrf_field() ?>
                
                <!-- เลือกบุคลากร -->
                <div class="mb-10 bg-blue-50/50 p-6 rounded-3xl border border-blue-100">
                    <label class="block text-[11px] font-black text-blue-600 uppercase tracking-widest mb-3 ml-1">1. เลือกบุคลากรที่ต้องการลาให้ (Proxy Target)</label>
                    <select name="leave_user_id" id="leave_user_id" onchange="fetchLastLeave(this.value)" required class="w-full px-5 py-4 bg-white border-2 border-slate-200 focus:border-blue-500 rounded-2xl font-black text-slate-900 outline-none transition-all appearance-none cursor-pointer">
                        <option value="">-- ค้นหาและเลือกรายชื่อพนักงาน --</option>
                        <?php foreach($users_list as $u): ?>
                            <option value="<?= $u['u_id'] ?>"><?= $u['u_prefix'] . $u['u_fullname'] ?> [<?= $u['u_role'] ?>]</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-black text-slate-800 flex items-center gap-2 mb-6">
                            <i data-lucide="file-text" class="w-5 h-5 text-blue-500"></i> ข้อมูลการลา
                        </h4>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ประเภทการลา <span class="text-rose-500">*</span></label>
                        <select name="leave_type" id="leave_type" required onchange="toggleSubstitute()" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none">
                            <option value="sick">ลาป่วย</option>
                            <option value="personal">ลากิจส่วนตัว</option>
                            <option value="maternity">ลาคลอดบุตร</option>
                            <option value="paternity">ลาไปช่วยเหลือภริยาที่คลอดบุตร</option>
                            <option value="vacation">ลาพักผ่อน</option>
                            <option value="ordination">ลาอุปสมบท / ไปประกอบพิธีฮัจย์</option>
                            <option value="military">ลาเข้ารับการตรวจเลือกหรือเข้ารับการเตรียมพล</option>
                            <option value="study">ลาไปศึกษา ฝึกอบรม ปฏิบัติการวิจัย หรือดูงาน</option>
                            <option value="international_org">ลาไปปฏิบัติงานในองค์การระหว่างประเทศ</option>
                            <option value="spouse_follow">ลาติดตามคู่สมรส</option>
                            <option value="rehabilitation">ลาไปฟื้นฟูสมรรถภาพด้านอาชีพ</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">เนื่องจาก (ระบุสาเหตุ) <span class="text-rose-500">*</span></label>
                        <input type="text" name="leave_reason" required placeholder="เหตุผลการลา..." class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ตั้งแต่วันที่ <span class="text-rose-500">*</span></label>
                        <input type="text" name="leave_from_date" id="leave_from_date" required placeholder="เลือกวันที่" class="datepicker w-full px-5 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ถึงวันที่ <span class="text-rose-500">*</span></label>
                        <input type="text" name="leave_to_date" id="leave_to_date" required placeholder="เลือกวันที่" class="datepicker w-full px-5 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">จำนวนหน่วยวันลา <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="number" step="0.5" name="leave_days" id="leave_days" required placeholder="0" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none pr-12">
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">วัน</span>
                        </div>
                    </div>

                    <div id="substitute_container" class="hidden">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ผู้ปฏิบัติราชการแทน <span class="text-rose-500">*</span></label>
                        <select name="leave_substitute_id" id="leave_substitute_id" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none">
                            <option value="">-- เลือกผู้รับมอบงาน --</option>
                            <?php foreach($users_list as $u): ?>
                                <option value="<?= $u['u_id'] ?>"><?= $u['u_prefix'] . $u['u_fullname'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ในระหว่างลาจะติดต่อได้ที่</label>
                        <textarea name="leave_contact" rows="2" placeholder="ที่อยู่หรือเบอร์โทรศัพท์..." class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 rounded-2xl font-bold text-slate-800 transition-all outline-none"></textarea>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-sm font-black text-slate-800 flex items-center gap-2 mb-6">
                        <i data-lucide="history" class="w-5 h-5 text-indigo-500"></i> ประวัติการลาครั้งสุดท้าย
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ลาตั้งแต่วันที่</label>
                            <input type="text" name="leave_last_from_date" id="leave_last_from_date" class="datepicker w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-800 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ลาถึงวันที่</label>
                            <input type="text" name="leave_last_to_date" id="leave_last_to_date" class="datepicker w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-800 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">จำนวนวัน</label>
                            <input type="number" step="0.5" name="leave_last_days" id="leave_last_days" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-800 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 mt-4 border-t border-slate-50">
                    <button type="button" onclick="closeLeaveModal()" class="px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-[1.5rem] font-black transition-all">ยกเลิก</button>
                    <button type="submit" class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-[1.5rem] font-black transition-all shadow-xl shadow-blue-100 flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i> บันทึกใบลาให้พนักงาน
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
