<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Staff Portal | อบจ.นครสวรรค์' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <style>
        body { font-family: 'Inter', 'Sarabun', sans-serif; background: #f8fafc; }
        #sidebar-menu { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-collapsed { width: 88px !important; }
        .sidebar-collapsed .sidebar-text, .sidebar-collapsed .sidebar-header-text, .sidebar-collapsed .sidebar-category-text { display: none; }
        .sidebar-collapsed .sidebar-item { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar-collapsed .sidebar-item i { margin-right: 0; }
        .sidebar-item { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-item:hover, .sidebar-item.active { background: rgba(37, 99, 235, 0.08); color: #2563eb; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.4); box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="antialiased text-slate-700">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar-menu" class="flex flex-col w-72 bg-white border-r border-slate-200 shrink-0 fixed inset-y-0 left-0 z-[60] lg:static -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <div class="h-20 flex items-center px-6 border-b border-slate-100 gap-3 shrink-0 overflow-hidden">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shrink-0">
                    <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                </div>
                <div class="sidebar-header-text">
                    <h2 class="text-sm font-black text-slate-900 leading-none">STAFF PORTAL</h2>
                    <p class="text-[9px] text-blue-600 font-bold uppercase tracking-widest mt-1">Nakhon Sawan PAO</p>
                </div>
            </div>
            <nav class="flex-1 overflow-y-auto p-6 space-y-2">
                <!-- 1. บริการพนักงาน (Staff Services) -->
                <div class="sidebar-category-text pt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest px-4 mb-3">บริการพนักงาน</div>
                <a href="<?= base_url('staff') ?>" class="sidebar-item <?= uri_string() == 'staff' ? 'active shadow-lg shadow-blue-100 bg-blue-50/50' : 'text-slate-500 hover:text-blue-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                    <i data-lucide="layout-grid" class="w-5 h-5"></i><span class="sidebar-text">หน้าแดชบอร์ด</span>
                </a>
                <!-- <a href="<?= base_url('staff/attendance') ?>" class="sidebar-item <?= uri_string() == 'staff/attendance' ? 'active shadow-lg shadow-blue-100 bg-blue-50/50' : 'text-slate-500 hover:text-blue-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                    <i data-lucide="map-pin" class="w-5 h-5"></i><span class="sidebar-text">ลงชื่อปฏิบัติงาน</span>
                </a> -->
                <a href="<?= base_url('staff/leave') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/leave') === 0 && strpos(uri_string(), 'staff/leave/admin') === false ? 'active shadow-lg shadow-blue-100 bg-blue-50/50' : 'text-slate-500 hover:text-blue-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                           <?php 
                    $userRoles = session()->get('u_role') ?? ''; 
                    $rolesArr  = array_filter(array_map('trim', explode(',', $userRoles)));
                    $isSuper   = in_array('superadmin', $rolesArr) || strpos($userRoles, 'superadmin') !== false;
                    $isAdmin   = $isSuper || in_array('admin', $rolesArr) || strpos($userRoles, 'admin') !== false;

                    $hasRole = function($role) use ($isAdmin, $rolesArr, $userRoles) {
                        if ($isAdmin) return true;
                        if (in_array($role, $rolesArr)) return true;
                        if (strpos($userRoles, $role) !== false) return true;
                        return false;
                    };
                ?>

                <!-- 2. บริการงานบุคคล (HR Management) -->
                <?php if($isAdmin || $hasRole('personnel') || $hasRole('summary') || $hasRole('head')): ?>
                    <div class="sidebar-category-text pt-6 text-[10px] font-bold text-indigo-400 uppercase tracking-widest px-4 mb-3">บริการงานบุคคล</div>
                    
                    <?php if($hasRole('summary')): ?>
                        <a href="<?= base_url('staff/attendance-admin') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/attendance-admin') === 0 ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="calendar-check" class="w-5 h-5"></i><span class="sidebar-text">จัดการเวลาเข้างาน</span>
                        </a>
                    <?php endif; ?>

                    <?php if($hasRole('personnel')): ?>
                        <a href="<?= base_url('staff/personnel') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/personnel') === 0 ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="users" class="w-5 h-5"></i><span class="sidebar-text">จัดการบุคลากร</span>
                        </a>
                        <a href="<?= base_url('admin/position') ?>" class="sidebar-item <?= strpos(uri_string(), 'admin/position') === 0 ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="award" class="w-5 h-5"></i><span class="sidebar-text">จัดการตำแหน่ง</span>
                        </a>
                    <?php endif; ?>

                    <?php if($isAdmin || $hasRole('head')): ?>
                        <a href="<?= base_url('staff/leave/admin') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/leave/admin') === 0 ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="clipboard-check" class="w-5 h-5"></i><span class="sidebar-text">จัดการการลางาน</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- 3. ประชาสัมพันธ์ & กิจกรรม & ทุน (Portal & Activity Services) -->
                <?php if($isAdmin || $hasRole('news') || $hasRole('scholarships') || $hasRole('scholarship') || $hasRole('forms') || $hasRole('science_week') || $hasRole('sports') || $hasRole('it_support')): ?>
                    <div class="sidebar-category-text pt-6 text-[10px] font-bold text-amber-500 uppercase tracking-widest px-4 mb-3">ประชาสัมพันธ์ & ทุน & กิจกรรม</div>
                    
                    <?php if($hasRole('news')): ?>
                        <a href="<?= base_url('staff/news') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/news') === 0 ? 'active shadow-lg shadow-amber-100 bg-amber-50/50' : 'text-slate-500 hover:text-amber-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="megaphone" class="w-5 h-5"></i><span class="sidebar-text">จัดการข่าวประชาสัมพันธ์</span>
                        </a>
                    <?php endif; ?>

                    <?php if($hasRole('scholarships') || $hasRole('scholarship')): ?>
                        <a href="<?= base_url('staff/scholarships') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/scholarships') === 0 ? 'active shadow-lg shadow-amber-100 bg-amber-50/50' : 'text-slate-500 hover:text-amber-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="graduation-cap" class="w-5 h-5"></i><span class="sidebar-text">จัดการทุนการศึกษา</span>
                        </a>
                    <?php endif; ?>

                    <?php if($hasRole('forms')): ?>
                        <a href="<?= base_url('staff/forms') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/forms') === 0 ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50 text-indigo-700' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="file-check-2" class="w-5 h-5 text-indigo-500"></i><span class="sidebar-text">ระบบแบบสอบถาม & เกียรติบัตร</span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if($hasRole('science_week')): ?>
                        <a href="<?= base_url('science-week/staff') ?>" class="sidebar-item <?= strpos(uri_string(), 'science-week/staff') === 0 ? 'active shadow-lg shadow-purple-100 bg-purple-50/50 text-purple-700' : 'text-slate-500 hover:text-purple-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="orbit" class="w-5 h-5 text-purple-500"></i><span class="sidebar-text">จัดการสัปดาห์วิทยาศาสตร์</span>
                        </a>
                    <?php endif; ?>

                    <?php if($hasRole('sports')): ?>
                        <a href="<?= base_url('staff/sports') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/sports') === 0 ? 'active shadow-lg shadow-emerald-100 bg-emerald-50/50 text-emerald-700' : 'text-slate-500 hover:text-emerald-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="trophy" class="w-5 h-5 text-emerald-500"></i><span class="sidebar-text">จัดการแข่งขันกีฬา อบจ.</span>
                        </a>
                    <?php endif; ?>

                    <?php if($hasRole('it_support')): ?>
                        <a href="<?= base_url('itsupport') ?>" class="sidebar-item <?= strpos(uri_string(), 'itsupport') === 0 ? 'active shadow-lg shadow-teal-100 bg-teal-50/50 text-teal-700' : 'text-slate-500 hover:text-teal-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="wrench" class="w-5 h-5 text-teal-500"></i><span class="sidebar-text">จัดการ IT Support</span>
                        </a>
                    <?php endif; ?>

                <?php endif; ?>

                <!-- 4. ตั้งค่าระบบ (System Admin) -->
                <?php if($isSuper || $isAdmin || $hasRole('settings')): ?>
                    <div class="sidebar-category-text pt-6 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-4 mb-3">ตั้งค่าระบบ</div>
                    <?php if($isSuper || $isAdmin): ?>
                        <a href="<?= base_url('staff/permissions') ?>" class="sidebar-item <?= uri_string() == 'staff/permissions' ? 'active shadow-lg shadow-rose-100 bg-rose-50/50' : 'text-slate-500 hover:text-rose-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="key" class="w-5 h-5"></i><span class="sidebar-text">สิทธิ์การใช้งาน</span>
                        </a>
                    <?php endif; ?>
                    <?php if($isSuper || $hasRole('settings')): ?>
                        <a href="<?= base_url('staff/settings') ?>" class="sidebar-item <?= uri_string() == 'staff/settings' ? 'active shadow-lg shadow-rose-100 bg-rose-50/50' : 'text-slate-500 hover:text-rose-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="settings" class="w-5 h-5"></i><span class="sidebar-text">ตั้งค่าระบบหลัก</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>
            <div class="p-6 border-t border-slate-100">
                <a href="<?= base_url('auth/logout') ?>" class="sidebar-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm text-rose-500 hover:bg-rose-50 transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5"></i><span class="sidebar-text">ออกจากระบบ</span>
                </a>
            </div>
        </aside>

        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 lg:hidden focus:outline-none" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0 z-20 sticky top-0">
                <div class="flex items-center gap-4 min-w-0">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-400"><i data-lucide="menu" class="w-6 h-6"></i></button>
                    <h1 class="text-xl font-black text-slate-900 leading-none truncate">ยินดีต้อนรับ, <?= session()->get('u_fullname') ?></h1>
                </div>
                <div class="flex items-center gap-6">
                    <!-- Dropdown -->
                    <div class="relative">
                        <button id="user-dropdown-btn" class="flex items-center gap-4 hover:bg-slate-50 p-1.5 rounded-2xl transition-all">
                            <div class="hidden md:flex flex-col items-end">
                                <span class="text-sm font-extrabold text-slate-900 leading-none"><?= session()->get('u_fullname') ?></span>
                                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-1"><?= session()->get('u_position') ?></span>
                            </div>
                            <div class="w-10 h-10 bg-slate-100 rounded-full border border-slate-200 overflow-hidden shadow-sm">
                                <?php if(session()->get('u_photo')): ?>
                                    <img src="<?= base_url('uploads/personnel/' . session()->get('u_photo')) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i data-lucide="user" class="w-5 h-5 text-slate-400 m-2.5"></i>
                                <?php endif; ?>
                            </div>
                        </button>
                        <div id="user-dropdown-menu" class="absolute right-0 mt-3 w-56 bg-white rounded-3xl shadow-2xl border border-slate-100 py-3 hidden animate-[fadeIn_0.2s_ease-out] z-50">
                            <a href="<?= base_url('staff/profile') ?>" class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-all"><i data-lucide="user-cog" class="w-5 h-5"></i> ข้อมูลส่วนตัว</a>
                            <a href="<?= base_url('staff/leave') ?>" class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-all"><i data-lucide="file-signature" class="w-5 h-5"></i> การลางานของฉัน</a>
                            <div class="border-t border-slate-50 my-2"></div>
                            <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-rose-500 hover:bg-rose-50 transition-all"><i data-lucide="log-out" class="w-5 h-5"></i> ออกจากระบบ</a>
                        </div>
                    </div>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto p-8 relative flex flex-col justify-between">
                <div class="max-w-7xl mx-auto w-full"><?= $this->renderSection('content') ?></div>
                <footer class="mt-12 pt-6 border-t border-slate-200/80 max-w-7xl mx-auto w-full flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400 font-medium">
                    <div>&copy; <?= date('Y') + 543 ?> PAO-ERC Management System.</div>
                    <div class="flex items-center gap-1.5 text-slate-500">
                        <span>Developed with ❤️ by</span>
                        <a href="https://erc.nsnpao.go.th/itsupport/portfolio" target="_blank" class="text-blue-600 hover:text-blue-700 font-bold flex items-center gap-1 transition-colors">
                            <i data-lucide="music" class="w-3.5 h-3.5"></i> Dekpiano
                        </a>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
        function toggleSidebar() {
            document.getElementById('sidebar-menu').classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }

        const userBtn = document.getElementById('user-dropdown-btn');
        const userMenu = document.getElementById('user-dropdown-menu');
        if (userBtn) {
            userBtn.addEventListener('click', (e) => { e.stopPropagation(); userMenu.classList.toggle('hidden'); });
            document.addEventListener('click', (e) => { if (userMenu && !userMenu.contains(e.target)) userMenu.classList.add('hidden'); });
        }

        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: '<?= session()->getFlashdata('success') ?>', timer: 3000, showConfirmButton: false });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: '<?= session()->getFlashdata('error') ?>' });
        <?php endif; ?>

        document.addEventListener('DOMContentLoaded', function() {
            initFlatpickrBE();
        });

        function initFlatpickrBE() {
            const fpConfig = {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                locale: "th",
                onReady: function(selectedDates, dateStr, instance) {
                    applyBE(instance);
                },
                onValueUpdate: function(selectedDates, dateStr, instance) {
                    applyBE(instance);
                },
                onOpen: function(selectedDates, dateStr, instance) {
                    applyBE(instance);
                },
                onMonthChange: function(selectedDates, dateStr, instance) {
                    setTimeout(() => applyBE(instance), 10);
                },
                onYearChange: function(selectedDates, dateStr, instance) {
                    setTimeout(() => applyBE(instance), 10);
                }
            };
            flatpickr(".datepicker-be", fpConfig);
        }

        function applyBE(instance) {
            if (!instance) return;

            // 1. แปลงปีใน Header ของ Calendar Container (ทั้ง input .cur-year และ numInputWrapper)
            if (instance.calendarContainer) {
                setTimeout(function() {
                    const yearInputs = instance.calendarContainer.querySelectorAll(".cur-year");
                    yearInputs.forEach(y => {
                        let val = parseInt(y.value);
                        if (val > 0 && val < 2400) {
                            y.value = val + 543;
                        }
                    });

                    // Dropdown Month/Year if present
                    const yearElements = instance.calendarContainer.querySelectorAll(".flatpickr-current-month .numInputWrapper span");
                    yearElements.forEach(el => {
                        el.addEventListener('click', () => {
                            setTimeout(() => applyBE(instance), 10);
                        });
                    });
                }, 10);
            }

            // 2. แปลงปีในช่องกรอก (altInput) ให้เป็น พ.ศ.
            if (instance.altInput) {
                let dateToUse = null;
                if (instance.selectedDates && instance.selectedDates.length > 0) {
                    dateToUse = instance.selectedDates[0];
                } else if (instance.input && instance.input.value) {
                    let parsed = new Date(instance.input.value.replace(/-/g, '/'));
                    if (!isNaN(parsed.getTime())) {
                        dateToUse = parsed;
                    }
                }

                if (dateToUse) {
                    const day = dateToUse.getDate().toString().padStart(2, '0');
                    const month = (dateToUse.getMonth() + 1).toString().padStart(2, '0');
                    const year = dateToUse.getFullYear() + 543;
                    instance.altInput.value = `${day}/${month}/${year}`;
                }
            }
        }

        // Global Loading for All Submit Buttons
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
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
