<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <a href="<?= base_url('staff/leave') ?>" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-blue-600 font-medium text-sm mb-3 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปหน้าการลางาน
    </a>
    <h1 class="text-2xl font-black text-slate-800 tracking-tight">เขียนใบลาออนไลน์</h1>
    <p class="text-slate-500 text-sm mt-1">กรอกข้อมูลการลาให้ครบถ้วน ตามระเบียบสำนักนายกรัฐมนตรีฯ</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden max-w-4xl">
    <form action="<?= base_url('staff/leave/store') ?>" method="POST" class="p-8">
        <?= csrf_field() ?>
        
        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2 pb-4 border-b border-slate-100">
            <i data-lucide="file-text" class="w-5 h-5 text-blue-500"></i> ข้อมูลการลา
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">ประเภทการลา <span class="text-rose-500">*</span></label>
                <select name="leave_type" id="leave_type" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3 px-4 font-medium transition-all">
                    <option value="" disabled selected>-- เลือกประเภทการลา --</option>
                    <option value="sick">ลากิจส่วนตัว</option>
                    <option value="personal">ลากิจส่วนตัว</option>
                    <option value="maternity">ลาคลอดบุตร</option>
                    <option value="paternity">ลาไปช่วยเหลือภริยาที่คลอดบุตร</option>
                    <option value="vacation">ลาพักผ่อน</option>
                    <option value="ordination">ลาอุปสมบท / ไปประกอบพิธีฮัจย์</option>
                    <option value="military">ลาเข้ารับการตรวจเลือกหรือเข้ารับการเตรียมพล</option>
                    <option value="study">ลาไปศึกษา ฝึกอบรม ปฏิบัติการวิจัย หรือดูงาน</option>
                    <option value="international_org">ลาไปปฏิบัติงานในองค์การระหว่างประเทศ</option>
                    <option value="spouse_follow">ลาติดตามคู่สมรส</option>
                    <option value="rehabilitation">ลาไปฟื้นฟูสมรรถภาพด้านอาชีพ</option>
                </select>
                <script>
                    // ลบ ลากิจส่วนตัว ตัวแรกที่ซ้ำ และเพิ่ม ลาป่วย
                    document.querySelector('option[value="sick"]').textContent = "ลาป่วย";
                </script>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">เนื่องจาก (ระบุสาเหตุ) <span class="text-rose-500">*</span></label>
                <input type="text" name="leave_reason" required placeholder="เช่น ป่วยเป็นไข้หวัด, ไปติดต่อสถานที่ราชการ..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3 px-4 transition-all">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">ตั้งแต่วันที่ <span class="text-rose-500">*</span></label>
                <input type="text" name="leave_from_date" id="leave_from_date" required placeholder="วว/ดด/ปปปป" class="datepicker w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3 px-4 transition-all">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">ถึงวันที่ <span class="text-rose-500">*</span></label>
                <input type="text" name="leave_to_date" id="leave_to_date" required placeholder="วว/ดด/ปปปป" class="datepicker w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3 px-4 transition-all">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">มีกำหนดกี่วัน (วันทำการ) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input type="number" step="0.5" min="0.5" name="leave_days" id="leave_days" required placeholder="1.0" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3 px-4 transition-all pr-12">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-sm">วัน</span>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">ในระหว่างลาจะติดต่อข้าพเจ้าได้ที่</label>
                <textarea name="leave_contact" rows="2" placeholder="ระบุสถานที่ หรือเบอร์โทรศัพท์" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3 px-4 transition-all"></textarea>
            </div>
            
            <div class="md:col-span-2 hidden" id="substitute_container">
                <label class="block text-sm font-bold text-slate-700 mb-2">มอบหมายงานให้ผู้ใดแทน (เฉพาะลาพักผ่อน) <span class="text-rose-500">*</span></label>
                <select name="leave_substitute_id" id="leave_substitute_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3 px-4 font-medium transition-all">
                    <option value="" disabled selected>-- เลือกผู้รับมอบงาน --</option>
                    <?php if(!empty($users)): ?>
                        <?php foreach($users as $u): ?>
                            <option value="<?= $u['u_id'] ?>"><?= esc($u['u_prefix'] . $u['u_fullname']) ?> - <?= esc($u['u_position']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <p class="text-xs text-slate-500 mt-1"><i data-lucide="info" class="w-3 h-3 inline"></i> ข้อมูลนี้จะไปปรากฏในส่วนของผู้รับมอบงาน ในใบลาพักผ่อน</p>
            </div>
        </div>

        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2 pb-4 border-b border-slate-100">
            <i data-lucide="history" class="w-5 h-5 text-indigo-500"></i> ประวัติการลาครั้งสุดท้าย (ถ้ามี)
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">ตั้งแต่วันที่</label>
                <input type="text" name="leave_last_from_date" value="<?= $lastLeave ? $lastLeave['leave_from_date'] : '' ?>" placeholder="วว/ดด/ปปปป" class="datepicker w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3 px-4 transition-all">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">ถึงวันที่</label>
                <input type="text" name="leave_last_to_date" value="<?= $lastLeave ? $lastLeave['leave_to_date'] : '' ?>" placeholder="วว/ดด/ปปปป" class="datepicker w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3 px-4 transition-all">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">จำนวนวัน</label>
                <div class="relative">
                    <input type="number" step="0.5" min="0" name="leave_last_days" value="<?= $lastLeave ? floatval($lastLeave['leave_days']) : '' ?>" placeholder="0.0" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3 px-4 transition-all pr-12">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-sm">วัน</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 bg-blue-50 p-4 rounded-xl border border-blue-100 mb-8">
            <i data-lucide="info" class="w-6 h-6 text-blue-600 shrink-0"></i>
            <p class="text-sm text-blue-800">โปรดตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก ระบบจะสร้างเอกสาร Word อัตโนมัติหลังจากนี้เพื่อนำไปพิมพ์เพื่อลงนาม</p>
        </div>

        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
            <a href="<?= base_url('staff/leave') ?>" class="px-6 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-slate-100 transition-colors">ยกเลิก</a>
            <button type="submit" class="px-8 py-2.5 rounded-xl font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-200 transition-all flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> บันทึกและส่งคำขอ
            </button>
        </div>
    </form>
</div>

<?= $this->section('scripts') ?>
<script>
    // ตั้งค่า Flatpickr ภาษาไทย + พ.ศ.
    const dateConfig = {
        locale: "th",
        dateFormat: "Y-m-d", // ส่งค่าไป DB เป็น ค.ศ.
        altInput: true,
        altFormat: "d/m/Y", // แสดงผลเบื้องต้น
        onOpen: function(selectedDates, dateStr, instance) {
            // เมื่อเปิดปฏิทิน ให้เปลี่ยนปีในดรอปดาวน์เป็น พ.ศ. (ถ้ามี)
            // Flatpickr ปกติจะแสดง ค.ศ. เราจะใช้ Hook ปรับแต่งตอนแสดงผล
        },
        onReady: function(selectedDates, dateStr, instance) {
            // ฟังก์ชั่นช่วยแปลงปีใน Alt Insight เป็น พ.ศ.
            const adjustYear = () => {
                if (instance.altInput) {
                    const value = instance.altInput.value;
                    if (value && value.includes('/')) {
                        const parts = value.split('/');
                        if (parts.length === 3 && parts[2].length === 4 && parseInt(parts[2]) < 2500) {
                            parts[2] = parseInt(parts[2]) + 543;
                            instance.altInput.value = parts.join('/');
                        }
                    }
                }
            };
            instance.altInput.addEventListener('blur', adjustYear);
            // ปรับปีเมื่อเลือกวันที่
            instance.config.onChange.push(function(selDates, dateStr, inst) {
                setTimeout(adjustYear, 10);
                calculateDays();
            });
            adjustYear(); // ปรับทันทีถ้ามีค่าเดิม
        }
    };

    flatpickr(".datepicker", dateConfig);
    
    function calculateDays() {
        const fromDate = document.getElementById('leave_from_date').value;
        const toDate = document.getElementById('leave_to_date').value;
        
        if(fromDate && toDate) {
            const start = new Date(fromDate);
            const end = new Date(toDate);
            
            // คำนวณวันเผื่อคร่าวๆ (ยังไม่หักวันหยุดเสาร์อาทิตย์นักขัตฤกษ์)
            let diffTime = Math.abs(end - start);
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // รวมวันแรก
            
            if(diffDays > 0) {
                document.getElementById('leave_days').value = diffDays;
            }
        }
    }

    // Toggle ช่องผู้รับมอบงาน เมื่อเลือกการลาพักผ่อน
    const leaveTypeSelect = document.getElementById('leave_type');
    const substituteContainer = document.getElementById('substitute_container');
    const substituteSelect = document.getElementById('leave_substitute_id');

    leaveTypeSelect.addEventListener('change', function() {
        if (this.value === 'vacation') {
            substituteContainer.classList.remove('hidden');
            substituteSelect.required = true;
        } else {
            substituteContainer.classList.add('hidden');
            substituteSelect.required = false;
            substituteSelect.value = ""; // เคลียร์ค่าคืน
        }
    });

    // เรียกตอนโหลดหนัาเผื่อมีค่าเก่าจาก old()
    if (leaveTypeSelect.value === 'vacation') {
        substituteContainer.classList.remove('hidden');
        substituteSelect.required = true;
    }
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
