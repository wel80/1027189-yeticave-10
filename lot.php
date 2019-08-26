<?php

require_once('init.php');

if (empty($_GET['id'])) {
  exit(http_response_code(404));
};

$id_get = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if (!$id_get) {
  exit(http_response_code(404));
};
$id = intval($id_get);

$query_lot = 'SELECT name_lot AS "name", name_cat AS "category", description_lot, 
initial_price AS "price", initial_price + step_rate AS "first_rate", MAX(bet_amount) AS "price_rate", 
MAX(bet_amount) + step_rate AS "next_rate", image_lot AS "image", completion_date AS "date_expiry"
FROM lot
LEFT JOIN category ON cat_id = id_cat
LEFT JOIN rate ON id_lot = lot_id
WHERE id_lot = ' . $id . '
GROUP BY id_lot';
$result_lot = mysqli_query($link, $query_lot);

if ($result_lot === false) {
  $layout_content = include_template('error.php', [
    'main_content' => 'Ошибка запроса на получение информации из базы данных',
    'user_name' => $user_name,
    'title' => 'Ошибка',
    'is_auth' => $is_auth
  ]);
  exit($layout_content);
};

$is_lot = mysqli_fetch_assoc($result_lot);
if (!$is_lot) {
  exit(http_response_code(404));
}

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

$main_content = include_template('main-lot.php', [
  'category_list' => $all_category,
  'is_lot' => $is_lot
]);

$layout_content = include_template('layout.php', [
  'main_content' => $main_content,
  'user_name' => $user_name,
  'title' => $is_lot['name'],
  'category_list' => $all_category,
  'is_auth' => $is_auth
]);;

print($layout_content);
