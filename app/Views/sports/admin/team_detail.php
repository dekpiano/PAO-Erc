<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<?php $activeCompYear = isset($activeYear) ? (int)$activeYear : (int)(session()->get('sports_active_year') ?: 2569); ?>
<div class="space-y-6">
    <?= view('sports/admin/layout/nav', ['activeYear' => $activeCompYear]) ?>

    <!-- Top Back Navigation & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="<?= base_url('staff/sports/teams') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>กลับหน้ารายการทีม</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="<?= base_url('staff/sports/teams/match-sheet/' . $team['team_id']) ?>" target="_blank" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all">
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
        
        <!-- Top Code & Status Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-400">รหัสทีม:</span>
                <span class="px-3.5 py-1 bg-slate-900 text-white rounded-xl font-mono font-black text-xs tracking-wider shadow-xs">
                    <?= esc($team['team_code']) ?>
                </span>
                <span class="text-xs font-bold text-slate-400 ml-2">ปีการแข่งขัน:</span>
                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-black">
                    <?= esc($team['comp_year'] ?? '') ?>
                </span>
            </div>

            <!-- Current Status Badge in Header -->
            <?php
            $statusMap = [
                'pending'   => ['bg' => 'bg-amber-50 text-amber-800 border-amber-300', 'icon' => 'clock', 'label' => '⏳ รอตรวจสอบ'],
                'approved'  => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-300', 'icon' => 'check-circle-2', 'label' => '✅ อนุมัติสิทธิ์แล้ว'],
                'rejected'  => ['bg' => 'bg-rose-50 text-rose-800 border-rose-300', 'icon' => 'x-circle', 'label' => '❌ ไม่อนุมัติ'],
                'cancelled' => ['bg' => 'bg-slate-100 text-slate-600 border-slate-300', 'icon' => 'ban', 'label' => '🚫 สละสิทธิ์'],
            ];
            $currentStatus = $statusMap[$team['status']] ?? $statusMap['pending'];
            ?>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 font-bold">สถานะทีม:</span>
                <span class="px-4 py-1.5 rounded-xl text-xs font-black border flex items-center gap-1.5 shadow-xs <?= $currentStatus['bg'] ?>">
                    <i data-lucide="<?= $currentStatus['icon'] ?>" class="w-4 h-4"></i>
                    <span><?= $currentStatus['label'] ?></span>
                </span>
            </div>
        </div>

        <!-- 3 Core Highlights (ชื่อกีฬา, ชื่อรุ่น, ชื่อโรงเรียน) ธีมสีเขียวสดใส เด่นพอๆ กันแบบชัดเจน -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- 1. ชนิดกีฬา (Sport Name) -->
            <div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-700 text-white p-5 rounded-2xl shadow-md shadow-emerald-700/20 flex items-center gap-4 relative overflow-hidden border border-emerald-500/30">
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0">
                    <i data-lucide="trophy" class="w-7 h-7 text-amber-300"></i>
                </div>
                <div class="space-y-0.5 min-w-0">
                    <span class="text-[11px] font-bold text-emerald-100 uppercase tracking-wider block">ชนิดกีฬา</span>
                    <h2 class="text-xl sm:text-2xl font-black text-white truncate tracking-tight">
                        <?= esc($team['sport_name']) ?>
                    </h2>
                </div>
            </div>

            <!-- 2. รุ่นการแข่งขัน (Category & Gender) -->
            <div class="bg-gradient-to-br from-teal-700 via-teal-800 to-emerald-800 text-white p-5 rounded-2xl shadow-md shadow-teal-800/20 flex items-center gap-4 relative overflow-hidden border border-teal-600/30">
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0">
                    <i data-lucide="layers" class="w-7 h-7 text-teal-200"></i>
                </div>
                <div class="space-y-0.5 min-w-0">
                    <span class="text-[11px] font-bold text-teal-100 uppercase tracking-wider block">รุ่นการแข่งขัน / เพศ</span>
                    <h2 class="text-xl sm:text-2xl font-black text-white truncate tracking-tight">
                        <?= (mb_strpos(trim($team['category_name']), 'รุ่น') === 0 ? '' : 'รุ่น ') . esc($team['category_name']) ?>
                        <span class="text-sm font-bold px-2 py-0.5 rounded-md bg-white/20 text-white ml-1">
                            <?= $team['category_gender'] === 'female' ? 'หญิง' : ($team['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>
                        </span>
                    </h2>
                </div>
            </div>

            <!-- 3. โรงเรียน / สังกัด (School & Team Name) -->
            <div class="bg-gradient-to-br from-emerald-900 via-teal-950 to-slate-900 text-white p-5 rounded-2xl shadow-md shadow-emerald-950/20 flex items-center gap-4 relative overflow-hidden border border-emerald-800/30">
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0">
                    <i data-lucide="school" class="w-7 h-7 text-amber-400"></i>
                </div>
                <div class="space-y-0.5 min-w-0">
                    <span class="text-[11px] font-bold text-emerald-200 uppercase tracking-wider block">โรงเรียน / สังกัด</span>
                    <h2 class="text-xl sm:text-2xl font-black text-white truncate tracking-tight">
                        <?= esc($team['school_name']) ?>
                    </h2>
                    <?php if (!empty($team['team_name']) && $team['team_name'] !== $team['school_name']): ?>
                        <p class="text-[11px] text-emerald-300 truncate font-medium">ชื่อทีม: <strong><?= esc($team['team_name']) ?></strong></p>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- Coordinator & School Info -->
        <div class="bg-slate-50 p-5 rounded-2xl text-xs border border-slate-100 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-200/60 pb-2.5">
                <span class="font-bold text-slate-700 flex items-center gap-1.5">
                    <i data-lucide="info" class="w-4 h-4 text-emerald-600"></i>
                    <span>ข้อมูลทีม & ผู้ประสานงาน</span>
                </span>
                <button type="button" onclick="openEditTeamModal()" class="px-3.5 py-1.5 bg-white hover:bg-slate-900 text-slate-700 hover:text-white border border-slate-200 hover:border-slate-900 rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all hover:scale-105 active:scale-95 cursor-pointer">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-500"></i>
                    <span>แก้ไขข้อมูล</span>
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
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
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-bold">
                        กำหนด <?= $team['min_players'] ?? 1 ?> - <?= $team['max_players'] ?? 20 ?> คน
                    </span>
                    <button type="button" onclick="openAddMemberModal('athlete')" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all hover:scale-105 active:scale-95 cursor-pointer">
                        <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                        <span>เพิ่มนักกีฬา</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto border border-emerald-200 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-gradient-to-r from-emerald-800 via-teal-800 to-emerald-900 text-white font-black text-xs tracking-wider uppercase border-b-2 border-emerald-400">
                        <tr>
                            <th class="px-5 py-4 text-center w-16 text-emerald-200">ลำดับ</th>
                            <th class="px-5 py-4 text-white">ชื่อ - นามสกุล นักกีฬา</th>
                            <th class="px-5 py-4 text-center text-emerald-100">ระดับชั้นเรียน</th>
                            <th class="px-5 py-4 text-center text-emerald-100">วัน/เดือน/ปีเกิด (พ.ศ.)</th>
                            <th class="px-5 py-4 text-center text-emerald-100">อายุ</th>
                            <th class="px-5 py-4 text-center text-emerald-200">การจัดการ</th>
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
                                    $dt = new DateTime($m['birth_date']);
                                    $bDateStr = $dt->format('d/m/') . ($dt->format('Y') + 543);
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
                                <tr class="hover:bg-emerald-50/30 transition-colors">
                                    <td class="px-5 py-3.5 text-center font-bold text-slate-400"><?= $no++ ?></td>
                                    <td class="px-5 py-3.5 font-bold text-slate-900 text-sm">
                                        <?= esc($m['prefix']) ?><?= esc($m['first_name']) ?> <?= esc($m['last_name']) ?>
                                    </td>
                                    <td class="px-5 py-3.5 text-center font-bold text-slate-700">
                                        <span class="bg-slate-100 px-2.5 py-1 rounded-lg">
                                             <?= !empty($m['jersey_number']) ? esc($m['jersey_number']) : '-' ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center font-mono text-slate-600">
                                        <?= $bDateStr ?>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 rounded-lg font-black text-xs">
                                            <?= $m['age'] > 0 ? $m['age'] . ' ปี' : '-' ?>
                                        </span>
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
                                            <a href="<?= base_url('staff/sports/teams/member-delete/' . $m['member_id']) ?>" onclick="return confirm('ยืนยันที่จะลบนักกีฬาท่านนี้ออกจากทีมใช่หรือไม่?')" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition-all" title="ลบนักกีฬา">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </a>
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
        <div class="space-y-4 pt-6 border-t border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                    </span>
                    <span>รายชื่อผู้ฝึกสอน & เจ้าหน้าที่ประจำทีม (<?= count($coaches) ?> คน)</span>
                </h3>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 border border-teal-200 rounded-full text-xs font-bold">
                        กำหนด <?= $team['min_coaches'] ?? 1 ?> - <?= $team['max_coaches'] ?? 5 ?> คน
                    </span>
                    <button type="button" onclick="openAddMemberModal('coach')" class="px-3.5 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all hover:scale-105 active:scale-95 cursor-pointer">
                        <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                        <span>เพิ่มผู้ฝึกสอน/เจ้าหน้าที่</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto border border-teal-200 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-gradient-to-r from-teal-900 via-emerald-900 to-teal-950 text-white font-black text-xs tracking-wider uppercase border-b-2 border-teal-400">
                        <tr>
                            <th class="px-5 py-4 text-center w-16 text-teal-200">ลำดับ</th>
                            <th class="px-5 py-4 text-white">ตำแหน่งในทีม</th>
                            <th class="px-5 py-4 text-white">ชื่อ - นามสกุล</th>
                            <th class="px-5 py-4 text-center text-teal-100">เบอร์โทรศัพท์ติดต่อ</th>
                            <th class="px-5 py-4 text-center text-teal-200">การจัดการ</th>
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

        <!-- 3. Final Step: Confirm & Update Team Status (Prominent Bottom Section) -->
        <div class="pt-6 border-t border-slate-100">
            <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 relative overflow-hidden">
                <!-- Background decorative shapes -->
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -left-10 -top-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md text-amber-400 flex items-center justify-center border border-white/10 shrink-0">
                                <i data-lucide="shield-check" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400/20 text-amber-300 border border-amber-400/30">
                                    ขั้นตอนสุดท้าย
                                </span>
                                <h3 class="text-lg font-black text-white mt-1">ยืนยันผลการตรวจสอบและอัปเดตสถานะทีม</h3>
                                <p class="text-xs text-slate-300">เมื่อตรวจสอบคุณสมบัตินักกีฬาและรายชื่อเจ้าหน้าที่ครบถ้วนแล้ว ให้เลือกผลการอนุมัติเพื่อบันทึกสถานะ</p>
                            </div>
                        </div>

                        <!-- Current status indicator badge -->
                        <div class="flex items-center gap-2 self-start sm:self-auto bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10">
                            <span class="text-xs text-slate-300">สถานะปัจจุบัน:</span>
                            <span class="font-bold text-xs <?= $team['status'] === 'approved' ? 'text-emerald-300' : ($team['status'] === 'rejected' ? 'text-rose-300' : 'text-amber-300') ?>">
                                <?= $currentStatus['label'] ?>
                            </span>
                        </div>
                    </div>

                    <form action="<?= base_url('staff/sports/teams/update-status/' . $team['team_id']) ?>" method="POST" class="space-y-4">
                        <?= csrf_field() ?>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <!-- Status Selector -->
                            <div class="md:col-span-4 space-y-1.5">
                                <label class="block text-xs font-bold text-slate-200">
                                    เลือกสถานะการอนุมัติ <span class="text-rose-400">*</span>
                                </label>
                                <select name="status" class="w-full px-4 py-3 rounded-2xl bg-white text-slate-900 font-bold text-xs border border-white/20 focus:ring-2 focus:ring-amber-400 transition-all cursor-pointer shadow-inner">
                                    <option value="pending" <?= $team['status'] === 'pending' ? 'selected' : '' ?>>⏳ รอตรวจสอบ (Pending)</option>
                                    <option value="approved" <?= $team['status'] === 'approved' ? 'selected' : '' ?>>✅ อนุมัติสิทธิ์ (Approved)</option>
                                    <option value="rejected" <?= $team['status'] === 'rejected' ? 'selected' : '' ?>>❌ ไม่อนุมัติ (Rejected)</option>
                                    <option value="cancelled" <?= $team['status'] === 'cancelled' ? 'selected' : '' ?>>🚫 สละสิทธิ์ (Cancelled)</option>
                                </select>
                            </div>

                            <!-- Admin Note / Remark -->
                            <div class="md:col-span-5 space-y-1.5">
                                <label class="block text-xs font-bold text-slate-200">
                                    หมายเหตุ / เหตุผลการพิจารณา (บันทึกข้อมูลเพิ่มเติม)
                                </label>
                                <input type="text" name="admin_note" value="<?= esc($team['admin_note'] ?? '') ?>" placeholder="เช่น เอกสารและคุณสมบัติถูกต้องครบถ้วน..." class="w-full px-4 py-3 rounded-2xl bg-white text-slate-900 font-medium text-xs border border-white/20 focus:ring-2 focus:ring-amber-400 transition-all shadow-inner">
                            </div>

                            <!-- Submit Button -->
                            <div class="md:col-span-3 flex items-end">
                                <button type="submit" class="w-full py-3 px-6 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-2xl font-black text-xs shadow-lg shadow-emerald-900/30 flex items-center justify-center gap-2 transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                                    <i data-lucide="check-check" class="w-4 h-4"></i>
                                    <span>บันทึกสถานะทีม</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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
                    <p class="text-[11px] text-slate-500 font-bold">
                        <span class="text-emerald-700 font-black">กีฬา <?= esc($team['sport_name']) ?></span> | <?= (mb_strpos(trim($team['category_name']), 'รุ่น') === 0 ? '' : 'รุ่น ') . esc($team['category_name']) ?> (รหัส: <?= esc($team['team_code']) ?>)
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeEditTeamModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors">
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
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">อายุ (ปี) <span id="edit-member-age-status" class="text-emerald-600 text-[10px] font-normal">คำนวณอัตโนมัติ</span></label>
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

<!-- Modal: Add Member Details -->
<div id="modal-add-member" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl animate-[fadeIn_0.2s_ease-out] max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2.5">
                <div id="add-modal-icon" class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 id="add-modal-title" class="font-black text-slate-900 text-base">เพิ่มนักกีฬาเข้าทีม</h3>
                    <p class="text-[11px] text-slate-400">ทีม: <?= esc($team['team_name'] ?: $team['school_name']) ?> (<?= esc($team['sport_name']) ?>)</p>
                </div>
            </div>
            <button onclick="closeAddMemberModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="form-add-member" action="<?= base_url('staff/sports/teams/member-create/' . $team['team_id']) ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">บทบาทในทีม <span class="text-rose-500">*</span></label>
                <select id="add-member-type" name="member_type" onchange="toggleAddMemberFields(this.value)" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500">
                    <option value="athlete">🏃 นักกีฬา</option>
                    <option value="coach">👔 ผู้ฝึกสอน / ผู้ควบคุมทีม / เจ้าหน้าที่</option>
                </select>
            </div>

            <div class="grid grid-cols-12 gap-3">
                <div class="col-span-4">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">คำนำหน้า <span class="text-rose-500">*</span></label>
                    <select id="add-member-prefix" name="prefix" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                        <option value="เด็กชาย">เด็กชาย</option>
                        <option value="เด็กหญิง">เด็กหญิง</option>
                        <option value="นาย" selected>นาย</option>
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
                    <input type="text" id="add-member-firstname" name="first_name" required placeholder="ชื่อ" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="col-span-4">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">นามสกุล <span class="text-rose-500">*</span></label>
                    <input type="text" id="add-member-lastname" name="last_name" required placeholder="นามสกุล" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <!-- Fields for Athlete (Datepicker BE, Age, Class Level) -->
            <div id="section-add-athlete-fields" class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">วัน/เดือน/ปีเกิด (พ.ศ.) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="add-member-birthdate" name="birth_date" placeholder="วว/ดด/ปปปป (พ.ศ.)" class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500 bg-white">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-3 pointer-events-none"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">อายุ (ปี) <span id="add-member-age-status" class="text-emerald-600 text-[10px] font-normal">คำนวณอัตโนมัติ</span></label>
                        <input type="number" id="add-member-age" name="age" placeholder="เช่น 15" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ระดับชั้นเรียน</label>
                    <select id="add-member-grade" name="jersey_number_athlete" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500">
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
            <div id="section-add-coach-fields" class="space-y-4 hidden">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ตำแหน่งในทีม</label>
                        <select id="add-member-position" name="position" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                            <option value="ผู้ควบคุมทีม">ผู้ควบคุมทีม</option>
                            <option value="ผู้ฝึกสอน">ผู้ฝึกสอน (โค้ช)</option>
                            <option value="ผู้ช่วยผู้ฝึกสอน">ผู้ช่วยผู้ฝึกสอน</option>
                            <option value="ผู้จัดการทีม">ผู้จัดการทีม</option>
                            <option value="เจ้าหน้าที่ประจำทีม">เจ้าหน้าที่ประจำทีม</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">เบอร์โทรศัพท์</label>
                        <input type="text" id="add-member-phone" name="jersey_number_coach" placeholder="08x-xxxxxxx" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>

            <!-- Hidden input to pass jersey_number correctly -->
            <input type="hidden" id="add-member-jersey-final" name="jersey_number" value="">

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeAddMemberModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" onclick="prepareAddMemberSubmit()" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-extrabold text-xs flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all hover:scale-105 active:scale-95">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>บันทึกเพิ่มสมาชิก</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const ageMin = <?= (int)($team['age_min'] ?? 0) ?>;
    const ageMax = <?= (int)($team['age_max'] ?? 99) ?>;
    const categoryName = '<?= esc($team['category_name'] ?? '', 'js') ?>';

    let editMemberFp = null;
    let addMemberFp  = null;

    function validateAthleteAge(age, isAdd = true) {
        const typeEl = document.getElementById(isAdd ? 'add-member-type' : 'edit-member-type');
        if (typeEl && typeEl.value !== 'athlete') return true;

        const ageStatusEl = document.getElementById(isAdd ? 'add-member-age-status' : 'edit-member-age-status');
        const effectiveAgeMin = ageMin > 0 ? ageMin : 5;

        if (!age || age <= 0) {
            if (ageStatusEl) {
                ageStatusEl.innerHTML = '<span class="text-rose-600 font-bold">❌ วันเกิดไม่ถูกต้อง (0 ปี)</span>';
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: '❌ ไม่สามารถสมัครได้ (วันเกิดไม่ถูกต้อง)',
                    html: `คำนวณอายุได้ <b>0 ปี</b> กรุณาเลือกปีเกิด (พ.ศ.) ให้ถูกต้อง (นักกีฬาต้องมีอายุอย่างน้อย ${effectiveAgeMin} ปี)`,
                    confirmButtonColor: '#e11d48'
                });
            }
            return false;
        }

        if (age < effectiveAgeMin) {
            if (ageStatusEl) {
                ageStatusEl.innerHTML = `<span class="text-rose-600 font-bold">❌ น้อยกว่าเกณฑ์ (${age} ปี)</span>`;
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: '❌ ไม่สามารถสมัครรุ่นนี้ได้ (อายุน้อยกว่าเกณฑ์)',
                    html: `นักกีฬาอายุ <b>${age} ปี</b> น้อยกว่าเกณฑ์ขั้นต่ำของการแข่งขัน (กำหนดอายุระหว่าง <b>${effectiveAgeMin} - ${ageMax} ปี</b>)`,
                    confirmButtonColor: '#d97706'
                });
            }
            return false;
        }

        if (ageMax < 99 && age > ageMax) {
            if (ageStatusEl) {
                ageStatusEl.innerHTML = `<span class="text-rose-600 font-bold">❌ อายุเกินเกณฑ์ (${age} ปี)</span>`;
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: '❌ ไม่สามารถสมัครรุ่นนี้ได้ (อายุเกินเกณฑ์)',
                    html: `นักกีฬาอายุ <b>${age} ปี</b> เกินเกณฑ์สูงสุดของรุ่น ${categoryName} (กำหนดอายุไม่เกิน <b>${ageMax} ปี</b>)`,
                    confirmButtonColor: '#e11d48'
                });
            }
            return false;
        }

        if (ageStatusEl) {
            ageStatusEl.innerHTML = `<span class="text-emerald-600 font-bold">✅ สามารถลงแข่งขันได้ (${age} ปี)</span>`;
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: '✅ ผ่านเกณฑ์คุณสมบัติ',
                html: `นักกีฬาอายุ <b>${age} ปี</b> สามารถลงสมัครแข่งขันในรุ่น ${categoryName} ได้`,
                confirmButtonColor: '#059669',
                timer: 2500,
                showConfirmButton: false
            });
        }
        return true;
    }

    function initAddMemberFlatpickr() {
        const input = document.getElementById('add-member-birthdate');
        if (!input) return;
        if (input._flatpickr) {
            input._flatpickr.destroy();
        }

        addMemberFp = flatpickr(input, {
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
                    calculateAddMemberAge(selectedDates[0]);
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
                    calculateAddMemberAge(selectedDates[0]);
                }
            }
        });
    }

    function calculateAddMemberAge(birthDate) {
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
            document.getElementById('add-member-age').value = age;
            validateAthleteAge(age, true);
        }
    }

    function toggleAddMemberFields(type) {
        const athleteSec = document.getElementById('section-add-athlete-fields');
        const coachSec   = document.getElementById('section-add-coach-fields');
        const modalTitle = document.getElementById('add-modal-title');
        const modalIcon  = document.getElementById('add-modal-icon');

        const bDateInput = document.getElementById('add-member-birthdate');
        const ageInput   = document.getElementById('add-member-age');
        const gradeInput = document.getElementById('add-member-grade');
        const posInput   = document.getElementById('add-member-position');
        const phoneInput = document.getElementById('add-member-phone');

        if (type === 'coach') {
            modalTitle.textContent = 'เพิ่มผู้ฝึกสอน / ผู้ควบคุมทีม';
            modalIcon.className = 'w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center';
            athleteSec.classList.add('hidden');
            coachSec.classList.remove('hidden');

            if (bDateInput) bDateInput.disabled = true;
            if (ageInput) ageInput.disabled = true;
            if (gradeInput) gradeInput.disabled = true;
            if (posInput) posInput.disabled = false;
            if (phoneInput) phoneInput.disabled = false;
        } else {
            modalTitle.textContent = 'เพิ่มนักกีฬาเข้าทีม';
            modalIcon.className = 'w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center';
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

    function prepareAddMemberSubmit() {
        const type = document.getElementById('add-member-type').value;
        const finalInput = document.getElementById('add-member-jersey-final');
        if (type === 'coach') {
            finalInput.value = document.getElementById('add-member-phone').value || '';
        } else {
            finalInput.value = document.getElementById('add-member-grade').value || '';
        }
    }

    function openAddMemberModal(type = 'athlete') {
        const modal = document.getElementById('modal-add-member');
        if (!modal) return;

        const form = document.getElementById('form-add-member');
        if (form) form.reset();

        const typeEl = document.getElementById('add-member-type');
        if (typeEl) typeEl.value = type;

        const ageStatusEl = document.getElementById('add-member-age-status');
        if (ageStatusEl) ageStatusEl.innerHTML = 'คำนวณอัตโนมัติ';

        toggleAddMemberFields(type);

        try {
            initAddMemberFlatpickr();
            if (addMemberFp) {
                addMemberFp.clear();
            }
        } catch (e) {
            console.warn('Flatpickr init warning:', e);
        }

        modal.classList.remove('hidden');
        if (window.lucide) {
            try { lucide.createIcons(); } catch (e) {}
        }
    }

    function closeAddMemberModal() {
        const modal = document.getElementById('modal-add-member');
        if (modal) modal.classList.add('hidden');
    }

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
            validateAthleteAge(age, false);
        }
    }

    function toggleEditMemberFields(type) {
        const athleteSec = document.getElementById('section-athlete-fields');
        const coachSec   = document.getElementById('section-coach-fields');

        const bDateInput = document.getElementById('edit-member-birthdate');
        const ageInput   = document.getElementById('edit-member-age');
        const gradeInput = document.getElementById('edit-member-grade');
        const posInput   = document.getElementById('edit-member-position');
        const phoneInput = document.getElementById('edit-member-phone');

        if (type === 'coach') {
            if (athleteSec) athleteSec.classList.add('hidden');
            if (coachSec) coachSec.classList.remove('hidden');

            if (bDateInput) bDateInput.disabled = true;
            if (ageInput) ageInput.disabled = true;
            if (gradeInput) gradeInput.disabled = true;
            if (posInput) posInput.disabled = false;
            if (phoneInput) phoneInput.disabled = false;
        } else {
            if (athleteSec) athleteSec.classList.remove('hidden');
            if (coachSec) coachSec.classList.add('hidden');

            if (bDateInput) bDateInput.disabled = false;
            if (ageInput) ageInput.disabled = false;
            if (gradeInput) gradeInput.disabled = false;
            if (posInput) posInput.disabled = true;
            if (phoneInput) phoneInput.disabled = true;
        }
        if (window.lucide) {
            try { lucide.createIcons(); } catch (e) {}
        }
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
        const modal = document.getElementById('modal-edit-team');
        if (modal) modal.classList.remove('hidden');
        if (window.lucide) {
            try { lucide.createIcons(); } catch (e) {}
        }
    }
    function closeEditTeamModal() {
        const modal = document.getElementById('modal-edit-team');
        if (modal) modal.classList.add('hidden');
    }

    function openEditMemberModal(m) {
        const modal = document.getElementById('modal-edit-member');
        if (!modal) return;

        const form = document.getElementById('form-edit-member');
        if (form) form.action = '<?= base_url('staff/sports/teams/member-update') ?>/' + m.member_id;

        const isAthlete = (m.member_type === 'athlete');
        const typeEl = document.getElementById('edit-member-type');
        if (typeEl) typeEl.value = m.member_type || 'athlete';
        toggleEditMemberFields(m.member_type || 'athlete');

        const prefixEl = document.getElementById('edit-member-prefix');
        if (prefixEl) prefixEl.value = m.prefix || 'นาย';

        const fnEl = document.getElementById('edit-member-firstname');
        if (fnEl) fnEl.value = m.first_name || '';

        const lnEl = document.getElementById('edit-member-lastname');
        if (lnEl) lnEl.value = m.last_name || '';

        const posEl = document.getElementById('edit-member-position');
        if (posEl) posEl.value = m.position || 'ผู้ควบคุมทีม';

        const ageEl = document.getElementById('edit-member-age');
        if (ageEl) ageEl.value = m.age || '';

        const ageStatusEl = document.getElementById('edit-member-age-status');
        if (ageStatusEl) ageStatusEl.innerHTML = 'คำนวณอัตโนมัติ';

        const gradeEl = document.getElementById('edit-member-grade');
        const phoneEl = document.getElementById('edit-member-phone');
        if (isAthlete) {
            if (gradeEl) gradeEl.value = m.jersey_number || '';
        } else {
            if (phoneEl) phoneEl.value = m.jersey_number || '';
        }

        try {
            initEditMemberFlatpickr();
            if (editMemberFp) {
                if (m.birth_date && m.birth_date !== '0000-00-00') {
                    editMemberFp.setDate(m.birth_date, true);
                    if (isAthlete && m.age) {
                        validateAthleteAge(parseInt(m.age), false);
                    }
                } else {
                    editMemberFp.clear();
                }
            }
        } catch (e) {
            console.warn('Flatpickr edit warning:', e);
        }

        modal.classList.remove('hidden');
        if (window.lucide) {
            try { lucide.createIcons(); } catch (e) {}
        }
    }

    function closeEditMemberModal() {
        const modal = document.getElementById('modal-edit-member');
        if (modal) modal.classList.add('hidden');
    }

    function confirmDeleteMember(url, name) {
        if (confirm('คุณต้องการลบรายชื่อ "' + name + '" ออกจากทีมหรือไม่?')) {
            window.location.href = url;
        }
    }
</script>
<?= $this->endSection() ?>
