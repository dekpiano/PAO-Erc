<?php
require 'vendor/autoload.php';
require 'system/Test/bootstrap.php';
$app = \Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();
$query = $db->query("SELECT u_fullname, u_position, u_role, u_sort, u_division FROM Tb_Users WHERE u_fullname IN ('ว่าที่ ร.ต.วชิรวิทย์ แกล้วการไถ', 'นายเที่ยว กลางคืน')");
print_r($query->getResultArray());
