<?php
require_once('init.php');

if (isset($_SESSION['user'])) {
    $query_my_bet_list = 'SELECT image_lot, name_lot, id_lot, name_cat, completion_date, bet_amount, contact, winner_id,
    DATE_FORMAT(date_rate, "%d.%m.%y") AS "day_month_year",
    DATE_FORMAT(date_rate, "%H:%i") AS "hour_min",
    DATEDIFF(NOW(), date_rate) AS "period_day",
    TIMESTAMPDIFF(MINUTE, date_rate, NOW()) AS "period_min",
    TIMESTAMPDIFF(MINUTE, NOW(), completion_date) AS "completion_period"
    FROM lot
    LEFT JOIN category ON cat_id = id_cat
    LEFT JOIN user ON author_id = id_user
    LEFT JOIN rate ON id_lot = lot_id
    WHERE participant_id = ' . $_SESSION['user']['id_user'] . '
    ORDER BY date_rate DESC';

    $result_my_bet_list = mysqli_query($link, $query_my_bet_list);
    if ($result_my_bet_list === false) {
        include_template_error('Ошибка запроса на получение информации из базы данных');
    };
    $my_bet_list = mysqli_fetch_all($result_my_bet_list, MYSQLI_ASSOC);
    
    $main_content = include_template('main-bets.php', [
        'category_list' => $all_category,
        'my_bet_list' => $my_bet_list
    ]);
} else {
    exit(http_response_code(403));
};

$layout_content = include_template('layout.php', [
    'main_content' => $main_content,
    'category_list' => $all_category,
    'title' => 'Мои ставки'
]);

print($layout_content);
