<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

    <div id="status-msg" class="hidden">
        <?php echo session()->getFlashdata('status'); ?>
    </div>

    <div class="max-w-2xl mx-auto space-y-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400 mb-2 leading-none uppercase tracking-tighter">
                ตั้งค่าระบบ
            </h2>
            <p class="text-xs text-white/30 tracking-[.5em] uppercase">System Configuration</p>
        </div>

        <form action="<?= base_url('admin/settingsUpdate') ?>" method="post" class="space-y-6 animate-[fadeIn_0.5s_ease-out]">
            
            <div class="glass-card shadow-2xl">
                <div class="space-y-8">
                    <!-- Company Name -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[.2em] text-white/40 mb-3 ml-2">ชื่อบริษัท / หน่วยงาน</label>
                        <input type="text" name="company_name" value="<?= $settings['company_name'] ?? '' ?>"
                               class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-blue-500 focus:bg-white/10 transition-all font-medium text-lg">
                    </div>

                    <!-- Work Times Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[.2em] text-white/40 mb-3 ml-2 flex items-center gap-2">
                                <i data-lucide="clock" class="w-3 h-3 text-emerald-400"></i> เวลาเริ่มงานปกติ
                            </label>
                            <input type="time" name="work_start_time" value="<?= $settings['work_start_time'] ?? '08:30' ?>"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-blue-500 focus:bg-white/10 transition-all font-bold text-center text-xl">
                            <p class="text-[10px] text-white/20 mt-2 ml-2 italic">* รายการที่เลทกว่านี้จะ "สาย"</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[.2em] text-white/40 mb-3 ml-2 flex items-center gap-2">
                                <i data-lucide="clock-9" class="w-3 h-3 text-rose-400"></i> เวลาเลิกงานปกติ
                            </label>
                            <input type="time" name="work_end_time" value="<?= $settings['work_end_time'] ?? '16:30' ?>"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-blue-500 focus:bg-white/10 transition-all font-bold text-center text-xl">
                            <p class="text-[10px] text-white/20 mt-2 ml-2 italic">* หากลงเวลาออกก่อนจะ "กลับก่อน"</p>
                        </div>
                    </div>

                    <!-- Distance Setting -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[.2em] text-white/40 mb-3 ml-2 flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-3 h-3 text-blue-400"></i> ระยะห่างสูงสุดจากสำนักงาน (เมตร)
                        </label>
                        <input type="number" name="max_distance" value="<?= $settings['max_distance'] ?? '500' ?>"
                               class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-blue-500 focus:bg-white/10 transition-all font-bold text-lg">
                        <p class="text-[10px] text-white/20 mt-2 ml-2 italic">* รัศมีการลงเวลาที่อนุญาต (ปัจจุบันรองรับการตรวจสอบผ่าน GPS)</p>
                    </div>

                    <!-- Office Location -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[.2em] text-white/40 mb-3 ml-2">พิกัดสำนักงานกลาง (Lat, Long)</label>
                        <div class="flex gap-3">
                            <input type="text" name="office_location" value="<?= $settings['office_location'] ?? '' ?>"
                                   class="flex-1 bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-mono focus:outline-none focus:border-blue-500 focus:bg-white/10 transition-all"
                                   placeholder="13.7563,100.5018">
                            <button type="button" onclick="getCurrentLoc()" class="bg-blue-600/10 text-blue-400 p-4 rounded-2xl border border-blue-500/20 hover:bg-blue-600 hover:text-white transition-all">
                                <i data-lucide="locate-fixed"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-br from-blue-600/80 to-indigo-600/80 hover:from-blue-600 hover:to-indigo-600 py-6 rounded-[2rem] font-black text-xl shadow-2xl shadow-blue-900/40 hover:-translate-y-1 transition-all uppercase tracking-[.2em]">
                บันทึกการตั้งค่า
                <i data-lucide="check-circle-2" class="inline w-6 h-6 ml-2"></i>
            </button>
        </form>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function getCurrentLoc() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                document.getElementsByName('office_location')[0].value = `${position.coords.latitude},${position.coords.longitude}`;
                Swal.fire({
                    icon: 'success',
                    title: 'ดึงพิกัดปัจจุบันสำเร็จ!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    background: '#1e293b',
                    color: '#f8fafc'
                });
            });
        }
    }

    const statusMsg = document.getElementById('status-msg').textContent.trim();
    if (statusMsg) {
        Swal.fire({
            icon: 'success',
            title: 'บันทึกสำเร็จ!',
            text: statusMsg,
            timer: 2000,
            showConfirmButton: false,
            background: '#1e293b',
            color: '#f8fafc'
        });
    }
</script>
<?= $this->endSection() ?>
