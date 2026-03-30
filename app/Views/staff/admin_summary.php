<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>

    <!-- Page Header -->
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 mb-2 mt-0">สรุปเวลาปฏิบัติงาน</h2>
            <p class="text-slate-400 font-bold tracking-widest flex items-center gap-2 uppercase text-[10px]">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-blue-600"></i> Attendance Analytics & Logs
            </p>
        </div>
        <div class="flex items-center gap-4 flex-wrap">
           <form action="<?= base_url('staff/admin-summary') ?>" method="get" class="flex items-center gap-2 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm">
               <span class="text-[10px] font-black uppercase text-slate-400 px-3">วันที่แสดงผล:</span>
               <input type="date" name="date" value="<?= $filter_date ?>" 
                      onchange="this.form.submit()"
                      class="bg-slate-50 border-0 rounded-xl px-4 py-2 text-sm font-bold text-blue-600 focus:ring-0 cursor-pointer">
           </form>
           <a href="<?= base_url('staff/exportExcel?date=' . $filter_date) ?>" 
              class="px-6 py-4 bg-emerald-600 text-white rounded-2xl font-bold flex items-center gap-2 hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 transform hover:-translate-y-1">
               <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
               ส่งออก Excel
           </a>
        </div>
    </div>

    <!-- Stats Section -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Stats 1: Total Records -->
        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-100/50 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="database" class="w-6 h-6"></i>
                </div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">รายการคัดกรอง</p>
                <h3 class="text-4xl font-black text-slate-900"><?= number_format($stats['total_filter'] ?? 0) ?></h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-wide">Records found for this date</p>
            </div>
            <i data-lucide="layers" class="absolute -right-6 -bottom-6 w-32 h-32 text-slate-50 opacity-50 group-hover:text-blue-50 group-hover:scale-110 transition-all duration-700 pointer-events-none"></i>
        </div>

        <!-- Stats 2: Check-ins -->
        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-100/50 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="log-in" class="w-6 h-6"></i>
                </div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">ลงเวลาเข้างาน</p>
                <h3 class="text-4xl font-black text-emerald-600"><?= number_format($stats['today_in'] ?? 0) ?></h3>
                <p class="text-[10px] text-emerald-600/50 font-bold mt-2 uppercase tracking-wide">Total Checked-in</p>
            </div>
            <i data-lucide="check-circle" class="absolute -right-6 -bottom-6 w-32 h-32 text-slate-50 opacity-50 group-hover:text-emerald-50 group-hover:scale-110 transition-all duration-700 pointer-events-none"></i>
        </div>

        <!-- Stats 3: Check-outs -->
        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-100/50 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="log-out" class="w-6 h-6"></i>
                </div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">ลงเวลาออกงาน</p>
                <h3 class="text-4xl font-black text-rose-600"><?= number_format($stats['today_out'] ?? 0) ?></h3>
                <p class="text-[10px] text-rose-600/50 font-bold mt-2 uppercase tracking-wide">Total Checked-out</p>
            </div>
            <i data-lucide="external-link" class="absolute -right-6 -bottom-6 w-32 h-32 text-slate-50 opacity-50 group-hover:text-rose-50 group-hover:scale-110 transition-all duration-700 pointer-events-none"></i>
        </div>
    </section>

    <!-- Main Table Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-2xl shadow-slate-200/50 overflow-hidden mb-12" data-aos="fade-up">
        <div class="px-10 py-8 border-b border-slate-100 flex flex-wrap justify-between items-center gap-6 bg-slate-50/50">
            <div>
                <h3 class="text-xl font-black text-slate-900 leading-none mb-2">ประวัติการลงเวลา</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Real-time attendance tracking logs</p>
            </div>
            <div class="relative group w-full md:w-80">
                <i data-lucide="search" class="absolute left-6 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-300 group-focus-within:text-blue-600 transition-colors"></i>
                <input type="text" id="atdSearch" placeholder="ค้นหาชื่อ หรือรหัสพนักงาน..." 
                       class="w-full bg-white border border-slate-200 rounded-2xl pl-14 pr-6 py-4 text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white">
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">พนักงาน</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">ประเภท / สถานะ</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">วันที่ / เวลา</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">พิกัดสถานที่</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50 text-right">เพิ่มเติม</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(empty($all_attendance)): ?>
                        <tr class="empty-row">
                            <td colspan="5" class="px-10 py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200 mb-6">
                                        <i data-lucide="database-zap" class="w-10 h-10"></i>
                                    </div>
                                    <p class="text-slate-400 font-bold text-lg">ไม่พบข้อมูลการลงเวลาในวันที่เลือก</p>
                                    <p class="text-[10px] text-slate-300 uppercase tracking-widest mt-2">No attendance data found</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($all_attendance as $atd): ?>
                        <tr class="hover:bg-slate-50/50 transition-all group">
                            <td class="px-10 py-7">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-lg shadow-lg shadow-blue-100 flex-shrink-0">
                                        <?= strtoupper(substr($atd['u_fullname'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 leading-none mb-1.5"><?= $atd['u_fullname'] ?></p>
                                        <div class="flex items-center gap-2">
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest leading-none">ID: #<?= $atd['u_username'] ?></p>
                                            <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                            <p class="text-[9px] text-blue-500 font-black uppercase tracking-widest leading-none"><?= $atd['u_position'] ?: 'บุคลากร' ?></p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7">
                                <div class="flex flex-col gap-2">
                                    <?php if($atd['atd_type'] == 'check_in'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-wider w-fit">
                                            <i data-lucide="log-in" class="w-3.5 h-3.5"></i> เข้างาน
                                        </span>
                                        <?php 
                                            $atd_time = date('H:i', strtotime($atd['atd_timestamp']));
                                            $is_late = $atd_time > $work_start_time;
                                        ?>
                                        <?php if($is_late): ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg text-[10px] font-black uppercase tracking-wider w-fit">
                                                <i data-lucide="clock-alert" class="w-3.5 h-3.5"></i> มาสาย
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-wider w-fit hover:bg-blue-100 transition-colors">
                                                <i data-lucide="check-check" class="w-3.5 h-3.5"></i> ตรงเวลา
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-black uppercase tracking-wider w-fit">
                                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i> ออกงาน
                                        </span>
                                        <?php 
                                            $atd_time = date('H:i', strtotime($atd['atd_timestamp']));
                                            $is_early = $atd_time < $work_end_time;
                                        ?>
                                        <?php if($is_early): ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-black uppercase tracking-wider w-fit">
                                                <i data-lucide="door-closed" class="w-3.5 h-3.5"></i> กลับก่อน
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-wider w-fit">
                                                <i data-lucide="check-check" class="w-3.5 h-3.5"></i> ปกติ
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-10 py-7">
                                <div class="flex flex-col">
                                    <p class="text-base font-black text-slate-900 leading-none mb-1.5"><?= date('H:i:s', strtotime($atd['atd_timestamp'])) ?> น.</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest"><?= thai_date($atd['atd_timestamp'], true) ?></p>
                                </div>
                            </td>
                            <td class="px-10 py-7">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 flex-shrink-0">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    </div>
                                    <div class="max-w-[150px]">
                                        <a href="https://www.google.com/maps?q=<?= $atd['atd_location'] ?>" target="_blank" 
                                           class="text-[10px] font-black text-blue-600 hover:text-blue-700 transition-colors flex items-center gap-1 uppercase tracking-widest" title="<?= $atd['atd_location'] ?>">
                                            <i data-lucide="map-pin" class="w-3 h-3"></i> คลิกดูแผนที่
                                        </a>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">IP: <?= $atd['atd_ip'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7 text-right">
                                <?php if($atd['atd_location']): ?>
                                    <a href="https://www.google.com/maps/dir/?api=1&origin=<?= $office_location ?>&destination=<?= $atd['atd_location'] ?>&travelmode=walking" 
                                       target="_blank"
                                       class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-blue-600 hover:text-white transition-all hover:shadow-lg hover:shadow-blue-200"
                                       title="ดูบนแผนที่">
                                        <i data-lucide="map" class="w-5 h-5"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="px-10 py-6 bg-slate-50/50 text-center border-t border-slate-100">
            <p class="text-[10px] uppercase font-black text-slate-300 tracking-[0.4em]">สิ้นสุดรายการข้อมูล</p>
        </div>
    </div>

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
