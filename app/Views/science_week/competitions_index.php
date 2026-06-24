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
    
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="<?= base_url('staff/science-week/competitions/create') ?>" class="w-full md:w-auto justify-center px-5 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 shadow-lg shadow-indigo-950/20">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> เพิ่มประเภทการแข่งขัน
        </a>
    </div>
</div>



<!-- Competitions List Table -->
<div class="glass-card rounded-3xl overflow-hidden bg-white dark:bg-slate-900/60 shadow-xl border border-slate-200 dark:border-slate-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-[80px]">ไอคอน</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">ชื่อประเภทการแข่งขัน</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">ระดับชั้น</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">โควตา</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">สมาชิก/ทีม</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">ธีมสี</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">คำอธิบายย่อ</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[120px]">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php if (empty($competitions)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-medium bg-white dark:bg-transparent">
                            ยังไม่มีข้อมูลประเภทการแข่งขันในระบบ คลิกปุ่ม "เพิ่มประเภทการแข่งขัน" เพื่อสร้างรายการใหม่
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($competitions as $comp): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-10 h-10 rounded-xl bg-<?= esc($comp['comp_color'] ?: 'cyan') ?>-500/10 border border-<?= esc($comp['comp_color'] ?: 'cyan') ?>-500/30 flex items-center justify-center text-<?= esc($comp['comp_color'] ?: 'cyan') ?>-400">
                                    <i data-lucide="<?= esc($comp['comp_icon'] ?: 'award') ?>" class="w-5 h-5"></i>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200 block"><?= esc($comp['comp_name']) ?></span>
                                <?php if (!empty($comp['comp_rule_file'])): ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 dark:text-emerald-400 font-bold mt-1">
                                        <i data-lucide="file-check" class="w-3.5 h-3.5"></i> มีไฟล์กติกา
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($comp['comp_rule_link'])): ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] text-sky-600 dark:text-sky-400 font-bold mt-1 <?= !empty($comp['comp_rule_file']) ? 'ml-3' : '' ?>">
                                        <i data-lucide="link" class="w-3.5 h-3.5"></i> มีลิงก์กติกา
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-350"><?= esc($comp['comp_level']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-350">
                                    <?= !empty($comp['comp_limit']) ? esc($comp['comp_limit']) . ' ทีม' : 'ไม่จำกัด' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-350">
                                    <?= !empty($comp['comp_member_limit']) ? esc($comp['comp_member_limit']) . ' คน' : 'ไม่จำกัด' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-<?= esc($comp['comp_color'] ?: 'cyan') ?>-950/40 text-<?= esc($comp['comp_color'] ?: 'cyan') ?>-400 border border-<?= esc($comp['comp_color'] ?: 'cyan') ?>-800/30 text-[10px] font-black uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full bg-<?= esc($comp['comp_color'] ?: 'cyan') ?>-500"></span>
                                    <?= esc($comp['comp_color']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed max-w-[300px]">
                                    <?= esc($comp['comp_description'] ?: '-') ?>
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('staff/science-week/competitions/edit/' . $comp['comp_id']) ?>" class="p-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-lg border border-blue-100 dark:border-slate-800 transition-all" title="แก้ไข">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <button onclick="deleteCompetition(<?= $comp['comp_id'] ?>, '<?= esc($comp['comp_name']) ?>')" class="p-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-lg border border-rose-100 dark:border-slate-800 transition-all" title="ลบ">
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

                fetch(`<?= base_url('staff/science-week/competitions/delete') ?>/${id}`, {
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
