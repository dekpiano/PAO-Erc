<?= $this->extend('staff/layout/main') ?>

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

    <!-- Facebook Quick Import Banner -->
    <div class="mb-8 p-6 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 rounded-[2rem] text-white shadow-xl shadow-blue-500/20" data-aos="fade-up">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center flex-shrink-0 text-white shadow-sm">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-base font-black">ดึงข้อมูลอัตโนมัติจาก Facebook Post</h4>
                    <p class="text-xs text-blue-100 font-medium">วางลิงก์โพสต์ Facebook เพื่อให้ระบบกรอกหัวข้อ เนื้อหา และรูปภาพลงในฟอร์มทันที</p>
                </div>
            </div>
            <div class="flex w-full md:w-auto items-center gap-2">
                <div class="relative flex-1 md:w-80">
                    <input type="url" id="fb-quick-url" placeholder="วางลิงก์โพสต์ Facebook ที่นี่..." class="w-full px-4 py-3 bg-white/15 border border-white/25 backdrop-blur-md rounded-xl text-xs font-bold text-white placeholder-blue-200 focus:outline-none focus:bg-white focus:text-slate-800 focus:placeholder-slate-400 transition-all">
                </div>
                <button type="button" id="btn-quick-fetch" onclick="quickFetchFacebook()" class="px-5 py-3 bg-white text-blue-600 rounded-xl font-black text-xs hover:bg-blue-50 transition-all shadow-md active:scale-95 flex items-center gap-2 flex-shrink-0">
                    <span id="btn-quick-fetch-text">ดึงข้อมูล</span>
                    <span id="btn-quick-fetch-spinner" class="hidden">
                        <svg class="animate-spin w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-9" data-aos="fade-up" data-aos-delay="100">
            <form id="news-form" action="<?= base_url('staff/news/store') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="fb_cover_url" id="fb_cover_url" value="">
                <div id="fb-hidden-gallery-container"></div>
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden p-10 space-y-8">
                    
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">หัวข้อข่าว</label>
                        <input type="text" name="title" id="news-title-input" value="<?= old('title') ?>" placeholder="พิมพ์หัวข้อข่าวที่นี่..." class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-100 transition-all">
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
                            <label for="cover" id="cover-dropzone" class="w-full flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-8 hover:bg-slate-50 transition-all cursor-pointer group">
                                <div class="w-12 h-12 bg-blue-50 rounded-2xl text-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <i data-lucide="image" class="w-6 h-6"></i>
                                </div>
                                <span class="text-xs font-black uppercase tracking-widest text-slate-500 mb-1">ลากไฟล์รูปภาพมาวาง หรือคลิกเพื่อเลือก</span>
                                <span class="text-[10px] text-slate-400 font-medium uppercase tracking-tight">JPG, PNG, WEBP (Max 2MB)</span>
                            </label>
                            <div id="image-preview" class="mt-4 hidden p-2 bg-slate-50 border border-slate-100 rounded-2xl">
                                <img src="" id="preview-src" referrerpolicy="no-referrer" class="w-full h-auto rounded-xl shadow-sm">
                            </div>
                        </div>
                        <?php if(isset(session('errors')['cover'])): ?>
                            <p class="text-[10px] text-rose-500 font-black mt-2 uppercase tracking-wide"><?= session('errors')['cover'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400">รูปภาพประกอบเพิ่มเติม (Gallery)</label>
                            <div id="fb-create-gallery-toolbar" class="hidden flex items-center gap-1.5 text-xs">
                                <span class="text-blue-600 font-bold text-[11px] mr-1" id="fb-create-photo-count"></span>
                                <button type="button" onclick="selectTopNCreateFbGallery(10)" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-[11px] font-bold transition-all active:scale-95 shadow-xs">
                                    <i data-lucide="check-check" class="w-3.5 h-3.5 inline mr-0.5"></i>เลือก 10 รูปแรก
                                </button>
                                <button type="button" onclick="setAllCreateFbGallery(true)" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[11px] font-bold transition-all active:scale-95">
                                    เลือกทั้งหมด
                                </button>
                                <button type="button" onclick="setAllCreateFbGallery(false)" class="px-2 py-1 bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 rounded-lg text-[11px] font-bold transition-all active:scale-95">
                                    ยกเลิกทั้งหมด
                                </button>
                            </div>
                        </div>
                        <div class="relative">
                            <input type="file" name="gallery[]" id="gallery" class="hidden" multiple onchange="previewGallery(this)">
                            <label for="gallery" id="gallery-dropzone" class="w-full flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-6 hover:bg-slate-50 transition-all cursor-pointer group">
                                <div class="w-10 h-10 bg-emerald-50 rounded-xl text-emerald-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i data-lucide="images" class="w-5 h-5"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">ลากหลายไฟล์มาวางที่นี่ หรือคลิกเพื่อเลือก</span>
                                <span class="text-[9px] text-slate-400 font-medium tracking-tight">สามารถลากไฟล์รูปภาพมาวางได้พร้อมกันหลายไฟล์</span>
                            </label>
                            <div id="gallery-preview" class="mt-4 grid grid-cols-4 sm:grid-cols-6 gap-3"></div>
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
        // Form Submission with AJAX & Chunking
        document.getElementById('news-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const btn = document.getElementById('submit-btn');
            const originalBtnContent = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-6 h-6 animate-spin inline mr-2"></i> กำลังเตรียมข้อมูล...`;
            lucide.createIcons();

            try {
                // 1. Handle Cover Chunked Upload
                let tempCover = null;
                const coverInput = document.getElementById('cover');
                if (coverInput.files && coverInput.files[0]) {
                    const coverFile = coverInput.files[0];
                    btn.innerHTML = `<i data-lucide="loader-2" class="w-6 h-6 animate-spin inline mr-2"></i> กำลังอัปโหลดหน้าปก...`;
                    tempCover = await uploadFileInChunks(coverFile);
                }

                // 2. Handle Gallery Chunked Uploads
                let tempGallery = [];
                if (selectedGalleryFiles.length > 0) {
                    for (let i = 0; i < selectedGalleryFiles.length; i++) {
                        const file = selectedGalleryFiles[i];
                        btn.innerHTML = `<i data-lucide="loader-2" class="w-6 h-6 animate-spin inline mr-2"></i> กำลังอัปโหลดรูปที่ ${i+1}/${selectedGalleryFiles.length}...`;
                        const tempName = await uploadFileInChunks(file);
                        tempGallery.push(tempName);
                    }
                }

                // 3. Final Submission
                btn.innerHTML = `<i data-lucide="loader-2" class="w-6 h-6 animate-spin inline mr-2"></i> กำลังบันทึกข้อมูลข่าว...`;
                const formData = new FormData(form);
                
                // Append temp names and remove raw files to keep request small
                if (tempCover) {
                    formData.delete('cover');
                    formData.append('temp_cover', tempCover);
                }
                if (tempGallery.length > 0) {
                    formData.delete('gallery[]');
                    tempGallery.forEach(name => {
                        formData.append('temp_gallery[]', name);
                    });
                }

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Server responded with non-JSON format:', text);
                    throw new Error('NON_JSON');
                }

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
                    throw new Error(data.message || 'Validation failed');
                }

            } catch (error) {
                console.error('Error:', error);
                btn.disabled = false;
                btn.innerHTML = originalBtnContent;
                lucide.createIcons();
                
                let errorTitle = 'ไม่สามารถบันทึกข้อมูลได้';
                let errorMsg = error.message;

                if (error.message === 'NON_JSON') {
                    errorMsg = 'เซิร์ฟเวอร์ตอบสนองผิดพลาด กรุณาตรวจสอบ Console';
                }

                Swal.fire({
                    icon: 'error',
                    title: errorTitle,
                    text: errorMsg,
                    customClass: { popup: 'rounded-[1.5rem]' }
                });
            }
        });

        async function uploadFileInChunks(file) {
            const chunkSize = 1024 * 512; // 512KB per chunk (Further decreased to fix persistent 413)
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
                if (result.status === 'error') {
                    throw new Error(result.message);
                }
                if (result.status === 'completed') {
                    return result.temp_file;
                }
            }
        }

        // ==========================================
        // DRAG AND DROP HANDLERS
        // ==========================================
        function initDropzone(id, inputId, previewFn) {
            const dropzone = document.getElementById(id);
            const input = document.getElementById(inputId);

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.add('bg-blue-50/50', 'border-blue-400', 'scale-[1.01]');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.remove('bg-blue-50/50', 'border-blue-400', 'scale-[1.01]');
                }, false);
            });

            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (inputId === 'gallery') {
                    // Accumulate for gallery
                    Array.from(files).forEach(file => {
                        selectedGalleryFiles.push(file);
                    });
                    renderGalleryPreview();
                } else {
                    // Normal behavior for cover
                    input.files = files;
                    previewFn(input);
                }
            }, false);
        }

        initDropzone('cover-dropzone', 'cover', previewImage);
        initDropzone('gallery-dropzone', 'gallery', previewGallery);

        let selectedGalleryFiles = [];

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
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    selectedGalleryFiles.push(file);
                });
                renderGalleryPreview();
                input.value = ''; // Clear input to allow re-selecting same file if needed
            }
        }

        function renderGalleryPreview() {
            const preview = document.getElementById('gallery-preview');
            preview.innerHTML = '';
            
            selectedGalleryFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square rounded-xl overflow-hidden border border-slate-100 shadow-sm group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <button type="button" onclick="removeGalleryFile(${index})" class="absolute top-1 right-1 w-6 h-6 bg-rose-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    `;
                    preview.appendChild(div);
                    lucide.createIcons();
                }
                reader.readAsDataURL(file);
            });
        }

        function removeGalleryFile(index) {
            selectedGalleryFiles.splice(index, 1);
            renderGalleryPreview();
        }

        async function quickFetchFacebook() {
            const urlInput = document.getElementById('fb-quick-url');
            const url = urlInput.value.trim();
            if (!url) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาระบุ URL',
                    text: 'โปรดวางลิงก์โพสต์ Facebook ก่อนกดดึงข้อมูล',
                    customClass: { popup: 'rounded-[2rem]' }
                });
                return;
            }

            const btn = document.getElementById('btn-quick-fetch');
            const btnText = document.getElementById('btn-quick-fetch-text');
            const spinner = document.getElementById('btn-quick-fetch-spinner');

            btn.disabled = true;
            btnText.textContent = 'กำลังดึงข้อมูล...';
            spinner.classList.remove('hidden');

            try {
                const formData = new FormData();
                formData.append('url', url);

                const response = await fetch('<?= base_url('staff/news/fetch-facebook') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const result = await response.json();

                if (result.status === 'success' && result.data) {
                    const data = result.data;
                    
                    // Fill title
                    if (data.title) {
                        const titleEl = document.getElementById('news-title-input');
                        if (titleEl) titleEl.value = data.title;
                    }

                    // Fill content (with links)
                    if (data.content) {
                        const contentEl = document.querySelector('textarea[name="content"]');
                        if (contentEl) contentEl.value = data.content;
                    }

                    // Fill Date
                    if (data.created_at) {
                        const dateEl = document.querySelector('input[name="created_at"]');
                        if (dateEl) dateEl.value = data.created_at;
                    }

                    // Collect all fetched images
                    createFbImages = [];
                    if (data.cover_local || data.cover_url) {
                        createFbImages.push({
                            remote: data.cover_url || '',
                            local_file: data.cover_local || '',
                            local_url: data.cover_local_url || data.cover_url || '',
                            inGallery: false
                        });
                    }

                    if (data.gallery_items && data.gallery_items.length > 0) {
                        data.gallery_items.forEach((item, gIdx) => {
                            createFbImages.push({
                                remote: item.remote || '',
                                local_file: item.local_file || '',
                                local_url: item.local_url || item.remote || '',
                                inGallery: (gIdx < 10)
                            });
                        });
                    } else if (data.gallery_urls && data.gallery_urls.length > 0) {
                        data.gallery_urls.forEach((u, gIdx) => {
                            createFbImages.push({
                                remote: u,
                                local_file: '',
                                local_url: u,
                                inGallery: (gIdx < 10)
                            });
                        });
                    }

                    selectedCreateCoverIdx = 0;
                    renderCreatePhotoGrid();

                    Swal.fire({
                        icon: 'success',
                        title: 'ดึงข้อมูลสำเร็จ!',
                        text: 'นำเข้าข้อมูลและรูปภาพจาก Facebook เรียบร้อยแล้ว (ระบบเลือกไว้ 10 รูปแรก สามารถเลือกเพิ่มหรือเปลี่ยนหน้าปกได้)',
                        timer: 2500,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ดึงข้อมูลไม่สำเร็จ',
                        text: result.message || 'ไม่สามารถดึงข้อมูลจากลิงก์นี้ได้ กรุณาตรวจสอบลิงก์อีกครั้ง',
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: err.message,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            } finally {
                btn.disabled = false;
                btnText.textContent = 'ดึงข้อมูล';
                spinner.classList.add('hidden');
            }
        }

        let createFbImages = [];
        let selectedCreateCoverIdx = 0;

        function renderCreatePhotoGrid() {
            const toolbar = document.getElementById('fb-create-gallery-toolbar');
            if (createFbImages.length === 0) {
                if (toolbar) toolbar.classList.add('hidden');
                return;
            }

            if (toolbar) toolbar.classList.remove('hidden');

            const activeCover = createFbImages[selectedCreateCoverIdx] || createFbImages[0];
            const displayCover = activeCover.local_url || activeCover.remote;

            // Update main cover preview box
            const preview = document.getElementById('image-preview');
            const previewSrc = document.getElementById('preview-src');
            const fbCoverInput = document.getElementById('fb_cover_url');
            if (preview && previewSrc) {
                previewSrc.src = displayCover;
                preview.classList.remove('hidden');
            }
            if (fbCoverInput) {
                fbCoverInput.value = activeCover.local_file ? '' : (activeCover.remote || '');
            }

            // Update temp_cover input
            let tempCoverInput = document.getElementById('fb_temp_cover');
            if (!tempCoverInput) {
                tempCoverInput = document.createElement('input');
                tempCoverInput.type = 'hidden';
                tempCoverInput.name = 'temp_cover';
                tempCoverInput.id = 'fb_temp_cover';
                document.getElementById('news-form').appendChild(tempCoverInput);
            }
            tempCoverInput.value = activeCover.local_file || '';

            // Render Gallery grid with cover indicator & swap buttons
            let galleryContainer = document.getElementById('fb-hidden-gallery-container');
            if (!galleryContainer) {
                galleryContainer = document.createElement('div');
                galleryContainer.id = 'fb-hidden-gallery-container';
                document.getElementById('news-form').appendChild(galleryContainer);
            }
            const galleryPreview = document.getElementById('gallery-preview');
            if (galleryPreview) {
                galleryContainer.innerHTML = '';
                galleryPreview.innerHTML = '';

                createFbImages.forEach((item, idx) => {
                    const isCover = (idx === selectedCreateCoverIdx);

                    // Add hidden gallery inputs for selected non-cover images
                    if (!isCover && item.inGallery) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        if (item.local_file) {
                            hiddenInput.name = 'temp_gallery[]';
                            hiddenInput.value = item.local_file;
                        } else {
                            hiddenInput.name = 'fb_gallery_urls[]';
                            hiddenInput.value = item.remote;
                        }
                        hiddenInput.id = `fb-gal-input-${idx}`;
                        galleryContainer.appendChild(hiddenInput);
                    }

                    // Visual card
                    const div = document.createElement('div');
                    div.id = `fb-gal-preview-${idx}`;
                    div.className = `relative aspect-square rounded-2xl overflow-hidden border-2 transition-all cursor-pointer group ${
                        isCover ? 'border-blue-600 ring-4 ring-blue-100 shadow-md scale-95' : (item.inGallery ? 'border-blue-400 bg-blue-50/20' : 'border-slate-200 opacity-60 hover:opacity-100 hover:border-slate-400')
                    }`;
                    div.onclick = () => setCreateCoverImage(idx);

                    div.innerHTML = `
                        <img src="${item.local_url || item.remote}" referrerpolicy="no-referrer" class="w-full h-full object-cover">
                        
                        ${isCover ? `
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-blue-900/90 to-transparent p-1.5 text-center pointer-events-none">
                                <span class="text-[10px] font-black text-white flex items-center justify-center gap-1">
                                    <i data-lucide="star" class="w-3 h-3 fill-current"></i> รูปหน้าปก
                                </span>
                            </div>
                        ` : `
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-1 text-center">
                                <span class="px-2 py-1 bg-white text-blue-600 rounded-lg text-[9px] font-black shadow-md flex items-center gap-1">
                                    <i data-lucide="star" class="w-3 h-3"></i> ตั้งเป็นหน้าปก
                                </span>
                            </div>
                        `}

                        ${!isCover ? `
                            <div class="absolute top-1.5 right-1.5 z-10" onclick="event.stopPropagation()">
                                <input type="checkbox" ${item.inGallery ? 'checked' : ''} onchange="toggleCreateGalleryInclusion(${idx}, this.checked)" title="เลือกรูปนี้ลงแกลเลอรี" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500 shadow-sm cursor-pointer accent-blue-600">
                            </div>
                        ` : ''}
                    `;
                    galleryPreview.appendChild(div);
                });
                updateCreateGalleryCountDisplay();
                if (window.lucide) lucide.createIcons();
            }
        }

        function setCreateCoverImage(index) {
            selectedCreateCoverIdx = index;
            renderCreatePhotoGrid();
        }

        function toggleCreateGalleryInclusion(index, checked) {
            if (createFbImages[index]) {
                createFbImages[index].inGallery = checked;
                renderCreatePhotoGrid();
            }
        }

        function updateCreateGalleryCountDisplay() {
            const countEl = document.getElementById('fb-create-photo-count');
            if (countEl) {
                const checkedCount = createFbImages.filter((img, idx) => idx !== selectedCreateCoverIdx && img.inGallery).length;
                countEl.textContent = `(ทั้งหมด ${createFbImages.length} รูป / เลือกแกลเลอรี ${checkedCount} รูป)`;
            }
        }

        function selectTopNCreateFbGallery(n = 10) {
            let count = 0;
            createFbImages.forEach((img, idx) => {
                if (idx === selectedCreateCoverIdx) {
                    img.inGallery = false;
                } else if (count < n) {
                    img.inGallery = true;
                    count++;
                } else {
                    img.inGallery = false;
                }
            });
            renderCreatePhotoGrid();
        }

        function setAllCreateFbGallery(checked) {
            createFbImages.forEach((img, idx) => {
                if (idx !== selectedCreateCoverIdx) {
                    img.inGallery = checked;
                }
            });
            renderCreatePhotoGrid();
        }
    </script>
<?= $this->endSection() ?>
