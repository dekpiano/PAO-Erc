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

    .result-row-award {
        background: linear-gradient(90deg, rgba(245, 158, 11, 0.08) 0%, rgba(15, 23, 42, 0.6) 100%) !important;
        border-left: 4px solid #f59e0b !important;
    }

    .badge-winner {
        background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
        box-shadow: 0 0 15px rgba(251, 191, 36, 0.4);
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }
    .badge-runner-1 {
        background: linear-gradient(135deg, #cbd5e1 0%, #64748b 100%);
        box-shadow: 0 0 15px rgba(148, 163, 184, 0.3);
        color: #ffffff;
    }
    .badge-runner-2 {
        background: linear-gradient(135deg, #fed7aa 0%, #c2410c 100%);
        box-shadow: 0 0 15px rgba(249, 115, 22, 0.3);
        color: #ffffff;
    }
    .badge-other-award {
        background: rgba(99, 102, 241, 0.2);
        border: 1px solid rgba(99, 102, 241, 0.4);
        color: #a5b4fc;
    }

    .gradient-text {
        background: linear-gradient(135deg, #818cf8 0%, #6366f1 50%, #4f46e5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Animation */
    .row-anim {
        animation: rowSlideIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    @keyframes rowSlideIn {
        from { opacity: 0; transform: translateY(15px); }
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
                    ประกาศผลการประกวดและแข่งขัน
                </span>
            </h1>
            <p class="text-slate-400 text-sm font-semibold">สรุปรายชื่อทีมและผู้เข้าแข่งขันที่ได้รับรางวัลงานสัปดาห์วิทยาศาสตร์</p>
            <div class="rainbow-divider max-w-20 mx-auto"></div>
        </div>

        <?php if ($publish_results): ?>
            <!-- Search / Filter Card -->
            <div class="glass-sci-card rounded-3xl p-6 sm:p-8 mb-10 shadow-lg relative overflow-hidden">
                <div class="rainbow-divider absolute top-0 left-0 right-0"></div>
                
                <form method="GET" action="<?= base_url('science-week/results') ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-4">
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
                            <option value="" class="bg-slate-900 text-white">-- กรุณาเลือกรายการแข่งขัน --</option>
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
                            <i data-lucide="filter" class="w-5 h-5"></i> แสดงผลการแข่งขัน
                        </button>
                        <?php if(!empty($search) || !empty($compType_active)): ?>
                            <a href="<?= base_url('science-week/results') ?>" class="p-4 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-300 rounded-2xl transition-all flex items-center justify-center" title="ล้างฟิลเตอร์">
                                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Result Section -->
            <div class="space-y-10">
                <?php if (empty($results)): ?>
                    <?php if (empty($compType_active) && empty($search)): ?>
                        <div class="glass-sci-card rounded-3xl p-10 sm:p-14 text-center text-slate-300 border border-indigo-500/30">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mx-auto mb-4 text-indigo-400 shadow-[0_0_20px_rgba(99,102,241,0.2)]">
                                <i data-lucide="trophy" class="w-8 h-8"></i>
                            </div>
                            <h3 class="font-black text-xl mb-2 text-white">กรุณาเลือกรายการแข่งขัน</h3>
                            <p class="text-xs sm:text-sm text-slate-400 max-w-md mx-auto font-medium">โปรดเลือกรายการแข่งขันจากตัวกรองด้านบน หรือพิมพ์ค้นหาเพื่อดูประกาศผลการแข่งขันและอันดับรางวัล</p>
                        </div>
                    <?php else: ?>
                        <div class="glass-sci-card rounded-3xl p-12 text-center text-slate-400 border border-dashed border-indigo-500/20">
                            <i data-lucide="award" class="w-16 h-16 mx-auto text-indigo-400/40 mb-4 animate-pulse"></i>
                            <p class="font-bold text-lg mb-1 text-white">ยังไม่มีประกาศผลการแข่งขันในรายการนี้</p>
                            <p class="text-xs text-slate-450">กรุณาเลือกประเภทการแข่งขันอื่นๆ หรือลองค้นหาด้วยคีย์เวิร์ดใหม่อีกครั้ง</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php
                    // Group results by competition type & level
                    $groupedAll = [];
                    foreach ($results as $reg) {
                        $key = $reg['reg_competition_type'];
                        if (!empty($reg['reg_level'])) {
                            $key .= ' - ' . $reg['reg_level'];
                        }
                        $groupedAll[$key][] = $reg;
                    }
                    ?>
                    
                    <?php foreach ($groupedAll as $compKey => $compRegsAll): ?>
                        <?php
                        $topRanks = ['รางวัลชนะเลิศ', 'รางวัลรองชนะเลิศอันดับ 1', 'รางวัลรองชนะเลิศอันดับ 2'];
                        $topRegs = [];
                        $otherRegs = [];
                        
                        foreach ($compRegsAll as $reg) {
                            if (in_array($reg['reg_rank'], $topRanks)) {
                                $topRegs[] = $reg;
                            } else {
                                $reg['reg_rank'] = $reg['reg_rank'] ?: 'รางวัลชมเชย';
                                $otherRegs[] = $reg;
                            }
                        }
                        ?>

                        <div class="space-y-4">
                            <!-- Group Title -->
                            <div class="flex items-center gap-3 pt-2">
                                <div class="w-2.5 h-6 rounded-full bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>
                                <h2 class="text-base sm:text-lg font-black text-indigo-300 tracking-wide">
                                    <?= esc($compKey) ?>
                                </h2>
                            </div>

                            <!-- Desktop Table View -->
                            <div class="hidden md:block glass-sci-card rounded-3xl overflow-hidden shadow-2xl border border-indigo-500/20">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-slate-950/60 border-b border-indigo-500/20 text-slate-350">
                                                <th class="px-6 py-4 text-xs font-black uppercase tracking-wider w-1/3">ชื่อทีม / ผู้เข้าแข่งขัน</th>
                                                <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">สถาบันการศึกษา</th>
                                                <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">สมาชิกผู้แข่งขัน</th>
                                                <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-right">รางวัลที่ได้รับ</th>
                                                <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-center">ดาวน์โหลดเกียรติบัตร</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-800/80">
                                            <?php $delay = 0; foreach ($topRegs as $reg): 
                                                $isWinner = !empty($reg['reg_rank']);
                                                $rowClass = $isWinner ? 'result-row-award' : '';
                                                
                                                // Determine award class
                                                $badgeClass = 'badge-other-award';
                                                if ($reg['reg_rank'] === 'รางวัลชนะเลิศ') {
                                                    $badgeClass = 'badge-winner';
                                                } elseif ($reg['reg_rank'] === 'รางวัลรองชนะเลิศอันดับ 1') {
                                                    $badgeClass = 'badge-runner-1';
                                                } elseif ($reg['reg_rank'] === 'รางวัลรองชนะเลิศอันดับ 2') {
                                                    $badgeClass = 'badge-runner-2';
                                                }
                                            ?>
                                                <tr class="hover:bg-slate-900/30 transition-colors row-anim <?= $rowClass ?>" style="animation-delay: <?= $delay ?>ms">
                                                    <td class="px-6 py-5">
                                                        <span class="text-sm font-extrabold text-white block"><?= $reg['reg_team_name'] ? esc($reg['reg_team_name']) : 'ทั่วไป (บุคคลเดี่ยว)' ?></span>
                                                    </td>
                                                    <td class="px-6 py-5">
                                                        <span class="text-xs font-bold text-slate-200 block"><?= esc($reg['reg_school_name']) ?></span>
                                                        <?php if (!empty($reg['reg_school_province'])): ?>
                                                            <span class="text-[10px] text-slate-400 font-medium">จ.<?= esc($reg['reg_school_province']) ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-6 py-5 text-xs text-slate-300">
                                                        <?php $members = json_decode($reg['reg_members'], true) ?: []; ?>
                                                        <button onclick="showMembersModal('<?= esc(json_encode($members), 'js') ?>', '<?= esc(json_encode(json_decode($reg['reg_advisors'], true) ?: []), 'js') ?>')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-indigo-500/20 text-indigo-300 hover:text-indigo-200 transition-all font-bold text-[11px] cursor-pointer shadow-sm">
                                                            <i data-lucide="users" class="w-3.5 h-3.5 text-indigo-400"></i>
                                                            <span>ดูสมาชิก (<?= count($members) ?> คน)</span>
                                                        </button>
                                                    </td>
                                                    <td class="px-6 py-5 text-right whitespace-nowrap">
                                                        <span class="px-3 py-1.5 rounded-xl text-xs font-black tracking-wider uppercase inline-flex items-center gap-1.5 <?= $badgeClass ?>">
                                                            <?= esc($reg['reg_rank']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-5 text-center">
                                                        <a href="<?= base_url('science-week/certificate/view-all/competition/' . $reg['reg_code']) ?>" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-50/10 hover:bg-indigo-650 hover:text-white text-indigo-300 border border-indigo-500/20 hover:border-indigo-500 transition-all font-bold text-xs shadow-sm" title="ดูเกียรติบัตรทั้งหมดในแท็บใหม่">
                                                            <i data-lucide="award" class="w-4 h-4 text-indigo-400"></i>
                                                            <span>ดูเกียรติบัตรทั้งหมด</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php $delay += 30; endforeach; ?>

                                            <!-- Row 4: Search for Honorable Mentions and other participants of this competition -->
                                            <?php if (!empty($otherRegs)): ?>
                                                <tr class="hover:bg-slate-900/30 transition-colors row-anim" style="animation-delay: <?= $delay ?>ms">
                                                    <td colspan="5" class="px-6 py-6 text-center bg-slate-950/20 border-t border-slate-800/80">
                                                        <button type="button" onclick="openOtherAwardsModal('<?= esc($compKey, 'js') ?>', '<?= esc(json_encode($otherRegs), 'js') ?>')" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-indigo-500/10 hover:bg-indigo-600 hover:text-white text-indigo-300 border border-indigo-500/20 hover:border-indigo-500 transition-all font-bold text-xs cursor-pointer shadow-md">
                                                            <i data-lucide="search" class="w-4.5 h-4.5 text-indigo-400"></i>
                                                            <span>🔍 ค้นหารายชื่อรางวัลชมเชยและผู้เข้าร่วมการแข่งขันในรายการนี้ (<?= count($otherRegs) ?> รายการ)</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="md:hidden space-y-4">
                                <?php $delay = 0; foreach ($topRegs as $reg): 
                                    $isWinner = !empty($reg['reg_rank']);
                                    $cardBorderClass = $isWinner ? 'border-amber-500/40 bg-gradient-to-b from-slate-950/80 via-slate-950/60 to-amber-950/10' : 'border-indigo-500/20 bg-slate-950/40';
                                    
                                    $badgeClass = 'badge-other-award';
                                    if ($reg['reg_rank'] === 'รางวัลชนะเลิศ') {
                                        $badgeClass = 'badge-winner';
                                    } elseif ($reg['reg_rank'] === 'รางวัลรองชนะเลิศอันดับ 1') {
                                        $badgeClass = 'badge-runner-1';
                                    } elseif ($reg['reg_rank'] === 'รางวัลรองชนะเลิศอันดับ 2') {
                                        $badgeClass = 'badge-runner-2';
                                    }
                                ?>
                                    <div class="glass-sci-card rounded-3xl p-5 border <?= $cardBorderClass ?> space-y-4 row-anim" style="animation-delay: <?= $delay ?>ms">
                                        <div class="flex justify-between items-start gap-4">
                                            <div class="space-y-1">
                                                <h4 class="text-sm font-black text-white"><?= $reg['reg_team_name'] ? esc($reg['reg_team_name']) : 'ทั่วไป (บุคคลเดี่ยว)' ?></h4>
                                            </div>
                                        </div>

                                        <div class="text-xs space-y-1">
                                            <span class="text-[10px] text-slate-400 font-black block uppercase tracking-wider">สถาบันการศึกษา</span>
                                            <p class="text-slate-200 font-bold leading-normal">
                                                <?= esc($reg['reg_school_name']) ?>
                                                <?php if (!empty($reg['reg_school_province'])): ?>
                                                    <span class="text-slate-400 font-medium text-[11px] block sm:inline">จ.<?= esc($reg['reg_school_province']) ?></span>
                                                <?php endif; ?>
                                            </p>
                                        </div>

                                        <div class="flex items-center justify-between flex-wrap gap-3 pt-3.5 border-t border-slate-800/80">
                                            <div>
                                                <span class="px-2.5 py-1 rounded-xl text-[9px] font-black tracking-wider uppercase inline-flex items-center gap-1 <?= $badgeClass ?>">
                                                    <?= esc($reg['reg_rank']) ?>
                                                </span>
                                            </div>
                                            
                                            <div class="flex gap-2">
                                                <?php $members = json_decode($reg['reg_members'], true) ?: []; ?>
                                                <button onclick="showMembersModal('<?= esc(json_encode($members), 'js') ?>', '<?= esc(json_encode(json_decode($reg['reg_advisors'], true) ?: []), 'js') ?>')" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-indigo-500/25 text-indigo-300 hover:text-indigo-200 font-bold text-[10px] cursor-pointer transition-colors shadow-sm">
                                                    <i data-lucide="users" class="w-3.5 h-3.5 text-indigo-400"></i>
                                                    <span>สมาชิก (<?= count($members) ?>)</span>
                                                </button>
                                                <a href="<?= base_url('science-week/certificate/view-all/competition/' . $reg['reg_code']) ?>" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-indigo-500/10 hover:bg-indigo-600 hover:text-white text-indigo-300 border border-indigo-500/20 transition-all font-bold text-[10px] shadow-sm">
                                                    <i data-lucide="award" class="w-3.5 h-3.5 text-indigo-400"></i>
                                                    <span>ใบประกาศ</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php $delay += 30; endforeach; ?>

                                <!-- Row 4 in Mobile Cards -->
                                <?php if (!empty($otherRegs)): ?>
                                    <div class="glass-sci-card rounded-3xl p-5 border border-indigo-500/20 bg-slate-950/40 text-center space-y-3 row-anim" style="animation-delay: <?= $delay ?>ms">
                                        <p class="text-xs text-slate-400 font-bold">รางวัลชมเชยและผู้เข้าร่วมการแข่งขันในรายการนี้</p>
                                        <button type="button" onclick="openOtherAwardsModal('<?= esc($compKey, 'js') ?>', '<?= esc(json_encode($otherRegs), 'js') ?>')" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs transition-all shadow-md cursor-pointer">
                                            <i data-lucide="search" class="w-4 h-4 text-white"></i>
                                            <span>ค้นหารายชื่อรางวัลชมเชย (<?= count($otherRegs) ?> รายการ)</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Sci-Fi Locked Screen View -->
            <div class="glass-sci-card rounded-3xl p-12 text-center border border-indigo-500/30 max-w-2xl mx-auto my-12 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-950/20 via-transparent to-purple-950/20 pointer-events-none"></div>
                <div class="relative z-10 space-y-6 py-6">
                    <!-- Animated Lock Icon with Ping -->
                    <div class="relative w-24 h-24 mx-auto flex items-center justify-center rounded-full bg-indigo-50/10 border border-indigo-500/30 text-indigo-400">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-500/10 opacity-75"></span>
                        <i data-lucide="lock" class="w-12 h-12 text-indigo-450 dark:text-indigo-400 animate-pulse"></i>
                    </div>
                    
                    <div class="space-y-3">
                        <h2 class="text-xl sm:text-2xl font-black text-white tracking-wider">ระบบประกาศผลรางวัลยังไม่เปิดให้บริการ</h2>
                        <p class="text-slate-400 text-xs sm:text-sm max-w-md mx-auto leading-relaxed">
                            เจ้าหน้าที่กำลังดำเนินการบันทึกข้อมูลและตรวจสอบความถูกต้องของคะแนนการประกวดและแข่งขัน เมื่อการตรวจสอบเสร็จสิ้นระบบจะทำการเปิดเผยผลรางวัลให้ทราบโดยทั่วกัน
                        </p>
                    </div>
                    
                    <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-indigo-950/40 border border-indigo-500/20 text-xs font-bold text-indigo-300">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>กำลังจัดทำและประมวลผลคะแนนการแข่งขัน</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    function showMembersModal(membersJson, advisorsJson) {
        function escapeHtml(text) {
            if (!text) return '';
            return text.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
        try {
            const members = JSON.parse(membersJson);
            const advisors = JSON.parse(advisorsJson);
            
            let htmlList = '<div class="space-y-4 text-left p-1 mt-2">';
            
            // Members Section
            htmlList += '<div class="space-y-2">';
            htmlList += '<h5 class="text-xs font-black text-indigo-400 uppercase tracking-wider mb-1.5 flex items-center gap-1.5"><i data-lucide="users" class="w-3.5 h-3.5"></i> รายชื่อสมาชิกทีม</h5>';
            members.forEach((m, idx) => {
                let mText = '';
                if (m && typeof m === 'object') {
                    const prefix = (m.prefix || '').trim();
                    const name = (m.name || '').trim();
                    mText = (prefix ? prefix + ' ' : '') + name;
                    if (m.custom_fields && Object.keys(m.custom_fields).length > 0) {
                        const cfStr = [];
                        for (const [cfKey, cfVal] of Object.entries(m.custom_fields)) {
                            if (cfVal) {
                                cfStr.push(`${cfKey}: ${cfVal}`);
                            }
                        }
                        if (cfStr.length > 0) {
                            mText += ' (' + cfStr.join(', ') + ')';
                        }
                    }
                } else {
                    mText = m;
                }
                htmlList += `
                    <div class="flex items-center gap-3 p-3 bg-slate-950/50 rounded-2xl border border-slate-800/80">
                        <span class="w-6 h-6 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-bold text-xs shrink-0">${idx + 1}</span>
                        <span class="text-sm font-bold text-slate-200">${escapeHtml(mText)}</span>
                    </div>
                `;
            });
            htmlList += '</div>';

            // Advisors Section
            if (advisors && advisors.length > 0) {
                htmlList += '<div class="space-y-2 mt-4">';
                htmlList += '<h5 class="text-xs font-black text-emerald-400 uppercase tracking-wider mb-1.5 flex items-center gap-1.5"><i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> ครู/อาจารย์ผู้ควบคุมทีม</h5>';
                advisors.forEach((a, idx) => {
                    htmlList += `
                        <div class="flex items-center gap-3 p-3 bg-slate-950/50 rounded-2xl border border-slate-800/80">
                            <span class="w-6 h-6 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold text-xs shrink-0">${idx + 1}</span>
                            <span class="text-sm font-bold text-slate-200">${a}</span>
                        </div>
                    `;
                });
                htmlList += '</div>';
            }
            
            htmlList += '</div>';

            Swal.fire({
                title: 'รายละเอียดรายชื่อทีม',
                html: htmlList,
                width: 'min(450px, 95%)',
                background: '#090d16',
                color: '#cbd5e1',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'ปิดหน้าต่าง',
                customClass: {
                    popup: 'glass-card rounded-[2rem] border border-indigo-500/20'
                },
                didOpen: () => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        } catch (e) {
            console.error('Failed to parse JSON:', e);
        }
    }

    function openOtherAwardsModal(compName, otherRegsJson) {
        try {
            const otherAwardsList = JSON.parse(otherRegsJson);
            
            Swal.fire({
                title: `รางวัลชมเชย / เข้าร่วมแข่งขัน`,
                html: `
                    <div class="space-y-4 text-left p-1">
                        <div class="p-3 bg-indigo-950/30 border border-indigo-500/20 rounded-2xl mb-2 text-center">
                            <span class="text-[10px] text-indigo-400 font-extrabold uppercase tracking-widest block">ประเภทการแข่งขัน</span>
                            <span class="text-sm font-black text-white">${compName}</span>
                        </div>
                        <p class="text-xs text-slate-400 font-bold mb-2">ระบุชื่อนักเรียน ชื่อทีม หรือโรงเรียนเพื่อค้นหาและดาวน์โหลดเกียรติบัตร</p>
                        <div class="relative">
                            <input type="text" id="swal-award-search" placeholder="พิมพ์คำค้นหา..." class="w-full px-4 py-3 bg-slate-900 border border-indigo-500/35 rounded-xl text-white font-medium text-sm outline-none focus:border-indigo-500">
                        </div>
                        <div id="swal-search-results" class="max-h-[300px] overflow-y-auto space-y-2 mt-4 pr-1">
                            <!-- JS Will Render Initial List Here -->
                        </div>
                    </div>
                `,
                width: 'min(520px, 95%)',
                background: '#090d16',
                color: '#cbd5e1',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'ปิดหน้าต่าง',
                customClass: {
                    popup: 'glass-card rounded-[2rem] border border-indigo-500/20'
                },
                didOpen: () => {
                    const input = document.getElementById('swal-award-search');
                    const container = document.getElementById('swal-search-results');
                    
                    function renderList(list) {
                        if (list.length === 0) {
                            container.innerHTML = '<p class="text-center text-xs text-slate-500 py-6">❌ ไม่พบรายชื่อที่ค้นหา</p>';
                            return;
                        }
                        
                        let html = '';
                        list.forEach(item => {
                            const certUrl = `<?= base_url('science-week/certificate/view-all/competition/') ?>${item.reg_code}`;
                            const schoolInfo = item.reg_school_province ? `${item.reg_school_name} (จ.${item.reg_school_province})` : item.reg_school_name;
                            const teamName = item.reg_team_name ? item.reg_team_name : 'ทั่วไป (บุคคลเดี่ยว)';
                            
                            html += `
                                <div class="p-3.5 bg-slate-950/60 rounded-2xl border border-slate-800/80 space-y-2 text-xs">
                                    <div class="flex justify-between items-start gap-2">
                                        <div>
                                            <h4 class="font-extrabold text-white text-sm">${teamName}</h4>
                                            <p class="text-slate-350 font-medium text-[11px] mt-0.5">${schoolInfo}</p>
                                        </div>
                                        <span class="px-2.5 py-1 bg-slate-800 text-slate-300 border border-slate-700/50 rounded-lg font-bold text-[9px] shrink-0 uppercase">${item.reg_rank}</span>
                                    </div>
                                    <div class="flex justify-end pt-1">
                                        <a href="${certUrl}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-500/10 hover:bg-indigo-600 hover:text-white text-indigo-300 transition-colors font-bold text-[10px]">
                                            <i data-lucide="award" class="w-3.5 h-3.5"></i>
                                            <span>ดาวน์โหลดเกียรติบัตร</span>
                                        </a>
                                    </div>
                                </div>
                            `;
                        });
                        
                        container.innerHTML = html;
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    }
                    
                    // Render all other awards initially
                    renderList(otherAwardsList);
                    
                    input.addEventListener('input', () => {
                        const query = input.value.trim().toLowerCase();
                        if (query === '') {
                            renderList(otherAwardsList);
                            return;
                        }
                        
                        const filtered = otherAwardsList.filter(item => {
                            const teamName = (item.reg_team_name || '').toLowerCase();
                            const schoolName = (item.reg_school_name || '').toLowerCase();
                            let membersStr = '';
                            try {
                                const members = JSON.parse(item.reg_members) || [];
                                membersStr = members.map(m => typeof m === 'object' ? (m.name || '') : m).join(' ').toLowerCase();
                            } catch (e) {}
                            
                            return teamName.includes(query) || schoolName.includes(query) || membersStr.includes(query);
                        });
                        
                        renderList(filtered);
                    });
                }
            });
        } catch(e) {
            console.error('Failed to parse other registrations JSON:', e);
        }
    }

    // Particles Canvas
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
