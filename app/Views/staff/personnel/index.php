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

<!-- Personnel Table -->
<div class="glass-card rounded-[2rem] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400">ลำดับ</th>
                    <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400">บุคลากร</th>
                    <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400">สังกัด/ฝ่าย</th>
                    <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400">ข้อมูลระบบ</th>
                    <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400">สิทธิ์ระบบ</th>
                    <th class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach($users as $user): ?>
                <tr class="hover:bg-blue-50/30 transition-all <?= ($user['u_status'] ?? 'active') == 'inactive' ? 'opacity-40' : '' ?>">
                    <td class="p-5 text-slate-300 font-mono text-xs font-black"><?= $user['u_sort'] ?? 99 ?></td>
                    <td class="p-5">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 overflow-hidden flex items-center justify-center shrink-0 border-2 border-slate-50">
                                <?php if(!empty($user['u_photo'])): ?>
                                    <img src="<?= base_url('uploads/personnel/' . $user['u_photo']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i data-lucide="user" class="w-7 h-7 text-slate-300"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-black text-slate-800 leading-tight"><?= $user['u_prefix'] ?? '' ?><?= $user['u_fullname'] ?></p>
                                <p class="text-[11px] text-blue-600 font-bold mt-1">
                                    <?= $user['position_name'] ?: ($user['u_position'] ?: 'ไม่ระบุตำแหน่ง') ?> 
                                    <?php if(!empty($user['u_level']) && $user['u_level'] !== 'ไม่มีระดับ'): ?>
                                        <span class="text-slate-400 font-medium">(<?= $user['u_level'] ?>)</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="p-5">
                        <?php 
                            $divColor = match($user['u_division'] ?? '') {
                                'ผู้บริหาร' => 'bg-purple-50 text-purple-600 border-purple-100',
                                'ฝ่ายบริหาร' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'ฝ่ายส่งเสริม' => 'bg-amber-50 text-amber-600 border-amber-100',
                                default => 'bg-slate-50 text-slate-500 border-slate-100'
                            };
                        ?>
                        <span class="text-[10px] font-black px-3 py-1.5 rounded-full border <?= $divColor ?>">
                            <?= $user['u_division'] ?: 'ยังไม่ระบุ' ?>
                        </span>
                        <?php if(!empty($user['u_phone'])): ?>
                            <p class="text-[10px] text-slate-400 mt-2"><i data-lucide="phone" class="w-3 h-3 inline"></i> <?= $user['u_phone'] ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="p-5">
                        <code class="text-[10px] bg-slate-100 px-2 py-1 rounded-lg text-slate-500 font-mono"><?= $user['u_email'] ?: 'No Email' ?></code>
                        <div class="flex flex-wrap gap-1 mt-2">
                            <?php foreach(explode(',', $user['u_role']) as $role): ?>
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md">
                                    <?= trim($role) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td class="p-5">
                        <a href="<?= base_url('staff/permissions#user-'.$user['u_id']) ?>" class="inline-flex items-center gap-2 text-[10px] font-black text-amber-600 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition-all">
                            <i data-lucide="shield-lock" class="w-3.5 h-3.5"></i> ตั้งค่าสิทธิ์
                        </a>
                    </td>
                    <td class="p-5 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick='editUser(<?= json_encode($user) ?>)' class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all" title="แก้ไข">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            <?php if($user['u_username'] !== 'admin'): ?>
                            <button onclick="confirmDelete(<?= $user['u_id'] ?>)" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-600 hover:text-white rounded-xl transition-all" title="ลบ">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="userModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[70] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-3xl rounded-[2.5rem] overflow-hidden shadow-2xl animate-[fadeIn_0.3s_ease-out]">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-white">
            <h3 id="modalTitle" class="text-2xl font-black">เพิ่มบุคลากรใหม่</h3>
            <p class="text-blue-200 text-xs font-bold uppercase tracking-widest mt-1">Personnel Registration</p>
        </div>
        
        <form id="personnel-form" action="<?= base_url('staff/personnel/save') ?>" method="post" enctype="multipart/form-data" class="p-8">
            <input type="hidden" name="u_id" id="u_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                
                <!-- ชื่อ -->
                <div class="md:col-span-2 grid grid-cols-4 gap-4">
                    <div class="col-span-1">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">คำนำหน้า</label>
                        <select name="u_prefix" id="u_prefix" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นางสาว">นางสาว</option>
                            <option value="ดร.">ดร.</option>
                            <option value="ว่าที่ ร.ต.">ว่าที่ ร.ต.</option>
                        </select>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ชื่อ-นามสกุล</label>
                        <input type="text" name="u_fullname" id="u_fullname" required placeholder="ชื่อ นามสกุล"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <!-- ตำแหน่ง -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ตำแหน่งงาน (เลือกจากฐานข้อมูล)</label>
                    <select name="u_pos_id" id="u_pos_id" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all bg-slate-50">
                        <option value="">-- เลือกตำแหน่งงาน --</option>
                        <?php foreach($positions as $pos): ?>
                            <option value="<?= $pos['pos_id'] ?>"><?= esc($pos['pos_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!-- เก็บฟิลด์เก่าไว้ซ่อนเผื่อกรณีพิมพ์เอง (Optional) -->
                    <input type="hidden" name="u_position" id="u_position">
                </div>

                <!-- ฝ่าย -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">สังกัด / ฝ่าย</label>
                    <select name="u_division" id="u_division" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                        <option value="ผู้บริหาร">ผู้บริหารกอง</option>
                        <option value="ฝ่ายบริหาร">ฝ่ายบริหารงานทั่วไป</option>
                        <option value="ฝ่ายส่งเสริม">ฝ่ายส่งเสริมการศึกษาฯ</option>
                    </select>
                </div>

                <!-- ระดับตำแหน่ง -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ระดับ / วิทยฐานะ</label>
                    <select name="u_level" id="u_level" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                        <option value="ไม่มีระดับ">- ไม่มีระดับ -</option>
                        <option value="ปฏิบัติงาน">ปฏิบัติงาน (ปง.)</option>
                        <option value="ชำนาญงาน">ชำนาญงาน (ชง.)</option>
                        <option value="อาวุธโส">อาวุโส</option>
                        <option value="ทักษะพิเศษ">ทักษะพิเศษ</option>
                        <option value="ปฏิบัติการ">ปฏิบัติการ (ปก.)</option>
                        <option value="ชำนาญการ">ชำนาญการ (ชก.)</option>
                        <option value="ชำนาญการพิเศษ">ชำนาญการพิเศษ (ชพ.)</option>
                        <option value="เชี่ยวชาญ">เชี่ยวชาญ (ชช.)</option>
                        <option value="ทรงคุณวุฒิ">ทรงคุณวุฒิ</option>
                        <option value="ลูกจ้างประจำ">ลูกจ้างประจำ</option>
                        <option value="พนักงานจ้างตามภารกิจ">พนักงานจ้างตามภารกิจ</option>
                        <option value="พนักงานจ้างทั่วไป">พนักงานจ้างทั่วไป</option>
                    </select>
                </div>

                <!-- Email (ใช้สำหรับ Google Login) -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Email (สำหรับล็อกอิน Google)</label>
                    <input type="email" name="u_email" id="u_email" required placeholder="example@erc.go.th"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-blue-600 font-black focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                </div>



                <!-- เบอร์โทร -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">เบอร์โทรศัพท์</label>
                    <input type="text" name="u_phone" id="u_phone" placeholder="0x-xxx-xxxx"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                </div>

                <!-- ลำดับ + สถานะ -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">ลำดับแสดงผล</label>
                        <input type="number" name="u_sort" id="u_sort" value="99"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">สถานะ</label>
                        <select name="u_status" id="u_status" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                            <option value="active">ปฏิบัติงาน</option>
                            <option value="inactive">พ้นสภาพ</option>
                        </select>
                    </div>
                </div>



                <!-- รูปภาพ -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">รูปถ่ายบุคลากร (ลากมาวางที่นี่ได้)</label>
                    <div id="photo-dropzone" class="relative group">
                        <input type="file" name="u_photo" id="u_photo_input" accept="image/*" onchange="previewUserPhoto(this)"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 bg-slate-50/30">
                        <div id="photo-preview-container" class="mt-4 hidden items-center gap-4 p-2 bg-blue-50 rounded-2xl border border-blue-100 animate-[fadeIn_0.3s_ease-out]">
                            <img id="photo-preview" src="" class="w-20 h-20 rounded-xl object-cover shadow-sm">
                            <div>
                                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">รูปภาพที่เลือก</p>
                                <p id="photo-filename" class="text-xs font-bold text-slate-600 truncate max-w-[200px]"></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Buttons -->
            <div class="mt-10 flex gap-4">
                <button type="button" onclick="closeModal()" class="flex-1 bg-slate-100 text-slate-600 py-4 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all">ยกเลิก</button>
                <button type="submit" id="submit-btn" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-600/20 hover:bg-blue-700 transition-all disabled:opacity-50">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function openModal() {
        document.getElementById('modalTitle').textContent = 'เพิ่มบุคลากรใหม่';
        document.getElementById('u_id').value = '';
        document.getElementById('u_email').value = '';
        document.getElementById('u_fullname').value = '';
        document.getElementById('u_prefix').value = 'นาย';
        document.getElementById('u_position').value = '';
        document.getElementById('u_level').value = 'ไม่มีระดับ';
        document.getElementById('u_division').value = 'ฝ่ายบริหาร';
        document.getElementById('u_phone').value = '';
        document.getElementById('u_sort').value = '99';
        document.getElementById('u_status').value = 'active';
        
        // Reset Photo Preview
        document.getElementById('photo-preview-container').classList.add('hidden');
        document.getElementById('u_photo_input').value = '';
        
        document.getElementById('userModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    function editUser(user) {
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

        // Current photo preview if exists
        const previewContainer = document.getElementById('photo-preview-container');
        const previewImg = document.getElementById('photo-preview');
        const filenameLabel = document.getElementById('photo-filename');
        
        if (user.u_photo) {
            previewImg.src = `<?= base_url('uploads/personnel/') ?>${user.u_photo}`;
            filenameLabel.textContent = 'รูปโปรไฟล์เดิม';
            previewContainer.classList.remove('hidden');
        } else {
            previewContainer.classList.add('hidden');
        }

        document.getElementById('userModal').classList.remove('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบบุคลากรคนนี้ออกจากระบบใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก',
            customClass: { popup: 'rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?= base_url('staff/personnel/delete/') ?>${id}`;
            }
        })
    }

    // Form Submission with AJAX & Chunking
    document.getElementById('personnel-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const btn = document.getElementById('submit-btn');
        const originalBtnContent = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin inline mr-2"></i> กำลังเตรียมข้อมูล...`;
        lucide.createIcons();

        try {
            // 1. Handle Photo Chunked Upload
            let tempPhoto = null;
            const photoInput = document.getElementById('u_photo_input');
            if (photoInput.files && photoInput.files[0]) {
                const photoFile = photoInput.files[0];
                btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin inline mr-2"></i> กำลังอัปโหลดรูปถ่าย...`;
                tempPhoto = await uploadFileInChunks(photoFile);
            }

            // 2. Final Submission
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin inline mr-2"></i> กำลังบันทึก...`;
            const formData = new FormData(form);
            
            if (tempPhoto) {
                formData.delete('u_photo');
                formData.append('temp_photo', tempPhoto);
            }

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Server responded with non-JSON format:', text);
                throw new Error('NON_JSON');
            }

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[1.5rem]' }
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                throw new Error(data.message || 'บันทึกข้อมูลไม่สำเร็จ');
            }

        } catch (error) {
            console.error('Error:', error);
            btn.disabled = false;
            btn.innerHTML = originalBtnContent;
            lucide.createIcons();
            
            let errorTitle = 'เกิดข้อผิดพลาด';
            let errorMsg = 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้ กรุณาลองใหม่อีกครั้ง';

            if (error.message === '413_TOO_LARGE') {
                errorTitle = 'รูปภาพมีขนาดใหญ่เกินไป';
                errorMsg = 'รูปถ่ายมีขนาดใหญ่เกินความสามารถของเซิร์ฟเวอร์ กรุณาย่อขนาดรูปภาพก่อนอัปโหลด';
            }

            Swal.fire({
                icon: 'error',
                title: errorTitle,
                text: errorMsg,
                customClass: { popup: 'rounded-[1.5rem]' }
            });
        }
    });

    async function uploadFileInChunks(file) {
        const chunkSize = 1024 * 512; // 512KB per chunk (Further decreased to fix persistent 413)
        const totalChunks = Math.ceil(file.size / chunkSize);
        const fileId = Math.random().toString(36).substring(2, 11) + Date.now();
        const extension = file.name.split('.').pop();
        const filename = fileId + '.' + extension;

        for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
            const start = chunkIndex * chunkSize;
            const end = Math.min(start + chunkSize, file.size);
            const chunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('file', chunk);
            formData.append('filename', filename);
            formData.append('chunkIndex', chunkIndex);
            formData.append('totalChunks', totalChunks);
            formData.append('fileId', fileId);

            const response = await fetch('<?= base_url('staff/news/uploadChunk') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            if (result.status === 'error') {
                throw new Error(result.message);
            }
            if (result.status === 'completed') {
                return result.temp_file;
            }
        }
    }

    // Personnel Photo Preview & Drag and Drop
    function previewUserPhoto(input) {
        const container = document.getElementById('photo-preview-container');
        const preview = document.getElementById('photo-preview');
        const filename = document.getElementById('photo-filename');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                filename.textContent = input.files[0].name;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Drag and Drop for Personnel Photo
    const dropzone = document.getElementById('photo-dropzone');
    const photoInput = document.getElementById('u_photo_input');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
             photoInput.classList.add('border-blue-400', 'bg-blue-50/50');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
             photoInput.classList.remove('border-blue-400', 'bg-blue-50/50');
        }, false);
    });

    dropzone.addEventListener('drop', e => {
        photoInput.files = e.dataTransfer.files;
        previewUserPhoto(photoInput);
    }, false);
</script>
<?= $this->endSection() ?>
