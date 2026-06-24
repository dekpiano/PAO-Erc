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
    .neon-input option {
        background-color: #0f172a;
        color: #f1f5f9;
    }
</style>

<!-- Header Section -->
<div class="mb-6">
    <a href="<?= base_url('staff/science-week/schedules') ?>" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-semibold transition-colors text-sm mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปหน้ารายการ
    </a>
    <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow">
        <span><?= !empty($sch) ? 'แก้ไขกำหนดการกิจกรรม' : 'เพิ่มกำหนดการกิจกรรม' ?></span>
    </h2>
    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">ระบุข้อมูลขั้นตอนกำหนดการกิจกรรมเพื่ออัปเดตลงหน้าแรกของระบบ</p>
</div>



<!-- Form Card -->
<div class="glass-card rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900/60 max-w-2xl border border-slate-200 dark:border-slate-800">
    <form action="<?= !empty($sch) ? base_url('staff/science-week/schedules/update/' . $sch['sch_id']) : base_url('staff/science-week/schedules/store') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <!-- Date Range Text -->
        <div class="space-y-2">
            <label for="sch_date" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-cyan-400"></i> ช่วงเวลา / วันที่ดำเนินงาน <span class="text-rose-450">*</span>
            </label>
            <input type="text" name="sch_date" id="sch_date" required value="<?= old('sch_date', $sch['sch_date'] ?? '') ?>" placeholder="เช่น 1 - 31 กรกฎาคม 2026, 18 สิงหาคม 2026..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
        </div>

        <!-- Title -->
        <div class="space-y-2">
            <label for="sch_title" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="text" class="w-4 h-4 text-indigo-400"></i> หัวข้อกำหนดการ <span class="text-rose-450">*</span>
            </label>
            <input type="text" name="sch_title" id="sch_title" required value="<?= old('sch_title', $sch['sch_title'] ?? '') ?>" placeholder="เช่น เปิดรับสมัครแข่งขันออนไลน์..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
        </div>

        <!-- Theme Color -->
        <div class="space-y-2">
            <label for="sch_color" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="palette" class="w-4 h-4 text-pink-400"></i> สีสัญลักษณ์ dot timeline <span class="text-rose-450">*</span>
            </label>
            <select name="sch_color" id="sch_color" class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
                <?php 
                $colors = [
                    'cyan'    => 'Cyan (ฟ้าสว่าง)',
                    'purple'  => 'Purple (ม่วง)',
                    'indigo'  => 'Indigo (น้ำเงินคราม)',
                    'pink'    => 'Pink (ชมพูหวาน)',
                    'amber'   => 'Amber (ส้มเหลือง)',
                    'emerald' => 'Emerald (เขียวมรกต)'
                ];
                $currentColor = old('sch_color', $sch['sch_color'] ?? 'cyan');
                foreach ($colors as $key => $label):
                ?>
                    <option value="<?= $key ?>" <?= $currentColor == $key ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <span class="text-[10px] text-slate-500 block">สีของจุดกลมสะท้อนแสงบนหน้าเส้นแสดงกำหนดการ</span>
        </div>

        <!-- Description -->
        <div class="space-y-2">
            <label for="sch_description" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="align-left" class="w-4 h-4 text-amber-400"></i> รายละเอียดแบบย่อ
            </label>
            <textarea name="sch_description" id="sch_description" rows="4" placeholder="ระบุข้อความอธิบายความคืบหน้า..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors resize-none"><?= old('sch_description', $sch['sch_description'] ?? '') ?></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-4 text-white font-bold rounded-2xl bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 shadow-lg shadow-indigo-950/20 transition-all flex items-center justify-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> บันทึกข้อมูลกำหนดการ
        </button>
    </form>
</div>
<?= $this->endSection() ?>
