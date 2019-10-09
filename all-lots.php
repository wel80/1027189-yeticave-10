<?php
require_once('init.php');

if (isset($_GET['cat'])) {
    $cat_number_validate = filter_var($_GET['cat'], FILTER_VALIDATE_INT);
    if (!$cat_number_validate) {
        exit(http_response_code(404));
    }
    $cat_number = intval($cat_number_validate);
} else {
    exit(http_response_code(404));
}

if (isset($_GET['page'])) {
    $page_number_validate = filter_var($_GET['page'], FILTER_VALIDATE_INT);
    if (!$page_number_validate) {
        exit(http_response_code(404));
    }
    $page_number = intval($page_number_validate);
} else {
    $page_number = 1;;
}
$offset = ($page_number - 1) * 9;

$query_count_lot = 'SELECT COUNT(id_lot) AS "quantity", name_cat 
FROM lot 
INNER JOIN category ON cat_id = id_cat
WHERE cat_id = ' . $cat_number . ' AND completion_date > NOW()';
$query_category_lot_list = 'SELECT image_lot, name_lot, name_cat, id_lot, initial_price, MAX(bet_amount) AS "rate_price", completion_date
FROM lot
LEFT JOIN category ON cat_id = id_cat
LEFT JOIN rate ON id_lot = lot_id
WHERE cat_id = ' . $cat_number . ' AND completion_date > NOW()
GROUP BY id_lot
ORDER BY date_lot DESC
LIMIT 9 OFFSET ' . $offset;

mysqli_query($link, "START TRANSACTION");
$result_count_lot = mysqli_query($link, $query_count_lot);
$result_category_lot_list = mysqli_query($link, $query_category_lot_list);
if ($result_count_lot === false || $result_category_lot_list === false) {
    mysqli_query($link, "ROLLBACK");
    include_template_error('Ошибка запроса на получение информации из базы данных');
}
mysqli_query($link, "COMMIT");
$count_lot = mysqli_fetch_assoc($result_count_lot);
$category_lot_list = mysqli_fetch_all($result_category_lot_list, MYSQLI_ASSOC);

if ($count_lot['quantity'] % 9 === 0) {
    $page_count = $count_lot['quantity'] / 9;
} else {
    $page_count = ($count_lot['quantity'] - $count_lot['quantity'] % 9) / 9 + 1;
}
$page_list = range(1, $page_count);
$not_lot = '<h2>В категории "' . htmlspecialchars($count_lot['name_cat']) . '" пока нет ни одного лота</h2>';

$main_content = include_template('main-all-lots.php', [
    'category_list' => $all_category,
    'category_lot_list' => $category_lot_list,
    'page_count' => $page_count,
    'page_list' => $page_list,
    'page_number' => $page_number,
    'cat_number' => $cat_number,
    'not_lot' => $not_lot
]);

$layout_content = include_template('layout.php', [
  'main_content' => $main_content,
  'category_list' => $all_category,
  'title' => 'Все лоты'
]);

print($layout_content);