<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<?= view('sports/admin/layout/nav') ?>

<div class="space-y-6">
    <!-- Header with Create Action -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full text-xs font-semibold mb-1">
                <i data-lucide="award" class="w-3.5 h-3.5 text-amber-600"></i>
                <span>Sports Certificates Management</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">ระบบจัดการและออกเกียรติบัตร</h1>
            <p class="text-xs sm:text-sm text-slate-400">สร้างเทมเพลต, อัปโหลดพื้นหลังเกียรติบัตร และตั้งค่าพิกัดข้อความด้วยระบบ Visual Coordinate Designer</p>
        </div>

        <div>
            <button onclick="openCreateCertModal()" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-xs flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all cursor-pointer">
                <i data-lucide="plus-circle" class="w-4 h-4 text-amber-300"></i>
                <span>สร้างแบบเกียรติบัตรใหม่</span>
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
            <button onclick="openCreateCertModal()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs inline-flex items-center gap-2 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>สร้างแบบเกียรติบัตรแรก</span>
            </button>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($certs as $c): ?>
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:border-emerald-200 transition-all flex flex-col justify-between group space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-black group-hover:scale-105 transition-transform">
                                <i data-lucide="award" class="w-5 h-5"></i>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black <?= $c['is_active'] ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' ?>">
                                <?= $c['is_active'] ? 'เปิดใช้งาน' : 'ปิดใช้งาน' ?>
                            </span>
                        </div>

                        <div>
                            <h3 class="text-base font-black text-slate-900 group-hover:text-emerald-700 transition-colors">
                                <?= esc($c['cert_title']) ?>
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">
                                รุ่น: <strong class="text-slate-600"><?= $c['sport_name'] ? esc($c['sport_name']) . ' - ' . esc($c['category_name']) : 'ทุกรุ่นการแข่งขัน' ?></strong>
                            </p>
                        </div>

                        <div class="bg-slate-50 p-3 rounded-2xl text-[11px] space-y-1 text-slate-600">
                            <div>เป้าหมาย: <strong><?= $c['target_type'] === 'athlete' ? 'เฉพาะนักกีฬา' : ($c['target_type'] === 'coach' ? 'เฉพาะผู้ฝึกสอน' : 'ทุกคนในทีม') ?></strong></div>
                            <div>รางวัล: <strong><?= $c['award_level'] === 'all' ? 'ทุกระดับรางวัล' : ($c['award_level'] === 'champion' ? 'ชนะเลิศ' : esc($c['award_level'])) ?></strong></div>
                            <div>รหัสนำหน้า: <strong class="font-mono text-slate-800"><?= esc($c['cert_prefix']) ?></strong></div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                        <a href="<?= base_url("staff/sports/certificates/design/{$c['cert_id']}") ?>" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 shadow-md shadow-emerald-100 transition-all">
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
            <button onclick="closeCreateCertModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100">
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
                        <option value="all">ทุกคน (นักกีฬา + โค้ช)</option>
                        <option value="athlete">เฉพาะนักกีฬา</option>
                        <option value="coach">เฉพาะโค้ช/ผู้ฝึกสอน</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ระดับรางวัล</label>
                    <select name="award_level" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                        <option value="all">ทุกระดับรางวัล</option>
                        <option value="champion">เฉพาะชนะเลิศ</option>
                        <option value="runner_up_1">เฉพาะรองชนะเลิศอันดับ 1</option>
                        <option value="runner_up_2">เฉพาะรองชนะเลิศอันดับ 2</option>
                        <option value="runner_up_3">เฉพาะรองชนะเลิศอันดับ 3</option>
                        <option value="participation">เฉพาะผู้เข้าร่วม</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">รหัสเกียรติบัตรนำหน้า (Certificate Prefix)</label>
                <input type="text" name="cert_prefix" value="PAO-SP-2569/" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono font-medium focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeCreateCertModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs">
                    ยกเลิก
                </button>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-extrabold text-xs flex items-center gap-2 shadow-lg shadow-emerald-200">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>สร้างและไปตั้งค่าพิกัด</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateCertModal() {
        document.getElementById('modal-create-cert').classList.remove('hidden');
    }
    function closeCreateCertModal() {
        document.getElementById('modal-create-cert').classList.add('hidden');
    }
</script>
<?= $this->endSection() ?>
