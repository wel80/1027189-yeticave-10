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
}

function price_format_rate($cost) {
    $cost_ceil = ceil($cost);
    if ($cost_ceil >= 1000) {
        $cost_format = number_format($cost_ceil, 0, ',', ' ');
    } else {
        $cost_format = $cost_ceil;
    }
    return ($cost_format . ' <span>р</span>');
}

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

function include_template_error($message) {
    $layout_content = include_template('error.php', [
        'main_content' => $message,
        'title' => 'Ошибка'
    ]);
    exit($layout_content);
}

function getPostVal($name) {
    return $_POST[$name] ?? ""; 
}

function validateCategory($name, $allowed_list) {
    $category_name = $_POST[$name];

    if (!in_array($category_name, $allowed_list)) {
        return "Указана несуществующая категория";
    }
    return null;
}

function validateLength($name, $min, $max) {
    $len = strlen($_POST[$name]);

    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }
    return null;
}

function validateNumber($name) {
    $number = filter_var($_POST[$name], FILTER_VALIDATE_INT);
    if ($number < 1) {
        return "Укажите целое число больше нуля";
    }
    return null;
}

function validateDate($name) {
    $date_format = is_date_valid($_POST[$name]);
    $date_end_unix = strtotime($_POST[$name]);
    $date_current_unix = time();
    $period_unix = $date_end_unix - $date_current_unix;
    if ($date_format && $period_unix > 86400) {
        return null;
    }
    return "Введите дату в формате ГГГГ-ММ-ДД.<br>До окончания торгов должно оставаться не менее суток.";
}

function validateEmail($name) {
    $email_correct = filter_var($_POST[$name], FILTER_VALIDATE_EMAIL);
    if ($email_correct) {
        return null;
    }
    return "Укажите корректный адрес электронной почты";
}

function passedTime($period_day, $period_min, $day_month_year, $hour_min) {
    if ($period_day > 1) {
        return ($day_month_year . ' в ' . $hour_min);
    } elseif ($period_day > 0) {
        return ('Вчера в ' . $hour_min);
    } else {
        $hour_int = ($period_min - $period_min % 60) / 60;
        $min_int = $period_min % 60;
        $hour_name = get_noun_plural_form($hour_int, 'час', 'часа', 'часов');
        $min_name = get_noun_plural_form($min_int, 'минуту', 'минуты', 'минут');
        if ($hour_int > 0) {
            return ($hour_int . ' ' . $hour_name . ' назад');
        } elseif ($min_int > 0) {
            return ($min_int . ' ' . $min_name . ' назад');
        } else {
            return ('Только что');
        }
    }
}

function rates_item($period, $id) {
    if ($period <= 0 && $id == $_SESSION['user']['id_user']) {
        return ('rates__item--win');
    } elseif ($period < 0) {
        return ('rates__item--end');
    } else {
        return ('');
    }
}

function rates_contact($period, $id, $con) {
    if ($period <= 0 && $id == $_SESSION['user']['id_user']) {
        return $con;
    } else {
        return ('');
    }
}

function rates_timer_class($period, $id) {
    if ($period <= 0 && $id == $_SESSION['user']['id_user']) {
        return ('timer--win');
    }

    if ($period <= 0) {
        return ('timer--end');
    }

    if ($period > 0 && $period <= 60) {
        return ('timer--finishing');
    }

    return ('');
}

function rates_timer_content($period, $id, $date) {
    if ($period <= 0 && $id == $_SESSION['user']['id_user']) {
        return ('Ставка выиграла');
    } elseif ($period <= 0) {
        return ('Торги окончены');
    } else {
        return (rest_time($date)[0] . ' : ' . rest_time($date)[1]);
    }
}

function db_find_all($connect, $query) {
    $result = mysqli_query($connect, $query);
    if ($result === false) {
        include_template_error('Ошибка запроса на получение информации из базы данных');
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}