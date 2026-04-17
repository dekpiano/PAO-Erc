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
                    <i data-lucide="file-signature" class="w-5 h-5"></i><span class="sidebar-text">การลางานของฉัน</span>
                </a>

                <?php 
                    $userRoles = session()->get('u_role') ?? ''; 
                    $isAdmin = (strpos($userRoles, 'admin') !== false || strpos($userRoles, 'superadmin') !== false);
                    $isSuper = (strpos($userRoles, 'superadmin') !== false);
                ?>

                <!-- 2. บริการงานบุคคล (HR Management) -->
                <?php if($isAdmin || strpos($userRoles, 'personnel') !== false || strpos($userRoles, 'summary') !== false): ?>
                    <div class="sidebar-category-text pt-6 text-[10px] font-bold text-indigo-400 uppercase tracking-widest px-4 mb-3">บริการงานบุคคล</div>
                    
                    <?php if($isAdmin || strpos($userRoles, 'summary') !== false): ?>
                        <!-- <a href="<?= base_url('staff/admin-summary') ?>" class="sidebar-item <?= uri_string() == 'staff/admin-summary' ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="bar-chart-3" class="w-5 h-5"></i><span class="sidebar-text">สรุปเวลาทำงาน</span>
                        </a> -->
                        <a href="<?= base_url('staff/attendance-admin') ?>" class="sidebar-item <?= uri_string() == 'staff/attendance-admin' ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="calendar-check" class="w-5 h-5"></i><span class="sidebar-text">จัดการเวลาเข้างาน</span>
                        </a>
                    <?php endif; ?>

                    <?php if($isSuper || strpos($userRoles, 'personnel') !== false): ?>
                        <a href="<?= base_url('staff/personnel') ?>" class="sidebar-item <?= uri_string() == 'staff/personnel' ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="users" class="w-5 h-5"></i><span class="sidebar-text">จัดการบุคลากร</span>
                        </a>
                    <?php endif; ?>

                    <?php if($isAdmin): ?>
                        <a href="<?= base_url('admin/position') ?>" class="sidebar-item <?= uri_string() == 'admin/position' ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="award" class="w-5 h-5"></i><span class="sidebar-text">จัดการตำแหน่ง</span>
                        </a>
                        <a href="<?= base_url('staff/leave/admin') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/leave/admin') === 0 ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="clipboard-check" class="w-5 h-5"></i><span class="sidebar-text">จัดการการลางาน</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- 3. ประชาสัมพันธ์ & ทุน (Portal Services) -->
                <?php if($isAdmin || strpos($userRoles, 'news') !== false || strpos($userRoles, 'scholarship') !== false): ?>
                    <div class="sidebar-category-text pt-6 text-[10px] font-bold text-amber-500 uppercase tracking-widest px-4 mb-3">ประชาสัมพันธ์ & ทุน</div>
                    
                    <?php if($isAdmin || strpos($userRoles, 'news') !== false): ?>
                        <a href="<?= base_url('staff/news') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/news') === 0 ? 'active shadow-lg shadow-amber-100 bg-amber-50/50' : 'text-slate-500 hover:text-amber-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="megaphone" class="w-5 h-5"></i><span class="sidebar-text">จัดการข่าวประชาสัมพันธ์</span>
                        </a>
                    <?php endif; ?>

                    <?php if($isAdmin || strpos($userRoles, 'scholarship') !== false): ?>
                        <a href="<?= base_url('staff/scholarships') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/scholarships') === 0 ? 'active shadow-lg shadow-amber-100 bg-amber-50/50' : 'text-slate-500 hover:text-amber-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                            <i data-lucide="graduation-cap" class="w-5 h-5"></i><span class="sidebar-text">จัดการทุนการศึกษา</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- 4. ตั้งค่าระบบ (System Admin) -->
                <?php if($isSuper): ?>
                    <div class="sidebar-category-text pt-6 text-[10px] font-bold text-rose-500 uppercase tracking-widest px-4 mb-3">ตั้งค่าระบบ (Superadmin)</div>
                    <a href="<?= base_url('staff/permissions') ?>" class="sidebar-item <?= uri_string() == 'staff/permissions' ? 'active shadow-lg shadow-rose-100 bg-rose-50/50' : 'text-slate-500 hover:text-rose-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                        <i data-lucide="key" class="w-5 h-5"></i><span class="sidebar-text">สิทธิ์การใช้งาน</span>
                    </a>
                    <a href="<?= base_url('staff/settings') ?>" class="sidebar-item <?= uri_string() == 'staff/settings' ? 'active shadow-lg shadow-rose-100 bg-rose-50/50' : 'text-slate-500 hover:text-rose-600' ?> flex items-center gap-4 px-4 py-3 rounded-2xl font-bold text-sm">
                        <i data-lucide="settings" class="w-5 h-5"></i><span class="sidebar-text">ตั้งค่าระบบหลัก</span>
                    </a>
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
            <main class="flex-1 overflow-y-auto p-8 relative">
                <div class="max-w-7xl mx-auto"><?= $this->renderSection('content') ?></div>
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
            const fpConfig = {
                dateFormat: "Y-m-d", altInput: true, altFormat: "d/m/Y", locale: "th",
                onReady: instance => applyBE(instance),
                onValueUpdate: instance => applyBE(instance),
                onOpen: instance => applyBE(instance),
                onMonthChange: instance => setTimeout(() => applyBE(instance), 1),
                onYearChange: instance => setTimeout(() => applyBE(instance), 1)
            };
            flatpickr(".datepicker-be", fpConfig);
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
                instance.altInput.value = `${day}/${month}/${year}`;
            }
        }
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
