<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
    <!-- Header -->
    <div class="mb-8" data-aos="fade-up">
        <a href="<?= base_url('staff/scholarships') ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-600 transition-colors mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปยังทุนการศึกษา
        </a>
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 mb-2">รายชื่อผู้จองคิว</h2>
                <p class="text-slate-400 font-medium tracking-wide flex items-center gap-2 text-xs">
                    <i data-lucide="users" class="w-4 h-4 text-emerald-600"></i>
                    <span class="font-bold text-emerald-600"><?= esc($scholarship['sch_title']) ?></span>
                </p>
            </div>
            <div class="flex gap-3">
                <a href="<?= base_url("staff/scholarship/{$scholarship['sch_id']}/slots") ?>" class="px-5 py-2.5 bg-violet-600 text-white rounded-2xl font-bold flex items-center gap-2 hover:bg-violet-700 transition-colors shadow-lg shadow-violet-100 text-sm">
                    <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                    จัดการสล็อต
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8" data-aos="fade-up" data-aos-delay="50">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">ทั้งหมด</div>
            <div class="text-3xl font-black text-slate-800"><?= $stats['total'] ?></div>
        </div>
        <div class="bg-amber-50 rounded-2xl border border-amber-100 p-5 shadow-sm">
            <div class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-2">รอยืนยัน</div>
            <div class="text-3xl font-black text-amber-600"><?= $stats['pending'] ?></div>
        </div>
        <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5 shadow-sm">
            <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2">ยืนยันแล้ว</div>
            <div class="text-3xl font-black text-blue-600"><?= $stats['confirmed'] ?></div>
        </div>
        <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-5 shadow-sm">
            <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-2">มาแล้ว</div>
            <div class="text-3xl font-black text-emerald-600"><?= $stats['checked_in'] ?></div>
        </div>
        <div class="bg-rose-50 rounded-2xl border border-rose-100 p-5 shadow-sm">
            <div class="text-xs font-bold text-rose-500 uppercase tracking-wider mb-2">ยกเลิก</div>
            <div class="text-3xl font-black text-rose-500"><?= $stats['cancelled'] ?></div>
        </div>
    </div>

    <!-- Date Filter -->
    <?php if (!empty($available_dates)): ?>
    <div class="mb-6 flex flex-wrap gap-2" data-aos="fade-up" data-aos-delay="100">
        <?php foreach ($available_dates as $d): ?>
            <?php
                $dateObj = new DateTime($d['slot_date']);
                $thaiMonths = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
                $dayLabel = $dateObj->format('j') . ' ' . $thaiMonths[(int)$dateObj->format('n')];
            ?>
            <a href="<?= base_url("staff/scholarship/{$scholarship['sch_id']}/bookings?date={$d['slot_date']}") ?>"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all <?= $filter_date == $d['slot_date'] ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' : 'bg-white text-slate-500 border border-slate-200 hover:border-emerald-300' ?>">
                <?= $dayLabel ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Bookings Table -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden" data-aos="fade-up" data-aos-delay="150">
        <div class="px-8 py-4 bg-slate-50 border-b border-slate-100">
            <p class="text-sm font-bold text-slate-600">
                <i data-lucide="calendar" class="w-4 h-4 inline mr-1 text-emerald-500"></i>
                วันที่ <?= date('d/m/Y', strtotime($filter_date)) ?>
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400 w-16 text-center">#</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-400">คิว</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-400">ช่วงเวลา</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-400">ชื่อ-สกุล</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-400 text-center">โทรศัพท์</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-400">สถานศึกษา (ระดับชั้น)</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-400">สถานะ</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-400 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="7" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center gap-4 text-slate-300">
                                    <i data-lucide="inbox" class="w-16 h-16"></i>
                                    <p class="font-bold text-slate-400">ยังไม่มีผู้จองในวันนี้</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach ($bookings as $b): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-black text-slate-400 text-sm italic text-center">
                                    <?= str_pad($i++, 2, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center text-violet-700 font-black text-lg">
                                        <?= $b['bk_queue_number'] ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 text-violet-700 rounded-lg text-xs font-black">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        <?= date('H:i', strtotime($b['slot_start_time'])) ?> - <?= date('H:i', strtotime($b['slot_end_time'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-black text-slate-800"><?= esc($b['bk_fullname']) ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-600 text-center"><?= esc($b['bk_phone']) ?></td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-500">
                                    <div class="font-bold text-slate-700"><?= esc($b['bk_school']) ?></div>
                                    <div class="text-[10px] uppercase font-black text-slate-400 tracking-wider"><?= esc($b['bk_grade']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $statusConfig = [
                                            'pending'    => ['color' => 'amber', 'label' => 'รอยืนยัน', 'icon' => 'clock'],
                                            'confirmed'  => ['color' => 'blue',  'label' => 'ยืนยันแล้ว', 'icon' => 'check-circle'],
                                            'checked_in' => ['color' => 'emerald', 'label' => 'มาแล้ว', 'icon' => 'user-check'],
                                            'cancelled'  => ['color' => 'rose', 'label' => 'ยกเลิก', 'icon' => 'x-circle'],
                                        ];
                                        $sc = $statusConfig[$b['bk_status']] ?? $statusConfig['pending'];
                                    ?>
                                    <span class="flex items-center gap-1.5 text-<?= $sc['color'] ?>-500 text-xs font-bold">
                                        <i data-lucide="<?= $sc['icon'] ?>" class="w-3.5 h-3.5"></i>
                                        <?= $sc['label'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <?php if ($b['bk_status'] !== 'checked_in' && $b['bk_status'] !== 'cancelled'): ?>
                                            <a href="<?= base_url("staff/scholarship/booking/status/{$b['bk_id']}?status=checked_in") ?>"
                                                class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all flex items-center gap-1"
                                                title="Check-in">
                                                <i data-lucide="user-check" class="w-3.5 h-3.5"></i> Check-in
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($b['bk_status'] !== 'cancelled'): ?>
                                            <a href="<?= base_url("staff/scholarship/booking/status/{$b['bk_id']}?status=cancelled") ?>"
                                                class="px-3 py-1.5 bg-rose-50 text-rose-500 rounded-lg text-xs font-bold hover:bg-rose-600 hover:text-white transition-all flex items-center gap-1"
                                                title="ยกเลิก">
                                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>
