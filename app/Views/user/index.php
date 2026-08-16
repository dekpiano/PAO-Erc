<?= $this->extend('user/layout/main') ?>

<?= $this->section('content') ?>



    <!-- Minimalist & Luxurious Hero Section -->
    <style>
        .lux-gradient-text {
            background: linear-gradient(to right, #60a5fa, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .lux-gold-text {
            color: #b79045; /* Soft, elegant gold */
        }
        .lux-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: all 0.4s ease;
        }
        .lux-card:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }
        .minimal-line {
            width: 40px;
            height: 2px;
            background-color: #60a5fa;
        }
        .hero-parallax {
            background-image: url('<?= base_url('assets/images/home-bg.png') ?>');
            background-attachment: scroll;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
            overflow: hidden;
        }
        .hero-overlay {
            /* Luxurious deep blue overlay */
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 58, 138, 0.75) 100%);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .cta-parallax {
            background-image: url('<?= base_url('assets/images/home-bg.png') ?>');
            background-attachment: scroll;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
            overflow: hidden;
        }
        .cta-overlay {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.95) 100%);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        /* Enable fixed parallax background on Desktop only (ignores mobile Safari bugs) */
        @media (min-width: 1024px) {
            .hero-parallax, .cta-parallax {
                background-attachment: fixed;
            }
        }
    </style>

    <section class="hero-parallax relative min-h-[90vh] flex items-center pt-24 pb-20">
        <!-- Overlay -->
        <div class="hero-overlay"></div>
        
        <!-- Subtle glow effects -->
        <div class="absolute top-1/4 -left-1/4 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl z-2"></div>
        <div class="absolute bottom-1/4 -right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl z-2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="text-center max-w-4xl mx-auto" data-aos="fade-up" data-aos-duration="1200">
                <!-- PAO Logo in Center -->
                <div class="flex justify-center mb-6">
                    <img src="<?= base_url('assets/images/logo-pao.png') ?>" alt="ตรา อบจ.นครสวรรค์" class="w-28 h-28 sm:w-36 sm:h-36 object-contain filter drop-shadow-md">
                </div>
                
                <div class="flex items-center justify-center gap-4 mb-8">
                    <div class="minimal-line hidden sm:block"></div>
                    <span class="text-xs font-bold tracking-[0.2em] uppercase text-blue-200">องค์การบริหารส่วนจังหวัดนครสวรรค์</span>
                    <div class="minimal-line hidden sm:block"></div>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-light text-white leading-[1.15] mb-8 tracking-tight drop-shadow-lg">
                    ยกระดับอนาคต<br/>
                    <span class="font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-white to-blue-200">
                        การศึกษาไทย
                    </span><br/>
                    สู่ยุคดิจิทัล
                </h1>
                
                <p class="text-lg md:text-xl text-blue-100/90 mb-12 max-w-2xl mx-auto leading-relaxed font-light drop-shadow">
                    กองการศึกษา ศาสนา และวัฒนธรรม มุ่งมั่นสร้างสรรค์นวัตกรรม 
                    เพื่อพัฒนาศักยภาพเยาวชน และสืบสานคุณค่าทางวัฒนธรรมให้ยั่งยืน
                </p>
                
                <div class="flex flex-wrap justify-center gap-5">
                    <a href="#news" class="group px-10 py-4 bg-white text-blue-900 rounded-full font-medium hover:bg-blue-50 transition-all duration-300 flex items-center gap-3 text-sm tracking-wide shadow-xl hover:-translate-y-1 hover:shadow-blue-500/10">
                        ข่าวสารล่าสุด <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform duration-300"></i>
                    </a>
                    <a href="<?= base_url('personnel') ?>" class="group px-10 py-4 bg-white/10 backdrop-blur-sm text-white border border-white/20 rounded-full font-medium hover:bg-white/20 hover:border-white/30 transition-all duration-300 text-sm tracking-wide hover:-translate-y-1 flex items-center gap-2">
                        ทำเนียบผู้บริหาร <i data-lucide="users" class="w-4 h-4 transform group-hover:scale-110 transition-transform duration-300"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Wave Divider at the bottom for smooth transition to next section -->
        <div class="absolute bottom-0 left-0 right-0 h-24 sm:h-32 pointer-events-none z-10 translate-y-1">
            <svg class="w-full h-full fill-slate-50" preserveAspectRatio="none" viewBox="0 0 1440 320">
                <path d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <!-- Featured Sports Banner Section -->
    <section class="bg-slate-50 py-8 relative -mt-10 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6" data-aos="fade-up">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-amber-300 shrink-0 shadow-inner">
                        <i data-lucide="trophy" class="w-8 h-8"></i>
                    </div>
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-2 px-3 py-0.5 bg-amber-400 text-slate-950 rounded-full text-[11px] font-black uppercase tracking-wider">
                            <span>เปิดรับสมัครแล้ว</span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black tracking-tight">การแข่งขันกีฬา อบจ.นครสวรรค์ เกมส์ ประจำปี 2569</h3>
                        <p class="text-emerald-100 text-xs sm:text-sm max-w-xl">ขอเชิญสถานศึกษาส่งทีมนักกีฬาเข้าร่วมการแข่งขัน พร้อมระบบตรวจสอบสถานะ ประกาศผล และดาวน์โหลดเกียรติบัตรออนไลน์</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto justify-end">
                    <a href="<?= base_url('sports') ?>" class="px-6 py-3.5 bg-amber-400 hover:bg-amber-500 text-slate-950 font-black rounded-2xl text-xs flex items-center gap-2 shadow-lg shadow-amber-400/20 transition-all hover:scale-105">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>เข้าสู่ระบบแข่งขันกีฬา</span>
                    </a>
                    <a href="<?= base_url('sports/results') ?>" class="px-5 py-3.5 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-bold rounded-2xl text-xs flex items-center gap-2 transition-all">
                        <i data-lucide="award" class="w-4 h-4 text-amber-300"></i>
                        <span>ผลการแข่งขัน</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- News & PR Section (Magazine Style) -->
    <section id="news" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6" data-aos="fade-up">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                        <span class="text-xs font-bold tracking-[0.2em] uppercase text-slate-500">Update & News</span>
                    </div>
                    <h3 class="text-4xl font-light text-slate-900">ข่าวประชาสัมพันธ์</h3>
                </div>
                <a href="<?= base_url('news') ?>" class="text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors flex items-center gap-2 group">
                    ดูทั้งหมด <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
                <?php if(!empty($latest_news)): ?>
                    <?php foreach($latest_news as $news): ?>
                        <a href="<?= base_url('news/' . $news['news_slug']) ?>" class="group block p-4 bg-white rounded-3xl shadow-[0_12px_35px_rgba(15,23,42,0.03)] hover:shadow-[0_22px_45px_rgba(30,58,138,0.09)] border border-slate-100 hover:border-blue-100/80 transition-all duration-500 hover:-translate-y-2" data-aos="fade-up">
                            <div class="overflow-hidden mb-6 bg-slate-100 rounded-2xl relative">
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="px-3 py-1 bg-white/90 backdrop-blur text-slate-800 text-[10px] font-medium tracking-widest uppercase">
                                        <?= $news['news_category'] ?>
                                    </span>
                                </div>
                                <?php if($news['news_cover']): ?>
                                    <img src="<?= base_url('uploads/news/covers/' . $news['news_cover']) ?>" class="w-full aspect-[4/3] object-cover filter brightness-95 group-hover:brightness-100 group-hover:scale-105 transition-all duration-700 ease-out">
                                <?php else: ?>
                                    <div class="w-full aspect-[4/3] bg-slate-50 flex items-center justify-center p-8">
                                        <img src="<?= base_url('assets/images/illustrations/news.svg') ?>" class="h-4/5 w-auto object-contain opacity-75 group-hover:scale-105 transition-transform duration-500">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="px-2">
                                <p class="text-xs text-slate-400 font-medium mb-3 tracking-wider">
                                    <?= date('d M Y', strtotime($news['news_created_at'])) ?>
                                </p>
                                <h4 class="text-xl font-medium text-slate-900 group-hover:text-blue-600 transition-colors duration-300 leading-snug mb-3 line-clamp-2">
                                    <?= $news['news_title'] ?>
                                </h4>
                                <p class="text-slate-500 text-sm leading-relaxed line-clamp-2 font-light">
                                    <?= strip_tags($news['news_content']) ?>
                                </p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-20 text-center">
                        <p class="text-slate-400 font-light">ไม่พบข้อมูลข่าวสารในขณะนี้</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Scholarships Section -->
    <section id="scholarships" class="py-32 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="lux-gold-text text-xs font-bold tracking-[0.2em] uppercase mb-4 block">Scholarships</span>
                <h3 class="text-4xl font-light text-slate-900">โอกาสเพื่ออนาคตทางการศึกษา</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php if(!empty($latest_scholarships)): ?>
                    <?php foreach($latest_scholarships as $sch): ?>
                        <div class="group lux-card p-6 flex flex-col h-full relative hover:border-blue-100/80 hover:shadow-xl hover:shadow-blue-500/5 hover:-translate-y-1.5 transition-all duration-500 rounded-3xl" data-aos="fade-up">
                             <div class="mb-6 -mx-6 -mt-6 overflow-hidden rounded-t-3xl bg-slate-50 aspect-[4/3] flex items-center justify-center p-6 border-b border-slate-100">
                                 <?php if($sch['sch_cover']): ?>
                                     <img src="<?= base_url('uploads/scholarships/covers/' . $sch['sch_cover']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                 <?php else: ?>
                                     <img src="<?= base_url('assets/images/illustrations/scholarship.svg') ?>" class="h-4/5 w-auto object-contain opacity-75 group-hover:scale-105 transition-transform duration-500">
                                 <?php endif; ?>
                             </div>
                             
                             <div class="mb-4">
                                 <span class="text-[10px] font-bold text-amber-600 uppercase tracking-widest bg-amber-50 px-2 py-1 rounded">
                                     <?= $sch['sch_amount'] ?: 'ทุนการศึกษา' ?>
                                 </span>
                             </div>
                             
                             <h4 class="text-lg font-semibold text-slate-800 leading-tight mb-4 flex-grow group-hover:text-blue-600 transition-colors duration-300">
                                 <?= $sch['sch_title'] ?>
                             </h4>
                             
                             <div class="pt-6 border-t border-slate-100 mt-auto flex items-center justify-between">
                                 <div class="text-xs text-slate-500">
                                     <?php if($sch['sch_deadline']): ?>
                                         หมดเขต: <?= date('d/m/Y', strtotime($sch['sch_deadline'])) ?>
                                     <?php else: ?>
                                         <span class="text-emerald-600">เปิดรับสมัคร</span>
                                     <?php endif; ?>
                                 </div>
                                 <a href="<?= base_url('booking/' . $sch['sch_slug']) ?>" class="text-slate-400 hover:text-blue-600 transition-colors flex items-center justify-center w-8 h-8 rounded-full bg-slate-50 group-hover:bg-blue-50 group-hover:text-blue-600 transform group-hover:scale-110 transition-all duration-300">
                                     <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                                 </a>
                             </div>
                         </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-12 text-center">
                        <p class="text-slate-400 font-light">ยังไม่มีข้อมูลทุนการศึกษาในขณะนี้</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Services Section (Minimal Icons) -->
    <section class="py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-24">
                <!-- Service 1 -->
                <div class="text-center group cursor-pointer hover:-translate-y-1.5 transition-transform duration-500" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-20 h-20 mx-auto mb-8 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-blue-50 group-hover:shadow-lg group-hover:shadow-blue-500/5 transition-all duration-500">
                        <i data-lucide="book-open" class="w-8 h-8 text-slate-400 group-hover:text-blue-600 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 stroke-[1.5]"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4 text-slate-900 group-hover:text-blue-600 transition-colors duration-300">แผนการจัดการศึกษา</h4>
                    <p class="text-slate-500 text-sm leading-relaxed font-light">
                        รวมข้อมูลแผนงานและโครงการต่างๆ ที่เกี่ยวข้องกับการพัฒนาเยาวชนและการศึกษาในจังหวัด
                    </p>
                </div>

                <!-- Service 2 -->
                <div class="text-center group cursor-pointer hover:-translate-y-1.5 transition-transform duration-500" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-20 h-20 mx-auto mb-8 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-amber-50 group-hover:shadow-lg group-hover:shadow-amber-500/5 transition-all duration-500">
                        <i data-lucide="briefcase" class="w-8 h-8 text-slate-400 group-hover:text-amber-600 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 stroke-[1.5]"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4 text-slate-900 group-hover:text-amber-600 transition-colors duration-300">ประกาศรับสมัครงาน</h4>
                    <p class="text-slate-500 text-sm leading-relaxed font-light">
                        ติดตามข้อมูลการรับสมัครบุคลากรทางการศึกษา และตำแหน่งว่างภายในกองการศึกษา อบจ.
                    </p>
                </div>

                <!-- Service 3 -->
                <div class="text-center group cursor-pointer hover:-translate-y-1.5 transition-transform duration-500" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-20 h-20 mx-auto mb-8 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-emerald-50 group-hover:shadow-lg group-hover:shadow-emerald-500/5 transition-all duration-500">
                        <i data-lucide="file-text" class="w-8 h-8 text-slate-400 group-hover:text-emerald-600 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 stroke-[1.5]"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4 text-slate-900 group-hover:text-emerald-600 transition-colors duration-300">ดาวน์โหลดแบบฟอร์ม</h4>
                    <p class="text-slate-500 text-sm leading-relaxed font-light">
                        ศูนย์รวมเอกสารราชการ แบบฟอร์มคำขอต่างๆ เพื่อความสะดวกในการติดต่อประสานงาน
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Elegant CTA -->
    <section class="cta-parallax py-32 text-white relative overflow-hidden">
        <div class="cta-overlay"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10" data-aos="fade-up">
            <h3 class="text-3xl md:text-5xl font-light mb-8 leading-tight">
                มุ่งเน้นความเสมอภาค และการเข้าถึง<br/>
                <span class="font-bold lux-gold-text">การศึกษาที่มีคุณภาพ</span>
            </h3>
            <p class="text-slate-400 font-light text-lg mb-12 max-w-2xl mx-auto leading-relaxed">
                ส่งเสริมระบบสารสนเทศเพื่อการศึกษา ทำนุบำรุงศาสนา ศิลปวัฒนธรรมประเพณีอันดีงาม และพัฒนากีฬาเพื่อสุขภาพของประชาชน
            </p>
            <a href="<?= base_url('personnel') ?>" class="inline-block px-10 py-4 bg-white text-slate-900 font-semibold text-sm tracking-widest uppercase hover:bg-slate-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-white/10 transition-all duration-300">
                ทำเนียบผู้บริหาร
            </a>
        </div>
    </section>

    <!-- Parallax Scroll Script -->
    <!-- Handled seamlessly via CSS @media queries -->


<?= $this->endSection() ?>
