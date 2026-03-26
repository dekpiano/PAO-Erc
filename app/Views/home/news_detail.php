<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <!-- News Detail Header -->
    <section class="relative pt-32 pb-20 bg-slate-50 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-50/50 to-transparent"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10">
            <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 mb-8 hover:translate-x-1 transition-transform">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับหน้าแรก
            </a>
            
            <div class="flex items-center gap-3 mb-6" data-aos="fade-up">
                <span class="px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black rounded-full uppercase tracking-widest shadow-lg shadow-blue-100">
                    <?= $news['news_category'] ?>
                </span>
                <span class="text-slate-400 font-bold text-xs flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-4 h-4"></i> <?= date('d F Y', strtotime($news['news_created_at'])) ?>
                </span>
                <span class="text-slate-400 font-bold text-xs flex items-center gap-1.5 ml-auto">
                    <i data-lucide="eye" class="w-4 h-4"></i> <?= number_format($news['news_view_count']) ?> views
                </span>
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

            <!-- Share Section -->
            <div class="mt-24 p-10 bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-100 text-center flex flex-col items-center" data-aos="fade-up">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-6">ช่วยพวกเราแชร์ข่าวนี้ต่อ</p>
                <div class="flex gap-4">
                    <button class="w-14 h-14 bg-[#1877F2]/10 text-[#1877F2] rounded-2xl flex items-center justify-center hover:bg-[#1877F2] hover:text-white transition-all">
                        <i data-lucide="facebook" class="w-6 h-6"></i>
                    </button>
                    <button class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:scale-110 transition-transform">
                        <i data-lucide="twitter" class="w-6 h-6"></i>
                    </button>
                    <button class="w-14 h-14 bg-rose-500/10 text-rose-500 rounded-2xl flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
                        <i data-lucide="link" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- FSLightbox Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/index.min.js"></script>
<?= $this->endSection() ?>
