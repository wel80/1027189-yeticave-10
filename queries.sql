USE yeticave_wel80;

INSERT INTO category (name_cat, code_cat)
VALUES ('Доски и лыжи', 'boards'), 
('Крепления', 'attachment'), 
('Ботинки', 'boots'), 
('Одежда', 'clothing'), 
('Инструменты', 'tools'), 
('Разное', 'other');

INSERT INTO user (e_mail, name_user, password_user, contact)
VALUES ('aaa@mail.ru', 'aaa', '111', 'Тел. 8-901-111-11-11'), 
('bbb@ya.ru', 'bbb', '222', 'Тел. 8-902-222-22-22'),
('ccc@ya.ru', 'ccc', '333', 'Тел. 8-903-111-22-33');

INSERT INTO lot (name_lot, cat_id, image_lot, author_id, initial_price, completion_date, step_rate)
VALUES ('2014 Rossignol District Snowboard', 1, 'img/lot-1.jpg', 2, 10999, '2019-10-18 00:00:00', 500), 
('DC Ply Mens 2016/2017 Snowboard', 1, 'img/lot-2.jpg', 2, 159999, '2019-10-19 00:00:00', 5000),
('Крепления Union Contact Pro 2015 года размер L/XL', 2, 'img/lot-3.jpg', 1, 8000, '2019-10-14 00:00:00', 500),
('Ботинки для сноуборда DC Mutiny Charocal', 3, 'img/lot-4.jpg', 2, 10999, '2019-10-15 00:00:00', 500),
('Куртка для сноуборда DC Mutiny Charocal', 4, 'img/lot-5.jpg', 1, 7500, '2019-10-12 00:00:00', 500),
('Маска Oakley Canopy', 6, 'img/lot-6.jpg', 1, 5400, '2019-10-10 00:00:00', 200);

INSERT INTO rate (bet_amount, participant_id, lot_id)
VALUES (5600, 2, 6), (11500, 1, 4), (12000, 3, 4);
