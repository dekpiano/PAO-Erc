<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LongWela | ระบบแอดมิน</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        'brand-dark': '#0f172a',
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer base {
            body {
                @apply bg-[#082f49] text-slate-100 min-h-screen font-sans;
                background-image: radial-gradient(circle at top right, #0c4a6e, #082f49 90%);
            }
        }
        @layer components {
            .glass-card {
                @apply bg-white/5 backdrop-blur-2xl border border-white/10 rounded-[1.5rem] p-6 shadow-xl;
            }
            .stat-card {
                @apply bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl p-6 transition-all hover:border-blue-500/30 hover:bg-white/10;
            }
        }
    </style>
</head>
