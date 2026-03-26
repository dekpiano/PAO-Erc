<?= $this->extend('staff/layout/admin') ?>

<?= $this->section('content') ?>
    <div class="mb-12 flex items-center gap-6" data-aos="fade-up">
        <a href="<?= base_url('staff/news') ?>" class="w-12 h-12 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-colors">
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </a>
        <div>
            <h2 class="text-3xl font-black text-slate-900 mb-2">แก้ไขข่าวสาร</h2>
            <p class="text-slate-400 font-medium tracking-wide flex items-center gap-2 uppercase text-xs text-amber-600">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit News Post
            </p>
        </div>
    </div>

    <?php /* Flash messages removed, handled by Global Swal in admin.php */ ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-9" data-aos="fade-up" data-aos-delay="100">
            <form action="<?= base_url('staff/news/update/' . $news['news_id']) ?>" method="post" enctype="multipart/form-data">
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden p-10 space-y-8">
                    
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">หัวข้อข่าว</label>
                        <input type="text" name="title" value="<?= old('title', $news['news_title']) ?>" placeholder="พิมพ์หัวข้อข่าวที่นี่..." class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-100 transition-all">
                        <?php if(isset(session('errors')['title'])): ?>
                            <p class="text-[10px] text-rose-500 font-black mt-2 uppercase tracking-wide"><?= session('errors')['title'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">หมวดหมู่ข่าว</label>
                            <select name="category" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer">
                                <option value="ข่าวประชาสัมพันธ์" <?= old('category', $news['news_category']) == 'ข่าวประชาสัมพันธ์' ? 'selected' : '' ?>>ข่าวประชาสัมพันธ์</option>
                                <option value="กิจกรรม" <?= old('category', $news['news_category']) == 'กิจกรรม' ? 'selected' : '' ?>>กิจกรรม / โครงการ</option>
                                <option value="ประกาศ" <?= old('category', $news['news_category']) == 'ประกาศ' ? 'selected' : '' ?>>ประกาศ / คำสั่ง</option>
                                <option value="สมัครงาน" <?= old('category', $news['news_category']) == 'สมัครงาน' ? 'selected' : '' ?>>ข่าวรับสมัครงาน</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">วันที่ลงข่าว </label>
                            <input type="datetime-local" name="created_at" value="<?= old('created_at', date('Y-m-d\TH:i', strtotime($news['news_created_at']))) ?>" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-100 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">สถานะการแสดงผล</label>
                            <select name="status" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer">
                                <option value="published" <?= old('status', $news['news_status']) == 'published' ? 'selected' : '' ?>>เผยแพร่ทันที</option>
                                <option value="draft" <?= old('status', $news['news_status']) == 'draft' ? 'selected' : '' ?>>เก็บเป็นฉบับร่าง</option>
                                <option value="hidden" <?= old('status', $news['news_status']) == 'hidden' ? 'selected' : '' ?>>ซ่อนจากหน้าหลัก</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">รูปหน้าปก</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <?php if($news['news_cover']): ?>
                                <div class="p-2 bg-slate-50 border border-slate-100 rounded-2xl">
                                    <img src="<?= base_url('uploads/news/covers/' . $news['news_cover']) ?>" class="w-full h-auto rounded-xl shadow-sm">
                                    <p class="text-[10px] text-slate-400 font-bold mt-2 text-center uppercase tracking-widest">รูปหน้าปกปัจจุบัน</p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="relative">
                                <input type="file" name="cover" id="cover" class=" hidden" onchange="previewImage(this)">
                                <label for="cover" class="w-full flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-8 hover:bg-slate-50 transition-colors cursor-pointer group">
                                    <div class="w-12 h-12 bg-blue-50 rounded-2xl text-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                        <i data-lucide="image" class="w-6 h-6"></i>
                                    </div>
                                    <span class="text-xs font-black uppercase tracking-widest text-slate-500 mb-1 leading-tight text-center">คลิกเพื่อเปลี่ยนรูปหน้าปก</span>
                                    <span class="text-[10px] text-slate-400 font-medium uppercase tracking-tight">JPG, PNG, WEBP</span>
                                </label>
                                <div id="image-preview" class="mt-4 hidden p-2 bg-slate-50 border border-slate-100 rounded-2xl">
                                    <img src="" id="preview-src" class="w-full h-auto rounded-xl shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">รูปภาพประกอบแกลเลอรี</label>
                        
                        <!-- Existing Images -->
                        <?php if(!empty($gallery)): ?>
                            <div class="grid grid-cols-4 md:grid-cols-6 gap-3 mb-6">
                                <?php foreach($gallery as $img): ?>
                                    <div class="relative aspect-square group rounded-xl overflow-hidden border border-slate-100 shadow-sm">
                                        <img src="<?= base_url('uploads/news/gallery/' . $img['gal_image']) ?>" class="w-full h-full object-cover">
                                        <button type="button" onclick="confirmDeleteImage('<?= base_url('staff/news/deleteImage/' . $img['gal_id']) ?>')" class="absolute inset-0 bg-rose-600/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                            <i data-lucide="trash-2" class="w-6 h-6"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="relative">
                            <input type="file" name="gallery[]" id="gallery" class="hidden" multiple onchange="previewGallery(this)">
                            <label for="gallery" class="w-full flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-6 hover:bg-slate-50 transition-colors cursor-pointer group">
                                <div class="w-10 h-10 bg-emerald-50 rounded-xl text-emerald-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i data-lucide="images" class="w-5 h-5"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">เพิ่มรูปภาพประกอบเพิ่ม</span>
                            </label>
                            <div id="gallery-preview" class="mt-4 grid grid-cols-4 gap-3"></div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">เนื้อหาข่าวละเอียด</label>
                        <textarea name="content" rows="12" placeholder="กรอกเนื้อหาข่าวสารที่นี่..." class="w-full px-8 py-6 bg-slate-50 border border-slate-100 rounded-[2rem] text-slate-800 font-medium focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all leading-relaxed"><?= old('content', $news['news_content']) ?></textarea>
                        <?php if(isset(session('errors')['content'])): ?>
                            <p class="text-[10px] text-rose-500 font-black mt-2 uppercase tracking-wide"><?= session('errors')['content'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="pt-8 border-t border-slate-100 flex justify-end gap-3">
                        <button type="submit" id="submit-btn" class="px-12 py-4 bg-blue-600 text-white rounded-2xl font-black text-lg flex items-center gap-3 hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 hover:-translate-y-1 disabled:opacity-70 disabled:cursor-not-allowed">
                            <i data-lucide="save" class="w-6 h-6"></i>
                            บันทึกการเปลี่ยนแปลง
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="lg:col-span-3 space-y-8" data-aos="fade-up" data-aos-delay="200">
            <div class="bg-amber-50 border border-amber-100 p-8 rounded-[2.5rem]">
                <h3 class="text-amber-900 font-black text-lg mb-4 flex items-center gap-3">
                    <i data-lucide="help-circle" class="w-6 h-6"></i> ข้อมูลระบบ
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-amber-700 font-black uppercase tracking-widest mb-1">วันที่สร้าง</p>
                        <p class="text-sm font-bold text-amber-900"><?= date('d F Y H:i', strtotime($news['news_created_at'])) ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-amber-700 font-black uppercase tracking-widest mb-1">อัปเดตล่าสุด</p>
                        <p class="text-sm font-bold text-amber-900"><?= date('d F Y H:i', strtotime($news['news_updated_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        // Form Loading State
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-6 h-6 animate-spin"></i> กำลังบันทึกข้อมูล...`;
            lucide.createIcons();
        });

        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewSrc = document.getElementById('preview-src');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewSrc.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewGallery(input) {
            const preview = document.getElementById('gallery-preview');
            preview.innerHTML = '';
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'aspect-square rounded-xl overflow-hidden border border-slate-100 shadow-sm';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        preview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        function confirmDeleteImage(url) {
            Swal.fire({
                title: 'ลบรูปภาพ?',
                text: "รูปภาพนี้จะถูกลบออกจากคลังภาพทันที",
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
