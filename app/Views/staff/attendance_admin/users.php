<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <a href="<?= base_url('staff/attendance-admin') ?>" class="inline-flex items-center gap-2 text-slate-400 hover:text-blue-600 font-bold text-sm mb-4 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        กลับหน้าสรุปการลงเวลา
    </a>
    <h2 class="text-3xl font-black text-slate-900 tracking-tight">ตั้งค่ารหัสสแกนนิ้ว</h2>
    <p class="text-slate-500 mt-1 font-medium">กำหนดรหัสจากเครื่องสแกนนิ้ว (คอลัมน์ B) ให้ตรงกับบุคลากรในระบบ</p>
</div>

<div class="glass-card rounded-[2.5rem] overflow-hidden border border-slate-200">
    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <h3 class="font-black text-slate-900">รายชื่อบุคลากร (<?= count($users) ?> ท่าน)</h3>
        </div>
        
        <div class="relative w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input type="text" id="userSearch" placeholder="ค้นหาชื่อ..." 
                   class="w-full pl-10 pr-4 py-2 bg-slate-50 border-none rounded-xl text-xs font-bold focus:ring-1 focus:ring-blue-100 transition-all">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ชื่อ-นามสกุล</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ตำแหน่ง</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">รหัสในเครื่องสแกนนิ้ว (คอลัมน์ B)</th>
                </tr>
            </thead>
            <tbody id="userTableBody" class="divide-y divide-slate-100">
                <?php foreach($users as $user): ?>
                <tr class="hover:bg-slate-50/50 transition-colors group user-row">
                    <td class="px-8 py-6">
                        <span class="text-sm font-bold text-slate-900"><?= $user['u_prefix'] . $user['u_fullname'] ?></span>
                    </td>
                    <td class="px-8 py-6 text-xs font-medium text-slate-500">
                        <div><?= $user['pos_name'] ?? 'ไม่มีข้อมูลตำแหน่ง' ?></div>
                        <div class="text-[10px] text-blue-500"><?= $user['u_division'] ?? '' ?></div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                             <input type="number" 
                                   value="<?= $user['u_fingerprint_id'] ?>" 
                                   placeholder="ระบุรหัส..."
                                   class="w-32 px-4 py-2 bg-slate-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-xl text-center font-black text-slate-900 outline-none transition-all"
                                   onchange="updateFingerprint(<?= $user['u_id'] ?>, this.value)">
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function updateFingerprint(userId, fingerId) {
        const input = event.target;
        input.classList.add('opacity-50');
        
        fetch('<?= base_url('staff/attendance-admin/save-mapping') ?>', {
            method: 'POST',
            body: new URLSearchParams({
                'u_id': userId,
                'finger_id': fingerId
            }),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            input.classList.remove('opacity-50');
            if (data.status === 'success') {
                input.classList.remove('focus:border-blue-600');
                input.classList.add('border-emerald-500');
                setTimeout(() => input.classList.remove('border-emerald-500'), 2000);
            }
        });
    }

    // Search Filtering
    document.getElementById('userSearch').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        const rows = document.querySelectorAll('.user-row');
        
        rows.forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            const position = row.cells[1].textContent.toLowerCase();
            if (name.includes(val) || position.includes(val)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
<?= $this->endSection() ?>
