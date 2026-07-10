<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span>จัดการรายชื่อสมัครแข่งขันสัปดาห์วิทยาศาสตร์</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">จัดการสถานะการยื่นสมัคร และส่งออกรายงานของนักเรียนแต่ละสถาบัน</p>
    </div>
    
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="<?= base_url('science-week/staff/export?' . http_build_query($_GET)) ?>" class="w-full md:w-auto justify-center px-5 py-3 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:text-slate-900 font-bold text-xs sm:text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 shadow-sm">
            <i data-lucide="file-spreadsheet" class="w-4 h-4 text-emerald-600"></i> ส่งออกรายงาน Excel
        </a>
    </div>
</div>

<!-- Dashboard Statistics Grid -->
<?php if (!empty($competition_stats)): ?>
<div class="space-y-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($competition_stats as $stat): ?>
            <div class="glass-card p-5 rounded-3xl border border-slate-800 bg-slate-900/30 flex flex-col justify-between hover:border-slate-750 transition-all duration-300">
                <!-- Comp Title -->
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-3 h-3 rounded-full shrink-0" style="background-color: <?= esc($stat['comp_color']) ?>; box-shadow: 0 0 10px <?= esc($stat['comp_color']) ?>80"></span>
                    <h3 class="text-xs font-black text-white truncate" title="<?= esc($stat['comp_name']) ?>"><?= esc($stat['comp_name']) ?></h3>
                </div>

                <!-- Levels breakdown -->
                <div class="space-y-2">
                    <?php foreach ($stat['levels'] as $lvl): ?>
                        <div class="p-2.5 rounded-2xl bg-slate-950/80 border border-slate-800/80 flex items-center justify-between text-xs gap-3">
                            <span class="font-bold text-slate-350 truncate max-w-[150px]" title="<?= esc($lvl['level_name']) ?>">
                                🎓 <?= esc($lvl['level_name']) ?>
                            </span>
                            <div class="flex items-center gap-4 shrink-0 font-mono">
                                <div class="text-[10px] text-slate-400">
                                    สมัคร: <span class="text-slate-100 font-extrabold"><?= esc($lvl['total']) ?></span>
                                </div>
                                <div class="text-[10px] text-emerald-450 border-l border-slate-800/80 pl-4">
                                    อนุมัติ: <span class="font-extrabold"><?= esc($lvl['approved']) ?></span>
                                </div>
                                <div class="text-[10px] text-amber-400 border-l border-slate-800/80 pl-4">
                                    รอตรวจ: <span class="font-extrabold"><?= esc($lvl['pending']) ?></span>
                                </div>
                                <span class="text-[9px] font-black text-slate-400 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 shrink-0">
                                    โควตา: <?= $lvl['limit'] > 0 ? esc($lvl['limit']) : '∞' ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Admin Guidelines Box -->
<div class="glass-card p-4 rounded-3xl mb-6 bg-blue-50/50 dark:bg-blue-950/10 border border-blue-100 dark:border-blue-900/30 text-xs text-slate-600 dark:text-slate-300 flex items-start gap-3 shadow-sm animate-fade-in">
    <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5 animate-pulse"></i>
    <div>
        <strong class="text-slate-800 dark:text-white text-sm block mb-1">📢 แนวทางการจัดการสิทธิ์ผู้สมัคร (ทีมจริง / ทีมสำรอง)</strong>
        <ul class="list-disc pl-4 space-y-1.5 text-slate-500 dark:text-slate-400 font-medium">
            <li><span class="text-emerald-600 dark:text-emerald-400 font-black">ปุ่มติ๊กถูก (✔️) - อนุมัติเป็นทีมจริง:</span> สำหรับอนุมัติใบสมัครเป็นผู้เข้าแข่งขันทีมจริง (ตามเงื่อนไขโควตาปกติ เช่น ไม่เกิน 4 ทีมแรกของแต่ละสถาบันศึกษา)</li>
            <li><span class="text-blue-600 dark:text-blue-400 font-black">ปุ่มติ๊กถูกกล่อง (☑️) - อนุมัติเป็นทีมสำรอง:</span> สำหรับใช้กับทีมสมัครลำดับถัดไป (เช่น ทีมที่ 5 ขึ้นไปของโรงเรียนเดิม) เพื่อปักธงเป็นทีมสำรองอย่างเป็นระบบ</li>
            <li><span class="text-rose-600 dark:text-rose-400 font-black">ปุ่มกากบาท (❌) - ปฏิเสธสิทธิ์:</span> สำหรับใบสมัครที่กรอกข้อมูลผิดพลาด เอกสารไม่ครบถ้วน หรือทำผิดกฎกติกา</li>
        </ul>
    </div>
</div>

<!-- Filters Card -->
<div class="glass-card p-4 sm:p-6 rounded-3xl mb-6 bg-slate-900/40 dark:bg-slate-900/60">
    <form method="GET" action="<?= base_url('science-week/staff') ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        <!-- Search input -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i data-lucide="search" class="w-4 h-4"></i></span>
            <input type="text" name="search" value="<?= esc($search) ?>" placeholder="ค้นหา รหัส, โรงเรียน, ชื่อทีม, สมาชิก..." class="w-full pl-10 pr-4 py-3 bg-slate-900/60 dark:bg-slate-850 border border-slate-700 dark:border-slate-750 focus:border-blue-500 rounded-2xl text-xs text-slate-200 outline-none transition-colors">
        </div>

        <!-- Competition Select -->
        <select name="competition_type" class="w-full px-4 py-3 bg-slate-900/60 dark:bg-slate-850 border border-slate-700 dark:border-slate-750 focus:border-blue-500 rounded-2xl text-xs text-slate-200 outline-none transition-colors">
            <option value="" class="bg-slate-950 text-slate-350 dark:bg-slate-900 dark:text-slate-200">-- ประเภทการแข่งขันทั้งหมด --</option>
            <?php if (!empty($competitions)): ?>
                <?php foreach ($competitions as $comp): ?>
                    <option value="<?= esc($comp['comp_name']) ?>" <?= $compType_active == $comp['comp_name'] ? 'selected' : '' ?> class="bg-slate-950 text-slate-300 dark:bg-slate-900 dark:text-slate-200"><?= esc($comp['comp_name']) ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <!-- Status Filter -->
        <select name="status" class="w-full px-4 py-3 bg-slate-900/60 dark:bg-slate-850 border border-slate-700 dark:border-slate-750 focus:border-blue-500 rounded-2xl text-xs text-slate-200 outline-none transition-colors">
            <option value="" class="bg-slate-950 text-slate-350 dark:bg-slate-900 dark:text-slate-200">-- กรองสถานะทั้งหมด --</option>
            <option value="pending" <?= $status_active == 'pending' ? 'selected' : '' ?> class="bg-slate-950 text-slate-300 dark:bg-slate-900 dark:text-slate-200">รอตรวจสอบ (Pending)</option>
            <option value="approved" <?= $status_active == 'approved' ? 'selected' : '' ?> class="bg-slate-950 text-slate-300 dark:bg-slate-900 dark:text-slate-200">อนุมัติสิทธิ์แล้ว (ทีมจริง)</option>
            <option value="approved_reserve" <?= $status_active == 'approved_reserve' ? 'selected' : '' ?> class="bg-slate-950 text-slate-300 dark:bg-slate-900 dark:text-slate-200">อนุมัติสิทธิ์แล้ว (ทีมสำรอง)</option>
            <option value="rejected" <?= $status_active == 'rejected' ? 'selected' : '' ?> class="bg-slate-950 text-slate-300 dark:bg-slate-900 dark:text-slate-200">ปฏิเสธ/ไม่ผ่าน (Rejected)</option>
        </select>

        <!-- Submit & Clear Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="flex-1 py-3 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-100 hover:border-blue-600 text-xs font-bold rounded-2xl transition-all flex items-center justify-center gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i> กรองรายการ
            </button>
            <?php if(!empty($search) || !empty($compType_active) || !empty($status_active)): ?>
                <a href="<?= base_url('science-week/staff') ?>" class="p-3 bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 rounded-2xl transition-all flex items-center justify-center" title="ล้างฟิลเตอร์">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- List Table -->
<div class="glass-card rounded-3xl overflow-hidden bg-slate-900/40 dark:bg-slate-900/60 shadow-xl border border-slate-200 dark:border-slate-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">รหัส/ประเภท</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">โรงเรียน / ทีม</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">สมาชิกในทีม</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">คุณครูที่ปรึกษา</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">สถานะตรวจสอบ</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">อนุมัติ</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php if (empty($registrations)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium bg-white dark:bg-transparent">
                            ไม่พบข้อมูลใบสมัครประกวดหรือแข่งขันตามระบุ
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($registrations as $reg): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-black text-cyan-600 dark:text-cyan-400 block font-mono"><?= $reg['reg_code'] ?></span>
                                <span class="text-[10px] text-slate-500 block mt-1 font-bold truncate max-w-[180px]"><?= $reg['reg_competition_type'] ?></span>
                                <?php if (!empty($reg['reg_level'])): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 mt-1"><?= esc($reg['reg_level']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200 block">
                                    <?= esc($reg['reg_school_name']) ?>
                                    <?php if (!empty($reg['reg_school_province'])): ?>
                                        <span class="text-indigo-650 dark:text-indigo-400 font-bold text-[10px]"> (<?= esc($reg['reg_school_province']) ?>)</span>
                                    <?php endif; ?>
                                </span>
                                <span class="text-[10px] text-slate-400 block font-semibold mt-0.5"><?= $reg['reg_team_name'] ? 'ทีม: '.esc($reg['reg_team_name']) : 'ทั่วไป' ?></span>
                                
                                <?php 
                                $customAnswers = [];
                                if (!empty($reg['reg_custom_fields'])) {
                                    $customAnswers = json_decode($reg['reg_custom_fields'], true) ?: [];
                                }
                                if (!empty($customAnswers)):
                                ?>
                                    <div class="mt-2 space-y-1 bg-slate-50 dark:bg-slate-800/40 p-2 rounded-xl border border-slate-100 dark:border-slate-800 text-[10px]">
                                        <?php foreach ($customAnswers as $q => $a): ?>
                                            <div class="truncate max-w-[220px]">
                                                <span class="font-bold text-slate-500 dark:text-slate-400"><?= esc($q) ?>:</span> 
                                                <?php if (empty($a)): ?>
                                                    <span class="text-slate-400 dark:text-slate-500 italic">ไม่ได้ระบุ</span>
                                                <?php elseif (strpos($a, 'uploads/science_week/') === 0): ?>
                                                    <a href="<?= base_url($a) ?>" target="_blank" class="text-indigo-650 dark:text-indigo-400 hover:underline font-bold">ดาวน์โหลดไฟล์</a>
                                                <?php elseif (filter_var($a, FILTER_VALIDATE_URL)): ?>
                                                    <a href="<?= htmlspecialchars($a) ?>" target="_blank" class="text-indigo-650 dark:text-indigo-400 hover:underline font-bold">ลิงก์แนบ</a>
                                                <?php else: ?>
                                                    <span class="text-slate-700 dark:text-slate-300 font-semibold"><?= esc($a) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div class="max-w-[200px]">
                                    <?php $members = json_decode($reg['reg_members'], true) ?: []; ?>
                                    <ul class="space-y-0.5">
                                        <?php foreach ($members as $m): 
                                            $mText = '';
                                            if (is_array($m)) {
                                                $prefix = trim($m['prefix'] ?? '');
                                                $name = trim($m['name'] ?? '');
                                                $mText = ($prefix !== '' ? $prefix . ' ' : '') . $name;
                                                if (!empty($m['custom_fields'])) {
                                                    $cfStr = [];
                                                    foreach ($m['custom_fields'] as $cfKey => $cfVal) {
                                                        if ($cfVal !== '') {
                                                            $cfStr[] = "{$cfKey}: {$cfVal}";
                                                        }
                                                    }
                                                    if (!empty($cfStr)) {
                                                        $mText .= ' (' . implode(', ', $cfStr) . ')';
                                                    }
                                                }
                                            } else {
                                                $mText = $m;
                                            }
                                        ?>
                                            <li class="text-[11px] text-slate-650 dark:text-slate-300 font-medium truncate" title="<?= esc($mText) ?>">• <?= esc($mText) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div class="max-w-[200px]">
                                    <?php $advisors = json_decode($reg['reg_advisors'], true) ?: []; ?>
                                    <ul class="space-y-0.5">
                                        <?php foreach ($advisors as $a): ?>
                                            <li class="text-[11px] text-slate-650 dark:text-slate-300 font-medium truncate">• <?= esc($a) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($reg['reg_status'] === 'approved'): ?>
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/30 text-[9px] font-black uppercase tracking-widest flex items-center gap-1 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> อนุมัติ (ทีมจริง)
                                    </span>
                                <?php elseif ($reg['reg_status'] === 'approved_reserve'): ?>
                                    <span class="px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800/30 text-[9px] font-black uppercase tracking-widest flex items-center gap-1 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> อนุมัติ (ทีมสำรอง)
                                    </span>
                                <?php elseif ($reg['reg_status'] === 'rejected'): ?>
                                    <span class="px-2.5 py-1 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-800/30 text-[9px] font-black uppercase tracking-widest flex items-center gap-1 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> ปฏิเสธ/ไม่ผ่าน
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-650 dark:text-amber-400 border border-amber-100 dark:border-amber-800/30 text-[9px] font-black uppercase tracking-widest flex items-center gap-1 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> รอตรวจสอบ
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="updateRegStatus(<?= $reg['reg_id'] ?>, 'approved')" class="p-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white rounded-lg border border-emerald-100 dark:border-emerald-900 transition-all cursor-pointer" title="อนุมัติเป็นทีมจริง">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="updateRegStatus(<?= $reg['reg_id'] ?>, 'approved_reserve')" class="p-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-lg border border-blue-100 dark:border-blue-900 transition-all cursor-pointer" title="อนุมัติเป็นทีมสำรอง">
                                        <i data-lucide="check-square" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="updateRegStatus(<?= $reg['reg_id'] ?>, 'rejected')" class="p-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-lg border border-rose-100 dark:border-rose-900 transition-all cursor-pointer" title="ปฏิเสธการเข้าร่วม">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="viewRegDetails(<?= $reg['reg_id'] ?>)" class="p-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white rounded-lg border border-indigo-100 dark:border-slate-800 transition-all cursor-pointer" title="ดูข้อมูลทั้งหมด">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    <a href="<?= base_url('science-week/staff/edit/' . $reg['reg_id']) ?>" class="p-1.5 bg-blue-50 hover:bg-blue-650 text-blue-600 hover:text-white rounded-lg border border-blue-100 dark:border-slate-800 transition-all" title="แก้ไขข้อมูลผู้สมัคร">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($registrations)): ?>
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800 flex justify-center">
            <?= $pager->links('default', 'itsupport_pager') ?>
        </div>
    <?php endif; ?>
</div>

<!-- Registration Info Modal -->
<div id="viewRegModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[70] hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="glass-card w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl border border-slate-800 my-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 px-6 py-5 border-b border-slate-800 flex justify-between items-center text-white">
            <div>
                <h3 class="text-base sm:text-lg font-black flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5 text-cyan-400"></i> รายละเอียดใบสมัคร <span id="modal-reg-code" class="text-cyan-400 font-mono"></span>
                </h3>
            </div>
            <button onclick="closeRegModal()" class="text-slate-400 hover:text-white transition-colors cursor-pointer">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto custom-scrollbar text-slate-300 text-sm">
            <!-- Competition Type -->
            <div class="space-y-1">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">ประเภทการแข่งขัน</span>
                <div id="modal-comp-type" class="text-sm font-black text-white"></div>
                <div id="modal-level" class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 mt-1"></div>
            </div>

            <!-- School & Team -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-800/80 pt-4">
                <div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">โรงเรียน / สถาบันศึกษา</span>
                    <div id="modal-school-name" class="font-extrabold text-white"></div>
                </div>
                <div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">ชื่อทีม</span>
                    <div id="modal-team-name" class="font-extrabold text-white"></div>
                </div>
            </div>

            <!-- Members & Advisors -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-800/80 pt-4">
                <div>
                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-wider block mb-2">รายชื่อผู้เข้าแข่งขัน</span>
                    <ol id="modal-members-list" class="list-decimal pl-4 space-y-1 font-semibold text-slate-200"></ol>
                </div>
                <div>
                    <span class="text-[10px] font-black text-purple-400 uppercase tracking-wider block mb-2">อาจารย์ที่ปรึกษา</span>
                    <ol id="modal-advisors-list" class="list-decimal pl-4 space-y-1 font-semibold text-slate-200"></ol>
                </div>
            </div>

            <!-- Contact -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-800/80 pt-4">
                <div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">เบอร์โทรศัพท์</span>
                    <div id="modal-phone" class="font-extrabold text-white font-mono"></div>
                </div>
                <div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">อีเมล</span>
                    <div id="modal-email" class="font-extrabold text-white font-mono"></div>
                </div>
            </div>

            <!-- Custom Fields Answers -->
            <div id="modal-custom-container" class="border-t border-slate-800/80 pt-4 space-y-3 hidden">
                <span class="text-[10px] font-black text-blue-400 uppercase tracking-wider block">ข้อมูลตอบกลับคำถามพิเศษ</span>
                <div id="modal-custom-fields" class="grid grid-cols-1 gap-2.5"></div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-slate-950 border-t border-slate-800 flex justify-end gap-3">
            <button onclick="closeRegModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-bold text-xs rounded-xl transition-all cursor-pointer">
                ปิดหน้าต่าง
            </button>
        </div>
    </div>
</div>

<script>
    // Embed registrations dataset
    const allRegistrations = <?= json_encode($registrations) ?>;

    function viewRegDetails(id) {
        const reg = allRegistrations.find(r => parseInt(r.reg_id) === parseInt(id));
        if (!reg) return;

        // Populate fields
        document.getElementById('modal-reg-code').textContent = '(' + reg.reg_code + ')';
        document.getElementById('modal-comp-type').textContent = reg.reg_competition_type;
        document.getElementById('modal-level').textContent = reg.reg_level || 'ทุกระดับชั้น';
        document.getElementById('modal-school-name').textContent = reg.reg_school_name + (reg.reg_school_province ? ' (' + reg.reg_school_province + ')' : '');
        document.getElementById('modal-team-name').textContent = reg.reg_team_name || 'ทั่วไป (ไม่มีชื่อทีม)';
        document.getElementById('modal-phone').textContent = reg.reg_contact_phone || 'ไม่ได้ระบุ';
        document.getElementById('modal-email').textContent = reg.reg_contact_email || 'ไม่ได้ระบุ';

        // Populate members
        const membersList = document.getElementById('modal-members-list');
        membersList.innerHTML = '';
        const members = JSON.parse(reg.reg_members || '[]');
        members.forEach(m => {
            const li = document.createElement('li');
            let mText = '';
            if (m && typeof m === 'object') {
                const prefix = (m.prefix || '').trim();
                const name = (m.name || '').trim();
                mText = (prefix ? prefix + ' ' : '') + name;
                if (m.custom_fields && Object.keys(m.custom_fields).length > 0) {
                    const cfStr = [];
                    for (const [cfKey, cfVal] of Object.entries(m.custom_fields)) {
                        if (cfVal) {
                            cfStr.push(`${cfKey}: ${cfVal}`);
                        }
                    }
                    if (cfStr.length > 0) {
                        mText += ' (' + cfStr.join(', ') + ')';
                    }
                }
            } else {
                mText = m;
            }
            li.textContent = mText;
            membersList.appendChild(li);
        });

        // Populate advisors
        const advisorsList = document.getElementById('modal-advisors-list');
        advisorsList.innerHTML = '';
        const advisors = JSON.parse(reg.reg_advisors || '[]');
        if (advisors.length > 0) {
            advisors.forEach(a => {
                const li = document.createElement('li');
                li.textContent = a;
                advisorsList.appendChild(li);
            });
        } else {
            advisorsList.innerHTML = '<span class="text-xs text-slate-500 italic font-normal">ไม่มีข้อมูลอาจารย์ที่ปรึกษา</span>';
        }

        // Custom Fields Answers
        const customContainer = document.getElementById('modal-custom-container');
        const customFieldsDiv = document.getElementById('modal-custom-fields');
        customFieldsDiv.innerHTML = '';
        const customFields = JSON.parse(reg.reg_custom_fields || '{}');

        if (Object.keys(customFields).length > 0) {
            customContainer.classList.remove('hidden');
            for (const [key, val] of Object.entries(customFields)) {
                const row = document.createElement('div');
                row.className = 'p-3 rounded-xl border border-slate-800 bg-slate-900/40 text-xs';
                
                let valueHtml = '';
                if (!val) {
                    valueHtml = '<span class="text-slate-500 italic">ไม่ได้ระบุ</span>';
                } else if (typeof val === 'string' && val.startsWith('uploads/science_week/')) {
                    valueHtml = `<a href="<?= base_url() ?>${val}" target="_blank" class="text-indigo-400 hover:underline font-extrabold flex items-center gap-1"><i data-lucide="external-link" class="w-3.5 h-3.5"></i> ดาวน์โหลด/เปิดดูไฟล์แนบ</a>`;
                } else if (typeof val === 'string' && (val.startsWith('http://') || val.startsWith('https://'))) {
                    valueHtml = `<a href="${val}" target="_blank" class="text-indigo-400 hover:underline font-extrabold flex items-center gap-1"><i data-lucide="external-link" class="w-3.5 h-3.5"></i> เปิดลิงก์ภายนอก</a>`;
                } else {
                    valueHtml = `<span class="text-slate-200 font-bold">${val}</span>`;
                }

                row.innerHTML = `
                    <span class="block text-[10px] text-slate-500 font-bold mb-1">${key}</span>
                    <div>${valueHtml}</div>
                `;
                customFieldsDiv.appendChild(row);
            }
        } else {
            customContainer.classList.add('hidden');
        }

        // Open modal
        document.getElementById('viewRegModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeRegModal() {
        document.getElementById('viewRegModal').classList.add('hidden');
    }

    function updateRegStatus(id, newStatus) {
        let actionText = 'ต้องการเปลี่ยนสถานะใบสมัครนี้?';
        let confirmBtnColor = '#3b82f6';
        
        if (newStatus === 'approved') {
            actionText = 'อนุมัติผู้สมัครรายนี้เป็น "ทีมจริง"?';
            confirmBtnColor = '#10b981';
        } else if (newStatus === 'approved_reserve') {
            actionText = 'อนุมัติผู้สมัครรายนี้เป็น "ทีมสำรอง"?';
            confirmBtnColor = '#2563eb';
        } else if (newStatus === 'rejected') {
            actionText = 'ปฏิเสธผู้สมัครรายนี้?';
            confirmBtnColor = '#ef4444';
        }

        Swal.fire({
            title: actionText,
            text: "คุณสามารถกลับมาเปลี่ยนสถานะภายหลังได้ตลอดเวลา",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: confirmBtnColor,
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            background: getSwalColors().bg,
            color: getSwalColors().text,
            customClass: {
                popup: 'glass-card rounded-[2rem]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.showLoading();

                fetch(`<?= base_url('science-week/staff/update-status') ?>/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `status=${newStatus}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'อัปเดตสำเร็จ',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#3b82f6',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#ef4444',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'ล้มเหลว',
                        text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้',
                        background: getSwalColors().bg,
                        color: getSwalColors().text,
                        confirmButtonColor: '#ef4444',
                        customClass: { popup: 'glass-card rounded-[2rem]' }
                    });
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
