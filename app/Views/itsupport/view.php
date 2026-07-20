<?= $this->extend('itsupport/layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="mb-10">
    <a href="<?= base_url('itsupport') ?>" class="text-xs font-bold text-slate-500 hover:text-blue-600 flex items-center gap-1 mb-3 transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> ย้อนกลับไปประวัติงาน
    </a>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight tech-glow flex items-center gap-3">
                <span class="font-mono text-blue-600"><?= $log['its_ticket_code'] ?></span>
            </h2>
            <p class="text-sm text-slate-500 mt-1 font-medium">ดูรายละเอียดประวัติการให้บริการและรายละเอียดทางเทคนิคทั้งหมด</p>
        </div>
        <?php if ($can_manage): ?>
        <div class="flex gap-2">
            <a href="<?= base_url('itsupport/print/' . $log['its_id']) ?>" target="_blank" class="px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 hover:text-slate-900 font-bold text-sm transition-all flex items-center gap-2 shadow-sm">
                <i data-lucide="printer" class="w-4 h-4 text-blue-600"></i> พิมพ์ใบงานส่งมอบ A4
            </a>
            <a href="<?= base_url('itsupport/edit/' . $log['its_id']) ?>" class="px-5 py-3 rounded-2xl bg-blue-650 hover:bg-blue-700 text-white font-bold text-sm transition-all flex items-center gap-2">
                <i data-lucide="edit" class="w-4 h-4"></i> แก้ไขข้อมูล
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Main Details Card -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Log Info Left (2 columns width) -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Details Card -->
        <div class="glass-card p-8 rounded-[2.5rem] bg-white">
            <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i> รายละเอียดบันทึกการทำงาน
            </h3>
            
            <div class="space-y-6 text-slate-700">
                <div class="space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">ผลงานการปฏิบัติหน้าที่แก้ไขปัญหา</span>
                    <div class="p-6 bg-slate-50 border border-slate-200 rounded-3xl text-sm leading-relaxed text-slate-650 font-medium whitespace-pre-wrap"><?= esc($log['its_task']) ?></div>
                </div>
            </div>
        </div>

        <!-- Attached Images Gallery -->
        <?php 
            $images = !empty($log['its_images']) ? json_decode($log['its_images'], true) : [];
            $imgUrls = [];
            foreach($images as $img) {
                $imgUrls[] = base_url('uploads/it_support/' . $img);
            }
            $imgJson = htmlspecialchars(json_encode($imgUrls), ENT_QUOTES, 'UTF-8');
        ?>
        <div class="glass-card p-8 rounded-[2.5rem] bg-white">
            <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                <i data-lucide="image" class="w-4 h-4 text-emerald-600"></i> ภาพถ่ายหน้างานประกอบการซ่อมบำรุง
            </h3>

            <?php if(empty($images)): ?>
                <div class="text-slate-400 text-center py-10 text-sm font-medium border border-slate-200 border-dashed rounded-3xl">
                    ไม่มีรูปภาพแนบในบันทึกใบงานนี้
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <?php foreach($images as $idx => $img): ?>
                        <div class="relative rounded-2xl overflow-hidden aspect-video border border-slate-200 bg-slate-50 shadow-sm cursor-zoom-in group" onclick="zoomImage(<?= $imgJson ?>, <?= $idx ?>)">
                            <img loading="lazy" src="<?= base_url('uploads/it_support/' . $img) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                <i data-lucide="zoom-in" class="w-5 h-5"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Sidebar Metadata (1 column width) -->
    <div class="space-y-6">
        <div class="glass-card p-6 rounded-[2rem] space-y-6 bg-white">
            <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center gap-2 border-b border-slate-100 pb-3">
                <i data-lucide="info" class="w-4 h-4 text-indigo-600"></i> ข้อมูลเมทาดาตาใบงาน
            </h3>

            <!-- Row 1 -->
            <div class="space-y-1">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">หมวดหมู่งานบริการ</span>
                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 border border-blue-100 text-[10px] font-extrabold inline-block uppercase mt-1">
                    <?= $log['its_category'] ?>
                </span>
            </div>

            <!-- Row 2 -->
            <div class="space-y-1">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">สถานที่ปฏิบัติงาน</span>
                <span class="text-sm font-extrabold text-amber-600 block mt-1">📍 <?= esc($log['its_location']) ?></span>
            </div>

            <!-- Row 3 -->
            <div class="space-y-1">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">วันเวลาทำงาน</span>
                <span class="text-sm font-bold text-slate-700 block mt-1">
                    📅 <?= date('d/m/Y H:i', strtotime($log['its_date'])) ?> น.
                </span>
            </div>

            <!-- Row 4 -->
            <div class="space-y-1">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">เจ้าหน้าที่ไอทีผู้รับผิดชอบ</span>
                <span class="text-sm font-extrabold text-slate-700 block mt-1">
                    👤 <?= esc($log['its_recorded_by']) ?>
                </span>
            </div>
            
            <!-- Row 5 -->
            <div class="space-y-1 pt-3 border-t border-slate-100 text-[10px] text-slate-400">
                <p>บันทึกเข้าระบบ: <?= date('d/m/Y H:i', strtotime($log['its_created_at'])) ?> น.</p>
                <p>แก้ไขล่าสุด: <?= date('d/m/Y H:i', strtotime($log['its_updated_at'])) ?> น.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function openImageGallery(images, startIndex) {
        if (!images || images.length === 0) return;
        
        let currentIndex = startIndex;
        
        const modal = document.createElement('div');
        modal.id = 'album-gallery-modal';
        modal.className = 'fixed inset-0 bg-slate-950/95 backdrop-blur-md z-[200] flex flex-col justify-between p-4 select-none';
        
        modal.innerHTML = `
            <!-- Top Bar -->
            <div class="flex justify-between items-center text-white z-10 py-2 px-4 max-w-7xl mx-auto w-full">
                <span class="text-xs sm:text-sm font-bold bg-slate-800/80 px-3.5 py-2 rounded-full border border-slate-700/50 backdrop-blur shadow-lg" id="gallery-counter">
                    ${currentIndex + 1} / ${images.length}
                </span>
                <button id="gallery-close" class="p-2.5 bg-slate-800/80 border border-slate-700/50 hover:bg-rose-600 hover:border-rose-500 rounded-full text-white transition-all shadow-lg hover:scale-105" title="ปิดหน้าต่าง (Esc)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Main Content Container -->
            <div class="flex-1 flex items-center justify-center relative max-w-5xl mx-auto w-full my-4">
                <button id="gallery-prev" class="absolute left-2 sm:left-4 p-3.5 bg-slate-900/60 border border-slate-800/50 hover:bg-blue-600 hover:border-blue-500 hover:scale-110 rounded-full text-white transition-all z-20 shadow-xl ${images.length <= 1 ? 'hidden' : ''}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <div class="w-full h-full flex items-center justify-center p-2">
                    <img loading="lazy" id="gallery-image" src="${images[currentIndex]}" class="max-w-full max-h-[72vh] object-contain rounded-2xl shadow-2xl transition-all duration-300 transform scale-100 ease-out">
                </div>
                
                <button id="gallery-next" class="absolute right-2 sm:right-4 p-3.5 bg-slate-900/60 border border-slate-800/50 hover:bg-blue-600 hover:border-blue-500 hover:scale-110 rounded-full text-white transition-all z-20 shadow-xl ${images.length <= 1 ? 'hidden' : ''}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            
            <!-- Bottom Thumbnails Carousel/Bar -->
            <div class="z-10 py-3 flex justify-center gap-2 overflow-x-auto max-w-xl mx-auto w-full px-4 scrollbar-none">
                ${images.map((img, idx) => `
                    <div id="gallery-thumb-${idx}" onclick="window.setGalleryIndex(${idx})" class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl overflow-hidden cursor-pointer border-2 transition-all shrink-0 ${idx === currentIndex ? 'border-blue-500 scale-105 shadow-md shadow-blue-500/20' : 'border-slate-800 opacity-50 hover:opacity-80'}">
                        <img loading="lazy" src="${img}" class="w-full h-full object-cover">
                    </div>
                `).join('')}
            </div>
        `;
        
        document.body.appendChild(modal);
        document.body.classList.add('overflow-hidden');
        
        const imgEl = modal.querySelector('#gallery-image');
        const counterEl = modal.querySelector('#gallery-counter');
        const prevBtn = modal.querySelector('#gallery-prev');
        const nextBtn = modal.querySelector('#gallery-next');
        
        function updateView() {
            imgEl.style.opacity = '0';
            imgEl.style.transform = 'scale(0.96)';
            
            setTimeout(() => {
                imgEl.src = images[currentIndex];
                counterEl.innerText = `${currentIndex + 1} / ${images.length}`;
                
                images.forEach((_, idx) => {
                    const thumb = modal.querySelector(`#gallery-thumb-${idx}`);
                    if (thumb) {
                        if (idx === currentIndex) {
                            thumb.className = "w-12 h-12 sm:w-16 sm:h-16 rounded-xl overflow-hidden cursor-pointer border-2 transition-all shrink-0 border-blue-500 scale-105 shadow-md shadow-blue-500/20";
                        } else {
                            thumb.className = "w-12 h-12 sm:w-16 sm:h-16 rounded-xl overflow-hidden cursor-pointer border-2 transition-all shrink-0 border-slate-800 opacity-50 hover:opacity-80";
                        }
                    }
                });
                
                imgEl.style.opacity = '1';
                imgEl.style.transform = 'scale(1)';
            }, 120);
        }
        
        window.setGalleryIndex = function(idx) {
            currentIndex = idx;
            updateView();
        };
        
        function next() {
            currentIndex = (currentIndex + 1) % images.length;
            updateView();
        }
        
        function prev() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateView();
        }
        
        let isClosed = false;
        function close() {
            if (isClosed) return;
            isClosed = true;
            modal.style.transition = 'opacity 0.2s ease';
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.remove();
                document.body.classList.remove('overflow-hidden');
                delete window.setGalleryIndex;
            }, 200);
        }
        
        prevBtn.addEventListener('click', prev);
        nextBtn.addEventListener('click', next);
        modal.querySelector('#gallery-close').addEventListener('click', close);
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.closest('.flex-1') === e.target) {
                close();
            }
        });
        
        const handleKeyDown = (e) => {
            if (e.key === 'Escape') close();
            if (images.length > 1) {
                if (e.key === 'ArrowRight') next();
                if (e.key === 'ArrowLeft') prev();
            }
        };
        document.addEventListener('keydown', handleKeyDown);
        
        const originalClose = close;
        close = () => {
            document.removeEventListener('keydown', handleKeyDown);
            originalClose();
        };
    }

    function zoomImage(urlOrArray, index = 0) {
        if (Array.isArray(urlOrArray)) {
            openImageGallery(urlOrArray, index);
        } else {
            openImageGallery([urlOrArray], 0);
        }
    }
</script>
<?= $this->endSection() ?>
