<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-12">
    <h2 class="text-4xl font-black text-slate-900 tracking-tight">ศูนย์รวมรายงานสรุป</h2>
    <p class="text-slate-500 mt-2 font-medium text-lg">เลือกประเภทรายงานที่คุณต้องการตรวจสอบหรือพิมพ์ส่งผู้บริหาร</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Annual Report Card -->
    <a href="<?= base_url('staff/attendance-admin/report/annual') ?>" class="group">
        <div class="glass-card bg-white p-10 rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/40 transition-all hover:shadow-2xl hover:shadow-blue-200/50 hover:-translate-y-2 relative overflow-hidden h-full">
            <div class="absolute top-0 right-0 p-8 text-slate-50 group-hover:text-blue-50 transition-colors">
                <i data-lucide="calendar-days" class="w-32 h-32 -mr-12 -mt-12 opacity-10"></i>
            </div>
            
            <div class="w-16 h-16 bg-blue-600 rounded-3xl flex items-center justify-center text-white mb-8 shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform">
                <i data-lucide="layout-grid" class="w-8 h-8"></i>
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 mb-4">รายงานสรุปปีงบประมาณ</h3>
            <p class="text-slate-500 font-medium leading-relaxed mb-8">
                สรุปภาพรวมการมาทำงาน สาย และการลา ทั้ง 12 เดือน (แยกเป็น 2 รอบการประเมิน) 
                แสดงเป็นตาราง Pivot สำหรับระเบียบวินัยรายบุคลากร
            </p>
            
            <div class="flex items-center text-blue-600 font-black text-sm uppercase tracking-widest gap-2">
                เปิดรายงาน 
                <i data-lucide="map-pin" class="w-4 h-4 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </div>
    </a>

    <!-- Monthly Report Card -->
    <a href="<?= base_url('staff/attendance-admin/report/monthly') ?>" class="group">
        <div class="glass-card bg-white p-10 rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/40 transition-all hover:shadow-2xl hover:shadow-emerald-200/50 hover:-translate-y-2 relative overflow-hidden h-full">
            <div class="absolute top-0 right-0 p-8 text-slate-50 group-hover:text-emerald-50 transition-colors">
                <i data-lucide="calendar" class="w-32 h-32 -mr-12 -mt-12 opacity-10"></i>
            </div>

            <div class="w-16 h-16 bg-emerald-600 rounded-3xl flex items-center justify-center text-white mb-8 shadow-lg shadow-emerald-200 group-hover:scale-110 transition-transform">
                <i data-lucide="clipboard-list" class="w-8 h-8"></i>
            </div>

            <h3 class="text-2xl font-black text-slate-900 mb-4">รายงานสรุปประจำเดือน</h3>
            <p class="text-slate-500 font-medium leading-relaxed mb-8">
                ตรวจสอบสถิติการมาทำงานแบบเจาะลึกเฉพาะรายเดือน แสดงรายละเอียดเป็นรายวัน 
                พร้อมสรุปยอด มา สาย ลา ขาด ของพนักงานทุกคนในเดือนนั้นๆ
            </p>

            <div class="flex items-center text-emerald-600 font-black text-sm uppercase tracking-widest gap-2">
                เปิดรายงาน 
                <i data-lucide="map-pin" class="w-4 h-4 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </div>
    </a>
</div>

<div class="mt-16 text-center">
    <a href="<?= base_url('staff/attendance-admin') ?>" class="inline-flex items-center gap-2 text-slate-400 hover:text-slate-600 font-bold transition-all">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
        กลับหน้าบันทึกการมาทำงาน
    </a>
</div>

<?= $this->endSection() ?>
