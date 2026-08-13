<?= $this->extend('forms/layout/main') ?>

<?= $this->section('content') ?>
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4">
    <div class="max-w-xl w-full text-center space-y-8 bg-white/90 backdrop-blur-xl p-8 md:p-12 rounded-3xl border border-slate-200/80 shadow-2xl relative overflow-hidden">
        
        <!-- Decorative Background Glow -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Animated Icon Container -->
        <div class="relative inline-block">
            <div class="w-24 h-24 md:w-28 md:h-28 bg-gradient-to-tr from-rose-500 via-amber-500 to-rose-400 rounded-3xl flex items-center justify-center mx-auto shadow-xl shadow-rose-200 text-white transform hover:rotate-3 transition-transform duration-300">
                <i data-lucide="<?= !empty($form) ? 'lock' : 'file-x-2' ?>" class="w-12 h-12 md:w-14 md:h-14"></i>
            </div>
            <span class="absolute -bottom-2 -right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md text-rose-500 border border-slate-100">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
            </span>
        </div>

        <!-- Title & Description -->
        <div class="space-y-3">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                <?= !empty($form) ? 'ปิดรับคำตอบแล้ว' : 'ไม่พบแบบสอบถาม' ?>
            </h2>

            <?php if (!empty($form)): ?>
                <div class="inline-block px-4 py-2 bg-slate-100/90 border border-slate-200 rounded-2xl max-w-full">
                    <p class="text-xs md:text-sm font-black text-slate-800 line-clamp-2">
                        "<?= esc($form['form_title']) ?>"
                    </p>
                </div>
            <?php endif; ?>

            <p class="text-slate-500 text-xs md:text-sm font-semibold max-w-md mx-auto leading-relaxed pt-2">
                <?= esc($message ?? 'แบบสอบถามนี้ไม่เปิดรับการตอบในขณะนี้ หรืออาจถูกปิดระบบแล้ว ขอขอบพระคุณทุกท่านที่สนใจ') ?>
            </p>
        </div>

        <!-- Information Card -->
        <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/60 text-left text-xs font-semibold text-slate-600 space-y-2">
            <div class="flex items-center gap-2 font-bold text-slate-700">
                <i data-lucide="info" class="w-4 h-4 text-indigo-600"></i> ข้อแนะนำสำหรับการเข้าถึง:
            </div>
            <ul class="list-disc list-inside space-y-1 text-slate-500 pl-1">
                <li>หากคุณเป็นเจ้าหน้าที่ สามารถล็อกอินเข้าสู่ระบบจัดการได้</li>
                <li>คุณสามารถเลือกทำแบบสอบถามเรื่องอื่นๆ ที่กำลังเปิดรับฟังความคิดเห็นอยู่ได้</li>
            </ul>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <a href="<?= base_url('forms') ?>" class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-xs md:text-sm flex items-center justify-center gap-2 shadow-xl shadow-indigo-200 transition-all hover:scale-[1.02]">
                <i data-lucide="layout-grid" class="w-4 h-4"></i> ดูแบบสอบถามทั้งหมด
            </a>
            <a href="<?= base_url('/') ?>" class="w-full sm:w-auto px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-xs md:text-sm flex items-center justify-center gap-2 transition-colors">
                <i data-lucide="home" class="w-4 h-4"></i> กลับหน้าแรก
            </a>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
