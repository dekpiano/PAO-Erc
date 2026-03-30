<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
    <div class="mb-12 flex justify-between items-end" data-aos="fade-up">
        <div>
            <h2 class="text-3xl font-black text-slate-900 mb-2">จัดการทุนการศึกษา</h2>
            <p class="text-slate-400 font-medium tracking-wide flex items-center gap-2 uppercase text-xs">
                <i data-lucide="graduation-cap" class="w-4 h-4 text-amber-600"></i> Manage Scholarships
            </p>
        </div>
        <a href="<?= base_url('staff/scholarship/create') ?>" class="px-6 py-3 bg-amber-600 text-white rounded-2xl font-bold flex items-center gap-2 hover:bg-amber-700 transition-colors shadow-lg shadow-amber-100">
            <i data-lucide="plus-circle" class="w-5 h-5"></i>
            เพิ่มทุนใหม่
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-wider text-slate-400 w-16 text-center">#</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">ชื่อทุนการศึกษา</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">จำนวนเงิน</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">สถานะ</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">หมดเขต</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($scholarships)): ?>
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-slate-400 font-medium">
                                ไม่พบข้อมูลทุนการศึกษาที่คุณเพิ่มไว้
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($scholarships as $item): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-6 font-black text-slate-400 text-sm italic text-center">
                                    <?= str_pad($i++, 2, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <?php if($item['sch_cover']): ?>
                                            <div class="w-12 h-12 rounded-xl border border-slate-100 overflow-hidden flex-shrink-0 shadow-sm shadow-slate-100">
                                                <img src="<?= base_url('uploads/scholarships/covers/' . $item['sch_cover']) ?>" class="w-full h-full object-cover">
                                            </div>
                                        <?php else: ?>
                                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 flex-shrink-0">
                                                <i data-lucide="image" class="w-6 h-6"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h4 class="text-sm font-black text-slate-800 leading-snug line-clamp-1"><?= $item['sch_title'] ?></h4>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">ID: #<?= $item['sch_id'] ?></span>
                                                <?php if($item['sch_attachment']): ?>
                                                    <span class="flex items-center gap-1 px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[9px] font-black uppercase tracking-tighter">
                                                        <i data-lucide="file-text" class="w-2.5 h-2.5"></i> มีไฟล์แนบ
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-sm font-black text-slate-700">
                                        <?= $item['sch_amount'] ?: 'ไม่ระบุ' ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <?php if($item['sch_status'] === 'published'): ?>
                                        <span class="flex items-center gap-1.5 text-emerald-500 text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> เปิดรับสมัคร
                                        </span>
                                    <?php elseif($item['sch_status'] === 'draft'): ?>
                                        <span class="flex items-center gap-1.5 text-amber-500 text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> ฉบับร่าง
                                        </span>
                                    <?php else: ?>
                                        <span class="flex items-center gap-1.5 text-rose-500 text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> ปิดรับสมัคร
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6 text-sm text-slate-500 font-medium">
                                    <?= $item['sch_deadline'] ? date('d/m/Y', strtotime($item['sch_deadline'])) : 'ไม่ระบุ' ?>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= base_url('staff/scholarship/edit/' . $item['sch_id']) ?>" class="w-9 h-9 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all">
                                            <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                                        </a>
                                        <button onclick="confirmDelete('<?= base_url('staff/scholarship/delete/' . $item['sch_id']) ?>')" class="w-9 h-9 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all">
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
            text: "ข้อมูลทุนการศึกษาจะถูกลบถาวร!",
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
