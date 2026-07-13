<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                <i data-lucide="megaphone" class="w-5 h-5 text-white"></i>
            </div>
            จัดการไฟล์ประกาศ
        </h2>
        <p class="text-slate-400 mt-2 text-sm font-medium">อัปโหลด ลบ และจัดการไฟล์ประกาศหน้าเว็บ สำหรับปีการศึกษา <?= $selected_year ?></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Upload -->
    <div class="lg:col-span-1">
        <div class="glass-card rounded-2xl p-6 border border-slate-800">
            <h3 class="text-lg font-bold text-white flex items-center gap-2 mb-4">
                <i data-lucide="upload-cloud" class="w-5 h-5 text-indigo-400"></i> อัปโหลดไฟล์ประกาศใหม่
            </h3>
            <form id="uploadForm" onsubmit="handleUpload(event)" enctype="multipart/form-data">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">หัวข้อประกาศ <span class="text-rose-500">*</span></label>
                        <input type="text" name="ann_title" id="ann_title" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder:text-slate-600" placeholder="ระบุหัวข้อประกาศ...">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">ไฟล์แนบ <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <input type="file" name="ann_file" id="ann_file" required accept=".pdf,.jpg,.jpeg,.png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="updateFileName(this)">
                            <div class="w-full border-2 border-dashed border-slate-700 rounded-xl px-4 py-6 text-center group-hover:border-indigo-500 group-hover:bg-indigo-500/5 transition-all">
                                <i data-lucide="file-up" class="w-6 h-6 text-slate-400 mx-auto mb-2 group-hover:text-indigo-400"></i>
                                <span id="file-name-display" class="text-sm font-medium text-slate-300 block truncate">คลิกเพื่อเลือกไฟล์ หรือลากไฟล์มาวาง</span>
                                <span class="text-[10px] text-slate-500 block mt-1">รองรับ PDF, JPG, PNG (สูงสุด 10MB)</span>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" id="btn-submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-indigo-500/25 flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Announcement List -->
    <div class="lg:col-span-2">
        <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden flex flex-col h-full">
            <div class="p-6 border-b border-slate-800/50 flex justify-between items-center bg-slate-900/30">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="list" class="w-5 h-5 text-cyan-400"></i> รายการไฟล์ประกาศทั้งหมด
                </h3>
                <span class="px-3 py-1 bg-slate-800 rounded-full text-xs font-bold text-slate-300 border border-slate-700">
                    ทั้งหมด <?= count($announcements) ?> รายการ
                </span>
            </div>
            
            <div class="p-0 overflow-x-auto custom-scrollbar flex-1">
                <?php if(empty($announcements)): ?>
                    <div class="py-12 flex flex-col items-center justify-center text-slate-500">
                        <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center mb-4">
                            <i data-lucide="inbox" class="w-8 h-8 opacity-50"></i>
                        </div>
                        <p class="text-sm font-medium">ยังไม่มีไฟล์ประกาศในปีการศึกษานี้</p>
                    </div>
                <?php else: ?>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900/50">
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">หัวข้อประกาศ</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-32">ประเภทไฟล์</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-40">วันที่อัปโหลด</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-24 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            <?php foreach($announcements as $ann): ?>
                                <?php 
                                    $ext = pathinfo($ann['ann_file'], PATHINFO_EXTENSION);
                                    $icon = 'file';
                                    $color = 'text-slate-400';
                                    if(in_array(strtolower($ext), ['pdf'])) {
                                        $icon = 'file-text';
                                        $color = 'text-rose-400';
                                    } elseif(in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
                                        $icon = 'image';
                                        $color = 'text-blue-400';
                                    }
                                ?>
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-200"><?= esc($ann['ann_title']) ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="<?= base_url($ann['ann_file']) ?>" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-800/50 border border-slate-700/50 hover:bg-slate-700 hover:border-slate-600 transition-all text-xs font-medium <?= $color ?>">
                                            <i data-lucide="<?= $icon ?>" class="w-3.5 h-3.5"></i> <?= strtoupper($ext) ?>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400 font-medium">
                                        <?= date('d/m/Y H:i', strtotime($ann['ann_created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button" onclick="deleteAnnouncement(<?= $ann['ann_id'] ?>)" class="p-2 bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition-all" title="ลบไฟล์">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function updateFileName(input) {
        const display = document.getElementById('file-name-display');
        if (input.files && input.files.length > 0) {
            display.textContent = input.files[0].name;
            display.classList.add('text-indigo-400');
            display.classList.remove('text-slate-300');
        } else {
            display.textContent = 'คลิกเพื่อเลือกไฟล์ หรือลากไฟล์มาวาง';
            display.classList.remove('text-indigo-400');
            display.classList.add('text-slate-300');
        }
    }

    function handleUpload(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const btnSubmit = document.getElementById('btn-submit');
        
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> กำลังอัปโหลด...';
        lucide.createIcons();

        fetch('<?= base_url('science-week/staff/announcements/store') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: data.message,
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#6366f1',
                    customClass: { popup: 'glass-card rounded-[2rem]' }
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: data.message || 'ไม่สามารถอัปโหลดไฟล์ได้',
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'glass-card rounded-[2rem]' }
                });
                
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i> บันทึกข้อมูล';
                lucide.createIcons();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'ข้อผิดพลาดระบบ',
                text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้',
                background: getSwalColors().bg,
                color: getSwalColors().text,
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'glass-card rounded-[2rem]' }
            });
            
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i> บันทึกข้อมูล';
            lucide.createIcons();
        });
    }

    function deleteAnnouncement(id) {
        Swal.fire({
            title: 'ยืนยันการลบ',
            text: "คุณต้องการลบไฟล์ประกาศนี้ใช่หรือไม่? ไฟล์ที่ถูกลบจะไม่สามารถกู้คืนได้",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#475569',
            confirmButtonText: 'ยืนยันการลบ',
            cancelButtonText: 'ยกเลิก',
            background: getSwalColors().bg,
            color: getSwalColors().text,
            customClass: { popup: 'glass-card rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`<?= base_url('science-week/staff/announcements/delete') ?>/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบสำเร็จ',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#6366f1',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ลบไม่สำเร็จ',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#ef4444',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อผิดพลาดระบบ',
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
