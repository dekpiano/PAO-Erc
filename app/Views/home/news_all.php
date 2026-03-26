<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <section class="pt-32 pb-24 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header & Search -->
            <div class="mb-16" data-aos="fade-up">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                    <div>
                        <h2 class="text-4xl font-black text-slate-900 mb-4">คลังข่าวสารและกิจกรรม</h2>
                        <p class="text-slate-400 font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                             <i data-lucide="archive" class="w-4 h-4 text-blue-600"></i> News Archive & Activities
                        </p>
                    </div>

                    <form action="<?= base_url('news') ?>" method="get" class="w-full md:w-96 relative">
                        <input type="text" name="search" value="<?= $search ?? '' ?>" placeholder="ค้นหาข่าวสาร..." class="w-full pl-14 pr-6 py-4 bg-white border border-slate-200 rounded-[2rem] shadow-xl shadow-slate-100 focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all font-bold text-slate-700">
                        <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                            <i data-lucide="search" class="w-6 h-6"></i>
                        </div>
                    </form>
                </div>

                <!-- Category Filters -->
                <div class="mt-12 flex flex-wrap gap-3">
                    <a href="<?= base_url('news') ?>" class="px-6 py-2.5 rounded-full text-sm font-bold transition-all <?= empty($category_active) ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-white text-slate-400 border border-slate-200 hover:border-blue-200 hover:text-blue-600' ?>">ทั้งหมด</a>
                    <?php 
                        $categories = ['ข่าวประชาสัมพันธ์', 'กิจกรรม', 'ประกาศ', 'สมัครงาน'];
                        foreach($categories as $cat):
                    ?>
                        <a href="<?= base_url('news?category=' . $cat) ?>" class="px-6 py-2.5 rounded-full text-sm font-bold transition-all <?= ($category_active == $cat) ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-white text-slate-400 border border-slate-200 hover:border-blue-200 hover:text-blue-600' ?>"><?= $cat ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-16">
                <?php if(!empty($news)): ?>
                    <?php foreach($news as $item): ?>
                        <a href="<?= base_url('news/' . $item['news_slug']) ?>" class="group block" data-aos="fade-up">
                            <div class="relative rounded-[32px] overflow-hidden mb-6 aspect-video bg-slate-100 border border-slate-100 shadow-sm transition-transform group-hover:scale-[1.02] duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                                <div class="absolute top-4 left-4 flex gap-2 z-20">
                                    <span class="px-3 py-1 bg-blue-600/90 text-white text-[10px] font-black rounded-lg uppercase tracking-wider backdrop-blur-md">
                                        <?= $item['news_category'] ?>
                                    </span>
                                </div>
                                <?php if($item['news_cover']): ?>
                                    <img src="<?= base_url('uploads/news/covers/' . $item['news_cover']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i data-lucide="image" class="w-12 h-12"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-slate-400 font-bold mb-3 flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-blue-500"></i> <?= date('d/m/Y', strtotime($item['news_created_at'])) ?>
                            </p>
                            <h4 class="text-xl font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-snug mb-3 line-clamp-2">
                                <?= $item['news_title'] ?>
                            </h4>
                            <div class="text-slate-500 text-sm leading-relaxed line-clamp-2">
                                <?= strip_tags($item['news_content']) ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-32 text-center bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-50">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 text-slate-200">
                            <i data-lucide="search-x" class="w-12 h-12"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-2">ไม่พบผลลัพธ์ที่ค้นหา</h3>
                        <p class="text-slate-400 font-medium">ลองเปลี่ยนคำค้นหา หรือหมวดหมู่ที่ต้องการอีกครั้ง</p>
                        <a href="<?= base_url('news') ?>" class="inline-flex items-center gap-2 mt-8 text-blue-600 font-black uppercase text-xs tracking-widest hover:underline">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> ล้างการค้นหา
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-20" data-aos="fade-up">
                <?= $pager->links() ?>
            </div>

        </div>
    </section>

    <!-- Style for Pagination custom styling to match our premium look -->
    <style>
        .pagination {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .pagination li {
            list-style: none;
        }
        .pagination li a, .pagination li span {
            display: flex;
            align-items: center;
            justify-center;
            min-width: 3rem;
            height: 3rem;
            padding: 0 1rem;
            border-radius: 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-weight: 700;
            transition: all 0.3s;
        }
        .pagination li.active span {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
        }
        .pagination li a:hover {
            border-color: #2563eb;
            color: #2563eb;
            transform: translateY(-2px);
        }
    </style>
<?= $this->endSection() ?>
