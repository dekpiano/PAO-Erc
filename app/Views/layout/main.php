<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'กองการศึกษา ศาสนา และวัฒนธรรม อบจ.นครสวรรค์' ?></title>
    
    <!-- Google Fonts: Inter & Sarabun -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #f59e0b;
        }

        body {
            font-family: 'Inter', 'Sarabun', sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }

        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        .premium-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -5px rgba(37, 99, 235, 0.3);
        }
    </style>
</head>
<body class="antialiased">

    <!-- Navigation -->
    <nav class="glass-nav fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="graduation-cap" class="w-6 h-6 sm:w-7 sm:h-7"></i>
                    </div>
                    <div>
                        <h1 class="text-[13px] min-[375px]:text-sm sm:text-lg font-extrabold text-blue-900 leading-none whitespace-nowrap">กองการศึกษา ศาสนา และวัฒนธรรม</h1>
                        <p class="text-[8px] sm:text-[10px] text-blue-600 font-semibold uppercase tracking-widest mt-1">อบจ.นครสวรรค์ | PAO NAKHONSAWAN</p>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="<?= base_url('/') ?>" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">หน้าแรก</a>
                    <a href="<?= base_url('news') ?>" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">ข่าวสาร</a>
                    <a href="#" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">เกี่ยวกับเรา</a>
                    <a href="#" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">ดาวน์โหลด</a>
                    <a href="<?= base_url('staff/attendance') ?>" class="px-5 py-2.5 bg-blue-600 text-white rounded-full text-sm font-bold premium-btn">
                        สำหรับบุคลากร
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="p-2 text-slate-600">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden fixed inset-0 z-[60] bg-white pt-24 px-6 overflow-y-auto">
        <div class="flex flex-col gap-6 animate-[fadeIn_0.3s_ease-out]">
            <a href="<?= base_url('/') ?>" class="text-xl font-bold text-slate-800 border-b border-slate-50 pb-4 flex items-center justify-between">
                หน้าแรก <i data-lucide="chevron-right" class="w-5 h-5 text-slate-300"></i>
            </a>
            <a href="<?= base_url('news') ?>" class="text-xl font-bold text-slate-800 border-b border-slate-50 pb-4 flex items-center justify-between">
                ข่าวสาร <i data-lucide="chevron-right" class="w-5 h-5 text-slate-300"></i>
            </a>
            <a href="#" class="text-xl font-bold text-slate-800 border-b border-slate-50 pb-4 flex items-center justify-between">
                เกี่ยวกับเรา <i data-lucide="chevron-right" class="w-5 h-5 text-slate-300"></i>
            </a>
            <a href="#" class="text-xl font-bold text-slate-800 border-b border-slate-50 pb-4 flex items-center justify-between">
                ดาวน์โหลด <i data-lucide="chevron-right" class="w-5 h-5 text-slate-300"></i>
            </a>
            <a href="<?= base_url('staff/attendance') ?>" class="w-full py-5 bg-blue-600 text-white rounded-[2rem] text-center font-black shadow-xl shadow-blue-100 flex items-center justify-center gap-3 mt-4">
                <i data-lucide="user" class="w-6 h-6"></i> สำหรับบุคลากร
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="pt-20">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <i data-lucide="graduation-cap" class="w-8 h-8 text-blue-400"></i>
                        <h2 class="text-xl font-bold">กองการศึกษา ศาสนา และวัฒนธรรม อบจ.นครสวรรค์</h2>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-md">
                        มุ่งมั่นพัฒนาการศึกษา ยกระดับคุณภาพชีวิต วัฒนธรรม และศาสนา เพื่อความเจริญรุ่งเรืองของท้องถิ่นอย่างยั่งยืน
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-6 text-slate-200">เข้าถึงข้อมูล</h3>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="<?= base_url('news?category=ข่าวประชาสัมพันธ์') ?>" class="hover:text-white transition-colors">ข่าวประชาสัมพันธ์</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">ผลการจัดการศึกษา</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">ดาวน์โหลดแบบฟอร์ม</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">ติดต่อเรา</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-6 text-slate-200">สำหรับเจ้าหน้าที่</h3>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="<?= base_url('staff/attendance') ?>" class="hover:text-white transition-colors">ระบบลงชื่อเข้างาน</a></li>
                        <li><a href="<?= base_url('admin') ?>" class="hover:text-white transition-colors">ระบบจัดการหลังบ้าน</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8 text-center text-xs text-slate-500 font-medium">
                &copy; <?= date('Y') ?> กองการศึกษา ศาสนา และวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์ (อบจ.นว)
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Mobile Menu Toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuBtn && mobileMenu) {
            const menuBtnIcon = menuBtn.querySelector('i');
            
            menuBtn.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden'); // Prevent scrolling
                
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
    </script>
</body>
</html>
