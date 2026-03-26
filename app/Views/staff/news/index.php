<?= $this->extend('staff/layout/admin') ?>

<?= $this->section('content') ?>
    <div class="mb-12 flex justify-between items-end" data-aos="fade-up">
        <div>
            <h2 class="text-3xl font-black text-slate-900 mb-2">จัดการข่าวสาร</h2>
            <p class="text-slate-400 font-medium tracking-wide flex items-center gap-2 uppercase text-xs">
                <i data-lucide="newspaper" class="w-4 h-4 text-blue-600"></i> Manage News & PR
            </p>
        </div>
        <a href="<?= base_url('staff/news/create') ?>" class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold flex items-center gap-2 hover:bg-blue-700 transition-colors shadow-lg shadow-blue-100">
            <i data-lucide="plus-circle" class="w-5 h-5"></i>
            เพิ่มข่าวใหม่
        </a>
    </div>

    <?php /* Flash messages removed, handled by Global Swal in admin.php */ ?>

    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">ข่าวสาร</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">หมวดหมู่</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">สถานะ</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">วันที่สร้าง</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">ยอดชม</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($news)): ?>
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-slate-400 font-medium">
                                ไม่พบข้อมูลข่าวสารที่คุณเพิ่มไว้
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($news as $item): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <?php if($item['news_cover']): ?>
                                            <div class="w-12 h-12 rounded-xl border border-slate-100 overflow-hidden flex-shrink-0">
                                                <img src="<?= base_url('uploads/news/covers/' . $item['news_cover']) ?>" class="w-full h-full object-cover">
                                            </div>
                                        <?php else: ?>
                                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 flex-shrink-0">
                                                <i data-lucide="image" class="w-6 h-6"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h4 class="text-sm font-black text-slate-800 leading-snug line-clamp-1"><?= $item['news_title'] ?></h4>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">ID: #<?= $item['news_id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                        <?= $item['news_category'] ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <?php if($item['news_status'] === 'published'): ?>
                                        <span class="flex items-center gap-1.5 text-emerald-500 text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> เผยแพร่
                                        </span>
                                    <?php elseif($item['news_status'] === 'draft'): ?>
                                        <span class="flex items-center gap-1.5 text-amber-500 text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> ฉบับร่าง
                                        </span>
                                    <?php else: ?>
                                        <span class="flex items-center gap-1.5 text-slate-400 text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> ซ่อนไว้
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6 text-sm text-slate-500 font-medium">
                                    <?= date('d/m/Y', strtotime($item['news_created_at'])) ?>
                                </td>
                                <td class="px-8 py-6 text-sm text-slate-800 font-black">
                                    <?= number_format($item['news_view_count']) ?>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= base_url('staff/news/edit/' . $item['news_id']) ?>" class="w-9 h-9 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                            <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                                        </a>
                                        <button onclick="confirmDelete('<?= base_url('staff/news/delete/' . $item['news_id']) ?>')" class="w-9 h-9 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all">
                                            <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลและรูปภาพทั้งหมดจะถูกลบถาวร!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ลบทันที',
            cancelButtonText: 'ยกเลิก',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl px-6 py-3 font-bold',
                cancelButton: 'rounded-xl px-6 py-3 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    }
</script>
<?= $this->endSection() ?>
