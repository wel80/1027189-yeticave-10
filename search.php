<?php
require_once('init.php');

if (isset($_GET['search'])) {
    $search_text_spacing = mysqli_real_escape_string($link, $_GET['search']);
    $search_text = trim($search_text_spacing);
}

if (isset($_GET['page'])) {
    $page_number_validate = filter_var($_GET['page'], FILTER_VALIDATE_INT);
    if (!$page_number_validate) {
        exit(http_response_code(404));
    };      
    $page_number = intval($page_number_validate);
} else {
    $page_number = 1;
}

if ($search_text) {
    $query_count_lot = 'SELECT COUNT(id_lot) AS "quantity" 
    FROM lot 
    WHERE MATCH(name_lot, description_lot) AGAINST(?) AND completion_date > NOW()';
    $prepared_memo = db_get_prepare_stmt($link, $query_count_lot, [$search_text]);
    mysqli_stmt_execute($prepared_memo);
    $result_count_lot = mysqli_stmt_get_result($prepared_memo);
    if ($result_count_lot === false) {
        include_template_error('Ошибка запроса на получение информации из базы данных');
    }
    $count_lot = mysqli_fetch_assoc($result_count_lot)['quantity'];
    $page_count = ($count_lot - $count_lot % 9) / 9 + 1;
    $page_list = range(1, $page_count);
    $offset = ($page_number - 1) * 9;

    $query_search_lot_list = 'SELECT image_lot, name_lot, name_cat, id_lot, initial_price, MAX(bet_amount) AS "rate_price", completion_date
    FROM lot
    LEFT JOIN category ON cat_id = id_cat
    LEFT JOIN rate ON id_lot = lot_id
    WHERE MATCH(name_lot, description_lot) AGAINST(?) AND completion_date > NOW()
    GROUP BY id_lot
    ORDER BY date_lot DESC
    LIMIT 9 OFFSET ' . $offset;
    $prepared_memo = db_get_prepare_stmt($link, $query_search_lot_list, [$search_text]);
    mysqli_stmt_execute($prepared_memo);
    $result_search_lot_list = mysqli_stmt_get_result($prepared_memo);
    if ($result_search_lot_list === false) {
        include_template_error('Ошибка запроса на получение информации из базы данных');
    }
    $search_lot_list = mysqli_fetch_all($result_search_lot_list, MYSQLI_ASSOC);

    if ($search_lot_list && $count_lot) {
        $main_content = include_template('main-search.php', [
            'category_list' => $all_category,
            'search_lot_list' => $search_lot_list,
            'search_text' => $search_text,
            'page_count' => $page_count,
            'page_list' => $page_list,
            'page_number' => $page_number
        ]);
    } else {
        $main_content = include_template('main-search.php', [
            'category_list' => $all_category,
            'search_text' => $search_text
        ]);
    }
} else {
    $main_content = include_template('main-search.php', ['category_list' => $all_category]);
}

$layout_content = include_template('layout.php', [
  'main_content' => $main_content,
  'category_list' => $all_category,
  'title' => 'Результаты поиска'
]);

print($layout_content);