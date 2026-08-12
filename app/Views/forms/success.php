<?= $this->extend('forms/layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto space-y-6 text-center">

    <div class="form-card rounded-3xl p-10 md:p-12 space-y-6">
        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mx-auto shadow-inner">
            <i data-lucide="check-circle-2" class="w-10 h-10"></i>
        </div>

        <div class="space-y-2">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900">ส่งแบบสอบถามสำเร็จแล้ว!</h2>
            <p class="text-slate-500 text-xs md:text-sm font-medium">ขอบพระคุณที่ร่วมตอบแบบสอบถามเรื่อง <br><span class="font-bold text-slate-800">"<?= esc($form['form_title']) ?>"</span></p>
        </div>

        <?php if ($form['form_has_certificate'] == 1 && !empty($sub['sub_cert_code'])): ?>
            <div class="p-6 bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-3xl space-y-4 shadow-sm">
                <div class="w-12 h-12 bg-amber-500 text-white rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-amber-200">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-amber-900">เกียรติบัตรออนไลน์ของคุณพร้อมแล้ว</h3>
                    <p class="text-amber-700/80 text-xs font-semibold mt-0.5">ออกให้แก่: <span class="font-bold text-amber-950"><?= esc($sub['sub_responder_name']) ?></span></p>
                    <p class="text-[11px] font-mono text-amber-600 mt-1">รหัสเกียรติบัตร: <?= esc($sub['sub_cert_code']) ?></p>
                </div>

                <a href="<?= base_url("forms/certificate/{$sub['sub_id']}") ?>" target="_blank" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-2xl font-black text-xs shadow-lg shadow-amber-200 transition-all hover:scale-105">
                    <i data-lucide="download" class="w-4 h-4"></i> ดาวน์โหลดเกียรติบัตร (Image/PDF)
                </a>
            </div>
        <?php endif; ?>

        <div class="pt-4 border-t border-slate-100 flex justify-center gap-4">
            <a href="<?= base_url('forms') ?>" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs">
                กลับหน้าแบบสอบถามทั้งหมด
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
