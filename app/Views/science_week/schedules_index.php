<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span>จัดการกำหนดการกิจกรรม</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">จัดการรายการความคืบหน้าของงานตามช่วงเวลากิจกรรมสัปดาห์วิทยาศาสตร์</p>
    </div>
    
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="<?= base_url('staff/science-week/schedules/create') ?>" class="w-full md:w-auto justify-center px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 shadow-lg shadow-indigo-950/20">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> เพิ่มกำหนดการกิจกรรม
        </a>
    </div>
</div>



<!-- Schedules List Table -->
<div class="glass-card rounded-3xl overflow-hidden bg-white dark:bg-slate-900/60 shadow-xl border border-slate-200 dark:border-slate-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-[200px]">ช่วงเวลา/วันที่</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">หัวข้อกำหนดการ</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">สีสัญลักษณ์</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">รายละเอียด</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[120px]">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php if (empty($schedules)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium bg-white dark:bg-transparent">
                            ยังไม่มีข้อมูลกำหนดการกิจกรรมในระบบ คลิกปุ่ม "เพิ่มกำหนดการกิจกรรม" เพื่อสร้างรายการใหม่
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($schedules as $sch): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-xs font-extrabold text-cyan-500 dark:text-cyan-400 block font-mono"><?= esc($sch['sch_date']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-extrabold text-slate-805 dark:text-slate-200 block"><?= esc($sch['sch_title']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-<?= esc($sch['sch_color'] ?: 'cyan') ?>-950/40 text-<?= esc($sch['sch_color'] ?: 'cyan') ?>-400 border border-<?= esc($sch['sch_color'] ?: 'cyan') ?>-800/30 text-[10px] font-black uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full bg-<?= esc($sch['sch_color'] ?: 'cyan') ?>-500 animate-pulse"></span>
                                    <?= esc($sch['sch_color']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed max-w-[300px]">
                                    <?= esc($sch['sch_description'] ?: '-') ?>
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('staff/science-week/schedules/edit/' . $sch['sch_id']) ?>" class="p-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-lg border border-blue-100 dark:border-slate-800 transition-all" title="แก้ไข">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <button onclick="deleteSchedule(<?= $sch['sch_id'] ?>, '<?= esc($sch['sch_title']) ?>')" class="p-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-lg border border-rose-100 dark:border-slate-800 transition-all" title="ลบ">
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
    function deleteSchedule(id, title) {
        Swal.fire({
            title: 'ยืนยันการลบกำหนดการกิจกรรม?',
            text: `คุณกำลังจะลบรายการ "${title}" การดำเนินการนี้ไม่สามารถย้อนกลับได้!`,
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

                fetch(`<?= base_url('staff/science-week/schedules/delete') ?>/${id}`, {
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
