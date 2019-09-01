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
};

function include_template_error($customer, $auth_rand) {
    $layout_content = include_template('error.php', [
        'main_content' => 'Ошибка запроса на получение информации из базы данных',
        'user_name' => $customer,
        'title' => 'Ошибка',
        'is_auth' => $auth_rand
    ]);
    exit($layout_content);
};

function getPostVal($name) {
    return $_POST[$name] ?? ""; 
};

function validateCategory($name, $allowed_list) {
    $category_name = $_POST[$name];

    if (!in_array($category_name, $allowed_list)) {
        return "Указана несуществующая категория";
    };
    return null;
};

function validateLength($name, $min, $max) {
    $len = strlen($_POST[$name]);

    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    };
    return null;
};

function validateNumber($name) {
    $number = filter_var($_POST[$name], FILTER_VALIDATE_INT);
    if ($number < 1) {
        return "Укажите целое число больше нуля";
    };
    return null;
};

function validateDate($name) {
    $date_format = date_create_from_format('Y-m-d', $_POST[$name]);
    $date_end_unix = strtotime($_POST[$name]);
    $date_current_unix = time();
    $period_unix = $date_end_unix - $date_current_unix;
    if ($date_format && $period_unix > 86400) {
        return null;
    };
    return "Введите дату в формате ГГГГ-ММ-ДД";
};