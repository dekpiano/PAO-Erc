<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<?php $activeCompYear = isset($activeYear) ? (int)$activeYear : (int)(session()->get('sports_active_year') ?: 2569); ?>
<?= view('sports/admin/layout/nav', ['activeYear' => $activeCompYear]) ?>

<div class="space-y-6">
    <!-- Header with Create Action -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full text-xs font-semibold mb-1">
                <i data-lucide="award" class="w-3.5 h-3.5 text-amber-600"></i>
                <span>Sports Certificates Management</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">ระบบจัดการและออกเกียรติบัตร</h1>
            <p class="text-xs sm:text-sm text-slate-400">สร้างแบบเกียรติบัตร, แก้ไขรายละเอียด, กำหนดรุ่น/รางวัล และตั้งค่าพิกัดข้อความด้วยระบบ Visual Coordinate Designer</p>
        </div>

        <div>
            <button onclick="openCreateCertModal()" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-xs flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all cursor-pointer hover:scale-105 active:scale-95">
                <i data-lucide="plus-circle" class="w-4 h-4 text-amber-300"></i>
                <span>สร้างแบบเกียรติบัตรใหม่</span>
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Certificates Grid List -->
    <?php if (empty($certs)): ?>
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm space-y-4">
            <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto text-amber-500">
                <i data-lucide="award" class="w-8 h-8"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-bold text-slate-700 text-base">ยังไม่มีแบบเกียรติบัตรในระบบ</h3>
                <p class="text-xs text-slate-400">เริ่มต้นสร้างแบบเกียรติบัตรสำหรับนักกีฬา ผู้ฝึกสอน หรือตามรุ่นการแข่งขัน</p>
            </div>
            <button onclick="openCreateCertModal()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs inline-flex items-center gap-2 transition-all shadow-md shadow-emerald-200">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>สร้างแบบเกียรติบัตรแรก</span>
            </button>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php 
            $awardLabels = [
                'all'           => 'ทุกระดับรางวัล',
                'champion'      => '🏆 ชนะเลิศ (Champion)',
                'runner_up_1'   => '🥈 รองชนะเลิศอันดับ 1',
                'runner_up_2'   => '🥉 รองชนะเลิศอันดับ 2',
                'runner_up_3'   => '🎖️ รองชนะเลิศอันดับ 3',
                'participation' => '📜 เข้าร่วมการแข่งขัน'
            ];
            $targetLabels = [
                'all'     => 'ทุกคน (นักกีฬา + ผู้ฝึกสอน + ผู้ควบคุมทีม)',
                'athlete' => 'เฉพาะนักกีฬา',
                'coach'   => 'ผู้ฝึกสอน / ผู้ควบคุมทีม / เจ้าหน้าที่ทีม'
            ];
            ?>
            <?php foreach ($certs as $c): ?>
                <?php 
                $certJson = htmlspecialchars(json_encode([
                    'cert_id'     => $c['cert_id'],
                    'cert_title'  => $c['cert_title'],
                    'category_id' => $c['category_id'],
                    'target_type' => $c['target_type'],
                    'award_level' => $c['award_level'],
                    'cert_prefix' => $c['cert_prefix'],
                    'is_active'   => $c['is_active']
                ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                ?>
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:border-emerald-200 transition-all flex flex-col justify-between group space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-black group-hover:scale-105 transition-transform">
                                <i data-lucide="award" class="w-5 h-5"></i>
                            </div>
                            <div class="flex items-center gap-2">
                                <form action="<?= base_url("staff/sports/certificates/toggle-status/{$c['cert_id']}") ?>" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-black cursor-pointer transition-all <?= $c['is_active'] ? 'bg-emerald-100 hover:bg-emerald-200 text-emerald-800' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' ?>" title="คลิกเพื่อเปลี่ยนสถานะเปิด/ปิด">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full mr-1 <?= $c['is_active'] ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                        <?= $c['is_active'] ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?>
                                    </button>
                                </form>
                                <button type="button" onclick='openEditCertModal(<?= $certJson ?>)' class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="แก้ไขรายละเอียด">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                <button type="button" onclick="confirmDeleteCert('<?= base_url("staff/sports/certificates/delete/{$c['cert_id']}") ?>', '<?= esc($c['cert_title'], 'js') ?>')" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="ลบแบบเกียรติบัตร">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-base font-black text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2">
                                <?= esc($c['cert_title']) ?>
                            </h3>
                            <div class="mt-2">
                                <?php if (!empty($c['sport_name'])): ?>
                                    <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-950 border border-emerald-200 text-xs font-black">
                                        <i data-lucide="trophy" class="w-3 h-3 text-emerald-600"></i>
                                        <span>กีฬา: <?= esc($c['sport_name']) ?></span>
                                    </div>
                                    <div class="text-xs text-slate-500 font-bold mt-1">
                                        รุ่น: <span class="text-slate-700"><?= (mb_strpos(trim($c['category_name']), 'รุ่น') === 0 ? mb_substr(trim($c['category_name']), 4) : esc($c['category_name'])) ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold">
                                        <span>ทุกรุ่นการแข่งขัน (General)</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-3 rounded-2xl text-[11px] space-y-1.5 text-slate-600 border border-slate-100">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">เป้าหมาย:</span>
                                <strong class="text-slate-700"><?= $targetLabels[$c['target_type']] ?? esc($c['target_type']) ?></strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">รางวัล:</span>
                                <strong class="text-slate-700"><?= $awardLabels[$c['award_level']] ?? esc($c['award_level']) ?></strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">รหัสนำหน้า:</span>
                                <strong class="font-mono text-emerald-700 bg-white px-2 py-0.5 rounded border border-slate-200"><?= esc($c['cert_prefix']) ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                        <button type="button" onclick='openEditCertModal(<?= $certJson ?>)' class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 transition-all" title="แก้ไขรายละเอียด">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            <span>แก้ไข</span>
                        </button>
                        <a href="<?= base_url("staff/sports/certificates/design/{$c['cert_id']}") ?>" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 shadow-md shadow-emerald-100 transition-all hover:scale-[1.02] active:scale-95">
                            <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                            <span>ตั้งค่าพิกัด Visual</span>
                        </a>
                        <a href="<?= base_url("staff/sports/certificates/demo/{$c['cert_id']}") ?>" target="_blank" class="p-2.5 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded-xl text-xs font-bold transition-colors" title="ดูตัวอย่างเกียรติบัตร (Demo)">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal: Create New Certificate -->
<div id="modal-create-cert" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl animate-[fadeIn_0.2s_ease-out]">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-base">สร้างแบบเกียรติบัตรใหม่</h3>
                    <p class="text-[11px] text-slate-400">ระบบจะสร้างพร้อมตำแหน่งพิกัดเริ่มต้นให้ปรับแต่ง</p>
                </div>
            </div>
            <button onclick="closeCreateCertModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="<?= base_url('staff/sports/certificates/create') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">ชื่อแบบเกียรติบัตร <span class="text-rose-500">*</span></label>
                <input type="text" name="cert_title" required placeholder="เช่น เกียรติบัตรรางวัลชนะเลิศ กีฬา อบจ.คัพ" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">กำหนดใช้กับชนิดกีฬา / รุ่น</label>
                <select name="category_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                    <option value="0">-- ใช้ร่วมกันทุกชนิดกีฬาและรุ่น (General Template) --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>">
                            <?= esc($cat['sport_name']) ?> - <?= esc($cat['category_name']) ?> (<?= $cat['category_gender'] === 'female' ? 'หญิง' : ($cat['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">กลุ่มเป้าหมาย</label>
                    <select name="target_type" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                        <option value="all">ทุกคน (นักกีฬา + ผู้ฝึกสอน + ผู้ควบคุมทีม)</option>
                        <option value="athlete">เฉพาะนักกีฬา</option>
                        <option value="coach">ผู้ฝึกสอน / ผู้ควบคุมทีม / จนท.</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ระดับรางวัล</label>
                    <select name="award_level" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                        <option value="all">ทุกระดับรางวัล</option>
                        <option value="champion">🏆 เฉพาะชนะเลิศ</option>
                        <option value="runner_up_1">🥈 เฉพาะรองชนะเลิศอันดับ 1</option>
                        <option value="runner_up_2">🥉 เฉพาะรองชนะเลิศอันดับ 2</option>
                        <option value="runner_up_3">🎖️ เฉพาะรองชนะเลิศอันดับ 3</option>
                        <option value="participation">📜 เฉพาะผู้เข้าร่วม</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">รหัสเกียรติบัตรนำหน้า (Certificate Prefix)</label>
                <input type="text" name="cert_prefix" value="PAO-SP-2569/" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono font-medium focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeCreateCertModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-extrabold text-xs flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all hover:scale-105 active:scale-95">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>สร้างและไปตั้งค่าพิกัด</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Certificate Details -->
<div id="modal-edit-cert" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl animate-[fadeIn_0.2s_ease-out]">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-base">แก้ไขรายละเอียดแบบเกียรติบัตร</h3>
                    <p class="text-[11px] text-slate-400">ปรับปรุงข้อมูลทั่วไป ชนิดกีฬา และเงื่อนไขรางวัล</p>
                </div>
            </div>
            <button onclick="closeEditCertModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="form-edit-cert" action="" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">ชื่อแบบเกียรติบัตร <span class="text-rose-500">*</span></label>
                <input type="text" id="edit-cert-title" name="cert_title" required placeholder="ชื่อแบบเกียรติบัตร" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">กำหนดใช้กับชนิดกีฬา / รุ่น</label>
                <select id="edit-category-id" name="category_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                    <option value="0">-- ใช้ร่วมกันทุกชนิดกีฬาและรุ่น (General Template) --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>">
                            <?= esc($cat['sport_name']) ?> - <?= esc($cat['category_name']) ?> (<?= $cat['category_gender'] === 'female' ? 'หญิง' : ($cat['category_gender'] === 'mixed' ? 'ผสม' : 'ชาย') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">กลุ่มเป้าหมาย</label>
                    <select id="edit-target-type" name="target_type" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                        <option value="all">ทุกคน (นักกีฬา + ผู้ฝึกสอน + ผู้ควบคุมทีม)</option>
                        <option value="athlete">เฉพาะนักกีฬา</option>
                        <option value="coach">ผู้ฝึกสอน / ผู้ควบคุมทีม / จนท.</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ระดับรางวัล</label>
                    <select id="edit-award-level" name="award_level" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                        <option value="all">ทุกระดับรางวัล</option>
                        <option value="champion">🏆 เฉพาะชนะเลิศ</option>
                        <option value="runner_up_1">🥈 เฉพาะรองชนะเลิศอันดับ 1</option>
                        <option value="runner_up_2">🥉 เฉพาะรองชนะเลิศอันดับ 2</option>
                        <option value="runner_up_3">🎖️ เฉพาะรองชนะเลิศอันดับ 3</option>
                        <option value="participation">📜 เฉพาะผู้เข้าร่วม</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">รหัสเกียรติบัตรนำหน้า (Certificate Prefix)</label>
                <input type="text" id="edit-cert-prefix" name="cert_prefix" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono font-medium focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">สถานะการใช้งาน</label>
                <select id="edit-is-active" name="is_active" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                    <option value="1">เปิดใช้งาน (Active)</option>
                    <option value="0">ปิดใช้งานชั่วคราว (Inactive)</option>
                </select>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeEditCertModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-extrabold text-xs flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all hover:scale-105 active:scale-95">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>บันทึกการแก้ไข</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateCertModal() {
        document.getElementById('modal-create-cert').classList.remove('hidden');
        if (window.lucide) lucide.createIcons();
    }
    function closeCreateCertModal() {
        document.getElementById('modal-create-cert').classList.add('hidden');
    }

    function openEditCertModal(cert) {
        const form = document.getElementById('form-edit-cert');
        form.action = '<?= base_url('staff/sports/certificates/update') ?>/' + cert.cert_id;
        
        document.getElementById('edit-cert-title').value = cert.cert_title || '';
        document.getElementById('edit-category-id').value = cert.category_id !== null ? cert.category_id : 0;
        document.getElementById('edit-target-type').value = cert.target_type || 'all';
        document.getElementById('edit-award-level').value = cert.award_level || 'all';
        document.getElementById('edit-cert-prefix').value = cert.cert_prefix || '';
        document.getElementById('edit-is-active').value = (cert.is_active == 1 || cert.is_active == '1') ? '1' : '0';

        document.getElementById('modal-edit-cert').classList.remove('hidden');
        if (window.lucide) lucide.createIcons();
    }
    function closeEditCertModal() {
        document.getElementById('modal-edit-cert').classList.add('hidden');
    }

    function confirmDeleteCert(url, title) {
        if (confirm('คุณต้องการลบแบบเกียรติบัตร "' + title + '" หรือไม่?\n(การกระทำนี้ไม่สามารถย้อนกลับได้)')) {
            window.location.href = url;
        }
    }
</script>
<?= $this->endSection() ?>
