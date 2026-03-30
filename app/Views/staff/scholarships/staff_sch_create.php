<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
    <div class="mb-12 flex items-center gap-6" data-aos="fade-up">
        <a href="<?= base_url('staff/scholarships') ?>" class="w-12 h-12 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-100 flex items-center justify-center text-slate-400 hover:text-amber-600 transition-colors">
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </a>
        <div>
            <h2 class="text-3xl font-black text-slate-900 mb-2">เพิ่มทุนการศึกษาใหม่</h2>
            <p class="text-slate-400 font-medium tracking-wide flex items-center gap-2 uppercase text-xs text-amber-600">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Create Scholarship
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-9" data-aos="fade-up" data-aos-delay="100">
            <form id="sch-form" action="<?= base_url('staff/scholarship/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden p-10 space-y-8">
                    
                    <!-- ชื่อทุน -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">ชื่อทุนการศึกษา</label>
                        <input type="text" name="sch_title" value="<?= old('sch_title') ?>" placeholder="ตัวอย่าง: ทุนเรียนดีเพื่ออนาคต 2569" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-100 transition-all">
                        <?php if(isset(session('errors')['sch_title'])): ?>
                            <p class="text-[10px] text-rose-500 font-black mt-2 uppercase tracking-wide"><?= session('errors')['sch_title'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- ข้อมูลเบื้องต้น -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">จำนวนเงินทุน (ถ้ามี)</label>
                            <input type="text" name="sch_amount" value="<?= old('sch_amount') ?>" placeholder="เช่น 5,000 บาท/ปี" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-amber-50 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">วันหมดเขตรับสมัคร</label>
                            <input type="date" name="sch_deadline" value="<?= old('sch_deadline') ?>" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-amber-50 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">สถานะ</label>
                            <select name="sch_status" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-amber-50 transition-all cursor-pointer">
                                <option value="published" <?= old('sch_status') == 'published' ? 'selected' : '' ?>>เปิดรับสมัคร</option>
                                <option value="draft" <?= old('sch_status') == 'draft' ? 'selected' : '' ?>>ฉบับร่าง</option>
                                <option value="closed" <?= old('sch_status') == 'closed' ? 'selected' : '' ?>>ปิดรับสมัคร</option>
                            </select>
                        </div>
                    </div>

                    <!-- ส่วนของการอัปโหลด -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        <!-- อัปโหลดหน้าปก -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">รูปภาพหน้าปก</label>
                            <div class="relative group">
                                <input type="file" name="sch_cover" id="sch_cover" accept="image/*" class="hidden" onchange="previewImage(this)">
                                <label for="sch_cover" class="cursor-pointer block">
                                    <div id="preview-container" class="aspect-video rounded-[2rem] bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-4 group-hover:bg-slate-100 group-hover:border-amber-300 transition-all overflow-hidden relative">
                                        <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-slate-400 group-hover:text-amber-500 transition-all">
                                            <i data-lucide="image" class="w-8 h-8"></i>
                                        </div>
                                        <div class="text-center px-4">
                                            <p class="text-sm font-black text-slate-600">คลิกเพื่ออัปโหลดหน้าปก</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">PNG, JPG (แนะนำ 1280x720px)</p>
                                        </div>
                                        <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- อัปโหลดไฟล์แนบ -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">เอกสารแนบ (ระเบียบการ/ใบสมัคร)</label>
                            <div class="relative group h-[calc(100%-2.5rem)]">
                                <input type="file" name="sch_attachment" id="sch_attachment" accept=".pdf,.doc,.docx,.zip" class="hidden" onchange="updateFileName(this)">
                                <label for="sch_attachment" class="cursor-pointer block h-full">
                                    <div id="attach-container" class="h-full min-h-[160px] rounded-[2rem] bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-4 group-hover:bg-slate-100 group-hover:border-blue-300 transition-all relative">
                                        <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-slate-400 group-hover:text-blue-500 transition-all">
                                            <i data-lucide="file-text" class="w-8 h-8"></i>
                                        </div>
                                        <div class="text-center px-6">
                                            <p id="attach-label" class="text-sm font-black text-slate-600 leading-tight">คลิกเพื่อแนบไฟล์เอกสาร</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-2">PDF, DOCX, ZIP (ไม่เกิน 5MB)</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- รายละเอียดทุน -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">รายละเอียดทุนการศึกษา</label>
                        <textarea name="sch_content" rows="12" required placeholder="ระบุรายละเอียดโครงการ เกณฑ์การคัดเลือก และวิธีการสมัคร..." class="w-full px-8 py-6 bg-slate-50 border border-slate-100 rounded-[2rem] text-slate-800 font-medium focus:outline-none focus:ring-4 focus:ring-amber-50 transition-all leading-relaxed"><?= old('sch_content') ?></textarea>
                        <?php if(isset(session('errors')['sch_content'])): ?>
                            <p class="text-[10px] text-rose-500 font-black mt-2 uppercase tracking-wide"><?= session('errors')['sch_content'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- ปุ่มดำเนินการ -->
                    <div class="pt-8 border-t border-slate-100 flex justify-end gap-3">
                        <button type="reset" class="px-8 py-4 bg-slate-100 text-slate-400 rounded-2xl font-bold flex items-center gap-2 hover:bg-slate-200 transition-colors">
                            <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                            ยกเลิก
                        </button>
                        <button type="submit" id="submit-btn" class="px-12 py-4 bg-amber-600 text-white rounded-2xl font-black text-lg flex items-center gap-3 hover:bg-amber-700 transition-all shadow-xl shadow-amber-100 hover:-translate-y-1 disabled:opacity-70 disabled:cursor-not-allowed">
                            <i data-lucide="send" class="w-6 h-6"></i>
                            บันทึกข้อมูล
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="lg:col-span-3 space-y-8" data-aos="fade-up" data-aos-delay="200">
            <div class="bg-amber-50 border border-amber-100 p-8 rounded-[2.5rem]">
                <h3 class="text-amber-900 font-black text-lg mb-4 flex items-center gap-3">
                    <i data-lucide="info" class="w-6 h-6"></i> ข้อแนะนำ
                </h3>
                <ul class="space-y-4">
                    <li class="flex gap-4 text-sm font-medium text-amber-700">
                        <span class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-[10px] font-black flex-shrink-0 shadow-sm border border-amber-100">1</span>
                        ระบุรายละเอียดวันหมดเขตให้ชัดเจน
                    </li>
                    <li class="flex gap-4 text-sm font-medium text-amber-700">
                        <span class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-[10px] font-black flex-shrink-0 shadow-sm border border-amber-100">2</span>
                        แนบไฟล์ PDF เพื่อให้อ่านง่ายในทุกอุปกรณ์
                    </li>
                    <li class="flex gap-4 text-sm font-medium text-amber-700">
                        <span class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-[10px] font-black flex-shrink-0 shadow-sm border border-amber-100">3</span>
                        รูปภาพหน้าปกควรมีขนาด 16:9
                    </li>
                </ul>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    lucide.createIcons();

    // ตัวอย่างการพรีวิวรูปภาพ
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // แสดงชื่อไฟล์ที่เลือก (สำหรับเอกสารแนบ)
    function updateFileName(input) {
        const label = document.getElementById('attach-label');
        const container = document.getElementById('attach-container');
        if (input.files && input.files[0]) {
            label.innerText = 'เลือกไฟล์: ' + input.files[0].name;
            container.classList.remove('bg-slate-50');
            container.classList.add('bg-blue-50', 'border-blue-400');
        } else {
            label.innerText = 'คลิกเพื่อแนบไฟล์เอกสาร';
            container.classList.add('bg-slate-50');
            container.classList.remove('bg-blue-50', 'border-blue-400');
        }
    }

    // จัดการการส่งฟอร์ม (AJAX)
    document.getElementById('sch-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = this;
        const btn = document.getElementById('submit-btn');
        const originalBtnContent = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = `<i data-lucide="loader-2" class="w-6 h-6 animate-spin inline mr-2"></i> กำลังบันทึก...`;
        lucide.createIcons();

        try {
            let tempCover = null;
            const coverInput = document.getElementById('sch_cover');
            
            // กรณีอัปโหลดรูปหน้าปก (ใช้ Chunked Upload เพื่อความชัวร์ถ้าภาพใหญ่)
            if (coverInput.files && coverInput.files[0]) {
                btn.innerHTML = `<i data-lucide="loader-2" class="w-6 h-6 animate-spin inline mr-2"></i> กำลังส่งรูปหน้าปก...`;
                tempCover = await uploadFileInChunks(coverInput.files[0]);
            }

            const formData = new FormData(form);
            if (tempCover) {
                formData.delete('sch_cover');
                formData.append('temp_cover', tempCover);
            }

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await response.json();
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[1.5rem]' }
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                let errorMsg = data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).join('<br>');
                }
                throw new Error(errorMsg);
            }
        } catch (error) {
            btn.disabled = false;
            btn.innerHTML = originalBtnContent;
            lucide.createIcons();
            Swal.fire({
                icon: 'error',
                title: 'พบปัญหาการบันทึกข้อมูล',
                html: `<div class="text-sm font-bold text-rose-500">${error.message}</div>`,
                customClass: { popup: 'rounded-[1.5rem]' }
            });
        }
    });

    // ฟังก์ชันช่วยอัปโหลดไฟล์แบบ Chunk (Reuse จาก News)
    async function uploadFileInChunks(file) {
        const chunkSize = 1024 * 512;
        const totalChunks = Math.ceil(file.size / chunkSize);
        const fileId = Math.random().toString(36).substring(2, 11) + Date.now();
        const extension = file.name.split('.').pop();
        const filename = fileId + '.' + extension;

        for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
            const start = chunkIndex * chunkSize;
            const end = Math.min(start + chunkSize, file.size);
            const chunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('file', chunk);
            formData.append('filename', filename);
            formData.append('chunkIndex', chunkIndex);
            formData.append('totalChunks', totalChunks);
            formData.append('fileId', fileId);

            const response = await fetch('<?= base_url('staff/news/uploadChunk') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            if (result.status === 'error') throw new Error(result.message);
            if (result.status === 'completed') return result.temp_file;
        }
    }
</script>
<?= $this->endSection() ?>
