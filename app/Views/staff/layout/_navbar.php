<nav class="flex justify-between items-start mb-8">
    <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-sky-400 to-indigo-400 m-0 leading-none">
        LongWela
    </h2>
    <div class="flex items-center gap-4">
        <?php if(in_array(session()->get('u_role'), ['head', 'director'])): ?>
            <a href="<?= base_url('admin') ?>" class="text-blue-400 text-xs font-bold border border-blue-500/30 px-3 py-1.5 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                หน้าแอดมิน
            </a>
        <?php endif; ?>
        <a href="<?= base_url('auth/logout') ?>" class="text-white/40 text-sm no-underline flex items-center gap-1 hover:text-red-400 transition-colors">
            <i data-lucide="log-out" class="w-4 h-4"></i> ออกจากระบบ
        </a>
    </div>
</nav>

<div class="mb-4 text-left">
    <p class="text-sm opacity-60 m-0">
        สวัสดีคุณ, <strong class="text-blue-400 font-semibold"><?= $fullname ?? 'User' ?></strong>
    </p>
</div>
