<?= $this->extend('science_week/layout/main') ?>

<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

    /* Select2 Dark Neon Custom Styling */
    .select2-container {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--single {
        background: rgba(8, 12, 24, 0.7) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        height: 48px !important;
        border-radius: 1rem !important;
        padding-left: 1rem !important;
        display: flex !important;
        align-items: center !important;
        transition: all 0.4s ease !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #ffffff !important;
        font-weight: 500 !important;
        font-size: 0.875rem !important;
        padding-left: 0px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered .select2-selection__placeholder {
        color: #64748b !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px !important;
        right: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #818cf8 transparent transparent transparent !important;
        border-width: 6px 6px 0 6px !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #818cf8 transparent !important;
        border-width: 0 6px 6px 6px !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
    }
    .select2-dropdown {
        background: rgba(15, 23, 42, 0.95) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(99, 102, 241, 0.4) !important;
        border-radius: 1rem !important;
        overflow: hidden !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
        z-index: 9999 !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        background: rgba(8, 12, 24, 0.9) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        color: white !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        outline: none !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #6366f1 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
        color: white !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: rgba(99, 102, 241, 0.2) !important;
        color: #a5b4fc !important;
    }
    .select2-results__option {
        background-color: transparent !important;
        padding: 10px 16px !important;
        font-size: 14px !important;
        color: #cbd5e1 !important;
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
        <div id="success-cert-card" class="glass-sci-card rounded-3xl p-8 text-center space-y-6 hidden mb-10 border border-indigo-500/30">
            <div class="relative w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-400">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-500/10 opacity-75"></span>
                <i data-lucide="award" class="w-10 h-10 text-indigo-400"></i>
            </div>
            
            <div class="space-y-2">
                <h2 class="text-xl sm:text-2xl font-black text-white">บันทึกข้อมูลและแบบประเมินสำเร็จ!</h2>
                <p class="text-slate-350 text-xs sm:text-sm max-w-md mx-auto">
                    ขอขอบพระคุณสำหรับข้อมูลและการประเมินความพึงพอใจของท่าน ระบบได้ทำการออกเกียรติบัตรการเข้าร่วมกิจกรรมให้เรียบร้อยแล้ว
                </p>
            </div>
            
            <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-center">
                <a id="btn-download-cert" href="#" target="_blank" class="px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-extrabold rounded-2xl shadow-lg transition-transform hover:scale-105 flex items-center justify-center gap-2">
                    <i data-lucide="award" class="w-5 h-5"></i> ดูและดาวน์โหลดเกียรติบัตรทั้งหมด
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
            <div class="space-y-6 pt-4">
                <h3 class="text-sm font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4"></i> ส่วนที่ 1: ข้อมูลทั่วไป
                </h3>

                <!-- 1.1 เพศ -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-200">1.1 เพศ *</label>
                    <div class="flex gap-4">
                        <label class="flex-1 max-w-[120px] text-center cursor-pointer">
                            <input type="radio" name="gender" value="ชาย" required class="peer hidden">
                            <div class="py-3 text-xs font-bold rounded-2xl border border-slate-700 bg-slate-900/60 text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 transition-all duration-300">
                                ชาย
                            </div>
                        </label>
                        <label class="flex-1 max-w-[120px] text-center cursor-pointer">
                            <input type="radio" name="gender" value="หญิง" required class="peer hidden">
                            <div class="py-3 text-xs font-bold rounded-2xl border border-slate-700 bg-slate-900/60 text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 transition-all duration-300">
                                หญิง
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 1.2 อายุ -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-200">1.2 อายุ *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <?php foreach (['ต่ำกว่า 15 ปี', '16 - 25 ปี', '26 - 35 ปี', '36 - 45 ปี', '46 ปีขึ้นไป'] as $ageGroup): ?>
                            <label class="text-center cursor-pointer">
                                <input type="radio" name="age" value="<?= $ageGroup ?>" required class="peer hidden">
                                <div class="py-3 px-2 text-xs font-bold rounded-2xl border border-slate-700 bg-slate-900/60 text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 transition-all duration-300">
                                    <?= $ageGroup ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 1.3 อาชีพ -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-200">1.3 อาชีพ *</label>
                    <div class="grid grid-cols-1 gap-3">
                        <?php 
                        $occupations = [
                            'นักเรียน / นักศึกษา',
                            'ครู บุคลากรทางการศึกษา',
                            'ผู้บริหาร ข้าราชการ เจ้าหน้าที่องค์กรปกครองส่วนท้องถิ่น',
                            'อื่นๆ'
                        ];
                        foreach ($occupations as $occ): 
                        ?>
                            <label class="cursor-pointer">
                                <input type="radio" name="occupation" value="<?= $occ ?>" required class="peer hidden" onchange="toggleOccupationOther(this.value)">
                                <div class="py-3.5 px-4 text-xs font-bold rounded-2xl border border-slate-700 bg-slate-900/60 text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 transition-all duration-300 flex items-center justify-between">
                                    <span><?= $occ ?></span>
                                    <div class="w-4 h-4 rounded-full border border-slate-650 flex items-center justify-center peer-checked:border-white">
                                        <div class="w-2 h-2 rounded-full bg-transparent peer-checked:bg-white"></div>
                                    </div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                        
                        <div id="occupation-other-wrapper" class="hidden mt-2">
                            <label class="block text-xs font-bold text-slate-450 mb-1.5">โปรดระบุอาชีพของคุณ</label>
                            <input type="text" name="occupation_other" id="occupation_other" placeholder="ระบุอาชีพ..." class="neon-input w-full px-4 py-3 rounded-2xl text-xs sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- 1.4 ระดับการศึกษา -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-200">1.4 ระดับการศึกษา *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                        <?php 
                        $levels = [
                            'ต่ำกว่าหรือเทียบเท่าประถมศึกษา',
                            'มัธยมศึกษา',
                            'อนุปริญญา',
                            'ปริญญาตรี',
                            'ปริญญาโท'
                        ];
                        foreach ($levels as $lvl): 
                        ?>
                            <label class="text-center cursor-pointer">
                                <input type="radio" name="education_level" value="<?= $lvl ?>" required class="peer hidden">
                                <div class="py-3 px-2 text-[11px] font-bold rounded-2xl border border-slate-700 bg-slate-900/60 text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 transition-all duration-300 h-full flex items-center justify-center">
                                    <?= $lvl ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 1.5 จังหวัดที่อาศัยอยู่ -->
                <div class="space-y-2">
                    <label for="province" class="block text-xs font-bold text-slate-200">1.5 อาศัยอยู่ ณ จังหวัด *</label>
                    <select name="province" id="province" required class="w-full neon-input rounded-2xl text-xs sm:text-sm">
                        <option value="" disabled>-- เลือกจังหวัด --</option>
                        <?php
                        $provinces = [
                            "กรุงเทพมหานคร", "กระบี่", "กาญจนบุรี", "กาฬสินธุ์", "กำแพงเพชร", "ขอนแก่น", "จันทบุรี", "ฉะเชิงเทรา",
                            "ชลบุรี", "ชัยนาท", "ชัยภูมิ", "ชุมพร", "เชียงราย", "เชียงใหม่", "ตรัง", "ตราด", "ตาก", "นครนายก",
                            "นครปฐม", "นครพนม", "นครราชสีมา", "นครศรีธรรมราช", "นครสวรรค์", "นนทบุรี", "นราธิวาส", "น่าน",
                            "บึงกาฬ", "บุรีรัมย์", "ปทุมธานี", "ประจวบคีรีขันธ์", "ปราจีนบุรี", "ปัตตานี", "พระนครศรีอยุธยา", "พะเยา",
                            "พังงา", "พัทลุง", "พิจิตร", "พิษณุโลก", "เพชรบุรี", "เพชรบูรณ์", "แพร่", "ภูเก็ต", "มหาสารคาม",
                            "มุกดาหาร", "แม่ฮ่องสอน", "ยะลา", "ยโสธร", "ร้อยเอ็ด", "ระนอง", "ระยอง", "ราชบุรี", "ลพบุรี",
                            "ลำปาง", "ลำพูน", "เลย", "ศรีสะเกษ", "สกลนคร", "สงขลา", "สตูล", "สมุทรปราการ", "สมุทรสงคราม",
                            "สมุทรสาคร", "สระแก้ว", "สระบุรี", "สิงห์บุรี", "สุโขทัย", "สุพรรณบุรี", "สุราษฎร์ธานี", "สุรินทร์",
                            "หนองคาย", "หนองบัวลำภู", "อ่างทอง", "อุดรธานี", "อุทัยธานี", "อุตรดิตถ์", "อุบลราชธานี", "อำนาจเจริญ"
                        ];
                        foreach ($provinces as $p):
                        ?>
                            <option value="<?= esc($p) ?>" <?= esc($p) === 'นครสวรรค์' ? 'selected' : '' ?>><?= esc($p) ?></option>
                        <?php endforeach; ?>
                    </select>
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
                        <div class="space-y-3">
                            <label class="block text-sm sm:text-base font-bold text-slate-200"><?= esc($q['label']) ?> *</label>
                            <div class="flex items-center gap-2 sm:gap-4">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <label class="flex-1 max-w-[64px] sm:max-w-[80px] text-center cursor-pointer">
                                        <input type="radio" name="ratings[<?= esc($q['key']) ?>]" value="<?= $i ?>" required class="peer hidden">
                                        <div class="rating-btn py-3 sm:py-4 text-base sm:text-xl font-black rounded-2xl border border-slate-700 bg-slate-900/60 text-slate-400 peer-checked:bg-gradient-to-r peer-checked:from-indigo-500 peer-checked:to-purple-500 peer-checked:text-white peer-checked:border-indigo-400 peer-checked:shadow-lg peer-checked:shadow-indigo-500/30 transition-all duration-300">
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
                <i data-lucide="check-circle" class="w-5 h-5"></i> ส่งแบบประเมินความพึงพอใจ
            </button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#province').select2({
            placeholder: "-- เลือกจังหวัด --",
            allowClear: false,
            width: '100%'
        });
    });

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

    function toggleOccupationOther(value) {
        const wrapper = document.getElementById('occupation-other-wrapper');
        const input = document.getElementById('occupation_other');
        if (value === 'อื่นๆ') {
            wrapper.classList.remove('hidden');
            input.setAttribute('required', 'required');
        } else {
            wrapper.classList.add('hidden');
            input.removeAttribute('required');
            input.value = '';
        }
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
                    title: 'บันทึกสำเร็จ',
                    text: data.message,
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#10b981',
                    customClass: { popup: 'glass-card rounded-[2rem]' },
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = `<?= base_url('science-week/evaluation/claim') ?>/${data.eval_code}`;
                });
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
