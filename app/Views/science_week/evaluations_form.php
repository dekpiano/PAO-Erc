<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<style>
    .neon-input {
        background: rgba(15, 23, 42, 0.7) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        color: #f1f5f9 !important;
        transition: all 0.3s ease;
    }
    .neon-input:focus {
        border-color: #22d3ee !important;
        box-shadow: 0 0 15px rgba(34, 211, 238, 0.3) !important;
        background: rgba(15, 23, 42, 0.9) !important;
        outline: none;
    }
    .neon-input option {
        background-color: #0f172a;
        color: #f1f5f9;
    }
    .builder-card {
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid rgba(99, 102, 241, 0.2);
    }
    .btn-action {
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
</style>

<!-- Header Section -->
<div class="mb-6">
    <a href="<?= base_url('staff/science-week/evaluations') ?>" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-semibold transition-colors text-sm mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปหน้ารายการ
    </a>
    <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow">
        <span>ตั้งค่าโครงสร้างฟอร์มประเมินความพึงพอใจ</span>
    </h2>
    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">ออกแบบโครงสร้างฟิลด์กรอกข้อมูลและข้อคำถามความพึงพอใจของหน้าบ้านได้ด้วยตนเอง</p>
</div>

<!-- Form Configurator -->
<form action="<?= base_url('staff/science-week/evaluations/store') ?>" method="POST" class="space-y-8">
    <?= csrf_field() ?>

    <!-- 1. General Config -->
    <div class="glass-card rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 space-y-4">
        <h3 class="text-sm font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2">
            <i data-lucide="layout-template" class="w-4 h-4"></i> ข้อมูลทั่วไปของฟอร์มประเมิน
        </h3>
        
        <div class="grid grid-cols-1 gap-4">
            <div class="space-y-2">
                <label for="form_title" class="block text-xs font-bold text-slate-700 dark:text-slate-350">หัวข้อหลักของแบบฟอร์ม *</label>
                <input type="text" name="form_title" id="form_title" required value="<?= esc($form_config['title'] ?? 'แบบประเมินความพึงพอใจ') ?>" placeholder="เช่น แบบประเมินความพึงพอใจกิจกรรมสัปดาห์วิทยาศาสตร์..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none">
            </div>

            <div class="space-y-2">
                <label for="form_subtitle" class="block text-xs font-bold text-slate-700 dark:text-slate-350">คำอธิบาย/หัวข้อย่อย</label>
                <input type="text" name="form_subtitle" id="form_subtitle" value="<?= esc($form_config['subtitle'] ?? '') ?>" placeholder="เช่น ร่วมประเมินการจัดกิจกรรมเพื่อดาวน์โหลดรับเกียรติบัตร..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none">
            </div>
        </div>
    </div>

    <!-- 2. Dynamic Input Fields Builder -->
    <div class="glass-card rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 space-y-6">
        <div class="flex justify-between items-center">
            <h3 class="text-sm font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                <i data-lucide="text-cursor-input" class="w-4 h-4"></i> ฟิลด์กรอกข้อมูลผู้ทำแบบประเมิน
            </h3>
            <button type="button" onclick="addFieldRow()" class="px-3 py-1.5 rounded-xl bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-300 hover:text-white border border-indigo-500/20 text-[10px] sm:text-xs font-bold flex items-center gap-1 transition-colors">
                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> เพิ่มฟิลด์กรอกข้อมูล
            </button>
        </div>

        <p class="text-[10px] text-slate-400 leading-relaxed">
            * ฟิลด์ <strong>ชื่อ-นามสกุล (fullname)</strong> เป็นฟิลด์หลักถาวรที่ระบบจำเป็นต้องใช้งานเพื่อนำไปจัดพิมพ์ลงบนเกียรติบัตร จึงไม่สามารถแก้ไขหรือลบออกได้
        </p>

        <!-- Fields Container -->
        <div id="fields-container" class="space-y-4">
            <!-- Permanent Fullname Row -->
            <div class="builder-card p-4 rounded-2xl flex flex-col md:flex-row gap-3 items-start md:items-center border border-indigo-500/30 bg-indigo-950/10">
                <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 mb-1">คีย์ระบุฟิลด์ (System Key)</label>
                        <input type="text" value="fullname" disabled class="w-full px-3 py-2 bg-slate-950/60 border border-slate-800 text-slate-500 rounded-xl text-[11px] font-mono outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 mb-1">ชื่อฟิลด์ (Label) *</label>
                        <input type="text" value="ชื่อ-นามสกุล" disabled class="w-full px-3 py-2 bg-slate-950/60 border border-slate-800 text-slate-500 rounded-xl text-[11px] outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 mb-1">ประเภท</label>
                        <input type="text" value="Text" disabled class="w-full px-3 py-2 bg-slate-950/60 border border-slate-800 text-slate-500 rounded-xl text-[11px] outline-none">
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-3 md:pt-0 self-end md:self-center">
                    <label class="inline-flex items-center gap-1.5 cursor-not-allowed">
                        <input type="checkbox" checked disabled class="rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-[10px] font-extrabold text-indigo-400 uppercase">บังคับ</span>
                    </label>
                    <button type="button" disabled class="p-2 text-slate-600 rounded-xl border border-transparent cursor-not-allowed" title="ไม่สามารถลบฟิลด์หลักได้">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- Dynamic Fields -->
            <?php 
            $fieldIndex = 0;
            foreach ($form_config['fields'] as $f): 
                $fieldIndex++;
            ?>
                <div class="builder-card p-4 rounded-2xl flex flex-col md:flex-row gap-3 items-start md:items-center border border-slate-800 bg-slate-950/10 field-row">
                    <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 mb-1">คีย์ระบุฟิลด์ (System Key) *</label>
                            <input type="text" name="fields[<?= $fieldIndex ?>][key]" required value="<?= esc($f['key']) ?>" placeholder="เช่น school, phone..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] font-mono outline-none focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 mb-1">ชื่อฟิลด์ (Label) *</label>
                            <input type="text" name="fields[<?= $fieldIndex ?>][label]" required value="<?= esc($f['label']) ?>" placeholder="เช่น สถานศึกษา, เบอร์โทร..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] outline-none focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 mb-1">ข้อความแนะนำ (Placeholder)</label>
                            <input type="text" name="fields[<?= $fieldIndex ?>][placeholder]" value="<?= esc($f['placeholder']) ?>" placeholder="เช่น กรอกโรงเรียน..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] outline-none focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 mb-1">ประเภทฟิลด์</label>
                            <select name="fields[<?= $fieldIndex ?>][type]" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] outline-none focus:border-indigo-500 transition-colors">
                                <option value="text" <?= $f['type'] === 'text' ? 'selected' : '' ?>>Text (ข้อความทั่วไป)</option>
                                <option value="tel" <?= $f['type'] === 'tel' ? 'selected' : '' ?>>Tel (เบอร์โทรศัพท์)</option>
                                <option value="email" <?= $f['type'] === 'email' ? 'selected' : '' ?>>Email (อีเมล)</option>
                                <option value="number" <?= $f['type'] === 'number' ? 'selected' : '' ?>>Number (ตัวเลข)</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 pt-3 md:pt-0 self-end md:self-center">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="fields[<?= $fieldIndex ?>][required]" value="1" <?= !empty($f['required']) ? 'checked' : '' ?> class="rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-[10px] font-extrabold text-slate-350 uppercase">บังคับ</span>
                        </label>
                        <button type="button" onclick="this.closest('.field-row').remove()" class="p-2 text-rose-500 hover:bg-rose-950/20 rounded-xl border border-rose-800/10 hover:border-rose-500/30 transition-colors" title="ลบฟิลด์นี้">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 3. Dynamic Rating Questions Builder -->
    <div class="glass-card rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 space-y-6">
        <div class="flex justify-between items-center">
            <h3 class="text-sm font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                <i data-lucide="star" class="w-4 h-4"></i> ข้อคำถามประเมินระดับความพึงพอใจ (คะแนน 1-5)
            </h3>
            <button type="button" onclick="addQuestionRow()" class="px-3 py-1.5 rounded-xl bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-300 hover:text-white border border-indigo-500/20 text-[10px] sm:text-xs font-bold flex items-center gap-1 transition-colors">
                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> เพิ่มข้อคำถาม
            </button>
        </div>

        <!-- Questions Container -->
        <div id="questions-container" class="space-y-4">
            <?php 
            $qIndex = 0;
            foreach ($form_config['questions'] as $q): 
                $qIndex++;
            ?>
                <div class="builder-card p-4 rounded-2xl flex gap-3 items-center border border-slate-800 bg-slate-950/10 question-row">
                    <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-5 gap-3">
                        <div class="sm:col-span-1">
                            <label class="block text-[10px] font-bold text-slate-400 mb-1">คีย์ข้อคำถาม (System Key) *</label>
                            <input type="text" name="questions[<?= $qIndex ?>][key]" required value="<?= esc($q['key']) ?>" placeholder="เช่น q1, q2..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] font-mono outline-none focus:border-indigo-500 transition-colors">
                        </div>
                        <div class="sm:col-span-4">
                            <label class="block text-[10px] font-bold text-slate-400 mb-1">คำอธิบายคำถามความพึงพอใจ *</label>
                            <input type="text" name="questions[<?= $qIndex ?>][label]" required value="<?= esc($q['label']) ?>" placeholder="ระบุข้อความอธิบายหัวข้อที่จะให้ประเมินคะแนน..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] outline-none focus:border-indigo-500 transition-colors">
                        </div>
                    </div>
                    <div class="flex items-center pt-5">
                        <button type="button" onclick="this.closest('.question-row').remove()" class="p-2 text-rose-500 hover:bg-rose-950/20 rounded-xl border border-rose-800/10 hover:border-rose-500/30 transition-colors" title="ลบข้อนี้">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 4. Comments & Suggestions Config -->
    <div class="glass-card rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 space-y-4">
        <h3 class="text-sm font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2">
            <i data-lucide="message-square" class="w-4 h-4"></i> ส่วนกล่องข้อเสนอแนะเพิ่มเติม
        </h3>
        
        <div class="space-y-2">
            <label for="comment_label" class="block text-xs font-bold text-slate-700 dark:text-slate-350">ชื่อป้ายคำอธิบายกล่องข้อเสนอแนะ</label>
            <input type="text" name="comment_label" id="comment_label" value="<?= esc($form_config['comment_label'] ?? 'ข้อเสนอแนะอื่นๆ สำหรับการปรับปรุงครั้งต่อไป') ?>" placeholder="เช่น ข้อเขียนแนะนำ, ข้อเสนอแนะอื่นๆ..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs sm:text-sm outline-none">
        </div>
    </div>

    <!-- Submit Config -->
    <button type="submit" class="w-full py-4 text-white font-bold rounded-2xl bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 shadow-lg shadow-indigo-950/20 transition-all flex items-center justify-center gap-2 btn-action">
        <i data-lucide="save" class="w-5 h-5 text-cyan-300"></i> บันทึกโครงสร้างฟอร์มประเมิน
    </button>
</form>

<script>
    let fieldCounter = <?= $fieldIndex ?>;
    let questionCounter = <?= $qIndex ?>;

    function addFieldRow() {
        fieldCounter++;
        const container = document.getElementById('fields-container');
        const div = document.createElement('div');
        div.className = 'builder-card p-4 rounded-2xl flex flex-col md:flex-row gap-3 items-start md:items-center border border-slate-800 bg-slate-950/10 field-row';
        div.innerHTML = `
            <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 mb-1">คีย์ระบุฟิลด์ (System Key) *</label>
                    <input type="text" name="fields[${fieldCounter}][key]" required placeholder="เช่น school, phone..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] font-mono outline-none focus:border-indigo-500 transition-colors">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 mb-1">ชื่อฟิลด์ (Label) *</label>
                    <input type="text" name="fields[${fieldCounter}][label]" required placeholder="เช่น สถานศึกษา, เบอร์โทร..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] outline-none focus:border-indigo-500 transition-colors">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 mb-1">ข้อความแนะนำ (Placeholder)</label>
                    <input type="text" name="fields[${fieldCounter}][placeholder]" placeholder="เช่น กรอกโรงเรียน..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] outline-none focus:border-indigo-500 transition-colors">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 mb-1">ประเภทฟิลด์</label>
                    <select name="fields[${fieldCounter}][type]" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] outline-none focus:border-indigo-500 transition-colors">
                        <option value="text">Text (ข้อความทั่วไป)</option>
                        <option value="tel">Tel (เบอร์โทรศัพท์)</option>
                        <option value="email">Email (อีเมล)</option>
                        <option value="number">Number (ตัวเลข)</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-3 md:pt-0 self-end md:self-center">
                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" name="fields[${fieldCounter}][required]" value="1" class="rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-[10px] font-extrabold text-slate-350 uppercase">บังคับ</span>
                </label>
                <button type="button" onclick="this.closest('.field-row').remove()" class="p-2 text-rose-500 hover:bg-rose-950/20 rounded-xl border border-rose-800/10 hover:border-rose-500/30 transition-colors" title="ลบฟิลด์นี้">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        lucide.createIcons();
    }

    function addQuestionRow() {
        questionCounter++;
        const container = document.getElementById('questions-container');
        const div = document.createElement('div');
        div.className = 'builder-card p-4 rounded-2xl flex gap-3 items-center border border-slate-800 bg-slate-950/10 question-row';
        div.innerHTML = `
            <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-5 gap-3">
                <div class="sm:col-span-1">
                    <label class="block text-[10px] font-bold text-slate-400 mb-1">คีย์ข้อคำถาม (System Key) *</label>
                    <input type="text" name="questions[${questionCounter}][key]" required value="q${questionCounter}" placeholder="เช่น q1, q2..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] font-mono outline-none focus:border-indigo-500 transition-colors">
                </div>
                <div class="sm:col-span-4">
                    <label class="block text-[10px] font-bold text-slate-400 mb-1">คำอธิบายคำถามความพึงพอใจ *</label>
                    <input type="text" name="questions[${questionCounter}][label]" required placeholder="ระบุข้อความอธิบายหัวข้อที่จะให้ประเมินคะแนน..." class="w-full px-3 py-2 bg-slate-950 border border-slate-800 text-slate-200 rounded-xl text-[11px] outline-none focus:border-indigo-500 transition-colors">
                </div>
            </div>
            <div class="flex items-center pt-5">
                <button type="button" onclick="this.closest('.question-row').remove()" class="p-2 text-rose-500 hover:bg-rose-950/20 rounded-xl border border-rose-800/10 hover:border-rose-500/30 transition-colors" title="ลบข้อนี้">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        lucide.createIcons();
    }
</script>
<?= $this->endSection() ?>
