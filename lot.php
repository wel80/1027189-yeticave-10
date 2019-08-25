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
};

mysqli_set_charset($link, "utf8");

if (!isset($_GET['id'])) {
  exit(http_response_code(404));
};

$query_lot = 'SELECT name_lot AS "name", name_cat AS "category", description_lot, step_rate, 
initial_price AS "price", initial_price + step_rate AS "first_rate", MAX(bet_amount) AS "price_rate", 
MAX(bet_amount) + step_rate AS "next_rate", image_lot AS "image", completion_date AS "date_expiry"
FROM lot
LEFT JOIN category ON cat_id = id_cat
LEFT JOIN rate ON id_lot = lot_id
WHERE id_lot = ' . $_GET['id'] . '
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
if(!$is_lot['name']) {
  exit(http_response_code(404));
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
};

$all_category = mysqli_fetch_all($result_category_list, MYSQLI_ASSOC);

$layout_content = include_template('layout-lot.php', [
  'user_name' => $user_name,
  'category_list' => $all_category,
  'is_auth' => $is_auth,
  'is_lot' => $is_lot
]);

print($layout_content);
