<!DOCTYPE html>
<html lang="th" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'งานสัปดาห์วิทยาศาสตร์ 2026 - อบจ.นครสวรรค์' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo-pao.png') ?>">
    
    <!-- Google Fonts: Inter, K2D & Chakra Petch (Sci-Tech style) -->
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:wght@400;500;600;700;800&family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --steam-science: #a855f7;   /* Purple */
            --steam-tech: #0284c7;      /* Sky Blue / Cyan (vibrant) */
            --steam-eng: #10b981;       /* Green */
            --steam-arts: #f59e0b;      /* Yellow / Orange */
            --steam-math: #ec4899;      /* Pink / Red */
            --steam-bg: #f8fafc;
        }

        html {
            width: 100%;
            overflow-x: hidden;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
            overflow-x: hidden;
            font-family: 'K2D', 'Inter', sans-serif;
            background-color: #03000a;
            background-image: 
                /* Outer Space Nebulae - vibrant & high contrast */
                radial-gradient(circle at 15% 25%, rgba(147, 51, 234, 0.4) 0%, rgba(147, 51, 234, 0.1) 25%, transparent 60%),
                radial-gradient(circle at 85% 70%, rgba(6, 182, 212, 0.42) 0%, rgba(6, 182, 212, 0.1) 30%, transparent 65%),
                radial-gradient(circle at 50% 50%, rgba(236, 72, 153, 0.22) 0%, rgba(236, 72, 153, 0.05) 20%, transparent 50%),
                radial-gradient(circle at 75% 20%, rgba(99, 102, 241, 0.3) 0%, rgba(99, 102, 241, 0.08) 25%, transparent 55%),
                /* Tech Cyber Grid */
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120' viewBox='0 0 120 120'%3E%3Cpath d='M 120 0 L 0 0 0 120' fill='none' stroke='%236366f1' stroke-width='0.5' stroke-opacity='0.08'/%3E%3C/svg%3E"),
                /* Stellar stars and cross-flares */
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300' viewBox='0 0 300 300'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Ccircle cx='45' cy='30' r='1'/%3E%3Ccircle cx='120' cy='85' r='1.5'/%3E%3Ccircle cx='270' cy='40' r='1.2'/%3E%3Ccircle cx='95' cy='180' r='0.8'/%3E%3Ccircle cx='210' cy='135' r='1.5'/%3E%3Ccircle cx='60' cy='250' r='1'/%3E%3Ccircle cx='235' cy='270' r='1.8'/%3E%3C/g%3E%3Cg stroke='%23ffffff' stroke-width='0.5' stroke-opacity='0.5' fill='none'%3E%3Cpath d='M 80 120 L 80 130 M 75 125 L 85 125'/%3E%3Cpath d='M 220 70 L 220 80 M 215 75 L 225 75'/%3E%3Cpath d='M 140 220 L 140 230 M 135 225 L 145 225'/%3E%3C/g%3E%3C/svg%3E");
            background-attachment: fixed;
            color: #f1f5f9;
        }

        main {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6, .tech-font {
            font-family: 'Chakra Petch', sans-serif;
            font-weight: 700;
        }

        .sci-navbar {
            background: rgba(6, 9, 19, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 2px solid rgba(99, 102, 241, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease !important;
            transform: translateY(0);
            opacity: 1;
        }

        .sci-navbar.nav-hidden {
            transform: translateY(-100%) !important;
            opacity: 0 !important;
            pointer-events: none;
        }

        .sci-navbar a.sci-nav-link {
            color: #94a3b8 !important;
        }

        .sci-navbar a.sci-nav-link:hover,
        .sci-navbar a.sci-nav-link.active {
            color: #ffffff !important;
        }

        .sci-navbar h1 {
            color: #ffffff !important;
        }

        .sci-nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        
        .sci-nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--steam-tech), var(--steam-science));
            transition: width 0.3s ease;
        }

        .sci-nav-link:hover::after,
        .sci-nav-link.active::after {
            width: 100%;
        }

        .sci-glow-btn {
            background: linear-gradient(135deg, var(--steam-science) 0%, var(--steam-tech) 100%);
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.3);
            transition: all 0.3s ease;
        }

        .sci-glow-btn:hover {
            box-shadow: 0 4px 25px rgba(2, 132, 199, 0.5);
            transform: translateY(-1px);
        }

        /* Unified Space-Tech Glassmorphic Cards & Form elements */
        .glass-sci-card {
            background: rgba(15, 23, 42, 0.5) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(99, 102, 241, 0.2) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4) !important;
            color: #f1f5f9 !important;
            position: relative;
            z-index: 10;
        }

        .glass-sci-card:hover {
            background: rgba(15, 23, 42, 0.65) !important;
            border-color: rgba(6, 182, 212, 0.3) !important;
            box-shadow: 0 20px 50px -10px rgba(6, 182, 212, 0.2) !important;
        }

        .glass-sci-card h3, .glass-sci-card h4, .glass-sci-card label {
            color: #ffffff !important;
        }

        .glass-sci-card p, .glass-sci-card .text-slate-500 {
            color: #94a3b8 !important;
        }

        .glass-sci-card input, .glass-sci-card select, .glass-sci-card textarea {
            background: rgba(8, 12, 24, 0.6) !important;
            border: 1px solid rgba(99, 102, 241, 0.3) !important;
            color: #ffffff !important;
        }

        .glass-sci-card input:focus, .glass-sci-card select:focus, .glass-sci-card textarea:focus {
            border-color: #06b6d4 !important;
            box-shadow: 0 0 8px rgba(6, 182, 212, 0.25) !important;
        }

        /* Floating Space Elements Styling */
        .space-bg-decorations {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-obj {
            position: absolute;
            will-change: transform;
            filter: drop-shadow(0 10px 25px rgba(0,0,0,0.6));
            opacity: 0.85;
            transition: opacity 0.5s ease;
        }

        /* Rocket */
        .floating-rocket {
            width: clamp(90px, 10vw, 150px);
            height: auto;
            bottom: 12%;
            left: 3%;
            animation: floatRocket 12s ease-in-out infinite;
        }
        @keyframes floatRocket {
            0%, 100% { transform: translateY(0) rotate(-15deg); }
            50% { transform: translateY(-30px) rotate(-10deg); }
        }

        /* Grey Moon */
        .floating-moon {
            width: clamp(100px, 12vw, 160px);
            height: auto;
            top: 15%;
            right: 4%;
            animation: floatMoon 20s ease-in-out infinite;
        }
        @keyframes floatMoon {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-15px, 20px) rotate(8deg); }
        }

        /* Saturn Ringed Planet */
        .floating-saturn {
            width: clamp(110px, 11vw, 170px);
            height: auto;
            bottom: 20%;
            right: 3%;
            animation: floatSaturn 16s ease-in-out infinite;
        }
        @keyframes floatSaturn {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(20px, -20px) rotate(-12deg); }
        }

        /* Cyan Planet */
        .floating-cyan {
            width: clamp(60px, 7vw, 100px);
            height: auto;
            top: 25%;
            left: 8%;
            animation: floatCyan 14s ease-in-out infinite;
        }
        @keyframes floatCyan {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-10px, -15px) scale(1.06); }
        }

        /* Small Asteroid */
        .floating-asteroid {
            width: clamp(45px, 5vw, 75px);
            height: auto;
            bottom: 40%;
            left: 15%;
            animation: floatAsteroid 18s ease-in-out infinite;
        }
        @keyframes floatAsteroid {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(15px, 25px) rotate(180deg); }
        }

        /* Shooting Stars / Meteors */
        .floating-meteor {
            position: absolute;
            width: 100px;
            height: 50px;
            opacity: 0;
            transform: rotate(-35deg);
        }
        .meteor-1 {
            top: 5%;
            right: 25%;
            animation: shootMeteor 8s linear infinite;
            animation-delay: 2s;
        }
        .meteor-2 {
            top: 30%;
            right: 50%;
            animation: shootMeteor 11s linear infinite;
            animation-delay: 5s;
        }
        .meteor-3 {
            top: 15%;
            right: 5%;
            animation: shootMeteor 6s linear infinite;
            animation-delay: 0s;
        }
        @keyframes shootMeteor {
            0% { transform: translate(150px, -150px) rotate(-35deg) scale(0.5); opacity: 0; }
            10% { opacity: 1; }
            30% { transform: translate(-400px, 400px) rotate(-35deg) scale(1.3); opacity: 0; }
            100% { transform: translate(-400px, 400px) rotate(-35deg) scale(1.3); opacity: 0; }
        }

        /* Responsive hiding for cleaner view on small mobiles */
        @media (max-width: 768px) {
            .floating-rocket { left: 1%; bottom: 8%; width: 70px; }
            .floating-moon { right: 1%; top: 18%; width: 80px; }
            .floating-saturn { right: 1%; bottom: 15%; width: 85px; }
            .floating-cyan { display: none; }
            .floating-asteroid { display: none; }
        }

        .page-container {
            background: transparent !important;
        }
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen">

    <!-- Floating Space Background Objects -->
    <div class="space-bg-decorations">
        <!-- 1. Rocket (Space Shuttle) -->
        <svg viewBox="0 0 120 180" class="floating-rocket floating-obj">
            <!-- Left booster smoke trail -->
            <path d="M20 150 C20 165 30 170 30 180 C20 180 15 170 20 150 Z" fill="#e2e8f0" opacity="0.5"/>
            <!-- Right booster smoke trail -->
            <path d="M100 150 C100 165 90 170 90 180 C100 180 105 170 100 150 Z" fill="#e2e8f0" opacity="0.5"/>
            <!-- Booster left -->
            <path d="M25 100 C25 125 33 135 33 148 L21 148 C17 135 17 110 25 100 Z" fill="#64748b"/>
            <path d="M21 148 L33 148 L27 155 Z" fill="#f97316"/>
            <!-- Booster right -->
            <path d="M95 100 C95 125 87 135 87 148 L99 148 C103 135 103 110 95 100 Z" fill="#64748b"/>
            <path d="M87 148 L99 148 L93 155 Z" fill="#f97316"/>
            <!-- Main Shuttle Body -->
            <path d="M60 15 C75 50 83 80 83 135 L37 135 C37 80 45 50 60 15 Z" fill="#f8fafc"/>
            <!-- Nose cone (Yellow/Orange) -->
            <path d="M60 15 C66 32 71 45 73 58 L47 58 C49 45 54 32 60 15 Z" fill="#f59e0b"/>
            <!-- Cockpit Window -->
            <circle cx="60" cy="85" r="9" fill="#38bdf8" stroke="#1e293b" stroke-width="2"/>
            <circle cx="57" cy="82" r="3" fill="#ffffff"/>
            <!-- Wing left -->
            <path d="M37 110 L15 135 L37 135 Z" fill="#cbd5e1"/>
            <!-- Wing right -->
            <path d="M83 110 L105 135 L83 135 Z" fill="#cbd5e1"/>
            <!-- Main Engine Fire -->
            <path d="M50 135 C50 160 60 178 60 178 C60 178 70 160 70 135 Z" fill="#ef4444"/>
            <path d="M55 135 C55 152 60 162 60 162 C60 162 65 152 65 135 Z" fill="#f97316"/>
            <!-- Engine smoke -->
            <circle cx="60" cy="178" r="8" fill="#e2e8f0" opacity="0.6"/>
            <circle cx="53" cy="182" r="6" fill="#e2e8f0" opacity="0.4"/>
            <circle cx="67" cy="182" r="6" fill="#e2e8f0" opacity="0.4"/>
        </svg>

        <!-- 2. Large Moon -->
        <svg viewBox="0 0 100 100" class="floating-moon floating-obj">
            <circle cx="50" cy="50" r="45" fill="#cbd5e1"/>
            <!-- Shadow for round sphere effect -->
            <path d="M50 5 A45 45 0 0 1 50 95 A45 45 0 0 0 50 5 Z" fill="#475569" opacity="0.25"/>
            <!-- Craters -->
            <circle cx="32" cy="30" r="9" fill="#94a3b8" opacity="0.7"/>
            <circle cx="29" cy="27" r="8" fill="#64748b" opacity="0.3"/>
            
            <circle cx="66" cy="45" r="13" fill="#94a3b8" opacity="0.7"/>
            <circle cx="62" cy="41" r="11" fill="#64748b" opacity="0.3"/>
            
            <circle cx="44" cy="70" r="7" fill="#94a3b8" opacity="0.7"/>
            <circle cx="42" cy="68" r="6" fill="#64748b" opacity="0.3"/>
            
            <circle cx="70" cy="22" r="5" fill="#94a3b8" opacity="0.7"/>
        </svg>

        <!-- 3. Ringed Planet (Saturn-like Pink) -->
        <svg viewBox="0 0 120 100" class="floating-saturn floating-obj">
            <!-- Back of the ring -->
            <ellipse cx="60" cy="50" rx="55" ry="16" fill="#f59e0b" transform="rotate(-15 60 50)" opacity="0.8"/>
            <!-- Planet body -->
            <circle cx="60" cy="50" r="28" fill="#ec4899"/>
            <!-- Planet body shadow -->
            <path d="M60 22 A28 28 0 0 1 60 78 A28 28 0 0 0 60 22 Z" fill="#9d174d" opacity="0.4"/>
            <!-- Front of the ring -->
            <path d="M8 38 A55 16 0 0 0 112 62" stroke="#fbbf24" stroke-width="8" fill="none" stroke-linecap="round" transform="rotate(-15 60 50)"/>
        </svg>

        <!-- 4. Cyan Planet -->
        <svg viewBox="0 0 80 80" class="floating-cyan floating-obj">
            <circle cx="40" cy="40" r="35" fill="#06b6d4"/>
            <!-- Planet patterns -->
            <circle cx="30" cy="25" r="9" fill="#22d3ee" opacity="0.6"/>
            <circle cx="55" cy="45" r="11" fill="#22d3ee" opacity="0.6"/>
            <circle cx="28" cy="52" r="6" fill="#22d3ee" opacity="0.6"/>
            <!-- Shadow -->
            <path d="M40 5 A35 35 0 0 1 40 75 A35 35 0 0 0 40 5 Z" fill="#0891b2" opacity="0.45"/>
        </svg>

        <!-- 5. Asteroid -->
        <svg viewBox="0 0 60 60" class="floating-asteroid floating-obj">
            <path d="M30 6 C42 4 54 13 52 28 C54 42 42 53 30 50 C18 53 6 42 8 28 C6 13 18 8 30 6 Z" fill="#d97706"/>
            <!-- Craters -->
            <circle cx="22" cy="20" r="4" fill="#92400e"/>
            <circle cx="38" cy="34" r="5" fill="#92400e"/>
            <circle cx="24" cy="38" r="3" fill="#92400e"/>
        </svg>

        <!-- 6. Shooting Stars / Meteors -->
        <svg viewBox="0 0 80 40" class="floating-meteor meteor-1">
            <path d="M0 20 L60 0 L55 20 L60 40 Z" fill="url(#meteorGrad1)" />
            <circle cx="60" cy="20" r="5" fill="#f97316" />
            <circle cx="60" cy="20" r="2.5" fill="#facc15" />
            <defs>
                <linearGradient id="meteorGrad1" x1="0" y1="20" x2="60" y2="20" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#ef4444" stop-opacity="0" />
                    <stop offset="70%" stop-color="#f97316" stop-opacity="0.8" />
                    <stop offset="100%" stop-color="#facc15" />
                </linearGradient>
            </defs>
        </svg>
        <svg viewBox="0 0 80 40" class="floating-meteor meteor-2">
            <path d="M0 20 L60 0 L55 20 L60 40 Z" fill="url(#meteorGrad2)" />
            <circle cx="60" cy="20" r="5" fill="#f97316" />
            <circle cx="60" cy="20" r="2.5" fill="#facc15" />
            <defs>
                <linearGradient id="meteorGrad2" x1="0" y1="20" x2="60" y2="20" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#ef4444" stop-opacity="0" />
                    <stop offset="70%" stop-color="#f97316" stop-opacity="0.8" />
                    <stop offset="100%" stop-color="#facc15" />
                </linearGradient>
            </defs>
        </svg>
        <svg viewBox="0 0 80 40" class="floating-meteor meteor-3">
            <path d="M0 20 L60 0 L55 20 L60 40 Z" fill="url(#meteorGrad3)" />
            <circle cx="60" cy="20" r="5" fill="#f97316" />
            <circle cx="60" cy="20" r="2.5" fill="#facc15" />
            <defs>
                <linearGradient id="meteorGrad3" x1="0" y1="20" x2="60" y2="20" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#ef4444" stop-opacity="0" />
                    <stop offset="70%" stop-color="#f97316" stop-opacity="0.8" />
                    <stop offset="100%" stop-color="#facc15" />
                </linearGradient>
            </defs>
        </svg>
    </div>

    <!-- Science Week Specific Navbar -->
    <nav class="sci-navbar fixed top-0 left-0 right-0 z-50 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Brand Logo -->
                <a href="<?= base_url('science-week') ?>" class="flex items-center gap-2 sm:gap-3 group">
                    <img src="<?= base_url('uploads/science_week/logo/S__49446940.jpg') ?>" alt="STEAM Logo" class="w-9 h-9 sm:w-12 sm:h-12 rounded-full border-2 border-indigo-500/40 object-cover shadow-md group-hover:scale-105 transition-all duration-300">
                    <div>
                        <h1 class="text-xs sm:text-base font-extrabold leading-none tracking-tight">STEAM SCIENCE WEEK</h1>
                        <p class="text-[8px] sm:text-[9px] text-indigo-400 font-bold uppercase tracking-wider mt-0.5 sm:mt-1">สนุกคิด ติดปีกจินตนาการ</p>
                    </div>
                </a>
                <!-- Desktop Menu -->
                <?php
                    $db = \Config\Database::connect();
                    $settingsModel = new \App\Models\SettingsModel();
                    $activeYear = (int)($settingsModel->getVal('science_week_active_year') ?: 2569);
                    $menuAnnouncements = $db->table('Tb_ScienceWeek_Announcements')
                                            ->where('ann_year', $activeYear)
                                            ->orderBy('ann_created_at', 'DESC')
                                            ->get()
                                            ->getResultArray();
                ?>
                <div class="hidden md:flex items-center gap-8">
                    <div class="relative group py-2">
                        <a href="javascript:void(0)" class="sci-nav-link text-xs font-black uppercase tracking-wider text-slate-600 hover:text-slate-900 <?= uri_string() == 'science-week' ? 'active text-indigo-650' : '' ?> flex items-center gap-1 cursor-pointer">
                            เอกสาร <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </a>
                        <!-- Dropdown -->
                        <div class="absolute top-full left-0 mt-0 pt-2 w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-xl shadow-slate-200/50 rounded-2xl p-2 transform origin-top-left scale-95 group-hover:scale-100 transition-transform duration-300">
                                <?php if(empty($menuAnnouncements)): ?>
                                    <div class="px-4 py-3 text-xs text-slate-500 font-medium">ยังไม่มีเอกสาร</div>
                                <?php else: ?>
                                    <?php foreach($menuAnnouncements as $ann): ?>
                                        <a href="<?= base_url($ann['ann_file']) ?>" target="_blank" class="block px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:text-indigo-600 hover:bg-indigo-50/50 transition-colors truncate">
                                            <?= esc($ann['ann_title']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <a href="<?= base_url('science-week/register') ?>" class="sci-nav-link text-xs font-black uppercase tracking-wider text-slate-600 hover:text-slate-900 <?= uri_string() == 'science-week/register' ? 'active text-indigo-650' : '' ?>">สมัครแข่งขัน</a>
                    <a href="<?= base_url('science-week/check-status') ?>" class="sci-nav-link text-xs font-black uppercase tracking-wider text-slate-600 hover:text-slate-900 <?= strpos(uri_string(), 'science-week/check-status') === 0 ? 'active text-indigo-650' : '' ?>">ตรวจสอบสถานะการสมัคร</a>
                    <a href="<?= base_url('science-week/approved-list') ?>" class="sci-nav-link text-xs font-black uppercase tracking-wider text-slate-600 hover:text-slate-900 <?= strpos(uri_string(), 'science-week/approved-list') === 0 ? 'active text-indigo-650' : '' ?>">รายชื่อผู้มีสิทธิ์แข่ง</a>
                    <a href="<?= base_url('science-week/results') ?>" class="sci-nav-link text-xs font-black uppercase tracking-wider text-slate-600 hover:text-slate-900 <?= strpos(uri_string(), 'science-week/results') === 0 ? 'active text-indigo-650' : '' ?>">ประกาศผลการแข่งขัน</a>
                    <a href="<?= base_url('science-week/evaluation') ?>" class="sci-nav-link text-xs font-black uppercase tracking-wider text-slate-600 hover:text-slate-900 <?= strpos(uri_string(), 'science-week/evaluation') === 0 ? 'active text-indigo-650' : '' ?>">ทำแบบประเมิน</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="p-2 text-slate-300 hover:text-white transition-colors">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Menu Drawer -->
    <div id="mobile-menu" class="hidden fixed inset-0 z-[60] bg-[#060913]/95 backdrop-blur-lg pt-24 px-6 overflow-y-auto">
        <div class="flex flex-col gap-6 animate-[fadeIn_0.3s_ease-out]">
            <div class="border-b border-slate-800/80 pb-4">
                <a href="javascript:void(0)" onclick="document.getElementById('mobile-docs-menu').classList.toggle('hidden')" class="w-full text-xl font-bold text-slate-200 flex items-center justify-between hover:text-white transition-colors cursor-pointer">
                    เอกสาร <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400"></i>
                </a>
                <div id="mobile-docs-menu" class="hidden mt-4 pl-4 space-y-3">
                    <?php if(empty($menuAnnouncements)): ?>
                        <div class="text-sm text-slate-500 font-medium">ยังไม่มีเอกสาร</div>
                    <?php else: ?>
                        <?php foreach($menuAnnouncements as $ann): ?>
                            <a href="<?= base_url($ann['ann_file']) ?>" target="_blank" class="block text-sm font-bold text-slate-400 hover:text-indigo-400 transition-colors truncate">
                                <?= esc($ann['ann_title']) ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <a href="<?= base_url('science-week/register') ?>" class="text-xl font-bold text-slate-200 border-b border-slate-800/80 pb-4 flex items-center justify-between hover:text-white transition-colors">
                สมัครแข่งขัน <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400"></i>
            </a>
            <a href="<?= base_url('science-week/check-status') ?>" class="text-xl font-bold text-slate-200 border-b border-slate-800/80 pb-4 flex items-center justify-between hover:text-white transition-colors">
                ตรวจสอบสถานะการสมัคร <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400"></i>
            </a>
            <a href="<?= base_url('science-week/approved-list') ?>" class="text-xl font-bold text-slate-200 border-b border-slate-800/80 pb-4 flex items-center justify-between hover:text-white transition-colors">
                รายชื่อผู้มีสิทธิ์แข่ง <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400"></i>
            </a>
            <a href="<?= base_url('science-week/results') ?>" class="text-xl font-bold text-slate-200 border-b border-slate-800/80 pb-4 flex items-center justify-between hover:text-white transition-colors">
                ประกาศผลการแข่งขัน <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400"></i>
            </a>
            <a href="<?= base_url('science-week/evaluation') ?>" class="text-xl font-bold text-slate-200 border-b border-slate-800/80 pb-4 flex items-center justify-between hover:text-white transition-colors">
                ทำแบบประเมิน <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400"></i>
            </a>
        </div>
    </div>
    <!-- Main Content Area -->
    <main class="pt-20 flex-1">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border-t border-indigo-900/50 text-slate-400 py-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(99,102,241,0.05),transparent)] pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs relative z-10">
            <div class="space-y-1">
                <div>
                    &copy; <?= date('Y') ?> SCIENCE WEEK SYSTEM • โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ &amp; กองการศึกษา อบจ.นครสวรรค์
                </div>
                <div class="text-[10px] text-slate-500 flex items-center gap-1">
                    พัฒนาโดย <a href="https://erc.nsnpao.go.th/itsupport/portfolio" target="_blank" class="text-indigo-400 hover:text-indigo-300 font-extrabold transition-colors inline-flex items-center gap-0.5"><i data-lucide="music" class="w-3 h-3"></i> Dekpiano</a>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <button onclick="openStaffCertModal()" class="px-4 py-2 rounded-full text-[11px] font-bold text-slate-350 bg-slate-800 hover:bg-slate-700 hover:text-white border border-slate-750 flex items-center gap-1 transition-all">
                    <i data-lucide="award" class="w-3.5 h-3.5 text-emerald-450"></i> เกียรติบัตรนักเรียนช่วยงาน (Staff)
                </button>
                <a href="<?= base_url('science-week/staff') ?>" class="px-4 py-2 rounded-full text-[11px] font-bold text-white sci-glow-btn flex items-center gap-1 transition-all">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> ระบบเจ้าหน้าที่
                </a>
                <div class="flex items-center gap-1.5 font-mono text-[10px] text-indigo-400/60">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    <span>STEAM SCIENCE WEEK PORTAL</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
        AOS.init({
            duration: 800,
            once: true
        });

        // Mobile Navigation Toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuBtn && mobileMenu) {
            const menuBtnIcon = menuBtn.querySelector('i');
            menuBtn.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
                if (menuBtnIcon) {
                    if (isHidden) {
                        menuBtnIcon.setAttribute('data-lucide', 'menu');
                    } else {
                        menuBtnIcon.setAttribute('data-lucide', 'x');
                    }
                    lucide.createIcons();
                }
            });
        }

        // Setup dynamic popup backgrounds for sweetalert matching bright theme
        function getSwalColors() {
            return {
                bg: '#ffffff',
                text: '#1e293b'
            };
        }

        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '<?= esc(session()->getFlashdata('success')) ?>',
                confirmButtonColor: '#4f46e5'
            });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'ขออภัย',
                text: '<?= esc(session()->getFlashdata('error')) ?>',
                confirmButtonColor: '#4f46e5'
            });
        <?php endif; ?>

        // Navbar auto-hide/show on scroll (for Homepage with Hero only)
        const navbar = document.querySelector('.sci-navbar');
        const hasHero = document.getElementById('hero');

        if (navbar) {
            if (hasHero) {
                // Initialize hidden state
                navbar.classList.add('nav-hidden');
                
                // Remove top padding on main tag so hero starts at top: 0
                const mainElement = document.querySelector('main');
                if (mainElement) {
                    mainElement.classList.remove('pt-20');
                }
                
                window.addEventListener('scroll', () => {
                    const scrollTop = window.scrollY || document.documentElement.scrollTop;
                    if (scrollTop > 80) {
                        navbar.classList.remove('nav-hidden');
                    } else {
                        navbar.classList.add('nav-hidden');
                    }
                }, { passive: true });
            } else {
                // On sub-pages, keep visible
                navbar.classList.remove('nav-hidden');
            }
        }

        function openStaffCertModal() {
            document.getElementById('staffCertModal').classList.remove('hidden');
            document.getElementById('staff-search-input').value = '';
            document.getElementById('staff-search-results').innerHTML = '';
            setTimeout(() => {
                document.getElementById('staff-search-input').focus();
            }, 100);
        }

        function closeStaffCertModal() {
            document.getElementById('staffCertModal').classList.add('hidden');
        }

        function performStaffSearch() {
            const input = document.getElementById('staff-search-input').value.trim();
            const resultsContainer = document.getElementById('staff-search-results');
            
            if (!input) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกคำค้นหา',
                    text: 'กรุณากรอกชื่อหรือนามสกุลของนักเรียนช่วยงาน',
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            resultsContainer.innerHTML = '<div class="text-center py-4 text-xs text-slate-400">กำลังค้นหาข้อมูล...</div>';

            fetch(`<?= base_url('science-week/certificate/search-staff') ?>?name=${encodeURIComponent(input)}`)
                .then(res => res.json())
                .then(res => {
                    resultsContainer.innerHTML = '';
                    if (res.status === 'success') {
                        res.data.forEach(item => {
                            const html = `
                                <div class="p-3.5 bg-slate-900/60 border border-slate-800/80 rounded-xl flex justify-between items-center gap-3">
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-200">${item.prefix}${item.firstname} ${item.lastname} (ชั้น ${item.class})</div>
                                        <div class="text-[10px] text-slate-400 truncate mt-0.5">${item.comp}</div>
                                    </div>
                                    <a href="${item.download_url}" target="_blank" class="shrink-0 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-lg transition-colors flex items-center gap-1 shadow-sm">
                                        <i data-lucide="download" class="w-3 h-3"></i> โหลดเกียรติบัตร
                                    </a>
                                </div>
                            `;
                            resultsContainer.insertAdjacentHTML('beforeend', html);
                        });
                        lucide.createIcons();
                    } else {
                        resultsContainer.innerHTML = `
                            <div class="text-center py-6 text-xs text-rose-405 bg-rose-950/10 border border-rose-950/20 rounded-xl">
                                ไม่พบรายชื่อนักเรียนช่วยงานตามคำค้นหานี้ กรุณาตรวจสอบการสะกดชื่อ-นามสกุล หรือติดต่ออาจารย์ผู้รับผิดชอบ
                            </div>
                        `;
                    }
                })
                .catch(() => {
                    resultsContainer.innerHTML = '<div class="text-center py-4 text-xs text-rose-500">เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์</div>';
                });
        }

        // Add Enter key listener for search input
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('staff-search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        performStaffSearch();
                    }
                });
            }
        });
    </script>

    <!-- Student Staff Search Modal -->
    <div id="staffCertModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeStaffCertModal()"></div>
        <div class="glass-sci-card rounded-2xl p-6 sm:p-8 w-full max-w-lg z-10 relative mx-4">
            <h3 class="text-lg font-black text-indigo-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i data-lucide="award" class="w-6 h-6 text-emerald-450"></i> <span>ค้นหาเกียรติบัตรนักเรียนช่วยงาน</span>
            </h3>
            <p class="text-xs text-slate-450 mb-4 leading-relaxed">กรอกชื่อหรือนามสกุลของนักเรียนช่วยงาน (ไม่ต้องระบุคำนำหน้าชื่อ) เพื่อค้นหาและดาวน์โหลดเกียรติบัตรเข้าร่วม</p>
            
            <div class="space-y-4">
                <div>
                    <input type="text" id="staff-search-input" placeholder="กรอกชื่อจริง หรือนามสกุล..." class="w-full px-4 py-3 rounded-xl outline-none text-sm transition-all">
                </div>
                <button onclick="performStaffSearch()" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-all flex items-center justify-center gap-1.5 shadow-md shadow-indigo-950/20">
                    <i data-lucide="search" class="w-4 h-4"></i> ค้นหารายชื่อ
                </button>
                
                <!-- Search Results Container -->
                <div id="staff-search-results" class="max-h-[250px] overflow-y-auto space-y-3 pt-2 custom-scrollbar">
                    <!-- Results injected here by JS -->
                </div>
            </div>
            
            <div class="pt-4 flex justify-end">
                <button onclick="closeStaffCertModal()" class="px-5 py-2 bg-slate-800 hover:bg-slate-750 text-slate-350 font-bold text-xs rounded-xl transition-colors">ปิด</button>
            </div>
        </div>
    </div>
</body>
</html>
