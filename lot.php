<?php
require_once('init.php');

if (empty($_GET['id'])) {
  exit(http_response_code(404));
};

$id_get = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if (!$id_get) {
  exit(http_response_code(404));
}

$id = intval($id_get);

$query_lot = 'SELECT name_lot AS "name", name_cat AS "category", description_lot, author_id, COUNT(id_rate) AS "count_rate",
initial_price AS "price", initial_price + step_rate AS "first_rate", MAX(bet_amount) AS "price_rate", 
MAX(bet_amount) + step_rate AS "next_rate", image_lot AS "image", completion_date AS "date_expiry",
TIMESTAMPDIFF(MINUTE, NOW(), completion_date) AS "completion_period"
FROM lot
LEFT JOIN category ON cat_id = id_cat
LEFT JOIN rate ON id_lot = lot_id
WHERE id_lot = ' . $id;

$query_rate_list = 'SELECT name_user, bet_amount, participant_id,
DATE_FORMAT(date_rate, "%d.%m.%y") AS "day_month_year",
DATE_FORMAT(date_rate, "%H:%i") AS "hour_min",
DATEDIFF(NOW(), date_rate) AS "period_day",
TIMESTAMPDIFF(MINUTE, date_rate, NOW()) AS "period_min"
FROM rate
LEFT JOIN user ON participant_id = id_user
WHERE lot_id = ' . $id . '
ORDER BY bet_amount DESC';


mysqli_query($link, "START TRANSACTION");
$result_lot = mysqli_query($link, $query_lot);
$result_rate_list = mysqli_query($link, $query_rate_list);

if ($result_lot === false || $result_rate_list === false) {
  mysqli_query($link, "ROLLBACK");
  include_template_error('Ошибка запроса на получение информации из базы данных');
}

mysqli_query($link, "COMMIT");
$is_lot = mysqli_fetch_assoc($result_lot);
$rate_list = mysqli_fetch_all($result_rate_list, MYSQLI_ASSOC);

if (!$is_lot) {
  exit(http_response_code(404));
} elseif ($rate_list && $is_lot['count_rate'] && $is_lot['price_rate']) {
  $rate_list_content = include_template('lot-rate-list.php', ['rate_list' => $rate_list, 'count_rate' => $is_lot['count_rate']]);
  $is_lot['current_price'] = $is_lot['price_rate'];
  $is_lot['min_rate'] = $is_lot['next_rate'];
  $user_id_max_rate = $rate_list[0]['participant_id'];
} else {
  $rate_list_content = '';
  $is_lot['current_price'] = $is_lot['price'];
  $is_lot['min_rate'] = $is_lot['first_rate'];
  $user_id_max_rate = 0;
}

if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] === 'POST' && strtotime($is_lot['date_expiry']) > time() && 
  $_SESSION['user']['id_user'] != $is_lot['author_id'] && $_SESSION['user']['id_user'] != $user_id_max_rate) {

  if (isset($_POST['cost']) && !empty($_POST['cost'])) {
    $new_rate_filter = filter_var($_POST['cost'], FILTER_VALIDATE_INT);
    $new_rate = intval($new_rate_filter);
    if ($new_rate < 1) {
      $error = 'В этом поле надо указать целое положительное число.';
    } elseif ($new_rate < $is_lot['min_rate']) {
      $error = 'Предложенная ставка должна быть больше или равна минимальной.';
    } else {
      $insert_new_rate = 'INSERT INTO rate (bet_amount, participant_id, lot_id) VALUES (?, ?, ?)';
      $prepared_memo = db_get_prepare_stmt($link, $insert_new_rate, [$new_rate, $_SESSION['user']['id_user'], $id]);
      $result_insert_rate = mysqli_stmt_execute($prepared_memo);

      if ($result_insert_rate) {
        header("Location: lot.php?id=$id");
      } else {
        include_template_error('При добавлении ставки возникла ошибка в базе данных.');
      }
    }
  } else {
    $error = 'Это поле надо заполнить.';
  }

  $right_content = include_template('lot-rate.php', [
    'is_lot' => $is_lot,
    'error' => $error,
    'id' => $id
  ]);

} elseif (isset($_SESSION['user']) && strtotime($is_lot['date_expiry']) > time() && $_SESSION['user']['id_user'] != $is_lot['author_id'] 
  && $_SESSION['user']['id_user'] != $user_id_max_rate) {
  $right_content = include_template('lot-rate.php', [
    'is_lot' => $is_lot,
    'error' => '',
    'id' => $id
  ]);

} else {
  $right_content = include_template('lot-price.php', [
    'is_lot' => $is_lot,
    'session_user_id' => 0
  ]);
}

$main_content = include_template('main-lot.php', [
  'category_list' => $all_category,
  'is_lot' => $is_lot,
  'right_content' => $right_content,
  'rate_list_content' => $rate_list_content
]);

$layout_content = include_template('layout.php', [
  'main_content' => $main_content,
  'category_list' => $all_category,
  'title' => $is_lot['name']
]);

print($layout_content);
