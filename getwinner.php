<?php
$query_lot_ended = 'SELECT id_lot, MAX(bet_amount) AS "max_rate", winner_id
FROM lot
LEFT JOIN rate ON id_lot = lot_id
LEFT JOIN user ON winner_id = id_user
WHERE completion_date < NOW()
GROUP BY id_lot';
$result_lot_ended = mysqli_query($link, $query_lot_ended);
if ($result_lot_ended === false) {
    include_template_error('Ошибка запроса на получение информации из базы данных');
};
$lot_ended_list = mysqli_fetch_all($result_lot_ended, MYSQLI_ASSOC);

foreach ($lot_ended_list as $val) {
    if ($val['max_rate'] && !$val['winner_id']) {
        $query_lot_winner = 'SELECT participant_id, name_user, e_mail, name_lot
        FROM rate
        LEFT JOIN user ON participant_id = id_user
        LEFT JOIN lot ON lot_id = id_lot
        WHERE lot_id = ' . $val["id_lot"] . ' AND bet_amount = ' . $val["max_rate"];
        $result_lot_winner = mysqli_query($link, $query_lot_winner);
        if ($result_lot_winner === false) {
            include_template_error('Ошибка запроса на получение информации из базы данных');
        };
        $lot_winner = mysqli_fetch_assoc($result_lot_winner);

        $query_winner_id = 'UPDATE lot SET winner_id = ' . $lot_winner['participant_id'] . ' WHERE id_lot = ' . $val["id_lot"];
        $result_winner_id = mysqli_query($link, $query_winner_id);
        if ($result_winner_id === false) {
            include_template_error('Ошибка запроса на запись информации в базу данных');
        };

        $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
        $transport->setUsername("keks@phpdemo.ru");
        $transport->setPassword("htmlacademy");

        $content_message = include_template('email.php', ['lot_winner' => $lot_winner]);
        $message = new Swift_Message();
        $message->setSubject("Ваша ставка победила");
        $message->setFrom(['keks@phpdemo.ru' => 'YetiCave']);
        $message->setTo([$lot_winner["e_mail"] => $lot_winner["name_user"]]);
        $message->setBody($content_message, 'text/html');

        $mailer = new Swift_Mailer($transport);
        $mailer->send($message);
    };
};
