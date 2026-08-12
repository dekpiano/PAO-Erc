<?= $this->extend('forms/layout/admin') ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<?php
// Compute overall stats
$totalSubmissions = count($submissions);
$certCount = 0;
$latestSubmission = !empty($submissions) ? $submissions[0]['sub_submitted_at'] : null;

if ($form['form_has_certificate'] == 1) {
    foreach ($submissions as $s) {
        if (!empty($s['sub_cert_code'])) $certCount++;
    }
}

// Keywords that indicate personal identity, contact info, or open comments
$personalKeywords = ['ชื่อ', 'นามสกุล', 'เบอร์', 'โทร', 'phone', 'tel', 'อีเมล', 'email', 'ที่อยู่', 'ข้อเสนอแนะ', 'ความคิดเห็น', 'หมายเหตุ', 'comment', 'รายละเอียด'];

// Compute field analytics map with Smart Display Auto-Detection
$fieldAnalytics = [];
foreach ($fields as $f) {
    $fId = $f['field_id'];
    $fType = $f['field_type'];
    $fLabel = $f['field_label'];
    $counts = [];
    $textList = [];
    $answeredCount = 0;
    $ratingSum = 0;
    $ratingCount = 0;

    // Check if label contains personal/contact/comment keywords or if type is text/textarea
    $isPersonal = false;
    if ($fType === 'text' || $fType === 'textarea') {
        $isPersonal = true;
    } else {
        $labelLower = mb_strtolower($fLabel);
        foreach ($personalKeywords as $kw) {
            if (mb_strpos($labelLower, $kw) !== false) {
                $isPersonal = true;
                break;
            }
        }
    }

    if ($fType === 'rating') {
        $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    }

    foreach ($submissions as $s) {
        $ansVal = $s['answers'][$fId] ?? null;
        if ($ansVal !== null && trim((string)$ansVal) !== '') {
            $answeredCount++;
            $cleanVal = trim((string)$ansVal);

            if ($isPersonal) {
                $textList[] = [
                    'text' => $cleanVal,
                    'name' => $s['sub_responder_name'] ?: 'ผู้ตอบแบบสอบถาม',
                    'date' => $s['sub_submitted_at']
                ];
            } else {
                if ($fType === 'checkbox') {
                    $vals = array_map('trim', explode(',', $ansVal));
                    foreach ($vals as $v) {
                        if ($v !== '') $counts[$v] = ($counts[$v] ?? 0) + 1;
                    }
                } elseif ($fType === 'rating') {
                    $num = (int) $ansVal;
                    if ($num >= 1 && $num <= 5) {
                        $counts[$num] = ($counts[$num] ?? 0) + 1;
                        $ratingSum += $num;
                        $ratingCount++;
                    }
                } else {
                    $counts[$cleanVal] = ($counts[$cleanVal] ?? 0) + 1;
                }
            }
        }
    }

    // Pre-populate defined choices if answers are empty
    if (!$isPersonal && empty($counts) && ($fType === 'radio' || $fType === 'checkbox')) {
        if (!empty($f['field_options'])) {
            $dec = json_decode($f['field_options'], true);
            if (is_array($dec)) {
                foreach ($dec as $optVal) {
                    if (is_string($optVal) && trim($optVal) !== '') $counts[trim($optVal)] = 0;
                }
            } else {
                $optsArr = array_map('trim', explode(',', $f['field_options']));
                foreach ($optsArr as $optVal) {
                    if ($optVal !== '') $counts[$optVal] = 0;
                }
            }
        }
        if (empty($counts)) {
            $counts = ['ตัวเลือก 1' => 0, 'ตัวเลือก 2' => 0];
        }
    }

    // Smart Display Type Determination:
    // 1. Personal/Text -> 'text_list' (Clean Text Response Cards)
    // 2. Rating Scale -> 'rating_bar' (Rating Column Bar)
    // 3. Checkbox or Many options (>5) -> 'hbar' (Horizontal Bar Chart)
    // 4. Radio or Single Choice (<=5) -> 'doughnut' (Pie/Doughnut Chart)
    $smartDisplayType = 'doughnut';
    if ($isPersonal) {
        $smartDisplayType = 'text_list';
    } elseif ($fType === 'rating') {
        $smartDisplayType = 'rating_bar';
    } elseif ($fType === 'checkbox' || count($counts) > 5) {
        $smartDisplayType = 'hbar';
    } else {
        $smartDisplayType = 'doughnut';
    }

    $fieldAnalytics[$fId] = [
        'field'              => $f,
        'answered_count'     => $answeredCount,
        'counts'             => $counts,
        'text_list'          => $textList,
        'smart_display_type' => $smartDisplayType,
        'avg_rating'         => $ratingCount > 0 ? round($ratingSum / $ratingCount, 2) : 0,
    ];
}
?>

<div class="space-y-6">

    <!-- Top Header & Actions -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-1">
                <a href="<?= base_url('staff/forms') ?>" class="hover:text-indigo-600">แบบสอบถามทั้งหมด</a>
                <span>/</span>
                <span class="text-indigo-600">แดชบอร์ดสรุปผลการตอบ</span>
            </div>
            <h2 class="text-2xl font-black text-slate-900"><?= esc($form['form_title']) ?></h2>
            <p class="text-slate-500 text-xs font-semibold mt-1">แดชบอร์ดวิเคราะห์ผลอัตโนมัติ (ตัวเลือกแสดงกราฟ / ชื่อและเบอร์โทรแสดงลิสต์ข้อมูล)</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="<?= base_url("forms/view/{$form['form_id']}") ?>" target="_blank" class="px-3.5 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i> ลองตอบฟอร์ม
            </a>
            <button onclick="window.print()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i> พิมพ์
            </button>
            <a href="<?= base_url("staff/forms/export/{$form['form_id']}") ?>" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-extrabold text-xs flex items-center justify-center gap-1.5 shadow-md shadow-emerald-100 transition-all hover:scale-[1.02]">
                <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i> ส่งออก Excel
            </a>
            <button onclick="confirmClearResponses(<?= $form['form_id'] ?>)" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl font-extrabold text-xs flex items-center gap-1.5 transition-colors border border-rose-200">
                <i data-lucide="trash-2" class="w-3.5 h-3.5 text-rose-600"></i> ล้างคำตอบ
            </button>
        </div>
    </div>

    <?php if ($totalSubmissions == 0): ?>
        <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-2xl flex items-center justify-between gap-4 text-xs font-bold text-indigo-950">
            <div class="flex items-center gap-3">
                <i data-lucide="info" class="w-5 h-5 text-indigo-600 flex-shrink-0"></i>
                <span>ยังไม่มีผู้ตอบแบบสอบถามนี้ — ระบบวิเคราะห์ชนิดข้อมูลและจัดเตรียมการแสดงผลไว้เรียบร้อยแล้ว</span>
            </div>
            <a href="<?= base_url("forms/view/{$form['form_id']}") ?>" target="_blank" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-extrabold flex-shrink-0 transition-colors">
                + ทดลองส่งคำตอบแรก
            </a>
        </div>
    <?php endif; ?>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Responders -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-1">จำนวนผู้ตอบทั้งหมด</p>
                <h3 class="text-3xl font-black text-slate-900"><?= number_format($totalSubmissions) ?> <span class="text-xs font-bold text-slate-400">คน</span></h3>
            </div>
            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner">
                <i data-lucide="users" class="w-7 h-7"></i>
            </div>
        </div>

        <!-- Card 2: Total Questions -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-1">ข้อคำถามทั้งหมด</p>
                <h3 class="text-3xl font-black text-slate-900"><?= number_format(count($fields)) ?> <span class="text-xs font-bold text-slate-400">ข้อ</span></h3>
            </div>
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-inner">
                <i data-lucide="help-circle" class="w-7 h-7"></i>
            </div>
        </div>

        <!-- Card 3: E-Certificates Issued -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-1">เกียรติบัตรที่ออกแล้ว</p>
                <h3 class="text-3xl font-black text-amber-600"><?= number_format($certCount) ?> <span class="text-xs font-bold text-slate-400">ใบ</span></h3>
            </div>
            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-inner">
                <i data-lucide="award" class="w-7 h-7"></i>
            </div>
        </div>

        <!-- Card 4: Latest Response -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-1">ตอบล่าสุดเมื่อ</p>
                <h3 class="text-sm font-black text-slate-800">
                    <?= $latestSubmission ? date('d/m/Y H:i', strtotime($latestSubmission)) : '-' ?>
                </h3>
            </div>
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner">
                <i data-lucide="clock" class="w-7 h-7"></i>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs (Google Forms Style) -->
    <div class="flex border-b border-slate-200 bg-white px-6 pt-3 rounded-t-3xl border border-b-0">
        <button id="tab-summary-btn" onclick="switchTab('summary')" class="px-6 py-3 border-b-4 border-indigo-600 text-indigo-600 font-black text-xs md:text-sm flex items-center gap-2 transition-all">
            <i data-lucide="sparkles" class="w-4 h-4"></i> 📊 สรุปภาพรวมคำตอบ (Smart Auto Summary)
        </button>
        <button id="tab-table-btn" onclick="switchTab('table')" class="px-6 py-3 border-b-4 border-transparent text-slate-500 hover:text-slate-800 font-bold text-xs md:text-sm flex items-center gap-2 transition-all">
            <i data-lucide="table" class="w-4 h-4"></i> 📋 ตารางข้อมูลคำตอบรายบุคคล (Data Table)
        </button>
    </div>

    <!-- Tab 1 Content: Smart Display Summary -->
    <div id="tab-summary-content" class="space-y-6">
        <?php if (empty($fields)): ?>
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
                <i data-lucide="inbox" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                <h3 class="text-lg font-bold text-slate-700">ยังไม่มีข้อคำถามในแบบสอบถามนี้</h3>
                <p class="text-xs text-slate-400 mt-1">โปรดเพิ่มข้อคำถามในระบบจัดการคำถามก่อน</p>
            </div>
        <?php else: ?>
            <?php foreach ($fields as $idx => $f): 
                $analytics = $fieldAnalytics[$f['field_id']] ?? null;
                $answeredCount = $analytics['answered_count'] ?? 0;
                $smartDisplayType = $analytics['smart_display_type'] ?? 'doughnut';
                $avgRating = $analytics['avg_rating'] ?? 0;
                $textList = $analytics['text_list'] ?? [];
                $fType = $f['field_type'];
                $fId = $f['field_id'];

                $badgeLabel = '🥧 กราฟวงกลม';
                $badgeBg = 'bg-indigo-50 text-indigo-600 border-indigo-100';
                if ($smartDisplayType === 'hbar') {
                    $badgeLabel = '📊 กราฟแท่ง';
                    $badgeBg = 'bg-blue-50 text-blue-600 border-blue-100';
                } elseif ($smartDisplayType === 'rating_bar') {
                    $badgeLabel = '⭐ คะแนนประเมิน';
                    $badgeBg = 'bg-amber-50 text-amber-700 border-amber-200';
                } elseif ($smartDisplayType === 'text_list') {
                    $badgeLabel = '📝 รายการข้อความ';
                    $badgeBg = 'bg-slate-100 text-slate-700 border-slate-200';
                }
            ?>
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                        <div>
                            <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100 mr-2">ข้อที่ <?= $idx + 1 ?></span>
                            <span class="text-xs font-bold text-slate-400 uppercase">
                                <?= esc($f['field_label']) ?>
                            </span>
                            <h3 class="text-lg font-black text-slate-900 mt-2"><?= esc($f['field_label']) ?></h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-xs font-extrabold px-3 py-1.5 rounded-xl border flex items-center gap-1.5 <?= $badgeBg ?>">
                                <?= $badgeLabel ?>
                            </span>

                            <span class="text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                                ตอบแล้ว <strong class="text-slate-800"><?= number_format($answeredCount) ?></strong> / <?= number_format($totalSubmissions) ?> คน
                            </span>
                        </div>
                    </div>

                    <?php if ($smartDisplayType === 'text_list'): ?>
                        <!-- Text List Display for Personal / Name / Phone / Open Text -->
                        <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                            <?php if (empty($textList)): ?>
                                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200/80 text-center text-xs font-bold text-slate-400">
                                    ยังไม่มีข้อมูลตอบกลับในข้อนี้
                                </div>
                            <?php else: ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <?php foreach ($textList as $tItem): ?>
                                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-1 hover:border-indigo-200 transition-colors">
                                            <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                                                <i data-lucide="message-square" class="w-4 h-4 text-indigo-500 flex-shrink-0"></i>
                                                <span class="line-clamp-2">"<?= esc($tItem['text']) ?>"</span>
                                            </div>
                                            <div class="flex items-center justify-between text-[10px] text-slate-400 font-semibold pt-2 border-t border-slate-200/60 mt-2">
                                                <span>ผู้ตอบ: <?= esc($tItem['name']) ?></span>
                                                <span><?= date('d/m/Y H:i', strtotime($tItem['date'])) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($smartDisplayType === 'rating_bar'): ?>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                            <div class="lg:col-span-3 bg-amber-50/60 p-6 rounded-2xl border border-amber-200 text-center space-y-2">
                                <p class="text-xs font-extrabold text-amber-900 uppercase">คะแนนเฉลี่ยรวม</p>
                                <div class="text-5xl font-black text-amber-600 flex items-center justify-center gap-1">
                                    <span><?= number_format($avgRating, 2) ?></span>
                                    <span class="text-2xl text-amber-400">★</span>
                                </div>
                                <p class="text-[11px] text-amber-700 font-semibold">จากคะแนนเต็ม 5.00 คะแนน (ประเมิน <?= number_format($answeredCount) ?> คน)</p>
                            </div>

                            <div class="lg:col-span-9">
                                <div class="w-full h-72 relative bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                                    <canvas id="chart-field-<?= $fId ?>"></canvas>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Chart Display Canvas -->
                        <div class="w-full h-80 relative bg-slate-50/50 p-6 rounded-2xl border border-slate-100 flex items-center justify-center">
                            <canvas id="chart-field-<?= $fId ?>"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Tab 2 Content: Individual Responses Table -->
    <div id="tab-table-content" class="hidden space-y-4">
        <!-- Table Search Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 flex items-center gap-3">
            <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
            <input type="text" id="table-search" oninput="filterTable()" placeholder="ค้นหาตามชื่อผู้ตอบ, อีเมล หรือรหัสเกียรติบัตร..." class="w-full text-xs font-bold bg-transparent focus:outline-none text-slate-700">
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium text-slate-600" id="responses-table">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="p-4 w-12 text-center">#</th>
                            <th class="p-4">วันเวลาที่ตอบ</th>
                            <th class="p-4">ชื่อ-นามสกุล</th>
                            <th class="p-4">อีเมล</th>
                            <?php if ($form['form_has_certificate'] == 1): ?>
                                <th class="p-4">รหัสเกียรติบัตร</th>
                            <?php endif; ?>
                            <?php foreach ($fields as $f): ?>
                                <th class="p-4 min-w-[150px]"><?= esc($f['field_label']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($submissions)): ?>
                            <tr>
                                <td colspan="<?= 5 + count($fields) ?>" class="p-12 text-center text-slate-400 font-bold">
                                    ยังไม่มีการส่งแบบสอบถาม
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($submissions as $idx => $sub): ?>
                                <tr class="hover:bg-slate-50/80 transition-colors response-row">
                                    <td class="p-4 text-center font-bold text-slate-400"><?= $idx + 1 ?></td>
                                    <td class="p-4 whitespace-nowrap text-slate-500 font-semibold"><?= date('d/m/Y H:i', strtotime($sub['sub_submitted_at'])) ?></td>
                                    <td class="p-4 font-bold text-slate-900 whitespace-nowrap search-name"><?= esc($sub['sub_responder_name'] ?: '-') ?></td>
                                    <td class="p-4 text-slate-500 whitespace-nowrap search-email"><?= esc($sub['sub_responder_email'] ?: '-') ?></td>
                                    <?php if ($form['form_has_certificate'] == 1): ?>
                                        <td class="p-4 font-mono font-bold text-amber-600 whitespace-nowrap search-cert">
                                            <a href="<?= base_url("forms/certificate/{$sub['sub_id']}") ?>" target="_blank" class="hover:underline flex items-center gap-1">
                                                <i data-lucide="download" class="w-3.5 h-3.5"></i> <?= esc($sub['sub_cert_code']) ?>
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                    <?php foreach ($fields as $f): ?>
                                        <td class="p-4 font-medium text-slate-800">
                                            <?= esc($sub['answers'][$f['field_id']] ?? '-') ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function confirmClearResponses(formId) {
        Swal.fire({
            title: 'ยืนยันการลบข้อมูลคำตอบทั้งหมด?',
            text: 'คำตอบของผู้ตอบทุกคนในแบบสอบถามนี้จะถูกลบทิ้งอย่างถาวร เพื่อเริ่มต้นฟอร์มใหม่อีกครั้ง!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, ลบทั้งหมดเพื่อเริ่มใหม่',
            cancelButtonText: 'ยกเลิก'
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href = '<?= base_url('staff/forms/clear-responses/') ?>' + formId;
            }
        });
    }

    if (typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }

    function switchTab(tab) {
        const summaryBtn = document.getElementById('tab-summary-btn');
        const tableBtn = document.getElementById('tab-table-btn');
        const summaryContent = document.getElementById('tab-summary-content');
        const tableContent = document.getElementById('tab-table-content');

        if (tab === 'summary') {
            summaryBtn.className = 'px-6 py-3 border-b-4 border-indigo-600 text-indigo-600 font-black text-xs md:text-sm flex items-center gap-2 transition-all';
            tableBtn.className = 'px-6 py-3 border-b-4 border-transparent text-slate-500 hover:text-slate-800 font-bold text-xs md:text-sm flex items-center gap-2 transition-all';
            summaryContent.classList.remove('hidden');
            tableContent.classList.add('hidden');
        } else {
            tableBtn.className = 'px-6 py-3 border-b-4 border-indigo-600 text-indigo-600 font-black text-xs md:text-sm flex items-center gap-2 transition-all';
            summaryBtn.className = 'px-6 py-3 border-b-4 border-transparent text-slate-500 hover:text-slate-800 font-bold text-xs md:text-sm flex items-center gap-2 transition-all';
            tableContent.classList.remove('hidden');
            summaryContent.classList.add('hidden');
        }
    }

    function filterTable() {
        const query = document.getElementById('table-search').value.toLowerCase();
        document.querySelectorAll('.response-row').forEach(row => {
            const name = row.querySelector('.search-name')?.innerText.toLowerCase() || '';
            const email = row.querySelector('.search-email')?.innerText.toLowerCase() || '';
            const cert = row.querySelector('.search-cert')?.innerText.toLowerCase() || '';

            if (name.includes(query) || email.includes(query) || cert.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function createSmartChart(fieldId, labels, values, smartType = 'doughnut') {
        const ctx = document.getElementById(`chart-field-${fieldId}`);
        if (!ctx) return;

        const chartColors = ['#4f46e5', '#3b82f6', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#6366f1', '#14b8a6'];

        let type = 'doughnut';
        let indexAxis = 'x';

        if (smartType === 'hbar') {
            type = 'bar';
            indexAxis = 'y';
        } else if (smartType === 'rating_bar') {
            type = 'bar';
            indexAxis = 'x';
        }

        new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: 'จำนวนผู้ตอบ (คน)',
                    data: values,
                    backgroundColor: (smartType === 'hbar') ? '#4f46e5' : ((smartType === 'rating_bar') ? '#f59e0b' : chartColors),
                    borderRadius: (smartType !== 'doughnut') ? 8 : 0,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: indexAxis,
                plugins: {
                    legend: { 
                        display: smartType === 'doughnut',
                        position: 'bottom',
                        labels: { font: { family: 'Inter, Sarabun', size: 12, weight: 'bold' } }
                    },
                    datalabels: {
                        color: smartType === 'doughnut' ? '#ffffff' : (smartType === 'rating_bar' ? '#d97706' : '#4f46e5'),
                        anchor: smartType === 'doughnut' ? 'center' : 'end',
                        align: smartType === 'doughnut' ? 'center' : 'end',
                        font: { family: 'Inter, Sarabun', weight: 'bold', size: 11 },
                        formatter: (value, context) => {
                            if (!value || value === 0) return '';
                            if (smartType !== 'doughnut') return value + ' คน';
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? ((value / total) * 100).toFixed(0) : 0;
                            return `${pct}%`;
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const val = context.raw || 0;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${val} คน (${pct}%)`;
                            }
                        }
                    }
                },
                scales: smartType !== 'doughnut' ? {
                    y: { beginAtZero: true, ticks: { precision: 0, font: { family: 'Inter, Sarabun', weight: 'bold' } } },
                    x: { beginAtZero: true, ticks: { precision: 0, font: { family: 'Inter, Sarabun', weight: 'bold' } } }
                } : {}
            }
        });
    }

    // Render Charts only for Chartable Fields on Load
    document.addEventListener('DOMContentLoaded', () => {
        <?php foreach ($fields as $f): 
            $fId = $f['field_id'];
            $analytics = $fieldAnalytics[$fId] ?? null;
            $smartDisplayType = $analytics['smart_display_type'] ?? 'doughnut';
            if ($smartDisplayType !== 'text_list'):
                $counts = $analytics['counts'] ?? [];
                $labels = array_keys($counts);
                $values = array_values($counts);
        ?>
            createSmartChart(<?= $fId ?>, <?= json_encode($labels) ?>, <?= json_encode($values) ?>, '<?= $smartDisplayType ?>');
        <?php endif; endforeach; ?>
    });
</script>
<?= $this->endSection() ?>
