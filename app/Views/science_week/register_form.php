<?= $this->extend('science_week/layout/main') ?>

<?= $this->section('content') ?>
<?php
// Determine primary color of the competition
$compColor = 'cyan';
$compName = mb_strtolower($competition_type);
if (strpos($compName, 'จรวด') !== false || strpos($compName, 'water rocket') !== false || strpos($compName, 'วิศว') !== false || strpos($compName, 'engineering') !== false) {
    $compColor = 'emerald';
} elseif (strpos($compName, 'show') !== false || strpos($compName, 'โชว์') !== false || strpos($compName, 'วิทย์') !== false || strpos($compName, 'science') !== false) {
    $compColor = 'purple';
} elseif (strpos($compName, 'โครงงาน') !== false || strpos($compName, 'project') !== false || strpos($compName, 'เทคโน') !== false || strpos($compName, 'technology') !== false || strpos($compName, 'คอม') !== false) {
    $compColor = 'cyan';
} elseif (strpos($compName, 'วาด') !== false || strpos($compName, 'ศิลป์') !== false || strpos($compName, 'art') !== false || strpos($compName, 'paint') !== false) {
    $compColor = 'yellow';
} elseif (strpos($compName, 'คณิต') !== false || strpos($compName, 'เลข') !== false || strpos($compName, 'math') !== false) {
    $compColor = 'rose';
}
?>
<style>
    :root {
        --comp-primary: var(--steam-<?= $compColor ?>);
        --comp-primary-glow: rgba(<?= $compColor === 'emerald' ? '16, 185, 129' : ($compColor === 'purple' ? '168, 85, 247' : ($compColor === 'yellow' ? '234, 179, 8' : ($compColor === 'rose' ? '244, 63, 94' : '2, 132, 199'))) ?>, 0.25);
    }

    .page-container {
        background: transparent;
        color: #f1f5f9;
        flex: 1;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    #particles-canvas {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        pointer-events: none;
        z-index: 0;
    }

    .glass-sci-card {
        background: rgba(15, 23, 42, 0.65) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(99, 102, 241, 0.25) !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3) !important;
        position: relative;
        color: #f1f5f9 !important;
    }

    /* Cyber tech corners decoration */
    .tech-corner {
        position: absolute;
        width: 16px;
        height: 16px;
        border-color: var(--comp-primary);
        border-style: solid;
        pointer-events: none;
        opacity: 0.8;
    }
    .tech-corner-tl { top: 12px; left: 12px; border-width: 3px 0 0 3px; border-top-left-radius: 4px; }
    .tech-corner-tr { top: 12px; right: 12px; border-width: 3px 3px 0 0; border-top-right-radius: 4px; }
    .tech-corner-bl { bottom: 12px; left: 12px; border-width: 0 0 3px 3px; border-bottom-left-radius: 4px; }
    .tech-corner-br { bottom: 12px; right: 12px; border-width: 0 3px 3px 0; border-bottom-right-radius: 4px; }

    /* High-tech Sci-Fi Inputs */
    .neon-input-wrapper {
        position: relative;
        transition: all 0.3s ease;
    }
    .neon-input {
        background: rgba(8, 12, 24, 0.7) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        color: #ffffff !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        padding-left: 3rem !important;
        font-size: 16px !important;
    }
    .neon-input:focus {
        background: rgba(15, 23, 42, 0.9) !important;
        border-color: var(--comp-primary) !important;
        box-shadow: 0 0 0 4px var(--comp-primary-glow), 0 10px 25px -5px var(--comp-primary-glow) !important;
        outline: none;
        transform: translateY(-1px);
    }
    .neon-input::placeholder { color: #64748b !important; font-weight: 400; }
    
    .input-icon-left {
        position: absolute;
        left: 1.1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        transition: color 0.3s ease;
        pointer-events: none;
    }
    .neon-input:focus + .input-icon-left {
        color: var(--comp-primary);
    }

    /* Cyber button styling */
    .neon-btn-submit {
        background: linear-gradient(135deg, var(--comp-primary) 0%, #4f46e5 100%);
        box-shadow: 0 8px 25px var(--comp-primary-glow);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }
    .neon-btn-submit::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }
    .neon-btn-submit:hover:not(:disabled) {
        box-shadow: 0 12px 35px var(--comp-primary-glow);
        transform: translateY(-3px) scale(1.01);
    }
    .neon-btn-submit:hover:not(:disabled)::after {
        transform: translateX(100%);
    }

    .list-item-enter { animation: slideIn 0.4s cubic-bezier(0.16,1,0.3,1) forwards; }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-15px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .rainbow-divider {
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c, #fda085);
        background-size: 200% auto;
        border-radius: 4px;
        animation: rainbowSlide 4s linear infinite;
    }
    @keyframes rainbowSlide {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    /* Entrance animations */
    .page-enter-back { animation: fadeInDown 0.6s cubic-bezier(0.16,1,0.3,1) 0.1s both; }
    .page-enter-title { animation: fadeInDown 0.7s cubic-bezier(0.16,1,0.3,1) 0.2s both; }
    .page-enter-subtitle { animation: fadeInDown 0.7s cubic-bezier(0.16,1,0.3,1) 0.3s both; }
    .page-enter-card { animation: fadeInUp 0.9s cubic-bezier(0.16,1,0.3,1) 0.35s both; }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .gradient-text {
        background: linear-gradient(135deg, var(--comp-primary) 0%, #6366f1 50%, #4f46e5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Form field stagger animation */
    .form-field { animation: fieldFadeIn 0.5s cubic-bezier(0.16,1,0.3,1) both; }
    @keyframes fieldFadeIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    /* Add member button */
    .add-btn {
        transition: all 0.3s ease;
        border-color: rgba(99, 102, 241, 0.4) !important;
        color: #a5b4fc !important;
        background: rgba(30, 41, 59, 0.6) !important;
    }
    .add-btn:hover {
        transform: scale(1.05);
        background: rgba(99, 102, 241, 0.15) !important;
        border-color: var(--comp-primary) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px var(--comp-primary-glow);
    }

    /* Remove btn */
    .remove-btn {
        background: rgba(244, 63, 94, 0.1) !important;
        border: 1px solid rgba(244, 63, 94, 0.2) !important;
        color: #f43f5e !important;
        transition: all 0.3s ease;
    }
    .remove-btn:hover {
        background: rgba(244, 63, 94, 0.25) !important;
        border-color: #f43f5e !important;
        color: #ffffff !important;
        transform: scale(1.05);
    }

    .glass-sci-card input, .glass-sci-card select, .glass-sci-card textarea {
        background: rgba(8, 12, 24, 0.6) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        color: #ffffff !important;
        font-size: 16px !important; /* Prevent mobile zoom-in on focus */
    }

    .glass-sci-card input:focus, .glass-sci-card select:focus, .glass-sci-card textarea:focus {
        border-color: var(--comp-primary) !important;
        box-shadow: 0 0 8px var(--comp-primary-glow) !important;
    }

    /* Dossier item wrappers */
    .dossier-card {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(99, 102, 241, 0.2) !important;
        border-left: 4px solid var(--comp-primary) !important;
        transition: all 0.3s ease;
    }
    .dossier-card:hover {
        background: rgba(15, 23, 42, 0.85) !important;
        border-color: var(--comp-primary) !important;
        box-shadow: 0 4px 15px -3px var(--comp-primary-glow) !important;
    }

    .prefix-select {
        padding-left: 1rem !important;
        padding-right: 2.2rem !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23818cf8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 0.75rem center !important;
        background-size: 1.15rem !important;
        appearance: none !important;
        -webkit-appearance: none !important;
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

<div class="page-container pt-8 pb-20 relative">
    <canvas id="particles-canvas"></canvas>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 relative z-10">
        
        <!-- Header -->
        <div class="text-center py-8 space-y-4">
            <a href="<?= base_url('science-week/register') ?>" class="page-enter-back inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-bold transition-colors text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> เปลี่ยนประเภทการแข่งขัน
            </a>
            <h1 class="page-enter-title text-3xl sm:text-4xl font-black tracking-tight">
                <span class="gradient-text leading-normal">
                    กรอกใบสมัครเข้าร่วมแข่งขัน
                </span>
            </h1>
            <p class="page-enter-subtitle text-slate-400 text-sm font-semibold">ระบุข้อมูลสมาชิกและทีมเพื่อยื่นคำขอสมัครเข้าร่วมแข่งขันระบบดิจิทัล</p>
            <div class="rainbow-divider max-w-20 mx-auto"></div>
        </div>

        <!-- Form Card -->
        <div class="glass-sci-card rounded-3xl p-6 sm:p-10 shadow-lg relative overflow-hidden page-enter-card">
            <!-- Deco lines and corners -->
            <div class="tech-corner tech-corner-tl"></div>
            <div class="tech-corner tech-corner-tr"></div>
            <div class="tech-corner tech-corner-bl"></div>
            <div class="tech-corner tech-corner-br"></div>
            
            <div class="rainbow-divider absolute top-0 left-0 right-0"></div>
            
            <!-- Tech specs indicator -->
            <div class="flex justify-between items-center text-[9px] font-mono text-slate-400 mb-6 uppercase tracking-wider">
                <span>[ SYSTEM: ONLINE ]</span>
                <span>[ FORM_ID: STEAM-0<?= rand(10, 99) ?> ]</span>
                <span class="text-emerald-400 font-bold flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> SECURE CONNECTION</span>
            </div>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-950/40 border border-rose-500/30 text-rose-200 text-sm font-semibold">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form id="regForm" action="<?= base_url('science-week/register/store') ?>" method="POST" class="space-y-6 pt-2" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Selected Competition -->
                <div class="form-field p-5 rounded-2xl border-2 border-dashed space-y-2 bg-gradient-to-r from-slate-950/60 to-indigo-950/30 border-slate-700/60" style="animation-delay: 0.4s">
                    <span class="text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5" style="color: var(--comp-primary);">
                        <i data-lucide="award" class="w-3.5 h-3.5 animate-pulse"></i> ประเภทการแข่งขันที่เลือก
                    </span>
                    <h3 class="text-lg font-black text-white flex items-center gap-2">
                        <span class="inline-block w-2.5 h-2.5 rounded-full" style="background-color: var(--comp-primary);"></span>
                        <?= esc($competition_type) ?>
                    </h3>
                    <input type="hidden" name="competition_type" value="<?= esc($competition_type) ?>">
                </div>

                <!-- Selected Level -->
                <?php
                $levelLimits = [];
                if (!empty($comp['comp_level_limits'])) {
                    $levelLimits = json_decode($comp['comp_level_limits'], true) ?: [];
                }
                if (!empty($levelLimits)):
                ?>
                    <div class="form-field space-y-2" style="animation-delay: 0.45s">
                        <label for="reg_level" class="block text-sm font-bold text-slate-200 flex items-center gap-2">
                            <span>เลือกระดับชั้นที่จะสมัครแข่งขัน</span> <span class="text-rose-450">*</span>
                        </label>
                        <div class="neon-input-wrapper">
                            <select name="reg_level" id="reg_level" required class="neon-input w-full p-4 rounded-2xl font-medium shadow-inner cursor-pointer appearance-none">
                                <option value="" disabled selected>-- เลือกระดับชั้น --</option>
                                <?php foreach ($levelLimits as $lvl): 
                                    $db = \Config\Database::connect();
                                    $activeRegForLevel = $db->table('Tb_ScienceWeek_Registrations')
                                        ->where('reg_competition_type', $comp['comp_name'])
                                        ->where('reg_level', $lvl['level'])
                                        ->where('reg_status !=', 'rejected')
                                        ->countAllResults();
                                    
                                    $isFull = false;
                                    $quotaText = '';
                                    if ($lvl['limit'] > 0) {
                                        $quotaText = " (โควตา {$activeRegForLevel}/{$lvl['limit']} ทีม)";
                                        if ($activeRegForLevel >= $lvl['limit']) {
                                            $isFull = true;
                                            $quotaText = " (เต็มจำนวนโควตา {$lvl['limit']} ทีม)";
                                        }
                                    } else {
                                        $quotaText = " (ไม่จำกัดจำนวน)";
                                    }
                                ?>
                                    <option value="<?= esc($lvl['level']) ?>" <?= $isFull ? 'disabled' : '' ?>>
                                        <?= esc($lvl['level']) ?><?= $quotaText ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <i data-lucide="graduation-cap" class="input-icon-left w-5 h-5"></i>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Row: School Name and Province -->
                <div class="form-field grid grid-cols-1 md:grid-cols-2 gap-4" style="animation-delay: 0.5s">
                    <div class="space-y-2">
                        <label for="school_name" class="block text-sm font-bold text-slate-200 flex items-center gap-2">
                            <span>ชื่อโรงเรียน / สถาบันการศึกษา</span> <span class="text-rose-450">*</span>
                        </label>
                        <div class="neon-input-wrapper">
                            <input type="text" name="school_name" id="school_name" required placeholder="ระบุชื่อเต็ม (เช่น โรงเรียนสวนกุหลาบวิทยาลัย)" class="neon-input w-full p-4 rounded-2xl font-medium shadow-inner">
                            <i data-lucide="school" class="input-icon-left w-5 h-5"></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="school_province" class="block text-sm font-bold text-slate-200 flex items-center gap-2">
                            <span>จังหวัดของโรงเรียน</span> <span class="text-rose-450">*</span>
                        </label>
                        <div class="neon-input-wrapper">
                            <select name="school_province" id="school_province" required class="neon-input w-full p-4 rounded-2xl font-medium shadow-inner cursor-pointer appearance-none">
                                <option value="" disabled selected>-- เลือกจังหวัด --</option>
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
                                foreach ($provinces as $p):
                                ?>
                                    <option value="<?= esc($p) ?>" <?= esc($p) === 'นครสวรรค์' ? 'selected' : '' ?>><?= esc($p) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i data-lucide="map-pin" class="input-icon-left w-5 h-5"></i>
                        </div>
                    </div>
                </div>

                <!-- Team Name -->
                <div class="form-field space-y-2" style="animation-delay: 0.55s">
                    <label for="team_name" class="block text-sm font-bold text-slate-200 flex items-center gap-2">
                        <span>ชื่อทีมที่เข้าแข่งขัน</span> <span class="text-slate-400 text-xs font-normal">(ระบุเฉพาะประเภทที่มีผู้สมัครมากกว่า 1 คน หรือมีชื่อทีมเฉพาะ)</span>
                    </label>
                    <div class="neon-input-wrapper">
                        <input type="text" name="team_name" id="team_name" placeholder="ระบุชื่อทีมของท่าน (เช่น ทีมไซไฟจูเนียร์)..." class="neon-input w-full p-4 rounded-2xl font-medium shadow-inner">
                        <i data-lucide="users" class="input-icon-left w-5 h-5"></i>
                    </div>
                </div>

                <div class="border-t border-slate-800/80 my-6"></div>

                <!-- Team Members -->
                <div class="form-field space-y-3" style="animation-delay: 0.6s">
                    <div class="flex justify-between items-center">
                        <label class="block text-sm font-bold text-slate-200 flex items-center gap-2 flex-wrap">
                            <span>รายชื่อผู้เข้าแข่งขัน / สมาชิกทีม</span>
                            <?php if (!empty($comp['comp_member_limit']) && $comp['comp_member_limit'] > 0): ?>
                                <span class="text-xs text-cyan-400 font-bold">(สูงสุด <?= esc($comp['comp_member_limit']) ?> คน)</span>
                            <?php endif; ?>
                            <span class="text-rose-450">*</span>
                        </label>
                        <button type="button" id="add-member-btn" class="add-btn px-4 py-2 border rounded-xl text-xs font-black flex items-center gap-1.5 shadow-sm">
                            <i data-lucide="plus" class="w-4 h-4"></i> เพิ่มรายชื่อสมาชิก
                        </button>
                    </div>
                    <div id="members-wrapper" class="space-y-3">
                        <div class="flex flex-col sm:flex-row gap-2 sm:items-center list-item-enter dossier-card p-3 rounded-2xl">
                            <span class="text-xs font-mono font-bold text-indigo-300 px-2 select-none sm:py-0 py-1">[MEMBER 01]</span>
                            <div class="flex flex-1 gap-2 items-center flex-wrap sm:flex-nowrap w-full">
                                <div class="w-full sm:w-36 shrink-0">
                                    <select name="member_prefixes[]" required class="neon-input prefix-select w-full p-3.5 rounded-xl font-medium shadow-inner text-base cursor-pointer">
                                        <option value="" disabled selected>คำนำหน้า</option>
                                        <option value="เด็กชาย">เด็กชาย</option>
                                        <option value="เด็กหญิง">เด็กหญิง</option>
                                        <option value="นาย">นาย</option>
                                        <option value="นางสาว">นางสาว</option>
                                        <option value="นาง">นาง</option>
                                        <option value="other">อื่น ๆ (ระบุเอง)</option>
                                    </select>
                                </div>
                                <div class="neon-input-wrapper flex-1 w-full">
                                    <input type="text" name="member_names[]" required placeholder="ชื่อ-นามสกุลจริง (เช่น สมชาย ดีใจ)" class="neon-input w-full p-3.5 rounded-xl font-medium shadow-inner text-base">
                                    <i data-lucide="user" class="input-icon-left w-5 h-5"></i>
                                </div>
                                <button type="button" class="remove-btn p-3.5 rounded-xl opacity-0 pointer-events-none">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-800/80 my-6"></div>

                <!-- Advisors -->
                <div class="form-field space-y-3" style="animation-delay: 0.65s">
                    <div class="flex justify-between items-center">
                        <label class="block text-sm font-bold text-slate-200 flex items-center gap-2">
                            <span>รายชื่ออาจารย์ที่ปรึกษา / ครูควบคุมทีม</span> <span class="text-rose-450">*</span>
                        </label>
                        <button type="button" id="add-advisor-btn" class="add-btn px-4 py-2 border rounded-xl text-xs font-black flex items-center gap-1.5 shadow-sm">
                            <i data-lucide="plus" class="w-4 h-4"></i> เพิ่มคุณครูผู้ควบคุม
                        </button>
                    </div>
                    <div id="advisors-wrapper" class="space-y-3">
                        <div class="flex flex-col sm:flex-row gap-2 sm:items-center list-item-enter dossier-card p-3 rounded-2xl">
                            <span class="text-xs font-mono font-bold text-indigo-300 px-2 select-none sm:py-0 py-1">[ADVISOR 01]</span>
                            <div class="flex flex-1 gap-2 items-center flex-wrap sm:flex-nowrap w-full">
                                <div class="w-full sm:w-36 shrink-0">
                                    <select name="advisor_prefixes[]" required class="neon-input prefix-select w-full p-3.5 rounded-xl font-medium shadow-inner text-base cursor-pointer">
                                        <option value="" disabled selected>คำนำหน้า</option>
                                        <option value="นาย">นาย</option>
                                        <option value="นางสาว">นางสาว</option>
                                        <option value="นาง">นาง</option>
                                        <option value="ดร.">ดร.</option>
                                        <option value="other">อื่น ๆ (ระบุเอง)</option>
                                    </select>
                                </div>
                                <div class="neon-input-wrapper flex-1 w-full">
                                    <input type="text" name="advisor_names[]" required placeholder="ชื่อ-นามสกุลจริง (เช่น สมบัติ ดีพร้อม)" class="neon-input w-full p-3.5 rounded-xl font-medium shadow-inner text-base">
                                    <i data-lucide="user-check" class="input-icon-left w-5 h-5"></i>
                                </div>
                                <button type="button" class="remove-btn p-3.5 rounded-xl opacity-0 pointer-events-none">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-800/80 my-6"></div>

                <!-- Contact details -->
                <div class="form-field grid grid-cols-1 md:grid-cols-2 gap-4" style="animation-delay: 0.7s">
                    <div class="space-y-2">
                        <label for="contact_phone" class="block text-sm font-bold text-slate-200">
                            เบอร์โทรศัพท์มือถือที่ติดต่อได้ <span class="text-rose-450">*</span>
                        </label>
                        <div class="neon-input-wrapper">
                            <input type="tel" name="contact_phone" id="contact_phone" required placeholder="เช่น 0891234567" class="neon-input w-full p-4 rounded-2xl font-medium shadow-inner">
                            <i data-lucide="phone" class="input-icon-left w-5 h-5"></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="contact_email" class="block text-sm font-bold text-slate-200">
                            อีเมลติดต่อผู้ควบคุม
                        </label>
                        <div class="neon-input-wrapper">
                            <input type="email" name="contact_email" id="contact_email" placeholder="เช่น school@email.com" class="neon-input w-full p-4 rounded-2xl font-medium shadow-inner">
                            <i data-lucide="mail" class="input-icon-left w-5 h-5"></i>
                        </div>
                    </div>
                </div>

                <!-- Custom Fields -->
                <?php
                $customFieldsConfig = [];
                if (!empty($comp['comp_custom_fields'])) {
                    $customFieldsConfig = json_decode($comp['comp_custom_fields'], true) ?: [];
                }
                if (!empty($customFieldsConfig)):
                ?>
                    <div class="border-t border-slate-800/80 my-6"></div>
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-slate-200 flex items-center gap-2 mb-2">
                            <i data-lucide="list-plus" class="w-4 h-4 text-indigo-400"></i> ข้อมูลเพิ่มเติมสำหรับการสมัครการแข่งขัน
                        </h4>
                        <div class="grid grid-cols-1 gap-4">
                            <?php foreach ($customFieldsConfig as $idx => $field): 
                                $fieldName = esc($field['label']);
                                $isRequired = !empty($field['required']) ? 'required' : '';
                                $requiredStar = !empty($field['required']) ? '<span class="text-rose-450">*</span>' : '';
                            ?>
                                <div class="form-field space-y-2">
                                    <label class="block text-sm font-bold text-slate-200">
                                        <?= esc($field['label']) ?> <?= $requiredStar ?>
                                    </label>
                                    
                                    <?php if ($field['type'] === 'text'): ?>
                                        <div class="neon-input-wrapper">
                                            <input type="text" name="custom_fields[<?= $fieldName ?>]" <?= $isRequired ?> placeholder="ระบุข้อมูล..." class="neon-input w-full p-4 rounded-2xl font-medium shadow-inner">
                                            <i data-lucide="edit-3" class="input-icon-left w-5 h-5"></i>
                                        </div>
                                    <?php elseif ($field['type'] === 'textarea'): ?>
                                        <div class="neon-input-wrapper">
                                            <textarea name="custom_fields[<?= $fieldName ?>]" <?= $isRequired ?> rows="3" placeholder="ระบุรายละเอียดเพิ่มเติม..." class="neon-input w-full p-4 rounded-2xl font-medium shadow-inner pl-12 pt-3.5 resize-none"></textarea>
                                            <i data-lucide="align-left" class="input-icon-left w-5 h-5" style="top: 24px; transform: none;"></i>
                                        </div>
                                    <?php elseif ($field['type'] === 'url'): ?>
                                        <div class="neon-input-wrapper">
                                            <input type="url" name="custom_fields[<?= $fieldName ?>]" <?= $isRequired ?> placeholder="https://..." class="neon-input w-full p-4 rounded-2xl font-medium shadow-inner">
                                            <i data-lucide="link" class="input-icon-left w-5 h-5"></i>
                                        </div>
                                    <?php elseif ($field['type'] === 'select'): 
                                        $options = array_filter(array_map('trim', explode(',', $field['options'] ?? '')));
                                    ?>
                                        <div class="neon-input-wrapper">
                                            <select name="custom_fields[<?= $fieldName ?>]" <?= $isRequired ?> class="neon-input w-full p-4 rounded-2xl font-medium shadow-inner cursor-pointer appearance-none">
                                                <option value="" disabled selected>-- เลือกตัวเลือก --</option>
                                                <?php foreach ($options as $opt): ?>
                                                    <option value="<?= esc($opt) ?>"><?= esc($opt) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <i data-lucide="chevron-down" class="input-icon-left w-5 h-5"></i>
                                        </div>
                                    <?php elseif ($field['type'] === 'file'): ?>
                                        <div class="neon-input-wrapper">
                                            <input type="file" name="custom_fields_files[<?= $fieldName ?>]" <?= $isRequired ?> class="neon-input w-full p-3 rounded-2xl font-medium shadow-inner file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20">
                                            <i data-lucide="upload-cloud" class="input-icon-left w-5 h-5"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <button type="submit" id="submitBtn" class="w-full py-4 text-white font-black rounded-2xl neon-btn-submit flex items-center justify-center gap-2 mt-8 text-base shadow-lg">
                    <i data-lucide="send" class="w-5 h-5"></i> บันทึกข้อมูลและส่งใบสมัคร
                </button>
            </form>
        </div>
    </div>
</div>
    </div>
</div>

<script>
    // Particle Canvas
    const canvas = document.getElementById('particles-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let particles = [];
        const colors = ['rgba(102,126,234,0.3)', 'rgba(118,75,162,0.3)', 'rgba(240,147,251,0.2)', 'rgba(52,211,153,0.2)'];
        function resize() { canvas.width = canvas.parentElement.offsetWidth; canvas.height = canvas.parentElement.offsetHeight; }
        window.addEventListener('resize', resize);
        resize();

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedX = Math.random() * 0.3 - 0.15;
                this.speedY = Math.random() * 0.3 - 0.15;
                this.color = colors[Math.floor(Math.random() * colors.length)];
            }
            update() {
                this.x += this.speedX; this.y += this.speedY;
                if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            }
            draw() { ctx.fillStyle = this.color; ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill(); }
        }
        for (let i = 0; i < 40; i++) particles.push(new Particle());
        function animate() { ctx.clearRect(0, 0, canvas.width, canvas.height); particles.forEach(p => { p.update(); p.draw(); }); requestAnimationFrame(animate); }
        animate();
    }

    // Dynamic Lists
    const compLimit = <?= isset($comp['comp_member_limit']) ? (int)$comp['comp_member_limit'] : 0 ?>;

    function setupDynamicList(wrapperId, addBtnId, placeholderText, prefixInputName, nameInputName, maxLimit = 0) {
        const wrapper = document.getElementById(wrapperId);
        const addBtn = document.getElementById(addBtnId);
        const isMember = nameInputName.includes('member');
        const prefixLabel = isMember ? 'MEMBER' : 'ADVISOR';
        const iconName = isMember ? 'user' : 'user-check';

        function checkRemoveButtons() {
            const inputs = wrapper.querySelectorAll('.dossier-card');
            inputs.forEach((item) => {
                const btn = item.querySelector('.remove-btn');
                if (inputs.length > 1) {
                    btn.classList.remove('opacity-0', 'pointer-events-none');
                } else {
                    btn.classList.add('opacity-0', 'pointer-events-none');
                }
            });
        }

        function reindexItems() {
            const items = wrapper.querySelectorAll('.dossier-card');
            items.forEach((item, idx) => {
                const count = idx + 1;
                const formattedCount = String(count).padStart(2, '0');
                const span = item.querySelector('span');
                if (span) span.textContent = `[${prefixLabel} ${formattedCount}]`;
                const input = item.querySelector('input[type="text"]');
                if (input) {
                    input.placeholder = placeholderText.replace('1', count);
                }
            });
        }

        addBtn.addEventListener('click', () => {
            const currentItemsCount = wrapper.querySelectorAll('.dossier-card').length;
            if (maxLimit > 0 && currentItemsCount >= maxLimit) {
                Swal.fire({
                    icon: 'warning',
                    title: 'จำกัดจำนวนผู้เข้าแข่งขัน',
                    text: `การแข่งขันนี้จำกัดจำนวนผู้เข้าแข่งขันได้สูงสุดไม่เกิน ${maxLimit} คนต่อทีม`,
                    background: '#ffffff',
                    color: '#1e293b',
                    confirmButtonColor: '#6366f1'
                });
                return;
            }

            const count = currentItemsCount + 1;
            const formattedCount = String(count).padStart(2, '0');
            const item = document.createElement('div');
            item.className = 'flex flex-col sm:flex-row gap-2 sm:items-center list-item-enter dossier-card p-3 rounded-2xl';
            
            const prefixOptions = isMember 
                ? `
                    <option value="" disabled selected>คำนำหน้า</option>
                    <option value="เด็กชาย">เด็กชาย</option>
                    <option value="เด็กหญิง">เด็กหญิง</option>
                    <option value="นาย">นาย</option>
                    <option value="นางสาว">นางสาว</option>
                    <option value="นาง">นาง</option>
                    <option value="other">อื่น ๆ (ระบุเอง)</option>
                `
                : `
                    <option value="" disabled selected>คำนำหน้า</option>
                    <option value="นาย">นาย</option>
                    <option value="นางสาว">นางสาว</option>
                    <option value="นาง">นาง</option>
                    <option value="ดร.">ดร.</option>
                    <option value="other">อื่น ๆ (ระบุเอง)</option>
                `;

            item.innerHTML = `
                <span class="text-xs font-mono font-bold text-indigo-300 px-2 select-none sm:py-0 py-1">[${prefixLabel} ${formattedCount}]</span>
                <div class="flex flex-1 gap-2 items-center flex-wrap sm:flex-nowrap w-full">
                    <div class="w-full sm:w-36 shrink-0">
                        <select name="${prefixInputName}" required class="neon-input prefix-select w-full p-3.5 rounded-xl font-medium shadow-inner text-base cursor-pointer">
                            ${prefixOptions}
                        </select>
                    </div>
                    <div class="neon-input-wrapper flex-1 w-full">
                        <input type="text" name="${nameInputName}" required placeholder="${placeholderText.replace('1', count)}" class="neon-input w-full p-3.5 rounded-xl font-medium shadow-inner text-base">
                        <i data-lucide="${iconName}" class="input-icon-left w-5 h-5"></i>
                    </div>
                    <button type="button" class="remove-btn p-3.5 rounded-xl">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                    </button>
                </div>
            `;
            wrapper.appendChild(item);
            lucide.createIcons();
            item.querySelector('.remove-btn').addEventListener('click', () => { item.remove(); checkRemoveButtons(); reindexItems(); });
            checkRemoveButtons();
        });

        checkRemoveButtons();
    }

    setupDynamicList('members-wrapper', 'add-member-btn', 'ระบุชื่อ-นามสกุล สมาชิกคนที่ 1', 'member_prefixes[]', 'member_names[]', compLimit);
    setupDynamicList('advisors-wrapper', 'add-advisor-btn', 'ระบุชื่อ-นามสกุล อาจารย์ที่ปรึกษาคนที่ 1', 'advisor_prefixes[]', 'advisor_names[]');

    // Custom Prefix handling for "other"
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('prefix-select') && e.target.value === 'other') {
            const select = e.target;
            const parent = select.parentElement;
            const inputName = select.name;
            
            // Create a custom text input to replace the select
            const input = document.createElement('input');
            input.type = 'text';
            input.name = inputName;
            input.required = true;
            input.placeholder = 'ระบุคำนำหน้า';
            input.className = 'neon-input w-full p-3.5 rounded-xl font-medium shadow-inner text-base';
            input.style.setProperty('padding-left', '1rem', 'important'); // Left padding adjustments override
            
            // Clear wrapper and append input
            parent.innerHTML = '';
            parent.appendChild(input);
            input.focus();
        }
    });

    // Form Submission
    const form = document.getElementById('regForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!form.checkValidity()) { form.reportValidity(); return; }

        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
        submitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>กำลังส่งข้อมูลเข้าระบบ...</span>
        `;

        const formData = new FormData(form);
        fetch(form.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'ลงทะเบียนสำเร็จ!',
                    text: 'ข้อมูลการสมัครของคุณได้รับเรียบร้อยแล้ว',
                    background: '#ffffff',
                    color: '#1e293b',
                    confirmButtonColor: '#6366f1'
                }).then(() => { window.location.href = data.redirect; });
            } else {
                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: data.message || 'ข้อมูลไม่ถูกต้อง', background: '#ffffff', color: '#1e293b', confirmButtonColor: '#ef4444' });
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-80', 'cursor-not-allowed');
                submitBtn.innerHTML = `<i data-lucide="send" class="w-5 h-5"></i> ส่งข้อมูลการสมัครลงทะเบียน`;
                lucide.createIcons();
            }
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'ขออภัย', text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้ในขณะนี้', background: '#ffffff', color: '#1e293b', confirmButtonColor: '#ef4444' });
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-80', 'cursor-not-allowed');
            submitBtn.innerHTML = `<i data-lucide="send" class="w-5 h-5"></i> ส่งข้อมูลการสมัครลงทะเบียน`;
            lucide.createIcons();
        });
    });
</script>
<?= $this->endSection() ?>
