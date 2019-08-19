INSERT INTO category (name_cat, code_cat)
VALUES ('Доски и лыжи', 'boards'), 
('Крепления', 'attachment'), 
('Ботинки', 'boots'), 
('Одежда', 'clothing'), 
('Инструменты', 'tools'), 
('Разное', 'other');

INSERT INTO user (e_mail, name_user, password_user, avatar, contact)
VALUES ('aaa@mail.ru', 'aaa', '111', 'aaa.jpg', 'Тел. 8-901-111-11-11'), 
('bbb@ya.ru', 'bbb', '222', 'bbb.jpg', 'Тел. 8-902-222-22-22');

INSERT INTO lot (name_lot, cat_id, image_lot, author_id, initial_price, completion_date, step_rate)
VALUES ('2014 Rossignol District Snowboard', 1, 'img/lot-1.jpg', 2, 10999, 1567098000, 500), 
('DC Ply Mens 2016/2017 Snowboard', 1, 'img/lot-2.jpg', 2, 159999, 1567098000, 5000),
('Крепления Union Contact Pro 2015 года размер L/XL', 2, 'img/lot-3.jpg', 1, 8000, 1567357200, 500),
('Ботинки для сноуборда DC Mutiny Charocal', 3, 'img/lot-4.jpg', 2, 10999, 1567098000, 500),
('Куртка для сноуборда DC Mutiny Charocal', 4, 'img/lot-5.jpg', 1, 7500, 1567357200, 500),
('Маска Oakley Canopy', 6, 'img/lot-6.jpg', 1, 5400, 1567357200, 200);

INSERT INTO rate (bet_amount, participant_id, lot_id)
VALUES (5600, 2, 6), (11500, 1, 4);

/*Получить все категории*/
SELECT name_cat FROM category;

/*Получить самые новые, открытые лоты. 
Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории*/
SELECT name_lot, initial_price, image_lot, MAX(bet_amount), name_cat
FROM lot
JOIN rate ON id_lot = lot_id
JOIN category ON cat_id = id_cat
WHERE completion_date > CURRENT_TIMESTAMP;

/*Показать лот по его id. Получите также название категории, к которой принадлежит лот*/
SELECT name_lot, image_lot, name_cat
FROM lot
JOIN category ON cat_id = id_cat
WHERE id_lot = 1;

/*Обновить название лота по его идентификатору*/
UPDATE lot 
SET name_lot = 'Крепления Union Contact Pro 2018 года размер L/XL' 
WHERE id_lot = 3;

/*Получить список ставок для лота по его идентификатору с сортировкой по дате*/
SELECT bet_amount 
FROM rate
ORDER BY date_rate ASC
WHERE lot_id = 1;