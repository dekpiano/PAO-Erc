<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>

<div class="mb-6">
    <a href="<?= base_url('science-week/staff') ?>" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors text-sm font-bold mb-4">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับหน้ารายชื่อ
    </a>
    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
            <i data-lucide="scan-line" class="w-5 h-5 text-white"></i>
        </div>
        ระบบรายงานตัว (Check-in)
    </h2>
    <p class="text-slate-400 mt-2 text-sm font-medium">สแกนจาก QR Code หน้างาน เพื่อยืนยันการเข้าร่วมแข่งขัน</p>
</div>

<div class="max-w-3xl mx-auto">
    <div class="glass-card rounded-[2rem] p-6 sm:p-10 border-2 <?= $reg['reg_checkin_status'] == 1 ? 'border-emerald-500/50 shadow-emerald-500/20' : 'border-indigo-500/20' ?> shadow-2xl relative overflow-hidden transition-colors duration-500" id="checkin-card">
        
        <!-- Status Banner -->
        <div class="absolute top-0 left-0 right-0 py-3 text-center font-black tracking-wider uppercase text-sm transition-colors duration-500 <?= $reg['reg_checkin_status'] == 1 ? 'bg-emerald-500/20 text-emerald-400 border-b border-emerald-500/30' : 'bg-slate-800/50 text-slate-400 border-b border-slate-700/50' ?>" id="status-banner">
            <?= $reg['reg_checkin_status'] == 1 ? '<i data-lucide="check-circle-2" class="w-4 h-4 inline-block mr-1 -mt-0.5"></i> รายงานตัวแล้ว' : '<i data-lucide="clock" class="w-4 h-4 inline-block mr-1 -mt-0.5"></i> รอการรายงานตัว' ?>
        </div>

        <div class="pt-12 pb-6 space-y-6 relative z-10">
            <!-- Header Info -->
            <div class="text-center space-y-2">
                <span class="inline-flex px-3 py-1 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-full text-xs font-black tracking-widest font-mono">
                    <?= esc($reg['reg_code']) ?>
                </span>
                <h3 class="text-2xl sm:text-3xl font-black text-white"><?= esc($reg['reg_school_name']) ?></h3>
                <p class="text-slate-300 font-bold text-lg">ทีม: <?= esc($reg['reg_team_name'] ?: 'ทั่วไป (ไม่มีชื่อทีม)') ?></p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-4">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">ประเภทการแข่งขัน</span>
                    <p class="text-sm font-extrabold text-white flex items-center gap-2">
                        <i data-lucide="award" class="w-4 h-4 text-indigo-400"></i>
                        <?= esc($reg['reg_competition_type']) ?>
                    </p>
                </div>
                <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-4">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">ระดับชั้น / หมวด</span>
                    <p class="text-sm font-extrabold text-white">
                        <?= esc($reg['reg_level'] ?: '-') ?>
                    </p>
                </div>
            </div>

            <!-- Members -->
            <div class="bg-slate-900/30 border border-slate-800/60 rounded-2xl p-5">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-3">รายชื่อผู้เข้าแข่งขัน</span>
                <ul class="space-y-2">
                    <?php 
                    $members = json_decode($reg['reg_members'], true) ?: [];
                    foreach ($members as $m): 
                        $name = is_array($m) ? (($m['prefix'] ?? '') . ' ' . ($m['name'] ?? '')) : $m;
                    ?>
                        <li class="text-slate-200 text-sm flex items-start gap-2.5 font-bold">
                            <i data-lucide="user" class="w-4 h-4 text-cyan-500 mt-0.5"></i>
                            <span><?= esc(trim($name)) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-slate-800/50 text-center">
                <?php if($reg['reg_checkin_status'] == 1): ?>
                    <div class="space-y-4">
                        <div class="text-emerald-400 font-bold text-sm bg-emerald-500/10 py-3 rounded-xl border border-emerald-500/20" id="time-display">
                            รายงานตัวเมื่อ: <?= date('d/m/Y H:i', strtotime($reg['reg_checkin_time'])) ?> น.
                        </div>
                        <button type="button" onclick="processCheckin('cancel')" class="w-full px-6 py-4 rounded-2xl font-black text-sm transition-all bg-slate-800 hover:bg-rose-500/20 text-slate-300 hover:text-rose-400 border border-slate-700 hover:border-rose-500/30 flex items-center justify-center gap-2">
                            <i data-lucide="x-circle" class="w-5 h-5"></i> ยกเลิกการรายงานตัว
                        </button>
                    </div>
                <?php else: ?>
                    <button type="button" onclick="processCheckin('confirm')" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-black text-lg py-5 rounded-2xl transition-all shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-3 transform hover:scale-[1.02]">
                        <i data-lucide="check-square" class="w-6 h-6"></i> ยืนยันการรายงานตัวเข้างาน
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function processCheckin(action) {
        let confirmMsg = action === 'confirm' ? 'ยืนยันการรายงานตัวสำหรับทีมนี้ใช่หรือไม่?' : 'ต้องการยกเลิกการรายงานตัวใช่หรือไม่?';
        let confirmBtnColor = action === 'confirm' ? '#10b981' : '#ef4444';
        
        Swal.fire({
            title: 'ยืนยันการดำเนินการ',
            text: confirmMsg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: confirmBtnColor,
            cancelButtonColor: '#475569',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            background: getSwalColors().bg,
            color: getSwalColors().text,
            customClass: { popup: 'glass-card rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                
                const formData = new FormData();
                formData.append('action', action);

                fetch('<?= base_url('science-week/staff/checkin/process/' . $reg['reg_code']) ?>', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#ef4444',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'ไม่สามารถเชื่อมต่อระบบได้', 'error');
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
