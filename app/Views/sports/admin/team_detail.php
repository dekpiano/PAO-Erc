<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <?= view('sports/admin/layout/nav') ?>

    <!-- Top Back Navigation & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="<?= base_url('staff/sports/teams') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>กลับหน้ารายการทีม</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="openEditTeamModal()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all cursor-pointer hover:scale-105 active:scale-95">
                <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-400"></i>
                <span>แก้ไขข้อมูลทีม/ผู้ประสานงาน</span>
            </button>
            <a href="<?= base_url('staff/sports/teams/match-sheet/' . $team['team_id']) ?>" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>พิมพ์ใบรายชื่อ (Match Sheet)</span>
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Team Details Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-3 py-1 bg-slate-900 text-white rounded-xl font-mono font-bold text-xs">
                        <?= esc($team['team_code']) ?>
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700">
                        <?= esc($team['sport_name']) ?> - <?= esc($team['category_name']) ?> (<?= $team['category_gender'] === 'female' ? 'หญิง' : ($team['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>)
                    </span>
                </div>
                <h1 class="text-2xl font-black text-slate-900"><?= esc($team['school_name']) ?></h1>
                <?php if (!empty($team['team_name']) && $team['team_name'] !== $team['school_name']): ?>
                    <p class="text-xs text-slate-400 mt-0.5">ชื่อทีม: <strong class="text-slate-700"><?= esc($team['team_name']) ?></strong></p>
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

        <!-- Coordinator & School Info -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 bg-slate-50 p-5 rounded-2xl text-xs border border-slate-100">
            <div>
                <span class="text-slate-400 block font-medium">ที่ตั้ง / พื้นที่:</span>
                <strong class="text-slate-800 text-sm"><?= esc($team['district'] ? 'อ.' . $team['district'] . ' ' : '') ?>จ.<?= esc($team['province'] ?: 'นครสวรรค์') ?></strong>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">ผู้ประสานงาน / ผู้ควบคุม:</span>
                <strong class="text-slate-800 text-sm"><?= esc($team['contact_name']) ?></strong>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">เบอร์โทรศัพท์ติดต่อ:</span>
                <strong class="text-slate-800 text-sm"><?= esc($team['contact_phone']) ?></strong>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">Line ID:</span>
                <strong class="text-slate-800 text-sm"><?= esc($team['contact_line_id'] ?: '-') ?></strong>
            </div>
        </div>

        <?php
        $athletes = array_values(array_filter($members, fn($m) => $m['member_type'] === 'athlete'));
        $coaches  = array_values(array_filter($members, fn($m) => $m['member_type'] !== 'athlete'));
        ?>

        <!-- 1. Athletes Table -->
        <div class="space-y-4 pt-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </span>
                    <span>รายชื่อนักกีฬา (<?= count($athletes) ?> คน)</span>
                </h3>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-bold self-start sm:self-auto">
                    กำหนด <?= $team['min_players'] ?? 1 ?> - <?= $team['max_players'] ?? 20 ?> คน
                </span>
            </div>

            <div class="overflow-x-auto border border-slate-100 rounded-2xl shadow-sm">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5 text-center w-16">ลำดับ</th>
                            <th class="px-5 py-3.5">ชื่อ - นามสกุล นักกีฬา</th>
                            <th class="px-5 py-3.5 text-center">ระดับชั้นเรียน</th>
                            <th class="px-5 py-3.5 text-center">วัน/เดือน/ปีเกิด (พ.ศ.)</th>
                            <th class="px-5 py-3.5 text-center">อายุ</th>
                            <th class="px-5 py-3.5 text-center">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($athletes)): ?>
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-400">ยังไม่มีรายชื่อนักกีฬาในทีมนี้</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($athletes as $m): ?>
                                <?php
                                $bDateStr = '-';
                                if (!empty($m['birth_date']) && $m['birth_date'] !== '0000-00-00') {
                                    $bTime = strtotime($m['birth_date']);
                                    if ($bTime) {
                                        $thaiYear = date('Y', $bTime) + 543;
                                        $bDateStr = date('d/m/', $bTime) . $thaiYear;
                                    }
                                }

                                $memberJson = htmlspecialchars(json_encode([
                                    'member_id'     => $m['member_id'],
                                    'prefix'        => $m['prefix'],
                                    'first_name'    => $m['first_name'],
                                    'last_name'     => $m['last_name'],
                                    'member_type'   => $m['member_type'],
                                    'position'      => $m['position'],
                                    'jersey_number' => $m['jersey_number'],
                                    'birth_date'    => $m['birth_date'],
                                    'age'           => $m['age']
                                ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                                ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-3.5 text-center font-bold text-slate-400"><?= $no++ ?></td>
                                    <td class="px-5 py-3.5 font-bold text-slate-900 text-sm">
                                        <?= esc($m['prefix']) ?><?= esc($m['first_name']) ?> <?= esc($m['last_name']) ?>
                                    </td>
                                    <td class="px-5 py-3.5 text-center font-bold text-slate-800">
                                        <span class="bg-slate-100 px-2.5 py-1 rounded-lg">
                                            <?= !empty($m['jersey_number']) ? 'ชั้น ' . esc($m['jersey_number']) : '-' ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center font-mono text-slate-700">
                                        <?= $bDateStr ?>
                                    </td>
                                    <td class="px-5 py-3.5 text-center font-bold text-slate-800">
                                        <?= $m['age'] ? $m['age'] . ' ปี' : '-' ?>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <div class="inline-flex items-center gap-1.5">
                                            <button type="button" onclick='openEditMemberModal(<?= $memberJson ?>)' class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl font-bold text-xs inline-flex items-center gap-1 transition-all" title="แก้ไขข้อมูลนักกีฬานี้">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                                <span>แก้ไข</span>
                                            </button>
                                            <a href="<?= base_url('sports/certificate/download/' . $m['member_id']) ?>" target="_blank" class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded-xl transition-all" title="ดูเกียรติบัตร">
                                                <i data-lucide="award" class="w-4 h-4"></i>
                                            </a>
                                            <button type="button" onclick="confirmDeleteMember('<?= base_url('staff/sports/teams/member-delete/' . $m['member_id']) ?>', '<?= esc($m['first_name'] . ' ' . $m['last_name'], 'js') ?>')" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="ลบรายชื่อ">
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

        <!-- 2. Coaches & Team Controllers Table -->
        <div class="space-y-4 pt-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                    </span>
                    <span>รายชื่อผู้ฝึกสอนและผู้ควบคุมทีม (<?= count($coaches) ?> คน)</span>
                </h3>
                <span class="px-3 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-full text-xs font-bold self-start sm:self-auto">
                    กำหนด <?= $team['min_coaches'] ?? 1 ?> - <?= $team['max_coaches'] ?? 5 ?> คน
                </span>
            </div>

            <div class="overflow-x-auto border border-slate-100 rounded-2xl shadow-sm">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5 text-center w-16">ลำดับ</th>
                            <th class="px-5 py-3.5">ตำแหน่งในทีม</th>
                            <th class="px-5 py-3.5">ชื่อ - นามสกุล</th>
                            <th class="px-5 py-3.5 text-center">เบอร์โทรศัพท์ติดต่อ</th>
                            <th class="px-5 py-3.5 text-center">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($coaches)): ?>
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">ยังไม่มีรายชื่อผู้ฝึกสอนหรือผู้ควบคุมทีมในทีมนี้</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($coaches as $m): ?>
                                <?php
                                $memberJson = htmlspecialchars(json_encode([
                                    'member_id'     => $m['member_id'],
                                    'prefix'        => $m['prefix'],
                                    'first_name'    => $m['first_name'],
                                    'last_name'     => $m['last_name'],
                                    'member_type'   => $m['member_type'],
                                    'position'      => $m['position'],
                                    'jersey_number' => $m['jersey_number'],
                                    'birth_date'    => $m['birth_date'],
                                    'age'           => $m['age']
                                ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                                ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-3.5 text-center font-bold text-slate-400"><?= $no++ ?></td>
                                    <td class="px-5 py-3.5">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200">
                                            <?= !empty($m['position']) ? esc($m['position']) : 'ผู้ควบคุมทีม / ผู้ฝึกสอน' ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 font-bold text-slate-900 text-sm">
                                        <?= esc($m['prefix']) ?><?= esc($m['first_name']) ?> <?= esc($m['last_name']) ?>
                                    </td>
                                    <td class="px-5 py-3.5 text-center font-mono text-slate-700">
                                        <?= !empty($m['jersey_number']) ? esc($m['jersey_number']) : '-' ?>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <div class="inline-flex items-center gap-1.5">
                                            <button type="button" onclick='openEditMemberModal(<?= $memberJson ?>)' class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl font-bold text-xs inline-flex items-center gap-1 transition-all" title="แก้ไขข้อมูลผู้ฝึกสอน/ผู้ควบคุมทีมนี้">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                                <span>แก้ไข</span>
                                            </button>
                                            <a href="<?= base_url('sports/certificate/download/' . $m['member_id']) ?>" target="_blank" class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded-xl transition-all" title="ดูเกียรติบัตร">
                                                <i data-lucide="award" class="w-4 h-4"></i>
                                            </a>
                                            <button type="button" onclick="confirmDeleteMember('<?= base_url('staff/sports/teams/member-delete/' . $m['member_id']) ?>', '<?= esc($m['first_name'] . ' ' . $m['last_name'], 'js') ?>')" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="ลบรายชื่อ">
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
    </div>
</div>

<!-- Modal: Edit Team Details -->
<div id="modal-edit-team" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl animate-[fadeIn_0.2s_ease-out]">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center">
                    <i data-lucide="school" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-base">แก้ไขข้อมูลทีม & ผู้ประสานงาน</h3>
                    <p class="text-[11px] text-slate-400">รหัสทีม: <?= esc($team['team_code']) ?></p>
                </div>
            </div>
            <button onclick="closeEditTeamModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="<?= base_url('staff/sports/teams/update/' . $team['team_id']) ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">ชื่อโรงเรียน / สังกัด <span class="text-rose-500">*</span></label>
                <input type="text" name="school_name" value="<?= esc($team['school_name']) ?>" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">ชื่อทีมแข่งขัน (ถ้ามี)</label>
                <input type="text" name="team_name" value="<?= esc($team['team_name'] ?? '') ?>" placeholder="ชื่อทีมเฉพาะ เช่น ทีม A, ทีม B" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ผู้ประสานงาน / ผู้ควบคุม <span class="text-rose-500">*</span></label>
                    <input type="text" name="contact_name" value="<?= esc($team['contact_name']) ?>" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">เบอร์โทรศัพท์ติดต่อ <span class="text-rose-500">*</span></label>
                    <input type="text" name="contact_phone" value="<?= esc($team['contact_phone']) ?>" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Line ID (ถ้ามี)</label>
                <input type="text" name="contact_line_id" value="<?= esc($team['contact_line_id'] ?? '') ?>" placeholder="Line ID" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeEditTeamModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-extrabold text-xs flex items-center gap-2 shadow-lg shadow-indigo-200 transition-all hover:scale-105 active:scale-95">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>บันทึกข้อมูลทีม</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Member Details -->
<div id="modal-edit-member" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl animate-[fadeIn_0.2s_ease-out]">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-base">แก้ไขข้อมูลสมาชิก / นักกีฬา / โค้ช</h3>
                    <p class="text-[11px] text-slate-400">แก้ไขข้อมูลตามแบบฟอร์มการรับสมัคร</p>
                </div>
            </div>
            <button onclick="closeEditMemberModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="form-edit-member" action="" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">บทบาทในทีม <span class="text-rose-500">*</span></label>
                <select id="edit-member-type" name="member_type" onchange="toggleEditMemberFields(this.value)" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500">
                    <option value="athlete">🏃 นักกีฬา</option>
                    <option value="coach">👔 ผู้ฝึกสอน / ผู้ควบคุมทีม / เจ้าหน้าที่</option>
                </select>
            </div>

            <div class="grid grid-cols-12 gap-3">
                <div class="col-span-4">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">คำนำหน้า <span class="text-rose-500">*</span></label>
                    <select id="edit-member-prefix" name="prefix" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                        <option value="เด็กชาย">เด็กชาย</option>
                        <option value="เด็กหญิง">เด็กหญิง</option>
                        <option value="นาย">นาย</option>
                        <option value="นางสาว">นางสาว</option>
                        <option value="นาง">นาง</option>
                        <option value="ครู">ครู</option>
                        <option value="อาจารย์">อาจารย์</option>
                        <option value="ว่าที่ร้อยตรี">ว่าที่ร้อยตรี</option>
                        <option value="ดร.">ดร.</option>
                    </select>
                </div>
                <div class="col-span-4">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ชื่อจริง <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit-member-firstname" name="first_name" required placeholder="ชื่อ" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="col-span-4">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">นามสกุล <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit-member-lastname" name="last_name" required placeholder="นามสกุล" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <!-- Fields for Athlete (Datepicker BE, Age, Class Level) -->
            <div id="section-athlete-fields" class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">วัน/เดือน/ปีเกิด (พ.ศ.) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="edit-member-birthdate" name="birth_date" placeholder="วว/ดด/ปปปป (พ.ศ.)" class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500 bg-white">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-3 pointer-events-none"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">อายุ (ปี) <span class="text-emerald-600 text-[10px] font-normal">คำนวณอัตโนมัติ</span></label>
                        <input type="number" id="edit-member-age" name="age" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ระดับชั้นเรียน</label>
                    <select id="edit-member-grade" name="jersey_number_athlete" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- เลือกระดับชั้น --</option>
                        <option value="ม.1">มัธยมศึกษาปีที่ 1 (ม.1)</option>
                        <option value="ม.2">มัธยมศึกษาปีที่ 2 (ม.2)</option>
                        <option value="ม.3">มัธยมศึกษาปีที่ 3 (ม.3)</option>
                        <option value="ม.4">มัธยมศึกษาปีที่ 4 (ม.4)</option>
                        <option value="ม.5">มัธยมศึกษาปีที่ 5 (ม.5)</option>
                        <option value="ม.6">มัธยมศึกษาปีที่ 6 (ม.6)</option>
                        <option value="ป.4">ประถมศึกษาปีที่ 4 (ป.4)</option>
                        <option value="ป.5">ประถมศึกษาปีที่ 5 (ป.5)</option>
                        <option value="ป.6">ประถมศึกษาปีที่ 6 (ป.6)</option>
                        <option value="ปวช.1">ปวช. 1</option>
                        <option value="ปวช.2">ปวช. 2</option>
                        <option value="ปวช.3">ปวช. 3</option>
                    </select>
                </div>
            </div>

            <!-- Fields for Coach / Staff (Position, Phone) -->
            <div id="section-coach-fields" class="space-y-4 hidden">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ตำแหน่งในทีม</label>
                        <select id="edit-member-position" name="position" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                            <option value="ผู้ควบคุมทีม">ผู้ควบคุมทีม</option>
                            <option value="ผู้ฝึกสอน">ผู้ฝึกสอน (โค้ช)</option>
                            <option value="ผู้ช่วยผู้ฝึกสอน">ผู้ช่วยผู้ฝึกสอน</option>
                            <option value="ผู้จัดการทีม">ผู้จัดการทีม</option>
                            <option value="เจ้าหน้าที่ประจำทีม">เจ้าหน้าที่ประจำทีม</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">เบอร์โทรศัพท์</label>
                        <input type="text" id="edit-member-phone" name="jersey_number_coach" placeholder="08x-xxxxxxx" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>

            <!-- Hidden input to pass jersey_number correctly -->
            <input type="hidden" id="edit-member-jersey-final" name="jersey_number" value="">

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeEditMemberModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" onclick="prepareMemberSubmit()" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-extrabold text-xs flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all hover:scale-105 active:scale-95">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>บันทึกการแก้ไข</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let editMemberFp = null;

    function initEditMemberFlatpickr() {
        const input = document.getElementById('edit-member-birthdate');
        if (!input) return;
        if (input._flatpickr) {
            input._flatpickr.destroy();
        }

        editMemberFp = flatpickr(input, {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            locale: "th",
            onReady: function (selectedDates, dateStr, instance) {
                if (typeof applyBE === 'function') { applyBE(instance); setTimeout(() => applyBE(instance), 10); }
            },
            onValueUpdate: function (selectedDates, dateStr, instance) {
                if (typeof applyBE === 'function') { applyBE(instance); setTimeout(() => applyBE(instance), 10); }
                if (selectedDates && selectedDates.length > 0) {
                    calculateEditMemberAge(selectedDates[0]);
                }
            },
            onOpen: function (selectedDates, dateStr, instance) {
                if (typeof applyBE === 'function') { applyBE(instance); setTimeout(() => applyBE(instance), 10); }
            },
            onMonthChange: function (selectedDates, dateStr, instance) {
                if (typeof applyBE === 'function') { setTimeout(() => applyBE(instance), 10); }
            },
            onYearChange: function (selectedDates, dateStr, instance) {
                if (typeof applyBE === 'function') { setTimeout(() => applyBE(instance), 10); }
            },
            onChange: function (selectedDates, dateStr, instance) {
                if (typeof applyBE === 'function') { applyBE(instance); setTimeout(() => applyBE(instance), 10); }
                if (selectedDates && selectedDates.length > 0) {
                    calculateEditMemberAge(selectedDates[0]);
                }
            }
        });
    }

    function calculateEditMemberAge(birthDate) {
        if (!birthDate) return;
        const d = (birthDate instanceof Date) ? birthDate : new Date(birthDate);
        if (isNaN(d.getTime())) return;
        const today = new Date();
        let age = today.getFullYear() - d.getFullYear();
        const m = today.getMonth() - d.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < d.getDate())) {
            age--;
        }
        if (age >= 0) {
            document.getElementById('edit-member-age').value = age;
        }
    }

    function toggleEditMemberFields(type) {
        const athleteSec = document.getElementById('section-athlete-fields');
        const coachSec = document.getElementById('section-coach-fields');

        const bDateInput = document.getElementById('edit-member-birthdate');
        const ageInput = document.getElementById('edit-member-age');
        const gradeInput = document.getElementById('edit-member-grade');
        const posInput = document.getElementById('edit-member-position');
        const phoneInput = document.getElementById('edit-member-phone');

        if (type === 'coach') {
            athleteSec.classList.add('hidden');
            coachSec.classList.remove('hidden');

            if (bDateInput) bDateInput.disabled = true;
            if (ageInput) ageInput.disabled = true;
            if (gradeInput) gradeInput.disabled = true;
            if (posInput) posInput.disabled = false;
            if (phoneInput) phoneInput.disabled = false;
        } else {
            athleteSec.classList.remove('hidden');
            coachSec.classList.add('hidden');

            if (bDateInput) bDateInput.disabled = false;
            if (ageInput) ageInput.disabled = false;
            if (gradeInput) gradeInput.disabled = false;
            if (posInput) posInput.disabled = true;
            if (phoneInput) phoneInput.disabled = true;
        }
        if (window.lucide) lucide.createIcons();
    }

    function prepareMemberSubmit() {
        const type = document.getElementById('edit-member-type').value;
        const finalInput = document.getElementById('edit-member-jersey-final');
        if (type === 'coach') {
            finalInput.value = document.getElementById('edit-member-phone').value || '';
        } else {
            finalInput.value = document.getElementById('edit-member-grade').value || '';
        }
    }

    function openEditTeamModal() {
        document.getElementById('modal-edit-team').classList.remove('hidden');
        if (window.lucide) lucide.createIcons();
    }
    function closeEditTeamModal() {
        document.getElementById('modal-edit-team').classList.add('hidden');
    }

    function openEditMemberModal(m) {
        const form = document.getElementById('form-edit-member');
        form.action = '<?= base_url('staff/sports/teams/member-update') ?>/' + m.member_id;

        const isAthlete = (m.member_type === 'athlete');
        document.getElementById('edit-member-type').value = m.member_type || 'athlete';
        toggleEditMemberFields(m.member_type || 'athlete');

        document.getElementById('edit-member-prefix').value = m.prefix || 'นาย';
        document.getElementById('edit-member-firstname').value = m.first_name || '';
        document.getElementById('edit-member-lastname').value = m.last_name || '';
        document.getElementById('edit-member-position').value = m.position || 'ผู้ควบคุมทีม';
        document.getElementById('edit-member-age').value = m.age || '';

        if (isAthlete) {
            document.getElementById('edit-member-grade').value = m.jersey_number || '';
        } else {
            document.getElementById('edit-member-phone').value = m.jersey_number || '';
        }

        initEditMemberFlatpickr();
        if (editMemberFp) {
            if (m.birth_date && m.birth_date !== '0000-00-00') {
                editMemberFp.setDate(m.birth_date, true);
            } else {
                editMemberFp.clear();
            }
        }

        document.getElementById('modal-edit-member').classList.remove('hidden');
        if (window.lucide) lucide.createIcons();
    }
    function closeEditMemberModal() {
        document.getElementById('modal-edit-member').classList.add('hidden');
    }

    function confirmDeleteMember(url, name) {
        if (confirm('คุณต้องการลบรายชื่อ "' + name + '" ออกจากทีมหรือไม่?')) {
            window.location.href = url;
        }
    }
</script>
<?= $this->endSection() ?>
