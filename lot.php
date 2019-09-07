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

if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

  if (empty($_POST['cost'])) {
    $error = 'Это поле надо заполнить.';
  } else {
    if ($is_lot['price_rate']) {
      $rate = $is_lot['next_rate'];
    } else {
      $rate = $is_lot['first_rate'];
    };

    $new_rate_filter = filter_var($_POST['cost'], FILTER_VALIDATE_INT);
    $new_rate = intval($new_rate_filter);
    if ($new_rate < 1) {
      $error = 'В этом поле надо указать целое положительное число.';
    } elseif ($new_rate < $rate) {
      $error = 'Предложенная ставка должна быть больше минимальной.';
    } else {
      $insert_new_rate = 'INSERT INTO rate (bet_amount, participant_id, lot_id) VALUES (?, ?, ?)';
      $prepared_memo = db_get_prepare_stmt($link, $insert_new_rate, [$new_rate, $_SESSION['user']['id_user'], $id]);
      $result_insert_rate = mysqli_stmt_execute($prepared_memo);

      if ($result_insert_rate) {
        header("Location: lot.php?id=$id");
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

} elseif (isset($_SESSION['user'])) {
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
