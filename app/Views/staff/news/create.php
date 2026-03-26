<?= $this->extend('staff/layout/admin') ?>

<?= $this->section('content') ?>
    <div class="mb-12 flex items-center gap-6" data-aos="fade-up">
        <a href="<?= base_url('staff/news') ?>" class="w-12 h-12 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-colors">
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </a>
        <div>
            <h2 class="text-3xl font-black text-slate-900 mb-2">เพิ่มข่าวสารใหม่</h2>
            <p class="text-slate-400 font-medium tracking-wide flex items-center gap-2 uppercase text-xs text-blue-600">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Create News Post
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-9" data-aos="fade-up" data-aos-delay="100">
            <form action="<?= base_url('staff/news/store') ?>" method="post" enctype="multipart/form-data">
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden p-10 space-y-8">
                    
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">หัวข้อข่าว</label>
                        <input type="text" name="title" value="<?= old('title') ?>" placeholder="พิมพ์หัวข้อข่าวที่นี่..." class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-100 transition-all">
                        <?php if(isset(session('errors')['title'])): ?>
                            <p class="text-[10px] text-rose-500 font-black mt-2 uppercase tracking-wide"><?= session('errors')['title'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">หมวดหมู่ข่าว</label>
                            <select name="category" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer">
                                <option value="ข่าวประชาสัมพันธ์" <?= old('category') == 'ข่าวประชาสัมพันธ์' ? 'selected' : '' ?>>ข่าวประชาสัมพันธ์</option>
                                <option value="กิจกรรม" <?= old('category') == 'กิจกรรม' ? 'selected' : '' ?>>กิจกรรม / โครงการ</option>
                                <option value="ประกาศ" <?= old('category') == 'ประกาศ' ? 'selected' : '' ?>>ประกาศ / คำสั่ง</option>
                                <option value="สมัครงาน" <?= old('category') == 'สมัครงาน' ? 'selected' : '' ?>>ข่าวรับสมัครงาน</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">วันที่ลงข่าว</label>
                            <input type="datetime-local" name="created_at" value="<?= old('created_at', date('Y-m-d\TH:i')) ?>" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-100 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">สถานะการแสดงผล</label>
                            <select name="status" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer">
                                <option value="published" <?= old('status') == 'published' ? 'selected' : '' ?>>เผยแพร่ทันที</option>
                                <option value="draft" <?= old('status') == 'draft' ? 'selected' : '' ?>>เก็บเป็นฉบับร่าง</option>
                                <option value="hidden" <?= old('status') == 'hidden' ? 'selected' : '' ?>>ซ่อนจากหน้าหลัก</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">รูปหน้าปก (แนะนำขนาด 1200x630px)</label>
                        <div class="relative">
                            <input type="file" name="cover" id="cover" class=" hidden" onchange="previewImage(this)">
                            <label for="cover" class="w-full flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-8 hover:bg-slate-50 transition-colors cursor-pointer group">
                                <div class="w-12 h-12 bg-blue-50 rounded-2xl text-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <i data-lucide="image" class="w-6 h-6"></i>
                                </div>
                                <span class="text-xs font-black uppercase tracking-widest text-slate-500 mb-1">คลิกเพื่อเลือกรูปหน้าปก</span>
                                <span class="text-[10px] text-slate-400 font-medium uppercase tracking-tight">JPG, PNG, WEBP (Max 2MB)</span>
                            </label>
                            <div id="image-preview" class="mt-4 hidden p-2 bg-slate-50 border border-slate-100 rounded-2xl">
                                <img src="" id="preview-src" class="w-full h-auto rounded-xl shadow-sm">
                            </div>
                        </div>
                        <?php if(isset(session('errors')['cover'])): ?>
                            <p class="text-[10px] text-rose-500 font-black mt-2 uppercase tracking-wide"><?= session('errors')['cover'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">รูปภาพประกอบเพิ่มเติม (Gallery)</label>
                        <div class="relative">
                            <input type="file" name="gallery[]" id="gallery" class="hidden" multiple onchange="previewGallery(this)">
                            <label for="gallery" class="w-full flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-6 hover:bg-slate-50 transition-colors cursor-pointer group">
                                <div class="w-10 h-10 bg-emerald-50 rounded-xl text-emerald-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i data-lucide="images" class="w-5 h-5"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">เลือกรูปภาพหลายรูปพร้อมกัน</span>
                                <span class="text-[9px] text-slate-400 font-medium tracking-tight">สามารถเลือกได้หลายไฟล์พร้อมกัน</span>
                            </label>
                            <div id="gallery-preview" class="mt-4 grid grid-cols-4 gap-3"></div>
                        </div>
                        <?php if(isset(session('errors')['gallery.*'])): ?>
                            <p class="text-[10px] text-rose-500 font-black mt-2 uppercase tracking-wide"><?= session('errors')['gallery.*'] ?></p>
                        <?php endif; ?>
                    </div>


                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">เนื้อหาข่าวละเอียด</label>
                        <textarea name="content" rows="12" placeholder="กรอกเนื้อหาข่าวสารที่นี่..." class="w-full px-8 py-6 bg-slate-50 border border-slate-100 rounded-[2rem] text-slate-800 font-medium focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all leading-relaxed"><?= old('content') ?></textarea>
                        <?php if(isset(session('errors')['content'])): ?>
                            <p class="text-[10px] text-rose-500 font-black mt-2 uppercase tracking-wide"><?= session('errors')['content'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="pt-8 border-t border-slate-100 flex justify-end gap-3">
                        <button type="reset" class="px-8 py-4 bg-slate-100 text-slate-400 rounded-2xl font-bold flex items-center gap-2 hover:bg-slate-200 transition-colors">
                            <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                            ยกเลิก
                        </button>
                        <button type="submit" id="submit-btn" class="px-12 py-4 bg-blue-600 text-white rounded-2xl font-black text-lg flex items-center gap-3 hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 hover:-translate-y-1 disabled:opacity-70 disabled:cursor-not-allowed">
                            <i data-lucide="send" class="w-6 h-6"></i>
                            ลงประกาศข่าว
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="lg:col-span-3 space-y-8" data-aos="fade-up" data-aos-delay="200">
            <div class="bg-blue-50 border border-blue-100 p-8 rounded-[2.5rem]">
                <h3 class="text-blue-900 font-black text-lg mb-4 flex items-center gap-3">
                    <i data-lucide="info" class="w-6 h-6"></i> ข้อแนะนำ
                </h3>
                <ul class="space-y-4">
                    <li class="flex gap-4 text-sm font-medium text-blue-700">
                        <span class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-[10px] font-black flex-shrink-0 shadow-sm border border-blue-100">1</span>
                        กรุณาตรวจสอบหัวข้อข่าวให้มีความกระชับและน่าสนใจ
                    </li>
                    <li class="flex gap-4 text-sm font-medium text-blue-700">
                        <span class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-[10px] font-black flex-shrink-0 shadow-sm border border-blue-100">2</span>
                        รูปหน้าปกข่าวมือความสำคัญมากต่อการดึงดูดผู้เข้าชม
                    </li>
                    <li class="flex gap-4 text-sm font-medium text-blue-700">
                        <span class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-[10px] font-black flex-shrink-0 shadow-sm border border-blue-100">3</span>
                        หากมีไฟล์แนบหรือรูปภาพเพิ่ม กรุณาระบุในเนื้อหาข่าว
                    </li>
                </ul>
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
    </script>
<?= $this->endSection() ?>
