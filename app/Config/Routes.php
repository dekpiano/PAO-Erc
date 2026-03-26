<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('staff', 'Staff::index');
$routes->get('staff/attendance', 'Attendance::index');
$routes->post('staff/attendance/submit', 'Attendance::submit');

// Authentication
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/doLogin', 'Auth::doLogin');
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
