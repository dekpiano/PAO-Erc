<?= $this->extend('user/layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section with Premium Gradient -->
<section class="relative pt-40 pb-20 overflow-hidden">
    <div class="absolute inset-0 bg-[#0f172a] -z-10">
        <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/40 via-blue-800/20 to-indigo-900/40"></div>
        <div class="absolute top-0 left-0 w-full h-full opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>
        <!-- Animated Blob -->
        <div class="absolute top-1/4 -right-20 w-80 h-80 bg-blue-600/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-1/4 -left-20 w-80 h-80 bg-indigo-600/10 rounded-full blur-[100px] animate-pulse" style="animation-delay: 2s"></div>
    </div>
    
    <div class="container mx-auto px-6 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] mb-8" data-aos="fade-up">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span>
            Our Personnel
        </div>
        <h1 class="text-4xl md:text-7xl font-black text-white mb-6 uppercase tracking-tight leading-none" data-aos="fade-up" data-aos-delay="100">
            ทำเนียบ<span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">บุคลากร</span>
        </h1>
        <p class="text-slate-400 max-w-2xl mx-auto text-lg md:text-xl font-medium leading-relaxed" data-aos="fade-up" data-aos-delay="200">
            กองการศึกษา ศาสนา และวัฒนธรรม องค์การบริหารส่วนจังหวัดนครสวรรค์<br>
            <span class="text-slate-500 text-sm italic">"มุ่งมั่นพัฒนาการศึกษา พัฒนาคน พัฒนาสังคมอย่างยั่งยืน"</span>
        </p>
    </div>
</section>

<!-- Organization Chart Structure -->
<section class="pb-40 bg-[#f8fafc] relative -mt-12 rounded-t-[4rem] z-10 border-t border-white/10">
    <div class="container mx-auto px-6 pt-24">
        
        <!-- ==========================================
             TIER 1: EXECUTIVES (ผู้อำนวยการ / รองผู้อำนวยการ)
             ========================================== -->
        <?php if(!empty($personnel['executives'])): ?>
        <div class="mb-32 relative">
            <!-- Decorative Line -->
            <div class="absolute left-1/2 bottom-[-4rem] transform -translate-x-1/2 w-0.5 h-16 bg-gradient-to-b from-blue-600 to-transparent opacity-20 hidden lg:block"></div>
            
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="px-4 py-1.5 rounded-full bg-blue-600 text-white text-[10px] font-black uppercase tracking-[0.3em] shadow-lg shadow-blue-600/30">Executive Management</span>
                <h2 class="text-3xl md:text-4xl font-black text-slate-800 mt-4 italic">คณะผู้บริหารกองการศึกษา</h2>
            </div>
            
            <div class="flex flex-wrap justify-center gap-8">
                <?php foreach($personnel['executives'] as $p): ?>
                <div class="w-full sm:w-fit sm:max-w-none" data-aos="zoom-in">
                    <div class="group relative bg-white hover:bg-blue-100/60 hover:backdrop-blur-md rounded-3xl p-6 md:p-8 shadow-[0_15px_40px_rgba(15,23,42,0.04)] hover:shadow-xl hover:shadow-blue-500/20 hover:-translate-y-1 transition-all duration-500 border border-slate-100 hover:border-blue-300 flex flex-row items-center gap-6 md:gap-8 w-full sm:w-fit">
                        <!-- Left: Circular Photo -->
                        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden shrink-0 border-2 border-slate-100 shadow-sm bg-slate-50 relative">
                            <?php if($p['u_photo']): ?>
                                <img src="<?= base_url('uploads/personnel/' . $p['u_photo']) ?>" class="w-full h-full object-cover object-top scale-105 group-hover:scale-100 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <i data-lucide="user" class="w-12 h-12 md:w-16 md:h-16"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Right: Info -->
                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                            <h3 class="text-[clamp(0.95rem,4vw,1.875rem)] font-black text-slate-800 leading-tight mb-1 whitespace-nowrap">
                                <?= $p['u_prefix'] ?><?= $p['u_fullname'] ?>
                            </h3>
                            <p class="text-slate-500 font-bold text-xs sm:text-sm mb-2.5"><?= $p['position_name'] ?? 'ไม่มีตำแหน่ง' ?></p>
                            
                            <hr class="border-slate-200/60 w-16 mb-2.5">
                            
                            <?php 
                                $u_emp_type = !empty($p['u_emp_type']) ? $p['u_emp_type'] : '';
                                $u_level_text = (!empty($p['u_level']) && $p['u_level'] !== 'ไม่มีระดับ') ? $p['u_level'] : '';
                                $badge_text = $u_emp_type && $u_level_text ? $u_emp_type . ' - ' . $u_level_text : ($u_emp_type ?: $u_level_text);
                            ?>
                            <?php if($badge_text): ?>
                                <p class="text-slate-400 font-bold text-[10px] sm:text-xs uppercase tracking-wider mb-3"><?= $badge_text ?></p>
                            <?php endif; ?>
                            
                            <!-- Contacts (Gray circular buttons matching mockup) -->
                            <div class="flex gap-2">
                                <?php if($p['u_phone']): ?>
                                <a href="tel:<?= $p['u_phone'] ?>" class="w-8 h-8 rounded-full bg-slate-100/80 flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-all duration-300 transform hover:scale-105">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </a>
                                <?php endif; ?>
                                <?php if($p['u_email']): ?>
                                <a href="mailto:<?= $p['u_email'] ?>" class="w-8 h-8 rounded-full bg-slate-100/80 flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-all duration-300 transform hover:scale-105">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- ==========================================
             TIER 2 & 3: DIVISIONS & STAFF (SIDE-BY-SIDE)
             ========================================== -->
        <!-- Changed to grid-cols-1 or xl:grid-cols-2 so they don't squish too early -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-12 sm:gap-16">
            <?php foreach($personnel['divisions'] as $division): ?>
            <div class="relative flex flex-col w-full">
                <!-- Division Title -->
                <div class="flex items-center gap-4 mb-10" data-aos="fade-up">
                    <div class="w-12 h-1 bg-blue-600 rounded-full"></div>
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-[0.4em] text-blue-500 block mb-1">Division</span>
                        <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight uppercase leading-none"><?= $division['name'] ?></h2>
                    </div>
                </div>

                <!-- Division Heads (หัวหน้าฝ่าย) -->
                <?php if(!empty($division['heads'])): ?>
                <div class="space-y-6 mb-8">
                    <?php foreach($division['heads'] as $h): ?>
                    <div class="group relative w-full" data-aos="zoom-in">
                        <!-- Head Label Badge -->
                        <div class="absolute -top-3 left-6 z-20 px-4 py-1 rounded-full bg-blue-600 text-white text-[9px] font-black uppercase tracking-[0.2em] shadow-lg shadow-blue-500/30">
                            หัวหน้าฝ่าย
                        </div>
                        
                        <div class="bg-white hover:bg-blue-100/60 hover:backdrop-blur-md rounded-3xl p-5 shadow-lg hover:shadow-xl hover:shadow-blue-500/20 hover:-translate-y-0.5 transition-all duration-500 border border-slate-100 hover:border-blue-300 flex flex-row items-center gap-5 w-full">
                            <!-- Left: Circular Photo -->
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden shrink-0 border-2 border-slate-100 shadow-inner bg-slate-50 relative">
                                <?php if($h['u_photo']): ?>
                                    <img src="<?= base_url('uploads/personnel/' . $h['u_photo']) ?>" class="w-full h-full object-cover object-top scale-105 group-hover:scale-100 transition-transform duration-500">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i data-lucide="user" class="w-10 h-10"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Right: Info -->
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <h3 class="text-[clamp(0.85rem,3.8vw,1.5rem)] font-black text-slate-800 leading-tight mb-1 whitespace-nowrap"><?= $h['u_prefix'] ?><?= $h['u_fullname'] ?></h3>
                                <p class="text-slate-500 font-bold text-xs mb-2"><?= $h['position_name'] ?? 'หัวหน้าฝ่าย' ?></p>
                                
                                <hr class="border-slate-200/60 w-12 mb-2">
                                
                                <?php 
                                    $h_emp = !empty($h['u_emp_type']) ? $h['u_emp_type'] : '';
                                    $h_lv = (!empty($h['u_level']) && $h['u_level'] !== 'ไม่มีระดับ') ? $h['u_level'] : '';
                                    $h_badge = $h_emp && $h_lv ? $h_emp . ' - ' . $h_lv : ($h_emp ?: $h_lv);
                                ?>
                                <?php if($h_badge): ?>
                                    <p class="text-slate-400 font-bold text-[10px] uppercase tracking-wider mb-2.5"><?= $h_badge ?></p>
                                <?php endif; ?>
                                
                                <div class="flex gap-1.5">
                                    <?php if($h['u_phone']): ?>
                                        <a href="tel:<?= $h['u_phone'] ?>" class="w-7 h-7 bg-slate-100/80 rounded-full flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-all transform hover:scale-105"><i data-lucide="phone" class="w-3.5 h-3.5"></i></a>
                                    <?php endif; ?>
                                    <?php if($h['u_email']): ?>
                                        <a href="mailto:<?= $h['u_email'] ?>" class="w-7 h-7 bg-slate-100/80 rounded-full flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-all transform hover:scale-105"><i data-lucide="mail" class="w-3.5 h-3.5"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Staff Grid (เจ้าหน้าที่) -->
                <div class="space-y-4">
                    <?php foreach($division['staff'] as $i => $s): ?>
                    <div class="group bg-white hover:bg-blue-100/60 hover:backdrop-blur-md rounded-3xl p-5 shadow-md hover:shadow-lg hover:shadow-blue-500/15 hover:-translate-y-0.5 transition-all duration-500 border border-slate-100 hover:border-blue-300 flex flex-row items-center gap-5 w-full" 
                         data-aos="fade-up" 
                         data-aos-delay="<?= $i * 30 ?>"
                         data-u-role="<?= $s['u_role'] ?? '' ?>">
                        
                        <!-- Left: Circular Photo -->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden shrink-0 border-2 border-slate-100 shadow-inner bg-slate-50 relative">
                            <?php if($s['u_photo']): ?>
                                <img src="<?= base_url('uploads/personnel/' . $s['u_photo']) ?>" class="w-full h-full object-cover object-top scale-105 group-hover:scale-100 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-slate-200">
                                    <i data-lucide="user" class="w-10 h-10"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right: Details -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-[clamp(0.75rem,3.3vw,1.125rem)] font-black text-slate-800 leading-tight mb-1 whitespace-nowrap"><?= $s['u_prefix'] ?? '' ?><?= $s['u_fullname'] ?></h3>
                            <p class="text-slate-500 font-bold text-xs mb-2"><?= $s['position_name'] ?? 'เจ้าหน้าที่' ?></p>
                            
                            <hr class="border-slate-200/60 w-10 mb-2">
                            
                            <?php 
                                $s_emp = !empty($s['u_emp_type']) ? $s['u_emp_type'] : '';
                                $s_lv = (!empty($s['u_level']) && $s['u_level'] !== 'ไม่มีระดับ') ? $s['u_level'] : '';
                                $s_badge = $s_emp && $s_lv ? $s_emp . ' - ' . $s_lv : ($s_emp ?: $s_lv);
                            ?>
                            <?php if($s_badge): ?>
                                <p class="text-[10px] text-slate-400 font-semibold uppercase mb-2.5"><?= $s_badge ?></p>
                            <?php endif; ?>

                            <!-- Small Contact icons -->
                            <div class="flex gap-1.5">
                                <?php if($s['u_phone']): ?>
                                <a href="tel:<?= $s['u_phone'] ?>" class="w-7 h-7 bg-slate-100/80 rounded-full flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-all transform hover:scale-105">
                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                </a>
                                <?php endif; ?>
                                <?php if($s['u_email']): ?>
                                <a href="mailto:<?= $s['u_email'] ?>" class="w-7 h-7 bg-slate-100/80 rounded-full flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-all transform hover:scale-105">
                                    <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Call to Action / Footer Decoration -->
<section class="py-24 bg-[#0f172a] overflow-hidden relative">
    <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(30deg, #1e293b 12%, transparent 12.5%, transparent 87%, #1e293b 87.5%, #1e293b), linear-gradient(150deg, #1e293b 12%, transparent 12.5%, transparent 87%, #1e293b 87.5%, #1e293b), linear-gradient(30deg, #1e293b 12%, transparent 12.5%, transparent 87%, #1e293b 87.5%, #1e293b), linear-gradient(150deg, #1e293b 12%, transparent 12.5%, transparent 87%, #1e293b 87.5%, #1e293b), linear-gradient(60deg, #334155 25%, transparent 25.5%, transparent 75%, #334155 75%, #334155), linear-gradient(60deg, #334155 25%, transparent 25.5%, transparent 75%, #334155 75%, #334155); background-size: 80px 140px; background-position: 0 0, 0 0, 40px 70px, 40px 70px, 0 0, 40px 70px;"></div>
    <div class="container mx-auto px-6 relative text-center">
        <h2 class="text-3xl font-black text-white mb-8">องค์การบริหารส่วนจังหวัดนครสวรรค์</h2>
        <p class="text-slate-400 mb-12 max-w-2xl mx-auto">กองการศึกษา ศาสนา และวัฒนธรรม ยินดีให้บริการและประสานงานเพื่อพัฒนาท้องถิ่นของเรา</p>
        <div class="flex flex-wrap justify-center gap-6">
            <a href="https://www.nakhonsawanpao.go.th" target="_blank" class="px-8 py-4 rounded-2xl bg-white/5 border border-white/10 text-white font-bold hover:bg-white/10 hover:-translate-y-1 transition-all">เว็บไซต์หลัก อบจ.</a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
