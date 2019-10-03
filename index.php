<?php
require_once('init.php');
require_once('getwinner.php');

$query_new_lot_list = 'SELECT id_lot, name_lot AS "name", name_cat AS "category", 
initial_price AS "price", MAX(bet_amount) AS "price_rate", image_lot AS "image", completion_date AS "date_expiry"
FROM lot
LEFT JOIN category ON cat_id = id_cat
LEFT JOIN rate ON id_lot = lot_id
WHERE completion_date > NOW()
GROUP BY id_lot
ORDER BY date_lot DESC
LIMIT 9';
$new_lot_list = db_find_all($link, $query_new_lot_list);


$main_content = include_template('main.php', [
    'category_list' => $all_category,
    'advertising_list' => $new_lot_list
]);

$layout_content = include_template('layout.php', [
    'main_content' => $main_content,
    'category_list' => $all_category,
    'title' => 'Главная'
]);

print($layout_content);
