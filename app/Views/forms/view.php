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
                        <label class="block text-sm md:text-base font-black text-slate-900 leading-relaxed break-words">
                            <?= nl2br(esc($f['field_label'])) ?>
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
                            $rawOpts = [];
                            if (!empty($f['field_options'])) {
                                $rawOpts = is_array($f['field_options']) ? $f['field_options'] : (json_decode($f['field_options'], true) ?: []);
                            }

                            $hasOther = false;
                            $rawList = [];
                            if (is_array($rawOpts)) {
                                if (isset($rawOpts['options']) && is_array($rawOpts['options'])) {
                                    $rawList = $rawOpts['options'];
                                    $hasOther = !empty($rawOpts['has_other']);
                                } else {
                                    $rawList = array_values($rawOpts);
                                }
                            }

                            $opts = [];
                            foreach ($rawList as $v) {
                                if (is_array($v)) {
                                    foreach ($v as $subV) {
                                        if (is_scalar($subV) && trim((string)$subV) !== '') $opts[] = (string)$subV;
                                    }
                                } elseif (is_scalar($v) && trim((string)$v) !== '') {
                                    $opts[] = (string)$v;
                                }
                            }

                            if ($hasOther) {
                                $alreadyHasOther = false;
                                foreach ($opts as $o) {
                                    if (mb_strpos($o, 'อื่นๆ') !== false || mb_strpos(mb_strtolower($o), 'other') !== false) {
                                        $alreadyHasOther = true;
                                        break;
                                    }
                                }
                                if (!$alreadyHasOther) {
                                    $opts[] = 'อื่นๆ (โปรดระบุ)';
                                }
                            }
                            ?>
                            <div class="space-y-2.5" id="radio-group-<?= $f['field_id'] ?>">
                                <?php foreach ($opts as $oIdx => $opt): ?>
                                    <?php 
                                    $isOther = (mb_strpos($opt, 'อื่นๆ') !== false || mb_strpos(mb_strtolower($opt), 'other') !== false);
                                    $containerId = $isOther ? "radio-input-container-{$f['field_id']}-{$oIdx}" : '';
                                    ?>
                                    <div class="space-y-2">
                                        <label class="radio-card-option flex items-center gap-3.5 p-4 md:p-4.5 bg-white hover:bg-indigo-50/40 rounded-2xl border-2 border-slate-200 cursor-pointer transition-all shadow-sm has-[:checked]:bg-indigo-50/90 has-[:checked]:border-indigo-600 has-[:checked]:shadow-md">
                                            <input type="radio" id="radio-opt-<?= $f['field_id'] ?>-<?= $oIdx ?>" name="answers[<?= $f['field_id'] ?>]" value="<?= esc($opt) ?>" <?= $f['field_is_required'] == 1 ? 'required' : '' ?> onchange="onRadioSelect('<?= $f['field_id'] ?>', '<?= $containerId ?>')" class="w-6 h-6 text-indigo-600 border-2 border-slate-300 focus:ring-indigo-500 shrink-0 cursor-pointer">
                                            <span class="text-sm md:text-base font-extrabold text-slate-800 flex-1 leading-relaxed break-words"><?= esc($opt) ?></span>
                                        </label>
                                        <?php if ($isOther): ?>
                                            <div id="<?= $containerId ?>" class="hidden pl-4 md:pl-8 pt-1.5 other-input-group-<?= $f['field_id'] ?>">
                                                <input type="text" placeholder="โปรดระบุคำตอบของคุณ..." oninput="syncRadioVal(this, 'radio-opt-<?= $f['field_id'] ?>-<?= $oIdx ?>', '<?= esc($opt, 'js') ?>')" class="w-full px-4 py-3.5 rounded-2xl border-2 border-slate-200 text-sm font-bold bg-white focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($f['field_type'] === 'checkbox'): ?>
                            <?php 
                            $rawOpts = [];
                            if (!empty($f['field_options'])) {
                                $rawOpts = is_array($f['field_options']) ? $f['field_options'] : (json_decode($f['field_options'], true) ?: []);
                            }

                            $hasOther = false;
                            $rawList = [];
                            if (is_array($rawOpts)) {
                                if (isset($rawOpts['options']) && is_array($rawOpts['options'])) {
                                    $rawList = $rawOpts['options'];
                                    $hasOther = !empty($rawOpts['has_other']);
                                } else {
                                    $rawList = array_values($rawOpts);
                                }
                            }

                            $opts = [];
                            foreach ($rawList as $v) {
                                if (is_array($v)) {
                                    foreach ($v as $subV) {
                                        if (is_scalar($subV) && trim((string)$subV) !== '') $opts[] = (string)$subV;
                                    }
                                } elseif (is_scalar($v) && trim((string)$v) !== '') {
                                    $opts[] = (string)$v;
                                }
                            }

                            if ($hasOther) {
                                $alreadyHasOther = false;
                                foreach ($opts as $o) {
                                    if (mb_strpos($o, 'อื่นๆ') !== false || mb_strpos(mb_strtolower($o), 'other') !== false) {
                                        $alreadyHasOther = true;
                                        break;
                                    }
                                }
                                if (!$alreadyHasOther) {
                                    $opts[] = 'อื่นๆ (โปรดระบุ)';
                                }
                            }
                            ?>
                            <div class="space-y-2.5">
                                <?php foreach ($opts as $oIdx => $opt): ?>
                                    <?php 
                                    $isOther = (mb_strpos($opt, 'อื่นๆ') !== false || mb_strpos(mb_strtolower($opt), 'other') !== false);
                                    $containerId = $isOther ? "chk-input-container-{$f['field_id']}-{$oIdx}" : '';
                                    ?>
                                    <div class="space-y-2">
                                        <label class="chk-card-option flex items-center gap-3.5 p-4 md:p-4.5 bg-white hover:bg-indigo-50/40 rounded-2xl border-2 border-slate-200 cursor-pointer transition-all shadow-sm has-[:checked]:bg-indigo-50/90 has-[:checked]:border-indigo-600 has-[:checked]:shadow-md">
                                            <input type="checkbox" id="chk-opt-<?= $f['field_id'] ?>-<?= $oIdx ?>" name="answers[<?= $f['field_id'] ?>][]" value="<?= esc($opt) ?>" onchange="onChkSelect(this, '<?= $containerId ?>')" class="w-6 h-6 text-indigo-600 rounded-lg border-2 border-slate-300 focus:ring-indigo-500 shrink-0 cursor-pointer">
                                            <span class="text-sm md:text-base font-extrabold text-slate-800 flex-1 leading-relaxed break-words"><?= esc($opt) ?></span>
                                        </label>
                                        <?php if ($isOther): ?>
                                            <div id="<?= $containerId ?>" class="hidden pl-4 md:pl-8 pt-1.5">
                                                <input type="text" placeholder="โปรดระบุคำตอบของคุณ..." oninput="syncChkVal(this, 'chk-opt-<?= $f['field_id'] ?>-<?= $oIdx ?>', '<?= esc($opt, 'js') ?>')" class="w-full px-4 py-3.5 rounded-2xl border-2 border-slate-200 text-sm font-bold bg-white focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500">
                                            </div>
                                        <?php endif; ?>
                                    </div>
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
                            <div class="space-y-2 w-full">
                                <div class="grid grid-cols-5 gap-2 md:gap-3.5 w-full p-2.5 md:p-3 bg-slate-100/90 rounded-2xl border border-slate-200 shadow-inner">
                                    <?php for ($r = $maxScore; $r >= 1; $r--): ?>
                                        <label class="rating-pill flex flex-col items-center justify-center p-3 md:p-4 bg-white rounded-xl border-2 border-slate-200 cursor-pointer transition-all shadow-sm hover:border-amber-400 hover:bg-amber-50/50 has-[:checked]:bg-gradient-to-b has-[:checked]:from-amber-400 has-[:checked]:to-amber-500 has-[:checked]:border-amber-500 has-[:checked]:text-white has-[:checked]:shadow-md has-[:checked]:scale-[1.03]">
                                            <input type="radio" name="answers[<?= $f['field_id'] ?>]" value="<?= $r ?>" <?= $f['field_is_required'] == 1 ? 'required' : '' ?> class="sr-only peer">
                                            <span class="text-lg md:text-2xl font-black text-slate-800 peer-checked:text-white leading-none"><?= $r ?></span>
                                            <span class="text-[10px] md:text-xs font-bold text-slate-400 peer-checked:text-amber-100 mt-1.5">
                                                <?php
                                                if ($r == 5) echo 'ดีมาก';
                                                elseif ($r == 4) echo 'ดี';
                                                elseif ($r == 3) echo 'ปานกลาง';
                                                elseif ($r == 2) echo 'พอใช้';
                                                elseif ($r == 1) echo 'ปรับปรุง';
                                                ?>
                                            </span>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                                <div class="flex justify-between text-xs font-bold text-slate-500 w-full px-2">
                                    <span class="text-amber-700">★ มากที่สุด (คะแนน <?= $maxScore ?>)</span>
                                    <span class="text-slate-400">น้อยที่สุด (คะแนน 1)</span>
                                </div>
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
                            
                            <!-- Mobile Cards Layout (sm/xs) -->
                            <div class="space-y-4 md:hidden">
                                <?php foreach ($gridItems as $gIdx => $gItem): ?>
                                    <div class="p-4 bg-slate-50/90 rounded-2xl border-2 border-slate-200 space-y-3 shadow-sm">
                                        <p class="text-sm font-extrabold text-slate-900 leading-relaxed">
                                            <?= $gIdx + 1 ?>. <?= esc($gItem ?: "ข้อคำถามย่อยที่ " . ($gIdx + 1)) ?>
                                        </p>
                                        <div class="grid grid-cols-5 gap-1.5 p-2 bg-white rounded-xl border border-slate-200">
                                            <?php for ($r = $maxScore; $r >= 1; $r--): ?>
                                                <label class="flex flex-col items-center justify-center p-2.5 bg-slate-50 rounded-xl border-2 border-slate-200 cursor-pointer transition-all has-[:checked]:bg-indigo-600 has-[:checked]:border-indigo-600 has-[:checked]:text-white">
                                                    <input type="radio" name="answers[<?= $f['field_id'] ?>][<?= $gIdx ?>]" value="<?= esc($gItem ?: "ข้อ " . ($gIdx + 1)) ?>: <?= $r ?>" <?= $f['field_is_required'] == 1 ? 'required' : '' ?> class="sr-only peer">
                                                    <span class="text-base font-black text-slate-800 peer-checked:text-white"><?= $r ?></span>
                                                </label>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="flex justify-between text-[11px] font-extrabold text-slate-400 px-1">
                                            <span class="text-indigo-600">มากที่สุด (<?= $maxScore ?>)</span>
                                            <span>น้อยที่สุด (1)</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Desktop Table Layout (md+) -->
                            <div class="hidden md:block overflow-x-auto rounded-2xl border-2 border-slate-200">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-slate-100/90 text-slate-800 font-extrabold border-b-2 border-slate-200">
                                        <tr>
                                            <th class="p-4 min-w-[220px]">ข้อประเมินย่อย</th>
                                            <?php for ($r = $maxScore; $r >= 1; $r--): ?>
                                                <th class="p-3 text-center min-w-[50px]"><?= $r ?></th>
                                            <?php endfor; ?>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        <?php foreach ($gridItems as $gIdx => $gItem): ?>
                                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                                <td class="p-4 font-bold text-slate-800">
                                                    <?= $gIdx + 1 ?>. <?= esc($gItem ?: "ข้อคำถามย่อยที่ " . ($gIdx + 1)) ?>
                                                </td>
                                                <?php for ($r = $maxScore; $r >= 1; $r--): ?>
                                                    <td class="p-3 text-center">
                                                        <label class="flex items-center justify-center w-full h-full p-2 cursor-pointer hover:bg-indigo-100/60 rounded-xl transition-colors">
                                                            <input type="radio" name="answers[<?= $f['field_id'] ?>][<?= $gIdx ?>]" value="<?= esc($gItem ?: "ข้อ " . ($gIdx + 1)) ?>: <?= $r ?>" <?= $f['field_is_required'] == 1 ? 'required' : '' ?> class="w-6 h-6 text-indigo-600 border-2 border-slate-300 focus:ring-indigo-500 cursor-pointer">
                                                        </label>
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

        const res = await fetch('<?= base_url("forms/submit/" . (!empty($form['form_code']) ? $form['form_code'] : $form['form_id'])) ?>', {
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

    function onRadioSelect(fieldId, targetContainerId) {
        document.querySelectorAll('.other-input-group-' + fieldId).forEach(el => {
            el.classList.add('hidden');
        });
        if (targetContainerId) {
            const container = document.getElementById(targetContainerId);
            if (container) {
                container.classList.remove('hidden');
                const input = container.querySelector('input[type="text"]');
                if (input) input.focus();
            }
        }
    }

    function syncRadioVal(input, radioId, originalLabel) {
        const radio = document.getElementById(radioId);
        if (radio) {
            const val = input.value.trim();
            radio.value = val ? (originalLabel + ': ' + val) : originalLabel;
        }
    }

    function onChkSelect(chk, targetContainerId) {
        if (!targetContainerId) return;
        const container = document.getElementById(targetContainerId);
        if (container) {
            if (chk.checked) {
                container.classList.remove('hidden');
                const input = container.querySelector('input[type="text"]');
                if (input) input.focus();
            } else {
                container.classList.add('hidden');
            }
        }
    }

    function syncChkVal(input, chkId, originalLabel) {
        const chk = document.getElementById(chkId);
        if (chk) {
            const val = input.value.trim();
            chk.value = val ? (originalLabel + ': ' + val) : originalLabel;
        }
    }
</script>
<?= $this->endSection() ?>
