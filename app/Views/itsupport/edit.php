<?= $this->extend('itsupport/layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="mb-6 sm:mb-10 px-1 sm:px-0">
    <a href="<?= base_url('itsupport') ?>" class="text-xs font-bold text-slate-400 hover:text-cyan-400 flex items-center gap-1 mb-2 sm:mb-3 transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> ย้อนกลับไปประวัติงาน
    </a>
    <h2 class="text-xl sm:text-3xl font-extrabold text-white tracking-tight tech-glow">แก้ไขประวัติงานบริการ <?= $log['its_ticket_code'] ?></h2>
    <p class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">แก้ไขรายละเอียดบันทึกผลงานการซ่อมบำรุงหรือการให้บริการไอที</p>
</div>

<!-- Form Container -->
<div class="glass-card p-4 sm:p-8 rounded-2xl sm:rounded-[2rem] max-w-4xl w-full mx-auto">
    <form method="POST" action="<?= base_url('itsupport/update/' . $log['its_id']) ?>" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>

        <!-- Basic Header Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Ticket Code -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-2">รหัสใบงานบริการ (อัตโนมัติ)</label>
                <div class="px-4 py-3 bg-slate-900 border border-slate-800 rounded-2xl text-cyan-400 font-mono font-bold">
                    <?= $log['its_ticket_code'] ?>
                </div>
            </div>

            <!-- Recorder -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-2">เจ้าหน้าที่ผู้ลงประวัติ</label>
                <div class="px-4 py-3 bg-slate-900 border border-slate-800 rounded-2xl text-slate-300 font-bold">
                    👤 <?= esc($log['its_recorded_by']) ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Date/Time Picker -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-2">วันเวลาที่ปฏิบัติงานจริง <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500"><i data-lucide="calendar" class="w-4 h-4"></i></span>
                    <input type="text" name="its_date" required value="<?= $log['its_date'] ?>" placeholder="เลือกวันเวลา..." class="datetimepicker-be w-full pl-10 pr-4 py-3 bg-slate-950/60 border border-slate-900 focus:border-cyan-500 rounded-2xl text-sm text-white outline-none transition-colors">
                </div>
                <?php if(session('errors.its_date')): ?>
                    <p class="text-rose-500 text-xs pl-2 font-bold"><?= session('errors.its_date') ?></p>
                <?php endif; ?>
            </div>

            <!-- Category -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-2">หมวดหมู่ประเภทงานบริการ <span class="text-rose-500">*</span></label>
                <select name="its_category" required class="w-full px-4 py-3 bg-slate-950/60 border border-slate-900 focus:border-cyan-500 rounded-2xl text-sm text-slate-300 outline-none transition-colors">
                    <option value="" disabled>-- เลือกประเภทงานบริการ --</option>
                    <?php
                        $categories = [
                            "🛠️ IT Support & Service", "🎤 งานโสตทัศนศึกษา", "📸 ผลิตสื่อและประชาสัมพันธ์", 
                            "📊 งานสารสนเทศโรงเรียน", "🤝 สนับสนุนงานฝ่าย/อาคาร", "👥 งานประชุม", 
                            "📚 การอบรม/พัฒนาตนเอง", "🏛️ งานอื่นๆ ตามคำสั่ง"
                        ];
                    ?>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat ?>" <?= (old('its_category') ?: $log['its_category']) == $cat ? 'selected' : '' ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if(session('errors.its_category')): ?>
                    <p class="text-rose-500 text-xs pl-2 font-bold"><?= session('errors.its_category') ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Location -->
        <div class="space-y-1.5">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-2">สถานที่ปฏิบัติงาน</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500"><i data-lucide="map-pin" class="w-4 h-4"></i></span>
                <input type="text" list="loc-datalist" name="its_location" value="<?= esc(old('its_location') ?: $log['its_location']) ?>" placeholder="ระบุตึก ห้อง หรือกองฝ่ายงาน (เว้นว่างได้)" class="w-full pl-10 pr-4 py-3 bg-slate-950/60 border border-slate-900 focus:border-cyan-500 rounded-2xl text-sm text-white placeholder-slate-500 outline-none transition-colors">
                <datalist id="loc-datalist">
                    <?php foreach($locations as $loc): ?>
                        <option value="<?= esc($loc) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>
        </div>

        <!-- Task Description -->
        <div class="space-y-1.5">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-2">รายละเอียดสิ่งที่ปฏิบัติงาน <span class="text-rose-500">*</span></label>
            <textarea name="its_task" required rows="5" placeholder="พิมพ์อธิบายปัญหา อาการชำรุด หรือกระบวนการซ่อมแซมแก้ไขให้ละเอียด..." class="w-full px-4 py-3 bg-slate-950/60 border border-slate-900 focus:border-cyan-500 rounded-2xl text-sm text-white placeholder-slate-500 outline-none transition-colors resize-none"><?= esc(old('its_task') ?: $log['its_task']) ?></textarea>
            <?php if(session('errors.its_task')): ?>
                <p class="text-rose-500 text-xs pl-2 font-bold"><?= session('errors.its_task') ?></p>
            <?php endif; ?>
        </div>

        <!-- Existing Images -->
        <?php 
            $images = !empty($log['its_images']) ? json_decode($log['its_images'], true) : [];
        ?>
        <?php if(!empty($images)): ?>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-2">รูปภาพประกอบเดิมในระบบ</label>
                <div class="grid grid-cols-3 sm:grid-cols-5 gap-4">
                    <?php foreach($images as $img): ?>
                        <div class="relative rounded-2xl overflow-hidden aspect-video border border-slate-800 bg-slate-900 shadow-lg">
                            <img src="<?= base_url('uploads/it_support/' . $img) ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Multi-Images Upload with Thumbnails Preview -->
        <div class="space-y-2">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-2 block">แนบภาพประกอบผลงานใหม่ (เลือกเพื่อลบและแทนที่รูปภาพชุดเดิมทั้งหมด)</label>
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-900 border-dashed rounded-3xl cursor-pointer bg-slate-950/20 hover:bg-slate-950/50 hover:border-slate-800 transition-all duration-300">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i data-lucide="cloud-upload" class="w-10 h-10 text-slate-500 mb-2"></i>
                        <p class="mb-2 text-sm text-slate-400"><span class="font-bold text-cyan-400">คลิกเพื่ออัปโหลดรูปชุดใหม่</span> แทนที่ชุดเดิม</p>
                        <p class="text-xs text-slate-500">รองรับนามสกุล JPG, JPEG, PNG (ไม่เกิน 10 รูป)</p>
                    </div>
                    <input id="dropzone-file" type="file" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(event)">
                </label>
            </div>
            
            <!-- Thumbnail list container -->
            <div id="image-preview-container" class="grid grid-cols-3 sm:grid-cols-5 gap-4 pt-4"></div>
        </div>

        <!-- Form Submit Button Group -->
        <div class="flex justify-end gap-3 pt-6 border-t border-slate-900">
            <a href="<?= base_url('itsupport/logs') ?>" class="px-6 py-3 rounded-2xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white font-bold text-sm transition-colors">
                ยกเลิก
            </a>
            <button type="submit" class="px-8 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white font-bold text-sm shadow-lg shadow-cyan-500/20 hover:scale-105 transition-all duration-200">
                บันทึกการแก้ไขประวัติ
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function previewImages(event) {
        const previewContainer = document.getElementById('image-preview-container');
        previewContainer.innerHTML = ''; // clear old preview
        
        const files = event.target.files;
        if (files) {
            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative rounded-2xl overflow-hidden aspect-video border border-slate-800 bg-slate-900 shadow-lg animate-[fadeIn_0.3s_ease]';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-slate-950/60 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-[9px] font-black text-white bg-slate-900/80 px-2 py-1 rounded-md uppercase tracking-wider">${(file.size / 1024).toFixed(0)} KB</span>
                        </div>
                    `;
                    previewContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>
<?= $this->endSection() ?>
