<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<?php $activeCompYear = isset($activeYear) ? (int)$activeYear : (int)(session()->get('sports_active_year') ?: 2569); ?>
<div class="space-y-6">
    <?= view('sports/admin/layout/nav', ['activeYear' => $activeCompYear]) ?>

    <!-- Header with Filter -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold mb-2">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    <span>Team & Athlete Verification</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">ตรวจสอบทีมและรายชื่อนักกีฬา</h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5">ตรวจสอบเอกสารหลักฐาน, อนุมัติสิทธิ์การแข่งขัน, และพิมพ์เอกสารประจำทีม</p>
            </div>
            <div>
                <a href="<?= base_url('staff/sports/export-excel?year=' . $activeCompYear) ?>" 
                   class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-2xl font-bold text-xs flex items-center gap-2 transition-all">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4 text-emerald-600"></i>
                    <span>ส่งออกข้อมูล Excel (ปี <?= $activeCompYear ?>)</span>
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="<?= base_url('staff/sports/teams') ?>" class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-2 border-t border-slate-100">
            <input type="hidden" name="year" value="<?= $activeCompYear ?>">
            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1">ชนิดกีฬา / รุ่น</label>
                <select name="category_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                    <option value="">-- กีฬาทั้งหมด --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= ($filters['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                            <?= esc($cat['sport_name']) ?> - <?= esc($cat['category_name']) ?> (<?= $cat['category_gender'] === 'female' ? 'หญิง' : ($cat['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1">สถานะ</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                    <option value="">-- ทุกสถานะ --</option>
                    <option value="pending" <?= ($filters['status'] === 'pending') ? 'selected' : '' ?>>รอตรวจสอบ</option>
                    <option value="approved" <?= ($filters['status'] === 'approved') ? 'selected' : '' ?>>อนุมัติแล้ว</option>
                    <option value="rejected" <?= ($filters['status'] === 'rejected') ? 'selected' : '' ?>>ไม่อนุมัติ</option>
                    <option value="cancelled" <?= ($filters['status'] === 'cancelled') ? 'selected' : '' ?>>สละสิทธิ์</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1">ค้นหา (โรงเรียน / รหัสทีม)</label>
                <input type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>" placeholder="ชื่อโรงเรียน, รหัส..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 transition-colors cursor-pointer">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>กรองข้อมูล</span>
                </button>
                <a href="<?= base_url('staff/sports/teams') ?>" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-colors" title="ล้างตัวกรอง">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <!-- Teams Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-gradient-to-r from-emerald-800 via-teal-800 to-emerald-900 text-white font-black text-xs tracking-wider uppercase border-b-2 border-emerald-400">
                    <tr>
                        <th class="px-6 py-4 text-white">รหัสทีม</th>
                        <th class="px-6 py-4 text-white">โรงเรียน / สังกัด</th>
                        <th class="px-6 py-4 text-white">กีฬา / รุ่น</th>
                        <th class="px-6 py-4 text-emerald-100">ผู้ประสานงาน</th>
                        <th class="px-6 py-4 text-center text-emerald-100">สมาชิก (คน)</th>
                        <th class="px-6 py-4 text-center text-emerald-100">สถานะ</th>
                        <th class="px-6 py-4 text-center text-emerald-200">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($teams)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="inbox" class="w-8 h-8 opacity-40"></i>
                                    <span>ไม่พบข้อมูลทีมที่ลงทะเบียนตามเงื่อนไข</span>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($teams as $t): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-slate-900"><?= esc($t['team_code']) ?></td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 text-sm"><?= esc($t['school_name']) ?></div>
                                    <div class="text-[11px] text-slate-400">
                                        <?= esc($t['district']) ? 'อ.' . esc($t['district']) : '' ?> จ.<?= esc($t['province']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-950 border border-emerald-300/80 rounded-xl text-xs font-black shadow-xs">
                                        <i data-lucide="trophy" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                                        <span><?= esc($t['sport_name']) ?></span>
                                    </div>
                                    <div class="text-[11px] font-bold text-slate-600 mt-1 ml-0.5">
                                        <?= (mb_strpos(trim($t['category_name']), 'รุ่น') === 0 ? '' : 'รุ่น ') . esc($t['category_name']) ?> (<?= $t['category_gender'] === 'female' ? 'หญิง' : ($t['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>)
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800"><?= esc($t['contact_name']) ?></div>
                                    <div class="text-[11px] text-slate-400"><?= esc($t['contact_phone']) ?></div>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-800">
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg">
                                        <?= number_format($t['total_members']) ?> คน
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
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
                                <td class="px-6 py-4 text-center">
                                    <a href="<?= base_url('staff/sports/teams/detail/' . $t['team_id']) ?>" class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 rounded-xl font-bold text-xs inline-flex items-center gap-1.5 transition-all">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>ตรวจเอกสาร</span>
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
