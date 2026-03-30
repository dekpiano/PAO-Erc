<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
    <div class="mb-12 flex justify-between items-end" data-aos="fade-up">
        <div>
            <h2 class="text-3xl font-black text-slate-900 mb-2">จัดการคิวจองทุนการศึกษา</h2>
            <p class="text-slate-400 font-medium tracking-wide flex items-center gap-2 uppercase text-xs">
                <i data-lucide="calendar-check" class="w-4 h-4 text-violet-600"></i> Manage Scholarship Appointment Queues
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-50 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">ทุนที่เปิดรับจอง</p>
                <h3 class="text-3xl font-black text-slate-900"><?= count($scholarships) ?></h3>
            </div>
            <i data-lucide="graduation-cap" class="absolute -right-4 -bottom-4 w-24 h-24 text-slate-50 group-hover:text-violet-50 group-hover:scale-110 transition-all duration-500"></i>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-50 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">คิวจองทั้งหมด</p>
                <h3 class="text-3xl font-black text-slate-900"><?= array_sum(array_column($scholarships, 'total_bookings')) ?></h3>
            </div>
            <i data-lucide="users" class="absolute -right-4 -bottom-4 w-24 h-24 text-slate-50 group-hover:text-emerald-50 group-hover:scale-110 transition-all duration-500"></i>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-50 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">รอตรวจสอบ (Pending)</p>
                <h3 class="text-3xl font-black text-rose-600"><?= array_sum(array_column($scholarships, 'pending_bookings')) ?></h3>
            </div>
            <i data-lucide="clock" class="absolute -right-4 -bottom-4 w-24 h-24 text-slate-50 group-hover:text-rose-50 group-hover:scale-110 transition-all duration-500"></i>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-wider text-slate-400 w-16">#</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">ทุนการศึกษา</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400 text-center">สถิติการจอง</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">สถานะ</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400 text-right">การจัดการคิว</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($scholarships)): ?>
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium">
                                ไม่พบทุนการศึกษาที่มีสถานะ "เปิดรับสมัคร" ในขณะนี้
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($scholarships as $item): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-6 font-black text-slate-400 text-sm italic">
                                    <?= str_pad($i++, 2, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <?php if($item['sch_cover']): ?>
                                            <div class="w-12 h-12 rounded-xl border border-slate-100 overflow-hidden flex-shrink-0">
                                                <img src="<?= base_url('uploads/scholarships/covers/' . $item['sch_cover']) ?>" class="w-full h-full object-cover">
                                            </div>
                                        <?php else: ?>
                                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 flex-shrink-0">
                                                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h4 class="text-sm font-black text-slate-800 leading-snug line-clamp-1"><?= $item['sch_title'] ?></h4>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">ID: #<?= $item['sch_id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-lg font-black text-slate-700 leading-none"><?= $item['total_bookings'] ?></span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-1">จองแล้วทั้งหมด</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <?php if($item['pending_bookings'] > 0): ?>
                                        <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-wider">
                                            <?= $item['pending_bookings'] ?> รอตรวจสอบ
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-wider">
                                            ครบถ้วนแล้ว
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button onclick="openGradeSettings(<?= $item['sch_id'] ?>, '<?= addslashes($item['sch_title']) ?>', '<?= $item['sch_allowed_grades'] ?>')" 
                                            class="p-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-violet-100 hover:text-violet-600 transition-all shadow-sm" title="ตั้งค่าระดับชั้นที่เปิดรับ">
                                            <i data-lucide="settings-2" class="w-4 h-4"></i>
                                        </button>
                                        <a href="<?= base_url('staff/scholarship/' . $item['sch_id'] . '/slots') ?>" class="px-5 py-2.5 bg-violet-600 text-white rounded-xl text-xs font-bold hover:bg-violet-700 transition-all flex items-center gap-2 shadow-lg shadow-violet-100">
                                            <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                                            จัดการ Slot เวลา
                                        </a>
                                        <a href="<?= base_url('staff/scholarship/' . $item['sch_id'] . '/bookings') ?>" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition-all flex items-center gap-2 shadow-lg shadow-emerald-100">
                                            <i data-lucide="users" class="w-4 h-4"></i>
                                            รายชื่อผู้จอง
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

    <!-- 🛠️ Modal ตั้งค่าระดับชั้น -->
    <div id="gradeSettingsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeGradeSettings()"></div>
        <div class="bg-white rounded-[2.5rem] w-full max-w-md relative z-10 shadow-2xl overflow-hidden animate-[zoomIn_0.3s_ease-out]">
            <div class="bg-violet-600 p-8 text-white">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i data-lucide="settings-2" class="w-6 h-6"></i>
                    </div>
                    <button onclick="closeGradeSettings()" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <h3 class="text-xl font-black mb-1">ตั้งค่าระดับชั้นที่เปิดรับ</h3>
                <p id="modalSchTitle" class="text-violet-100 text-xs font-medium line-clamp-1 opacity-80 uppercase tracking-wider"></p>
            </div>
            
            <form id="gradeSettingsForm" class="p-8 space-y-6">
                <input type="hidden" name="sch_id" id="modalSchId">
                <div class="space-y-3">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">กรุณาเลือกที่ต้องการเปิดรับ:</p>
                    
                    <label class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border-2 border-slate-50 hover:border-violet-100 transition-all cursor-pointer group">
                        <input type="checkbox" name="grades[]" value="มัธยมศึกษาตอนต้น" class="w-5 h-5 rounded-lg border-slate-300 text-violet-600 focus:ring-violet-500">
                        <span class="text-sm font-bold text-slate-700 group-hover:text-violet-700 transition-colors">ระดับ ม.ต้น</span>
                    </label>

                    <label class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border-2 border-slate-50 hover:border-violet-100 transition-all cursor-pointer group">
                        <input type="checkbox" name="grades[]" value="มัธยมศึกษาตอนปลาย / ปวช." class="w-5 h-5 rounded-lg border-slate-300 text-violet-600 focus:ring-violet-500">
                        <span class="text-sm font-bold text-slate-700 group-hover:text-violet-700 transition-colors">ระดับ ม.ปลาย / ปวช.</span>
                    </label>

                    <label class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border-2 border-slate-50 hover:border-violet-100 transition-all cursor-pointer group">
                        <input type="checkbox" name="grades[]" value="ระดับปริญญาตรี / ปวส." class="w-5 h-5 rounded-lg border-slate-300 text-violet-600 focus:ring-violet-500">
                        <span class="text-sm font-bold text-slate-700 group-hover:text-violet-700 transition-colors">ระดับปริญญาตรี / ปวส.</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeGradeSettings()" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">ยกเลิก</button>
                    <button type="submit" class="flex-1 py-4 bg-violet-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-violet-700 transition-all shadow-lg shadow-violet-100">บันทึกการตั้งค่า</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openGradeSettings(id, title, allowedGrades) {
            document.getElementById('modalSchId').value = id;
            document.getElementById('modalSchTitle').innerText = title;
            
            // Clear checked
            document.querySelectorAll('#gradeSettingsForm input[name="grades[]"]').forEach(cb => {
                cb.checked = allowedGrades.split(',').includes(cb.value);
            });

            document.getElementById('gradeSettingsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            lucide.createIcons();
        }

        function closeGradeSettings() {
            document.getElementById('gradeSettingsModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.getElementById('gradeSettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="flex items-center gap-2 justify-center"><i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> บันทึก...</span>';
            lucide.createIcons();

            fetch('<?= base_url('staff/scholarship/update-grades') ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-[2rem]' }
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด!',
                        text: data.message,
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                }
            })
            .catch(() => {
                Swal.fire({ icon: 'error', title: 'ผิดพลาด!', text: 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', customClass: { popup: 'rounded-[2rem]' } });
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerText = 'บันทึกการตั้งค่า';
            });
        });
    </script>
<?= $this->endSection() ?>
