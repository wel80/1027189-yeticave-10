<?php

require_once('config.php');
require_once('functions.php');
require_once('data.php');

$link = mysqli_init(); 
mysqli_options($link, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1); 
mysqli_real_connect($link, "localhost", "root", "", "yeticave_wel80");

if (!$link) {
    print("Невозможно подключиться к базе данных. Ошибка: " . mysqli_connect_error());
} else {
    mysqli_set_charset($link, "utf8");

    $query_new_lot = 'SELECT name_lot AS "name", name_cat AS "category", 
    initial_price AS "price", image_lot AS "image", completion_date AS "date_expiry"
    FROM lot
    LEFT JOIN category ON cat_id = id_cat
    WHERE completion_date > NOW()
    ORDER BY date_lot DESC';

    $result_new_lot = mysqli_query($link, $query_new_lot);
    if (!$result_new_lot) {
        print("Произошла ошибка при выполнении запроса");
    } else {
        $new_lot = mysqli_fetch_all($result_new_lot, MYSQLI_ASSOC);
    }

    $query_category = 'SELECT name_cat, code_cat FROM category';
    $result_category = mysqli_query($link, $query_category);
    if (!$result_category) {
        print("Произошла ошибка при выполнении запроса");
    } else {
        $all_category = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
    }
};

$main_content = include_template('main.php', [
    'category_list' => $all_category,
    'advertising_list' => $new_lot
]);

$layout_content = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $user_name,
    'title' => 'Главная',
    'category_list' => $all_category,
    'is_auth' => $is_auth
]);

print($layout_content);
