<?php

require_once('config.php');
require_once('functions.php');

ob_start();
$link = mysqli_connect ($db['host'], $db['user'], $db['password'], $db['database']);
$result_connect_buffer = ob_get_clean();

if (!$link) {
    $layout_content = include_template('error.php', [
        'main_content' => 'Ошибка: ' . $result_connect_buffer,
        'user_name' => $user_name,
        'title' => 'Ошибка',
        'is_auth' => $is_auth
    ]);
    exit($layout_content);

} else {
    mysqli_set_charset($link, "utf8");

    $query_new_lot_list = 'SELECT name_lot AS "name", name_cat AS "category", 
    initial_price AS "price", MAX(bet_amount) AS "price_rate", image_lot AS "image", completion_date AS "date_expiry"
    FROM lot
    LEFT JOIN category ON cat_id = id_cat
    LEFT JOIN rate ON id_lot = lot_id
    WHERE completion_date > NOW()
    GROUP BY id_lot
    ORDER BY date_lot DESC';

    $result_new_lot_list = mysqli_query($link, $query_new_lot_list);

    if ($result_new_lot_list === false) {
        $layout_content = include_template('error.php', [
            'main_content' => 'Ошибка запроса на получение информации из базы данных',
            'user_name' => $user_name,
            'title' => 'Ошибка',
            'is_auth' => $is_auth
        ]);
        exit($layout_content);

    } else {
        $new_lot_list = mysqli_fetch_all($result_new_lot_list, MYSQLI_ASSOC);
    };

    $query_category_list = 'SELECT name_cat, code_cat FROM category';

    $result_category_list = mysqli_query($link, $query_category_list);

    if ($result_category_list === false) {
        $layout_content = include_template('error.php', [
            'main_content' => 'Ошибка запроса на получение информации из базы данных',
            'user_name' => $user_name,
            'title' => 'Ошибка',
            'is_auth' => $is_auth
        ]);
        exit($layout_content);

    } else {
        $all_category = mysqli_fetch_all($result_category_list, MYSQLI_ASSOC);
    };

    $main_content = include_template('main.php', [
        'category_list' => $all_category,
        'advertising_list' => $new_lot_list
    ]);

    $layout_content = include_template('layout.php', [
        'main_content' => $main_content,
        'user_name' => $user_name,
        'title' => 'Главная',
        'category_list' => $all_category,
        'is_auth' => $is_auth
    ]);

    print($layout_content);
};
