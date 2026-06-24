<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
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
</style>

<!-- Header Section -->
<div class="mb-6">
    <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow flex items-center gap-3">
        <i data-lucide="clock" class="w-8 h-8 text-amber-400 animate-pulse"></i>
        <span>ตั้งค่าเวลานับถอยหลังสู่วันงาน</span>
    </h2>
    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">ระบุวันและเวลาเป้าหมายของการจัดงาน เพื่อแสดงผลในระบบนับถอยหลัง (Countdown Timer) ที่หน้าเว็บประชาสัมพันธ์</p>
</div>



<!-- Form Card -->
<div class="glass-card rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900/60 max-w-xl border border-slate-200 dark:border-slate-800">
    <form action="<?= base_url('staff/science-week/settings/save') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <!-- Target Date Input -->
        <div class="space-y-2">
            <label for="countdown_date" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-cyan-400"></i> วันและเวลาจัดงานวันวิทยาศาสตร์ <span class="text-rose-450">*</span>
            </label>
            
            <?php 
            // Convert any format to datetime-local format (Y-m-d\TH:i)
            $formattedDate = '';
            if (!empty($countdown_date)) {
                $time = strtotime($countdown_date);
                if ($time !== false) {
                    $formattedDate = date('Y-m-d\TH:i', $time);
                }
            }
            ?>
            <input type="datetime-local" name="countdown_date" id="countdown_date" required value="<?= esc($formattedDate) ?>" class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
            <span class="text-[10px] text-slate-500 block">เมื่อบันทึกแล้ว เวลาในการนับถอยหลังที่แสดงบนหน้าแรกของสัปดาห์วิทยาศาสตร์จะเปลี่ยนตามเวลานี้โดยอัตโนมัติ</span>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-4 text-white font-bold rounded-2xl bg-gradient-to-r from-amber-500 to-rose-500 hover:from-amber-600 hover:to-rose-600 shadow-lg shadow-rose-950/20 transition-all flex items-center justify-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> บันทึกข้อมูลการตั้งค่า
        </button>
    </form>
</div>
<?= $this->endSection() ?>
