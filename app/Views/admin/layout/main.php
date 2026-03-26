<?= $this->include('admin/layout/_header') ?>
<body class="p-6 md:p-12">

    <div class="max-w-6xl mx-auto space-y-10 animate-[fadeIn_0.6s_ease-out]">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row justify-between items-center bg-white/5 backdrop-blur-xl border border-white/10 p-6 rounded-[1.5rem] shadow-2xl gap-8">
            <div class="flex items-center gap-8">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r from-sky-400 via-blue-400 to-indigo-400 tracking-tighter hover:scale-105 transition-transform cursor-default leading-none">
                        LONGWELA ADMIN
                    </h1>
                    <div class="hidden lg:block">
                        <p class="text-[10px] font-bold text-white/40 tracking-wider">กองการศึกษา ศาสนาและวัฒนธรรม</p>
                        <p class="text-[8px] text-white/20 uppercase tracking-widest">สังกัดองค์การบริหารส่วนจังหวัดนครสวรรค์</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex items-center gap-4 border-l border-white/10 pl-8">
                    <a href="<?= base_url('admin') ?>" class="text-sm font-bold flex items-center gap-2 transition-colors hover:text-blue-400 <?= url_is('admin') ? 'text-blue-400' : 'text-white/40' ?>">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> แดชบอร์ด
                    </a>
                    <a href="<?= base_url('admin/users') ?>" class="text-sm font-bold flex items-center gap-2 transition-colors hover:text-blue-400 <?= url_is('admin/users*') ? 'text-blue-400' : 'text-white/40' ?>">
                        <i data-lucide="users" class="w-4 h-4"></i> จัดการพนักงาน
                    </a>
                    <a href="<?= base_url('admin/settings') ?>" class="text-sm font-bold flex items-center gap-2 transition-colors hover:text-blue-400 <?= url_is('admin/settings') ? 'text-blue-400' : 'text-white/40' ?>">
                        <i data-lucide="settings" class="w-4 h-4"></i> ตั้งค่าระบบ
                    </a>
                </nav>
            </div>
            
            <div class="flex items-center gap-6 self-end md:self-center">
                <div class="text-right">
                    <?php 
                        $role_thai = [
                            'admin' => 'ผู้ดูแลระบบ',
                            'head' => 'หัวหน้าสายงาน',
                            'director' => 'ผู้อำนวยการ',
                            'user' => 'พนักงานทั่วไป'
                        ];
                        $u_role = session()->get('u_role');
                    ?>
                    <p class="text-[10px] text-white/30 uppercase tracking-wider mb-0.5"><?= $role_thai[$u_role] ?? 'พนักงาน' ?></p>
                    <p class="text-sm font-bold text-blue-400"><?= $fullname ?? 'Administrator' ?></p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?= base_url('attendance') ?>" 
                       class="p-2 text-white/20 hover:text-blue-400 transition-colors" title="หน้าลงเวลา">
                        <i data-lucide="clock-4" class="w-6 h-6"></i>
                    </a>
                    <a href="<?= base_url('auth/logout') ?>" 
                       class="p-2 text-white/20 hover:text-red-400 transition-colors" title="ออกจากระบบ">
                        <i data-lucide="log-out" class="w-6 h-6"></i>
                    </a>
                </div>
            </div>
        </header>

        <?= $this->renderSection('content') ?>

    </div>

    <script>
        lucide.createIcons();
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
