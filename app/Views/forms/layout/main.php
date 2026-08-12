<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'แบบสอบถามออนไลน์ | อบจ.นครสวรรค์' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', 'Sarabun', sans-serif; background: #f1f5f9; }
        .form-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="antialiased text-slate-700 min-h-screen flex flex-col justify-between">

    <!-- Top Bar -->
    <header class="bg-white border-b border-slate-200 py-4 px-6 shadow-sm">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="<?= base_url('forms') ?>" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i data-lucide="file-check-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-base font-black text-slate-900 leading-tight">แบบสอบถามออนไลน์</h1>
                    <p class="text-[10px] font-bold text-indigo-600 tracking-wider">องค์การบริหารส่วนจังหวัดนครสวรรค์</p>
                </div>
            </a>
            <a href="<?= base_url() ?>" class="text-xs font-bold text-slate-500 hover:text-indigo-600 flex items-center gap-1.5 transition-colors">
                <i data-lucide="home" class="w-4 h-4"></i> หน้าหลักเว็บไซต์
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-10 px-4 md:px-6 max-w-4xl w-full mx-auto">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs font-bold text-slate-400">
        <p>© <?= date('Y') ?> กองการศึกษา ศาสนา และวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์</p>
    </footer>

    <script>
        lucide.createIcons();
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
