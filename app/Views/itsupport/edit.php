<?= $this->extend('itsupport/layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="mb-6 sm:mb-10 px-1 sm:px-0">
    <a href="<?= base_url('itsupport') ?>" class="text-xs font-bold text-slate-500 hover:text-blue-600 flex items-center gap-1 mb-2 sm:mb-3 transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> ย้อนกลับไปประวัติงาน
    </a>
    <h2 class="text-xl sm:text-3xl font-extrabold text-slate-800 tracking-tight tech-glow">แก้ไขประวัติงานบริการ <?= $log['its_ticket_code'] ?></h2>
    <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">แก้ไขรายละเอียดบันทึกผลงานการซ่อมบำรุงหรือการให้บริการไอที</p>
</div>

<!-- Form Container -->
<div class="glass-card p-4 sm:p-8 rounded-2xl sm:rounded-[2rem] max-w-4xl w-full mx-auto bg-white">
    <form id="edit-form" method="POST" action="<?= base_url('itsupport/update/' . $log['its_id']) ?>" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>

        <!-- Basic Header Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Ticket Code -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest pl-2">รหัสใบงานบริการ (อัตโนมัติ)</label>
                <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-blue-600 font-mono font-bold">
                    <?= $log['its_ticket_code'] ?>
                </div>
            </div>

            <!-- Recorder -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest pl-2">เจ้าหน้าที่ผู้ลงประวัติ</label>
                <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 font-bold">
                    👤 <?= esc($log['its_recorded_by']) ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Date/Time Picker -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-550 uppercase tracking-widest pl-2">วันเวลาที่ปฏิบัติงานจริง <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i data-lucide="calendar" class="w-4 h-4"></i></span>
                    <input type="text" name="its_date" required value="<?= $log['its_date'] ?>" placeholder="เลือกวันเวลา..." class="datetimepicker-be w-full pl-10 pr-4 py-3 bg-white border border-slate-200 focus:border-blue-500 rounded-2xl text-base md:text-sm text-slate-800 outline-none transition-colors">
                </div>
                <?php if(session('errors.its_date')): ?>
                    <p class="text-rose-500 text-xs pl-2 font-bold"><?= session('errors.its_date') ?></p>
                <?php endif; ?>
            </div>

            <!-- Category -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-550 uppercase tracking-widest pl-2">หมวดหมู่ประเภทงานบริการ <span class="text-rose-500">*</span></label>
                <select name="its_category" required class="w-full px-4 py-3 bg-white border border-slate-200 focus:border-blue-500 rounded-2xl text-base md:text-sm text-slate-700 outline-none transition-colors">
                    <option value="" disabled>-- เลือกประเภทงานบริการ --</option>
                    <?php
                        $categories = [
                            "💻 พัฒนาและบำรุงรักษาระบบสารสนเทศ",
                            "🛠️ IT Support & Service",
                            "🎤 งานโสตทัศนศึกษา",
                            "📸 ผลิตสื่อและประชาสัมพันธ์", 
                            "📊 งานสารสนเทศโรงเรียนและสำนักฯ",
                            "🤝 สนับสนุนงานฝ่าย/อาคาร",
                            "👥 งานประชุม", 
                            "📚 การอบรม/พัฒนาตนเอง",
                            "🏛️ งานอื่นๆ ตามคำสั่ง"
                        ];
                    ?>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat ?>" <?= (old('its_category') ?: $log['its_category']) == $cat ? 'selected' : '' ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if(session('errors.its_category')): ?>
                    <p class="text-rose-500 text-xs pl-2 font-bold"><?= session('errors.its_category') ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Location -->
        <div class="space-y-1.5">
            <label class="text-[10px] font-bold text-slate-550 uppercase tracking-widest pl-2">สถานที่ปฏิบัติงาน</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i data-lucide="map-pin" class="w-4 h-4"></i></span>
                <input type="text" list="loc-datalist" name="its_location" value="<?= esc(old('its_location') ?: $log['its_location']) ?>" placeholder="ระบุตึก ห้อง หรือกองฝ่ายงาน (เว้นว่างได้)" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 focus:border-blue-500 rounded-2xl text-base md:text-sm text-slate-800 placeholder-slate-400 outline-none transition-colors">
                <datalist id="loc-datalist">
                    <?php foreach($locations as $loc): ?>
                        <option value="<?= esc($loc) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>
        </div>

        <!-- Task Description -->
        <div class="space-y-1.5">
            <label class="text-[10px] font-bold text-slate-550 uppercase tracking-widest pl-2">รายละเอียดสิ่งที่ปฏิบัติงาน <span class="text-rose-500">*</span></label>
            <textarea name="its_task" required rows="5" placeholder="พิมพ์อธิบายปัญหา อาการชำรุด หรือกระบวนการซ่อมแซมแก้ไขให้ละเอียด..." class="w-full px-4 py-3 bg-white border border-slate-200 focus:border-blue-500 rounded-2xl text-base md:text-sm text-slate-800 placeholder-slate-400 outline-none transition-colors resize-none"><?= esc(old('its_task') ?: $log['its_task']) ?></textarea>
            <?php if(session('errors.its_task')): ?>
                <p class="text-rose-500 text-xs pl-2 font-bold"><?= session('errors.its_task') ?></p>
            <?php endif; ?>
        </div>

        <!-- Existing Images -->
        <?php 
            $images = !empty($log['its_images']) ? json_decode($log['its_images'], true) : [];
        ?>
        <?php if(!empty($images)): ?>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest pl-2">รูปภาพประกอบเดิมในระบบ (คลิกเพื่อเลือกรูปที่จะลบออก)</label>
                <div class="grid grid-cols-3 sm:grid-cols-5 gap-4">
                    <?php foreach($images as $img): ?>
                        <div id="existing-img-container-<?= md5($img) ?>" class="relative rounded-2xl overflow-hidden aspect-video border border-slate-200 bg-slate-50 shadow-sm transition-all duration-300 group">
                            <img loading="lazy" src="<?= base_url('uploads/it_support/' . $img) ?>" class="w-full h-full object-cover">
                            
                            <!-- Overlay and Trash Button -->
                            <button type="button" onclick="toggleDeleteExisting('<?= esc($img) ?>', '<?= md5($img) ?>')" class="absolute top-1.5 right-1.5 w-6 h-6 bg-rose-600 hover:bg-rose-700 text-white rounded-full flex items-center justify-center shadow-md transition-all z-10" title="เลือกเพื่อลบรูปนี้">
                                <span class="text-[10px] font-bold">✕</span>
                            </button>
                            
                            <!-- Red indicator overlay -->
                            <div id="existing-overlay-<?= md5($img) ?>" class="hidden absolute inset-0 bg-rose-900/60 backdrop-blur-[1px] flex items-center justify-center transition-all animate-[fadeIn_0.2s_ease]">
                                <span class="px-2 py-1 bg-white text-rose-600 text-[9px] font-black uppercase rounded-md tracking-wider shadow-md">จะถูกลบออก</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Multi-Images Upload with Thumbnails Preview -->
        <div class="space-y-2">
            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest pl-2 block">แนบภาพประกอบผลงานเพิ่มเติม (เลือกเพื่ออัปโหลดใหม่เข้ามาเสริม)</label>
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-200 border-dashed rounded-3xl cursor-pointer bg-slate-50 hover:bg-slate-100/50 hover:border-slate-350 transition-all duration-300">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i data-lucide="cloud-upload" class="w-10 h-10 text-slate-400 mb-2"></i>
                        <p class="mb-2 text-sm text-slate-500"><span class="font-bold text-blue-600">คลิกเพื่อเพิ่มรูปภาพใหม่</span> รวมกับรูปภาพเดิม</p>
                        <p class="text-xs text-slate-400">รองรับนามสกุล JPG, JPEG, PNG (ไม่เกิน 10 รูป)</p>
                    </div>
                    <input id="dropzone-file" type="file" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(event)">
                </label>
            </div>
            
            <!-- Progress Bar Container -->
            <div id="edit-progress-container" class="hidden mt-3 space-y-1.5">
                <div class="flex justify-between text-[10px] font-extrabold text-slate-500">
                    <span id="edit-progress-label" class="text-blue-600">กำลังอัปโหลด...</span>
                    <span id="edit-progress-percent">0%</span>
                </div>
                <div class="w-full bg-slate-150 dark:bg-slate-800 rounded-full h-2 overflow-hidden shadow-inner">
                    <div id="edit-progress-bar" class="bg-gradient-to-r from-blue-500 to-blue-600 h-full rounded-full transition-all duration-300 w-0"></div>
                </div>
            </div>

            <!-- Thumbnail list container -->
            <div id="image-preview-container" class="grid grid-cols-3 sm:grid-cols-5 gap-4 pt-4"></div>
        </div>

        <!-- Form Submit Button Group -->
        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
            <a href="<?= base_url('itsupport') ?>" class="px-6 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100 font-bold text-sm transition-colors">
                ยกเลิก
            </a>
            <button type="submit" class="px-8 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold text-sm shadow-lg shadow-blue-500/20 hover:scale-105 transition-all duration-200">
                บันทึกการแก้ไขประวัติ
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // --- File Queue Manager for Edit Form ---
    let editFilesQueue = [];
    let deletedExistingImages = [];

    function toggleDeleteExisting(filename, md5Hash) {
        const idx = deletedExistingImages.indexOf(filename);
        const container = document.getElementById(`existing-img-container-${md5Hash}`);
        const overlay = document.getElementById(`existing-overlay-${md5Hash}`);
        
        if (idx === -1) {
            deletedExistingImages.push(filename);
            if (overlay) overlay.classList.remove('hidden');
            if (container) {
                container.classList.add('border-rose-500', 'opacity-70');
            }
        } else {
            deletedExistingImages.splice(idx, 1);
            if (overlay) overlay.classList.add('hidden');
            if (container) {
                container.classList.remove('border-rose-500', 'opacity-70');
            }
        }
    }

    function previewImages(event) {
        handleNewEditFiles(event.target.files);
        event.target.value = '';
    }

    function handleNewEditFiles(files) {
        if (!files) return;
        const imageFiles = Array.from(files).filter(file => file.type.startsWith('image/'));
        if (imageFiles.length > 0) {
            imageFiles.forEach(file => {
                const isDuplicate = editFilesQueue.some(q => q.name === file.name && q.size === file.size);
                if (!isDuplicate) {
                    editFilesQueue.push(file);
                }
            });
            renderEditPreviews();
        }
    }

    function renderEditPreviews() {
        const previewContainer = document.getElementById('image-preview-container');
        previewContainer.innerHTML = '';
        editFilesQueue.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative rounded-2xl overflow-hidden aspect-video border border-slate-200 bg-slate-50 shadow-sm animate-[fadeIn_0.3s_ease] group';
                div.innerHTML = `
                    <img loading="lazy" src="${e.target.result}" class="w-full h-full object-cover">
                    <button type="button" onclick="removeEditFile(${index})" class="absolute top-1 right-1 w-5 h-5 bg-rose-600 hover:bg-rose-700 text-white rounded-full flex items-center justify-center text-[10px] font-black shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200" title="ลบรูปภาพนี้">✕</button>
                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="text-[9px] font-black text-white bg-slate-900/70 px-2 py-1 rounded-md uppercase tracking-wider">${(file.size / 1024).toFixed(0)} KB</span>
                    </div>
                `;
                previewContainer.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    }

    function removeEditFile(index) {
        editFilesQueue.splice(index, 1);
        renderEditPreviews();
    }

    // --- Client-side image compression ---
    function compressImageClientSide(file) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function(event) {
                const img = new Image();
                img.src = event.target.result;
                img.onload = function() {
                    const maxW = 1200;
                    const maxH = 1200;
                    let width = img.width;
                    let height = img.height;

                    if (width > maxW || height > maxH) {
                        if (width > height) {
                            height = Math.round((height * maxW) / width);
                            width = maxW;
                        } else {
                            width = Math.round((width * maxH) / height);
                            height = maxH;
                        }
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob((blob) => {
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        resolve(compressedFile);
                    }, 'image/jpeg', 0.85);
                };
            };
        });
    }

    // --- Helper: get cookie value by name ---
    function getCookie(name) {
        const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
        return match ? decodeURIComponent(match[1]) : null;
    }

    // --- Chunked Upload Helper ---
    function uploadFileInChunks(file, progressCallback) {
        return new Promise((resolve, reject) => {
            const chunkSize = 256 * 1024; // 256KB Chunks
            const totalChunks = Math.ceil(file.size / chunkSize);
            const fileId = Date.now().toString() + Math.random().toString(36).substring(2, 8);
            let chunkIndex = 0;

            function uploadNextChunk() {
                const start = chunkIndex * chunkSize;
                const end = Math.min(start + chunkSize, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('file_id', fileId);
                formData.append('chunk_index', chunkIndex);
                formData.append('total_chunks', totalChunks);
                formData.append('filename', file.name);
                formData.append('chunk', chunk);
                
                const csrfInput = document.querySelector('input[name="csrf_test_name"]');
                if (csrfInput) {
                    formData.append('csrf_test_name', csrfInput.value);
                }

                fetch('<?= base_url("itsupport/upload_chunk") ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('การอัปโหลดไฟล์ล้มเหลว');
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        progressCallback(100);
                        resolve(data.filename);
                    } else if (data.status === 'uploading') {
                        const percent = Math.round(((chunkIndex + 1) / totalChunks) * 100);
                        progressCallback(percent);
                        chunkIndex++;
                        uploadNextChunk();
                    } else {
                        reject(new Error(data.message || 'Unknown upload error'));
                    }
                })
                .catch(err => reject(err));
            }

            uploadNextChunk();
        });
    }

    // --- Edit Form Submit: compress + fetch ---
    document.addEventListener('DOMContentLoaded', function() {
        const editForm = document.getElementById('edit-form');
        if (editForm) {
            editForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const submitBtn = editForm.querySelector('button[type="submit"]');
                const originalBtnHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="edit-upload-status">กำลังบันทึก...</span>
                    </span>
                `;

                // Progress Bar elements
                const progressContainer = document.getElementById('edit-progress-container');
                const progressBar = document.getElementById('edit-progress-bar');
                const progressPercent = document.getElementById('edit-progress-percent');
                const progressLabel = document.getElementById('edit-progress-label');

                try {
                    // 1. Compress images
                    const compressedFiles = [];
                    if (editFilesQueue.length > 0) {
                        progressContainer.classList.remove('hidden');
                        progressBar.style.width = '0%';
                        progressPercent.innerText = '0%';

                        for(let i=0; i<editFilesQueue.length; i++) {
                            progressLabel.innerText = `กำลังลดขนาดรูปภาพที่ ${i+1}/${editFilesQueue.length}...`;
                            const compressed = await compressImageClientSide(editFilesQueue[i]);
                            compressedFiles.push(compressed);

                            const compPercent = Math.round(((i + 1) / editFilesQueue.length) * 20);
                            progressBar.style.width = `${compPercent}%`;
                            progressPercent.innerText = `${compPercent}%`;
                        }
                    }

                    // 2. Upload chunked files (only if there are files selected)
                    const finalFilenames = [];
                    for(let i=0; i<compressedFiles.length; i++) {
                        const file = compressedFiles[i];
                        const filename = await uploadFileInChunks(file, (percent) => {
                            const basePercent = 20;
                            const range = 80;
                            const currentFileContribution = (percent / 100) * (range / compressedFiles.length);
                            const overallPercent = Math.round(basePercent + (i * (range / compressedFiles.length)) + currentFileContribution);
                            
                            progressBar.style.width = `${overallPercent}%`;
                            progressPercent.innerText = `${overallPercent}%`;
                            progressLabel.innerText = `กำลังอัปโหลดรูปที่ ${i+1}/${compressedFiles.length} (${percent}%)...`;
                        });
                        finalFilenames.push(filename);
                    }

                    if (editFilesQueue.length > 0) {
                        progressBar.style.width = '100%';
                        progressPercent.innerText = '100%';
                        progressLabel.innerText = 'อัปโหลดรูปภาพใหม่เรียบร้อยแล้ว!';
                    }

                    const formData = new FormData();
                    const csrfInput = editForm.querySelector('input[name="csrf_test_name"]');
                    if (csrfInput) {
                        formData.append('csrf_test_name', csrfInput.value);
                    }

                    const formElements = editForm.elements;
                    for (let i = 0; i < formElements.length; i++) {
                        const el = formElements[i];
                        if (el.name && el.name !== 'csrf_test_name' && el.type !== 'file' && el.type !== 'submit') {
                            formData.append(el.name, el.value);
                        }
                    }

                    // ส่งชื่อไฟล์ใหม่
                    if (finalFilenames.length > 0) {
                        formData.append('uploaded_images', JSON.stringify(finalFilenames));
                    }

                    // ส่งชื่อไฟล์เดิมที่ผู้ใช้เลือกให้ลบออก
                    if (deletedExistingImages.length > 0) {
                        formData.append('deleted_existing_images', JSON.stringify(deletedExistingImages));
                    }

                    editForm.querySelector('#edit-upload-status').innerText = `กำลังบันทึกข้อมูล...`;

                    const headers = {
                        'X-Requested-With': 'XMLHttpRequest'
                    };
                    const csrfCookie = getCookie('csrf_cookie_name');
                    if (csrfCookie) {
                        headers['X-CSRF-TOKEN'] = csrfCookie;
                    } else if (csrfInput) {
                        headers['X-CSRF-TOKEN'] = csrfInput.value;
                    }

                    fetch(editForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: headers,
                        credentials: 'same-origin'
                    }).then(async response => {
                        const status = response.status;
                        const responseText = await response.text();

                        const newCsrfCookie = getCookie('csrf_cookie_name');
                        if (newCsrfCookie && csrfInput) {
                            csrfInput.value = newCsrfCookie;
                        }

                        if (response.ok || response.redirected) {
                            try {
                                const json = JSON.parse(responseText);
                                if (json.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'บันทึกสำเร็จ!',
                                        text: json.message || 'ปรับปรุงประวัติการซ่อมบำรุงเรียบร้อยแล้ว',
                                        background: '#ffffff',
                                        color: '#1e293b',
                                        timer: 2000,
                                        showConfirmButton: false,
                                        customClass: { popup: 'glass-card rounded-[2rem]' }
                                    }).then(() => {
                                        window.location.href = '<?= base_url('itsupport') ?>';
                                    });
                                    return;
                                }
                            } catch(e) {
                            }
                            window.location.href = '<?= base_url('itsupport') ?>';
                            return;
                        }

                        // Hide progress bar on failure
                        progressContainer.classList.add('hidden');

                        let errorTitle = 'เกิดข้อผิดพลาดในการบันทึก';
                        let errorDetail = 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง';

                        if (status === 403 || (responseText.includes('csrf') || responseText.includes('Security'))) {
                            errorTitle = 'Session หมดอายุ (CSRF)';
                            errorDetail = 'Token ความปลอดภัยไม่ตรง กรุณารีเฟรชหน้า (F5) แล้วลองใหม่อีกครั้ง';
                            try {
                                const refreshResp = await fetch(window.location.href, { credentials: 'same-origin' });
                                const html = await refreshResp.text();
                                const match = html.match(/name="csrf_test_name"\s+value="([^"]+)"/);
                                if (match && csrfInput) {
                                    csrfInput.value = match[1];
                                    errorDetail += '\n\n✅ Token ถูกรีเฟรชอัตโนมัติแล้ว ลองกดบันทึกอีกครั้งได้เลย!';
                                }
                            } catch(refreshErr) {
                                errorDetail += '\n\nกรุณากด F5 รีเฟรชหน้าด้วยตนเอง';
                            }
                        } else if (status === 404) {
                            errorTitle = 'ไม่พบหน้า (404)';
                            errorDetail = 'Route ไม่ตรง — ตรวจสอบว่า URL: ' + editForm.action + ' ถูกต้อง';
                        } else if (status === 413) {
                            errorTitle = 'ไฟล์ใหญ่เกินไป (413)';
                            errorDetail = 'ขนาดไฟล์รวมเกิน limit ของเซิร์ฟเวอร์ กรุณาลดจำนวนรูปหรือเลือกรูปที่เล็กกว่า';
                        } else if (status === 500) {
                            try {
                                const json = JSON.parse(responseText);
                                if (json.message) {
                                    errorTitle = 'PHP Exception (500)';
                                    let msg = json.message;
                                    if (json.file) msg += '\n📁 ' + json.file + ':' + json.line;
                                    errorDetail = msg;
                                }
                            } catch(e) {
                                const fatalMatch = responseText.match(/Fatal error:\s*(.+?)(?:\n|<br)/);
                                const exceptionMatch = responseText.match(/<span class="exception">([^<]+)<\/span>/);
                                if (fatalMatch) {
                                    errorTitle = 'PHP Fatal Error (500)';
                                    errorDetail = fatalMatch[1].trim();
                                } else if (exceptionMatch) {
                                    errorTitle = 'PHP Exception (500)';
                                    errorDetail = exceptionMatch[1].trim();
                                } else if (responseText.trim() === '') {
                                    errorTitle = 'Server Error (500)';
                                    errorDetail = 'Server crash โดยไม่มี error message';
                                } else {
                                    errorTitle = 'Server Error (500)';
                                    errorDetail = 'เกิดข้อผิดพลาดภายในเซิร์ฟเวอร์ กรุณาติดต่อผู้ดูแลระบบ';
                                }
                            }
                        } else if (status === 400) {
                            try {
                                const json = JSON.parse(responseText);
                                if (json.message) {
                                    errorTitle = 'ข้อมูลไม่ถูกต้อง (Validation)';
                                    errorDetail = json.message;
                                }
                            } catch(e) {
                                const liMatch = responseText.match(/<li>([^<]+)<\/li>/g);
                                if (liMatch && liMatch.length > 0) {
                                    errorTitle = 'ข้อมูลไม่ถูกต้อง (Validation)';
                                    errorDetail = liMatch.slice(0, 3).map(li => li.replace(/<\/?li>/g, '').trim()).join('\n');
                                } else {
                                    errorTitle = 'Bad Request (400)';
                                    errorDetail = 'ข้อมูลที่ส่งไม่ถูกต้อง กรุณาตรวจสอบฟอร์มแล้วลองใหม่';
                                }
                            }
                        } else {
                            try {
                                const json = JSON.parse(responseText);
                                if (json.message) errorDetail = json.message;
                                if (json.status) errorTitle = 'Error (' + json.status + ')';
                            } catch(e) {
                                if (responseText.includes('<html')) {
                                    const titleMatch = responseText.match(/<title>([^<]+)<\/title>/);
                                    if (titleMatch) errorDetail = titleMatch[1].trim();
                                }
                            }
                            if (status >= 400) {
                                errorDetail = '[HTTP ' + status + '] ' + errorDetail;
                            }
                        }

                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHTML;

                        Swal.fire({
                            icon: 'error',
                            title: errorTitle,
                            text: errorDetail,
                            background: '#ffffff',
                            color: '#1e293b',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        });
                    }).catch(err => {
                        console.error('Fetch network error:', err);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHTML;
                        progressContainer.classList.add('hidden');

                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'การเชื่อมต่อขัดข้อง: ' + err.message,
                            background: '#ffffff',
                            color: '#1e293b',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        });
                    });
                } catch (uploadError) {
                    console.error('Upload error:', uploadError);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHTML;
                    progressContainer.classList.add('hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'อัปโหลดรูปภาพล้มเหลว',
                        text: 'เกิดข้อผิดพลาดขณะอัปโหลดไฟล์ภาพ: ' + uploadError.message,
                        background: '#ffffff',
                        color: '#1e293b',
                        customClass: { popup: 'glass-card rounded-[2rem]' }
                    });
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
