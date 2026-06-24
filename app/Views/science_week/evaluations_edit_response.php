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
    .rating-btn {
        transition: all 0.2s ease-in-out;
    }
    .rating-btn:hover {
        transform: scale(1.05);
    }
</style>

<!-- Header Section -->
<div class="mb-6">
    <a href="<?= base_url('staff/science-week/evaluations') ?>" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-semibold transition-colors text-sm mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปหน้ารายการ
    </a>
    <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow">
        <span>แก้ไขข้อมูลผู้ทำแบบประเมิน</span>
    </h2>
    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">แก้ไขรายละเอียดส่วนบุคคล คะแนนประเมิน หรือความคิดเห็นของผู้ร่วมกิจกรรมรายนี้</p>
</div>

<!-- Form Card -->
<div class="glass-card rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900/60 max-w-3xl border border-slate-200 dark:border-slate-800">
    <form action="<?= base_url('staff/science-week/evaluations/update/' . $eval['eval_id']) ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <!-- Fullname (Primary required field) -->
        <div class="space-y-2">
            <label for="fullname" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-cyan-400"></i> ชื่อ-นามสกุล <span class="text-rose-450">*</span>
            </label>
            <input type="text" name="fullname" id="fullname" required value="<?= old('fullname', $eval['eval_name'] ?? '') ?>" placeholder="ชื่อ-นามสกุล ผู้ได้รับเกียรติบัตร..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
        </div>

        <!-- Dynamic Fields -->
        <?php if (!empty($form_config['fields'])): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php 
                foreach ($form_config['fields'] as $field):
                    // Extract existing value from user record
                    $fieldVal = $eval['custom_fields'][$field['key']] ?? '';
                    if (empty($fieldVal)) {
                        // Fallback checking for key names
                        if ($field['key'] === 'phone') $fieldVal = $eval['eval_phone'];
                        elseif ($field['key'] === 'school') $fieldVal = $eval['eval_school'];
                        elseif ($field['key'] === 'province') $fieldVal = $eval['eval_province'];
                    }
                ?>
                    <div class="space-y-2">
                        <label for="field_<?= $field['key'] ?>" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                            <i data-lucide="info" class="w-4 h-4 text-indigo-400"></i> <?= esc($field['label']) ?> <?= $field['required'] ? '<span class="text-rose-450">*</span>' : '' ?>
                        </label>
                        <input type="<?= esc($field['type']) ?>" name="fields[<?= esc($field['key']) ?>]" id="field_<?= $field['key'] ?>" <?= $field['required'] ? 'required' : '' ?> value="<?= old("fields.{$field['key']}", $fieldVal) ?>" placeholder="<?= esc($field['placeholder']) ?>" class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Dynamic Ratings Section -->
        <?php if (!empty($form_config['questions'])): ?>
            <div class="space-y-5 border-t border-slate-800/80 pt-6">
                <h3 class="text-sm font-black text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="star" class="w-4 h-4"></i> ระดับความพึงพอใจ
                </h3>
                <p class="text-[11px] text-slate-400">ระบุคะแนนประเมิน (5 = พึงพอใจมากที่สุด, 1 = พึงพอใจน้อยที่สุด)</p>

                <?php 
                foreach ($form_config['questions'] as $q):
                    $currentVal = $eval['ratings'][$q['key']] ?? '';
                ?>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-200"><?= esc($q['label']) ?> <span class="text-rose-450">*</span></label>
                        <div class="flex items-center gap-2">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <label class="flex-1 max-w-[60px] text-center cursor-pointer">
                                    <input type="radio" name="ratings[<?= esc($q['key']) ?>]" value="<?= $i ?>" required class="peer hidden" <?= (string)$currentVal === (string)$i ? 'checked' : '' ?>>
                                    <div class="rating-btn py-2 text-xs font-bold rounded-xl border border-slate-700 bg-slate-950/60 text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 transition-colors">
                                        <?= $i ?>
                                    </div>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Comments -->
        <div class="space-y-2 border-t border-slate-800/80 pt-6">
            <label for="comments" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <i data-lucide="message-square" class="w-4 h-4 text-emerald-400"></i> <?= esc($form_config['comment_label'] ?? 'ข้อเสนอแนะอื่นๆ สำหรับการปรับปรุงครั้งต่อไป') ?>
            </label>
            <textarea name="comments" id="comments" rows="3" placeholder="ระบุข้อคิดเห็นหรือข้อเสนอแนะเพิ่มเติม..." class="w-full px-4 py-3 neon-input rounded-2xl text-xs outline-none transition-colors resize-none"><?= old('comments', $eval['comments'] ?? '') ?></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-4 text-white font-bold rounded-2xl bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 shadow-lg shadow-indigo-950/20 transition-all flex items-center justify-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> อัปเดตข้อมูลผู้ประเมิน
        </button>
    </form>
</div>
<?= $this->endSection() ?>
