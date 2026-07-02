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

    /* Print styles optimized for exactly half A4 portrait */
    @media print {
        @page {
            size: A4 portrait;
            margin: 0;
        }

        html,
        body {
            background: #ffffff !important;
            background-image: none !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            overflow: hidden !important;
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

        /* Compact Half A4 size print container */
        .printable-area {
            position: absolute !important;
            left: 50% !important;
            top: 15px !important;
            transform: translateX(-50%) !important;
            width: 142mm !important;
            /* Compact width approx A5 width */
            max-height: 135mm !important;
            /* Hard constraint to keep under half page */
            background: #ffffff !important;
            background-image: none !important;
            color: #000000 !important;
            border: 1.5px solid #0f172a !important;
            box-shadow: none !important;
            padding: 12px 16px !important;
            border-radius: 12px !important;
            page-break-inside: avoid !important;
            overflow: hidden !important;
        }

        /* Scale down everything inside card for print */
        .printable-area * {
            font-size: 8px !important;
            line-height: 1.15 !important;
        }

        .printable-area h2 {
            font-size: 10px !important;
        }

        .printable-area h3 {
            font-size: 14px !important;
        }

        .printable-area .field-value {
            font-size: 9.5px !important;
            font-weight: 850 !important;
        }

        .printable-area .field-label {
            font-size: 7.5px !important;
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
            padding: 5px 8px !important;
        }

        .printable-area .space-y-6>*+* {
            margin-top: 4px !important;
        }

        .printable-area .space-y-4>*+* {
            margin-top: 3px !important;
        }

        .barcode-line {
            height: 15px !important;
            background-size: 20px 100% !important;
        }

        /* Enable Contact details on print */
        .group-contact-section {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
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
</style>

<!-- Confetti Celebration -->
<div class="confetti-container" id="confetti-container"></div>

<div class="page-container pt-8 pb-20 relative">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 relative z-10">

        <!-- Top Status -->
        <div class="text-center py-6 space-y-3 no-print">
            <div
                class="success-icon w-20 h-20 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center mx-auto shadow-xl shadow-emerald-200">
                <i data-lucide="check" class="w-10 h-10 stroke-[3]"></i>
            </div>
            <h1 class="success-title text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                ลงทะเบียนสำเร็จเรียบร้อย!</h1>
            <p class="success-subtitle text-slate-100 text-sm sm:text-base font-medium">
                ระบบทำการออกบัตรสมัครเพื่อตรวจสอบหลักฐานการเข้าแข่งเรียบร้อย</p>
        </div>

        <!-- Ticket Card -->
        <div class="ticket-badge printable-area rounded-3xl p-8 sm:p-10 space-y-6 success-ticket">
            <!-- Ticket Cutouts on the sides -->
            <div class="ticket-cut-left no-print"></div>
            <div class="ticket-cut-right no-print"></div>

            <!-- Code Header -->
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-200 pb-5 gap-4 pt-2">
                <div class="flex items-center gap-4">
                    <img src="<?= base_url('uploads/science_week/logo/S__49446940.jpg') ?>" alt="STEAM Logo"
                        class="w-14 h-14 rounded-2xl border border-indigo-150 object-cover shadow-sm shrink-0">
                    <div>
                        <h2 class="text-sm font-black uppercase text-indigo-650 tracking-wider">STEAM SCIENCE WEEK 2026
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5 font-semibold">สนุกคิด ติดปีกจินตนาการ</p>
                    </div>
                </div>
                <div
                    class="text-left sm:text-right bg-slate-50 border border-slate-100 px-4 py-2.5 rounded-2xl shrink-0 w-full sm:w-auto">
                    <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider">รหัสใบสมัคร
                        (Application ID)</span>
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
                            <span
                                class="inline-flex px-3 py-1 rounded-xl text-xs font-black bg-indigo-600 text-white shadow-sm shadow-indigo-200"><?= esc($reg['reg_level']) ?></span>
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
                                <li class="text-slate-800 text-sm flex items-start gap-2.5 font-bold"
                                    title="<?= esc($mText) ?>">
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
                                        <a href="<?= base_url($a) ?>" target="_blank"
                                            class="inline-flex items-center gap-1 text-indigo-650 hover:text-indigo-800 hover:underline font-extrabold">
                                            <i data-lucide="file-text" class="w-4 h-4"></i> ดาวน์โหลดไฟล์หลักฐานที่แนบมา
                                        </a>
                                    <?php elseif (filter_var($a, FILTER_VALIDATE_URL)): ?>
                                        <a href="<?= htmlspecialchars($a) ?>" target="_blank"
                                            class="inline-flex items-center gap-1 text-indigo-655 hover:text-indigo-800 hover:underline font-extrabold">
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
                    <div
                        class="bg-indigo-50 border border-indigo-100/70 p-6 rounded-3xl text-center space-y-4 shadow-sm group-contact-section">
                        <span
                            class="text-xs text-indigo-805 font-black block uppercase tracking-wider contact-title">ช่องทางประสานงานรับข่าวสารการแข่งขัน</span>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 contact-content">
                            <?php if (!empty($comp['comp_group_qr'])): ?>
                                <div
                                    class="w-32 h-32 rounded-2xl overflow-hidden border border-indigo-150 shadow-md bg-white p-2 flex items-center justify-center contact-qr shrink-0">
                                    <img src="<?= base_url($comp['comp_group_qr']) ?>" alt="Group QR Code"
                                        class="w-full h-full object-contain">
                                </div>
                            <?php endif; ?>

                            <div class="text-left space-y-2 contact-text">
                                <?php if (!empty($comp['comp_group_link'])): ?>
                                    <a href="<?= esc($comp['comp_group_link']) ?>" target="_blank"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-indigo-650 hover:bg-indigo-750 text-white font-extrabold text-xs sm:text-sm transition-colors shadow-md no-print-btn">
                                        <i data-lucide="message-square" class="w-4.5 h-4.5"></i> เข้าร่วมกลุ่มไลน์ประสานงาน
                                    </a>
                                    <span class="print-only-link hidden"><?= esc($comp['comp_group_link']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="border-t border-dashed border-slate-200 my-4"></div>

                <!-- Contact and Date footer -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50 p-4 rounded-2xl border border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-500 font-bold tracking-wider">เบอร์โทรศัพท์ติดต่อ</span>
                        <span
                            class="text-slate-900 font-extrabold font-mono text-sm block mt-0.5"><?= $reg['reg_contact_phone'] ?></span>
                    </div>
                    <div class="sm:text-right">
                        <span class="text-slate-500 font-bold tracking-wider">วันที่เวลาที่ส่งสมัคร</span>
                        <span
                            class="text-slate-900 font-extrabold font-mono text-sm block mt-0.5"><?= date('d/m/Y H:i', strtotime($reg['reg_created_at'])) ?>
                            N.</span>
                    </div>
                </div>
            </div>

            <!-- Barcode Footer -->
            <div class="pt-5 border-t border-slate-200 flex flex-col items-center justify-center gap-2">
                <div class="barcode-line w-full opacity-60"></div>
                <span
                    class="text-[9px] font-mono tracking-widest text-slate-450 uppercase font-bold">SCI-CONFIRMED-VERIFIED-2026</span>
            </div>
        </div>

        <!-- Buttons -->
        <div
            class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 mt-8 no-print success-buttons max-w-sm sm:max-w-none mx-auto w-full">
            <button onclick="window.print()"
                class="btn-print w-full sm:w-auto px-6 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-extrabold rounded-2xl border border-slate-200 flex items-center justify-center gap-2 shadow-sm">
                <i data-lucide="printer" class="w-5 h-5"></i> พิมพ์ใบสมัครนี้ (PDF)
            </button>
            <a href="<?= base_url('science-week') ?>"
                class="btn-home w-full sm:w-auto px-6 py-3.5 text-white font-extrabold rounded-2xl flex items-center justify-center gap-2 shadow-lg">
                <i data-lucide="home" class="w-5 h-5"></i> กลับหน้าหลักกิจกรรม
            </a>
        </div>

    </div>
</div>

<script>
    // Confetti Celebration
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
</script>
<?= $this->endSection() ?>