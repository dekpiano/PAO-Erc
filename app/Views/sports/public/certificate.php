<?= $this->extend('sports/public/layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen pb-20 pt-4">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Header Card -->
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-emerald-900/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <a href="<?= base_url('sports') ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-100 hover:text-white transition-colors mb-1">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>กลับหน้ารายการกีฬา</span>
                </a>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-400 text-slate-950 rounded-full text-xs font-black">
                    <i data-lucide="award" class="w-3.5 h-3.5"></i>
                    <span>E-Certificate Online</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">ค้นหาและดาวน์โหลดเกียรติบัตร</h1>
                <p class="text-emerald-100 text-xs sm:text-sm">ค้นหาเกียรติบัตรของนักกีฬาและผู้ฝึกสอน โดยระบุชื่อ-นามสกุล, เลขบัตร ปชช. หรือชื่อโรงเรียน</p>
            </div>
        </div>

        <!-- Search Box -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-4">
            <form action="<?= base_url('sports/certificate/search') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">ระบุคำค้นหา (ชื่อ-นามสกุล / เลขบัตร ปชช. / ชื่อโรงเรียน)</label>
                    <div class="relative">
                        <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" required
                               placeholder="เช่น นายสมชาย, 16099xxxx, โรงเรียนสวนกุหลาบ..." 
                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-bold text-sm bg-slate-50 focus:bg-white transition-all">
                        <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-4 top-3.5"></i>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-extrabold text-sm flex items-center justify-center gap-2 shadow-lg shadow-emerald-200 transition-all cursor-pointer">
                        <i data-lucide="search" class="w-4 h-4 text-amber-300"></i>
                        <span>ค้นหาเกียรติบัตร</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        <?php if ($results !== null): ?>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <i data-lucide="award" class="w-5 h-5 text-emerald-600"></i>
                        <span>ผลการค้นหา (พบ <?= count($results) ?> รายการ)</span>
                    </h2>
                </div>

                <?php if (empty($results)): ?>
                    <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm space-y-3">
                        <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto text-amber-600">
                            <i data-lucide="inbox" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-bold text-slate-700 text-base">ไม่พบข้อมูลเกียรติบัตร</h3>
                        <p class="text-xs text-slate-400">กรุณาตรวจสอบความถูกต้องของชื่อ-นามสกุล หรือเลขบัตรประชาชน</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($results as $m): ?>
                            <?php 
                                $finalAward = $m['award_level'] !== 'none' ? $m['award_level'] : $m['team_award'];
                                $genderText = $m['category_gender'] === 'female' ? 'หญิง' : ($m['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย');
                            ?>
                            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:border-emerald-200 transition-all flex flex-col justify-between space-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black <?= $m['member_type'] === 'athlete' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' ?>">
                                            <?= !empty($m['position']) ? esc($m['position']) : ($m['member_type'] === 'athlete' ? 'นักกีฬา' : 'ผู้ฝึกสอน / จนท.') ?>
                                        </span>
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full text-[10px] font-black">
                                            <?= $finalAward === 'champion' ? '🏆 ชนะเลิศ' : ($finalAward === 'runner_up_1' ? '🥈 รองชนะเลิศอันดับ 1' : ($finalAward === 'runner_up_2' ? '🥉 รองชนะเลิศอันดับ 2' : ($finalAward === 'runner_up_3' ? '🎖️ รองชนะเลิศอันดับ 3' : '📜 เข้าร่วมการแข่งขัน'))) ?>
                                        </span>
                                    </div>

                                    <div>
                                        <h3 class="text-base font-black text-slate-900">
                                            <?= esc($m['prefix']) ?><?= esc($m['first_name']) ?> <?= esc($m['last_name']) ?>
                                        </h3>
                                        <p class="text-xs font-bold text-slate-600 mt-0.5"><?= esc($m['school_name']) ?></p>
                                    </div>

                                    <div class="bg-slate-50/90 p-3.5 rounded-2xl text-xs space-y-1.5 border border-slate-100">
                                        <div class="flex items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-emerald-100 text-emerald-950 font-black text-[11px]">
                                                <i data-lucide="trophy" class="w-3 h-3 text-emerald-700"></i>
                                                <span>กีฬา: <?= esc($m['sport_name']) ?></span>
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-slate-600 font-bold">
                                            รุ่น: <strong class="text-slate-800"><?= (mb_strpos(trim($m['category_name']), 'รุ่น') === 0 ? mb_substr(trim($m['category_name']), 4) : esc($m['category_name'])) ?> (<?= $genderText ?>)</strong>
                                        </div>
                                        <div class="text-[11px] text-slate-500">
                                            สังกัด: <span class="text-slate-700"><?= esc($m['team_name'] ?: $m['school_name']) ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-100">
                                    <a href="<?= base_url('sports/certificate/download/' . $m['member_id']) ?>" target="_blank" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 shadow-md shadow-emerald-200 transition-all">
                                        <i data-lucide="download" class="w-4 h-4 text-amber-300"></i>
                                        <span>ดาวน์โหลดเกียรติบัตร (PDF/PNG)</span>
                                    </a>
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
