<?= $this->extend('sports/public/layout/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-20 pt-4">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Success Hero Box -->
        <div class="bg-white rounded-3xl p-8 sm:p-12 text-center border border-slate-100 shadow-xl space-y-6">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-emerald-100 animate-bounce">
                <i data-lucide="check-circle-2" class="w-10 h-10"></i>
            </div>

            <div class="space-y-2">
                <span class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-black uppercase">
                    Registration Successful
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900">ลงทะเบียนเข้าร่วมแข่งขันสำเร็จ!</h1>
                <p class="text-slate-500 text-xs sm:text-sm max-w-md mx-auto">
                    ข้อมูลของท่านได้ถูกส่งเข้าสู่ระบบเรียบร้อยแล้ว กรุณาบันทึกรหัสทีมเพื่อใช้ตรวจสอบสถานะการอนุมัติสิทธิ์
                </p>
            </div>

            <!-- Team Tracking Code Display -->
            <div class="bg-slate-50 border-2 border-dashed border-slate-200 p-6 rounded-3xl max-w-md mx-auto space-y-2">
                <span class="text-xs font-bold text-slate-400">รหัสประจำทีมของคุณ (Team Code)</span>
                <div class="text-3xl sm:text-4xl font-black font-mono tracking-wider text-emerald-700 select-all">
                    <?= esc($team['team_code']) ?>
                </div>
                <div class="text-[11px] text-slate-400">
                    <?= esc($team['school_name']) ?> (<?= esc($team['sport_name']) ?> - <?= esc($team['category_name']) ?>)
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
                <a href="<?= base_url('sports/status') ?>" class="px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-extrabold text-xs flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span>ตรวจสอบสถานะการสมัคร</span>
                </a>
                <a href="<?= base_url('sports') ?>" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-xs flex items-center gap-2 transition-all">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    <span>กลับหน้าหลักกีฬา</span>
                </a>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
