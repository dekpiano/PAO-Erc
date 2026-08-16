<?= $this->extend('sports/public/layout/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-20 pt-4">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Search Header Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
            <div class="text-center max-w-xl mx-auto space-y-2">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-2">
                    <i data-lucide="search" class="w-6 h-6"></i>
                </div>
                <h1 class="text-2xl font-black text-slate-900">ตรวจสอบสถานะการสมัครแข่งขัน</h1>
                <p class="text-xs sm:text-sm text-slate-400">ค้นหาด้วยชื่อโรงเรียน, รหัสทีม (Team Code), หรือเลขประจำตัวประชาชนนักกีฬา</p>
            </div>

            <!-- Search Form -->
            <form action="<?= base_url('sports/status/search') ?>" method="POST" class="max-w-xl mx-auto flex flex-col sm:flex-row gap-2">
                <?= csrf_field() ?>
                <div class="relative flex-1">
                    <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" required placeholder="พิมพ์ชื่อโรงเรียน / รหัสทีม / เลขบัตร ปชช..." class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-medium bg-slate-50 focus:bg-white transition-all">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-4 pointer-events-none"></i>
                </div>
                <button type="submit" class="px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-200 transition-all cursor-pointer">
                    <span>ค้นหาข้อมูล</span>
                </button>
            </form>
        </div>

        <!-- Search Results Area -->
        <?php if ($results !== null): ?>
            <div class="space-y-4">
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i data-lucide="list-checks" class="w-5 h-5 text-emerald-600"></i>
                    <span>ผลการค้นหาสำหรับ "<?= esc($keyword) ?>" (พบ <?= count($results) ?> รายการ)</span>
                </h2>

                <?php if (empty($results)): ?>
                    <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm space-y-2">
                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto text-slate-400">
                            <i data-lucide="inbox" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-bold text-slate-700 text-sm">ไม่พบข้อมูลการลงทะเบียน</h3>
                        <p class="text-xs text-slate-400">กรุณาตรวจสอบความถูกต้องของคำค้นหา หรือติดต่อฝ่ายประสานงานการแข่งขัน</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-4">
                        <?php foreach ($results as $r): ?>
                            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-xs bg-slate-900 text-white px-3 py-1 rounded-xl">
                                            <?= esc($r['team_code']) ?>
                                        </span>
                                        <span class="text-xs font-bold px-3 py-1 bg-indigo-50 text-indigo-700 rounded-xl">
                                            <?= esc($r['sport_name']) ?> (<?= esc($r['category_name']) ?>)
                                        </span>
                                    </div>
                                    <h3 class="font-black text-slate-900 text-base"><?= esc($r['school_name']) ?></h3>
                                    <p class="text-xs text-slate-400">
                                        ผู้ประสานงาน: <strong class="text-slate-700"><?= esc($r['contact_name']) ?></strong> | นักกีฬา/เจ้าหน้าที่ในทีม: <?= $r['total_members'] ?> คน
                                    </p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <?php if ($r['status'] === 'approved'): ?>
                                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1.5">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                            <span>อนุมัติสิทธิ์แข่งขันแล้ว</span>
                                        </span>
                                    <?php elseif ($r['status'] === 'rejected'): ?>
                                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1.5">
                                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                                            <span>ไม่อนุมัติ</span>
                                        </span>
                                    <?php elseif ($r['status'] === 'cancelled'): ?>
                                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500">
                                            สละสิทธิ์
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1.5">
                                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                            <span>รอเจ้าหน้าที่ตรวจสอบ</span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>
<?= $this->endSection() ?>
