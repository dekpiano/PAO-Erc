<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>



    <!-- Hero Section with Parallax -->
    <!-- Hero Section with Modern Aesthetic & Parallax -->
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        .hero-parallax {
            background-image: url('<?= base_url('assets/images/home-bg.png') ?>');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
            overflow: hidden;
        }
        .hero-overlay {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.9), rgba(30, 58, 138, 0.6));
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>

    <section class="hero-parallax relative min-h-[90vh] flex items-center pt-32 pb-40 px-4">
        <!-- Overlay -->
        <div class="hero-overlay"></div>

        <!-- Floating Blobs for Depth (Subtle) -->
        <div class="absolute top-0 -left-4 w-72 h-72 bg-blue-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob z-2"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob animation-delay-2000 z-2"></div>

        <div class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
            <!-- Left Side: Core Content -->
            <div class="lg:col-span-7" data-aos="fade-right">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/20 border border-blue-400/30 rounded-full text-blue-100 text-xs font-bold uppercase tracking-widest mb-8 backdrop-blur-md">
                    <span class="flex h-2 w-2 rounded-full bg-blue-400 animate-ping"></span>
                    องค์การบริหารส่วนจังหวัดนครสวรรค์
                </div>
                
                <h2 class="text-5xl md:text-7xl font-black text-white leading-[1.1] mb-8 tracking-tight drop-shadow-2xl">
                    ยกระดับอนาคต <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-indigo-200">
                        การศึกษาไทย
                    </span><br/>
                    สู่ยุคดิจิทัล
                </h2>
                
                <p class="text-xl text-blue-50 mb-12 max-w-xl leading-relaxed font-medium drop-shadow-md">
                    กองการศึกษา ศาสนา และวัฒนธรรม มุ่งมั่นสร้างสรรค์นวัตกรรม 
                    เพื่อพัฒนาศักยภาพเยาวชน และสืบสานคุณค่าทางวัฒนธรรมให้ยั่งยืน
                </p>
                
                <div class="flex flex-col sm:flex-row gap-5">
                    <!-- <a href="<?= base_url('staff/attendance') ?>" class="group px-8 py-4 bg-white text-blue-700 rounded-2xl font-black text-lg shadow-2xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                        <i data-lucide="scan-eye" class="w-6 h-6 group-hover:rotate-12 transition-transform"></i>
                        ระบบลงชื่อเจ้าหน้าที่
                    </a> -->
                    <a href="#news" class="px-8 py-4 bg-white/10 text-white border border-white/20 rounded-2xl font-bold text-lg backdrop-blur-md hover:bg-white/20 transition-all flex items-center justify-center gap-3">
                        <i data-lucide="layers" class="w-6 h-6"></i>
                        ดูข่าวสารล่าสุด
                    </a>
                </div>
            </div>

            <!-- Right Side: Interactive Features/Stats -->
            <div class="lg:col-span-5 grid grid-cols-2 gap-4" data-aos="fade-left" data-aos-delay="200">
                <!-- Feature Card 1 -->
                <div class="glass-card p-6 rounded-3xl hover:bg-white/10 transition-colors group cursor-default">
                    <div class="w-12 h-12 bg-blue-500/30 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                    </div>
                    <div class="text-3xl font-black text-white mb-1 tracking-tighter">โรงเรียน</div>
                    <p class="text-blue-200/80 text-sm font-bold uppercase tracking-tighter">ในสังกัด อบจ.</p>
                </div>

                <!-- Feature Card 2 -->
                <div class="glass-card p-6 rounded-3xl mt-8 hover:bg-white/10 transition-colors group cursor-default">
                    <div class="w-12 h-12 bg-amber-500/30 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <div class="text-3xl font-black text-white mb-1 tracking-tighter">นวัตกรรม</div>
                    <p class="text-amber-200/80 text-sm font-bold uppercase tracking-tighter">การเรียนรู้ใหม่</p>
                </div>

                <!-- Feature Card 3 -->
                <div class="glass-card p-6 rounded-3xl -mt-4 hover:bg-white/10 transition-colors group cursor-default">
                    <div class="w-12 h-12 bg-emerald-500/30 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <i data-lucide="trophy" class="w-6 h-6"></i>
                    </div>
                    <div class="text-3xl font-black text-white mb-1 tracking-tighter">卓越</div>
                    <p class="text-emerald-200/80 text-sm font-bold uppercase tracking-tighter">ความเป็นเลิศ</p>
                </div>

                <!-- Feature Card 4 -->
                <div class="glass-card p-6 rounded-3xl mt-4 hover:bg-white/10 transition-colors group cursor-default">
                    <div class="w-12 h-12 bg-purple-500/30 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <i data-lucide="book-open-check" class="w-6 h-6"></i>
                    </div>
                    <div class="text-3xl font-black text-white mb-1 tracking-tighter">โอกาส</div>
                    <p class="text-purple-200/80 text-sm font-bold uppercase tracking-tighter">ทางการศึกษา</p>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-50 z-10">
            <p class="text-[10px] text-white font-bold uppercase tracking-[0.2em]">เลื่อนลงเพื่อดูข้อมูล</p>
            <div class="w-px h-12 bg-gradient-to-b from-white to-transparent"></div>
        </div>

        <!-- Wave SVG Separator -->
        <div class="absolute bottom-0 left-0 right-0 h-32 pointer-events-none z-10 translate-y-1">
            <svg class="w-full h-full fill-white" preserveAspectRatio="none" viewBox="0 0 1440 320">
                <path d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <!-- News & PR Section -->
    <section id="news" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
                <div>
                    <span class="text-blue-600 font-black tracking-widest uppercase text-xs mb-3 inline-block">ประกาศและข่าวสาร</span>
                    <h3 class="text-4xl font-black text-slate-900 leading-tight">ข่าวประชาสัมพันธ์ล่าสุด</h3>
                </div>
                <a href="<?= base_url('news') ?>" class="px-6 py-3 border-2 border-slate-100 rounded-xl font-bold text-slate-600 hover:bg-slate-50 hover:border-blue-200 transition-all flex items-center gap-2">
                    ดูข่าวทั้งหมด <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <?php if(!empty($latest_news)): ?>
                    <?php foreach($latest_news as $news): ?>
                        <a href="<?= base_url('news/' . $news['news_slug']) ?>" class="group block" data-aos="fade-up">
                            <div class="relative rounded-[32px] overflow-hidden mb-6 aspect-video bg-slate-100 border border-slate-100 shadow-sm transition-transform group-hover:scale-[1.02] duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-10"></div>
                                <div class="absolute top-4 left-4 flex gap-2 z-20">
                                    <span class="px-3 py-1 bg-blue-600 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider">
                                        <?= $news['news_category'] ?>
                                    </span>
                                </div>
                                <?php if($news['news_cover']): ?>
                                    <img src="<?= base_url('uploads/news/covers/' . $news['news_cover']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i data-lucide="image" class="w-12 h-12"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-slate-400 font-bold mb-3 flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-blue-500"></i> <?= date('d/m/Y', strtotime($news['news_created_at'])) ?>
                            </p>
                            <h4 class="text-xl font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-snug mb-3 line-clamp-2">
                                <?= $news['news_title'] ?>
                            </h4>
                            <div class="text-slate-500 text-sm leading-relaxed line-clamp-2">
                                <?= strip_tags($news['news_content']) ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-20 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                            <i data-lucide="newspaper" class="w-10 h-10"></i>
                        </div>
                        <p class="text-slate-400 font-bold">ไม่พบข้อมูลข่าวสารในขณะนี้</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Quick Services / Stats Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h3 class="text-3xl font-black text-slate-900 mb-4">บริการและข้อมูลด่วน</h3>
                <div class="w-20 h-1.5 bg-blue-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="p-8 bg-blue-50/50 rounded-3xl border border-blue-100 hover:bg-blue-600 group transition-all duration-500 overflow-hidden relative cursor-pointer" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute -right-8 -bottom-8 opacity-10 group-hover:scale-110 transition-transform">
                        <i data-lucide="book-open" class="w-40 h-40 text-blue-900 group-hover:text-white"></i>
                    </div>
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:bg-white group-hover:text-blue-600 transition-colors">
                        <i data-lucide="book-open" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4 group-hover:text-white">แผนการจัดการศึกษา</h4>
                    <p class="text-slate-600 group-hover:text-blue-100 text-sm leading-relaxed mb-6">
                        รวมข้อมูลแผนงานและโครงการต่างๆ ที่เกี่ยวข้องกับการพัฒนาเยาวชนและการศึกษาในจังหวัด
                    </p>
                    <span class="text-sm font-bold text-blue-600 group-hover:text-white flex items-center gap-2">
                        ดูรายละเอียดเพิ่มเติม <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </span>
                </div>

                <!-- Card 2 -->
                <div class="p-8 bg-amber-50/50 rounded-3xl border border-amber-100 hover:bg-amber-500 group transition-all duration-500 overflow-hidden relative cursor-pointer" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute -right-8 -bottom-8 opacity-10 group-hover:scale-110 transition-transform">
                        <i data-lucide="briefcase" class="w-40 h-40 text-amber-900 group-hover:text-white"></i>
                    </div>
                    <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:bg-white group-hover:text-amber-500 transition-colors">
                        <i data-lucide="briefcase" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4 group-hover:text-white">ประกาศรับสมัครงาน</h4>
                    <p class="text-slate-600 group-hover:text-amber-50 text-sm leading-relaxed mb-6">
                        ติดตามข้อมูลการรับสมัครบุคลากรทางการศึกษา และตำแหน่งว่างภายในกองการศึกษา อบจ.
                    </p>
                    <span class="text-sm font-bold text-amber-600 group-hover:text-white flex items-center gap-2">
                        สมัครงานออนไลน์ <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </span>
                </div>

                <!-- Card 3 -->
                <div class="p-8 bg-emerald-50/50 rounded-3xl border border-emerald-100 hover:bg-emerald-600 group transition-all duration-500 overflow-hidden relative cursor-pointer" data-aos="fade-up" data-aos-delay="300">
                    <div class="absolute -right-8 -bottom-8 opacity-10 group-hover:scale-110 transition-transform">
                        <i data-lucide="file-text" class="w-40 h-40 text-emerald-900 group-hover:text-white"></i>
                    </div>
                    <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:bg-white group-hover:text-emerald-600 transition-colors">
                        <i data-lucide="file-text" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-4 group-hover:text-white">ดาวน์โหลดแบบฟอร์ม</h4>
                    <p class="text-slate-600 group-hover:text-emerald-100 text-sm leading-relaxed mb-6">
                        ศูนย์รวมเอกสารราชการ แบบฟอร์มคำขอต่างๆ เพื่อความสะดวกในการติดต่อประสานงาน
                    </p>
                    <span class="text-sm font-bold text-emerald-600 group-hover:text-white flex items-center gap-2">
                        ดาวน์โหลดเอกสาร <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </span>
                </div>
            </div>
        </div>
    </section>



    <!-- Executive Team / About CTA -->
    <section class="py-24 bg-slate-50 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-20">
                <div class="w-full lg:w-1/2 relative" data-aos="fade-right">
                    <!-- Glassy decoration behind "image" placeholder -->
                    <div class="absolute -top-10 -left-10 w-full h-full bg-blue-100 rounded-full blur-3xl opacity-50"></div>
                    <div class="relative bg-gradient-to-br from-blue-600 to-blue-800 rounded-[40px] aspect-[4/5] shadow-2xl overflow-hidden flex items-center justify-center text-white text-center p-12">
                        <div class="space-y-6">
                            <i data-lucide="user" class="w-24 h-24 mx-auto opacity-20"></i>
                            <h5 class="text-3xl font-black">ทำเนียบผู้บริหาร</h5>
                            <p class="opacity-80 font-medium">ทำความรู้จักกับคณะผู้บริหารกองการศึกษา ศาสนา และวัฒนธรรม</p>
                            <button class="px-8 py-3 bg-white text-blue-700 rounded-xl font-bold hover:scale-105 transition-transform">
                                ดูรายชื่อทั้งหมด
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="w-full lg:w-1/2" data-aos="fade-left">
                    <span class="text-blue-600 font-black tracking-widest uppercase text-xs mb-4 inline-block">วิสัยทัศน์และการทำงาน</span>
                    <h3 class="text-4xl md:text-5xl font-black text-slate-900 mb-8 leading-tight">
                        เรามุ่งเน้นความเสมอภาค <br/> และการเข้าถึง <span class="text-blue-600">การศึกษาที่มีคุณภาพ</span>
                    </h3>
                    <div class="space-y-6 text-slate-600 mb-12 font-medium">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex-shrink-0 flex items-center justify-center text-blue-600 mt-1">
                                <i data-lucide="check" class="w-5 h-5"></i>
                            </div>
                            <p>ส่งเสริมระบบสารสนเทศเพื่อการศึกษาและการเรียนรู้ตลอดชีวิต</p>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex-shrink-0 flex items-center justify-center text-blue-600 mt-1">
                                <i data-lucide="check" class="w-5 h-5"></i>
                            </div>
                            <p>ทำนุบำรุงศาสนา ศิลปวัฒนธรรม และประเพณีอันดีงามของท้องถิ่น</p>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex-shrink-0 flex items-center justify-center text-blue-600 mt-1">
                                <i data-lucide="check" class="w-5 h-5"></i>
                            </div>
                            <p>พัฒนากีฬาและส่งเสริมสุขภาพของประชาชนทุกช่วงวัย</p>
                        </div>
                    </div>
                    <a href="#" class="inline-flex items-center gap-3 text-lg font-bold text-slate-900 hover:text-blue-600 transition-colors">
                        เรียนรู้เพิ่มเติมเกี่ยวกับเรา <i data-lucide="arrow-right" class="w-6 h-6 animate-pulse"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>
