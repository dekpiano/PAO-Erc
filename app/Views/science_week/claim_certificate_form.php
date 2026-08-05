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
</style>

<div class="page-container pt-8 pb-20 relative">
    <canvas id="particles-canvas"></canvas>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 relative z-10">
        <!-- Header -->
        <div class="text-center py-6 space-y-4">
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight">
                <span class="gradient-text leading-normal">รับเกียรติบัตรเข้าร่วมกิจกรรม</span>
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm font-semibold">กรุณากรอกชื่อ-นามสกุลจริงเพื่อใช้ในการออกเกียรติบัตร</p>
            <div class="rainbow-divider max-w-20 mx-auto"></div>
        </div>

        <!-- Success Result Card (Hidden by default) -->
        <div id="success-cert-card" class="glass-sci-card rounded-3xl p-8 text-center space-y-6 hidden mb-10 border border-indigo-500/30">
            <div class="relative w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-400">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-500/10 opacity-75"></span>
                <i data-lucide="award" class="w-10 h-10 text-indigo-400"></i>
            </div>
            
            <div class="space-y-2">
                <h2 class="text-xl sm:text-2xl font-black text-white">บันทึกข้อมูลเรียบร้อยแล้ว!</h2>
                <p class="text-slate-350 text-xs sm:text-sm max-w-md mx-auto">
                    ระบบได้ทำการออกเกียรติบัตรการเข้าร่วมกิจกรรมสัปดาห์วิทยาศาสตร์ให้เรียบร้อยแล้ว ท่านสามารถกดปุ่มด้านล่างเพื่อเข้าดูและดาวน์โหลด
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

        <?php
        $existingStudents = !empty($eval['eval_students']) ? json_decode($eval['eval_students'], true) : [];
        $primaryName = '';
        if (!empty($existingStudents)) {
            $primaryName = $existingStudents[0];
            array_shift($existingStudents); // Remaining are additional students
        } else {
            $primaryName = ($eval['eval_name'] !== 'ผู้ประเมินทั่วไป') ? $eval['eval_name'] : '';
        }
        $phoneVal = $eval['eval_phone'] ?? '';
        $schoolVal = $eval['eval_school'] ?? '';
        ?>

        <!-- Claim Form Card -->
        <form id="claim-form" onsubmit="submitClaim(event)" class="glass-sci-card rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden space-y-8">
            <div class="rainbow-divider absolute top-0 left-0 right-0"></div>
            
            <!-- Section 1: Primary Attendee Info -->
            <div class="space-y-4 pt-4">
                <h3 class="text-sm font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2 border-b border-slate-800/80 pb-2">
                    <i data-lucide="user-check" class="w-4 h-4"></i> ส่วนที่ 1: ข้อมูลผู้เข้างานหลัก (ผู้รับเกียรติบัตรหลัก)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-200 mb-1.5">ชื่อ-นามสกุล ผู้เข้างานหลัก *</label>
                        <input type="text" name="eval_name" required value="<?= esc($primaryName) ?>" placeholder="เช่น นายสมชาย ดีใจ" class="neon-input w-full px-4 py-3 rounded-2xl text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-200 mb-1.5">เบอร์โทรศัพท์ (ถ้ามี)</label>
                        <input type="text" name="eval_phone" id="eval_phone" maxlength="12" value="<?= esc($phoneVal) ?>" placeholder="เช่น 08X-XXX-XXXX" class="neon-input w-full px-4 py-3 rounded-2xl text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-200 mb-1.5">ชื่อโรงเรียน / หน่วยงาน (ถ้ามี)</label>
                        <input type="text" name="eval_school" value="<?= esc($schoolVal) ?>" placeholder="เช่น โรงเรียนวัดบ้านไร่" class="neon-input w-full px-4 py-3 rounded-2xl text-xs sm:text-sm">
                    </div>
                </div>
            </div>
            
            <!-- Section 2: Additional Recipients -->
            <div class="space-y-4 border-t border-slate-800/80 pt-6">
                <div class="pb-2">
                    <h3 class="text-sm font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4"></i> ส่วนที่ 2: รายชื่อผู้เข้าร่วมเพิ่มเติม (กรณีครูพานักเรียนมาหลายคน)
                    </h3>
                </div>
                
                <div id="students-wrapper" class="space-y-3">
                    <?php if (!empty($existingStudents)): ?>
                        <?php foreach ($existingStudents as $idx => $sName): ?>
                            <div class="flex items-center gap-2 student-row">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-slate-450 mb-1.5">ชื่อ-นามสกุล คนที่ <?= $idx + 1 ?> *</label>
                                    <input type="text" name="student_names[]" required value="<?= esc($sName) ?>" placeholder="เช่น เด็กชายสมศักดิ์ รักเรียน" class="neon-input w-full px-4 py-3 rounded-2xl text-xs sm:text-sm">
                                </div>
                                <button type="button" class="remove-student-btn mt-6 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-455 hover:bg-rose-500/25 hover:text-white rounded-2xl transition-all">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Empty placeholder to let user add names manually if they wish -->
                        <div class="text-center py-6 text-slate-500 border border-dashed border-slate-800 rounded-2xl no-students-placeholder">
                            <i data-lucide="info" class="w-8 h-8 mx-auto text-slate-600 mb-2"></i>
                            <p class="text-xs font-semibold">หากไม่มีผู้เข้างานท่านอื่นเพิ่มเติม ไม่จำเป็นต้องกรอกส่วนนี้</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="button" id="add-student-btn" class="px-4 py-2.5 bg-indigo-500/10 border border-indigo-500/30 hover:border-indigo-500 text-indigo-300 hover:text-white rounded-2xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="plus" class="w-4 h-4"></i> เพิ่มรายชื่อนักเรียน/ผู้เข้างานเพิ่มเติม
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-extrabold rounded-2xl transition-transform hover:scale-[1.02] shadow-lg shadow-indigo-500/20 text-xs sm:text-sm flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i> บันทึกข้อมูลและออกเกียรติบัตรทั้งหมด
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

    // Dynamic Add/Remove Students
    document.getElementById('add-student-btn').addEventListener('click', function() {
        const wrapper = document.getElementById('students-wrapper');
        const limit = <?= (int)($claim_limit ?? 20) ?>;
        
        // Remove placeholder if exists
        const placeholder = wrapper.querySelector('.no-students-placeholder');
        if (placeholder) {
            placeholder.remove();
        }

        const currentCount = wrapper.getElementsByClassName('student-row').length;
        if (currentCount >= (limit - 1)) { // Limit minus 1 because the primary attendee counts as 1
            Swal.fire({
                icon: 'warning',
                title: 'ขออภัย',
                text: `คุณสามารถเคลมเกียรติบัตรได้สูงสุดรวมทั้งหมด ${limit} คนต่อแบบประเมิน`,
                background: getSwalColors().bg,
                color: getSwalColors().text,
                confirmButtonColor: '#4f46e5',
                customClass: { popup: 'glass-card rounded-[2rem]' }
            });
            return;
        }

        const count = currentCount + 1;
        const newRow = document.createElement('div');
        newRow.className = 'flex items-center gap-2 student-row mt-2';
        newRow.innerHTML = `
            <div class="flex-1">
                <label class="block text-xs font-bold text-slate-450 mb-1.5">ชื่อ-นามสกุล คนที่ ${count} *</label>
                <input type="text" name="student_names[]" required placeholder="เช่น เด็กชายสมศักดิ์ รักเรียน" class="neon-input w-full px-4 py-3 rounded-2xl text-xs sm:text-sm">
            </div>
            <button type="button" class="remove-student-btn mt-6 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-455 hover:bg-rose-500/25 hover:text-white rounded-2xl transition-all">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        `;
        
        wrapper.appendChild(newRow);
        lucide.createIcons();
        updateRemoveButtons();
    });

    document.getElementById('students-wrapper').addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-student-btn');
        if (btn) {
            const row = btn.closest('.student-row');
            row.remove();
            renameLabels();
            updateRemoveButtons();
        }
    });

    function renameLabels() {
        const wrapper = document.getElementById('students-wrapper');
        const rows = wrapper.getElementsByClassName('student-row');
        Array.from(rows).forEach((row, index) => {
            const label = row.querySelector('label');
            if (label) {
                label.innerText = `ชื่อ-นามสกุล คนที่ ${index + 1} *`;
            }
        });
    }

    function updateRemoveButtons() {
        const wrapper = document.getElementById('students-wrapper');
        const rows = wrapper.getElementsByClassName('student-row');
        
        if (rows.length === 0) {
            if (!wrapper.querySelector('.no-students-placeholder')) {
                wrapper.innerHTML = `
                    <div class="text-center py-6 text-slate-500 border border-dashed border-slate-800 rounded-2xl no-students-placeholder">
                        <i data-lucide="info" class="w-8 h-8 mx-auto text-slate-600 mb-2"></i>
                        <p class="text-xs font-semibold">หากไม่มีผู้เข้างานท่านอื่นเพิ่มเติม ไม่จำเป็นต้องกรอกส่วนนี้</p>
                    </div>
                `;
                lucide.createIcons();
            }
        }
    }

    function submitClaim(event) {
        event.preventDefault();
        Swal.showLoading();

        const form = document.getElementById('claim-form');
        const formData = new FormData(form);

        fetch('<?= base_url('science-week/evaluation/claim/store/' . esc($eval['eval_code'])) ?>', {
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
                    title: 'สำเร็จ!',
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
                
                downloadBtn.href = `<?= base_url('science-week/certificate/view-all/evaluation') ?>/${data.eval_code}`;
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

    // Phone number formatting
    const phoneInput = document.getElementById('eval_phone');
    if (phoneInput) {
        // Run on load to format existing value
        formatPhoneInput(phoneInput);

        phoneInput.addEventListener('input', function(e) {
            formatPhoneInput(e.target);
        });
    }

    function formatPhoneInput(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.startsWith('02')) {
            // Landline: 9 digits (02-XXX-XXXX)
            if (value.length > 9) value = value.slice(0, 9);
            if (value.length > 2) {
                value = value.slice(0, 2) + '-' + value.slice(2);
            }
            if (value.length > 6) {
                value = value.slice(0, 6) + '-' + value.slice(6);
            }
        } else {
            // Mobile: 10 digits (0XX-XXX-XXXX)
            if (value.length > 10) value = value.slice(0, 10);
            if (value.length > 3) {
                value = value.slice(0, 3) + '-' + value.slice(3);
            }
            if (value.length > 7) {
                value = value.slice(0, 7) + '-' + value.slice(7);
            }
        }
        input.value = value;
    }
</script>
<?= $this->endSection() ?>
