<?php
$currentUri = uri_string();
$navLinks = [
    [
        'title' => 'ภาพรวม (Dashboard)',
        'icon'  => 'layout-dashboard',
        'url'   => 'staff/sports',
        'exact' => true
    ],
    [
        'title' => 'ชนิดกีฬา & รุ่นการแข่งขัน',
        'icon'  => 'activity',
        'url'   => 'staff/sports/categories',
        'exact' => false
    ],
    [
        'title' => 'ตรวจสอบทีม & นักกีฬา',
        'icon'  => 'shield-check',
        'url'   => 'staff/sports/teams',
        'exact' => false
    ],
    [
        'title' => 'บันทึกผลการแข่งขัน & รางวัล',
        'icon'  => 'trophy',
        'url'   => 'staff/sports/results',
        'exact' => false
    ],
    [
        'title' => 'ระบบออกเกียรติบัตร',
        'icon'  => 'award',
        'url'   => 'staff/sports/certificates',
        'exact' => false
    ],
];
?>

<!-- Sports Admin Sub-Navigation Menu -->
<div class="bg-white p-2 sm:p-2.5 rounded-3xl border border-slate-100 shadow-sm overflow-x-auto flex items-center justify-between gap-2 scrollbar-none mb-6">
    <div class="flex items-center gap-1.5 min-w-max">
        <?php foreach ($navLinks as $nav): ?>
            <?php 
                $isActive = $nav['exact'] ? ($currentUri === $nav['url']) : (strpos($currentUri, $nav['url']) === 0);
            ?>
            <a href="<?= base_url($nav['url']) ?>" 
               class="px-4 py-2.5 rounded-2xl font-bold text-xs flex items-center gap-2 transition-all <?= $isActive ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' ?>">
                <i data-lucide="<?= $nav['icon'] ?>" class="w-4 h-4 <?= $isActive ? 'text-white' : 'text-slate-400' ?>"></i>
                <span><?= $nav['title'] ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Quick Public Link & Back -->
    <div class="flex items-center gap-2 min-w-max pl-2 border-l border-slate-100">
        <a href="<?= base_url('sports') ?>" target="_blank" class="px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-2xl font-bold text-xs flex items-center gap-1.5 transition-colors" title="ดูหน้าเว็บลงทะเบียนสำหรับบุคคลภายนอก">
            <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-400"></i>
            <span>หน้าเว็บรับสมัคร</span>
        </a>
    </div>
</div>
