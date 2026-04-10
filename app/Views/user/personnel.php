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
            
            <div class="flex flex-wrap justify-center gap-10">
                <?php foreach($personnel['executives'] as $p): ?>
                <div class="w-full max-w-[380px]" data-aos="zoom-in">
                    <div class="group relative bg-white rounded-[3rem] p-10 shadow-[0_30px_60px_-15px_rgba(15,23,42,0.1)] border border-blue-50 overflow-hidden transition-all duration-500 hover:-translate-y-3 hover:shadow-blue-900/10">
                        <!-- Card Glow -->
                        <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-600/5 rounded-full blur-3xl group-hover:bg-blue-600/10 transition-all duration-500"></div>
                        
                        <!-- Photo Section -->
                        <div class="relative w-56 h-56 mx-auto mb-10">
                            <div class="absolute inset-0 bg-blue-600 rounded-[3rem] rotate-6 opacity-5 group-hover:rotate-12 transition-all duration-500"></div>
                            <div class="relative w-full h-full rounded-[2.5rem] overflow-hidden border-4 border-white shadow-xl">
                                <?php if($p['u_photo']): ?>
                                    <img src="<?= base_url('uploads/personnel/' . $p['u_photo']) ?>" class="w-full h-full object-cover scale-110 group-hover:scale-100 transition-transform duration-700">
                                <?php else: ?>
                                    <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
                                        <i data-lucide="user" class="w-24 h-24"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Info Section -->
                        <div class="text-center">
                            <h3 class="text-2xl md:text-3xl font-black text-slate-800 mb-3 leading-tight tracking-tight">
                                <?= $p['u_prefix'] ?><?= $p['u_fullname'] ?>
                            </h3>
                            <div class="inline-block px-5 py-2 rounded-2xl bg-slate-50 border border-slate-100 mb-2">
                                <p class="text-blue-600 font-black uppercase tracking-widest text-[12px]"><?= $p['position_name'] ?? 'ไม่มีตำแหน่ง' ?></p>
                            </div>
                            <?php if(!empty($p['u_level']) && $p['u_level'] !== 'ไม่มีระดับ'): ?>
                                <p class="text-slate-400 font-bold text-[11px] mb-6 uppercase tracking-wider"><?= $p['u_level'] ?></p>
                            <?php else: ?>
                                <div class="mb-6"></div>
                            <?php endif; ?>
                            
                            <!-- Contact -->
                            <div class="flex justify-center gap-4 pt-6 border-t border-slate-50">
                                <?php if($p['u_phone']): ?>
                                <a href="tel:<?= $p['u_phone'] ?>" class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white hover:scale-110 transition-all duration-300 shadow-sm">
                                    <i data-lucide="phone" class="w-5 h-5"></i>
                                </a>
                                <?php endif; ?>
                                <?php if($p['u_email']): ?>
                                <a href="mailto:<?= $p['u_email'] ?>" class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white hover:scale-110 transition-all duration-300 shadow-sm">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 sm:gap-20">
            <?php foreach($personnel['divisions'] as $division): ?>
            <div class="relative flex flex-col">
                <!-- Division Title -->
                <div class="flex items-center gap-4 mb-12" data-aos="fade-up">
                    <div class="w-12 h-1 bg-blue-600 rounded-full"></div>
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-[0.4em] text-blue-500 block mb-1">Division</span>
                        <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tighter uppercase leading-none"><?= $division['name'] ?></h2>
                    </div>
                </div>

                <!-- Division Heads (หัวหน้าฝ่าย) - Suports multiple heads -->
                <?php if(!empty($division['heads'])): ?>
                <div class="space-y-4 mb-20">
                    <?php foreach($division['heads'] as $h): ?>
                    <div class="group relative w-full" data-aos="zoom-in">
                        <!-- Head Label Badge -->
                        <div class="absolute -top-4 left-6 z-20 px-4 py-1.5 rounded-full bg-[#1e293b] text-white text-[9px] font-black uppercase tracking-[0.2em] shadow-xl border border-white/10">
                            หัวหน้าฝ่าย
                        </div>
                        
                        <div class="bg-white rounded-[2rem] p-6 shadow-2xl shadow-blue-900/5 border-2 border-blue-500/10 flex items-center gap-6 group-hover:border-blue-500/30 transition-all duration-500">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden shadow-lg shrink-0 border-4 border-white group-hover:scale-110 transition-transform duration-500">
                                <?php if($h['u_photo']): ?>
                                    <img src="<?= base_url('uploads/personnel/' . $h['u_photo']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-200">
                                        <i data-lucide="user" class="w-8 h-8"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="relative z-10 flex-1">
                                <h3 class="text-lg font-black text-slate-800 leading-tight mb-1"><?= $h['u_prefix'] ?><?= $h['u_fullname'] ?></h3>
                                <p class="text-blue-600 font-black text-[10px] uppercase tracking-widest mb-1 leading-tight"><?= $h['position_name'] ?? 'หัวหน้าฝ่าย' ?></p>
                                <?php if(!empty($h['u_level']) && $h['u_level'] !== 'ไม่มีระดับ'): ?>
                                    <p class="text-slate-400 font-bold text-[8px] uppercase"><?= $h['u_level'] ?></p>
                                <?php endif; ?>
                                
                                <div class="flex gap-2 mt-3">
                                    <?php if($h['u_phone']): ?>
                                        <a href="tel:<?= $h['u_phone'] ?>" class="text-slate-300 hover:text-blue-600 transition-colors"><i data-lucide="phone" class="w-3.5 h-3.5"></i></a>
                                    <?php endif; ?>
                                    <?php if($h['u_email']): ?>
                                        <a href="mailto:<?= $h['u_email'] ?>" class="text-slate-300 hover:text-blue-600 transition-colors"><i data-lucide="mail" class="w-3.5 h-3.5"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Staff Grid (เจ้าหน้าที่) - Adjusted to 2 cols for side-by-side layout -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach($division['staff'] as $i => $s): ?>
                    <div class="group bg-white rounded-3xl p-5 shadow-lg shadow-slate-200/40 border border-slate-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-blue-200" 
                         data-aos="fade-up" 
                         data-aos-delay="<?= $i * 30 ?>"
                         data-u-role="<?= $s['u_role'] ?? '' ?>">
                        <div class="flex items-center gap-4">
                            
                            <div class="w-16 h-16 rounded-2xl overflow-hidden shrink-0 border-2 border-slate-50 shadow-sm group-hover:scale-105 transition-transform">
                                <?php if($s['u_photo']): ?>
                                    <img src="<?= base_url('uploads/personnel/' . $s['u_photo']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-200">
                                        <i data-lucide="user" class="w-6 h-6"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="overflow-hidden">
                                <h3 class="font-black text-slate-800 text-sm leading-tight mb-1 truncate"><?= $s['u_prefix'] ?? '' ?><?= $s['u_fullname'] ?></h3>
                                <p class="text-[9px] text-blue-500 font-bold uppercase tracking-wider mb-1 opacity-80"><?= $s['position_name'] ?? 'เจ้าหน้าที่' ?></p>
                                <?php if(!empty($s['u_level']) && $s['u_level'] !== 'ไม่มีระดับ'): ?>
                                    <p class="text-[8px] text-slate-400 font-medium truncate"><?= $s['u_level'] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ==========================================
             OTHER / UNCATEGORIZED
             ========================================== -->
        <?php if(!empty($personnel['other'])): ?>
        <div class="mt-40">
            <div class="text-center mb-16">
                <span class="px-4 py-1 bg-slate-100 rounded-full text-[10px] font-black uppercase text-slate-400 tracking-widest">Other Personnel</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 opacity-80 hover:opacity-100 transition-opacity">
                <?php foreach($personnel['other'] as $s): ?>
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-2xl overflow-hidden mb-3 border-2 border-slate-100">
                        <?php if($s['u_photo']): ?>
                            <img src="<?= base_url('uploads/personnel/' . $s['u_photo']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-200">
                                <i data-lucide="user" class="w-6 h-6"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h4 class="text-[11px] font-black text-slate-700 leading-tight"><?= $s['u_prefix'] ?? '' ?><?= $s['u_fullname'] ?></h4>
                    <p class="text-[9px] text-slate-400 mt-1"><?= $s['position_name'] ?? 'พนักงาน' ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

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
