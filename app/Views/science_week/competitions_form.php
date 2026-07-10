<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<!-- Flatpickr CSS/JS for Thai Calendar (BE) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<style>
    .neon-input {
        background: rgba(15, 23, 42, 0.7) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        color: #f1f5f9 !important;
        transition: all 0.3s ease;
    }
    .neon-input:focus {
        border-color: #22d3ee !important;
        box-shadow: 0 0 15px rgba(34, 211, 238, 0.3) !important;
        background: rgba(15, 23, 42, 0.9) !important;
        outline: none;
    }
    .neon-input option {
        background-color: #0f172a;
        color: #f1f5f9;
    }
</style>

<!-- Header Section -->
<div class="mb-6">
    <a href="<?= base_url('science-week/staff/competitions') ?>" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-semibold transition-colors text-sm mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปหน้ารายการ
    </a>
    <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow">
        <span><?= !empty($comp) ? 'แก้ไขประเภทการแข่งขัน' : 'เพิ่มประเภทการแข่งขัน' ?></span>
    </h2>
    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">ระบุรายละเอียดข้อมูลการแข่งขันเพื่ออัปเดตลงฐานข้อมูล</p>
</div>

<!-- Form Layout -->
<form action="<?= !empty($comp) ? base_url('science-week/staff/competitions/update/' . $comp['comp_id']) : base_url('science-week/staff/competitions/store') ?>" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start w-full" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Left Column: Main Details & Level Quotas -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Main Details Card -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 space-y-6">
            <h3 class="text-base sm:text-lg font-extrabold text-cyan-400 flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-3">
                <i data-lucide="info" class="w-5 h-5"></i> รายละเอียดหลักของการแข่งขัน
            </h3>

            <!-- Competition Name -->
            <div class="space-y-2">
                <label for="comp_name" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i data-lucide="award" class="w-5 h-5 text-cyan-400"></i> ชื่อประเภทการแข่งขัน <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="comp_name" id="comp_name" required value="<?= old('comp_name', $comp['comp_name'] ?? '') ?>" placeholder="เช่น การแข่งขันเขียนโปรแกรมควบคุม..." class="w-full px-4 py-3.5 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors">
            </div>

            <!-- Row: Icon and Theme Color -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Icon -->
                <div class="space-y-2">
                    <label for="comp_icon" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <i data-lucide="orbit" class="w-5 h-5 text-purple-400"></i> ไอคอนการแข่งขัน <span class="text-rose-500">*</span>
                    </label>
                    <select name="comp_icon" id="comp_icon" class="w-full px-4 py-3.5 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors">
                        <?php 
                        $icons = ['award', 'rocket', 'target', 'atom', 'cpu', 'lightbulb', 'palette', 'bot', 'sparkles', 'globe', 'flask-conical', 'help-circle'];
                        $currentIcon = old('comp_icon', $comp['comp_icon'] ?? 'award');
                        foreach ($icons as $icon):
                        ?>
                            <option value="<?= $icon ?>" <?= $currentIcon == $icon ? 'selected' : '' ?>><?= $icon ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mt-1">เลือกสัญลักษณ์ไอคอนที่ตรงกับประเภทกิจกรรม</span>
                </div>

                <!-- Theme Color -->
                <div class="space-y-2">
                    <label for="comp_color" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <i data-lucide="palette" class="w-5 h-5 text-pink-400"></i> สีธีมตกแต่งป้าย <span class="text-rose-500">*</span>
                    </label>
                    <select name="comp_color" id="comp_color" class="w-full px-4 py-3.5 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors">
                        <?php 
                        $colors = [
                            'cyan'    => 'Cyan (ฟ้าสว่าง)',
                            'purple'  => 'Purple (ม่วง)',
                            'indigo'  => 'Indigo (น้ำเงินคราม)',
                            'pink'    => 'Pink (ชมพูหวาน)',
                            'amber'   => 'Amber (ส้มเหลือง)',
                            'emerald' => 'Emerald (เขียวมรกต)'
                        ];
                        $currentColor = old('comp_color', $comp['comp_color'] ?? 'cyan');
                        foreach ($colors as $key => $label):
                        ?>
                            <option value="<?= $key ?>" <?= $currentColor == $key ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mt-1">สีที่แสดงบนหน้าเว็บหลักและบัตรคิวการแข่งขัน</span>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-2">
                <label for="comp_description" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i data-lucide="align-left" class="w-5 h-5 text-amber-400"></i> คำอธิบายย่อสำหรับการ์ดโชว์หน้าเว็บ
                </label>
                <textarea name="comp_description" id="comp_description" rows="4" placeholder="ท้าทายจินตนาการความสามารถด้วย..." class="w-full px-4 py-3.5 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors resize-none"><?= old('comp_description', $comp['comp_description'] ?? '') ?></textarea>
            </div>

            <!-- Banner Image Attachment -->
            <div class="space-y-2">
                <label for="comp_banner" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i data-lucide="image" class="w-5 h-5 text-cyan-400"></i> อัปโหลดรูปภาพแบนเนอร์กิจกรรม (แนะนำสัดส่วน 16:9 หรือ 2:1)
                </label>
                <input type="file" name="comp_banner" id="comp_banner" class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20" accept="image/*">
                <?php if (!empty($comp['comp_banner'])): ?>
                    <div class="space-y-3 mt-2 bg-indigo-950/20 p-4 rounded-2xl border border-indigo-500/10">
                        <div class="flex items-center justify-between text-xs sm:text-sm">
                            <span class="text-slate-300 font-bold flex items-center gap-1.5">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-450"></i> 
                                รูปแบนเนอร์ปัจจุบัน:
                            </span>
                            <label class="flex items-center gap-1.5 text-rose-455 font-black cursor-pointer select-none">
                                <input type="checkbox" name="delete_banner" value="1" class="rounded border-rose-500/30 text-rose-500 focus:ring-rose-500 bg-slate-900">
                                ลบรูปแบนเนอร์เดิม
                            </label>
                        </div>
                        <div class="w-full max-w-xs rounded-xl overflow-hidden border border-slate-700/50">
                            <img src="<?= base_url($comp['comp_banner']) ?>" alt="Banner Preview" class="w-full h-auto object-cover">
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Rule File Attachment -->
            <div class="space-y-2">
                <label for="comp_rule_file" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5 text-emerald-400"></i> แนบไฟล์กติกาการแข่งขัน (.pdf, .doc, .docx, .zip)
                </label>
                <input type="file" name="comp_rule_file" id="comp_rule_file" class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20">
                <?php if (!empty($comp['comp_rule_file'])): ?>
                    <div class="flex items-center gap-4 text-xs sm:text-sm mt-2 bg-indigo-950/20 p-3 rounded-xl border border-indigo-500/10">
                        <span class="text-slate-300 font-bold flex items-center gap-1.5">
                            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-450"></i> 
                            ไฟล์ปัจจุบัน: <a href="<?= base_url($comp['comp_rule_file']) ?>" target="_blank" class="text-indigo-400 hover:underline font-extrabold">ดาวน์โหลดไฟล์กติกา</a>
                        </span>
                        <label class="flex items-center gap-1.5 text-rose-400 font-black cursor-pointer select-none">
                            <input type="checkbox" name="delete_rule_file" value="1" class="rounded border-rose-500/30 text-rose-500 focus:ring-rose-500 bg-slate-900">
                            ลบไฟล์กติกาเดิม
                        </label>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Rule Link URL -->
            <div class="space-y-2">
                <label for="comp_rule_link" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i data-lucide="link" class="w-5 h-5 text-sky-400"></i> ลิงก์รายละเอียดกติกาภายนอก (เช่น Google Drive, Canva)
                </label>
                <input type="url" name="comp_rule_link" id="comp_rule_link" value="<?= old('comp_rule_link', $comp['comp_rule_link'] ?? '') ?>" placeholder="https://drive.google.com/..." class="w-full px-4 py-3.5 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors">
            </div>
        </div>

        <!-- Levels and Quotas Card (Moved to Left Side) -->
        <div class="glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 space-y-4">
            <?php
            $levelLimits = [];
            if (!empty($comp['comp_level_limits'])) {
                $levelLimits = json_decode($comp['comp_level_limits'], true) ?: [];
            }
            ?>
            <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-base font-extrabold text-indigo-400 flex items-center gap-2">
                    <i data-lucide="graduation-cap" class="w-5 h-5"></i> ระดับชั้น & โควตารวม
                </h3>
                <button type="button" id="add-level-limit-btn" class="px-2.5 py-1.5 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 font-black rounded-xl text-xs flex items-center gap-1 transition-all">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> เพิ่มชั้น
                </button>
            </div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">ระบุโควตาทีมสูงสุดที่รับสมัครต่อระดับชั้น (0 = ไม่จำกัด)</p>
            
            <div id="level-limits-container" class="space-y-3">
                <!-- Dynamic level rows will go here -->
            </div>
            
            <!-- Hidden input to store textual list of levels for backward compatibility -->
            <input type="hidden" name="comp_level" id="comp_level" value="<?= old('comp_level', $comp['comp_level'] ?? '') ?>">
        </div>
    </div>

    <!-- Right Column: Settings & Submit -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Settings & Open Status Card -->
        <div class="glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 space-y-5">
            <h3 class="text-base sm:text-lg font-extrabold text-cyan-400 flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-3">
                <i data-lucide="settings" class="w-5 h-5"></i> ตั้งค่าการควบคุมระบบ
            </h3>

            <!-- Registration Date/Time Limits -->
            <?php 
            $openTime = '';
            if (!empty($comp['comp_open_time'])) {
                $openTime = date('Y-m-d H:i:s', strtotime($comp['comp_open_time']));
            }
            $closeTime = '';
            if (!empty($comp['comp_close_time'])) {
                $closeTime = date('Y-m-d H:i:s', strtotime($comp['comp_close_time']));
            }
            ?>
            <div class="grid grid-cols-1 gap-4">
                <!-- Open Time -->
                <div class="space-y-2">
                    <label for="comp_open_time" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <i data-lucide="calendar" class="w-5 h-5 text-emerald-400"></i> วันเวลาเริ่มรับสมัคร (พ.ศ.)
                    </label>
                    <input type="text" name="comp_open_time" id="comp_open_time" value="<?= old('comp_open_time', $openTime) ?>" class="datetimepicker-be w-full px-4 py-3.5 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors" placeholder="เลือกวันเวลาเริ่มรับสมัคร...">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mt-1">ระบบจะเริ่มเปิดให้ปุ่มสมัครกดได้เมื่อถึงเวลาที่กำหนด</span>
                </div>

                <!-- Close Time -->
                <div class="space-y-2">
                    <label for="comp_close_time" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <i data-lucide="calendar-x" class="w-5 h-5 text-rose-455"></i> วันเวลาสิ้นสุดรับสมัคร (พ.ศ.)
                    </label>
                    <input type="text" name="comp_close_time" id="comp_close_time" value="<?= old('comp_close_time', $closeTime) ?>" class="datetimepicker-be w-full px-4 py-3.5 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors" placeholder="เลือกวันเวลาสิ้นสุดรับสมัคร...">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mt-1">ระบุเวลาหมดเขตรับสมัครของรายการนี้</span>
                </div>
            </div>

            <!-- Manual Status Override -->
            <div class="space-y-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                <label for="comp_status" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i data-lucide="toggle-left" class="w-5 h-5 text-indigo-400"></i> บังคับปิดรับสมัคร (Override Status) <span class="text-rose-500">*</span>
                </label>
                <select name="comp_status" id="comp_status" required class="w-full px-4 py-3.5 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors">
                    <?php $currentStatus = old('comp_status', $comp['comp_status'] ?? 'open'); ?>
                    <option value="open" <?= $currentStatus === 'open' ? 'selected' : '' ?>>🟢 ให้งานเป็นไปตามช่วงเวลาปกติ</option>
                    <option value="closed" <?= $currentStatus === 'closed' ? 'selected' : '' ?>>🔴 บังคับปิดระบบทันที (แมนนวล)</option>
                </select>
            </div>

            <!-- Limit Members per Team -->
            <div class="space-y-2">
                <label for="comp_member_limit" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i data-lucide="user-check" class="w-5 h-5 text-emerald-400"></i> จำนวนสมาชิกสูงสุดต่อทีม
                </label>
                <input type="number" name="comp_member_limit" id="comp_member_limit" min="0" value="<?= old('comp_member_limit', $comp['comp_member_limit'] ?? 0) ?>" placeholder="ระบุจำนวน เช่น 3 (ระบุ 0 หากไม่จำกัด)" class="w-full px-4 py-3.5 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mt-1">กำหนดว่า 1 ทีมสามารถใส่รายชื่อสมาชิกได้กี่คน (0 = ไม่จำกัด)</span>
            </div>

            <!-- Contact Group Link -->
            <div class="space-y-2">
                <label for="comp_group_link" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i data-lucide="message-square" class="w-5 h-5 text-emerald-500"></i> ลิงก์กลุ่มผู้ประสานงาน (Line OpenChat)
                </label>
                <input type="url" name="comp_group_link" id="comp_group_link" value="<?= old('comp_group_link', $comp['comp_group_link'] ?? '') ?>" placeholder="https://line.me/ti/g/..." class="w-full px-4 py-3.5 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors">
            </div>

            <!-- Contact Group QR Code -->
            <div class="space-y-2">
                <label for="comp_group_qr" class="block text-sm sm:text-base font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i data-lucide="qr-code" class="w-5 h-5 text-cyan-400"></i> อัปโหลดรูป QR Code กลุ่มไลน์
                </label>
                <input type="file" name="comp_group_qr" id="comp_group_qr" class="w-full px-4 py-2.5 neon-input rounded-2xl text-xs sm:text-sm font-bold outline-none transition-colors file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20" accept="image/*">
                <?php if (!empty($comp['comp_group_qr'])): ?>
                    <div class="flex items-center gap-4 text-xs sm:text-sm mt-2 bg-indigo-950/20 p-3 rounded-xl border border-indigo-500/10">
                        <span class="text-slate-300 font-bold flex items-center gap-1.5">
                            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-455"></i> 
                            มี QR Code เดิมแล้ว: <a href="<?= base_url($comp['comp_group_qr']) ?>" target="_blank" class="text-indigo-400 hover:underline font-extrabold">ดูรูปภาพ</a>
                        </span>
                        <label class="flex items-center gap-1.5 text-rose-455 font-black cursor-pointer select-none">
                            <input type="checkbox" name="delete_group_qr" value="1" class="rounded border-rose-500/30 text-rose-500 focus:ring-rose-500 bg-slate-900">
                            ลบ QR Code
                        </label>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Custom Fields Card -->
        <div class="glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 space-y-4">
            <?php
            $customFields = [];
            if (!empty($comp['comp_custom_fields'])) {
                $customFields = json_decode($comp['comp_custom_fields'], true) ?: [];
            }
            ?>
            <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-base font-extrabold text-purple-400 flex items-center gap-2">
                    <i data-lucide="list-plus" class="w-5 h-5"></i> ฟิลด์ข้อคำถามเพิ่มเติม (สำหรับทีม)
                </h3>
                <button type="button" id="add-field-btn" class="px-2.5 py-1.5 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 font-black rounded-xl text-xs flex items-center gap-1 transition-all">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> เพิ่มข้อคำถาม
                </button>
            </div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">ใช้ถามคำถามเฉพาะสำหรับการแข่งขันนี้ (เช่น ลิงก์คลิปวิดีโอ, แนบสลิป/เอกสารทีม)</p>
            
            <div id="fields-container" class="space-y-4">
                <!-- Dynamic custom fields config items will go here -->
            </div>
        </div>

        <!-- Member Custom Fields Card -->
        <div class="glass-card rounded-3xl p-6 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 space-y-4">
            <?php
            $memberCustomFields = [];
            if (!empty($comp['comp_member_custom_fields'])) {
                $memberCustomFields = json_decode($comp['comp_member_custom_fields'], true) ?: [];
            }
            ?>
            <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-base font-extrabold text-cyan-400 flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-5 h-5"></i> ฟิลด์ข้อมูลสมาชิกเพิ่มเติม (ต่อรายบุคคล)
                </h3>
                <button type="button" id="add-member-field-btn" class="px-2.5 py-1.5 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 font-black rounded-xl text-xs flex items-center gap-1 transition-all">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> เพิ่มฟิลด์สมาชิก
                </button>
            </div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">ใช้ถามข้อมูลผู้เข้าแข่งขันแต่ละรายบุคคล (เช่น ขนาดเสื้อ, ชั้นเรียน, เลขบัตรประชาชน, เบอร์โทร)</p>
            
            <div id="member-fields-container" class="space-y-4">
                <!-- Dynamic member custom fields config items will go here -->
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-4 text-white font-extrabold rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 shadow-lg shadow-indigo-950/20 hover:shadow-cyan-500/10 transition-all duration-300 flex items-center justify-center gap-2 text-sm sm:text-base">
            <i data-lucide="save" class="w-5 h-5"></i> บันทึกข้อมูลประเภทการแข่งขัน
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Level Limits Management ---
    const levelContainer = document.getElementById('level-limits-container');
    const addLevelBtn = document.getElementById('add-level-limit-btn');
    const compLevelInput = document.getElementById('comp_level');
    const existingLevels = <?= json_encode($levelLimits) ?>;
    let levelCount = 0;

    function addLevelRow(data = null) {
        levelCount++;
        const id = 'lvl_' + levelCount;
        
        const row = document.createElement('div');
        row.className = 'flex items-center gap-3 p-3 rounded-2xl bg-slate-900/40 border border-slate-800 level-row';
        row.dataset.id = id;
        
        const nameVal = data ? data.level : '';
        const limitVal = data ? (data.limit !== undefined ? data.limit : 0) : 0;
        
        row.innerHTML = `
            <div class="flex-1 space-y-1">
                <input type="text" name="level_limits[${id}][level]" required value="${escapeHtml(nameVal)}" oninput="syncCompLevelText()" placeholder="เช่น มัธยมศึกษาตอนต้น" class="level-name-input w-full px-3 py-2.5 neon-input rounded-xl text-xs outline-none">
            </div>
            <div class="w-[140px] space-y-1">
                <input type="number" name="level_limits[${id}][limit]" min="0" required value="${limitVal}" placeholder="โควตา (0 = ไม่จำกัด)" class="w-full px-3 py-2.5 neon-input rounded-xl text-xs outline-none">
            </div>
            <button type="button" class="remove-level-btn p-2.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-xl transition-all" title="ลบระดับชั้น">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        `;
        
        levelContainer.appendChild(row);
        lucide.createIcons();
        
        row.querySelector('.remove-level-btn').addEventListener('click', () => {
            row.remove();
            syncCompLevelText();
        });
        
        syncCompLevelText();
    }

    function syncCompLevelText() {
        const names = Array.from(document.querySelectorAll('.level-name-input'))
            .map(input => input.value.trim())
            .filter(v => v !== '');
        compLevelInput.value = names.join(', ');
    }

    if (existingLevels && existingLevels.length > 0) {
        existingLevels.forEach(lvl => addLevelRow(lvl));
    } else {
        addLevelRow({ level: 'ทุกระดับชั้น', limit: 0 });
    }

    addLevelBtn.addEventListener('click', () => addLevelRow());

    // --- Custom Fields Management ---
    const container = document.getElementById('fields-container');
    const addBtn = document.getElementById('add-field-btn');
    
    // Existing fields
    const existingFields = <?= json_encode($customFields) ?>;
    
    let fieldCount = 0;
    
    function addFieldRow(data = null) {
        fieldCount++;
        const id = 'cf_' + fieldCount;
        
        const row = document.createElement('div');
        row.className = 'p-4 rounded-2xl bg-slate-900/40 border border-slate-800 space-y-3 relative field-row';
        row.dataset.id = id;
        
        const labelVal = data ? data.label : '';
        const typeVal = data ? data.type : 'text';
        const optionsVal = data ? (data.options || '') : '';
        const requiredVal = data ? (data.required === true || data.required === '1' || data.required === 'true' || data.required === 1) : false;
        
        row.innerHTML = `
            <div class="flex justify-between items-start gap-4">
                <!-- Field Label/Name -->
                <div class="flex-1 space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase font-mono">ชื่อฟิลด์คำถาม / Label *</label>
                    <input type="text" name="custom_fields[${id}][label]" required value="${escapeHtml(labelVal)}" placeholder="เช่น ขนาดเสื้อ หรือ ลิงก์คลิปนำเสนอ" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                </div>
                
                <!-- Field Type -->
                <div class="w-[140px] space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase font-mono">ประเภทข้อมูล</label>
                    <select name="custom_fields[${id}][type]" onchange="toggleOptions(this, '${id}')" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                        <option value="text" ${typeVal === 'text' ? 'selected' : ''}>ข้อความสั้น (Text)</option>
                        <option value="textarea" ${typeVal === 'textarea' ? 'selected' : ''}>ข้อความยาว (Textarea)</option>
                        <option value="select" ${typeVal === 'select' ? 'selected' : ''}>ตัวเลือก (Select)</option>
                        <option value="file" ${typeVal === 'file' ? 'selected' : ''}>แนบไฟล์/รูป (File)</option>
                        <option value="url" ${typeVal === 'url' ? 'selected' : ''}>ระบุลิงก์ (URL)</option>
                    </select>
                </div>

                <!-- Delete button -->
                <button type="button" onclick="this.closest('.field-row').remove()" class="mt-5 p-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-xl transition-all" title="ลบฟิลด์นี้">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
            
            <!-- Extra Options -->
            <div class="options-container space-y-1 ${typeVal === 'select' ? '' : 'hidden'}" id="opts_${id}">
                <label class="block text-[10px] font-bold text-slate-400 uppercase font-mono">ตัวเลือกย่อย (แยกด้วยเครื่องหมายจุลภาค , ) *</label>
                <input type="text" name="custom_fields[${id}][options]" value="${escapeHtml(optionsVal)}" placeholder="เช่น S, M, L, XL" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none" ${typeVal === 'select' ? 'required' : ''}>
            </div>

            <!-- Required Toggle -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="custom_fields[${id}][required]" value="1" id="req_${id}" ${requiredVal ? 'checked' : ''} class="rounded border-slate-700/60 bg-slate-900 text-indigo-500 focus:ring-indigo-500">
                <label for="req_${id}" class="text-[10px] font-bold text-slate-350 select-none cursor-pointer">บังคับกรอก (Required)</label>
            </div>
        `;
        
        container.appendChild(row);
        lucide.createIcons();
    }
    
    // Toggle options field visibility for select
    window.toggleOptions = function(select, id) {
        const optsDiv = document.getElementById('opts_' + id);
        if (select.value === 'select') {
            optsDiv.classList.remove('hidden');
            optsDiv.querySelector('input').required = true;
        } else {
            optsDiv.classList.add('hidden');
            optsDiv.querySelector('input').required = false;
        }
    };
    
    function escapeHtml(text) {
        if (!text) return '';
        return text
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    
    // Load existing
    if (existingFields) {
        const fieldsArr = Array.isArray(existingFields) ? existingFields : Object.values(existingFields);
        fieldsArr.forEach(field => {
            addFieldRow(field);
        });
    }

    addBtn.addEventListener('click', () => {
        addFieldRow();
    });

    // --- Member Custom Fields Management ---
    const memberContainer = document.getElementById('member-fields-container');
    const addMemberFieldBtn = document.getElementById('add-member-field-btn');
    const existingMemberFields = <?= json_encode($memberCustomFields) ?>;
    let memberFieldCount = 0;

    function addMemberFieldRow(data = null) {
        memberFieldCount++;
        const id = 'mcf_' + memberFieldCount;
        
        const row = document.createElement('div');
        row.className = 'p-4 rounded-2xl bg-slate-900/40 border border-slate-800 space-y-3 relative member-field-row';
        row.dataset.id = id;
        
        const labelVal = data ? data.label : '';
        const typeVal = data ? data.type : 'text';
        const optionsVal = data ? (data.options || '') : '';
        const requiredVal = data ? (data.required === true || data.required === '1' || data.required === 'true' || data.required === 1) : false;
        
        row.innerHTML = `
            <div class="flex justify-between items-start gap-4">
                <!-- Field Label/Name -->
                <div class="flex-1 space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase font-mono">ชื่อฟิลด์สมาชิก / Label *</label>
                    <input type="text" name="member_custom_fields[${id}][label]" required value="${escapeHtml(labelVal)}" placeholder="เช่น ขนาดเสื้อ หรือ ห้องเรียน" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                </div>
                
                <!-- Field Type -->
                <div class="w-[140px] space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase font-mono">ประเภทข้อมูล</label>
                    <select name="member_custom_fields[${id}][type]" onchange="toggleMemberOptions(this, '${id}')" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                        <option value="text" ${typeVal === 'text' ? 'selected' : ''}>ข้อความสั้น (Text)</option>
                        <option value="textarea" ${typeVal === 'textarea' ? 'selected' : ''}>ข้อความยาว (Textarea)</option>
                        <option value="select" ${typeVal === 'select' ? 'selected' : ''}>ตัวเลือก (Select)</option>
                        <option value="url" ${typeVal === 'url' ? 'selected' : ''}>ระบุลิงก์ (URL)</option>
                    </select>
                </div>

                <!-- Delete button -->
                <button type="button" onclick="this.closest('.member-field-row').remove()" class="mt-5 p-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-xl transition-all" title="ลบฟิลด์นี้">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
            
            <!-- Extra Options -->
            <div class="options-container space-y-1 ${typeVal === 'select' ? '' : 'hidden'}" id="mopts_${id}">
                <label class="block text-[10px] font-bold text-slate-400 uppercase font-mono">ตัวเลือกย่อย (แยกด้วยเครื่องหมายจุลภาค , ) *</label>
                <input type="text" name="member_custom_fields[${id}][options]" value="${escapeHtml(optionsVal)}" placeholder="เช่น S, M, L, XL" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none" ${typeVal === 'select' ? 'required' : ''}>
            </div>

            <!-- Required Toggle -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="member_custom_fields[${id}][required]" value="1" id="mreq_${id}" ${requiredVal ? 'checked' : ''} class="rounded border-slate-700/60 bg-slate-900 text-indigo-500 focus:ring-indigo-500">
                <label for="mreq_${id}" class="text-[10px] font-bold text-slate-350 select-none cursor-pointer">บังคับกรอก (Required)</label>
            </div>
        `;
        
        memberContainer.appendChild(row);
        lucide.createIcons();
    }

    window.toggleMemberOptions = function(select, id) {
        const optsDiv = document.getElementById('mopts_' + id);
        if (select.value === 'select') {
            optsDiv.classList.remove('hidden');
            optsDiv.querySelector('input').required = true;
        } else {
            optsDiv.classList.add('hidden');
            optsDiv.querySelector('input').required = false;
        }
    };

    if (existingMemberFields) {
        const fieldsArr = Array.isArray(existingMemberFields) ? existingMemberFields : Object.values(existingMemberFields);
        fieldsArr.forEach(field => {
            addMemberFieldRow(field);
        });
    }

    addMemberFieldBtn.addEventListener('click', () => {
        addMemberFieldRow();
    });

    // Initialize BE Datetimepicker (Flatpickr)
    const fpConfig = {
        enableTime: true,
        time_24hr: true,
        dateFormat: "Y-m-d H:i:s",
        altInput: true,
        altFormat: "d/m/Y H:i",
        locale: "th",
        onReady: (selectedDates, dateStr, instance) => {
            applyBE(instance);
        },
        onValueUpdate: (selectedDates, dateStr, instance) => {
            applyBE(instance);
        },
        onOpen: (selectedDates, dateStr, instance) => {
            applyBE(instance);
        },
        onMonthChange: (selectedDates, dateStr, instance) => {
            setTimeout(() => applyBE(instance), 1);
        },
        onYearChange: (selectedDates, dateStr, instance) => {
            setTimeout(() => applyBE(instance), 1);
        }
    };
    
    const fpInstances = flatpickr(".datetimepicker-be", fpConfig);
    if (Array.isArray(fpInstances)) {
        fpInstances.forEach(instance => applyBE(instance));
    } else if (fpInstances) {
        applyBE(fpInstances);
    }

    function applyBE(instance) {
        if (!instance) return;
        const years = instance.calendarContainer ? instance.calendarContainer.querySelectorAll(".cur-year") : [];
        years.forEach(y => {
            let val = parseInt(y.value);
            if (val > 0 && val < 2400) y.value = val + 543;
        });
        if (instance.altInput && instance.selectedDates.length > 0) {
            const d = instance.selectedDates[0];
            const day = d.getDate().toString().padStart(2, '0');
            const month = (d.getMonth() + 1).toString().padStart(2, '0');
            const year = d.getFullYear() + 543;
            const hours = d.getHours().toString().padStart(2, '0');
            const minutes = d.getMinutes().toString().padStart(2, '0');
            instance.altInput.value = `${day}/${month}/${year} ${hours}:${minutes}`;
        }
    }
});
</script>
<?= $this->endSection() ?>
