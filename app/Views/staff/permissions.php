<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>

<!-- Header Section -->
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
    <div>
        <div class="flex items-center gap-3">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">ตั้งค่าสิทธิ์การใช้งานระบบ</h2>
            <span class="bg-blue-100 text-blue-700 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider">Matrix View</span>
        </div>
        <p class="text-sm text-slate-500 mt-1 font-medium">จัดการสิทธิ์การเข้าถึงเมนูและระบบย่อยต่างๆ สำหรับบุคลากรแต่ละคน</p>
    </div>

    <!-- Quick Stats Cards -->
    <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
        <div class="glass-card px-5 py-3 rounded-2xl flex items-center gap-3 bg-white/80 border border-slate-200/80 shadow-sm">
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">บุคลากรทั้งหมด</p>
                <p class="text-lg font-black text-slate-800 mt-1" id="stat-total-users"><?= count($users) ?> คน</p>
            </div>
        </div>

        <div class="glass-card px-5 py-3 rounded-2xl flex items-center gap-3 bg-white/80 border border-slate-200/80 shadow-sm">
            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center font-bold">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">รายการสิทธิ์ระบบ</p>
                <p class="text-lg font-black text-slate-800 mt-1"><?= count($available_permissions) ?> สิทธิ์</p>
            </div>
        </div>
    </div>
</div>

<!-- Controls & Filter Toolbar -->
<div class="glass-card rounded-3xl p-5 mb-8 bg-white border border-slate-200/80 shadow-sm">
    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Search Box -->
        <div class="relative flex-1 max-w-md">
            <i data-lucide="search" class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" id="searchInput" onkeyup="filterUsers()" placeholder="ค้นหาชื่อ-นามสกุล, ตำแหน่ง..." 
                   class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
        </div>

        <!-- Mode Toggle & Filter Status -->
        <div class="flex items-center gap-3">
            <div class="flex items-center bg-slate-100 p-1 rounded-2xl border border-slate-200">
                <button onclick="setFilterCategory('all')" id="btn-cat-all" class="px-4 py-2 text-xs font-bold rounded-xl transition-all bg-white text-blue-600 shadow-sm">
                    ทั้งหมด
                </button>
                <?php foreach($permission_categories as $catKey => $cat): ?>
                <button onclick="setFilterCategory('<?= $catKey ?>')" id="btn-cat-<?= $catKey ?>" class="px-4 py-2 text-xs font-bold rounded-xl text-slate-600 hover:text-slate-900 transition-all">
                    <?= $cat['title'] ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Categories Quick Overview Legend -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <?php foreach($permission_categories as $catKey => $cat): ?>
    <div class="glass-card rounded-2xl p-5 border border-slate-200/80 bg-white/70">
        <div class="flex items-center gap-2 mb-3">
            <span class="w-3 h-3 rounded-full <?= str_replace('text-', 'bg-', $cat['badge_text']) ?>"></span>
            <h3 class="font-black text-sm text-slate-800 uppercase tracking-tight"><?= $cat['title'] ?></h3>
        </div>
        <div class="space-y-2">
            <?php foreach($cat['items'] as $itemKey => $item): ?>
            <div class="flex items-center justify-between text-xs py-1 border-b border-slate-100 last:border-0">
                <span class="flex items-center gap-2 text-slate-600 font-medium">
                    <i data-lucide="<?= $item['icon'] ?>" class="w-3.5 h-3.5 <?= $item['color'] ?>"></i>
                    <?= $item['label'] ?>
                </span>
                <span class="text-[10px] text-slate-400 font-mono"><?= $itemKey ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Permission Matrix Table Card -->
<div class="glass-card rounded-[2.5rem] overflow-hidden border border-slate-200/80 bg-white shadow-sm">
    <div class="overflow-x-auto overflow-y-auto max-h-[70vh] custom-scrollbar">
        <table class="w-full text-left border-collapse" id="permissionsTable">
            <thead class="sticky top-0 z-30 bg-slate-900 text-white shadow-md">
                <tr>
                    <th class="p-6 text-xs font-black uppercase tracking-wider sticky left-0 top-0 z-40 bg-slate-900 min-w-[260px] shadow-[4px_0_12px_-4px_rgba(0,0,0,0.3)]">
                        <div class="flex items-center justify-between">
                            <span>บุคลากร</span>
                            <span class="text-[10px] text-slate-400 font-normal">นามสกุล / ตำแหน่ง</span>
                        </div>
                    </th>

                    <?php foreach($permission_categories as $catKey => $cat): ?>
                        <?php foreach($cat['items'] as $key => $perms): ?>
                        <th class="p-5 text-center bg-slate-800/90 border-l border-slate-700/50 cat-col cat-col-<?= $catKey ?> min-w-[130px]">
                            <div class="flex flex-col items-center gap-2 group cursor-pointer" title="<?= $perms['desc'] ?>">
                                <div class="w-10 h-10 rounded-2xl <?= $perms['bg'] ?> flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i data-lucide="<?= $perms['icon'] ?>" class="w-5 h-5 <?= $perms['color'] ?>"></i>
                                </div>
                                <span class="text-[11px] font-black text-slate-200 leading-tight text-center max-w-[110px]">
                                    <?= $perms['label'] ?>
                                </span>
                            </div>
                        </th>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <th class="p-5 text-center bg-slate-900 min-w-[140px] sticky right-0 z-30 shadow-[-4px_0_12px_-4px_rgba(0,0,0,0.3)]">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-300">ตัวช่วยกำหนด</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                <?php foreach($users as $user): ?>
                <?php 
                    $userPerms = explode(',', $user['u_role'] ?? ''); 
                    $isSuper = in_array('superadmin', $userPerms);
                    $searchData = mb_strtolower($user['u_fullname'] . ' ' . $user['position_name'] . ' ' . $user['u_position'] . ' ' . $user['u_username']);
                ?>
                <tr id="user-<?= $user['u_id'] ?>" 
                    data-search="<?= htmlspecialchars($searchData) ?>"
                    class="user-row hover:bg-blue-50/40 transition-all target:bg-amber-50/80 group">
                    
                    <!-- Personnel Info (Sticky Column) -->
                    <td class="p-5 sticky left-0 bg-white z-20 group-hover:bg-slate-50 transition-colors shadow-[4px_0_12px_-4px_rgba(0,0,0,0.06)]">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 overflow-hidden shrink-0 border-2 border-white shadow-sm relative">
                                <?php if(!empty($user['u_photo'])): ?>
                                    <img src="<?= base_url('uploads/personnel/' . $user['u_photo']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-slate-200 text-slate-400">
                                        <i data-lucide="user" class="w-6 h-6"></i>
                                    </div>
                                <?php endif; ?>

                                <?php if($isSuper): ?>
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-amber-500 rounded-full border border-white flex items-center justify-center text-white shadow-sm" title="Super Admin">
                                    <i data-lucide="crown" class="w-3 h-3"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <p class="font-black text-slate-900 text-sm truncate"><?= $user['u_prefix'] ?><?= $user['u_fullname'] ?></p>
                                <p class="text-[11px] text-blue-600 font-bold truncate mt-0.5"><?= $user['position_name'] ?: ($user['u_position'] ?: 'พนักงาน') ?></p>
                                <?php if($isSuper): ?>
                                    <span class="mt-1 inline-block text-[9px] font-black bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-md uppercase tracking-wider">Super Admin</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>

                    <!-- Permission Checkboxes Grouped by Category -->
                    <?php foreach($permission_categories as $catKey => $cat): ?>
                        <?php foreach($cat['items'] as $key => $perms): ?>
                        <?php 
                            $isChecked = in_array($key, $userPerms) || $isSuper;
                            $isDisabled = $isSuper && in_array($key, ['settings', 'summary', 'admin']);
                        ?>
                        <td class="p-4 text-center align-middle border-l border-slate-100 cat-col cat-col-<?= $catKey ?>">
                            <label class="relative inline-flex items-center cursor-pointer group/toggle <?= $isDisabled ? 'opacity-50 cursor-not-allowed' : '' ?>">
                                <input type="checkbox" 
                                       data-user="<?= $user['u_id'] ?>"
                                       data-key="<?= $key ?>"
                                       onchange="togglePermission(<?= $user['u_id'] ?>, '<?= $key ?>', this)"
                                       <?= $isChecked ? 'checked' : '' ?>
                                       <?= $isDisabled ? 'disabled' : '' ?>
                                       class="sr-only peer perm-checkbox-<?= $user['u_id'] ?>">
                                <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:start-[3px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-5 after:transition-all peer-checked:bg-blue-600 group-hover/toggle:ring-4 group-hover/toggle:ring-blue-100 transition-all"></div>
                            </label>
                            <?php if($isDisabled): ?>
                                <p class="text-[8px] font-black text-amber-600 uppercase tracking-tighter mt-1">Super Admin</p>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <!-- Action Helpers for Row (Select All / Clear All) -->
                    <td class="p-4 text-center align-middle sticky right-0 bg-white z-20 group-hover:bg-slate-50 border-l border-slate-100 shadow-[-4px_0_12px_-4px_rgba(0,0,0,0.06)]">
                        <?php if(!$isSuper): ?>
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="setRowPermissions(<?= $user['u_id'] ?>, true)" 
                                    class="text-[10px] font-bold text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white px-2.5 py-1.5 rounded-lg transition-all" 
                                    title="เลือกสิทธิ์ทั้งหมด">
                                เลือกหมด
                            </button>
                            <button onclick="setRowPermissions(<?= $user['u_id'] ?>, false)" 
                                    class="text-[10px] font-bold text-slate-500 bg-slate-100 hover:bg-rose-600 hover:text-white px-2.5 py-1.5 rounded-lg transition-all" 
                                    title="ล้างสิทธิ์ทั้งหมด">
                                ล้างหมด
                            </button>
                        </div>
                        <?php else: ?>
                            <span class="text-[10px] font-black text-slate-300">เต็มสิทธิ์</span>
                        <?php endif; ?>
                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-400 gap-3">
    <p class="flex items-center gap-2">
        <i data-lucide="info" class="w-4 h-4 text-blue-500"></i>
        <span>ระบบจะบันทึกการเปลี่ยนแปลงสิทธิ์อัตโนมัติทันทีที่สลับปุ่ม</span>
    </p>
    <p>กำลังแสดงบุคลากรทั้งหมด <span id="visible-count" class="font-bold text-slate-700"><?= count($users) ?></span> คน</p>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let activeCategory = 'all';

    function filterUsers() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.user-row');
        let visible = 0;

        rows.forEach(row => {
            const text = row.getAttribute('data-search') || '';
            if (text.includes(query)) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('visible-count').innerText = visible;
    }

    function setFilterCategory(catKey) {
        activeCategory = catKey;
        
        // Update Filter Buttons UI
        const buttons = document.querySelectorAll('[id^="btn-cat-"]');
        buttons.forEach(btn => {
            btn.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
            btn.classList.add('text-slate-600');
        });
        
        const activeBtn = document.getElementById(`btn-cat-${catKey}`);
        if (activeBtn) {
            activeBtn.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
            activeBtn.classList.remove('text-slate-600');
        }

        // Show / Hide Columns
        const allCols = document.querySelectorAll('.cat-col');
        allCols.forEach(col => {
            if (catKey === 'all' || col.classList.contains(`cat-col-${catKey}`)) {
                col.style.display = '';
            } else {
                col.style.display = 'none';
            }
        });
    }

    async function togglePermission(userId, permissionKey, checkbox) {
        const row = document.getElementById(`user-${userId}`);
        const rowCheckboxes = row.querySelectorAll('input[type="checkbox"]');
        
        const formData = new FormData();
        formData.append('u_id', userId);
        
        rowCheckboxes.forEach(cb => {
            if (cb.checked && !cb.disabled) {
                const key = cb.getAttribute('data-key');
                if (key) formData.append('permissions[]', key);
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
                // Highlight success feedback
                row.classList.add('bg-emerald-50/70');
                setTimeout(() => row.classList.remove('bg-emerald-50/70'), 600);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: data.message || 'ไม่สามารถบันทึกสิทธิ์ได้',
                    customClass: { popup: 'rounded-[2rem]' }
                });
                checkbox.checked = !checkbox.checked; // Revert state
            }
        } catch (error) {
            console.error('Error updating permission:', error);
            checkbox.checked = !checkbox.checked; // Revert state
        }
    }

    async function setRowPermissions(userId, enableAll) {
        const row = document.getElementById(`user-${userId}`);
        const checkboxes = row.querySelectorAll(`input.perm-checkbox-${userId}`);
        
        const formData = new FormData();
        formData.append('u_id', userId);

        checkboxes.forEach(cb => {
            if (!cb.disabled) {
                cb.checked = enableAll;
                if (enableAll) {
                    const key = cb.getAttribute('data-key');
                    if (key) formData.append('permissions[]', key);
                }
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
                row.classList.add(enableAll ? 'bg-blue-50/80' : 'bg-rose-50/80');
                setTimeout(() => row.classList.remove('bg-blue-50/80', 'bg-rose-50/80'), 600);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: data.message,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
        } catch (error) {
            console.error('Error setting row permissions:', error);
        }
    }
</script>
<?= $this->endSection() ?>
