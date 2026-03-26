<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

    <div id="status-msg" class="hidden">
        <?php echo session()->getFlashdata('status'); ?>
    </div>

    <div class="glass-card overflow-hidden px-0 pb-0">
        <div class="px-8 pb-6 border-b border-white/5 flex flex-wrap justify-between items-center gap-4">
            <div>
                <h2 class="text-xl font-black text-white/90 m-0">ทำเนียบบุคลากร</h2>
                <p class="text-xs text-white/30 uppercase tracking-widest mt-1">Personnel Directory & access management</p>
            </div>
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-500 px-6 py-3 rounded-2xl text-sm font-bold transition-all shadow-lg shadow-blue-900/40 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-5 h-5"></i> เพิ่มบุคลากรใหม่
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/[0.02]">
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30">ลำดับ</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30">บุคลากร</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30">สังกัด / ฝ่าย</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30">การเข้าถึง</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.03]">
                    <?php 
                    // Sort users by u_sort
                    usort($users, function($a, $b) {
                        return $a['u_sort'] - $b['u_sort'];
                    });
                    foreach($users as $user): 
                    ?>
                    <tr class="hover:bg-white/5 transition-all group <?= $user['u_status'] == 'inactive' ? 'opacity-50 grayscale' : '' ?>">
                        <td class="p-6 text-white/20 font-mono text-xs"><?= $user['u_sort'] ?></td>
                        <td class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/5 overflow-hidden flex items-center justify-center text-blue-400 font-black border border-white/5 shrink-0">
                                    <?php if($user['u_photo']): ?>
                                        <img src="<?= base_url('uploads/personnel/' . $user['u_photo']) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <i data-lucide="user" class="w-6 h-6"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-black text-white/90 leading-tight"><?= $user['u_prefix'] ?><?= $user['u_fullname'] ?></p>
                                    <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mt-1"><?= $user['u_position'] ?: 'ไม่ระบุตำแหน่ง' ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full <?= $user['u_division'] == 'ฝ่ายบริหาร' ? 'bg-purple-500 shadow-[0_0_10px_rgba(168,85,247,0.5)]' : 'bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]' ?>"></span>
                                <span class="text-xs font-bold text-white/60"><?= $user['u_division'] ?: 'ยังไม่ได้ระบุ' ?></span>
                            </div>
                            <p class="text-[10px] text-white/20 mt-1"><?= $user['u_phone'] ?: 'ไม่มีเบอร์ติดต่อ' ?></p>
                        </td>
                        <td class="p-6">
                            <div class="flex flex-col gap-2">
                                <div class="flex flex-wrap gap-1">
                                    <?php foreach(explode(',', $user['u_role']) as $role): ?>
                                        <?php 
                                            $borderTheme = 'border-blue-500/20 text-blue-400';
                                            if (trim($role) === 'admin') $borderTheme = 'border-red-500/20 text-red-400';
                                            if (trim($role) === 'superadmin') $borderTheme = 'border-purple-500/30 text-purple-400';
                                        ?>
                                        <span class="w-fit text-[9px] font-black uppercase px-2 py-0.5 rounded bg-blue-500/10 border <?= $borderTheme ?>">
                                            <?= trim($role) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                                <code class="text-[10px] text-white/20 font-mono"><?= $user['u_username'] ?></code>
                            </div>
                        </td>
                        <td class="p-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick='editUser(<?= json_encode($user) ?>)' class="w-10 h-10 flex items-center justify-center bg-white/5 hover:bg-blue-600 hover:text-white rounded-xl transition-all text-white/40" title="แก้ไข">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                <?php if($user['u_username'] !== 'admin'): ?>
                                <button onclick="confirmDelete(<?= $user['u_id'] ?>)" class="w-10 h-10 flex items-center justify-center bg-white/5 hover:bg-red-600 hover:text-white rounded-xl transition-all text-white/40" title="ลบ">
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
        <div class="p-6 bg-white/[0.02] text-center border-t border-white/5">
            <p class="text-[10px] uppercase font-black text-white/20 tracking-[.3em]">Organization Personnel: <?= count($users) ?> members</p>
        </div>
    </div>

    <!-- Modal -->
    <div id="userModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-6">
        <div class="bg-slate-900 border border-white/10 w-full max-w-2xl rounded-[3rem] overflow-hidden shadow-3xl animate-[fadeIn_0.3s_ease-out]">
            <div class="bg-blue-600/10 p-10 flex justify-between items-center border-b border-white/5">
                <div>
                    <h3 id="modalTitle" class="text-2xl font-black text-white">ลงทะเบียนบุคลากร</h3>
                    <p class="text-xs text-blue-400 font-bold uppercase tracking-widest mt-1">Fill in the details for the new staff member</p>
                </div>
                <button onclick="closeModal()" class="w-12 h-12 flex items-center justify-center bg-white/5 rounded-full text-white/20 hover:text-white hover:bg-white/10 transition-all">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <form action="<?= base_url('admin/userSave') ?>" method="post" enctype="multipart/form-data" class="p-10">
                <input type="hidden" name="u_id" id="u_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-1">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-white/40 mb-3 ml-2">คำนำหน้า</label>
                                <select name="u_prefix" id="u_prefix" class="w-full bg-slate-800 border border-white/10 rounded-2xl px-5 py-4 text-white font-bold focus:ring-2 focus:ring-blue-500/50 transition-all">
                                    <option value="นาย">นาย</option>
                                    <option value="นาง">นาง</option>
                                    <option value="นางสาว">นางสาว</option>
                                    <option value="ดร.">ดร.</option>
                                    <option value="ว่าที่ ร.ต.">ว่าที่ ร.ต.</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-white/40 mb-3 ml-2">ชื่อ-นามสกุล</label>
                                <input type="text" name="u_fullname" id="u_fullname" required placeholder="ชื่อ นามสกุล"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white font-bold focus:ring-2 focus:ring-blue-500/50 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-white/40 mb-3 ml-2">ตำแหน่งบริหาร / สายงาน</label>
                            <input type="text" name="u_position" id="u_position" required placeholder="เช่น ผู้อำนวยการกองการศึกษา"
                                class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white font-bold focus:ring-2 focus:ring-blue-500/50 transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-white/40 mb-3 ml-2">สังกัดสำนัก/ฝ่าย</label>
                            <select name="u_division" id="u_division" class="w-full bg-slate-800 border border-white/10 rounded-2xl px-5 py-4 text-white font-bold focus:ring-2 focus:ring-blue-500/50 transition-all">
                                <option value="ผู้บริหาร">ผู้บริหารกอง</option>
                                <option value="ฝ่ายบริหาร">ฝ่ายบริหารงานทั่วไป</option>
                                <option value="ฝ่ายส่งเสริม">ฝ่ายส่งเสริมการศึกษาฯ</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-white/40 mb-3 ml-2">ลำดับการแสดงผล</label>
                                <input type="number" name="u_sort" id="u_sort" value="99"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white font-bold focus:ring-2 focus:ring-blue-500/50 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-white/40 mb-3 ml-2">สถานะ</label>
                                <select name="u_status" id="u_status" class="w-full bg-slate-800 border border-white/10 rounded-2xl px-5 py-4 text-white font-bold focus:ring-2 focus:ring-blue-500/50 transition-all">
                                    <option value="active">Active (ปฏิบัติงาน)</option>
                                    <option value="inactive">Inactive (พ้นสภาพ)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-white/40 mb-3 ml-2">Username / Email</label>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="u_username" id="u_username" required placeholder="User ID"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white font-bold focus:ring-2 focus:ring-blue-500/50 transition-all">
                                <input type="email" name="u_email" id="u_email" placeholder="example@erc.go.th"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white font-bold focus:ring-2 focus:ring-blue-500/50 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-white/40 mb-3 ml-2">สิทธิ์การเข้าถึงระบบ (เลือกได้มากกว่า 1)</label>
                            <div class="grid grid-cols-2 gap-3 mt-2 bg-slate-800 p-4 rounded-2xl border border-white/10">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="u_role[]" value="user" class="role-checkbox w-4 h-4 bg-slate-700 text-blue-500 rounded border-white/20 focus:ring-blue-500/50">
                                    <span class="text-xs font-bold text-white"> พนักงาน (User)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="u_role[]" value="head" class="role-checkbox w-4 h-4 bg-slate-700 text-blue-500 rounded border-white/20 focus:ring-blue-500/50">
                                    <span class="text-xs font-bold text-white"> หัวหน้าฝ่าย (Head)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="u_role[]" value="director" class="role-checkbox w-4 h-4 bg-slate-700 text-blue-500 rounded border-white/20 focus:ring-blue-500/50">
                                    <span class="text-xs font-bold text-white"> ผอ.กอง (Director)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="u_role[]" value="admin" class="role-checkbox w-4 h-4 bg-slate-700 text-red-500 rounded border-white/20 focus:ring-red-500/50">
                                    <span class="text-xs font-bold text-red-400"> ผู้ดูแลระบบ (Admin)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer col-span-2">
                                    <input type="checkbox" name="u_role[]" value="superadmin" class="role-checkbox w-4 h-4 bg-slate-700 text-purple-500 rounded border-white/20 focus:ring-purple-500/50">
                                    <span class="text-xs font-bold text-purple-400"> ผู้ดูแลระบบสูงสุด (Super Admin)</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-white/40 mb-3 ml-2">เบอร์โทรศัพท์ / รหัสผ่าน</label>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="u_phone" id="u_phone" placeholder="0x-xxx-xxxx"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white font-bold focus:ring-2 focus:ring-blue-500/50 transition-all">
                                <input type="password" name="u_password" id="u_password" placeholder="Pass****"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white font-bold focus:ring-2 focus:ring-blue-500/50 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-white/40 mb-3 ml-2">รูปถ่ายบุคลากร</label>
                            <input type="file" name="u_photo" id="u_photo" accept="image/*"
                                class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white font-bold focus:ring-2 focus:ring-blue-500/50 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex gap-4">
                    <button type="button" onclick="closeModal()" class="flex-1 bg-white/5 py-5 rounded-[2rem] font-black uppercase text-xs tracking-widest hover:bg-white/10 transition-all">ยกเลิก</button>
                    <button type="submit" class="flex-1 bg-blue-600 py-5 rounded-[2rem] font-black uppercase text-xs tracking-widest shadow-xl shadow-blue-900/40 hover:bg-blue-500 transition-all">บันทึกข้อมูลบุคลากร</button>
                </div>
            </form>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function openModal() {
        document.getElementById('modalTitle').textContent = 'ลงทะเบียนบุคลากรใหม่';
        document.getElementById('u_id').value = '';
        document.getElementById('u_username').value = '';
        document.getElementById('u_email').value = '';
        document.getElementById('u_fullname').value = '';
        document.getElementById('u_prefix').value = 'นาย';
        document.getElementById('u_position').value = '';
        document.getElementById('u_division').value = 'ฝ่ายบริหาร';
        document.getElementById('u_phone').value = '';
        document.getElementById('u_sort').value = '99';
        document.getElementById('u_status').value = 'active';
        document.querySelectorAll('.role-checkbox').forEach(cb => {
            cb.checked = (cb.value === 'user');
        });
        document.getElementById('u_password').required = true;
        document.getElementById('userModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    function editUser(user) {
        document.getElementById('modalTitle').textContent = 'แก้ไขข้อมูลบุคลากร';
        document.getElementById('u_id').value = user.u_id;
        document.getElementById('u_username').value = user.u_username;
        document.getElementById('u_email').value = user.u_email || '';
        document.getElementById('u_fullname').value = user.u_fullname;
        document.getElementById('u_prefix').value = user.u_prefix || 'นาย';
        document.getElementById('u_position').value = user.u_position || '';
        document.getElementById('u_division').value = user.u_division || 'ฝ่ายบริหาร';
        document.getElementById('u_phone').value = user.u_phone || '';
        document.getElementById('u_sort').value = user.u_sort || '99';
        document.getElementById('u_status').value = user.u_status || 'active';
        const userRoles = (user.u_role || '').split(',').map(r => r.trim());
        document.querySelectorAll('.role-checkbox').forEach(cb => {
            cb.checked = userRoles.includes(cb.value);
        });
        document.getElementById('u_password').required = false;
        document.getElementById('userModal').classList.remove('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลบุคลากรและประวัติการลงเวลาจะยังคงอยู่ในฐานข้อมูลอื่น (หากลบเฉพาะ User) คุณแน่ใจใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#1e293b',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?= base_url('admin/userDelete/') ?>${id}`;
            }
        })
    }

    const statusMsg = document.getElementById('status-msg').textContent.trim();
    if (statusMsg) {
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ!',
            text: statusMsg,
            timer: 2000,
            showConfirmButton: false
        });
    }
</script>
<?= $this->endSection() ?>
