<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span>จัดการผลคะแนนและอันดับรางวัล</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">บันทึกผลคะแนนดิบและระดับเหรียญรางวัล/เกียรติยศสำหรับผู้สมัครที่ผ่านเข้ารอบ</p>
    </div>
    
    <!-- Toggle Publish Buttons -->
    <div class="flex flex-wrap gap-2 shrink-0 w-full md:w-auto">
        <button id="manage-comps-publish-btn" onclick="openCompPublishModal()" class="w-full md:w-auto px-4 py-3 rounded-2xl font-bold text-xs sm:text-sm bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 text-indigo-600 dark:text-indigo-400 transition-all duration-300 flex items-center justify-center gap-2 shadow-md cursor-pointer">
            <i data-lucide="sliders" class="w-4 h-4 text-indigo-500"></i>
            <span>เปิด-ปิดประกาศผลแยกรายการ</span>
        </button>

        <button id="toggle-publish-btn" onclick="togglePublishResults()" class="w-full md:w-auto px-5 py-3 rounded-2xl font-bold text-xs sm:text-sm transition-all duration-300 flex items-center justify-center gap-2 shadow-md cursor-pointer <?= $publish_results ? 'bg-emerald-600/10 hover:bg-emerald-600/20 border border-emerald-500/30 text-emerald-450 dark:text-emerald-400' : 'bg-slate-800 hover:bg-slate-750 border border-slate-700 text-slate-450 dark:text-slate-400' ?>">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 <?= $publish_results ? 'bg-emerald-450' : 'bg-rose-400' ?>"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 <?= $publish_results ? 'bg-emerald-500' : 'bg-rose-500' ?>"></span>
            </span>
            <span id="publish-status-text"><?= $publish_results ? 'ระบบประกาศผลรวม: เปิดใช้งาน' : 'ระบบประกาศผลรวม: ปิดอยู่' ?></span>
        </button>
    </div>
</div>

<!-- Filters Card -->
<div class="glass-card p-4 sm:p-6 rounded-3xl mb-6 bg-slate-900/40 dark:bg-slate-900/60">
    <form method="GET" action="<?= base_url('science-week/staff/ranking') ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
        <!-- Search input -->
        <div class="relative lg:col-span-2">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i data-lucide="search" class="w-4 h-4"></i></span>
            <input type="text" name="search" value="<?= esc($search) ?>" placeholder="ค้นหา รหัสสิทธิ์, โรงเรียน, ชื่อทีม, รายชื่อสมาชิก..." class="w-full pl-10 pr-4 py-3 bg-slate-900/60 dark:bg-slate-850 border border-slate-700 dark:border-slate-750 focus:border-blue-500 rounded-2xl text-xs text-slate-200 outline-none transition-colors">
        </div>

        <!-- Competition Select -->
        <div>
            <select name="competition_type" class="w-full px-4 py-3 bg-slate-900/60 dark:bg-slate-850 border border-slate-700 dark:border-slate-750 focus:border-blue-500 rounded-2xl text-xs text-slate-200 outline-none transition-colors cursor-pointer">
                <option value="" class="bg-slate-950 text-slate-350 dark:bg-slate-900 dark:text-slate-200">-- ประเภทการแข่งขันทั้งหมด --</option>
                <?php if (!empty($competitions)): ?>
                    <?php foreach ($competitions as $comp): ?>
                        <option value="<?= esc($comp['comp_name']) ?>" <?= $compType_active == $comp['comp_name'] ? 'selected' : '' ?> class="bg-slate-950 text-slate-300 dark:bg-slate-900 dark:text-slate-200"><?= esc($comp['comp_name']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- Level Filter -->
        <div>
            <select name="level" class="w-full px-4 py-3 bg-slate-900/60 dark:bg-slate-850 border border-slate-700 dark:border-slate-750 focus:border-blue-500 rounded-2xl text-xs text-slate-200 outline-none transition-colors cursor-pointer">
                <option value="" class="bg-slate-950 text-slate-350 dark:bg-slate-900 dark:text-slate-200">-- ระดับชั้นแข่งขันทั้งหมด --</option>
                <?php if (!empty($available_levels)): ?>
                    <?php foreach ($available_levels as $lvl): ?>
                        <option value="<?= esc($lvl) ?>" <?= ($level_active ?? '') == $lvl ? 'selected' : '' ?> class="bg-slate-950 text-slate-300 dark:bg-slate-900 dark:text-slate-200"><?= esc($lvl) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- Submit & Clear Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="flex-1 py-3 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-100 hover:border-blue-600 text-xs font-bold rounded-2xl transition-all flex items-center justify-center gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i> กรองรายการ
            </button>
            <?php if(!empty($search) || !empty($compType_active) || !empty($level_active)): ?>
                <a href="<?= base_url('science-week/staff/ranking') ?>" class="p-3 bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 rounded-2xl transition-all flex items-center justify-center" title="ล้างฟิลเตอร์">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php
// Build competition lookup map
$compMapByName = [];
if (!empty($competitions)) {
    foreach ($competitions as $c) {
        $compMapByName[$c['comp_name']] = $c;
    }
}

// Group registrations by competition type
$grouped = [];
if (!empty($registrations)) {
    foreach ($registrations as $reg) {
        $grouped[$reg['reg_competition_type']][] = $reg;
    }
}
?>

<!-- Grouped Tables Section -->
<div class="space-y-8">
    <?php if (empty($grouped)): ?>
        <div class="glass-card rounded-3xl overflow-hidden bg-slate-900/40 dark:bg-slate-900/60 shadow-xl border border-slate-200 dark:border-slate-800 p-12 text-center text-slate-400 font-bold text-sm">
            <?php if (empty($search) && empty($compType_active) && empty($level_active)): ?>
                <i data-lucide="filter" class="w-10 h-10 mx-auto text-indigo-500 mb-3 opacity-60"></i>
                <span>กรุณาพิมพ์ค้นหา, เลือก "ประเภทการแข่งขัน" หรือเลือก "ระดับชั้น" เพื่อจัดการอันดับรางวัล</span>
            <?php else: ?>
                <i data-lucide="alert-circle" class="w-10 h-10 mx-auto text-rose-500 mb-3 opacity-60"></i>
                <span>ไม่พบรายชื่อผู้สมัครตามตัวกรองที่เลือก</span>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php foreach ($grouped as $compName => $regs): 
            $compInfo = $compMapByName[$compName] ?? null; 
            $compId = $compInfo['comp_id'] ?? null;
            $compPublished = isset($compInfo['comp_publish_results']) ? ((int)$compInfo['comp_publish_results'] === 1) : true;
        ?>
            <div class="mb-8">
                <!-- Competition Group Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pl-2">
                    <div class="flex items-center gap-3">
                        <?php if ($compId): ?>
                            <span class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-500 dark:text-indigo-400 font-mono text-xs font-black shadow-sm" title="ไอดีรายการ: #<?= $compId ?>">#<?= $compId ?></span>
                        <?php else: ?>
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-500 dark:text-indigo-400">
                                <i data-lucide="trophy" class="w-4 h-4"></i>
                            </div>
                        <?php endif; ?>
                        <h3 class="text-base sm:text-lg font-black text-slate-850 dark:text-white"><?= esc($compName) ?></h3>
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500">(<?= count($regs) ?> ทีม)</span>
                    </div>

                    <?php if ($compId): ?>
                        <button onclick="toggleCompPublish(<?= $compId ?>, '<?= esc($compName, 'js') ?>')" class="w-full sm:w-auto px-3.5 py-2 rounded-xl font-bold text-xs transition-all duration-300 flex items-center justify-center gap-2 shadow-sm border cursor-pointer <?= $compPublished ? 'bg-emerald-500/10 hover:bg-emerald-500/20 border-emerald-500/30 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 hover:bg-rose-500/20 border-rose-500/30 text-rose-600 dark:text-rose-400' ?>" title="คลิกเพื่อ เปิด/ปิด การประกาศผลรางวัลรายการนี้">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 <?= $compPublished ? 'bg-emerald-400' : 'bg-rose-400' ?>"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 <?= $compPublished ? 'bg-emerald-500' : 'bg-rose-500' ?>"></span>
                            </span>
                            <span><?= $compPublished ? 'ประกาศผลรายการนี้แล้ว' : 'ปิดประกาศผลรายการนี้' ?></span>
                        </button>
                    <?php endif; ?>
                </div>

                <!-- List Table -->
                <div class="glass-card rounded-3xl overflow-hidden bg-slate-900/40 dark:bg-slate-900/60 shadow-xl border border-slate-200 dark:border-slate-800">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800">
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-1/5">รหัสสิทธิ์</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-2/5">โรงเรียน / ทีม</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-1/5">สมาชิกในทีม</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 w-1/5">อันดับรางวัล</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[130px]">ผู้บันทึก</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-[150px]">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <?php foreach ($regs as $reg): ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs font-black text-cyan-600 dark:text-cyan-400 block font-mono"><?= $reg['reg_code'] ?></span>
                                            <?php if (!empty($reg['reg_level'])): ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 mt-1"><?= esc($reg['reg_level']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200 block">
                                                <?= esc($reg['reg_school_name']) ?>
                                                <?php if (!empty($reg['reg_school_province'])): ?>
                                                    <span class="text-indigo-650 dark:text-indigo-400 font-bold text-[10px]"> (<?= esc($reg['reg_school_province']) ?>)</span>
                                                <?php endif; ?>
                                            </span>
                                            <span class="text-[10px] text-slate-440 block font-semibold mt-0.5"><?= $reg['reg_team_name'] ? 'ทีม: '.esc($reg['reg_team_name']) : 'ทั่วไป' ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-xs">
                                            <div class="max-w-[200px]">
                                                <?php $members = json_decode($reg['reg_members'], true) ?: []; ?>
                                                <ul class="space-y-0.5">
                                                    <?php foreach ($members as $m): 
                                                        $mText = '';
                                                        if (is_array($m)) {
                                                            $prefix = trim($m['prefix'] ?? '');
                                                            $name = trim($m['name'] ?? '');
                                                            $mText = ($prefix !== '' ? $prefix . ' ' : '') . $name;
                                                            if (!empty($m['custom_fields'])) {
                                                                $cfStr = [];
                                                                foreach ($m['custom_fields'] as $cfKey => $cfVal) {
                                                                    if ($cfVal !== '') {
                                                                        $cfStr[] = "{$cfKey}: {$cfVal}";
                                                                    }
                                                                }
                                                                if (!empty($cfStr)) {
                                                                    $mText .= ' (' . implode(', ', $cfStr) . ')';
                                                                }
                                                            }
                                                        } else {
                                                            $mText = $m;
                                                        }
                                                    ?>
                                                        <li class="text-[11px] text-slate-650 dark:text-slate-300 font-medium truncate" title="<?= esc($mText) ?>">• <?= esc($mText) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if (!empty($reg['reg_rank'])): ?>
                                                <span class="px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30 text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5 w-max">
                                                    🏆 <?= esc($reg['reg_rank']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 text-[10px] font-bold tracking-wider inline-block">
                                                    ยังไม่ได้รับรางวัล
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <?php $updatedBy = $reg['reg_updated_by'] ?? null; ?>
                                            <?php if (!empty($updatedBy)): ?>
                                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center justify-center gap-1">
                                                    <i data-lucide="user-check" class="w-3 h-3 text-emerald-500"></i>
                                                    <?= esc($updatedBy) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-[10px] text-slate-400 italic">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                            <div class="flex items-center justify-center gap-2">
                                                <button onclick="editRegRank(<?= $reg['reg_id'] ?>, '<?= esc($reg['reg_rank'] ?: '') ?>')" class="px-3.5 py-2 bg-amber-50 hover:bg-amber-600 text-amber-600 hover:text-white rounded-xl border border-amber-100 dark:border-amber-900 transition-all font-bold flex items-center gap-1.5" title="ระบุอันดับรางวัล">
                                                    <i data-lucide="award" class="w-4 h-4"></i> บันทึกผล
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    const compLevelMap = <?= json_encode($comp_level_map ?? []) ?>;
    const allAvailableLevels = <?= json_encode($all_available_levels ?? []) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const compSelect = document.querySelector('form select[name="competition_type"]');
        const levelSelect = document.querySelector('form select[name="level"]');

        if (compSelect && levelSelect) {
            compSelect.addEventListener('change', function() {
                const selectedComp = this.value;
                const currentLevel = levelSelect.value;
                
                let targetLevels = [];
                if (selectedComp && compLevelMap[selectedComp]) {
                    targetLevels = compLevelMap[selectedComp];
                } else {
                    targetLevels = allAvailableLevels;
                }

                levelSelect.innerHTML = '<option value="" class="bg-slate-950 text-slate-350 dark:bg-slate-900 dark:text-slate-200">-- ระดับชั้นแข่งขันทั้งหมด --</option>';
                
                targetLevels.forEach(lvl => {
                    const opt = document.createElement('option');
                    opt.value = lvl;
                    opt.textContent = lvl;
                    opt.className = 'bg-slate-950 text-slate-300 dark:bg-slate-900 dark:text-slate-200';
                    if (lvl === currentLevel) {
                        opt.selected = true;
                    }
                    levelSelect.appendChild(opt);
                });
            });
        }
    });

    function editRegRank(id, currentRank) {
        Swal.fire({
            title: 'ระบุอันดับรางวัล',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">อันดับ / รางวัลที่ได้รับ</label>
                        <select id="swal-rank" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none cursor-pointer">
                            <option value="">-- ไม่ได้รับรางวัล / เข้าร่วม --</option>
                            <option value="รางวัลชนะเลิศ" ${currentRank === 'รางวัลชนะเลิศ' ? 'selected' : ''}>🏆 รางวัลชนะเลิศ</option>
                            <option value="รางวัลรองชนะเลิศอันดับ 1" ${currentRank === 'รางวัลรองชนะเลิศอันดับ 1' ? 'selected' : ''}>🥈 รางวัลรองชนะเลิศอันดับ 1</option>
                            <option value="รางวัลรองชนะเลิศอันดับ 2" ${currentRank === 'รางวัลรองชนะเลิศอันดับ 2' ? 'selected' : ''}>🥉 รางวัลรองชนะเลิศอันดับ 2</option>
                            <option value="รางวัลชมเชย" ${currentRank === 'รางวัลชมเชย' ? 'selected' : ''}>🏅 รางวัลชมเชย</option>
                            <option value="custom" ${currentRank !== '' && !['รางวัลชนะเลิศ','รางวัลรองชนะเลิศอันดับ 1','รางวัลรองชนะเลิศอันดับ 2','รางวัลชมเชย'].includes(currentRank) ? 'selected' : ''}>ระบุอื่นๆ...</option>
                        </select>
                    </div>
                    <div id="swal-custom-rank-wrapper" class="hidden">
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">ระบุอันดับรางวัลอื่นๆ</label>
                        <input type="text" id="swal-custom-rank" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none" placeholder="เช่น รางวัลนวัตกรรมยอดเยี่ยม">
                    </div>
                </div>
            `,
            didOpen: () => {
                const select = document.getElementById('swal-rank');
                const customWrapper = document.getElementById('swal-custom-rank-wrapper');
                const customInput = document.getElementById('swal-custom-rank');
                
                function toggleCustom() {
                    if (select.value === 'custom') {
                        customWrapper.classList.remove('hidden');
                    } else {
                        customWrapper.classList.add('hidden');
                    }
                }
                
                select.addEventListener('change', toggleCustom);
                
                if (currentRank !== '' && !['รางวัลชนะเลิศ','รางวัลรองชนะเลิศอันดับ 1','รางวัลรองชนะเลิศอันดับ 2','รางวัลชมเชย'].includes(currentRank)) {
                    select.value = 'custom';
                    customInput.value = currentRank;
                    toggleCustom();
                }
            },
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก',
            background: getSwalColors().bg,
            color: getSwalColors().text,
            customClass: { popup: 'glass-card rounded-[2rem]' },
            preConfirm: () => {
                const score = '';
                const selectValue = document.getElementById('swal-rank').value;
                let rank = selectValue;
                if (selectValue === 'custom') {
                    rank = document.getElementById('swal-custom-rank').value.trim();
                }
                return { score, rank };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                const { score, rank } = result.value;

                fetch(`<?= base_url('science-week/staff/update-rank') ?>/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `score=${encodeURIComponent(score)}&rank=${encodeURIComponent(rank)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกผลสำเร็จ',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#3b82f6',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        }).then(() => { window.location.reload(); });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ล้มเหลว',
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
        });
    }

    const competitionsData = <?= json_encode($competitions ?? []) ?>;

    function toggleCompPublish(compId, compName) {
        Swal.fire({
            title: `ยืนยันการเปลี่ยนสถานะ?`,
            text: `ต้องการเปลี่ยนสถานะการประกาศผลสำหรับ "${compName}" หรือไม่`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            background: getSwalColors().bg,
            color: getSwalColors().text,
            customClass: { popup: 'glass-card rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                fetch(`<?= base_url('science-week/staff/toggle-publish-comp') ?>/${compId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'เปลี่ยนสถานะสำเร็จ',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#3b82f6',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        }).then(() => { window.location.reload(); });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ล้มเหลว',
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
        });
    }

    function openCompPublishModal() {
        if (!competitionsData || competitionsData.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'ไม่พบรายการแข่งขัน',
                text: 'ไม่มีรายการแข่งขันในระบบปีนี้',
                background: getSwalColors().bg,
                color: getSwalColors().text
            });
            return;
        }

        let htmlContent = '<div class="space-y-3 text-left max-h-[60vh] overflow-y-auto p-1">';
        competitionsData.forEach(comp => {
            const isPub = parseInt(comp.comp_publish_results ?? 1) === 1;
            const safeCompName = (comp.comp_name || '').replace(/'/g, "\\'");
            htmlContent += `
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-900/60 border border-slate-800 gap-3">
                    <div class="space-y-0.5 min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-mono font-black text-indigo-400 bg-indigo-500/10 px-1.5 py-0.5 rounded border border-indigo-500/20">#${comp.comp_id}</span>
                            <span class="text-xs font-black text-white truncate" title="${comp.comp_name}">${comp.comp_name}</span>
                        </div>
                        <span class="text-[10px] text-slate-400 block truncate pl-7">${comp.comp_level || 'ทุกระดับชั้น'}</span>
                    </div>
                    <button onclick="Swal.close(); toggleCompPublish(${comp.comp_id}, '${safeCompName}')" class="shrink-0 px-3 py-1.5 rounded-xl font-bold text-[11px] border transition-all cursor-pointer ${isPub ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-rose-500/10 border-rose-500/30 text-rose-400'}">
                        ${isPub ? '🟢 ประกาศผลแล้ว' : '🔴 ปิดประกาศ'}
                    </button>
                </div>
            `;
        });
        htmlContent += '</div>';

        Swal.fire({
            title: 'จัดการเปิด/ปิด ประกาศผลแยกรายการ',
            html: htmlContent,
            width: 'min(520px, 95%)',
            showConfirmButton: false,
            showCloseButton: true,
            background: getSwalColors().bg,
            color: getSwalColors().text,
            customClass: { popup: 'glass-card rounded-[2rem]' }
        });
    }

    function togglePublishResults() {
        Swal.fire({
            title: 'ยืนยันการเปลี่ยนสถานะการประกาศผลรวม?',
            text: "ต้องการปรับเปลี่ยนการแสดงผลระบบประกาศผลรางวัลสาธารณะหลักหรือไม่",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            background: getSwalColors().bg,
            color: getSwalColors().text,
            customClass: { popup: 'glass-card rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                fetch(`<?= base_url('science-week/staff/toggle-publish-results') ?>`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'เปลี่ยนสถานะสำเร็จ',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#3b82f6',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        }).then(() => { window.location.reload(); });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ล้มเหลว',
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
        });
    }
</script>
<?= $this->endSection() ?>
