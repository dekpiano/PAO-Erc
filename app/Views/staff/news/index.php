<?= $this->extend('staff/layout/main') ?>

<?= $this->section('content') ?>
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4" data-aos="fade-up">
        <div>
            <h2 class="text-3xl font-black text-slate-900 mb-2">จัดการข่าวสาร</h2>
            <p class="text-slate-400 font-medium tracking-wide flex items-center gap-2 uppercase text-xs">
                <i data-lucide="newspaper" class="w-4 h-4 text-blue-600"></i> Manage News & PR
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Facebook Import Button -->
            <button type="button" onclick="openFbImportModal()" class="flex-1 md:flex-initial px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-bold flex items-center justify-center gap-2.5 hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/20 active:scale-95 group">
                <svg class="w-5 h-5 fill-current text-white group-hover:rotate-6 transition-transform" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <span>นำเข้าจาก Facebook</span>
            </button>

            <!-- Standard Create Button -->
            <a href="<?= base_url('staff/news/create') ?>" class="flex-1 md:flex-initial px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 active:scale-95">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span>เพิ่มข่าวใหม่</span>
            </a>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="mb-6 flex flex-col sm:flex-row items-center justify-between gap-4" data-aos="fade-up" data-aos-delay="50">
        <div class="relative w-full sm:w-80">
            <i data-lucide="search" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" id="news-search" placeholder="ค้นหาหัวข้อข่าว, ผู้ลงข่าว..." onkeyup="filterNewsTable()" class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all shadow-sm">
        </div>
        <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto pb-1 sm:pb-0">
            <button type="button" onclick="filterByCategory('all')" class="category-filter-btn active px-4 py-2 rounded-xl text-xs font-black transition-all bg-blue-600 text-white shadow-sm" data-category="all">ทั้งหมด</button>
            <button type="button" onclick="filterByCategory('ข่าวประชาสัมพันธ์')" class="category-filter-btn px-4 py-2 rounded-xl text-xs font-black transition-all bg-white text-slate-600 hover:bg-slate-100 border border-slate-200" data-category="ข่าวประชาสัมพันธ์">ข่าวประชาสัมพันธ์</button>
            <button type="button" onclick="filterByCategory('กิจกรรม')" class="category-filter-btn px-4 py-2 rounded-xl text-xs font-black transition-all bg-white text-slate-600 hover:bg-slate-100 border border-slate-200" data-category="กิจกรรม">กิจกรรม</button>
            <button type="button" onclick="filterByCategory('ประกาศ')" class="category-filter-btn px-4 py-2 rounded-xl text-xs font-black transition-all bg-white text-slate-600 hover:bg-slate-100 border border-slate-200" data-category="ประกาศ">ประกาศ</button>
            <button type="button" onclick="filterByCategory('สมัครงาน')" class="category-filter-btn px-4 py-2 rounded-xl text-xs font-black transition-all bg-white text-slate-600 hover:bg-slate-100 border border-slate-200" data-category="สมัครงาน">สมัครงาน</button>
        </div>
    </div>

    <!-- News Table Card -->
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="news-table">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">ข่าวสาร</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">หมวดหมู่</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">สถานะ</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">วันที่สร้าง</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">ผู้ลงข่าว</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400">ยอดชม</th>
                        <th class="px-8 py-5 text-xs font-black uppercase tracking-wider text-slate-400 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="news-tbody">
                    <?php if(empty($news)): ?>
                        <tr class="no-data-row">
                            <td colspan="7" class="px-8 py-12 text-center text-slate-400 font-medium">
                                ไม่พบข้อมูลข่าวสารในระบบ
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($news as $item): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors news-row" data-category="<?= esc($item['news_category']) ?>">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <?php if($item['news_cover']): ?>
                                            <div class="w-14 h-14 rounded-2xl border border-slate-100 overflow-hidden flex-shrink-0 shadow-sm">
                                                <img src="<?= base_url('uploads/news/covers/' . $item['news_cover']) ?>" class="w-full h-full object-cover">
                                            </div>
                                        <?php else: ?>
                                            <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 flex-shrink-0">
                                                <i data-lucide="image" class="w-6 h-6"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="max-w-md">
                                            <a href="<?= base_url('news/' . $item['news_slug']) ?>" target="_blank" class="text-sm font-black text-slate-800 leading-snug line-clamp-2 hover:text-blue-600 transition-colors news-title">
                                                <?= $item['news_title'] ?>
                                            </a>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">ID: #<?= $item['news_id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-wider inline-block">
                                        <?= $item['news_category'] ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <?php if($item['news_status'] === 'published'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> เผยแพร่
                                        </span>
                                    <?php elseif($item['news_status'] === 'draft'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 text-amber-600 text-xs font-bold">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> ฉบับร่าง
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-100 text-slate-500 text-xs font-bold">
                                            <span class="w-2 h-2 rounded-full bg-slate-400"></span> ซ่อนไว้
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6 text-sm text-slate-500 font-medium whitespace-nowrap">
                                    <?= date('d/m/Y H:i', strtotime($item['news_created_at'])) ?>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-black uppercase shadow-sm">
                                            <?= strtoupper(substr($item['author_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 news-author"><?= $item['author_name'] ?? 'Unknown' ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-sm text-slate-800 font-black">
                                    <div class="flex items-center gap-1 text-slate-600">
                                        <i data-lucide="eye" class="w-3.5 h-3.5 text-slate-400"></i>
                                        <?= number_format($item['news_view_count']) ?>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= base_url('staff/news/edit/' . $item['news_id']) ?>" title="แก้ไข" class="w-9 h-9 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <button onclick="confirmDelete('<?= base_url('staff/news/delete/' . $item['news_id']) ?>')" title="ลบ" class="w-9 h-9 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 🚀 FACEBOOK IMPORT MODAL (MODERN TAILWIND) -->
    <!-- ========================================== -->
    <div id="fb-import-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 transition-all duration-300 opacity-0">
        <div class="bg-white w-full max-w-3xl rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden transform scale-95 transition-all duration-300" id="fb-modal-container">
            
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-blue-50/50 to-indigo-50/30">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800">นำเข้าข่าวจาก Facebook</h3>
                        <p class="text-xs text-slate-400 font-medium">วางลิงก์โพสต์ Facebook เพื่อดึงรูปภาพและเนื้อหามาลงข่าวอัตโนมัติ</p>
                    </div>
                </div>
                <button type="button" onclick="closeFbImportModal()" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition-colors flex items-center justify-center">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-8 space-y-6 max-h-[78vh] overflow-y-auto">
                
                <!-- URL Input Group -->
                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500">ลิงก์โพสต์ Facebook</label>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="link" class="w-4 h-4"></i>
                            </div>
                            <input type="url" id="fb-post-url" placeholder="https://www.facebook.com/..." class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-300 transition-all">
                        </div>
                        <button type="button" id="btn-fb-fetch" onclick="fetchFacebookPostData()" class="px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold flex items-center gap-2 transition-all shadow-md shadow-blue-500/20 active:scale-95 disabled:opacity-50">
                            <span id="btn-fb-fetch-text">ดึงข้อมูล</span>
                            <span id="btn-fb-fetch-spinner" class="hidden">
                                <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium">รองรับลิงก์โพสต์สาธารณะของเพจ เช่น https://www.facebook.com/PageName/posts/... หรือ https://facebook.com/share/p/...</p>
                </div>

                <!-- Preview & Edit Section (Initially Hidden) -->
                <div id="fb-preview-card" class="hidden space-y-6 pt-6 border-t border-slate-100 animate-[fadeIn_0.3s_ease-out]">
                    
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-black rounded-xl inline-flex items-center gap-1.5">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> ดึงข้อมูลสำเร็จ (ตรวจสอบและแก้ไขได้ตามต้องการ)
                        </span>
                        <span id="fb-detected-source" class="text-[11px] text-slate-400 font-semibold"></span>
                    </div>

                    <!-- Cover Image Preview -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500">
                                รูปหน้าปกปัจจุบัน <span class="text-blue-600 font-semibold">(สามารถคลิกรูปด้านล่างเพื่อเปลี่ยนหน้าปกได้)</span>
                            </label>
                            <span class="text-[11px] text-slate-400 font-medium">★ รูปที่เลือกจะใช้เป็นปกข่าว</span>
                        </div>
                        <div id="fb-cover-preview-box" class="relative rounded-2xl overflow-hidden border border-slate-200 max-h-56 bg-slate-900 group shadow-inner">
                            <img id="fb-cover-preview-img" src="" referrerpolicy="no-referrer" class="w-full h-56 object-cover opacity-90 group-hover:opacity-100 transition-opacity">
                            <input type="hidden" id="fb-cover-url" value="">
                            <input type="hidden" id="fb-cover-local" value="">
                            <div class="absolute bottom-3 left-3 bg-blue-600/90 backdrop-blur-md text-white px-3 py-1.5 rounded-xl text-xs font-black flex items-center gap-1.5 shadow-md">
                                <i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i> รูปหน้าปกหลัก
                            </div>
                        </div>
                    </div>

                    <!-- Extracted Photos / Gallery Selector -->
                    <div id="fb-gallery-box" class="hidden space-y-3 pt-2">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500">
                                รูปภาพทั้งหมดที่ตรวจพบ (<span id="fb-photo-count">0</span> รูป) <span class="text-blue-600 font-bold ml-1" id="fb-selected-count"></span>
                            </label>
                            <div class="flex items-center gap-1.5 text-xs">
                                <button type="button" onclick="selectTopNFbGallery(10)" class="px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-[11px] font-bold transition-all active:scale-95 shadow-xs">
                                    <i data-lucide="check-check" class="w-3.5 h-3.5 inline mr-1"></i>เลือก 10 รูปแรก
                                </button>
                                <button type="button" onclick="setAllFbGallery(true)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-[11px] font-bold transition-all active:scale-95">
                                    เลือกทั้งหมด
                                </button>
                                <button type="button" onclick="setAllFbGallery(false)" class="px-2.5 py-1 bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 rounded-xl text-[11px] font-bold transition-all active:scale-95">
                                    ยกเลิกทั้งหมด
                                </button>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium">★ คลิกที่รูปเพื่อตั้งเป็นหน้าปก หรือติ๊กเครื่องหมายถูกเพื่อนำเข้าในแกลเลอรี</p>
                        <div id="fb-gallery-grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3 max-h-80 overflow-y-auto p-1.5 bg-slate-50/70 rounded-2xl border border-slate-100">
                            <!-- Injected dynamically -->
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">หัวข้อข่าว</label>
                        <input type="text" id="fb-news-title" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all">
                    </div>

                    <!-- Category & Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500">หมวดหมู่</label>
                            <select id="fb-news-category" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all">
                                <option value="ข่าวประชาสัมพันธ์" selected>ข่าวประชาสัมพันธ์</option>
                                <option value="กิจกรรม">กิจกรรม / โครงการ</option>
                                <option value="ประกาศ">ประกาศ / คำสั่ง</option>
                                <option value="สมัครงาน">ข่าวรับสมัครงาน</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500">สถานะ</label>
                            <select id="fb-news-status" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all">
                                <option value="published" selected>เผยแพร่ทันที</option>
                                <option value="draft">บันทึกเป็นฉบับร่าง</option>
                                <option value="hidden">ซ่อนไว้</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500">วันที่ลงข่าว</label>
                            <input type="datetime-local" id="fb-news-date" value="<?= date('Y-m-d\TH:i') ?>" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all">
                        </div>
                    </div>

                    <!-- Content Details -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">เนื้อหาข่าว</label>
                        <textarea id="fb-news-content" rows="6" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-50 transition-all leading-relaxed"></textarea>
                    </div>

                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-8 py-5 border-t border-slate-100 flex items-center justify-between bg-slate-50">
                <button type="button" onclick="closeFbImportModal()" class="px-5 py-3 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    ยกเลิก
                </button>
                <div class="flex items-center gap-3">
                    <button type="button" id="btn-save-fb-news" onclick="submitFacebookImport()" disabled class="px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-bold flex items-center gap-2 transition-all shadow-lg shadow-blue-500/20 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span id="btn-save-fb-text">บันทึกเป็นข่าวสารทันที</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Search news table
    function filterNewsTable() {
        const query = document.getElementById('news-search').value.toLowerCase().trim();
        const rows = document.querySelectorAll('#news-tbody .news-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const title = row.querySelector('.news-title') ? row.querySelector('.news-title').innerText.toLowerCase() : '';
            const author = row.querySelector('.news-author') ? row.querySelector('.news-author').innerText.toLowerCase() : '';
            const category = row.getAttribute('data-category').toLowerCase();
            
            const matches = title.includes(query) || author.includes(query) || category.includes(query);
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });
    }

    // Category Filter tabs
    function filterByCategory(category) {
        document.querySelectorAll('.category-filter-btn').forEach(btn => {
            if (btn.getAttribute('data-category') === category) {
                btn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
                btn.classList.remove('bg-white', 'text-slate-600');
            } else {
                btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
                btn.classList.add('bg-white', 'text-slate-600');
            }
        });

        const rows = document.querySelectorAll('#news-tbody .news-row');
        rows.forEach(row => {
            if (category === 'all' || row.getAttribute('data-category') === category) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Delete Confirmation
    function confirmDelete(url) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลและรูปภาพทั้งหมดจะถูกลบถาวร!",
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
        });
    }

    // ==========================================
    // 🌐 FACEBOOK IMPORT MODAL LOGIC
    // ==========================================
    function openFbImportModal() {
        const modal = document.getElementById('fb-import-modal');
        const container = document.getElementById('fb-modal-container');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            container.classList.remove('scale-95');
            container.classList.add('scale-100');
        }, 10);
        document.getElementById('fb-post-url').focus();
    }

    function closeFbImportModal() {
        const modal = document.getElementById('fb-import-modal');
        const container = document.getElementById('fb-modal-container');
        modal.classList.add('opacity-0');
        container.classList.remove('scale-100');
        container.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);

        // Clean up unused downloaded temp images if modal is closed without saving
        if (currentModalImages.length > 0) {
            fetch('<?= base_url('staff/news/clean-temp') ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).catch(() => {});
            currentModalImages = [];
            document.getElementById('fb-preview-card').classList.add('hidden');
            document.getElementById('btn-save-fb-news').disabled = true;
        }
    }

    async function fetchFacebookPostData() {
        const url = document.getElementById('fb-post-url').value.trim();
        if (!url) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาระบุ URL',
                text: 'โปรดวางลิงก์โพสต์ Facebook ที่ต้องการดึงข้อมูล',
                customClass: { popup: 'rounded-[2rem]' }
            });
            return;
        }

        const btn = document.getElementById('btn-fb-fetch');
        const btnText = document.getElementById('btn-fb-fetch-text');
        const spinner = document.getElementById('btn-fb-fetch-spinner');
        
        btn.disabled = true;
        btnText.textContent = 'กำลังดึงข้อมูล...';
        spinner.classList.remove('hidden');

        try {
            const formData = new FormData();
            formData.append('url', url);

            const response = await fetch('<?= base_url('staff/news/fetch-facebook') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.status === 'success' && result.data) {
                const data = result.data;
                
                // Populate Fields
                document.getElementById('fb-news-title').value = data.title || '';
                document.getElementById('fb-news-content').value = data.content || '';
                if (data.created_at) {
                    document.getElementById('fb-news-date').value = data.created_at;
                }
                
                // Build loaded images list
                currentModalImages = [];
                if (data.cover_local || data.cover_url) {
                    currentModalImages.push({
                        remote: data.cover_url || '',
                        local_file: data.cover_local || '',
                        local_url: data.cover_local_url || data.cover_url || '',
                        inGallery: false
                    });
                }

                if (data.gallery_items && data.gallery_items.length > 0) {
                    data.gallery_items.forEach((item, gIdx) => {
                        // Pre-select first 10 gallery images
                        currentModalImages.push({
                            remote: item.remote || '',
                            local_file: item.local_file || '',
                            local_url: item.local_url || item.remote || '',
                            inGallery: (gIdx < 10)
                        });
                    });
                } else if (data.gallery_urls && data.gallery_urls.length > 0) {
                    data.gallery_urls.forEach((u, gIdx) => {
                        currentModalImages.push({
                            remote: u,
                            local_file: '',
                            local_url: u,
                            inGallery: (gIdx < 10)
                        });
                    });
                }

                selectedCoverIndex = 0;
                renderModalPhotoGrid();

                // Show preview card and enable Save
                document.getElementById('fb-preview-card').classList.remove('hidden');
                document.getElementById('btn-save-fb-news').disabled = false;

                if (window.lucide) lucide.createIcons();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ดึงข้อมูลไม่สำเร็จ',
                    text: result.message || 'ไม่พบข้อมูลโพสต์ กรุณาตรวจสอบลิงก์อีกครั้ง',
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้: ' + err.message,
                customClass: { popup: 'rounded-[2rem]' }
            });
        } finally {
            btn.disabled = false;
            btnText.textContent = 'ดึงข้อมูล';
            spinner.classList.add('hidden');
        }
    }

    let currentModalImages = [];
    let selectedCoverIndex = 0;

    function renderModalPhotoGrid() {
        const coverBox = document.getElementById('fb-cover-preview-box');
        const coverImg = document.getElementById('fb-cover-preview-img');
        const coverInput = document.getElementById('fb-cover-url');
        const coverLocalInput = document.getElementById('fb-cover-local');
        const galleryBox = document.getElementById('fb-gallery-box');
        const galleryGrid = document.getElementById('fb-gallery-grid');

        if (currentModalImages.length === 0) {
            coverBox.parentElement.classList.add('hidden');
            galleryBox.classList.add('hidden');
            return;
        }

        // Set Main Cover display
        coverBox.parentElement.classList.remove('hidden');
        const activeCover = currentModalImages[selectedCoverIndex] || currentModalImages[0];
        coverImg.src = activeCover.local_url || activeCover.remote;
        coverInput.value = activeCover.remote || '';
        if (coverLocalInput) coverLocalInput.value = activeCover.local_file || '';

        // Render Photo Grid
        galleryGrid.innerHTML = '';

        currentModalImages.forEach((item, idx) => {
            const isCover = (idx === selectedCoverIndex);
            const card = document.createElement('div');
            card.className = `relative group aspect-square rounded-2xl overflow-hidden border-2 transition-all cursor-pointer ${
                isCover ? 'border-blue-600 ring-4 ring-blue-100 shadow-md scale-95' : (item.inGallery ? 'border-blue-400 bg-blue-50/20' : 'border-slate-200 opacity-70 hover:opacity-100 hover:border-slate-400')
            }`;
            card.onclick = () => selectModalCoverImage(idx);

            card.innerHTML = `
                <img src="${item.local_url || item.remote}" referrerpolicy="no-referrer" class="w-full h-full object-cover">
                
                ${isCover ? `
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-blue-900/90 to-transparent p-2 text-center pointer-events-none">
                        <span class="text-[10px] font-black text-white flex items-center justify-center gap-1">
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i> หน้าปกหลัก
                        </span>
                    </div>
                ` : `
                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-1.5 text-center">
                        <span class="px-2.5 py-1 bg-white text-blue-600 rounded-xl text-[10px] font-black shadow-md flex items-center gap-1 transform scale-95 group-hover:scale-100 transition-transform">
                            <i data-lucide="star" class="w-3.5 h-3.5"></i> ตั้งเป็นหน้าปก
                        </span>
                    </div>
                `}

                ${!isCover ? `
                <div class="absolute top-1.5 right-1.5 z-10" onclick="event.stopPropagation()">
                    <label class="relative flex items-center justify-center cursor-pointer">
                        <input type="checkbox" name="fb_gallery_items[]" data-local="${item.local_file || ''}" data-remote="${item.remote || ''}" ${item.inGallery ? 'checked' : ''} onchange="toggleGalleryInclusion(${idx}, this.checked)" title="นำเข้ารูปนี้ในแกลเลอรี" class="w-5 h-5 rounded-lg text-blue-600 border-slate-300 focus:ring-blue-500 shadow-md cursor-pointer accent-blue-600">
                    </label>
                </div>
                ` : ''}
            `;
            galleryGrid.appendChild(card);
        });

        galleryBox.classList.remove('hidden');
        updateGalleryCountDisplay();
        if (window.lucide) lucide.createIcons();
    }

    function selectModalCoverImage(index) {
        selectedCoverIndex = index;
        renderModalPhotoGrid();
    }

    function toggleGalleryInclusion(index, checked) {
        if (currentModalImages[index]) {
            currentModalImages[index].inGallery = checked;
            updateGalleryCountDisplay();
        }
    }

    function updateGalleryCountDisplay() {
        const countSpan = document.getElementById('fb-photo-count');
        const selectedCountSpan = document.getElementById('fb-selected-count');
        if (countSpan) countSpan.textContent = currentModalImages.length;
        if (selectedCountSpan) {
            const checkedCount = currentModalImages.filter((img, idx) => idx !== selectedCoverIndex && img.inGallery).length;
            selectedCountSpan.textContent = `(เลือกแกลเลอรี ${checkedCount} รูป)`;
        }
    }

    function selectTopNFbGallery(n = 10) {
        let count = 0;
        currentModalImages.forEach((img, idx) => {
            if (idx === selectedCoverIndex) {
                img.inGallery = false;
            } else if (count < n) {
                img.inGallery = true;
                count++;
            } else {
                img.inGallery = false;
            }
        });
        renderModalPhotoGrid();
    }

    function setAllFbGallery(checked) {
        currentModalImages.forEach((img, idx) => {
            if (idx !== selectedCoverIndex) {
                img.inGallery = checked;
            }
        });
        renderModalPhotoGrid();
    }

    async function submitFacebookImport() {
        const title = document.getElementById('fb-news-title').value.trim();
        if (!title) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาระบุหัวข้อข่าว',
                customClass: { popup: 'rounded-[2rem]' }
            });
            return;
        }

        const btnSave = document.getElementById('btn-save-fb-news');
        const btnSaveText = document.getElementById('btn-save-fb-text');
        btnSave.disabled = true;
        btnSaveText.textContent = 'กำลังบันทึกข้อมูลและรูปภาพ...';

        try {
            const formData = new FormData();
            formData.append('title', title);
            formData.append('content', document.getElementById('fb-news-content').value);
            formData.append('category', document.getElementById('fb-news-category').value);
            formData.append('status', document.getElementById('fb-news-status').value);
            formData.append('created_at', document.getElementById('fb-news-date').value);
            const activeCover = currentModalImages[selectedCoverIndex] || currentModalImages[0];
            if (activeCover) {
                if (activeCover.local_file) {
                    formData.append('cover_local', activeCover.local_file);
                } else if (activeCover.remote) {
                    formData.append('cover_url', activeCover.remote);
                }
            }

            // Collect selected gallery items (excluding the chosen cover image)
            currentModalImages.forEach((img, idx) => {
                if (idx !== selectedCoverIndex && img.inGallery) {
                    if (img.local_file) {
                        formData.append('gallery_locals[]', img.local_file);
                    } else if (img.remote) {
                        formData.append('gallery_urls[]', img.remote);
                    }
                }
            });

            const response = await fetch('<?= base_url('staff/news/import-facebook') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ!',
                    text: result.message,
                    timer: 1800,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกได้',
                    text: result.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                    customClass: { popup: 'rounded-[2rem]' }
                });
                btnSave.disabled = false;
                btnSaveText.textContent = 'บันทึกเป็นข่าวสารทันที';
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: err.message,
                customClass: { popup: 'rounded-[2rem]' }
            });
            btnSave.disabled = false;
            btnSaveText.textContent = 'บันทึกเป็นข่าวสารทันที';
        }
    }
</script>
<?= $this->endSection() ?>
