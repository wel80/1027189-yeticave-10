<?php
require_once('init.php');
if (isset($_SESSION['user'])) {
    exit(http_response_code(403));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
    $new_account = $_POST;
    $error_list = [];
    $rule_list = [
        'email' => function() {
            return validateEmail('email');
        },
        'password' => function() {
            return validateLength('password', 5, 100);
        },
        'name' => function() {
            return validateLength('name', 1, 100);
        },
        'message' => function() {
            return validateLength('message', 10, 255);
        }
    ];

    foreach ($rule_list as $key => $val) {
		if (isset($_POST[$key]) && !empty($_POST[$key])) {
            $rule = $rule_list[$key];
            $error_list[$key] = $rule();
		} else {
            $error_list[$key] = 'Это поле надо заполнить.';
        }
    }
    $error_list = array_filter($error_list);
    
    if (!count($error_list)) {
        $new_email = mysqli_real_escape_string($link, $new_account['email']);
        $query_email_id = "SELECT id_user FROM user WHERE e_mail = '$new_email'";
        $result_email_id = mysqli_query($link, $query_email_id);
        if ($result_email_id === false) {
            include_template_error('Ошибка запроса на получение информации из базы данных');
        };
        if (mysqli_num_rows($result_email_id) > 0) {
            $error_list['email'] = 'Пользователь с таким адресом электронной почты уже зарегестрирован.';
        }
    }

    if (count($error_list)) {
        $main_content = include_template('main-sign-up.php', [
            'category_list' => $all_category,
            'error_list' => $error_list
        ]);
    } else {
        $new_account['password'] = password_hash($new_account['password'], PASSWORD_DEFAULT);
        $insert_new_user = 'INSERT INTO user (e_mail, password_user, name_user, contact) 
        VALUES (?, ?, ?, ?)';
        $prepared_memo = db_get_prepare_stmt($link, $insert_new_user, $new_account);
        $result_insert_user = mysqli_stmt_execute($prepared_memo);

        if ($result_insert_user) {
            header("Location: login.php");
        } else {
            include_template_error('При добавлении пользователя возникла ошибка в базе данных.');
        }
    }

} else {
    $main_content = include_template('main-sign-up.php', ['category_list' => $all_category]);
}

$layout_content = include_template('layout.php', [
    'main_content' => $main_content,
    'category_list' => $all_category,
    'title' => 'Регистрация нового аккаунта'
]);
print($layout_content);