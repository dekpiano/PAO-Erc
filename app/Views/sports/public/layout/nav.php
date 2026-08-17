<div class="bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-slate-200/80 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">
        <a href="<?= base_url('sports') ?>" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black group-hover:scale-105 transition-transform shadow-md shadow-emerald-200">
                <i data-lucide="trophy" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-sm font-black text-slate-900 leading-none block">อบจ.นครสวรรค์ เกมส์ <?= !empty($activeCompYear) ? esc($activeCompYear) : '' ?></span>
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block mt-0.5">ระบบลงทะเบียนการแข่งขันกีฬา</span>
            </div>
        </a>

        <div class="flex items-center gap-1.5 sm:gap-2">
            <a href="<?= base_url('sports') ?>" class="px-3 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all <?= uri_string() === 'sports' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' ?>">
                <i data-lucide="home" class="w-3.5 h-3.5 inline mr-1"></i> หน้าแรก
            </a>
            <a href="<?= base_url('sports/status') ?>" class="px-3 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all <?= strpos(uri_string(), 'sports/status') === 0 ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' ?>">
                <i data-lucide="search" class="w-3.5 h-3.5 inline mr-1"></i> ตรวจสอบสถานะ
            </a>
            <a href="<?= base_url('sports/certificate') ?>" class="px-3 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all <?= strpos(uri_string(), 'sports/certificate') === 0 ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' ?>">
                <i data-lucide="award" class="w-3.5 h-3.5 inline mr-1"></i> ค้นหาเกียรติบัตร
            </a>
        </div>
    </div>
</div>
