<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<style>
    .neon-input {
        background: rgba(15, 23, 42, 0.7) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        color: #f1f5f9 !important;
        transition: all 0.3s ease;
    }
    .neon-input:focus {
        border-color: #22d3ee !important;
        box-shadow: 0 0 15px rgba(34, 211, 238, 0.3) !important;
        background: rgba(15, 23, 42, 0.9) !important;
        outline: none;
    }
    .neon-input option {
        background-color: #0f172a;
        color: #f1f5f9;
    }
</style>

<!-- Header Section -->
<div class="mb-6">
    <a href="<?= base_url('staff/science-week/competitions') ?>" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-semibold transition-colors text-sm mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปหน้ารายการ
    </a>
    <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow">
        <span><?= !empty($comp) ? 'แก้ไขประเภทการแข่งขัน' : 'เพิ่มประเภทการแข่งขัน' ?></span>
    </h2>
    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">ระบุรายละเอียดข้อมูลการแข่งขันเพื่ออัปเดตลงฐานข้อมูล</p>
</div>



<!-- Form Card -->
<div class="glass-card rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900/60 max-w-2xl border border-slate-200 dark:border-slate-800">
    <form action="<?= !empty($comp) ? base_url('staff/science-week/competitions/update/' . $comp['comp_id']) : base_url('staff/science-week/competitions/store') ?>" method="POST" class="space-y-6" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Competition Name -->
        <div class="space-y-2">
            <label for="comp_name" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="award" class="w-4 h-4 text-cyan-400"></i> ชื่อประเภทการแข่งขัน <span class="text-rose-450">*</span>
            </label>
            <input type="text" name="comp_name" id="comp_name" required value="<?= old('comp_name', $comp['comp_name'] ?? '') ?>" placeholder="เช่น การแข่งขันเขียนโปรแกรมควบคุม..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
        </div>

        <!-- Level -->
        <div class="space-y-2">
            <label for="comp_level" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="graduation-cap" class="w-4 h-4 text-indigo-400"></i> ระดับชั้นที่เปิดรับสมัคร <span class="text-rose-450">*</span>
            </label>
            <input type="text" name="comp_level" id="comp_level" required value="<?= old('comp_level', $comp['comp_level'] ?? '') ?>" placeholder="เช่น มัธยมศึกษาตอนต้น-ปลาย, ทุกระดับชั้น..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
        </div>

        <!-- Row: Icon and Theme Color -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Icon -->
            <div class="space-y-2">
                <label for="comp_icon" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                    <i data-lucide="orbit" class="w-4 h-4 text-purple-400"></i> ไอคอน (ชื่อไอคอน Lucide) <span class="text-rose-450">*</span>
                </label>
                <select name="comp_icon" id="comp_icon" class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
                    <?php 
                    $icons = ['award', 'rocket', 'target', 'atom', 'cpu', 'lightbulb', 'palette', 'bot', 'sparkles', 'globe', 'flask-conical', 'help-circle'];
                    $currentIcon = old('comp_icon', $comp['comp_icon'] ?? 'award');
                    foreach ($icons as $icon):
                    ?>
                        <option value="<?= $icon ?>" <?= $currentIcon == $icon ? 'selected' : '' ?>><?= $icon ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="text-[10px] text-slate-500 block">สามารถเลือกไอคอนที่เหมาะสมสำหรับการแข่งขัน</span>
            </div>

            <!-- Theme Color -->
            <div class="space-y-2">
                <label for="comp_color" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                    <i data-lucide="palette" class="w-4 h-4 text-pink-400"></i> สีธีมตกแต่ง <span class="text-rose-450">*</span>
                </label>
                <select name="comp_color" id="comp_color" class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
                    <?php 
                    $colors = [
                        'cyan'    => 'Cyan (ฟ้าสว่าง)',
                        'purple'  => 'Purple (ม่วง)',
                        'indigo'  => 'Indigo (น้ำเงินคราม)',
                        'pink'    => 'Pink (ชมพูหวาน)',
                        'amber'   => 'Amber (ส้มเหลือง)',
                        'emerald' => 'Emerald (เขียวมรกต)'
                    ];
                    $currentColor = old('comp_color', $comp['comp_color'] ?? 'cyan');
                    foreach ($colors as $key => $label):
                    ?>
                        <option value="<?= $key ?>" <?= $currentColor == $key ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="text-[10px] text-slate-500 block">สีที่จะนำไปประดับการ์ดและป้ายของประเภทนั้นๆ</span>
            </div>
        </div>

        <!-- Limit Quota -->
        <div class="space-y-2">
            <label for="comp_limit" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="users-round" class="w-4 h-4 text-cyan-400"></i> จำนวนทีมที่เปิดรับสมัครสูงสุด (โควตา)
            </label>
            <input type="number" name="comp_limit" id="comp_limit" min="0" value="<?= old('comp_limit', $comp['comp_limit'] ?? 0) ?>" placeholder="ระบุจำนวน เช่น 20 (ระบุ 0 หากต้องการรับแบบไม่จำกัดจำนวน)" class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
            <span class="text-[10px] text-slate-500 block">ระบุโควตาจำนวนทีมที่รับ หากครบตามจำนวนที่กำหนดแล้ว ระบบจะปิดการรับสมัครของการแข่งขันนี้โดยอัตโนมัติ (ระบุเป็น 0 เพื่อไม่จำกัดจำนวน)</span>
        </div>

        <!-- Limit Members per Team -->
        <div class="space-y-2">
            <label for="comp_member_limit" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="user-check" class="w-4 h-4 text-emerald-400"></i> จำนวนสมาชิกผู้เข้าแข่งขันต่อทีมสูงสุด
            </label>
            <input type="number" name="comp_member_limit" id="comp_member_limit" min="0" value="<?= old('comp_member_limit', $comp['comp_member_limit'] ?? 0) ?>" placeholder="ระบุจำนวน เช่น 3 (ระบุ 0 หากต้องการให้เพิ่มได้ไม่จำกัด)" class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
            <span class="text-[10px] text-slate-500 block">ระบุจำนวนผู้เข้าแข่งขันสูงสุดที่อนุญาตให้กรอกในฟอร์มลงทะเบียนต่อ 1 ทีม (ระบุเป็น 0 เพื่อไม่จำกัดจำนวน)</span>
        </div>

        <!-- Description -->
        <div class="space-y-2">
            <label for="comp_description" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="align-left" class="w-4 h-4 text-amber-400"></i> รายละเอียด / คำอธิบายแบบย่อ
            </label>
            <textarea name="comp_description" id="comp_description" rows="3" placeholder="ท้าทายจินตนาการความสามารถด้วย..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors resize-none"><?= old('comp_description', $comp['comp_description'] ?? '') ?></textarea>
        </div>

        <!-- Rule File Attachment -->
        <div class="space-y-2">
            <label for="comp_rule_file" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-emerald-450"></i> แนบไฟล์กติกาการแข่งขัน (.pdf, .doc, .docx, .zip)
            </label>
            <input type="file" name="comp_rule_file" id="comp_rule_file" class="w-full px-4 py-2.5 neon-input rounded-2xl text-xs outline-none transition-colors file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20">
            <?php if (!empty($comp['comp_rule_file'])): ?>
                <div class="flex items-center gap-4 text-xs mt-2 bg-indigo-950/20 p-3 rounded-xl border border-indigo-500/10">
                    <span class="text-slate-300 flex items-center gap-1.5">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i> 
                        ไฟล์ปัจจุบัน: <a href="<?= base_url($comp['comp_rule_file']) ?>" target="_blank" class="text-indigo-400 hover:underline font-bold">ดาวน์โหลดไฟล์</a>
                    </span>
                    <label class="flex items-center gap-1.5 text-rose-400 font-bold cursor-pointer select-none">
                        <input type="checkbox" name="delete_rule_file" value="1" class="rounded border-rose-500/30 text-rose-500 focus:ring-rose-500 bg-slate-900">
                        ลบไฟล์กติกาเดิม
                    </label>
                </div>
            <?php endif; ?>
        </div>

        <!-- Rule Link URL -->
        <div class="space-y-2">
            <label for="comp_rule_link" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="link" class="w-4 h-4 text-sky-400"></i> หรือ ใช้ลิงก์รายละเอียดกติกาการแข่งขัน (เช่น Google Drive, เว็บไซต์นอก)
            </label>
            <input type="url" name="comp_rule_link" id="comp_rule_link" value="<?= old('comp_rule_link', $comp['comp_rule_link'] ?? '') ?>" placeholder="https://drive.google.com/..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
        </div>

        <!-- Custom Fields Section -->
        <?php
        $customFields = [];
        if (!empty($comp['comp_custom_fields'])) {
            $customFields = json_decode($comp['comp_custom_fields'], true) ?: [];
        }
        ?>
        <div class="space-y-4 border-t border-slate-200 dark:border-slate-800 pt-6">
            <div class="flex justify-between items-center">
                <label class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                    <i data-lucide="list-plus" class="w-4 h-4 text-indigo-400"></i> ฟิลด์ข้อมูลเพิ่มเติมสำหรับผู้สมัคร (Custom Fields)
                </label>
                <button type="button" id="add-field-btn" class="px-3 py-1.5 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 font-bold rounded-xl text-xs flex items-center gap-1 transition-all">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> เพิ่มฟิลด์คำถาม
                </button>
            </div>
            <p class="text-[10px] text-slate-500">คุณสามารถเพิ่มฟิลด์คำถามที่ต้องการให้ผู้สมัครกรอกข้อมูลเพิ่มเติม (เช่น ไซส์เสื้อ, ลิงก์วิดีโอผลงาน, แนบรูปภาพ)</p>
            
            <div id="fields-container" class="space-y-4">
                <!-- Dynamic custom fields config items will go here -->
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-4 text-white font-bold rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 shadow-lg shadow-indigo-950/20 transition-all flex items-center justify-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> บันทึกข้อมูลประเภทการแข่งขัน
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('fields-container');
    const addBtn = document.getElementById('add-field-btn');
    
    // Existing fields
    const existingFields = <?= json_encode($customFields) ?>;
    
    let fieldCount = 0;
    
    function addFieldRow(data = null) {
        fieldCount++;
        const id = 'cf_' + fieldCount;
        
        const row = document.createElement('div');
        row.className = 'p-4 rounded-2xl bg-slate-900/40 border border-slate-800 space-y-3 relative field-row';
        row.dataset.id = id;
        
        const labelVal = data ? data.label : '';
        const typeVal = data ? data.type : 'text';
        const optionsVal = data ? (data.options || '') : '';
        const requiredVal = data ? (data.required === true || data.required === '1' || data.required === 'true' || data.required === 1) : false;
        
        row.innerHTML = `
            <div class="flex justify-between items-start gap-4">
                <!-- Field Label/Name -->
                <div class="flex-1 space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase font-mono">ชื่อฟิลด์คำถาม / Label *</label>
                    <input type="text" name="custom_fields[${id}][label]" required value="${escapeHtml(labelVal)}" placeholder="เช่น ขนาดเสื้อ หรือ ลิงก์คลิปนำเสนอ" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                </div>
                
                <!-- Field Type -->
                <div class="w-[140px] space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase font-mono">ประเภทข้อมูล</label>
                    <select name="custom_fields[${id}][type]" onchange="toggleOptions(this, '${id}')" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                        <option value="text" ${typeVal === 'text' ? 'selected' : ''}>ข้อความสั้น (Text)</option>
                        <option value="textarea" ${typeVal === 'textarea' ? 'selected' : ''}>ข้อความยาว (Textarea)</option>
                        <option value="select" ${typeVal === 'select' ? 'selected' : ''}>ตัวเลือก (Select)</option>
                        <option value="file" ${typeVal === 'file' ? 'selected' : ''}>แนบไฟล์/รูป (File)</option>
                        <option value="url" ${typeVal === 'url' ? 'selected' : ''}>ระบุลิงก์ (URL)</option>
                    </select>
                </div>

                <!-- Delete button -->
                <button type="button" onclick="this.closest('.field-row').remove()" class="mt-5 p-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-xl transition-all" title="ลบฟิลด์นี้">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
            
            <!-- Extra Options -->
            <div class="options-container space-y-1 ${typeVal === 'select' ? '' : 'hidden'}" id="opts_${id}">
                <label class="block text-[10px] font-bold text-slate-400 uppercase font-mono">ตัวเลือกย่อย (แยกด้วยเครื่องหมายจุลภาค , ) *</label>
                <input type="text" name="custom_fields[${id}][options]" value="${escapeHtml(optionsVal)}" placeholder="เช่น S, M, L, XL" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none" ${typeVal === 'select' ? 'required' : ''}>
            </div>

            <!-- Required Toggle -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="custom_fields[${id}][required]" value="1" id="req_${id}" ${requiredVal ? 'checked' : ''} class="rounded border-slate-700/60 bg-slate-900 text-indigo-500 focus:ring-indigo-500">
                <label for="req_${id}" class="text-[10px] font-bold text-slate-350 select-none cursor-pointer">บังคับกรอก (Required)</label>
            </div>
        `;
        
        container.appendChild(row);
        lucide.createIcons();
    }
    
    // Toggle options field visibility for select
    window.toggleOptions = function(select, id) {
        const optsDiv = document.getElementById('opts_' + id);
        if (select.value === 'select') {
            optsDiv.classList.remove('hidden');
            optsDiv.querySelector('input').required = true;
        } else {
            optsDiv.classList.add('hidden');
            optsDiv.querySelector('input').required = false;
        }
    };
    
    function escapeHtml(text) {
        if (!text) return '';
        return text
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    
    // Load existing
    if (existingFields) {
        const fieldsArr = Array.isArray(existingFields) ? existingFields : Object.values(existingFields);
        fieldsArr.forEach(field => {
            addFieldRow(field);
        });
    }
    
    addBtn.addEventListener('click', () => {
        addFieldRow();
    });
});
</script>
<?= $this->endSection() ?>
