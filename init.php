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
