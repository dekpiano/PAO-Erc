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
    .gradient-text {
        background: linear-gradient(135deg, #818cf8 0%, #6366f1 50%, #4f46e5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .rating-btn {
        transition: all 0.2s ease-in-out;
    }
    .rating-btn:hover {
        transform: scale(1.1);
    }
</style>

<div class="page-container pt-8 pb-20 relative">
    <canvas id="particles-canvas"></canvas>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 relative z-10">
        <!-- Header -->
        <div class="text-center py-6 space-y-4">
            <a href="<?= base_url('science-week') ?>" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-bold transition-colors text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับหน้าแรก
            </a>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight">
                <span class="gradient-text leading-normal"><?= esc($form_config['title'] ?? 'แบบประเมินความพึงพอใจ') ?></span>
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm font-semibold"><?= esc($form_config['subtitle'] ?? 'ร่วมประเมินการจัดกิจกรรมสัปดาห์วิทยาศาสตร์เพื่อรับเกียรติบัตรเข้าร่วมกิจกรรม') ?></p>
            <div class="rainbow-divider max-w-20 mx-auto"></div>
        </div>

        <!-- Success Result Card (Hidden by default) -->
        <div id="success-cert-card" class="glass-sci-card rounded-3xl p-8 text-center space-y-6 hidden mb-10 border border-emerald-500/30">
            <div class="relative w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500/10 opacity-75"></span>
                <i data-lucide="award" class="w-10 h-10 text-emerald-400"></i>
            </div>
            
            <div class="space-y-2">
                <h2 class="text-xl sm:text-2xl font-black text-white">ประเมินความพึงพอใจสำเร็จ!</h2>
                <p class="text-slate-350 text-xs sm:text-sm max-w-md mx-auto">
                    ขอขอบพระคุณสำหรับความคิดเห็นที่เป็นประโยชน์ของท่าน ระบบได้ทำการออกเกียรติบัตรการเข้าร่วมกิจกรรมให้แก่ท่านเรียบร้อยแล้ว
                </p>
            </div>
            
            <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-center">
                <a id="btn-download-cert" href="#" target="_blank" class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-extrabold rounded-2xl shadow-lg transition-transform hover:scale-105 flex items-center justify-center gap-2">
                    <i data-lucide="download" class="w-5 h-5"></i> ดาวน์โหลดเกียรติบัตร (PNG)
                </a>
                <a href="<?= base_url('science-week') ?>" class="px-6 py-4 bg-slate-900 border border-slate-700 hover:bg-slate-800 text-slate-300 font-bold rounded-2xl transition-colors flex items-center justify-center">
                    กลับหน้าหลัก
                </a>
            </div>
        </div>

        <!-- Evaluation Form Card -->
        <form id="evaluation-form" onsubmit="submitEvaluation(event)" class="glass-sci-card rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden space-y-8">
            <div class="rainbow-divider absolute top-0 left-0 right-0"></div>
            
            <!-- Section 1: Personal Info -->
            <div class="space-y-4 pt-4">
                <h3 class="text-sm font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4"></i> ข้อมูลผู้ทำแบบประเมิน (เพื่อพิมพ์บนเกียรติบัตร)
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-450 mb-1.5">ชื่อ-นามสกุล * (ใช้สำหรับจัดพิมพ์เกียรติบัตร)</label>
                        <input type="text" name="fullname" required placeholder="เด็กชายสมศักดิ์ รักเรียน" class="neon-input w-full px-4 py-3 rounded-2xl text-xs sm:text-sm">
                    </div>
                    <?php if (!empty($form_config['fields'])): ?>
                        <?php foreach ($form_config['fields'] as $field): ?>
                            <div>
                                <label class="block text-xs font-bold text-slate-450 mb-1.5"><?= esc($field['label']) ?> <?= $field['required'] ? '*' : '' ?></label>
                                <input type="<?= esc($field['type']) ?>" name="fields[<?= esc($field['key']) ?>]" <?= $field['required'] ? 'required' : '' ?> placeholder="<?= esc($field['placeholder']) ?>" class="neon-input w-full px-4 py-3 rounded-2xl text-xs sm:text-sm">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Section 2: Ratings -->
            <div class="space-y-5 border-t border-slate-800/80 pt-6">
                <h3 class="text-sm font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="star" class="w-4 h-4"></i> ระดับความพึงพอใจในการจัดกิจกรรม
                </h3>
                <p class="text-[11px] text-slate-400">กรุณาเลือกคะแนนประเมิน (5 = พึงพอใจมากที่สุด, 1 = พึงพอใจน้อยที่สุด)</p>

                <?php if (!empty($form_config['questions'])): ?>
                    <?php foreach ($form_config['questions'] as $q): ?>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-200"><?= esc($q['label']) ?> *</label>
                            <div class="flex items-center gap-1.5 sm:gap-3">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <label class="flex-1 max-w-[48px] sm:max-w-[60px] text-center cursor-pointer">
                                        <input type="radio" name="ratings[<?= esc($q['key']) ?>]" value="<?= $i ?>" required class="peer hidden">
                                        <div class="rating-btn py-2 text-xs font-bold rounded-xl border border-slate-700 bg-slate-900/60 text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 transition-colors">
                                            <?= $i ?>
                                        </div>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Section 3: Comments -->
            <div class="space-y-4 border-t border-slate-800/80 pt-6">
                <h3 class="text-sm font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="message-square" class="w-4 h-4"></i> <?= esc($form_config['comment_label'] ?? 'ข้อเสนอแนะอื่นๆ สำหรับการปรับปรุงครั้งต่อไป') ?>
                </h3>
                <textarea name="comments" rows="3" placeholder="ระบุข้อคิดเห็นหรือข้อเสนอแนะเพิ่มเติมของท่าน..." class="neon-input w-full px-4 py-3 rounded-2xl text-xs sm:text-sm"></textarea>
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-extrabold rounded-2xl transition-transform hover:scale-[1.02] shadow-lg shadow-indigo-500/20 text-xs sm:text-sm flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i> ส่งแบบประเมินและขอรับเกียรติบัตร
            </button>
        </form>
    </div>
</div>

<script>
    // Particles Background
    const canvas = document.getElementById('particles-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let particles = [];
        const colors = ['rgba(102,126,234,0.3)', 'rgba(118,75,162,0.3)', 'rgba(240,147,251,0.2)'];
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

    function submitEvaluation(event) {
        event.preventDefault();
        Swal.showLoading();

        const form = document.getElementById('evaluation-form');
        const formData = new FormData(form);

        fetch('<?= base_url('science-week/evaluation/store') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'ขอบคุณสำหรับการประเมิน',
                    text: data.message,
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#10b981',
                    customClass: { popup: 'glass-card rounded-[2rem]' }
                });

                // Display download certificate card and hide form
                form.classList.add('hidden');
                
                const successCard = document.getElementById('success-cert-card');
                const downloadBtn = document.getElementById('btn-download-cert');
                
                downloadBtn.href = `<?= base_url('science-week/certificate/download/evaluation') ?>/${data.eval_code}?preview=1`;
                successCard.classList.remove('hidden');
                
                // Scroll to top of card
                successCard.scrollIntoView({ behavior: 'smooth' });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: data.message,
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'glass-card rounded-[2rem]' }
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้',
                background: getSwalColors().bg,
                color: getSwalColors().text,
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'glass-card rounded-[2rem]' }
            });
        });
    }
</script>
<?= $this->endSection() ?>
