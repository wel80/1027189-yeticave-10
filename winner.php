<?php
$query_lot_ended = 'SELECT id_lot, MAX(bet_amount) AS "max_rate", winner_id, e_mail, name_user, name_lot
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
        $query_id_winner = 'SELECT participant_id FROM rate WHERE lot_id = ' . $val["id_lot"] . ' AND bet_amount = ' . $val["max_rate"];
        $result_id_winner = mysqli_query($link, $query_id_winner);
        if ($result_id_winner === false) {
            include_template_error('Ошибка запроса на получение информации из базы данных');
        };
        $id_winner = mysqli_fetch_assoc($result_id_winner)['participant_id'];

        $query_winner_id = 'UPDATE lot SET winner_id = ' . $id_winner . ' WHERE id_lot = ' . $val["id_lot"];
        $result_winner_id = mysqli_query($link, $query_winner_id);
        if ($result_winner_id === false) {
            include_template_error('Ошибка запроса на запись информации в базу данных');
        };
        $transport = new Swift_SmtpTransport('smtp.example.org', 25);

        $message = new Swift_Message("Сообщение от YetiCave");
        $message->setFrom(["wel80@list.ru" => "YetiCave"]); 
        $message->setTo([$val['e_mail'] => $val['name_user']]); 
        $message->setBody("Ваша ставка выиграла. Вы стали обладателем " . $val['name_lot']);

        $mailer = new Swift_Mailer($transport); 
        $mailer->send($message);
    };
};
