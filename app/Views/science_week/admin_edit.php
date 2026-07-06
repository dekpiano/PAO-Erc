<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<style>
    .neon-input {
        background-color: #0f172a !important; /* Always dark background for dark theme */
        border: 1.5px solid #475569 !important;
        color: #f8fafc !important;
        font-weight: 750 !important;
        transition: all 0.2s ease;
    }
    .neon-input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.25) !important;
        outline: none;
    }
    .neon-input option {
        background-color: #0f172a;
        color: #f8fafc;
        font-weight: 750;
    }
    .neon-input::placeholder {
        color: #64748b !important;
        font-weight: 600;
    }
    .form-label {
        color: #e2e8f0 !important; /* Bright label color for dark background */
        font-weight: 900;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<?php
// Parse members
$parsedMembers = [];
$membersJson = json_decode($reg['reg_members'], true) ?: [];
foreach ($membersJson as $m) {
    if (is_array($m)) {
        $parsedMembers[] = [
            'prefix' => $m['prefix'] ?? '',
            'name' => $m['name'] ?? '',
            'custom_fields' => $m['custom_fields'] ?? []
        ];
    } else {
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
        $parsedMembers[] = ['prefix' => $prefix, 'name' => $name, 'custom_fields' => []];
    }
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

$memberCustomFieldsConfig = [];
if ($comp && !empty($comp['comp_member_custom_fields'])) {
    $memberCustomFieldsConfig = json_decode($comp['comp_member_custom_fields'], true) ?: [];
}
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-xl sm:text-2xl md:text-3xl font-black text-white tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span>แก้ไขข้อมูลผู้สมัคร (<?= $reg['reg_code'] ?>)</span>
        </h2>
        <p class="text-xs text-slate-400 mt-1 font-bold">แก้ไขรายละเอียดใบสมัคร สมาชิกกลุ่ม และความถูกต้องของเอกสาร</p>
    </div>
    
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="<?= base_url('staff/science-week') ?>" class="w-full md:w-auto justify-center px-4 py-3 rounded-2xl bg-slate-900 border-2 border-slate-700 text-slate-200 hover:text-white font-extrabold text-xs sm:text-sm hover:bg-slate-800 transition-colors flex items-center gap-2 shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4 text-slate-300"></i> ย้อนกลับไปหน้าจัดการ
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Form Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="glass-card p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-xl">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-950/40 border border-rose-900/30 text-rose-200 text-sm font-semibold">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-950/40 border border-rose-900/30 text-rose-200 text-sm font-semibold">
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
                <div class="p-5 rounded-2xl bg-indigo-950/20 border-2 border-indigo-900 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black text-indigo-400 block uppercase mb-1">ประเภทการแข่งขัน</span>
                        <span class="text-base font-black text-slate-100"><?= esc($reg['reg_competition_type']) ?></span>
                    </div>
                    <span class="text-sm font-mono font-black text-cyan-400 bg-cyan-950 border-2 border-cyan-800 px-4 py-2 rounded-xl">
                        <?= $reg['reg_code'] ?>
                    </span>
                </div>

                <!-- Row: School Name & Province -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="school_name" class="block form-label">
                            <i data-lucide="school" class="w-4 h-4 text-cyan-400"></i> ชื่อโรงเรียน / สถาบันศึกษา <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="school_name" id="school_name" required value="<?= esc(old('school_name', $reg['reg_school_name'])) ?>" class="w-full px-4 py-3.5 neon-input rounded-2xl text-sm outline-none transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label for="school_province" class="block form-label">
                            <i data-lucide="map-pin" class="w-4 h-4 text-rose-450"></i> จังหวัด <span class="text-rose-500">*</span>
                        </label>
                        <select name="school_province" id="school_province" required class="w-full px-4 py-3.5 neon-input rounded-2xl text-sm outline-none transition-colors cursor-pointer">
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
                    <label for="team_name" class="block form-label">
                        <i data-lucide="users" class="w-4 h-4 text-purple-400"></i> ชื่อทีมที่เข้าแข่งขัน <span class="text-slate-400 font-bold">(ถ้ามี)</span>
                    </label>
                    <input type="text" name="team_name" id="team_name" value="<?= esc(old('team_name', $reg['reg_team_name'] ?: '')) ?>" placeholder="ระบุชื่อทีม..." class="w-full px-4 py-3.5 neon-input rounded-2xl text-sm outline-none transition-colors">
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
                        <label for="reg_level" class="block form-label">
                            <i data-lucide="graduation-cap" class="w-4 h-4 text-emerald-400"></i> ระดับชั้นที่สมัครแข่งขัน <span class="text-rose-500">*</span>
                        </label>
                        <select name="reg_level" id="reg_level" required class="w-full px-4 py-3.5 neon-input rounded-2xl text-sm outline-none transition-colors cursor-pointer">
                            <?php foreach ($levelLimits as $lvl): ?>
                                <option value="<?= esc($lvl['level']) ?>" <?= $reg['reg_level'] === $lvl['level'] ? 'selected' : '' ?>><?= esc($lvl['level']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <hr class="border-slate-800 my-2" />

                <!-- Members Section -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="block form-label">
                            <i data-lucide="user-check" class="w-4 h-4 text-indigo-400"></i> รายชื่อผู้เข้าแข่งขัน / สมาชิกทีม
                            <?php if ($comp && !empty($comp['comp_member_limit']) && $comp['comp_member_limit'] > 0): ?>
                                <span class="text-xs text-cyan-400 font-black">(สูงสุด <?= esc($comp['comp_member_limit']) ?> คน)</span>
                            <?php endif; ?>
                        </label>
                        <button type="button" id="add-member-btn" class="px-3.5 py-2 bg-slate-900 hover:bg-indigo-500 hover:text-white border-2 border-slate-700 rounded-xl text-xs font-black flex items-center gap-1 transition-all">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> เพิ่มสมาชิก
                        </button>
                    </div>
                    <div id="members-wrapper" class="space-y-3">
                        <?php foreach ($parsedMembers as $idx => $m): ?>
                            <div class="flex flex-col gap-2 p-3 rounded-2xl border-2 border-slate-800 bg-slate-900/40 dynamic-item">
                                <div class="flex items-center gap-2 w-full">
                                    <span class="text-xs font-mono font-black text-slate-400 w-8 text-center"><?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                    <div class="flex-1 flex gap-2 items-center flex-wrap sm:flex-nowrap">
                                        <div class="w-full sm:w-28 shrink-0">
                                            <?php $known = in_array($m['prefix'], ['เด็กชาย', 'เด็กหญิง', 'นาย', 'นางสาว', 'นาง', 'ดร.']); ?>
                                            <select name="member_prefixes[<?= $idx ?>]" class="w-full px-3 py-2.5 neon-input rounded-xl text-xs outline-none prefix-select">
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
                                                <input type="text" name="member_prefixes[<?= $idx ?>]" value="<?= esc($m['prefix']) ?>" placeholder="ระบุเอง..." class="w-full px-3 py-2.5 neon-input rounded-xl text-xs outline-none">
                                            </div>
                                        <?php endif; ?>
                                        <input type="text" name="member_names[<?= $idx ?>]" required value="<?= esc($m['name']) ?>" placeholder="ชื่อ-นามสกุล..." class="flex-1 w-full px-3 py-2.5 neon-input rounded-xl text-xs sm:text-sm font-bold outline-none name-input-el">
                                        <button type="button" class="remove-btn p-2.5 text-rose-450 hover:text-white hover:bg-rose-500 rounded-xl border border-transparent hover:border-rose-600 transition-colors">
                                            <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Member Custom Fields -->
                                <?php if (!empty($memberCustomFieldsConfig)): ?>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2 pl-0 sm:pl-10 pr-0 sm:pr-10 w-full member-custom-fields-container">
                                        <?php foreach ($memberCustomFieldsConfig as $f): 
                                            $fName = esc($f['label']);
                                            $isRequired = !empty($f['required']) ? 'required' : '';
                                            $requiredStar = !empty($f['required']) ? '<span class="text-rose-455">*</span>' : '';
                                            $currentVal = $m['custom_fields'][$f['label']] ?? '';
                                        ?>
                                            <div class="space-y-1">
                                                <label class="block text-xs font-bold text-slate-350"><?= esc($f['label']) ?> <?= $requiredStar ?></label>
                                                <div class="neon-input-wrapper relative">
                                                    <?php if ($f['type'] === 'select'): 
                                                        $opts = array_filter(array_map('trim', explode(',', $f['options'] ?? '')));
                                                    ?>
                                                        <select name="member_custom_fields[<?= $idx ?>][<?= $fName ?>]" <?= $isRequired ?> class="member-custom-input neon-input w-full px-3 py-2 neon-input rounded-xl text-xs outline-none cursor-pointer" data-label="<?= $fName ?>">
                                                            <option value="" disabled <?= $currentVal === '' ? 'selected' : '' ?>>-- เลือก --</option>
                                                            <?php foreach ($opts as $o): ?>
                                                                <option value="<?= esc($o) ?>" <?= $currentVal === $o ? 'selected' : '' ?>><?= esc($o) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    <?php elseif ($f['type'] === 'textarea'): ?>
                                                        <textarea name="member_custom_fields[<?= $idx ?>][<?= $fName ?>]" <?= $isRequired ?> rows="2" class="member-custom-input neon-input w-full px-3 py-2 neon-input rounded-xl text-xs outline-none resize-none" data-label="<?= $fName ?>"><?= esc($currentVal) ?></textarea>
                                                    <?php elseif ($f['type'] === 'url'): ?>
                                                        <input type="url" name="member_custom_fields[<?= $idx ?>][<?= $fName ?>]" <?= $isRequired ?> value="<?= esc($currentVal) ?>" class="member-custom-input neon-input w-full px-3 py-2 neon-input rounded-xl text-xs outline-none" placeholder="https://..." data-label="<?= $fName ?>">
                                                    <?php else: ?>
                                                        <input type="text" name="member_custom_fields[<?= $idx ?>][<?= $fName ?>]" <?= $isRequired ?> value="<?= esc($currentVal) ?>" class="member-custom-input neon-input w-full px-3 py-2 neon-input rounded-xl text-xs outline-none" placeholder="ระบุข้อมูล..." data-label="<?= $fName ?>">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Advisors Section -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="block form-label">
                            <i data-lucide="user-cog" class="w-4 h-4 text-indigo-400"></i> รายชื่ออาจารย์ที่ปรึกษา / ครูควบคุมทีม
                        </label>
                        <button type="button" id="add-advisor-btn" class="px-3.5 py-2 bg-slate-900 hover:bg-indigo-500 hover:text-white border-2 border-slate-700 rounded-xl text-xs font-black flex items-center gap-1 transition-all">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> เพิ่มที่ปรึกษา
                        </button>
                    </div>
                    <div id="advisors-wrapper" class="space-y-3">
                        <?php foreach ($parsedAdvisors as $idx => $a): ?>
                            <div class="flex items-center gap-2 p-3 rounded-2xl border-2 border-slate-800 bg-slate-900/40">
                                <span class="text-xs font-mono font-black text-slate-400 w-8 text-center"><?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                <div class="flex-1 flex gap-2 items-center flex-wrap sm:flex-nowrap">
                                    <div class="w-full sm:w-28 shrink-0">
                                        <?php $known = in_array($a['prefix'], ['เด็กชาย', 'เด็กหญิง', 'นาย', 'นางสาว', 'นาง', 'ดร.']); ?>
                                        <select name="advisor_prefixes[]" class="w-full px-3 py-2.5 neon-input rounded-xl text-xs outline-none">
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
                                            <input type="text" name="advisor_prefixes[]" value="<?= esc($a['prefix']) ?>" placeholder="ระบุเอง..." class="w-full px-3 py-2.5 neon-input rounded-xl text-xs outline-none">
                                        </div>
                                    <?php endif; ?>
                                    <input type="text" name="advisor_names[]" required value="<?= esc($a['name']) ?>" placeholder="ชื่อ-นามสกุล..." class="flex-1 w-full px-3 py-2.5 neon-input rounded-xl text-xs sm:text-sm font-bold outline-none name-input-el">
                                    <button type="button" class="remove-btn p-2.5 text-rose-450 hover:text-white hover:bg-rose-500 rounded-xl border border-transparent hover:border-rose-600 transition-colors">
                                        <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <hr class="border-slate-800 my-2" />

                <!-- Row: Contact details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="contact_phone" class="block form-label">
                            <i data-lucide="phone" class="w-4 h-4 text-emerald-400"></i> เบอร์โทรศัพท์มือถือที่ติดต่อได้ <span class="text-rose-550">*</span>
                        </label>
                        <input type="tel" name="contact_phone" id="contact_phone" required value="<?= esc(old('contact_phone', $reg['reg_contact_phone'])) ?>" class="w-full px-4 py-3.5 neon-input rounded-2xl text-sm outline-none transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label for="contact_email" class="block form-label">
                            <i data-lucide="mail" class="w-4 h-4 text-indigo-400"></i> อีเมลติดต่อผู้ควบคุม
                        </label>
                        <input type="email" name="contact_email" id="contact_email" value="<?= esc(old('contact_email', $reg['reg_contact_email'] ?: '')) ?>" class="w-full px-4 py-3.5 neon-input rounded-2xl text-sm outline-none transition-colors">
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
                    <hr class="border-slate-800 my-2" />
                    <div class="space-y-4">
                        <h4 class="text-base font-black text-slate-100 flex items-center gap-1.5 border-b-2 border-slate-800 pb-2">
                            <i data-lucide="file-plus" class="w-5 h-5 text-blue-400"></i> ข้อมูลคำถามเฉพาะเพิ่มเติม (Custom Fields)
                        </h4>
                        <div class="grid grid-cols-1 gap-6">
                            <?php foreach ($customFieldsConfig as $field): 
                                $fieldName = esc($field['label']);
                                $isRequired = !empty($field['required']) ? 'required' : '';
                                $requiredStar = !empty($field['required']) ? '<span class="text-rose-550">*</span>' : '';
                                $currentVal = $customAnswers[$field['label']] ?? null;
                            ?>
                                <div class="space-y-2">
                                    <label class="block form-label">
                                        <?= esc($field['label']) ?> <?= $requiredStar ?>
                                    </label>

                                    <?php if ($field['type'] === 'text'): ?>
                                        <input type="text" name="custom_fields[<?= $fieldName ?>]" value="<?= esc($currentVal) ?>" <?= $isRequired ?> class="w-full px-4 py-3.5 neon-input rounded-2xl text-sm outline-none transition-colors">
                                    
                                    <?php elseif ($field['type'] === 'textarea'): ?>
                                        <textarea name="custom_fields[<?= $fieldName ?>]" <?= $isRequired ?> rows="3" class="w-full px-4 py-3.5 neon-input rounded-2xl text-sm outline-none transition-colors resize-none"><?= esc($currentVal) ?></textarea>
                                    
                                    <?php elseif ($field['type'] === 'url'): ?>
                                        <input type="url" name="custom_fields[<?= $fieldName ?>]" value="<?= esc($currentVal) ?>" <?= $isRequired ?> class="w-full px-4 py-3.5 neon-input rounded-2xl text-sm outline-none transition-colors">
                                    
                                    <?php elseif ($field['type'] === 'select'): 
                                        $options = array_filter(array_map('trim', explode(',', $field['options'] ?? '')));
                                    ?>
                                        <select name="custom_fields[<?= $fieldName ?>]" <?= $isRequired ?> class="w-full px-4 py-3.5 neon-input rounded-2xl text-sm outline-none transition-colors cursor-pointer">
                                            <option value="" disabled <?= is_null($currentVal) ? 'selected' : '' ?>>-- เลือก --</option>
                                            <?php foreach ($options as $opt): ?>
                                                <option value="<?= esc($opt) ?>" <?= $currentVal === $opt ? 'selected' : '' ?>><?= esc($opt) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    
                                    <?php elseif ($field['type'] === 'file'): ?>
                                        <div class="space-y-2 p-4 rounded-2xl border-2 border-slate-800 bg-slate-900/40">
                                            <?php if (!empty($currentVal)): ?>
                                                <div class="flex items-center justify-between text-sm mb-2">
                                                    <span class="font-black text-slate-400">ไฟล์ปัจจุบัน:</span>
                                                    <a href="<?= base_url($currentVal) ?>" target="_blank" class="text-blue-400 hover:underline font-extrabold flex items-center gap-1">
                                                        <i data-lucide="external-link" class="w-4 h-4"></i> ดาวน์โหลด/เปิดดูไฟล์
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-xs text-slate-450 italic mb-2">ยังไม่มีการอัปโหลดไฟล์</p>
                                            <?php endif; ?>
                                            <input type="file" name="custom_fields_files[<?= $fieldName ?>]" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-2 file:border-slate-700 file:text-xs file:font-semibold file:bg-slate-800 file:text-blue-300 hover:file:bg-slate-700 cursor-pointer">
                                            <p class="text-[10px] text-slate-400 mt-1 font-bold">* หากต้องการเปลี่ยนไฟล์ ให้ทำการเลือกไฟล์ใหม่ มิฉะนั้นจะใช้ไฟล์เดิม</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Status Select for easy editing inside form -->
                <div class="space-y-2 p-5 rounded-2xl border-2 border-amber-800 bg-amber-950/10">
                    <label for="status" class="block form-label">
                        <i data-lucide="activity" class="w-4 h-4 text-amber-400"></i> สถานะเอกสารการสมัคร <span class="text-rose-550">*</span>
                    </label>
                    <select name="status" id="status" required class="w-full px-4 py-3.5 neon-input rounded-2xl text-sm outline-none transition-colors cursor-pointer">
                        <option value="pending" <?= $reg['reg_status'] === 'pending' ? 'selected' : '' ?>>🟡 รอตรวจสอบ (Pending)</option>
                        <option value="approved" <?= $reg['reg_status'] === 'approved' ? 'selected' : '' ?>>🟢 อนุมัติสิทธิ์แล้ว (ตัวจริง)</option>
                        <option value="approved_reserve" <?= $reg['reg_status'] === 'approved_reserve' ? 'selected' : '' ?>>🔵 อนุมัติสิทธิ์แล้ว (ตัวสำรอง)</option>
                        <option value="rejected" <?= $reg['reg_status'] === 'rejected' ? 'selected' : '' ?>>🔴 ปฏิเสธ/ไม่ผ่าน (Rejected)</option>
                    </select>
                </div>

                <div class="flex gap-4 pt-4">
                    <a href="<?= base_url('staff/science-week') ?>" class="flex-1 py-3.5 border-2 border-slate-700 text-slate-300 font-extrabold text-sm hover:bg-slate-800 rounded-2xl transition-colors flex items-center justify-center gap-1">
                        ยกเลิก
                    </a>
                    <button type="submit" class="flex-1 py-3.5 bg-blue-600 hover:bg-blue-750 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-blue-500/10 transition-colors flex items-center justify-center gap-1">
                        <i data-lucide="save" class="w-5 h-5"></i> บันทึกข้อมูลใบสมัคร
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Info & Status Summary -->
    <div class="space-y-6">
        <div class="glass-card p-6 rounded-3xl border border-slate-800 shadow-xl">
            <h3 class="text-base font-extrabold text-white mb-4 flex items-center gap-2">
                <i data-lucide="info" class="w-5 h-5 text-cyan-400"></i> สรุปข้อมูลผู้สมัคร
            </h3>
            
            <div class="space-y-4 text-xs sm:text-sm font-semibold">
                <div class="flex justify-between border-b border-slate-800 pb-3">
                    <span class="text-slate-400">วันที่สมัคร:</span>
                    <span class="font-extrabold text-slate-100"><?= date('d/m/Y H:i', strtotime($reg['reg_created_at'])) ?> น.</span>
                </div>
                <div class="flex justify-between border-b border-slate-800 pb-3">
                    <span class="text-slate-400">แก้ไขล่าสุด:</span>
                    <span class="font-extrabold text-slate-100"><?= $reg['reg_updated_at'] ? date('d/m/Y H:i', strtotime($reg['reg_updated_at'])) . ' น.' : 'ยังไม่มีการแก้ไข' ?></span>
                </div>
                <div class="flex justify-between border-b border-slate-800 pb-3">
                    <span class="text-slate-400">สถานะปัจจุบัน:</span>
                    <?php if ($reg['reg_status'] === 'approved'): ?>
                        <span class="text-emerald-400 font-black">🟢 อนุมัติ (ตัวจริง)</span>
                    <?php elseif ($reg['reg_status'] === 'approved_reserve'): ?>
                        <span class="text-blue-400 font-black">🔵 อนุมัติ (ตัวสำรอง)</span>
                    <?php elseif ($reg['reg_status'] === 'rejected'): ?>
                        <span class="text-rose-400 font-black">🔴 ปฏิเสธ/ไม่ผ่าน</span>
                    <?php else: ?>
                        <span class="text-amber-400 font-black">🟡 รอตรวจสอบ</span>
                    <?php endif; ?>
                </div>
                <div class="flex justify-between border-b border-slate-800 pb-3">
                    <span class="text-slate-400">คะแนนปัจจุบัน:</span>
                    <span class="font-extrabold text-slate-100"><?= $reg['reg_score'] !== null ? $reg['reg_score'] . ' คะแนน' : 'ยังไม่บันทึก' ?></span>
                </div>
                <div class="flex justify-between pb-1">
                    <span class="text-slate-400">รางวัลที่ได้รับ:</span>
                    <span class="font-extrabold text-indigo-400"><?= $reg['reg_rank'] ?: 'ยังไม่บันทึก' ?></span>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 rounded-3xl border border-slate-800 shadow-xl">
            <h3 class="text-base font-extrabold text-white mb-4 flex items-center gap-2">
                <i data-lucide="award" class="w-5 h-5 text-emerald-455"></i> จัดการเกียรติบัตร
            </h3>
            
            <div class="space-y-3">
                <a href="<?= base_url("science-week/certificate/view-all/competition/{$reg['reg_code']}") ?>" target="_blank" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-slate-200 hover:text-indigo-400 border-2 border-slate-700 rounded-2xl text-xs font-black transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i data-lucide="eye" class="w-4 h-4"></i> ดูเกียรติบัตรผู้สมัครทั้งหมด
                </a>
                <a href="<?= base_url("science-week/certificate/view-all/trainer/{$reg['reg_code']}") ?>" target="_blank" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-slate-200 hover:text-indigo-400 border-2 border-slate-700 rounded-2xl text-xs font-black transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i data-lucide="eye" class="w-4 h-4"></i> ดูเกียรติบัตรผู้ควบคุมทั้งหมด
                </a>
            </div>
        </div>
    </div>
</div>

<script>    // Config limit
    const compLimit = <?= isset($comp['comp_member_limit']) ? (int)$comp['comp_member_limit'] : 0 ?>;
    const memberCustomFieldsConfig = <?= json_encode($memberCustomFieldsConfig) ?>;

    function setupDynamicList(wrapperId, addBtnId, placeholderText, prefixInputName, nameInputName, maxLimit = 0) {
        const wrapper = document.getElementById(wrapperId);
        const addBtn = document.getElementById(addBtnId);
        const isMember = nameInputName.includes('member');
        const prefixLabel = isMember ? 'MEMBER' : 'ADVISOR';
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
                const span = items[idx].querySelector('span.font-mono');
                if (span) span.textContent = formattedCount;
                
                // Reindex select/input name
                const prefixSelect = items[idx].querySelector('select.prefix-select') || items[idx].querySelector('select');
                if (prefixSelect && prefixSelect.name !== '_temp_prefix') {
                    prefixSelect.name = isMember ? `member_prefixes[${idx}]` : `advisor_prefixes[${idx}]`;
                }
                const customPrefixInput = items[idx].querySelector('.custom-prefix-wrapper input') || items[idx].querySelector('input[placeholder="ระบุเอง..."]');
                if (customPrefixInput) {
                    customPrefixInput.name = isMember ? `member_prefixes[${idx}]` : `advisor_prefixes[${idx}]`;
                }

                const nameInput = items[idx].querySelector('.name-input-el');
                if (nameInput) {
                    nameInput.name = isMember ? `member_names[${idx}]` : `advisor_names[${idx}]`;
                    if (nameInput.placeholder.includes('...')) {
                        nameInput.placeholder = placeholderText.replace('1', count);
                    }
                }

                if (isMember) {
                    const customInputs = items[idx].querySelectorAll('.member-custom-input');
                    customInputs.forEach(input => {
                        const fieldLabel = input.dataset.label;
                        input.name = `member_custom_fields[${idx}][${fieldLabel}]`;
                    });
                }
            }
        }

        // Initialize remove buttons for existing elements
        wrapper.addEventListener('click', (e) => {
            if (e.target.closest('.remove-btn')) {
                const item = e.target.closest('.dynamic-item') || e.target.closest('.flex');
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
            const idx = currentItemsCount;
            const formattedCount = String(count).padStart(2, '0');
            const item = document.createElement('div');
            item.className = isMember 
                ? 'flex flex-col gap-2 p-3 rounded-2xl border-2 border-slate-800 bg-slate-900/40 dynamic-item'
                : 'flex items-center gap-2 p-3 rounded-2xl border-2 border-slate-800 bg-slate-900/40';
            
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

            const prefixInputNameValue = isMember ? `member_prefixes[${idx}]` : `advisor_prefixes[${idx}]`;
            const nameInputNameValue = isMember ? `member_names[${idx}]` : `advisor_names[${idx}]`;

            let customFieldsHtml = '';
            if (isMember && memberCustomFieldsConfig && memberCustomFieldsConfig.length > 0) {
                customFieldsHtml += `<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2 pl-0 sm:pl-10 pr-0 sm:pr-10 w-full member-custom-fields-container">`;
                memberCustomFieldsConfig.forEach(f => {
                    const fName = escapeHtml(f.label);
                    const isRequired = f.required ? 'required' : '';
                    const requiredStar = f.required ? '<span class="text-rose-455">*</span>' : '';
                    
                    customFieldsHtml += `
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-350">${escapeHtml(f.label)} ${requiredStar}</label>
                            <div class="neon-input-wrapper relative">
                    `;
                    
                    if (f.type === 'select') {
                        const opts = (f.options || '').split(',').map(o => o.trim()).filter(o => o !== '');
                        customFieldsHtml += `
                            <select name="member_custom_fields[${idx}][${fName}]" ${isRequired} class="member-custom-input neon-input w-full px-3 py-2 neon-input rounded-xl text-xs outline-none cursor-pointer" data-label="${fName}">
                                <option value="" disabled selected>-- เลือก --</option>
                        `;
                        opts.forEach(o => {
                            customFieldsHtml += `<option value="${escapeHtml(o)}">${escapeHtml(o)}</option>`;
                        });
                        customFieldsHtml += `
                            </select>
                        `;
                    } else if (f.type === 'textarea') {
                        customFieldsHtml += `
                            <textarea name="member_custom_fields[${idx}][${fName}]" ${isRequired} rows="2" class="member-custom-input neon-input w-full px-3 py-2 neon-input rounded-xl text-xs outline-none resize-none" data-label="${fName}"></textarea>
                        `;
                    } else if (f.type === 'url') {
                        customFieldsHtml += `
                            <input type="url" name="member_custom_fields[${idx}][${fName}]" ${isRequired} class="member-custom-input neon-input w-full px-3 py-2 neon-input rounded-xl text-xs outline-none" placeholder="https://..." data-label="${fName}">
                        `;
                    } else {
                        customFieldsHtml += `
                            <input type="text" name="member_custom_fields[${idx}][${fName}]" ${isRequired} class="member-custom-input neon-input w-full px-3 py-2 neon-input rounded-xl text-xs outline-none" placeholder="ระบุข้อมูล..." data-label="${fName}">
                        `;
                    }
                    
                    customFieldsHtml += `
                            </div>
                        </div>
                    `;
                });
                customFieldsHtml += `</div>`;
            }

            if (isMember) {
                item.innerHTML = `
                    <div class="flex items-center gap-2 w-full">
                        <span class="text-xs font-mono font-black text-slate-400 w-8 text-center">${formattedCount}</span>
                        <div class="flex-1 flex gap-2 items-center flex-wrap sm:flex-nowrap">
                            <div class="w-full sm:w-28 shrink-0">
                                <select name="${prefixInputNameValue}" class="w-full px-3 py-2.5 neon-input rounded-xl text-xs outline-none prefix-select">
                                    ${prefixOptions}
                                </select>
                            </div>
                            <input type="text" name="${nameInputNameValue}" required placeholder="ชื่อ-นามสกุล..." class="flex-1 w-full px-3 py-2.5 neon-input rounded-xl text-xs sm:text-sm font-bold outline-none name-input-el">
                            <button type="button" class="remove-btn p-2.5 text-rose-450 hover:text-white hover:bg-rose-500 rounded-xl border border-transparent hover:border-rose-600 transition-colors">
                                <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                            </button>
                        </div>
                    </div>
                    ${customFieldsHtml}
                `;
            } else {
                item.innerHTML = `
                    <span class="text-xs font-mono font-black text-slate-400 w-8 text-center">${formattedCount}</span>
                    <div class="flex-1 flex gap-2 items-center flex-wrap sm:flex-nowrap">
                        <div class="w-full sm:w-28 shrink-0">
                            <select name="${prefixInputNameValue}" class="w-full px-3 py-2.5 neon-input rounded-xl text-xs outline-none prefix-select">
                                ${prefixOptions}
                            </select>
                        </div>
                        <input type="text" name="${nameInputNameValue}" required placeholder="ชื่อ-นามสกุล..." class="flex-1 w-full px-3 py-2.5 neon-input rounded-xl text-xs sm:text-sm font-bold outline-none name-input-el">
                        <button type="button" class="remove-btn p-2.5 text-rose-450 hover:text-white hover:bg-rose-500 rounded-xl border border-transparent hover:border-rose-600 transition-colors">
                            <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                        </button>
                    </div>
                `;
            }
            
            wrapper.appendChild(item);
            lucide.createIcons();
            checkRemoveButtons();
            reindexItems();
        });

        checkRemoveButtons();
    }

    function escapeHtml(string) {
        return String(string)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    setupDynamicList('members-wrapper', 'add-member-btn', 'ชื่อ-นามสกุล สมาชิกคนที่ 1...', 'member_prefixes[]', 'member_names[]', compLimit);
    setupDynamicList('advisors-wrapper', 'add-advisor-btn', 'ชื่อ-นามสกุล ที่ปรึกษาคนที่ 1...', 'advisor_prefixes[]', 'advisor_names[]');

    // Handle prefix "other" select changes
    document.addEventListener('change', function(e) {
        if (e.target.name && (e.target.name.startsWith('member_prefixes') || e.target.name.startsWith('advisor_prefixes')) && e.target.tagName === 'SELECT') {
            if (e.target.value === 'other') {
                const select = e.target;
                const parentDiv = select.parentElement;
                
                // Add text input for custom prefix
                const input = document.createElement('input');
                input.type = 'text';
                input.name = select.name; // Keep same name
                input.required = true;
                input.placeholder = 'ระบุเอง...';
                input.className = 'w-full px-3 py-2.5 neon-input rounded-xl text-xs outline-none';

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
