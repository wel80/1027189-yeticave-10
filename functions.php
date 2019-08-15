<?php

require_once('helpers.php');

function price_format($cost) {
    $cost_ceil = ceil($cost);
    if ($cost_ceil >= 1000) {
        $cost_format = number_format($cost_ceil, 0, ',', ' ');
    } else {
        $cost_format = $cost_ceil;
    }
    return ($cost_format . ' <b class="rub">р</b>');
};

function rest_time ($date_end) {
    date_default_timezone_set('Asia/Novosibirsk');
    $date_end_unix = strtotime($date_end);
    $date_current_unix = time();
    $period_unix = $date_end_unix - $date_current_unix;
    $period_hour = floor($period_unix / 3600);
    $period_min = floor(($period_unix - $period_hour * 3600) / 60);
    $time_expiry = [
        str_pad($period_hour, 2, "0", STR_PAD_LEFT),
        str_pad($period_min, 2, "0", STR_PAD_LEFT)
    ];
    return $time_expiry;
}