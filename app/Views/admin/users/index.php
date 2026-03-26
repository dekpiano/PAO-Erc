<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

    <div id="status-msg" class="hidden">
        <?php echo session()->getFlashdata('status'); ?>
    </div>

    <div class="glass-card overflow-hidden px-0 pb-0">
        <div class="px-8 pb-6 border-b border-white/5 flex flex-wrap justify-between items-center gap-4">
            <div>
                <h2 class="text-lg font-bold text-white/90 m-0">รายชื่อพนักงานทั้งหมด</h2>
                <p class="text-xs text-white/30 uppercase tracking-widest mt-1">Manage system accounts and access</p>
            </div>
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-500 px-6 py-3 rounded-2xl text-sm font-bold transition-all shadow-lg shadow-blue-900/40 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-5 h-5"></i> เพิ่มพนักงานใหม่
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/[0.02]">
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30">พนักงาน</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30">Username / Email</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30">Role</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.03]">
                    <?php foreach($users as $user): ?>
                    <tr class="hover:bg-white/5 transition-all group">
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/5 flex items-center justify-center text-blue-400 font-black border border-white/5">
                                    <?= strtoupper(substr($user['u_fullname'] ?? 'U', 0, 1)) ?>
                                </div>
                                <p class="font-bold text-white/90"><?= $user['u_fullname'] ?></p>
                            </div>
                        </td>
                        <td class="p-6">
                            <code class="text-xs bg-white/5 px-2 py-1 rounded-lg text-blue-300 font-mono"><?= $user['u_username'] ?></code>
                            <?php if(!empty($user['u_email'])): ?>
                                <p class="text-[10px] text-white/30 mt-1"><?= $user['u_email'] ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="p-6">
                            <span class="text-[10px] font-black uppercase px-3 py-1.5 rounded-full 
                                <?= $user['u_role'] == 'admin' ? 'bg-purple-500/10 text-purple-400' : 
                                   ($user['u_role'] == 'director' ? 'bg-rose-500/10 text-rose-400' : 
                                   ($user['u_role'] == 'head' ? 'bg-amber-500/10 text-amber-500' : 
                                   'bg-blue-500/10 text-blue-400')) ?>">
                                <?= $user['u_role'] ?>
                            </span>
                        </td>
                        <td class="p-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick='editUser(<?= json_encode($user) ?>)' class="p-2 bg-white/5 hover:bg-blue-600 hover:text-white rounded-xl transition-all text-white/40" title="แก้ไข">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                <?php if($user['u_username'] !== 'admin'): ?>
                                <a href="javascript:void(0)" onclick="confirmDelete(<?= $user['u_id'] ?>)" class="p-2 bg-white/5 hover:bg-red-600 hover:text-white rounded-xl transition-all text-white/40" title="ลบ">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-white/[0.02] text-center border-t border-white/5">
            <p class="text-[10px] uppercase font-black text-white/20 tracking-[.3em]">Total <?= count($users) ?> members</p>
        </div>
    </div>

    <!-- Modal -->
    <div id="userModal" class="fixed inset-0 bg-brand-dark/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-6">
        <div class="bg-slate-900 border border-white/10 w-full max-w-md rounded-[2.5rem] p-10 shadow-3xl animate-[fadeIn_0.3s_ease-out]">
            <div class="flex justify-between items-center mb-8">
                <h3 id="modalTitle" class="text-xl font-bold">เพิ่มพนักงานใหม่</h3>
                <button onclick="closeModal()" class="text-white/20 hover:text-white"><i data-lucide="x"></i></button>
            </div>
            
            <form action="<?= base_url('admin/userSave') ?>" method="post" class="space-y-6">
                <input type="hidden" name="u_id" id="u_id">
                
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-white/40 mb-2 ml-2">Username</label>
                    <input type="text" name="u_username" id="u_username" required
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-white/40 mb-2 ml-2">Email (อีเมลส่วนตัว)</label>
                    <input type="email" name="u_email" id="u_email" required
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-white/40 mb-2 ml-2">Full Name (ชื่อ-นามสกุล)</label>
                    <input type="text" name="u_fullname" id="u_fullname" required
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-white/40 mb-2 ml-2">Role (ระดับผู้ใช้)</label>
                    <select name="u_role" id="u_role" required
                            class="w-full bg-slate-800 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-blue-500 transition-all">
                        <option value="user">User (พนักงานทั่วไป)</option>
                        <option value="head">Head (หัวหน้าสายงาน)</option>
                        <option value="director">Director (ผู้อำนวยการ)</option>
                        <option value="admin">Admin (ผู้ดูแลระบบ)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-white/40 mb-2 ml-2">Password (รหัสผ่าน)</label>
                    <input type="password" name="u_password" id="u_password"
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-blue-500 transition-all"
                           placeholder="ปล่อยว่างหากไม่ต้องการเปลี่ยน">
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="closeModal()" class="flex-1 bg-white/5 py-4 rounded-2xl font-bold hover:bg-white/10 transition-all">ยกเลิก</button>
                    <button type="submit" class="flex-1 bg-blue-600 py-4 rounded-2xl font-bold shadow-lg shadow-blue-900/40 hover:bg-blue-500 transition-all">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function openModal() {
        document.getElementById('modalTitle').textContent = 'เพิ่มพนักงานใหม่';
        document.getElementById('u_id').value = '';
        document.getElementById('u_username').value = '';
        document.getElementById('u_email').value = '';
        document.getElementById('u_fullname').value = '';
        document.getElementById('u_role').value = 'user';
        document.getElementById('u_password').required = true;
        document.getElementById('userModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    function editUser(user) {
        document.getElementById('modalTitle').textContent = 'แก้ไขข้อมูลพนักงาน';
        document.getElementById('u_id').value = user.u_id;
        document.getElementById('u_username').value = user.u_username;
        document.getElementById('u_email').value = user.u_email || '';
        document.getElementById('u_fullname').value = user.u_fullname;
        document.getElementById('u_role').value = user.u_role;
        document.getElementById('u_password').required = false;
        document.getElementById('userModal').classList.remove('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบพนักงานคนนี้ออกจากระบบใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#1e293b',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก',
            background: '#1e293b',
            color: '#f8fafc'
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
            showConfirmButton: false,
            background: '#1e293b',
            color: '#f8fafc'
        });
    }
</script>
<?= $this->endSection() ?>
