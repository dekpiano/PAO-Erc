<?= $this->extend('itsupport/layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span class="truncate">ไทม์ไลน์งานบริการ</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">ประวัติการบำรุงรักษาและแก้ไขปัญหาทางระบบ</p>
    </div>
    
    <?php if ($can_manage): ?>
        <div class="flex flex-wrap gap-3 w-full md:w-auto">
            <a href="<?= base_url('itsupport/export?' . http_build_query($_GET)) ?>" class="w-full md:w-auto justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-slate-900 font-bold text-xs sm:text-sm hover:bg-slate-50 transition-colors flex items-center gap-2 shadow-sm">
                <i data-lucide="file-spreadsheet" class="w-4 h-4 text-emerald-600"></i> ส่งออกรายงาน Excel
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Search & Filter Bar (Mobile Collapsible) -->
<div class="glass-card p-4 sm:p-6 rounded-3xl mb-6 max-w-2xl mx-auto bg-white">
    <!-- Toggle button for mobile only -->
    <button type="button" onclick="toggleMobileFilters()" class="w-full sm:hidden py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-600 hover:text-slate-800 flex items-center justify-center gap-2 transition-all">
        <i data-lucide="filter" class="w-4 h-4 text-blue-600"></i> 🔍 ค้นหา & กรองข้อมูลประวัติ
    </button>
    
    <!-- Collapsible Container (hidden on mobile, shown by default on tablet/desktop) -->
    <div id="filter-container" class="hidden sm:block mt-3 sm:mt-0 animate-[fadeIn_0.3s_ease]">
        <form method="GET" action="<?= base_url('itsupport') ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            <!-- Search -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i data-lucide="search" class="w-4 h-4"></i></span>
                <input type="text" name="search" value="<?= esc($search) ?>" placeholder="ค้นหารายละเอียด, ผู้บันทึก..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 focus:border-blue-500 rounded-2xl text-base md:text-xs text-slate-800 outline-none transition-colors">
            </div>

            <!-- Category -->
            <select name="category" class="w-full px-4 py-3 bg-white border border-slate-200 focus:border-blue-500 rounded-2xl text-base md:text-xs text-slate-700 outline-none transition-colors">
                <option value="">-- กรองประเภทงานทั้งหมด --</option>
                <?php
                    $categories = [
                        "🛠️ IT Support & Service", "🎤 งานโสตทัศนศึกษา", "📸 ผลิตสื่อและประชาสัมพันธ์", 
                        "📊 งานสารสนเทศโรงเรียน", "🤝 สนับสนุนงานฝ่าย/อาคาร", "👥 งานประชุม", 
                        "📚 การอบรม/พัฒนาตนเอง", "🏛️ งานอื่นๆ ตามคำสั่ง"
                    ];
                ?>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat ?>" <?= $category_active == $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Location -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i data-lucide="map-pin" class="w-4 h-4"></i></span>
                <input type="text" list="loc-datalist" name="location" value="<?= esc($location_active) ?>" placeholder="📍 สถานที่..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 focus:border-blue-500 rounded-2xl text-base md:text-xs text-slate-800 outline-none transition-colors">
                <datalist id="loc-datalist">
                    <?php foreach($locations as $loc): ?>
                        <option value="<?= esc($loc) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>

            <!-- Filter Submit Button -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-3 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-100 hover:border-blue-600 text-xs font-bold rounded-2xl transition-all flex items-center justify-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i> กรองฟีด
                </button>
                <?php if(!empty($search) || !empty($category_active) || !empty($location_active)): ?>
                    <a href="<?= base_url('itsupport') ?>" class="p-3 bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 rounded-2xl transition-all flex items-center justify-center" title="ล้างค่าตัวกรอง">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Quick Post Composer Card (สไตล์ Facebook โพสต์เฉพาะ IT Admin เท่านั้น) -->
<?php if ($can_manage): ?>
<div id="composer-card" class="glass-card p-4 sm:p-6 rounded-2xl sm:rounded-[2rem] mb-6 border-l-4 border-blue-500 max-w-2xl mx-auto shadow-xl transition-all duration-300 bg-white">
    <div class="flex gap-3 sm:gap-4 items-start">
        <!-- User Avatar -->
        <div class="w-10 h-10 sm:w-11 sm:h-11 bg-slate-100 rounded-full border border-slate-200 overflow-hidden shadow-sm flex-shrink-0 flex items-center justify-center">
            <?php if(session()->get('u_photo')): ?>
                <img src="<?= base_url('uploads/personnel/' . session()->get('u_photo')) ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </div>
            <?php endif; ?>
        </div>

        <!-- Composer Form -->
        <div class="flex-1">
            <form id="composer-form" method="POST" action="<?= base_url('itsupport/store') ?>" enctype="multipart/form-data" class="space-y-3.5">
                <?= csrf_field() ?>
                
                <!-- Textarea -->
                <textarea name="its_task" required rows="3" placeholder="คุณกำลังปฏิบัติงานบริการหรือซ่อมบำรุงส่วนไหนอยู่ครับ <?= explode(' ', session()->get('u_fullname') ?? '')[0] ?>?" class="w-full bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-2xl p-3.5 text-base md:text-xs text-slate-800 placeholder-slate-400 outline-none transition-all resize-none"></textarea>
                
                <!-- Expanded fields -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Date Input -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i data-lucide="calendar" class="w-3.5 h-3.5"></i></span>
                        <input type="text" name="its_date" required value="<?= date('Y-m-d H:i:s') ?>" class="datetimepicker-be w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-800 outline-none transition-all">
                    </div>
                    
                    <!-- Category Select -->
                    <select name="its_category" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-700 outline-none transition-all">
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat ?>"><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Location Input with datalist -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i></span>
                        <input type="text" list="loc-datalist-quick" name="its_location" placeholder="📍 ระบุสถานที่..." class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-800 outline-none transition-all">
                        <datalist id="loc-datalist-quick">
                            <?php foreach($locations as $loc): ?>
                                <option value="<?= esc($loc) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                </div>

                <!-- Footer Action Area -->
                <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                    <label class="flex items-center gap-2 text-emerald-650 hover:text-emerald-700 text-xs font-bold cursor-pointer bg-emerald-50 hover:bg-emerald-100/50 px-4 py-2.5 rounded-xl border border-emerald-100 transition-all">
                        <i data-lucide="image" class="w-4 h-4 text-emerald-600"></i> แนบรูปถ่าย
                        <input type="file" id="composer-file-input" name="images[]" multiple accept="image/*" class="hidden" onchange="previewQuickImages(event)">
                    </label>

                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/15 transition-all">
                        🚀 โพสต์บันทึกงาน
                    </button>
                </div>

                <!-- Preview container -->
                <div id="quick-image-preview" class="grid grid-cols-3 gap-3 pt-2"></div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Timeline Feed -->
<div class="relative pl-0 md:pl-6 border-l-0 md:border-l-2 border-slate-200 space-y-4 sm:space-y-6 md:space-y-10 max-w-2xl mx-auto py-2 sm:py-4 w-full overflow-x-hidden">
    <?php if(empty($logs)): ?>
        <div class="glass-card p-12 text-center text-slate-400 rounded-3xl max-w-2xl mx-auto bg-white">
            ยังไม่มีบันทึกประวัติผลงาน IT Support ตามตัวกรองที่เลือก
        </div>
    <?php else: ?>
        <?php 
            $prevDate = '';
        ?>
        <?php foreach($logs as $log): ?>
            <?php 
                $dateKey = date('Y-m-d', strtotime($log['its_date']));
                $showDateHeader = $dateKey !== $prevDate;
                $prevDate = $dateKey;
                
                // สลักภาษาไทยสำหรับ Date Header
                $thaiDay = date('d', strtotime($log['its_date']));
                $thaiMonth = [
                    '01'=>'มกราคม','02'=>'กุมภาพันธ์','03'=>'มีนาคม','04'=>'เมษายน','05'=>'พฤษภาคม','06'=>'มิถุนายน',
                    '07'=>'กรกฎาคม','08'=>'สิงหาคม','09'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม'
                ][date('m', strtotime($log['its_date']))];
                $thaiYear = date('Y', strtotime($log['its_date'])) + 543;
                $thaiDateStr = "📅 วันที่ " . $thaiDay . " " . $thaiMonth . " " . $thaiYear;
            ?>
            
            <div class="relative">
                <!-- Timeline Dot Indicator (แสดงเฉพาะ Desktop) -->
                <span class="hidden md:block absolute -left-[31px] top-12 w-4 h-4 rounded-full bg-blue-600 border-4 border-white shadow-[0_0_12px_rgba(37,99,235,0.4)] z-10"></span>

                <!-- Date Header (Shown only on first occurrence of date) -->
                <?php if($showDateHeader): ?>
                    <div class="mb-3 px-3 md:px-0 text-[10px] sm:text-xs font-black text-blue-600 bg-blue-50 md:bg-transparent px-3 py-1.5 md:py-0 rounded-lg md:rounded-none border border-blue-100 md:border-none w-max tracking-wide shadow-sm md:shadow-none animate-[fadeIn_0.3s_ease]">
                        <?= $thaiDateStr ?>
                    </div>
                <?php endif; ?>

                <!-- Log Card Feed -->
                <div class="glass-card rounded-xl sm:rounded-2xl md:rounded-[2rem] hover:border-slate-350 transition-all duration-300 space-y-3 sm:space-y-4 shadow-xl relative overflow-hidden p-3 sm:p-4 md:p-6 animate-[fadeIn_0.4s_ease] w-full bg-white">
                    
                    <!-- Post Header -->
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Rounded User Avatar -->
                            <div class="w-10 h-10 sm:w-11 sm:h-11 bg-slate-100 rounded-full border border-slate-200 overflow-hidden shadow-sm flex-shrink-0 flex items-center justify-center">
                                <?php if(!empty($log['u_photo'])): ?>
                                    <img src="<?= base_url('uploads/personnel/' . $log['u_photo']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                                        <i data-lucide="user" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Post Meta -->
                            <div class="flex flex-col min-w-0">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="text-xs font-extrabold text-slate-800 leading-tight hover:text-blue-600 transition-colors truncate"><?= esc($log['its_recorded_by']) ?></span>
                                    <span class="px-2 py-0.5 rounded bg-blue-50 border border-blue-100 text-blue-600 text-[8px] font-black uppercase tracking-widest flex items-center gap-1 shadow-sm shrink-0">
                                        <i data-lucide="shield-check" class="w-2.5 h-2.5"></i> IT SUPPORT 🛡️
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 mt-0.5 text-[9px] text-slate-400 font-bold">
                                    <span><?= date('H:i', strtotime($log['its_date'])) ?> น.</span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1"><i data-lucide="globe" class="w-2.5 h-2.5 text-slate-400"></i> สาธารณะ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Dropdown Options Menu -->
                        <div class="relative dropdown-container shrink-0">
                            <button onclick="toggleCardDropdown(event, <?= $log['its_id'] ?>)" class="p-2 hover:bg-slate-50 border border-transparent hover:border-slate-100 rounded-full text-slate-400 hover:text-slate-700 transition-all outline-none" title="ตัวเลือกโพสต์">
                                <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                            </button>
                            <div id="dropdown-<?= $log['its_id'] ?>" class="hidden absolute right-0 mt-2 w-56 rounded-2xl glass-card border border-slate-100 p-2 shadow-2xl z-50 animate-[fadeIn_0.2s_ease-out] bg-white">
                                <a href="<?= base_url('itsupport/view/' . $log['its_id']) ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-slate-650 hover:text-slate-900 text-[11px] font-bold transition-all">
                                    <i data-lucide="eye" class="w-4 h-4 text-blue-600"></i> เปิดดูรายละเอียดใบงาน
                                </a>
                                
                                <?php if ($can_manage): ?>
                                    <a href="<?= base_url('itsupport/edit/' . $log['its_id']) ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-slate-650 hover:text-slate-900 text-[11px] font-bold transition-all">
                                        <i data-lucide="edit" class="w-4 h-4 text-amber-500"></i> แก้ไขบันทึกประวัติ
                                    </a>
                                    <a href="<?= base_url('itsupport/print/' . $log['its_id']) ?>" target="_blank" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-slate-655 hover:text-slate-900 text-[11px] font-bold transition-all">
                                        <i data-lucide="printer" class="w-4 h-4 text-indigo-500"></i> พิมพ์ใบงานส่งมอบ A4
                                    </a>
                                    <button onclick="confirmDelete(<?= $log['its_id'] ?>, '<?= $log['its_ticket_code'] ?>')" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl hover:bg-rose-50 text-rose-600 text-[11px] font-bold text-left transition-all">
                                        <i data-lucide="trash-2" class="w-4 h-4 text-rose-500"></i> ลบบันทึกประวัตินี้
                                    </button>
                                <?php endif; ?>
                                
                                <div class="border-t border-slate-100 my-1"></div>
                                <button onclick="copyTicketLink('<?= base_url('itsupport/view/' . $log['its_id']) ?>')" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-slate-650 hover:text-slate-900 text-[11px] font-bold text-left transition-all">
                                    <i data-lucide="copy" class="w-4 h-4 text-emerald-500"></i> คัดลอกลิงก์ตรงใบงาน
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Post Body Description -->
                    <div class="text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-wrap font-medium pl-1"><?= esc($log['its_task']) ?></div>

                    <!-- Metadata Badges -->
                    <div class="flex flex-wrap gap-1.5 sm:gap-2 pt-1 sm:pt-1.5 w-full overflow-hidden">
                        <span class="px-2 sm:px-3 py-0.5 sm:py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-[8px] sm:text-[10px] font-extrabold flex items-center gap-1 shadow-sm max-w-full truncate">
                            <i data-lucide="tag" class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-blue-600 shrink-0"></i> <span class="truncate"><?= $log['its_category'] ?></span>
                        </span>
                        <span class="px-2 sm:px-3 py-0.5 sm:py-1 rounded-full bg-amber-50 border border-amber-100 text-amber-600 text-[8px] sm:text-[10px] font-extrabold flex items-center gap-1 shadow-sm max-w-full truncate">
                            <i data-lucide="map-pin" class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-amber-600 shrink-0"></i> <span class="truncate"><?= esc($log['its_location']) ?></span>
                        </span>
                    </div>

                    <!-- Images Gallery -->
                    <?php 
                        $images = !empty($log['its_images']) ? json_decode($log['its_images'], true) : [];
                        $imgCount = count($images);
                    ?>
                    <?php if($imgCount > 0): ?>
                        <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 shadow-sm bg-slate-50">
                            <?php if($imgCount === 1): ?>
                                <!-- 1 Image -->
                                <div class="relative w-full aspect-video bg-slate-100 cursor-zoom-in group" onclick="zoomImage('<?= base_url('uploads/it_support/' . $images[0]) ?>')">
                                    <img src="<?= base_url('uploads/it_support/' . $images[0]) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <i data-lucide="zoom-in" class="w-6 h-6"></i>
                                    </div>
                                </div>
                            <?php elseif($imgCount === 2): ?>
                                <!-- 2 Images -->
                                <div class="grid grid-cols-2 gap-0.5">
                                    <?php foreach($images as $img): ?>
                                        <div class="relative aspect-square bg-slate-100 cursor-zoom-in group" onclick="zoomImage('<?= base_url('uploads/it_support/' . $img) ?>')">
                                            <img src="<?= base_url('uploads/it_support/' . $img) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
                                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                                <i data-lucide="zoom-in" class="w-5 h-5"></i>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif($imgCount === 3): ?>
                                <!-- 3 Images -->
                                <div class="grid grid-cols-3 gap-0.5 aspect-video">
                                    <div class="col-span-2 relative h-full bg-slate-100 cursor-zoom-in group" onclick="zoomImage('<?= base_url('uploads/it_support/' . $images[0]) ?>')">
                                        <img src="<?= base_url('uploads/it_support/' . $images[0]) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                            <i data-lucide="zoom-in" class="w-6 h-6"></i>
                                        </div>
                                    </div>
                                    <div class="grid grid-rows-2 gap-0.5 h-full">
                                        <?php for($k = 1; $k <= 2; $k++): ?>
                                            <div class="relative h-full bg-slate-100 cursor-zoom-in group" onclick="zoomImage('<?= base_url('uploads/it_support/' . $images[$k]) ?>')">
                                                <img src="<?= base_url('uploads/it_support/' . $images[$k]) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
                                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                                    <i data-lucide="zoom-in" class="w-4 h-4"></i>
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- 4+ Images -->
                                <div class="space-y-0.5">
                                    <div class="relative aspect-video bg-slate-100 cursor-zoom-in group" onclick="zoomImage('<?= base_url('uploads/it_support/' . $images[0]) ?>')">
                                        <img src="<?= base_url('uploads/it_support/' . $images[0]) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                            <i data-lucide="zoom-in" class="w-6 h-6"></i>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-0.5">
                                        <div class="relative aspect-square bg-slate-100 cursor-zoom-in group" onclick="zoomImage('<?= base_url('uploads/it_support/' . $images[1]) ?>')">
                                            <img src="<?= base_url('uploads/it_support/' . $images[1]) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
                                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                                <i data-lucide="zoom-in" class="w-4 h-4"></i>
                                            </div>
                                        </div>
                                        <div class="relative aspect-square bg-slate-100 cursor-zoom-in group" onclick="zoomImage('<?= base_url('uploads/it_support/' . $images[2]) ?>')">
                                            <img src="<?= base_url('uploads/it_support/' . $images[2]) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
                                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                                <i data-lucide="zoom-in" class="w-4 h-4"></i>
                                            </div>
                                        </div>
                                        <div class="relative aspect-square bg-slate-100 cursor-pointer group" onclick="zoomImage('<?= base_url('uploads/it_support/' . $images[3]) ?>')">
                                            <img src="<?= base_url('uploads/it_support/' . $images[3]) ?>" class="w-full h-full object-cover">
                                            <?php if($imgCount > 4): ?>
                                                <div class="absolute inset-0 bg-slate-900/65 flex flex-col items-center justify-center text-white">
                                                    <span class="text-base font-extrabold text-blue-400 tracking-wider">+<?= ($imgCount - 3) ?></span>
                                                    <span class="text-[8px] font-black uppercase text-slate-350 tracking-widest mt-0.5">ภาพเพิ่มเติม</span>
                                                </div>
                                            <?php else: ?>
                                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                                    <i data-lucide="zoom-in" class="w-4 h-4"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Social Interactive Bar -->
                    <div class="pt-2 border-t border-slate-100">
                        <div class="flex justify-between items-center text-xs">
                            <!-- Likes count -->
                            <div class="flex items-center gap-1.5 text-slate-500 font-bold text-[10px] pl-1 select-none">
                                <span class="w-4 h-4 bg-blue-600 text-white rounded-full flex items-center justify-center text-[8px] shadow-sm"><i data-lucide="thumbs-up" class="w-2.5 h-2.5 fill-white text-white"></i></span>
                                <span id="like-count-<?= $log['its_id'] ?>">1</span> คนถูกใจโพสต์นี้
                            </div>
                        </div>

                        <div class="border-t border-slate-100 mt-2.5 pt-1.5 flex gap-0.5 sm:gap-1 select-none">
                            <!-- Like Button -->
                            <button id="btn-like-<?= $log['its_id'] ?>" onclick="toggleLike(<?= $log['its_id'] ?>)" class="flex-1 py-2 hover:bg-slate-50 rounded-xl text-slate-500 hover:text-blue-600 font-extrabold transition-all flex items-center justify-center gap-1 sm:gap-2 text-[10px] sm:text-xs">
                                <i data-lucide="thumbs-up" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i> ถูกใจ
                            </button>

                            <!-- Comment Button -->
                            <button onclick="handleCommentClick(<?= $log['its_id'] ?>)" class="flex-1 py-2 hover:bg-slate-50 rounded-xl text-slate-500 hover:text-slate-800 font-extrabold transition-all flex items-center justify-center gap-1 sm:gap-2 text-[10px] sm:text-xs">
                                <i data-lucide="message-square" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i> ความคิดเห็น
                            </button>

                            <!-- Share Button -->
                            <button onclick="copyTicketLink('<?= base_url('itsupport/view/' . $log['its_id']) ?>')" class="flex-1 py-2 hover:bg-slate-50 rounded-xl text-slate-500 hover:text-emerald-600 font-extrabold transition-all flex items-center justify-center gap-1 sm:gap-2 text-[10px] sm:text-xs">
                                <i data-lucide="share-2" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i> แชร์ลิงก์
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <!-- Pagination links -->
        <div class="mt-8 flex justify-center pt-4">
            <?= $pager->links('default', 'itsupport_pager') ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Script for sweetalert2 image zoom and delete confirm -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initLikes();
    });

    function toggleMobileFilters() {
        const container = document.getElementById('filter-container');
        container.classList.toggle('hidden');
    }

    function zoomImage(url) {
        Swal.fire({
            imageUrl: url,
            imageAlt: 'Work Image',
            showConfirmButton: false,
            background: '#ffffff',
            color: '#1e293b',
            width: 'auto',
            padding: '10px',
            customClass: {
                popup: 'glass-card rounded-[2.5rem] max-w-4xl overflow-hidden'
            }
        });
    }

    function confirmDelete(id, ticketCode) {
        Swal.fire({
            title: 'ยืนยันการลบประวัติงาน?',
            text: `ต้องการลบประวัติ IT Support รหัส ${ticketCode} ใช่หรือไม่? การลบนี้จะไม่สามารถย้อนกลับได้!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', 
            cancelButtonColor: '#e2e8f0',
            confirmButtonText: 'ลบเลย!',
            cancelButtonText: 'ยกเลิก',
            background: '#ffffff',
            color: '#1e293b',
            customClass: {
                popup: 'glass-card rounded-[2rem]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?= base_url('itsupport/delete') ?>/${id}`;
            }
        });
    }

    // --- File Queue Manager for Quick Composer ---
    let composerFilesQueue = [];

    function previewQuickImages(event) {
        handleNewComposerFiles(event.target.files);
        // Reset file input value to allow selecting same file multiple times
        event.target.value = '';
    }

    function handleNewComposerFiles(files) {
        if (!files) return;
        const imageFiles = Array.from(files).filter(file => file.type.startsWith('image/'));
        
        if (imageFiles.length > 0) {
            // Append unique files based on name and size
            imageFiles.forEach(file => {
                const isDuplicate = composerFilesQueue.some(q => q.name === file.name && q.size === file.size);
                if (!isDuplicate) {
                    composerFilesQueue.push(file);
                }
            });
            renderComposerPreviews();
            syncComposerFileInput();
        }
    }

    function renderComposerPreviews() {
        const previewContainer = document.getElementById('quick-image-preview');
        previewContainer.innerHTML = '';
        
        composerFilesQueue.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative rounded-xl overflow-hidden aspect-video border border-slate-200 bg-slate-50 shadow-sm animate-[fadeIn_0.3s_ease] group';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-contain">
                    <!-- Delete Button (✕) -->
                    <button type="button" onclick="removeComposerFile(${index})" class="absolute top-1 right-1 w-5 h-5 bg-rose-600 hover:bg-rose-700 text-white rounded-full flex items-center justify-center text-[10px] font-black shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200" title="ลบรูปภาพนี้">
                        ✕
                    </button>
                `;
                previewContainer.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    }

    function removeComposerFile(index) {
        composerFilesQueue.splice(index, 1);
        renderComposerPreviews();
        syncComposerFileInput();
        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            background: '#ffffff',
            color: '#1e293b',
            customClass: {
                popup: 'glass-card rounded-2xl border border-rose-100'
            }
        });
        Toast.fire({
            icon: 'info',
            title: 'ลบรูปภาพที่เลือกออกแล้ว'
        });
    }

    function syncComposerFileInput() {
        // ไม่ต้องยัด file กลับเข้า input แล้ว — จะใช้ FormData ตอน submit แทน
        // เพียงแค่อัปเดต preview เท่านั้น
    }

    // --- ฟังก์ชันย่อขนาดภาพฝั่ง Client ด้วย Canvas ---
    function compressImageClientSide(file) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function(event) {
                const img = new Image();
                img.src = event.target.result;
                img.onload = function() {
                    const maxW = 1200;
                    const maxH = 1200;
                    let width = img.width;
                    let height = img.height;

                    if (width > maxW || height > maxH) {
                        if (width > height) {
                            height = Math.round((height * maxW) / width);
                            width = maxW;
                        } else {
                            width = Math.round((width * maxH) / height);
                            height = maxH;
                        }
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    
                    // วาดภาพย่อลงบน Canvas
                    ctx.drawImage(img, 0, 0, width, height);

                    // บีบอัดเป็น JPEG 85%
                    canvas.toBlob((blob) => {
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        resolve(compressedFile);
                    }, 'image/jpeg', 0.85);
                };
            };
        });
    }

    // --- Form Submit Interceptor ---
    document.addEventListener('DOMContentLoaded', function() {
        // ตรวจสอบความสำเร็จจาก sessionStorage
        const ajaxSuccessMsg = sessionStorage.getItem('its_ajax_success');
        if (ajaxSuccessMsg) {
            sessionStorage.removeItem('its_ajax_success');
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#ffffff',
                color: '#1e293b',
                customClass: {
                    popup: 'glass-card rounded-2xl border border-blue-100 shadow-lg shadow-blue-500/10'
                }
            });
            Toast.fire({
                icon: 'success',
                title: ajaxSuccessMsg
            });
        }

        const composerForm = document.getElementById('composer-form');
        if (composerForm) {
            composerForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // แสดงสถานะกำลังย่อรูปและโพสต์
                const submitBtn = composerForm.querySelector('button[type="submit"]');
                const originalBtnHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="flex items-center gap-1.5 sm:gap-2">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        กำลังย่อและส่งรูป...
                    </span>
                `;

                // ทำการย่อขนาดไฟล์ภาพทั้งหมดฝั่ง Client
                const compressPromises = composerFilesQueue.map(file => compressImageClientSide(file));

                Promise.all(compressPromises).then(compressedFiles => {
                    const formData = new FormData();

                    // เก็บฟิลด์ปกติจากฟอร์ม
                    const formElements = composerForm.elements;
                    for (let i = 0; i < formElements.length; i++) {
                        const el = formElements[i];
                        if (el.name && el.type !== 'file' && el.type !== 'submit') {
                            formData.append(el.name, el.value);
                        }
                    }

                    // แนบรูปภาพที่ย่อขนาดแล้ว
                    compressedFiles.forEach(file => {
                        formData.append('images[]', file);
                    });

                    // ส่งข้อมูลผ่าน fetch
                    fetch(composerForm.action, {
                        method: 'POST',
                        body: formData
                    }).then(async response => {
                        if (response.ok) {
                            sessionStorage.setItem('its_ajax_success', 'โพสต์บันทึกงานบริการเรียบร้อยแล้ว 🚀');
                            window.location.href = '<?= base_url('itsupport') ?>';
                        } else {
                            const errText = await response.text();
                            console.error('Server error response:', errText);

                            let errorDetail = 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง';
                            if (errText.includes('class="exception"')) {
                                const match = errText.match(/<span class="exception">([^<]+)<\/span>/);
                                if (match) errorDetail = 'เซิร์ฟเวอร์แจ้งข้อผิดพลาด: ' + match[1];
                            } else if (errText.startsWith('{') || errText.includes('{"status"')) {
                                try {
                                    const json = JSON.parse(errText);
                                    if (json.message) errorDetail = json.message;
                                } catch(e){}
                            }

                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnHTML;
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาดในการบันทึก',
                                text: errorDetail,
                                background: '#ffffff',
                                color: '#1e293b',
                                customClass: { popup: 'glass-card rounded-[2rem]' }
                            });
                        }
                    }).catch(err => {
                        console.error('Submit error:', err);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHTML;
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'เครือข่ายมีปัญหา',
                            text: 'กรุณาตรวจสอบการเชื่อมต่ออินเทอร์เน็ตของท่านครับ',
                            background: '#ffffff',
                            color: '#1e293b',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        });
                    });
                });
            });
        }
    });

    // --- Interactive Dropdowns ---
    function toggleCardDropdown(event, id) {
        event.stopPropagation();
        const dropdown = document.getElementById(`dropdown-${id}`);
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
        
        allDropdowns.forEach(d => {
            if (d.id !== `dropdown-${id}`) {
                d.classList.add('hidden');
            }
        });
        
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function() {
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
        allDropdowns.forEach(d => d.classList.add('hidden'));
    });

    // --- Like System Simulator ---
    function initLikes() {
        const likedKeys = Object.keys(localStorage).filter(k => k.startsWith('its_liked_'));
        likedKeys.forEach(k => {
            const id = k.replace('its_liked_', '');
            const btn = document.getElementById(`btn-like-${id}`);
            const countSpan = document.getElementById(`like-count-${id}`);
            
            if (btn) {
                btn.innerHTML = `<i data-lucide="thumbs-up" class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-blue-600 text-blue-600"></i> ถูกใจแล้ว`;
                btn.classList.add('text-blue-600');
                btn.classList.remove('text-slate-500');
                
                if (countSpan) {
                    countSpan.innerText = '2'; // Simulated increment
                }
            }
        });
        lucide.createIcons();
    }

    function toggleLike(id) {
        const btn = document.getElementById(`btn-like-${id}`);
        const countSpan = document.getElementById(`like-count-${id}`);
        const storageKey = `its_liked_${id}`;
        
        if (localStorage.getItem(storageKey)) {
            // Unlike
            localStorage.removeItem(storageKey);
            btn.innerHTML = `<i data-lucide="thumbs-up" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i> ถูกใจ`;
            btn.classList.remove('text-blue-600');
            btn.classList.add('text-slate-500');
            if (countSpan) countSpan.innerText = '1';
        } else {
            // Like
            localStorage.setItem(storageKey, 'true');
            btn.innerHTML = `<i data-lucide="thumbs-up" class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-blue-600 text-blue-600"></i> ถูกใจแล้ว`;
            btn.classList.add('text-blue-600');
            btn.classList.remove('text-slate-500');
            if (countSpan) countSpan.innerText = '2';
        }
        lucide.createIcons();
    }

    // --- Share / Link Copier ---
    function copyTicketLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                background: '#ffffff',
                color: '#1e293b',
                customClass: {
                    popup: 'glass-card rounded-2xl border border-emerald-100 shadow-lg shadow-emerald-500/5'
                }
            });
            Toast.fire({
                icon: 'success',
                title: 'คัดลอกลิงก์ใบงานไปยังคลิปบอร์ดแล้ว!'
            });
        });
    }

    function handleCommentClick(id) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            background: '#ffffff',
            color: '#1e293b',
            customClass: {
                popup: 'glass-card rounded-2xl border border-blue-100'
            }
        });
        Toast.fire({
            icon: 'info',
            title: 'ฟังก์ชันกล่องความคิดเห็นจะเปิดให้บริการในเฟสถัดไปครับ'
        });
    }

    // --- Drag and Drop File Manager on Composer Card ---
    const composerCard = document.getElementById('composer-card');
    const composerFileInput = document.getElementById('composer-file-input');

    if (composerCard && composerFileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            composerCard.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        // Add glow highlight on dragover
        ['dragenter', 'dragover'].forEach(eventName => {
            composerCard.addEventListener(eventName, function() {
                composerCard.classList.add('border-dashed', 'border-blue-400', 'bg-blue-50/50', 'scale-[1.015]');
                composerCard.classList.remove('border-l-4', 'border-blue-500');
            }, false);
        });

        // Remove highlight on dragleave or drop
        ['dragleave', 'drop'].forEach(eventName => {
            composerCard.addEventListener(eventName, function() {
                composerCard.classList.remove('border-dashed', 'border-blue-400', 'bg-blue-50/50', 'scale-[1.015]');
                composerCard.classList.add('border-l-4', 'border-blue-500');
            }, false);
        });

        // Handle dropped files
        composerCard.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files && files.length > 0) {
                const imageFiles = Array.from(files).filter(file => file.type.startsWith('image/'));
                
                if (imageFiles.length > 0) {
                    handleNewComposerFiles(imageFiles);
                    
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        background: '#ffffff',
                        color: '#1e293b',
                        customClass: {
                            popup: 'glass-card rounded-2xl border border-blue-100 shadow-lg'
                        }
                    });
                    Toast.fire({
                        icon: 'success',
                        title: `ลากวางเพิ่มรูปภาพสำเร็จแล้ว ${imageFiles.length} รูป!`
                    });
                } else {
                    Swal.fire({
                        title: 'ไฟล์ไม่ถูกต้อง',
                        text: 'กรุณาเลือกเฉพาะรูปภาพ (.png, .jpg, .jpeg) เท่านั้นครับ',
                        icon: 'warning',
                        background: '#ffffff',
                        color: '#1e293b',
                        customClass: {
                            popup: 'glass-card rounded-[2rem] border border-slate-100'
                        }
                    });
                }
            }
        }, false);
    }
</script>
<?= $this->endSection() ?>
