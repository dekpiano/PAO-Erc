<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span>จัดการรายชื่อสมัครแข่งขันสัปดาห์วิทยาศาสตร์</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">จัดการสถานะการยื่นสมัคร และส่งออกรายงานของนักเรียนแต่ละสถาบัน</p>
    </div>
    
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="<?= base_url('staff/science-week/export?' . http_build_query($_GET)) ?>" class="w-full md:w-auto justify-center px-5 py-3 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:text-slate-900 font-bold text-xs sm:text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 shadow-sm">
            <i data-lucide="file-spreadsheet" class="w-4 h-4 text-emerald-600"></i> ส่งออกรายงาน Excel
        </a>
    </div>
</div>

<!-- Filters Card -->
<div class="glass-card p-4 sm:p-6 rounded-3xl mb-6 bg-white dark:bg-slate-900/60">
    <form method="GET" action="<?= base_url('staff/science-week') ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        <!-- Search input -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i data-lucide="search" class="w-4 h-4"></i></span>
            <input type="text" name="search" value="<?= esc($search) ?>" placeholder="ค้นหา รหัส, โรงเรียน, ชื่อทีม, สมาชิก..." class="w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-750 focus:border-blue-500 rounded-2xl text-xs text-slate-850 outline-none transition-colors">
        </div>

        <!-- Competition Select -->
        <select name="competition_type" class="w-full px-4 py-3 bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-750 focus:border-blue-500 rounded-2xl text-xs text-slate-800 dark:text-slate-250 outline-none transition-colors">
            <option value="" class="bg-white text-slate-900 dark:bg-slate-900 dark:text-slate-200">-- ประเภทการแข่งขันทั้งหมด --</option>
            <?php if (!empty($competitions)): ?>
                <?php foreach ($competitions as $comp): ?>
                    <option value="<?= esc($comp['comp_name']) ?>" <?= $compType_active == $comp['comp_name'] ? 'selected' : '' ?> class="bg-white text-slate-900 dark:bg-slate-900 dark:text-slate-200"><?= esc($comp['comp_name']) ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <!-- Status Filter -->
        <select name="status" class="w-full px-4 py-3 bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-750 focus:border-blue-500 rounded-2xl text-xs text-slate-850 dark:text-slate-250 outline-none transition-colors">
            <option value="" class="bg-white text-slate-900 dark:bg-slate-900 dark:text-slate-200">-- กรองสถานะทั้งหมด --</option>
            <option value="pending" <?= $status_active == 'pending' ? 'selected' : '' ?> class="bg-white text-slate-900 dark:bg-slate-900 dark:text-slate-200">รอตรวจสอบ (Pending)</option>
            <option value="approved" <?= $status_active == 'approved' ? 'selected' : '' ?> class="bg-white text-slate-900 dark:bg-slate-900 dark:text-slate-200">อนุมัติสิทธิ์แล้ว (Approved)</option>
            <option value="rejected" <?= $status_active == 'rejected' ? 'selected' : '' ?> class="bg-white text-slate-900 dark:bg-slate-900 dark:text-slate-200">ปฏิเสธ/ไม่ผ่าน (Rejected)</option>
        </select>

        <!-- Submit & Clear Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="flex-1 py-3 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-100 hover:border-blue-600 text-xs font-bold rounded-2xl transition-all flex items-center justify-center gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i> กรองรายการ
            </button>
            <?php if(!empty($search) || !empty($compType_active) || !empty($status_active)): ?>
                <a href="<?= base_url('staff/science-week') ?>" class="p-3 bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 rounded-2xl transition-all flex items-center justify-center" title="ล้างฟิลเตอร์">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- List Table -->
<div class="glass-card rounded-3xl overflow-hidden bg-white dark:bg-slate-900/60 shadow-xl border border-slate-200 dark:border-slate-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">รหัส/ประเภท</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">โรงเรียน / ทีม</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">สมาชิกในทีม</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">คุณครูที่ปรึกษา</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">สถานะตรวจสอบ</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php if (empty($registrations)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium bg-white dark:bg-transparent">
                            ไม่พบข้อมูลใบสมัครประกวดหรือแข่งขันตามระบุ
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($registrations as $reg): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-black text-cyan-600 dark:text-cyan-400 block font-mono"><?= $reg['reg_code'] ?></span>
                                <span class="text-[10px] text-slate-500 block mt-1 font-bold truncate max-w-[180px]"><?= $reg['reg_competition_type'] ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200 block">
                                    <?= esc($reg['reg_school_name']) ?>
                                    <?php if (!empty($reg['reg_school_province'])): ?>
                                        <span class="text-indigo-650 dark:text-indigo-400 font-bold text-[10px]"> (<?= esc($reg['reg_school_province']) ?>)</span>
                                    <?php endif; ?>
                                </span>
                                <span class="text-[10px] text-slate-400 block font-semibold mt-0.5"><?= $reg['reg_team_name'] ? 'ทีม: '.esc($reg['reg_team_name']) : 'ทั่วไป' ?></span>
                                
                                <?php 
                                $customAnswers = [];
                                if (!empty($reg['reg_custom_fields'])) {
                                    $customAnswers = json_decode($reg['reg_custom_fields'], true) ?: [];
                                }
                                if (!empty($customAnswers)):
                                ?>
                                    <div class="mt-2 space-y-1 bg-slate-50 dark:bg-slate-800/40 p-2 rounded-xl border border-slate-100 dark:border-slate-800 text-[10px]">
                                        <?php foreach ($customAnswers as $q => $a): ?>
                                            <div class="truncate max-w-[220px]">
                                                <span class="font-bold text-slate-500 dark:text-slate-400"><?= esc($q) ?>:</span> 
                                                <?php if (empty($a)): ?>
                                                    <span class="text-slate-400 dark:text-slate-500 italic">ไม่ได้ระบุ</span>
                                                <?php elseif (strpos($a, 'uploads/science_week/') === 0): ?>
                                                    <a href="<?= base_url($a) ?>" target="_blank" class="text-indigo-650 dark:text-indigo-400 hover:underline font-bold">ดาวน์โหลดไฟล์</a>
                                                <?php elseif (filter_var($a, FILTER_VALIDATE_URL)): ?>
                                                    <a href="<?= htmlspecialchars($a) ?>" target="_blank" class="text-indigo-650 dark:text-indigo-400 hover:underline font-bold">ลิงก์แนบ</a>
                                                <?php else: ?>
                                                    <span class="text-slate-700 dark:text-slate-300 font-semibold"><?= esc($a) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div class="max-w-[200px]">
                                    <?php $members = json_decode($reg['reg_members'], true) ?: []; ?>
                                    <ul class="space-y-0.5">
                                        <?php foreach ($members as $m): ?>
                                            <li class="text-[11px] text-slate-650 dark:text-slate-300 font-medium truncate">• <?= esc($m) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div class="max-w-[200px]">
                                    <?php $advisors = json_decode($reg['reg_advisors'], true) ?: []; ?>
                                    <ul class="space-y-0.5">
                                        <?php foreach ($advisors as $a): ?>
                                            <li class="text-[11px] text-slate-650 dark:text-slate-300 font-medium truncate">• <?= esc($a) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($reg['reg_status'] === 'approved'): ?>
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/30 text-[9px] font-black uppercase tracking-widest flex items-center gap-1 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> อนุมัติสิทธิ์แล้ว
                                    </span>
                                <?php elseif ($reg['reg_status'] === 'rejected'): ?>
                                    <span class="px-2.5 py-1 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-800/30 text-[9px] font-black uppercase tracking-widest flex items-center gap-1 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> ปฏิเสธ/ไม่ผ่าน
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-800/30 text-[9px] font-black uppercase tracking-widest flex items-center gap-1 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> รอตรวจสอบ
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('staff/science-week/edit/' . $reg['reg_id']) ?>" class="p-1.5 bg-blue-50 hover:bg-blue-650 text-blue-600 hover:text-white rounded-lg border border-blue-100 dark:border-slate-800 transition-all" title="แก้ไขข้อมูลผู้สมัคร">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <button onclick="updateRegStatus(<?= $reg['reg_id'] ?>, 'approved')" class="p-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white rounded-lg border border-emerald-100 dark:border-emerald-900 transition-all" title="อนุมัติใบสมัคร">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="updateRegStatus(<?= $reg['reg_id'] ?>, 'rejected')" class="p-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-lg border border-rose-100 dark:border-rose-900 transition-all" title="ปฏิเสธการเข้าร่วม">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($registrations)): ?>
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800 flex justify-center">
            <?= $pager->links('default', 'itsupport_pager') ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function updateRegStatus(id, newStatus) {
        let actionText = newStatus === 'approved' ? 'อนุมัติผู้สมัครรายนี้?' : 'ปฏิเสธผู้สมัครรายนี้?';
        let confirmBtnColor = newStatus === 'approved' ? '#10b981' : '#ef4444';

        Swal.fire({
            title: actionText,
            text: "คุณสามารถกลับมาเปลี่ยนสถานะภายหลังได้ตลอดเวลา",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: confirmBtnColor,
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            background: getSwalColors().bg,
            color: getSwalColors().text,
            customClass: {
                popup: 'glass-card rounded-[2rem]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.showLoading();

                fetch(`<?= base_url('staff/science-week/update-status') ?>/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `status=${newStatus}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'อัปเดตสำเร็จ',
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
