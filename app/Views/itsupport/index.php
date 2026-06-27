<?= $this->extend('itsupport/layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 w-full">
    <div class="min-w-0 w-full md:w-auto">
        <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight tech-glow flex items-center gap-2 sm:gap-3">
            <span class="truncate">ไทม์ไลน์งานบริการ</span>
        </h2>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">ประวัติการบำรุงรักษาและแก้ไขปัญหาทางระบบ</p>
    </div>
    
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <?php if ($can_manage): ?>
            <!-- Button to open Post Job Modal -->
            <button onclick="openModal('modal-post-job')" class="flex-1 md:flex-none justify-center px-4 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm transition-all flex items-center gap-2 shadow-md shadow-blue-500/10">
                <i data-lucide="plus-circle" class="w-4.5 h-4.5"></i> <span>โพสต์บันทึกงาน</span>
            </button>
        <?php endif; ?>

        <!-- Button to open Search & Filter Modal -->
        <button onclick="openModal('modal-search')" class="flex-1 md:flex-none justify-center px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-slate-900 font-bold text-xs sm:text-sm hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
            <i data-lucide="search" class="w-4.5 h-4.5 text-blue-600"></i> <span>ค้นหา & กรองฟีด</span>
        </button>

        <?php if ($can_manage): ?>
            <!-- Button to open Export Report Modal -->
            <button onclick="openModal('modal-export')" class="flex-1 md:flex-none justify-center px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-slate-900 font-bold text-xs sm:text-sm hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                <i data-lucide="file-spreadsheet" class="w-4.5 h-4.5 text-emerald-600"></i> <span>ส่งออกรายงาน</span>
            </button>
        <?php endif; ?>
    </div>
</div>

<!-- Active Filters Summary Badge Bar -->
<?php if(!empty($search) || !empty($category_active) || !empty($location_active) || !empty($start_date) || !empty($end_date)): ?>
<div class="flex flex-wrap items-center gap-2 mb-6 bg-blue-50/50 dark:bg-blue-950/20 border border-blue-100/50 dark:border-blue-900/30 p-3.5 rounded-2xl max-w-2xl mx-auto w-full">
    <span class="text-xs font-bold text-slate-500">ตัวกรองที่ใช้งานอยู่:</span>
    <?php if(!empty($search)): ?>
        <span class="px-2.5 py-1 text-[10px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg flex items-center gap-1 text-slate-700">
            🔍 "<?= esc($search) ?>"
        </span>
    <?php endif; ?>
    <?php if(!empty($category_active)): ?>
        <span class="px-2.5 py-1 text-[10px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg flex items-center gap-1 text-slate-700">
            📁 <?= esc($category_active) ?>
        </span>
    <?php endif; ?>
    <?php if(!empty($location_active)): ?>
        <span class="px-2.5 py-1 text-[10px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg flex items-center gap-1 text-slate-700">
            📍 <?= esc($location_active) ?>
        </span>
    <?php endif; ?>
    <?php if(!empty($start_date) || !empty($end_date)): ?>
        <span class="px-2.5 py-1 text-[10px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg flex items-center gap-1 text-slate-700">
            📅 <?= esc($start_date ?: '...') ?> ถึง <?= esc($end_date ?: '...') ?>
        </span>
    <?php endif; ?>
    <a href="<?= base_url('itsupport') ?>" class="ml-auto text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1 transition-colors">
        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> ล้างทั้งหมด
    </a>
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
                                    <a href="<?= base_url('itsupport/edit/' . $log['its_id']) ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl hover:bg-slate-50 text-slate-655 hover:text-slate-900 text-[11px] font-bold transition-all">
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
                        $imgUrls = [];
                        foreach($images as $img) {
                            $imgUrls[] = base_url('uploads/it_support/' . $img);
                        }
                        $imgJson = htmlspecialchars(json_encode($imgUrls), ENT_QUOTES, 'UTF-8');
                    ?>
                    <?php if($imgCount > 0): ?>
                        <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 shadow-sm bg-slate-50">
                            <?php if($imgCount === 1): ?>
                                <!-- 1 Image -->
                                <div class="relative w-full aspect-video bg-slate-100 cursor-zoom-in group" onclick="zoomImage(<?= $imgJson ?>, 0)">
                                    <img src="<?= base_url('uploads/it_support/' . $images[0]) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <i data-lucide="zoom-in" class="w-6 h-6"></i>
                                    </div>
                                </div>
                            <?php elseif($imgCount === 2): ?>
                                <!-- 2 Images -->
                                <div class="grid grid-cols-2 gap-0.5">
                                    <?php foreach($images as $idx => $img): ?>
                                        <div class="relative aspect-square bg-slate-100 cursor-zoom-in group" onclick="zoomImage(<?= $imgJson ?>, <?= $idx ?>)">
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
                                    <div class="col-span-2 relative h-full bg-slate-100 cursor-zoom-in group" onclick="zoomImage(<?= $imgJson ?>, 0)">
                                        <img src="<?= base_url('uploads/it_support/' . $images[0]) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                            <i data-lucide="zoom-in" class="w-6 h-6"></i>
                                        </div>
                                    </div>
                                    <div class="grid grid-rows-2 gap-0.5 h-full">
                                        <?php for($k = 1; $k <= 2; $k++): ?>
                                            <div class="relative h-full bg-slate-100 cursor-zoom-in group" onclick="zoomImage(<?= $imgJson ?>, <?= $k ?>)">
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
                                    <div class="relative aspect-video bg-slate-100 cursor-zoom-in group" onclick="zoomImage(<?= $imgJson ?>, 0)">
                                        <img src="<?= base_url('uploads/it_support/' . $images[0]) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                            <i data-lucide="zoom-in" class="w-6 h-6"></i>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-0.5">
                                        <div class="relative aspect-square bg-slate-100 cursor-zoom-in group" onclick="zoomImage(<?= $imgJson ?>, 1)">
                                            <img src="<?= base_url('uploads/it_support/' . $images[1]) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
                                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                                <i data-lucide="zoom-in" class="w-4 h-4"></i>
                                            </div>
                                        </div>
                                        <div class="relative aspect-square bg-slate-100 cursor-zoom-in group" onclick="zoomImage(<?= $imgJson ?>, 2)">
                                            <img src="<?= base_url('uploads/it_support/' . $images[2]) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
                                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                                <i data-lucide="zoom-in" class="w-4 h-4"></i>
                                            </div>
                                        </div>
                                        <div class="relative aspect-square bg-slate-100 cursor-pointer group" onclick="zoomImage(<?= $imgJson ?>, 3)">
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

<!-- Modal: Search & Filter -->
<div id="modal-search" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] transition-all flex items-center justify-center p-4">
    <div class="glass-card rounded-[2rem] w-full max-w-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl p-6 sm:p-8 relative max-h-[90vh] overflow-y-auto custom-scrollbar animate-[scaleIn_0.3s_ease]">
        <button onclick="closeModal('modal-search')" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-650 rounded-full hover:bg-slate-50 transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        
        <h3 class="text-lg sm:text-xl font-extrabold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
            <i data-lucide="search" class="w-6 h-6 text-blue-600"></i> ค้นหา & กรองข้อมูลประวัติ
        </h3>

        <form method="GET" action="<?= base_url('itsupport') ?>" class="space-y-4 mt-4">
            <!-- Search Keyword -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">คำค้นหา</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i data-lucide="search" class="w-4 h-4"></i></span>
                    <input type="text" name="search" value="<?= esc($search) ?>" placeholder="ค้นหารายละเอียด, ผู้บันทึก, รหัส..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-800 outline-none transition-all">
                </div>
            </div>

            <!-- Category -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">กรองตามประเภทงาน</label>
                <select name="category" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-700 outline-none transition-all">
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
            </div>

            <!-- Location -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">สถานที่ปฏิบัติงาน</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><i data-lucide="map-pin" class="w-4 h-4"></i></span>
                    <input type="text" list="loc-datalist-modal" name="location" value="<?= esc($location_active) ?>" placeholder="📍 สถานที่..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-800 outline-none transition-all">
                    <datalist id="loc-datalist-modal">
                        <?php foreach($locations as $loc): ?>
                            <option value="<?= esc($loc) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">ตั้งแต่วันที่</label>
                    <input type="text" name="start_date" value="<?= esc($start_date) ?>" placeholder="เริ่มต้น..." class="datetimepicker-be w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-800 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">ถึงวันที่</label>
                    <input type="text" name="end_date" value="<?= esc($end_date) ?>" placeholder="สิ้นสุด..." class="datetimepicker-be w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-800 outline-none transition-all">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-search')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                    ยกเลิก
                </button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5">
                    <i data-lucide="filter" class="w-4 h-4"></i> ค้นหา & กรองฟีด
                </button>
            </div>
        </form>
    </div>
</div>

<?php if ($can_manage): ?>
<!-- Modal: Post Job -->
<div id="modal-post-job" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] transition-all flex items-center justify-center p-4">
    <div id="composer-card" class="glass-card rounded-[2rem] w-full max-w-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl p-6 sm:p-8 relative max-h-[90vh] overflow-y-auto custom-scrollbar animate-[scaleIn_0.3s_ease] border-l-4 border-blue-500">
        <button onclick="closeModal('modal-post-job')" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-650 rounded-full hover:bg-slate-50 transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        
        <h3 class="text-lg sm:text-xl font-extrabold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
            <i data-lucide="plus-circle" class="w-6 h-6 text-blue-600"></i> โพสต์บันทึกงานบริการใหม่
        </h3>

        <div class="flex gap-3 sm:gap-4 items-start mt-4">
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
                <form id="composer-form" method="POST" action="<?= base_url('itsupport/store') ?>" enctype="multipart/form-data" class="space-y-4">
                    <?= csrf_field() ?>
                    
                    <!-- Textarea -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">รายละเอียดการทำงาน</label>
                        <textarea name="its_task" required rows="3" placeholder="คุณกำลังปฏิบัติงานบริการหรือซ่อมบำรุงส่วนไหนอยู่ครับ <?= explode(' ', session()->get('u_fullname') ?? '')[0] ?>?" class="w-full bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-2xl p-3.5 text-base md:text-xs text-slate-800 placeholder-slate-400 outline-none transition-all resize-none"></textarea>
                    </div>
                    
                    <!-- Expanded fields -->
                    <div class="grid grid-cols-1 gap-3">
                        <!-- Date Input -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">วันเวลาที่ทำงาน</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i data-lucide="calendar" class="w-3.5 h-3.5"></i></span>
                                <input type="text" name="its_date" required value="<?= date('Y-m-d H:i:s') ?>" class="datetimepicker-be w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-800 outline-none transition-all">
                            </div>
                        </div>
                        
                        <!-- Category Select -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">ประเภทหมวดหมู่</label>
                            <select name="its_category" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-700 outline-none transition-all">
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat ?>"><?= $cat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Location Input with datalist -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">สถานที่ปฏิบัติงาน</label>
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

                    <!-- Progress Bar Container -->
                    <div id="upload-progress-container" class="hidden mt-3 space-y-1.5">
                        <div class="flex justify-between text-[10px] font-extrabold text-slate-500">
                            <span id="progress-label" class="text-blue-600">กำลังอัปโหลด...</span>
                            <span id="progress-percent">0%</span>
                        </div>
                        <div class="w-full bg-slate-150 dark:bg-slate-800 rounded-full h-2 overflow-hidden shadow-inner">
                            <div id="progress-bar" class="bg-gradient-to-r from-blue-500 to-blue-600 h-full rounded-full transition-all duration-300 w-0"></div>
                        </div>
                    </div>

                    <!-- Preview container -->
                    <div id="quick-image-preview" class="grid grid-cols-3 gap-3 pt-2"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Export Excel & Print Report Options -->
<div id="modal-export" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] transition-all flex items-center justify-center p-4">
    <div class="glass-card rounded-[2rem] w-full max-w-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl p-6 sm:p-8 relative max-h-[90vh] overflow-y-auto custom-scrollbar animate-[scaleIn_0.3s_ease]">
        <button onclick="closeModal('modal-export')" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-650 rounded-full hover:bg-slate-50 transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        
        <h3 class="text-lg sm:text-xl font-extrabold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
            <i data-lucide="file-text" class="w-6 h-6 text-blue-600"></i> สรุปรายงาน & ส่งออกข้อมูล
        </h3>

        <form method="GET" action="<?= base_url('itsupport/export') ?>" target="_blank" class="space-y-4 mt-4">
            <p class="text-xs text-slate-550 font-medium">ระบุพารามิเตอร์เพื่อกรองข้อมูลสำหรับการพิมพ์รายงานส่งหัวหน้า หรือส่งออกเป็นไฟล์ Excel</p>
            
            <!-- Search Keyword -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">คำค้นหา</label>
                <input type="text" name="search" value="<?= esc($search) ?>" placeholder="คำค้นหา..." class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-800 outline-none transition-all">
            </div>

            <!-- Category -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">ประเภทงาน</label>
                <select name="category" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-700 outline-none transition-all">
                    <option value="">-- กรองทุกประเภทงาน --</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat ?>" <?= $category_active == $cat ? 'selected' : '' ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Location -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">สถานที่</label>
                <input type="text" list="loc-datalist-export" name="location" value="<?= esc($location_active) ?>" placeholder="สถานที่..." class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-800 outline-none transition-all">
                <datalist id="loc-datalist-export">
                    <?php foreach($locations as $loc): ?>
                        <option value="<?= esc($loc) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">ตั้งแต่วันที่</label>
                    <input type="text" name="start_date" value="<?= esc($start_date) ?>" placeholder="เริ่มต้น..." class="datetimepicker-be w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-800 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">ถึงวันที่</label>
                    <input type="text" name="end_date" value="<?= esc($end_date) ?>" placeholder="สิ้นสุด..." class="datetimepicker-be w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl text-base md:text-xs text-slate-800 outline-none transition-all">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 flex-wrap">
                <button type="button" onclick="closeModal('modal-export')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                    ยกเลิก
                </button>
                <button type="button" onclick="submitPrintReport()" class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5">
                    <i data-lucide="printer" class="w-4 h-4"></i> พิมพ์รายงานสรุป (A4)
                </button>
                <button type="submit" onclick="closeModal('modal-export')" class="px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> ดาวน์โหลด Excel
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Script for sweetalert2 image zoom and delete confirm -->
<style>
    @keyframes scaleIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initLikes();
    });

    // Modal Control Functions
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            lucide.createIcons();
            
            // Re-initialize Flatpickr for dynamically opened elements inside modal
            const fpConfig = {
                dateFormat: "Y-m-d H:i:00", 
                enableTime: true,
                time_24hr: true,
                allowInput: false,
                disableMobile: true,
                altInput: true, 
                altFormat: "d/m/Y H:i น.", 
                locale: "th",
                onReady: function(d, s, fp) { applyBE(fp); },
                onValueUpdate: function(d, s, fp) { applyBE(fp); },
                onOpen: function(d, s, fp) { applyBE(fp); },
                onMonthChange: function(d, s, fp) { setTimeout(function(){ applyBE(fp); }, 10); },
                onYearChange: function(d, s, fp) { setTimeout(function(){ applyBE(fp); }, 10); }
            };
            modal.querySelectorAll(".datetimepicker-be").forEach(el => {
                if(el._flatpickr) {
                    el._flatpickr.destroy();
                }
                flatpickr(el, fpConfig);
            });
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function submitPrintReport() {
        const exportForm = document.querySelector('#modal-export form');
        if (exportForm) {
            const originalAction = exportForm.action;
            exportForm.action = '<?= base_url("itsupport/report_print") ?>';
            exportForm.submit();
            // คืนค่า action เดิมกลับไปเป็น export excel
            exportForm.action = originalAction;
            closeModal('modal-export');
        }
    }

    // Close on clicking backdrop
    document.addEventListener('click', function(e) {
        const modals = ['modal-search', 'modal-post-job', 'modal-export'];
        modals.forEach(id => {
            const modal = document.getElementById(id);
            if (modal && !modal.classList.contains('hidden') && e.target === modal) {
                closeModal(id);
            }
        });
    });

    function openImageGallery(images, startIndex) {
        if (!images || images.length === 0) return;
        
        let currentIndex = startIndex;
        
        // สร้างคอนเทนเนอร์ modal สำหรับแกลเลอรีรูปภาพ
        const modal = document.createElement('div');
        modal.id = 'album-gallery-modal';
        modal.className = 'fixed inset-0 bg-slate-950/95 backdrop-blur-md z-[200] flex flex-col justify-between p-4 select-none';
        
        // เพิ่มสไตล์แอนิเมชันและความสวยงาม
        modal.innerHTML = `
            <!-- Top Bar -->
            <div class="flex justify-between items-center text-white z-10 py-2 px-4 max-w-7xl mx-auto w-full">
                <span class="text-xs sm:text-sm font-bold bg-slate-800/80 px-3.5 py-2 rounded-full border border-slate-700/50 backdrop-blur shadow-lg" id="gallery-counter">
                    ${currentIndex + 1} / ${images.length}
                </span>
                <button id="gallery-close" class="p-2.5 bg-slate-800/80 border border-slate-700/50 hover:bg-rose-600 hover:border-rose-500 rounded-full text-white transition-all shadow-lg hover:scale-105" title="ปิดหน้าต่าง (Esc)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Main Content Container -->
            <div class="flex-1 flex items-center justify-center relative max-w-5xl mx-auto w-full my-4">
                <!-- ปุ่มย้อนกลับ (Prev Button) -->
                <button id="gallery-prev" class="absolute left-2 sm:left-4 p-3.5 bg-slate-900/60 border border-slate-800/50 hover:bg-blue-600 hover:border-blue-500 hover:scale-110 rounded-full text-white transition-all z-20 shadow-xl ${images.length <= 1 ? 'hidden' : ''}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <!-- กล่องแสดงภาพประกอบหลัก -->
                <div class="w-full h-full flex items-center justify-center p-2">
                    <img id="gallery-image" src="${images[currentIndex]}" class="max-w-full max-h-[72vh] object-contain rounded-2xl shadow-2xl transition-all duration-300 transform scale-100 ease-out">
                </div>
                
                <!-- ปุ่มถัดไป (Next Button) -->
                <button id="gallery-next" class="absolute right-2 sm:right-4 p-3.5 bg-slate-900/60 border border-slate-800/50 hover:bg-blue-600 hover:border-blue-500 hover:scale-110 rounded-full text-white transition-all z-20 shadow-xl ${images.length <= 1 ? 'hidden' : ''}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            
            <!-- Bottom Thumbnails Carousel/Bar -->
            <div class="z-10 py-3 flex justify-center gap-2 overflow-x-auto max-w-xl mx-auto w-full px-4 scrollbar-none">
                ${images.map((img, idx) => `
                    <div id="gallery-thumb-${idx}" onclick="window.setGalleryIndex(${idx})" class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl overflow-hidden cursor-pointer border-2 transition-all shrink-0 ${idx === currentIndex ? 'border-blue-500 scale-105 shadow-md shadow-blue-500/20' : 'border-slate-800 opacity-50 hover:opacity-80'}">
                        <img src="${img}" class="w-full h-full object-cover">
                    </div>
                `).join('')}
            </div>
        `;
        
        document.body.appendChild(modal);
        document.body.classList.add('overflow-hidden');
        
        const imgEl = modal.querySelector('#gallery-image');
        const counterEl = modal.querySelector('#gallery-counter');
        const prevBtn = modal.querySelector('#gallery-prev');
        const nextBtn = modal.querySelector('#gallery-next');
        
        function updateView() {
            imgEl.style.opacity = '0';
            imgEl.style.transform = 'scale(0.96)';
            
            setTimeout(() => {
                imgEl.src = images[currentIndex];
                counterEl.innerText = `${currentIndex + 1} / ${images.length}`;
                
                // ไฮไลต์รูปย่อ (Thumbnail)
                images.forEach((_, idx) => {
                    const thumb = modal.querySelector(`#gallery-thumb-${idx}`);
                    if (thumb) {
                        if (idx === currentIndex) {
                            thumb.className = "w-12 h-12 sm:w-16 sm:h-16 rounded-xl overflow-hidden cursor-pointer border-2 transition-all shrink-0 border-blue-500 scale-105 shadow-md shadow-blue-500/20";
                        } else {
                            thumb.className = "w-12 h-12 sm:w-16 sm:h-16 rounded-xl overflow-hidden cursor-pointer border-2 transition-all shrink-0 border-slate-800 opacity-50 hover:opacity-80";
                        }
                    }
                });
                
                imgEl.style.opacity = '1';
                imgEl.style.transform = 'scale(1)';
            }, 120);
        }
        
        window.setGalleryIndex = function(idx) {
            currentIndex = idx;
            updateView();
        };
        
        function next() {
            currentIndex = (currentIndex + 1) % images.length;
            updateView();
        }
        
        function prev() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateView();
        }
        
        let isClosed = false;
        function close() {
            if (isClosed) return;
            isClosed = true;
            modal.style.transition = 'opacity 0.2s ease';
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.remove();
                document.body.classList.remove('overflow-hidden');
                delete window.setGalleryIndex;
            }, 200);
        }
        
        // ผูกปุ่มการทำงาน
        prevBtn.addEventListener('click', prev);
        nextBtn.addEventListener('click', next);
        modal.querySelector('#gallery-close').addEventListener('click', close);
        
        // คลิกตรงส่วนที่ว่างภายนอกเพื่อปิดรูป
        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.id === 'gallery-image-wrapper' || e.target.closest('.flex-1') === e.target) {
                close();
            }
        });
        
        // รองรับการกดแป้นคีย์บอร์ด
        const handleKeyDown = (e) => {
            if (e.key === 'Escape') close();
            if (images.length > 1) {
                if (e.key === 'ArrowRight') next();
                if (e.key === 'ArrowLeft') prev();
            }
        };
        document.addEventListener('keydown', handleKeyDown);
        
        // เคลียร์คีย์บอร์ด Listener ตอนปิด Modal
        const originalClose = close;
        close = () => {
            document.removeEventListener('keydown', handleKeyDown);
            originalClose();
        };
    }

    function zoomImage(urlOrArray, index = 0) {
        if (Array.isArray(urlOrArray)) {
            openImageGallery(urlOrArray, index);
        } else {
            openImageGallery([urlOrArray], 0);
        }
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
        // Preview updates only
    }

    // --- Client Side Image Compression ---
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
                    
                    ctx.drawImage(img, 0, 0, width, height);

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

    // --- Chunked Upload Helper ---
    function uploadFileInChunks(file, progressCallback) {
        return new Promise((resolve, reject) => {
            const chunkSize = 256 * 1024; // 256KB Chunks
            const totalChunks = Math.ceil(file.size / chunkSize);
            const fileId = Date.now().toString() + Math.random().toString(36).substring(2, 8);
            let chunkIndex = 0;

            function uploadNextChunk() {
                const start = chunkIndex * chunkSize;
                const end = Math.min(start + chunkSize, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('file_id', fileId);
                formData.append('chunk_index', chunkIndex);
                formData.append('total_chunks', totalChunks);
                formData.append('filename', file.name);
                formData.append('chunk', chunk);
                
                const csrfInput = document.querySelector('input[name="csrf_test_name"]');
                if (csrfInput) {
                    formData.append('csrf_test_name', csrfInput.value);
                }

                fetch('<?= base_url("itsupport/upload_chunk") ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('การอัปโหลดไฟล์ล้มเหลว');
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        progressCallback(100);
                        resolve(data.filename);
                    } else if (data.status === 'uploading') {
                        const percent = Math.round(((chunkIndex + 1) / totalChunks) * 100);
                        progressCallback(percent);
                        chunkIndex++;
                        uploadNextChunk();
                    } else {
                        reject(new Error(data.message || 'Unknown upload error'));
                    }
                })
                .catch(err => reject(err));
            }

            uploadNextChunk();
        });
    }

    // --- Form Submit Interceptor ---
    document.addEventListener('DOMContentLoaded', function() {
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

        // --- ระบบบันทึกร่างข้อมูลอัตโนมัติ (Draft Auto-save) ---
        const draftTask = document.querySelector('#composer-form textarea[name="its_task"]');
        const draftCategory = document.querySelector('#composer-form select[name="its_category"]');
        const draftLocation = document.querySelector('#composer-form input[name="its_location"]');

        if (draftTask && draftCategory && draftLocation) {
            // โหลดข้อมูลร่างกลับมาแสดง
            if (localStorage.getItem('its_draft_task')) {
                draftTask.value = localStorage.getItem('its_draft_task');
            }
            if (localStorage.getItem('its_draft_category')) {
                draftCategory.value = localStorage.getItem('its_draft_category');
            }
            if (localStorage.getItem('its_draft_location')) {
                draftLocation.value = localStorage.getItem('its_draft_location');
            }

            // ฟังความเปลี่ยนแปลงเพื่อจัดเก็บร่าง
            draftTask.addEventListener('input', () => localStorage.setItem('its_draft_task', draftTask.value));
            draftCategory.addEventListener('change', () => localStorage.setItem('its_draft_category', draftCategory.value));
            draftLocation.addEventListener('input', () => localStorage.setItem('its_draft_location', draftLocation.value));
        }

        const composerForm = document.getElementById('composer-form');
        if (composerForm) {
            composerForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const submitBtn = composerForm.querySelector('button[type="submit"]');
                const originalBtnHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="flex items-center gap-1.5 sm:gap-2">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="upload-status-text">กำลังบันทึก...</span>
                    </span>
                `;

                // คอนเทนเนอร์และแถบความคืบหน้าความก้าวหน้า
                const progressContainer = document.getElementById('upload-progress-container');
                const progressBar = document.getElementById('progress-bar');
                const progressPercent = document.getElementById('progress-percent');
                const progressLabel = document.getElementById('progress-label');

                try {
                    // 1. บีบอัดรูปภาพ
                    const compressedFiles = [];
                    if (composerFilesQueue.length > 0) {
                        progressContainer.classList.remove('hidden');
                        progressBar.style.width = '0%';
                        progressPercent.innerText = '0%';
                        
                        for(let i=0; i<composerFilesQueue.length; i++) {
                            progressLabel.innerText = `กำลังลดขนาดรูปภาพที่ ${i+1}/${composerFilesQueue.length}...`;
                            const compressed = await compressImageClientSide(composerFilesQueue[i]);
                            compressedFiles.push(compressed);
                            
                            const compPercent = Math.round(((i + 1) / composerFilesQueue.length) * 20); // ให้คอมเพรสกินโควต้า 20% แรก
                            progressBar.style.width = `${compPercent}%`;
                            progressPercent.innerText = `${compPercent}%`;
                        }
                    }

                    // 2. อัปโหลดเป็น Chunks แบบเรียงลำดับ
                    const finalFilenames = [];
                    for(let i=0; i<compressedFiles.length; i++) {
                        const file = compressedFiles[i];
                        const filename = await uploadFileInChunks(file, (percent) => {
                            // คำนวณช่วงเปอร์เซ็นต์สะสมของการอัปโหลด (20% - 100%)
                            const basePercent = 20;
                            const range = 80;
                            const currentFileContribution = (percent / 100) * (range / compressedFiles.length);
                            const overallPercent = Math.round(basePercent + (i * (range / compressedFiles.length)) + currentFileContribution);
                            
                            progressBar.style.width = `${overallPercent}%`;
                            progressPercent.innerText = `${overallPercent}%`;
                            progressLabel.innerText = `กำลังอัปโหลดรูปที่ ${i+1}/${compressedFiles.length} (${percent}%)...`;
                        });
                        finalFilenames.push(filename);
                    }

                    if (composerFilesQueue.length > 0) {
                        progressBar.style.width = '100%';
                        progressPercent.innerText = '100%';
                        progressLabel.innerText = 'อัปโหลดสำเร็จ! กำลังบันทึกข้อมูลใบงาน...';
                    }

                    const formData = new FormData();
                    const formElements = composerForm.elements;
                    for (let i = 0; i < formElements.length; i++) {
                        const el = formElements[i];
                        if (el.name && el.type !== 'file' && el.type !== 'submit') {
                            formData.append(el.name, el.value);
                        }
                    }

                    formData.append('uploaded_images', JSON.stringify(finalFilenames));

                    fetch(composerForm.action, {
                        method: 'POST',
                        body: formData
                    }).then(async response => {
                        if (response.ok) {
                            // ลบข้อมูลร่างเมื่อบันทึกสำเร็จ
                            localStorage.removeItem('its_draft_task');
                            localStorage.removeItem('its_draft_category');
                            localStorage.removeItem('its_draft_location');

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
                            progressContainer.classList.add('hidden');
                            
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
                        progressContainer.classList.add('hidden');
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'เครือข่ายมีปัญหา',
                            text: 'กรุณาตรวจสอบการเชื่อมต่ออินเทอร์เน็ตของท่านครับ',
                            background: '#ffffff',
                            color: '#1e293b',
                            customClass: { popup: 'glass-card rounded-[2rem]' }
                        });
                    });

                } catch (uploadError) {
                    console.error('Upload error:', uploadError);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHTML;
                    progressContainer.classList.add('hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'อัปโหลดรูปภาพล้มเหลว',
                        text: 'เกิดข้อผิดพลาดขณะอัปโหลดไฟล์ภาพ: ' + uploadError.message,
                        background: '#ffffff',
                        color: '#1e293b',
                        customClass: { popup: 'glass-card rounded-[2rem]' }
                    });
                }
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
                    countSpan.innerText = '2';
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
            localStorage.removeItem(storageKey);
            btn.innerHTML = `<i data-lucide="thumbs-up" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i> ถูกใจ`;
            btn.classList.remove('text-blue-600');
            btn.classList.add('text-slate-500');
            if (countSpan) countSpan.innerText = '1';
        } else {
            localStorage.setItem(storageKey, 'true');
            btn.innerHTML = `<i data-lucide="thumbs-up" class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-blue-600 text-blue-600"></i> ถูกใจแล้ว`;
            btn.classList.add('text-blue-600');
            btn.classList.remove('text-slate-500');
            if (countSpan) countSpan.innerText = '2';
        }
        lucide.createIcons();
    }

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

        ['dragenter', 'dragover'].forEach(eventName => {
            composerCard.addEventListener(eventName, function() {
                composerCard.classList.add('border-dashed', 'border-blue-400', 'bg-blue-50/50', 'scale-[1.015]');
                composerCard.classList.remove('border-l-4', 'border-blue-500');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            composerCard.addEventListener(eventName, function() {
                composerCard.classList.remove('border-dashed', 'border-blue-400', 'bg-blue-50/50', 'scale-[1.015]');
                composerCard.classList.add('border-l-4', 'border-blue-500');
            }, false);
        });

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
