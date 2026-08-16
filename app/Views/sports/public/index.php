<?= $this->extend('sports/public/layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen pb-20 pt-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Hero Section -->
        <div
            class="relative bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-800 rounded-3xl p-8 sm:p-12 text-white shadow-2xl overflow-hidden">
            <div class="absolute -right-12 -bottom-12 opacity-10 pointer-events-none">
                <i data-lucide="trophy" class="w-96 h-96"></i>
            </div>

            <div class="relative z-10 max-w-3xl space-y-4">
                <div
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs sm:text-sm font-semibold">
                    <i data-lucide="sparkles" class="w-4 h-4 text-amber-300"></i>
                    <span>ระบบลงทะเบียนการแข่งขันกีฬา อบจ.นครสวรรค์ เกมส์ ประจำปี 2569</span>
                </div>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                    เปิดรับสมัครเข้าร่วมการแข่งขันกีฬา <br class="hidden sm:inline" />
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-emerald-200">อบจ.นครสวรรค์
                        เกมส์</span>
                </h1>
                <p class="text-emerald-100 text-sm sm:text-base leading-relaxed">
                    ขอเชิญโรงเรียนและสถานศึกษาในจังหวัด ส่งทีมนักกีฬาเข้าร่วมการแข่งขันในชนิดกีฬาและรุ่นอายุต่าง ๆ
                    พร้อมระบบตรวจสอบสถานะการสมัคร และดาวน์โหลดเกียรติบัตรออนไลน์
                </p>

                <!-- Search / Track Button Group -->
                <div class="pt-4 flex flex-wrap items-center gap-3">
                    <a href="#sports-list"
                        class="px-6 py-3.5 bg-amber-400 hover:bg-amber-500 text-slate-950 rounded-2xl font-extrabold text-sm flex items-center gap-2 shadow-lg shadow-amber-400/20 transition-all hover:scale-105">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>เลือกลงทะเบียนกีฬา</span>
                    </a>
                    <a href="<?= base_url('sports/status') ?>"
                        class="px-6 py-3.5 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/30 rounded-2xl font-bold text-sm flex items-center gap-2 transition-all">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>ตรวจสอบสถานะการสมัคร</span>
                    </a>
                    <a href="<?= base_url('sports/certificate') ?>"
                        class="px-6 py-3.5 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/30 rounded-2xl font-bold text-sm flex items-center gap-2 transition-all">
                        <i data-lucide="award" class="w-4 h-4 text-amber-300"></i>
                        <span>ค้นหาเกียรติบัตร</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sports Category List -->
        <div id="sports-list" class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 flex items-center gap-2">
                        <i data-lucide="medal" class="w-6 h-6 text-emerald-600"></i>
                        <span>รายการกีฬาและรุ่นที่เปิดรับสมัคร</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        เลือกชนิดกีฬาและรุ่นอายุที่โรงเรียนต้องการส่งเข้าร่วมแข่งขัน</p>
                </div>
                <div
                    class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl self-start sm:self-auto">
                    เปิดรับสมัคร <?= count($categories) ?> รายการ
                </div>
            </div>

            <?php if (empty($categories)): ?>
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm">
                    <div
                        class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto text-slate-400 mb-4">
                        <i data-lucide="calendar-x" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">ยังไม่มีรายการกีฬาที่เปิดรับสมัครในขณะนี้</h3>
                    <p class="text-xs text-slate-400 mt-1">กรุณาติดตามข่าวสารประกาศการรับสมัครเร็ว ๆ นี้</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($categories as $cat): ?>
                        <div
                            class="bg-white rounded-2xl p-5 border border-slate-100/90 shadow-sm hover:shadow-lg hover:border-emerald-200/80 transition-all flex flex-col justify-between group">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                            <i data-lucide="trophy" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <span
                                                class="text-[11px] font-extrabold text-emerald-600 uppercase tracking-wider block leading-none"><?= esc($cat['sport_name']) ?></span>
                                            <h3
                                                class="text-sm font-black text-slate-900 group-hover:text-emerald-700 transition-colors mt-0.5 leading-snug">
                                                <?= esc($cat['category_name']) ?></h3>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-black shrink-0 <?= $cat['category_gender'] === 'female' ? 'bg-rose-50 text-rose-600 border border-rose-200' : ($cat['category_gender'] === 'mixed' ? 'bg-purple-50 text-purple-600 border border-purple-200' : 'bg-blue-50 text-blue-600 border border-blue-200') ?>">
                                        <?= $cat['category_gender'] === 'female' ? 'หญิง' : ($cat['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>
                                    </span>
                                </div>

                                <div
                                    class="py-2.5 px-3 bg-slate-50/80 rounded-xl grid grid-cols-2 gap-2 text-[11px] text-slate-600 font-medium border border-slate-100/80">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <i data-lucide="users" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                                        <span class="truncate">ผู้เล่น: <strong
                                                class="text-slate-800 font-bold"><?= $cat['min_players'] ?>-<?= $cat['max_players'] ?></strong>
                                            คน</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 truncate">
                                        <i data-lucide="user-check" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                                        <span class="truncate">โค้ช: <strong
                                                class="text-slate-800 font-bold"><?= $cat['min_coaches'] ?>-<?= $cat['max_coaches'] ?></strong>
                                            คน</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3.5 mt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                <?php
                                $isFull = ($cat['max_teams'] > 0 && $cat['registered_teams'] >= $cat['max_teams']);
                                $today = date('Y-m-d');
                                $isExpired = (!empty($cat['reg_end_date']) && $today > $cat['reg_end_date']);
                                $isNotStarted = (!empty($cat['reg_start_date']) && $today < $cat['reg_start_date']);
                                $isClosed = ($cat['status'] === 'closed');
                                $isDraft  = ($cat['status'] === 'draft');
                                ?>
                                <div class="text-[11px]">
                                    <?php if ($isClosed): ?>
                                        <span class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-200">
                                            ปิดรับสมัครแล้ว
                                        </span>
                                    <?php elseif ($isDraft): ?>
                                        <span class="text-slate-500 font-bold bg-slate-100 px-2 py-0.5 rounded-lg">
                                            แบบร่าง (ยังไม่เปิด)
                                        </span>
                                    <?php elseif ($isFull): ?>
                                        <span
                                            class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-200 flex items-center gap-1">
                                            <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                                            <span>เต็มโควตา</span>
                                        </span>
                                    <?php elseif ($isExpired): ?>
                                        <span class="text-slate-500 font-bold bg-slate-100 px-2 py-0.5 rounded-lg">
                                            ปิดรับสมัคร
                                        </span>
                                    <?php elseif ($isNotStarted): ?>
                                        <span
                                            class="text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200">
                                            เปิด <?= date('d/m', strtotime($cat['reg_start_date'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-400 font-medium">
                                            สมัคร <strong
                                                class="text-emerald-600 font-black text-xs"><?= number_format($cat['registered_teams']) ?></strong><?= $cat['max_teams'] > 0 ? '/' . $cat['max_teams'] : '' ?>
                                            ทีม
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($isClosed || $isDraft || $isFull || $isExpired): ?>
                                    <button disabled
                                        class="px-3.5 py-1.5 bg-slate-100 text-slate-400 rounded-xl font-bold text-xs cursor-not-allowed">
                                        <?= $isClosed ? 'ปิดรับแล้ว' : ($isDraft ? 'แบบร่าง' : ($isFull ? 'เต็มแล้ว' : 'ปิดรับ')) ?>
                                    </button>
                                <?php elseif ($isNotStarted): ?>
                                    <button disabled
                                        class="px-3.5 py-1.5 bg-slate-100 text-amber-600 rounded-xl font-bold text-xs cursor-not-allowed">
                                        เร็วๆ นี้
                                    </button>
                                <?php else: ?>
                                    <a href="<?= base_url('sports/register/' . $cat['category_id']) ?>"
                                        class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm shadow-emerald-200 group-hover:shadow-md transition-all cursor-pointer">
                                        <span>สมัครแข่งขัน</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?= $this->endSection() ?>