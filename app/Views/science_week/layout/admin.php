<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ระบบจัดการสัปดาห์วิทยาศาสตร์ | อบจ.นครสวรรค์' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo-pao.png') ?>">
    
    <!-- Google Fonts: Inter & Sarabun -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', 'Sarabun', sans-serif;
            background-color: #090d16;
            color: #cbd5e1;
            overflow-x: hidden;
        }

        .sci-sidebar {
            background-color: #020617;
            border-right: 1px solid rgba(99, 102, 241, 0.15);
        }

        .sci-header {
            background-color: rgba(2, 6, 23, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.15);
        }

        .glass-card {
            background: rgba(17, 25, 40, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.2);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.4);
        }

        .sidebar-item-active {
            background: rgba(99, 102, 241, 0.15);
            color: #818cf8;
            border-left: 3px solid #818cf8;
        }
    </style>
</head>
<body class="antialiased custom-scrollbar overflow-x-hidden">
    <div class="flex h-screen overflow-hidden w-full max-w-full">
        
        <!-- Sidebar -->
        <aside id="sidebar-menu" class="sci-sidebar flex flex-col w-72 shrink-0 fixed inset-y-0 left-0 z-[60] lg:static -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <!-- Header/Branding -->
            <div class="h-20 flex items-center px-6 border-b border-slate-900 gap-3 shrink-0 overflow-hidden">
                <div class="w-10 h-10 bg-gradient-to-tr from-cyan-500 to-violet-750 rounded-xl flex items-center justify-center text-white shadow-lg shrink-0">
                    <i data-lucide="orbit" class="w-5 h-5 text-cyan-300 animate-spin" style="animation-duration: 20s;"></i>
                </div>
                <div>
                    <h2 class="text-xs font-black tracking-widest uppercase text-slate-100">SCI-WEEK CONTROL</h2>
                    <p class="text-[8px] text-cyan-400 font-bold uppercase tracking-widest mt-1">อบจ.นครสวรรค์</p>
                </div>
            </div>
            
            <!-- Links -->
            <nav class="flex-1 overflow-y-auto p-6 space-y-2 custom-scrollbar">
                <!-- Group 1: Registration & Scores -->
                <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-4 mb-2 mt-2">จัดการการแข่งขัน</div>
                
                <a href="<?= base_url('staff/science-week') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 <?= uri_string() == 'staff/science-week' ? 'sidebar-item-active' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-900/30' ?>">
                    <i data-lucide="users" class="w-5 h-5 text-cyan-400"></i><span>รายชื่อผู้สมัครแข่งขัน</span>
                </a>

                <a href="<?= base_url('staff/science-week/ranking') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 <?= strpos(uri_string(), 'staff/science-week/ranking') !== false ? 'sidebar-item-active' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-900/30' ?>">
                    <i data-lucide="trophy" class="w-5 h-5 text-amber-400"></i><span>จัดการผลการแข่งขัน</span>
                </a>
                
                <!-- Group 2: Configurations -->
                <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-4 pt-4 mb-2">ตั้งค่ากิจกรรม & ข้อมูล</div>

                <a href="<?= base_url('staff/science-week/competitions') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 <?= strpos(uri_string(), 'staff/science-week/competitions') !== false ? 'sidebar-item-active' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-900/30' ?>">
                    <i data-lucide="award" class="w-5 h-5 text-indigo-400"></i><span>จัดการประเภทการแข่งขัน</span>
                </a>

                <a href="<?= base_url('staff/science-week/certificates') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 <?= strpos(uri_string(), 'staff/science-week/certificates') !== false ? 'sidebar-item-active' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-900/30' ?>">
                    <i data-lucide="file-badge" class="w-5 h-5 text-emerald-400"></i><span>ตั้งค่าระบบเกียรติบัตร</span>
                </a>

                <a href="<?= base_url('staff/science-week/schedules') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 <?= strpos(uri_string(), 'staff/science-week/schedules') !== false ? 'sidebar-item-active' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-900/30' ?>">
                    <i data-lucide="calendar-days" class="w-5 h-5 text-purple-400"></i><span>จัดการกำหนดการกิจกรรม</span>
                </a>

                <a href="<?= base_url('staff/science-week/evaluations') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 <?= strpos(uri_string(), 'staff/science-week/evaluations') !== false ? 'sidebar-item-active' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-900/30' ?>">
                    <i data-lucide="star" class="w-5 h-5 text-amber-400"></i><span>จัดการแบบประเมิน</span>
                </a>

                <!-- Group 3: System Settings -->
                <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-4 pt-4 mb-2">ตั้งค่าระบบแอดมิน</div>

                <a href="<?= base_url('staff/science-week/users') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 <?= strpos(uri_string(), 'staff/science-week/users') !== false ? 'sidebar-item-active' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-900/30' ?>">
                    <i data-lucide="shield-check" class="w-5 h-5 text-cyan-400"></i><span>จัดการสิทธิ์เจ้าหน้าที่</span>
                </a>

                <a href="<?= base_url('staff/science-week/settings') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-sm transition-all duration-200 <?= strpos(uri_string(), 'staff/science-week/settings') !== false ? 'sidebar-item-active' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-900/30' ?>">
                    <i data-lucide="settings" class="w-5 h-5 text-slate-400"></i><span>ตั้งค่าระบบ</span>
                </a>

                <!-- Group 4: Navigation -->
                <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-4 pt-4 mb-2">ลิงก์ระบบงาน</div>
                
                <a href="<?= base_url('science-week') ?>" target="_blank" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-sm text-slate-400 hover:text-slate-100 hover:bg-slate-900/30 transition-all">
                    <i data-lucide="external-link" class="w-5 h-5 text-emerald-400"></i><span>เปิดหน้าเว็บกิจกรรม</span>
                </a>

                <a href="<?= base_url('auth/select') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-sm text-slate-400 hover:text-slate-100 hover:bg-slate-900/30 transition-all">
                    <i data-lucide="arrow-left-right" class="w-5 h-5 text-blue-400"></i><span>สลับระบบงานอื่น</span>
                </a>
            </nav>

            <div class="p-6 border-t border-slate-900">
                <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-bold text-sm text-rose-500 hover:bg-rose-950/20 transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5"></i><span>ออกจากระบบ</span>
                </a>
            </div>
        </aside>

        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 lg:hidden focus:outline-none" onclick="toggleSidebar()"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden w-full max-w-full">
            
            <!-- Header -->
            <header class="h-20 sci-header flex items-center justify-between px-6 shrink-0 z-20 sticky top-0 w-full max-w-full">
                <div class="flex items-center gap-4 min-w-0">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-400 hover:text-slate-100"><i data-lucide="menu" class="w-6 h-6"></i></button>
                    <h1 class="text-xs sm:text-base font-extrabold flex items-center gap-2 truncate">
                        <span class="w-2.5 h-2.5 bg-violet-500 rounded-full animate-pulse shadow-glow"></span>
                        <span class="truncate">ระบบจัดการข้อมูลวันวิทยาศาสตร์ (Staff)</span>
                    </h1>
                </div>
                
                <!-- Profile & Year Switcher -->
                <div class="flex items-center gap-4">
                    <?php
                    $layoutDb = \Config\Database::connect();
                    $layoutActiveYear = $layoutDb->table('Tb_Settings')->where('s_key', 'science_week_active_year')->get()->getRowArray()['s_value'] ?? 2569;
                    $layoutSelectedYear = session()->get('science_week_selected_year') ?: $layoutActiveYear;

                    $layoutYearsQuery = $layoutDb->query("
                        SELECT DISTINCT year_val FROM (
                            SELECT comp_year AS year_val FROM Tb_ScienceWeek_Competitions WHERE comp_year IS NOT NULL
                            UNION
                            SELECT reg_year AS year_val FROM Tb_ScienceWeek_Registrations WHERE reg_year IS NOT NULL
                            UNION
                            SELECT sch_year AS year_val FROM Tb_ScienceWeek_Schedules WHERE sch_year IS NOT NULL
                            UNION
                            SELECT eval_year AS year_val FROM Tb_ScienceWeek_Evaluations WHERE eval_year IS NOT NULL
                        ) t ORDER BY year_val DESC
                    ");
                    $layoutAvailableYears = array_column($layoutYearsQuery->getResultArray(), 'year_val');
                    if (empty($layoutAvailableYears)) {
                        $layoutAvailableYears = [$layoutSelectedYear];
                    } else if (!in_array($layoutSelectedYear, $layoutAvailableYears)) {
                        $layoutAvailableYears[] = $layoutSelectedYear;
                        rsort($layoutAvailableYears);
                    }
                    ?>
                    <!-- Year Switcher Dropdown -->
                    <div class="flex items-center gap-2 bg-slate-900/90 border border-slate-800 px-3 py-2 rounded-2xl shadow-inner shadow-black/40">
                        <label for="layout_year_switcher" class="hidden sm:inline text-[9px] font-bold text-slate-400 uppercase tracking-widest">ปีการศึกษา:</label>
                        <select id="layout_year_switcher" onchange="switchAcademicYear(this.value)" class="bg-transparent text-xs text-indigo-400 font-extrabold outline-none border-none cursor-pointer hover:text-cyan-400 transition-colors pr-1">
                            <?php foreach ($layoutAvailableYears as $yr): ?>
                                <option value="<?= $yr ?>" class="bg-slate-950 text-slate-300 font-bold" <?= $yr == $layoutSelectedYear ? 'selected' : '' ?>>
                                    <?= $yr ?> <?= $yr == $layoutActiveYear ? '(ปัจจุบัน)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Profile details -->
                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex flex-col items-end">
                            <span class="text-xs font-extrabold text-slate-200"><?= session()->get('u_fullname') ?></span>
                            <span class="text-[9px] font-bold text-cyan-400 uppercase tracking-widest mt-1">ADMINISTRATOR</span>
                        </div>
                        <div class="w-10 h-10 bg-slate-900 rounded-full border border-slate-800 overflow-hidden shadow-sm shadow-black/5">
                            <?php if(session()->get('u_photo')): ?>
                                <img src="<?= base_url('uploads/personnel/' . session()->get('u_photo')) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-slate-900 text-slate-400">
                                    <i data-lucide="user" class="w-5 h-5"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scroll Container -->
            <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 sm:p-6 lg:p-10 relative custom-scrollbar w-full max-w-full">
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

        function switchAcademicYear(year) {
            const url = new URL(window.location.href);
            url.searchParams.set('year', year);
            window.location.href = url.toString();
        }

        function getSwalColors() {
            return {
                bg: '#020617',
                text: '#cbd5e1'
            };
        }

        // Global SweetAlert2 notification handler for flash messages
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '<?= esc(session()->getFlashdata('success')) ?>',
                background: getSwalColors().bg,
                color: getSwalColors().text,
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'glass-card rounded-[2rem]' }
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: '<?= esc(session()->getFlashdata('error')) ?>',
                background: getSwalColors().bg,
                color: getSwalColors().text,
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'glass-card rounded-[2rem]' }
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <?php 
                // Format validation errors list
                $errText = implode('\n', array_map('esc', session()->getFlashdata('errors'))); 
            ?>
            Swal.fire({
                icon: 'error',
                title: 'กรุณาตรวจสอบข้อมูล!',
                html: '<div class="text-left text-xs text-rose-300 space-y-1"><?= str_replace('\n', '<br>', $errText) ?></div>',
                background: getSwalColors().bg,
                color: getSwalColors().text,
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'glass-card rounded-[2rem]' }
            });
        <?php endif; ?>
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
