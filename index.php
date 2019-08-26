<?php

require_once('init.php');

$query_new_lot_list = 'SELECT id_lot, name_lot AS "name", name_cat AS "category", 
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
};

$new_lot_list = mysqli_fetch_all($result_new_lot_list, MYSQLI_ASSOC);

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
};

$all_category = mysqli_fetch_all($result_category_list, MYSQLI_ASSOC);

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
