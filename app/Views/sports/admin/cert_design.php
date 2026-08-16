<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<?= view('sports/admin/layout/nav') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Niramit:wght@400;600;700&display=swap');

.preview-container {
    position: relative;
    overflow: auto;
    max-height: 580px;
    border: 2px dashed rgba(16, 185, 129, 0.4);
    border-radius: 1.25rem;
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
    background: rgba(5, 150, 105, 0.95);
    color: white;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: bold;
    transform: translate(-50%, -50%);
    z-index: 10;
    white-space: nowrap;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
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
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-1">
                <a href="<?= base_url('staff/sports/certificates') ?>" class="hover:text-emerald-600">รายการเกียรติบัตรทั้งหมด</a>
                <span>/</span>
                <span class="text-emerald-600">ตั้งค่าพิกัด Visual Designer</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900"><?= esc($cert['cert_title']) ?></h1>
            <p class="text-xs text-slate-400 mt-0.5">
                ใช้สำหรับ: <strong class="text-slate-700"><?= $category ? esc($category['sport_name']) . ' - ' . esc($category['category_name']) : 'ทุกชนิดกีฬา/รุ่น' ?></strong>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?= base_url("staff/sports/certificates/demo/{$cert['cert_id']}") ?>" target="_blank" class="px-4 py-2.5 bg-amber-400 hover:bg-amber-500 text-slate-950 rounded-xl font-extrabold text-xs flex items-center gap-2 shadow-lg shadow-amber-400/20 transition-all hover:scale-105">
                <i data-lucide="eye" class="w-4 h-4"></i> ดูเกียรติบัตรตัวอย่าง (Demo)
            </a>
            <a href="<?= base_url('staff/sports/certificates') ?>" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs">
                กลับหน้ารายการ
            </a>
        </div>
    </div>

    <!-- Visual Certificate Designer Form -->
    <form id="form-cert-design" onsubmit="submitCertConfig(event)" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Certificate Preview Canvas (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-slate-900 p-6 rounded-3xl border border-slate-800 shadow-xl space-y-4 text-white">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-800">
                        <h3 class="text-sm sm:text-base font-black text-amber-400 flex items-center gap-2">
                            <i data-lucide="award" class="w-5 h-5"></i> แสดงตัวอย่างเทมเพลต & คลิกจัดตำแหน่งพิกัด
                        </h3>
                        <span class="text-[10px] text-emerald-400 bg-emerald-950/60 border border-emerald-800/40 px-2.5 py-1 rounded-lg font-bold">
                            คลิกที่รูปเพื่อเลือกพิกัด
                        </span>
                    </div>

                    <!-- Template Preview Canvas -->
                    <div class="preview-container" id="container-cert">
                        <?php 
                        $bgImage = $cert_config['bg_image'] ?? ($cert['cert_template'] ?? '');
                        $imageExists = !empty($bgImage) && file_exists(FCPATH . $bgImage);
                        ?>
                        <img src="<?= $imageExists ? base_url($bgImage) : '' ?>" 
                             alt="Template Preview" 
                             id="preview-img-cert" 
                             class="preview-img <?= $imageExists ? '' : 'hidden' ?>"
                             onload="if (typeof updateBadges === 'function') updateBadges()"
                             onclick="handleImageClick(event)">
                             
                        <div id="no-img-placeholder-cert" class="py-28 text-center <?= $imageExists ? 'hidden' : '' ?>">
                            <i data-lucide="image" class="w-12 h-12 text-slate-600 mx-auto mb-3 opacity-50"></i>
                            <p class="text-xs text-slate-400 font-medium">กรุณาอัปโหลดรูปภาพเทมเพลตเกียรติบัตร (.PNG / .JPG ขนาด 1920x1080 หรือ 3508x2480)</p>
                        </div>
                        
                        <!-- Badges for visual position picker -->
                        <?php 
                        $certLabels = [
                            'name'     => 'ชื่อ - นามสกุล (นักกีฬา / ผู้ฝึกสอน)',
                            'award'    => 'รางวัลที่ได้รับ (เช่น ชนะเลิศ / รองชนะเลิศ)',
                            'category' => 'ชนิดกีฬา / ประเภท (เช่น กีฬาฟุตบอล ประเภททีม)',
                            'model'    => 'รุ่นการแข่งขัน (เช่น รุ่นอายุไม่เกิน 15 ปี (ชาย))',
                            'school'   => 'ชื่อโรงเรียน / สังกัด',
                            'date'     => 'วันที่ออกเกียรติบัตร (พ.ศ.)',
                            'code'     => 'รหัสเกียรติบัตร (Cert No.)'
                        ];

                        foreach ($certLabels as $fKey => $fLabel):
                        ?>
                            <div id="badge-cert-<?= $fKey ?>" class="coordinate-badge hidden">
                                <?= $fLabel ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Upload Template Input -->
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-300 mb-2">อัปโหลด / เปลี่ยนรูปภาพพื้นหลังเกียรติบัตร</label>
                        <div class="flex items-center gap-3">
                            <input type="file" name="cert_template" id="cert-template-file" accept="image/png, image/jpeg" 
                                   onchange="previewUploadedImage(this)"
                                   class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer bg-slate-800/80 border border-slate-700 rounded-xl p-1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Visual Fields Config Panel (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                            <i data-lucide="sliders" class="w-4 h-4 text-emerald-600"></i> ตั้งค่าพิกัดและขนาดตัวอักษร
                        </h3>
                        <span class="text-[10px] text-slate-400">เลือกแท็บด้านล่างเพื่อปรับพิกัด</span>
                    </div>

                    <!-- Field Selection Tabs -->
                    <div class="flex flex-wrap gap-1.5 p-1.5 bg-slate-50 border border-slate-100 rounded-2xl">
                        <?php 
                        $firstField = true;
                        foreach ($certLabels as $fKey => $fLabel): 
                        ?>
                            <button type="button" 
                                    onclick="switchFieldTab('<?= $fKey ?>')" 
                                    id="tab-btn-<?= $fKey ?>"
                                    class="tab-btn px-3 py-1.5 rounded-xl text-xs font-bold transition-all <?= $firstField ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>">
                                <?= $fKey === 'name' ? '1. ชื่อผู้รับ' : ($fKey === 'award' ? '2. รางวัล' : ($fKey === 'category' ? '3. ประเภทกีฬา' : ($fKey === 'model' ? '4. รุ่นอายุ' : ($fKey === 'school' ? '5. โรงเรียน' : ($fKey === 'date' ? '6. วันที่' : '7. รหัส'))))) ?>
                            </button>
                        <?php 
                        $firstField = false;
                        endforeach; 
                        ?>
                    </div>

                    <!-- Field Configuration Cards -->
                    <?php 
                    $firstCard = true;
                    foreach ($certLabels as $fKey => $fLabel): 
                        $enabled = (int) ($cert_config["enabled_{$fKey}"] ?? 1);
                        $x       = (int) ($cert_config["x_{$fKey}"] ?? 960);
                        $y       = (int) ($cert_config["y_{$fKey}"] ?? ($fKey === 'name' ? 520 : ($fKey === 'school' ? 580 : ($fKey === 'award' ? 640 : ($fKey === 'event' ? 700 : ($fKey === 'date' ? 780 : 140))))));
                        $size    = (int) ($cert_config["size_{$fKey}"] ?? ($fKey === 'name' ? 42 : ($fKey === 'award' ? 34 : 28)));
                        $align   = $cert_config["align_{$fKey}"] ?? ($fKey === 'code' ? 'right' : 'center');
                        $weight  = $cert_config["weight_{$fKey}"] ?? ($fKey === 'name' || $fKey === 'award' ? 'bold' : 'regular');
                        $color   = $cert_config["color_{$fKey}"] ?? ($fKey === 'award' ? '#b45309' : '#0f172a');
                    ?>
                        <div id="card-<?= $fKey ?>" class="field-card <?= $firstCard ? 'active-card' : '' ?> space-y-4 bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-black text-slate-800"><?= $fLabel ?></label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="enabled_<?= $fKey ?>" value="0">
                                    <input type="checkbox" name="enabled_<?= $fKey ?>" value="1" <?= $enabled ? 'checked' : '' ?> onchange="updateBadges()" class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                                    <span class="ml-2 text-[11px] font-bold text-slate-500">แสดงผล</span>
                                </label>
                            </div>

                            <!-- Text Concatenation / ต่อท้ายข้อความบรรทัดเดียวกัน -->
                            <div class="bg-amber-50/60 p-3 rounded-xl border border-amber-200/60 space-y-2">
                                <label class="block text-[11px] font-black text-amber-900 flex items-center gap-1.5">
                                    <i data-lucide="link" class="w-3.5 h-3.5 text-amber-600"></i>
                                    <span>การต่อท้ายข้อความ (รวมข้อความในบรรทัดเดียวกัน)</span>
                                </label>
                                <select name="parent_<?= $fKey ?>" onchange="toggleFieldParentControls('<?= $fKey ?>')" class="w-full px-3 py-2 rounded-xl border border-amber-200 bg-white text-xs font-bold text-slate-800">
                                    <option value="none" <?= ($cert_config["parent_{$fKey}"] ?? 'none') === 'none' ? 'selected' : '' ?>>-- แสดงแยกบรรทัดเดี่ยว (ไม่ต่อท้ายข้อความอื่น) --</option>
                                    <?php foreach ($certLabels as $oKey => $oLabel): ?>
                                        <?php if ($oKey !== $fKey): ?>
                                            <option value="<?= $oKey ?>" <?= ($cert_config["parent_{$fKey}"] ?? 'none') === $oKey ? 'selected' : '' ?>>
                                                ดึงข้อความ: <?= esc($oLabel) ?> ➔ มาต่อท้ายฟิลด์นี้
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <p class="text-[10px] text-amber-700/80">ตัวอย่าง: นำ <strong>"ประเภทกีฬา"</strong> มาต่อท้าย <strong>"รางวัล"</strong> หรือนำ <strong>"รุ่นอายุ"</strong> มาต่อท้าย <strong>"ประเภทกีฬา"</strong> ให้อยู่บรรทัดเดียวกันได้ทันที</p>
                            </div>

                            <div id="controls-<?= $fKey ?>" class="space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">พิกัด X (แนวนอน)</label>
                                        <input type="number" name="x_<?= $fKey ?>" value="<?= $x ?>" oninput="updateBadges()" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">พิกัด Y (แนวตั้ง)</label>
                                        <input type="number" name="y_<?= $fKey ?>" value="<?= $y ?>" oninput="updateBadges()" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold">
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">ขนาดฟอนต์ (pt)</label>
                                        <input type="number" name="size_<?= $fKey ?>" value="<?= $size ?>" oninput="updateBadges()" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">การจัดวาง</label>
                                        <select name="align_<?= $fKey ?>" onchange="updateBadges()" class="w-full px-2 py-2 rounded-xl border border-slate-200 text-xs font-medium">
                                            <option value="center" <?= $align === 'center' ? 'selected' : '' ?>>กึ่งกลาง</option>
                                            <option value="left" <?= $align === 'left' ? 'selected' : '' ?>>ชิดซ้าย</option>
                                            <option value="right" <?= $align === 'right' ? 'selected' : '' ?>>ชิดขวา</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">น้ำหนักฟอนต์</label>
                                        <select name="weight_<?= $fKey ?>" onchange="updateBadges()" class="w-full px-2 py-2 rounded-xl border border-slate-200 text-xs font-medium">
                                            <option value="regular" <?= $weight === 'regular' ? 'selected' : '' ?>>ปกติ</option>
                                            <option value="bold" <?= $weight === 'bold' ? 'selected' : '' ?>>ตัวหนา</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">สีตัวอักษร</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" name="color_<?= $fKey ?>" value="<?= $color ?>" oninput="updateBadges()" class="w-8 h-8 rounded-lg border border-slate-200 cursor-pointer p-0.5">
                                        <input type="text" value="<?= $color ?>" oninput="this.previousElementSibling.value = this.value; updateBadges();" class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-mono">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                    $firstCard = false;
                    endforeach; 
                    ?>

                    <!-- Submit Button -->
                    <div class="pt-3 border-t border-slate-100">
                        <button type="submit" id="btn-save-cert" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-extrabold text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-200 transition-all cursor-pointer">
                            <i data-lucide="save" class="w-4 h-4 text-amber-300"></i>
                            <span>บันทึกการตั้งค่าพิกัดเกียรติบัตร</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>

<script>
    let activeFieldTab = 'name';
    const certFields = <?= json_encode(array_keys($certLabels)) ?>;

    function switchFieldTab(fieldKey) {
        activeFieldTab = fieldKey;
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-emerald-600', 'text-white', 'shadow-sm');
            btn.classList.add('text-slate-600');
        });
        const activeBtn = document.getElementById(`tab-btn-${fieldKey}`);
        if (activeBtn) {
            activeBtn.classList.add('bg-emerald-600', 'text-white', 'shadow-sm');
            activeBtn.classList.remove('text-slate-600');
        }

        document.querySelectorAll('.field-card').forEach(card => card.classList.remove('active-card'));
        const activeCard = document.getElementById(`card-${fieldKey}`);
        if (activeCard) activeCard.classList.add('active-card');
    }

    function handleImageClick(e) {
        const img = document.getElementById('preview-img-cert');
        if (!img || !img.naturalWidth) return;

        const rect = img.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const clickY = e.clientY - rect.top;

        const naturalX = Math.round((clickX / rect.width) * img.naturalWidth);
        const naturalY = Math.round((clickY / rect.height) * img.naturalHeight);

        const form = document.getElementById('form-cert-design');
        const xInput = form.querySelector(`input[name="x_${activeFieldTab}"]`);
        const yInput = form.querySelector(`input[name="y_${activeFieldTab}"]`);

        if (xInput && yInput) {
            xInput.value = naturalX;
            yInput.value = naturalY;
            updateBadges();
            
            // SweetAlert mini toast
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `กำหนดพิกัด ${activeFieldTab} สำเร็จ (X: ${naturalX}, Y: ${naturalY})`,
                showConfirmButton: false,
                timer: 1500
            });
        }
    }

    function toggleFieldParentControls(fieldKey) {
        updateBadges();
    }

    function getFieldText(fieldKey, form, samples, visited = new Set()) {
        if (visited.has(fieldKey)) return '';
        visited.add(fieldKey);

        let text = samples[fieldKey] || '';
        const parentSelect = form.querySelector(`select[name="parent_${fieldKey}"]`);
        const appendKey = parentSelect ? parentSelect.value : 'none';

        if (appendKey !== 'none') {
            text += '   ' + getFieldText(appendKey, form, samples, visited);
        }

        return text;
    }

    function updateBadges() {
        const img = document.getElementById('preview-img-cert');
        if (!img || !img.complete || img.naturalWidth === 0) return;

        const rect = img.getBoundingClientRect();
        const naturalWidth = img.naturalWidth;
        const naturalHeight = img.naturalHeight;
        const form = document.getElementById('form-cert-design');

        const samples = {
            name: 'นายสมชาย รักดี',
            award: 'ได้รับรางวัล ชนะเลิศ',
            category: 'กีฬาฟุตบอล (ประเภททีม)',
            model: 'ประเภท รุ่นอายุไม่เกิน 15 ปี (ชาย)',
            school: 'โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์',
            date: 'ให้ไว้ ณ วันที่ 14 สิงหาคม พ.ศ. 2569',
            code: 'PAO-SP-2569/0001'
        };

        // ตรวจหาฟิลด์ที่ถูกนำไปต่อท้ายฟิลด์อื่น (consumed) ไม่ต้องแสดง Badge ซ้ำ
        const consumedFields = new Set();
        certFields.forEach(f => {
            const ps = form.querySelector(`select[name="parent_${f}"]`);
            const pv = ps ? ps.value : 'none';
            if (pv !== 'none') consumedFields.add(pv);
        });

        certFields.forEach(field => {
            const enabledInput = form.querySelector(`input[type="checkbox"][name="enabled_${field}"]`);
            const isEnabled = enabledInput ? enabledInput.checked : true;
            const badge = document.getElementById(`badge-cert-${field}`);
            if (!badge) return;

            if (isEnabled && !consumedFields.has(field)) {
                const xVal = parseInt(form.querySelector(`input[name="x_${field}"]`)?.value) || 960;
                const yVal = parseInt(form.querySelector(`input[name="y_${field}"]`)?.value) || 540;
                const sizeVal = parseInt(form.querySelector(`input[name="size_${field}"]`)?.value) || 32;
                const alignVal = form.querySelector(`select[name="align_${field}"]`)?.value || 'center';
                const weightVal = form.querySelector(`select[name="weight_${field}"]`)?.value || 'bold';

                badge.innerText = getFieldText(field, form, samples);

                const displayX = (xVal / naturalWidth) * rect.width;
                const displayY = (yVal / naturalHeight) * rect.height;
                const displayFontSize = Math.max(10, sizeVal * (rect.width / naturalWidth));

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

    function previewUploadedImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('preview-img-cert');
                const placeholder = document.getElementById('no-img-placeholder-cert');
                img.src = e.target.result;
                img.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
                img.onload = function() {
                    updateBadges();
                };
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function submitCertConfig(e) {
        e.preventDefault();
        const form = document.getElementById('form-cert-design');
        const formData = new FormData(form);
        const btn = document.getElementById('btn-save-cert');

        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> กำลังบันทึก...';
        lucide.createIcons();

        fetch('<?= base_url("staff/sports/certificates/save-design/{$cert['cert_id']}") ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="save" class="w-4 h-4 text-amber-300"></i> บันทึกการตั้งค่าพิกัดเกียรติบัตร';
            lucide.createIcons();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: data.message || 'ไม่สามารถบันทึกได้'
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="save" class="w-4 h-4 text-amber-300"></i> บันทึกการตั้งค่าพิกัดเกียรติบัตร';
            lucide.createIcons();
            Swal.fire({ icon: 'error', title: 'ข้อผิดพลาด', text: 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์' });
        });
    }

    window.addEventListener('resize', updateBadges);
    document.addEventListener('DOMContentLoaded', () => {
        const img = document.getElementById('preview-img-cert');
        if (img && img.complete) updateBadges();
    });
</script>
<?= $this->endSection() ?>
