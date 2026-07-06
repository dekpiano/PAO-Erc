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
        background: rgba(15, 23, 42, 0.65) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(99, 102, 241, 0.25) !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3) !important;
        position: relative;
        color: #f1f5f9 !important;
    }

    .neon-input {
        background: rgba(8, 12, 24, 0.7) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        color: #ffffff !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .neon-input:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
        outline: none;
    }

    .neon-btn-search {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.25);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .neon-btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(99, 102, 241, 0.35);
    }

    .gradient-text {
        background: linear-gradient(135deg, #818cf8 0%, #6366f1 50%, #4f46e5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .rainbow-divider {
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899, #f59e0b, #10b981);
        background-size: 200% auto;
        border-radius: 4px;
        animation: rainbowSlide 4s linear infinite;
    }
    @keyframes rainbowSlide {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    /* Staggered entry animation */
    .result-section {
        animation: sectionFadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    @keyframes sectionFadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="page-container pt-8 pb-20 relative">
    <canvas id="particles-canvas"></canvas>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 relative z-10">
        
        <!-- Header -->
        <div class="text-center py-8 space-y-4">
            <a href="<?= base_url('science-week') ?>" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-bold transition-colors text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับหน้าแรก
            </a>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight">
                <span class="gradient-text leading-normal">
                    ตรวจสอบสถานะการสมัครแข่งขัน
                </span>
            </h1>
            <p class="text-slate-400 text-sm font-semibold">ค้นหาข้อมูลใบสมัครของคุณเพื่อตรวจสอบสถานะการอนุมัติเข้าร่วมแข่งขัน</p>
            <div class="rainbow-divider max-w-20 mx-auto"></div>
        </div>

        <!-- Search Card -->
        <div class="glass-sci-card rounded-3xl p-6 sm:p-8 mb-10 shadow-lg relative overflow-hidden">
            <div class="rainbow-divider absolute top-0 left-0 right-0"></div>
            
            <form method="GET" action="<?= base_url('science-week/check-status') ?>" class="pt-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <i data-lucide="search" class="w-5 h-5 text-indigo-400"></i>
                        </span>
                        <input type="text" name="search" value="<?= esc($search) ?>" required placeholder="ระบุ ชื่อโรงเรียน, ชื่อทีม, เบอร์โทรศัพท์ หรือรหัสใบสมัคร..." class="neon-input w-full pl-12 pr-4 py-4 rounded-2xl text-white font-medium">
                    </div>
                    <button type="submit" class="sm:w-44 py-4 rounded-2xl text-white font-bold neon-btn-search flex items-center justify-center gap-2 shrink-0">
                        <i data-lucide="search" class="w-5 h-5"></i> ค้นหาใบสมัคร
                    </button>
                </div>
                <p class="text-[11px] text-slate-400 mt-3 flex items-center gap-1 font-semibold">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-indigo-400"></i> 
                    คำแนะนำ: สามารถค้นหาด้วยข้อมูลบางส่วน เช่น "สวนกุหลาบ", "089xxxxxxx" หรือรหัสใบสมัคร "SCI-xxxxx"
                </p>
            </form>
        <!-- Explanation Info Box for Reserve Teams -->
        <div class="glass-sci-card rounded-3xl p-5 mb-8 border border-blue-500/20 bg-blue-500/5 text-xs text-slate-350 flex items-start gap-3 shadow-md">
            <i data-lucide="info" class="w-5 h-5 text-blue-400 shrink-0 mt-0.5 animate-pulse"></i>
            <div>
                <strong class="text-white text-sm block mb-1">💡 คำแนะนำเกี่ยวกับสถานะผู้เข้าแข่งขัน (ตัวจริง / ตัวสำรอง)</strong>
                <ul class="list-disc pl-4 space-y-1.5 text-slate-400 font-medium">
                    <li><span class="text-emerald-400 font-black">ทีมจริง:</span> เป็นทีมที่มีสิทธิ์เข้าร่วมกิจกรรมประกวด/แข่งขันในวันจัดงานอย่างเป็นทางการ</li>
                    <li><span class="text-blue-400 font-black">ตัวสำรอง:</span> เป็นทีมลำดับถัดไปที่สมัครเข้าแข่งขันเกินโควตาจำกัดของสถาบันศึกษา โดยจะได้รับสิทธิ์เข้าแข่งในกรณีที่มีทีมตัวจริงสละสิทธิ์ หรือตามเงื่อนไขที่คณะกรรมการกำหนดเพิ่มเติม</li>
                </ul>
            </div>
        </div>

        <!-- Results -->
        <?php if ($search !== null): ?>
            <div class="result-section space-y-6">
                <h3 class="text-lg font-bold text-indigo-300 border-b border-slate-800/80 pb-3 flex items-center gap-2">
                    <i data-lucide="list-filter" class="w-5 h-5"></i>
                    ผลการค้นหาสำหรับ: "<span class="text-white font-black"><?= esc($search) ?></span>"
                    <span class="text-xs text-slate-450 font-normal">(พบ <?= count($registrations) ?> รายการ)</span>
                </h3>

                <?php if (empty($registrations)): ?>
                    <div class="glass-sci-card rounded-3xl p-12 text-center text-slate-400 border border-dashed border-indigo-500/20">
                        <i data-lucide="search-x" class="w-16 h-16 mx-auto text-indigo-400/40 mb-4 animate-pulse"></i>
                        <p class="font-bold text-lg mb-1 text-white">ไม่พบข้อมูลการลงทะเบียนสมัคร</p>
                        <p class="text-xs text-slate-450">กรุณาตรวจสอบความถูกต้องของข้อมูล หรือค้นหาด้วยคีย์เวิร์ดอื่น</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($registrations as $reg): ?>
                            <div class="glass-sci-card rounded-3xl p-6 border border-indigo-500/10 space-y-4 transition-all hover:border-indigo-500/30">
                                
                                <!-- Header -->
                                <div class="flex flex-wrap justify-between items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-black text-indigo-400 font-mono tracking-wider bg-indigo-500/5 px-2.5 py-1.5 rounded-xl border border-indigo-500/15">
                                            <?= $reg['reg_code'] ?>
                                        </span>
                                        <?php if (!empty($reg['reg_level'])): ?>
                                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-slate-900/60 text-slate-300 border border-slate-800">
                                                <?= esc($reg['reg_level']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Status Badges -->
                                    <div>
                                        <?php if ($reg['reg_status'] === 'approved'): ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                                                 อนุมัติแล้ว (ตัวจริง)
                                            </span>
                                        <?php elseif ($reg['reg_status'] === 'approved_reserve'): ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-ping"></span>
                                                 อนุมัติแล้ว (ตัวสำรอง)
                                            </span>
                                        <?php elseif ($reg['reg_status'] === 'rejected'): ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                ❌ ปฏิเสธ/ไม่ผ่านการอนุมัติ
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                ⏳ อยู่ระหว่างการตรวจสอบ
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Body details -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-slate-800/60">
                                    <div class="space-y-1">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">ประเภทการแข่งขัน</span>
                                        <p class="text-sm font-extrabold text-white flex items-center gap-1">
                                            <i data-lucide="award" class="w-4 h-4 text-indigo-400"></i>
                                            <?= esc($reg['reg_competition_type']) ?>
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">โรงเรียน / สถาบันการศึกษา</span>
                                        <p class="text-sm font-extrabold text-white flex items-center gap-1">
                                            <i data-lucide="school" class="w-4 h-4 text-cyan-400"></i>
                                            <?= esc($reg['reg_school_name']) ?> <span class="text-[10px] font-normal text-slate-400">(จ.<?= esc($reg['reg_school_province']) ?>)</span>
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">ชื่อทีม</span>
                                        <p class="text-sm font-extrabold text-white">
                                            <?= esc($reg['reg_team_name'] ?: 'ทั่วไป (ไม่มีชื่อทีม)') ?>
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">สมาชิกในทีม</span>
                                        <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-slate-300">
                                            <?php 
                                            $members = json_decode($reg['reg_members'], true) ?: [];
                                            foreach ($members as $m) {
                                                $name = is_array($m) ? (($m['prefix'] ?? '') . ' ' . ($m['name'] ?? '')) : $m;
                                                echo '<span class="inline-flex items-center gap-1 font-semibold"><span class="w-1 h-1 rounded-full bg-slate-400"></span>' . esc(trim($name)) . '</span>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-4 border-t border-slate-800/60 flex flex-wrap gap-2 justify-between items-center text-xs">
                                    <span class="text-slate-400 font-semibold font-mono">
                                        สมัครเมื่อ: <?= date('d/m/Y H:i', strtotime($reg['reg_created_at'])) ?> น.
                                    </span>
                                    <div class="flex gap-2">
                                        <a href="<?= base_url('science-week/success/' . $reg['reg_code']) ?>" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-500/10 hover:bg-indigo-600 hover:text-white text-indigo-300 border border-indigo-500/20 transition-all font-bold shadow-sm">
                                            <i data-lucide="ticket" class="w-4 h-4 text-indigo-400"></i>
                                            <span>ดูรายละเอียด/พิมพ์ตั๋วใบสมัคร</span>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    // Particles Canvas Animation
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
            draw() { ctx.fillStyle = this.color; ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill(); }
        }
        for (let i = 40; i > 0; i--) particles.push(new Particle());
        function animate() { ctx.clearRect(0, 0, canvas.width, canvas.height); particles.forEach(p => { p.update(); p.draw(); }); requestAnimationFrame(animate); }
        animate();
    }
</script>
<?= $this->endSection() ?>
