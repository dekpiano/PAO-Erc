<?php
use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Page navigation" class="flex flex-wrap items-center justify-center gap-1.5 w-full">
    <!-- First & Previous -->
    <?php if ($pager->hasPrevious()) : ?>
        <a href="<?= $pager->getFirst() ?>" aria-label="หน้าแรก" title="หน้าแรก" class="px-3 py-2 rounded-2xl bg-slate-900/80 hover:bg-slate-800 border border-slate-800 hover:border-indigo-500 text-slate-400 hover:text-white transition-all text-xs font-extrabold flex items-center gap-1 shadow-sm">
            <span>&laquo;</span>
            <span class="hidden sm:inline">หน้าแรก</span>
        </a>
        <a href="<?= $pager->getPrevious() ?>" aria-label="ก่อนหน้า" title="ก่อนหน้า" class="p-2 sm:px-3 sm:py-2 rounded-2xl bg-slate-900/80 hover:bg-slate-800 border border-slate-800 hover:border-indigo-500 text-slate-400 hover:text-white transition-all text-xs font-bold flex items-center gap-1 shadow-sm">
            <i data-lucide="chevron-left" class="w-4 h-4 text-indigo-400"></i>
            <span class="hidden sm:inline">ก่อนหน้า</span>
        </a>
    <?php endif ?>

    <!-- Page Number Links -->
    <div class="flex items-center gap-1 sm:gap-1.5 mx-1">
        <?php foreach ($pager->links() as $link) : ?>
            <a href="<?= $link['uri'] ?>" class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl flex items-center justify-center text-xs font-black transition-all duration-200 <?= $link['active'] ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/25 border border-indigo-400 scale-105' : 'bg-slate-900/80 hover:bg-slate-800 border border-slate-800 hover:border-indigo-500/50 text-slate-300 hover:text-white hover:scale-105' ?>">
                <?= $link['title'] ?>
            </a>
        <?php endforeach ?>
    </div>

    <!-- Next & Last -->
    <?php if ($pager->hasNext()) : ?>
        <a href="<?= $pager->getNext() ?>" aria-label="ถัดไป" title="ถัดไป" class="p-2 sm:px-3 sm:py-2 rounded-2xl bg-slate-900/80 hover:bg-slate-800 border border-slate-800 hover:border-indigo-500 text-slate-400 hover:text-white transition-all text-xs font-bold flex items-center gap-1 shadow-sm">
            <span class="hidden sm:inline">ถัดไป</span>
            <i data-lucide="chevron-right" class="w-4 h-4 text-indigo-400"></i>
        </a>
        <a href="<?= $pager->getLast() ?>" aria-label="หน้าสุดท้าย" title="หน้าสุดท้าย" class="px-3 py-2 rounded-2xl bg-slate-900/80 hover:bg-slate-800 border border-slate-800 hover:border-indigo-500 text-slate-400 hover:text-white transition-all text-xs font-extrabold flex items-center gap-1 shadow-sm">
            <span class="hidden sm:inline">หน้าสุดท้าย</span>
            <span>&raquo;</span>
        </a>
    <?php endif ?>
</nav>
