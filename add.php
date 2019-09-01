<?php
require_once('init.php');

$query_category_list = 'SELECT id_cat, name_cat, code_cat FROM category';
$result_category_list = mysqli_query($link, $query_category_list);

if ($result_category_list === false) {
    include_template_error($user_name, $is_auth);
};
$all_category = mysqli_fetch_all($result_category_list, MYSQLI_ASSOC);
$all_category_name = array_column($all_category, 'name_cat');

$field_list = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
$error_list = [];
$rule_list = [
    'lot-name' => function() {
        return validateLength('lot-name', 1, 100);
    },
    'category' => function() use ($all_category_name) {
        return validateCategory('category', $all_category_name);
    },
    'message' => function() {
        return validateLength('message', 5, 1000);
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_lot = $_POST;
    /*$new_lot['lot-date'] = '2019-09-30';*/

    foreach ($new_lot as $key => $value) {
        if (isset($rule_list[$key])) {
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

        move_uploaded_file($_FILES['lot-img']['tmp_name'], $image_name);
        $new_lot['lot-img'] = $image_name;
    };

    foreach ($field_list as $val) {
		if (empty($_POST[$val])) {
            $error_list[$val] = 'Это поле надо заполнить.';
		};
	};

    foreach($all_category as $val) {
        if ($new_lot['category'] === $val['name_cat']) {
            $new_lot['category'] = $val['id_cat'];
        };
    };

    if (count($error_list)) {
        $main_content = include_template('main-add.php', [
            'category_list' => $all_category,
            'error_list' => $error_list
        ]);

    } else {

        $insert_new_lot = 'INSERT INTO lot (name_lot, cat_id, description_lot, author_id, initial_price, step_rate, completion_date, image_lot) 
        VALUES (?, ?, ?, 1, ?, ?, ?, ?)';
        $prepared_memo = db_get_prepare_stmt($link, $insert_new_lot, $new_lot);
        $result_insert_lot = mysqli_stmt_execute($prepared_memo);

        if ($result_insert_lot) {
            $id_lot = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $id_lot);
        } else {
            include_template_error($user_name, $is_auth);
        };
    };

} else {
    $main_content = include_template('main-add.php', ['category_list' => $all_category]);
};

$layout_content = include_template('layout.php', [
    'main_content' => $main_content,
    'user_name' => $user_name,
    'title' => 'Добавление лота',
    'category_list' => $all_category,
    'is_auth' => $is_auth
]);
print($layout_content);