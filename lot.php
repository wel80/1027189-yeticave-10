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
  include_template_error('Ошибка запроса на получение информации из базы данных');
};

$is_lot = mysqli_fetch_assoc($result_lot);

if (!$is_lot) {
  exit(http_response_code(404));
};


$main_content = include_template('main-lot.php', [
  'category_list' => $all_category,
  'is_lot' => $is_lot
]);

$layout_content = include_template('layout.php', [
  'main_content' => $main_content,
  'category_list' => $all_category,
  'title' => $is_lot['name']
]);

print($layout_content);
