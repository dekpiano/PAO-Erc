<?php

if (! function_exists('thai_date')) {
    function thai_date($datetime, $full = false)
    {
        $thai_months = [
            1  => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5  => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9  => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม',
        ];
        $thai_months_short = [
            1  => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
            5  => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
            9  => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.',
        ];

        $time = strtotime($datetime);
        $d = date('j', $time);
        $m = date('n', $time);
        $y = date('Y', $time) + 543; // Buddhist Era

        $month_text = $full ? $thai_months[$m] : $thai_months_short[$m];
        
        return "$d $month_text $y";
    }
}
