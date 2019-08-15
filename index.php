<?php

require_once('config.php');
require_once('functions.php');
require_once('data.php');

$main_content = include_template('main.php', [
    'category_list' => $category_list,
    'advertising_list' => $advertising_list
]);

$layout_content = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $user_name,
    'title' => 'Главная',
    'category_list' => $category_list,
    'is_auth' => $is_auth
]);

print($layout_content);
