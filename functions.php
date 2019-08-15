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