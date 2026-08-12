<?= $this->extend('forms/layout/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">

    <!-- Title & Create Button -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">รายการแบบสอบถามทั้งหมด</h2>
            <p class="text-slate-500 text-xs font-semibold mt-1">จัดการแบบสอบถาม สร้างคำถาม และตั้งค่าการออกเกียรติบัตรออนไลน์</p>
        </div>
        <button onclick="openCreateModal()" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-extrabold text-sm flex items-center justify-center gap-2 shadow-lg shadow-indigo-200 transition-all hover:scale-[1.02]">
            <i data-lucide="plus-circle" class="w-5 h-5"></i> สร้างแบบสอบถามใหม่
        </button>
    </div>

    <!-- Forms List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (empty($forms)): ?>
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i data-lucide="file-question" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700">ยังไม่มีแบบสอบถาม</h3>
                <p class="text-slate-400 text-xs mt-1 mb-6">เริ่มสร้างแบบสอบถามแรกของคุณเพื่อรวบรวมข้อมูลและแจกเกียรติบัตร</p>
                <button onclick="openCreateModal()" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-xs">
                    + สร้างแบบสอบถาม
                </button>
            </div>
        <?php else: ?>
            <?php foreach ($forms as $f): ?>
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider <?= $f['form_status'] === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-500' ?>">
                                <?= $f['form_status'] === 'active' ? '● เปิดรับคำตอบ' : '○ ปิดรับคำตอบ' ?>
                            </span>

                            <?php if ($f['form_has_certificate'] == 1): ?>
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                                    <i data-lucide="award" class="w-3.5 h-3.5"></i> มีเกียรติบัตร
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold text-slate-400 bg-slate-50">
                                    ไม่มีเกียรติบัตร
                                </span>
                            <?php endif; ?>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 line-clamp-1 mb-1"><?= esc($f['form_title']) ?></h3>
                        <p class="text-slate-500 text-xs line-clamp-2 mb-4"><?= esc($f['form_description'] ?: 'ไม่มีคำอธิบายเพิ่มเติม') ?></p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div class="text-xs text-slate-400 font-bold">
                            ผู้ตอบ: <span class="text-indigo-600 font-black text-sm"><?= number_format($f['response_count']) ?></span> คน
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button onclick="openShareModal(<?= $f['form_id'] ?>, '<?= esc($f['form_title'], 'js') ?>')" title="แชร์ฟอร์ม & QR Code" class="px-2.5 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-xl font-bold text-xs flex items-center gap-1 transition-colors">
                                <i data-lucide="qr-code" class="w-3.5 h-3.5"></i> แชร์ / QR
                            </button>
                            <a href="<?= base_url("forms/view/{$f['form_id']}") ?>" target="_blank" title="ดูหน้าฟอร์มจริง" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-slate-50 rounded-xl transition-colors">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                            </a>
                            <a href="<?= base_url("staff/forms/responses/{$f['form_id']}") ?>" title="ดูผลการตอบ" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-colors">
                                <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                            </a>
                            <a href="<?= base_url("staff/forms/edit/{$f['form_id']}") ?>" title="ตั้งค่าทั่วไป" class="px-2.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl font-bold text-xs flex items-center gap-1 transition-colors">
                                <i data-lucide="settings" class="w-3.5 h-3.5 text-slate-500"></i> ตั้งค่า
                            </a>
                            <a href="<?= base_url("staff/forms/certificate/{$f['form_id']}") ?>" title="ตั้งค่าเกียรติบัตร" class="px-2.5 py-2 bg-amber-50 text-amber-800 hover:bg-amber-100 rounded-xl font-bold text-xs flex items-center gap-1 transition-colors">
                                <i data-lucide="award" class="w-3.5 h-3.5 text-amber-600"></i> เกียรติบัตร
                            </a>
                            <a href="<?= base_url("staff/forms/builder/{$f['form_id']}") ?>" title="แก้ไขคำถาม" class="px-2.5 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl font-bold text-xs flex items-center gap-1 transition-colors">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> คำถาม
                            </a>
                            <button onclick="confirmDelete(<?= $f['form_id'] ?>)" title="ลบแบบสอบถาม" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
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
                <i data-lucide="share-2" class="w-5 h-5 text-indigo-600"></i> แชร์แบบสอบถาม & QR Code
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
                <label class="block text-xs font-bold text-slate-600 uppercase">ลิงก์สำหรับส่งให้ผู้ตอบแบบสอบถาม</label>
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

<!-- Modal: Create Form -->
<div id="create-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-lg font-black text-slate-900">สร้างแบบสอบถามใหม่</h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>

        <form id="create-form" onsubmit="handleCreate(event)" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">ชื่อแบบสอบถาม <span class="text-rose-500">*</span></label>
                <input type="text" name="form_title" required placeholder="เช่น แบบประเมินความพึงพอใจการอบรม 2026" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">คำอธิบายเพิ่มเติม</label>
                <textarea name="form_description" rows="3" placeholder="ระบุวัตถุประสงค์ หรือรายละเอียดเพิ่มเติม..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm"></textarea>
            </div>

            <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="form_has_certificate" value="1" class="w-5 h-5 text-indigo-600 rounded-md focus:ring-indigo-500">
                    <div>
                        <span class="text-sm font-extrabold text-slate-800">เปิดใช้งานเกียรติบัตรออนไลน์ (E-Certificate)</span>
                        <p class="text-[11px] text-slate-500 font-semibold">เมื่อตอบแบบสอบถามเสร็จ ระบบจะออกใบเกียรติบัตรให้ดาวน์โหลดอัตโนมัติ</p>
                    </div>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">ยกเลิก</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-xs shadow-lg shadow-indigo-100">สร้างแบบสอบถาม</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function openCreateModal() {
        document.getElementById('create-modal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
    }

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
        Swal.fire({ icon: 'success', title: 'คัดลอกลิงก์สำเร็จ!', timer: 1500, showConfirmButton: false });
    }

    async function handleCreate(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        const res = await fetch('<?= base_url('staff/forms/store') ?>', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.status === 'success') {
            window.location.href = data.redirect;
        } else {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: data.message });
        }
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบแบบสอบถาม?',
            text: 'คำตอบและข้อมูลทั้งหมดที่เกี่ยวข้องจะถูกลบทันที!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, ลบทันที',
            cancelButtonText: 'ยกเลิก'
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href = '<?= base_url('staff/forms/delete/') ?>' + id;
            }
        });
    }
</script>
<?= $this->endSection() ?>

