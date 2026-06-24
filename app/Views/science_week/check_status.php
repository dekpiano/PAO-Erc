<?= $this->extend('science_week/layout/main') ?>

<?= $this->section('content') ?>
<style>
    .page-container {
        background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 40%, #f8fafc 70%, #f0f4ff 100%);
        color: #1e293b;
        flex: 1;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    #particles-canvas {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        pointer-events: none;
        z-index: 0;
    }

    .glass-sci-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(99, 102, 241, 0.12);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.04);
    }

    .neon-input {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #0f172a;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .neon-input:focus {
        border-color: var(--steam-tech);
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.08), 0 0 20px rgba(2, 132, 199, 0.1);
        background: #ffffff;
        outline: none;
        transform: scale(1.01);
    }

    .neon-btn-search {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .neon-btn-search::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent, rgba(255,255,255,0.15));
        opacity: 0;
        transition: opacity 0.3s;
    }
    .neon-btn-search:hover {
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.5);
        transform: translateY(-3px) scale(1.02);
    }
    .neon-btn-search:hover::after { opacity: 1; }

    .result-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }
    .result-card:hover {
        border-color: var(--steam-tech);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.1);
        transform: translateY(-5px);
    }

    .rainbow-divider {
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c, #fda085);
        background-size: 200% auto;
        border-radius: 4px;
        animation: rainbowSlide 4s linear infinite;
    }
    @keyframes rainbowSlide {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    /* Entrance animations */
    .page-enter-badge { animation: fadeInDown 0.7s cubic-bezier(0.16,1,0.3,1) 0.1s both; }
    .page-enter-title { animation: fadeInDown 0.8s cubic-bezier(0.16,1,0.3,1) 0.2s both; }
    .page-enter-subtitle { animation: fadeInDown 0.8s cubic-bezier(0.16,1,0.3,1) 0.35s both; }
    .page-enter-card { animation: fadeInUp 0.9s cubic-bezier(0.16,1,0.3,1) 0.4s both; }
    .page-enter-results { animation: fadeInUp 0.9s cubic-bezier(0.16,1,0.3,1) 0.6s both; }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Result card stagger */
    .result-card-anim {
        animation: resultSlideIn 0.6s cubic-bezier(0.16,1,0.3,1) both;
    }

    @keyframes resultSlideIn {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 30%, #f093fb 60%, #f5576c 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

<div class="page-container pt-8 pb-20 relative">
    <canvas id="particles-canvas"></canvas>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 relative z-10">
        
        <!-- Header -->
        <div class="text-center py-8 space-y-4">
            <a href="<?= base_url('science-week') ?>" class="page-enter-badge inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-bold transition-colors text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับหน้าแรก
            </a>
            <h1 class="page-enter-title text-3xl sm:text-4xl font-black tracking-tight">
                <span class="gradient-text leading-normal">
                    ตรวจสอบสถานะการสมัครแข่งขัน
                </span>
            </h1>
            <p class="page-enter-subtitle text-slate-500 text-sm font-semibold">พิมพ์รหัสใบสมัคร ชื่อโรงเรียน หรือชื่อทีม เพื่อค้นหาข้อมูลการสมัครของท่าน</p>
            <div class="rainbow-divider max-w-20 mx-auto"></div>
        </div>

        <!-- Search Bar Card -->
        <div class="glass-sci-card rounded-3xl p-6 sm:p-8 mb-10 shadow-lg relative overflow-hidden page-enter-card">
            <div class="rainbow-divider absolute top-0 left-0 right-0"></div>
            <form method="GET" action="<?= base_url('science-week/check-status/search') ?>" class="flex flex-col sm:flex-row gap-4 pt-4">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <i data-lucide="search" class="w-5 h-5 text-indigo-500"></i>
                    </span>
                    <input type="text" name="search" required value="<?= esc($search) ?>" placeholder="รหัสใบสมัคร (เช่น SCI-2026-00001) หรือ ชื่อโรงเรียน..." class="neon-input w-full pl-12 pr-4 py-4 rounded-2xl text-slate-900 font-medium">
                </div>
                <button type="submit" class="px-8 py-4 rounded-2xl text-white font-bold neon-btn-search flex items-center justify-center gap-2 shrink-0">
                    <i data-lucide="compass" class="w-5 h-5 animate-pulse"></i> ค้นหาประวัติ
                </button>
            </form>
        </div>

        <!-- Search Results -->
        <?php if ($results !== null): ?>
            <div class="space-y-6 page-enter-results">
                <h3 class="text-lg font-black text-slate-700 flex items-center gap-2 pl-2">
                    <i data-lucide="list-filter" class="w-5 h-5 text-indigo-600"></i> ผลการค้นหาพบ (<?= count($results) ?> รายการ)
                </h3>

                <?php if (empty($results)): ?>
                    <div class="glass-sci-card rounded-3xl p-12 text-center text-slate-500">
                        <i data-lucide="search-x" class="w-14 h-14 mx-auto text-indigo-400 mb-4 animate-pulse"></i>
                        <p class="font-bold text-lg mb-1">ไม่พบข้อมูลการลงทะเบียน</p>
                        <p class="text-sm text-slate-400">สำหรับคำค้นหา "<strong><?= esc($search) ?></strong>" — กรุณาตรวจสอบตัวอักษรแล้วลองใหม่อีกครั้ง</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-4">
                        <?php $resultDelay = 0; foreach ($results as $reg): ?>
                            <div class="result-card rounded-2xl p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 shadow-sm result-card-anim" style="animation-delay: <?= $resultDelay ?>ms">
                                <div class="space-y-2 min-w-0">
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <span class="text-sm font-black text-indigo-600 font-mono tracking-wider"><?= $reg['reg_code'] ?></span>
                                        <?php if ($reg['reg_status'] === 'approved'): ?>
                                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 border border-emerald-300 text-emerald-700 text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                                <i data-lucide="check-circle-2" class="w-3 h-3"></i> อนุมัติสิทธิ์แล้ว
                                            </span>
                                        <?php elseif ($reg['reg_status'] === 'rejected'): ?>
                                            <span class="px-2.5 py-0.5 rounded-full bg-rose-100 border border-rose-300 text-rose-700 text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                                <i data-lucide="x-circle" class="w-3 h-3"></i> ปฏิเสธสิทธิ์
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-0.5 rounded-full bg-amber-100 border border-amber-300 text-amber-700 text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3 h-3 animate-pulse"></i> รอการตรวจสอบ
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <h4 class="text-lg font-black text-slate-800 truncate"><?= esc($reg['reg_competition_type']) ?></h4>
                                    <p class="text-xs text-slate-500 font-semibold">
                                        โรงเรียน: <span class="text-slate-700 font-bold"><?= esc($reg['reg_school_name']) ?></span> 
                                        <?= $reg['reg_team_name'] ? ' | ทีม: <span class="text-slate-700 font-bold">'.esc($reg['reg_team_name']).'</span>' : '' ?>
                                    </p>
                                </div>
                                <div class="shrink-0 w-full sm:w-auto flex flex-col gap-2">
                                    <a href="<?= base_url('science-week/success/' . $reg['reg_code']) ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 hover:from-indigo-100 hover:to-purple-100 text-indigo-700 border border-indigo-200 transition-all font-bold text-xs uppercase tracking-wider hover:shadow-md hover:-translate-y-0.5">
                                        <i data-lucide="ticket" class="w-4 h-4"></i> รายละเอียดตั๋ว / พิมพ์บัตร
                                    </a>
                                    <?php if ($reg['reg_status'] === 'approved'): ?>
                                        <?php $members = json_decode($reg['reg_members'], true) ?: []; ?>
                                        <?php foreach ($members as $m): ?>
                                            <a href="<?= base_url('science-week/certificate/download/competition/' . $reg['reg_code']) ?>?name=<?= urlencode($m) ?>&preview=1" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-600 hover:text-white text-emerald-600 border border-emerald-500/20 transition-all font-bold text-[10px]">
                                                <i data-lucide="award" class="w-3.5 h-3.5"></i> เกียรติบัตร: <?= esc($m) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php $resultDelay += 100; endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    // Particle Canvas
    const canvas = document.getElementById('particles-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let particles = [];
        const colors = ['rgba(102,126,234,0.3)', 'rgba(118,75,162,0.3)', 'rgba(240,147,251,0.2)', 'rgba(52,211,153,0.2)'];
        function resize() { canvas.width = canvas.parentElement.offsetWidth; canvas.height = canvas.parentElement.offsetHeight; }
        window.addEventListener('resize', resize);
        resize();

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedX = Math.random() * 0.3 - 0.15;
                this.speedY = Math.random() * 0.3 - 0.15;
                this.color = colors[Math.floor(Math.random() * colors.length)];
            }
            update() {
                this.x += this.speedX; this.y += this.speedY;
                if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            }
            draw() {
                ctx.fillStyle = this.color;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }
        for (let i = 0; i < 40; i++) particles.push(new Particle());
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => { p.update(); p.draw(); });
            requestAnimationFrame(animate);
        }
        animate();
    }
</script>
<?= $this->endSection() ?>
