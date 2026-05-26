<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(1);
?>

<nav aria-label="Page navigation" class="flex flex-wrap justify-center items-center gap-1 sm:gap-1.5 pt-4 sm:pt-6 w-full">
    <?php if ($pager->hasPrevious()) : ?>
        <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" class="hidden sm:flex px-3 py-2 rounded-xl bg-slate-900/60 hover:bg-slate-850 border border-slate-800 hover:border-cyan-500/50 text-slate-400 hover:text-white transition-all text-[10px] font-extrabold tracking-wider items-center gap-1 shadow-md shadow-black/10">
            <span>&laquo;</span>
        </a>
        <a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" class="p-2 sm:p-2.5 rounded-xl sm:rounded-2xl bg-slate-900/60 hover:bg-slate-850 border border-slate-800 hover:border-cyan-500/50 text-slate-400 hover:text-white transition-all shadow-md shadow-black/10 flex items-center justify-center">
            <i data-lucide="chevron-left" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
        </a>
    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
        <a href="<?= $link['uri'] ?>" class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl flex items-center justify-center text-[10px] sm:text-xs font-black transition-all duration-300 <?= $link['active'] ? 'bg-gradient-to-r from-cyan-500 to-indigo-600 text-white shadow-lg shadow-cyan-500/20 border border-cyan-400/40 scale-105 tech-glow' : 'bg-slate-900/60 hover:bg-slate-850 border border-slate-800 hover:border-cyan-500/50 text-slate-400 hover:text-white hover:scale-105' ?>">
            <?= $link['title'] ?>
        </a>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" class="p-2 sm:p-2.5 rounded-xl sm:rounded-2xl bg-slate-900/60 hover:bg-slate-850 border border-slate-800 hover:border-cyan-500/50 text-slate-400 hover:text-white transition-all shadow-md shadow-black/10 flex items-center justify-center">
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
        </a>
        <a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" class="hidden sm:flex px-3 py-2 rounded-xl bg-slate-900/60 hover:bg-slate-850 border border-slate-800 hover:border-cyan-500/50 text-slate-400 hover:text-white transition-all text-[10px] font-extrabold tracking-wider items-center gap-1 shadow-md shadow-black/10">
            <span>&raquo;</span>
        </a>
    <?php endif ?>
</nav>
