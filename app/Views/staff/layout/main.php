<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Staff Portal | อบจ.นครสวรรค์' ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', 'Sarabun', sans-serif;
            background: #f8fafc;
        }

        /* Sidebar Collapse Transitions */
        #sidebar-menu {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-collapsed {
            width: 88px !important;
        }

        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .sidebar-header-text,
        .sidebar-collapsed .sidebar-category-text {
            display: none;
        }

        .sidebar-collapsed .sidebar-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        .sidebar-collapsed .sidebar-item i {
            margin-right: 0;
        }

        .sidebar-item {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-item:hover, .sidebar-item.active {
            background: rgba(37, 99, 235, 0.08);
            color: #2563eb;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="antialiased text-slate-700">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside id="sidebar-menu" class="hidden lg:flex flex-col w-72 bg-white border-r border-slate-200 shrink-0 fixed inset-y-0 left-0 z-[60] lg:static">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center px-6 border-b border-slate-100 gap-3 shrink-0 overflow-hidden">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shrink-0">
                    <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                </div>
                <div class="sidebar-header-text">
                    <h2 class="text-sm font-black text-slate-900 leading-none">STAFF PORTAL</h2>
                    <p class="text-[9px] text-blue-600 font-bold uppercase tracking-widest mt-1">Nakhon Sawan PAO</p>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 overflow-y-auto p-6 space-y-2">
                <div class="sidebar-category-text text-[10px] font-bold text-slate-400 uppercase tracking-widest px-4 mb-4">เมนูหลัก</div>
                
                <a href="<?= base_url('staff') ?>" class="sidebar-item <?= uri_string() == 'staff' ? 'active shadow-lg shadow-blue-100 bg-blue-50/50' : 'text-slate-500 hover:text-blue-600' ?> flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm">
                    <i data-lucide="layout-grid" class="w-5 h-5"></i>
                    <span class="sidebar-text">หน้าแดชบอร์ด</span>
                </a>

                <div class="sidebar-category-text pt-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest px-4 mb-4">ระบบบริหารงาน</div>
                
                <a href="<?= base_url('staff/attendance') ?>" class="sidebar-item <?= uri_string() == 'staff/attendance' ? 'active shadow-lg shadow-blue-100 bg-blue-50/50' : 'text-slate-500 hover:text-blue-600' ?> flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                    <span class="sidebar-text">บันทึกเวลาปฏิบัติราชการ</span>
                </a>

                <?php 
                    $userRoles = session()->get('u_role') ?? ''; 
                    $isAdmin = (strpos($userRoles, 'admin') !== false || strpos($userRoles, 'superadmin') !== false);
                    $isSuper = (strpos($userRoles, 'superadmin') !== false);
                ?>

                <?php if($isAdmin || strpos($userRoles, 'news') !== false || strpos($userRoles, 'personnel') !== false || strpos($userRoles, 'summary') !== false): ?>
                <div class="sidebar-category-text pt-8 text-[10px] font-bold text-rose-400 uppercase tracking-widest px-4 mb-4 flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> การจัดการ (แอดมิน)
                </div>
                <?php if($isSuper || strpos($userRoles, 'news') !== false): ?>
                <a href="<?= base_url('staff/news') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/news') === 0 ? 'active shadow-lg shadow-blue-100 bg-blue-50/50' : 'text-slate-500 hover:text-blue-600' ?> flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm">
                    <i data-lucide="newspaper" class="w-5 h-5"></i>
                    <span class="sidebar-text">จัดการข่าวสาร</span>
                </a>
                <?php endif; ?>
                
                <?php if($isSuper || strpos($userRoles, 'personnel') !== false): ?>
                <a href="<?= base_url('staff/personnel') ?>" class="sidebar-item <?= strpos(uri_string(), 'staff/personnel') === 0 ? 'active shadow-lg shadow-blue-100 bg-blue-50/50' : 'text-slate-500 hover:text-blue-600' ?> flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span class="sidebar-text">จัดการบุคลากร</span>
                </a>
                <?php endif; ?>

                <?php if($isSuper || strpos($userRoles, 'summary') !== false): ?>
                <a href="<?= base_url('staff/admin-summary') ?>" class="sidebar-item <?= uri_string() == 'staff/admin-summary' ? 'active shadow-lg shadow-blue-100 bg-blue-50/50' : 'text-slate-500 hover:text-blue-600' ?> flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    <span class="sidebar-text">สรุปเวลาปฏิบัติงาน</span>
                </a>
                <?php endif; ?>
                <?php endif; ?>

                <?php if($isSuper || strpos($userRoles, 'scholarships') !== false): ?>
                <div class="sidebar-category-text pt-8 text-[10px] font-bold text-amber-500 uppercase tracking-widest px-4 mb-4 flex items-center gap-2">
                    <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> ระบบทุนการศึกษา
                </div>
                <a href="<?= base_url('staff/scholarships') ?>" class="sidebar-item <?= (uri_string() == 'staff/scholarships' || (strpos(uri_string(), 'staff/scholarship/') === 0 && strpos(uri_string(), 'slots') === false && strpos(uri_string(), 'bookings') === false)) ? 'active shadow-lg shadow-amber-100 bg-amber-50/50' : 'text-slate-500 hover:text-amber-600' ?> flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm">
                    <i data-lucide="book-copy" class="w-5 h-5"></i>
                    <span class="sidebar-text">จัดการเนื้อหาทุน</span>
                </a>
                <a href="<?= base_url('staff/scholarship-bookings') ?>" class="sidebar-item <?= (strpos(uri_string(), 'staff/scholarship-bookings') === 0 || strpos(uri_string(), 'slots') !== false || strpos(uri_string(), 'bookings') !== false) ? 'active shadow-lg shadow-violet-100 bg-violet-50/50' : 'text-slate-500 hover:text-violet-600' ?> flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm">
                    <i data-lucide="calendar-check" class="w-5 h-5"></i>
                    <span class="sidebar-text">จัดการคิวจองทุน</span>
                </a>
                <?php endif; ?>

                <?php if($isSuper || strpos($userRoles, 'settings') !== false): ?>
                <div class="sidebar-category-text pt-8 text-[10px] font-bold text-indigo-500 uppercase tracking-widest px-4 mb-4 flex items-center gap-2">
                    <i data-lucide="database" class="w-3.5 h-3.5"></i> ผู้ดูแลระบบสูงสุด
                </div>

                <?php if($isSuper): ?>
                <a href="<?= base_url('staff/permissions') ?>" class="sidebar-item <?= uri_string() == 'staff/permissions' ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                    <span class="sidebar-text">จัดการสิทธิ์การใช้งาน</span>
                </a>
                <?php endif; ?>

                <?php if($isSuper || strpos($userRoles, 'settings') !== false): ?>
                <a href="<?= base_url('staff/settings') ?>" class="sidebar-item <?= uri_string() == 'staff/settings' ? 'active shadow-lg shadow-indigo-100 bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600' ?> flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span class="sidebar-text">ตั้งค่าระบบ</span>
                </a>
                <?php endif; ?>
                <?php endif; ?>

            </nav>

            <!-- Sidebar Footer -->
            <div class="p-6 border-t border-slate-100 overflow-hidden">
                <a href="<?= base_url('auth/logout') ?>" class="sidebar-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm text-rose-500 hover:bg-rose-50 transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span class="sidebar-text">ออกจากระบบ</span>
                </a>
            </div>
        </aside>

        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 lg:hidden"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Navbar -->
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0 z-20">
                <div class="flex items-center gap-4">
                    <button id="mobile-sidebar-btn" class="lg:hidden p-2 text-slate-400">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <button id="sidebar-collapse-btn" class="hidden lg:flex p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all rounded-lg">
                        <i data-lucide="chevrons-left" class="w-6 h-6"></i>
                    </button>
                    <h1 class="text-xl font-black text-slate-900 leading-none">ยินดีต้อนรับ, <?= session()->get('u_fullname') ?></h1>
                </div>

                <div class="flex items-center gap-6">
                    <div class="hidden md:flex flex-col items-end text-right">
                        <span class="text-sm font-extrabold text-slate-900 leading-none"><?= session()->get('u_fullname') ?></span>
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-1">
                            <?= session()->get('u_position') ?>
                        </span>
                    </div>
                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center border border-slate-200 shadow-sm overflow-hidden shrink-0">
                        <?php if(session()->get('u_photo')): ?>
                            <img src="<?= base_url('uploads/personnel/' . session()->get('u_photo')) ?>" alt="Profile" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i data-lucide="user" class="w-5 h-5 text-slate-400"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-y-auto p-8 relative">
                <div class="max-w-7xl mx-auto animate-[fadeIn_0.5s_ease-out]">
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Mobile Sidebar Toggle
        const sidebarBtn = document.getElementById('mobile-sidebar-btn');
        const sidebarMenu = document.getElementById('sidebar-menu');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        if (sidebarBtn && sidebarMenu && sidebarOverlay) {
            const toggleSidebar = () => {
                sidebarMenu.classList.toggle('hidden');
                sidebarOverlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            };

            sidebarBtn.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }

        // Desktop Sidebar Collapse Toggle
        const collapseBtn = document.getElementById('sidebar-collapse-btn');
        if (collapseBtn) {
            collapseBtn.addEventListener('click', () => {
                const isCollapsed = sidebarMenu.classList.toggle('sidebar-collapsed');
                const icon = collapseBtn.querySelector('i');
                if (isCollapsed) {
                    icon.setAttribute('data-lucide', 'chevrons-right');
                } else {
                    icon.setAttribute('data-lucide', 'chevrons-left');
                }
                lucide.createIcons();
            });
        }

        // Global SweetAlert2 Notifications
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 3000,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-[2rem]',
                }
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: '<?= session()->getFlashdata('error') ?>',
                customClass: {
                    popup: 'rounded-[2rem]',
                }
            });
        <?php endif; ?>

        // Global Loading for All Submit Buttons
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const submitBtn = e.submitter || form.querySelector('button[type="submit"]');
            
            if (submitBtn && !submitBtn.hasAttribute('data-no-loading')) {
                // ตรวจสอบความถูกต้องของฟอร์มก่อน (Browser Validation)
                if (form.checkValidity()) {
                    // ใช้ setTimeout เล็กน้อยเพื่อให้ Browser รันการทำงานของระบบเดิมก่อน
                    setTimeout(() => {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-80', 'cursor-not-allowed', 'pointer-events-none');
                        
                        // สร้าง Spinner Loading
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
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
