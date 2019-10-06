<?php

require_once('helpers.php');

/**
 * Возвращает строку в виде цены разделенной на разряды с указанием валюты рубль
 * Присваивает класс "rub"
 * @param int $cost Цена в виде числа
 * 
 * @return string $cost_format Цена в виде строки 
 */
function price_format($cost) {
    $cost_ceil = ceil($cost);
    if ($cost_ceil >= 1000) {
        $cost_format = number_format($cost_ceil, 0, ',', ' ');
    } else {
        $cost_format = $cost_ceil;
    }
    return htmlspecialchars($cost_format) . ' <b class="rub">р</b>';
}

/**
 * Возвращает строку в виде цены разделенной на разряды с указанием валюты рубль
 * Класс "rub" не присваивается
 * @param int $cost Цена в виде числа
 * 
 * @return string $cost_format Цена в виде строки 
 */
function price_format_rate($cost) {
    $cost_ceil = ceil($cost);
    if ($cost_ceil >= 1000) {
        $cost_format = number_format($cost_ceil, 0, ',', ' ');
    } else {
        $cost_format = $cost_ceil;
    }
    return htmlspecialchars($cost_format) . ' <span>р</span>';
}


/**
 * Возвращает период времени между временем из будущего и текущим временем в формате "часы : минуты"
 * @param string $date_end - дата и время из будущего
 * 
 * @return string $time_expiry - период времени
 */
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


/**
 * Подключает шаблон показа ошибки, передает туда текст ошибки и возвращает итоговый HTML контент
 * @param string $message - Текст ошибки
 * 
 * @return string $layout_content - Итоговый HTML
 */
function include_template_error($message) {
    $layout_content = include_template('error.php', [
        'main_content' => $message,
        'category_list' => $all_category,
        'title' => 'Ошибка'
    ]);
    exit($layout_content);
}


/**
 * Возвращает данные из массива POST, проверенные на предмет опасных символов
 * или пустую строку, если данных нет
 * @param string $name - Ключ массива
 */
function getPostVal($name) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST[$name])) {
        return htmlspecialchars($_POST[$name]); 
    }
    return '';
}

/**
 * Валидация категории
 * Возвращает NULL, если указана существующая категория
 * В противном случае возвращает соощение об ошибке
 * @param string $name - ключ массива POST
 * @param array $allowed_list - массив с перечнем существующих категорий
 */
function validateCategory($name, $allowed_list) {
    if (isset($_POST[$name])) {
        $category_name = $_POST[$name];
    } else {
        $category_name = '';
    }

    if (!in_array($category_name, $allowed_list)) {
        return "Указана несуществующая категория";
    }
    return null;
}

/**
 * Валидация длины строки
 * Возвращает NULL, если длина строки соответствует указанным требованиям
 * В противном случае возвращает сообщение об ошибке
 * @param string $name - ключ массива POST
 * @param int $min минимальный размер строки
 * @param int $max максимальный размер строки
 */
function validateLength($name, $min, $max) {
    if (isset($_POST[$name])) {
        $len = strlen($_POST[$name]);
    } else {
        $len = 0;
    }

    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }
    return null;
}

/**
 * Валидация числа
 * Возвращает NULL, если число целое и больше нуля
 * В противном случае возвращает сообщение об ошибке
 * @param string $name - ключ массива POST
 */
function validateNumber($name) {
    if (isset($_POST[$name])) {
        $number = filter_var($_POST[$name], FILTER_VALIDATE_INT);
    } else {
        $number = 0;
    }

    if ($number < 1) {
        return "Укажите целое число больше нуля";
    }
    return null;
}

/**
 * Валидация даты
 * Возвращает NULL, если дата указана в формате ГГГГ-ММ-ДД и период времени 
 * между указанной датой и текущим временем больше, чем 24 часа
 * В противном случае возвращает сообщение об ошибке
 * @param string $name - ключ массива POST
 */
function validateDate($name) {
    if (isset($_POST[$name])) {
        $date_format = is_date_valid($_POST[$name]);
        $date_end_unix = strtotime($_POST[$name]);
    } else {
        $date_format = null;
        $date_end_unix = 0;
    }

    $date_current_unix = time();
    $period_unix = $date_end_unix - $date_current_unix;
    if ($date_format && $period_unix > 86400) {
        return null;
    }
    return "Введите дату в формате ГГГГ-ММ-ДД.<br>До окончания торгов должно оставаться не менее суток.";
}

/**
 * Валидация электронного адреса
 * Возвращает NULL, если электронный адрес указан в правильном формате
 * В противном случае возвращает сообщение об ошибке
 * @param string $name - ключ массива POST
 */
function validateEmail($name) {
    if (isset($_POST[$name])) {
        $email_correct = filter_var($_POST[$name], FILTER_VALIDATE_EMAIL);
    } else {
        $email_correct = null;
    }
    if ($email_correct) {
        return null;
    }
    return "Укажите корректный адрес электронной почты";
}

/**
 * Возвращает период времени в человекочитаемом виде
 * Например:
 * Вчера в 15:30
 * 2 часа назад
 * 15 минут назад
 * Только что
 * @param array $bet_content - Ассоциативный массив с данными по одной ставке
 */
function passedTime($bet_content) {
    if (isset($bet_content['period_day']) && isset($bet_content['day_month_year']) && isset($bet_content['hour_min']) && $bet_content['period_day'] > 1) {
        return (htmlspecialchars($bet_content['day_month_year']) . ' в ' . htmlspecialchars($bet_content['hour_min']));
    } elseif (isset($bet_content['period_day']) && isset($bet_content['hour_min']) && $bet_content['period_day'] > 0) {
        return ('Вчера в ' . htmlspecialchars($bet_content['hour_min']));
    }

    if (isset($bet_content['period_min'])) {
        $hour_int = ($bet_content['period_min'] - $bet_content['period_min'] % 60) / 60;
        $min_int = $bet_content['period_min'] % 60;
        $hour_name = get_noun_plural_form($hour_int, 'час', 'часа', 'часов');
        $min_name = get_noun_plural_form($min_int, 'минуту', 'минуты', 'минут');
    
        if ($hour_int > 0) {
            return (htmlspecialchars($hour_int) . ' ' . htmlspecialchars($hour_name) . ' назад');
        } elseif ($min_int > 0) {
            return (htmlspecialchars($min_int) . ' ' . htmlspecialchars($min_name) . ' назад');
        }
    }
    return ('Только что');
}

/**
 * Возвращает имя класса для строки таблцы показа ставок
 * @param array $bet_content - Ассоциативный массив с данными по одной ставке
 */
function rates_item($bet_content) {
    if (isset($bet_content['completion_period']) && $bet_content['completion_period'] < 0 && isset($bet_content['winner_id']) 
    && isset($_SESSION['user']['id_user']) && $bet_content['winner_id'] === $_SESSION['user']['id_user']) {
        return ('rates__item--win');
    } elseif (isset($bet_content['completion_period']) && $bet_content['completion_period'] <= 0) {
        return ('rates__item--end');
    }
    return ('');
    
}

/**
 * Возвращает контактные данные автора лота
 * @param array $bet_content - Ассоциативный массив с данными по одной ставке
 */
function rates_contact($bet_content) {
    if (isset($bet_content['completion_period']) && isset($bet_content['winner_id']) && isset($bet_content['contact']) && 
    isset($_SESSION['user']['id_user']) && $bet_content['completion_period'] < 0 && $bet_content['winner_id'] === $_SESSION['user']['id_user']) {
        return htmlspecialchars($bet_content['contact']);
    }
    return ('');
}

/**
 * Возвращает имя класса для блока показа остатка времени
 * @param array $bet_content - Ассоциативный массив с данными по одной ставке
 */
function rates_timer_class($bet_content) {
    if (isset($bet_content['completion_period']) && isset($bet_content['winner_id']) && isset($_SESSION['user']['id_user']) &&
    $bet_content['completion_period'] < 0 && $bet_content['winner_id'] === $_SESSION['user']['id_user']) {
        return ('timer--win');
    }

    if (isset($bet_content['completion_period']) && $bet_content['completion_period'] < 0) {
        return ('timer--end');
    }

    if (isset($bet_content['completion_period']) && $bet_content['completion_period'] > 0 && $bet_content['completion_period'] <= 60) {
        return ('timer--finishing');
    }

    return ('');
}

/**
 * Возвращает строку с данными для блока показа остатка времени до окончания торгов
 * @param array $bet_content - Ассоциативный массив с данными по одной ставке
 */
function rates_timer_content($bet_content) {
    if (isset($bet_content['completion_period']) && isset($bet_content['winner_id']) && isset($_SESSION['user']['id_user']) && 
    $bet_content['completion_period'] < 0 && $bet_content['winner_id'] === $_SESSION['user']['id_user']) {
        return 'Ставка выиграла';
    } elseif (isset($bet_content['completion_period']) && $bet_content['completion_period'] < 0) {
        return 'Торги окончены';
    }

    if (isset($bet_content['completion_date'])) {
        return htmlspecialchars(rest_time($bet_content['completion_date'])[0] . ' : ' . rest_time($bet_content['completion_date'])[1]);
    }
    return '';
}

/**
 * Возвращает массив с перечнем категорий лотов
 * @param $connect mysqli Ресурс соединения
 * @param $query - SQL запрос
 */
function db_find_all($connect, $query) {
    $result = mysqli_query($connect, $query);
    if ($result === false) {
        include_template_error('Ошибка запроса на получение информации из базы данных');
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}