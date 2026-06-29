<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<style>
    .neon-input {
        background: rgba(15, 23, 42, 0.4) !important;
        border: 1px solid rgba(99, 102, 241, 0.2) !important;
        color: #f1f5f9 !important;
        transition: all 0.3s ease;
    }
    .neon-input:focus {
        border-color: #22d3ee !important;
        box-shadow: 0 0 15px rgba(34, 211, 238, 0.35) !important;
        background: rgba(15, 23, 42, 0.75) !important;
        outline: none;
    }
    .hub-card {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }
    .hub-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border: 1px solid transparent;
        border-radius: inherit;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), transparent) border-box;
        -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: destination-out;
        mask-composite: exclude;
        pointer-events: none;
    }
    .hub-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.15) !important;
        border-color: rgba(34, 211, 238, 0.4) !important;
    }
    .hub-icon-box {
        transition: all 0.3s ease;
    }
    .hub-card:hover .hub-icon-box {
        transform: scale(1.1) rotate(3deg);
    }
</style>

<!-- Header Section -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight flex items-center gap-3">
            <i data-lucide="sliders" class="w-8 h-8 text-indigo-400 animate-pulse"></i>
            <span>ศูนย์ควบคุมและการตั้งค่าระบบ</span>
        </h2>
        <p class="text-xs text-slate-500 mt-1 font-medium">จัดการพารามิเตอร์พื้นฐานและเข้าถึงพื้นที่ตั้งค่าเฉพาะส่วนของงานสัปดาห์วิทยาศาสตร์ทั้งหมด</p>
    </div>
</div>

<!-- Main Grid -->
<div class="space-y-6">
    <!-- Top Row: Quick Control Dashboard Form -->
    <div class="glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-850">
        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-4 flex items-center gap-2">
            <i data-lucide="cog" class="w-4 h-4 text-cyan-400"></i>
            <span>ตั้งค่าควบคุมหลักของระบบ (Main Controls)</span>
        </h3>
        
        <form action="<?= base_url('staff/science-week/settings/save') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <?= csrf_field() ?>

            <!-- Countdown Date -->
            <div class="space-y-2 col-span-1 md:col-span-2">
                <label for="countdown_date" class="block text-xs font-bold text-slate-400 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-cyan-400"></i> วันที่จัดงาน (นับถอยหลัง) <span class="text-rose-500">*</span>
                </label>
                <?php 
                $formattedDate = '';
                if (!empty($countdown_date)) {
                    $time = strtotime($countdown_date);
                    if ($time !== false) {
                        $formattedDate = date('Y-m-d\TH:i', $time);
                    }
                }
                ?>
                <input type="datetime-local" name="countdown_date" id="countdown_date" required value="<?= esc($formattedDate) ?>" class="w-full px-4 py-2.5 neon-input rounded-xl text-xs outline-none">
            </div>

            <!-- Active Year -->
            <div class="space-y-2">
                <label for="active_year" class="block text-xs font-bold text-slate-400 flex items-center gap-2">
                    <i data-lucide="hash" class="w-3.5 h-3.5 text-amber-400"></i> ปีการศึกษาที่ใช้งาน <span class="text-rose-500">*</span>
                </label>
                <input type="number" name="active_year" id="active_year" required min="2500" max="3000" value="<?= esc($active_year) ?>" class="w-full px-4 py-2.5 neon-input rounded-xl text-xs outline-none">
            </div>

            <!-- Registration Toggle -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 flex items-center gap-2">
                    <i data-lucide="toggle-left" class="w-3.5 h-3.5 text-emerald-400"></i> สถานะรับสมัครการแข่งขัน
                </label>
                <div class="flex items-center gap-4 py-2 bg-slate-900/40 rounded-xl px-4 border border-slate-800/60 h-[38px]">
                    <label class="flex items-center gap-2 cursor-pointer text-[11px] font-semibold text-slate-300">
                        <input type="radio" name="registration_open" value="1" <?= $registration_open ? 'checked' : '' ?> class="accent-emerald-500 w-3.5 h-3.5">
                        <span class="<?= $registration_open ? 'text-emerald-400 font-bold' : '' ?>">เปิด</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-[11px] font-semibold text-slate-300">
                        <input type="radio" name="registration_open" value="0" <?= !$registration_open ? 'checked' : '' ?> class="accent-rose-500 w-3.5 h-3.5">
                        <span class="<?= !$registration_open ? 'text-rose-400 font-bold' : '' ?>">ปิด</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button (inline) -->
            <div class="col-span-1 md:col-span-4 flex justify-end pt-2 border-t border-slate-800/40">
                <button type="submit" class="px-5 py-2.5 text-white text-xs font-bold rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 hover:from-indigo-600 hover:to-cyan-600 shadow-md transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> บันทึกการตั้งค่าควบคุมหลัก
                </button>
            </div>
        </form>
    </div>

    <!-- Middle Row: 6 Settings Modules Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Module 1: General Settings Overview -->
        <div class="hub-card glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-850 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-start justify-between">
                    <div class="hub-icon-box p-3 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400">
                        <i data-lucide="monitor" class="w-6 h-6"></i>
                    </div>
                    <span class="px-2 py-0.5 text-[9px] bg-cyan-500/10 text-cyan-400 rounded-full font-bold">ระบบแกนหลัก</span>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-700 dark:text-white">ตั้งค่าระบบทั่วไป</h4>
                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">ตั้งค่าปีการศึกษาที่จัดงาน กำหนดเวลานับถอยหลัง และเปิด-ปิดรับสมัครการแข่ง</p>
                </div>
                <div class="py-2 border-y border-slate-800/50 space-y-2 text-[11px]">
                    <div class="flex justify-between">
                        <span class="text-slate-400">ปีการศึกษาที่ใช้งาน:</span>
                        <span class="font-bold text-white"><?= esc($active_year) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">สถานะเปิดรับสมัคร:</span>
                        <span class="font-bold <?= $registration_open ? 'text-emerald-400' : 'text-rose-400' ?>">
                            <?= $registration_open ? 'เปิดระบบรับสมัคร' : 'ปิดระบบรับสมัคร' ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="#countdown_date" onclick="document.getElementById('countdown_date').focus(); return false;" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-850 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-[11px] font-bold">
                    <i data-lucide="focus" class="w-3.5 h-3.5"></i> ไปที่เมนูแก้ไขด่วน
                </a>
            </div>
        </div>

        <!-- Module 2: Certificates Configurations -->
        <div class="hub-card glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-850 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-start justify-between">
                    <div class="hub-icon-box p-3 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400">
                        <i data-lucide="award" class="w-6 h-6"></i>
                    </div>
                    <span class="px-2 py-0.5 text-[9px] bg-amber-500/10 text-amber-400 rounded-full font-bold">เกียรติบัตร</span>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-700 dark:text-white">การออกเกียรติบัตร</h4>
                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">ตั้งค่าไฟล์รูปภาพพื้นหลังพิกัด และขนาดตัวอักษรของใบเกียรติบัตรแต่ละประเภท</p>
                </div>
                <div class="py-2 border-y border-slate-800/50 space-y-1.5 text-[11px]">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">เกียรติบัตรผู้สมัครแข่ง:</span>
                        <span class="font-bold flex items-center gap-1 <?= $cert_comp_configured ? 'text-emerald-400' : 'text-slate-500' ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $cert_comp_configured ? 'bg-emerald-400' : 'bg-slate-500' ?>"></span>
                            <?= $cert_comp_configured ? 'พร้อมใช้งาน' : 'ยังไม่พร้อม' ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">เกียรติบัตรอาจารย์ที่ปรึกษา:</span>
                        <span class="font-bold flex items-center gap-1 <?= $cert_train_configured ? 'text-emerald-400' : 'text-slate-500' ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $cert_train_configured ? 'bg-emerald-400' : 'bg-slate-500' ?>"></span>
                            <?= $cert_train_configured ? 'พร้อมใช้งาน' : 'ยังไม่พร้อม' ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">เกียรติบัตรประเมินผลงาน:</span>
                        <span class="font-bold flex items-center gap-1 <?= $cert_eval_configured ? 'text-emerald-400' : 'text-slate-500' ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $cert_eval_configured ? 'bg-emerald-400' : 'bg-slate-500' ?>"></span>
                            <?= $cert_eval_configured ? 'พร้อมใช้งาน' : 'ยังไม่พร้อม' ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?= base_url('staff/science-week/certificates') ?>" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-850 hover:bg-amber-650 hover:text-white text-slate-300 hover:shadow-lg transition-colors text-[11px] font-bold">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> แก้ไขการตั้งค่าพิกัด & รูปภาพ
                </a>
            </div>
        </div>

        <!-- Module 3: Evaluation Form Builder -->
        <div class="hub-card glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-850 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-start justify-between">
                    <div class="hub-icon-box p-3 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400">
                        <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                    </div>
                    <span class="px-2 py-0.5 text-[9px] bg-purple-500/10 text-purple-400 rounded-full font-bold">ประเมินผล</span>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-700 dark:text-white">โครงสร้างฟอร์มประเมิน</h4>
                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">จัดการข้อมูลคำถาม ความพึงพอใจและฟิลด์ที่บังคับกรอกในการประเมินผลเพื่อรับเกียรติบัตร</p>
                </div>
                <div class="py-2 border-y border-slate-800/50 space-y-2 text-[11px]">
                    <div class="flex justify-between">
                        <span class="text-slate-400">จำนวนฟิลด์กรอกข้อมูล:</span>
                        <span class="font-bold text-white"><?= esc($eval_field_count) ?> ฟิลด์</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">จำนวนข้อคำถามประเมิน:</span>
                        <span class="font-bold text-white"><?= esc($eval_question_count) ?> ข้อ</span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?= base_url('staff/science-week/evaluations/create') ?>" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-850 hover:bg-purple-650 hover:text-white text-slate-300 hover:shadow-lg transition-colors text-[11px] font-bold">
                    <i data-lucide="wrench" class="w-3.5 h-3.5"></i> ปรับแต่งโครงสร้างแบบสอบถาม
                </a>
            </div>
        </div>

        <!-- Module 4: Competitions CRUD Settings -->
        <div class="hub-card glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-850 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-start justify-between">
                    <div class="hub-icon-box p-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                        <i data-lucide="trophy" class="w-6 h-6"></i>
                    </div>
                    <span class="px-2 py-0.5 text-[9px] bg-emerald-500/10 text-emerald-400 rounded-full font-bold">การแข่ง</span>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-700 dark:text-white">ประเภทการแข่งขัน</h4>
                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">เพิ่มประเภทการประกวด/แข่งขัน กำหนดการโควตาสมาชิก โควตาทีม และระดับชั้น</p>
                </div>
                <div class="py-2 border-y border-slate-800/50 space-y-2 text-[11px]">
                    <div class="flex justify-between">
                        <span class="text-slate-400">ปีการศึกษาที่สืบค้น:</span>
                        <span class="font-bold text-white"><?= esc($active_year) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">รายการแข่งขันทั้งหมด:</span>
                        <span class="font-bold text-white"><?= esc($active_comp_count) ?> ประเภทการแข่ง</span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?= base_url('staff/science-week/competitions') ?>" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-850 hover:bg-emerald-650 hover:text-white text-slate-300 hover:shadow-lg transition-colors text-[11px] font-bold">
                    <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> รายการและการรับสมัคร
                </a>
            </div>
        </div>

        <!-- Module 5: Schedules Settings -->
        <div class="hub-card glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-850 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-start justify-between">
                    <div class="hub-icon-box p-3 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400">
                        <i data-lucide="calendar-clock" class="w-6 h-6"></i>
                    </div>
                    <span class="px-2 py-0.5 text-[9px] bg-indigo-500/10 text-indigo-400 rounded-full font-bold">กำหนดการ</span>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-700 dark:text-white">กำหนดการและแผนงาน</h4>
                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">ปรับปรุงแก้ไขกำหนดเวลารับสมัคร วันแข่ง และเวลาปิดส่งเอกสารหลักฐานของปีนี้</p>
                </div>
                <div class="py-2 border-y border-slate-800/50 space-y-2 text-[11px]">
                    <div class="flex justify-between">
                        <span class="text-slate-400">ปีการศึกษาที่จัดงาน:</span>
                        <span class="font-bold text-white"><?= esc($active_year) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">จำนวนไทม์ไลน์กิจกรรม:</span>
                        <span class="font-bold text-white"><?= esc($active_sch_count) ?> ลำดับกิจกรรม</span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?= base_url('staff/science-week/schedules') ?>" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-850 hover:bg-indigo-650 hover:text-white text-slate-300 hover:shadow-lg transition-colors text-[11px] font-bold">
                    <i data-lucide="list" class="w-3.5 h-3.5"></i> แก้ไขกำหนดการจัดกิจกรรม
                </a>
            </div>
        </div>

        <!-- Module 6: Users & Access Settings -->
        <div class="hub-card glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-850 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-start justify-between">
                    <div class="hub-icon-box p-3 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <span class="px-2 py-0.5 text-[9px] bg-rose-500/10 text-rose-400 rounded-full font-bold">เจ้าหน้าที่</span>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-700 dark:text-white">จัดการสิทธิ์เจ้าหน้าที่</h4>
                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">เพิ่ม ลบ หรือแก้ไขบัญชีเจ้าหน้าที่ผู้มีสิทธิ์อนุมัติใบสมัครและการคัดเลือกผลรางวัล</p>
                </div>
                <div class="py-2 border-y border-slate-800/50 space-y-2 text-[11px]">
                    <div class="flex justify-between">
                        <span class="text-slate-400">สิทธิ์ทั้งหมด:</span>
                        <span class="font-bold text-white">วิทยาศาสตร์ (Staff/Admin)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">จำนวนเจ้าหน้าที่ในระบบ:</span>
                        <span class="font-bold text-white"><?= esc($staff_count) ?> คน</span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?= base_url('staff/science-week/users') ?>" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-850 hover:bg-rose-650 hover:text-white text-slate-300 hover:shadow-lg transition-colors text-[11px] font-bold">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> จัดการรายชื่อบัญชีเจ้าหน้าที่
                </a>
            </div>
        </div>

    </div>

    <!-- Bottom Row: Statistics Dashboard by Academic Year -->
    <div class="glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-850">
        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-3 flex items-center gap-2">
            <i data-lucide="bar-chart-2" class="w-4 h-4 text-indigo-400"></i>
            <span>สถิติรวมของระบบแบ่งแยกตามปีการศึกษา (Academic Year Archives)</span>
        </h3>
        <p class="text-[11px] text-slate-500 mb-4">ตารางภาพรวมประวัติข้อมูลที่จัดเก็บอยู่ในระบบแยกตามปีการศึกษาเพื่อความสะดวกในการสลับดูและตรวจสอบข้อมูลใบสมัครย้อนหลัง</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-[10px] uppercase tracking-wider text-slate-400 font-semibold">
                        <th class="py-2.5 px-4">ปีการศึกษา</th>
                        <th class="py-2.5 px-4 text-center">ประเภทการแข่ง</th>
                        <th class="py-2.5 px-4 text-center">ใบสมัครทั้งหมด</th>
                        <th class="py-2.5 px-4 text-center">อนุมัติแล้ว</th>
                        <th class="py-2.5 px-4 text-center">ผู้ประเมิน</th>
                        <th class="py-2.5 px-4 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-850 text-xs text-slate-750 dark:text-slate-300">
                    <?php if (!empty($year_stats)): ?>
                        <?php foreach ($year_stats as $stat): ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-colors">
                                <td class="py-2.5 px-4 font-bold flex items-center gap-2">
                                    <span class="w-2 rounded-full h-2 <?= $stat['year'] == $active_year ? 'bg-emerald-500 animate-pulse' : 'bg-slate-500' ?>"></span>
                                    <span>ปีการศึกษา <?= esc($stat['year']) ?></span>
                                    <?php if ($stat['year'] == $active_year): ?>
                                        <span class="px-1.5 py-0.5 text-[8px] bg-emerald-500/10 text-emerald-400 rounded font-medium">ปัจจุบัน</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2.5 px-4 text-center font-semibold text-cyan-400"><?= esc($stat['competitions']) ?> รายการ</td>
                                <td class="py-2.5 px-4 text-center font-semibold text-amber-400"><?= esc($stat['registrations']) ?> ทีม</td>
                                <td class="py-2.5 px-4 text-center font-semibold text-emerald-400"><?= esc($stat['approved']) ?> ทีม</td>
                                <td class="py-2.5 px-4 text-center font-semibold text-indigo-400"><?= esc($stat['evaluations']) ?> คน</td>
                                <td class="py-2.5 px-4 text-right">
                                    <a href="<?= base_url('staff/science-week?year=' . $stat['year']) ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-850 text-slate-300 hover:bg-indigo-650 hover:text-white transition-colors text-[10px] font-bold">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        เปิดดูปีนี้
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-4 text-center text-slate-500 text-xs">ไม่มีข้อมูลประวัติย้อนหลังในระบบขณะนี้</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
