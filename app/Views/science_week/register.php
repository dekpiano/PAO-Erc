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
        border-color: var(--hover-border, rgba(6, 182, 212, 0.3)) !important;
    }
    .glass-sci-card h3 { color: #ffffff !important; }
    .glass-sci-card p { color: #cbd5e1 !important; }
    .glass-sci-card:hover p { color: #ffffff !important; }

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
                    $colorName = $comp['comp_color'] ?: 'cyan';
                    $colorHex = '#22d3ee';
                    if ($colorName === 'emerald') $colorHex = '#34d399';
                    elseif ($colorName === 'purple') $colorHex = '#c084fc';
                    elseif ($colorName === 'yellow') $colorHex = '#facc15';
                    elseif ($colorName === 'rose') $colorHex = '#f43f5e';
                    elseif ($colorName === 'indigo') $colorHex = '#818cf8';
                    
                    $isFull = false;
                    if (!empty($comp['comp_limit']) && $comp['comp_limit'] > 0) {
                        if ($comp['reg_count'] >= $comp['comp_limit']) {
                            $isFull = true;
                        }
                    }
                ?>
                    <div class="glass-sci-card rounded-3xl p-6 flex flex-col justify-between card-anim relative overflow-hidden group" style="animation-delay: <?= $delay ?>ms; --hover-border: <?= $colorHex ?>;">
                        <div class="space-y-4">
                            <div class="icon-container w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm transition-transform group-hover:scale-110" style="background-color: <?= $colorHex ?>20; border: 1px solid <?= $colorHex ?>35; color: <?= $colorHex ?>;">
                                <i data-lucide="<?= esc($comp['comp_icon'] ?: 'award') ?>" class="w-7 h-7"></i>
                            </div>
                            <h3 class="text-xl font-black text-white"><?= esc($comp['comp_name']) ?></h3>
                            <p class="text-slate-300 text-xs leading-relaxed font-semibold">
                                <?= esc($comp['comp_description']) ?>
                            </p>
                            
                            <!-- Quota Progress Indicator -->
                            <div class="pt-2 space-y-2">
                                <?php
                                $levelLimits = [];
                                if (!empty($comp['comp_level_limits'])) {
                                    $levelLimits = json_decode($comp['comp_level_limits'], true) ?: [];
                                }
                                ?>
                                <?php if (!empty($levelLimits)): ?>
                                    <span class="text-slate-400 text-[10px] font-bold block mb-1">สถานะการรับสมัครแบ่งตามระดับชั้น:</span>
                                    <?php 
                                    $allFull = true;
                                    foreach ($levelLimits as $lvl): 
                                        $db = \Config\Database::connect();
                                        $activeCount = $db->table('Tb_ScienceWeek_Registrations')
                                            ->where('reg_competition_type', $comp['comp_name'])
                                            ->where('reg_level', $lvl['level'])
                                            ->where('reg_status !=', 'rejected')
                                            ->countAllResults();
                                        
                                        $lvlLimit = (int)$lvl['limit'];
                                        $lvlFull = $lvlLimit > 0 && $activeCount >= $lvlLimit;
                                        if (!$lvlFull) {
                                            $allFull = false;
                                        }
                                        
                                        $limitText = $lvlLimit > 0 ? "{$lvlLimit} ทีม" : "ไม่จำกัด";
                                        $pct = $lvlLimit > 0 ? min(100, ($activeCount / $lvlLimit) * 100) : 0;
                                        $barColor = $lvlFull ? '#f43f5e' : $colorHex;
                                    ?>
                                        <div class="space-y-1">
                                            <div class="flex justify-between items-center text-[10px] font-medium">
                                                <span class="text-slate-300 font-semibold"><?= esc($lvl['level']) ?></span>
                                                <span class="font-bold" style="color: <?= $barColor ?>;">
                                                    <?= $activeCount ?> / <?= $limitText ?>
                                                </span>
                                            </div>
                                            <?php if ($lvlLimit > 0): ?>
                                                <div class="w-full bg-slate-950/60 rounded-full h-1.5 overflow-hidden border border-slate-900">
                                                    <div class="h-full rounded-full transition-all" style="width: <?= $pct ?>%; background-color: <?= $barColor ?>; box-shadow: 0 0 8px <?= $barColor ?>80;"></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php $isFull = $allFull; ?>
                                <?php else: ?>
                                    <div class="flex justify-between items-center text-[10px] font-bold mb-1">
                                        <span class="text-slate-400">สถานะการรับสมัคร</span>
                                        <span class="font-bold" style="color: <?= $isFull ? '#f43f5e' : $colorHex ?>;">
                                            <?= $comp['reg_count'] ?> / <?= !empty($comp['comp_limit']) ? esc($comp['comp_limit']) : 'ไม่จำกัด' ?> ทีม
                                        </span>
                                    </div>
                                    <?php if (!empty($comp['comp_limit']) && $comp['comp_limit'] > 0): 
                                        $pct = min(100, ($comp['reg_count'] / $comp['comp_limit']) * 100);
                                        $barColor = $isFull ? '#f43f5e' : $colorHex;
                                    ?>
                                        <div class="w-full bg-slate-950/60 rounded-full h-1.5 overflow-hidden border border-slate-900">
                                            <div class="h-full rounded-full transition-all" style="width: <?= $pct ?>%; background-color: <?= $barColor ?>; box-shadow: 0 0 8px <?= $barColor ?>80;"></div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mt-6 space-y-4">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <span class="inline-block text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider" style="color: <?= $colorHex ?>; background-color: <?= $colorHex ?>15; border: 1px solid <?= $colorHex ?>25;"><?= esc($comp['comp_level']) ?></span>
                            </div>

                            <!-- Show Time Limits in Thai BE Format -->
                            <?php if (!empty($comp['comp_open_time']) || !empty($comp['comp_close_time'])): ?>
                                <div class="mt-3 p-3 bg-slate-950/20 rounded-xl border border-slate-800/30 text-[10px] space-y-1 text-slate-400">
                                    <?php if (!empty($comp['comp_open_time'])): ?>
                                        <div class="flex justify-between">
                                            <span>เริ่มรับสมัคร:</span>
                                            <span class="font-bold text-slate-300"><?= date('d/m/Y H:i', strtotime($comp['comp_open_time'] . ' +543 years')) ?> น. (พ.ศ.)</span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($comp['comp_close_time'])): ?>
                                        <div class="flex justify-between">
                                            <span>สิ้นสุดรับสมัคร:</span>
                                            <span class="font-bold text-rose-400"><?= date('d/m/Y H:i', strtotime($comp['comp_close_time'] . ' +543 years')) ?> น. (พ.ศ.)</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php 
                            $now = date('Y-m-d H:i:s');
                            $isTimeOpen = true;
                            $timeMsg = '';
                            if (!empty($comp['comp_open_time']) && $now < $comp['comp_open_time']) {
                                $isTimeOpen = false;
                                $timeMsg = 'ยังไม่เปิดรับสมัคร (เริ่ม ' . date('d/m/Y H:i', strtotime($comp['comp_open_time'] . ' +543 years')) . ')';
                            }
                            elseif (!empty($comp['comp_close_time']) && $now > $comp['comp_close_time']) {
                                $isTimeOpen = false;
                                $timeMsg = 'หมดเขตรับสมัครแล้ว';
                            }
                            elseif (($comp['comp_status'] ?? 'open') === 'closed') {
                                $isTimeOpen = false;
                                $timeMsg = 'ปิดรับสมัครชั่วคราว';
                            }
                            ?>

                            <div class="pt-2 space-y-3">
                                <?php if (!empty($comp['comp_rule_file']) || !empty($comp['comp_rule_link'])): ?>
                                    <a href="<?= !empty($comp['comp_rule_file']) ? base_url($comp['comp_rule_file']) : esc($comp['comp_rule_link']) ?>" target="_blank" class="w-full py-2.5 rounded-xl text-center text-cyan-300 bg-cyan-950/40 border border-cyan-800/40 hover:border-cyan-400 text-xs font-black flex items-center justify-center gap-2 transition-all hover:shadow-[0_0_15px_rgba(34,211,238,0.25)]">
                                        <i data-lucide="file-text" class="w-4 h-4 animate-pulse"></i> ดาวน์โหลดกติกาการแข่งขัน
                                    </a>
                                <?php endif; ?>

                                <?php if (!$registration_open): ?>
                                    <button disabled class="w-full py-3 rounded-xl text-center text-rose-300 bg-rose-950/20 border border-rose-900/35 text-xs font-bold cursor-not-allowed flex items-center justify-center gap-2">
                                        <i data-lucide="lock" class="w-4 h-4 text-rose-450 animate-pulse"></i> ปิดรับสมัครระบบหลักแล้ว
                                    </button>
                                <?php elseif (!$isTimeOpen): ?>
                                    <button disabled class="w-full py-3 rounded-xl text-center text-rose-300 bg-rose-950/20 border border-rose-900/35 text-xs font-bold cursor-not-allowed flex items-center justify-center gap-2" title="<?= esc($timeMsg) ?>">
                                        <i data-lucide="calendar-x" class="w-4 h-4 text-rose-450"></i> <?= esc($timeMsg) ?>
                                    </button>
                                <?php elseif ($isFull): ?>
                                    <button disabled class="w-full py-3 rounded-xl text-center text-slate-500 bg-slate-900/80 border border-slate-800 text-xs font-bold cursor-not-allowed flex items-center justify-center gap-2">
                                        <i data-lucide="minus-circle" class="w-4 h-4"></i> เต็มแล้ว (Full)
                                    </button>
                                <?php else: ?>
                                    <a href="<?= base_url('science-week/register/form?type=' . urlencode($comp['comp_name'])) ?>" class="w-full py-3 rounded-xl text-center text-white text-xs font-bold sci-btn-card flex items-center justify-center gap-2" style="background: linear-gradient(135deg, <?= $colorHex ?> 0%, #4f46e5 100%); box-shadow: 0 4px 15px <?= $colorHex ?>30;">
                                        <i data-lucide="plus-circle" class="w-4 h-4"></i> สมัครการแข่งขัน
                                    </a>
                                <?php endif; ?>
                            </div>
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
