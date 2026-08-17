<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<?php $activeCompYear = isset($activeYear) ? (int)$activeYear : (int)(session()->get('sports_active_year') ?: 2569); ?>
<div class="space-y-6">
    <?= view('sports/admin/layout/nav', ['activeYear' => $activeCompYear]) ?>

    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full text-xs font-semibold mb-2">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-amber-600"></i>
                <span>Sports Categories & Age Groups</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">จัดการชนิดกีฬาและรุ่นการแข่งขัน</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-0.5">กำหนดรายละเอียดกีฬา, รุ่นอายุ, จำนวนผู้เล่น และช่วงเวลารับสมัคร</p>
        </div>
        <div>
            <button onclick="openCreateModal()" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-xs flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all cursor-pointer">
                <i data-lucide="plus-circle" class="w-4 h-4 text-amber-300"></i>
                <span>เพิ่มชนิดกีฬา / รุ่นการแข่งขัน</span>
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Categories Table Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-gradient-to-r from-emerald-800 via-teal-800 to-emerald-900 text-white font-black text-xs tracking-wider uppercase border-b-2 border-emerald-400">
                    <tr>
                        <th class="px-6 py-4 text-white">ชนิดกีฬา</th>
                        <th class="px-6 py-4 text-white">รุ่น / ประเภท</th>
                        <th class="px-6 py-4 text-center text-emerald-100">เพศ</th>
                        <th class="px-6 py-4 text-center text-emerald-100">จำนวนผู้เล่น (คน)</th>
                        <th class="px-6 py-4 text-center text-emerald-100">ทีมสมัครแล้ว</th>
                        <th class="px-6 py-4 text-center text-emerald-100">สถานะรับสมัคร</th>
                        <th class="px-6 py-4 text-center text-emerald-200">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="folder-x" class="w-8 h-8 opacity-40"></i>
                                    <span>ยังไม่มีข้อมูลชนิดกีฬาและรุ่นการแข่งขัน</span>
                                    <button onclick="openCreateModal()" class="mt-2 text-indigo-600 font-bold hover:underline">คลิกที่นี่เพื่อเพิ่มรายการแรก</button>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $c): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-500/30 rounded-xl text-emerald-950 font-black text-sm">
                                        <i data-lucide="trophy" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                                        <span class="tracking-wide"><?= esc($c['sport_name']) ?></span>
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-bold mt-1 ml-1">ปี <?= esc($c['comp_year']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800"><?= esc($c['category_name']) ?></div>
                                    <div class="text-[11px] text-slate-400">
                                        <?= $c['category_type'] === 'team' ? 'ประเภททีม' : ($c['category_type'] === 'pair' ? 'ประเภทคู่' : 'ประเภทเดี่ยว') ?>
                                        <?php if ($c['age_min'] > 0 || $c['age_max'] < 99): ?>
                                            (อายุ <?= $c['age_min'] ?> - <?= $c['age_max'] ?> ปี)
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold <?= $c['category_gender'] === 'female' ? 'bg-rose-50 text-rose-600 border border-rose-200' : ($c['category_gender'] === 'mixed' ? 'bg-purple-50 text-purple-600 border border-purple-200' : 'bg-blue-50 text-blue-600 border border-blue-200') ?>">
                                        <?= $c['category_gender'] === 'female' ? 'หญิง' : ($c['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="font-bold text-slate-800"><?= $c['min_players'] ?> - <?= $c['max_players'] ?> คน</div>
                                    <div class="text-[10px] text-slate-400">โค้ช <?= $c['min_coaches'] ?> - <?= $c['max_coaches'] ?> คน</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 font-extrabold rounded-xl">
                                        <?= number_format($c['team_count']) ?> <?= $c['max_teams'] > 0 ? '/ ' . $c['max_teams'] : '' ?> ทีม
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <button onclick="toggleCategoryStatus(<?= $c['category_id'] ?>, this)" 
                                                id="status-badge-<?= $c['category_id'] ?>"
                                                class="px-3 py-1 rounded-full text-[11px] font-extrabold transition-all cursor-pointer flex items-center gap-1.5 shadow-sm <?= $c['status'] === 'open' ? 'bg-emerald-50 text-emerald-700 border border-emerald-300 hover:bg-emerald-100' : ($c['status'] === 'closed' ? 'bg-rose-50 text-rose-700 border border-rose-300 hover:bg-rose-100' : 'bg-slate-100 text-slate-600') ?>"
                                                title="คลิกเพื่อสลับสถานะ เปิด/ปิด รับสมัครทันที">
                                            <span class="w-2 h-2 rounded-full <?= $c['status'] === 'open' ? 'bg-emerald-500 animate-pulse' : ($c['status'] === 'closed' ? 'bg-rose-500' : 'bg-slate-400') ?>"></span>
                                            <span class="status-text"><?= $c['status'] === 'open' ? 'เปิดรับสมัคร' : ($c['status'] === 'closed' ? 'ปิดรับสมัคร' : 'แบบร่าง') ?></span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button onclick='openEditModal(<?= json_encode($c) ?>)' class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl transition-colors cursor-pointer" title="แก้ไข">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <a href="<?= base_url('staff/sports/categories/delete/' . $c['category_id']) ?>" onclick="return confirm('ยืนยันที่จะลบรายการนี้ใช่หรือไม่?')" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition-colors" title="ลบ">
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
</div>

<!-- Modal Create / Edit -->
<div id="categoryModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 animate-[fadeIn_0.2s_ease-out]">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <h3 id="modalTitle" class="text-lg font-black text-slate-900">เพิ่มชนิดกีฬาและรุ่นการแข่งขัน</h3>
            <button onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="categoryForm" action="<?= base_url('staff/sports/categories/store') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ชนิดกีฬา <span class="text-rose-500">*</span></label>
                    <input type="text" name="sport_name" id="f_sport_name" required placeholder="เช่น ฟุตบอล, วอลเลย์บอล, เปตอง" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ชื่อรุ่นการแข่งขัน <span class="text-rose-500">*</span></label>
                    <input type="text" name="category_name" id="f_category_name" required placeholder="เช่น U-12, U-15, ประชาชนทั่วไป" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ปีการแข่งขัน <span class="text-rose-500">*</span></label>
                    <input type="number" name="comp_year" id="f_comp_year" value="<?= $activeCompYear ?>" min="2500" max="2700" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-black text-emerald-700 bg-emerald-50/40">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">เพศ</label>
                    <select name="category_gender" id="f_category_gender" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                        <option value="male">ชาย</option>
                        <option value="female">หญิง</option>
                        <option value="mixed">ผสม (Mixed)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ประเภทการแข่ง</label>
                    <select name="category_type" id="f_category_type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                        <option value="team">ทีม (Team)</option>
                        <option value="pair">คู่ (Pair)</option>
                        <option value="individual">เดี่ยว (Individual)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">สถานะรับสมัคร</label>
                    <select name="status" id="f_status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                        <option value="open">เปิดรับสมัคร</option>
                        <option value="closed">ปิดรับสมัคร</option>
                        <option value="draft">แบบร่าง (Draft)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">อายุขั้นต่ำ (ปี, 0=ไม่จำกัด)</label>
                    <input type="number" name="age_min" id="f_age_min" value="0" min="0" max="99" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">อายุสูงสุด (ปี, เช่น 12, 15, 18, 99=ไม่จำกัด)</label>
                    <input type="number" name="age_max" id="f_age_max" value="99" min="1" max="99" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">โควตารับสมัครสูงสุด (ทีม, 0=ไม่จำกัด)</label>
                    <input type="number" name="max_teams" id="f_max_teams" value="0" min="0" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ผู้เล่นต่ำสุด (คน)</label>
                    <input type="number" name="min_players" id="f_min_players" value="1" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ผู้เล่นสูงสุด (คน)</label>
                    <input type="number" name="max_players" id="f_max_players" value="20" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ผู้ฝึกสอนต่ำสุด (คน)</label>
                    <input type="number" name="min_coaches" id="f_min_coaches" value="1" min="0" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ผู้ฝึกสอนสูงสุด (คน)</label>
                    <input type="number" name="max_coaches" id="f_max_coaches" value="5" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">วันเปิดรับสมัคร (พ.ศ.)</label>
                    <div class="relative">
                        <input type="text" name="reg_start_date" id="f_reg_start_date" placeholder="วว/ดด/ปปปป (พ.ศ.)" class="datepicker-be w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium bg-white">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">วันปิดรับสมัคร (พ.ศ.)</label>
                    <div class="relative">
                        <input type="text" name="reg_end_date" id="f_reg_end_date" placeholder="วว/ดด/ปปปป (พ.ศ.)" class="datepicker-be w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium bg-white">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">รายละเอียด / กติกาเพิ่มเติม</label>
                <textarea name="rules_detail" id="f_rules_detail" rows="3" placeholder="ระบุรายละเอียดเพิ่มเติม หรือคุณสมบัติผู้เข้าแข่งขัน..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-medium"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors cursor-pointer">ยกเลิก</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-md shadow-indigo-200 transition-all cursor-pointer">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'เพิ่มชนิดกีฬาและรุ่นการแข่งขัน';
        document.getElementById('categoryForm').action = '<?= base_url('staff/sports/categories/store') ?>';
        document.getElementById('categoryForm').reset();
        document.getElementById('f_comp_year').value = '<?= $activeCompYear ?>';
        document.getElementById('f_age_min').value = 0;
        document.getElementById('f_age_max').value = 99;
        document.getElementById('f_max_teams').value = 0;
        document.getElementById('f_min_players').value = 1;
        document.getElementById('f_max_players').value = 20;
        document.getElementById('f_min_coaches').value = 1;
        document.getElementById('f_max_coaches').value = 5;
        
        const fpStart = document.querySelector("#f_reg_start_date")._flatpickr;
        const fpEnd = document.querySelector("#f_reg_end_date")._flatpickr;
        if (fpStart) fpStart.clear();
        if (fpEnd) fpEnd.clear();

        document.getElementById('categoryModal').classList.remove('hidden');
    }

    function openEditModal(c) {
        document.getElementById('modalTitle').innerText = 'แก้ไขชนิดกีฬาและรุ่นการแข่งขัน';
        document.getElementById('categoryForm').action = '<?= base_url('staff/sports/categories/update') ?>/' + c.category_id;
        document.getElementById('f_sport_name').value = c.sport_name || '';
        document.getElementById('f_category_name').value = c.category_name || '';
        document.getElementById('f_comp_year').value = c.comp_year || '<?= $activeCompYear ?>';
        document.getElementById('f_category_gender').value = c.category_gender || 'male';
        document.getElementById('f_category_type').value = c.category_type || 'team';
        document.getElementById('f_status').value = c.status || 'open';
        document.getElementById('f_age_min').value = c.age_min !== undefined ? c.age_min : 0;
        document.getElementById('f_age_max').value = c.age_max !== undefined ? c.age_max : 99;
        document.getElementById('f_max_teams').value = c.max_teams !== undefined ? c.max_teams : 0;
        document.getElementById('f_min_players').value = c.min_players || 1;
        document.getElementById('f_max_players').value = c.max_players || 20;
        document.getElementById('f_min_coaches').value = c.min_coaches || 1;
        document.getElementById('f_max_coaches').value = c.max_coaches || 5;
        document.getElementById('f_rules_detail').value = c.rules_detail || '';

        const fpStart = document.querySelector("#f_reg_start_date")._flatpickr;
        const fpEnd = document.querySelector("#f_reg_end_date")._flatpickr;
        if (fpStart) {
            if (c.reg_start_date) fpStart.setDate(c.reg_start_date, true);
            else fpStart.clear();
            applyBE(fpStart);
        }
        if (fpEnd) {
            if (c.reg_end_date) fpEnd.setDate(c.reg_end_date, true);
            else fpEnd.clear();
            applyBE(fpEnd);
        }

        document.getElementById('categoryModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('categoryModal').classList.add('hidden');
    }

    function toggleCategoryStatus(catId, btn) {
        btn.disabled = true;
        btn.classList.add('opacity-50');

        fetch('<?= base_url('staff/sports/categories/toggle-status') ?>/' + catId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.classList.remove('opacity-50');

            if (data.status === 'success') {
                const isOpen = (data.new_status === 'open');
                
                btn.className = `px-3 py-1 rounded-full text-[11px] font-extrabold transition-all cursor-pointer flex items-center gap-1.5 shadow-sm ${
                    isOpen ? 'bg-emerald-50 text-emerald-700 border border-emerald-300 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-300 hover:bg-rose-100'
                }`;

                btn.innerHTML = `
                    <span class="w-2 h-2 rounded-full ${isOpen ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'}"></span>
                    <span class="status-text">${isOpen ? 'เปิดรับสมัคร' : 'ปิดรับสมัคร'}</span>
                `;

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: data.message || 'ไม่สามารถเปลี่ยนสถานะได้'
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.classList.remove('opacity-50');
            console.error(err);
        });
    }
</script>
<?= $this->endSection() ?>
