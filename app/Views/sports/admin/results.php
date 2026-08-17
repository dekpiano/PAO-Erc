<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<?php $activeCompYear = isset($activeYear) ? (int)$activeYear : (int)(session()->get('sports_active_year') ?: 2569); ?>
<?= view('sports/admin/layout/nav', ['activeYear' => $activeCompYear]) ?>

<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-black uppercase flex items-center gap-1.5">
                    <i data-lucide="trophy" class="w-3.5 h-3.5"></i>
                    <span>บันทึกผล & มอบรางวัล</span>
                </span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">บันทึกผลการแข่งขันและรางวัล</h1>
            <p class="text-xs sm:text-sm text-slate-400">เลือกรุ่นการแข่งขันเพื่อบันทึกผลการแข่งขัน (ชนะเลิศ, รองชนะเลิศ, เข้าร่วม) เพื่อนำไปออกเกียรติบัตร</p>
        </div>

        <!-- Category Selector Filter -->
        <form method="GET" action="<?= base_url('staff/sports/results') ?>" class="flex items-center gap-3">
            <input type="hidden" name="year" value="<?= $activeCompYear ?>">
            <div class="relative min-w-[280px]">
                <select name="category_id" onchange="this.form.submit()" class="w-full pl-4 pr-10 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-bold text-xs bg-slate-50 focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- กรุณาเลือกชนิดกีฬาและรุ่นการแข่งขัน --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= $categoryId == $cat['category_id'] ? 'selected' : '' ?>>
                            <?= esc($cat['sport_name']) ?> - <?= (mb_strpos(trim($cat['category_name']), 'รุ่น') === 0 ? '' : 'รุ่น ') . esc($cat['category_name']) ?> (<?= $cat['category_gender'] === 'female' ? 'หญิง' : ($cat['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3.5 top-3.5 pointer-events-none"></i>
            </div>
        </form>
    </div>

    <?php if (!$selectedCategory): ?>
        <div class="bg-white rounded-3xl p-8 sm:p-12 text-center border border-slate-100 shadow-sm space-y-6">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto shadow-inner">
                <i data-lucide="trophy" class="w-8 h-8"></i>
            </div>
            <div class="max-w-md mx-auto space-y-1">
                <h3 class="text-xl font-black text-slate-900">กรุณาเลือกชนิดกีฬาและรุ่นการแข่งขัน</h3>
                <p class="text-xs text-slate-400">เลือกชนิดกีฬาจากเมนูด้านบน หรือคลิกเลือกรุ่นการแข่งขันด้านล่างนี้เพื่อเริ่มต้นบันทึกผลและมอบรางวัล</p>
            </div>

            <?php if (!empty($categories)): ?>
                <div class="text-left border-t border-slate-100 pt-6 mt-6">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">คลิกเลือกรุ่นการแข่งขันเพื่อบันทึกผล (ปี <?= esc($activeCompYear) ?>):</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        <?php foreach ($categories as $cat): ?>
                            <a href="<?= base_url('staff/sports/results?year=' . $activeCompYear . '&category_id=' . $cat['category_id']) ?>" 
                               class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-emerald-50/60 hover:border-emerald-300 transition-all flex items-center justify-between gap-2 group cursor-pointer shadow-2xs">
                                <div class="space-y-1 min-w-0">
                                    <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-emerald-700 text-white text-[11px] font-black">
                                        <span><?= esc($cat['sport_name']) ?></span>
                                    </div>
                                    <div class="font-bold text-slate-900 group-hover:text-emerald-700 text-xs truncate">
                                        <?= (mb_strpos(trim($cat['category_name']), 'รุ่น') === 0 ? '' : 'รุ่น ') . esc($cat['category_name']) ?>
                                    </div>
                                </div>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 shrink-0 group-hover:translate-x-0.5 transition-transform"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Category Summary Bar -->
        <div class="bg-gradient-to-r from-emerald-800 via-teal-900 to-slate-900 text-white rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-lg shadow-emerald-950/10">
            <div class="space-y-1">
                <span class="text-xs font-bold text-amber-300 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="trophy" class="w-3.5 h-3.5 text-amber-400"></i>
                <h2 class="text-xl font-black flex items-center flex-wrap gap-2 pt-1">
                    <span class="px-3 py-1 bg-amber-400 text-slate-950 rounded-xl text-sm font-black flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="trophy" class="w-4 h-4 text-emerald-900"></i>
                        <span>กีฬา: <?= esc($selectedCategory['sport_name']) ?></span>
                    </span>
                    <span class="text-white font-black text-base"><?= (mb_strpos(trim($selectedCategory['category_name']), 'รุ่น') === 0 ? '' : 'รุ่น ') . esc($selectedCategory['category_name']) ?></span>
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3.5 py-1.5 bg-amber-400/20 text-amber-200 border border-amber-400/30 rounded-xl text-xs font-bold backdrop-blur-md">
                    ทีมที่ผ่านการอนุมัติ: <strong class="text-white"><?= count($teams) ?></strong> ทีม
                </span>
            </div>
        </div>

        <!-- Teams Results List -->
        <?php if (empty($teams)): ?>
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm space-y-3">
                <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto text-slate-400">
                    <i data-lucide="inbox" class="w-6 h-6"></i>
                </div>
                <h3 class="font-bold text-slate-700 text-sm">ยังไม่มีทีมที่ได้รับการอนุมัติในรุ่นนี้</h3>
                <p class="text-xs text-slate-400">กรุณาไปที่เมนู "ตรวจสอบทีม & นักกีฬา" เพื่ออนุมัติสิทธิ์แข่งขันก่อนบันทึกผลรางวัล</p>
                <a href="<?= base_url('staff/sports/teams?category_id=' . $categoryId) ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors mt-2">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    <span>ไปหน้าตรวจสอบทีม</span>
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php 
                $awardBadges = [
                    'champion'      => ['bg' => 'bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-black shadow-sm border border-amber-300', 'label' => '🏆 ชนะเลิศ (Champion)'],
                    'runner_up_1'   => ['bg' => 'bg-gradient-to-r from-slate-100 to-slate-200 text-slate-800 font-bold border border-slate-300', 'label' => '🥈 รองชนะเลิศอันดับ 1'],
                    'runner_up_2'   => ['bg' => 'bg-gradient-to-r from-amber-100 to-amber-200 text-amber-950 font-bold border border-amber-300', 'label' => '🥉 รองชนะเลิศอันดับ 2'],
                    'runner_up_3'   => ['bg' => 'bg-indigo-50 text-indigo-900 font-bold border border-indigo-200', 'label' => '🎖️ รองชนะเลิศอันดับ 3'],
                    'participation' => ['bg' => 'bg-slate-100 text-slate-700 font-medium border border-slate-200', 'label' => '📜 เข้าร่วมการแข่งขัน'],
                    'none'          => ['bg' => 'bg-slate-50 text-slate-400 font-medium border border-slate-200', 'label' => '⚪ ยังไม่ระบุรางวัล']
                ];
                foreach ($teams as $idx => $t): 
                    $badge = $awardBadges[$t['award_level']] ?? $awardBadges['none'];
                ?>
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4 hover:border-emerald-200 transition-all">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                            <div class="space-y-2">
                                <div class="flex items-center flex-wrap gap-2">
                                    <span class="px-3 py-1 rounded-xl text-xs <?= $badge['bg'] ?>">
                                        <?= $badge['label'] ?>
                                    </span>
                                    <span class="font-mono text-xs font-bold bg-slate-900 text-white px-2.5 py-0.5 rounded-lg">
                                        <?= esc($t['team_code']) ?>
                                    </span>
                                    <h3 class="text-base font-black text-slate-900"><?= esc($t['school_name']) ?></h3>
                                    <?php if ($t['team_name'] && $t['team_name'] !== $t['school_name']): ?>
                                        <span class="text-xs text-slate-500 font-bold">(<?= esc($t['team_name']) ?>)</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-slate-400">
                                    ผู้ประสานงาน: <strong class="text-slate-700"><?= esc($t['contact_name']) ?></strong> (<?= esc($t['contact_phone']) ?>) | นักกีฬา/โค้ช: <?= count($t['members']) ?> คน
                                </p>
                            </div>

                            <!-- Team Award Selector -->
                            <form action="<?= base_url('staff/sports/results/save-team-award') ?>" method="POST" class="flex items-center gap-2 self-start md:self-auto">
                                <?= csrf_field() ?>
                                <input type="hidden" name="team_id" value="<?= $t['team_id'] ?>">
                                <span class="text-xs font-bold text-slate-500 min-w-max">บันทึกรางวัล:</span>
                                <select name="award_level" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border text-xs font-extrabold focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer <?= $t['award_level'] === 'champion' ? 'bg-amber-50 border-amber-300 text-amber-900' : ($t['award_level'] === 'runner_up_1' ? 'bg-slate-100 border-slate-300 text-slate-800' : ($t['award_level'] === 'runner_up_2' ? 'bg-amber-100/50 border-amber-400 text-amber-950' : ($t['award_level'] === 'runner_up_3' ? 'bg-indigo-50 border-indigo-200 text-indigo-800' : 'bg-slate-50 border-slate-200 text-slate-600'))) ?>">
                                    <option value="none" <?= $t['award_level'] === 'none' ? 'selected' : '' ?>>-- ยังไม่กำหนด --</option>
                                    <option value="champion" <?= $t['award_level'] === 'champion' ? 'selected' : '' ?>>🏆 ชนะเลิศ (Champion)</option>
                                    <option value="runner_up_1" <?= $t['award_level'] === 'runner_up_1' ? 'selected' : '' ?>>🥈 รองชนะเลิศอันดับ 1</option>
                                    <option value="runner_up_2" <?= $t['award_level'] === 'runner_up_2' ? 'selected' : '' ?>>🥉 รองชนะเลิศอันดับ 2</option>
                                    <option value="runner_up_3" <?= $t['award_level'] === 'runner_up_3' ? 'selected' : '' ?>>🎖️ รองชนะเลิศอันดับ 3</option>
                                    <option value="participation" <?= $t['award_level'] === 'participation' ? 'selected' : '' ?>>📜 เข้าร่วมการแข่งขัน</option>
                                </select>
                            </form>
                        </div>

                        <!-- Members Roster Accordion / Toggle -->
                        <div>
                            <details class="group">
                                <summary class="flex items-center justify-between text-xs font-bold text-indigo-600 hover:text-indigo-800 cursor-pointer select-none py-1">
                                    <span class="flex items-center gap-1.5">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                        <span>รายชื่อนักกีฬาและเจ้าหน้าที่ในทีม (<?= count($t['members']) ?> คน)</span>
                                    </span>
                                    <span class="text-[11px] text-slate-400 group-open:rotate-180 transition-transform">
                                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                    </span>
                                </summary>

                                <div class="pt-3 overflow-x-auto">
                                    <table class="w-full text-left text-xs border border-emerald-200 rounded-2xl overflow-hidden shadow-xs">
                                        <thead class="bg-gradient-to-r from-emerald-800 via-teal-800 to-emerald-900 text-white font-black text-xs tracking-wider uppercase border-b-2 border-emerald-400">
                                            <tr>
                                                <th class="px-4 py-3 text-emerald-200 w-20">ประเภท</th>
                                                <th class="px-4 py-3 text-white">ชื่อ - นามสกุล</th>
                                                <th class="px-4 py-3 text-emerald-100">เลข ปชช.</th>
                                                <th class="px-4 py-3 text-emerald-100 text-center">อายุ</th>
                                                <th class="px-4 py-3 text-emerald-100">เบอร์/ตำแหน่ง</th>
                                                <th class="px-4 py-3 text-emerald-200 text-right">รางวัลรายบุคคล</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 font-medium">
                                            <?php foreach ($t['members'] as $m): ?>
                                                <tr class="hover:bg-slate-50/50 transition-colors">
                                                    <td class="px-4 py-2">
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-black <?= $m['member_type'] === 'athlete' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' ?>">
                                                            <?= $m['member_type'] === 'athlete' ? 'นักกีฬา' : 'โค้ช/จนท.' ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 font-bold text-slate-800">
                                                        <?= esc($m['prefix']) ?><?= esc($m['first_name']) ?> <?= esc($m['last_name']) ?>
                                                    </td>
                                                    <td class="px-4 py-2 font-mono text-slate-500"><?= esc($m['id_card']) ?></td>
                                                    <td class="px-4 py-2"><?= $m['age'] ? $m['age'] . ' ปี' : '-' ?></td>
                                                    <td class="px-4 py-2 text-slate-600">
                                                        <?= $m['jersey_number'] ? '#' . esc($m['jersey_number']) : '' ?> <?= $m['position'] ? '(' . esc($m['position']) . ')' : '' ?>
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <form action="<?= base_url('staff/sports/results/save-member-award') ?>" method="POST">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="member_id" value="<?= $m['member_id'] ?>">
                                                            <select name="award_level" onchange="this.form.submit()" class="px-2 py-1 rounded-lg border text-[11px] font-bold focus:outline-none focus:ring-1 focus:ring-indigo-500 cursor-pointer <?= $m['award_level'] === 'champion' ? 'bg-amber-50 border-amber-300 text-amber-900' : ($m['award_level'] === 'runner_up_1' ? 'bg-slate-100 border-slate-300 text-slate-800' : ($m['award_level'] === 'runner_up_2' ? 'bg-amber-100/50 border-amber-400 text-amber-950' : 'bg-slate-50 border-slate-200 text-slate-600')) ?>">
                                                                <option value="none" <?= $m['award_level'] === 'none' ? 'selected' : '' ?>>ตามทีม</option>
                                                                <option value="champion" <?= $m['award_level'] === 'champion' ? 'selected' : '' ?>>ชนะเลิศ</option>
                                                                <option value="runner_up_1" <?= $m['award_level'] === 'runner_up_1' ? 'selected' : '' ?>>รองฯ 1</option>
                                                                <option value="runner_up_2" <?= $m['award_level'] === 'runner_up_2' ? 'selected' : '' ?>>รองฯ 2</option>
                                                                <option value="runner_up_3" <?= $m['award_level'] === 'runner_up_3' ? 'selected' : '' ?>>รองฯ 3</option>
                                                                <option value="participation" <?= $m['award_level'] === 'participation' ? 'selected' : '' ?>>เข้าร่วม</option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
