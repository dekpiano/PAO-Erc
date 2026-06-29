<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<style>
    .neon-input {
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        color: #1e293b !important;
        transition: all 0.2s ease;
    }
    .dark .neon-input {
        background-color: #1e293b !important;
        border: 1px solid #475569 !important;
        color: #f8fafc !important;
    }
    .neon-input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        outline: none;
    }
    .neon-input option {
        background-color: #ffffff;
        color: #1e293b;
    }
    .dark .neon-input option {
        background-color: #1e293b;
        color: #f8fafc;
    }
    .neon-input::placeholder {
        color: #94a3b8 !important;
    }
    .form-label {
        color: #475569 !important;
        font-weight: 700;
        font-size: 0.825rem;
    }
    .dark .form-label {
        color: #cbd5e1 !important;
    }
</style>

<?php
// Parse members
$parsedMembers = [];
$membersJson = json_decode($reg['reg_members'], true) ?: [];
foreach ($membersJson as $m) {
    $parts = explode(' ', trim($m), 2);
    $prefix = '';
    $name = $m;
    if (count($parts) > 1) {
        $possiblePrefix = $parts[0];
        $knownPrefixes = ['เด็กชาย', 'เด็กหญิง', 'นาย', 'นางสาว', 'นาง', 'ดร.'];
        if (in_array($possiblePrefix, $knownPrefixes)) {
            $prefix = $possiblePrefix;
            $name = $parts[1];
        } else {
            $prefix = $possiblePrefix;
            $name = $parts[1];
        }
    }
    $parsedMembers[] = ['prefix' => $prefix, 'name' => $name];
}

// Parse advisors
$parsedAdvisors = [];
$advisorsJson = json_decode($reg['reg_advisors'], true) ?: [];
foreach ($advisorsJson as $a) {
    $parts = explode(' ', trim($a), 2);
    $prefix = '';
    $name = $a;
    if (count($parts) > 1) {
        $possiblePrefix = $parts[0];
        $knownPrefixes = ['เด็กชาย', 'เด็กหญิง', 'นาย', 'นางสาว', 'นาง', 'ดร.'];
        if (in_array($possiblePrefix, $knownPrefixes)) {
            $prefix = $possiblePrefix;
            $name = $parts[1];
        } else {
            $prefix = $possiblePrefix;
            $name = $parts[1];
        }
    }
    $parsedAdvisors[] = ['prefix' => $prefix, 'name' => $name];
}

// Parse Custom Answers
$customAnswers = [];
if (!empty($reg['reg_custom_fields'])) {
    $customAnswers = json_decode($reg['reg_custom_fields'], true) ?: [];
}
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span>แก้ไขข้อมูลผู้สมัคร (<?= $reg['reg_code'] ?>)</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">แก้ไขรายละเอียดใบสมัคร สมาชิกกลุ่ม และความถูกต้องของเอกสาร</p>
    </div>
    
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="<?= base_url('staff/science-week') ?>" class="w-full md:w-auto justify-center px-4 py-2.5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-250 hover:text-slate-900 font-bold text-xs sm:text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4 text-slate-500"></i> ย้อนกลับไปหน้าจัดการ
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Form Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="glass-card p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 shadow-xl">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-100 dark:border-rose-800/30 text-rose-600 dark:text-rose-200 text-sm font-semibold">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-100 dark:border-rose-800/30 text-rose-600 dark:text-rose-200 text-sm font-semibold">
                    <ul class="list-disc pl-5 space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="editRegForm" action="<?= base_url('staff/science-week/update/' . $reg['reg_id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?= csrf_field() ?>

                <!-- Competition display -->
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-750 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-450 dark:text-slate-500 block uppercase">ประเภทการแข่งขัน</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200"><?= esc($reg['reg_competition_type']) ?></span>
                    </div>
                    <span class="text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 bg-cyan-50 dark:bg-cyan-950/40 border border-cyan-150 dark:border-cyan-900 px-3 py-1.5 rounded-xl">
                        <?= $reg['reg_code'] ?>
                    </span>
                </div>

                <!-- Row: School Name & Province -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="school_name" class="block text-xs sm:text-sm form-label">
                            ชื่อโรงเรียน / สถาบันศึกษา <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="school_name" id="school_name" required value="<?= esc(old('school_name', $reg['reg_school_name'])) ?>" class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label for="school_province" class="block text-xs sm:text-sm form-label">
                            จังหวัด <span class="text-rose-500">*</span>
                        </label>
                        <select name="school_province" id="school_province" required class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none transition-colors cursor-pointer">
                            <option value="" disabled>-- เลือกจังหวัด --</option>
                            <?php
                            $provinces = [
                                "กรุงเทพมหานคร", "กระบี่", "กาญจนบุรี", "กาฬสินธุ์", "กำแพงเพชร", "ขอนแก่น", "จันทบุรี", "ฉะเชิงเทรา", "ชลบุรี", "ชัยนาท", 
                                "ชัยภูมิ", "ชุมพร", "เชียงราย", "เชียงใหม่", "ตรัง", "ตราด", "ตาก", "นครนายก", "นครปฐม", "นครพนม", 
                                "นครราชสีมา", "นครศรีธรรมราช", "นครสวรรค์", "นนทบุรี", "นราธิวาส", "น่าน", "บึงกาฬ", "บุรีรัมย์", "ปทุมธานี", "ประจวบคีรีขันธ์", 
                                "ปราจีนบุรี", "ปัตตานี", "พระนครศรีอยุธยา", "พะเยา", "พังงา", "พัทลุง", "พิจิตร", "พิษณุโลก", "เพชรบุรี", "เพชรบูรณ์", 
                                "แพร่", "พะเยา", "ภูเก็ต", "มหาสารคาม", "มุกดาหาร", "แม่ฮ่องสอน", "ยะลา", "ยโสธร", "ร้อยเอ็ด", "ระนอง", 
                                "ระยอง", "ราชบุรี", "ลพบุรี", "ลำปาง", "ลำพูน", "เลย", "ศรีสะเกษ", "สกลนคร", "สงขลา", "สตูล", 
                                "สมุทรปราการ", "สมุทรสงคราม", "สมุทรสาคร", "สระแก้ว", "สระบุรี", "สิงห์บุรี", "สุโขทัย", "สุพรรณบุรี", "สุราษฎร์ธานี", "สุรินทร์", 
                                "หนองคาย", "หนองบัวลำภู", "อ่างทอง", "อุดรธานี", "อุทัยธานี", "อุตรดิตถ์", "อุบลราชธานี", "อำนาจเจริญ"
                            ];
                            $selectedProvince = old('school_province', $reg['reg_school_province']);
                            foreach ($provinces as $p):
                            ?>
                                <option value="<?= esc($p) ?>" <?= esc($p) === $selectedProvince ? 'selected' : '' ?>><?= esc($p) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Team Name -->
                <div class="space-y-2">
                    <label for="team_name" class="block text-xs sm:text-sm form-label">
                        ชื่อทีมที่เข้าแข่งขัน <span class="text-slate-400 font-normal">(ถ้ามี)</span>
                    </label>
                    <input type="text" name="team_name" id="team_name" value="<?= esc(old('team_name', $reg['reg_team_name'] ?: '')) ?>" placeholder="ระบุชื่อทีม..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none transition-colors">
                </div>

                <!-- Selected Level -->
                <?php
                $levelLimits = [];
                if ($comp && !empty($comp['comp_level_limits'])) {
                    $levelLimits = json_decode($comp['comp_level_limits'], true) ?: [];
                }
                if (!empty($levelLimits)):
                ?>
                    <div class="space-y-2">
                        <label for="reg_level" class="block text-xs sm:text-sm form-label">
                            ระดับชั้นที่สมัครแข่งขัน <span class="text-rose-500">*</span>
                        </label>
                        <select name="reg_level" id="reg_level" required class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none transition-colors cursor-pointer">
                            <?php foreach ($levelLimits as $lvl): ?>
                                <option value="<?= esc($lvl['level']) ?>" <?= $reg['reg_level'] === $lvl['level'] ? 'selected' : '' ?>><?= esc($lvl['level']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <hr class="border-slate-100 dark:border-slate-800/80 my-2" />

                <!-- Members Section -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs sm:text-sm form-label">
                            รายชื่อผู้เข้าแข่งขัน / สมาชิกทีม
                            <?php if ($comp && !empty($comp['comp_member_limit']) && $comp['comp_member_limit'] > 0): ?>
                                <span class="text-[10px] text-cyan-500 font-bold">(สูงสุด <?= esc($comp['comp_member_limit']) ?> คน)</span>
                            <?php endif; ?>
                        </label>
                        <button type="button" id="add-member-btn" class="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold flex items-center gap-1 transition-all">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> เพิ่มสมาชิก
                        </button>
                    </div>
                    <div id="members-wrapper" class="space-y-2">
                        <?php foreach ($parsedMembers as $idx => $m): ?>
                            <div class="flex items-center gap-2 p-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850/40">
                                <span class="text-[10px] font-mono font-bold text-slate-400 w-8 text-center"><?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                <div class="flex-1 flex gap-2 items-center flex-wrap sm:flex-nowrap">
                                    <div class="w-full sm:w-28 shrink-0">
                                        <?php $known = in_array($m['prefix'], ['เด็กชาย', 'เด็กหญิง', 'นาย', 'นางสาว', 'นาง', 'ดร.']); ?>
                                        <select name="member_prefixes[]" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                                            <option value="" disabled <?= empty($m['prefix']) ? 'selected' : '' ?>>คำนำหน้า</option>
                                            <option value="เด็กชาย" <?= $m['prefix'] === 'เด็กชาย' ? 'selected' : '' ?>>เด็กชาย</option>
                                            <option value="เด็กหญิง" <?= $m['prefix'] === 'เด็กหญิง' ? 'selected' : '' ?>>เด็กหญิง</option>
                                            <option value="นาย" <?= $m['prefix'] === 'นาย' ? 'selected' : '' ?>>นาย</option>
                                            <option value="นางสาว" <?= $m['prefix'] === 'นางสาว' ? 'selected' : '' ?>>นางสาว</option>
                                            <option value="นาง" <?= $m['prefix'] === 'นาง' ? 'selected' : '' ?>>นาง</option>
                                            <option value="other" <?= (!empty($m['prefix']) && !$known) ? 'selected' : '' ?>>อื่น ๆ</option>
                                        </select>
                                    </div>
                                    <?php if (!empty($m['prefix']) && !$known): ?>
                                        <div class="w-full sm:w-28 shrink-0 custom-prefix-wrapper">
                                            <input type="text" name="member_prefixes[]" value="<?= esc($m['prefix']) ?>" placeholder="ระบุเอง..." class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                                        </div>
                                    <?php endif; ?>
                                    <input type="text" name="member_names[]" required value="<?= esc($m['name']) ?>" placeholder="ชื่อ-นามสกุล..." class="flex-1 w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                                    <button type="button" class="remove-btn p-2 text-rose-500 hover:text-white hover:bg-rose-500 rounded-xl border border-transparent hover:border-rose-600 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Advisors Section -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs sm:text-sm form-label">
                            รายชื่ออาจารย์ที่ปรึกษา / ครูควบคุมทีม
                        </label>
                        <button type="button" id="add-advisor-btn" class="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold flex items-center gap-1 transition-all">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> เพิ่มที่ปรึกษา
                        </button>
                    </div>
                    <div id="advisors-wrapper" class="space-y-2">
                        <?php foreach ($parsedAdvisors as $idx => $a): ?>
                            <div class="flex items-center gap-2 p-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850/40">
                                <span class="text-[10px] font-mono font-bold text-slate-400 w-8 text-center"><?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                <div class="flex-1 flex gap-2 items-center flex-wrap sm:flex-nowrap">
                                    <div class="w-full sm:w-28 shrink-0">
                                        <?php $known = in_array($a['prefix'], ['เด็กชาย', 'เด็กหญิง', 'นาย', 'นางสาว', 'นาง', 'ดร.']); ?>
                                        <select name="advisor_prefixes[]" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                                            <option value="" disabled <?= empty($a['prefix']) ? 'selected' : '' ?>>คำนำหน้า</option>
                                            <option value="นาย" <?= $a['prefix'] === 'นาย' ? 'selected' : '' ?>>นาย</option>
                                            <option value="นางสาว" <?= $a['prefix'] === 'นางสาว' ? 'selected' : '' ?>>นางสาว</option>
                                            <option value="นาง" <?= $a['prefix'] === 'นาง' ? 'selected' : '' ?>>นาง</option>
                                            <option value="ดร." <?= $a['prefix'] === 'ดร.' ? 'selected' : '' ?>>ดร.</option>
                                            <option value="other" <?= (!empty($a['prefix']) && !$known) ? 'selected' : '' ?>>อื่น ๆ</option>
                                        </select>
                                    </div>
                                    <?php if (!empty($a['prefix']) && !$known): ?>
                                        <div class="w-full sm:w-28 shrink-0 custom-prefix-wrapper">
                                            <input type="text" name="advisor_prefixes[]" value="<?= esc($a['prefix']) ?>" placeholder="ระบุเอง..." class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                                        </div>
                                    <?php endif; ?>
                                    <input type="text" name="advisor_names[]" required value="<?= esc($a['name']) ?>" placeholder="ชื่อ-นามสกุล..." class="flex-1 w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                                    <button type="button" class="remove-btn p-2 text-rose-500 hover:text-white hover:bg-rose-500 rounded-xl border border-transparent hover:border-rose-600 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800/80 my-2" />

                <!-- Row: Contact details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="contact_phone" class="block text-xs sm:text-sm form-label">
                            เบอร์โทรศัพท์มือถือที่ติดต่อได้ <span class="text-rose-550">*</span>
                        </label>
                        <input type="tel" name="contact_phone" id="contact_phone" required value="<?= esc(old('contact_phone', $reg['reg_contact_phone'])) ?>" class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label for="contact_email" class="block text-xs sm:text-sm form-label">
                            อีเมลติดต่อผู้ควบคุม
                        </label>
                        <input type="email" name="contact_email" id="contact_email" value="<?= esc(old('contact_email', $reg['reg_contact_email'] ?: '')) ?>" class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none transition-colors">
                    </div>
                </div>

                <!-- Custom Fields Section -->
                <?php
                $customFieldsConfig = [];
                if ($comp && !empty($comp['comp_custom_fields'])) {
                    $customFieldsConfig = json_decode($comp['comp_custom_fields'], true) ?: [];
                }
                if (!empty($customFieldsConfig)):
                ?>
                    <hr class="border-slate-100 dark:border-slate-800/80 my-2" />
                    <div class="space-y-4">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-750 dark:text-slate-200 flex items-center gap-1.5">
                            <i data-lucide="file-text" class="w-4 h-4 text-blue-500"></i> ข้อมูลฟิลด์พิเศษเพิ่มเติม (Custom Fields)
                        </h4>
                        <div class="grid grid-cols-1 gap-4">
                            <?php foreach ($customFieldsConfig as $field): 
                                $fieldName = esc($field['label']);
                                $isRequired = !empty($field['required']) ? 'required' : '';
                                $requiredStar = !empty($field['required']) ? '<span class="text-rose-550">*</span>' : '';
                                $currentVal = $customAnswers[$field['label']] ?? null;
                            ?>
                                <div class="space-y-2">
                                    <label class="block text-xs sm:text-sm form-label">
                                        <?= esc($field['label']) ?> <?= $requiredStar ?>
                                    </label>

                                    <?php if ($field['type'] === 'text'): ?>
                                        <input type="text" name="custom_fields[<?= $fieldName ?>]" value="<?= esc($currentVal) ?>" <?= $isRequired ?> class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none transition-colors">
                                    
                                    <?php elseif ($field['type'] === 'textarea'): ?>
                                        <textarea name="custom_fields[<?= $fieldName ?>]" <?= $isRequired ?> rows="3" class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none transition-colors resize-none"><?= esc($currentVal) ?></textarea>
                                    
                                    <?php elseif ($field['type'] === 'url'): ?>
                                        <input type="url" name="custom_fields[<?= $fieldName ?>]" value="<?= esc($currentVal) ?>" <?= $isRequired ?> class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none transition-colors">
                                    
                                    <?php elseif ($field['type'] === 'select'): 
                                        $options = array_filter(array_map('trim', explode(',', $field['options'] ?? '')));
                                    ?>
                                        <select name="custom_fields[<?= $fieldName ?>]" <?= $isRequired ?> class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none transition-colors cursor-pointer">
                                            <option value="" disabled <?= is_null($currentVal) ? 'selected' : '' ?>>-- เลือก --</option>
                                            <?php foreach ($options as $opt): ?>
                                                <option value="<?= esc($opt) ?>" <?= $currentVal === $opt ? 'selected' : '' ?>><?= esc($opt) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    
                                    <?php elseif ($field['type'] === 'file'): ?>
                                        <div class="space-y-1.5 p-3 rounded-2xl border border-slate-200 dark:border-slate-750 bg-slate-50/50 dark:bg-slate-850/40">
                                            <?php if (!empty($currentVal)): ?>
                                                <div class="flex items-center justify-between text-xs mb-2">
                                                    <span class="font-bold text-slate-500 dark:text-slate-450">ไฟล์ปัจจุบัน:</span>
                                                    <a href="<?= base_url($currentVal) ?>" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline font-bold flex items-center gap-1">
                                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i> เปิดดูไฟล์
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-[10px] text-slate-400 dark:text-slate-500 italic mb-2">ยังไม่มีการอัปโหลดไฟล์</p>
                                            <?php endif; ?>
                                            <input type="file" name="custom_fields_files[<?= $fieldName ?>]" class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 dark:file:bg-slate-800 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-slate-700 cursor-pointer">
                                            <p class="text-[9px] text-slate-400 mt-1">* หากต้องการเปลี่ยนไฟล์ ให้ทำการเลือกไฟล์ใหม่ มิฉะนั้นจะใช้ไฟล์เดิม</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Status Select for easy editing inside form -->
                <div class="space-y-2 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40">
                    <label for="status" class="block text-xs sm:text-sm form-label">
                        สถานะเอกสารการสมัคร <span class="text-rose-550">*</span>
                    </label>
                    <select name="status" id="status" required class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none transition-colors cursor-pointer">
                        <option value="pending" <?= $reg['reg_status'] === 'pending' ? 'selected' : '' ?>>รอตรวจสอบ (Pending)</option>
                        <option value="approved" <?= $reg['reg_status'] === 'approved' ? 'selected' : '' ?>>อนุมัติสิทธิ์แล้ว (Approved)</option>
                        <option value="rejected" <?= $reg['reg_status'] === 'rejected' ? 'selected' : '' ?>>ปฏิเสธ/ไม่ผ่าน (Rejected)</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="<?= base_url('staff/science-week') ?>" class="flex-1 py-3 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-350 font-bold text-xs sm:text-sm hover:bg-slate-50 dark:hover:bg-slate-850 rounded-2xl transition-colors flex items-center justify-center gap-1">
                        ยกเลิก
                    </a>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm rounded-2xl shadow-md transition-colors flex items-center justify-center gap-1">
                        <i data-lucide="save" class="w-4 h-4"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Info & Status Summary -->
    <div class="space-y-6">
        <div class="glass-card p-6 rounded-3xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 shadow-xl">
            <h3 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-cyan-500"></i> ข้อมูลเพิ่มเติม
            </h3>
            
            <div class="space-y-4 text-xs">
                <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2.5">
                    <span class="text-slate-400 font-semibold">วันที่สมัคร:</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200"><?= date('d/m/Y H:i', strtotime($reg['reg_created_at'])) ?> น.</span>
                </div>
                <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2.5">
                    <span class="text-slate-400 font-semibold">แก้ไขล่าสุด:</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200"><?= $reg['reg_updated_at'] ? date('d/m/Y H:i', strtotime($reg['reg_updated_at'])) . ' น.' : 'ยังไม่มีการแก้ไข' ?></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2.5">
                    <span class="text-slate-400 font-semibold">สถานะปัจจุบัน:</span>
                    <?php if ($reg['reg_status'] === 'approved'): ?>
                        <span class="text-emerald-600 dark:text-emerald-400 font-black">อนุมัติแล้ว</span>
                    <?php elseif ($reg['reg_status'] === 'rejected'): ?>
                        <span class="text-rose-600 dark:text-rose-400 font-black">ปฏิเสธ/ไม่ผ่าน</span>
                    <?php else: ?>
                        <span class="text-amber-600 dark:text-amber-400 font-black">รอการตรวจสอบ</span>
                    <?php endif; ?>
                </div>
                <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2.5">
                    <span class="text-slate-400 font-semibold">คะแนนปัจจุบัน:</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200"><?= $reg['reg_score'] !== null ? $reg['reg_score'] . ' คะแนน' : 'ยังไม่บันทึก' ?></span>
                </div>
                <div class="flex justify-between pb-1">
                    <span class="text-slate-400 font-semibold">รางวัลที่ได้รับ:</span>
                    <span class="font-bold text-indigo-655 dark:text-indigo-400"><?= $reg['reg_rank'] ?: 'ยังไม่บันทึก' ?></span>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 rounded-3xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 shadow-xl">
            <h3 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="award" class="w-4 h-4 text-emerald-500"></i> เกียรติบัตร
            </h3>
            
            <div class="space-y-3">
                <a href="<?= base_url("science-week/certificate/view-all/competition/{$reg['reg_code']}") ?>" target="_blank" class="w-full py-2.5 bg-slate-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 hover:text-indigo-650 dark:hover:text-indigo-400 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i data-lucide="eye" class="w-4 h-4"></i> ดูเกียรติบัตรผู้สมัครทั้งหมด
                </a>
                <a href="<?= base_url("science-week/certificate/view-all/trainer/{$reg['reg_code']}") ?>" target="_blank" class="w-full py-2.5 bg-slate-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 hover:text-indigo-655 dark:hover:text-indigo-400 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i data-lucide="eye" class="w-4 h-4"></i> ดูเกียรติบัตรผู้ควบคุมทั้งหมด
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Config limit
    const compLimit = <?= isset($comp['comp_member_limit']) ? (int)$comp['comp_member_limit'] : 0 ?>;

    function setupDynamicList(wrapperId, addBtnId, placeholderText, prefixInputName, nameInputName, maxLimit = 0) {
        const wrapper = document.getElementById(wrapperId);
        const addBtn = document.getElementById(addBtnId);
        const isMember = nameInputName.includes('member');
        const iconName = isMember ? 'user' : 'user-check';

        function checkRemoveButtons() {
            const inputs = wrapper.querySelectorAll('.remove-btn');
            inputs.forEach((btn) => {
                if (wrapper.children.length > 1) {
                    btn.classList.remove('opacity-0', 'pointer-events-none');
                } else {
                    btn.classList.add('opacity-0', 'pointer-events-none');
                }
            });
        }

        function reindexItems() {
            const items = wrapper.children;
            for (let idx = 0; idx < items.length; idx++) {
                const count = idx + 1;
                const formattedCount = String(count).padStart(2, '0');
                const span = items[idx].querySelector('span');
                if (span) span.textContent = formattedCount;
                const input = items[idx].querySelector('input[type="text"]');
                if (input && input.placeholder.includes('...')) {
                    input.placeholder = placeholderText.replace('1', count);
                }
            }
        }

        // Initialize remove buttons for existing elements
        wrapper.addEventListener('click', (e) => {
            if (e.target.closest('.remove-btn')) {
                const item = e.target.closest('.flex');
                item.remove();
                checkRemoveButtons();
                reindexItems();
            }
        });

        addBtn.addEventListener('click', () => {
            const currentItemsCount = wrapper.children.length;
            if (maxLimit > 0 && currentItemsCount >= maxLimit) {
                Swal.fire({
                    icon: 'warning',
                    title: 'จำกัดจำนวนสมาชิก',
                    text: `การแข่งขันนี้จำกัดจำนวนสมาชิกได้สูงสุดไม่เกิน ${maxLimit} คน`,
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'glass-card rounded-[2rem]' }
                });
                return;
            }

            const count = currentItemsCount + 1;
            const formattedCount = String(count).padStart(2, '0');
            const item = document.createElement('div');
            item.className = 'flex items-center gap-2 p-2.5 rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850/40';
            
            const prefixOptions = isMember 
                ? `
                    <option value="" disabled selected>คำนำหน้า</option>
                    <option value="เด็กชาย">เด็กชาย</option>
                    <option value="เด็กหญิง">เด็กหญิง</option>
                    <option value="นาย">นาย</option>
                    <option value="นางสาว">นางสาว</option>
                    <option value="นาง">นาง</option>
                    <option value="other">อื่น ๆ</option>
                `
                : `
                    <option value="" disabled selected>คำนำหน้า</option>
                    <option value="นาย">นาย</option>
                    <option value="นางสาว">นางสาว</option>
                    <option value="นาง">นาง</option>
                    <option value="ดร.">ดร.</option>
                    <option value="other">อื่น ๆ</option>
                `;

            item.innerHTML = `
                <span class="text-[10px] font-mono font-bold text-slate-400 w-8 text-center">${formattedCount}</span>
                <div class="flex-1 flex gap-2 items-center flex-wrap sm:flex-nowrap">
                    <div class="w-full sm:w-28 shrink-0">
                        <select name="${prefixInputName}" class="w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                            ${prefixOptions}
                        </select>
                    </div>
                    <input type="text" name="${nameInputName}" required placeholder="${placeholderText.replace('1', count)}" class="flex-1 w-full px-3 py-2 neon-input rounded-xl text-xs outline-none">
                    <button type="button" class="remove-btn p-2 text-rose-500 hover:text-white hover:bg-rose-500 rounded-xl border border-transparent hover:border-rose-600 transition-colors">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
            wrapper.appendChild(item);
            lucide.createIcons();
            checkRemoveButtons();
        });

        checkRemoveButtons();
    }

    setupDynamicList('members-wrapper', 'add-member-btn', 'ชื่อ-นามสกุล สมาชิกคนที่ 1...', 'member_prefixes[]', 'member_names[]', compLimit);
    setupDynamicList('advisors-wrapper', 'add-advisor-btn', 'ชื่อ-นามสกุล ที่ปรึกษาคนที่ 1...', 'advisor_prefixes[]', 'advisor_names[]');

    // Handle prefix "other" select changes
    document.addEventListener('change', function(e) {
        if (e.target.name && (e.target.name === 'member_prefixes[]' || e.target.name === 'advisor_prefixes[]') && e.target.tagName === 'SELECT') {
            if (e.target.value === 'other') {
                const select = e.target;
                const parentDiv = select.parentElement;
                
                // Add text input for custom prefix
                const input = document.createElement('input');
                input.type = 'text';
                input.name = select.name; // Keep same name
                input.required = true;
                input.placeholder = 'ระบุเอง...';
                input.className = 'w-full px-3 py-2 neon-input rounded-xl text-xs outline-none';

                // Change select name to prevent duplication or use it for custom fields
                select.name = '_temp_prefix';
                select.style.display = 'none';

                // Append the input
                parentDiv.appendChild(input);
                input.focus();
            }
        }
    });
</script>

<?= $this->endSection() ?>
