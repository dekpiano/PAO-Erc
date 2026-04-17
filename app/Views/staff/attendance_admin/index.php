<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">สรุปการลงเวลาประจำวัน</h2>
        <p class="text-slate-500 mt-1 font-medium">จัดการและตรวจสอบข้อมูลการมาทำงานจากไฟล์ Excel</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= base_url('staff/attendance-admin/report') ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold transition-all shadow-lg shadow-blue-100">
            <i data-lucide="bar-chart-big" class="w-5 h-5"></i>
            รายงานสรุปภาพรวม
        </a>
        <button onclick="openManualModal()" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-2xl font-bold transition-all shadow-lg shadow-emerald-100">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
            เพิ่มรายคน
        </button>
        <a href="<?= base_url('staff/attendance-admin/users') ?>" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-2xl font-bold transition-all">
            <i data-lucide="settings-2" class="w-5 h-5"></i>
            ตั้งค่ารหัสสแกนนิ้ว
        </a>
        <a href="<?= base_url('staff/attendance-admin/upload') ?>" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold transition-all shadow-lg shadow-blue-100">
            <i data-lucide="upload" class="w-5 h-5"></i>
            อัปโหลดไฟล์ใหม่
        </a>
    </div>
</div>

<div class="glass-card rounded-[2.5rem] overflow-hidden border border-slate-200">
    <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">วันที่แสดงผล</span>
                <input type="date" id="dateFilter" value="<?= $selected_date ?>" 
                       class="text-lg font-bold text-slate-900 bg-transparent border-none p-0 focus:ring-0 cursor-pointer">
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <?php
                $stats = ['มา' => 0, 'สาย' => 0, 'ลา' => 0, 'ขาด' => 0, 'ไปราชการ' => 0];
                foreach($attendance as $item) {
                    if(isset($stats[$item['atd_status']])) $stats[$item['atd_status']]++;
                }
            ?>
            <div class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-bold border border-emerald-100">มา: <?= $stats['มา'] ?></div>
            <div class="px-4 py-2 bg-amber-50 text-amber-600 rounded-xl text-xs font-bold border border-amber-100">สาย: <?= $stats['สาย'] ?></div>
            <div class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-xs font-bold border border-blue-100">ลา: <?= $stats['ลา'] ?></div>
            <div class="px-4 py-2 bg-orange-50 text-orange-600 rounded-xl text-xs font-bold border border-orange-100">ไปราชการ: <?= $stats['ไปราชการ'] ?></div>
            <div class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl text-xs font-bold border border-rose-100">ขาด: <?= $stats['ขาด'] ?></div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ชื่อ-นามสกุล</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ตำแหน่ง / ฝ่ายงาน</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">สถานะ</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">หมายเหตุ</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">บันทึกเมื่อ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($attendance)): ?>
                <tr>
                    <td colspan="3" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center gap-4 text-slate-400">
                            <i data-lucide="inbox" class="w-12 h-12 opacity-20"></i>
                            <p class="font-bold">ไม่มีข้อมูลการเข้างานในวันที่เลือก</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach($attendance as $row): ?>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 flex items-center justify-center">
                                <?php if(!empty($row['u_photo'])): ?>
                                    <img src="<?= base_url('uploads/users/' . $row['u_photo']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i data-lucide="user" class="w-5 h-5 text-slate-300"></i>
                                <?php endif; ?>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900"><?= $row['u_prefix'] . $row['u_fullname'] ?></span>
                                <span class="text-[10px] font-medium text-slate-400">
                                    <?= $row['pos_name'] ?? 'ไม่มีข้อมูลตำแหน่ง' ?> 
                                    <?= !empty($row['u_division']) ? '<span class="text-blue-500">| '.$row['u_division'].'</span>' : '' ?>
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <?php
                            $statusClass = [
                                'มา' => 'bg-emerald-100 text-emerald-700',
                                'สาย' => 'bg-amber-100 text-amber-700',
                                'ลา' => 'bg-blue-100 text-blue-700',
                                'ไปราชการ' => 'bg-orange-100 text-orange-700',
                                'ขาด' => 'bg-rose-100 text-rose-700'
                            ];
                        ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider <?= $statusClass[$row['atd_status']] ?? 'bg-slate-100' ?>">
                            <?= $row['atd_status'] ?>
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <input type="text" value="<?= $row['atd_note'] ?>" 
                               onchange="updateNote(<?= $row['atd_id'] ?>, this.value)"
                               placeholder="เพิ่มหมายเหตุ..."
                               class="text-[10px] font-bold text-slate-500 bg-slate-50 border-none rounded-lg px-3 py-1.5 w-full focus:ring-1 focus:ring-blue-100 focus:bg-white transition-all">
                    </td>
                    <td class="px-8 py-6 text-right">
                        <span class="text-[10px] font-bold text-slate-400"><?= date('H:i', strtotime($row['atd_timestamp'])) ?> น.</span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (session()->getFlashdata('import_errors')): ?>
<div class="mt-8 glass-card rounded-[2rem] border border-rose-100 p-8">
    <div class="flex items-center gap-3 text-rose-600 mb-4">
        <i data-lucide="alert-circle" class="w-6 h-6"></i>
        <h3 class="font-black text-lg">รายการที่ไม่พบในระบบ (<?= count(session()->getFlashdata('import_errors')) ?> รายการ)</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
        <?php foreach(session()->getFlashdata('import_errors') as $error): ?>
            <div class="text-xs font-bold text-slate-500 bg-slate-50 px-4 py-2 rounded-xl"><?= $error ?></div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Manual Add Modal -->
<div id="manualModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeManualModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-10 bg-white rounded-[3rem] shadow-2xl border border-slate-100">
        <h3 class="text-2xl font-black text-slate-900 mb-2">บันทึกการมาทำงาน (รายคน)</h3>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-8">กรณีไม่มีข้อมูลในไฟล์ Excel หรือบันทึกย้อนหลัง</p>
        
        <form id="manualForm" action="<?= base_url('staff/attendance-admin/save-manual') ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="date" value="<?= $selected_date ?>">

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">เลือกบุคลากร</label>
                <select name="user_id" required class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl font-bold text-slate-900 outline-none transition-all appearance-none cursor-pointer">
                    <option value="">-- เลือกรายชื่อ --</option>
                    <?php foreach($users as $u): ?>
                    <option value="<?= $u['u_id'] ?>"><?= $u['u_fullname'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">สถานะ</label>
                    <select name="status" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl font-bold text-slate-900 outline-none transition-all appearance-none cursor-pointer">
                        <option value="มา">มา</option>
                        <option value="สาย">สาย</option>
                        <option value="ลา">ลา</option>
                        <option value="ไปราชการ">ไปราชการ</option>
                        <option value="ขาด">ขาด</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">เวลาเข้า</label>
                    <input type="time" name="time" value="08:00" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl font-bold text-slate-900 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">หมายเหตุ</label>
                <input type="text" name="note" placeholder="ระบุเหตุผล (ถ้ามี)..." class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl font-bold text-slate-900 outline-none transition-all">
            </div>

            <div class="pt-4 flex gap-4">
                <button type="button" onclick="closeManualModal()" class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-black transition-all">ยกเลิก</button>
                <button type="submit" class="flex-2 px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black transition-all shadow-lg shadow-blue-100">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openManualModal() {
        document.getElementById('manualModal').classList.remove('hidden');
    }
    function closeManualModal() {
        document.getElementById('manualModal').classList.add('hidden');
    }

    document.getElementById('dateFilter').addEventListener('change', function() {
        window.location.href = '<?= base_url('staff/attendance-admin') ?>?date=' + this.value;
    });

    function updateNote(id, note) {
        fetch('<?= base_url('staff/attendance-admin/update-note') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: JSON.stringify({ id: id, note: note })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Optional: Show a tiny success toast or indicator
            } else {
                alert('ไม่สามารถบันทึกหมายเหตุได้');
            }
        });
    }
</script>
<?= $this->endSection() ?>
