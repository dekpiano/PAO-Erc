<?= $this->extend('forms/layout/admin') ?>

<?= $this->section('content') ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Niramit:wght@400;600;700&display=swap');

    .preview-container {
        position: relative;
        overflow: auto;
        max-height: 550px;
        border: 2px dashed rgba(99, 102, 241, 0.3);
        border-radius: 1rem;
        background: #020617;
    }
    .preview-img {
        max-width: 100%;
        height: auto;
        display: block;
        cursor: crosshair;
    }
    .preview-img.hidden {
        display: none !important;
    }
    .coordinate-badge {
        font-family: 'Niramit', sans-serif;
        position: absolute;
        pointer-events: none;
        background: rgba(99, 102, 241, 0.9);
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: bold;
        transform: translate(-50%, -100%);
        z-index: 10;
        white-space: nowrap;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .field-card {
        display: none;
    }
    .field-card.active-card {
        display: block !important;
    }
</style>

<div class="space-y-6">

    <!-- Top Header & Actions -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-1">
                <a href="<?= base_url('staff/forms') ?>" class="hover:text-indigo-600">แบบสอบถามทั้งหมด</a>
                <span>/</span>
                <span class="text-indigo-600">ตั้งค่าเกียรติบัตร</span>
            </div>
            <h2 class="text-xl font-black text-slate-900"><?= esc($form['form_title']) ?></h2>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?= base_url("staff/forms/builder/{$form['form_id']}") ?>" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-2">
                <i data-lucide="list-checks" class="w-4 h-4 text-indigo-600"></i> จัดการข้อคำถาม
            </a>
            <button type="button" onclick="downloadDemoCert()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-extrabold text-xs flex items-center gap-2 shadow-lg shadow-emerald-100">
                <i data-lucide="eye" class="w-4 h-4"></i> ดูเกียรติบัตรตัวอย่าง (Demo)
            </button>
        </div>
    </div>

    <!-- ScienceWeek Visual Certificate Designer Form -->
    <form id="form-settings" onsubmit="submitConfig(event)" enctype="multipart/form-data">
        <input type="hidden" name="is_cert_save" value="1">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Certificate Preview Canvas (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-xl space-y-4 text-white">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-800">
                        <h3 class="text-base font-black text-amber-400 flex items-center gap-2">
                            <i data-lucide="award" class="w-5 h-5"></i> แสดงตัวอย่างเทมเพลต & คลิกจัดตำแหน่งพิกัด
                        </h3>
                        <span class="text-[10px] text-cyan-400 bg-cyan-950/40 border border-cyan-800/30 px-2 py-1 rounded-lg">คลิกที่รูปเพื่อเลือกพิกัด</span>
                    </div>

                    <!-- Template Preview Canvas -->
                    <div class="preview-container" id="container-form">
                        <?php 
                        $bgImage = $cert_config['bg_image'] ?? ($form['form_cert_template'] ?? '');
                        $imageExists = !empty($bgImage) && file_exists(FCPATH . $bgImage);
                        ?>
                        <img src="<?= $imageExists ? base_url($bgImage) : '' ?>" 
                             alt="Template Preview" 
                             id="preview-img-form" 
                             class="preview-img <?= $imageExists ? '' : 'hidden' ?>"
                             onload="if (typeof updateBadges === 'function') updateBadges()"
                             onclick="handleImageClick(event)">
                             
                        <div id="no-img-placeholder-form" class="py-24 text-center <?= $imageExists ? 'hidden' : '' ?>">
                            <i data-lucide="image" class="w-12 h-12 text-slate-600 mx-auto mb-3 opacity-50"></i>
                            <p class="text-xs text-slate-400 font-medium">กรุณาอัปโหลดรูปภาพเทมเพลตพื้นหลังเพื่อเริ่มต้นกำหนดพิกัด</p>
                        </div>
                        
                        <!-- Badges for visual position picker -->
                        <?php 
                        $certLabels = [
                            'name' => 'ชื่อ-นามสกุล (ผู้ตอบแบบสอบถาม)',
                            'date' => 'วันที่ออกเกียรติบัตร',
                            'code' => 'รหัสเกียรติบัตร',
                            'text' => 'ข้อความประเมิน/รายละเอียด'
                        ];
                        if (!empty($fields)) {
                            foreach ($fields as $f) {
                                $fKey = 'field_' . $f['field_id'];
                                $certLabels[$fKey] = $f['field_label'] ?: "ข้อคำถามที่ {$f['field_sort_order']}";
                            }
                        }

                        $getCertVal = function($param, $fKey, $default, $sortIdx = 0) use ($cert_config) {
                            $exactKey = "{$param}_{$fKey}";
                            if (isset($cert_config[$exactKey])) return $cert_config[$exactKey];
                            if (strpos($fKey, 'field_') === 0 && $sortIdx > 0 && is_array($cert_config)) {
                                $dynamicKeys = [];
                                foreach ($cert_config as $ck => $cv) {
                                    if (strpos($ck, "{$param}_field_") === 0) {
                                        $dynamicKeys[] = $ck;
                                    }
                                }
                                if (isset($dynamicKeys[$sortIdx - 1])) {
                                    return $cert_config[$dynamicKeys[$sortIdx - 1]];
                                }
                            }
                            return $default;
                        };

                        $defaultActiveField = '';
                        if (!empty($cert_config)) {
                            $counter = 0;
                            foreach ($certLabels as $fKey => $fLabel) {
                                if (strpos($fKey, 'field_') === 0) $counter++;
                                $chkEnabled = (int) $getCertVal('enabled', $fKey, 0, $counter);
                                if ($chkEnabled === 1) {
                                    $defaultActiveField = $fKey;
                                    break;
                                }
                            }
                            if (empty($defaultActiveField) && !empty($certLabels)) {
                                $defaultActiveField = array_key_first($certLabels);
                            }
                        }

                        foreach ($certLabels as $fKey => $fLabel): 
                        ?>
                            <div id="badge-form-<?= $fKey ?>" class="coordinate-badge hidden">
                                <?= esc($fLabel) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-2">อัปโหลดรูปภาพเทมเพลตพื้นหลัง (แนะนำ 1920x1357px A4 แนวนอน, JPG/PNG)</label>
                        <input type="file" name="form_cert_template" accept="image/png, image/jpeg" onchange="previewTemplate(this)" class="w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- Right Column: Coordinates & Typography Controls (5 cols) -->
            <div class="lg:col-span-5 space-y-6">

                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-base font-black text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i data-lucide="sliders" class="w-5 h-5 text-indigo-600"></i> สวิตช์และตั้งค่าพิกัดข้อความ
                    </h3>

                    <div>
                        <label class="flex items-center gap-3 cursor-pointer p-3.5 bg-amber-50 rounded-2xl border border-amber-200 w-full mb-4">
                            <input type="checkbox" name="form_has_certificate" value="1" <?= $form['form_has_certificate'] == 1 ? 'checked' : '' ?> class="w-5 h-5 text-amber-600 rounded">
                            <div>
                                <span class="text-xs font-black text-amber-950 block">เปิดใช้งานเกียรติบัตรออนไลน์สำหรับแบบสอบถามนี้</span>
                                <span class="text-[10px] text-amber-700 font-medium">ผู้ตอบแบบสอบถามจะได้รับเกียรติบัตรหลังทำเสร็จ</span>
                            </div>
                        </label>
                    </div>

                    <!-- Active Field Selector -->
                    <div class="p-4 bg-slate-900 text-white rounded-2xl border border-slate-800 space-y-2">
                        <label class="block text-[11px] font-extrabold text-amber-400 uppercase tracking-wider">เลือกฟิลด์ที่ต้องการตั้งค่าและจัดพิกัด</label>
                        <select id="active-field-select" onchange="switchActiveField(this.value)" class="w-full bg-slate-950 border border-slate-700 rounded-xl py-2 px-3 text-xs text-white font-bold focus:outline-none focus:border-amber-500">
                            <option value="" <?= empty($defaultActiveField) ? 'selected' : '' ?> disabled>-- กรุณาเลือกฟิลด์ที่ต้องการจัดตำแหน่ง --</option>
                            <?php foreach ($certLabels as $fKey => $fLabel): ?>
                                <option value="<?= $fKey ?>" <?= $fKey === $defaultActiveField ? 'selected' : '' ?>><?= esc($fLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-[10px] text-slate-400 leading-relaxed">เลือกฟิลด์ที่ต้องการจัดวาง แล้วคลิกหรือลากจุดบนภาพทางซ้าย เพื่อปรับพิกัด X, Y</p>
                    </div>

                    <!-- Placeholder Notice when no field is selected -->
                    <div id="no-field-selected-notice" class="p-6 bg-slate-50 rounded-2xl border border-slate-200 border-dashed text-center <?= !empty($defaultActiveField) ? 'hidden' : '' ?>" style="<?= !empty($defaultActiveField) ? 'display: none;' : '' ?>">
                        <i data-lucide="mouse-pointer-click" class="w-8 h-8 text-indigo-500 mx-auto mb-2 opacity-60"></i>
                        <p class="text-xs font-bold text-slate-600">กรุณาเลือกฟิลด์ในดรอปดาวน์ด้านบน</p>
                        <p class="text-[10px] text-slate-400 mt-1">เพื่อเปิดหน้าต่างตั้งค่าพิกัด ขนาด และสไตล์ฟอนต์ของฟิลด์นั้นๆ</p>
                    </div>

                    <!-- Card Settings Container (Shows only selected field) -->
                    <div id="field-cards-container">
                        <?php 
                        $fieldSortCounter = 0;
                        foreach ($certLabels as $fKey => $fLabel): 
                            if (strpos($fKey, 'field_') === 0) $fieldSortCounter++;

                            $isEnabled = (int)$getCertVal('enabled', $fKey, 0, $fieldSortCounter) === 1;
                            $xVal = $getCertVal('x', $fKey, 960, $fieldSortCounter);
                            $yVal = $getCertVal('y', $fKey, 600, $fieldSortCounter);
                            $sizeVal = $getCertVal('size', $fKey, 32, $fieldSortCounter);
                            $alignVal = $getCertVal('align', $fKey, 'center', $fieldSortCounter);
                            $colorVal = $getCertVal('color', $fKey, '#000000', $fieldSortCounter);
                            $parentVal = $getCertVal('parent', $fKey, 'none', $fieldSortCounter);
                            $weightVal = $getCertVal('weight', $fKey, 'bold', $fieldSortCounter);
                            $isActiveCard = ($fKey === $defaultActiveField);
                        ?>
                            <div id="card-<?= $fKey ?>" class="field-card <?= $isActiveCard ? 'active-card' : '' ?> p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                                <div class="flex justify-between items-center pb-2 border-b border-slate-200/80">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="enabled_<?= $fKey ?>" value="1" <?= $isEnabled ? 'checked' : '' ?> onchange="updateBadges()" class="w-4 h-4 rounded border-slate-300 text-indigo-600">
                                        <span class="text-xs font-extrabold text-slate-900">แสดง <?= esc($fLabel) ?></span>
                                    </label>
                                    <span class="text-[10px] font-mono text-slate-400 font-bold">#<?= $fKey ?></span>
                                </div>

                                <div>
                                    <label class="block text-[10px] text-slate-500 font-bold mb-1">การต่อท้ายข้อความ (เลือกฟิลด์ที่จะนำข้อความมาต่อท้ายฟิลด์นี้)</label>
                                    <select name="parent_<?= $fKey ?>" onchange="toggleFieldParentControls('<?= $fKey ?>')" class="w-full bg-white border border-slate-200 rounded-xl py-2 px-3 text-xs text-slate-800 font-bold">
                                        <option value="none" <?= $parentVal === 'none' ? 'selected' : '' ?>>แสดงเฉพาะฟิลด์นี้ (ไม่มีข้อความฟิลด์อื่นมาต่อท้าย)</option>
                                        <?php foreach ($certLabels as $oKey => $oLabel): ?>
                                            <?php if ($oKey !== $fKey): ?>
                                                <option value="<?= $oKey ?>" <?= $parentVal === $oKey ? 'selected' : '' ?>>ดึงข้อความจาก: <?= esc($oLabel) ?> ➔ มาต่อท้ายฟิลด์นี้</option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="text-[9px] text-slate-400 mt-1">หมายเหตุ: ข้อความจากฟิลด์ที่เลือกจะถูกนำมาต่อท้ายฟิลด์นี้ในพิกัด X, Y และสไตล์เดียวกัน</p>
                                </div>

                                <div id="controls-<?= $fKey ?>" class="space-y-3">
                                    <div class="grid grid-cols-2 gap-2.5">
                                        <div>
                                            <label class="block text-[10px] text-slate-500 mb-1 font-bold">พิกัด X (px)</label>
                                            <input type="number" name="x_<?= $fKey ?>" value="<?= $xVal ?>" oninput="updateBadges()" class="w-full bg-white border border-slate-200 rounded-xl py-1.5 px-3 text-xs text-slate-800 font-mono font-bold">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] text-slate-500 mb-1 font-bold">พิกัด Y (px)</label>
                                            <input type="number" name="y_<?= $fKey ?>" value="<?= $yVal ?>" oninput="updateBadges()" class="w-full bg-white border border-slate-200 rounded-xl py-1.5 px-3 text-xs text-slate-800 font-mono font-bold">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] text-slate-500 mb-1 font-bold">ขนาดฟอนต์ (px)</label>
                                            <input type="number" name="size_<?= $fKey ?>" value="<?= $sizeVal ?>" oninput="updateBadges()" class="w-full bg-white border border-slate-200 rounded-xl py-1.5 px-3 text-xs text-slate-800 font-mono font-bold">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] text-slate-500 mb-1 font-bold">จัดตำแหน่ง</label>
                                            <select name="align_<?= $fKey ?>" onchange="updateBadges()" class="w-full bg-white border border-slate-200 rounded-xl py-1.5 px-3 text-xs text-slate-800 font-semibold">
                                                <option value="left" <?= $alignVal === 'left' ? 'selected' : '' ?>>ชิดซ้าย</option>
                                                <option value="center" <?= $alignVal === 'center' ? 'selected' : '' ?>>กึ่งกลาง</option>
                                                <option value="right" <?= $alignVal === 'right' ? 'selected' : '' ?>>ชิดขวา</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-[10px] text-slate-500 mb-1 font-bold">ความหนาฟอนต์</label>
                                            <select name="weight_<?= $fKey ?>" onchange="updateBadges()" class="w-full bg-white border border-slate-200 rounded-xl py-1.5 px-3 text-xs text-slate-800 font-semibold">
                                                <option value="regular" <?= $weightVal === 'regular' ? 'selected' : '' ?>>ตัวบาง (Regular)</option>
                                                <option value="bold" <?= $weightVal === 'bold' ? 'selected' : '' ?>>ตัวหนา (Bold)</option>
                                                <option value="extrabold" <?= $weightVal === 'extrabold' ? 'selected' : '' ?>>ตัวหนาพิเศษ (Extra Bold)</option>
                                                <option value="ultrabold" <?= $weightVal === 'ultrabold' ? 'selected' : '' ?>>ตัวหนามาก (Ultra Bold)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-[10px] text-slate-500 font-bold">สีตัวอักษร</span>
                                        <div class="flex items-center gap-2">
                                            <input type="color" name="color_<?= $fKey ?>" value="<?= $colorVal ?>" class="w-7 h-7 bg-transparent border-0 cursor-pointer p-0">
                                            <input type="text" value="<?= $colorVal ?>" oninput="syncColorPicker(this)" class="w-20 bg-white border border-slate-200 rounded-xl py-1 px-2 text-[10px] text-slate-800 text-center font-mono font-bold">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black rounded-2xl text-xs transition-all flex items-center justify-center gap-1.5 shadow-lg shadow-amber-500/20">
                        <i data-lucide="save" class="w-4 h-4"></i> บันทึกการตั้งค่าเกียรติบัตร
                    </button>
                </div>

            </div>
        </div>
    </form>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let draggingBadge = null;
    let dragStartX = 0, dragStartY = 0;
    let badgeStartX = 0, badgeStartY = 0;
    let dragCachedRect = null, dragCachedNaturalWidth = 0, dragCachedNaturalHeight = 0;

    function previewTemplate(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('preview-img-form');
                const placeholder = document.getElementById('no-img-placeholder-form');
                img.src = e.target.result;
                img.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
                updateBadges();
                img.onload = function() { updateBadges(); };
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const activeSelect = document.getElementById('active-field-select');
        if (activeSelect && activeSelect.value) {
            switchActiveField(activeSelect.value);
        }
        setTimeout(() => updateBadges(), 200);

        window.addEventListener('mousemove', (e) => {
            if (!draggingBadge || !dragCachedNaturalWidth || !dragCachedNaturalHeight) return;

            const dx = e.clientX - dragStartX;
            const dy = e.clientY - dragStartY;

            const deltaXNatural = (dx / dragCachedRect.width) * dragCachedNaturalWidth;
            const deltaYNatural = (dy / dragCachedRect.height) * dragCachedNaturalHeight;

            let newX = Math.round(badgeStartX + deltaXNatural);
            let newY = Math.round(badgeStartY + deltaYNatural);

            newX = Math.max(0, Math.min(dragCachedNaturalWidth, newX));
            newY = Math.max(0, Math.min(dragCachedNaturalHeight, newY));

            const fieldKey = draggingBadge.id.replace('badge-form-', '');
            const form = document.getElementById('form-settings');
            const xInput = form.querySelector(`input[name="x_${fieldKey}"]`);
            const yInput = form.querySelector(`input[name="y_${fieldKey}"]`);

            if (xInput) xInput.value = newX;
            if (yInput) yInput.value = newY;

            const displayX = (newX / dragCachedNaturalWidth) * dragCachedRect.width;
            const displayY = (newY / dragCachedNaturalHeight) * dragCachedRect.height;
            draggingBadge.style.left = `${displayX}px`;
            draggingBadge.style.top = `${displayY}px`;
        });

        window.addEventListener('mouseup', () => {
            if (draggingBadge) {
                draggingBadge.classList.remove('dragging');
                updateBadges();
                draggingBadge = null;
            }
        });

        const container = document.getElementById('container-form');
        if (container) {
            container.addEventListener('mousedown', (e) => {
                const badge = e.target.closest('.coordinate-badge');
                if (!badge) return;

                e.preventDefault();
                draggingBadge = badge;
                draggingBadge.classList.add('dragging');

                const fieldKey = badge.id.replace('badge-form-', '');
                switchActiveField(fieldKey);

                const form = document.getElementById('form-settings');
                const xInput = form.querySelector(`input[name="x_${fieldKey}"]`);
                const yInput = form.querySelector(`input[name="y_${fieldKey}"]`);

                const img = document.getElementById('preview-img-form');
                dragCachedRect = img.getBoundingClientRect();
                dragCachedNaturalWidth = img.naturalWidth;
                dragCachedNaturalHeight = img.naturalHeight;

                dragStartX = e.clientX;
                dragStartY = e.clientY;
                badgeStartX = parseInt(xInput.value) || 0;
                badgeStartY = parseInt(yInput.value) || 0;
            });
        }
    });

    function switchActiveField(fKey) {
        if (!fKey) return;

        const activeSelect = document.getElementById('active-field-select');
        if (activeSelect && activeSelect.value !== fKey) {
            activeSelect.value = fKey;
        }

        const notice = document.getElementById('no-field-selected-notice');
        if (notice) {
            notice.style.display = 'none';
        }

        document.querySelectorAll('.field-card').forEach(card => {
            card.classList.remove('active-card');
        });

        const targetCard = document.getElementById(`card-${fKey}`);
        if (targetCard) {
            targetCard.classList.add('active-card');
        }
    }

    function previewTemplate(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('preview-img-form');
                const placeholder = document.getElementById('no-img-placeholder-form');
                
                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
                
                img.onload = function() {
                    updateBadges();
                };
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleFieldParentControls(fKey) {
        updateBadges();
    }

    function handleImageClick(event) {
        if (event.target.closest('.coordinate-badge')) return;

        const img = document.getElementById('preview-img-form');
        const rect = img.getBoundingClientRect();
        
        const clickX = event.clientX - rect.left;
        const clickY = event.clientY - rect.top;
        
        const naturalWidth = img.naturalWidth;
        const naturalHeight = img.naturalHeight;
        
        if (naturalWidth && naturalHeight) {
            const scaledX = Math.round((clickX / rect.width) * naturalWidth);
            const scaledY = Math.round((clickY / rect.height) * naturalHeight);
            
            const activeSelect = document.getElementById('active-field-select');
            const activeField = activeSelect ? activeSelect.value : '';
            if (!activeField) return;

            // Check if this field is consumed as append-target by another field → block clicks
            const form = document.getElementById('form-settings');
            let isConsumed = false;
            certFieldsList.forEach(f => {
                if (f === activeField) return;
                const ps = form.querySelector(`select[name="parent_${f}"]`);
                if (ps && ps.value === activeField) isConsumed = true;
            });
            if (isConsumed) return;
            
            const xInput = form.querySelector(`input[name="x_${activeField}"]`);
            const yInput = form.querySelector(`input[name="y_${activeField}"]`);
            
            if (xInput && yInput) {
                xInput.value = scaledX;
                yInput.value = scaledY;
                updateBadges();
            }
        }
    }

    function syncColorPicker(input) {
        const picker = input.previousElementSibling;
        if (picker && /^#[0-9A-F]{6}$/i.test(input.value)) {
            picker.value = input.value;
            updateBadges();
        }
    }

    document.querySelectorAll('input[type="color"]').forEach(picker => {
        picker.addEventListener('input', (e) => {
            const textInput = e.target.nextElementSibling;
            if (textInput) {
                textInput.value = e.target.value;
                updateBadges();
            }
        });
    });

    const certFieldsList = <?= json_encode(array_keys($certLabels)) ?>;

    function getFieldText(fieldKey, form, samples, visited = new Set()) {
        if (visited.has(fieldKey)) return '';
        visited.add(fieldKey);
        
        let text = samples[fieldKey] || '';
        
        // ดูว่าฟิลด์นี้เลือกดึงฟิลด์ไหนมาต่อท้าย
        const parentSelect = form.querySelector(`select[name="parent_${fieldKey}"]`);
        const appendKey = parentSelect ? parentSelect.value : 'none';
        
        if (appendKey !== 'none') {
            text += ' ' + getFieldText(appendKey, form, samples, visited);
        }
        
        return text;
    }

    function updateBadges() {
        const img = document.getElementById('preview-img-form');
        if (!img || !img.complete || img.naturalWidth === 0) return;

        const rect = img.getBoundingClientRect();
        const naturalWidth = img.naturalWidth;
        const naturalHeight = img.naturalHeight;
        const form = document.getElementById('form-settings');

        const samples = {
            date: 'ให้ไว้ ณ วันที่ 11 สิงหาคม 2569',
            code: 'เลขที่: CERT-2026-DEMO',
            name: 'นายสมชาย รักดี',
            text: 'ได้ผ่านการตอบแบบสอบถามเรื่อง "<?= esc($form['form_title']) ?>"'
        };

        <?php if (!empty($fields)): ?>
            <?php foreach ($fields as $f): ?>
                samples['field_<?= $f['field_id'] ?>'] = '<?= esc($f['field_label'] ?: "ข้อคำถามที่ {$f['field_sort_order']}", "js") ?>';
            <?php endforeach; ?>
        <?php endif; ?>

        // สร้าง set ของฟิลด์ที่ถูกดึงไปต่อท้ายฟิลด์อื่น → ไม่ต้องแสดงแยก
        const consumedFields = new Set();
        certFieldsList.forEach(f => {
            const ps = form.querySelector(`select[name="parent_${f}"]`);
            const pv = ps ? ps.value : 'none';
            if (pv !== 'none') consumedFields.add(pv);
        });

        certFieldsList.forEach(field => {
            const enabledInput = form.querySelector(`input[name="enabled_${field}"]`);
            const isEnabled = enabledInput ? enabledInput.checked : false;

            const badge = document.getElementById(`badge-form-${field}`);
            if (!badge) return;

            // แสดง badge เฉพาะฟิลด์ที่ enabled และไม่ถูกดึงไปต่อท้ายฟิลด์อื่น
            if (isEnabled && !consumedFields.has(field)) {
                const xVal = parseInt(form.querySelector(`input[name="x_${field}"]`).value) || 0;
                const yVal = parseInt(form.querySelector(`input[name="y_${field}"]`).value) || 0;
                const sizeVal = parseInt(form.querySelector(`input[name="size_${field}"]`).value) || 32;
                const alignVal = form.querySelector(`select[name="align_${field}"]`).value || 'center';
                const weightVal = form.querySelector(`select[name="weight_${field}"]`)?.value || 'bold';
                
                badge.innerText = getFieldText(field, form, samples);
                
                const displayX = (xVal / naturalWidth) * rect.width;
                const displayY = (yVal / naturalHeight) * rect.height;
                const displayFontSize = sizeVal * (rect.width / naturalWidth);

                badge.style.left = `${displayX}px`;
                badge.style.top = `${displayY}px`;
                badge.style.fontSize = `${displayFontSize}px`;
                badge.style.fontWeight = weightVal === 'regular' ? 'normal' : 'bold';
                
                if (alignVal === 'left') {
                    badge.style.transform = 'translate(0%, -50%)';
                } else if (alignVal === 'right') {
                    badge.style.transform = 'translate(-100%, -50%)';
                } else {
                    badge.style.transform = 'translate(-50%, -50%)';
                }

                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    }

    window.addEventListener('resize', updateBadges);

    function downloadDemoCert() {
        window.open('<?= base_url("forms/certificate/demo?form_id={$form['form_id']}") ?>', '_blank');
    }

    function submitConfig(event) {
        event.preventDefault();
        const form = document.getElementById('form-settings');
        const fileInput = form.querySelector('input[name="form_cert_template"]');
        const file = fileInput ? fileInput.files[0] : null;

        if (file) {
            uploadChunks(file, function(uploadedFilename) {
                proceedWithSave(form, uploadedFilename);
            });
        } else {
            proceedWithSave(form, null);
        }
    }

    function uploadChunks(file, callback) {
        const chunkSize = 512 * 1024;
        const totalChunks = Math.ceil(file.size / chunkSize);
        const fileId = Math.random().toString(36).substring(2, 15);
        let currentChunk = 0;

        Swal.fire({
            title: 'กำลังอัปโหลดรูปภาพเทมเพลต...',
            html: 'กรุณารอสักครู่ (0%)',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading()
        });

        function sendNextChunk() {
            const start = currentChunk * chunkSize;
            const end = Math.min(start + chunkSize, file.size);
            const chunk = file.slice(start, end);

            const chunkForm = new FormData();
            chunkForm.append('file_id', fileId);
            chunkForm.append('chunk_index', currentChunk);
            chunkForm.append('total_chunks', totalChunks);
            chunkForm.append('filename', file.name);
            chunkForm.append('chunk', chunk);

            fetch('<?= base_url('staff/forms/upload-chunk') ?>', {
                method: 'POST',
                body: chunkForm,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' || data.status === 'uploading') {
                    currentChunk++;
                    const percent = Math.round((currentChunk / totalChunks) * 100);
                    Swal.getHtmlContainer().textContent = `กำลังอัปโหลด... (${percent}%)`;

                    if (currentChunk < totalChunks) {
                        sendNextChunk();
                    } else {
                        Swal.close();
                        callback(data.filename);
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'อัปโหลดล้มเหลว', text: data.message });
                }
            });
        }
        sendNextChunk();
    }

    function proceedWithSave(form, uploadedFilename) {
        const formData = new FormData(form);

        if (uploadedFilename) {
            formData.append('bg_image_uploaded', uploadedFilename);
            formData.delete('form_cert_template');
        }

        Swal.fire({
            title: 'กำลังบันทึกข้อมูล...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('<?= base_url("staff/forms/save-cert-settings/{$form['form_id']}") ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => window.location.reload());
            } else {
                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: data.message });
            }
        });
    }
</script>
<?= $this->endSection() ?>
