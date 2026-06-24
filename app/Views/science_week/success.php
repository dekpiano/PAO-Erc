<?= $this->extend('science_week/layout/main') ?>

<?= $this->section('content') ?>
<style>
    .page-container {
        background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 40%, #f8fafc 70%, #f0f4ff 100%);
        color: #1e293b;
        flex: 1;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    /* Ticket Badge */
    .ticket-badge {
        background: radial-gradient(circle at top right, #ffffff 0%, #f8fafc 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 2px solid rgba(99, 102, 241, 0.2);
        box-shadow: 0 15px 40px rgba(99, 102, 241, 0.08);
        position: relative;
        overflow: hidden;
    }
    .ticket-badge::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 5px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c, #fda085);
        background-size: 200% auto;
        animation: rainbowSlide 4s linear infinite;
    }
    @keyframes rainbowSlide {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    /* Entrance animations */
    .success-icon { animation: successBounceIn 0.8s cubic-bezier(0.34,1.56,0.64,1) 0.2s both; }
    .success-title { animation: fadeInDown 0.7s cubic-bezier(0.16,1,0.3,1) 0.4s both; }
    .success-subtitle { animation: fadeInDown 0.7s cubic-bezier(0.16,1,0.3,1) 0.5s both; }
    .success-ticket { animation: fadeInUp 0.9s cubic-bezier(0.16,1,0.3,1) 0.5s both; }
    .success-buttons { animation: fadeInUp 0.8s cubic-bezier(0.16,1,0.3,1) 0.8s both; }
    @keyframes successBounceIn {
        from { opacity: 0; transform: scale(0.3) rotate(-15deg); }
        to { opacity: 1; transform: scale(1) rotate(0deg); }
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Confetti particles */
    .confetti-container {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
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
        0% { transform: translateY(0) rotate(0deg); opacity: 1; }
        100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
    }

    /* Print styles */
    @media print {
        @page {
            size: A4 portrait;
            margin: 0;
        }
        html, body {
            background: #ffffff !important;
            background-image: none !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            overflow: hidden !important;
        }
        /* Hide everything that shouldn't print */
        .sci-navbar, footer, .no-print, #particles-canvas, .confetti-container, .success-icon, .space-bg-decorations {
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
        .printable-area {
            position: absolute !important;
            left: 50% !important;
            top: 40px !important;
            transform: translateX(-50%) !important;
            width: 170mm !important;
            background: #ffffff !important;
            background-image: none !important;
            color: #000000 !important;
            border: 2px solid #000000 !important;
            box-shadow: none !important;
            padding: 15px !important;
            border-radius: 12px !important;
            page-break-inside: avoid !important;
        }
        .barcode-line {
            background: linear-gradient(90deg, 
                #000000 0px, #000000 2px, transparent 2px, transparent 5px,
                #000000 5px, #000000 9px, transparent 9px, transparent 11px,
                #000000 11px, #000000 12px, transparent 12px, transparent 15px,
                #000000 15px, #000000 19px, transparent 19px, transparent 23px,
                #000000 23px, #000000 24px, transparent 24px
            ) !important;
            background-size: 26px 100% !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            opacity: 1 !important;
        }
    }

    .barcode-line {
        height: 40px;
        background: linear-gradient(90deg, 
            #0f172a 0px, #0f172a 2px, transparent 2px, transparent 5px,
            #0f172a 5px, #0f172a 9px, transparent 9px, transparent 11px,
            #0f172a 11px, #0f172a 12px, transparent 12px, transparent 15px,
            #0f172a 15px, #0f172a 19px, transparent 19px, transparent 23px,
            #0f172a 23px, #0f172a 24px, transparent 24px
        );
        background-size: 26px 100%;
    }

    /* Button hover effects */
    .btn-print {
        transition: all 0.3s ease;
    }
    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .btn-home {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.3s ease;
    }
    .btn-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #10b981, #059669);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

<!-- Confetti Celebration -->
<div class="confetti-container" id="confetti-container"></div>

<div class="page-container pt-8 pb-20 relative">
    <div class="max-w-xl mx-auto px-4 sm:px-6 relative z-10">
        
        <!-- Top Status -->
        <div class="text-center py-6 space-y-3 no-print">
            <div class="success-icon w-20 h-20 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-200 border-2 border-emerald-300 text-emerald-600 flex items-center justify-center mx-auto shadow-lg shadow-emerald-100">
                <i data-lucide="check-circle-2" class="w-12 h-12"></i>
            </div>
            <h1 class="success-title text-2xl sm:text-3xl font-black gradient-text">ลงทะเบียนสำเร็จเรียบร้อย!</h1>
            <p class="success-subtitle text-slate-500 text-xs sm:text-sm font-semibold">ระบบทำการออกบัตรสมัครเพื่อตรวจสอบหลักฐานการเข้าแข่งเรียบร้อย</p>
        </div>

        <!-- Ticket Card -->
        <div class="ticket-badge printable-area rounded-3xl p-6 sm:p-8 space-y-6 success-ticket">
            
            <!-- Code Header -->
            <div class="flex justify-between items-start border-b border-slate-200 pb-4 gap-4 pt-2">
                <div class="flex items-center gap-3">
                    <img src="<?= base_url('uploads/science_week/logo/S__49446940.jpg') ?>" alt="STEAM Logo" class="w-12 h-12 rounded-full border-2 border-indigo-200 object-cover shadow-md shrink-0">
                    <div>
                        <h2 class="text-xs font-black uppercase text-indigo-600 tracking-wider">STEAM SCIENCE WEEK</h2>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium">ตั๋วสมัครลงทะเบียนอย่างเป็นทางการ</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[9px] text-slate-400 font-mono block uppercase tracking-wider">CODE ID</span>
                    <h3 class="text-lg font-black text-indigo-600 font-mono tracking-wider leading-none"><?= $reg['reg_code'] ?></h3>
                </div>
            </div>

            <!-- Details -->
            <div class="space-y-4">
                <div>
                    <span class="text-xs text-slate-500 block uppercase font-bold tracking-wider">การแข่งขัน</span>
                    <p class="text-slate-800 font-black text-base mt-0.5"><?= $reg['reg_competition_type'] ?></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-slate-500 block uppercase font-bold tracking-wider">โรงเรียน/สถาบัน</span>
                        <p class="text-slate-800 font-bold text-sm mt-0.5"><?= $reg['reg_school_name'] ?></p>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 block uppercase font-bold tracking-wider">ชื่อทีม</span>
                        <p class="text-slate-800 font-bold text-sm mt-0.5"><?= $reg['reg_team_name'] ?: 'ทั่วไป (ไม่มีชื่อทีม)' ?></p>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-200 my-4"></div>

                <!-- Members & Advisors -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-slate-500 block uppercase font-bold tracking-wider mb-2">ผู้เข้าแข่งขัน</span>
                        <ul class="space-y-1.5">
                            <?php $members = json_decode($reg['reg_members'], true) ?: []; ?>
                            <?php foreach ($members as $index => $m): ?>
                                <li class="text-slate-700 text-xs flex items-center gap-2 font-semibold">
                                    <span class="w-2 h-2 rounded-full bg-gradient-to-r from-cyan-400 to-blue-500 shadow-sm"></span> <?= htmlspecialchars($m) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 block uppercase font-bold tracking-wider mb-2">อาจารย์ที่ปรึกษา</span>
                        <ul class="space-y-1.5">
                            <?php $advisors = json_decode($reg['reg_advisors'], true) ?: []; ?>
                            <?php foreach ($advisors as $index => $a): ?>
                                <li class="text-slate-700 text-xs flex items-center gap-2 font-semibold">
                                    <span class="w-2 h-2 rounded-full bg-gradient-to-r from-indigo-400 to-purple-500 shadow-sm"></span> <?= htmlspecialchars($a) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <?php
                $customAnswers = [];
                if (!empty($reg['reg_custom_fields'])) {
                    $customAnswers = json_decode($reg['reg_custom_fields'], true) ?: [];
                }
                if (!empty($customAnswers)):
                ?>
                    <div class="border-t border-dashed border-slate-200 my-4"></div>
                    <div>
                        <span class="text-xs text-slate-500 block uppercase font-bold tracking-wider mb-2">ข้อมูลเพิ่มเติมที่ส่งสมัคร</span>
                        <div class="space-y-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <?php foreach ($customAnswers as $q => $a): ?>
                                <div class="text-xs flex flex-col gap-0.5">
                                    <span class="text-slate-500 font-bold"><?= htmlspecialchars($q) ?>:</span>
                                    <?php if (empty($a)): ?>
                                        <span class="text-slate-400 italic">ไม่ได้ระบุ</span>
                                    <?php elseif (strpos($a, 'uploads/science_week/') === 0): ?>
                                        <a href="<?= base_url($a) ?>" target="_blank" class="text-indigo-600 hover:underline font-bold flex items-center gap-1">
                                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i> ดาวน์โหลดไฟล์ที่แนบ
                                        </a>
                                    <?php elseif (filter_var($a, FILTER_VALIDATE_URL)): ?>
                                        <a href="<?= htmlspecialchars($a) ?>" target="_blank" class="text-indigo-600 hover:underline font-bold flex items-center gap-1">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i> <?= htmlspecialchars($a) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-slate-800 font-semibold"><?= htmlspecialchars($a) ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="border-t border-dashed border-slate-200 my-4"></div>

                <div class="flex justify-between items-center text-xs">
                    <div>
                        <span class="text-slate-500 block uppercase font-bold tracking-wider">เบอร์โทรติดต่อ</span>
                        <span class="text-slate-800 font-bold font-mono"><?= $reg['reg_contact_phone'] ?></span>
                    </div>
                    <div class="text-right">
                        <span class="text-slate-500 block uppercase font-bold tracking-wider">วันที่ลงทะเบียน</span>
                        <span class="text-slate-800 font-bold font-mono"><?= date('d/m/Y H:i', strtotime($reg['reg_created_at'])) ?> น.</span>
                    </div>
                </div>
            </div>

            <!-- Barcode Footer -->
            <div class="pt-4 border-t border-slate-200 flex flex-col items-center justify-center gap-2">
                <div class="barcode-line w-full opacity-20"></div>
                <span class="text-[9px] font-mono tracking-widest text-slate-400 uppercase">SCI-CONFIRMED-VERIFIED-2026</span>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 mt-8 no-print success-buttons max-w-xs sm:max-w-none mx-auto w-full">
            <button onclick="window.print()" class="btn-print w-full sm:w-auto px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 font-bold rounded-2xl border border-slate-200 flex items-center justify-center gap-2 shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i> พิมพ์ใบสมัครนี้
            </button>
            <a href="<?= base_url('science-week') ?>" class="btn-home w-full sm:w-auto px-6 py-3 text-white font-bold rounded-2xl flex items-center justify-center gap-2 shadow-md">
                <i data-lucide="home" class="w-4 h-4"></i> กลับหน้าหลัก
            </a>
        </div>

    </div>
</div>

<script>
    // Confetti Celebration
    const confettiContainer = document.getElementById('confetti-container');
    const confettiColors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#fda085', '#34d399', '#fbbf24', '#60a5fa'];
    
    function createConfetti() {
        for (let i = 0; i < 60; i++) {
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
            }, i * 40);
        }
    }
    
    // Fire confetti on load
    setTimeout(createConfetti, 600);
</script>
<?= $this->endSection() ?>
