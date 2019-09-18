<?php
require_once('init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidate_login = $_POST;
    $error_list = [];
    $rule_list = [
        'email' => function() {
            return validateEmail('email');
        },
        'password' => function() {
            return validateLength('password', 5, 100);
        }
    ];

    foreach ($rule_list as $key => $val) {
		if (empty($_POST[$key])) {
            $error_list[$key] = 'Это поле надо заполнить.';
		} else {
            $rule = $rule_list[$key];
            $error_list[$key] = $rule();
        }
    }
    $error_list = array_filter($error_list);
    
    if (!count($error_list)) {
        $candidate_email = mysqli_real_escape_string($link, $candidate_login['email']);
        $query_user = "SELECT * FROM user WHERE e_mail = '$candidate_email'";
        $result_user = mysqli_query($link, $query_user);
        if ($result_user === false) {
            include_template_error('Ошибка запроса на получение информации из базы данных');
        }
        $user = mysqli_fetch_assoc($result_user);
        if (!$user) {
            $error_list['email'] = 'Такой пользователь не найден';
        }  elseif (!password_verify($candidate_login['password'], $user['password_user'])) {
            $error_list['password'] = 'Вы ввели неверный пароль.';
        } else {
            $_SESSION['user'] = $user;
            header("Location: index.php");
        }
    }

    $main_content = include_template('main-login.php', [
        'category_list' => $all_category,
        'error_list' => $error_list
    ]);
    
} elseif (isset($_SESSION['user'])) {
    header("Location: /index.php");
} else {
    $main_content = include_template('main-login.php', ['category_list' => $all_category]);
}

$layout_content = include_template('layout.php', [
    'main_content' => $main_content,
    'category_list' => $all_category,
    'title' => 'Вход'
]);
print($layout_content);