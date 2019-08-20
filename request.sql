USE yeticave_wel80;

/*Получить все категории*/
SELECT name_cat FROM category;

/*Получить самые новые, открытые лоты. 
Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории*/
SELECT name_lot, initial_price, image_lot, MAX(bet_amount), name_cat
FROM lot
LEFT JOIN category ON cat_id = id_cat
LEFT JOIN rate ON id_lot = lot_id
WHERE completion_date > NOW()
GROUP BY id_lot
ORDER BY date_lot ASC;

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
WHERE lot_id = 4
ORDER BY date_rate ASC;
