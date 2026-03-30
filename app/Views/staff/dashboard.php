<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
    <div class="mb-12" data-aos="fade-up">
        <h2 class="text-3xl font-black text-slate-900 mb-2">แผงควบคุมบุคลากร</h2>
        <p class="text-slate-400 font-medium tracking-wide flex items-center gap-2 uppercase text-xs">
            <i data-lucide="layout-grid" class="w-4 h-4 text-blue-600"></i> Dashboard Overview
        </p>
    </div>

    <!-- Dashboard Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12" data-aos="fade-up" data-aos-delay="100">
        
        <!-- Welcome/Profile Card -->
        <div class="lg:col-span-1 p-8 bg-blue-600 rounded-[2.5rem] text-white shadow-xl shadow-blue-200 relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10 flex items-center gap-5">
                <?php if(session()->get('u_photo')): ?>
                    <img src="<?= base_url('uploads/personnel/' . session()->get('u_photo')) ?>" alt="Profile" class="w-20 h-20 rounded-2xl object-cover border-2 border-white/20 shadow-xl shrink-0">
                <?php else: ?>
                    <div class="w-20 h-20 rounded-2xl bg-white/20 border-2 border-white/20 shadow-xl shrink-0 flex items-center justify-center">
                        <i data-lucide="user" class="w-8 h-8 text-white"></i>
                    </div>
                <?php endif; ?>
                <div>
                    <p class="text-blue-100 font-bold text-xs uppercase tracking-widest mb-1">โปรไฟล์บุคลากร</p>
                    <h3 class="text-2xl font-black mb-1"><?= $fullname ?></h3>
                    <span class="px-2 py-0.5 bg-white/20 rounded-lg text-[9px] font-black uppercase tracking-wider backdrop-blur-md">
                        <?= strpos(session()->get('u_role'), 'superadmin') !== false ? 'Super Administrator' : 'Personnel' ?>
                    </span>
                </div>
            </div>
            <div class="mt-12">
                <a href="#" class="text-xs font-bold text-blue-100 flex items-center gap-2 hover:translate-x-1 transition-transform">
                    จัดการข้อมูลส่วนตัว <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <!-- Today's Attendance Card -->
        <div class="lg:col-span-1 p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 flex flex-col justify-between group cursor-pointer hover:border-emerald-200 transition-colors" onclick="location.href='<?= base_url('staff/attendance') ?>'">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">กิจกรรมวันนี้</p>
                    <h4 class="text-xl font-black text-slate-800">การเข้างาน</h4>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-8">
                <?php if($today_checkin): ?>
                    <p class="text-2xl font-black text-slate-900 leading-none mb-1"><?= date('H:i', strtotime($today_checkin['atd_timestamp'])) ?></p>
                    <p class="text-xs font-bold text-emerald-500 flex items-center gap-1">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> ลงชื่อเข้างานแล้ว
                    </p>
                <?php else: ?>
                    <p class="text-2xl font-black text-slate-300 leading-none mb-1">--:--</p>
                    <p class="text-xs font-bold text-rose-400 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> ยังไม่ได้ลงชื่อวันนี้
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- General Stats Card -->
        <div class="lg:col-span-1 p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">สถิติรวม</p>
                    <h4 class="text-xl font-black text-slate-800">จำนวนวันปฏิบัติงาน</h4>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                    <i data-lucide="bar-chart-2" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-8">
                <p class="text-4xl font-black text-slate-900 leading-none mb-1"><?= $total_attendance ?></p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">ครั้งที่บันทึกข้อมูล</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions and News -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" data-aos="fade-up" data-aos-delay="200">
        <!-- Announcements -->
        <div class="p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden relative">
            <h3 class="text-lg font-black text-slate-800 mb-8 flex items-center gap-3">
                <i data-lucide="megaphone" class="w-6 h-6 text-amber-500"></i> ประกาศสำหรับบุคลากร
            </h3>
            <div class="space-y-6">
                <div class="flex gap-4 p-4 hover:bg-slate-50 rounded-2xl transition-colors cursor-pointer">
                    <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 flex-shrink-0 animate-pulse"></div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 mb-1">ประชุมสรุปงานประจำเดือนมีนาคม</h4>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed">วันศุกร์ที่ 27 มีนาคม เวลา 13:30 น. ณ ห้องประชุมใหญ่</p>
                    </div>
                </div>
                <div class="flex gap-4 p-4 hover:bg-slate-50 rounded-2xl transition-colors cursor-pointer">
                    <div class="w-2 h-2 rounded-full bg-slate-300 mt-2 flex-shrink-0"></div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 mb-1">แจ้งการอัปเกรดระบบบริหารงานบุคคล</h4>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed">ระบบจะปรับปรุงในวันที่ 28 มีนาคม ช่วงเวลา 22:00 น.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shortcuts -->
        <div class="space-y-6">
            <!-- User Shortcuts -->
            <div>
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4"></i> เมนูพนักงานทั่วไป
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="<?= base_url('staff/attendance') ?>" class="p-6 bg-emerald-50 hover:bg-emerald-600 hover:text-white rounded-[2rem] border border-emerald-100 group transition-all duration-300 flex flex-col items-center justify-center text-center gap-3 shadow-sm shadow-emerald-100">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <i data-lucide="map-pin" class="w-6 h-6 text-emerald-600"></i>
                        </div>
                        <span class="text-xs font-black uppercase tracking-wider">ลงชื่อปฏิบัติงาน</span>
                    </a>
                </div>
            </div>


        </div>
    </div>

<?= $this->endSection() ?>
