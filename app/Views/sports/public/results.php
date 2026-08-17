<?= $this->extend('sports/public/layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen pb-20 pt-4">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Hero Header -->
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-emerald-900/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <a href="<?= base_url('sports') ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-100 hover:text-white transition-colors mb-1">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>กลับหน้ารายการกีฬา</span>
                </a>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-400 text-slate-950 rounded-full text-xs font-black">
                    <i data-lucide="trophy" class="w-3.5 h-3.5"></i>
                    <span>Competition Results & Standings</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">ประกาศผลการแข่งขันและทำเนียบรางวัล (<?= esc($activeCompYear ?? '') ?>)</h1>
                <p class="text-emerald-100 text-xs sm:text-sm">ค้นหาและดูผลการแข่งขันกีฬา อบจ.นครสวรรค์ เกมส์ ประจำปี <?= esc($activeCompYear ?? '') ?> ชิงชนะเลิศ แยกตามชนิดกีฬาและรุ่นอายุ</p>
            </div>
            <?php if (!empty($availableYears) && count($availableYears) > 1): ?>
                <div class="flex items-center gap-2 bg-white/20 backdrop-blur-md px-3.5 py-2 rounded-2xl border border-white/30 self-start md:self-auto">
                    <span class="text-xs font-bold text-emerald-100">ปีการแข่งขัน:</span>
                    <select onchange="window.location.href='<?= base_url('sports/results?year=') ?>' + this.value" class="bg-transparent text-xs font-black text-white outline-none cursor-pointer">
                        <?php foreach ($availableYears as $yr): ?>
                            <option value="<?= $yr ?>" class="text-slate-900" <?= $yr == $activeCompYear ? 'selected' : '' ?>>
                                ปี <?= $yr ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
        </div>

        <!-- Search / Category Selector Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-4">
            <form method="GET" action="<?= base_url('sports/results') ?>" class="space-y-4">
                <input type="hidden" name="year" value="<?= esc($activeCompYear ?? 2569) ?>">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        <i data-lucide="filter" class="w-3.5 h-3.5 inline mr-1 text-emerald-600"></i>
                        เลือกชนิดกีฬา และ รุ่นการแข่งขันที่ต้องการค้นหา <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="category_id" required class="w-full pl-4 pr-10 py-3.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-bold text-sm bg-slate-50 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">-- กรุณาเลือกชนิดกีฬาและรุ่นการแข่งขัน (ปี <?= esc($activeCompYear ?? '') ?>) --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>" <?= $categoryId == $cat['category_id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['sport_name']) ?> - <?= (mb_strpos(trim($cat['category_name']), 'รุ่น') === 0 ? '' : 'รุ่น ') . esc($cat['category_name']) ?> (<?= $cat['category_gender'] === 'female' ? 'หญิง' : ($cat['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 absolute right-4 top-3.5 pointer-events-none"></i>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-extrabold text-sm flex items-center justify-center gap-2 shadow-lg shadow-emerald-200 transition-all cursor-pointer">
                        <i data-lucide="search" class="w-4 h-4 text-amber-300"></i>
                        <span>ค้นหาผลการแข่งขัน</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Initial Placeholder State (Before Searching) -->
        <?php if ($teams === null): ?>
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm space-y-3">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto">
                    <i data-lucide="trophy" class="w-8 h-8"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-base">กรุณาเลือกชนิดกีฬาและกดปุ่ม "ค้นหาผลการแข่งขัน"</h3>
                <p class="text-xs text-slate-400 max-w-md mx-auto">เลือกรุ่นการแข่งขันที่ต้องการตรวจสอบจากรายการด้านบน เพื่อดูทำเนียบรางวัล ทีมชนะเลิศ และรายชื่อนักกีฬา</p>
            </div>
        <?php else: ?>
            <!-- Category Title Ribbon -->
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                        <i data-lucide="medal" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-950 border border-emerald-200 text-xs font-black tracking-wide mb-1">
                            <i data-lucide="trophy" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>กีฬา: <?= esc($selectedCategory['sport_name']) ?></span>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 leading-tight">
                            <?= (mb_strpos(trim($selectedCategory['category_name']), 'รุ่น') === 0 ? '' : 'รุ่น ') . esc($selectedCategory['category_name']) ?> (<?= $selectedCategory['category_gender'] === 'female' ? 'หญิง' : ($selectedCategory['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>)
                        </h2>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?= base_url('sports/certificate') ?>" class="px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-colors">
                        <i data-lucide="award" class="w-3.5 h-3.5"></i>
                        <span>ค้นหาเกียรติบัตรของรุ่นนี้</span>
                    </a>
                </div>
            </div>

            <!-- Awards Listing -->
            <?php if (empty($teams)): ?>
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm space-y-3">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto text-amber-500">
                        <i data-lucide="hourglass" class="w-7 h-7"></i>
                    </div>
                    <h3 class="font-bold text-slate-700 text-base">อยู่ระหว่างการแข่งขัน / ยังไม่มีการบันทึกผลรางวัล</h3>
                    <p class="text-xs text-slate-400">คณะกรรมการกำลังดำเนินการแข่งขัน และจะประกาศผลรางวัลอย่างเป็นทางการหลังสิ้นสุดการแข่งขัน</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 gap-4">
                    <?php foreach ($teams as $t): ?>
                        <?php 
                            $awardBadge = [
                                'champion'      => ['bg' => 'bg-gradient-to-r from-amber-400 to-yellow-500', 'text' => 'text-slate-950', 'icon' => 'trophy', 'label' => 'รางวัลชนะเลิศ (Champion)'],
                                'runner_up_1'   => ['bg' => 'bg-gradient-to-r from-slate-200 to-slate-300', 'text' => 'text-slate-900', 'icon' => 'medal', 'label' => 'รางวัลรองชนะเลิศอันดับ 1'],
                                'runner_up_2'   => ['bg' => 'bg-gradient-to-r from-amber-700 to-amber-800', 'text' => 'text-white', 'icon' => 'medal', 'label' => 'รางวัลรองชนะเลิศอันดับ 2'],
                                'runner_up_3'   => ['bg' => 'bg-gradient-to-r from-emerald-600 to-teal-700', 'text' => 'text-white', 'icon' => 'award', 'label' => 'รางวัลรองชนะเลิศอันดับ 3'],
                                'participation' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'icon' => 'check-circle-2', 'label' => 'เข้าร่วมการแข่งขัน']
                            ][$t['award_level']] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'icon' => 'award', 'label' => 'ผู้เข้าร่วมการแข่งขัน'];
                        ?>
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:border-emerald-200 transition-all space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 rounded-xl text-xs font-black flex items-center gap-1.5 shadow-sm <?= $awardBadge['bg'] ?> <?= $awardBadge['text'] ?>">
                                            <i data-lucide="<?= $awardBadge['icon'] ?>" class="w-3.5 h-3.5"></i>
                                            <span><?= $awardBadge['label'] ?></span>
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-black text-slate-900 mt-2">
                                        <?= esc($t['school_name']) ?>
                                        <?php if ($t['team_name'] && $t['team_name'] !== $t['school_name']): ?>
                                            <span class="text-sm font-bold text-slate-500">(<?= esc($t['team_name']) ?>)</span>
                                        <?php endif; ?>
                                    </h3>
                                    <p class="text-xs text-slate-400">
                                        รหัสทีม: <strong class="font-mono text-slate-700"><?= esc($t['team_code']) ?></strong> | ผู้ประสานงาน: <?= esc($t['contact_name']) ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Team Members List / Accordion -->
                            <div>
                                <details class="group">
                                    <summary class="flex items-center justify-between text-xs font-bold text-emerald-700 hover:text-emerald-800 cursor-pointer select-none py-1">
                                        <span class="flex items-center gap-1.5">
                                            <i data-lucide="users" class="w-4 h-4"></i>
                                            <span>ดูรายชื่อนักกีฬาและผู้ฝึกสอน (<?= count($t['members']) ?> คน)</span>
                                        </span>
                                        <span class="text-[11px] text-slate-400 group-open:rotate-180 transition-transform">
                                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                        </span>
                                    </summary>

                                    <div class="pt-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                                        <?php foreach ($t['members'] as $m): ?>
                                            <div class="p-3 bg-slate-50/70 border border-slate-100 rounded-2xl flex items-center justify-between gap-2">
                                                <div class="space-y-0.5">
                                                    <div class="text-xs font-bold text-slate-800">
                                                        <?= esc($m['prefix']) ?><?= esc($m['first_name']) ?> <?= esc($m['last_name']) ?>
                                                    </div>
                                                    <div class="text-[10px] text-slate-400">
                                                        <?= $m['member_type'] === 'athlete' ? 'นักกีฬา' : 'ผู้ฝึกสอน' ?> <?= $m['jersey_number'] ? '• ชั้น ' . esc($m['jersey_number']) : '' ?>
                                                    </div>
                                                </div>
                                                <a href="<?= base_url('sports/certificate/download/' . $m['member_id']) ?>" target="_blank" class="p-2 bg-white hover:bg-emerald-50 text-emerald-700 border border-slate-200 hover:border-emerald-300 rounded-xl transition-colors shadow-sm" title="ดาวน์โหลดเกียรติบัตร">
                                                    <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </details>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>
<?= $this->endSection() ?>
