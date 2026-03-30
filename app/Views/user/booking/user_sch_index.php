<?= $this->extend('user/layout/main') ?>

<?= $this->section('content') ?>

<style>
    .slot-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .slot-card:not(.disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -8px rgba(139, 92, 246, 0.25);
    }
    .slot-card.selected {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        color: white;
        border-color: transparent;
        box-shadow: 0 12px 24px -8px rgba(109, 40, 217, 0.4);
    }
    .slot-card.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
    .step-indicator .step.active {
        background: #7c3aed;
        color: white;
    }
    .step-indicator .step.done {
        background: #10b981;
        color: white;
    }
    .booking-section {
        display: none;
    }
    .booking-section.active {
        display: block;
        animation: slideIn 0.4s ease-out;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .date-btn {
        transition: all 0.2s ease;
    }
    .date-btn:hover:not(.disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px -4px rgba(139, 92, 246, 0.3);
    }
    .date-btn.selected {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        color: white;
        border-color: transparent;
    }
</style>

<section class="min-h-screen bg-gradient-to-br from-slate-50 via-violet-50/30 to-slate-50 py-12 px-4">
    <div class="max-w-3xl mx-auto">

        <!-- Scholarship Header -->
        <div class="text-center mb-10" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-violet-100 text-violet-700 rounded-full text-xs font-black uppercase tracking-widest mb-6">
                <i data-lucide="calendar-check" class="w-4 h-4"></i>
                ระบบจองคิว
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-4 leading-tight">
                <?= esc($scholarship['sch_title']) ?>
            </h1>
            <p class="text-slate-500 font-medium max-w-lg mx-auto">
                กรุณาเลือกวันที่และช่วงเวลาที่ต้องการ แล้วกรอกข้อมูลเพื่อยืนยันการจอง
            </p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator flex items-center justify-center gap-3 mb-10" data-aos="fade-up" data-aos-delay="100">
            <div id="step1-indicator" class="step active w-10 h-10 rounded-full flex items-center justify-center text-sm font-black border-2 border-violet-200 transition-all">1</div>
            <div class="w-12 h-0.5 bg-slate-200 rounded"></div>
            <div id="step2-indicator" class="step w-10 h-10 rounded-full flex items-center justify-center text-sm font-black border-2 border-slate-200 text-slate-400 transition-all">2</div>
            <div class="w-12 h-0.5 bg-slate-200 rounded"></div>
            <div id="step3-indicator" class="step w-10 h-10 rounded-full flex items-center justify-center text-sm font-black border-2 border-slate-200 text-slate-400 transition-all">3</div>
        </div>

        <!-- ====================== STEP 1: เลือกวันที่ ====================== -->
        <div id="step1" class="booking-section active" data-aos="fade-up" data-aos-delay="150">
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-100 p-8">
                <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center text-violet-600">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    เลือกวันที่
                </h3>

                <?php if (empty($available_dates)): ?>
                    <div class="py-16 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                            <i data-lucide="calendar-x2" class="w-10 h-10"></i>
                        </div>
                        <p class="text-slate-400 font-bold text-lg mb-2">ยังไม่เปิดให้จองคิว</p>
                        <p class="text-slate-400 text-sm">ทุนการศึกษานี้ยังไม่มีตารางเวลาเปิดรับจองในขณะนี้</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3" id="date-list">
                        <?php
                            $thaiDays = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
                            $thaiMonths = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
                        ?>
                        <?php foreach ($available_dates as $d): ?>
                            <?php
                                $dateObj = new DateTime($d['slot_date']);
                                $dayName = $thaiDays[(int)$dateObj->format('w')];
                                $dayNum = $dateObj->format('j');
                                $monthName = $thaiMonths[(int)$dateObj->format('n')];
                            ?>
                            <button type="button"
                                class="date-btn p-4 bg-white border-2 border-slate-100 rounded-2xl text-center cursor-pointer hover:border-violet-300"
                                data-date="<?= $d['slot_date'] ?>"
                                onclick="selectDate(this, '<?= $d['slot_date'] ?>')">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">วัน<?= $dayName ?></div>
                                <div class="text-2xl font-black text-slate-800"><?= $dayNum ?></div>
                                <div class="text-xs font-bold text-violet-600"><?= $monthName ?></div>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ====================== STEP 2: เลือกเวลา ====================== -->
        <div id="step2" class="booking-section">
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-100 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-slate-800 flex items-center gap-3">
                        <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center text-violet-600">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                        </div>
                        เลือกช่วงเวลา
                    </h3>
                    <button onclick="goToStep(1)" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors flex items-center gap-1">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> เปลี่ยนวัน
                    </button>
                </div>
                <p class="text-sm text-slate-400 font-medium mb-6" id="selected-date-label"></p>

                <div id="slots-container" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <!-- Populated by AJAX -->
                </div>

                <div id="slots-loading" class="py-16 text-center hidden">
                    <div class="inline-flex items-center gap-3 text-violet-600">
                        <svg class="animate-spin w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span class="font-bold">กำลังโหลดช่วงเวลา...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====================== STEP 3: กรอกข้อมูล & ยืนยัน ====================== -->
        <div id="step3" class="booking-section">
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-100 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-slate-800 flex items-center gap-3">
                        <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center text-violet-600">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        กรอกข้อมูลผู้จอง
                    </h3>
                    <button onclick="goToStep(2)" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors flex items-center gap-1">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> เปลี่ยนเวลา
                    </button>
                </div>

                <!-- Summary -->
                <div class="bg-violet-50 rounded-2xl p-5 mb-8 border border-violet-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-violet-200 rounded-xl flex items-center justify-center text-violet-700">
                            <i data-lucide="ticket" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-violet-500 uppercase tracking-wider">สรุปการจอง</p>
                            <p class="text-sm font-black text-violet-800" id="booking-summary-text">-</p>
                        </div>
                    </div>
                </div>

                <form id="booking-form" action="<?= base_url('booking/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="slot_id" id="input-slot-id">

                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">ชื่อ-นามสกุล <span class="text-rose-500">*</span></label>
                            <input type="text" name="bk_fullname" id="input-fullname" required placeholder="เช่น สมชาย ใจดี"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition-all placeholder:text-slate-300">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">เบอร์โทรศัพท์ <span class="text-rose-500">*</span></label>
                                <input type="tel" name="bk_phone" id="input-phone" required placeholder="08X-XXX-XXXX" maxlength="10"
                                    class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition-all placeholder:text-slate-300">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">ระดับชั้น <span class="text-rose-500">*</span></label>
                                <select name="bk_grade" id="input-grade" required
                                    class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition-all cursor-pointer">
                                    <option value="" disabled selected>เลือกความระดับชั้น...</option>
                                    <?php 
                                        $allowed = !empty($scholarship['sch_allowed_grades']) ? explode(',', $scholarship['sch_allowed_grades']) : [];
                                        $grades = [
                                            'มัธยมศึกษาตอนต้น' => 'ระดับ ม.ต้น',
                                            'มัธยมศึกษาตอนปลาย / ปวช.' => 'ระดับ ม.ปลาย / ปวช.',
                                            'ระดับปริญญาตรี / ปวส.' => 'ระดับปริญญาตรี / ปวส.'
                                        ];
                                    ?>
                                    <?php foreach($grades as $val => $label): ?>
                                        <?php if(in_array($val, $allowed)): ?>
                                            <option value="<?= $val ?>"><?= $label ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                    <?php if(empty($allowed)): ?>
                                        <option value="" disabled>ขออภัย ยังไม่เปิดรับสมัครในขณะนี้</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">สถานศึกษา <span class="text-rose-500">*</span></label>
                            <input type="text" name="bk_school" id="input-school" required placeholder="ระบุชื่อโรงเรียนหรือมหาวิทยาลัย"
                                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition-all placeholder:text-slate-300">
                        </div>
                    </div>

                    <button type="submit" id="btn-submit"
                        class="w-full mt-8 py-4 bg-gradient-to-r from-violet-600 to-purple-700 text-white rounded-2xl font-black text-lg shadow-xl shadow-violet-200 hover:shadow-2xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                        ยืนยันการจองคิว
                    </button>
                </form>
            </div>
        </div>

    </div>
</section>

<script>
    let selectedDate = '';
    let selectedSlotId = '';
    let selectedSlotLabel = '';
    let pollingInterval = null; // 🛡️ เรดาร์สแกนคิวตัดหน้า
    const schId = <?= $scholarship['sch_id'] ?>;

    function goToStep(step) {
        document.querySelectorAll('.booking-section').forEach(el => el.classList.remove('active'));
        document.getElementById('step' + step).classList.add('active');

        // Update indicators
        for (let i = 1; i <= 3; i++) {
            const ind = document.getElementById('step' + i + '-indicator');
            ind.classList.remove('active', 'done');
            if (i < step) ind.classList.add('done');
            else if (i === step) ind.classList.add('active');
        }

        // จัดการเรื่องการ Polling เช็คคิว (เด้งหลุดสะดุดล้ม)
        if (step === 3) {
            startPolling();
        } else {
            stopPolling();
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // 📡 เริ่มตรวจการโกง (ส่องหลังบ้านทุก 2 วินาที)
    function startPolling() {
        stopPolling();
        pollingInterval = setInterval(() => {
            if (!selectedSlotId) return;
            
            fetch(`<?= base_url('booking/check-slot') ?>/${selectedSlotId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.is_full) {
                        stopPolling();
                        Swal.fire({
                            icon: 'warning',
                            title: 'เสียใจด้วยครับ!',
                            text: 'มีผู้จองสล็อตเวลานี้เต็มไปเรียบร้อยแล้ว กรุณาเลือกช่วงเวลาอื่นครับ',
                            confirmButtonColor: '#7c3aed',
                            confirmButtonText: 'รับทราบ',
                            customClass: { popup: 'rounded-[2rem]' }
                        }).then(() => {
                            loadSlots(selectedDate); // โหลดสล็อตใหม่ให้เห็นว่าเต็ม
                            goToStep(2); // ดีดกลับหน้าเลือกเวลา
                        });
                    }
                });
        }, 2000); 
    }

    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    function selectDate(btn, date) {
        selectedDate = date;
        document.querySelectorAll('.date-btn').forEach(el => el.classList.remove('selected'));
        btn.classList.add('selected');

        // Format date for label
        const d = new Date(date);
        const thaiMonths = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        document.getElementById('selected-date-label').textContent = 'วันที่ ' + d.getDate() + ' ' + thaiMonths[d.getMonth() + 1] + ' ' + (d.getFullYear() + 543);

        // Load slots via AJAX
        loadSlots(date);
        goToStep(2);
    }

    function loadSlots(date) {
        const container = document.getElementById('slots-container');
        const loading = document.getElementById('slots-loading');

        container.innerHTML = '';
        loading.classList.remove('hidden');

        fetch(`<?= base_url('booking/slots') ?>/${schId}?date=${date}`)
            .then(res => res.json())
            .then(data => {
                loading.classList.add('hidden');

                if (data.status === 'success' && data.slots.length > 0) {
                    data.slots.forEach(slot => {
                        const isFull = parseInt(slot.booked_count) >= parseInt(slot.slot_max);
                        const remaining = parseInt(slot.slot_max) - parseInt(slot.booked_count);
                        const startTime = slot.slot_start_time.substring(0, 5);
                        const endTime = slot.slot_end_time.substring(0, 5);

                        const card = document.createElement('button');
                        card.type = 'button';
                        card.className = `slot-card p-4 border-2 rounded-2xl text-center ${isFull ? 'disabled border-slate-100 bg-slate-50' : 'border-slate-100 bg-white cursor-pointer hover:border-violet-300'}`;

                        if (!isFull) {
                            card.onclick = () => selectSlot(slot.slot_id, `${startTime} - ${endTime}`, card);
                        }

                        card.innerHTML = `
                            <div class="text-lg font-black ${isFull ? 'text-slate-300' : 'text-slate-800'}">${startTime}</div>
                            <div class="text-[10px] font-bold ${isFull ? 'text-slate-300' : 'text-slate-400'} uppercase tracking-wider">ถึง ${endTime}</div>
                            <div class="mt-2 text-xs font-bold ${isFull ? 'text-rose-400' : 'text-emerald-600'}">
                                ${isFull ? 'เต็ม' : `ว่าง ${remaining} ที่`}
                            </div>
                        `;

                        container.appendChild(card);
                    });

                    lucide.createIcons();
                } else {
                    container.innerHTML = '<div class="col-span-full py-10 text-center text-slate-400 font-bold">ไม่มีช่วงเวลาเปิดรับในวันนี้</div>';
                }
            })
            .catch(err => {
                loading.classList.add('hidden');
                container.innerHTML = '<div class="col-span-full py-10 text-center text-rose-500 font-bold">เกิดข้อผิดพลาดในการโหลดข้อมูล</div>';
            });
    }

    function selectSlot(slotId, label, element) {
        selectedSlotId = slotId;
        selectedSlotLabel = label;

        document.querySelectorAll('.slot-card').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');

        document.getElementById('input-slot-id').value = slotId;

        const d = new Date(selectedDate);
        const thaiMonths = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        document.getElementById('booking-summary-text').textContent = `วันที่ ${d.getDate()} ${thaiMonths[d.getMonth() + 1]} ${d.getFullYear() + 543} เวลา ${label}`;

        setTimeout(() => goToStep(3), 300);
    }

    // Form submission with AJAX
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-submit');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> กำลังบันทึก...';

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'จองคิวสำเร็จ!',
                    text: 'ระบบจะพาคุณไปหน้าใบนัดหมาย',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                const msg = data.message || Object.values(data.errors || {}).join(', ');
                Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถจองได้',
                    text: msg,
                    customClass: { popup: 'rounded-[2rem]' }
                });
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="check-circle" class="w-6 h-6"></i> ยืนยันการจองคิว';
                lucide.createIcons();
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'กรุณาลองใหม่อีกครั้ง',
                customClass: { popup: 'rounded-[2rem]' }
            });
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="check-circle" class="w-6 h-6"></i> ยืนยันการจองคิว';
            lucide.createIcons();
        });
    });
</script>

<?= $this->endSection() ?>
