<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--single {
        background: rgba(8, 12, 24, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        height: 42px !important;
        border-radius: 12px !important;
        display: flex !important;
        align-items: center !important;
        transition: all 0.3s ease !important;
        padding-left: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #cbd5e1 !important;
        font-size: 13px !important;
        padding-left: 0px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #64748b !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
        right: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #818cf8 transparent transparent transparent !important;
        border-width: 5px 5px 0 5px !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #818cf8 transparent !important;
        border-width: 0 5px 5px 5px !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #6366f1 !important;
    }
    .select2-dropdown {
        background: #090d16 !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        z-index: 99999 !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        background: #020617 !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #cbd5e1 !important;
        border-radius: 8px !important;
        outline: none !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: #6366f1 !important;
        color: white !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: rgba(99, 102, 241, 0.2) !important;
        color: #a5b4fc !important;
    }
    .select2-results__option {
        color: #cbd5e1 !important;
        font-size: 13px !important;
        padding: 8px 12px !important;
    }
</style>
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span>จัดการรายชื่อนักเรียนช่วยงาน (Student Staff)</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">จัดการรายชื่อนักเรียนที่เข้ามาช่วยงานในการแข่งขันต่างๆ โดยสามารถเพิ่ม ลบ หรือแก้ไขข้อมูลรายชื่อได้</p>
    </div>
    
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="<?= base_url('science-week/staff/student-staff/export') ?>?search=<?= urlencode($search ?? '') ?>&competition_type=<?= urlencode($compType_active ?? '') ?>&t=<?= time() ?>" class="w-full md:w-auto justify-center px-5 py-3 rounded-2xl bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:hover:bg-emerald-800/40 text-emerald-700 dark:text-emerald-400 font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 border border-emerald-200 dark:border-emerald-800 shadow-sm">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> ส่งออก Excel
        </a>
        <a href="<?= base_url('science-week/staff/student-staff/print') ?>?search=<?= urlencode($search ?? '') ?>&competition_type=<?= urlencode($compType_active ?? '') ?>&t=<?= time() ?>" target="_blank" class="w-full md:w-auto justify-center px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 border border-slate-200 dark:border-slate-700 shadow-sm">
            <i data-lucide="printer" class="w-4 h-4"></i> พิมพ์ใบลงชื่อ
        </a>
        <button onclick="openAddModal()" class="w-full md:w-auto justify-center px-5 py-3 rounded-2xl bg-gradient-to-r from-violet-500 to-indigo-500 hover:from-violet-600 hover:to-indigo-600 text-white font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 shadow-lg shadow-indigo-950/20">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> เพิ่มรายชื่อ
        </button>
    </div>
</div>

<!-- Dashboard Section -->
<?php 
$total_staff = count($student_staff);
$roles_count = count(array_unique(array_column($student_staff, 'staff_competition_type')));

$m_ton = 0; // ม.ต้น
$m_plai = 0; // ม.ปลาย
$general = 0; // บุคคลทั่วไป

foreach($student_staff as $st) {
    if (preg_match('/ม\.[1-3]/', $st['staff_class'])) {
        $m_ton++;
    } elseif (preg_match('/ม\.[4-6]/', $st['staff_class'])) {
        $m_plai++;
    } elseif (strpos($st['staff_class'], 'บุคคลทั่วไป') !== false) {
        $general++;
    }
}
?>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <!-- Card 1: Total Staff -->
    <div class="glass-card rounded-3xl p-5 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 border border-indigo-500/20 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/20 rounded-full blur-2xl group-hover:bg-indigo-500/30 transition-all"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1">นักเรียน/ผู้ช่วยงานทั้งหมด</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= number_format($total_staff) ?> <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">คน</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 flex items-center justify-center text-indigo-500 border border-indigo-500/30">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>
    </div>
    
    <!-- Card 2: Total Roles/Competitions -->
    <div class="glass-card rounded-3xl p-5 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/20 rounded-full blur-2xl group-hover:bg-emerald-500/30 transition-all"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-1">จำนวนฝ่ายงาน / รายการ</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= number_format($roles_count) ?> <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">ฝ่ายงาน</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-500 border border-emerald-500/30">
                <i data-lucide="briefcase" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Card 3: Junior High -->
    <div class="glass-card rounded-3xl p-5 bg-gradient-to-br from-amber-500/10 to-orange-500/10 border border-amber-500/20 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/20 rounded-full blur-2xl group-hover:bg-amber-500/30 transition-all"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[10px] font-bold text-amber-400 uppercase tracking-widest mb-1">ระดับชั้นม.ต้น (1-3)</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= number_format($m_ton) ?> <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">คน</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-500 border border-amber-500/30">
                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Card 4: Senior High -->
    <div class="glass-card rounded-3xl p-5 bg-gradient-to-br from-rose-500/10 to-pink-500/10 border border-rose-500/20 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-500/20 rounded-full blur-2xl group-hover:bg-rose-500/30 transition-all"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-1">ระดับชั้นม.ปลาย (4-6)</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= number_format($m_plai) ?> <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">คน</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-500/20 flex items-center justify-center text-rose-500 border border-rose-500/30">
                <i data-lucide="book-open" class="w-6 h-6"></i>
            </div>
        </div>
    </div>
    
    <!-- Card 5: General Public -->
    <div class="glass-card rounded-3xl p-5 bg-gradient-to-br from-sky-500/10 to-cyan-500/10 border border-sky-500/20 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-sky-500/20 rounded-full blur-2xl group-hover:bg-sky-500/30 transition-all"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-1">บุคคลทั่วไป</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= number_format($general) ?> <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">คน</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-500/20 flex items-center justify-center text-sky-500 border border-sky-500/30">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="glass-card rounded-3xl p-5 mb-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
    <form method="GET" action="<?= base_url('science-week/staff/student-staff') ?>" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label for="search" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">ค้นหาชื่อ-นามสกุล / ชั้น</label>
            <div class="relative">
                <input type="text" name="search" id="search" value="<?= esc($search) ?>" placeholder="ระบุคำค้นหา..." class="w-full pl-10 pr-4 py-2.5 bg-slate-950/60 border border-slate-850 hover:border-slate-700 focus:border-indigo-500 text-slate-200 text-xs rounded-xl outline-none transition-all">
                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        <div>
            <label for="competition_type" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">กรองตามรายการแข่งขัน / ฝ่ายงาน</label>
            <select name="competition_type" id="competition_type" class="w-full px-4 py-2.5 bg-slate-950/60 border border-slate-850 hover:border-slate-700 focus:border-indigo-500 text-slate-200 text-xs rounded-xl outline-none transition-all cursor-pointer">
                <option value="">-- ทั้งหมดทุกรายการ --</option>
                <optgroup label="รายการแข่งขัน">
                    <?php foreach ($competitions as $c): ?>
                        <option value="<?= esc($c['comp_name']) ?>" <?= $compType_active == $c['comp_name'] ? 'selected' : '' ?>><?= esc($c['comp_name']) ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="ฝ่ายงานส่วนกลาง / ทั่วไป">
                    <?php 
                    $genRoles = [
                        'ฝ่ายงานทั่วไป / ส่วนกลาง',
                        'ฝ่ายลงทะเบียนและประเมินผล',
                        'ฝ่ายสถานที่และโสตทัศนูปกรณ์',
                        'ฝ่ายอาหารและเครื่องดื่ม',
                        'ฝ่ายประชาสัมพันธ์และต้อนรับ',
                        'ฝ่ายจัดนิทรรศการและกิจกรรมพิเศษ'
                    ];
                    foreach ($genRoles as $gr): ?>
                        <option value="<?= $gr ?>" <?= $compType_active == $gr ? 'selected' : '' ?>><?= $gr ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <?php
                // Filter custom roles currently assigned in the database
                $compNames = array_column($competitions, 'comp_name');
                $customDbRoles = array_filter($assigned_roles ?? [], function($role) use ($compNames, $genRoles) {
                    return !in_array($role, $compNames) && !in_array($role, $genRoles) && !empty($role);
                });
                if (!empty($customDbRoles)):
                ?>
                <optgroup label="ฝ่ายงานเพิ่มเติม (พิมพ์ระบุเองในระบบ)">
                    <?php foreach ($customDbRoles as $cdr): ?>
                        <option value="<?= esc($cdr) ?>" <?= $compType_active == $cdr ? 'selected' : '' ?>><?= esc($cdr) ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <?php endif; ?>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-colors flex items-center gap-1.5 shadow-md shadow-indigo-950/20">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i> กรองข้อมูล
            </button>
            <a href="<?= base_url('science-week/staff/student-staff') ?>" class="p-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-100 dark:border-slate-800 text-rose-600 dark:bg-rose-950/10 dark:text-rose-400 rounded-xl transition-all flex items-center justify-center" title="ล้างตัวกรอง">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
            </a>
        </div>
    </form>
</div>

<!-- Table List -->
<div class="glass-card rounded-3xl overflow-hidden bg-white dark:bg-slate-900/60 shadow-xl border border-slate-200 dark:border-slate-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-[60px] text-center">ลำดับ</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">ชื่อ - นามสกุล</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[120px]">ชั้นเรียน</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">รายการแข่งขันที่รับผิดชอบ</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[120px]">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php if (empty($student_staff)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium bg-white dark:bg-transparent">
                            ยังไม่มีข้อมูลนักเรียนช่วยงานในระบบ คลิกปุ่ม "เพิ่มรายชื่อนักเรียนช่วยงาน" เพื่อเพิ่มคนใหม่
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $idx = 1; foreach ($student_staff as $st): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-semibold text-slate-500"><?= $idx++ ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200">
                                    <?= esc($st['staff_prefix']) ?><?= esc($st['staff_firstname']) ?> <?= esc($st['staff_lastname']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-[10px] font-bold">
                                    <?= esc($st['staff_class']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-350"><?= esc($st['staff_competition_type']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('science-week/certificate/download/student_staff/' . $st['staff_id']) ?>" target="_blank" class="p-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white rounded-lg border border-emerald-100 dark:border-slate-800 transition-all flex items-center justify-center" title="ดาวน์โหลดเกเกียรติบัตร">
                                        <i data-lucide="file-badge" class="w-4 h-4"></i>
                                    </a>
                                    <button onclick='openEditModal(<?= json_encode($st) ?>)' class="p-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-lg border border-blue-100 dark:border-slate-800 transition-all" title="แก้ไข">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="deleteStaff(<?= $st['staff_id'] ?>)" class="p-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-lg border border-rose-100 dark:border-slate-800 transition-all" title="ลบ">
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

<!-- Modal Form -->
<div id="staffModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="glass-card rounded-[2rem] p-6 sm:p-8 bg-slate-900 border border-slate-800 w-full max-w-4xl z-10 relative mx-4 max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center mb-6 shrink-0">
            <h3 id="modalTitle" class="text-base sm:text-lg font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                <span id="modalIcon"><i data-lucide="user-plus" class="w-5 h-5"></i></span> <span id="modalTitleText">เพิ่มรายชื่อนักเรียนช่วยงาน</span>
            </h3>
        </div>
        
        <form id="staffForm" onsubmit="saveStaff(event)" class="flex-1 flex flex-col min-h-0">
            <input type="hidden" name="staff_id" id="staff_id">
            
            <div class="space-y-1.5 mb-4 shrink-0">
                <label for="form_competition" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">รายการแข่งขัน / ฝ่ายงานที่รับผิดชอบ *</label>
                <select name="staff_competition_type" id="form_competition" required class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 focus:border-indigo-500 text-slate-200 text-xs sm:text-sm rounded-xl outline-none transition-all cursor-pointer">
                    <option value="" disabled selected>-- เลือกประเภทการแข่งขัน / ฝ่ายงาน --</option>
                    <optgroup label="รายการแข่งขัน">
                        <?php foreach ($competitions as $c): ?>
                            <option value="<?= esc($c['comp_name']) ?>"><?= esc($c['comp_name']) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                    <optgroup label="ฝ่ายงานส่วนกลาง / ทั่วไป">
                        <option value="ฝ่ายงานทั่วไป / ส่วนกลาง">ฝ่ายงานทั่วไป / ส่วนกลาง</option>
                        <option value="ฝ่ายลงทะเบียนและประเมินผล">ฝ่ายลงทะเบียนและประเมินผล</option>
                        <option value="ฝ่ายสถานที่และโสตทัศนูปกรณ์">ฝ่ายสถานที่และโสตทัศนูปกรณ์</option>
                        <option value="ฝ่ายอาหารและเครื่องดื่ม">ฝ่ายอาหารและเครื่องดื่ม</option>
                        <option value="ฝ่ายประชาสัมพันธ์และต้อนรับ">ฝ่ายประชาสัมพันธ์และต้อนรับ</option>
                        <option value="ฝ่ายจัดนิทรรศการและกิจกรรมพิเศษ">ฝ่ายจัดนิทรรศการและกิจกรรมพิเศษ</option>
                    </optgroup>
                    <?php
                    $compNames = array_column($competitions, 'comp_name');
                    $genRoles = [
                        'ฝ่ายงานทั่วไป / ส่วนกลาง',
                        'ฝ่ายลงทะเบียนและประเมินผล',
                        'ฝ่ายสถานที่และโสตทัศนูปกรณ์',
                        'ฝ่ายอาหารและเครื่องดื่ม',
                        'ฝ่ายประชาสัมพันธ์และต้อนรับ',
                        'ฝ่ายจัดนิทรรศการและกิจกรรมพิเศษ'
                    ];
                    $customDbRoles = array_filter($assigned_roles ?? [], function($role) use ($compNames, $genRoles) {
                        return !in_array($role, $compNames) && !in_array($role, $genRoles) && !empty($role);
                    });
                    if (!empty($customDbRoles)):
                    ?>
                    <optgroup label="ฝ่ายงานเพิ่มเติม (เคยระบุในระบบ)">
                        <?php foreach ($customDbRoles as $cdr): ?>
                            <option value="<?= esc($cdr) ?>"><?= esc($cdr) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                    <?php endif; ?>
                    <optgroup label="กำหนดเอง">
                        <option value="custom_role">-- ระบุฝ่ายงาน/รายการแข่งขันอื่นๆ (พิมพ์ระบุเอง) --</option>
                    </optgroup>
                </select>
            </div>

            <div class="space-y-1.5 mb-4 shrink-0 hidden" id="custom_competition_container">
                <label for="form_custom_competition" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">ระบุฝ่ายงาน/รายการแข่งขันเพิ่มเติม *</label>
                <input type="text" id="form_custom_competition" placeholder="ระบุฝ่ายงาน เช่น ฝ่ายงานอาคารสถานที่, ประธานนักเรียน..." class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 focus:border-indigo-500 text-slate-200 text-xs sm:text-sm rounded-xl outline-none transition-all">
            </div>

            <!-- Scrollable container for rows -->
            <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar" id="rows-container">
                <!-- Rows will be injected here by JS -->
            </div>

            <div class="pt-4 flex justify-end gap-3 shrink-0 border-t border-slate-850 mt-4">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 font-bold text-xs rounded-xl transition-colors">ยกเลิก</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-colors flex items-center gap-1.5 shadow-md shadow-indigo-950/20">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let rowIndex = 0;

    $(document).ready(function() {
        $('#form_competition').on('change', function() {
            const val = $(this).val();
            const customContainer = $('#custom_competition_container');
            const customInput = $('#form_custom_competition');
            const selectEl = $('#form_competition');

            if (val === 'custom_role') {
                customContainer.removeClass('hidden');
                customInput.attr('required', true);
                selectEl.removeAttr('name');
                customInput.attr('name', 'staff_competition_type');
            } else {
                customContainer.addClass('hidden');
                customInput.removeAttr('required');
                customInput.val('');
                selectEl.attr('name', 'staff_competition_type');
                customInput.removeAttr('name');
            }
        });
    });

    function addEmptyRow(prefix = 'นาย', firstname = '', lastname = '', classVal = '', hideRemove = false, hideAdd = false) {
        const container = document.getElementById('rows-container');
        const index = rowIndex++;

        // Generate class options
        let classOptionsHTML = `<option value="" disabled ${classVal === '' ? 'selected' : ''}>-- เลือกชั้นเรียน / สถานะ --</option>`;
        classOptionsHTML += `<option value="บุคคลทั่วไป" ${classVal === 'บุคคลทั่วไป' ? 'selected' : ''}>บุคคลทั่วไป</option>`;
        for (let g = 1; g <= 6; g++) {
            for (let r = 1; r <= 6; r++) {
                const cName = `ม.${g}/${r}`;
                classOptionsHTML += `<option value="${cName}" ${classVal === cName ? 'selected' : ''}>${cName}</option>`;
            }
        }

        const rowHTML = `
            <div class="grid grid-cols-12 gap-3 items-end bg-slate-950/30 p-4 rounded-2xl border border-slate-800 relative group" id="row_${index}">
                <div class="col-span-2 space-y-1.5">
                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest">คำนำหน้า *</label>
                    <select name="staff[${index}][prefix]" required class="w-full px-2 py-2.5 bg-slate-950/80 border border-slate-800 focus:border-indigo-500 text-slate-200 text-xs rounded-xl outline-none cursor-pointer">
                        <option value="นาย" ${prefix === 'นาย' ? 'selected' : ''}>นาย</option>
                        <option value="นาง" ${prefix === 'นาง' ? 'selected' : ''}>นาง</option>
                        <option value="นางสาว" ${prefix === 'นางสาว' ? 'selected' : ''}>นางสาว</option>
                        <option value="เด็กชาย" ${prefix === 'เด็กชาย' ? 'selected' : ''}>เด็กชาย</option>
                        <option value="เด็กหญิง" ${prefix === 'เด็กหญิง' ? 'selected' : ''}>เด็กหญิง</option>
                    </select>
                </div>
                <div class="col-span-3 space-y-1.5">
                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest">ชื่อจริง *</label>
                    <input type="text" name="staff[${index}][firstname]" value="${firstname}" required class="w-full px-3 py-2.5 bg-slate-950/80 border border-slate-800 focus:border-indigo-500 text-slate-200 text-xs rounded-xl outline-none">
                </div>
                <div class="col-span-3 space-y-1.5">
                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest">นามสกุล *</label>
                    <input type="text" name="staff[${index}][lastname]" value="${lastname}" required class="w-full px-3 py-2.5 bg-slate-950/80 border border-slate-800 focus:border-indigo-500 text-slate-200 text-xs rounded-xl outline-none">
                </div>
                <div class="col-span-2 space-y-1.5">
                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest">ชั้นเรียน / สถานะ *</label>
                    <select name="staff[${index}][class]" id="class_select_${index}" required class="w-full">
                        ${classOptionsHTML}
                    </select>
                </div>
                <div class="col-span-2 flex justify-center pb-1 gap-1.5">
                    <button type="button" onclick="addEmptyRow()" class="p-2 bg-emerald-500/10 hover:bg-emerald-600 text-emerald-500 hover:text-white rounded-xl border border-emerald-500/20 transition-all ${hideAdd ? 'hidden' : ''}" title="เพิ่มแถวรายชื่อใหม่ต่อท้าย">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </button>
                    <button type="button" onclick="removeRow(${index})" class="p-2 bg-rose-500/10 hover:bg-rose-600 text-rose-500 hover:text-white rounded-xl border border-rose-500/20 transition-all ${hideRemove ? 'invisible pointer-events-none' : ''}" title="ลบแถวนี้">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', rowHTML);
        lucide.createIcons();

        $(`#class_select_${index}`).select2({
            dropdownParent: $('#staffModal')
        });
    }

    function removeRow(index) {
        const row = document.getElementById(`row_${index}`);
        if (row) {
            $(`#class_select_${index}`).select2('destroy');
            row.remove();
        }
    }

    function openAddModal() {
        document.getElementById('modalTitleText').innerText = 'เพิ่มรายชื่อนักเรียนช่วยงาน';
        document.getElementById('modalIcon').innerHTML = '<i data-lucide="user-plus" class="w-5 h-5"></i>';
        document.getElementById('staff_id').value = '';
        $('#form_competition').val('').trigger('change');
        document.getElementById('rows-container').innerHTML = '';
        
        rowIndex = 0;
        addEmptyRow('นาย', '', '', '', true, false); // first row hide remove, show add
        
        document.getElementById('staffModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function openEditModal(st) {
        document.getElementById('modalTitleText').innerText = 'แก้ไขรายชื่อนักเรียนช่วยงาน';
        document.getElementById('modalIcon').innerHTML = '<i data-lucide="user-cog" class="w-5 h-5"></i>';
        document.getElementById('staff_id').value = st.staff_id;
        
        // Check if the competition type exists in the dropdown list
        const compSelect = document.getElementById('form_competition');
        let exists = false;
        for (let i = 0; i < compSelect.options.length; i++) {
            if (compSelect.options[i].value === st.staff_competition_type) {
                exists = true;
                break;
            }
        }

        if (exists) {
            $('#form_competition').val(st.staff_competition_type).trigger('change');
        } else {
            $('#form_competition').val('custom_role').trigger('change');
            $('#form_custom_competition').val(st.staff_competition_type);
        }

        document.getElementById('rows-container').innerHTML = '';
        
        rowIndex = 0;
        addEmptyRow(st.staff_prefix, st.staff_firstname, st.staff_lastname, st.staff_class, true, true); // edit mode hide remove, hide add
        
        document.getElementById('staffModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeModal() {
        document.getElementById('staffModal').classList.add('hidden');
    }

    function saveStaff(e) {
        e.preventDefault();
        
        const id = document.getElementById('staff_id').value;
        const url = id ? `<?= base_url('science-week/staff/student-staff/update') ?>/${id}` : `<?= base_url('science-week/staff/student-staff/store') ?>`;
        
        const formData = new FormData(document.getElementById('staffForm'));
        
        Swal.showLoading();
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: data.message,
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#6366f1',
                    customClass: { popup: 'glass-card rounded-[2rem]' }
                }).then(() => {
                    closeModal();
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

    function deleteStaff(id) {
        Swal.fire({
            title: 'ยืนยันการลบข้อมูล?',
            text: 'คุณกำลังจะลบรายชื่อนักเรียนช่วยงานนี้ การดำเนินการนี้ไม่สามารถย้อนกลับได้!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ยืนยันการลบ',
            cancelButtonText: 'ยกเลิก',
            background: getSwalColors().bg,
            color: getSwalColors().text,
            customClass: {
                popup: 'glass-card rounded-[2rem]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();

                fetch(`<?= base_url('science-week/staff/student-staff/delete') ?>/${id}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบสำเร็จ',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#6366f1',
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
