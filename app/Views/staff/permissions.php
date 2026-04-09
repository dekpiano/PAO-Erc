<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
    <div>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">ตั้งค่าสิทธิ์การใช้งาน</h2>
        <p class="text-sm text-slate-400 mt-1 font-medium">กำหนดว่าใครสามารถเข้าถึงและจัดการระบบส่วนไหนได้บ้าง</p>
    </div>
    <div class="bg-blue-50 px-6 py-3 rounded-2xl border border-blue-100 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
            <i data-lucide="shield-check" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest leading-none">Status</p>
            <p class="text-xs font-bold text-blue-900 mt-0.5">ระบบควบคุมสิทธิ์รายบุคคล</p>
        </div>
    </div>
</div>

<!-- Permission Matrix Table -->
<div class="glass-card rounded-[2.5rem] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="p-8 text-[11px] font-black uppercase tracking-widest text-slate-400">บุคลากร</th>
                    <?php foreach($available_permissions as $key => $perms): ?>
                    <th class="p-8 text-center bg-slate-50/50">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center <?= $perms['color'] ?>">
                                <i data-lucide="<?= $perms['icon'] ?>" class="w-5 h-5"></i>
                            </div>
                            <span class="text-[10px] font-black text-slate-800 uppercase tracking-tighter"><?= $perms['label'] ?></span>
                        </div>
                    </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach($users as $user): ?>
                <?php 
                    $userPerms = explode(',', $user['u_role'] ?? ''); 
                    $isSuper = in_array('superadmin', $userPerms);
                ?>
                <tr class="hover:bg-blue-50/20 transition-all">
                    <td class="p-8">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 overflow-hidden shrink-0 border-2 border-slate-50 relative">
                                <?php if(!empty($user['u_photo'])): ?>
                                    <img src="<?= base_url('uploads/personnel/' . $user['u_photo']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-slate-200">
                                        <i data-lucide="user" class="w-8 h-8 text-slate-400"></i>
                                    </div>
                                <?php endif; ?>
                                <?php if($isSuper): ?>
                                <div class="absolute -top-1 -right-1 w-6 h-6 bg-amber-400 rounded-full border-2 border-white flex items-center justify-center text-white" title="Super Admin">
                                    <i data-lucide="crown" class="w-3 h-3"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-black text-lg text-slate-800 leading-none"><?= $user['u_prefix'] ?><?= $user['u_fullname'] ?></p>
                                <p class="text-xs text-blue-600 font-bold mt-1.5 uppercase tracking-wide"><?= $user['u_position'] ?: 'พนักงาน' ?></p>
                                <?php if($isSuper): ?>
                                    <span class="mt-2 inline-block text-[9px] font-black bg-amber-50 text-amber-600 border border-amber-100 px-2 py-0.5 rounded-md uppercase tracking-widest">Master Admin</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    
                    <?php foreach($available_permissions as $key => $perms): ?>
                    <td class="p-8 text-center align-middle">
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" 
                                   onchange="togglePermission(<?= $user['u_id'] ?>, '<?= $key ?>', this)"
                                   <?= in_array($key, $userPerms) || $isSuper ? 'checked' : '' ?>
                                   <?= $isSuper && ($key == 'settings' || $key == 'summary') ? 'disabled' : '' ?>
                                   class="sr-only peer">
                            <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-6 after:transition-all peer-checked:bg-blue-600 group-hover:ring-4 group-hover:ring-blue-100 transition-all"></div>
                        </label>
                        <?php if($isSuper && in_array($key, ['settings', 'summary'])): ?>
                            <p class="text-[8px] font-black text-slate-400 mt-2 uppercase">Locked</p>
                        <?php endif; ?>
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8 flex justify-end">
    <p class="text-sm text-slate-400 italic flex items-center gap-2">
        <i data-lucide="info" class="w-4 h-4"></i>
        * ระบบจะบันทึกสิทธิ์อัตโนมัติเมื่อกดสลับปุ่ม
    </p>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    async function togglePermission(userId, permissionKey, checkbox) {
        // Find all checkboxes for this user to get current set
        const row = checkbox.closest('tr');
        const checkboxes = row.querySelectorAll('input[type="checkbox"]:checked');
        const currentPermissions = Array.from(checkboxes).map(cb => {
            // We need to find the key. The easiest is to store it in a data attribute
            // but for now we can infer from the loop or just send the action to server
            // Let's use a simpler approach: Send the toggle action to server.
        });

        // Better approach: Server handles adding/removing the specific key
        const formData = new FormData();
        formData.append('u_id', userId);
        
        // Collect all checked ones in the row
        const rowCheckboxes = row.querySelectorAll('input[type="checkbox"]');
        let index = 0;
        const availableKeys = <?= json_encode(array_keys($available_permissions)) ?>;
        
        rowCheckboxes.forEach((cb, idx) => {
            if (cb.checked) {
                formData.append('permissions[]', availableKeys[idx]);
            }
        });

        try {
            const response = await fetch('<?= base_url('staff/permissionsUpdate') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            const data = await response.json();
            
            if (data.status === 'success') {
                // Flash success color on the row
                row.classList.add('bg-emerald-50');
                setTimeout(() => row.classList.remove('bg-emerald-50'), 500);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ล้มเหลว',
                    text: data.message,
                    customClass: { popup: 'rounded-[2rem]' }
                });
                checkbox.checked = !checkbox.checked; // Revert
            }
        } catch (error) {
            console.error('Error:', error);
            checkbox.checked = !checkbox.checked; // Revert
        }
    }
</script>
<?= $this->endSection() ?>
