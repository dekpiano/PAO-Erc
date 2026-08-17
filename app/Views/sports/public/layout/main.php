<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ระบบจัดการและลงทะเบียนแข่งขันกีฬา อบจ.คัพ' ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo-pao.png') ?>">

    <!-- Google Fonts: K2D -->
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery & Select2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Flatpickr (Thai Buddhist Era support) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

    <style>
        /* Select2 Custom Tailored Styling */
        .select2-container .select2-selection--single {
            height: 44px !important;
            border-radius: 0.875rem !important;
            border: 1px solid #e2e8f0 !important;
            padding: 7px 12px !important;
            font-size: 0.75rem !important;
            font-family: 'K2D', sans-serif !important;
            font-weight: 600 !important;
            background-color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            right: 10px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1e293b !important;
            line-height: normal !important;
            padding-left: 0 !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #059669 !important;
            outline: 2px solid rgba(5, 150, 105, 0.2) !important;
        }
        .select2-dropdown {
            border-radius: 0.875rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
            overflow: hidden !important;
            font-family: 'K2D', sans-serif !important;
            font-size: 0.75rem !important;
            z-index: 9999 !important;
        }
        .select2-results__option {
            padding: 8px 14px !important;
            font-weight: 500 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #059669 !important;
            color: #ffffff !important;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #ecfdf5 !important;
            color: #065f46 !important;
            font-weight: 700 !important;
        }
        .select2-search--dropdown .select2-search__field {
            border-radius: 0.5rem !important;
            border: 1px solid #cbd5e1 !important;
            padding: 6px 10px !important;
            font-size: 0.75rem !important;
            outline: none !important;
        }
        body {
            font-family: 'K2D', sans-serif;
            background-color: #f0fdf4;
            color: #1e293b;
            position: relative;
        }

        /* Animated Floating Background Elements */
        @keyframes float-slow {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(30px, -20px) scale(1.05); }
        }

        @keyframes float-reverse {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(-25px, 25px) scale(0.95); }
        }
    </style>
</head>

<body class="antialiased min-h-screen flex flex-col justify-between bg-grid-pattern relative">

    <!-- Dynamic Animated SVG Background Layer -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-gradient-to-br from-emerald-300/30 to-teal-200/20 rounded-full blur-3xl animate-float-1"></div>
        <div class="absolute top-1/3 -right-32 w-[30rem] h-[30rem] bg-gradient-to-bl from-teal-300/20 via-cyan-200/20 to-emerald-200/20 rounded-full blur-3xl animate-float-2"></div>
        <div class="absolute -bottom-32 left-1/4 w-[28rem] h-[28rem] bg-gradient-to-tr from-emerald-400/15 to-green-200/20 rounded-full blur-3xl animate-float-1"></div>

        <svg class="absolute top-24 left-[10%] w-24 h-24 text-emerald-600/10 animate-float-1" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="50" cy="50" r="40" />
            <path d="M50 10 C 30 30, 30 70, 50 90" />
            <path d="M50 10 C 70 30, 70 70, 50 90" />
            <line x1="10" y1="50" x2="90" y2="50" />
        </svg>

        <svg class="absolute top-1/2 right-[8%] w-32 h-32 text-amber-500/10 animate-float-2" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="50" cy="50" r="40" stroke-dasharray="4 2" />
            <polygon points="50,20 80,75 20,75" stroke-width="1.5" />
            <circle cx="50" cy="50" r="10" fill="currentColor" fill-opacity="0.05" />
        </svg>

        <svg class="absolute bottom-20 left-[15%] w-28 h-28 text-teal-600/10 animate-float-1" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="20" y="20" width="60" height="60" rx="12" />
            <path d="M20 50 H80 M50 20 V80" />
            <circle cx="50" cy="50" r="15" />
        </svg>

        <svg class="absolute -bottom-20 right-[25%] w-96 h-96 text-emerald-500/[0.04] animate-pulse-soft" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="100" cy="100" r="80" />
            <circle cx="100" cy="100" r="50" />
            <line x1="100" y1="0" x2="100" y2="200" />
        </svg>
    </div>

    <!-- Sports Main Header Navbar -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-emerald-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Brand -->
                <a href="<?= base_url('sports') ?>" class="flex items-center gap-3.5 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 p-0.5 shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform flex items-center justify-center">
                        <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center">
                            <i data-lucide="trophy" class="w-6 h-6 text-emerald-600"></i>
                        </div>
                    </div>
                    <div>
                        <span class="text-lg font-black tracking-tight text-slate-900 leading-none block">
                            อบจ.นครสวรรค์ เกมส์ <?= !empty($activeCompYear) ? esc($activeCompYear) : '' ?>
                        </span>
                        <p class="text-[11px] font-bold text-emerald-600 tracking-wide mt-1">
                            ระบบลงทะเบียนแข่งขันกีฬา อบจ.นครสวรรค์
                        </p>
                    </div>
                </a>

                <!-- Desktop Nav Items -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="<?= base_url('sports') ?>" class="px-4 py-2 rounded-xl text-xs font-bold transition-all <?= uri_string() === 'sports' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-200' : 'text-slate-600 hover:bg-slate-100 hover:text-emerald-600' ?>">
                        <i data-lucide="home" class="w-4 h-4 inline mr-1"></i> หน้าหลักกีฬา
                    </a>
                    <a href="<?= base_url('sports/results') ?>" class="px-4 py-2 rounded-xl text-xs font-bold transition-all <?= strpos(uri_string(), 'sports/results') === 0 ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-200' : 'text-slate-600 hover:bg-slate-100 hover:text-emerald-600' ?>">
                        <i data-lucide="trophy" class="w-4 h-4 inline mr-1 text-amber-400"></i> ผลการแข่งขัน
                    </a>
                    <a href="<?= base_url('sports/status') ?>" class="px-4 py-2 rounded-xl text-xs font-bold transition-all <?= strpos(uri_string(), 'sports/status') === 0 ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-200' : 'text-slate-600 hover:bg-slate-100 hover:text-emerald-600' ?>">
                        <i data-lucide="search" class="w-4 h-4 inline mr-1"></i> ตรวจสอบสถานะ
                    </a>
                    <a href="<?= base_url('sports/certificate') ?>" class="px-4 py-2 rounded-xl text-xs font-bold transition-all <?= strpos(uri_string(), 'sports/certificate') === 0 ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-200' : 'text-slate-600 hover:bg-slate-100 hover:text-emerald-600' ?>">
                        <i data-lucide="award" class="w-4 h-4 inline mr-1"></i> ค้นหาเกียรติบัตร
                    </a>
                    <a href="<?= base_url('staff/sports') ?>" class="ml-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                        <span>สำหรับเจ้าหน้าที่</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="sports-mobile-menu-btn" class="p-2 text-slate-700 hover:bg-slate-100 rounded-xl">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Dropdown Menu -->
    <div id="sports-mobile-menu" class="hidden md:hidden fixed inset-x-0 top-20 bg-white border-b border-slate-200 shadow-xl p-4 space-y-2 z-40">
        <a href="<?= base_url('sports') ?>" class="block px-4 py-2.5 rounded-xl text-xs font-bold <?= uri_string() === 'sports' ? 'bg-emerald-600 text-white' : 'text-slate-700 hover:bg-slate-100' ?>">
            <i data-lucide="home" class="w-4 h-4 inline mr-2"></i> หน้าหลักกีฬา
        </a>
        <a href="<?= base_url('sports/results') ?>" class="block px-4 py-2.5 rounded-xl text-xs font-bold <?= strpos(uri_string(), 'sports/results') === 0 ? 'bg-emerald-600 text-white' : 'text-slate-700 hover:bg-slate-100' ?>">
            <i data-lucide="trophy" class="w-4 h-4 inline mr-2 text-amber-400"></i> ผลการแข่งขัน
        </a>
        <a href="<?= base_url('sports/status') ?>" class="block px-4 py-2.5 rounded-xl text-xs font-bold <?= strpos(uri_string(), 'sports/status') === 0 ? 'bg-emerald-600 text-white' : 'text-slate-700 hover:bg-slate-100' ?>">
            <i data-lucide="search" class="w-4 h-4 inline mr-2"></i> ตรวจสอบสถานะการสมัคร
        </a>
        <a href="<?= base_url('sports/certificate') ?>" class="block px-4 py-2.5 rounded-xl text-xs font-bold <?= strpos(uri_string(), 'sports/certificate') === 0 ? 'bg-emerald-600 text-white' : 'text-slate-700 hover:bg-slate-100' ?>">
            <i data-lucide="award" class="w-4 h-4 inline mr-2"></i> ค้นหาเกียรติบัตร
        </a>
        <a href="<?= base_url('staff/sports') ?>" class="block px-4 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold text-center">
            <i data-lucide="shield-check" class="w-4 h-4 inline mr-1 text-emerald-400"></i> สำหรับเจ้าหน้าที่
        </a>
    </div>

    <!-- Main Content Area -->
    <main class="flex-1">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Sports Footer -->
    <footer class="bg-slate-900 text-white py-10 border-t border-slate-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center shadow-md shadow-emerald-500/20 text-white">
                    <i data-lucide="trophy" class="w-5 h-5"></i>
                </div>
                <div>
                    <h5 class="text-sm font-black text-white tracking-tight">ระบบลงทะเบียนการแข่งขันกีฬา อบจ.นครสวรรค์ เกมส์</h5>
                    <p class="text-xs text-slate-400">องค์การบริหารส่วนจังหวัดนครสวรรค์</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 text-xs text-slate-400">
                <span>&copy; <?= date('Y') + 543 ?> PAO Sports System. All rights reserved.</span>
                <span class="hidden sm:inline text-slate-600">•</span>
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-slate-300">
                    <i data-lucide="code" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span>Developed with ❤️ by</span>
                    <a href="https://erc.nsnpao.go.th/itsupport/portfolio" target="_blank" class="text-emerald-400 hover:text-emerald-300 font-bold flex items-center gap-1 transition-colors">
                        <i data-lucide="music" class="w-3 h-3"></i> Dekpiano
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 600,
                once: true
            });
        }

        // Mobile menu toggle
        const mobBtn = document.getElementById('sports-mobile-menu-btn');
        const mobMenu = document.getElementById('sports-mobile-menu');
        if (mobBtn && mobMenu) {
            mobBtn.addEventListener('click', () => {
                mobMenu.classList.toggle('hidden');
            });
        }

        // Global Flash Messages
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: '<?= session()->getFlashdata('success') ?>', timer: 3000, showConfirmButton: false });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({ icon: 'error', title: 'แจ้งเตือน', text: '<?= session()->getFlashdata('error') ?>' });
        <?php endif; ?>

        // Global Submit Loading
        document.addEventListener('submit', function (e) {
            const form = e.target;
            const submitBtn = e.submitter || form.querySelector('button[type="submit"]');

            if (submitBtn && !submitBtn.hasAttribute('data-no-loading')) {
                if (form.checkValidity()) {
                    setTimeout(() => {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-80', 'cursor-not-allowed', 'pointer-events-none');
                        submitBtn.innerHTML = `
                            <div class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>กำลังประมวลผล...</span>
                            </div>
                        `;
                    }, 0);
                }
            }
        });

        // Global Flatpickr Thai Buddhist Era (พ.ศ.) Helper
        function applyBE(instance) {
            if (!instance) return;
            if (instance.calendarContainer) {
                const yearInputs = instance.calendarContainer.querySelectorAll(".cur-year");
                yearInputs.forEach(y => {
                    let val = parseInt(y.value);
                    if (val > 0 && val < 2400) y.value = val + 543;
                });
            }
            if (instance.altInput) {
                let dateToUse = null;
                if (instance.selectedDates && instance.selectedDates.length > 0) {
                    dateToUse = instance.selectedDates[0];
                } else if (instance.input && instance.input.value) {
                    let parsed = new Date(instance.input.value.replace(/-/g, '/'));
                    if (!isNaN(parsed.getTime())) dateToUse = parsed;
                }
                if (dateToUse) {
                    const day = dateToUse.getDate().toString().padStart(2, '0');
                    const month = (dateToUse.getMonth() + 1).toString().padStart(2, '0');
                    const year = dateToUse.getFullYear() + 543;
                    instance.altInput.value = `${day}/${month}/${year}`;
                }
            }
        }
    </script>
</body>
</html>
