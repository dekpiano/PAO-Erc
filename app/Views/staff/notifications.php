<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>

<div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
    <div>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">การแจ้งเตือน</h2>
        <p class="text-sm text-slate-400 mt-1 font-medium">ติดตามความเคลื่อนไหวและสถานะการดำเนินงานต่างๆ ของคุณ</p>
    </div>
    <div class="bg-blue-50 px-6 py-3 rounded-2xl border border-blue-100 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
            <i data-lucide="bell" class="w-6 h-6 animate-pulse"></i>
        </div>
        <div>
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest leading-none">Activity</p>
            <p class="text-xs font-bold text-blue-900 mt-0.5"><?= count($notifications) ?> รายการทั้งหมด</p>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <div class="glass-card rounded-[2.5rem] overflow-hidden border border-slate-100 divide-y divide-slate-50">
        <?php if(empty($notifications)): ?>
            <div class="p-20 text-center">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="calendar-check" class="w-10 h-10 text-slate-200"></i>
                </div>
                <h3 class="text-xl font-black text-slate-400">ยังไม่มีการแจ้งเตือนในขณะนี้</h3>
                <p class="text-sm text-slate-300 mt-2">เมื่อมีการทำรายการใหม่หรือสถานะเปลี่ยนไป ระบบจะแจ้งให้คุณทราบที่นี่</p>
            </div>
        <?php else: ?>
            <?php foreach($notifications as $not): ?>
                <div class="p-6 sm:p-8 hover:bg-slate-50/50 transition-colors group relative <?= $not['not_is_read'] ? 'opacity-70' : '' ?>">
                    <div class="flex gap-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0 shadow-sm">
                            <i data-lucide="message-square" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-4 mb-2">
                                <h4 class="font-black text-lg text-slate-900 leading-tight">
                                    <?= $not['not_title'] ?>
                                    <?php if(!$not['not_is_read']): ?>
                                        <span class="inline-block w-2 h-2 bg-rose-500 rounded-full ml-2"></span>
                                    <?php endif; ?>
                                </h4>
                                <span class="text-[11px] font-bold text-slate-400 whitespace-nowrap uppercase tracking-wider">
                                    <?= date('d/m/Y H:i', strtotime($not['not_created_at'])) ?>
                                </span>
                            </div>
                            <p class="text-slate-500 font-medium leading-relaxed mb-4"><?= $not['not_message'] ?></p>
                            
                            <?php if(!empty($not['not_link'])): ?>
                                <a href="<?= base_url($not['not_link']) ?>" class="inline-flex items-center gap-2 text-blue-600 font-black text-xs uppercase tracking-widest hover:gap-3 transition-all">
                                    ดูรายละเอียด <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
