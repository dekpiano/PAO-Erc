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
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tech: {
                            bg: '#090d16',
                            card: 'rgba(17, 25, 40, 0.75)',
                            border: 'rgba(255, 255, 255, 0.08)',
                            primary: '#06b6d4', // cyan-500
                            secondary: '#6366f1', // indigo-500
                            accent: '#10b981', // emerald-500
                        }
                    }
                }
            }
        }
    </script>
    <style>
        html, body { 
            font-family: 'Inter', 'Sarabun', sans-serif; 
            background-color: #090d16; 
            color: #e2e8f0;
            overflow-x: hidden;
            max-width: 100vw;
        }
        .glass-card { 
            background: rgba(17, 25, 40, 0.75); 
            backdrop-filter: blur(16px); 
            border: 1px solid rgba(255, 255, 255, 0.08); 
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37); 
        }
        .tech-glow {
            text-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(6, 182, 212, 0.3);
        }
        * { box-sizing: border-box; }
    </style>
</head>
<body class="antialiased custom-scrollbar overflow-x-hidden">
    <div class="flex h-screen overflow-hidden w-full max-w-full">
        <!-- Sidebar -->
        <?php if (session()->get('u_id')): ?>
        <aside id="sidebar-menu" class="flex flex-col w-72 bg-slate-950 border-r border-slate-900 shrink-0 fixed inset-y-0 left-0 z-[60] lg:static -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <div class="h-20 flex items-center px-6 border-b border-slate-900 gap-3 shrink-0 overflow-hidden bg-slate-950">
                <div class="w-10 h-10 bg-gradient-to-tr from-cyan-500 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-cyan-500/20 shrink-0">
                    <i data-lucide="cpu" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-white leading-none tracking-wider uppercase">IT SUPPORT PORTAL</h2>
                    <p class="text-[9px] text-cyan-400 font-bold uppercase tracking-widest mt-1">อบจ.นครสวรรค์</p>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto p-6 space-y-2 custom-scrollbar">
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4 mb-3">เมนูหลัก</div>
                
                <a href="<?= base_url('itsupport') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 <?= uri_string() == 'itsupport' || uri_string() == 'itsupport/logs' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 shadow-lg shadow-cyan-500/5' : 'text-slate-400 hover:text-cyan-400 hover:bg-slate-900/50' ?>">
                    <i data-lucide="history" class="w-5 h-5"></i><span>ไทม์ไลน์งานบริการ</span>
                </a>
                
                <?php if (isset($can_manage) && $can_manage): ?>
                <a href="<?= base_url('itsupport/dashboard') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 <?= uri_string() == 'itsupport/dashboard' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 shadow-lg shadow-cyan-500/5' : 'text-slate-400 hover:text-cyan-400 hover:bg-slate-900/50' ?>">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i><span>แดชบอร์ดวิเคราะห์สถิติ</span>
                </a>

                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4 pt-4 mb-3">บันทึกผลงาน</div>

                <a href="<?= base_url('itsupport/create') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 <?= uri_string() == 'itsupport/create' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 shadow-lg shadow-cyan-500/5' : 'text-slate-400 hover:text-cyan-400 hover:bg-slate-900/50' ?>">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i><span>บันทึกงานบริการใหม่</span>
                </a>
                <?php endif; ?>

            </nav>

            <div class="p-6 border-t border-slate-900">
                <?php if (session()->get('u_id')): ?>
                    <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm text-rose-500 hover:bg-rose-950/20 transition-colors">
                        <i data-lucide="log-out" class="w-5 h-5"></i><span>ออกจากระบบ</span>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm text-cyan-400 hover:bg-cyan-950/20 transition-colors">
                        <i data-lucide="log-in" class="w-5 h-5"></i><span>เข้าสู่ระบบ</span>
                    </a>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="hidden fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 lg:hidden focus:outline-none" onclick="toggleSidebar()"></div>
        <?php endif; ?>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden w-full max-w-full">
            <!-- Header -->
            <header class="h-16 sm:h-20 bg-slate-950/40 border-b border-slate-900/50 flex items-center justify-between px-3 sm:px-6 shrink-0 z-20 sticky top-0 backdrop-blur-md w-full max-w-full">
                <div class="flex items-center gap-4 min-w-0">
                    <?php if (session()->get('u_id')): ?>
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-400 hover:text-white"><i data-lucide="menu" class="w-6 h-6"></i></button>
                    <?php endif; ?>
                    <h1 class="text-xs sm:text-lg font-bold text-white flex items-center gap-1.5 sm:gap-2 truncate">
                        <span class="w-2 h-2 sm:w-2.5 sm:h-2.5 bg-cyan-500 rounded-full animate-pulse shadow-glow shrink-0"></span>
                        <span class="truncate">IT Support</span>
                    </h1>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Dropdown บุคลากร -->
                    <div class="flex items-center gap-3">
                        <?php if (session()->get('u_id')): ?>
                            <div class="hidden md:flex flex-col items-end">
                                <span class="text-sm font-extrabold text-white leading-none"><?= session()->get('u_fullname') ?></span>
                                <span class="text-[9px] font-bold text-cyan-400 uppercase tracking-widest mt-1">
                                    <?php if (isset($can_manage) && $can_manage): ?>
                                        IT OFFICER
                                    <?php else: ?>
                                        STAFF
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="w-10 h-10 bg-slate-800 rounded-full border border-slate-700 overflow-hidden shadow-sm shadow-black">
                                <?php if(session()->get('u_photo')): ?>
                                    <img src="<?= base_url('uploads/personnel/' . session()->get('u_photo')) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-400">
                                        <i data-lucide="user" class="w-5 h-5"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="hidden md:flex flex-col items-end">
                                <span class="text-sm font-extrabold text-slate-400 leading-none">ผู้มาเยือน (Guest)</span>
                                <span class="text-[9px] font-bold text-amber-500 uppercase tracking-widest mt-1">READ ONLY</span>
                            </div>
                            <a href="<?= base_url('auth/login') ?>" class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-600 hover:to-indigo-700 border border-cyan-500/20 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-cyan-500/10 flex items-center gap-1.5">
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

        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 3000,
                showConfirmButton: false,
                background: '#090d16',
                color: '#e2e8f0',
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
                background: '#090d16',
                color: '#e2e8f0',
                customClass: {
                    popup: 'glass-card rounded-[2rem]'
                }
            });
        <?php endif; ?>

        document.addEventListener('DOMContentLoaded', function() {
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
