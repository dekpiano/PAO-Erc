<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span>จัดการข้อมูลแบบประเมิน</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">จัดการและตรวจสอบรายชื่อผู้ทำแบบประเมินความพึงพอใจและผลคะแนนประเมิน</p>
    </div>
    
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="<?= base_url('staff/science-week/evaluations/create') ?>" class="w-full md:w-auto justify-center px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 shadow-lg shadow-indigo-950/20">
            <i data-lucide="settings" class="w-4 h-4 text-cyan-300"></i> ตั้งค่าโครงสร้างฟอร์มประเมิน
        </a>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="glass-card rounded-3xl p-4 sm:p-6 mb-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
    <form action="<?= base_url('staff/science-week/evaluations') ?>" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="ค้นหาตามชื่อ, โรงเรียน, จังหวัด, เบอร์โทร หรือรหัสแบบประเมิน..." class="w-full pl-12 pr-4 py-3 bg-slate-950/50 border border-slate-800 rounded-2xl text-xs sm:text-sm text-slate-200 focus:outline-none focus:border-indigo-500 transition-colors">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-750 text-white font-bold rounded-2xl text-xs sm:text-sm transition-colors flex items-center gap-2">
                ค้นหา
            </button>
            <?php if (!empty($search)): ?>
                <a href="<?= base_url('staff/science-week/evaluations') ?>" class="px-6 py-3 bg-slate-850 hover:bg-slate-800 text-slate-350 font-bold rounded-2xl text-xs sm:text-sm transition-colors flex items-center justify-center">
                    ล้างตัวกรอง
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Evaluations Table -->
<div class="glass-card rounded-3xl overflow-hidden bg-white dark:bg-slate-900/60 shadow-xl border border-slate-200 dark:border-slate-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-[140px]">รหัสประเมิน</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">ข้อมูลผู้ประเมิน</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">สังกัด / จังหวัด</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[120px]">คะแนนเฉลี่ย</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-[200px]">ข้อเสนอแนะ</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[160px]">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php if (empty($evaluations)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium bg-white dark:bg-transparent">
                            ไม่พบข้อมูลแบบประเมินในระบบ
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($evaluations as $eval): 
                        $feedback = json_decode($eval['eval_feedback'], true) ?: [];
                        $ratings = $feedback['ratings'] ?? [];
                        $comments = $feedback['comments'] ?? '-';
                        $sum = array_sum($ratings);
                        $count = count($ratings);
                        $avg = $count > 0 ? number_format($sum / $count, 2) : '-';
                    ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-xs font-extrabold text-cyan-500 dark:text-cyan-400 block font-mono"><?= esc($eval['eval_code']) ?></span>
                                <span class="text-[9px] text-slate-500 block mt-1"><?= date('d/m/Y H:i', strtotime($eval['eval_created_at'])) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-xs text-slate-800 dark:text-slate-200"><?= esc($eval['eval_name']) ?></div>
                                <div class="text-[10px] text-slate-500 mt-1 font-mono">โทร: <?= esc($eval['eval_phone']) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-700 dark:text-slate-300"><?= esc($eval['eval_school'] ?: '-') ?></div>
                                <div class="text-[10px] text-slate-500 mt-1"><?= esc($eval['eval_province'] ?: '-') ?></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-indigo-950/40 text-indigo-400 border border-indigo-900/30">
                                    <i data-lucide="star" class="w-3.5 h-3.5 fill-indigo-400 text-indigo-400"></i>
                                    <?= $avg ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed max-w-[200px]" title="<?= esc($comments) ?>">
                                    <?= esc($comments) ?>
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Print/View Certificate -->
                                    <a href="<?= base_url('science-week/certificate/view-all/evaluation/' . $eval['eval_code']) ?>" target="_blank" class="p-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white rounded-lg border border-emerald-100 dark:border-slate-800 transition-all" title="ดูเกียรติบัตร">
                                        <i data-lucide="award" class="w-4 h-4"></i>
                                    </a>
                                    <!-- Edit -->
                                    <a href="<?= base_url('staff/science-week/evaluations/edit/' . $eval['eval_id']) ?>" class="p-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-lg border border-blue-100 dark:border-slate-800 transition-all" title="แก้ไข">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <!-- Delete -->
                                    <button onclick="deleteEvaluation(<?= $eval['eval_id'] ?>, '<?= esc($eval['eval_name']) ?>')" class="p-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-lg border border-rose-100 dark:border-slate-800 transition-all" title="ลบ">
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
    
    <!-- Pagination -->
    <?php if (!empty($pager)): ?>
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="text-[10px] sm:text-xs text-slate-500 font-medium">
                แสดงผลแบบประเมิน
            </div>
            <div class="custom-pagination">
                <?= $pager->links('default', 'default_full') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function deleteEvaluation(id, name) {
        Swal.fire({
            title: 'ยืนยันการลบข้อมูลแบบประเมิน?',
            text: `คุณกำลังจะลบรายการแบบประเมินของ "${name}" การดำเนินการนี้ไม่สามารถย้อนกลับได้!`,
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

                fetch(`<?= base_url('staff/science-week/evaluations/delete') ?>/${id}`, {
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
