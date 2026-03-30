<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>

    <div id="status-msg" class="hidden">
        <?php echo session()->getFlashdata('status'); ?>
    </div>

    <div class="max-w-3xl mx-auto py-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="w-20 h-20 bg-blue-50 rounded-[2.5rem] flex items-center justify-center text-blue-600 mx-auto mb-6 shadow-lg shadow-blue-100/50">
                <i data-lucide="settings-2" class="w-10 h-10"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-2 leading-none">ตั้งค่าระบบ</h2>
            <p class="text-xs text-slate-400 font-bold tracking-[.3em] uppercase">System Configuration & Global Rules</p>
        </div>

        <form action="<?= base_url('staff/settingsUpdate') ?>" method="post" class="space-y-8 animate-[fadeIn_0.5s_ease-out]">
            
            <!-- Global Identity Card -->
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100/50 p-10">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-50 pb-6">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 leading-none">ข้อมูลทั่วไป</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Basic Identity Information</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[.2em] text-slate-400 mb-3 ml-2 italic">ชื่อองค์กร / หน่วยงาน</label>
                        <input type="text" name="company_name" value="<?= $settings['company_name'] ?? '' ?>"
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-300 transition-all font-bold text-lg">
                        <p class="text-[10px] text-slate-300 font-bold mt-2 ml-2 uppercase">* This name appears on reports and global headers</p>
                    </div>
                </div>
            </div>

            <!-- Attendance Rules Card -->
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100/50 p-10">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-50 pb-6">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                        <i data-lucide="clock-cog" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 leading-none">กฎเกณฑ์การลงเวลา</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Working Hours & Attendance Rules</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[.2em] text-slate-400 mb-4 ml-2 flex items-center gap-2">
                            <i data-lucide="alarm-clock" class="w-3.5 h-3.5 text-emerald-500"></i> เวลาเริ่มงานปกติ
                        </label>
                        <input type="time" name="work_start_time" value="<?= $settings['work_start_time'] ?? '08:30' ?>"
                               class="w-full bg-slate-50 border border-slate-100 rounded-[2rem] px-8 py-6 text-slate-700 focus:outline-none focus:ring-4 focus:ring-emerald-50 focus:border-emerald-300 transition-all font-black text-center text-3xl">
                        <div class="mt-4 p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100/50">
                            <p class="text-[10px] text-emerald-700 font-bold italic leading-tight">* หากลงเวลาหลังจากนี้ ระบบจะถือว่า <span class="text-emerald-900 underline">"สาย"</span> โดยอัตโนมัติ</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[.2em] text-slate-400 mb-4 ml-2 flex items-center gap-2">
                            <i data-lucide="alarm-clock-off" class="w-3.5 h-3.5 text-rose-500"></i> เวลาเลิกงานปกติ
                        </label>
                        <input type="time" name="work_end_time" value="<?= $settings['work_end_time'] ?? '16:30' ?>"
                               class="w-full bg-slate-50 border border-slate-100 rounded-[2rem] px-8 py-6 text-slate-700 focus:outline-none focus:ring-4 focus:ring-rose-50 focus:border-rose-300 transition-all font-black text-center text-3xl">
                        <div class="mt-4 p-4 bg-rose-50/50 rounded-2xl border border-rose-100/50">
                            <p class="text-[10px] text-rose-700 font-bold italic leading-tight">* หากลงเวลาออกก่อนเวลานี้ ระบบจะถือว่า <span class="text-rose-900 underline">"กลับก่อน"</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Geolocation Rules Card -->
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100/50 p-10">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-50 pb-6">
                    <div class="w-10 h-10 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600">
                        <i data-lucide="globe" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 leading-none">พิกัดสถานที่ปฏิบัติราชการ</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Geo-fencing & Location Boundaries</p>
                    </div>
                </div>

                <div class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[.2em] text-slate-400 mb-3 ml-2 flex items-center gap-2 font-sarabun">
                            <i data-lucide="crosshair" class="w-3.5 h-3.5 text-blue-500"></i> รัศมีการลงเวลา (เมตร)
                        </label>
                        <div class="flex items-center gap-4">
                            <input type="range" min="50" max="2000" step="50" value="<?= $settings['max_distance'] ?? '500' ?>" 
                                   oninput="this.nextElementSibling.value = this.value; document.getElementById('dist-val').value = this.value"
                                   class="flex-1 h-3 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-blue-600">
                            <input type="number" id="dist-val" name="max_distance" value="<?= $settings['max_distance'] ?? '500' ?>"
                                   oninput="this.previousElementSibling.value = this.value"
                                   class="w-24 bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-slate-700 font-black text-center focus:outline-none focus:border-blue-400 transition-all shadow-inner">
                        </div>
                        <p class="text-[10px] text-slate-300 font-bold mt-2 ml-2 uppercase font-sarabun">* เจ้าหน้าที่ต้องอยู่ภายในรัศมีนี้จึงจะสามารถกดลงเวลาได้</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[.2em] text-slate-400 mb-3 ml-2">พิกัดสำนักงาน (Lat, Long)</label>
                        <div class="flex gap-4">
                            <div class="relative flex-1">
                                <i data-lucide="map-pin" class="absolute left-6 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-300"></i>
                                <input type="text" name="office_location" value="<?= $settings['office_location'] ?? '' ?>"
                                       class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-14 pr-6 py-4 text-slate-700 font-mono font-bold focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-inner"
                                       placeholder="เช่น 13.7563, 100.5018">
                            </div>
                            <button type="button" onclick="getCurrentLoc()" 
                                    class="bg-white border border-slate-200 text-blue-600 px-6 rounded-2xl flex items-center gap-3 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-md group">
                                <i data-lucide="locate-fixed" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                                <span class="hidden md:inline text-xs font-black uppercase whitespace-nowrap">ดึงพิกัดปัจจุบัน</span>
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-300 font-bold mt-3 ml-2 uppercase italic">* เป็นจุดศูนย์กลางในการวัดระยะห่าง (Geo-fence Center)</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 py-6 rounded-[2.5rem] font-black text-xl text-white shadow-2xl shadow-blue-200 hover:-translate-y-1.5 active:scale-[0.98] transition-all uppercase tracking-[.2em] flex items-center justify-center gap-4 group">
                    <span class="relative">
                        บันทึกการตั้งค่า
                        <span class="absolute -right-8 top-1/2 -translate-y-1/2 w-6 h-6 bg-white/20 rounded-full scale-0 group-hover:scale-100 transition-transform duration-500"></span>
                    </span>
                    <i data-lucide="save" class="w-6 h-6 group-hover:rotate-12 transition-transform"></i>
                </button>
            </div>
        </form>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function getCurrentLoc() {
        if (navigator.geolocation) {
            Swal.fire({
                title: 'กำลังดึงข้อมูลพิกัด...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            navigator.geolocation.getCurrentPosition((position) => {
                document.getElementsByName('office_location')[0].value = `${position.coords.latitude}, ${position.coords.longitude}`;
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: 'ดึงพิกัดปัจจุบันของคุณเรียบร้อยแล้ว',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[1.5rem]' }
                });
            }, (error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'ล้มเหลว!',
                    text: 'ไม่สามารถเข้าถึงตำแหน่งของคุณได้ กรุณาตรวจสอบสิทธิ์ของเบราว์เซอร์',
                    customClass: { popup: 'rounded-[1.5rem]' }
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
            customClass: {
                popup: 'rounded-[1.5rem]',
            }
        });
    }
</script>
<?= $this->endSection() ?>
