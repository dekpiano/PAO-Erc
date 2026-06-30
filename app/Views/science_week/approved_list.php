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
    .comp-section {
        animation: sectionFadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    @keyframes sectionFadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="page-container pt-8 pb-20 relative">
    <canvas id="particles-canvas"></canvas>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
        
        <!-- Header -->
        <div class="text-center py-8 space-y-4">
            <a href="<?= base_url('science-week') ?>" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-bold transition-colors text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับหน้าแรก
            </a>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight">
                <span class="gradient-text leading-normal">
                    ประกาศรายชื่อผู้มีสิทธิ์เข้าร่วมแข่งขัน
                </span>
            </h1>
            <p class="text-slate-400 text-sm font-semibold">รายชื่อผู้ผ่านการตรวจสอบข้อมูลการสมัครแข่งขันที่ได้รับการยืนยันสิทธิ์เรียบร้อยแล้ว</p>
            <div class="rainbow-divider max-w-20 mx-auto"></div>
        </div>

        <!-- Search / Filter Card -->
        <div class="glass-sci-card rounded-3xl p-6 sm:p-8 mb-10 shadow-lg relative overflow-hidden">
            <div class="rainbow-divider absolute top-0 left-0 right-0"></div>
            
            <form method="GET" action="<?= base_url('science-week/approved-list') ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-4">
                <!-- Search input -->
                <div class="relative lg:col-span-2">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <i data-lucide="search" class="w-5 h-5 text-indigo-400"></i>
                    </span>
                    <input type="text" name="search" value="<?= esc($search) ?>" placeholder="ค้นหา ชื่อโรงเรียน, ชื่อทีม, รายชื่อสมาชิก..." class="neon-input w-full pl-12 pr-4 py-4 rounded-2xl text-white font-medium">
                </div>

                <!-- Filter Competition -->
                <div>
                    <select name="competition_type" class="w-full px-4 py-4 bg-slate-900 border border-indigo-500/30 text-white rounded-2xl font-medium outline-none cursor-pointer neon-input">
                        <option value="" class="bg-slate-900 text-white">-- การแข่งขันทั้งหมด --</option>
                        <?php if (!empty($competitions)): ?>
                            <?php foreach ($competitions as $comp): ?>
                                <option value="<?= esc($comp['comp_name']) ?>" <?= $compType_active == $comp['comp_name'] ? 'selected' : '' ?> class="bg-slate-900 text-white"><?= esc($comp['comp_name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Submit & Clear Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 py-4 rounded-2xl text-white font-bold neon-btn-search flex items-center justify-center gap-2">
                        <i data-lucide="filter" class="w-5 h-5"></i> กรองรายชื่อ
                    </button>
                    <?php if(!empty($search) || !empty($compType_active)): ?>
                        <a href="<?= base_url('science-week/approved-list') ?>" class="p-4 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-300 rounded-2xl transition-all flex items-center justify-center" title="ล้างฟิลเตอร์">
                            <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Participants List separated by Competitions -->
        <div class="space-y-12">
            <?php 
            // Group registrations in PHP to display clearly per competition
            $groupedRegs = [];
            foreach ($registrations as $r) {
                $groupedRegs[$r['reg_competition_type']][] = $r;
            }

            // If empty, display message
            if (empty($groupedRegs)): 
            ?>
                <div class="glass-sci-card rounded-3xl p-12 text-center text-slate-400 border border-dashed border-indigo-500/20">
                    <i data-lucide="search-x" class="w-16 h-16 mx-auto text-indigo-400/40 mb-4 animate-pulse"></i>
                    <p class="font-bold text-lg mb-1 text-white">ไม่พบรายชื่อผู้มีสิทธิ์เข้าร่วมแข่งขัน</p>
                    <p class="text-xs text-slate-400">กรุณาลองเปลี่ยนคำค้นหา หรือตรวจสอบการเลือกประเภทการแข่งขัน</p>
                </div>
            <?php 
            else:
                $secDelay = 0;
                foreach ($groupedRegs as $compName => $regs): 
                    // Find corresponding competition icon/color if configured
                    $compMeta = null;
                    foreach ($competitions as $c) {
                        if ($c['comp_name'] === $compName) {
                            $compMeta = $c;
                            break;
                        }
                    }
                    $compIcon = $compMeta['comp_icon'] ?? 'award';
                    $compColor = $compMeta['comp_color'] ?? '#a855f7';
            ?>
                <div class="comp-section space-y-6" style="animation-delay: <?= $secDelay ?>ms">
                    <!-- Competition Header -->
                    <div class="flex items-center gap-3 border-b border-slate-800/80 pb-4">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white" style="background: <?= esc($compColor) ?>; box-shadow: 0 0 15px <?= esc($compColor) ?>60">
                            <i data-lucide="<?= esc($compIcon) ?>" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-white"><?= esc($compName) ?></h2>
                            <p class="text-xs text-slate-400 font-semibold">จำนวนผู้ยืนยันสิทธิ์: <span class="text-indigo-400 font-black font-mono"><?= count($regs) ?></span> รายการ</p>
                        </div>
                    </div>

                    <!-- Desktop Listing Table -->
                    <div class="hidden md:block glass-sci-card rounded-3xl overflow-hidden shadow-2xl border border-indigo-500/10">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-950/60 border-b border-indigo-500/20 text-slate-350">
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider w-32">รหัสใบสมัคร</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider w-48">ระดับชั้น</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">ชื่อทีม / สถาบันการศึกษา</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">สมาชิกในทีม</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">อาจารย์ที่ปรึกษา</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-center">พิมพ์บัตรประจำตัว</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/60">
                                    <?php foreach ($regs as $reg): ?>
                                        <tr class="hover:bg-slate-900/20 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-xs font-black text-indigo-400 font-mono tracking-wider bg-indigo-500/5 px-2.5 py-1.5 rounded-xl border border-indigo-500/15"><?= $reg['reg_code'] ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-block px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-900/60 text-slate-200 border border-slate-800"><?= esc($reg['reg_level'] ?: 'ไม่ระบุ') ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-extrabold text-white text-sm"><?= $reg['reg_team_name'] ? esc($reg['reg_team_name']) : 'บุคคลทั่วไป' ?></div>
                                                <div class="text-xs text-slate-400 font-medium mt-0.5"><?= esc($reg['reg_school_name']) ?> <span class="text-[10px] text-slate-500">จ.<?= esc($reg['reg_school_province']) ?></span></div>
                                            </td>
                                            <td class="px-6 py-4 text-xs text-slate-300">
                                                <?php 
                                                $members = json_decode($reg['reg_members'], true) ?: []; 
                                                foreach ($members as $m):
                                                ?>
                                                    <div class="flex items-center gap-1.5 py-0.5">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                                                        <span><?= esc($m) ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </td>
                                            <td class="px-6 py-4 text-xs text-slate-300">
                                                <?php 
                                                $advisors = json_decode($reg['reg_advisors'], true) ?: []; 
                                                foreach ($advisors as $a):
                                                ?>
                                                    <div class="flex items-center gap-1.5 py-0.5">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                                                        <span><?= esc($a) ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                <a href="<?= base_url('science-week/success/' . $reg['reg_code']) ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-500/10 hover:bg-indigo-600 hover:text-white text-indigo-300 border border-indigo-500/20 transition-all font-bold text-xs shadow-sm">
                                                    <i data-lucide="ticket" class="w-4 h-4 text-indigo-400"></i>
                                                    <span>พิมพ์บัตร/ตั๋วทีม</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Listing Cards -->
                    <div class="md:hidden space-y-4">
                        <?php foreach ($regs as $reg): ?>
                            <div class="glass-sci-card rounded-3xl p-5 border border-indigo-500/10 space-y-3">
                                <div class="flex justify-between items-center gap-3">
                                    <span class="text-[10px] font-black text-indigo-400 font-mono tracking-wider bg-indigo-500/5 px-2 py-1 rounded-xl border border-indigo-500/15"><?= $reg['reg_code'] ?></span>
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-bold bg-slate-900/60 text-slate-300 border border-slate-800"><?= esc($reg['reg_level'] ?: 'ทั่วไป') ?></span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-black text-white"><?= $reg['reg_team_name'] ? esc($reg['reg_team_name']) : 'บุคคลทั่วไป' ?></h4>
                                    <p class="text-xs text-slate-400 font-medium mt-0.5">โรงเรียน: <span class="text-slate-300 font-bold"><?= esc($reg['reg_school_name']) ?></span> (จ.<?= esc($reg['reg_school_province']) ?>)</p>
                                </div>

                                <div class="grid grid-cols-1 gap-2 pt-2.5 border-t border-slate-800/80">
                                    <div>
                                        <span class="text-[9px] text-indigo-400 font-black uppercase tracking-wider block mb-1">สมาชิกทีม</span>
                                        <?php 
                                        $members = json_decode($reg['reg_members'], true) ?: []; 
                                        foreach ($members as $m):
                                        ?>
                                            <div class="text-xs text-slate-300 font-medium py-0.5 flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                                                <span><?= esc($m) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <?php 
                                    $advisors = json_decode($reg['reg_advisors'], true) ?: []; 
                                    if (!empty($advisors)): 
                                    ?>
                                        <div class="mt-1">
                                            <span class="text-[9px] text-emerald-400 font-black uppercase tracking-wider block mb-1">อาจารย์ที่ปรึกษา</span>
                                            <?php foreach ($advisors as $a): ?>
                                                <div class="text-xs text-slate-300 font-medium py-0.5 flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                                                    <span><?= esc($a) ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="flex flex-col gap-2 pt-2.5 border-t border-slate-800/80">
                                    <a href="<?= base_url('science-week/success/' . $reg['reg_code']) ?>" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-500/10 hover:bg-indigo-600 hover:text-white text-indigo-300 border border-indigo-500/20 transition-all font-bold text-xs shadow-sm">
                                        <i data-lucide="ticket" class="w-4 h-4"></i>
                                        <span>พิมพ์บัตร/ตั๋วทีม</span>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php 
                $secDelay += 100;
                endforeach; 
            endif;
            ?>
        </div>

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
        for (let i = 0; i < 40; i++) particles.push(new Particle());
        function animate() { ctx.clearRect(0, 0, canvas.width, canvas.height); particles.forEach(p => { p.update(); p.draw(); }); requestAnimationFrame(animate); }
        animate();
    }
</script>
<?= $this->endSection() ?>
