<?= $this->extend('forms/layout/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">

    <!-- Form Title Header -->
    <div class="form-card rounded-3xl p-8 border-t-8 border-indigo-600 space-y-3">
        <h2 class="text-2xl md:text-3xl font-black text-slate-900"><?= esc($form['form_title']) ?></h2>
        <?php if (!empty($form['form_description'])): ?>
            <p class="text-slate-600 text-xs md:text-sm font-medium leading-relaxed"><?= nl2br(esc($form['form_description'])) ?></p>
        <?php endif; ?>

        <?php if ($form['form_has_certificate'] == 1): ?>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-2xl text-amber-800 text-xs font-bold mt-2">
                <i data-lucide="award" class="w-4 h-4 text-amber-600"></i>
                แบบสอบถามนี้มีใบเกียรติบัตรออนไลน์ (E-Certificate) สามารถกรอกชื่อรับเกียรติบัตรได้ทันทีหลังทำแบบสอบถามเสร็จสิ้น
            </div>
        <?php endif; ?>
    </div>

    <!-- Questionnaire Form -->
    <form id="public-form" onsubmit="handleSubmit(event)" class="space-y-6">

        <!-- Dynamic Questions Blocks -->
        <?php if (!empty($fields)): ?>
            <?php 
            $secNum = 0; 
            $qNum = 0; 
            foreach ($fields as $idx => $f): 
            ?>
                <?php if ($f['field_type'] === 'section'): ?>
                    <?php 
                    $secNum++; 
                    $opts = [];
                    if (!empty($f['field_options'])) {
                        $opts = is_array($f['field_options']) ? $f['field_options'] : (json_decode($f['field_options'], true) ?: []);
                    }
                    $secDesc = $opts['description'] ?? '';
                    ?>
                    <div class="form-card rounded-3xl p-6 md:p-8 space-y-2 bg-gradient-to-r from-indigo-900 via-indigo-950 to-slate-900 text-white border border-indigo-500/30 shadow-md mt-8">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-400/20 border border-amber-400/30 rounded-xl text-amber-300 font-black text-xs">
                            <i data-lucide="layers" class="w-3.5 h-3.5"></i> ส่วนที่ <?= $secNum ?>
                        </div>
                        <h3 class="text-xl md:text-2xl font-black text-white"><?= esc($f['field_label'] ?: "ส่วนที่ {$secNum}") ?></h3>
                        <?php if (!empty($secDesc)): ?>
                            <p class="text-xs md:text-sm font-medium text-indigo-200/90 leading-relaxed pt-1 border-t border-indigo-800/60 mt-2"><?= nl2br(esc($secDesc)) ?></p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="form-card rounded-3xl p-6 md:p-8 space-y-4">
                        <label class="block text-sm md:text-base font-black text-slate-900 leading-snug">
                            <?= esc($f['field_label']) ?>
                            <?php if ($f['field_is_required'] == 1): ?>
                                <span class="text-rose-500 ml-1">*</span>
                            <?php endif; ?>
                        </label>

                        <!-- Render Input based on type -->
                        <?php if ($f['field_type'] === 'text'): ?>
                            <input type="text" name="answers[<?= $f['field_id'] ?>]" <?= $f['field_is_required'] == 1 ? 'required' : '' ?> placeholder="คำตอบของคุณ..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm">

                        <?php elseif ($f['field_type'] === 'textarea'): ?>
                            <textarea name="answers[<?= $f['field_id'] ?>]" <?= $f['field_is_required'] == 1 ? 'required' : '' ?> rows="3" placeholder="คำตอบของคุณ..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm"></textarea>

                        <?php elseif ($f['field_type'] === 'radio'): ?>
                            <?php 
                            $opts = [];
                            if (!empty($f['field_options'])) {
                                $opts = is_array($f['field_options']) ? $f['field_options'] : (json_decode($f['field_options'], true) ?: []);
                            }
                            if (!is_array($opts)) $opts = [];
                            ?>
                            <div class="space-y-2">
                                <?php foreach ($opts as $oIdx => $opt): ?>
                                    <label class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-indigo-50/50 rounded-2xl border border-slate-200/80 cursor-pointer transition-colors">
                                        <input type="radio" name="answers[<?= $f['field_id'] ?>]" value="<?= esc($opt) ?>" <?= $f['field_is_required'] == 1 ? 'required' : '' ?> class="w-5 h-5 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                        <span class="text-xs md:text-sm font-bold text-slate-700"><?= esc($opt) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($f['field_type'] === 'checkbox'): ?>
                            <?php 
                            $opts = [];
                            if (!empty($f['field_options'])) {
                                $opts = is_array($f['field_options']) ? $f['field_options'] : (json_decode($f['field_options'], true) ?: []);
                            }
                            if (!is_array($opts)) $opts = [];
                            ?>
                            <div class="space-y-2">
                                <?php foreach ($opts as $oIdx => $opt): ?>
                                    <label class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-indigo-50/50 rounded-2xl border border-slate-200/80 cursor-pointer transition-colors">
                                        <input type="checkbox" name="answers[<?= $f['field_id'] ?>][]" value="<?= esc($opt) ?>" class="w-5 h-5 text-indigo-600 rounded-md border-slate-300 focus:ring-indigo-500">
                                        <span class="text-xs md:text-sm font-bold text-slate-700"><?= esc($opt) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($f['field_type'] === 'rating'): ?>
                            <?php 
                            $opts = [];
                            if (!empty($f['field_options'])) {
                                $opts = is_array($f['field_options']) ? $f['field_options'] : (json_decode($f['field_options'], true) ?: []);
                            }
                            $maxScore = (int) ($opts['max'] ?? 5);
                            ?>
                            <div class="flex items-center justify-between max-w-xl mx-auto p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <?php for ($r = 1; $r <= $maxScore; $r++): ?>
                                    <label class="flex flex-col items-center gap-1 cursor-pointer">
                                        <input type="radio" name="answers[<?= $f['field_id'] ?>]" value="<?= $r ?>" <?= $f['field_is_required'] == 1 ? 'required' : '' ?> class="w-5 h-5 text-amber-500 border-slate-300 focus:ring-amber-400">
                                        <span class="text-xs font-black text-slate-600"><?= $r ?></span>
                                    </label>
                                <?php endfor; ?>
                            </div>
                            <div class="flex justify-between text-[11px] font-bold text-slate-400 max-w-xl mx-auto px-2">
                                <span>น้อยที่สุด (1)</span>
                                <span>มากที่สุด (<?= $maxScore ?>)</span>
                            </div>
                        <?php elseif ($f['field_type'] === 'rating_grid'): ?>
                            <?php 
                            $opts = [];
                            if (!empty($f['field_options'])) {
                                $opts = is_array($f['field_options']) ? $f['field_options'] : (json_decode($f['field_options'], true) ?: []);
                            }
                            $maxScore = (int) ($opts['max'] ?? 5);
                            $gridItems = is_array($opts['items'] ?? null) ? $opts['items'] : [];
                            ?>
                            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                                <table class="w-full text-left text-xs md:text-sm">
                                    <thead class="bg-slate-100/80 text-slate-700 font-bold border-b border-slate-200">
                                        <tr>
                                            <th class="p-3 md:p-4 min-w-[200px]">ข้อประเมินย่อย</th>
                                            <?php for ($r = 1; $r <= $maxScore; $r++): ?>
                                                <th class="p-2 md:p-3 text-center min-w-[45px]"><?= $r ?></th>
                                            <?php endfor; ?>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        <?php foreach ($gridItems as $gIdx => $gItem): ?>
                                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                                <td class="p-3 md:p-4 font-semibold text-slate-800">
                                                    <?= $gIdx + 1 ?>. <?= esc($gItem ?: "ข้อคำถามย่อยที่ " . ($gIdx + 1)) ?>
                                                </td>
                                                <?php for ($r = 1; $r <= $maxScore; $r++): ?>
                                                    <td class="p-2 md:p-3 text-center">
                                                        <input type="radio" name="answers[<?= $f['field_id'] ?>][<?= $gIdx ?>]" value="<?= esc($gItem ?: "ข้อ " . ($gIdx + 1)) ?>: <?= $r ?>" <?= $f['field_is_required'] == 1 ? 'required' : '' ?> class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 cursor-pointer">
                                                    </td>
                                                <?php endfor; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Submit Button -->
        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-sm flex items-center gap-2 shadow-xl shadow-indigo-200 transition-all hover:scale-[1.02]">
                <i data-lucide="send" class="w-5 h-5"></i> ส่งแบบสอบถาม
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    async function handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        Swal.fire({
            title: 'กำลังส่งข้อมูล...',
            text: 'โปรดรอสักครู่',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const res = await fetch('<?= base_url("forms/submit/{$form['form_id']}") ?>', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.status === 'success') {
            window.location.href = data.redirect;
        } else {
            Swal.fire({ icon: 'error', title: 'ไม่สามารถส่งได้', text: data.message });
        }
    }
</script>
<?= $this->endSection() ?>
