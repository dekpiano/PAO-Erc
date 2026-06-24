<?= $this->extend('science_week/layout/main') ?>

<?= $this->section('content') ?>
<style>
    .page-container {
        background: transparent;
        color: #f1f5f9;
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
        background: rgba(15, 23, 42, 0.5) !important;
        backdrop-filter: blur(16px) !important;
        -webkit-backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(99, 102, 241, 0.2) !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4) !important;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        color: #f1f5f9 !important;
    }
    .glass-sci-card:hover {
        background: rgba(15, 23, 42, 0.65) !important;
        transform: translateY(-8px);
        box-shadow: 0 20px 50px -10px rgba(99, 102, 241, 0.2) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }

    /* Hover glow per STEAM color */
    .comp-card-purple:hover { border-color: var(--steam-science) !important; box-shadow: 0 20px 50px -10px rgba(168, 85, 247, 0.15); }
    .comp-card-cyan:hover, .comp-card-indigo:hover { border-color: var(--steam-tech) !important; box-shadow: 0 20px 50px -10px rgba(2, 132, 199, 0.15); }
    .comp-card-emerald:hover, .comp-card-green:hover { border-color: var(--steam-eng) !important; box-shadow: 0 20px 50px -10px rgba(16, 185, 129, 0.15); }
    .comp-card-amber:hover, .comp-card-yellow:hover { border-color: var(--steam-arts) !important; box-shadow: 0 20px 50px -10px rgba(234, 179, 8, 0.15); }
    .comp-card-rose:hover, .comp-card-pink:hover, .comp-card-red:hover { border-color: var(--steam-math) !important; box-shadow: 0 20px 50px -10px rgba(236, 72, 153, 0.15); }

    .sci-btn-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .sci-btn-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent, rgba(255,255,255,0.15));
        opacity: 0;
        transition: opacity 0.3s;
    }
    .sci-btn-card:hover {
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        transform: scale(1.02);
    }
    .sci-btn-card:hover::after { opacity: 1; }

    /* Icon animation on card hover */
    .icon-container { transition: all 0.4s cubic-bezier(0.4,0,0.2,1); }
    .glass-sci-card:hover .icon-container { transform: scale(1.15) rotate(5deg); }

    /* Shine sweep effect on cards */
    .glass-sci-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        transition: left 0.6s ease;
        z-index: 0;
    }
    .glass-sci-card:hover::before { left: 100%; }
    .glass-sci-card > * { position: relative; z-index: 1; }

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
    .page-enter-back { animation: fadeInDown 0.6s cubic-bezier(0.16,1,0.3,1) 0.1s both; }
    .page-enter-title { animation: fadeInDown 0.7s cubic-bezier(0.16,1,0.3,1) 0.2s both; }
    .page-enter-subtitle { animation: fadeInDown 0.7s cubic-bezier(0.16,1,0.3,1) 0.3s both; }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Card stagger animation */
    .card-anim {
        opacity: 0;
        transform: translateY(40px) scale(0.97);
        animation: cardFadeIn 0.7s cubic-bezier(0.16,1,0.3,1) both;
    }
    @keyframes cardFadeIn {
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

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

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
        
        <!-- Header -->
        <div class="text-center py-8 space-y-4">
            <a href="<?= base_url('science-week') ?>" class="page-enter-back inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-bold transition-colors text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับหน้าแรก
            </a>
            <h1 class="page-enter-title text-3xl sm:text-4xl font-black tracking-tight">
                <span class="gradient-text leading-normal">
                    เลือกประเภทการแข่งขันเพื่อสมัคร
                </span>
            </h1>
            <p class="page-enter-subtitle text-slate-400 text-sm font-semibold">เลือกหัวข้อประกวดหรือการแข่งขันตามแนวทาง STEAM ที่ท่านต้องการเข้าร่วมชิงรางวัล</p>
            <div class="rainbow-divider max-w-20 mx-auto"></div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <?php if (empty($competitions)): ?>
                <div class="col-span-full text-center py-12 text-slate-400 font-bold">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-4 text-slate-300"></i>
                    <p>ยังไม่มีข้อมูลประเภทการแข่งขันในระบบ</p>
                </div>
            <?php else: ?>
                <?php $delay = 400; foreach ($competitions as $comp): 
                    $color_class = 'comp-card-' . ($comp['comp_color'] ?: 'cyan');
                    
                    $isFull = false;
                    if (!empty($comp['comp_limit']) && $comp['comp_limit'] > 0) {
                        if ($comp['reg_count'] >= $comp['comp_limit']) {
                            $isFull = true;
                        }
                    }
                ?>
                    <div class="glass-sci-card rounded-3xl p-6 flex flex-col justify-between <?= $color_class ?> card-anim relative overflow-hidden" style="animation-delay: <?= $delay ?>ms">
                        <div class="space-y-4">
                            <div class="icon-container w-14 h-14 rounded-2xl bg-<?= esc($comp['comp_color'] ?: 'cyan') ?>-950/40 border border-<?= esc($comp['comp_color'] ?: 'cyan') ?>-800/30 flex items-center justify-center text-<?= esc($comp['comp_color'] ?: 'cyan') ?>-400 shadow-sm">
                                <i data-lucide="<?= esc($comp['comp_icon'] ?: 'award') ?>" class="w-7 h-7"></i>
                            </div>
                            <h3 class="text-lg font-black text-white"><?= esc($comp['comp_name']) ?></h3>
                            <p class="text-slate-400 text-xs leading-relaxed font-medium">
                                <?= esc($comp['comp_description']) ?>
                            </p>
                            
                            <!-- Quota Progress Indicator -->
                            <div class="pt-2">
                                <div class="flex justify-between items-center text-[10px] font-bold mb-1">
                                    <span class="text-slate-500">สถานะการรับสมัคร</span>
                                    <span class="<?= $isFull ? 'text-rose-400' : 'text-cyan-400' ?>">
                                        <?= $comp['reg_count'] ?> / <?= !empty($comp['comp_limit']) ? esc($comp['comp_limit']) : 'ไม่จำกัด' ?> ทีม
                                    </span>
                                </div>
                                <?php if (!empty($comp['comp_limit']) && $comp['comp_limit'] > 0): 
                                    $pct = min(100, ($comp['reg_count'] / $comp['comp_limit']) * 100);
                                ?>
                                    <div class="w-full bg-slate-950/60 rounded-full h-1.5 overflow-hidden border border-slate-900">
                                        <div class="h-full rounded-full transition-all <?= $isFull ? 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]' : 'bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.5)]' ?>" style="width: <?= $pct ?>%"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mt-6 space-y-4">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <span class="inline-block text-[10px] font-black text-<?= esc($comp['comp_color'] ?: 'cyan') ?>-400 bg-<?= esc($comp['comp_color'] ?: 'cyan') ?>-950/40 px-3 py-1 rounded-full border border-<?= esc($comp['comp_color'] ?: 'cyan') ?>-800/30 uppercase tracking-wider"><?= esc($comp['comp_level']) ?></span>
                                <?php if (!empty($comp['comp_rule_file']) || !empty($comp['comp_rule_link'])): ?>
                                    <a href="<?= !empty($comp['comp_rule_file']) ? base_url($comp['comp_rule_file']) : esc($comp['comp_rule_link']) ?>" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-400 hover:text-indigo-300 transition-colors">
                                        <i data-lucide="file-down" class="w-3.5 h-3.5"></i> ดาวน์โหลดกติกา
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($isFull): ?>
                                <button disabled class="w-full py-3 rounded-xl text-center text-slate-500 bg-slate-900/80 border border-slate-800 text-xs font-bold cursor-not-allowed flex items-center justify-center gap-2">
                                    <i data-lucide="minus-circle" class="w-4 h-4"></i> เต็มแล้ว (Full)
                                </button>
                            <?php else: ?>
                                <a href="<?= base_url('science-week/register/form?type=' . urlencode($comp['comp_name'])) ?>" class="w-full py-3 rounded-xl text-center text-white text-xs font-bold sci-btn-card flex items-center justify-center gap-2">
                                    <i data-lucide="plus-circle" class="w-4 h-4"></i> สมัครการแข่งขัน
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php $delay += 80; endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Particle Canvas
    const canvas = document.getElementById('particles-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let particles = [];
        const colors = ['rgba(102,126,234,0.3)', 'rgba(118,75,162,0.3)', 'rgba(240,147,251,0.2)', 'rgba(52,211,153,0.2)', 'rgba(251,191,36,0.2)'];
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
            draw() { ctx.fillStyle = this.color; ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill(); }
        }
        for (let i = 0; i < 50; i++) particles.push(new Particle());
        function animate() { ctx.clearRect(0, 0, canvas.width, canvas.height); particles.forEach(p => { p.update(); p.draw(); }); requestAnimationFrame(animate); }
        animate();
    }
</script>
<?= $this->endSection() ?>
