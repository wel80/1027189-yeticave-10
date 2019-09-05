<?php
session_start();
require_once('config.php');
require_once('functions.php');

ob_start();
$link = mysqli_connect ($db['host'], $db['user'], $db['password'], $db['database']);
$result_connect_buffer = ob_get_clean();

if (!$link) {
    $message_error  = 'Ошибка: ' . $result_connect_buffer;
    include_template_error($message_error);
};

mysqli_set_charset($link, "utf8");

$query_category_list = 'SELECT id_cat, name_cat, code_cat FROM category';
$result_category_list = mysqli_query($link, $query_category_list);

if ($result_category_list === false) {
    include_template_error('Ошибка запроса на получение информации из базы данных');
};
$all_category = mysqli_fetch_all($result_category_list, MYSQLI_ASSOC);