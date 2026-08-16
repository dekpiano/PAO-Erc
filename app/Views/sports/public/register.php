<?= $this->extend('sports/public/layout/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen pb-20 pt-4">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Header Info Card -->
        <div
            class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-emerald-900/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <a href="<?= base_url('sports') ?>"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-100 hover:text-white transition-colors mb-1">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>กลับหน้ารายการกีฬา</span>
                </a>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-extrabold uppercase">
                        <?= esc($category['sport_name']) ?>
                    </span>
                    <span class="px-3 py-1 bg-amber-400 text-slate-950 rounded-full text-xs font-black">
                        <?= $category['category_gender'] === 'female' ? 'หญิง' : ($category['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">ลงทะเบียนทีม:
                    <?= esc($category['category_name']) ?>
                </h1>
                <p class="text-emerald-100 text-xs sm:text-sm">กรอกข้อมูลโรงเรียน/สังกัด ผู้ประสานงาน
                    และรายชื่อนักกีฬาประจำทีม</p>
            </div>

            <!-- Quota Badge -->
            <?php
            $dispAgeMin = (int) ($category['age_min'] ?? 0);
            $dispAgeMax = (int) ($category['age_max'] ?? 99);
            if (($dispAgeMax === 99 || $dispAgeMax === 0) && preg_match('/\d+/', $category['category_name'], $mAge)) {
                $dispAgeMax = (int) $mAge[0];
            }
            ?>
            <div
                class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-xs space-y-1.5 min-w-[240px]">
                <div class="font-bold text-amber-300 flex items-center gap-1.5">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <span>เงื่อนไขและคุณสมบัติรุ่นนี้</span>
                </div>
                <div class="text-emerald-100">
                    เพศที่รับ:
                    <strong><?= $category['category_gender'] === 'female' ? 'หญิง' : ($category['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?></strong>
                    |
                    ประเภท:
                    <strong><?= $category['category_type'] === 'team' ? 'ทีม' : ($category['category_type'] === 'pair' ? 'คู่' : 'เดี่ยว') ?></strong>
                </div>
                <div class="text-emerald-100">
                    เกณฑ์อายุ: <strong class="text-amber-300 font-black">
                        <?php if ($dispAgeMin > 0 && $dispAgeMax < 99): ?>
                            <?= $dispAgeMin ?> - <?= $dispAgeMax ?> ปี
                        <?php elseif ($dispAgeMax < 99): ?>
                            อายุไม่เกิน <?= $dispAgeMax ?> ปี (รุ่น <?= esc($category['category_name']) ?>)
                        <?php elseif ($dispAgeMin > 0): ?>
                            ตั้งแต่ <?= $dispAgeMin ?> ปีขึ้นไป
                        <?php else: ?>
                            ไม่จำกัดอายุ (ประชาชนทั่วไป)
                        <?php endif; ?>
                    </strong>
                </div>
                <div class="text-emerald-100">ผู้เล่น: <strong><?= $category['min_players'] ?> -
                        <?= $category['max_players'] ?></strong> คน | โค้ช: <strong><?= $category['min_coaches'] ?> -
                        <?= $category['max_coaches'] ?></strong> คน</div>
                <?php if (!empty($category['rules_detail'])): ?>
                    <div class="pt-1.5 mt-1.5 border-t border-white/10 text-[11px] text-emerald-200 leading-snug">
                        📝 <strong>กติกา:</strong> <?= esc($category['rules_detail']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Registration Form -->
        <form action="<?= base_url('sports/register/submit') ?>" method="POST" id="sportsRegForm" class="space-y-8">
            <?= csrf_field() ?>
            <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">

            <!-- 1. School / Institution Info -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div
                        class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black">
                        1
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900">ข้อมูลโรงเรียน / สังกัด / หน่วยงาน</h2>
                        <p class="text-xs text-slate-400">ระบุชื่อโรงเรียนและที่ตั้งของทีมที่ส่งเข้าร่วมแข่งขัน</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ชื่อโรงเรียน / สังกัด <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="school_name" required placeholder="เช่น โรงเรียน อบจ. นครสวรรค์"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ชื่อทีมแข่งขัน (ถ้ามี)</label>
                        <input type="text" name="team_name"
                            placeholder="เช่น ทีม A / ทีมสิงห์แดง (หากไม่มีจะใช้ชื่อโรงเรียน)"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">จังหวัด <span
                                class="text-rose-500">*</span></label>
                        <select name="province" id="province_select" required
                            class="w-full text-xs font-medium cursor-pointer">
                            <option value="">-- เลือกจังหวัด --</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">อำเภอ <span
                                class="text-rose-500">*</span></label>
                        <select name="district" id="district_select" required
                            class="w-full text-xs font-medium cursor-pointer">
                            <option value="">-- เลือกอำเภอ --</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 2. Team Coordinator / Coach Contact -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div
                        class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black">
                        2
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900">ข้อมูลผู้ประสานงาน / ผู้ควบคุมทีม</h2>
                        <p class="text-xs text-slate-400">สำหรับเจ้าหน้าที่ติดต่อประสานงาน แจ้งกำหนดการ และผลการตรวจสอบ
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ชื่อ - นามสกุล ผู้ประสานงาน <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="contact_name" required placeholder="เช่น นายสมชาย ใจดี"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">เบอร์โทรศัพท์ติดต่อ <span
                                class="text-rose-500">*</span></label>
                        <input type="tel" name="contact_phone" required placeholder="08x-xxxxxxx"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Line ID (ถ้ามี)</label>
                        <input type="text" name="contact_line_id" placeholder="Line ID"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs font-medium">
                    </div>
                </div>
            </div>

            <!-- 3. Athletes List Section -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                            3
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                                <span>รายชื่อนักกีฬา</span>
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800">
                                    กำหนด <?= $category['min_players'] ?> - <?= $category['max_players'] ?> คน
                                </span>
                            </h2>
                            <p class="text-xs text-slate-400">กรอกข้อมูลนักกีฬาประจำทีม ตรวจสอบคำนำหน้า ชื่อ นามสกุล และระดับชั้นเรียน</p>
                        </div>
                    </div>

                    <div>
                        <button type="button" onclick="addMemberRow('athlete')"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm shadow-emerald-200 transition-all cursor-pointer">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            <span>+ เพิ่มนักกีฬา</span>
                        </button>
                    </div>
                </div>

                <!-- Athletes Container -->
                <div id="athletesContainer" class="space-y-4">
                    <!-- Athlete items will be added here dynamically -->
                </div>
            </div>

            <!-- 4. Coaches & Staff List Section -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-black">
                            4
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                                <span>รายชื่อผู้ฝึกสอนและเจ้าหน้าที่ประจำทีม</span>
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-purple-100 text-purple-800">
                                    กำหนด <?= $category['min_coaches'] ?> - <?= $category['max_coaches'] ?> คน
                                </span>
                            </h2>
                            <p class="text-xs text-slate-400">ระบุรายชื่อผู้จัดการทีม, หัวหน้าผู้ฝึกสอน,
                                ผู้ช่วยผู้ฝึกสอน</p>
                        </div>
                    </div>

                    <div>
                        <button type="button" onclick="addMemberRow('coach')"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm shadow-purple-200 transition-all cursor-pointer">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                            <span>+ เพิ่มผู้ฝึกสอน / เจ้าหน้าที่</span>
                        </button>
                    </div>
                </div>

                <!-- Coaches Container -->
                <div id="coachesContainer" class="space-y-4">
                    <!-- Coach items will be added here dynamically -->
                </div>
            </div>

            <!-- Submit Button Card -->
            <div
                class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="text-xs text-slate-500">
                    <p class="font-bold text-slate-700">📌 ตรวจสอบความถูกต้องก่อนกดส่งใบสมัคร</p>
                    <p>เมื่อลงทะเบียนสำเร็จ ระบบจะออกรหัสใบสมัคร (Team Code) สำหรับติดตามสถานะ</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="<?= base_url('sports') ?>"
                        class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-xs transition-colors">
                        ยกเลิก
                    </a>
                    <button type="submit"
                        class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-extrabold text-sm shadow-lg shadow-emerald-200 transition-all cursor-pointer">
                        ยืนยันการลงทะเบียน
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    let memberIndex = 0;
    const categoryName = <?= json_encode($category['category_name'] ?? '') ?>;
    const minPlayers = <?= (int) ($category['min_players'] ?? 1) ?>;
    const maxPlayers = <?= (int) ($category['max_players'] ?? 20) ?>;
    const minCoaches = <?= (int) ($category['min_coaches'] ?? 1) ?>;
    const maxCoaches = <?= (int) ($category['max_coaches'] ?? 5) ?>;

    // ดึงตัวเลขอายุจาก category_name อัตโนมัติ (เช่น '15', '12 ปี', 'U-15', 'รุ่นไม่เกิน 15 ปี')
    let parsedAgeMax = <?= (int) ($category['age_max'] ?? 99) ?>;
    let parsedAgeMin = <?= (int) ($category['age_min'] ?? 0) ?>;

    // ถ้า age_max เป็นค่าเริ่มต้น (99) ให้สกัดตัวเลขจากชื่อรุ่น category_name ทันที
    const matchedNumber = categoryName.match(/\d+/);
    if (matchedNumber && (parsedAgeMax === 99 || parsedAgeMax === 0)) {
        parsedAgeMax = parseInt(matchedNumber[0]);
    }

    const ageMin = parsedAgeMin;
    const ageMax = parsedAgeMax;
    const compYear = <?= (int) ($category['comp_year'] ?? 2569) ?>;

    document.addEventListener('DOMContentLoaded', function () {
        // Automatically generate initial rows based on minimum requirements
        for (let i = 0; i < Math.max(1, minPlayers); i++) {
            addMemberRow('athlete');
        }
        for (let i = 0; i < Math.max(1, minCoaches); i++) {
            addMemberRow('coach');
        }
    });

    // ฟังก์ชันคำนวณอายุอัตโนมัติจากวันเกิด + ตรวจสอบเกณฑ์อายุตามรุ่นการแข่งขัน
    function calculateAndValidateAge(dateInputVal, idx, isAthlete) {
        if (!dateInputVal) return;

        let birthDate = null;
        if (dateInputVal instanceof Date) {
            birthDate = dateInputVal;
        } else if (typeof dateInputVal === 'string') {
            // Check if format is DD/MM/YYYY (BE)
            if (dateInputVal.includes('/')) {
                const parts = dateInputVal.split('/');
                if (parts.length === 3) {
                    let d = parseInt(parts[0]);
                    let m = parseInt(parts[1]) - 1;
                    let y = parseInt(parts[2]);
                    if (y > 2400) y -= 543; // Convert BE to CE for Date
                    birthDate = new Date(y, m, d);
                }
            } else {
                birthDate = new Date(dateInputVal.replace(/-/g, '/'));
            }
        }

        if (!birthDate || isNaN(birthDate.getTime())) return;

        // คำนวณอายุโดยเทียบกับวันปัจจุบัน
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        if (age < 0) age = 0;

        // อัปเดตค่าลงในช่องอายุทันที
        const ageInput = document.getElementById(`age_input_${idx}`);
        const memberRow = document.getElementById(`member_row_${idx}`);
        if (ageInput) {
            ageInput.value = age;
        }

        // ตรวจสอบเฉพาะ "นักกีฬา" ว่าอายุอยู่ในเกณฑ์ที่รุ่นกำหนดหรือไม่
        if (isAthlete) {
            let isEligible = true;
            let title = '';
            let message = '';
            let icon = 'success';
            let color = '#059669';

            // กำหนดอายุขั้นต่ำที่สมเหตุสมผลสำหรับนักกีฬา (อย่างน้อย 5 ปี หรือตามที่รุ่นระบุ)
            const effectiveAgeMin = ageMin > 0 ? ageMin : 5;

            if (age <= 0) {
                isEligible = false;
                icon = 'error';
                color = '#e11d48';
                title = '❌ ไม่สามารถสมัครได้ (วันเกิดไม่ถูกต้อง)';
                message = `คำนวณอายุได้ <b>0 ปี</b> กรุณาเลือกปีเกิด (พ.ศ.) ให้ถูกต้อง (นักกีฬาต้องมีอายุอย่างน้อย ${effectiveAgeMin} ปี)`;
            } else if (age < effectiveAgeMin) {
                isEligible = false;
                icon = 'warning';
                color = '#d97706';
                title = '❌ ไม่สามารถสมัครรุ่นนี้ได้ (อายุน้อยกว่าเกณฑ์)';
                message = `นักกีฬาอายุ <b>${age} ปี</b> น้อยกว่าเกณฑ์ขั้นต่ำของการแข่งขัน (กำหนดอายุระหว่าง <b>${effectiveAgeMin} - ${ageMax} ปี</b>)`;
            } else if (ageMax < 99 && age > ageMax) {
                isEligible = false;
                icon = 'error';
                color = '#e11d48';
                title = '❌ ไม่สามารถสมัครรุ่นนี้ได้ (อายุเกินเกณฑ์)';
                message = `นักกีฬาอายุ <b>${age} ปี</b> เกินเกณฑ์สูงสุดของรุ่น ${categoryName} (กำหนดอายุไม่เกิน <b>${ageMax} ปี</b>)`;
            } else {
                title = '✅ ผ่านเกณฑ์คุณสมบัติ';
                message = `นักกีฬาอายุ <b>${age} ปี</b> สามารถลงสมัครแข่งขันในรุ่น ${categoryName} ได้`;
            }

            if (memberRow) {
                if (isEligible) {
                    memberRow.classList.remove('border-rose-300', 'bg-rose-50/50');
                    memberRow.classList.add('border-emerald-300', 'bg-emerald-50/30');
                } else {
                    memberRow.classList.remove('border-emerald-300', 'bg-emerald-50/30');
                    memberRow.classList.add('border-rose-300', 'bg-rose-50/50');
                }
            }

            Swal.fire({
                icon: icon,
                title: title,
                html: message,
                confirmButtonColor: color,
                timer: isEligible ? 2500 : undefined,
                showConfirmButton: !isEligible
            });
        }
    }

    function addMemberRow(type = 'athlete') {
        const isAthlete = type === 'athlete';
        const container = document.getElementById(isAthlete ? 'athletesContainer' : 'coachesContainer');

        // 1. ตรวจสอบจำนวนนักกีฬาและโค้ชว่าเกินโควตาสูงสุดที่กำหนดหรือไม่
        const currentCount = container.children.length;
        if (isAthlete && maxPlayers > 0 && currentCount >= maxPlayers) {
            Swal.fire({
                icon: 'warning',
                title: 'เกินจำนวนนักกีฬาที่กำหนด',
                text: `รุ่นนี้อนุญาตให้ลงทะเบียนนักกีฬาได้สูงสุด ${maxPlayers} คน`,
                confirmButtonColor: '#059669'
            });
            return;
        }

        if (!isAthlete && maxCoaches > 0 && currentCount >= maxCoaches) {
            Swal.fire({
                icon: 'warning',
                title: 'เกินจำนวนผู้ฝึกสอนที่กำหนด',
                text: `รุ่นนี้อนุญาตให้ลงทะเบียนผู้ฝึกสอน/เจ้าหน้าที่ได้สูงสุด ${maxCoaches} คน`,
                confirmButtonColor: '#7c3aed'
            });
            return;
        }

        const idx = memberIndex++;
        const displayNo = currentCount + 1;

        const rowDiv = document.createElement('div');
        rowDiv.className = `p-4 sm:p-5 rounded-2xl border transition-all ${isAthlete ? 'bg-slate-50/70 border-slate-200' : 'bg-purple-50/40 border-purple-200'}`;
        rowDiv.id = `member_row_${idx}`;

        if (isAthlete) {
            rowDiv.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold bg-emerald-100 text-emerald-800">
                            🏃 นักกีฬาคนที่ ${displayNo}
                        </span>
                        <input type="hidden" name="members[${idx}][member_type]" value="athlete">
                    </div>
                    <button type="button" onclick="removeMemberRow(${idx})" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" title="ลบรายการนี้">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">คำนำหน้า <span class="text-rose-500">*</span></label>
                        <select name="members[${idx}][prefix]" required class="select2-init w-full text-xs font-medium bg-white">
                            <option value="เด็กชาย">เด็กชาย</option>
                            <option value="เด็กหญิง">เด็กหญิง</option>
                            <option value="นาย">นาย</option>
                            <option value="นางสาว">นางสาว</option>
                        </select>
                    </div>

                    <div class="sm:col-span-5">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">ชื่อ <span class="text-rose-500">*</span></label>
                        <input type="text" name="members[${idx}][first_name]" required placeholder="ชื่อจริง" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500 bg-white">
                    </div>

                    <div class="sm:col-span-5">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">นามสกุล <span class="text-rose-500">*</span></label>
                        <input type="text" name="members[${idx}][last_name]" required placeholder="นามสกุล" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500 bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 mt-3 pt-3 border-t border-slate-200/60">
                    <div class="sm:col-span-4">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">
                            วัน/เดือน/ปีเกิด (พ.ศ.) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="members[${idx}][birth_date]" id="birth_date_${idx}" required placeholder="วว/ดด/ปปปป (พ.ศ.)" class="datepicker-be w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500 bg-white">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-3 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">อายุ (ปี) <span class="text-emerald-600 text-[9px] font-normal">คำนวณอัตโนมัติ</span></label>
                        <input type="number" name="members[${idx}][age]" id="age_input_${idx}" min="1" max="99" placeholder="ปี" readonly class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-900 bg-slate-100 cursor-not-allowed">
                    </div>

                    <div class="sm:col-span-5">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">
                            ระดับชั้นเรียน <span class="text-rose-500">*</span>
                        </label>
                        <select name="members[${idx}][jersey_number]" required class="select2-init w-full text-xs font-bold text-slate-800 bg-white">
                            <option value="">-- เลือกระดับชั้น --</option>
                            <option value="ม.1">มัธยมศึกษาปีที่ 1 (ม.1)</option>
                            <option value="ม.2">มัธยมศึกษาปีที่ 2 (ม.2)</option>
                            <option value="ม.3">มัธยมศึกษาปีที่ 3 (ม.3)</option>
                            <option value="ม.4">มัธยมศึกษาปีที่ 4 (ม.4)</option>
                            <option value="ม.5">มัธยมศึกษาปีที่ 5 (ม.5)</option>
                            <option value="ม.6">มัธยมศึกษาปีที่ 6 (ม.6)</option>
                            <option value="ป.4">ประถมศึกษาปีที่ 4 (ป.4)</option>
                            <option value="ป.5">ประถมศึกษาปีที่ 5 (ป.5)</option>
                            <option value="ป.6">ประถมศึกษาปีที่ 6 (ป.6)</option>
                            <option value="ปวช.1">ปวช. 1</option>
                            <option value="ปวช.2">ปวช. 2</option>
                            <option value="ปวช.3">ปวช. 3</option>
                        </select>
                    </div>
                </div>
            `;
        } else {
            rowDiv.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold bg-purple-100 text-purple-800">
                            👔 ผู้ฝึกสอน/เจ้าหน้าที่คนที่ ${displayNo}
                        </span>
                        <input type="hidden" name="members[${idx}][member_type]" value="coach">
                    </div>
                    <button type="button" onclick="removeMemberRow(${idx})" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" title="ลบรายการนี้">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">คำนำหน้า <span class="text-rose-500">*</span></label>
                        <select name="members[${idx}][prefix]" required class="select2-init w-full text-xs font-medium bg-white">
                            <option value="นาย">นาย</option>
                            <option value="นางสาว">นางสาว</option>
                            <option value="นาง">นาง</option>
                            <option value="ว่าที่ร้อยตรี">ว่าที่ร้อยตรี</option>
                            <option value="ดร.">ดร.</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">ชื่อ <span class="text-rose-500">*</span></label>
                        <input type="text" name="members[${idx}][first_name]" required placeholder="ชื่อจริง" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 bg-white">
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">นามสกุล <span class="text-rose-500">*</span></label>
                        <input type="text" name="members[${idx}][last_name]" required placeholder="นามสกุล" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 bg-white">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">ตำแหน่งในทีม</label>
                        <select name="members[${idx}][position]" class="select2-init w-full text-xs font-medium bg-white">
                            <option value="ผู้ควบคุมทีม">ผู้ควบคุมทีม</option>
                            <option value="ผู้ฝึกสอน">ผู้ฝึกสอน (โค้ช)</option>
                            <option value="ผู้ช่วยผู้ฝึกสอน">ผู้ช่วยผู้ฝึกสอน</option>
                            <option value="ผู้จัดการทีม">ผู้จัดการทีม</option>
                            <option value="เจ้าหน้าที่ประจำทีม">เจ้าหน้าที่ประจำทีม</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">เบอร์โทรศัพท์</label>
                        <input type="text" name="members[${idx}][jersey_number]" placeholder="08x-xxxxxxx" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 bg-white">
                    </div>
                </div>
            `;
        }

        container.appendChild(rowDiv);
        lucide.createIcons();

        // Initialize Select2 on newly created selects inside row
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $(rowDiv).find('.select2-init').select2({
                width: '100%',
                dropdownParent: $(rowDiv)
            });
        }

        // Initialize Flatpickr for the date input with change listener for age calculation
        const dateInput = rowDiv.querySelector('.datepicker-be');
        if (dateInput) {
            flatpickr(dateInput, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                locale: "th",
                onReady: function (selectedDates, dateStr, instance) {
                    if (typeof applyBE === 'function') { applyBE(instance); setTimeout(() => applyBE(instance), 10); }
                },
                onValueUpdate: function (selectedDates, dateStr, instance) {
                    if (typeof applyBE === 'function') { applyBE(instance); setTimeout(() => applyBE(instance), 10); }
                    if (selectedDates && selectedDates.length > 0) {
                        calculateAndValidateAge(selectedDates[0], idx, isAthlete);
                    }
                },
                onOpen: function (selectedDates, dateStr, instance) {
                    if (typeof applyBE === 'function') { applyBE(instance); setTimeout(() => applyBE(instance), 10); }
                },
                onMonthChange: function (selectedDates, dateStr, instance) {
                    if (typeof applyBE === 'function') { setTimeout(() => applyBE(instance), 10); }
                },
                onYearChange: function (selectedDates, dateStr, instance) {
                    if (typeof applyBE === 'function') { setTimeout(() => applyBE(instance), 10); }
                },
                onChange: function (selectedDates, dateStr, instance) {
                    if (typeof applyBE === 'function') { applyBE(instance); setTimeout(() => applyBE(instance), 10); }
                    if (selectedDates && selectedDates.length > 0) {
                        calculateAndValidateAge(selectedDates[0], idx, isAthlete);
                    } else if (dateStr) {
                        calculateAndValidateAge(dateStr, idx, isAthlete);
                    }
                },
                onClose: function (selectedDates, dateStr, instance) {
                    if (typeof applyBE === 'function') { applyBE(instance); }
                    if (selectedDates && selectedDates.length > 0) {
                        calculateAndValidateAge(selectedDates[0], idx, isAthlete);
                    }
                }
            });
        }
    }

    function removeMemberRow(idx) {
        const row = document.getElementById(`member_row_${idx}`);
        if (row) {
            row.remove();
        }
    }

    // --- Thai Provinces & Districts Auto-Select Data ---
    const thaiLocationData = {
        "นครสวรรค์": ["เมืองนครสวรรค์", "เก้าเลี้ยว", "โกรกพระ", "ชุมแสง", "ตากฟ้า", "ตาคลี", "ท่าตะโก", "บรรพตพิสัย", "พยุหะคีรี", "ไพศาลี", "ลาดยาว", "หนองบัว", "แม่วงก์", "แม่เปิน", "ชุมตาบง"],
        "กรุงเทพมหานคร": ["พระนคร", "ดุสิต", "หนองจอก", "บางรัก", "บางเขน", "บางกะปิ", "ปทุมวัน", "ป้อมปราบศัตรูพ่าย", "พระโขนง", "มีนบุรี", "ลาดกระบัง", "ยานนาวา", "สัมพันธวงศ์", "พญาไท", "ธนบุรี", "บางกอกใหญ่", "ห้วยขวาง", "คลองสาน", "ตลิ่งชัน", "บางกอกน้อย", "บางขุนเทียน", "ภาษีเจริญ", "หนองแขม", "ราษฎร์บูรณะ", "บางพลัด", "ดินแดง", "บึงกุ่ม", "สาทร", "บางซื่อ", "จตุจักร", "บางคอแหลม", "ประเวศ", "คลองเตย", "สวนหลวง", "จอมทอง", "ดอนเมือง", "ราชเทวี", "ลาดพร้าว", "วัฒนา", "บางแค", "หลักสี่", "สายไหม", "คันนายาว", "สะพานสูง", "วังทองหลาง", "คลองสามวา", "บางนา", "ทวีวัฒนา", "ทุ่งครุ", "บางบอน"],
        "กำแพงเพชร": ["เมืองกำแพงเพชร", "ไทรงาม", "คลองลาน", "ขาณุวรลักษบุรี", "คลองขลุง", "พรานกระต่าย", "ลานกระบือ", "ทรายทองวัฒนา", "ปางศิลาทอง", "บึงสามัคคี", "โกสัมพีนคร"],
        "ชัยนาท": ["เมืองชัยนาท", "มโนรมย์", "วัดสิงห์", "สรรพยา", "สรรคบุรี", "หันคา", "หนองมะโมง", "เนินขาม"],
        "พิจิตร": ["เมืองพิจิตร", "วังทรายพูน", "โพธิ์ประทับช้าง", "ตะพานหิน", "บางมูลนาก", "โพทะเล", "สามง่าม", "ทับคล้อ", "สากเหล็ก", "บึงนาราง", "ดงเจริญ", "วชิรบารมี"],
        "พิษณุโลก": ["เมืองพิษณุโลก", "นครไทย", "ชาติตระการ", "บางระกำ", "บางกระทุ่ม", "พรหมพิราม", "วัดโบสถ์", "วังทอง", "เนินมะปราง"],
        "เพชรบูรณ์": ["เมืองเพชรบูรณ์", "ชนแดน", "หล่มสัก", "หล่มเก่า", "วิเชียรบุรี", "ศรีเทพ", "หนองไผ่", "บึงสามพัน", "น้ำหนาว", "วังโป่ง", "เขาค้อ"],
        "อุทัยธานี": ["เมืองอุทัยธานี", "ทัพทัน", "สว่างอารมณ์", "หนองฉาง", "หนองขาหย่าง", "บ้านไร่", "ลานสัก", "ห้วยคต"],
        "ลพบุรี": ["เมืองลพบุรี", "พัฒนานิคม", "โคกสำโรง", "ชัยบาดาล", "ท่าวุ้ง", "บ้านหมี่", "ท่าหลวง", "สระโบสถ์", "โคกเจริญ", "ลำสนธิ", "หนองม่วง"],
        "สิงห์บุรี": ["เมืองสิงห์บุรี", "บางระจัน", "ค่ายบางระจัน", "พรหมบุรี", "ท่าช้าง", "อินทร์บุรี"],
        "สุพรรณบุรี": ["เมืองสุพรรณบุรี", "เดิมบางนางบวช", "ด่านช้าง", "บางปลาม้า", "ศรีประจันต์", "ดอนเจดีย์", "สองพี่น้อง", "สามชุก", "อู่ทอง", "หนองหญ้าไซ"],
        "สุโขทัย": ["เมืองสุโขทัย", "บ้านด่านลานหอย", "คีรีมาศ", "กงไกรลาศ", "ศรีสัชนาลัย", "ศรีสำโรง", "สวรรคโลก", "ศรีนคร", "ทุ่งเสลี่ยม"],
        "ตาก": ["เมืองตาก", "บ้านตาก", "สามเงา", "แม่ระมาด", "ท่าสองยาง", "แม่สอด", "พบพระ", "อุ้มผาง", "วังเจ้า"],
        "สระบุรี": ["เมืองสระบุรี", "แก่งคอย", "หนองแค", "วิหารแดง", "หนองแซง", "บ้านหมอ", "ดอนพุด", "หนองโดน", "พระพุทธบาท", "เสาไห้", "มวกเหล็ก", "วังม่วง", "เฉลิมพระเกียรติ"],
        "อ่างทอง": ["เมืองอ่างทอง", "ไชโย", "ป่าโมก", "โพธิ์ทอง", "แสวงหา", "วิเศษชัยชาญ", "สามโก้"],
        "พระนครศรีอยุธยา": ["พระนครศรีอยุธยา", "ท่าเรือ", "นครหลวง", "บางไทร", "บางบาล", "บางปะอิน", "บางปะหัน", "ผักไห่", "ภาชี", "ลาดบัวหลวง", "วังน้อย", "เสนา", "บางซ้าย", "อุทัย", "มหาราช", "บ้านแพรก"],
        "กระบี่": ["เมืองกระบี่", "เขาพนม", "เกาะลันตา", "คลองท่อม", "อ่าวลึก", "ปลายพระยา", "ลำทับ", "เหนือคลอง"],
        "กาญจนบุรี": ["เมืองกาญจนบุรี", "ไทรโยค", "บ่อพลอย", "ศรีสวัสดิ์", "ท่ามะกา", "ท่าม่วง", "ทองผาภูมิ", "สังขละบุรี", "พนมทวน", "เลาขวัญ", "ด่านมะขามเตี้ย", "หนองปรือ", "ห้วยกระเจา"],
        "กาฬสินธุ์": ["เมืองกาฬสินธุ์", "นามน", "กมลาไสย", "ร่องคำ", "กุฉินารายณ์", "เขาวง", "ยางตลาด", "ห้วยเม็ก", "สหัสขันธ์", "คำม่วง", "ท่าคันโท", "หนองกุงศรี", "สมเด็จ", "ห้วยผึ้ง", "สามชัย", "นาคู", "ดอนจาน", "ฆ้องชัย"],
        "ขอนแก่น": ["เมืองขอนแก่น", "บ้านฝาง", "พระยืน", "หนองเรือ", "ชุมแพ", "สีชมพู", "น้ำพอง", "อุบลรัตน์", "กระนวน", "บ้านไผ่", "เปือยน้อย", "พล", "แวงใหญ่", "แวงน้อย", "หนองสองห้อง", "ภูเวียง", "มัญจาคีรี", "ชนบท", "เขาสวนกวาง", "ภูผาม่าน", "ซำสูง", "โคกโพธิ์ไชย", "หนองนาคำ", "บ้านแฮด", "โนนศิลา", "เวียงเก่า"],
        "จันทบุรี": ["เมืองจันทบุรี", "ขลุง", "ท่าใหม่", "โป่งน้ำร้อน", "มะขาม", "แหลมสิงห์", "สอยดาว", "แก่งหางแมว", "นายายอาม", "เขาคิชฌกูฏ"],
        "ฉะเชิงเทรา": ["เมืองฉะเชิงเทรา", "บางคล้า", "บางน้ำเปรี้ยว", "บางปะกง", "บ้านโพธิ์", "พนมสารคาม", "ราชสาส์น", "สนามชัยเขต", "แปลงยาว", "ท่าตะเกียบ", "คลองเขื่อน"],
        "ชลบุรี": ["เมืองชลบุรี", "บ้านบึง", "หนองใหญ่", "บางละมุง", "พานทอง", "พนัสนิคม", "ศรีราชา", "เกาะสีชัง", "สัตหีบ", "บ่อทอง", "เกาะจันทร์"],
        "ชุมพร": ["เมืองชุมพร", "ท่าแซะ", "ปะทิว", "หลังสวน", "ละแม", "พะโต๊ะ", "สวี", "ทุ่งตะโก"],
        "เชียงราย": ["เมืองเชียงราย", "เวียงชัย", "เชียงของ", "เทิง", "พาน", "ป่าแดด", "แม่จัน", "เชียงแสน", "แม่สาย", "แม่สรวย", "เวียงป่าเป้า", "พญาเม็งราย", "เวียงแก่น", "ขุนตาล", "แม่ฟ้าหลวง", "แม่ลาว", "เวียงเชียงรุ้ง", "ดอยหลวง"],
        "เชียงใหม่": ["เมืองเชียงใหม่", "จอมทอง", "แม่แจ่ม", "เชียงดาว", "ดอยสะเก็ด", "แม่แตง", "แม่ริม", "สะเมิง", "ฝาง", "แม่อาย", "พร้าว", "สันป่าตอง", "สันกำแพง", "สันทราย", "หางดง", "ฮอด", "ดอยเต่า", "อมก๋อย", "สารภี", "เวียงแหง", "ไชยปราการ", "แม่วาง", "แม่ออน", "ดอยหล่อ", "กัลยาณิวัฒนา"],
        "ตรัง": ["เมืองตรัง", "กันตัง", "ย่านตาขาว", "ปะเหลียน", "สิเกา", "ห้วยยอด", "วังวิเศษ", "นาโยง", "รัษฎา", "หาดสำราญ"],
        "ตราด": ["เมืองตราด", "คลองใหญ่", "เขาสมิง", "บ่อไร่", "แหลมงอบ", "เกาะกูด", "เกาะช้าง"],
        "นครนายก": ["เมืองนครนายก", "ปากพลี", "บ้านนา", "องครักษ์"],
        "นครปฐม": ["เมืองนครปฐม", "กำแพงแสน", "นครชัยศรี", "ดอนตูม", "บางเลน", "สามพราน", "พุทธมณฑล"],
        "นครพนม": ["เมืองนครพนม", "ปลาปาก", "ท่าอุเทน", "โพนสวรรค์", "ธาตุพนม", "เรณูนคร", "บ้านแพง", "นาแก", "ศรีสงคราม", "นาหว้า", "โพนสวรรค์", "นาทม", "วังยาง"],
        "นครราชสีมา": ["เมืองนครราชสีมา", "ครบุรี", "เสิงสาง", "คง", "บ้านเหลื่อม", "จักราช", "โชคชัย", "ด่านขุนทด", "โนนไทย", "โนนสูง", "ขามสะแกแสง", "บัวใหญ่", "ประทาย", "ปักธงชัย", "พิมาย", "ห้วยแถลง", "ชุมพวง", "สูงเนิน", "ขามทะเลสอ", "สีคิ้ว", "ปากช่อง", "หนองบุญมาก", "แก้งสนามนาง", "โนนแดง", "วังน้ำเขียว", "เทพารักษ์", "เมืองยาง", "พระทองคำ", "ลำทะเมนชัย", "บัวลาย", "สีดา", "เฉลิมพระเกียรติ"],
        "นครศรีธรรมราช": ["เมืองนครศรีธรรมราช", "พรหมคีรี", "ลานสกา", "ฉวาง", "พิปูน", "เชียรใหญ่", "ชะอวด", "ท่าศาลา", "ทุ่งสง", "นาบอน", "ทุ่งใหญ่", "ปากพนัง", "ร่อนพิบูลย์", "สิชล", "ขนอม", "หัวไทร", "บางขัน", "ถ้ำพรรณรา", "จุฬาภรณ์", "พระพรหม", "นบพิตำ", "ช้างกลาง", "เฉลิมพระเกียรติ"],
        "นนทบุรี": ["เมืองนนทบุรี", "บางกรวย", "บางใหญ่", "บางบัวทอง", "ไทรน้อย", "ปากเกร็ด"],
        "นราธิวาส": ["เมืองนราธิวาส", "ตากใบ", "บาเจาะ", "ยี่งอ", "ระแงะ", "รือเสาะ", "ศรีสาคร", "แว้ง", "สุคิริน", "สุไหงโก-ลก", "สุไหงปาดี", "จะแนะ", "เจาะไอร้อง"],
        "น่าน": ["เมืองน่าน", "แม่จริม", "บ้านหลวง", "นาน้อย", "ปัว", "ท่าวังผา", "เวียงสา", "ทุ่งช้าง", "เชียงกลาง", "นาหมื่น", "สันติสุข", "บ่อเกลือ", "สองแคว", "ภูเพียง", "เฉลิมพระเกียรติ"],
        "บึงกาฬ": ["เมืองบึงกาฬ", "พรเจริญ", "โซ่พิสัย", "เซกา", "ปากคาด", "บึงโขงหลง", "ศรีวิไล", "บุ่งคล้า"],
        "บุรีรัมย์": ["เมืองบุรีรัมย์", "คูเมือง", "กระสัง", "นางรอง", "หนองกี่", "ละหานทราย", "ประโคนชัย", "บ้านกรวด", "พุทไธสง", "ลำปลายมาศ", "สตึก", "ปะคำ", "นาโพธิ์", "หนองหงส์", "พลับพลาชัย", "ห้วยราช", "โนนสุวรรณ", "ชำนิ", "บ้านใหม่ไชยพจน์", "โนนดินแดง", "บ้านด่าน", "แคนดง", "เฉลิมพระเกียรติ"],
        "ปทุมธานี": ["เมืองปทุมธานี", "คลองหลวง", "ธัญบุรี", "หนองเสือ", "ลาดหลุมแก้ว", "ลำลูกกา", "สามโคก"],
        "ประจวบคีรีขันธ์": ["เมืองประจวบคีรีขันธ์", "กุยบุรี", "ทับสะแก", "บางสะพาน", "บางสะพานน้อย", "ปราณบุรี", "หัวหิน", "สามร้อยยอด"],
        "ปราจีนบุรี": ["เมืองปราจีนบุรี", "กบินทร์บุรี", "นาดี", "สระบพิตร", "บ้านสร้าง", "ประจันตคาม", "ศรีมหาโพธิ", "ศรีมโหสถ"],
        "ปัตตานี": ["เมืองปัตตานี", "โคกโพธิ์", "หนองจิก", "ปะนาเระ", "มายอ", "ทุ่งยางแดง", "สายบุรี", "ไม้แก่น", "ยะหริ่ง", "ยะรัง", "กะพ้อ", "แม่ลาน"],
        "พะเยา": ["เมืองพะเยา", "จุน", "เชียงคำ", "เชียงม่วน", "ดอกคำใต้", "ปง", "แม่ใจ", "ภูซาง", "ภูกามยาว"],
        "พังงา": ["เมืองพังงา", "เกาะยาว", "กะปง", "ตะกั่วทุ่ง", "ตะกั่วป่า", "คุระบุรี", "ทับปุด", "ท้ายเหมือง"],
        "พัทลุง": ["เมืองพัทลุง", "กงหรา", "เขาชัยสน", "ตะโหมด", "ควนขนุน", "ปากพะยูน", "ศรีบรรพต", "ป่าบอน", "บางแก้ว", "ป่าพะยอม", "ศรีนครินทร์"],
        "ภูเก็ต": ["เมืองภูเก็ต", "กะทู้", "ถลาง"],
        "มหาสารคาม": ["เมืองมหาสารคาม", "แกดำ", "โกสุมพิสัย", "กันทรวิชัย", "เชียงยืน", "บรบือ", "นาเชือก", "พยัคฆภูมิพิสัย", "วาปีปทุม", "นาดูน", "ยางสีสุราช", "กุดรัง", "ชื่นชม"],
        "มุกดาหาร": ["เมืองมุกดาหาร", "นิคมคำสร้อย", "ดอนตาล", "ดงหลวง", "คำชะอี", "หว้านใหญ่", "หนองสูง"],
        "แม่ฮ่องสอน": ["เมืองแม่ฮ่องสอน", "ขุนยวม", "ปาย", "แม่สะเรียง", "แม่ลาน้อย", "สบเมย", "ปางมะผ้า"],
        "ยโสธร": ["เมืองยโสธร", "ทรายมูล", "กุดชุม", "คำเขื่อนแก้ว", "ป่าติ้ว", "มหาชนะชัย", "ค้อวัง", "เลิงนกทา", "ไทยเจริญ"],
        "ยะลา": ["เมืองยะลา", "เบตง", "บันนังสตา", "ธารโต", "ยะหา", "รามัน", "กาบัง", "กรงปินัง"],
        "ร้อยเอ็ด": ["เมืองร้อยเอ็ด", "เกษตรวิสัย", "ปทุมรัตต์", "จตุรพักตรพิมาน", "ธวัชบุรี", "พนมไพร", "โพนทอง", "โพธิ์ชัย", "หนองพอก", "เสลภูมิ", "สุวรรณภูมิ", "เมืองสรวง", "โพนทราย", "อาจสามารถ", "เมยวดี", "ศรีสมเด็จ", "จังหาร", "เชียงขวัญ", "หนองฮี", "ทุ่งเขาหลวง"],
        "ระนอง": ["เมืองระนอง", "ละอุ่น", "กะเปอร์", "กระบุรี", "สุขสำราญ"],
        "ระยอง": ["เมืองระยอง", "บ้านฉาง", "แกลง", "วังจันทร์", "บ้านค่าย", "ปลวกแดง", "เขาชะเมา", "นิคมพัฒนา"],
        "ราชบุรี": ["เมืองราชบุรี", "จอมบึง", "ดำเนินสะดวก", "บ้านโป่ง", "บางแพ", "โพธาราม", "ปากท่อ", "วัดเพลง", "สวนผึ้ง", "บ้านคา"],
        "ลพบุรี": ["เมืองลพบุรี", "พัฒนานิคม", "โคกสำโรง", "ชัยบาดาล", "ท่าวุ้ง", "บ้านหมี่", "ท่าหลวง", "สระโบสถ์", "โคกเจริญ", "ลำสนธิ", "หนองม่วง"],
        "ลำปาง": ["เมืองลำปาง", "แม่เมาะ", "เกาะคา", "เสริมงาม", "งาว", "แจ้ห่ม", "วังเหนือ", "เถิน", "แม่พริก", "แม่ทะ", "สบปราบ", "ห้างฉัตร", "เมืองปาน"],
        "ลำพูน": ["เมืองลำพูน", "แม่ทา", "บ้านโฮ่ง", "ลี้", "ทุ่งหัวช้าง", "ป่าซาง", "บ้านธิ", "เวียงหนองล่อง"],
        "เลย": ["เมืองเลย", "นาด้วง", "เชียงคาน", "ปากชม", "ด่านซ้าย", "ภูเรือ", "ท่าลี่", "วังสะพุง", "ภูกระดึง", "ภูหลวง", "ผาขาว", "เอราวัณ", "หนองหิน"],
        "ศรีสะเกษ": ["เมืองศรีสะเกษ", "ยางชุมน้อย", "กันทรารมย์", "กันทรลักษ์", "ขุขันธ์", "ไพรบึง", "ปรางค์กู่", "ขุนหาญ", "ราษีไศล", "อุทุมพรพิสัย", "บึงบูรพ์", "ห้วยทับทัน", "โนนคูณ", "ศรีรัตนะ", "น้ำเกลี้ยง", "วังหิน", "ภูสิงห์", "เมืองจันทร์", "เบญจลักษ์", "พยุห์", "โพธิ์ศรีสุวรรณ", "ศิลาลาด"],
        "สกลนคร": ["เมืองสกลนคร", "กุสุมาลย์", "กุดบาก", "พรรณานิคม", "พังโคน", "วาริชภูมิ", "นิคมน้ำอูน", "วานรนิวาส", "คำตากล้า", "บ้านม่วง", "อากาศอำนวย", "สว่างแดนดิน", "ส่องดาว", "เต่างอย", "โคกศรีสุพรรณ", "เจริญศิลป์", "โพนนาแก้ว", "ภูพาน"],
        "สงขลา": ["เมืองสงขลา", "สทิงพระ", "จะนะ", "นาทวี", "เทพา", "สะบ้าย้อย", "ระโนด", "กระแสสินธุ์", "รัตภูมิ", "สะเดา", "หาดใหญ่", "นาหม่อม", "ควนเนียง", "บางกล่ำ", "สิงหนคร", "คลองหอยโข่ง"],
        "สตูล": ["เมืองสตูล", "ควนโดน", "ควนกาหลง", "ท่าแพ", "ละงู", "ทุ่งหว้า", "มะนัง"],
        "สมุทรปราการ": ["เมืองสมุทรปราการ", "บางบ่อ", "บางพลี", "พระประแดง", "พระสมุทรเจดีย์", "บางเสาธง"],
        "สมุทรสงคราม": ["เมืองสมุทรสงคราม", "บางคนที", "อัมพวา"],
        "สมุทรสาคร": ["เมืองสมุทรสาคร", "กระทุ่มแบน", "บ้านแพ้ว"],
        "สระแก้ว": ["เมืองสระแก้ว", "คลองหาด", "ตาพระยา", "วังน้ำเย็น", "วัฒนานคร", "อรัญประเทศ", "เขาฉกรรจ์", "โคกสูง", "วังสมบูรณ์"],
        "สุราษฎร์ธานี": ["เมืองสุราษฎร์ธานี", "กาญจนดิษฐ์", "ดอนสัก", "เกาะสมุย", "เกาะพะงัน", "ไชยา", "ท่าชนะ", "คีรีรัฐนิคม", "บ้านตาขุน", "พนม", "ท่าฉาง", "บ้านนาสาร", "บ้านนาเดิม", "เคียนซา", "เวียงสระ", "พระแสง", "พุนพิน", "ชัยบุรี", "วิภาวดี"],
        "หนองคาย": ["เมืองหนองคาย", "ท่าบ่อ", "เมืองหมี", "โพนพิสัย", "ศรีเชียงใหม่", "สังคม", "สระใคร", "เฝ้าไร่", "รัตนวาปี", "โพธิ์ตาก"],
        "หนองบัวลำภู": ["เมืองหนองบัวลำภู", "นากลาง", "โนนสัง", "ศรีบุญเรือง", "สุวรรณคูหา", "นาวัง"],
        "อุดรธานี": ["เมืองอุดรธานี", "กุดจับ", "หนองวัวซอ", "กุมภวาปี", "โนนสะอาด", "หนองหาน", "ทุ่งฝน", "ไชยวาน", "ศรีธาตุ", "วังสามหมอ", "บ้านดุง", "บ้านผือ", "น้ำโสม", "เพ็ญ", "สร้างคอม", "หนองแสง", "นายูง", "พิบูลย์รักษ์", "กู่แก้ว", "ประจักษ์ศิลปาคม"],
        "อุตรดิตถ์": ["เมืองอุตรดิตถ์", "ตรอน", "ท่าปลา", "น้ำปาด", "ฟากท่า", "บ้านโคก", "พิชัย", "ลับแล", "ทองแสนขัน"],
        "อุบลราชธานี": ["เมืองอุบลราชธานี", "ศรีเมืองใหม่", "โขงเจียม", "เขื่องใน", "เขมราฐ", "เดชอุดม", "นาจะหลวย", "น้ำยืน", "บุณฑริก", "ตระการพืชผล", "กุดข้าวปุ้น", "ม่วงสามสิบ", "วารินชำราบ", "พิบูลมังสาหาร", "ตาลสุม", "โพธิ์ไทร", "สำโรง", "ดอนมดแดง", "สิรินธร", "ทุ่งศรีอุดม", "ปทุมราชวงศา", "ศรีรัตนะ", "นาเยีย", "นาตาล", "เหล่าเสือโก้ก", "สว่างวีระวงศ์", "น้ำขุ่น"],
        "อำนาจเจริญ": ["เมืองอำนาจเจริญ", "ชานุมาน", "ปทุมราชวงศา", "พนา", "เสนางคนิคม", "หัวตะพาน", "ลืออำนาจ"]
    };

    function initProvinceDistrict() {
        const provSelect = $('#province_select');
        const defaultProv = "นครสวรรค์";

        provSelect.empty().append('<option value="">-- เลือกจังหวัด --</option>');
        Object.keys(thaiLocationData).forEach(p => {
            provSelect.append(new Option(p, p, false, p === defaultProv));
        });

        provSelect.select2({
            width: '100%',
            placeholder: '-- เลือกจังหวัด --'
        });

        $('#district_select').select2({
            width: '100%',
            placeholder: '-- เลือกอำเภอ --'
        });

        provSelect.on('change', function () {
            onProvinceChange($(this).val());
        });

        onProvinceChange(defaultProv);
    }

    function onProvinceChange(provName) {
        const distSelect = $('#district_select');
        distSelect.empty().append('<option value="">-- เลือกอำเภอ --</option>');

        if (provName && thaiLocationData[provName]) {
            thaiLocationData[provName].forEach(d => {
                distSelect.append(new Option(d, d));
            });
        }
        distSelect.trigger('change');
    }

    // Call init on document ready
    $(document).ready(function () {
        initProvinceDistrict();
    });
</script>
<?= $this->endSection() ?>