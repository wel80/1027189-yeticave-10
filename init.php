<?php

require_once('config.php');
require_once('functions.php');

ob_start();
$link = mysqli_connect ($db['host'], $db['user'], $db['password'], $db['database']);
$result_connect_buffer = ob_get_clean();

if (!$link) {
    $message_error  = 'Ошибка: ' . $result_connect_buffer;
    include_template_error($message_error, $user_name, $is_auth);
};

mysqli_set_charset($link, "utf8");
