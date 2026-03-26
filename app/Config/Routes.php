<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('news', 'Home::newsAll');
$routes->get('news/(:segment)', 'Home::newsDetail/$1');
$routes->get('personnel', 'Home::personnel');
$routes->get('migrate', 'Home::migrate');
$routes->get('staff', 'Staff::index');
$routes->get('staff/attendance', 'Attendance::index');
$routes->post('staff/attendance/submit', 'Attendance::submit');

// Staff News Management
$routes->get('staff/news', 'Staff::news');
$routes->get('staff/news/create', 'Staff::newsCreate');
$routes->post('staff/news/store', 'Staff::newsStore');
$routes->get('staff/news/edit/(:num)', 'Staff::newsEdit/$1');
$routes->post('staff/news/update/(:num)', 'Staff::newsUpdate/$1');
$routes->get('staff/news/delete/(:num)', 'Staff::newsDelete/$1');
$routes->get('staff/news/deleteImage/(:num)', 'Staff::newsDeleteImage/$1');

// Staff Personnel Management
$routes->get('staff/personnel', 'Staff::personnel');
$routes->post('staff/personnel/save', 'Staff::personnelSave');
$routes->get('staff/personnel/delete/(:num)', 'Staff::personnelDelete/$1');


// Authentication
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/doLogin', 'Auth::doLogin');
$routes->get('auth/select', 'Auth::select');
$routes->get('auth/logout', 'Auth::logout');
$routes->post('auth/googleLogin', 'Auth::googleLogin');

// Admin Panel
$routes->get('admin', 'Admin::index');
$routes->get('admin/users', 'Admin::users');
$routes->post('admin/userSave', 'Admin::userSave');
$routes->get('admin/userDelete/(:num)', 'Admin::userDelete/$1');
$routes->get('admin/settings', 'Admin::settings');
$routes->post('admin/settingsUpdate', 'Admin::settingsUpdate');
$routes->get('admin/exportExcel', 'Admin::exportExcel');
