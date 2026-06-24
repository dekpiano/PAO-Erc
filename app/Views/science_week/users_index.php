<?= $this->extend('science_week/layout/admin') ?>

<?= $this->section('content') ?>
<style>
    .glass-table-card {
        background: rgba(17, 25, 40, 0.65) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
    }
    .neon-input-sci {
        background: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        color: #f1f5f9 !important;
        transition: all 0.3s ease;
    }
    .neon-input-sci:focus {
        border-color: #22d3ee !important;
        box-shadow: 0 0 15px rgba(34, 211, 238, 0.25) !important;
        outline: none;
    }
    .custom-modal {
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(24px);
        border: 1px solid rgba(99, 102, 241, 0.2);
    }
</style>

<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
            <i data-lucide="shield-check" class="w-8 h-8 text-cyan-400"></i>
            <span>จัดการสิทธิ์เจ้าหน้าที่วิทยาศาสตร์</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-450 mt-1">กำหนดสิทธิ์และเพิ่มรายชื่อบัญชีเจ้าหน้าที่สำหรับช่วยดูแลระบบสัปดาห์วิทยาศาสตร์ โดยใช้งานผ่าน Google Login ด้วยอีเมล</p>
    </div>
    
    <button onclick="openAddModal()" class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-605 hover:to-indigo-600 text-white font-bold text-xs sm:text-sm transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-indigo-950/30">
        <i data-lucide="user-plus" class="w-4 h-4"></i> เพิ่มเจ้าหน้าที่ใหม่
    </button>
</div>

<!-- Main Table -->
<div class="glass-table-card rounded-[2rem] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-800 bg-slate-950/50">
                    <th class="p-6 text-xs font-bold text-slate-400 uppercase tracking-widest">ชื่อ-นามสกุล</th>
                    <th class="p-6 text-xs font-bold text-slate-400 uppercase tracking-widest">สถานะสิทธิ์การใช้งาน</th>
                    <th class="p-6 text-xs font-bold text-slate-400 uppercase tracking-widest">อีเมลสำหรับ Google Login</th>
                    <th class="p-6 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" class="p-16 text-center">
                            <i data-lucide="users" class="w-12 h-12 text-slate-600 mx-auto mb-3 opacity-40"></i>
                            <p class="text-xs text-slate-500 font-medium">ยังไม่มีการเพิ่มรายชื่อเจ้าหน้าที่วิทยาศาสตร์</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-slate-900/20 transition-colors">
                            <td class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 overflow-hidden flex items-center justify-center shrink-0">
                                        <?php if (!empty($user['u_photo'])): ?>
                                            <img src="<?= base_url('uploads/personnel/' . $user['u_photo']) ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i data-lucide="user" class="w-5 h-5 text-slate-550"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-200"><?= esc($user['u_fullname']) ?></p>
                                        <p class="text-[9px] text-slate-500 font-mono mt-0.5">UID: #<?= $user['u_id'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6 text-xs font-medium">
                                <?php 
                                    $roles = $user['u_role'] ?? '';
                                    if (strpos($roles, 'superadmin') !== false) {
                                        echo '<span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">ผู้ดูแลระบบสูงสุด (Superadmin)</span>';
                                    } elseif (strpos($roles, 'admin') !== false) {
                                        echo '<span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">ผู้ดูแลระบบ (Admin)</span>';
                                    } else {
                                        echo '<span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">เจ้าหน้าที่ระบบ (Staff)</span>';
                                    }
                                ?>
                            </td>
                            <td class="p-6 text-xs text-cyan-400 font-mono font-bold">
                                <?= esc($user['u_email']) ?>
                            </td>
                            <td class="p-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='openEditModal(<?= json_encode($user) ?>)' class="p-2 bg-indigo-500/10 hover:bg-indigo-600 border border-indigo-500/20 hover:border-indigo-500 text-indigo-300 hover:text-white rounded-xl transition-all" title="แก้ไข">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="confirmDelete(<?= $user['u_id'] ?>, '<?= esc($user['u_fullname']) ?>')" class="p-2 bg-rose-500/10 hover:bg-rose-600 border border-rose-500/20 hover:border-rose-500 text-rose-300 hover:text-white rounded-xl transition-all" title="ลบ">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Structure -->
<div id="userModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md hidden">
    <div class="w-full max-w-lg custom-modal rounded-3xl overflow-hidden shadow-2xl p-6 sm:p-8">
        
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-800">
            <h3 id="modalTitle" class="text-sm sm:text-base font-extrabold text-white flex items-center gap-2">
                <i data-lucide="user-cog" class="w-5 h-5 text-indigo-400"></i>
                <span>เพิ่มเจ้าหน้าที่วิทยาศาสตร์</span>
            </h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <form id="userForm" onsubmit="handleFormSubmit(event)">
            <input type="hidden" name="u_id" id="field_u_id">
            
            <div class="space-y-4">
                <!-- Fullname -->
                <div>
                    <label for="field_u_fullname" class="block text-[10px] sm:text-xs font-bold text-slate-350 mb-2">ชื่อ-นามสกุล <span class="text-rose-455">*</span></label>
                    <input type="text" name="u_fullname" id="field_u_fullname" required class="w-full px-4 py-3 text-xs rounded-2xl neon-input-sci outline-none">
                </div>

                <!-- Email -->
                <div>
                    <label for="field_u_email" class="block text-[10px] sm:text-xs font-bold text-slate-350 mb-2">อีเมล (สำหรับ Google Login) <span class="text-rose-455">*</span></label>
                    <input type="email" name="u_email" id="field_u_email" required class="w-full px-4 py-3 text-xs rounded-2xl neon-input-sci outline-none" placeholder="example@gmail.com หรือเมลโดเมนองค์กร">
                </div>

                <!-- Status/Role Selection -->
                <div>
                    <label for="field_u_role" class="block text-[10px] sm:text-xs font-bold text-slate-350 mb-2">สถานะสิทธิ์การใช้งาน <span class="text-rose-455">*</span></label>
                    <select name="u_role" id="field_u_role" required class="w-full px-4 py-3 text-xs rounded-2xl neon-input-sci outline-none">
                        <option value="science_week">เจ้าหน้าที่ระบบ (Staff)</option>
                        <option value="science_week,admin">ผู้ดูแลระบบ (Admin)</option>
                        <option value="superadmin">ผู้ดูแลระบบสูงสุด (Superadmin)</option>
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 pt-4 border-t border-slate-800/80 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-5 py-3 rounded-2xl bg-slate-850 hover:bg-slate-800 text-slate-300 font-bold text-xs transition-colors border border-slate-750">ยกเลิก</button>
                <button type="submit" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold text-xs transition-all shadow-lg shadow-emerald-950/20">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<script>
    let isEditMode = false;

    function openAddModal() {
        isEditMode = false;
        document.getElementById('modalTitle').querySelector('span').innerText = 'เพิ่มเจ้าหน้าที่วิทยาศาสตร์';
        document.getElementById('userForm').reset();
        document.getElementById('field_u_id').value = '';

        document.getElementById('userModal').classList.remove('hidden');
    }

    function openEditModal(user) {
        isEditMode = true;
        document.getElementById('modalTitle').querySelector('span').innerText = 'แก้ไขข้อมูลเจ้าหน้าที่';
        
        // Populate inputs
        document.getElementById('field_u_id').value = user.u_id;
        document.getElementById('field_u_fullname').value = user.u_fullname;
        document.getElementById('field_u_email').value = user.u_email || '';
        
        // Determine role value for dropdown selection
        let roleVal = 'science_week';
        if (user.u_role) {
            if (user.u_role.includes('superadmin')) {
                roleVal = 'superadmin';
            } else if (user.u_role.includes('admin')) {
                roleVal = 'science_week,admin';
            }
        }
        document.getElementById('field_u_role').value = roleVal;

        document.getElementById('userModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    function handleFormSubmit(event) {
        event.preventDefault();
        
        const form = document.getElementById('userForm');
        const formData = new FormData(form);
        const userId = document.getElementById('field_u_id').value;

        // Determine URL
        const url = isEditMode 
            ? `<?= base_url('staff/science-week/users/update') ?>/${userId}`
            : '<?= base_url('staff/science-week/users/store') ?>';

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ',
                    text: data.message,
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'glass-card rounded-[2rem]' }
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: data.message,
                    background: getSwalColors().bg,
                    color: getSwalColors().text,
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'glass-card rounded-[2rem]' }
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้',
                background: getSwalColors().bg,
                color: getSwalColors().text,
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'glass-card rounded-[2rem]' }
            });
        });
    }

    function confirmDelete(userId, fullname) {
        Swal.fire({
            title: 'ยืนยันการลบสิทธิ์?',
            text: `ต้องการยกเลิกสิทธิ์และบัญชีใช้งานของ ${fullname} หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            background: getSwalColors().bg,
            color: getSwalColors().text,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#3b82f6',
            confirmButtonText: 'ใช่, ต้องการลบ',
            cancelButtonText: 'ยกเลิก',
            customClass: { popup: 'glass-card rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`<?= base_url('staff/science-week/users/delete') ?>/${userId}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบสำเร็จ',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#3b82f6',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ล้มเหลว',
                            text: data.message,
                            background: getSwalColors().bg,
                            color: getSwalColors().text,
                            confirmButtonColor: '#ef4444',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        });
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
