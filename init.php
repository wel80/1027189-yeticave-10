<?php
session_start();
require_once('config.php');
require_once('functions.php');
require_once("vendor/autoload.php");

ob_start();
$link = mysqli_connect ($db['host'], $db['user'], $db['password'], $db['database']);
$result_connect_buffer = ob_get_clean();

if (!$link) {
    $message_error  = 'Ошибка: ' . $result_connect_buffer;
    include_template_error($message_error);
}

mysqli_set_charset($link, "utf8");

$query_category_list = 'SELECT id_cat, name_cat, code_cat FROM category';
$all_category = db_find_all($link, $query_category_list);