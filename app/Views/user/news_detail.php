<?= $this->extend('user/layout/main') ?>

<?= $this->section('content') ?>
    <!-- News Detail Header -->
    <section class="relative pt-32 pb-20 bg-slate-50 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-50/50 to-transparent"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10">
            <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 mb-8 hover:translate-x-1 transition-transform">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับหน้าแรก
            </a>
            
            <div class="flex flex-wrap items-center gap-3 mb-6" data-aos="fade-up">
                <span class="px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black rounded-full uppercase tracking-widest shadow-lg shadow-blue-100">
                    <?= $news['news_category'] ?>
                </span>
                <span class="text-slate-400 font-bold text-xs flex items-center gap-1.5 border-r border-slate-200 pr-3 mr-1">
                    <i data-lucide="calendar" class="w-4 h-4"></i> <?= date('d F Y', strtotime($news['news_created_at'])) ?>
                </span>
                
                <!-- Stats & Share Buttons -->
                <div class="flex items-center gap-3 ml-auto">
                    <span class="text-slate-400 font-bold text-xs flex items-center gap-1.5 mr-2">
                        <i data-lucide="eye" class="w-4 h-4"></i> <?= number_format($news['news_view_count']) ?> views
                    </span>
                    
                    <div class="flex items-center gap-1.5 bg-white p-1 rounded-xl border border-slate-100 shadow-sm">
                        <!-- Facebook Share -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" class="w-8 h-8 bg-[#1877F2]/10 text-[#1877F2] rounded-lg flex items-center justify-center hover:bg-[#1877F2] hover:text-white transition-all" title="แชร์ลง Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        </a>
                        
                        <!-- Copy Link -->
                        <button onclick="copyCurrentUrl()" class="w-8 h-8 bg-slate-50 text-slate-500 rounded-lg flex items-center justify-center hover:bg-slate-200 hover:text-slate-700 transition-all" title="คัดลอกลิงก์">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>

            <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-[1.1] mb-12 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                <?= $news['news_title'] ?>
            </h1>

            <?php if($news['news_cover']): ?>
                <div class="relative rounded-[3rem] overflow-hidden shadow-2xl shadow-blue-100 mb-16 border-4 border-white" data-aos="zoom-in">
                    <img src="<?= base_url('uploads/news/covers/' . $news['news_cover']) ?>" class="w-full h-auto max-h-[600px] object-cover">
                </div>
            <?php endif; ?>

            <!-- Article Content -->
            <article class="prose prose-lg max-w-none text-slate-700 font-medium leading-[1.8] mb-20" data-aos="fade-up">
                <?= nl2br($news['news_content']) ?>
            </article>

            <!-- Photo Gallery Section -->
            <?php if(!empty($gallery)): ?>
                <div class="pt-20 border-t border-slate-200">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm border border-amber-100">
                            <i data-lucide="images" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 leading-none">คลังภาพกิจกรรม</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-2">Photo Gallery (<?= count($gallery) ?> images)</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" data-aos="fade-up">
                        <?php foreach($gallery as $img): ?>
                            <a data-fslightbox="news-gallery" href="<?= base_url('uploads/news/gallery/' . $img['gal_image']) ?>" class="group relative aspect-square rounded-[2rem] overflow-hidden bg-slate-100 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500">
                                <img src="<?= base_url('uploads/news/gallery/' . $img['gal_image']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-blue-600/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                    <i data-lucide="zoom-in" class="w-8 h-8 scale-50 group-hover:scale-100 transition-transform"></i>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <!-- FSLightbox Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/index.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

        function copyCurrentUrl() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'คัดลอกลิงก์แล้ว!',
                    text: 'คุณสามารถนำลิงก์ไปแชร์ต่อได้ทันที',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-[2rem]',
                    }
                });
            });
        }
    </script>
<?= $this->endSection() ?>
