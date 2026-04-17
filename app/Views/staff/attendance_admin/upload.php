<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="<?= base_url('staff/attendance-admin') ?>" class="inline-flex items-center gap-2 text-slate-400 hover:text-blue-600 font-bold text-sm mb-4 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            กลับหน้าสรุปการลงเวลา
        </a>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">อัปโหลดไฟล์ Excel</h2>
        <p class="text-slate-500 mt-1 font-medium">เลือกไฟล์ Excel ต้นฉบับเพื่อนำเข้าข้อมูลการมาทำงาน</p>
    </div>

    <div class="glass-card rounded-[2.5rem] border border-slate-200 p-8 sm:p-12">
        <form action="<?= base_url('staff/attendance-admin/process') ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
            <?= csrf_field() ?>

            <div class="space-y-4 text-center">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-left px-1">ไฟล์ Excel (.xlsx)</label>
                <div id="drop-zone" class="relative group border-2 border-dashed border-slate-200 rounded-[2rem] p-12 transition-all hover:border-blue-400 hover:bg-blue-50/30">
                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls" required
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    
                    <div class="flex flex-col items-center gap-6">
                        <div class="w-20 h-20 bg-blue-600/10 rounded-[2rem] flex items-center justify-center text-blue-600">
                            <i data-lucide="file-spreadsheet" class="w-10 h-10"></i>
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-black text-xl text-slate-900">คลิกหรือลากไฟล์มาวางที่นี่</h3>
                            <p class="text-slate-400 text-sm font-medium" id="file-name">รองรับเฉพาะไฟล์ .xlsx หรือ .xls เท่านั้น</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 rounded-3xl p-6 border border-amber-100/50">
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600 shrink-0">
                        <i data-lucide="info" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-amber-900 text-sm">ข้อแนะนำการเตรียมไฟล์</h4>
                        <ul class="mt-2 text-xs text-amber-800/80 space-y-1 font-medium list-disc list-inside">
                            <li>แถวแรกควรเป็นหัวข้อตาราง (Header) ระบบจะเริ่มอ่านจากแถวที่ 2 เป็นต้นไป</li>
                            <li>คอลัมน์ที่ 2 (B) ต้องเป็น <span class="font-bold underline">ชื่อ-นามสกุล</span> ที่ตรงกับระบบ</li>
                            <li>คอลัมน์ที่ 4 (D) ต้องเป็น <span class="font-bold underline">สถานะ</span> (มา, ขาด, ลา, สาย, ไปราชการ)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-5 rounded-[1.5rem] font-black text-lg transition-all shadow-xl shadow-blue-200 flex items-center justify-center gap-3">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
                ประมวลผลและนำเข้าข้อมูล
            </button>
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('excel_file');
    const fileNameDisplay = document.getElementById('file-name');
    const dropZone = document.getElementById('drop-zone');

    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            fileNameDisplay.textContent = e.target.files[0].name;
            fileNameDisplay.classList.remove('text-slate-400');
            fileNameDisplay.classList.add('text-blue-600', 'font-black');
            dropZone.classList.add('border-blue-600', 'bg-blue-50/50');
        }
    });

    // Drag and Drop Visuals
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.add('border-blue-400', 'bg-blue-50'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            if (fileInput.files.length === 0) {
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            }
        }, false);
    });
</script>
<?= $this->endSection() ?>
