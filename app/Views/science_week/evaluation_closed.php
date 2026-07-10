<?= $this->extend('science_week/layout/main') ?>

<?= $this->section('content') ?>
<!-- Particles Background Wrapper -->
<div class="relative min-h-[70vh] flex items-center justify-center py-12 px-4 overflow-hidden">
    <canvas id="particles-canvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

    <!-- Glowing Background Orbs -->
    <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-purple-600/10 rounded-full blur-[100px] animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-600/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>

    <div class="relative z-10 w-full max-w-lg text-center space-y-6">
        <!-- Icon Container with Sci-Fi Tech Ring -->
        <div class="inline-flex relative items-center justify-center">
            <!-- Animated Outer Ring -->
            <div class="absolute inset-0 rounded-full border border-dashed border-rose-500/30 animate-[spin_20s_linear_infinite]"></div>
            <!-- Pulse Glow -->
            <div class="absolute -inset-4 bg-rose-500/10 rounded-full blur-md animate-ping" style="animation-duration: 3s;"></div>
            
            <div class="relative p-6 rounded-full bg-slate-900/80 border border-rose-500/30 text-rose-455 shadow-2xl shadow-rose-950/40">
                <i data-lucide="lock" class="w-10 h-10 animate-bounce" style="animation-duration: 2s;"></i>
            </div>
        </div>

        <!-- Glassmorphism Card -->
        <div class="glass-card rounded-[2.5rem] p-8 md:p-10 bg-white/5 dark:bg-slate-900/60 border border-slate-200/10 dark:border-slate-800/80 shadow-2xl shadow-black/30 backdrop-blur-xl">
            <h2 class="text-xl sm:text-2xl font-black tracking-tight text-slate-800 dark:text-white uppercase tech-glow">
                ระบบแบบประเมินปิดชั่วคราว
            </h2>
            
            <div class="w-16 h-1 bg-gradient-to-r from-rose-500 to-orange-500 mx-auto my-4 rounded-full"></div>

            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                ขณะนี้ผู้จัดกิจกรรมได้ปิดระบบการทำแบบประเมินความพึงพอใจและเคลมเกียรติบัตรเข้าร่วมงานสัปดาห์วิทยาศาสตร์เรียบร้อยแล้ว หากท่านมีข้อสงสัยหรือปัญหาประการใด กรุณาติดต่อฝ่ายประสานงานผู้จัดกิจกรรมหลักโดยตรง
            </p>

            <!-- Decorative tech line specs -->
            <div class="flex items-center justify-center gap-1.5 mt-6 text-[10px] text-slate-500 font-mono">
                <span>STATUS: INACTIVE</span>
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                <span class="text-slate-600">|</span>
                <span>CODE: SW_EVAL_CLOSED</span>
            </div>
        </div>

        <!-- Action Button -->
        <div>
            <a href="<?= base_url('science-week') ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 dark:from-slate-800 dark:to-slate-900 border border-slate-700/50 dark:border-slate-700 text-slate-300 dark:text-slate-200 text-xs sm:text-sm font-bold shadow-lg hover:shadow-cyan-950/20 hover:border-cyan-500/50 hover:text-white transition-all transform hover:-translate-y-0.5">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                กลับสู่หน้าหลักกิจกรรม
            </a>
        </div>
    </div>
</div>

<script>
    // Particles Background
    const canvas = document.getElementById('particles-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let particles = [];
        const colors = ['rgba(239,68,68,0.2)', 'rgba(99,102,241,0.2)', 'rgba(168,85,247,0.15)'];
        function resize() { canvas.width = canvas.parentElement.offsetWidth; canvas.height = canvas.parentElement.offsetHeight; }
        window.addEventListener('resize', resize);
        resize();

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedX = Math.random() * 0.2 - 0.1;
                this.speedY = Math.random() * 0.2 - 0.1;
                this.color = colors[Math.floor(Math.random() * colors.length)];
            }
            update() {
                this.x += this.speedX; this.y += this.speedY;
                if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            }
            draw() { ctx.fillStyle = this.color; ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill(); }
        }
        for (let i = 0; i < 30; i++) particles.push(new Particle());
        function animate() { ctx.clearRect(0, 0, canvas.width, canvas.height); particles.forEach(p => { p.update(); p.draw(); }); requestAnimationFrame(animate); }
        animate();
    }
</script>
<?= $this->endSection() ?>
