<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
    <!-- Header -->
    <div class="mb-8" data-aos="fade-up">
        <a href="<?= base_url('staff/scholarships') ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-600 transition-colors mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปยังทุนการศึกษา
        </a>
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 mb-2">ตั้งค่าตารางจองคิว</h2>
                <p class="text-slate-400 font-medium tracking-wide flex items-center gap-2 text-xs">
                    <i data-lucide="calendar-clock" class="w-4 h-4 text-violet-600"></i>
                    <span class="font-bold text-violet-600"><?= esc($scholarship['sch_title']) ?></span>
                </p>
            </div>
            <div class="flex gap-3">
                <a href="<?= base_url("staff/scholarship/{$scholarship['sch_id']}/bookings") ?>" class="px-5 py-2.5 bg-emerald-600 text-white rounded-2xl font-bold flex items-center gap-2 hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-100 text-sm">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    ดูรายชื่อผู้จอง
                </a>
            </div>
        </div>
    </div>

    <!-- Generator Form -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-100 p-8 mb-8" data-aos="fade-up" data-aos-delay="100">
        <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center text-violet-600">
                <i data-lucide="wand-2" class="w-5 h-5"></i>
            </div>
            สร้างสล็อตอัตโนมัติ
        </h3>
        <form action="<?= base_url("staff/scholarship/{$scholarship['sch_id']}/slots/generate") ?>" method="post">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">วันที่</label>
                    <input type="date" name="slot_date" value="<?= date('Y-m-d') ?>" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">เวลาเริ่ม</label>
                    <input type="time" name="start_time" value="09:00" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">เวลาสิ้นสุด</label>
                    <input type="time" name="end_time" value="12:00" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">นาที/รอบ</label>
                    <select name="duration" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition-all">
                        <option value="5">5 นาที</option>
                        <option value="10" selected>10 นาที</option>
                        <option value="15">15 นาที</option>
                        <option value="20">20 นาที</option>
                        <option value="30">30 นาที</option>
                        <option value="60">60 นาที</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">รับสูงสุด/รอบ</label>
                    <input type="number" name="max_per_slot" value="1" min="1" max="100" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition-all">
                </div>
            </div>
            <div class="mt-6 flex items-center justify-between">
                <p class="text-xs text-slate-400 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    ระบบจะสร้างช่วงเวลาจาก "เวลาเริ่ม" ถึง "เวลาสิ้นสุด" โดยแบ่งตามนาทีที่กำหนด
                </p>
                <button type="submit" class="px-8 py-3 bg-violet-600 text-white rounded-2xl font-bold flex items-center gap-2 hover:bg-violet-700 transition-colors shadow-lg shadow-violet-100">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                    สร้างสล็อต
                </button>
            </div>
        </form>
    </div>

    <!-- Date Filter -->
    <?php if (!empty($available_dates)): ?>
    <div class="mb-6 flex flex-wrap gap-2" data-aos="fade-up" data-aos-delay="150">
        <a href="<?= base_url("staff/scholarship/{$scholarship['sch_id']}/slots") ?>"
            class="px-4 py-2 rounded-xl text-xs font-bold transition-all <?= !$filter_date ? 'bg-violet-600 text-white shadow-lg shadow-violet-100' : 'bg-white text-slate-500 border border-slate-200 hover:border-violet-300' ?>">
            ทั้งหมด
        </a>
        <?php foreach ($available_dates as $d): ?>
            <?php
                $dateObj = new DateTime($d['slot_date']);
                $thaiMonths = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
                $dayLabel = $dateObj->format('j') . ' ' . $thaiMonths[(int)$dateObj->format('n')];
            ?>
            <a href="<?= base_url("staff/scholarship/{$scholarship['sch_id']}/slots?date={$d['slot_date']}") ?>"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all <?= $filter_date == $d['slot_date'] ? 'bg-violet-600 text-white shadow-lg shadow-violet-100' : 'bg-white text-slate-500 border border-slate-200 hover:border-violet-300' ?>">
                <?= $dayLabel ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Slot Table -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
        <?php if ($filter_date): ?>
            <div class="px-8 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <p class="text-sm font-bold text-slate-600">
                    <i data-lucide="calendar" class="w-4 h-4 inline mr-1 text-violet-500"></i>
                    แสดงผลวันที่ <?= date('d/m/Y', strtotime($filter_date)) ?>
                    <span class="text-slate-400 ml-2">(<?= count($slots) ?> สล็อต)</span>
                </p>
                <button onclick="confirmDeleteDay('<?= base_url("staff/scholarship/{$scholarship['sch_id']}/slots/delete-day?date={$filter_date}") ?>')" class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl text-xs font-bold hover:bg-rose-100 transition-colors flex items-center gap-1.5">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> ลบสล็อตทั้งวัน
                </button>
            </div>
        <?php endif; ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400 w-16 text-center">#</th>
                        <th class="px-8 py-4 text-xs font-black uppercase tracking-wider text-slate-400 text-center">วันที่</th>
                        <th class="px-8 py-4 text-xs font-black uppercase tracking-wider text-slate-400">ช่วงเวลา</th>
                        <th class="px-8 py-4 text-xs font-black uppercase tracking-wider text-slate-400 text-center">จองแล้ว / สูงสุด</th>
                        <th class="px-8 py-4 text-xs font-black uppercase tracking-wider text-slate-400 text-center">สถานะ</th>
                        <th class="px-8 py-4 text-xs font-black uppercase tracking-wider text-slate-400 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($slots)): ?>
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center gap-4 text-slate-300">
                                    <i data-lucide="calendar-x2" class="w-16 h-16"></i>
                                    <p class="font-bold text-slate-400">ยังไม่มีสล็อตที่สร้างไว้</p>
                                    <p class="text-xs text-slate-400">ใช้ฟอร์มด้านบนเพื่อสร้างสล็อตอัตโนมัติ</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach ($slots as $s): ?>
                            <?php
                                $isFull = $s['booked_count'] >= $s['slot_max'];
                                $isActive = $s['slot_is_active'];
                            ?>
                            <tr class="hover:bg-slate-50 transition-colors <?= !$isActive ? 'opacity-50' : '' ?>">
                                <td class="px-8 py-4 font-black text-slate-400 text-sm italic text-center">
                                    <?= str_pad($i++, 2, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="px-8 py-4 text-sm font-bold text-slate-700">
                                    <?= date('d/m/Y', strtotime($s['slot_date'])) ?>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-violet-50 text-violet-700 rounded-lg text-sm font-black">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                        <?= date('H:i', strtotime($s['slot_start_time'])) ?> - <?= date('H:i', strtotime($s['slot_end_time'])) ?>
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-24 h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500 <?= $isFull ? 'bg-rose-500' : 'bg-emerald-500' ?>"
                                                style="width: <?= $s['slot_max'] > 0 ? min(100, ($s['booked_count'] / $s['slot_max']) * 100) : 0 ?>%">
                                            </div>
                                        </div>
                                        <span class="text-sm font-black <?= $isFull ? 'text-rose-500' : 'text-slate-700' ?>">
                                            <?= $s['booked_count'] ?>/<?= $s['slot_max'] ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <?php if (!$isActive): ?>
                                        <span class="flex items-center gap-1.5 text-slate-400 text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> ปิดรับ
                                        </span>
                                    <?php elseif ($isFull): ?>
                                        <span class="flex items-center gap-1.5 text-rose-500 text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> เต็ม
                                        </span>
                                    <?php else: ?>
                                        <span class="flex items-center gap-1.5 text-emerald-500 text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> เปิดรับ
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <a href="<?= base_url("staff/scholarship/slot/toggle/{$s['slot_id']}") ?>"
                                        class="w-9 h-9 inline-flex items-center justify-center bg-slate-100 text-slate-600 rounded-xl hover:bg-violet-600 hover:text-white transition-all"
                                        title="<?= $isActive ? 'ปิดรับ' : 'เปิดรับ' ?>">
                                        <i data-lucide="<?= $isActive ? 'pause' : 'play' ?>" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function confirmDeleteDay(url) {
        Swal.fire({
            title: 'ลบสล็อตทั้งวัน?',
            text: "สล็อตและรายชื่อผู้จองในวันนี้จะถูกลบถาวร!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ลบทันที',
            cancelButtonText: 'ยกเลิก',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl px-6 py-3 font-bold',
                cancelButton: 'rounded-xl px-6 py-3 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    }
</script>
<?= $this->endSection() ?>
