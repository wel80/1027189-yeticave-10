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


$query_lot = 'SELECT name_lot AS "name", name_cat AS "category", description_lot, author_id,
initial_price AS "price", initial_price + step_rate AS "first_rate", MAX(bet_amount) AS "price_rate", 
MAX(bet_amount) + step_rate AS "next_rate", image_lot AS "image", completion_date AS "date_expiry"
FROM lot
LEFT JOIN category ON cat_id = id_cat
LEFT JOIN rate ON id_lot = lot_id
WHERE id_lot = ' . $id;
$result_lot = mysqli_query($link, $query_lot);

if ($result_lot === false) {
  include_template_error('Ошибка запроса на получение информации из базы данных');
};

$is_lot = mysqli_fetch_assoc($result_lot);

if (!$is_lot) {
  exit(http_response_code(404));
};

if ($is_lot['price_rate']) {
  $is_lot['current_price'] = $is_lot['price_rate'];
  $is_lot['min_rate'] = $is_lot['next_rate'];
} else {
  $is_lot['current_price'] = $is_lot['price'];
  $is_lot['min_rate'] = $is_lot['first_rate'];
};

$query_user_id_max_rate = 'SELECT participant_id FROM rate WHERE lot_id= ' . $id . ' AND bet_amount = ' . $is_lot['current_price'];
$result_user_id_max_rate = mysqli_query($link, $query_user_id_max_rate);
if ($result_user_id_max_rate === false) {
  include_template_error('Ошибка запроса на получение информации из базы данных');
};
$user_id_max_rate = mysqli_fetch_assoc($result_user_id_max_rate);

if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] === 'POST' && strtotime($is_lot['date_expiry']) > time() && 
  $_SESSION['user']['id_user'] != $is_lot['author_id'] && $_SESSION['user']['id_user'] != $user_id_max_rate['participant_id']) {

  if (empty($_POST['cost'])) {
    $error = 'Это поле надо заполнить.';
  } else {
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
        header("Location: my-bets.php");
      } else {
        include_template_error('При добавлении ставки возникла ошибка в базе данных.');
      };
    };
  };

  $rate_content = include_template('lot-rate.php', [
    'is_lot' => $is_lot,
    'error' => $error,
    'id' => $id
  ]);

} elseif ((isset($_SESSION['user']) && strtotime($is_lot['date_expiry']) > time() && $_SESSION['user']['id_user'] != $is_lot['author_id'] 
  && $_SESSION['user']['id_user'] != $user_id_max_rate['participant_id'])) {
  $rate_content = include_template('lot-rate.php', [
    'is_lot' => $is_lot,
    'error' => '',
    'id' => $id
  ]);

} else {
  $rate_content = '';
};

$main_content = include_template('main-lot.php', [
  'category_list' => $all_category,
  'is_lot' => $is_lot,
  'rate_content' => $rate_content
]);

$layout_content = include_template('layout.php', [
  'main_content' => $main_content,
  'category_list' => $all_category,
  'title' => $is_lot['name']
]);

print($layout_content);
