<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบบุคลากร | อบจ.นครสวรรค์</title>
    
    <!-- Google Fonts: Inter & Sarabun -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Google Sign-In -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>

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

        .glass-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 antialiased">

    <div class="w-full max-w-lg transition-all duration-700 animate-[fadeIn_0.8s_ease-out]">
        <div class="login-card rounded-[2.5rem] p-12 md:p-16 text-center">
            
            <!-- Logo & Brand -->
            <div class="mb-12">
                <div class="w-20 h-20 bg-blue-600 rounded-3xl flex items-center justify-center text-white shadow-xl shadow-blue-200 mx-auto mb-6 transform -rotate-6">
                    <i data-lucide="shield-check" class="w-10 h-10"></i>
                </div>
                <h1 class="text-3xl font-black text-slate-900 mb-2 tracking-tight uppercase">Portal Login</h1>
                <p class="text-blue-600/80 font-bold text-sm tracking-widest uppercase mb-1">กองการศึกษา ศาสนา และวัฒนธรรม</p>
                <p class="text-slate-400 text-xs font-medium italic">องค์การบริหารส่วนจังหวัดนครสวรรค์</p>
            </div>

            <div id="error-msg" class="hidden">
                <?php echo session()->getFlashdata('error'); ?>
            </div>

            <!-- Login Content -->
            <div class="space-y-10">
                <div class="py-6 border-y border-slate-200/60">
                    <p class="text-slate-600 font-semibold mb-2">ยินดีต้อนรับสู่ระบบบริหารงานบุคคล</p>
                    <p class="text-slate-400 text-xs">กรุณาเข้าใช้งานด้วยบัญชี Google ขององค์กรเพื่อความปลอดภัย</p>
                </div>

                <!-- Google Sign-In Button -->
                <div class="flex justify-center scale-[1.3] py-4">
                    <div id="g_id_onload"
                        data-client_id="466841507075-cb297mr52ffija6jsif1b080mjus0k11.apps.googleusercontent.com"
                        data-context="signin"
                        data-ux_mode="popup"
                        data-callback="handleCredentialResponse"
                        data-auto_prompt="false">
                    </div>
                    <div class="g_id_signin"
                        data-type="standard"
                        data-shape="pill"
                        data-theme="outline"
                        data-text="signin_with"
                        data-size="large"
                        data-logo_alignment="left">
                    </div>
                </div>

                <div class="pt-8 text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em]">
                    PAO NAKHON SAWAN - SECURE ACCESS
                </div>
            </div>
        </div>

        <!-- Meta Links -->
        <div class="mt-8 flex justify-center gap-8 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
            <a href="<?= base_url() ?>" class="hover:text-blue-600 flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> กลับสู่หน้าประชาสัมพันธ์
            </a>
            <span class="opacity-20">|</span>
            <a href="#" class="hover:text-blue-600">ติดต่อแอดมิน</a>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function handleCredentialResponse(response) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= base_url('auth/googleLogin') ?>';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'credential';
            input.value = response.credential;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        const errorMsg = document.getElementById('error-msg').textContent.trim();
        if (errorMsg) {
            Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถเข้าสู่ระบบได้',
                text: errorMsg,
                background: '#ffffff',
                color: '#1e293b',
                confirmButtonColor: '#2563eb',
                iconColor: '#ef4444',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'px-8 py-3 rounded-xl font-bold uppercase text-xs tracking-widest'
                }
            });
        }
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
