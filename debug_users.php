<?php
define('FCPATH', __DIR__ . '/public/');
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();
$query = $db->query("SELECT u_fullname, u_position, u_role, u_sort, u_division FROM Tb_Users WHERE u_fullname LIKE '%วชิรวิทย์%' OR u_fullname LIKE '%เที่ยว กลางคืน%'");
print_r($query->getResultArray());
