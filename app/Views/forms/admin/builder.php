<?= $this->extend('forms/layout/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">

    <!-- Top Header & Actions -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-1">
                <a href="<?= base_url('staff/forms') ?>" class="hover:text-indigo-600">แบบสอบถามทั้งหมด</a>
                <span>/</span>
                <span class="text-indigo-600">Form Builder</span>
            </div>
            <h2 class="text-xl font-black text-slate-900"><?= esc($form['form_title']) ?></h2>
        </div>

        <div class="flex items-center gap-3">
            <div id="save-indicator" class="text-xs font-bold px-3 py-1.5 bg-slate-100 rounded-xl">
                <span class="text-slate-400 flex items-center gap-1"><i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> พร้อมบันทึก</span>
            </div>
            <a href="<?= base_url("staff/forms/certificate/{$form['form_id']}") ?>" class="px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 rounded-xl font-bold text-xs flex items-center gap-2">
                <i data-lucide="award" class="w-4 h-4 text-amber-600"></i> ตั้งค่าเกียรติบัตร
            </a>
            <a href="<?= base_url("forms/view/{$form['form_id']}") ?>" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-2">
                <i data-lucide="external-link" class="w-4 h-4"></i> ดูฟอร์มจริง
            </a>
        </div>
    </div>

    <!-- Main Grid: Builder & General Form Settings -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left Column: Form Builder Fields (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <i data-lucide="list-checks" class="w-5 h-5 text-indigo-600"></i> ข้อคำถามในแบบสอบถาม
                    </h3>
                    <span class="text-xs font-bold text-slate-400">ลากจัดลำดับได้</span>
                </div>

                <!-- Fields Container (Sortable) -->
                <div id="fields-list" class="space-y-4 min-h-[150px]">
                    <!-- Render Dynamic Fields via JS -->
                </div>

                <!-- Add Field Controls -->
                <div class="pt-4 border-t border-slate-100 flex flex-wrap gap-2">
                    <button onclick="addField('text')" class="px-4 py-2 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                        + ข้อความสั้น
                    </button>
                    <button onclick="addField('textarea')" class="px-4 py-2 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                        + ข้อความยาว
                    </button>
                    <button onclick="addField('radio')" class="px-4 py-2 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                        + ตัวเลือกเดียว (Radio)
                    </button>
                    <button onclick="addField('checkbox')" class="px-4 py-2 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                        + หลายตัวเลือก (Checkbox)
                    </button>
                    <button onclick="addField('rating')" class="px-4 py-2 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                        + ให้คะแนนรายข้อ (Rating Scale)
                    </button>
                    <button onclick="addField('rating_grid')" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-xl font-extrabold text-xs flex items-center gap-1.5 transition-colors shadow-sm">
                        + ตารางประเมินหลายข้อ (Rating Grid)
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Column: General Settings (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-base font-black text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="settings" class="w-5 h-5 text-indigo-600"></i> ตั้งค่าทั่วไปแบบสอบถาม
                </h3>

                <form id="general-settings-form" onsubmit="saveGeneralSettings(event)" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">ชื่อแบบสอบถาม</label>
                        <input type="text" name="form_title" value="<?= esc($form['form_title']) ?>" required oninput="triggerGeneralAutoSave()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-bold text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">คำชี้แจง / รายละเอียด</label>
                        <textarea name="form_description" rows="3" oninput="triggerGeneralAutoSave()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-medium text-xs"><?= esc($form['form_description']) ?></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">สถานะรับคำตอบ</label>
                        <select name="form_status" onchange="triggerGeneralAutoSave()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-bold text-xs">
                            <option value="active" <?= $form['form_status'] === 'active' ? 'selected' : '' ?>>● เปิดรับคำตอบ (Active)</option>
                            <option value="closed" <?= $form['form_status'] === 'closed' ? 'selected' : '' ?>>○ ปิดรับคำตอบ (Closed)</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <a href="<?= base_url("staff/forms/certificate/{$form['form_id']}") ?>" class="flex items-center justify-between p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl border border-amber-200 group hover:border-amber-300 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-md">
                                    <i data-lucide="award" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-black text-amber-950 block">ตั้งค่าเกียรติบัตรออนไลน์</span>
                                    <span class="text-[10px] text-amber-700 font-semibold">
                                        <?= $form['form_has_certificate'] == 1 ? '✓ เปิดใช้งานเกียรติบัตรอยู่' : '○ ปิดการใช้งานเกียรติบัตรอยู่' ?>
                                    </span>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-5 h-5 text-amber-500 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let fieldsData = <?= json_encode($fields ?: []) ?>;

    document.addEventListener('DOMContentLoaded', () => {
        renderFields();
        initSortable();
    });

    let autoSaveTimer = null;

    function triggerAutoSave() {
        const indicator = document.getElementById('save-indicator');
        if (indicator) {
            indicator.innerHTML = `<span class="text-amber-500 flex items-center gap-1"><i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> กำลังบันทึก...</span>`;
            lucide.createIcons();
        }

        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            saveAllFields(true);
        }, 600);
    }

    function initSortable() {
        const el = document.getElementById('fields-list');
        Sortable.create(el, {
            animation: 150,
            onEnd: function () {
                const newFields = [];
                document.querySelectorAll('.field-item').forEach((item) => {
                    const id = item.getAttribute('data-id');
                    const f = fieldsData.find(x => (x.field_id || x.temp_id) == id);
                    if (f) newFields.push(f);
                });
                fieldsData = newFields;
                triggerAutoSave();
            }
        });
    }

    function addField(type) {
        const tempId = 'temp_' + Date.now();
        let defaultOpts = null;
        if (type === 'radio' || type === 'checkbox') {
            defaultOpts = ["", ""];
        } else if (type === 'rating') {
            defaultOpts = { max: 5 };
        } else if (type === 'rating_grid') {
            defaultOpts = { max: 5, items: ["", ""] };
        }

        fieldsData.push({
            temp_id: tempId,
            field_label: '',
            field_type: type,
            field_options: defaultOpts,
            field_is_required: 1
        });
        renderFields();
        triggerAutoSave();
    }

    function removeField(id) {
        fieldsData = fieldsData.filter(f => (f.field_id || f.temp_id) != id);
        renderFields();
        triggerAutoSave();
    }

    function renderFields() {
        const container = document.getElementById('fields-list');
        container.innerHTML = '';

        if (fieldsData.length === 0) {
            container.innerHTML = `
                <div class="p-8 text-center border-2 border-dashed border-slate-200 rounded-2xl">
                    <p class="text-slate-400 text-xs font-bold">ยังไม่มีข้อคำถามในแบบสอบถามนี้ กดปุ่มด้านล่างเพื่อเพิ่มคำถาม</p>
                </div>`;
            return;
        }

        fieldsData.forEach((f, idx) => {
            const fId = f.field_id || f.temp_id;
            let rawOpts = (typeof f.field_options === 'object' && f.field_options !== null) ? f.field_options : {};
            if (typeof f.field_options === 'string') {
                try { rawOpts = JSON.parse(f.field_options) || {}; } catch(e) { rawOpts = {}; }
            }

            let options = Array.isArray(rawOpts) ? rawOpts : [];
            let gridItems = Array.isArray(rawOpts.items) ? rawOpts.items : [];

            const item = document.createElement('div');
            item.className = 'field-item bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-3 relative group';
            item.setAttribute('data-id', fId);

            item.innerHTML = `
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 flex-1">
                        <i data-lucide="grip-vertical" class="w-4 h-4 text-slate-400 cursor-move"></i>
                        <span class="text-xs font-black text-indigo-600">ข้อ ${idx + 1}</span>
                        <input type="text" value="${f.field_label || ''}" oninput="updateLabel('${fId}', this.value)" placeholder="กรอกหัวข้อคำถาม..." class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold bg-white">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-600 cursor-pointer">
                            <input type="checkbox" ${f.field_is_required == 1 ? 'checked' : ''} onchange="updateRequired('${fId}', this.checked)" class="rounded text-indigo-600">
                            บังคับตอบ
                        </label>
                        <button onclick="removeField('${fId}')" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                </div>

                ${(f.field_type === 'radio' || f.field_type === 'checkbox') ? `
                    <div class="pl-6 space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase">ตัวเลือก:</label>
                        ${options.map((opt, oIdx) => `
                            <div class="flex items-center gap-2">
                                <span class="text-slate-300 text-xs">${f.field_type === 'radio' ? '○' : '□'}</span>
                                <input type="text" value="${opt}" oninput="updateOption('${fId}', ${oIdx}, this.value)" placeholder="ตัวเลือก ${oIdx + 1}" class="px-3 py-1 rounded-lg border border-slate-200 text-xs bg-white">
                                <button onclick="removeOption('${fId}', ${oIdx})" class="text-slate-300 hover:text-rose-500 text-xs">✕</button>
                            </div>
                        `).join('')}
                        <button onclick="addOption('${fId}')" class="text-xs font-bold text-indigo-600 hover:underline">+ เพิ่มตัวเลือก</button>
                    </div>
                ` : (f.field_type === 'rating' ? `
                    <div class="pl-6 flex items-center gap-3">
                        <label class="text-[11px] font-bold text-slate-500">ช่วงคะแนนประเมิน (สเกล): 1 ถึง</label>
                        <select onchange="updateRatingMax('${fId}', this.value)" class="px-3 py-1 rounded-xl border border-slate-200 text-xs font-bold bg-white text-indigo-600">
                            ${[3, 4, 5, 6, 7, 8, 9, 10].map(num => `
                                <option value="${num}" ${(options.max || 5) == num ? 'selected' : ''}>${num} คะแนน</option>
                            `).join('')}
                        </select>
                    </div>
                ` : (f.field_type === 'rating_grid' ? `
                    <div class="pl-6 space-y-3">
                        <div class="flex items-center gap-3 pb-2 border-b border-slate-200/60">
                            <label class="text-[11px] font-bold text-slate-500">ช่วงคะแนนประเมินทุกข้อ (สเกล): 1 ถึง</label>
                            <select onchange="updateGridMax('${fId}', this.value)" class="px-3 py-1 rounded-xl border border-slate-200 text-xs font-bold bg-white text-indigo-600">
                                ${[3, 4, 5, 6, 7, 8, 9, 10].map(num => `
                                    <option value="${num}" ${(rawOpts.max || 5) == num ? 'selected' : ''}>${num} คะแนน</option>
                                `).join('')}
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase">รายการข้อคำถามย่อย:</label>
                            ${gridItems.map((itemVal, itemIdx) => `
                                <div class="flex items-center gap-2">
                                    <span class="text-indigo-500 text-xs font-bold">${itemIdx + 1}.</span>
                                    <input type="text" value="${itemVal}" oninput="updateGridItem('${fId}', ${itemIdx}, this.value)" placeholder="กรอกข้อคำถามย่อยข้อที่ ${itemIdx + 1}..." class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold bg-white">
                                    <button onclick="removeGridItem('${fId}', ${itemIdx})" class="text-slate-300 hover:text-rose-500 text-xs p-1">✕</button>
                                </div>
                            `).join('')}
                            <button onclick="addGridItem('${fId}')" class="text-xs font-bold text-indigo-600 hover:underline">+ เพิ่มข้อคำถามย่อย</button>
                        </div>
                    </div>
                ` : ''))}
            `;
            container.appendChild(item);
        });

        lucide.createIcons();
    }

    function updateLabel(id, val) {
        const f = fieldsData.find(x => (x.field_id || x.temp_id) == id);
        if (f) {
            f.field_label = val;
            triggerAutoSave();
        }
    }

    function updateRequired(id, checked) {
        const f = fieldsData.find(x => (x.field_id || x.temp_id) == id);
        if (f) {
            f.field_is_required = checked ? 1 : 0;
            triggerAutoSave();
        }
    }

    function getParsedOptions(fieldOptions) {
        if (typeof fieldOptions === 'string') {
            try { return JSON.parse(fieldOptions) || []; } catch(e) { return []; }
        }
        return Array.isArray(fieldOptions) ? fieldOptions : [];
    }

    function addOption(id) {
        const f = fieldsData.find(x => (x.field_id || x.temp_id) == id);
        if (f) {
            let opts = getParsedOptions(f.field_options);
            opts.push("");
            f.field_options = opts;
            renderFields();
            triggerAutoSave();
        }
    }

    function updateOption(id, oIdx, val) {
        const f = fieldsData.find(x => (x.field_id || x.temp_id) == id);
        if (f) {
            let opts = getParsedOptions(f.field_options);
            opts[oIdx] = val;
            f.field_options = opts;
            triggerAutoSave();
        }
    }

    function updateRatingMax(id, maxVal) {
        const f = fieldsData.find(x => (x.field_id || x.temp_id) == id);
        if (f) {
            let opts = (typeof f.field_options === 'object' && f.field_options !== null) ? f.field_options : {};
            if (typeof f.field_options === 'string') {
                try { opts = JSON.parse(f.field_options); } catch(e) { opts = {}; }
            }
            opts.max = parseInt(maxVal) || 5;
            f.field_options = opts;
            triggerAutoSave();
        }
    }

    function updateGridMax(id, maxVal) {
        const f = fieldsData.find(x => (x.field_id || x.temp_id) == id);
        if (f) {
            let opts = (typeof f.field_options === 'object' && f.field_options !== null) ? f.field_options : {};
            if (typeof f.field_options === 'string') {
                try { opts = JSON.parse(f.field_options); } catch(e) { opts = {}; }
            }
            opts.max = parseInt(maxVal) || 5;
            f.field_options = opts;
            triggerAutoSave();
        }
    }

    function addGridItem(id) {
        const f = fieldsData.find(x => (x.field_id || x.temp_id) == id);
        if (f) {
            let opts = (typeof f.field_options === 'object' && f.field_options !== null) ? f.field_options : {};
            if (typeof f.field_options === 'string') {
                try { opts = JSON.parse(f.field_options); } catch(e) { opts = {}; }
            }
            let items = Array.isArray(opts.items) ? opts.items : [];
            items.push("");
            opts.items = items;
            f.field_options = opts;
            renderFields();
            triggerAutoSave();
        }
    }

    function updateGridItem(id, itemIdx, val) {
        const f = fieldsData.find(x => (x.field_id || x.temp_id) == id);
        if (f) {
            let opts = (typeof f.field_options === 'object' && f.field_options !== null) ? f.field_options : {};
            if (typeof f.field_options === 'string') {
                try { opts = JSON.parse(f.field_options); } catch(e) { opts = {}; }
            }
            let items = Array.isArray(opts.items) ? opts.items : [];
            items[itemIdx] = val;
            opts.items = items;
            f.field_options = opts;
            triggerAutoSave();
        }
    }

    function removeGridItem(id, itemIdx) {
        const f = fieldsData.find(x => (x.field_id || x.temp_id) == id);
        if (f) {
            let opts = (typeof f.field_options === 'object' && f.field_options !== null) ? f.field_options : {};
            if (typeof f.field_options === 'string') {
                try { opts = JSON.parse(f.field_options); } catch(e) { opts = {}; }
            }
            let items = Array.isArray(opts.items) ? opts.items : [];
            items.splice(itemIdx, 1);
            opts.items = items;
            f.field_options = opts;
            renderFields();
            triggerAutoSave();
        }
    }

    function removeOption(id, oIdx) {
        const f = fieldsData.find(x => (x.field_id || x.temp_id) == id);
        if (f) {
            let opts = getParsedOptions(f.field_options);
            opts.splice(oIdx, 1);
            f.field_options = opts;
            renderFields();
            triggerAutoSave();
        }
    }

    async function saveAllFields(isAuto = false) {
        const payload = fieldsData.map(f => ({
            field_id: f.field_id || null,
            label: f.field_label,
            type: f.field_type,
            options: f.field_options,
            is_required: f.field_is_required
        }));

        const formData = new FormData();
        formData.append('fields', JSON.stringify(payload));

        const res = await fetch('<?= base_url("staff/forms/save-fields/{$form['form_id']}") ?>', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        const indicator = document.getElementById('save-indicator');

        if (data.status === 'success') {
            if (Array.isArray(data.saved_fields)) {
                data.saved_fields.forEach((sf, idx) => {
                    if (fieldsData[idx]) {
                        fieldsData[idx].field_id = sf.field_id;
                    }
                });
            }

            if (indicator) {
                indicator.innerHTML = `<span class="text-emerald-600 flex items-center gap-1"><i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> บันทึกอัตโนมัติแล้ว</span>`;
                lucide.createIcons();
            }
            if (!isAuto) {
                Swal.fire({ icon: 'success', title: 'บันทึกคำถามสำเร็จ!', timer: 1500, showConfirmButton: false });
            }
        } else {
            if (indicator) {
                indicator.innerHTML = `<span class="text-rose-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> บันทึกล้มเหลว</span>`;
                lucide.createIcons();
            }
            if (!isAuto) {
                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: data.message });
            }
        }
    }

    let generalAutoSaveTimer = null;

    function triggerGeneralAutoSave() {
        const indicator = document.getElementById('save-indicator');
        if (indicator) {
            indicator.innerHTML = `<span class="text-amber-500 flex items-center gap-1"><i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> กำลังบันทึก...</span>`;
            lucide.createIcons();
        }

        clearTimeout(generalAutoSaveTimer);
        generalAutoSaveTimer = setTimeout(() => {
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
