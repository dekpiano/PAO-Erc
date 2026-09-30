<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<?php 
$activeCompYear = isset($activeYear) ? (int)$activeYear : (int)(session()->get('sports_active_year') ?: 2569); 
$sysStatus = $settings['system_status'] ?? 'open';
$statusMode = $settings['system_status_mode'] ?? 'manual';
?>
<div class="space-y-6">
    <?= view('sports/admin/layout/nav', ['activeYear' => $activeCompYear]) ?>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-full text-xs font-semibold mb-2">
                <i data-lucide="sliders" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span>Sports Portal & Registration Settings</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">ตั้งค่าระบบการแข่งขันกีฬา & เปิด-ปิดระบบ</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-0.5">ควบคุมสถานะการเปิดรับสมัคร, ปิดรับสมัคร, ปิดปรับปรุง และตั้งค่าข้อความประกาศบนหน้าเว็บหลัก</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= base_url('sports') ?>" target="_blank" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-bold text-xs flex items-center gap-2 shadow-sm transition-all">
                <i data-lucide="external-link" class="w-4 h-4 text-emerald-400"></i>
                <span>ดูหน้าเว็บหลัก</span>
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-2.5 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold flex items-center gap-2.5 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 shrink-0"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Quick Status Switcher Banner -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black <?= $sysStatus === 'open' ? 'bg-emerald-50 text-emerald-600' : ($sysStatus === 'closed' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') ?>">
                    <i data-lucide="<?= $sysStatus === 'open' ? 'check-circle' : ($sysStatus === 'closed' ? 'lock' : 'wrench') ?>" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">สถานะระบบปัจจุบัน</span>
                    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <span><?= $sysStatus === 'open' ? 'เปิดรับสมัครตามปกติ' : ($sysStatus === 'closed' ? 'ปิดรับสมัครทั้งหมด' : 'ปิดปรับปรุงระบบชั่วคราว') ?></span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black <?= $sysStatus === 'open' ? 'bg-emerald-100 text-emerald-800' : ($sysStatus === 'closed' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') ?>">
                            <?= strtoupper($sysStatus) ?>
                        </span>
                    </h2>
                </div>
            </div>

            <!-- Quick Action Form -->
            <form action="<?= base_url('staff/sports/toggle-system-status') ?>" method="POST" class="flex flex-wrap items-center gap-2">
                <?= csrf_field() ?>
                <button type="submit" name="status" value="open" 
                        class="px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-all cursor-pointer <?= $sysStatus === 'open' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' : 'bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700' ?>">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>เปิดรับสมัคร (Open)</span>
                </button>
                <button type="submit" name="status" value="closed" 
                        class="px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-all cursor-pointer <?= $sysStatus === 'closed' ? 'bg-rose-600 text-white shadow-md shadow-rose-200' : 'bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-700' ?>">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    <span>ปิดรับสมัคร (Closed)</span>
                </button>
                <button type="submit" name="status" value="maintenance" 
                        class="px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-all cursor-pointer <?= $sysStatus === 'maintenance' ? 'bg-amber-500 text-white shadow-md shadow-amber-200' : 'bg-slate-100 hover:bg-amber-50 text-slate-700 hover:text-amber-700' ?>">
                    <i data-lucide="wrench" class="w-4 h-4"></i>
                    <span>ปิดปรับปรุง (Maintenance)</span>
                </button>
            </form>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            <strong class="text-slate-700">คำอธิบาย:</strong> 
            การเปลี่ยนสถานะที่นี่จะมีผลกับหน้าเว็บหลักและการกดลงทะเบียนของบุคคลภายนอกทันที (ระบบค้นหาสถานะการสมัครและดาวน์โหลดเกียรติบัตรยังคงเปิดให้บริการตามปกติ)
        </p>
    </div>

    <!-- Main Settings Form -->
    <form action="<?= base_url('staff/sports/settings/save') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <!-- Card 0: Homepage Visibility Control (Show / Hide) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold shrink-0">
                        <i data-lucide="eye" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-700 mb-1">
                            <span>Homepage Display</span>
                        </div>
                        <h3 class="font-black text-slate-900 text-base">การแสดงผลบนหน้าแรกของเว็บไซต์ (http://localhost:9000/)</h3>
                        <p class="text-xs text-slate-400">ควบคุมการแสดงหรือซ่อนแบนเนอร์รับสมัครกีฬาบนหน้าหลักของ อบจ.</p>
                    </div>
                </div>

                <!-- Toggle Switch for Homepage Visibility -->
                <label class="inline-flex items-center gap-3 cursor-pointer select-none bg-slate-50 hover:bg-slate-100 px-4 py-2.5 rounded-2xl border border-slate-200 transition-colors shrink-0">
                    <input type="checkbox" name="show_on_homepage" value="1" <?= ($settings['show_on_homepage'] ?? '1') === '1' ? 'checked' : '' ?> class="sr-only peer" onchange="updateHomepageLabel(this)">
                    <div class="relative w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    <span id="homepageStatusText" class="text-xs font-black <?= ($settings['show_on_homepage'] ?? '1') === '1' ? 'text-emerald-700' : 'text-slate-500' ?>">
                        <?= ($settings['show_on_homepage'] ?? '1') === '1' ? '👁️ แสดงบนหน้าแรก' : '🙈 ซ่อน (ไม่แสดงผล)' ?>
                    </span>
                </label>
            </div>

            <div class="p-3.5 bg-indigo-50/60 rounded-2xl border border-indigo-100 text-xs text-indigo-900 flex items-start gap-2.5">
                <i data-lucide="info" class="w-4 h-4 text-indigo-600 shrink-0 mt-0.5"></i>
                <p class="leading-relaxed">
                    <strong>คำแนะนำ:</strong> หากต้องการเตรียมระบบล่วงหน้าโดยยังไม่ให้บุคคลทั่วไปเห็นส่วนรับสมัครกีฬาบนหน้าแรก ให้สวิตช์เป็น <strong>"ซ่อน"</strong> แบนเนอร์จะหายไปจากหน้าหลักทันที และเมื่อเปิดใช้งานจะกลับมาแสดงผลตามสถานะที่ตั้งไว้
                </p>
            </div>
        </div>

        <!-- Card 1: System Status & Registration Schedule -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <i data-lucide="toggle-left" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-base">1. การเปิด-ปิดระบบ และกำหนดการรับสมัคร</h3>
                    <p class="text-xs text-slate-400">เลือกโหมดการทำงานและกำหนดข้อความแจ้งเตือน</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- System Status Selector -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700">
                        สถานะระบบหลัก <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="relative flex flex-col items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all text-center <?= $sysStatus === 'open' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-900 font-black' : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300' ?>">
                            <input type="radio" name="system_status" value="open" <?= $sysStatus === 'open' ? 'checked' : '' ?> class="sr-only" onchange="updateStatusPreview()">
                            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 mb-1"></i>
                            <span class="text-xs font-bold">เปิดรับสมัคร</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Open</span>
                        </label>

                        <label class="relative flex flex-col items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all text-center <?= $sysStatus === 'closed' ? 'border-rose-500 bg-rose-50/50 text-rose-900 font-black' : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300' ?>">
                            <input type="radio" name="system_status" value="closed" <?= $sysStatus === 'closed' ? 'checked' : '' ?> class="sr-only" onchange="updateStatusPreview()">
                            <i data-lucide="lock" class="w-5 h-5 text-rose-600 mb-1"></i>
                            <span class="text-xs font-bold">ปิดรับสมัคร</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Closed</span>
                        </label>

                        <label class="relative flex flex-col items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all text-center <?= $sysStatus === 'maintenance' ? 'border-amber-500 bg-amber-50/50 text-amber-900 font-black' : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300' ?>">
                            <input type="radio" name="system_status" value="maintenance" <?= $sysStatus === 'maintenance' ? 'checked' : '' ?> class="sr-only" onchange="updateStatusPreview()">
                            <i data-lucide="wrench" class="w-5 h-5 text-amber-600 mb-1"></i>
                            <span class="text-xs font-bold">ปิดปรับปรุง</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Maintenance</span>
                        </label>
                    </div>
                </div>

                <!-- Mode Selector -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700">
                        รูปแบบการควบคุมระบบ <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all <?= $statusMode === 'manual' ? 'border-indigo-500 bg-indigo-50/50 text-indigo-900 font-bold' : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300' ?>">
                            <input type="radio" name="system_status_mode" value="manual" <?= $statusMode === 'manual' ? 'checked' : '' ?> class="sr-only" onchange="toggleScheduleFields(false)">
                            <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0">
                                <i data-lucide="hand" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-xs font-black block">เปิด-ปิดเอง (Manual)</span>
                                <span class="text-[10px] text-slate-400">ตามสวิตช์ด้านบน</span>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all <?= $statusMode === 'schedule' ? 'border-indigo-500 bg-indigo-50/50 text-indigo-900 font-bold' : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300' ?>">
                            <input type="radio" name="system_status_mode" value="schedule" <?= $statusMode === 'schedule' ? 'checked' : '' ?> class="sr-only" onchange="toggleScheduleFields(true)">
                            <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0">
                                <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-xs font-black block">ตามกำหนดเวลา</span>
                                <span class="text-[10px] text-slate-400">Schedule Auto</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Schedule Dates -->
            <div id="scheduleFields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200/80 <?= $statusMode === 'schedule' ? '' : 'hidden' ?>">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <i data-lucide="calendar-plus" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>วันที่เริ่มเปิดรับสมัครทั้งระบบ</span>
                    </label>
                    <input type="date" name="system_reg_start_date" value="<?= esc($settings['system_reg_start_date'] ?? '') ?>" 
                           class="w-full px-4 py-2.5 bg-white rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-[10px] text-slate-400">หากยังไม่ถึงวันดังกล่าว หน้าเว็บจะแสดงสถานะ "ยังไม่เปิดรับสมัคร"</p>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <i data-lucide="calendar-x" class="w-3.5 h-3.5 text-rose-600"></i>
                        <span>วันที่สิ้นสุดการรับสมัครทั้งระบบ</span>
                    </label>
                    <input type="date" name="system_reg_end_date" value="<?= esc($settings['system_reg_end_date'] ?? '') ?>" 
                           class="w-full px-4 py-2.5 bg-white rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-[10px] text-slate-400">เมื่อพ้นกำหนดวันดังกล่าว ระบบจะปิดรับสมัครโดยอัตโนมัติ</p>
                </div>
            </div>

            <!-- Notice Messages -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <i data-lucide="lock" class="w-3.5 h-3.5 text-rose-600"></i>
                        <span>ข้อความแจ้งเมื่อปิดรับสมัคร (Closed Notice)</span>
                    </label>
                    <textarea name="system_closed_message" rows="3" 
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500"><?= esc($settings['system_closed_message'] ?? '') ?></textarea>
                    <p class="text-[10px] text-slate-400">ข้อความนี้จะแสดงบนแบนเนอร์หน้าแรกเมื่อระบบปิดรับสมัคร</p>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <i data-lucide="wrench" class="w-3.5 h-3.5 text-amber-600"></i>
                        <span>ข้อความแจ้งเมื่อปิดปรับปรุง (Maintenance Notice)</span>
                    </label>
                    <textarea name="system_maintenance_message" rows="3" 
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500"><?= esc($settings['system_maintenance_message'] ?? '') ?></textarea>
                    <p class="text-[10px] text-slate-400">ข้อความนี้จะแสดงเมื่ออยู่ในโหมดปิดปรับปรุงระบบชั่วคราว</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Public Announcement Banner -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <i data-lucide="megaphone" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base">2. ป้ายประกาศข่าวสารบนหน้าเว็บหลัก (Public Banner)</h3>
                        <p class="text-xs text-slate-400">แสดงข้อความข่าวสารหรือแจ้งเตือนสำคัญบนหัวหน้าแรกของเว็บ</p>
                    </div>
                </div>

                <!-- Enable Banner Switch -->
                <label class="inline-flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="system_announcement_active" value="1" <?= ($settings['system_announcement_active'] ?? '0') === '1' ? 'checked' : '' ?> class="sr-only peer">
                    <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    <span class="text-xs font-black text-slate-700">เปิดแสดงประกาศ</span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">ประเภทประกาศ</label>
                    <select name="system_announcement_type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="info" <?= ($settings['system_announcement_type'] ?? 'info') === 'info' ? 'selected' : '' ?>>🔵 ข้อมูลทั่วไป (Info)</option>
                        <option value="warning" <?= ($settings['system_announcement_type'] ?? 'info') === 'warning' ? 'selected' : '' ?>>🟡 สำคัญ / แจ้งเตือน (Warning)</option>
                        <option value="success" <?= ($settings['system_announcement_type'] ?? 'info') === 'success' ? 'selected' : '' ?>>🟢 ข่าวดี / ประกาศผล (Success)</option>
                    </select>
                </div>

                <div class="md:col-span-3 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">ข้อความประกาศบนหน้าแรก</label>
                    <input type="text" name="system_announcement" value="<?= esc($settings['system_announcement'] ?? '') ?>" 
                           placeholder="เช่น กำหนดการส่งเอกสารเพิ่มเติม หรือประกาศตารางการแข่งขันอย่างเป็นทางการ" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
        </div>

        <!-- Card 3: Year & Coordinator Contact Info -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <i data-lucide="settings-2" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-base">3. ปีการแข่งขันหลัก & ข้อมูลติดต่อเจ้าหน้าที่</h3>
                    <p class="text-xs text-slate-400">กำหนดปีการแข่งขันเริ่มต้นของระบบ และข้อมูลช่องทางติดต่อสำหรับผู้สมัคร</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">ปีการแข่งขันหลัก (พ.ศ.) <span class="text-rose-500">*</span></label>
                    <input type="number" name="active_comp_year" value="<?= esc($settings['active_comp_year'] ?? $activeCompYear) ?>" min="2500" max="2650" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-[10px] text-slate-400">หน้าเว็บสาธารณะจะแสดงข้อมูลตามปีนี้</p>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">ชื่อผู้ประสานงาน / หน่วยงาน</label>
                    <input type="text" name="contact_name" value="<?= esc($settings['contact_name'] ?? '') ?>" 
                           placeholder="ฝ่ายจัดการแข่งขันกีฬา อบจ." 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">เบอร์โทรศัพท์ติดต่อ</label>
                    <input type="text" name="contact_phone" value="<?= esc($settings['contact_phone'] ?? '') ?>" 
                           placeholder="056-XXXXXX" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Line Official / Line ID</label>
                    <input type="text" name="contact_line" value="<?= esc($settings['contact_line'] ?? '') ?>" 
                           placeholder="@paonakhonsawan" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
        </div>

        <!-- Submit Button Bar -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="<?= base_url('staff/sports') ?>" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-xs transition-colors">
                ยกเลิก
            </a>
            <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-xs flex items-center gap-2 shadow-lg shadow-emerald-200 hover:scale-105 active:scale-95 transition-all cursor-pointer">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>บันทึกการตั้งค่าระบบทั้งหมด</span>
            </button>
        </div>
    </form>
</div>

<script>
function toggleScheduleFields(show) {
    var el = document.getElementById('scheduleFields');
    if (el) {
        if (show) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }
}

function updateStatusPreview() {
    document.querySelectorAll('input[name="system_status"]').forEach(function(r) {
        var p = r.closest('label');
        if (p) {
            if (r.checked) {
                if (r.value === 'open') {
                    p.className = 'relative flex flex-col items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all text-center border-emerald-500 bg-emerald-50/50 text-emerald-900 font-black';
                } else if (r.value === 'closed') {
                    p.className = 'relative flex flex-col items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all text-center border-rose-500 bg-rose-50/50 text-rose-900 font-black';
                } else {
                    p.className = 'relative flex flex-col items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all text-center border-amber-500 bg-amber-50/50 text-amber-900 font-black';
                }
            } else {
                p.className = 'relative flex flex-col items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all text-center border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300';
            }
        }
    });
}

function updateHomepageLabel(chk) {
    var txt = document.getElementById('homepageStatusText');
    if (txt) {
        if (chk.checked) {
            txt.textContent = '👁️ แสดงบนหน้าแรก';
            txt.className = 'text-xs font-black text-emerald-700';
        } else {
            txt.textContent = '🙈 ซ่อน (ไม่แสดงผล)';
            txt.className = 'text-xs font-black text-slate-500';
        }
    }
}
</script>
<?= $this->endSection() ?>
