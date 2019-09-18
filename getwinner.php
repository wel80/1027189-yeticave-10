<?php
$query_lot_ended = 'SELECT id_lot, MAX(bet_amount) AS "max_rate", winner_id
FROM lot
LEFT JOIN rate ON id_lot = lot_id
LEFT JOIN user ON winner_id = id_user
WHERE completion_date < NOW()
GROUP BY id_lot';
$lot_ended_list = db_find_all($link, $query_lot_ended);

foreach ($lot_ended_list as $val) {
    if ($val['max_rate'] && !$val['winner_id']) {
        $query_lot_winner = 'SELECT participant_id, name_user, e_mail, name_lot, lot_id
        FROM rate
        LEFT JOIN user ON participant_id = id_user
        LEFT JOIN lot ON lot_id = id_lot
        WHERE lot_id = ' . $val["id_lot"] . ' AND bet_amount = ' . $val["max_rate"];
        $lot_winner = db_find_all($link, $query_lot_winner)[0];

        $query_winner_id = 'UPDATE lot SET winner_id = ' . $lot_winner['participant_id'] . ' WHERE id_lot = ' . $val["id_lot"];
        $result_winner_id = mysqli_query($link, $query_winner_id);
        if ($result_winner_id === false) {
            include_template_error('Ошибка запроса на запись информации в базу данных');
        }

        $lot_url = 'http://' . $_SERVER['SERVER_NAME'] . '/lot.php?id=' . $lot_winner['lot_id'];
        $rate_list_url = 'http://' . $_SERVER['SERVER_NAME'] . '/my-bets.php';

        $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
        $transport->setUsername("keks@phpdemo.ru");
        $transport->setPassword("htmlacademy");

        $content_message = include_template('email.php', ['lot_winner' => $lot_winner, 'lot_url' => $lot_url, 'rate_list_url' => $rate_list_url]);
        $message = new Swift_Message();
        $message->setSubject("Ваша ставка победила");
        $message->setFrom(['keks@phpdemo.ru' => 'YetiCave']);
        $message->setTo([$lot_winner["e_mail"] => $lot_winner["name_user"]]);
        $message->setBody($content_message, 'text/html');

        $mailer = new Swift_Mailer($transport);
        $result = $mailer->send($message);
    }
}
