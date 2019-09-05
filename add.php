<?php
require_once('init.php');
if (empty($_SESSION['user'])) {
    exit(http_response_code(403));
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_lot = $_POST;
    $all_category_id = array_column($all_category, 'id_cat');
    $error_list = [];
    $rule_list = [
        'lot-name' => function() {
            return validateLength('lot-name', 5, 100);
        },
        'category' => function() use ($all_category_id) {
            return validateCategory('category', $all_category_id);
        },
        'message' => function() {
            return validateLength('message', 10, 1000);
        },
        'lot-rate' => function() {
            return validateNumber('lot-rate');
        },
        'lot-step' => function() {
            return validateNumber('lot-step');
        },
        'lot-date' => function() {
            return validateDate('lot-date');
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
    $error_list = array_filter($error_list);

    if (empty($_FILES['lot-img']['name'])) {      
        $error_list['lot-img'] = "Загрузите картинку в формате png, jpeg или jpg";
    } else {
        $image_tmp_name = $_FILES['lot-img']['tmp_name'];

        if (mime_content_type($image_tmp_name) !== 'image/jpeg' && mime_content_type($image_tmp_name) !== 'image/png') {
            $error_list['lot-img'] = "Загрузите картинку в формате png, jpeg или jpg";

        } elseif (mime_content_type($image_tmp_name) === 'image/png') {
            $image_name = 'uploads/' . uniqid() . '.png';

        } else {
            $image_name = 'uploads/' . uniqid() . '.jpg';
        };
    };

    if (count($error_list)) {
        $main_content = include_template('main-add.php', [
            'category_list' => $all_category,
            'error_list' => $error_list,
            'new_lot' => $new_lot
        ]);

    } else {
        move_uploaded_file($_FILES['lot-img']['tmp_name'], $image_name);
        $new_lot['lot-img'] = $image_name;
        $new_lot['author'] = $_SESSION['user']['id_user'];

        $insert_new_lot = 'INSERT INTO lot (name_lot, cat_id, description_lot, initial_price, step_rate, completion_date, image_lot, author_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $prepared_memo = db_get_prepare_stmt($link, $insert_new_lot, $new_lot);
        $result_insert_lot = mysqli_stmt_execute($prepared_memo);

        if ($result_insert_lot) {
            $id_lot = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $id_lot);
        } else {
            include_template_error('Ошибка запроса на получение информации из базы данных');
        };
    };

} else {
    $main_content = include_template('main-add.php', ['category_list' => $all_category]);
};

$layout_content = include_template('layout.php', [
    'main_content' => $main_content,
    'category_list' => $all_category,
    'title' => 'Добавление лота'
]);
print($layout_content);