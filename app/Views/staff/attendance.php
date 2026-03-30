<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
    <div id="status-msg" class="hidden">
        <?php echo session()->getFlashdata('status'); ?>
    </div>

    <!-- Attendance Dashboard -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Action Card -->
        <div class="lg:col-span-2 space-y-8">
            <div class="p-10 bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-50 -mr-16 -mt-16"></div>
                
                <div id="location-status" class="flex items-center justify-center gap-2 mb-8 text-[11px] font-black uppercase tracking-[0.2em]">
                    <span id="gps-dot" class="w-2.5 h-2.5 rounded-full bg-slate-300 shadow-[0_0_10px_rgba(0,0,0,0.1)] transition-all duration-300"></span>
                    <span id="gps-text" class="text-slate-400">กำลังตรวจสอบตำแหน่ง (GPS)</span>
                    <a href="javascript:void(0)" onclick="getGPS(true)" class="text-blue-600 underline ml-2 hover:text-blue-500">ขอสิทธิ์ใหม่</a>
                </div>

                <div class="mb-10">
                    <p class="text-[10px] text-blue-600 font-bold uppercase tracking-[0.3em] mb-2"><?= session()->get('u_position') ?></p>
                    <h1 id="live-clock" class="text-6xl md:text-8xl font-black text-slate-900 tracking-tighter mb-4 drop-shadow-sm">
                        00:00:00
                    </h1>
                    <p id="live-date" class="text-lg text-slate-400 font-bold m-0 uppercase tracking-widest">...</p>
                </div>

                <form action="<?= base_url('staff/attendance/submit') ?>" method="post" id="attendance-form">
                    <input type="hidden" name="type" id="attendance-type">
                    <input type="hidden" name="location" id="attendance-location">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
                        <button type="button" id="btn-check-in"
                                onclick="submitAttendance('check_in')"
                                class="flex items-center justify-center gap-4 py-6 bg-gradient-to-br from-emerald-600 to-emerald-500 text-white rounded-[2rem] font-black text-xl shadow-2xl shadow-emerald-200 hover:scale-105 active:scale-95 transition-all">
                            <i data-lucide="log-in" class="w-7 h-7"></i> 🟢 ลงชื่อเข้างาน
                        </button>
                        <button type="button" id="btn-check-out"
                                onclick="submitAttendance('check_out')"
                                class="flex items-center justify-center gap-4 py-6 bg-gradient-to-br from-rose-600 to-rose-500 text-white rounded-[2rem] font-black text-xl shadow-2xl shadow-rose-200 hover:scale-105 active:scale-95 transition-all">
                            <i data-lucide="log-out" class="w-7 h-7"></i> 🔴 ลงชื่อออกงาน
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- History Side Card -->
        <div class="lg:col-span-1">
            <div class="p-8 bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50">
                <h3 class="flex items-center gap-3 text-lg font-black mb-8 text-slate-800">
                    <i data-lucide="history" class="w-6 h-6 text-blue-600"></i> ประวัติล่าสุด
                </h3>
                
                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                    <?php if(empty($history)): ?>
                        <div class="p-12 text-center text-slate-300 italic border-2 border-dashed border-slate-100 rounded-3xl">
                            ยังไม่มีรายการวันนี้
                        </div>
                    <?php else: ?>
                        <?php foreach($history as $item): ?>
                            <div class="group flex items-center justify-between p-5 bg-slate-50 border border-slate-100 rounded-[1.5rem] hover:bg-slate-100 transition-all">
                                <div class="flex flex-col gap-1 flex-1">
                                    <span class="text-[10px] uppercase font-black px-2 py-0.5 rounded-full w-max <?= $item['atd_type'] == 'check_in' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' ?>">
                                        <?= $item['atd_type'] == 'check_in' ? 'เข้างาน' : 'ออกงาน' ?>
                                    </span>
                                    <span class="text-xl font-black text-slate-900 leading-tight">
                                        <?= date('H:i:s', strtotime($item['atd_timestamp'])) ?>
                                    </span>
                                    <?php if($item['atd_location']): ?>
                                        <a href="https://www.google.com/maps?q=<?= $item['atd_location'] ?>" target="_blank" class="text-[10px] text-blue-500 font-black hover:underline flex items-center gap-1 mt-1 uppercase tracking-widest">
                                            <i data-lucide="map-pin" class="w-3 h-3"></i> คลิกดูแผนที่
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right">
                                    <p class="text-[11px] text-slate-400 font-bold"><?= thai_date($item['atd_timestamp']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function updateClock() {
        const now = new Date();
        const clock = document.getElementById('live-clock');
        const dateDisplay = document.getElementById('live-date');
        
        clock.textContent = now.toLocaleTimeString('th-TH', { hour12: false });
        dateDisplay.textContent = now.toLocaleDateString('th-TH', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    }

    let userLocation = null;

    let watchId = null;

    function getGPS(isManual = false) {
        const dot = document.getElementById('gps-dot');
        const text = document.getElementById('gps-text');
        
        // Reset Visuals
        dot.classList.remove('bg-emerald-500', 'bg-red-500', 'shadow-emerald-500/80');
        dot.classList.add('bg-slate-300', 'animate-pulse');
        text.innerHTML = '<span class="animate-pulse">กำลังตรวจสอบพิกัดล่าสุด...</span>';
        
        if (isManual) {
            Swal.fire({
                title: 'กำลังเชื่อมต่อ GPS...',
                text: 'กรุณาสังเกตแถบแจ้งเตือนของเบราว์เซอร์เพื่ออนุญาตการเข้าถึง',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });
        }

        if (navigator.geolocation) {
            if (watchId) navigator.geolocation.clearWatch(watchId);

            watchId = navigator.geolocation.watchPosition((position) => {
                if (isManual) Swal.close();
                
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const accuracy = position.coords.accuracy;
                userLocation = `${lat},${lng}`;
                
                const officePos = "<?= $office_location ?>".split(',');
                const officeLat = parseFloat(officePos[0]);
                const officeLng = parseFloat(officePos[1]);
                const maxDist = <?= $max_distance ?>;
                
                const dist = getDistance(lat, lng, officeLat, officeLng);
                
                dot.classList.remove('bg-red-500', 'shadow-red-500/50', 'bg-slate-300', 'animate-pulse');
                dot.classList.add('bg-emerald-500', 'shadow-emerald-500/80');
                
                let accuracyColor = accuracy > 100 ? 'text-rose-400' : (accuracy > 30 ? 'text-amber-400' : 'text-emerald-400');
                let accuracyText = `ความแม่นยำ: <span class="${accuracyColor} font-bold">±${accuracy.toFixed(0)} ม.</span>`;

                if (dist > maxDist) {
                    text.innerHTML = `พิกัด <a href="https://www.google.com/maps?q=${userLocation}" target="_blank" class="text-blue-600 font-black underline decoration-dotted hover:text-blue-700 transition-colors uppercase tracking-widest text-[10px]">คลิกดูพิกัด</a><br>ระยะห่าง: <span class="text-rose-400 font-bold">${dist.toFixed(0)} ม.</span> (เกินรัศมี ${maxDist} ม.)<br>${accuracyText}`;
                    text.classList.remove('text-rose-400');
                    text.classList.add('text-rose-400');
                    userLocation = "too_far"; 
                } else {
                    text.innerHTML = `พิกัด <a href="https://www.google.com/maps?q=${userLocation}" target="_blank" class="text-blue-600 font-black underline decoration-dotted hover:text-blue-700 transition-colors uppercase tracking-widest text-[10px]">คลิกดูพิกัด</a><br>ระยะห่าง: <span class="text-emerald-400 font-bold">${dist.toFixed(0)} ม.</span> (อยู่ในรัศมี)<br>${accuracyText}`;
                    text.classList.remove('text-rose-400');
                    text.classList.add('text-emerald-400');
                }
                
                document.getElementById('attendance-location').value = userLocation;
            }, (error) => {
                if (isManual) Swal.close();
                dot.classList.remove('animate-pulse');
                dot.classList.add('bg-red-500');
                
                let errorMsg = 'กรุณาอนุญาตพิกัดก่อนลงเวลา';
                if (error.code === 1) { // PERMISSION_DENIED
                    errorMsg = 'ถูกปฏิเสธการเข้าถึง GPS โปรดแก้ไขการตั้งค่าในเบราว์เซอร์';
                    if (isManual) {
                        Swal.fire({
                            icon: 'error',
                            title: 'เข้าถึงตำแหน่งไม่ได้',
                            html: '<p class="text-sm">คุณได้ปิดกั้นการเข้าถึงพิกัดไว้<br><b>วิธีแก้ไข:</b> คลิกไอคอนรูปกุญแจ 🔒 ที่แถบที่อยู่เว็บด้านบน แล้วกดเปิด "ตำแหน่ง" (Location)</p>',
                            confirmButtonText: 'เข้าใจแล้ว',
                            confirmButtonColor: '#3b82f6'
                        });
                    }
                } else if (error.code === 3) {
                    errorMsg = 'ค้นหาตำแหน่งนานเกินไป กรุณาลองใหม่';
                }
                
                text.innerHTML = `<span class="text-rose-500">${errorMsg}</span>`;
                console.error("GPS Error:", error);
            }, {
                enableHighAccuracy: true,
                timeout: 20000,
                maximumAge: 0
            });
        }
    }

    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // meters
        const p1 = lat1 * Math.PI/180;
        const p2 = lat2 * Math.PI/180;
        const dPhi = (lat2-lat1) * Math.PI/180;
        const dLam = (lon2-lon1) * Math.PI/180;
        const a = Math.sin(dPhi/2) * Math.sin(dPhi/2) +
                  Math.cos(p1) * Math.cos(p2) *
                  Math.sin(dLam/2) * Math.sin(dLam/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    getGPS();
    setInterval(updateClock, 1000);
    updateClock();

    const statusMsg = document.getElementById('status-msg').textContent.trim();
    if (statusMsg) {
        Swal.fire({
            icon: 'success',
            title: 'บันทึกสำเร็จ!',
            text: statusMsg,
            timer: 2000,
            showConfirmButton: false,
            background: '#1e293b',
            color: '#f8fafc',
            iconColor: '#10b981'
        });
    }

    function submitAttendance(type) {
        if (!userLocation || userLocation === "too_far") {
            const isTooFar = userLocation === "too_far";
            
            if (isTooFar) {
                Swal.fire({
                    icon: 'warning',
                    title: 'คุณอยู่นอกพื้นที่!',
                    text: `คุณต้องอยู่ภายในรัศมี <?= $max_distance ?> เมตร จากสำนักงาน (ขณะนี้ระบบวัดระยะได้เกินที่กำหนด)`,
                    confirmButtonText: 'รับทราบ',
                    confirmButtonColor: '#3b82f6',
                    background: '#1e293b',
                    color: '#f8fafc'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ไม่พบพิกัดตำแหน่ง!',
                    html: `
                        <div style="text-align: left; font-size: 0.9rem; line-height: 1.6;">
                            <p class="mb-2">กรุณาทำตามขั้นตอนดังนี้เพื่อลงเวลา:</p>
                            <ol class="list-decimal ml-6 space-y-1">
                                <li>สังเกตแถบแจ้งเตือนของเบราว์เซอร์</li>
                                <li>กดปุ่ม <b>"อนุญาต" (Allow)</b> เพื่อแชร์ตำแหน่ง</li>
                                <li>หากยังไม่ได้ ให้กดปุ่ม <b class="text-blue-400">"ขอสิทธิ์ใหม่"</b> ด้านบน</li>
                            </ol>
                        </div>
                    `,
                    confirmButtonText: 'ลองอีกครั้ง',
                    confirmButtonColor: '#3b82f6',
                    background: '#1e293b',
                    color: '#f8fafc'
                }).then(() => {
                    getGPS();
                });
            }
            return;
        }
        document.getElementById('attendance-type').value = type;
        document.getElementById('attendance-form').submit();
    }
</script>
<?= $this->endSection() ?>
