<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
    <div>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">จัดการบุคลากร</h2>
        <p class="text-sm text-slate-400 mt-1 font-medium">จัดการข้อมูลบุคลากร กองการศึกษา ศาสนาและวัฒนธรรม</p>
    </div>
    <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-3">
        <i data-lucide="plus-circle" class="w-5 h-5"></i> เพิ่มบุคลากรใหม่
    </button>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
    <div class="glass-card rounded-2xl p-6 text-center">
        <p class="text-3xl font-black text-blue-600"><?= count($users) ?></p>
        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">ทั้งหมด</p>
    </div>
    <div class="glass-card rounded-2xl p-6 text-center">
        <p class="text-3xl font-black text-purple-600"><?= count(array_filter($users, fn($u) => $u['u_division'] == 'ผู้บริหาร')) ?></p>
        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">ผู้บริหาร</p>
    </div>
    <div class="glass-card rounded-2xl p-6 text-center">
        <p class="text-3xl font-black text-emerald-600"><?= count(array_filter($users, fn($u) => $u['u_division'] == 'ฝ่ายบริหาร')) ?></p>
        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">ฝ่ายบริหาร</p>
    </div>
    <div class="glass-card rounded-2xl p-6 text-center">
        <p class="text-3xl font-black text-amber-600"><?= count(array_filter($users, fn($u) => $u['u_division'] == 'ฝ่ายส่งเสริม')) ?></p>
        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">ฝ่ายส่งเสริม</p>
    </div>
</div>

<!-- Personnel Tables -->
<?php 
$grouped_users = [];
foreach($users as $u) {
    if(!isset($grouped_users[$u['u_division']])) $grouped_users[$u['u_division']] = [];
    $grouped_users[$u['u_division']][] = $u;
}
?>

<?php if(isset($grouped_users['ผู้บริหาร'])): ?>
<div class="mb-12">
    <div class="flex items-center gap-4 mb-6">
        <div class="h-10 w-2 bg-purple-600 rounded-full"></div>
        <h3 class="text-2xl font-black text-slate-800 tracking-tight">ผู้บริหารกอง <span class="text-slate-400 font-bold ml-2 text-sm">(<?= count($grouped_users['ผู้บริหาร']) ?> ท่าน)</span></h3>
    </div>
    <?= view_cell('\App\Controllers\Staff::renderPersonnelTable', ['members' => $grouped_users['ผู้บริหาร']]) ?>
</div>
<?php unset($grouped_users['ผู้บริหาร']); ?>
<?php endif; ?>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-10 mb-12">
    <div class="animate-[fadeIn_0.6s_ease-out]">
        <?php if(isset($grouped_users['ฝ่ายบริหาร'])): ?>
            <div class="flex items-center gap-4 mb-6">
                <div class="h-8 w-1.5 bg-emerald-500 rounded-full"></div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight">ฝ่ายบริหารงานทั่วไป <span class="text-slate-400 font-bold ml-2 text-sm">(<?= count($grouped_users['ฝ่ายบริหาร']) ?> ท่าน)</span></h3>
            </div>
            <?= view_cell('\App\Controllers\Staff::renderPersonnelTable', ['members' => $grouped_users['ฝ่ายบริหาร'], 'compact' => true]) ?>
            <?php unset($grouped_users['ฝ่ายบริหาร']); ?>
        <?php endif; ?>
    </div>
    <div class="animate-[fadeIn_0.7s_ease-out]">
        <?php if(isset($grouped_users['ฝ่ายส่งเสริม'])): ?>
            <div class="flex items-center gap-4 mb-6">
                <div class="h-8 w-1.5 bg-amber-500 rounded-full"></div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight">ฝ่ายส่งเสริมการศึกษาฯ <span class="text-slate-400 font-bold ml-2 text-sm">(<?= count($grouped_users['ฝ่ายส่งเสริม']) ?> ท่าน)</span></h3>
            </div>
            <?= view_cell('\App\Controllers\Staff::renderPersonnelTable', ['members' => $grouped_users['ฝ่ายส่งเสริม'], 'compact' => true]) ?>
            <?php unset($grouped_users['ฝ่ายส่งเสริม']); ?>
        <?php endif; ?>
    </div>
</div>

<?php foreach($grouped_users as $division => $members): ?>
<div class="mb-12">
    <div class="flex items-center gap-4 mb-6">
        <div class="h-10 w-2 bg-slate-400 rounded-full"></div>
        <h3 class="text-xl font-black text-slate-800 tracking-tight"><?= esc($division) ?> <span class="text-slate-400 font-bold ml-2 text-sm">(<?= count($members) ?> ท่าน)</span></h3>
    </div>
    <?= view_cell('\App\Controllers\Staff::renderPersonnelTable', ['members' => $members]) ?>
</div>
<?php endforeach; ?>

<!-- Personnel Modal -->
<div id="userModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[70] hidden flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
    <div class="bg-white w-full max-w-4xl rounded-none sm:rounded-[2.5rem] overflow-hidden shadow-2xl animate-[fadeIn_0.3s_ease-out] my-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 sm:p-8 text-white relative">
            <h3 id="modalTitle" class="text-xl sm:text-2xl font-black">บุคลากร</h3>
            <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest mt-1">Personnel Management</p>
            <button onclick="closeModal()" class="absolute top-6 right-6 text-white/50 hover:text-white">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <form id="personnel-form" action="<?= base_url('staff/personnel/save') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="u_id" id="u_id">

            <!-- Tab Navigation -->
            <div class="flex border-b border-slate-100 px-8 gap-8 bg-slate-50/50">
                <button type="button" onclick="switchTab('basic')" id="tab-basic" class="py-4 text-sm font-black border-b-2 border-blue-600 text-blue-600 transition-all flex items-center gap-2">
                    <i data-lucide="user-circle" class="w-4 h-4"></i> ข้อมูลพื้นฐาน
                </button>
                <button type="button" onclick="switchTab('detailed')" id="tab-detailed" class="py-4 text-sm font-black border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all flex items-center gap-2 hidden">
                    <i data-lucide="file-text" class="w-4 h-4"></i> ทะเบียนประวัติ (พ.ค. 7)
                </button>
            </div>

            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar p-6 sm:p-8">
                
                <!-- Tab 1: ข้อมูลพื้นฐาน -->
                <div id="pane-basic" class="tab-pane animate-[fadeIn_0.3s_ease-out]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="md:col-span-2 grid grid-cols-4 gap-4">
                            <div class="col-span-1">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">คำนำหน้า</label>
                                <select name="u_prefix" id="u_prefix" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                                    <option value="นาย">นาย</option><option value="นาง">นาง</option><option value="นางสาว">นางสาว</option><option value="ดร.">ดร.</option><option value="ว่าที่ ร.ต.">ว่าที่ ร.ต.</option>
                                </select>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ชื่อ-นามสกุล</label>
                                <input type="text" name="u_fullname" id="u_fullname" required placeholder="ชื่อ นามสกุล" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ตำแหน่งงาน</label>
                            <select name="u_pos_id" id="u_pos_id" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold bg-slate-50">
                                <option value="">-- เลือกตำแหน่งงาน --</option>
                                <?php foreach($positions as $pos): ?>
                                    <option value="<?= $pos['pos_id'] ?>"><?= esc($pos['pos_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">สังกัด / ฝ่าย</label>
                            <select name="u_division" id="u_division" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                                <option value="ผู้บริหาร">ผู้บริหารกอง</option><option value="ฝ่ายบริหาร">ฝ่ายบริหารงานทั่วไป</option><option value="ฝ่ายส่งเสริม">ฝ่ายส่งเสริมการศึกษาฯ</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ระดับ / วิทยฐานะ</label>
                            <select name="u_level" id="u_level" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                                <option value="ไม่มีระดับ">- ไม่มีระดับ -</option><option value="ปฏิบัติงาน">ปฏิบัติงาน (ปง.)</option><option value="ชำนาญงาน">ชำนาญงาน (ชง.)</option><option value="อาวุธโส">อาวุโส</option><option value="ปฏิบัติการ">ปฏิบัติการ (ปก.)</option><option value="ชำนาญการ">ชำนาญการ (ชก.)</option><option value="ชำนาญการพิเศษ">ชำนาญการพิเศษ (ชพ.)</option><option value="ลูกจ้างประจำ">ลูกจ้างประจำ</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Email (Google Login)</label>
                            <input type="email" name="u_email" id="u_email" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-blue-600 font-black">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">เบอร์โทรศัพท์</label>
                            <input type="text" name="u_phone" id="u_phone" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ลำดับแสดงผล</label>
                                <input type="number" name="u_sort" id="u_sort" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">สถานะ</label>
                                <select name="u_status" id="u_status" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                                    <option value="active">ปฏิบัติงาน</option>
                                    <option value="inactive">พ้นสภาพ</option>
                                </select>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">รูปถ่ายบุคลากร</label>
                            <input type="file" name="u_photo" id="u_photo_input" accept="image/*" onchange="previewUserPhoto(this)" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold bg-slate-50">
                            <div id="photo-preview-container" class="mt-4 hidden items-center gap-4 p-2 bg-blue-50 rounded-2xl border border-blue-100">
                                <img id="photo-preview" src="" class="w-20 h-20 rounded-xl object-cover">
                                <div><p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">รูปภาพ</p><p id="photo-filename" class="text-xs font-bold text-slate-600 truncate max-w-[200px]"></p></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: ข้อมูลทะเบียนประวัติ (พ.ค. 7) -->
                <div id="pane-detailed" class="tab-pane hidden animate-[fadeIn_0.3s_ease-out]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">เลขประจำตัวประชาชน (13 หลัก)</label>
                            <div class="relative">
                                <input type="text" name="u_id_card" id="u_id_card" oninput="validateThaiID(this.value)" maxlength="13" placeholder="x-xxxx-xxxxx-xx-x"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-0 transition-all tracking-[0.2em]">
                                <div id="id-validation-icon" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                                </div>
                            </div>
                            <p id="id-validation-msg" class="text-[10px] font-bold mt-2 hidden"></p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">วัน/เดือน/ปีเกิด (พ.ศ.)</label>
                            <input type="text" name="u_birthday" id="u_birthday" class="datepicker-be w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">วันที่เริ่มบรรจุ / เริ่มงาน (พ.ศ.)</label>
                            <input type="text" name="u_hired_date" id="u_hired_date" class="datepicker-be w-full border border-slate-200 rounded-xl px-4 py-3 text-blue-600 font-black bg-blue-50/30">
                        </div>

                        <div class="md:col-span-2 grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">หมู่เลือด</label>
                                <select name="u_blood_type" id="u_blood_type" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                                    <option value="">- ระบุ -</option>
                                    <option value="A">A</option><option value="B">B</option><option value="AB">AB</option><option value="O">O</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ศาสนา</label>
                                <select name="u_religion" id="u_religion" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                                    <option value="พุทธ">พุทธ</option>
                                    <option value="คริสต์">คริสต์</option>
                                    <option value="อิสลาม">อิสลาม</option>
                                    <option value="ซิกข์">ซิกข์</option>
                                    <option value="ฮินดู">ฮินดู</option>
                                    <option value="อื่นๆ">อื่นๆ</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">สัญชาติ</label>
                                <select name="u_nationality" id="u_nationality" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                                    <option value="ไทย">ไทย</option>
                                    <option value="จีน">จีน</option>
                                    <option value="อเมริกัน">อเมริกัน</option>
                                    <option value="ลาว">ลาว</option>
                                    <option value="อื่นๆ">อื่นๆ</option>
                                </select>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ติดต่อกรณีฉุกเฉิน (ชื่อ-เบอร์โทร)</label>
                            <input type="text" name="u_emergency_contact" id="u_emergency_contact" placeholder="ระบุชื่อผู้ติดต่อ และเบอร์โทรศัพท์"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ที่อยู่ตามทะเบียนบ้าน</label>
                            <textarea name="u_address" id="u_address" rows="2" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold bg-slate-50/30"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ที่อยู่ปัจจุบัน</label>
                            <textarea name="u_current_address" id="u_current_address" rows="2" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="p-8 flex gap-4 bg-slate-50 border-t border-slate-100">
                <button type="button" onclick="closeModal()" class="flex-1 bg-white text-slate-600 py-4 rounded-2xl font-black text-sm border border-slate-200 hover:bg-slate-50 transition-all">ยกเลิก</button>
                <button type="submit" id="submit-btn" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-600/20 hover:bg-blue-700 transition-all disabled:opacity-50">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<style>
    .sortable-ghost { opacity: 0.3; background: #dbeafe !important; }
    .sortable-drag { background: white !important; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1) !important; }
    .drag-handle { cursor: grab; }
    .drag-handle:active { cursor: grabbing; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script>
    // Tab Logic
    function switchTab(tab) {
        document.getElementById('pane-basic').classList.add('hidden');
        document.getElementById('pane-detailed').classList.add('hidden');
        document.getElementById(`pane-${tab}`).classList.remove('hidden');

        ['basic', 'detailed'].forEach(b => {
            const btn = document.getElementById(`tab-${b}`);
            if (b === tab) {
                btn.classList.add('border-blue-600', 'text-blue-600');
                btn.classList.remove('border-transparent', 'text-slate-400');
            } else {
                btn.classList.remove('border-blue-600', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-slate-400');
            }
        });
    }

    function openModal() {
        document.getElementById('modalTitle').textContent = 'เพิ่มบุคลากรใหม่';
        document.getElementById('u_id').value = '';
        document.getElementById('personnel-form').reset();
        switchTab('basic');
        document.getElementById('tab-detailed').classList.add('hidden');
        document.getElementById('photo-preview-container').classList.add('hidden');
        validateThaiID('');
        document.getElementById('userModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    function editUser(user) {
        console.log("Personnel Data:", user); // ตรวจสอบข้อมูลที่ส่งมาจากเซิร์ฟเวอร์
        document.getElementById('modalTitle').textContent = 'แก้ไขข้อมูลบุคลากร';
        document.getElementById('u_id').value = user.u_id;
        document.getElementById('u_email').value = user.u_email || '';
        document.getElementById('u_fullname').value = user.u_fullname;
        document.getElementById('u_prefix').value = user.u_prefix || 'นาย';
        document.getElementById('u_pos_id').value = user.u_position || '';
        document.getElementById('u_level').value = user.u_level || 'ไม่มีระดับ';
        document.getElementById('u_division').value = user.u_division || 'ฝ่ายบริหาร';
        document.getElementById('u_phone').value = user.u_phone || '';
        document.getElementById('u_sort').value = user.u_sort || '99';
        document.getElementById('u_status').value = user.u_status || 'active';

        // P.K. 7
        document.getElementById('u_id_card').value = user.u_id_card || '';
        validateThaiID(user.u_id_card || ''); // แสดงสถานะตรวจสอบทันที

        // จัดการวันที่ด้วย Flatpickr Instance
        const fpBirthday = document.querySelector("#u_birthday")._flatpickr;
        const fpHired = document.querySelector("#u_hired_date")._flatpickr;
        
        if (fpBirthday) {
            fpBirthday.setDate(user.u_birthday || null);
            applyBE(fpBirthday); // แปลงทันที ไม่ต้องรอคลิก
        } else {
            document.getElementById('u_birthday').value = user.u_birthday || '';
        }

        if (fpHired) {
            fpHired.setDate(user.u_hired_date || null);
            applyBE(fpHired); // แปลงทันที ไม่ต้องรอคลิก
        } else {
            document.getElementById('u_hired_date').value = user.u_hired_date || '';
        }

        document.getElementById('u_blood_type').value = user.u_blood_type || '';
        document.getElementById('u_religion').value = user.u_religion || 'พุทธ';
        document.getElementById('u_nationality').value = user.u_nationality || 'ไทย';
        document.getElementById('u_address').value = user.u_address || '';
        document.getElementById('u_current_address').value = user.u_current_address || '';
        document.getElementById('u_emergency_contact').value = user.u_emergency_contact || '';

        switchTab('basic');
        document.getElementById('tab-detailed').classList.remove('hidden');

        if (user.u_photo) {
            document.getElementById('photo-preview').src = `<?= base_url('uploads/personnel/') ?>${user.u_photo}`;
            document.getElementById('photo-filename').textContent = 'รูปโปรไฟล์เดิม';
            document.getElementById('photo-preview-container').classList.remove('hidden');
        } else {
            document.getElementById('photo-preview-container').classList.add('hidden');
        }
        document.getElementById('userModal').classList.remove('hidden');
    }

    // Photo Preview
    function previewUserPhoto(input) {
        const container = document.getElementById('photo-preview-container');
        const preview = document.getElementById('photo-preview');
        const filename = document.getElementById('photo-filename');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; filename.textContent = input.files[0].name; container.classList.remove('hidden'); }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Sorting Logic
    document.querySelectorAll('.sortable-tbody').forEach(tbody => {
        new Sortable(tbody, {
            animation: 200, handle: '.drag-handle', swap: true, onEnd: function (evt) {
                if (evt.swapItem) swapOrder(evt.item.dataset.id, evt.swapItem.dataset.id, evt.item, evt.swapItem);
            }
        });
    });

    async function swapOrder(id1, id2, item1, item2) {
        try {
            const formData = new FormData();
            formData.append('id1', id1); formData.append('id2', id2);
            const response = await fetch('<?= base_url('staff/personnel/reorder') ?>', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await response.json();
            if (data.status === 'success') {
                Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, icon: 'success', title: 'สลับลำดับเรียบร้อย' });
            } else throw new Error(data.message);
        } catch (error) { window.location.reload(); }
    }

    // Form Submission
    document.getElementById('personnel-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = 'กำลังบันทึก...';
        try {
            const response = await fetch(this.action, { method: 'POST', body: new FormData(this), headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await response.json();
            if (data.status === 'success') {
                Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: data.message, timer: 2000, showConfirmButton: false }).then(() => { window.location.href = data.redirect; });
            } else throw new Error(data.message);
        } catch (error) {
            btn.disabled = false; btn.innerHTML = 'บันทึกข้อมูล';
            Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: error.message });
        }
    });

    lucide.createIcons();
    // ==========================================
    // Tab Switching
    // ==========================================
    function switchTab(tab) {
        document.getElementById('pane-basic').classList.add('hidden');
        document.getElementById('pane-detailed').classList.add('hidden');
        document.getElementById(`pane-${tab}`).classList.remove('hidden');

        const btns = ['basic', 'detailed'];
        btns.forEach(b => {
            const btn = document.getElementById(`tab-${b}`);
            if (b === tab) {
                btn.classList.add('border-blue-600', 'text-blue-600');
                btn.classList.remove('border-transparent', 'text-slate-400');
            } else {
                btn.classList.remove('border-blue-600', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-slate-400');
            }
        });
    }

    // ==========================================
    // Thai ID Validation (CheckSum)
    // ==========================================
    function validateThaiID(id) {
        const iconContainer = document.getElementById('id-validation-icon');
        const msg = document.getElementById('id-validation-msg');
        const input = document.getElementById('u_id_card');

        if (!id) {
            iconContainer.classList.add('hidden');
            msg.classList.add('hidden');
            input.classList.remove('border-emerald-500', 'border-rose-500');
            return;
        }

        if (id.length !== 13 || isNaN(id)) {
            showValidation(false, 'กรุณาระบุเลข 13 หลักให้ครบถ้วน');
            return;
        }

        let sum = 0;
        for (let i = 0; i < 12; i++) {
            sum += parseFloat(id.charAt(i)) * (13 - i);
        }

        if ((11 - (sum % 11)) % 10 !== parseFloat(id.charAt(12))) {
            showValidation(false, 'เลขบัตรประชาชนไม่ถูกต้อง (Checksum Error)');
        } else {
            showValidation(true, 'เลขบัตรประชาชนถูกต้อง');
        }

        function showValidation(isValid, message) {
            iconContainer.classList.remove('hidden');
            msg.classList.remove('hidden');
            msg.textContent = message;

            if (isValid) {
                iconContainer.innerHTML = '<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>';
                msg.className = 'text-[10px] font-bold mt-2 text-emerald-600';
                input.classList.add('border-emerald-500');
                input.classList.remove('border-rose-500');
            } else {
                iconContainer.innerHTML = '<i data-lucide="alert-circle" class="w-5 h-5 text-rose-500"></i>';
                msg.className = 'text-[10px] font-bold mt-2 text-rose-500';
                input.classList.add('border-rose-500');
                input.classList.remove('border-emerald-500');
            }
            lucide.createIcons();
        }
    }

    // ==========================================
    // Initialize BE Datepicker (Flatpickr)
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        const fpConfig = {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            locale: "th",
            onReady: function(selectedDates, dateStr, instance) {
                applyBE(instance);
            },
            onValueUpdate: function(selectedDates, dateStr, instance) {
                applyBE(instance);
            },
            onOpen: function(selectedDates, dateStr, instance) {
                applyBE(instance);
            },
            onMonthChange: function(selectedDates, dateStr, instance) {
                setTimeout(() => applyBE(instance), 1);
            },
            onYearChange: function(selectedDates, dateStr, instance) {
                setTimeout(() => applyBE(instance), 1);
            }
        };
        flatpickr(".datepicker-be", fpConfig);
    });

    // Global Function เพื่อให้เรียกจากนอก Scope ได้
    function applyBE(instance) {
        if (!instance) return;
        
        // 1. แปลงปีในปฏิทิน (ถ้าเปิดอยู่)
        const years = instance.calendarContainer ? instance.calendarContainer.querySelectorAll(".cur-year") : [];
        years.forEach(y => {
            let val = parseInt(y.value);
            if (val > 0 && val < 2400) y.value = val + 543;
        });

        // 2. แปลงปีในช่องกรอกที่โชว์ (altInput)
        if (instance.altInput && instance.selectedDates.length > 0) {
            const d = instance.selectedDates[0];
            const day = d.getDate().toString().padStart(2, '0');
            const month = (d.getMonth() + 1).toString().padStart(2, '0');
            const year = d.getFullYear() + 543;
            instance.altInput.value = `${day}/${month}/${year}`;
        }
    }

</script>
<?= $this->endSection() ?>
