<?= $this->extend('forms/layout/admin') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Top Header & Navigation -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-1">
                <a href="<?= base_url('staff/forms') ?>" class="hover:text-indigo-600">แบบสอบถามทั้งหมด</a>
                <span>/</span>
                <span class="text-indigo-600">ตั้งค่าทั่วไป</span>
            </div>
            <h2 class="text-xl font-black text-slate-900"><?= esc($form['form_title']) ?></h2>
        </div>

        <div class="flex items-center gap-2">
            <a href="<?= base_url("staff/forms/builder/{$form['form_id']}") ?>" class="px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                <i data-lucide="list-checks" class="w-4 h-4"></i> จัดการข้อคำถาม
            </a>
            <a href="<?= base_url("staff/forms/certificate/{$form['form_id']}") ?>" class="px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-900 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                <i data-lucide="award" class="w-4 h-4 text-amber-600"></i> ตั้งค่าเกียรติบัตร
            </a>
        </div>
    </div>

    <!-- General Settings Form -->
    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                <i data-lucide="settings" class="w-5 h-5 text-indigo-600"></i> แก้ไขการตั้งค่าทั่วไปแบบสอบถาม
            </h3>
            <div id="save-indicator" class="text-xs font-bold px-3 py-1.5 bg-slate-100 rounded-xl">
                <span class="text-slate-400 flex items-center gap-1"><i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> พร้อมบันทึก</span>
            </div>
        </div>

        <form id="general-settings-form" onsubmit="saveGeneralSettings(event)" class="space-y-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    ชื่อแบบสอบถาม <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="form_title" value="<?= esc($form['form_title']) ?>" required oninput="triggerAutoSave()" placeholder="ระบุชื่อแบบสอบถาม..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">คำชี้แจง / รายละเอียดเพิ่มเติม</label>
                <textarea name="form_description" rows="4" oninput="triggerAutoSave()" placeholder="ระบุวัตถุประสงค์ คำชี้แจงในการตอบแบบสอบถาม..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm"><?= esc($form['form_description']) ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">สถานะการเปิดรับคำตอบ</label>
                <select name="form_status" onchange="triggerAutoSave()" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-sm">
                    <option value="active" <?= $form['form_status'] === 'active' ? 'selected' : '' ?>>● เปิดรับคำตอบ (Active)</option>
                    <option value="closed" <?= $form['form_status'] === 'closed' ? 'selected' : '' ?>>○ ปิดรับคำตอบ (Closed)</option>
                </select>
            </div>

            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="form_has_certificate" value="1" <?= $form['form_has_certificate'] == 1 ? 'checked' : '' ?> onchange="triggerAutoSave()" class="w-5 h-5 text-amber-600 rounded">
                    <div>
                        <span class="text-sm font-extrabold text-amber-950 block">เปิดใช้งานเกียรติบัตรออนไลน์ (E-Certificate)</span>
                        <span class="text-xs text-amber-700 font-medium">หากเปิดใช้งาน ผู้ตอบแบบสอบถามจะได้รับเกียรติบัตรทันทีหลังส่งข้อมูล</span>
                    </div>
                </label>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let autoSaveTimer = null;

    function triggerAutoSave() {
        const indicator = document.getElementById('save-indicator');
        if (indicator) {
            indicator.innerHTML = `<span class="text-amber-500 flex items-center gap-1"><i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> กำลังบันทึก...</span>`;
            lucide.createIcons();
        }

        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            saveGeneralSettings();
        }, 600);
    }

    async function saveGeneralSettings(e) {
        if (e) e.preventDefault();
        const form = document.getElementById('general-settings-form');
        const formData = new FormData(form);

        const res = await fetch('<?= base_url("staff/forms/save-general/{$form['form_id']}") ?>', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        const indicator = document.getElementById('save-indicator');

        if (data.status === 'success') {
            if (indicator) {
                indicator.innerHTML = `<span class="text-emerald-600 flex items-center gap-1"><i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> บันทึกอัตโนมัติแล้ว</span>`;
                lucide.createIcons();
            }
        } else {
            if (indicator) {
                indicator.innerHTML = `<span class="text-rose-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> บันทึกล้มเหลว</span>`;
                lucide.createIcons();
            }
        }
    }
</script>
<?= $this->endSection() ?>
