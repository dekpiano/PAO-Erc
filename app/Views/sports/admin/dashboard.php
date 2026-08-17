<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<?php $activeCompYear = isset($activeYear) ? (int)$activeYear : (int)(session()->get('sports_active_year') ?: 2569); ?>
<div class="space-y-6">
    <?= view('sports/admin/layout/nav', ['activeYear' => $activeCompYear]) ?>

    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-emerald-900/10 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-15">
            <i data-lucide="trophy" class="w-64 h-64 text-white"></i>
        </div>
        <div class="space-y-1">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-200">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>ระบบบริหารจัดการกีฬาสำหรับเจ้าหน้าที่ • ข้อมูลประจำปี <?= esc($activeCompYear) ?></span>
            </span>
            <h1 class="text-2xl md:text-3xl font-black tracking-tight">ระบบจัดการแข่งขันกีฬา อบจ.นครสวรรค์ เกมส์ (<?= esc($activeCompYear) ?>)</h1>
            <p class="text-xs md:text-sm text-emerald-100 font-light">ภาพรวมสถิติการรับสมัคร ทีม และนักกีฬาทั้งหมด ประจำปีการแข่งขัน <?= esc($activeCompYear) ?></p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500">ชนิดกีฬา / รุ่น</span>
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl"><i data-lucide="activity" class="w-4 h-4"></i></span>
            </div>
            <div class="text-2xl font-black text-slate-900"><?= number_format($totalCategories) ?></div>
            <div class="text-xs text-slate-400 mt-1">รุ่นการแข่งขันทั้งหมด</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500">ทีมสมัครทั้งหมด</span>
                <span class="p-2 bg-blue-50 text-blue-600 rounded-xl"><i data-lucide="shield" class="w-4 h-4"></i></span>
            </div>
            <div class="text-2xl font-black text-slate-900"><?= number_format($totalTeams) ?></div>
            <div class="text-xs text-slate-400 mt-1">รอตรวจ: <strong class="text-amber-600"><?= number_format($pendingTeams) ?></strong> | อนุมัติ: <strong class="text-emerald-600"><?= number_format($approvedTeams) ?></strong></div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500">นักกีฬาทั้งหมด</span>
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl"><i data-lucide="users" class="w-4 h-4"></i></span>
            </div>
            <div class="text-2xl font-black text-slate-900"><?= number_format($totalAthletes) ?></div>
            <div class="text-xs text-slate-400 mt-1">คน (ที่ลงทะเบียน)</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500">ผู้ฝึกสอน / เจ้าหน้าที่</span>
                <span class="p-2 bg-purple-50 text-purple-600 rounded-xl"><i data-lucide="user-check" class="w-4 h-4"></i></span>
            </div>
            <div class="text-2xl font-black text-slate-900"><?= number_format($totalCoaches) ?></div>
            <div class="text-xs text-slate-400 mt-1">คน (ประจำทีม)</div>
        </div>
    </div>

    <!-- Main Navigation Shortcuts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="<?= base_url('staff/sports/categories') ?>" class="group p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="settings-2" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">จัดการชนิดกีฬา & รุ่น</h3>
                <p class="text-xs text-slate-400">กำหนดอายุ, จำนวนผู้เล่น, วันเวลารับสมัคร</p>
            </div>
        </a>

        <a href="<?= base_url('staff/sports/teams') ?>" class="group p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm group-hover:text-emerald-600 transition-colors">ตรวจสอบทีม & นักกีฬา</h3>
                <p class="text-xs text-slate-400">ดูเอกสาร, อนุมัติสิทธิ์, พิมพ์ใบรายงานตัว</p>
            </div>
        </a>

        <a href="<?= base_url('staff/sports/certificates') ?>" class="group p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-amber-200 transition-all flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="award" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm group-hover:text-amber-600 transition-colors">ระบบออกเกียรติบัตร</h3>
                <p class="text-xs text-slate-400">ออกแบบเทมเพลต, บันทึกรางวัล, ออก PDF</p>
            </div>
        </a>
    </div>

    <!-- Recent Registrations Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-slate-800 text-base">การสมัครล่าสุด</h2>
                <p class="text-xs text-slate-400">ทีมและโรงเรียนที่เพิ่งลงทะเบียนเข้าร่วมแข่งขัน</p>
            </div>
            <a href="<?= base_url('staff/sports/teams') ?>" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                <span>ดูทั้งหมด</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-gradient-to-r from-emerald-800 via-teal-800 to-emerald-900 text-white font-black text-xs tracking-wider uppercase border-b-2 border-emerald-400">
                    <tr>
                        <th class="px-5 py-4 text-white">รหัสทีม</th>
                        <th class="px-5 py-4 text-white">โรงเรียน / ทีม</th>
                        <th class="px-5 py-4 text-white">กีฬา / รุ่น</th>
                        <th class="px-5 py-4 text-emerald-100">ผู้ประสานงาน</th>
                        <th class="px-5 py-4 text-center text-emerald-100">สถานะ</th>
                        <th class="px-5 py-4 text-center text-emerald-200">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($recentTeams)): ?>
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="inbox" class="w-8 h-8 opacity-40"></i>
                                    <span>ยังไม่มีข้อมูลการสมัคร</span>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentTeams as $t): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900"><?= esc($t['team_code']) ?></td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-800"><?= esc($t['school_name']) ?></div>
                                    <?php if (!empty($t['team_name']) && $t['team_name'] !== $t['school_name']): ?>
                                        <div class="text-slate-400 text-[11px]"><?= esc($t['team_name']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-950 border border-emerald-300/80 rounded-lg text-xs font-black">
                                        <i data-lucide="trophy" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                                        <span><?= esc($t['sport_name']) ?></span>
                                    </div>
                                    <div class="text-[10px] font-bold text-slate-500 mt-0.5 ml-0.5">
                                        <?= (mb_strpos(trim($t['category_name']), 'รุ่น') === 0 ? '' : 'รุ่น ') . esc($t['category_name']) ?>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-slate-700"><?= esc($t['contact_name']) ?></div>
                                    <div class="text-slate-400 text-[11px]"><?= esc($t['contact_phone']) ?></div>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <?php if ($t['status'] === 'approved'): ?>
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">อนุมัติแล้ว</span>
                                    <?php elseif ($t['status'] === 'rejected'): ?>
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">ไม่อนุมัติ</span>
                                    <?php elseif ($t['status'] === 'cancelled'): ?>
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500">สละสิทธิ์</span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">รอตรวจสอบ</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <a href="<?= base_url('staff/sports/teams/detail/' . $t['team_id']) ?>" class="px-3 py-1.5 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 rounded-lg font-bold text-xs inline-flex items-center gap-1 transition-colors">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>ดูข้อมูล</span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
