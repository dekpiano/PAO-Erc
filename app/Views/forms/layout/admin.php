<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'จัดการระบบแบบสอบถาม & เกียรติบัตร' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <style>
        body { font-family: 'Inter', 'Sarabun', sans-serif; background: #f8fafc; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.4); box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="antialiased text-slate-700 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0 z-20 sticky top-0">
        <div class="flex items-center gap-4">
            <a href="<?= base_url('staff/forms') ?>" class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                <i data-lucide="file-check-2" class="w-6 h-6"></i>
            </a>
            <div>
                <h1 class="text-lg font-black text-slate-900 leading-none">ระบบแบบสอบถาม & เกียรติบัตร</h1>
                <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest mt-1">Nakhon Sawan PAO</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="<?= base_url('staff') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs flex items-center gap-2 transition-all">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับ Staff Portal
            </a>
            <div class="hidden md:flex flex-col items-end">
                <span class="text-xs font-extrabold text-slate-900"><?= $fullname ?? 'เจ้าหน้าที่' ?></span>
                <span class="text-[9px] font-bold text-slate-400">ผู้ดูแลระบบ</span>
            </div>
        </div>
    </header>

    <!-- Main Body -->
    <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
        <?= $this->renderSection('content') ?>
    </main>

    <script>
        lucide.createIcons();
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: '<?= session()->getFlashdata('success') ?>', timer: 3000, showConfirmButton: false });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: '<?= session()->getFlashdata('error') ?>' });
        <?php endif; ?>
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
