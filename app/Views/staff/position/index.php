<?php 
// หน้าจัดการตำแหน่งงาน (Position Management)
?>
<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">ตั้งค่าตำแหน่งงาน</h1>
        <p class="text-slate-500 text-sm mt-1">เพิ่ม แก้ไข และจัดการประเภทบุคลากรในระบบ</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('add')" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-blue-200">
            <i data-lucide="plus-circle" class="w-5 h-5"></i> เพิ่มตำแหน่งใหม่
        </button>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl flex items-center gap-3 font-bold text-sm">
        <i data-lucide="check-circle" class="w-5 h-5"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl flex items-center gap-3 font-bold text-sm">
        <i data-lucide="alert-circle" class="w-5 h-5"></i> <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-xs border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">ชื่อตำแหน่งงาน</th>
                    <th class="px-6 py-4 text-center">ประเภทบุคลากร</th>
                    <th class="px-6 py-4 text-center">แท่งงาน/กลุ่ม</th>
                    <th class="px-6 py-4 text-center">หัวหน้า</th>
                    <th class="px-6 py-4 text-right">การจัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                <?php if (empty($positions)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <i data-lucide="ghost" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="font-medium">ยังไม่มีข้อมูลตำแหน่งงาน</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($positions as $p): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-slate-400">#<?= $p['pos_id'] ?></td>
                        <td class="px-6 py-4 font-bold text-slate-800"><?= esc($p['pos_name']) ?></td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($p['pos_type'] == 'civil_servant'): ?>
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[11px] font-black uppercase">ข้าราชการ</span>
                            <?php elseif ($p['pos_type'] == 'mission_based'): ?>
                                <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-[11px] font-black uppercase">ตามภารกิจ</span>
                            <?php else: ?>
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-[11px] font-black uppercase">จ้างทั่วไป</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center font-medium">
                            <?php 
                                $levels = ['academic' => 'วิชาการ', 'general' => 'ทั่วไป', 'executive' => 'อำนวยการ', 'administrative' => 'บริหาร'];
                                echo $levels[$p['pos_level']] ?? $p['pos_level'];
                            ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($p['pos_is_head']): ?>
                                <span class="text-emerald-500 tooltip" title="ตำแหน่งนี้เป็นหัวหน้าฝ่าย"><i data-lucide="shield-check" class="w-5 h-5 mx-auto"></i></span>
                            <?php else: ?>
                                <span class="text-slate-300"><i data-lucide="minus" class="w-5 h-5 mx-auto"></i></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick='openEditModal(<?= json_encode($p) ?>)' class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                <a href="<?= base_url('admin/position/delete/'.$p['pos_id']) ?>" onclick="return confirm('ยืนยันการลบตำแหน่งนี้?')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal สำหรับ เพิ่ม/แก้ไข -->
<div id="posModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 id="modalTitle" class="text-xl font-black text-slate-800 tracking-tight">เพิ่มตำแหน่งใหม่</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>
        <form id="posForm" action="<?= base_url('admin/position/store') ?>" method="POST" class="p-6">
            <?= csrf_field() ?>
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">ชื่อตำแหน่งงาน</label>
                    <input type="text" name="pos_name" id="pos_name" required placeholder="เช่น นักวิชาการศึกษาปฏิบัติการ" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 px-4 text-slate-800 font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">ประเภทบุคลากร</label>
                        <select name="pos_type" id="pos_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-slate-700">
                            <option value="civil_servant">ข้าราชการ</option>
                            <option value="mission_based">ตามภารกิจ</option>
                            <option value="general_contract">จ้างทั่วไป</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">แท่งงาน/กลุ่ม</label>
                        <select name="pos_level" id="pos_level" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-slate-700">
                            <option value="academic">วิชาการ</option>
                            <option value="general">ทั่วไป</option>
                            <option value="executive">อำนวยการ</option>
                            <option value="administrative">บริหาร</option>
                        </select>
                    </div>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="pos_is_head" id="pos_is_head" value="1" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </div>
                        <span class="text-sm font-black text-slate-700">กำหนดเป็นตำแหน่งระดับหัวหน้า</span>
                    </label>
                    <p class="text-[10px] text-slate-400 mt-2 ml-13 leading-relaxed">หัวหน้าจะมีสิทธิในการพิจารณาใบลาของลูกน้องในฝ่ายได้</p>
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-colors">ยกเลิก</button>
                <button type="submit" class="flex-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(type) {
    document.getElementById('posModal').classList.remove('hidden');
    document.getElementById('posForm').reset();
    document.getElementById('modalTitle').innerText = (type === 'add') ? 'เพิ่มตำแหน่งใหม่' : 'แก้ไขตำแหน่ง';
    document.getElementById('posForm').action = "<?= base_url('admin/position/store') ?>";
}

function openEditModal(pos) {
    openModal('edit');
    document.getElementById('posForm').action = "<?= base_url('admin/position/update') ?>/" + pos.pos_id;
    document.getElementById('pos_name').value = pos.pos_name;
    document.getElementById('pos_type').value = pos.pos_type;
    document.getElementById('pos_level').value = pos.pos_level;
    document.getElementById('pos_is_head').checked = (pos.pos_is_head == 1);
}

function closeModal() {
    document.getElementById('posModal').classList.add('hidden');
}

// ปิด Modal เมื่อคลิกพื้นหลัง
document.getElementById('posModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
<?= $this->endSection() ?>
