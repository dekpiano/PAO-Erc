<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('news', 'Home::newsAll');
$routes->get('news/(:segment)', 'Home::newsDetail/$1');
$routes->get('personnel', 'Home::personnel');
$routes->get('booking/slots/(:num)', 'Home::scholarshipBookingSlots/$1');
$routes->get('booking/check-slot/(:num)', 'Home::scholarshipBookingCheckSlot/$1');
$routes->post('booking/store', 'Home::scholarshipBookingStore');
$routes->get('booking/success/(:num)', 'Home::scholarshipBookingSuccess/$1');
$routes->get('booking/(:segment)', 'Home::scholarshipBooking/$1');
$routes->get('migrate', 'Home::migrate');
$routes->get('staff', 'Staff::index');
$routes->get('staff/profile', 'Staff::profile');
$routes->post('staff/profile/save', 'Staff::profileSave');
$routes->get('staff/notifications', 'Staff::notifications');
$routes->get('staff/attendance', 'Attendance::index');
$routes->post('staff/attendance/submit', 'Attendance::submit');
$routes->get('staff/attendance-admin', 'StaffAttendance::index');
$routes->get('staff/attendance-admin/upload', 'StaffAttendance::upload');
$routes->get('staff/attendance-admin/report', 'StaffAttendance::report'); // Hub page
$routes->get('staff/attendance-admin/report/annual', 'StaffAttendance::annualReport'); // Annual logic
$routes->get('staff/attendance-admin/report/annual/export', 'StaffAttendance::exportAnnualExcel'); 
$routes->get('staff/attendance-admin/report/monthly', 'StaffAttendance::monthlyReport'); // Monthly logic placeholder
$routes->get('staff/attendance-admin/report/monthly/export', 'StaffAttendance::exportMonthlyExcel');
$routes->post('staff/attendance-admin/process', 'StaffAttendance::process');
$routes->post('staff/attendance-admin/update-note', 'StaffAttendance::updateNote');
$routes->get('staff/attendance-admin/users', 'StaffAttendance::users');
$routes->post('staff/attendance-admin/save-mapping', 'StaffAttendance::saveUserMapping');
$routes->post('staff/attendance-admin/save-manual', 'StaffAttendance::saveManual');

// Leave System
$routes->get('staff/leave', 'Leave::index');
$routes->get('staff/leave/admin', 'Leave::adminIndex');
$routes->get('staff/leave/create', 'Leave::create');
$routes->post('staff/leave/store', 'Leave::store');
$routes->post('staff/leave/update-status', 'Leave::updateStatus');
$routes->get('staff/leave/get-last/(:num)', 'Leave::getLastLeave/$1');
$routes->get('staff/leave/export/(:num)', 'Leave::exportDocs/$1');

// Admin News Management
$routes->get('staff/news', 'Staff::news');
$routes->get('staff/news/create', 'Staff::newsCreate');
$routes->post('staff/news/store', 'Staff::newsStore');
$routes->get('staff/news/edit/(:num)', 'Staff::newsEdit/$1');
$routes->post('staff/news/update/(:num)', 'Staff::newsUpdate/$1');
$routes->get('staff/news/delete/(:num)', 'Staff::newsDelete/$1');
$routes->get('staff/news/deleteImage/(:num)', 'Staff::newsDeleteImage/$1');
$routes->post('staff/news/uploadChunk', 'Staff::uploadChunk');
$routes->get('staff/news/uploadChunk', 'Staff::uploadChunk');

// Admin Scholarship Management
$routes->get('staff/scholarships', 'Staff::scholarships');
$routes->get('staff/scholarship-bookings', 'Staff::scholarshipBookingIndex');
$routes->get('staff/scholarship/create', 'Staff::scholarshipCreate');
$routes->post('staff/scholarship/store', 'Staff::scholarshipStore');
$routes->get('staff/scholarship/edit/(:num)', 'Staff::scholarshipEdit/$1');
$routes->post('staff/scholarship/update/(:num)', 'Staff::scholarshipUpdate/$1');
$routes->get('staff/scholarship/delete/(:num)', 'Staff::scholarshipDelete/$1');

// Admin Scholarship Booking/Slot Management
$routes->get('staff/scholarship/(:num)/slots', 'Staff::scholarshipSlots/$1');
$routes->post('staff/scholarship/(:num)/slots/generate', 'Staff::scholarshipSlotGenerate/$1');
$routes->get('staff/scholarship/slot/toggle/(:num)', 'Staff::scholarshipSlotToggle/$1');
$routes->get('staff/scholarship/(:num)/slots/delete-day', 'Staff::scholarshipSlotDeleteDay/$1');
$routes->get('staff/scholarship/(:num)/bookings', 'Staff::scholarshipBookings/$1');
$routes->get('staff/scholarship/booking/status/(:num)', 'Staff::scholarshipBookingStatus/$1');
$routes->post('staff/scholarship/update-grades', 'Staff::scholarshipUpdateGrades');

// Admin Personnel Management
$routes->get('staff/personnel', 'Staff::personnel');
$routes->post('staff/personnel/save', 'Staff::personnelSave');
$routes->post('staff/personnel/reorder', 'Staff::personnelReorder');
$routes->get('staff/personnel/delete/(:num)', 'Staff::personnelDelete/$1');

// Admin Position Management
$routes->get('admin/position', 'Position::index');
$routes->post('admin/position/store', 'Position::store');
$routes->post('admin/position/update/(:num)', 'Position::update/$1');
$routes->get('admin/position/delete/(:num)', 'Position::delete/$1');
// Authentication
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/doLogin', 'Auth::doLogin');
$routes->get('auth/select', 'Auth::select');
$routes->get('auth/logout', 'Auth::logout');
$routes->post('auth/googleLogin', 'Auth::googleLogin');

// Super Admin Panel (Nested in Staff)
$routes->get('staff/admin-summary', 'Admin::index');
$routes->get('staff/permissions', 'Admin::permissions');
$routes->post('staff/permissionsUpdate', 'Admin::permissionsUpdate');
$routes->get('staff/settings', 'Admin::settings');
$routes->post('staff/settingsUpdate', 'Admin::settingsUpdate');
$routes->get('staff/exportExcel', 'Admin::exportExcel');

// IT Support Standalone Module (อิงจาก SKJ Work Journey)
// Public routes (No login required)
$routes->get('itsupport', 'ITSupport::index');
$routes->get('itsupport/logs', 'ITSupport::index');
$routes->get('itsupport/view/(:num)', 'ITSupport::view/$1');

// Protected routes (Require login)
$routes->group('itsupport', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'ITSupport::stats');
    $routes->get('create', 'ITSupport::create');
    $routes->post('store', 'ITSupport::store');
    $routes->get('edit/(:num)', 'ITSupport::edit/$1');
    $routes->post('update/(:num)', 'ITSupport::update/$1');
    $routes->get('delete/(:num)', 'ITSupport::delete/$1');
    $routes->get('print/(:num)', 'ITSupport::printJob/$1');
    $routes->get('export', 'ITSupport::exportExcel');
    $routes->get('report_print', 'ITSupport::printReport');
    $routes->post('upload_chunk', 'ITSupport::uploadChunk');
});

// Science Week (ระบบวันสัปดาห์วิทยาศาสตร์)
// Public routes
$routes->get('science-week', 'ScienceWeek::index');
$routes->get('science-week/register', 'ScienceWeek::register');
$routes->get('science-week/register/form', 'ScienceWeek::registerForm');
$routes->post('science-week/register/store', 'ScienceWeek::store');
$routes->get('science-week/success/(:segment)', 'ScienceWeek::success/$1');
$routes->get('science-week/approved-list', 'ScienceWeek::publicApprovedList');
$routes->get('science-week/check-status', 'ScienceWeek::publicCheckStatus');
$routes->get('science-week/results', 'ScienceWeek::publicResults');
$routes->get('science-week/evaluation', 'ScienceWeek::publicEvaluation');
$routes->post('science-week/evaluation/store', 'ScienceWeek::storeEvaluation');
$routes->get('science-week/evaluation/claim/(:segment)', 'ScienceWeek::claimCertificateForm/$1');
$routes->post('science-week/evaluation/claim/store/(:segment)', 'ScienceWeek::storeClaimCertificate/$1');
$routes->get('science-week/certificate/download/(:segment)/(:segment)', 'ScienceWeek::downloadCertificate/$1/$2');
$routes->get('science-week/certificate/view-all/(:segment)/(:segment)', 'ScienceWeek::viewAllCertificates/$1/$2');
$routes->get('science-week/certificate/search-staff', 'ScienceWeek::searchStudentStaff');


// Staff/Admin routes
$routes->group('science-week/staff', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ScienceWeek::adminIndex');
    
    // Announcements
    $routes->get('announcements', 'ScienceWeek::adminAnnouncements');
    $routes->post('announcements/store', 'ScienceWeek::adminAnnouncementStore');
    $routes->post('announcements/delete/(:num)', 'ScienceWeek::adminAnnouncementDelete/$1');

    // Check-in System
    $routes->get('checkin/(:segment)', 'ScienceWeek::checkinView/$1');
    $routes->post('checkin/process/(:segment)', 'ScienceWeek::checkinProcess/$1');

    $routes->get('ranking', 'ScienceWeek::adminRanking');
    $routes->get('edit/(:num)', 'ScienceWeek::adminEdit/$1');
    $routes->post('update/(:num)', 'ScienceWeek::adminUpdate/$1');
    $routes->post('update-status/(:num)', 'ScienceWeek::updateStatus/$1');
    $routes->post('update-rank/(:num)', 'ScienceWeek::updateRank/$1');
    $routes->match(['get', 'post'], 'toggle-publish-results', 'ScienceWeek::togglePublishResults');
    $routes->match(['get', 'post'], 'toggle-publish-comp/(:num)', 'ScienceWeek::toggleCompPublish/$1');
    $routes->match(['get', 'post'], 'toggle-publish-level', 'ScienceWeek::toggleLevelPublish');
    $routes->get('export', 'ScienceWeek::export');
    $routes->get('export-ranking', 'ScienceWeek::exportRanking');

    // Certificates Management
    $routes->get('certificates', 'ScienceWeek::adminCertificates');
    $routes->post('certificates/save', 'ScienceWeek::saveCertConfig');
    $routes->post('certificates/upload-chunk', 'ScienceWeek::uploadCertChunk');

    // Science Week Users/Staff Management
    $routes->get('users', 'ScienceWeek::usersIndex');
    $routes->post('users/store', 'ScienceWeek::usersStore');
    $routes->post('users/update/(:num)', 'ScienceWeek::usersUpdate/$1');
    $routes->get('users/delete/(:num)', 'ScienceWeek::usersDelete/$1');

    // Competitions CRUD
    $routes->get('competitions', 'ScienceWeek::compIndex');
    $routes->get('competitions/create', 'ScienceWeek::compCreate');
    $routes->post('competitions/store', 'ScienceWeek::compStore');
    $routes->get('competitions/edit/(:num)', 'ScienceWeek::compEdit/$1');
    $routes->post('competitions/update/(:num)', 'ScienceWeek::compUpdate/$1');
    $routes->get('competitions/delete/(:num)', 'ScienceWeek::compDelete/$1');

    // Settings (Countdown Date)
    $routes->get('settings', 'ScienceWeek::adminSettings');
    $routes->post('settings/save', 'ScienceWeek::settingsSave');

    // Schedules CRUD
    $routes->get('schedules', 'ScienceWeek::schIndex');
    $routes->get('schedules/create', 'ScienceWeek::schCreate');
    $routes->post('schedules/store', 'ScienceWeek::schStore');
    $routes->get('schedules/edit/(:num)', 'ScienceWeek::schEdit/$1');
    $routes->post('schedules/update/(:num)', 'ScienceWeek::schUpdate/$1');
    $routes->get('schedules/delete/(:num)', 'ScienceWeek::schDelete/$1');

    // Evaluations CRUD
    $routes->get('evaluations', 'ScienceWeek::evalIndex');
    $routes->get('evaluations/create', 'ScienceWeek::evalCreate');
    $routes->post('evaluations/store', 'ScienceWeek::evalStore');
    $routes->get('evaluations/edit/(:num)', 'ScienceWeek::evalEdit/$1');
    $routes->post('evaluations/update/(:num)', 'ScienceWeek::evalUpdate/$1');
    $routes->get('evaluations/delete/(:num)', 'ScienceWeek::evalDelete/$1');

    // Student Staff CRUD
    $routes->get('student-staff/print', 'ScienceWeek::studentStaffPrint');
    $routes->get('student-staff/export', 'ScienceWeek::studentStaffExport');
    $routes->get('student-staff', 'ScienceWeek::studentStaffIndex');
    $routes->post('student-staff/store', 'ScienceWeek::studentStaffStore');
    $routes->post('student-staff/update/(:num)', 'ScienceWeek::studentStaffUpdate/$1');
    $routes->get('student-staff/delete/(:num)', 'ScienceWeek::studentStaffDelete/$1');
});

// ================================================================
// 📝 FORMS & E-CERTIFICATE MODULE (ระบบแบบสอบถาม & เกียรติบัตร)
// ================================================================
// Public Routes
$routes->get('forms', 'Forms\FormPublicController::index');
$routes->get('forms/view/(:segment)', 'Forms\FormPublicController::view/$1');
$routes->post('forms/submit/(:segment)', 'Forms\FormPublicController::submit/$1');
$routes->get('forms/success/(:segment)', 'Forms\FormPublicController::success/$1');
$routes->post('forms/claim-certificate/(:segment)', 'Forms\FormPublicController::claimCertificate/$1');
$routes->get('forms/certificate/(:segment)', 'Forms\FormPublicController::downloadCert/$1');

// Admin / Staff Routes
$routes->group('staff/forms', function($routes) {
    $routes->get('/', 'Forms\FormAdminController::index');
    $routes->post('store', 'Forms\FormAdminController::store');
    $routes->get('builder/(:num)', 'Forms\FormAdminController::builder/$1');
    $routes->get('edit/(:num)', 'Forms\FormAdminController::edit/$1');
    $routes->get('certificate/(:num)', 'Forms\FormAdminController::certificate/$1');
    $routes->post('save-general/(:num)', 'Forms\FormAdminController::saveGeneralSettings/$1');
    $routes->post('save-cert-settings/(:num)', 'Forms\FormAdminController::saveCertSettings/$1');
    $routes->post('save-settings/(:num)', 'Forms\FormAdminController::saveGeneralSettings/$1');
    $routes->post('save-fields/(:num)', 'Forms\FormAdminController::saveFields/$1');
    $routes->post('upload-chunk', 'Forms\FormAdminController::uploadCertChunk');
    $routes->get('delete/(:num)', 'Forms\FormAdminController::delete/$1');
    $routes->get('responses/(:num)', 'Forms\FormAdminController::responses/$1');
    $routes->get('clear-responses/(:num)', 'Forms\FormAdminController::clearResponses/$1');
    $routes->match(['get', 'post'], 'toggle-status/(:num)', 'Forms\FormAdminController::toggleStatus/$1');
    $routes->match(['get', 'post'], 'toggle-share/(:num)', 'Forms\FormAdminController::toggleShare/$1');
    $routes->get('get-permissions/(:num)', 'Forms\FormAdminController::getPermissions/$1');
    $routes->post('save-permissions/(:num)', 'Forms\FormAdminController::savePermissions/$1');
});

// ================================================================
// 🏆 SPORTS CUP MODULE (ระบบจัดการแข่งขันกีฬา อบจ.คัพ & เกียรติบัตร)
// ================================================================
// Public Routes
$routes->group('sports', function($routes) {
    $routes->get('/', 'Sports\SportsPublicController::index');
    $routes->get('register/(:num)', 'Sports\SportsPublicController::register/$1');
    $routes->post('register/submit', 'Sports\SportsPublicController::submit');
    $routes->get('success/(:segment)', 'Sports\SportsPublicController::success/$1');
    $routes->get('status', 'Sports\SportsPublicController::status');
    $routes->post('status/search', 'Sports\SportsPublicController::searchStatus');
    $routes->get('print-reg/(:segment)', 'Sports\SportsPublicController::printRegistration/$1');
    $routes->get('results', 'Sports\SportsPublicController::results');
    $routes->get('certificate', 'Sports\SportsPublicController::certificate');
    $routes->post('certificate/search', 'Sports\SportsPublicController::searchCertificate');
    $routes->get('certificate/download/(:segment)', 'Sports\SportsPublicController::downloadCert/$1');
});

// Admin / Staff Routes
$routes->group('staff/sports', function($routes) {
    $routes->get('/', 'Sports\SportsAdminController::index');
    
    // Categories CRUD
    $routes->get('categories', 'Sports\SportsAdminController::categories');
    $routes->post('categories/store', 'Sports\SportsAdminController::categoryStore');
    $routes->post('categories/update/(:num)', 'Sports\SportsAdminController::categoryUpdate/$1');
    $routes->get('categories/delete/(:num)', 'Sports\SportsAdminController::categoryDelete/$1');
    $routes->post('categories/toggle-status/(:num)', 'Sports\SportsAdminController::categoryToggleStatus/$1');
    
    // Teams Management & Verification
    $routes->get('teams', 'Sports\SportsAdminController::teams');
    $routes->get('teams/detail/(:num)', 'Sports\SportsAdminController::teamDetail/$1');
    $routes->post('teams/update-status/(:num)', 'Sports\SportsAdminController::teamUpdateStatus/$1');
    $routes->get('teams/edit/(:num)', 'Sports\SportsAdminController::teamEdit/$1');
    $routes->post('teams/update/(:num)', 'Sports\SportsAdminController::teamUpdate/$1');
    $routes->get('teams/delete/(:num)', 'Sports\SportsAdminController::teamDelete/$1');
    $routes->get('teams/match-sheet/(:num)', 'Sports\SportsAdminController::matchSheet/$1');
    $routes->get('export-excel', 'Sports\SportsAdminController::exportExcel');
    
    // Results & Awards
    $routes->get('results', 'Sports\SportsAdminController::results');
    $routes->post('results/save-team-award', 'Sports\SportsAdminController::saveTeamAward');
    $routes->post('results/save-member-award', 'Sports\SportsAdminController::saveMemberAward');
    
    // Certificates Management & Designer
    $routes->get('certificates', 'Sports\SportsAdminController::certificates');
    $routes->post('certificates/create', 'Sports\SportsAdminController::certCreate');
    $routes->get('certificates/design/(:num)', 'Sports\SportsAdminController::certDesign/$1');
    $routes->post('certificates/save-design/(:num)', 'Sports\SportsAdminController::saveCertDesign/$1');
    $routes->get('certificates/demo/(:num)', 'Sports\SportsAdminController::certDemo/$1');
    $routes->post('certificates/upload-template', 'Sports\SportsAdminController::uploadCertTemplate');
    $routes->post('certificates/generate-batch', 'Sports\SportsAdminController::generateBatch');
});



