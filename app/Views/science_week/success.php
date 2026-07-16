<?= $this->extend('science_week/layout/main') ?>

<?= $this->section('content') ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sarabun:wght@300;400;500;600;700;800&display=swap');

    .page-container {
        font-family: 'Outfit', 'Sarabun', sans-serif;
        background: linear-gradient(135deg, #eef2f6 0%, #f8fafc 50%, #e0e7ff 100%);
        color: #0f172a;
        flex: 1;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    /* Ticket Badge */
    .ticket-badge {
        background: #ffffff;
        border: 1px solid rgba(99, 102, 241, 0.15);
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08), 0 4px 12px rgba(99, 102, 241, 0.03);
        position: relative;
        overflow: hidden;
    }

    .ticket-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899, #3b82f6, #10b981);
        background-size: 200% auto;
        animation: rainbowSlide 4s linear infinite;
    }

    @keyframes rainbowSlide {
        0% {
            background-position: 0% center;
        }

        100% {
            background-position: 200% center;
        }
    }

    /* Ticket Tear-off side-cuts */
    .ticket-cut-left,
    .ticket-cut-right {
        position: absolute;
        top: 40%;
        width: 24px;
        height: 24px;
        background: #f1f5f9;
        /* Matches background */
        border-radius: 50%;
        z-index: 10;
        border: 1px solid rgba(99, 102, 241, 0.15);
    }

    .ticket-cut-left {
        left: -12px;
        box-shadow: inset -5px 0 8px rgba(0, 0, 0, 0.03);
    }

    .ticket-cut-right {
        right: -12px;
        box-shadow: inset 5px 0 8px rgba(0, 0, 0, 0.03);
    }

    /* Entrance animations */
    .success-icon {
        animation: successBounceIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
    }

    .success-title {
        animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both;
    }

    .success-subtitle {
        animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.5s both;
    }

    .success-ticket {
        animation: fadeInUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.5s both;
    }

    .success-buttons {
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.8s both;
    }

    @keyframes successBounceIn {
        from {
            opacity: 0;
            transform: scale(0.3) rotate(-15deg);
        }

        to {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Confetti particles */
    .confetti-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 50;
        overflow: hidden;
    }

    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 2px;
        top: -20px;
        animation: confettiFall 4s linear forwards;
    }

    @keyframes confettiFall {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
        }

        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0;
        }
    }

    /* Highlight badge style */
    .field-label {
        color: #4f5e71;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .field-value {
        color: #0f172a;
        font-weight: 800;
        font-size: 1.05rem;
    }

    .print-only-link {
        display: none;
    }

    /* Print styles optimized for A4 Landscape split-screen */
    @media print {
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        html,
        body {
            background: #ffffff !important;
            background-image: none !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
            height: auto !important;
        }

        /* Hide layout items */
        .sci-navbar,
        footer,
        .no-print,
        #particles-canvas,
        .confetti-container,
        .success-icon,
        .space-bg-decorations,
        .ticket-cut-left,
        .ticket-cut-right {
            display: none !important;
        }

        main {
            padding: 0 !important;
            margin: 0 !important;
            background: none !important;
            background-image: none !important;
        }

        .page-container {
            background: none !important;
            background-image: none !important;
            min-height: auto !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Full A4 landscape print container - using standard relative widths instead of absolute */
        .printable-area {
            position: relative !important;
            width: 100% !important;
            max-width: 275mm !important; /* Avoid clipping at edges */
            height: 180mm !important;
            margin: 0 auto !important;
            background: #ffffff !important;
            background-image: none !important;
            color: #000000 !important;
            border: 2px solid #000000 !important;
            box-shadow: none !important;
            padding: 24px !important;
            border-radius: 12px !important;
            page-break-inside: avoid !important;
            box-sizing: border-box !important;
            
            /* Flex Row for left/right split */
            display: flex !important;
            flex-direction: row !important;
            gap: 24px !important;
        }

        /* Left/Right Panels for Print - Exactly 50/50 split */
        .print-left-panel {
            width: 50% !important;
            padding-right: 24px !important;
            border-right: 2px dashed #000000 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-start !important; /* Stack items to the top instead of spreading them */
            box-sizing: border-box !important;
        }
        
        .print-right-panel {
            width: 50% !important;
            padding-left: 24px !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            border: none !important;
            box-sizing: border-box !important;
        }

        /* Scale down everything inside card for print */
        .printable-area * {
            font-size: 11px !important;
            line-height: 1.4 !important;
            color: #000000 !important;
        }

        .printable-area h2 {
            font-size: 13px !important;
            font-weight: 800 !important;
            color: #000000 !important;
        }

        .printable-area h3 {
            font-size: 18px !important;
            font-weight: 900 !important;
            color: #000000 !important;
        }

        .printable-area .field-value {
            font-size: 12px !important;
            font-weight: 800 !important;
            color: #000000 !important;
        }

        .printable-area .field-label {
            font-size: 9px !important;
            font-weight: 700 !important;
            color: #000000 !important;
        }

        .printable-area p {
            margin: 0 !important;
        }

        .printable-area div,
        .printable-area ul,
        .printable-area li {
            margin-top: 1px !important;
            margin-bottom: 1px !important;
        }

        .printable-area .py-5,
        .printable-area .p-5,
        .printable-area .p-6,
        .printable-area .p-4 {
            padding: 6px 10px !important;
        }

        .printable-area .space-y-6 > * + * {
            margin-top: 4px !important;
        }

        .printable-area .space-y-4 > * + * {
            margin-top: 3px !important;
        }

        .print-qr-img {
            width: 50mm !important;
            height: 50mm !important;
            margin-bottom: 10px !important;
        }

        /* Enable Contact details on print */
        .group-contact-section {
            background: #ffffff !important;
            border: 1px solid #000000 !important;
            padding: 6px !important;
            border-radius: 8px !important;
            margin-top: 4px !important;
            margin-bottom: 4px !important;
            text-align: center !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .contact-title {
            font-size: 7.5px !important;
            margin-bottom: 2px !important;
            color: #4338ca !important;
        }

        .contact-content {
            display: flex !important;
            flex-direction: row !important;
            gap: 12px !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .contact-qr {
            width: 18mm !important;
            height: 18mm !important;
            padding: 1px !important;
            margin: 0 !important;
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            display: flex !important;
        }

        .no-print-btn {
            display: none !important;
        }

        .print-only-link {
            display: block !important;
            font-size: 7px !important;
            color: #0f172a !important;
            font-family: monospace !important;
        }
    }

    .barcode-line {
        height: 40px;
        background: linear-gradient(90deg,
                #0f172a 0px, #0f172a 2px, transparent 2px, transparent 5px,
                #0f172a 5px, #0f172a 9px, transparent 9px, transparent 11px,
                #0f172a 11px, #0f172a 12px, transparent 12px, transparent 15px,
                #0f172a 15px, #0f172a 19px, transparent 19px, transparent 23px,
                #0f172a 23px, #0f172a 24px, transparent 24px);
        background-size: 26px 100%;
    }

    /* Button hover effects */
    .btn-print {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.1);
    }

    .btn-home {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(99, 102, 241, 0.4);
    }
</style><?php 
$isPrintMode = (service('request')->getGet('mode') === 'print'); 
?>

<!-- Confetti Celebration -->
<?php if (!$isPrintMode): ?>
<div class="confetti-container" id="confetti-container"></div>
<?php endif; ?>

<div class="page-container pt-8 pb-20 relative print:p-0 print:m-0">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 relative z-10 print:max-w-none print:w-full print:p-0 print:m-0">

        <!-- Top Status -->
        <div class="text-center py-6 space-y-3 no-print">
            <?php if ($isPrintMode): ?>
                <div class="success-icon w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-650 text-white flex items-center justify-center mx-auto shadow-xl">
                    <i data-lucide="ticket" class="w-10 h-10"></i>
                </div>
                <h1 class="success-title text-3xl sm:text-4xl font-extrabold text-white tracking-tight">บัตรประจำตัวผู้เข้าแข่งขัน</h1>
                <p class="success-subtitle text-slate-100 text-sm sm:text-base font-medium">กรุณาจัดพิมพ์เอกสารนี้ หรือบันทึกภาพเก็บไว้เพื่อนำมาเช็คอินในวันจัดกิจกรรม</p>
            <?php else: ?>
                <div class="success-icon w-20 h-20 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center mx-auto shadow-xl shadow-emerald-200">
                    <i data-lucide="check" class="w-10 h-10 stroke-[3]"></i>
                </div>
                <h1 class="success-title text-3xl sm:text-4xl font-extrabold text-white tracking-tight">ลงทะเบียนสำเร็จเรียบร้อย!</h1>
                <p class="success-subtitle text-slate-100 text-sm sm:text-base font-medium">กรุณาเข้าร่วมกลุ่มติดต่อประสานงาน และติดตามข่าวสารประกาศรายชื่อผู้มีสิทธิ์เข้าร่วมแข่งขัน</p>
            <?php endif; ?>
        </div>

        <?php if ($isPrintMode): ?>
            <!-- Ticket Card (Print Mode) -->
            <div class="ticket-badge printable-area rounded-3xl p-8 sm:p-10 success-ticket flex flex-col md:flex-row gap-6 print:flex-row print:gap-4 print:p-6">
                <!-- Ticket Cutouts on the sides (Hidden on print) -->
                <div class="ticket-cut-left no-print"></div>
                <div class="ticket-cut-right no-print"></div>

                <!-- Left Side (Info) -->
                <div class="print-left-panel flex-1 space-y-6">
                    <!-- Code Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-200 pb-5 gap-4 pt-2">
                        <div class="flex items-center gap-4">
                            <img src="<?= base_url('uploads/science_week/logo/S__49446940.jpg') ?>" alt="STEAM Logo" class="w-14 h-14 rounded-2xl border border-indigo-150 object-cover shadow-sm shrink-0">
                            <div>
                                <h2 class="text-sm font-black uppercase text-indigo-655 tracking-wider">STEAM SCIENCE WEEK 2026</h2>
                                <p class="text-xs text-slate-500 mt-0.5 font-semibold">สนุกคิด ติดปีกจินตนาการ</p>
                            </div>
                        </div>
                        <div class="text-left sm:text-right bg-slate-50 border border-slate-100 px-4 py-2.5 rounded-2xl shrink-0 w-full sm:w-auto">
                            <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider">รหัสใบสมัคร</span>
                            <h3 class="text-2xl font-black text-indigo-600 font-mono tracking-wider leading-none mt-1">
                                <?= $reg['reg_code'] ?>
                            </h3>
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="space-y-6">
                        <!-- Competition Name -->
                        <div class="bg-indigo-50/40 border border-indigo-100/50 p-5 rounded-2xl">
                            <span class="field-label text-indigo-700">
                                <i data-lucide="award" class="w-4 h-4"></i> ประเภทการแข่งขัน
                            </span>
                            <div class="field-value text-lg sm:text-xl text-slate-900 mt-1.5 flex flex-wrap items-center gap-3">
                                <span><?= $reg['reg_competition_type'] ?></span>
                                <?php if (!empty($reg['reg_level'])): ?>
                                    <span class="inline-flex px-3 py-1 rounded-xl text-xs font-black bg-indigo-600 text-white shadow-sm shadow-indigo-200"><?= esc($reg['reg_level']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- School / Team Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-slate-50/70 border border-slate-100 p-5 rounded-2xl">
                                <span class="field-label text-slate-600">
                                    <i data-lucide="school" class="w-4 h-4"></i> โรงเรียน / สถาบันการศึกษา
                                </span>
                                <p class="field-value text-slate-900 mt-1.5"><?= $reg['reg_school_name'] ?></p>
                            </div>
                            <div class="bg-slate-50/70 border border-slate-100 p-5 rounded-2xl">
                                <span class="field-label text-slate-600">
                                    <i data-lucide="users-2" class="w-4 h-4"></i> ชื่อทีมที่ลงทะเบียน
                                </span>
                                <p class="field-value text-slate-900 mt-1.5">
                                    <?= $reg['reg_team_name'] ?: 'ทั่วไป (ไม่มีชื่อทีม)' ?>
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-slate-200 my-4"></div>

                        <!-- Members & Advisors Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <span class="field-label text-slate-600 mb-3">
                                    <i data-lucide="user-check" class="w-4 h-4 text-cyan-500"></i> สมาชิกผู้เข้าแข่งขัน
                                </span>
                                <ul class="space-y-2.5">
                                    <?php $members = json_decode($reg['reg_members'], true) ?: []; ?>
                                    <?php foreach ($members as $index => $m):
                                        $mText = '';
                                        if (is_array($m)) {
                                            $prefix = trim($m['prefix'] ?? '');
                                            $name = trim($m['name'] ?? '');
                                            $mText = ($prefix !== '' ? $prefix . ' ' : '') . $name;
                                            if (!empty($m['custom_fields'])) {
                                                $cfStr = [];
                                                foreach ($m['custom_fields'] as $cfKey => $cfVal) {
                                                    if ($cfVal !== '') {
                                                        $cfStr[] = "{$cfKey}: {$cfVal}";
                                                    }
                                                }
                                                if (!empty($cfStr)) {
                                                    $mText .= ' (' . implode(', ', $cfStr) . ')';
                                                }
                                            }
                                        } else {
                                            $mText = $m;
                                        }
                                        ?>
                                        <li class="text-slate-800 text-sm flex items-start gap-2.5 font-bold" title="<?= esc($mText) ?>">
                                            <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 shadow-sm shrink-0 mt-1"></span>
                                            <span class="break-words"><?= esc($mText) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div>
                                <span class="field-label text-slate-600 mb-3">
                                    <i data-lucide="user-cog" class="w-4 h-4 text-indigo-500"></i> คุณครูผู้ควบคุม / ที่ปรึกษา
                                </span>
                                <ul class="space-y-2.5">
                                    <?php $advisors = json_decode($reg['reg_advisors'], true) ?: []; ?>
                                    <?php foreach ($advisors as $index => $a): ?>
                                        <li class="text-slate-800 text-sm flex items-start gap-2.5 font-bold">
                                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 shadow-sm shrink-0 mt-1"></span>
                                            <span class="break-words"><?= htmlspecialchars($a) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Custom Answers Section -->
                        <?php
                        $customAnswers = [];
                        if (!empty($reg['reg_custom_fields'])) {
                            $customAnswers = json_decode($reg['reg_custom_fields'], true) ?: [];
                        }
                        if (!empty($customAnswers)):
                            ?>
                            <div class="border-t border-dashed border-slate-200 my-4"></div>
                            <div>
                                <span class="field-label text-slate-600 mb-3">
                                    <i data-lucide="clipboard-list" class="w-4 h-4 text-slate-600"></i>
                                    ข้อมูลเฉพาะการแข่งขันเพิ่มเติม
                                </span>
                                <div class="space-y-3 bg-slate-50/70 border border-slate-100 p-5 rounded-2xl">
                                    <?php foreach ($customAnswers as $q => $a): ?>
                                        <div class="text-sm flex flex-col gap-1">
                                            <span class="text-slate-550 font-bold text-xs"><?= htmlspecialchars($q) ?>:</span>
                                            <?php if (empty($a)): ?>
                                                <span class="text-slate-400 italic">ไม่ได้ระบุ</span>
                                            <?php elseif (strpos($a, 'uploads/science_week/') === 0): ?>
                                                <a href="<?= base_url($a) ?>" target="_blank" class="inline-flex items-center gap-1 text-indigo-655 hover:text-indigo-800 hover:underline font-extrabold">
                                                    <i data-lucide="file-text" class="w-4 h-4"></i> ดาวน์โหลดไฟล์หลักฐานที่แนบมา
                                                </a>
                                            <?php elseif (filter_var($a, FILTER_VALIDATE_URL)): ?>
                                                <a href="<?= htmlspecialchars($a) ?>" target="_blank" class="inline-flex items-center gap-1 text-indigo-655 hover:text-indigo-800 hover:underline font-extrabold">
                                                    <i data-lucide="external-link" class="w-4 h-4"></i> ลิงก์แนบภายนอก
                                                </a>
                                            <?php else: ?>
                                                <span class="text-slate-900 font-extrabold"><?= htmlspecialchars($a) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- QR Code & Line Info -->
                        <?php if (!empty($comp['comp_group_link']) || !empty($comp['comp_group_qr'])): ?>
                            <div class="border-t border-dashed border-slate-200 my-4 no-print-divider"></div>
                            <div class="bg-indigo-50 border border-indigo-100/70 p-6 rounded-3xl text-center space-y-4 shadow-sm group-contact-section">
                                <span class="text-xs text-indigo-805 font-black block uppercase tracking-wider contact-title">ช่องทางประสานงานรับข่าวสารการแข่งขัน</span>

                                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 contact-content">
                                    <?php if (!empty($comp['comp_group_qr'])): ?>
                                        <div class="w-32 h-32 rounded-2xl overflow-hidden border border-indigo-150 shadow-md bg-white p-2 flex items-center justify-center contact-qr shrink-0">
                                            <img src="<?= base_url($comp['comp_group_qr']) ?>" alt="Group QR Code" class="w-full h-full object-contain">
                                        </div>
                                    <?php endif; ?>

                                    <div class="text-left space-y-2 contact-text">
                                        <?php if (!empty($comp['comp_group_link'])): ?>
                                            <a href="<?= esc($comp['comp_group_link']) ?>" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-indigo-650 hover:bg-indigo-750 text-white font-extrabold text-xs sm:text-sm transition-colors shadow-md no-print-btn">
                                                <i data-lucide="message-square" class="w-4.5 h-4.5"></i> เข้าร่วมกลุ่มไลน์ประสานงาน
                                            </a>
                                            <span class="print-only-link hidden"><?= esc($comp['comp_group_link']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="border-t border-dashed border-slate-200 my-4 mt-auto"></div>

                        <!-- Contact and Date footer -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50 p-4 rounded-2xl border border-slate-100 text-xs">
                            <div>
                                <span class="text-slate-500 font-bold tracking-wider">เบอร์โทรศัพท์ติดต่อ</span>
                                <span class="text-slate-900 font-extrabold font-mono text-sm block mt-0.5"><?= $reg['reg_contact_phone'] ?></span>
                            </div>
                            <div class="sm:text-right">
                                <span class="text-slate-500 font-bold tracking-wider">วันที่เวลาที่ส่งสมัคร</span>
                                <span class="text-slate-900 font-extrabold font-mono text-sm block mt-0.5"><?= date('d/m/Y H:i', strtotime($reg['reg_created_at'])) ?> น.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side (QR Code for Check-in) -->
                <div class="print-right-panel w-full md:w-1/3 pt-6 md:pt-0 border-t md:border-t-0 md:border-l border-slate-200 border-dashed flex flex-col items-center justify-center gap-4 text-center">
                    <?php 
                        $qrUrl = base_url('science-week/staff/checkin/' . $reg['reg_code']); 
                        $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrUrl);
                    ?>
                    <img src="<?= $qrImageUrl ?>" alt="Check-in QR Code" class="print-qr-img w-32 h-32 sm:w-40 sm:h-40 p-2 border-2 border-indigo-100 rounded-xl bg-white shadow-sm" crossorigin="anonymous">
                    <div class="space-y-1.5 mt-2">
                        <span class="text-sm sm:text-base font-black text-indigo-600 uppercase tracking-widest block">เช็คอินร่วมกิจกรรม</span>
                        <span class="text-slate-500 font-bold text-xs sm:text-sm block">กรุณาเตรียมหน้านี้ไว้ให้เจ้าหน้าที่สแกน<br>เพื่อยืนยันสิทธิ์ในวันเข้าร่วมกิจกรรม</span>
                        <span class="text-xs sm:text-sm font-mono tracking-widest text-slate-800 uppercase font-black mt-3 block py-1.5 px-3 bg-slate-100 rounded-lg">ID: <?= $reg['reg_code'] ?></span>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 mt-8 no-print success-buttons max-w-sm sm:max-w-none mx-auto w-full">
                <button onclick="window.print()" class="btn-print w-full sm:w-auto px-6 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-extrabold rounded-2xl border border-slate-200 flex items-center justify-center gap-2 shadow-sm">
                    <i data-lucide="printer" class="w-5 h-5"></i> พิมพ์ใบสมัครนี้ (PDF)
                </button>
                <a href="<?= base_url('science-week') ?>" class="btn-home w-full sm:w-auto px-6 py-3.5 text-white font-extrabold rounded-2xl flex items-center justify-center gap-2 shadow-lg">
                    <i data-lucide="home" class="w-5 h-5"></i> กลับหน้าหลักกิจกรรม
                </a>
            </div>

        <?php else: ?>
            <!-- Success Info Card (General Register Success Mode) -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
                <!-- Summary Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-200 dark:border-slate-800 pb-5 gap-4">
                    <div class="flex items-center gap-4">
                        <img src="<?= base_url('uploads/science_week/logo/S__49446940.jpg') ?>" alt="STEAM Logo" class="w-14 h-14 rounded-2xl border border-indigo-150 object-cover shadow-sm shrink-0">
                        <div>
                            <h2 class="text-sm font-black uppercase text-indigo-650 dark:text-indigo-400 tracking-wider">STEAM SCIENCE WEEK 2026</h2>
                            <p class="text-xs text-slate-500 mt-0.5 font-semibold">สนุกคิด ติดปีกจินตนาการ</p>
                        </div>
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100/50 dark:border-indigo-900/30 px-4 py-2 rounded-2xl shrink-0 w-full sm:w-auto text-left sm:text-right">
                        <span class="text-[10px] text-indigo-700 dark:text-indigo-400 font-bold block uppercase tracking-wider">รหัสใบสมัคร</span>
                        <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-300 font-mono tracking-wider leading-none mt-1">
                            <?= $reg['reg_code'] ?>
                        </h3>
                    </div>
                </div>

                <!-- Basic Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-100 dark:border-slate-850">
                        <span class="text-slate-500 font-bold block mb-1">ประเภทการแข่งขัน</span>
                        <span class="font-extrabold text-slate-900 dark:text-slate-100 text-sm">
                            <?= esc($reg['reg_competition_type']) ?>
                            <?php if (!empty($reg['reg_level'])): ?>
                                <span class="ml-1 inline-flex px-2 py-0.5 rounded-lg text-[10px] font-black bg-indigo-600 text-white"><?= esc($reg['reg_level']) ?></span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-100 dark:border-slate-850">
                        <span class="text-slate-500 font-bold block mb-1">สถาบันการศึกษา</span>
                        <span class="font-extrabold text-slate-900 dark:text-slate-100 text-sm"><?= esc($reg['reg_school_name']) ?></span>
                    </div>
                </div>

                <!-- Contact Line Group section -->
                <?php if (!empty($comp['comp_group_link']) || !empty($comp['comp_group_qr'])): ?>
                    <div class="bg-indigo-50/60 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40 p-6 rounded-2xl text-center space-y-4 shadow-inner">
                        <span class="text-xs text-indigo-800 dark:text-indigo-350 font-black block uppercase tracking-wider">ช่องทางการติดต่อประสานงาน (กลุ่ม LINE)</span>
                        
                        <div class="flex flex-col items-center justify-center gap-4">
                            <?php if (!empty($comp['comp_group_qr'])): ?>
                                <div class="w-40 h-40 rounded-2xl overflow-hidden border border-indigo-200 bg-white p-2 shadow-sm shrink-0">
                                    <img src="<?= base_url($comp['comp_group_qr']) ?>" alt="Group QR Code" class="w-full h-full object-contain">
                                </div>
                            <?php endif; ?>

                            <div class="space-y-2">
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold">สแกน QR Code หรือคลิกปุ่มด้านล่างเพื่อเข้าร่วมกลุ่มประสานงานการแข่งขัน</p>
                                <?php if (!empty($comp['comp_group_link'])): ?>
                                    <a href="<?= esc($comp['comp_group_link']) ?>" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-[#06C755] hover:bg-[#05b04b] text-white font-extrabold text-xs sm:text-sm transition-all shadow-md">
                                        <i data-lucide="message-square" class="w-4.5 h-4.5"></i> เข้าร่วมกลุ่ม Line ประสานงาน
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Important Notice Banner (High-contrast Callout) -->
                <div class="relative overflow-hidden rounded-3xl border-2 border-amber-500/40 bg-gradient-to-r from-amber-500/10 via-amber-600/5 to-transparent p-6 shadow-lg shadow-amber-500/5 transition-all duration-300 transform hover:scale-[1.01] flex flex-col sm:flex-row items-center sm:items-start gap-4">
                    <!-- Glowing pulsing icon container -->
                    <div class="relative flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-500/20 border border-amber-500/35 text-amber-600 dark:text-amber-400 shrink-0 shadow-inner">
                        <span class="animate-ping absolute inline-flex h-8 w-8 rounded-full bg-amber-500/30 opacity-75"></span>
                        <i data-lucide="alert-triangle" class="w-7 h-7 relative z-10 text-amber-600 dark:text-amber-400"></i>
                    </div>
                    <!-- Content -->
                    <div class="text-center sm:text-left space-y-1">
                        <h4 class="text-base font-black text-amber-800 dark:text-amber-300 flex items-center justify-center sm:justify-start gap-1.5 uppercase tracking-wider">
                            ⚠️ โปรดอ่าน: หมายเหตุสำคัญมาก
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-200 font-extrabold leading-relaxed">
                            ขณะนี้ยังไม่มีความจำเป็นต้องจัดพิมพ์เอกสารใบสมัคร
                        </p>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-350 font-bold leading-relaxed">
                            ระบบจะเปิดให้ดาวน์โหลดและจัดพิมพ์ใบสมัครอย่างเป็นทางการอีกครั้ง <span class="text-indigo-600 dark:text-indigo-400 font-black underline underline-offset-4">ในขั้นตอนของการประกาศรายชื่อผู้มีสิทธิ์เข้าร่วมการแข่งขัน</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-center mt-8 max-w-xs sm:max-w-none mx-auto w-full">
                <a href="<?= base_url('science-week') ?>" class="btn-home px-8 py-3.5 text-white font-extrabold rounded-2xl flex items-center justify-center gap-2 shadow-lg w-full sm:w-auto">
                    <i data-lucide="home" class="w-5 h-5"></i> กลับหน้าหลักกิจกรรม
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    // Confetti Celebration
    <?php if (!$isPrintMode): ?>
    const confettiContainer = document.getElementById('confetti-container');
    const confettiColors = ['#4f46e5', '#8b5cf6', '#ec4899', '#3b82f6', '#10b981', '#fbbf24', '#f43f5e', '#06b6d4'];

    function createConfetti() {
        for (let i = 0; i < 70; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.background = confettiColors[Math.floor(Math.random() * confettiColors.length)];
                confetti.style.width = (Math.random() * 8 + 4) + 'px';
                confetti.style.height = (Math.random() * 8 + 4) + 'px';
                confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
                confetti.style.animationDelay = (Math.random() * 0.5) + 's';
                confettiContainer.appendChild(confetti);
                setTimeout(() => confetti.remove(), 5000);
            }, i * 35);
        }
    }

    // Fire confetti on load
    setTimeout(createConfetti, 500);
    <?php endif; ?>
</script>
<?= $this->endSection() ?>