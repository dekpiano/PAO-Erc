<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Niramit:wght@400;600;700&display=swap');

    .config-card {
        background: rgba(17, 25, 40, 0.75) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        color: #f1f5f9;
    }
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
    .tab-btn-active {
        background-color: rgba(99, 102, 241, 0.2);
        color: #818cf8;
        border-color: #6366f1;
    }
</style>

<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
            <i data-lucide="file-badge" class="w-8 h-8 text-emerald-400"></i>
            <span>ตั้งค่าระบบเกียรติบัตร</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-400 mt-1">อัปโหลดภาพพื้นหลังและกำหนดพิกัดตำแหน่ง (X, Y) สำหรับการพิมพ์ข้อความลงบนเกียรติบัตรแต่ละประเภท</p>
    </div>
</div>

<!-- Tabs -->
<div class="flex flex-wrap gap-2 mb-6 border-b border-slate-800 pb-4">
    <button onclick="switchTab('competition')" id="tab-btn-competition" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all border border-transparent text-slate-450 hover:bg-slate-900/40">
        เกียรติบัตรผู้เข้าแข่งขัน (นักเรียน)
    </button>
    <button onclick="switchTab('trainer')" id="tab-btn-trainer" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all border border-transparent text-slate-450 hover:bg-slate-900/40">
        เกียรติบัตรครูผู้ฝึกสอน (ครู)
    </button>
    <button onclick="switchTab('evaluation')" id="tab-btn-evaluation" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all border border-transparent text-slate-450 hover:bg-slate-900/40">
        เกียรติบัตรการทำแบบประเมิน
    </button>
    <button onclick="switchTab('student_staff')" id="tab-btn-student_staff" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all border border-transparent text-slate-450 hover:bg-slate-900/40">
        เกียรติบัตรนักเรียนช่วยงาน (Staff)
    </button>
</div>

<!-- Config Forms -->
<?php
$types = [
    'competition' => [
        'title' => 'เกียรติบัตรผู้เข้าแข่งขัน (นักเรียน)',
        'config' => $comp_config,
        'fields' => [
            'name' => 'ชื่อ-นามสกุล',
            'school' => 'ชื่อโรงเรียน',
            'level' => 'ระดับชั้น',
            'comp' => 'ประเภทรายการแข่งขัน',
            'rank' => 'รางวัลที่ได้รับ',
            'code' => 'รหัสเกียรติบัตร (เลขที่)'
        ]
    ],
    'trainer' => [
        'title' => 'เกียรติบัตรครูผู้ฝึกสอน (ครู)',
        'config' => $trainer_config,
        'fields' => [
            'name' => 'ชื่อ-นามสกุล',
            'school' => 'ชื่อโรงเรียน',
            'level' => 'ระดับชั้น',
            'comp' => 'ประเภทรายการแข่งขัน',
            'rank' => 'รางวัลที่ได้รับ (ข้อความครู)',
            'code' => 'รหัสเกียรติบัตร (เลขที่)'
        ]
    ],
    'evaluation' => [
        'title' => 'เกียรติบัตรการทำแบบประเมิน',
        'config' => $eval_config,
        'fields' => [
            'name' => 'ชื่อ-นามสกุล',
            'text' => 'ข้อความประเมิน',
            'date' => 'วันที่ออกเกียรติบัตร',
            'code' => 'รหัสเกียรติบัตร (เลขที่)'
        ]
    ],
    'student_staff' => [
        'title' => 'เกียรติบัตรนักเรียนช่วยงาน (Staff)',
        'config' => $student_staff_config,
        'fields' => [
            'name' => 'ชื่อ-นามสกุล',
            'school' => 'ชื่อโรงเรียน',
            'level' => 'ระดับชั้นเรียน',
            'comp' => 'รายการที่รับผิดชอบ/ช่วยงาน',
            'rank' => 'บทบาทหน้าที่/ข้อความช่วยงาน',
            'code' => 'รหัสเกียรติบัตร (เลขที่)'
        ]
    ]
];
?>

<?php foreach ($types as $typeKey => $typeData): ?>
<div id="panel-<?= $typeKey ?>" class="tab-panel hidden">
    <form id="form-<?= $typeKey ?>" onsubmit="submitConfig(event, '<?= $typeKey ?>')" enctype="multipart/form-data">
        <input type="hidden" name="cert_type" value="<?= $typeKey ?>">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left: Visual Designer / Preview -->
            <div class="lg:col-span-7 space-y-6">
                <div class="config-card rounded-2xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-bold text-slate-200">ผู้ออกแบบและแสดงตำแหน่งพิกัด</h3>
                        <span class="text-[10px] text-cyan-400 bg-cyan-950/40 border border-cyan-800/30 px-2 py-1 rounded-lg">คลิกที่รูปเพื่อเลือกพิกัด</span>
                    </div>

                    <div class="preview-container mb-4" id="container-<?= $typeKey ?>">
                        <?php 
                        $bgImage = $typeData['config']['bg_image'] ?? ''; 
                        $imageExists = !empty($bgImage) && file_exists(FCPATH . $bgImage);
                        ?>
                        <img src="<?= $imageExists ? base_url($bgImage) : '' ?>" 
                             alt="Template Preview" 
                             id="preview-img-<?= $typeKey ?>" 
                             class="preview-img <?= $imageExists ? '' : 'hidden' ?>"
                             onclick="handleImageClick(event, '<?= $typeKey ?>')">
                             
                        <div id="no-img-placeholder-<?= $typeKey ?>" class="py-24 text-center <?= $imageExists ? 'hidden' : '' ?>">
                            <i data-lucide="image" class="w-12 h-12 text-slate-605 mx-auto mb-3 opacity-40"></i>
                            <p class="text-xs text-slate-500 font-medium">กรุณาอัปโหลดภาพเทมเพลตพื้นหลังเพื่อเริ่มต้นการออกแบบ</p>
                        </div>
                        
                        <!-- Badges indicating locations of enabled fields -->
                        <?php foreach ($typeData['fields'] as $fieldKey => $fieldName): ?>
                            <div id="badge-<?= $typeKey ?>-<?= $fieldKey ?>" class="coordinate-badge hidden">
                                <?= esc($fieldName) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-350 mb-2">อัปโหลดรูปภาพเทมเพลต (แนะนำขนาด 1920x1357 px หรืออัตราส่วน A4 แนวนอน, ไฟล์ PNG/JPG)</label>
                            <input type="file" name="bg_image" accept="image/png, image/jpeg" onchange="previewTemplate(this, '<?= $typeKey ?>')" class="w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="button" onclick="downloadDemoCert('<?= $typeKey ?>')" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-750 border border-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-md">
                                <i data-lucide="eye" class="w-4 h-4 text-emerald-450"></i> ดูเกียรติบัตรจำลอง (Demo)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Field Coordinates / Control Panel -->
            <div class="lg:col-span-5 space-y-6">
                <div class="config-card rounded-2xl p-6">
                    <h3 class="text-sm font-bold text-slate-200 mb-4 flex items-center gap-2">
                        <i data-lucide="sliders" class="w-4 h-4 text-indigo-400"></i>
                        <span>ตั้งค่าตำแหน่งและขนาดฟอนต์</span>
                    </h3>

                    <!-- Active Field Selector for coordinate picking -->
                    <div class="mb-5 p-3.5 bg-slate-900/60 rounded-xl border border-slate-800/80">
                        <label for="active-field-<?= $typeKey ?>" class="block text-[10px] font-bold text-cyan-400 uppercase tracking-wider mb-2">ฟิลด์ที่ต้องการจัดตำแหน่ง</label>
                        <select id="active-field-<?= $typeKey ?>" class="w-full bg-slate-950 border border-slate-850 rounded-lg py-2 px-3 text-xs text-slate-300 font-bold focus:outline-none focus:border-indigo-500">
                            <?php foreach ($typeData['fields'] as $fieldKey => $fieldName): ?>
                                <option value="<?= $fieldKey ?>"><?= esc($fieldName) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-[9px] text-slate-500 mt-1.5 leading-relaxed">เลือกฟิลด์ด้านบน จากนั้นคลิกที่รูปภาพทางซ้ายเพื่อนำพิกัดจุดนั้นมาใส่ในช่อง X และ Y โดยอัตโนมัติ</p>
                    </div>

                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        <?php foreach ($typeData['fields'] as $fieldKey => $fieldName): 
                            $fieldConfig = $typeData['config'];
                            $isEnabled = !isset($fieldConfig["enabled_{$fieldKey}"]) || $fieldConfig["enabled_{$fieldKey}"];
                            $xVal = $fieldConfig["x_{$fieldKey}"] ?? 960;
                            $yVal = $fieldConfig["y_{$fieldKey}"] ?? 500;
                            $sizeVal = $fieldConfig["size_{$fieldKey}"] ?? 32;
                            $alignVal = $fieldConfig["align_{$fieldKey}"] ?? 'center';
                            $colorVal = $fieldConfig["color_{$fieldKey}"] ?? '#000000';
                            $parentVal = $fieldConfig["parent_{$fieldKey}"] ?? 'none';
                            $weightVal = $fieldConfig["weight_{$fieldKey}"] ?? 'bold';
                        ?>
                            <div class="p-4 bg-slate-900/30 rounded-xl border border-slate-800/40 space-y-3" id="field-card-<?= $typeKey ?>-<?= $fieldKey ?>">
                                <div class="flex justify-between items-center">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="enabled_<?= $fieldKey ?>" value="1" <?= $isEnabled ? 'checked' : '' ?> onchange="updateBadges('<?= $typeKey ?>')" class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-indigo-650 focus:ring-indigo-600 focus:ring-offset-slate-900">
                                        <span class="text-xs font-bold text-slate-200"><?= esc($fieldName) ?></span>
                                    </label>
                                    <span class="text-[9px] font-mono text-slate-500 font-bold">#<?= $fieldKey ?></span>
                                </div>

                                <div>
                                    <label class="block text-[9px] text-slate-450 font-bold mb-1">การจัดวางข้อความ</label>
                                    <select name="parent_<?= $fieldKey ?>" onchange="toggleFieldParentControls('<?= $typeKey ?>', '<?= $fieldKey ?>')" class="w-full bg-slate-950 border border-slate-850 rounded-lg py-1.5 px-2.5 text-[10px] text-slate-350">
                                        <option value="none" <?= $parentVal === 'none' ? 'selected' : '' ?>>เป็นเอกเทศ (มีพิกัด X, Y และสไตล์ของตัวเอง)</option>
                                        <?php foreach ($typeData['fields'] as $otherKey => $otherName): ?>
                                            <?php if ($otherKey !== $fieldKey): ?>
                                                <option value="<?= $otherKey ?>" <?= $parentVal === $otherKey ? 'selected' : '' ?>>ต่อท้ายฟิลด์: <?= esc($otherName) ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div id="controls-<?= $typeKey ?>-<?= $fieldKey ?>" class="space-y-3 <?= $parentVal !== 'none' ? 'hidden' : '' ?>">
                                    <div class="grid grid-cols-2 gap-2.5">
                                        <div>
                                            <label class="block text-[9px] text-slate-450 font-bold mb-1">ตำแหน่ง X (px)</label>
                                            <input type="number" name="x_<?= $fieldKey ?>" value="<?= $xVal ?>" oninput="updateBadges('<?= $typeKey ?>')" class="w-full bg-slate-950 border border-slate-850 rounded-lg py-1.5 px-2.5 text-xs text-slate-350 font-mono">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] text-slate-450 font-bold mb-1">ตำแหน่ง Y (px)</label>
                                            <input type="number" name="y_<?= $fieldKey ?>" value="<?= $yVal ?>" oninput="updateBadges('<?= $typeKey ?>')" class="w-full bg-slate-950 border border-slate-850 rounded-lg py-1.5 px-2.5 text-xs text-slate-350 font-mono">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] text-slate-450 font-bold mb-1">ขนาดฟอนต์ (px)</label>
                                            <input type="number" name="size_<?= $fieldKey ?>" value="<?= $sizeVal ?>" class="w-full bg-slate-950 border border-slate-850 rounded-lg py-1.5 px-2.5 text-xs text-slate-350">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] text-slate-450 font-bold mb-1">จัดตำแหน่ง</label>
                                            <select name="align_<?= $fieldKey ?>" class="w-full bg-slate-950 border border-slate-850 rounded-lg py-1.5 px-2.5 text-xs text-slate-350">
                                                <option value="left" <?= $alignVal === 'left' ? 'selected' : '' ?>>ชิดซ้าย</option>
                                                <option value="center" <?= $alignVal === 'center' ? 'selected' : '' ?>>กึ่งกลาง</option>
                                                <option value="right" <?= $alignVal === 'right' ? 'selected' : '' ?>>ชิดขวา</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-[9px] text-slate-450 font-bold mb-1">ความหนาตัวอักษร</label>
                                            <select name="weight_<?= $fieldKey ?>" class="w-full bg-slate-950 border border-slate-850 rounded-lg py-1.5 px-2.5 text-xs text-slate-350">
                                                <option value="regular" <?= $weightVal === 'regular' ? 'selected' : '' ?>>ตัวบาง (Regular)</option>
                                                <option value="bold" <?= $weightVal === 'bold' ? 'selected' : '' ?>>ตัวหนา (Bold)</option>
                                                <option value="extrabold" <?= $weightVal === 'extrabold' ? 'selected' : '' ?>>ตัวหนาพิเศษ (Extra Bold)</option>
                                                <option value="ultrabold" <?= $weightVal === 'ultrabold' ? 'selected' : '' ?>>ตัวหนามาก (Ultra Bold)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between gap-4 pt-1">
                                        <span class="text-[9px] text-slate-500">ตัวอย่างสีที่ใช้พิมพ์ข้อความ</span>
                                        <div class="flex items-center gap-2">
                                            <input type="color" name="color_<?= $fieldKey ?>" value="<?= $colorVal ?>" class="w-6 h-6 bg-transparent border-0 cursor-pointer p-0">
                                            <input type="text" value="<?= $colorVal ?>" oninput="syncColorPicker(this)" class="w-20 bg-slate-950 border border-slate-850 rounded-lg py-1 px-2 text-[10px] text-slate-350 text-center font-mono">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="pt-6 border-t border-slate-800/80 mt-6">
                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-650 hover:from-emerald-650 hover:to-teal-700 text-white font-bold rounded-xl text-xs transition-all flex items-center justify-center gap-1.5 shadow-lg shadow-emerald-950/20">
                            <i data-lucide="save" class="w-4 h-4"></i> บันทึกการตั้งค่าเกียรติบัตร
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php endforeach; ?>

<script>
    let activeTab = 'competition';
    let draggingBadge = null;
    let dragStartX = 0;
    let dragStartY = 0;
    let badgeStartX = 0;
    let badgeStartY = 0;
    let dragCachedRect = null;
    let dragCachedNaturalWidth = 0;
    let dragCachedNaturalHeight = 0;
    let dragType = '';

    document.addEventListener('DOMContentLoaded', () => {
        // Initial tab load
        switchTab('competition');

        // Global mousemove for buttery smooth dragging
        window.addEventListener('mousemove', (e) => {
            if (!draggingBadge) return;
            if (!dragCachedNaturalWidth || !dragCachedNaturalHeight) return;

            const dx = e.clientX - dragStartX;
            const dy = e.clientY - dragStartY;

            const deltaXNatural = (dx / dragCachedRect.width) * dragCachedNaturalWidth;
            const deltaYNatural = (dy / dragCachedRect.height) * dragCachedNaturalHeight;

            let newX = Math.round(badgeStartX + deltaXNatural);
            let newY = Math.round(badgeStartY + deltaYNatural);

            // Clamp to image boundaries
            newX = Math.max(0, Math.min(dragCachedNaturalWidth, newX));
            newY = Math.max(0, Math.min(dragCachedNaturalHeight, newY));

            const fieldKey = draggingBadge.id.replace(`badge-${dragType}-`, '');
            const form = document.getElementById(`form-${dragType}`);
            const xInput = form.querySelector(`input[name="x_${fieldKey}"]`);
            const yInput = form.querySelector(`input[name="y_${fieldKey}"]`);

            if (xInput) xInput.value = newX;
            if (yInput) yInput.value = newY;

            // Directly update positioning of the dragged element (buttery smooth 60fps)
            const displayX = (newX / dragCachedNaturalWidth) * dragCachedRect.width;
            const displayY = (newY / dragCachedNaturalHeight) * dragCachedRect.height;
            draggingBadge.style.left = `${displayX}px`;
            draggingBadge.style.top = `${displayY}px`;
        });

        window.addEventListener('mouseup', () => {
            if (draggingBadge) {
                draggingBadge.classList.remove('dragging');
                updateBadges(dragType); // Align any children/parents
                draggingBadge = null;
                dragType = '';
            }
        });

        // Setup mousedown for all preview containers
        document.querySelectorAll('.preview-container').forEach(container => {
            const type = container.id.replace('container-', '');

            container.addEventListener('mousedown', (e) => {
                const badge = e.target.closest('.coordinate-badge');
                if (!badge) return;

                e.preventDefault();
                draggingBadge = badge;
                draggingBadge.classList.add('dragging');
                dragType = type;

                const fieldKey = badge.id.replace(`badge-${type}-`, '');
                const form = document.getElementById(`form-${type}`);
                const xInput = form.querySelector(`input[name="x_${fieldKey}"]`);
                const yInput = form.querySelector(`input[name="y_${fieldKey}"]`);

                // Set field as active in dropdown
                const activeSelect = document.getElementById(`active-field-${type}`);
                if (activeSelect) {
                    activeSelect.value = fieldKey;
                }

                // Cache dimensions to avoid layout thrashing during mousemove
                const img = document.getElementById(`preview-img-${type}`);
                dragCachedRect = img.getBoundingClientRect();
                dragCachedNaturalWidth = img.naturalWidth;
                dragCachedNaturalHeight = img.naturalHeight;

                dragStartX = e.clientX;
                dragStartY = e.clientY;

                badgeStartX = parseInt(xInput.value) || 0;
                badgeStartY = parseInt(yInput.value) || 0;
            });

            // Prevent native HTML5 drag-and-drop behavior on badges
            container.addEventListener('dragstart', (e) => {
                if (e.target.closest('.coordinate-badge')) {
                    e.preventDefault();
                }
            });
        });

        // Set up real-time preview updates when changing control panel inputs
        ['competition', 'trainer', 'evaluation', 'student_staff'].forEach(type => {
            const form = document.getElementById(`form-${type}`);
            if (form) {
                form.querySelectorAll('input, select').forEach(input => {
                    if (input.name && (
                        input.name.startsWith('x_') || 
                        input.name.startsWith('y_') || 
                        input.name.startsWith('size_') || 
                        input.name.startsWith('align_') || 
                        input.name.startsWith('color_') || 
                        input.name.startsWith('enabled_') ||
                        input.name.startsWith('parent_') ||
                        input.name.startsWith('weight_')
                    )) {
                        input.addEventListener('input', () => updateBadges(type));
                        input.addEventListener('change', () => updateBadges(type));
                    }
                });
            }
        });
    });

    function toggleFieldParentControls(type, field) {
        const form = document.getElementById(`form-${type}`);
        const parentSelect = form.querySelector(`select[name="parent_${field}"]`);
        const parentVal = parentSelect ? parentSelect.value : 'none';
        const controlsDiv = document.getElementById(`controls-${type}-${field}`);
        
        if (controlsDiv) {
            if (parentVal === 'none') {
                controlsDiv.classList.remove('hidden');
            } else {
                controlsDiv.classList.add('hidden');
            }
        }
        
        updateBadges(type);
    }

    function switchTab(tab) {
        activeTab = tab;
        
        // Update tab buttons style
        ['competition', 'trainer', 'evaluation', 'student_staff'].forEach(t => {
            const btn = document.getElementById(`tab-btn-${t}`);
            const panel = document.getElementById(`panel-${t}`);
            
            if (t === tab) {
                btn.classList.add('tab-btn-active');
                panel.classList.remove('hidden');
                // Trigger badges calculation for the active tab once image is loaded/rendered
                setTimeout(() => updateBadges(t), 100);
            } else {
                btn.classList.remove('tab-btn-active');
                panel.classList.add('hidden');
            }
        });
    }

    function previewTemplate(input, type) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(`preview-img-${type}`);
                const placeholder = document.getElementById(`no-img-placeholder-${type}`);
                
                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
                
                // Recalculate badges after image loads
                img.onload = function() {
                    updateBadges(type);
                };
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handleImageClick(event, type) {
        // Prevent click if we clicked on a badge
        if (event.target.closest('.coordinate-badge')) return;

        const img = document.getElementById(`preview-img-${type}`);
        const rect = img.getBoundingClientRect();
        
        // Calculate click coordinates relative to the image display size
        const clickX = event.clientX - rect.left;
        const clickY = event.clientY - rect.top;
        
        // Scale to image natural size
        const naturalWidth = img.naturalWidth;
        const naturalHeight = img.naturalHeight;
        
        if (naturalWidth && naturalHeight) {
            const scaledX = Math.round((clickX / rect.width) * naturalWidth);
            const scaledY = Math.round((clickY / rect.height) * naturalHeight);
            
            // Get currently active field in coordinates panel
            const activeFieldSelect = document.getElementById(`active-field-${type}`);
            const activeField = activeFieldSelect ? activeFieldSelect.value : '';
            if (!activeField) return;

            // If the active field is currently set to append to another field (parent is not 'none'),
            // we should not update its coordinates because it does not use standalone coordinates.
            const form = document.getElementById(`form-${type}`);
            const parentSelect = form.querySelector(`select[name="parent_${activeField}"]`);
            if (parentSelect && parentSelect.value !== 'none') {
                return;
            }
            
            // Update input values
            const xInput = form.querySelector(`input[name="x_${activeField}"]`);
            const yInput = form.querySelector(`input[name="y_${activeField}"]`);
            
            if (xInput && yInput) {
                xInput.value = scaledX;
                yInput.value = scaledY;
                
                // Update badge positioning
                updateBadges(type);
                
                // Highlight input changes briefly
                xInput.classList.add('ring-2', 'ring-cyan-500');
                yInput.classList.add('ring-2', 'ring-cyan-500');
                setTimeout(() => {
                    xInput.classList.remove('ring-2', 'ring-cyan-500');
                    yInput.classList.remove('ring-2', 'ring-cyan-500');
                }, 400);
            }
        }
    }

    function syncColorPicker(input) {
        const picker = input.previousElementSibling;
        if (picker && /^#[0-9A-F]{6}$/i.test(input.value)) {
            picker.value = input.value;
            const type = input.closest('form').id.replace('form-', '');
            updateBadges(type);
        }
    }

    // Sync from picker to text input
    document.querySelectorAll('input[type="color"]').forEach(picker => {
        picker.addEventListener('input', (e) => {
            const textInput = e.target.nextElementSibling;
            if (textInput) {
                textInput.value = e.target.value;
                const type = e.target.closest('form').id.replace('form-', '');
                updateBadges(type);
            }
        });
    });

    function getFieldText(type, fieldKey, form, samples, visited = new Set()) {
        if (visited.has(fieldKey)) return '';
        visited.add(fieldKey);
        
        let text = samples[fieldKey] || '';
        
        const fields = (type === 'competition' || type === 'trainer' || type === 'student_staff')
            ? ['name', 'school', 'level', 'comp', 'rank', 'code']
            : ['name', 'text', 'date', 'code'];
            
        fields.forEach(f => {
            const parentSelect = form.querySelector(`select[name="parent_${f}"]`);
            const parentVal = parentSelect ? parentSelect.value : 'none';
            const enabledInput = form.querySelector(`input[name="enabled_${f}"]`);
            const isEnabled = enabledInput ? enabledInput.checked : false;
            
            if (isEnabled && parentVal === fieldKey) {
                text += " " + getFieldText(type, f, form, samples, visited);
            }
        });
        
        return text;
    }

    function updateBadges(type) {
        const img = document.getElementById(`preview-img-${type}`);
        if (img.classList.contains('hidden') || !img.complete || img.naturalWidth === 0) {
            return;
        }

        const rect = img.getBoundingClientRect();
        const naturalWidth = img.naturalWidth;
        const naturalHeight = img.naturalHeight;
        
        // Find all fields for this type
        const form = document.getElementById(`form-${type}`);
        const fields = (type === 'competition' || type === 'trainer' || type === 'student_staff')
            ? ['name', 'school', 'level', 'comp', 'rank', 'code']
            : ['name', 'text', 'date', 'code'];

        const samples = type === 'competition' ? {
            name: 'นายสมชาย รักดี',
            school: 'โรงเรียนวิทยาศาสตร์แสนดี',
            level: 'ระดับมัธยมศึกษาตอนต้น',
            comp: 'ประกวดโครงงานวิทยาศาสตร์ประเภททดลอง',
            rank: 'ได้รับรางวัลชนะเลิศ',
            code: 'SCI-2026-00042'
        } : type === 'trainer' ? {
            name: 'นางสาวสมศรี สอนดี',
            school: 'โรงเรียนวิทยาศาสตร์แสนดี',
            level: 'ระดับมัธยมศึกษาตอนต้น',
            comp: 'ประกวดโครงงานวิทยาศาสตร์ประเภททดลอง',
            rank: 'ผู้ควบคุมทีม ที่ได้รับรางวัลชนะเลิศ',
            code: 'SCI-2026-00042'
        } : type === 'student_staff' ? {
            name: 'นายกิตติคุณ มุ่งดี',
            school: 'โรงเรียนองค์การบริหารส่วนจังหวัดเชียงราย',
            level: 'ชั้นมัธยมศึกษาปีที่ 5/1',
            comp: 'กิจกรรมประกวดภาพยนตร์สั้นวิทยาศาสตร์',
            rank: 'ได้ปฏิบัติหน้าที่ คณะกรรมการดำเนินงานนักเรียนช่วยงาน',
            code: 'SW-ST-0001'
        } : {
            name: 'นายสมคิด ใฝ่เรียน',
            text: 'ได้ผ่านการประเมินผลการเรียนรู้ด้วยคะแนน 85%',
            date: '29 มิถุนายน 2569',
            code: 'SCI-EVAL-0123'
        };

        fields.forEach(field => {
            const enabledInput = form.querySelector(`input[name="enabled_${field}"]`);
            const isEnabled = enabledInput ? enabledInput.checked : false;
            
            const parentSelect = form.querySelector(`select[name="parent_${field}"]`);
            const parentVal = parentSelect ? parentSelect.value : 'none';

            const badge = document.getElementById(`badge-${type}-${field}`);
            if (!badge) return;

            if (isEnabled && parentVal === 'none') {
                const xVal = parseInt(form.querySelector(`input[name="x_${field}"]`).value) || 0;
                const yVal = parseInt(form.querySelector(`input[name="y_${field}"]`).value) || 0;
                const sizeVal = parseInt(form.querySelector(`input[name="size_${field}"]`).value) || 32;
                const alignVal = form.querySelector(`select[name="align_${field}"]`).value || 'center';
                const colorVal = form.querySelector(`input[name="color_${field}"]`).value || '#000000';
                const weightVal = form.querySelector(`select[name="weight_${field}"]`)?.value || 'bold';
                
                // Get concatenated text content
                badge.innerText = getFieldText(type, field, form, samples);
                
                // Map from natural coordinates to container coordinates
                const displayX = (xVal / naturalWidth) * rect.width;
                const displayY = (yVal / naturalHeight) * rect.height;
                const displayFontSize = sizeVal * (rect.width / naturalWidth);

                badge.style.left = `${displayX}px`;
                badge.style.top = `${displayY}px`;
                badge.style.fontSize = `${displayFontSize}px`;
                badge.style.fontWeight = weightVal === 'regular' ? 'normal' : 'bold';
                badge.style.textShadow = 'none';
                if (weightVal === 'extrabold') {
                    badge.style.textShadow = '-1px -1px 0 currentColor, 1px -1px 0 currentColor, -1px 1px 0 currentColor, 1px 1px 0 currentColor, -1px 0 0 currentColor, 1px 0 0 currentColor, 0 -1px 0 currentColor, 0 1px 0 currentColor';
                } else if (weightVal === 'ultrabold') {
                    badge.style.textShadow = '-2px -2px 0 currentColor, 2px -2px 0 currentColor, -2px 2px 0 currentColor, 2px 2px 0 currentColor, -2px 0 0 currentColor, 2px 0 0 currentColor, 0 -2px 0 currentColor, 0 2px 0 currentColor, -1px -1px 0 currentColor, 1px -1px 0 currentColor, -1px 1px 0 currentColor, 1px 1px 0 currentColor';
                }
                
                // Set text alignment positioning transform
                if (alignVal === 'left') {
                    badge.style.transform = 'translate(0%, -50%)';
                    badge.style.textAlign = 'left';
                } else if (alignVal === 'right') {
                    badge.style.transform = 'translate(-100%, -50%)';
                    badge.style.textAlign = 'right';
                } else {
                    badge.style.transform = 'translate(-50%, -50%)';
                    badge.style.textAlign = 'center';
                }

                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    }

    window.addEventListener('resize', () => {
        updateBadges(activeTab);
    });

    function downloadDemoCert(type) {
        const previewUrl = `<?= base_url('science-week/certificate/download') ?>/${type}/demo`;
        window.open(previewUrl, '_blank');
    }

    function submitConfig(event, type) {
        event.preventDefault();
        
        const form = document.getElementById(`form-${type}`);
        const fileInput = form.querySelector('input[name="bg_image"]');
        const file = fileInput ? fileInput.files[0] : null;

        if (file) {
            uploadChunks(file, type, function(uploadedFilename) {
                proceedWithSave(form, type, uploadedFilename);
            });
        } else {
            proceedWithSave(form, type, null);
        }
    }

    function uploadChunks(file, type, callback) {
        const chunkSize = 512 * 1024; // 512KB chunks
        const totalChunks = Math.ceil(file.size / chunkSize);
        const fileId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        let currentChunk = 0;

        Swal.fire({
            title: 'กำลังอัปโหลดรูปภาพเทมเพลต...',
            html: 'กรุณารอสักครู่ (0%)',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            background: getSwalColors().bg,
            color: getSwalColors().text,
            didOpen: () => {
                Swal.showLoading();
            }
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

            fetch('<?= base_url('science-week/staff/certificates/upload-chunk') ?>', {
                method: 'POST',
                body: chunkForm,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
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
                    Swal.fire({
                        icon: 'error',
                        title: 'อัปโหลดล้มเหลว',
                        text: data.message,
                        background: getSwalColors().bg,
                        color: getSwalColors().text,
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'การอัปโหลดขาดการเชื่อมต่อ',
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#ef4444'
                });
            });
        }

        sendNextChunk();
    }

    function proceedWithSave(form, type, uploadedFilename) {
        const formData = new FormData(form);

        // Explicitly handle checkbox fields that are unchecked
        const fields = (type === 'competition' || type === 'trainer' || type === 'student_staff')
            ? ['name', 'school', 'level', 'comp', 'rank', 'code']
            : ['name', 'text', 'date', 'code'];
            
        fields.forEach(field => {
            if (!formData.has(`enabled_${field}`)) {
                formData.append(`enabled_${field}`, '0');
            }
        });

        if (uploadedFilename) {
            formData.append('bg_image_uploaded', uploadedFilename);
            formData.delete('bg_image'); // Remove raw file to avoid Nginx 413 error
        }

        Swal.fire({
            title: 'กำลังบันทึกข้อมูล...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            background: getSwalColors().bg,
            color: getSwalColors().text,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('<?= base_url('science-week/staff/certificates/save') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            Swal.close();
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ',
                    text: data.message,
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'glass-card rounded-[2rem]' }
                });

                // Clear file input since upload is complete (the preview is already updated locally via FileReader)
                const bgInput = form.querySelector('input[name="bg_image"]');
                if (bgInput) {
                    bgInput.value = '';
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ล้มเหลว',
                    text: data.message,
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'glass-card rounded-[2rem]' }
                });
            }
        })
        .catch(() => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้',
                background: getSwalColors().bg,
                color: getSwalColors().text,
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'glass-card rounded-[2rem]' }
            });
        });
    }
</script>
<?= $this->endSection() ?>
