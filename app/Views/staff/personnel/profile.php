<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
    <div class="mb-10 animate-[fadeIn_0.5s_ease-out]">
        <h2 class="text-3xl font-black text-slate-900 mb-2">ข้อมูลส่วนตัว</h2>
        <p class="text-slate-400 font-medium tracking-wide flex items-center gap-2 uppercase text-xs">
            <i data-lucide="user-cog" class="w-4 h-4 text-indigo-600"></i> Manage your personal profile & P.K. 7 records
        </p>
    </div>

    <form id="profile-form" action="<?= base_url('staff/profile/save') ?>" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden">
            
            <!-- Tabs Navigation -->
            <div class="flex border-b border-slate-100 px-8 pt-6">
                <button type="button" onclick="switchTab('basic')" id="tab-basic" 
                    class="px-6 py-4 text-sm font-black border-b-2 border-blue-600 text-blue-600 transition-all">
                    ข้อมูลพื้นฐาน
                </button>
                <button type="button" onclick="switchTab('detailed')" id="tab-detailed" 
                    class="px-6 py-4 text-sm font-black border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">
                    ทะเบียนประวัติ (พ.ค. 7)
                </button>
            </div>

            <div class="p-8 md:p-12">
                <!-- Tab 1: Basic Info -->
                <div id="pane-basic" class="tab-pane animate-[fadeIn_0.3s_ease-out]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Photo Section -->
                        <div class="md:col-span-2 flex flex-col md:flex-row items-center gap-8 p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
                            <div class="relative group">
                                <div class="w-32 h-32 rounded-[2rem] border-4 border-white shadow-xl overflow-hidden bg-white">
                                    <img id="photo-preview" src="<?= $user['u_photo'] ? base_url('uploads/personnel/'.$user['u_photo']) : 'https://ui-avatars.com/api/?name='.urlencode($user['u_fullname']).'&background=random' ?>" 
                                        class="w-full h-full object-cover">
                                </div>
                                <label for="u_photo" class="absolute -bottom-2 -right-2 w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center cursor-pointer shadow-lg hover:bg-blue-700 transition-all">
                                    <i data-lucide="camera" class="w-5 h-5"></i>
                                    <input type="file" name="u_photo" id="u_photo" class="hidden" onchange="previewUserPhoto(this)">
                                </label>
                            </div>
                            <div class="flex-1 text-center md:text-left">
                                <h4 class="text-xl font-black text-slate-900 mb-1"><?= $user['u_fullname'] ?></h4>
                                <p class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-4"><?= $user['position_name'] ?></p>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed max-w-sm">คุณสามารถอัปเดตรูปถ่ายและข้อมูลเบื้องต้นของท่านได้ในส่วนนี้ รูปภาพจะแสดงในหน้าทำเนียบและรายงานต่างๆ</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:col-span-2">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">คำนำหน้าชื่อ</label>
                                <select name="u_prefix" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-100 transition-all">
                                    <?php foreach(['นาย','นาง','นางสาว','ดร.'] as $p): ?>
                                        <option value="<?= $p ?>" <?= $user['u_prefix'] == $p ? 'selected' : '' ?>><?= $p ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ชื่อ-นามสกุล</label>
                                <input type="text" name="u_fullname" value="<?= $user['u_fullname'] ?>" required 
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-100 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">เบอร์โทรศัพท์</label>
                            <input type="text" name="u_phone" value="<?= $user['u_phone'] ?>"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-100 transition-all">
                        </div>

                        <div class="md:col-span-2">
                             <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 flex gap-4 items-start">
                                <i data-lucide="info" class="w-5 h-5 text-amber-500 shrink-0"></i>
                                <p class="text-[11px] font-bold text-amber-700 leading-relaxed uppercase">
                                    ข้อมูลสิทธิ์การใช้งานและฝ่ายงาน ต้องดำเนินการผ่านผู้ดูแลระบบเท่านั้น หากข้อมูลไม่ถูกต้องกรุณาติดต่อแอดมินกองการศึกษาฯ
                                </p>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Detailed Info (P.K. 7) -->
                <div id="pane-detailed" class="tab-pane hidden animate-[fadeIn_0.3s_ease-out]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">เลขประจำตัวประชาชน (13 หลัก)</label>
                            <div class="relative">
                                <input type="text" name="u_id_card" id="u_id_card" value="<?= $user['u_id_card'] ?>" oninput="validateThaiID(this.value)" maxlength="13" 
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-0 transition-all tracking-[0.2em]">
                                <div id="id-validation-icon" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                                </div>
                            </div>
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
                                <select name="u_blood_type" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                                    <option value="">- ระบุ -</option>
                                    <?php foreach(['A','B','AB','O'] as $b): ?>
                                        <option value="<?= $b ?>" <?= $user['u_blood_type'] == $b ? 'selected' : '' ?>><?= $b ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ศาสนา</label>
                                <select name="u_religion" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                                    <?php foreach(['พุทธ','คริสต์','อิสลาม','ซิกข์','ฮินดู','อื่นๆ'] as $r): ?>
                                        <option value="<?= $r ?>" <?= $user['u_religion'] == $r ? 'selected' : '' ?>><?= $r ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">สัญชาติ</label>
                                <select name="u_nationality" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                                    <?php foreach(['ไทย','จีน','อเมริกัน','ลาว','อื่นๆ'] as $n): ?>
                                        <option value="<?= $n ?>" <?= $user['u_nationality'] == $n ? 'selected' : '' ?>><?= $n ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="md:col-span-2 text-slate-400 border-t pt-6 mb-2 mt-4 font-black uppercase tracking-[0.2em] text-[9px]">ที่อยู่และการติดต่อ</div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ติดต่อกรณีฉุกเฉิน (ชื่อ-เบอร์โทร)</label>
                            <input type="text" name="u_emergency_contact" value="<?= $user['u_emergency_contact'] ?>" placeholder="ระบุชื่อผู้ติดต่อ และเบอร์โทรศัพท์"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ที่อยู่ตามทะเบียนบ้าน</label>
                            <textarea name="u_address" rows="2" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold bg-slate-50/30"><?= $user['u_address'] ?></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ที่อยู่ปัจจุบัน</label>
                            <textarea name="u_current_address" rows="2" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold"><?= $user['u_current_address'] ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="p-8 md:p-12 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" id="submit-btn" class="w-full md:w-auto px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all flex items-center justify-center gap-3">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i> บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </div>
    </form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // รอให้ปฏิทินหลักเตรียมตัวเสร็จก่อน
        setTimeout(() => {
            const fpBirthday = document.querySelector("#u_birthday")._flatpickr;
            const fpHired = document.querySelector("#u_hired_date")._flatpickr;
            
            if (fpBirthday) {
                fpBirthday.setDate('<?= $user['u_birthday'] ?>' || null);
                applyBE(fpBirthday);
            }
            if (fpHired) {
                fpHired.setDate('<?= $user['u_hired_date'] ?>' || null);
                applyBE(fpHired);
            }
        }, 300); // เพิ่มเวลาเป็น 300ms เพื่อความแน่นอน

        validateThaiID('<?= $user['u_id_card'] ?>');
    });

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

    function previewUserPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('photo-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function validateThaiID(id) {
        const iconContainer = document.getElementById('id-validation-icon');
        const input = document.getElementById('u_id_card');

        if (!id || id.length !== 13) {
            iconContainer.classList.add('hidden');
            input.classList.remove('border-emerald-500', 'border-rose-500');
            return;
        }

        let sum = 0;
        for(let i=0; i < 12; i++) sum += parseInt(id.charAt(i)) * (13 - i);
        let check = (11 - (sum % 11)) % 10;
        
        if (check === parseInt(id.charAt(12))) {
            iconContainer.classList.remove('hidden');
            input.classList.add('border-emerald-500');
            input.classList.remove('border-rose-500');
        } else {
            iconContainer.classList.add('hidden');
            input.classList.add('border-rose-500');
            input.classList.remove('border-emerald-500');
        }
    }

    document.getElementById('profile-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('submit-btn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i> กำลังบันทึก...';
        lucide.createIcons();

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => { window.location.reload(); });
            } else throw new Error(data.message);
        } catch (error) {
            btn.disabled = false;
            btn.innerHTML = originalText;
            lucide.createIcons();
            Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: error.message });
        }
    });
    </script>
<?= $this->endSection() ?>
