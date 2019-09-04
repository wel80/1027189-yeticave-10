<?php
require_once('init.php');

$query_category_list = 'SELECT id_cat, name_cat, code_cat FROM category';
$result_category_list = mysqli_query($link, $query_category_list);

if ($result_category_list === false) {
    $message_error  = 'Ошибка запроса на получение информации из базы данных';
    include_template_error($message_error, $user_name, $is_auth);
};
$all_category = mysqli_fetch_all($result_category_list, MYSQLI_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
		if (empty($_POST[$key])) {
            $error_list[$key] = 'Это поле надо заполнить.';
		} else {
            $rule = $rule_list[$key];
            $error_list[$key] = $rule();
        };
    };
    
    $new_email = mysqli_real_escape_string($link, $new_account['email']);
    $query_email_id = "SELECT id_user FROM user WHERE e_mail = '$new_email'";
    $result_email_id = mysqli_query($link, $query_email_id);
    if (mysqli_num_rows($result_email_id) > 0) {
        $error_list['email'] = 'Пользователь с таким адресом электронной почты уже зарегестрирован.';
    };

    $error_list = array_filter($error_list);

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
            header("Location: pages/login.html");
        } else {
            $message_error  = 'При добавлении пользователя возникла ошибка в базе данных.';
            include_template_error($message_error, $user_name, $is_auth);
        };
    };

} else {
    $main_content = include_template('main-sign-up.php', ['category_list' => $all_category]);
};

$layout_content = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $user_name,
    'title' => 'Регистрация нового аккаунта',
    'category_list' => $all_category,
    'is_auth' => $is_auth
]);
print($layout_content);