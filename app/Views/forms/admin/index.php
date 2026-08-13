<?= $this->extend('forms/layout/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">

    <!-- Title & Create Button -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">รายการแบบสอบถามทั้งหมด</h2>
            <p class="text-slate-500 text-xs font-semibold mt-1">จัดการแบบสอบถาม สร้างคำถาม และตั้งค่าการออกเกียรติบัตรออนไลน์</p>
        </div>
        <button onclick="openCreateModal()" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-extrabold text-sm flex items-center justify-center gap-2 shadow-lg shadow-indigo-200 transition-all hover:scale-[1.02]">
            <i data-lucide="plus-circle" class="w-5 h-5"></i> สร้างแบบสอบถามใหม่
        </button>
    </div>

    <!-- Forms List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (empty($forms)): ?>
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i data-lucide="file-question" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700">ยังไม่มีแบบสอบถาม</h3>
                <p class="text-slate-400 text-xs mt-1 mb-6">เริ่มสร้างแบบสอบถามแรกของคุณเพื่อรวบรวมข้อมูลและแจกเกียรติบัตร</p>
                <button onclick="openCreateModal()" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-xs">
                    + สร้างแบบสอบถาม
                </button>
            </div>
        <?php else: ?>
            <?php foreach ($forms as $f): ?>
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2">
                                <button onclick="toggleFormStatus(<?= $f['form_id'] ?>, this)" id="status-btn-<?= $f['form_id'] ?>" data-status="<?= $f['form_status'] ?>" title="คลิกเพื่อสลับเปิด-ปิดใช้งานเป็นสาธารณะ" class="px-3.5 py-1.5 rounded-full text-xs font-black flex items-center gap-1.5 transition-all shadow-sm hover:scale-105 cursor-pointer <?= $f['form_status'] === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-300 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' ?>">
                                    <span class="w-2.5 h-2.5 rounded-full <?= $f['form_status'] === 'active' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' ?>"></span>
                                    <span class="status-label"><?= $f['form_status'] === 'active' ? '● เปิดเป็นสาธารณะ' : '○ ปิดใช้งาน' ?></span>
                                </button>

                                <?php if ($f['is_owner']): ?>
                                    <?php 
                                    $isSharedVal = (int)($f['form_is_shared'] ?? 1);
                                    $shareBadgeClass = 'bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100';
                                    $shareBadgeText = '👥 แชร์ทุกคน';
                                    $shareBadgeIcon = 'users';

                                    if ($isSharedVal === 2) {
                                        $shareBadgeClass = 'bg-sky-50 text-sky-700 border border-sky-200 hover:bg-sky-100';
                                        $shareBadgeText = '🎯 แชร์เลือกคน';
                                        $shareBadgeIcon = 'user-check';
                                    } elseif ($isSharedVal === 0) {
                                        $shareBadgeClass = 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200';
                                        $shareBadgeText = '🔒 ส่วนตัว';
                                        $shareBadgeIcon = 'lock';
                                    }
                                    ?>
                                    <button onclick="openPermissionsModal(<?= $f['form_id'] ?>, '<?= esc($f['form_title'], 'js') ?>')" id="share-btn-<?= $f['form_id'] ?>" title="คลิกเพื่อกำหนดสิทธิ์ผู้จัดการแบบสอบถาม" class="px-3 py-1.5 rounded-full text-xs font-extrabold flex items-center gap-1.5 transition-all shadow-sm hover:scale-105 cursor-pointer <?= $shareBadgeClass ?>">
                                        <i data-lucide="<?= $shareBadgeIcon ?>" class="w-3.5 h-3.5"></i>
                                        <span class="share-label"><?= $shareBadgeText ?></span>
                                    </button>
                                <?php else: ?>
                                    <span class="px-3 py-1.5 rounded-full text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1.5" title="แบบสอบถามนี้ได้รับการแชร์มาจาก <?= esc($f['creator_name'] ?: 'เพื่อนร่วมงาน') ?>">
                                        <i data-lucide="users" class="w-3.5 h-3.5 text-indigo-600"></i>
                                        <span>แชร์จาก: <?= esc($f['creator_name'] ?: 'เจ้าหน้าที่') ?></span>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if ($f['form_has_certificate'] == 1): ?>
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                                    <i data-lucide="award" class="w-3.5 h-3.5"></i> มีเกียรติบัตร
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold text-slate-400 bg-slate-50">
                                    ไม่มีเกียรติบัตร
                                </span>
                            <?php endif; ?>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 line-clamp-1 mb-1"><?= esc($f['form_title']) ?></h3>
                        <p class="text-slate-500 text-xs line-clamp-2 mb-4"><?= esc($f['form_description'] ?: 'ไม่มีคำอธิบายเพิ่มเติม') ?></p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                        <!-- Combined Response Count & Dashboard Link -->
                        <a href="<?= base_url("staff/forms/responses/{$f['form_id']}") ?>" title="ดูสรุปผลการตอบและสถิติ" class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200/80 rounded-2xl font-extrabold text-xs flex items-center gap-2 transition-all shadow-sm group">
                            <i data-lucide="bar-chart-2" class="w-4 h-4 text-emerald-600 group-hover:scale-110 transition-transform"></i>
                            <span>ผลการตอบ <strong class="text-emerald-950 font-black text-sm ml-0.5"><?= number_format($f['response_count']) ?></strong> คน</span>
                        </a>

                        <!-- Action Buttons Group -->
                        <div class="flex items-center gap-2">
                            <!-- Share & QR Code -->
                            <button onclick="openShareModal('<?= esc(!empty($f['form_code']) ? $f['form_code'] : $f['form_id'], 'js') ?>', '<?= esc($f['form_title'], 'js') ?>')" title="แชร์ฟอร์ม & QR Code" class="px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer">
                                <i data-lucide="qr-code" class="w-4 h-4 text-indigo-600"></i>
                                <span>แชร์ / QR</span>
                            </button>

                            <!-- Primary Action: Edit Questions -->
                            <a href="<?= base_url("staff/forms/builder/{$f['form_id']}") ?>" title="จัดการแก้ไขคำถามแบบสอบถาม" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors shadow-sm shadow-indigo-200">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                <span>จัดการคำถาม</span>
                            </a>

                            <!-- Settings Dropdown Menu -->
                            <div class="relative group/dropdown inline-block">
                                <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer">
                                    <i data-lucide="settings" class="w-4 h-4 text-slate-500"></i>
                                    <span>ตั้งค่า</span>
                                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-60"></i>
                                </button>

                                <div class="absolute right-0 bottom-full mb-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 hidden group-hover/dropdown:block group-focus-within/dropdown:block z-30 transition-all">
                                    <a href="<?= base_url("staff/forms/edit/{$f['form_id']}") ?>" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                                        <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i> ตั้งค่าทั่วไป
                                    </a>
                                    <a href="<?= base_url("staff/forms/certificate/{$f['form_id']}") ?>" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-amber-800 hover:bg-amber-50 transition-colors">
                                        <i data-lucide="award" class="w-4 h-4 text-amber-600"></i> ตั้งค่าเกียรติบัตร
                                    </a>
                                    <a href="<?= base_url("forms/view/" . (!empty($f['form_code']) ? $f['form_code'] : $f['form_id'])) ?>" target="_blank" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                                        <i data-lucide="external-link" class="w-4 h-4 text-slate-400"></i> ดูหน้าฟอร์มจริง
                                    </a>
                                    <div class="border-t border-slate-100 my-1"></div>
                                    <button onclick="confirmDelete(<?= $f['form_id'] ?>)" class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer">
                                        <i data-lucide="trash-2" class="w-4 h-4 text-rose-500"></i> ลบแบบสอบถาม
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal: Share & QR Code -->
<div id="share-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl space-y-6 text-center">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i data-lucide="share-2" class="w-5 h-5 text-indigo-600"></i> แชร์แบบสอบถาม & QR Code
            </h3>
            <button onclick="closeShareModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <div class="space-y-4">
            <h4 id="share-modal-title" class="text-sm font-bold text-slate-800 line-clamp-1"></h4>
            
            <!-- QR Code Container -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl inline-block shadow-inner">
                <img id="share-qr-image" src="" alt="QR Code" class="w-48 h-48 mx-auto rounded-lg">
            </div>

            <!-- Copy Link Input -->
            <div class="space-y-2 text-left">
                <label class="block text-xs font-bold text-slate-600 uppercase">ลิงก์สำหรับส่งให้ผู้ตอบแบบสอบถาม</label>
                <div class="flex items-center gap-2">
                    <input type="text" id="share-link-input" readonly class="flex-1 px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl font-mono text-xs text-slate-700">
                    <button onclick="copyShareLink()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-md shadow-indigo-100 transition-colors">
                        <i data-lucide="copy" class="w-4 h-4"></i> คัดลอก
                    </button>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-center gap-3">
            <a id="share-live-link" href="" target="_blank" class="w-1/2 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-colors">
                <i data-lucide="external-link" class="w-4 h-4 text-indigo-600"></i> เปิดดูฟอร์มจริง
            </a>
            <a id="share-qr-download" href="" download="qrcode.png" target="_blank" class="w-1/2 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-2 transition-colors">
                <i data-lucide="download" class="w-4 h-4"></i> โหลด QR Code
            </a>
        </div>
    </div>
</div>

<!-- Modal: Create Form -->
<div id="create-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-lg font-black text-slate-900">สร้างแบบสอบถามใหม่</h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>

        <form id="create-form" onsubmit="handleCreate(event)" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">ชื่อแบบสอบถาม <span class="text-rose-500">*</span></label>
                <input type="text" name="form_title" required placeholder="เช่น แบบประเมินความพึงพอใจการอบรม 2026" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">คำอธิบายเพิ่มเติม</label>
                <textarea name="form_description" rows="3" placeholder="ระบุวัตถุประสงค์ หรือรายละเอียดเพิ่มเติม..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm"></textarea>
            </div>

            <div class="space-y-3">
                <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="form_has_certificate" value="1" class="w-5 h-5 text-indigo-600 rounded-md focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-extrabold text-slate-800">เปิดใช้งานเกียรติบัตรออนไลน์ (E-Certificate)</span>
                            <p class="text-[11px] text-slate-500 font-semibold">เมื่อตอบแบบสอบถามเสร็จ ระบบจะออกใบเกียรติบัตรให้ดาวน์โหลดอัตโนมัติ</p>
                        </div>
                    </label>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="form_is_shared" value="1" checked class="w-5 h-5 text-indigo-600 rounded-md focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-extrabold text-slate-800">แชร์ให้เพื่อนในองค์กรช่วยจัดการ (Form Sharing)</span>
                            <p class="text-[11px] text-slate-500 font-semibold">อนุญาตให้เจ้าหน้าที่ท่านอื่นที่ Login สามารถดู แก้ไขคำถาม และดูสรุปผลตอบได้</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">ยกเลิก</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-xs shadow-lg shadow-indigo-100">สร้างแบบสอบถาม</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Granular Permission Management -->
<div id="modal-permissions" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 md:p-8 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i data-lucide="shield-check" class="w-5 h-5 text-indigo-600"></i> กำหนดสิทธิ์การจัดการแบบสอบถาม
            </h3>
            <button onclick="closePermissionsModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form id="form-permissions" onsubmit="savePermissions(event)" class="space-y-5">
            <input type="hidden" id="perm-form-id" name="form_id">
            
            <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                <h4 id="perm-form-title" class="text-sm font-black text-slate-900 line-clamp-1"></h4>
                <p class="text-xs text-slate-500 font-medium mt-0.5">เลือกสิทธิ์การเข้าถึง ดูผลคำตอบ และแก้ไขแบบสอบถามนี้</p>
            </div>

            <!-- Radio Scope Options -->
            <div class="space-y-3">
                <label class="flex items-start gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                    <input type="radio" name="form_is_shared" value="0" onchange="toggleStaffChecklist(this.value)" class="w-4 h-4 mt-0.5 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <span class="text-xs font-black text-slate-900 block">🔒 ส่วนตัว (Private)</span>
                        <span class="text-[11px] text-slate-500 font-semibold">ให้สิทธิ์เฉพาะผู้สร้างฟอร์มจัดการได้เพียงคนเดียว</span>
                    </div>
                </label>

                <label class="flex items-start gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                    <input type="radio" name="form_is_shared" value="1" onchange="toggleStaffChecklist(this.value)" class="w-4 h-4 mt-0.5 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <span class="text-xs font-black text-slate-900 block">👥 เจ้าหน้าที่ทุกคนในองค์กร (Shared All)</span>
                        <span class="text-[11px] text-slate-500 font-semibold">อนุญาตให้เจ้าหน้าที่ทุกคนที่ Login สามารถดูและร่วมจัดการได้</span>
                    </div>
                </label>

                <label class="flex items-start gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                    <input type="radio" name="form_is_shared" value="2" onchange="toggleStaffChecklist(this.value)" class="w-4 h-4 mt-0.5 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <span class="text-xs font-black text-slate-900 block">🎯 เฉพาะบุคลากรที่เลือก (ระบุรายบุคคลทุกฝ่าย)</span>
                        <span class="text-[11px] text-slate-500 font-semibold">เลือกระบุรายชื่อบุคลากรทุกฝ่ายในกองการศึกษาฯ (ผู้บริหาร / ฝ่ายบริหาร / ฝ่ายส่งเสริม)</span>
                    </div>
                </label>
            </div>

            <!-- Staff Selection Checklist Box (shown when value === 2) -->
            <div id="staff-checklist-box" class="hidden space-y-3 pt-2">
                <div class="flex items-center justify-between">
                    <label id="staff-checklist-label" class="text-xs font-extrabold text-slate-800">เลือกรายชื่อผู้มีสิทธิ์จัดการ:</label>
                    <input type="text" id="staff-search-input" oninput="filterStaffList()" placeholder="ค้นหาชื่อเจ้าหน้าที่..." class="px-3 py-1 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="max-h-52 overflow-y-auto border border-slate-200 rounded-2xl divide-y divide-slate-100 p-1 bg-white" id="staff-checklist-container">
                    <!-- Staff checkboxes loaded dynamically -->
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closePermissionsModal()" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">ยกเลิก</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-lg shadow-indigo-100">บันทึกสิทธิ์การแชร์</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let currentStaffList = [];

    function openCreateModal() {
        document.getElementById('create-modal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
    }

    async function openPermissionsModal(formId, formTitle) {
        document.getElementById('perm-form-id').value = formId;
        document.getElementById('perm-form-title').innerText = formTitle;

        Swal.fire({
            title: 'กำลังดึงสิทธิ์การแชร์...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const res = await fetch(`<?= base_url('staff/forms/get-permissions/') ?>${formId}`);
            const data = await res.json();
            Swal.close();

            if (data.status === 'success') {
                currentStaffList = data.staff || [];
                const isShared = data.form_is_shared;
                const sharedUsers = data.form_shared_users || [];

                const labelEl = document.getElementById('staff-checklist-label');
                if (labelEl) {
                    labelEl.innerText = `เลือกรายชื่อผู้มีสิทธิ์จัดการ (${data.current_division || 'บุคลากรกองการศึกษา'}):`;
                }

                const radios = document.querySelectorAll('input[name="form_is_shared"]');
                radios.forEach(r => {
                    r.checked = (parseInt(r.value) === isShared);
                });

                renderStaffChecklist(sharedUsers);
                toggleStaffChecklist(isShared);

                document.getElementById('modal-permissions').classList.remove('hidden');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            } else {
                Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: data.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: 'ไม่สามารถดึงข้อมูลสิทธิ์ได้' });
        }
    }

    function renderStaffChecklist(selectedUserIds = []) {
        const container = document.getElementById('staff-checklist-container');
        container.innerHTML = '';

        if (!currentStaffList || currentStaffList.length === 0) {
            container.innerHTML = `<div class="p-4 text-center text-xs text-slate-400">ไม่พบรายชื่อเจ้าหน้าที่ในระบบ</div>`;
            return;
        }

        currentStaffList.forEach(s => {
            const isChecked = selectedUserIds.includes(parseInt(s.u_id)) ? 'checked' : '';
            const divText = s.u_division ? ` • ${s.u_division}` : '';
            const itemHtml = `
                <label class="staff-item flex items-center justify-between p-2.5 hover:bg-indigo-50/50 rounded-xl cursor-pointer transition-colors" data-name="${(s.u_fullname || '').toLowerCase()} ${(s.u_division || '').toLowerCase()}">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" name="shared_users[]" value="${s.u_id}" ${isChecked} class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">${s.u_fullname || 'เจ้าหน้าที่'}</span>
                            <span class="text-[10px] text-slate-400 font-medium">${s.u_position || 'เจ้าหน้าที่'}${divText}</span>
                        </div>
                    </div>
                </label>
            `;
            container.insertAdjacentHTML('beforeend', itemHtml);
        });
    }

    function toggleStaffChecklist(val) {
        const box = document.getElementById('staff-checklist-box');
        if (parseInt(val) === 2) {
            box.classList.remove('hidden');
        } else {
            box.classList.add('hidden');
        }
    }

    function filterStaffList() {
        const q = document.getElementById('staff-search-input').value.toLowerCase();
        document.querySelectorAll('#staff-checklist-container .staff-item').forEach(el => {
            const name = el.getAttribute('data-name') || '';
            if (name.includes(q)) {
                el.style.display = 'flex';
            } else {
                el.style.display = 'none';
            }
        });
    }

    function closePermissionsModal() {
        document.getElementById('modal-permissions').classList.add('hidden');
    }

    async function savePermissions(e) {
        e.preventDefault();
        const form = e.target;
        const formId = document.getElementById('perm-form-id').value;
        const formData = new FormData(form);

        Swal.fire({
            title: 'กำลังบันทึกสิทธิ์...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const res = await fetch(`<?= base_url('staff/forms/save-permissions/') ?>${formId}`, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            Swal.close();

            if (data.status === 'success') {
                closePermissionsModal();
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: data.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: 'ไม่สามารถบันทึกข้อมูลได้' });
        }
    }

    function toggleFormShare(formId, btn) {
        const currentShared = parseInt(btn.getAttribute('data-shared') || '1');

        fetch('<?= base_url('staff/forms/toggle-share/') ?>' + formId, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                btn.setAttribute('data-shared', data.form_is_shared);
                const label = btn.querySelector('.share-label');

                if (data.form_is_shared === 1) {
                    btn.className = 'px-3 py-1.5 rounded-full text-xs font-extrabold flex items-center gap-1.5 transition-all shadow-sm hover:scale-105 cursor-pointer bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100';
                    if (label) label.innerText = '👥 แชร์จัดการร่วมกัน';
                } else {
                    btn.className = 'px-3 py-1.5 rounded-full text-xs font-extrabold flex items-center gap-1.5 transition-all shadow-sm hover:scale-105 cursor-pointer bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200';
                    if (label) label.innerText = '🔒 ส่วนตัว';
                }
                if (typeof lucide !== 'undefined') lucide.createIcons();

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2500
                });
            } else {
                Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: data.message });
            }
        })
        .catch(err => {
            Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้' });
        });
    }

    function openShareModal(formId, formTitle) {
        const publicUrl = `<?= base_url('forms/view/') ?>` + formId;
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=` + encodeURIComponent(publicUrl);

        document.getElementById('share-modal-title').innerText = formTitle;
        document.getElementById('share-link-input').value = publicUrl;
        document.getElementById('share-qr-image').src = qrUrl;
        document.getElementById('share-qr-download').href = qrUrl;
        const liveBtn = document.getElementById('share-live-link');
        if (liveBtn) liveBtn.href = publicUrl;

        document.getElementById('share-modal').classList.remove('hidden');
    }

    function closeShareModal() {
        document.getElementById('share-modal').classList.add('hidden');
    }

    function copyShareLink() {
        const input = document.getElementById('share-link-input');
        input.select();
        navigator.clipboard.writeText(input.value);
        Swal.fire({ icon: 'success', title: 'คัดลอกลิงก์สำเร็จ!', timer: 1500, showConfirmButton: false });
    }

    async function handleCreate(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        const res = await fetch('<?= base_url('staff/forms/store') ?>', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.status === 'success') {
            window.location.href = data.redirect;
        } else {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: data.message });
        }
    }

    async function toggleFormStatus(formId, btn) {
        const currentStatus = btn.getAttribute('data-status');
        try {
            const res = await fetch(`<?= base_url('staff/forms/toggle-status/') ?>${formId}`, {
                method: 'POST'
            });
            const data = await res.json();
            if (data.status === 'success') {
                const newStatus = data.new_status;
                btn.setAttribute('data-status', newStatus);
                const label = btn.querySelector('.status-label');
                const dot = btn.querySelector('span:first-child');
                
                if (newStatus === 'active') {
                    btn.className = 'px-3.5 py-1.5 rounded-full text-xs font-black flex items-center gap-1.5 transition-all shadow-sm hover:scale-105 cursor-pointer bg-emerald-50 text-emerald-700 border border-emerald-300 hover:bg-emerald-100';
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse';
                    label.innerText = '● เปิดเป็นสาธารณะ';
                } else {
                    btn.className = 'px-3.5 py-1.5 rounded-full text-xs font-black flex items-center gap-1.5 transition-all shadow-sm hover:scale-105 cursor-pointer bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100';
                    dot.className = 'w-2.5 h-2.5 rounded-full bg-rose-500';
                    label.innerText = '○ ปิดใช้งาน (ซ่อน)';
                }
                Swal.fire({
                    icon: 'success',
                    title: data.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: data.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาดในการเชื่อมต่อ' });
        }
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบแบบสอบถาม?',
            text: 'คำตอบและข้อมูลทั้งหมดที่เกี่ยวข้องจะถูกลบทันที!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, ลบทันที',
            cancelButtonText: 'ยกเลิก'
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href = '<?= base_url('staff/forms/delete/') ?>' + id;
            }
        });
    }
</script>
<?= $this->endSection() ?>

