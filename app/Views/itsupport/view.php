<?= $this->extend('itsupport/layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="mb-10">
    <a href="<?= base_url('itsupport') ?>" class="text-xs font-bold text-slate-400 hover:text-cyan-400 flex items-center gap-1 mb-3 transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> ย้อนกลับไปประวัติงาน
    </a>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-white tracking-tight tech-glow flex items-center gap-3">
                <span class="font-mono text-cyan-400"><?= $log['its_ticket_code'] ?></span>
            </h2>
            <p class="text-sm text-slate-400 mt-1 font-medium">ดูรายละเอียดประวัติการให้บริการและรายละเอียดทางเทคนิคทั้งหมด</p>
        </div>
        <?php if ($can_manage): ?>
        <div class="flex gap-2">
            <a href="<?= base_url('itsupport/print/' . $log['its_id']) ?>" target="_blank" class="px-5 py-3 rounded-2xl bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white font-bold text-sm transition-all flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4 text-cyan-400"></i> พิมพ์ใบงานส่งมอบ A4
            </a>
            <a href="<?= base_url('itsupport/edit/' . $log['its_id']) ?>" class="px-5 py-3 rounded-2xl bg-cyan-500 hover:bg-cyan-600 text-white font-bold text-sm transition-all flex items-center gap-2">
                <i data-lucide="edit" class="w-4 h-4"></i> แก้ไขข้อมูล
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Main Details Card -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Log Info Left (2 columns width) -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Details Card -->
        <div class="glass-card p-8 rounded-[2.5rem]">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-cyan-400"></i> รายละเอียดบันทึกการทำงาน
            </h3>
            
            <div class="space-y-6 text-slate-200">
                <div class="space-y-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">ผลงานการปฏิบัติหน้าที่แก้ไขปัญหา</span>
                    <div class="p-6 bg-slate-950/60 border border-slate-900 rounded-3xl text-sm leading-relaxed text-slate-300 font-medium whitespace-pre-wrap"><?= esc($log['its_task']) ?></div>
                </div>
            </div>
        </div>

        <!-- Attached Images Gallery -->
        <?php 
            $images = !empty($log['its_images']) ? json_decode($log['its_images'], true) : [];
        ?>
        <div class="glass-card p-8 rounded-[2.5rem]">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <i data-lucide="image" class="w-4 h-4 text-emerald-400"></i> ภาพถ่ายหน้างานประกอบการซ่อมบำรุง
            </h3>

            <?php if(empty($images)): ?>
                <div class="text-slate-500 text-center py-10 text-sm font-medium border border-slate-900 border-dashed rounded-3xl">
                    ไม่มีรูปภาพแนบในบันทึกใบงานนี้
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <?php foreach($images as $img): ?>
                        <div class="relative rounded-2xl overflow-hidden aspect-video border border-slate-800 bg-slate-900 shadow-lg cursor-zoom-in group" onclick="zoomImage('<?= base_url('uploads/it_support/' . $img) ?>')">
                            <img src="<?= base_url('uploads/it_support/' . $img) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                <i data-lucide="zoom-in" class="w-5 h-5"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Sidebar Metadata (1 column width) -->
    <div class="space-y-6">
        <div class="glass-card p-6 rounded-[2rem] space-y-6">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 border-b border-slate-900 pb-3">
                <i data-lucide="info" class="w-4 h-4 text-indigo-400"></i> ข้อมูลเมทาดาตาใบงาน
            </h3>

            <!-- Row 1 -->
            <div class="space-y-1">
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">หมวดหมู่งานบริการ</span>
                <span class="px-3 py-1 rounded-full bg-slate-900 text-cyan-400 border border-slate-800 text-[10px] font-extrabold inline-block uppercase mt-1">
                    <?= $log['its_category'] ?>
                </span>
            </div>

            <!-- Row 2 -->
            <div class="space-y-1">
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">สถานที่ปฏิบัติงาน</span>
                <span class="text-sm font-extrabold text-amber-500 block mt-1">📍 <?= esc($log['its_location']) ?></span>
            </div>

            <!-- Row 3 -->
            <div class="space-y-1">
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">วันเวลาทำงาน</span>
                <span class="text-sm font-bold text-slate-300 block mt-1">
                    📅 <?= date('d/m/Y H:i', strtotime($log['its_date'])) ?> น.
                </span>
            </div>

            <!-- Row 4 -->
            <div class="space-y-1">
                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block">เจ้าหน้าที่ไอทีผู้รับผิดชอบ</span>
                <span class="text-sm font-extrabold text-slate-300 block mt-1">
                    👤 <?= esc($log['its_recorded_by']) ?>
                </span>
            </div>
            
            <!-- Row 5 -->
            <div class="space-y-1 pt-3 border-t border-slate-900 text-[10px] text-slate-500">
                <p>บันทึกเข้าระบบ: <?= date('d/m/Y H:i', strtotime($log['its_created_at'])) ?> น.</p>
                <p>แก้ไขล่าสุด: <?= date('d/m/Y H:i', strtotime($log['its_updated_at'])) ?> น.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function zoomImage(url) {
        Swal.fire({
            imageUrl: url,
            imageAlt: 'Work Image',
            showConfirmButton: false,
            background: '#090d16',
            color: '#e2e8f0',
            width: 'auto',
            padding: '10px',
            customClass: {
                popup: 'glass-card rounded-[2.5rem] max-w-4xl overflow-hidden'
            }
        });
    }
</script>
<?= $this->endSection() ?>
