<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <?= view('sports/admin/layout/nav') ?>

    <!-- Top Back Navigation & Team Info Header -->
    <div class="flex items-center justify-between">
        <a href="<?= base_url('staff/sports/teams') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>กลับหน้ารายการทีม</span>
        </a>
    </div>

    <!-- Team Details Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-3 py-1 bg-slate-900 text-white rounded-xl font-mono font-bold text-xs">
                        <?= esc($team['team_code']) ?>
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700">
                        <?= esc($team['sport_name']) ?> - <?= esc($team['category_name']) ?>
                    </span>
                </div>
                <h1 class="text-2xl font-black text-slate-900"><?= esc($team['school_name']) ?></h1>
                <?php if (!empty($team['team_name']) && $team['team_name'] !== $team['school_name']): ?>
                    <p class="text-xs text-slate-400 mt-0.5">ชื่อทีม: <?= esc($team['team_name']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Status Badge & Form -->
            <form action="<?= base_url('staff/sports/teams/update-status/' . $team['team_id']) ?>" method="POST" class="flex flex-wrap items-center gap-3">
                <?= csrf_field() ?>
                <select name="status" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                    <option value="pending" <?= $team['status'] === 'pending' ? 'selected' : '' ?>>⏳ รอตรวจสอบ</option>
                    <option value="approved" <?= $team['status'] === 'approved' ? 'selected' : '' ?>>✅ อนุมัติสิทธิ์</option>
                    <option value="rejected" <?= $team['status'] === 'rejected' ? 'selected' : '' ?>>❌ ไม่อนุมัติ</option>
                    <option value="cancelled" <?= $team['status'] === 'cancelled' ? 'selected' : '' ?>>🚫 สละสิทธิ์</option>
                </select>
                <input type="text" name="admin_note" value="<?= esc($team['admin_note'] ?? '') ?>" placeholder="หมายเหตุ / เหตุผล..." class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-indigo-500">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-sm transition-all cursor-pointer">
                    บันทึกสถานะ
                </button>
            </form>
        </div>

        <!-- Coordinator Info -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-2xl text-xs">
            <div>
                <span class="text-slate-400 block font-medium">ผู้ประสานงาน / ผู้ควบคุม:</span>
                <strong class="text-slate-800 text-sm"><?= esc($team['contact_name']) ?></strong>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">เบอร์โทรศัพท์ติดต่อ:</span>
                <strong class="text-slate-800 text-sm"><?= esc($team['contact_phone']) ?></strong>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">Line ID / Email:</span>
                <strong class="text-slate-800 text-sm"><?= esc($team['contact_line_id'] ?: ($team['contact_email'] ?: '-')) ?></strong>
            </div>
        </div>

        <!-- Members Table -->
        <div class="space-y-4 pt-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i data-lucide="users" class="w-5 h-5 text-indigo-600"></i>
                    <span>รายชื่อนักกีฬาและผู้ฝึกสอนประจำทีม (<?= count($members) ?> คน)</span>
                </h3>
            </div>

            <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3">ลำดับ</th>
                            <th class="px-5 py-3">ตำแหน่ง / บทบาท</th>
                            <th class="px-5 py-3">ชื่อ - นามสกุล</th>
                            <th class="px-5 py-3">เลขประจำตัวประชาชน</th>
                            <th class="px-5 py-3 text-center">ระดับชั้นเรียน</th>
                            <th class="px-5 py-3 text-center">อายุ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($members)): ?>
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-400">ยังไม่มีรายชื่อสมาชิกในทีมนี้</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($members as $m): ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-3 font-bold text-slate-400"><?= $no++ ?></td>
                                    <td class="px-5 py-3">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold <?= $m['member_type'] === 'athlete' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' ?>">
                                            <?= $m['member_type'] === 'athlete' ? 'นักกีฬา' : ($m['member_type'] === 'coach' ? 'ผู้ฝึกสอน' : 'ผู้จัดการทีม') ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 font-bold text-slate-900">
                                        <?= esc($m['prefix']) ?><?= esc($m['first_name']) ?> <?= esc($m['last_name']) ?>
                                    </td>
                                    <td class="px-5 py-3 font-mono text-slate-600"><?= esc($m['id_card']) ?></td>
                                    <td class="px-5 py-3 text-center font-bold text-slate-800">
                                        <?= !empty($m['jersey_number']) ? esc($m['jersey_number']) : '-' ?>
                                        <?= !empty($m['position']) ? '<span class="text-xs text-slate-400 font-normal block">(' . esc($m['position']) . ')</span>' : '' ?>
                                    </td>
                                    <td class="px-5 py-3 text-center"><?= $m['age'] ? $m['age'] . ' ปี' : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
