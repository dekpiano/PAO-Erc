<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'IT Support Portal | อบจ.นครสวรรค์' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <script>
        // Check local storage or system preference to set initial theme blocking flash
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        tailwind.config = {
            darkMode: 'class', // class-based dark mode
            theme: {
                extend: {
                    colors: {
                        tech: {
                            bg: '#eff6ff',
                            card: 'rgba(255, 255, 255, 0.85)',
                            border: 'rgba(226, 232, 240, 0.8)',
                            primary: '#2563eb', // blue-600
                            secondary: '#1d4ed8', // blue-700
                            accent: '#10b981', // emerald-500
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --bg-primary: #eff6ff;
            --bg-card: rgba(255, 255, 255, 0.85);
            --bg-sidebar: #ffffff;
            --bg-header: rgba(255, 255, 255, 0.8);
            --text-primary: #334155;
            --text-title: #1e293b;
            --text-muted: #64748b;
            --border-primary: rgba(226, 232, 240, 0.8);
            --border-sidebar: #f1f5f9;
            --shadow-card: 0 8px 32px 0 rgba(31, 38, 135, 0.04);
            --glow-color: rgba(37, 99, 235, 0.15);
            --active-menu-bg: #eff6ff;
            --active-menu-text: #2563eb;
            --hover-menu-bg: #f8fafc;
            --scrollbar-thumb: rgba(0, 0, 0, 0.1);
            --scrollbar-thumb-hover: rgba(37, 99, 235, 0.2);
            --sweet-alert-bg: #ffffff;
            --sweet-alert-text: #1e293b;
        }

        .dark {
            --bg-primary: #090d16;
            --bg-card: rgba(17, 25, 40, 0.75);
            --bg-sidebar: #020617;
            --bg-header: rgba(2, 6, 23, 0.6);
            --text-primary: #cbd5e1;
            --text-title: #f8fafc;
            --text-muted: #94a3b8;
            --border-primary: rgba(255, 255, 255, 0.08);
            --border-sidebar: #0f172a;
            --shadow-card: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            --glow-color: rgba(96, 165, 250, 0.3);
            --active-menu-bg: rgba(37, 99, 235, 0.1);
            --active-menu-text: #60a5fa;
            --hover-menu-bg: rgba(15, 23, 42, 0.6);
            --scrollbar-thumb: rgba(255, 255, 255, 0.1);
            --scrollbar-thumb-hover: rgba(96, 165, 250, 0.3);
            --sweet-alert-bg: #090d16;
            --sweet-alert-text: #cbd5e1;
        }

        html, body { 
            font-family: 'Inter', 'Sarabun', sans-serif; 
            background-color: var(--bg-primary); 
            color: var(--text-primary);
            overflow-x: hidden;
            max-width: 100vw;
            transition: background-color 0.3s, color 0.3s;
        }
        .glass-card { 
            background: var(--bg-card); 
            backdrop-filter: blur(16px); 
            border: 1px solid var(--border-primary); 
            box-shadow: var(--shadow-card); 
            transition: background-color 0.3s, border-color 0.3s, box-shadow 0.3s;
        }
        .tech-glow {
            text-shadow: 0 0 15px var(--glow-color);
        }
        .sidebar-bg {
            background-color: var(--bg-sidebar);
            border-color: var(--border-sidebar);
            transition: background-color 0.3s, border-color 0.3s;
        }
        .header-bg {
            background-color: var(--bg-header);
            border-color: var(--border-sidebar);
            transition: background-color 0.3s, border-color 0.3s;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.02);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--scrollbar-thumb);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--scrollbar-thumb-hover);
        }
        * { box-sizing: border-box; }

        /* Dynamic overrides for deep elements under dark mode selector */
        .dark h2, .dark h3, .dark h1, .dark .text-slate-800 {
            color: #f8fafc !important;
        }
        .dark p, .dark label, .dark .text-slate-500, .dark .text-slate-400 {
            color: #94a3b8 !important;
        }
        .dark input, .dark textarea, .dark select {
            background-color: rgba(15, 23, 42, 0.6) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #cbd5e1 !important;
        }
        .dark select option {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
        }
        .dark table th {
            color: #94a3b8 !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        .dark table td {
            color: #cbd5e1 !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }
        .dark table tr:hover {
            background-color: rgba(255, 255, 255, 0.02) !important;
        }
        .dark .bg-slate-50, .dark .bg-slate-100 {
            background-color: rgba(15, 23, 42, 0.6) !important;
        }
        .dark .border-slate-100, .dark .border-slate-200 {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        .dark .bg-slate-900, .dark .bg-slate-950 {
            background-color: #020617 !important;
        }
    </style>
</head>
<body class="antialiased custom-scrollbar overflow-x-hidden">
    <div class="flex h-screen overflow-hidden w-full max-w-full">
        <!-- Sidebar -->
        <?php if (session()->get('u_id')): ?>
        <aside id="sidebar-menu" class="sidebar-bg flex flex-col w-72 border-r shrink-0 fixed inset-y-0 left-0 z-[60] lg:static -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <div class="h-20 flex items-center px-6 border-b border-slate-150 gap-3 shrink-0 overflow-hidden bg-transparent">
                <div class="w-10 h-10 bg-gradient-to-tr from-blue-500 to-blue-700 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20 shrink-0">
                    <i data-lucide="cpu" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black tracking-wider uppercase text-slate-800">IT SUPPORT PORTAL</h2>
                    <p class="text-[9px] text-blue-600 dark:text-blue-400 font-bold uppercase tracking-widest mt-1">อบจ.นครสวรรค์</p>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto p-6 space-y-2 custom-scrollbar">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-4 mb-3">เมนูหลัก</div>
                
                <a href="<?= base_url('itsupport') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 <?= uri_string() == 'itsupport' || uri_string() == 'itsupport/logs' ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-100/50 dark:border-blue-800/30 shadow-md shadow-blue-500/5' : 'text-slate-550 dark:text-slate-400 hover:text-blue-600 hover:bg-slate-50 dark:hover:bg-slate-900/40' ?>">
                    <i data-lucide="history" class="w-5 h-5"></i><span>ไทม์ไลน์งานบริการ</span>
                </a>
                
                <?php if (isset($can_manage) && $can_manage): ?>
                <a href="<?= base_url('itsupport/dashboard') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 <?= uri_string() == 'itsupport/dashboard' ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-100/50 dark:border-blue-800/30 shadow-md shadow-blue-500/5' : 'text-slate-550 dark:text-slate-400 hover:text-blue-600 hover:bg-slate-50 dark:hover:bg-slate-900/40' ?>">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i><span>แดชบอร์ดวิเคราะห์สถิติ</span>
                </a>

                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-4 pt-4 mb-3">บันทึกผลงาน</div>

                <a href="<?= base_url('itsupport/create') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 <?= uri_string() == 'itsupport/create' ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-100/50 dark:border-blue-800/30 shadow-md shadow-blue-500/5' : 'text-slate-550 dark:text-slate-400 hover:text-blue-600 hover:bg-slate-50 dark:hover:bg-slate-900/40' ?>">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i><span>บันทึกงานบริการใหม่</span>
                </a>
                <?php endif; ?>

            </nav>

            <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                <?php if (session()->get('u_id')): ?>
                    <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-colors">
                        <i data-lucide="log-out" class="w-5 h-5"></i><span>ออกจากระบบ</span>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm text-blue-600 hover:bg-blue-50 transition-colors">
                        <i data-lucide="log-in" class="w-5 h-5"></i><span>เข้าสู่ระบบ</span>
                    </a>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 lg:hidden focus:outline-none" onclick="toggleSidebar()"></div>
        <?php endif; ?>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden w-full max-w-full">
            <!-- Header -->
            <header class="h-16 sm:h-20 header-bg border-b flex items-center justify-between px-3 sm:px-6 shrink-0 z-20 sticky top-0 backdrop-blur-md w-full max-w-full">
                <div class="flex items-center gap-4 min-w-0">
                    <?php if (session()->get('u_id')): ?>
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-400 hover:text-slate-650"><i data-lucide="menu" class="w-6 h-6"></i></button>
                    <?php endif; ?>
                    <h1 class="text-xs sm:text-lg font-bold flex items-center gap-1.5 sm:gap-2 truncate">
                        <span class="w-2 h-2 sm:w-2.5 sm:h-2.5 bg-blue-600 rounded-full animate-pulse shadow-glow shrink-0"></span>
                        <span class="truncate">IT Support</span>
                    </h1>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Dark/Light Mode Toggle Button -->
                    <button id="theme-toggle" onclick="toggleTheme()" class="p-2.5 rounded-2xl bg-slate-100 dark:bg-slate-800/80 hover:bg-slate-200 dark:hover:bg-slate-700/80 text-slate-500 dark:text-slate-400 transition-all flex items-center justify-center border border-slate-200/50 dark:border-slate-700/50" title="สลับโทนมืด/สว่าง">
                        <i id="theme-icon-sun" data-lucide="sun" class="w-4 h-4 hidden"></i>
                        <i id="theme-icon-moon" data-lucide="moon" class="w-4 h-4"></i>
                    </button>

                    <!-- Dropdown บุคลากร -->
                    <div class="flex items-center gap-3">
                        <?php if (session()->get('u_id')): ?>
                            <div class="hidden md:flex flex-col items-end">
                                <span class="text-sm font-extrabold leading-none"><?= session()->get('u_fullname') ?></span>
                                <span class="text-[9px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mt-1">
                                    <?php if (isset($can_manage) && $can_manage): ?>
                                        IT OFFICER
                                    <?php else: ?>
                                        STAFF
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm shadow-black/5">
                                <?php if(session()->get('u_photo')): ?>
                                    <img src="<?= base_url('uploads/personnel/' . session()->get('u_photo')) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-400">
                                        <i data-lucide="user" class="w-5 h-5"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="hidden md:flex flex-col items-end">
                                <span class="text-sm font-extrabold text-slate-400 leading-none">ผู้มาเยือน (Guest)</span>
                                <span class="text-[9px] font-bold text-amber-500 uppercase tracking-widest mt-1">READ ONLY</span>
                            </div>
                            <a href="<?= base_url('auth/login') ?>" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 border border-blue-500/20 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/10 flex items-center gap-1.5">
                                <i data-lucide="log-in" class="w-3.5 h-3.5"></i> เข้าสู่ระบบ
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Main Scroll Container -->
            <main class="flex-1 overflow-y-auto overflow-x-hidden p-3 sm:p-6 md:p-10 relative custom-scrollbar w-full max-w-full">
                <div class="max-w-7xl mx-auto w-full">
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
        function toggleSidebar() {
            document.getElementById('sidebar-menu').classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }

        // Toggle Dark/Light Mode Theme Function
        function toggleTheme() {
            const html = document.documentElement;
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');
            
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                if (sunIcon && moonIcon) {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                if (sunIcon && moonIcon) {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                }
            }

            // Fire event to allow subpages to adapt dynamically (e.g. redraw charts)
            window.dispatchEvent(new Event('themeChanged'));
        }

        // Initialize dynamic popup backgrounds
        function getSwalColors() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                bg: isDark ? '#090d16' : '#ffffff',
                text: isDark ? '#cbd5e1' : '#1e293b'
            };
        }

        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 3000,
                showConfirmButton: false,
                background: getSwalColors().bg,
                color: getSwalColors().text,
                customClass: {
                    popup: 'glass-card rounded-[2rem]'
                }
            });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: '<?= session()->getFlashdata('error') ?>',
                background: getSwalColors().bg,
                color: getSwalColors().text,
                customClass: {
                    popup: 'glass-card rounded-[2rem]'
                }
            });
        <?php endif; ?>

        document.addEventListener('DOMContentLoaded', function() {
            // Init theme toggle buttons state
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');
            if (sunIcon && moonIcon) {
                if (document.documentElement.classList.contains('dark')) {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }

            const fpConfig = {
                dateFormat: "Y-m-d H:i:00", 
                enableTime: true,
                time_24hr: true,
                allowInput: false,
                disableMobile: true,
                altInput: true, 
                altFormat: "d/m/Y H:i น.", 
                locale: "th",
                onReady: instance => applyBE(instance),
                onValueUpdate: instance => applyBE(instance),
                onOpen: instance => applyBE(instance),
                onMonthChange: instance => setTimeout(() => applyBE(instance), 1),
                onYearChange: instance => setTimeout(() => applyBE(instance), 1)
            };
            flatpickr(".datetimepicker-be", fpConfig);
        });

        function applyBE(instance) {
            if (!instance) return;
            const years = instance.calendarContainer ? instance.calendarContainer.querySelectorAll(".cur-year") : [];
            years.forEach(y => {
                let val = parseInt(y.value);
                if (val > 0 && val < 2400) y.value = val + 543;
            });
            if (instance.altInput && instance.selectedDates.length > 0) {
                const d = instance.selectedDates[0];
                const day = d.getDate().toString().padStart(2, '0');
                const month = (d.getMonth() + 1).toString().padStart(2, '0');
                const year = d.getFullYear() + 543;
                const hour = d.getHours().toString().padStart(2, '0');
                const minute = d.getMinutes().toString().padStart(2, '0');
                instance.altInput.value = `${day}/${month}/${year} ${hour}:${minute} น.`;
            }
        }
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
