<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span>จัดการประเภทการแข่งขัน</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">จัดการรายการหัวข้อประกวดและแข่งขันวันวิทยาศาสตร์ เช่น เพิ่มหัวข้อใหม่ หรือลบหัวข้อเก่าออก</p>
    </div>
    
    <?php $layoutIsAdmin = $is_admin ?? false; ?>
    <?php if ($layoutIsAdmin): ?>
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="<?= base_url('science-week/staff/competitions/create') ?>" class="w-full md:w-auto justify-center px-5 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 shadow-lg shadow-indigo-950/20">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> เพิ่มประเภทการแข่งขัน
        </a>
    </div>
    <?php endif; ?>
</div>



<!-- Competitions List Table -->
<div class="glass-card rounded-3xl overflow-hidden bg-white dark:bg-slate-900/60 shadow-xl border border-slate-200 dark:border-slate-800">
    <div class="w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800">
                    <th class="px-4 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">ชื่อประเภทการแข่งขัน / รายละเอียด</th>
                    <th class="px-4 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 w-[120px]">ระดับชั้น</th>
                    <th class="px-4 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[90px]">สมาชิก/ทีม</th>
                    <th class="px-4 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[110px]">สถานะรับสมัคร</th>
                    <th class="px-4 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">คำอธิบายย่อ</th>
                    <th class="px-4 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[100px]">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php if (empty($competitions)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-slate-400 font-medium bg-white dark:bg-transparent">
                            ยังไม่มีข้อมูลประเภทการแข่งขันในระบบ คลิกปุ่ม "เพิ่มประเภทการแข่งขัน" เพื่อสร้างรายการใหม่
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($competitions as $comp): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-<?= esc($comp['comp_color'] ?: 'cyan') ?>-500/10 border border-<?= esc($comp['comp_color'] ?: 'cyan') ?>-500/30 flex items-center justify-center text-<?= esc($comp['comp_color'] ?: 'cyan') ?>-400 shrink-0">
                                        <i data-lucide="<?= esc($comp['comp_icon'] ?: 'award') ?>" class="w-4.5 h-4.5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200 block truncate"><?= esc($comp['comp_name']) ?></span>
                                        <div class="flex flex-wrap items-center gap-2 mt-1">
                                            <?php if (!empty($comp['comp_rule_file'])): ?>
                                                <span class="inline-flex items-center gap-0.5 text-[9px] text-emerald-600 dark:text-emerald-400 font-bold">
                                                    <i data-lucide="file-check" class="w-3 h-3"></i> กติกา (ไฟล์)
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($comp['comp_rule_link'])): ?>
                                                <span class="inline-flex items-center gap-0.5 text-[9px] text-sky-600 dark:text-sky-400 font-bold">
                                                    <i data-lucide="link" class="w-3 h-3"></i> กติกา (ลิงก์)
                                                </span>
                                            <?php endif; ?>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black bg-<?= esc($comp['comp_color'] ?: 'cyan') ?>-500/10 text-<?= esc($comp['comp_color'] ?: 'cyan') ?>-450 border border-<?= esc($comp['comp_color'] ?: 'cyan') ?>-500/20 uppercase">
                                                ธีม: <?= esc($comp['comp_color']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex flex-col gap-1 items-center">
                                    <?php 
                                    $levels = explode(',', $comp['comp_level']);
                                    foreach ($levels as $lvl): 
                                        $lvl = trim($lvl);
                                        if (empty($lvl)) continue;
                                    ?>
                                        <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 rounded-lg inline-block leading-tight text-center whitespace-nowrap">
                                            <?= esc($lvl) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-350">
                                    <?= !empty($comp['comp_member_limit']) ? esc($comp['comp_member_limit']) . ' คน' : 'ไม่จำกัด' ?>
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                <?php 
                                $now = date('Y-m-d H:i:s');
                                $isOpen = true;
                                if (!empty($comp['comp_open_time']) && $now < $comp['comp_open_time']) {
                                    $isOpen = false;
                                }
                                if (!empty($comp['comp_close_time']) && $now > $comp['comp_close_time']) {
                                    $isOpen = false;
                                }
                                if (($comp['comp_status'] ?? 'open') === 'closed') {
                                    $isOpen = false;
                                }
                                ?>
                                <?php if ($isOpen): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-[9px] font-bold">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                        เปิดรับสมัคร
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-500/10 text-rose-455 border border-rose-500/20 text-[9px] font-bold">
                                        <span class="w-1 h-1 rounded-full bg-rose-500"></span>
                                        ปิดรับสมัคร
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-[11px] text-slate-400 line-clamp-1 leading-relaxed max-w-[220px]" title="<?= esc($comp['comp_description']) ?>">
                                    <?= esc($comp['comp_description'] ?: '-') ?>
                                </p>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center text-xs">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('science-week/staff/competitions/edit/' . $comp['comp_id']) ?>" class="p-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-lg border border-blue-100 dark:border-slate-800 transition-all" title="แก้ไข">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <?php if ($layoutIsAdmin): ?>
                                    <button onclick="deleteCompetition(<?= $comp['comp_id'] ?>, '<?= esc($comp['comp_name']) ?>')" class="p-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-lg border border-rose-100 dark:border-slate-800 transition-all" title="ลบ">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function deleteCompetition(id, name) {
        Swal.fire({
            title: 'ยืนยันการลบประเภทการแข่งขัน?',
            text: `คุณกำลังจะลบรายการ "${name}" การดำเนินการนี้ไม่สามารถย้อนกลับได้!`,
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

                fetch(`<?= base_url('science-week/staff/competitions/delete') ?>/${id}`, {
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
