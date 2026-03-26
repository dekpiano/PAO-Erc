<?= $this->include('staff/layout/_header') ?>
<body class="flex items-center justify-center p-4">

    <main class="w-full max-w-xl animate-[fadeIn_0.8s_ease-out]">
        <div class="glass-card text-center">
            <?= $this->include('staff/layout/_navbar') ?>
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
    <?= $this->renderSection('scripts') ?>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
