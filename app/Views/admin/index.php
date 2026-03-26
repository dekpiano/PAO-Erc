<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stats 1 -->
        <div class="stat-card">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-blue-500/10 rounded-xl">
                    <i data-lucide="database" class="w-6 h-6 text-blue-400"></i>
                </div>
                <h3 class="text-sm font-semibold uppercase tracking-widest text-white/40">รายการกรอง</h3>
            </div>
            <p class="text-4xl font-black text-white/90 leading-none"><?= number_format($stats['total_filter'] ?? 0) ?></p>
            <p class="text-xs text-white/20 mt-2 uppercase">ข้อมูลที่แสดงในตารางขณะนี้</p>
        </div>

        <!-- Stats 2 -->
        <div class="stat-card border-emerald-500/5 hover:border-emerald-500/30">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-emerald-500/10 rounded-xl">
                    <i data-lucide="log-in" class="w-6 h-6 text-emerald-400"></i>
                </div>
                <h3 class="text-sm font-semibold uppercase tracking-widest text-white/40">เข้างานวันนี้</h3>
            </div>
            <p class="text-4xl font-black text-emerald-400 leading-none"><?= number_format($stats['today_in'] ?? 0) ?></p>
            <p class="text-xs text-white/20 mt-2 uppercase">จำนวนพนักงานที่ลงเวลาเข้า</p>
        </div>

        <!-- Stats 3 -->
        <div class="stat-card border-rose-500/5 hover:border-rose-500/30">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-rose-500/10 rounded-xl">
                    <i data-lucide="log-out" class="w-6 h-6 text-rose-400"></i>
                </div>
                <h3 class="text-sm font-semibold uppercase tracking-widest text-white/40">ออกงานวันนี้</h3>
            </div>
            <p class="text-4xl font-black text-rose-400 leading-none"><?= number_format($stats['today_out'] ?? 0) ?></p>
            <p class="text-xs text-white/20 mt-2 uppercase">จำนวนพนักงานที่ลงเวลาออก</p>
        </div>
    </section>

    <section class="glass-card mt-12 overflow-hidden px-0 pb-0">
        <div class="px-8 pb-6 border-b border-white/5 flex flex-wrap justify-between items-center gap-4">
            <div>
                <h2 class="text-lg font-bold text-white/90 m-0">ประวัติการลงเวลาล่าสุด</h2>
                <p class="text-xs text-white/30 uppercase tracking-widest mt-1">ติดตามผลการทำงานแบบเรียลไทม์</p>
            </div>
            <div class="flex items-center gap-4 flex-wrap">
               <div class="relative group">
                   <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-white/20 group-focus-within:text-blue-400 transition-colors"></i>
                   <input type="text" id="atdSearch" placeholder="ค้นหาพนักงาน..." 
                          class="bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-2 text-xs font-bold text-white focus:outline-none focus:border-blue-500 transition-all w-48 focus:w-64">
               </div>
               <form action="<?= base_url('admin') ?>" method="get" class="flex gap-2">
                   <input type="date" name="date" value="<?= $filter_date ?>" 
                          onchange="this.form.submit()"
                          class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs font-bold text-blue-400 focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
               </form>
               <a href="<?= base_url('admin/exportExcel?date=' . $filter_date) ?>" 
                  class="bg-blue-600 hover:bg-blue-500 px-4 py-3 rounded-xl text-xs font-bold transition-all shadow-lg shadow-blue-900/40 flex items-center gap-2 group">
                   <i data-lucide="file-spreadsheet" class="w-4 h-4 group-hover:scale-110"></i> ส่งออก Excel
               </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/[0.02]">
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30">พนักงาน</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30">ประเภท</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30">วันที่ / เวลา</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30">พิกัด</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-white/30 text-right">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.03]">
                    <?php if(empty($all_attendance)): ?>
                        <tr class="empty-row"><td colspan="5" class="p-20 text-center text-white/20 italic">ยังไม่มีข้อมูลการลงเวลาในระบบ</td></tr>
                    <?php else: ?>
                        <?php foreach($all_attendance as $atd): ?>
                        <tr class="hover:bg-white/5 transition-all group">
                            <td class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 border border-indigo-500/10 flex items-center justify-center text-indigo-400 font-black">
                                        <?= strtoupper(substr($atd['u_fullname'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white/90 leading-none mb-1"><?= $atd['u_fullname'] ?></p>
                                        <p class="text-[10px] text-white/40 font-mono tracking-tighter"><?= $atd['u_username'] ?> (IP: <?= $atd['atd_ip'] ?>)</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="flex flex-col gap-1.5">
                                    <span class="text-[10px] font-black uppercase px-3 py-1.5 rounded-full w-fit <?= $atd['atd_type'] == 'check_in' ? 'bg-sky-500/10 text-sky-400' : 'bg-slate-500/10 text-slate-400' ?>">
                                        <?= $atd['atd_type'] == 'check_in' ? 'เข้างาน' : 'ออกงาน' ?>
                                    </span>
                                    <?php 
                                        $atd_time = date('H:i', strtotime($atd['atd_timestamp']));
                                        if($atd['atd_type'] == 'check_in'):
                                            $is_late = $atd_time > $work_start_time;
                                    ?>
                                        <span class="text-[10px] font-bold px-3 py-1 rounded-full w-fit <?= $is_late ? 'bg-amber-500/10 text-amber-500' : 'bg-emerald-500/10 text-emerald-400' ?>">
                                            <i data-lucide="<?= $is_late ? 'clock-alert' : 'check-check' ?>" class="w-3 h-3 inline mr-1"></i>
                                            <?= $is_late ? 'สาย' : 'ตรงเวลา' ?>
                                        </span>
                                    <?php else: 
                                            $is_early = $atd_time < $work_end_time;
                                    ?>
                                        <span class="text-[10px] font-bold px-3 py-1 rounded-full w-fit <?= $is_early ? 'bg-rose-500/10 text-rose-400' : 'bg-emerald-500/10 text-emerald-400' ?>">
                                            <i data-lucide="<?= $is_early ? 'log-out' : 'check-check' ?>" class="w-3 h-3 inline mr-1"></i>
                                            <?= $is_early ? 'กลับก่อน' : 'ปกติ' ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="p-6">
                                <p class="text-sm font-bold text-white/80"><?= date('H:i:s', strtotime($atd['atd_timestamp'])) ?></p>
                                <p class="text-[10px] text-white/30 mt-0.5"><?= thai_date($atd['atd_timestamp'], true) ?></p>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-blue-400"></i>
                                    <p class="text-[11px] font-mono text-white/40 truncate max-w-[120px]" title="<?= $atd['atd_location'] ?>">
                                        <?= $atd['atd_location'] ?>
                                    </p>
                                </div>
                            </td>
                            <td class="p-6 text-right">
                                <?php if($atd['atd_location']): ?>
                                    <a href="https://www.google.com/maps/dir/?api=1&origin=<?= $office_location ?>&destination=<?= $atd['atd_location'] ?>&travelmode=walking" 
                                       target="_blank"
                                       class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/5 hover:bg-blue-600 hover:text-white transition-all text-white/40"
                                       title="ดูบนแผนที่และระยะห่าง">
                                        <i data-lucide="map" class="w-4 h-4"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-6 bg-white/[0.02] text-center border-t border-white/5">
            <p class="text-[10px] uppercase font-black text-white/20 tracking-[.3em]">สิ้นสุดรายการ</p>
        </div>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const atdSearch = document.getElementById('atdSearch');
    const tableRows = document.querySelectorAll('tbody tr:not(.empty-row)');

    atdSearch.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if(text.includes(term)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
<?= $this->endSection() ?>
