<?= $this->extend('forms/layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto space-y-6 text-center">

    <div class="form-card rounded-3xl p-8 md:p-12 space-y-6 bg-white border border-slate-200 shadow-sm">
        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mx-auto shadow-inner">
            <i data-lucide="check-circle-2" class="w-10 h-10"></i>
        </div>

        <div class="space-y-2">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900">ส่งแบบสอบถามสำเร็จแล้ว!</h2>
            <p class="text-slate-500 text-xs md:text-sm font-medium">ระบบได้บันทึกคำตอบของคุณเรียบร้อยแล้ว ขอบพระคุณที่ร่วมตอบแบบสอบถามเรื่อง <br><span class="font-bold text-slate-800">"<?= esc($form['form_title']) ?>"</span></p>
        </div>

        <?php if ($form['form_has_certificate'] == 1): ?>
            <div id="cert-claim-card" class="p-6 md:p-8 bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200/80 rounded-3xl space-y-5 text-left shadow-sm">
                <?php if (!empty($sub['sub_responder_name'])): ?>
                    <!-- State: Already Claimed / Certificate Ready -->
                    <div class="space-y-4 text-center">
                        <div class="w-14 h-14 bg-amber-500 text-white rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-amber-200">
                            <i data-lucide="award" class="w-7 h-7"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-amber-950">เกียรติบัตรออนไลน์ของคุณพร้อมแล้ว</h3>
                            <p class="text-amber-800 text-xs md:text-sm font-semibold mt-1">ออกให้แก่: <span class="font-bold text-amber-950 border-b border-amber-400 pb-0.5"><?= esc($sub['sub_responder_name']) ?></span></p>
                            <p class="text-[11px] font-mono text-amber-600 mt-1.5">รหัสเกียรติบัตร: <?= esc($sub['sub_cert_code']) ?></p>
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row justify-center gap-3">
                            <a href="<?= base_url("forms/certificate/{$sub['sub_id']}") ?>" target="_blank" class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-2xl font-black text-xs shadow-lg shadow-amber-200 flex items-center justify-center gap-2 transition-all hover:scale-105">
                                <i data-lucide="download" class="w-4 h-4"></i> ดาวน์โหลดเกียรติบัตร
                            </a>
                            <a href="<?= base_url('forms') ?>" class="px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-2xl font-bold text-xs flex items-center justify-center gap-2">
                                <i data-lucide="home" class="w-4 h-4"></i> กลับหน้าหลักแบบสอบถาม
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- State: First Time Claim Form -->
                    <div class="flex items-center gap-3 border-b border-amber-200/60 pb-4">
                        <div class="w-12 h-12 bg-amber-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-amber-200 shrink-0">
                            <i data-lucide="award" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-amber-950">รับเกียรติบัตรออนไลน์ (E-Certificate)</h3>
                            <p class="text-amber-700/90 text-xs font-medium">กรอกชื่อ-นามสกุล เพื่อออกเกียรติบัตร (สามารถออกได้ 1 ใบ ต่อการทำแบบสอบถาม 1 ครั้ง)</p>
                        </div>
                    </div>

                    <form id="cert-claim-form" onsubmit="handleClaimCertificate(event)" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                ชื่อ-นามสกุล ที่ต้องการระบุในเกียรติบัตร <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="sub_responder_name" required 
                                   placeholder="เช่น นายสมชาย รักดี / ด.ช.วิชัย สุขใจ" 
                                   class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500 font-bold text-sm bg-white">
                            <p class="text-[11px] text-slate-400 mt-1 font-medium">* ตรวจสอบตัวสะกดและคำนำหน้าชื่อให้ถูกต้องก่อนกด ยืนยัน</p>
                        </div>

                        <div class="pt-2">
                            <button type="submit" id="btn-submit-cert" class="w-full py-3.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-2xl font-black text-sm shadow-xl shadow-amber-200 flex items-center justify-center gap-2 transition-all hover:scale-[1.01]">
                                <i data-lucide="download" class="w-5 h-5"></i> ยืนยันออกเกียรติบัตร & ดาวน์โหลด
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
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

<?= $this->section('scripts') ?>
<script>
    async function handleClaimCertificate(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-submit-cert');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        const formData = new FormData(e.target);

        Swal.fire({
            title: 'กำลังสร้างเกียรติบัตร...',
            text: 'กรุณารอสักครู่',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const res = await fetch('<?= base_url("forms/claim-certificate/{$sub['sub_id']}") ?>', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'ออกเกียรติบัตรเรียบร้อย!',
                    text: 'ระบบกำลังเปิดใบเกียรติบัตรของคุณ...',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Open Certificate image in new tab
                    window.open(data.cert_url, '_blank');
                    // Reload current page to lock form and show completed state
                    window.location.reload();
                });
            } else {
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
                Swal.fire({ icon: 'error', title: 'ไม่สามารถออกเกียรติบัตรได้', text: data.message });
            }
        } catch (err) {
            console.error('Error claiming certificate:', err);
            if (btn) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้' });
        }
    }
</script>
<?= $this->endSection() ?>
