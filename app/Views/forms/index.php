<?= $this->extend('forms/layout/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">

    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-3xl p-8 text-white shadow-xl">
        <h2 class="text-2xl md:text-3xl font-black mb-2">ระบบแบบสอบถามออนไลน์</h2>
        <p class="text-indigo-100 text-xs md:text-sm font-medium max-w-xl">
            ร่วมตอบแบบสอบถามเพื่อช่วยพัฒนาการดำเนินงานและการให้บริการของกองการศึกษา ศาสนา และวัฒนธรรม อบจ.นครสวรรค์
        </p>
    </div>

    <!-- Active Forms List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (empty($forms)): ?>
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i data-lucide="inbox" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700">ไม่มีแบบสอบถามเปิดใช้งานในขณะนี้</h3>
                <p class="text-slate-400 text-xs mt-1">โปรดกลับมาตรวจสอบใหม่อีกครั้งในภายหลัง</p>
            </div>
        <?php else: ?>
            <?php foreach ($forms as $f): ?>
                <div class="form-card rounded-3xl p-6 flex flex-col justify-between hover:shadow-lg transition-all border border-slate-200">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-600 border border-emerald-200">
                                ● เปิดรับคำตอบ
                            </span>

                            <?php if ($f['form_has_certificate'] == 1): ?>
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                                    <i data-lucide="award" class="w-3.5 h-3.5"></i> มีเกียรติบัตร
                                </span>
                            <?php endif; ?>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 mb-2 line-clamp-2"><?= esc($f['form_title']) ?></h3>
                        <p class="text-slate-500 text-xs line-clamp-3 mb-6 font-medium"><?= esc($f['form_description'] ?: 'ไม่มีคำอธิบายเพิ่มเติม') ?></p>
                    </div>

                    <div class="space-y-2">
                        <a href="<?= base_url("forms/view/{$f['form_id']}") ?>" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-extrabold text-xs flex items-center justify-center gap-2 shadow-lg shadow-indigo-100 transition-all hover:scale-[1.02]">
                            ทำแบบสอบถาม <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                        <button type="button" onclick="openShareModal(<?= $f['form_id'] ?>, '<?= esc($f['form_title'], 'js') ?>')" class="w-full py-2.5 bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 rounded-2xl font-bold text-xs flex items-center justify-center gap-2 transition-all border border-slate-200/80">
                            <i data-lucide="qr-code" class="w-4 h-4 text-indigo-600"></i> สแกน QR Code / แชร์ลิงก์
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal: Share & QR Code -->
<div id="share-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl space-y-6 text-center">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i data-lucide="qr-code" class="w-5 h-5 text-indigo-600"></i> QR Code & แชร์แบบสอบถาม
            </h3>
            <button onclick="closeShareModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <div class="space-y-4">
            <h4 id="share-modal-title" class="text-sm font-bold text-slate-800 line-clamp-1"></h4>
            
            <!-- QR Code Container -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl inline-block shadow-inner">
                <img id="share-qr-image" src="" alt="QR Code" class="w-48 h-48 mx-auto rounded-lg">
            </div>

            <!-- Copy Link Input -->
            <div class="space-y-2 text-left">
                <label class="block text-xs font-bold text-slate-600 uppercase">ลิงก์สำหรับเข้าตอบแบบสอบถาม</label>
                <div class="flex items-center gap-2">
                    <input type="text" id="share-link-input" readonly class="flex-1 px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl font-mono text-xs text-slate-700">
                    <button onclick="copyShareLink()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-md shadow-indigo-100 transition-colors">
                        <i data-lucide="copy" class="w-4 h-4"></i> คัดลอก
                    </button>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-center gap-3">
            <a id="share-qr-download" href="" download="qrcode.png" target="_blank" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-2">
                <i data-lucide="download" class="w-4 h-4"></i> ดาวน์โหลด QR Code
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function openShareModal(formId, formTitle) {
        const publicUrl = `<?= base_url('forms/view/') ?>` + formId;
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=` + encodeURIComponent(publicUrl);

        document.getElementById('share-modal-title').innerText = formTitle;
        document.getElementById('share-link-input').value = publicUrl;
        document.getElementById('share-qr-image').src = qrUrl;
        document.getElementById('share-qr-download').href = qrUrl;

        document.getElementById('share-modal').classList.remove('hidden');
    }

    function closeShareModal() {
        document.getElementById('share-modal').classList.add('hidden');
    }

    function copyShareLink() {
        const input = document.getElementById('share-link-input');
        input.select();
        navigator.clipboard.writeText(input.value);
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'success', title: 'คัดลอกลิงก์สำเร็จ!', timer: 1500, showConfirmButton: false });
        } else {
            alert('คัดลอกลิงก์เรียบร้อยแล้ว!');
        }
    }
</script>
<?= $this->endSection() ?>
