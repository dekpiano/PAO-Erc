<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกระบบการเข้าใช้งาน | อบจ.นครสวรรค์</title>
    
    <!-- Google Fonts: Inter & Sarabun -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', 'Sarabun', sans-serif;
            background-color: #f3f4f6;
            background-image: 
                radial-gradient(at 0% 0%, hsla(217,100%,94%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(217,100%,92%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(217,100%,94%,1) 0, transparent 50%);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }

        .group-hover-scale {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .group:hover .group-hover-scale {
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 antialiased">
    
    <div class="w-full max-w-2xl transition-all duration-700 animate-[fadeIn_0.8s_ease-out]">
        <div class="login-card rounded-[2.5rem] p-12 md:p-16 text-center">
            
            <div class="mb-12">
                <div class="w-20 h-20 bg-emerald-500 rounded-3xl flex items-center justify-center text-white shadow-xl shadow-emerald-200 mx-auto mb-6">
                    <i data-lucide="layers" class="w-10 h-10"></i>
                </div>
                <h1 class="text-3xl font-black text-slate-900 mb-2 tracking-tight uppercase">เลือกพื้นที่เข้าใช้งาน</h1>
                <p class="text-emerald-600/80 font-bold text-sm tracking-widest uppercase mb-1">SELECT YOUR WORKSPACE</p>
                <p class="text-slate-400 text-xs font-medium italic mt-2">บัญชีของคุณมีสิทธิ์เข้าถึงหลายระบบ กรุณาเลือกเป้าหมาย</p>
            </div>

            <!-- Portal Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Admin Portal -->
                <a href="<?= base_url('admin') ?>" class="group bg-white p-8 rounded-[2rem] border border-slate-100 shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:border-red-200 transition-all duration-300 text-left relative overflow-hidden flex flex-col items-center text-center">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-red-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover-scale">
                        <i data-lucide="shield-alert" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2">หน้าผู้ดูแลระบบ</h3>
                    <p class="text-xs text-slate-400 font-medium">จัดการบุคลากร, ระบบหลังบ้าน, ตั้งค่าองค์กร</p>
                    <span class="mt-6 px-4 py-1.5 bg-red-50 text-red-500 text-[10px] font-black uppercase tracking-widest rounded-full group-hover:bg-red-500 group-hover:text-white transition-colors">
                        Admin Portal
                    </span>
                </a>

                <!-- Staff Portal -->
                <a href="<?= base_url('staff') ?>" class="group bg-white p-8 rounded-[2rem] border border-slate-100 shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:border-blue-200 transition-all duration-300 text-left relative overflow-hidden flex flex-col items-center text-center">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover-scale">
                        <i data-lucide="user" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2">หน้าบุคลากร</h3>
                    <p class="text-xs text-slate-400 font-medium">ทำเนียบ, ลงเวลาปฏิบัติงาน, ข่าวกิจกรรม</p>
                    <span class="mt-6 px-4 py-1.5 bg-blue-50 text-blue-500 text-[10px] font-black uppercase tracking-widest rounded-full group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        Staff Portal
                    </span>
                </a>

            </div>

        </div>

        <!-- Meta Links -->
        <div class="mt-8 flex justify-center gap-8 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
            <a href="<?= base_url('auth/logout') ?>" class="hover:text-rose-500 flex items-center gap-2">
                <i data-lucide="log-out" class="w-3.5 h-3.5"></i> ออกจากระบบ
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
