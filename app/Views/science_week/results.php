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
    .badge-medal-gold {
        background: linear-gradient(135deg, #fef08a 0%, #ca8a04 100%);
        box-shadow: 0 0 12px rgba(234, 179, 8, 0.3);
        color: #713f12;
    }
    .badge-medal-silver {
        background: linear-gradient(135deg, #e2e8f0 0%, #94a3b8 100%);
        box-shadow: 0 0 12px rgba(148, 163, 184, 0.3);
        color: #1e293b;
    }
    .badge-medal-bronze {
        background: linear-gradient(135deg, #ffedd5 0%, #b45309 100%);
        box-shadow: 0 0 12px rgba(249, 115, 22, 0.3);
        color: #fff;
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
                            <option value="" class="bg-slate-900 text-white">-- การแข่งขันทั้งหมด --</option>
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
                            <i data-lucide="filter" class="w-5 h-5"></i> กรองผล
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
            <div class="space-y-6">
                <?php if (empty($results)): ?>
                    <div class="glass-sci-card rounded-3xl p-12 text-center text-slate-400 border border-dashed border-indigo-500/20">
                        <i data-lucide="award" class="w-16 h-16 mx-auto text-indigo-400/40 mb-4 animate-pulse"></i>
                        <p class="font-bold text-lg mb-1 text-white">ยังไม่มีประกาศผลการแข่งขันในส่วนนี้</p>
                        <p class="text-xs text-slate-450">กรุณาเลือกประเภทการแข่งขันอื่นๆ หรือลองค้นหาด้วยคีย์เวิร์ดใหม่อีกครั้ง</p>
                    </div>
                <?php else: ?>
                    <!-- Desktop Table View -->
                    <div class="hidden md:block glass-sci-card rounded-3xl overflow-hidden shadow-2xl border border-indigo-500/20">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-950/60 border-b border-indigo-500/20 text-slate-350">
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">ประเภทการแข่งขัน / ทีม</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">สถาบันการศึกษา</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider">สมาชิกผู้แข่งขัน</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-center">คะแนน</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-right">รางวัลที่ได้รับ</th>
                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-center">ดาวน์โหลดเกียรติบัตร</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/80">
                                    <?php $delay = 0; foreach ($results as $reg): 
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
                                        } elseif ($reg['reg_rank'] === 'รางวัลเหรียญทอง') {
                                            $badgeClass = 'badge-medal-gold';
                                        } elseif ($reg['reg_rank'] === 'รางวัลเหรียญเงิน') {
                                            $badgeClass = 'badge-medal-silver';
                                        } elseif ($reg['reg_rank'] === 'รางวัลเหรียญทองแดง') {
                                            $badgeClass = 'badge-medal-bronze';
                                        }
                                    ?>
                                        <tr class="hover:bg-slate-900/30 transition-colors row-anim <?= $rowClass ?>" style="animation-delay: <?= $delay ?>ms">
                                            <td class="px-6 py-5">
                                                <span class="text-[10px] text-indigo-400 font-bold block uppercase tracking-wider">
                                                    <?= esc($reg['reg_competition_type']) ?>
                                                    <?php if (!empty($reg['reg_level'])): ?>
                                                        <span class="inline-block px-1.5 py-0.5 rounded text-[8px] font-bold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 ml-1.5 align-middle"><?= esc($reg['reg_level']) ?></span>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="text-sm font-extrabold text-white block mt-1"><?= $reg['reg_team_name'] ? esc($reg['reg_team_name']) : 'ทั่วไป (บุคคลเดี่ยว)' ?></span>
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
                                            <td class="px-6 py-5 text-center whitespace-nowrap">
                                                <?php if ($reg['reg_score'] !== null): ?>
                                                    <span class="text-sm font-black text-indigo-400 font-mono"><?= number_format($reg['reg_score'], 2) ?></span>
                                                <?php else: ?>
                                                    <span class="text-xs text-slate-500 font-medium italic">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-5 text-right whitespace-nowrap">
                                                <?php if (!empty($reg['reg_rank'])): ?>
                                                    <span class="px-3 py-1.5 rounded-xl text-xs font-black tracking-wider uppercase inline-flex items-center gap-1.5 <?= $badgeClass ?>">
                                                        <?= esc($reg['reg_rank']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="px-2.5 py-1 rounded-lg bg-slate-800/40 text-slate-500 border border-slate-800/20 text-[10px] font-black uppercase tracking-wider">
                                                        เข้าร่วมการแข่งขัน
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <a href="<?= base_url('science-week/certificate/view-all/competition/' . $reg['reg_code']) ?>" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-500/10 hover:bg-indigo-600 hover:text-white text-indigo-300 border border-indigo-500/20 hover:border-indigo-500 transition-all font-bold text-xs shadow-sm" title="ดูเกียรติบัตรทั้งหมดในแท็บใหม่">
                                                    <i data-lucide="award" class="w-4 h-4 text-indigo-400"></i>
                                                    <span>ดูเกียรติบัตรทั้งหมด</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php $delay += 50; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden space-y-4">
                        <?php $delay = 0; foreach ($results as $reg): 
                            $isWinner = !empty($reg['reg_rank']);
                            $cardBorderClass = $isWinner ? 'border-amber-500/40 bg-gradient-to-b from-slate-950/80 via-slate-950/60 to-amber-950/10' : 'border-indigo-500/20 bg-slate-950/40';
                            
                            $badgeClass = 'badge-other-award';
                            if ($reg['reg_rank'] === 'รางวัลชนะเลิศ') {
                                $badgeClass = 'badge-winner';
                            } elseif ($reg['reg_rank'] === 'รางวัลรองชนะเลิศอันดับ 1') {
                                $badgeClass = 'badge-runner-1';
                            } elseif ($reg['reg_rank'] === 'รางวัลรองชนะเลิศอันดับ 2') {
                                $badgeClass = 'badge-runner-2';
                            } elseif ($reg['reg_rank'] === 'รางวัลเหรียญทอง') {
                                $badgeClass = 'badge-medal-gold';
                            } elseif ($reg['reg_rank'] === 'รางวัลเหรียญเงิน') {
                                $badgeClass = 'badge-medal-silver';
                            } elseif ($reg['reg_rank'] === 'รางวัลเหรียญทองแดง') {
                                $badgeClass = 'badge-medal-bronze';
                            }
                        ?>
                            <div class="glass-sci-card rounded-3xl p-5 border <?= $cardBorderClass ?> space-y-4 row-anim" style="animation-delay: <?= $delay ?>ms">
                                <div class="flex justify-between items-start gap-4">
                                    <div class="space-y-1">
                                        <span class="text-[9px] text-indigo-400 font-extrabold block uppercase tracking-widest">
                                            <?= esc($reg['reg_competition_type']) ?>
                                            <?php if (!empty($reg['reg_level'])): ?>
                                                <span class="inline-block px-1.5 py-0.5 rounded text-[8px] font-bold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 ml-1.5 align-middle"><?= esc($reg['reg_level']) ?></span>
                                            <?php endif; ?>
                                        </span>
                                        <h4 class="text-sm font-black text-white"><?= $reg['reg_team_name'] ? esc($reg['reg_team_name']) : 'ทั่วไป (บุคคลเดี่ยว)' ?></h4>
                                    </div>
                                    <?php if ($reg['reg_score'] !== null): ?>
                                        <div class="text-right shrink-0">
                                            <span class="text-[9px] text-slate-500 block font-bold uppercase tracking-wider">คะแนน</span>
                                            <span class="text-xs font-black text-indigo-400 font-mono"><?= number_format($reg['reg_score'], 2) ?></span>
                                        </div>
                                    <?php endif; ?>
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
                                        <?php if (!empty($reg['reg_rank'])): ?>
                                            <span class="px-2.5 py-1 rounded-xl text-[9px] font-black tracking-wider uppercase inline-flex items-center gap-1 <?= $badgeClass ?>">
                                                <?= esc($reg['reg_rank']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 rounded-lg bg-slate-800/40 text-slate-500 border border-slate-800/20 text-[9px] font-black uppercase tracking-wider">
                                                เข้าร่วมการแข่งขัน
                                            </span>
                                        <?php endif; ?>
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
                        <?php $delay += 50; endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Sci-Fi Locked Screen View -->
            <div class="glass-sci-card rounded-3xl p-12 text-center border border-indigo-500/30 max-w-2xl mx-auto my-12 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-950/20 via-transparent to-purple-950/20 pointer-events-none"></div>
                <div class="relative z-10 space-y-6 py-6">
                    <!-- Animated Lock Icon with Ping -->
                    <div class="relative w-24 h-24 mx-auto flex items-center justify-center rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-400">
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
