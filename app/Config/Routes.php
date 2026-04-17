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
