<?php
$currentUri = uri_string();
$navLinks = [
    [
        'title' => 'ภาพรวม',
        'icon'  => 'layout-dashboard',
        'url'   => 'staff/sports',
        'exact' => true
    ],
    [
        'title' => 'ชนิดกีฬา & รุ่น',
        'icon'  => 'layers',
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
        'title' => 'บันทึกผล & รางวัล',
        'icon'  => 'trophy',
        'url'   => 'staff/sports/results',
        'exact' => false
    ],
    [
        'title' => 'เกียรติบัตร',
        'icon'  => 'award',
        'url'   => 'staff/sports/certificates',
        'exact' => false
    ],
];

$activeCompYear  = isset($activeYear) ? (int)$activeYear : (int)(session()->get('sports_active_year') ?: 2569);
$availYears      = isset($availableYears) && is_array($availableYears) ? $availableYears : [2569, 2568, 2567];
if (!in_array($activeCompYear, $availYears)) {
    $availYears[] = $activeCompYear;
    rsort($availYears);
}
?>

<!-- Sports Admin Sub-Navigation Menu -->
<div class="bg-white p-2 sm:p-2.5 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm flex flex-wrap lg:flex-nowrap items-center justify-between gap-2.5 mb-6">
    
    <!-- Navigation Tabs -->
    <div class="flex items-center gap-1 sm:gap-1.5 overflow-x-auto scrollbar-none w-full lg:w-auto pb-1 lg:pb-0">
        <?php foreach ($navLinks as $nav): ?>
            <?php 
                $isActive = $nav['exact'] ? ($currentUri === $nav['url']) : (strpos($currentUri, $nav['url']) === 0);
            ?>
            <a href="<?= base_url($nav['url'] . '?year=' . $activeCompYear) ?>" 
               class="px-3 sm:px-3.5 py-2 rounded-xl sm:rounded-2xl font-bold text-xs flex items-center gap-1.5 sm:gap-2 transition-all whitespace-nowrap shrink-0 <?= $isActive ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-200' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/80' ?>">
                <i data-lucide="<?= $nav['icon'] ?>" class="w-4 h-4 <?= $isActive ? 'text-white' : 'text-slate-400' ?>"></i>
                <span><?= $nav['title'] ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Year Selector & Public Link -->
    <div class="flex items-center gap-2 shrink-0 w-full lg:w-auto justify-between lg:justify-end pt-1 lg:pt-0 border-t lg:border-t-0 lg:border-l border-slate-100 lg:pl-3">
        
        <!-- Year Switcher Dropdown -->
        <div class="flex items-center gap-1.5 bg-slate-50 hover:bg-slate-100/80 px-2.5 sm:px-3 py-1.5 rounded-xl sm:rounded-2xl border border-slate-200/70 transition-colors">
            <i data-lucide="calendar" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
            <span class="text-[11px] font-bold text-slate-500 hidden sm:inline">ดูข้อมูลปี:</span>
            <select onchange="window.location.href='<?= base_url('staff/sports/set-year/') ?>' + this.value" 
                    class="bg-transparent text-xs font-black text-slate-800 outline-none cursor-pointer pr-1">
                <?php foreach ($availYears as $yr): ?>
                    <option value="<?= $yr ?>" <?= $yr == $activeCompYear ? 'selected' : '' ?>>
                        ปี <?= $yr ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Set System Default Year Button -->
        <button type="button" onclick="openSystemYearModal()" 
                class="px-2.5 sm:px-3 py-1.5 sm:py-2 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/80 rounded-xl sm:rounded-2xl text-xs font-black flex items-center gap-1.5 transition-all shadow-xs cursor-pointer" 
                title="ตั้งค่าปีการแข่งขันหลักที่จะแสดงบนหน้าเว็บสาธารณะ">
            <i data-lucide="settings" class="w-3.5 h-3.5 text-amber-600"></i>
            <span class="hidden sm:inline">ตั้งค่าปีหลัก</span>
        </button>

        <!-- Public Portal Link -->
        <a href="<?= base_url('sports') ?>" target="_blank" 
           class="px-3 py-1.5 sm:py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl sm:rounded-2xl font-bold text-xs flex items-center gap-1.5 transition-colors shrink-0" 
           title="เปิดดูหน้าเว็บรับสมัครของบุคคลภายนอก">
            <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-500"></i>
            <span class="text-xs">หน้าเว็บรับสมัคร</span>
        </a>
    </div>
</div>

<!-- System Year Setting Modal -->
<div id="systemYearModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <i data-lucide="settings-2" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-base">ตั้งค่าปีการแข่งขันหลักของระบบ</h3>
                    <p class="text-xs text-slate-400">กำหนดปีที่หน้าเว็บสาธารณะจะเปิดรับสมัคร/แสดงผล</p>
                </div>
            </div>
            <button type="button" onclick="closeSystemYearModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-50">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="<?= base_url('staff/sports/set-system-year') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">ปีการแข่งขันหลัก <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input type="number" name="active_comp_year" value="<?= $activeCompYear ?>" min="2500" max="2650" required 
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-black text-slate-900">
                </div>
                <div class="bg-amber-50/70 border border-amber-200/60 rounded-xl p-3 text-[11px] text-amber-900 leading-relaxed space-y-1">
                    <p class="font-bold flex items-center gap-1">
                        <i data-lucide="info" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i>
                        <span>ผลของการตั้งค่านี้:</span>
                    </p>
                    <p class="text-amber-800">
                        เมื่อเปลี่ยนปีที่นี่ หน้าแรกของระบบลงทะเบียน (Public Portal) จะเปลี่ยนไปแสดงเฉพาะรายการกีฬาและผลการแข่งขันของปีนี้ทันที
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeSystemYearModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs flex items-center gap-1.5 shadow-md shadow-emerald-200 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>บันทึกการตั้งค่าปี</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openSystemYearModal() {
    var m = document.getElementById('systemYearModal');
    if (m) {
        m.classList.remove('hidden');
        if (window.lucide) lucide.createIcons();
    }
}
function closeSystemYearModal() {
    var m = document.getElementById('systemYearModal');
    if (m) {
        m.classList.add('hidden');
    }
}
</script>

