<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="relative pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 bg-blue-900 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 via-transparent to-purple-600/20"></div>
        <div class="absolute top-0 left-0 w-full h-full opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>
    
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6 uppercase tracking-tight" data-aos="fade-up">
            ทำเนียบ<span class="text-blue-400">บุคลากร</span>
        </h1>
        <p class="text-blue-100/60 max-w-2xl mx-auto text-lg leading-relaxed" data-aos="fade-up" data-aos-delay="100">
            กองการศึกษา ศาสนา และวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์
            มุ่งเน้นการให้บริการและส่งเสริมการเรียนรู้สู่ชุมชนอย่างยั่งยืน
        </p>
    </div>
</section>

<!-- Organizational Groups -->
<section class="pb-32 bg-slate-50 relative -mt-10 rounded-t-[3rem] z-10">
    <div class="container mx-auto px-6">
        
        <!-- Executive Section (Director) -->
        <?php if(!empty($personnel['director'])): ?>
        <div class="pt-20 mb-24">
            <div class="flex flex-col items-center">
                <div class="text-center mb-12">
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600 bg-blue-50 px-4 py-2 rounded-full mb-4 inline-block">Management</span>
                    <h2 class="text-3xl font-black text-slate-800">ผู้บริหารกองการศึกษา</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-1 gap-12 justify-center w-full max-w-md">
                    <?php foreach($personnel['director'] as $p): ?>
                    <div class="group bg-white rounded-[2.5rem] p-8 shadow-2xl shadow-blue-900/10 border border-blue-50 relative overflow-hidden text-center transition-all hover:-translate-y-2" data-aos="zoom-in">
                        <div class="w-48 h-48 mx-auto mb-8 rounded-3xl overflow-hidden border-4 border-blue-50 shadow-inner group-hover:scale-105 transition-transform">
                            <?php if($p['u_photo']): ?>
                                <img src="<?= base_url('uploads/personnel/' . $p['u_photo']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                    <i data-lucide="user" class="w-20 h-20"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-2 leading-tight">
                            <?= $p['u_prefix'] ?><?= $p['u_fullname'] ?>
                        </h3>
                        <p class="text-blue-600 font-bold uppercase tracking-widest text-[11px] mb-1"><?= $p['u_position'] ?></p>
                        <?php if(!empty($p['u_level']) && $p['u_level'] !== 'ไม่มีระดับ'): ?>
                            <p class="text-slate-400 font-medium text-[10px] mb-4">(<?= $p['u_level'] ?>)</p>
                        <?php else: ?>
                            <div class="mb-4"></div>
                        <?php endif; ?>
                        
                        <div class="flex justify-center gap-3 pt-4 opacity-40 group-hover:opacity-100 transition-opacity">
                            <?php if($p['u_phone']): ?>
                            <a href="tel:<?= $p['u_phone'] ?>" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-all">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </a>
                            <?php endif; ?>
                            <?php if($p['u_email']): ?>
                            <a href="mailto:<?= $p['u_email'] ?>" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-all">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="w-32 h-1 bg-gradient-to-r from-transparent via-slate-200 to-transparent mx-auto mb-24"></div>
        <?php endif; ?>

        <!-- Two Divisions Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
            
            <!-- Administration (ฝ่ายบริหาร) -->
            <div data-aos="fade-right">
                <div class="mb-12 border-l-8 border-purple-500 pl-6">
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-purple-600">Administration Division</span>
                    <h2 class="text-3xl font-black text-slate-800 mt-2">ฝ่ายบริหารงานทั่วไป</h2>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <?php foreach($personnel['admin'] as $p): ?>
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100 transition-all hover:shadow-2xl hover:border-purple-100">
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden border border-slate-50 shrink-0">
                                <?php if($p['u_photo']): ?>
                                    <img src="<?= base_url('uploads/personnel/' . $p['u_photo']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                        <i data-lucide="user" class="w-8 h-8"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-800 leading-tight"><?= $p['u_fullname'] ?></h3>
                                <p class="text-[10px] text-purple-600 font-bold uppercase mt-1"><?= $p['u_position'] ?></p>
                                <?php if(!empty($p['u_level']) && $p['u_level'] !== 'ไม่มีระดับ'): ?>
                                    <p class="text-[9px] text-slate-400 font-medium">(<?= $p['u_level'] ?>)</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Promotion (ฝ่ายส่งเสริม) -->
            <div data-aos="fade-left">
                <div class="mb-12 border-l-8 border-amber-500 pl-6">
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-amber-600">Educational Promotion</span>
                    <h2 class="text-3xl font-black text-slate-800 mt-2">ฝ่ายส่งเสริมการศึกษาฯ</h2>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <?php foreach($personnel['promotion'] as $p): ?>
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100 transition-all hover:shadow-2xl hover:border-amber-100">
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden border border-slate-50 shrink-0">
                                <?php if($p['u_photo']): ?>
                                    <img src="<?= base_url('uploads/personnel/' . $p['u_photo']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                        <i data-lucide="user" class="w-8 h-8"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-800 leading-tight"><?= $p['u_fullname'] ?></h3>
                                <p class="text-[10px] text-amber-600 font-bold uppercase mt-1"><?= $p['u_position'] ?></p>
                                <?php if(!empty($p['u_level']) && $p['u_level'] !== 'ไม่มีระดับ'): ?>
                                    <p class="text-[9px] text-slate-400 font-medium">(<?= $p['u_level'] ?>)</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div>
</section>

<?= $this->endSection() ?>
