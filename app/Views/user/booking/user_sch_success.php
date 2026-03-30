<?= $this->extend('user/layout/main') ?>

<?= $this->section('content') ?>

<style>
    .ticket-card {
        position: relative;
        overflow: hidden;
    }
    .ticket-card::before {
        content: '';
        position: absolute;
        left: -12px;
        top: 50%;
        width: 24px;
        height: 24px;
        background: #f8fafc;
        border-radius: 50%;
        z-index: 5;
    }
    .ticket-card::after {
        content: '';
        position: absolute;
        right: -12px;
        top: 50%;
        width: 24px;
        height: 24px;
        background: #f8fafc;
        border-radius: 50%;
        z-index: 5;
    }
    @media print {
        nav, footer, .no-print { display: none !important; }
        body { background: white !important; }
        .ticket-card { box-shadow: none !important; border: 2px solid #e2e8f0 !important; }
    }
</style>

<section class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/30 to-slate-50 py-12 px-4">
    <div class="max-w-lg mx-auto">

        <!-- Success Animation -->
        <div class="text-center mb-10" data-aos="zoom-in">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-100">
                <i data-lucide="check-circle" class="w-10 h-10 text-emerald-600"></i>
            </div>
            <h1 class="text-3xl font-black text-slate-900 mb-3">จองคิวสำเร็จ!</h1>
            <p class="text-slate-500 font-medium">กรุณาจดจำข้อมูลด้านล่าง หรือบันทึกภาพหน้าจอไว้</p>
        </div>

        <!-- E-Ticket Card -->
        <div class="ticket-card bg-white rounded-[2rem] border border-slate-200 shadow-2xl shadow-slate-200" data-aos="fade-up" data-aos-delay="200">
            
            <!-- Ticket Header -->
            <div class="bg-gradient-to-r from-violet-600 to-purple-700 p-8 rounded-t-[2rem] text-white text-center">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/20 rounded-full text-xs font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                    <i data-lucide="ticket" class="w-3.5 h-3.5"></i>
                    ใบนัดหมาย
                </div>
                <h2 class="text-2xl font-black leading-tight mb-2"><?= esc($booking['sch_title']) ?></h2>
                <p class="text-violet-200 text-sm font-medium">องค์การบริหารส่วนจังหวัดนครสวรรค์</p>
            </div>

            <!-- Queue Number -->
            <div class="py-8 text-center border-b border-dashed border-slate-200">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">หมายเลขคิวของคุณ</p>
                <div class="text-7xl font-black text-violet-700 leading-none tracking-tighter">
                    <?= str_pad($booking['bk_queue_number'], 3, '0', STR_PAD_LEFT) ?>
                </div>
            </div>

            <!-- Ticket Details -->
            <div class="p-8 space-y-5">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-violet-50 rounded-xl flex-shrink-0 flex items-center justify-center text-violet-600">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">วันที่นัดหมาย</p>
                        <?php
                            $dateObj = new DateTime($booking['slot_date']);
                            $thaiDays = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
                            $thaiMonths = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
                            $dayName = $thaiDays[(int)$dateObj->format('w')];
                            $fullDate = "วัน{$dayName}ที่ " . $dateObj->format('j') . ' ' . $thaiMonths[(int)$dateObj->format('n')] . ' ' . ($dateObj->format('Y') + 543);
                        ?>
                        <p class="text-sm font-black text-slate-800 mt-1"><?= $fullDate ?></p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-violet-50 rounded-xl flex-shrink-0 flex items-center justify-center text-violet-600">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ช่วงเวลา</p>
                        <p class="text-sm font-black text-slate-800 mt-1">
                            <?= date('H:i', strtotime($booking['slot_start_time'])) ?> - <?= date('H:i', strtotime($booking['slot_end_time'])) ?> น.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-violet-50 rounded-xl flex-shrink-0 flex items-center justify-center text-violet-600">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ชื่อผู้จอง</p>
                        <p class="text-sm font-black text-slate-800 mt-1"><?= esc($booking['bk_fullname']) ?></p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-violet-50 rounded-xl flex-shrink-0 flex items-center justify-center text-violet-600">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">เบอร์โทรศัพท์</p>
                        <p class="text-sm font-black text-slate-800 mt-1"><?= esc($booking['bk_phone']) ?></p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-violet-50 rounded-xl flex-shrink-0 flex items-center justify-center text-violet-600">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">สถานศึกษา</p>
                        <p class="text-sm font-black text-slate-800 mt-1"><?= esc($booking['bk_school']) ?> (<?= esc($booking['bk_grade']) ?>)</p>
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-emerald-700">สถานะ: ยืนยันแล้ว</p>
                        <p class="text-[10px] text-emerald-600 font-medium mt-0.5">จองเมื่อ <?= date('d/m/Y H:i', strtotime($booking['bk_created_at'])) ?> น.</p>
                    </div>
                </div>
            </div>

            <!-- Ticket Footer -->
            <div class="bg-slate-50 px-8 py-5 rounded-b-[2rem] border-t border-slate-100 text-center">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                    Booking ID: #<?= str_pad($booking['bk_id'], 6, '0', STR_PAD_LEFT) ?>
                </p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 mt-8 no-print" data-aos="fade-up" data-aos-delay="300">
            <button onclick="window.print()" class="flex-1 py-3.5 bg-white border-2 border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i> พิมพ์ใบนัด
            </button>
            <a href="<?= base_url('/') ?>" class="flex-1 py-3.5 bg-violet-600 text-white rounded-2xl font-bold text-sm hover:bg-violet-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-violet-100">
                <i data-lucide="home" class="w-4 h-4"></i> กลับหน้าหลัก
            </a>
        </div>

    </div>
</section>

<?= $this->endSection() ?>
