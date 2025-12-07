/* Список категорий с их символьными кодами:
    Доски и лыжи  (boards),
    Крепления     (attachment),
    Ботинки       (boots),
    Одежда        (clothing),
    Инструменты   (tools),
    Разное        (other)
*/
-- Вставка данных в таблицу категорий
INSERT INTO categories (name, symbol_code) VALUES
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки', 'boots'),
('Одежда', 'clothing'),
('Инструменты', 'tools'),
('Разное', 'other');

-- Вставка данных в таблицу пользователей
INSERT INTO users (name, email, password, contacts) VALUES
('John', 'john@mail.com', 'john123', 'email'),
('Ann', 'ann@mail.com', 'ann123', 'email'),
('Tom', 'tom@mail.com', 'tom123', 'email');

-- Вставка данных в таблицу лотов
INSERT INTO lots (
  title,
  description, image_url,
  start_price, end_at, bid_step, author_id, category_id) 
VALUES
  ('2014 Rossignol District Snowboard',
    'сноуборд', 'img/lot-1.jpg',
    10999, '2025-12-21', 100, 1, 1),
  ('DC Ply Mens 2016/2017 Snowboard',
    'ещё сноуборд', 'img/lot-2.jpg',
    159999, '2025-12-02', 100, 1, 1),
  ('Крепления Union Contact Pro 2015 года размер L/XL',
    'крепления', 'img/lot-3.jpg',
    8000, '2026-01-30', 100, 2, 2),
  ('Ботинки для сноуборда DC Mutiny Charcoal',
    'ботинки', 'img/lot-4.jpg',
    10999, '2025-12-29', 100, 2, 3),
  ('Куртка для сноуборда DC Mutiny Charcoal',
    'куртка', 'img/lot-5.jpg',
    7500, '2025-12-03', 100, 3, 4),
  ('Маска Oakley Canopy',
    'маска', 'img/lot-6.jpg',
    5400, '2025-12-07 09:40', 100, 3, 6);

-- Вставка данных в таблицу ставок
INSERT INTO bids (price, user_id, lot_id) VALUES
(8600, 1, 3),
(7700, 2, 5),
(12000, 3, 1);

-- Показать все записи из таблицы категорий
SELECT * FROM categories;

/*
  Получить самые новые, открытые лоты.
  Каждый лот должен включать название, стартовую цену,
  ссылку на изображение, текущую цену с учётом ставок, название категории;
*/
SELECT
  l.title,
  l.start_price,
  l.image_url,
  COALESCE(MAX(b.price), l.start_price) AS current_price,
    -- returns the first non-null value from its arguments.
    -- If there are bids for the lot,
    -- it returns the highest bid;
    -- if not (meaning there are no bids),
    -- it defaults to the starting price
  c.name AS category
FROM lots l
LEFT JOIN bids b ON l.id = b.lot_id
    -- This ensures that lots without any bids are still included in the results
JOIN categories c ON l.category_id = c.id
WHERE l.end_at > NOW()
GROUP BY l.id
    -- When using aggregate functions like MAX,
    -- it's necessary to group by to get a single result for each lot,
    -- avoiding duplication in the results when there are multiple bids
ORDER BY l.created_at DESC;

-- Показать лот по его ID с названием категории, к которой он принадлежит;
SELECT
  l.created_at,
  l.title,
  l.description,
  l.start_price,
  l.end_at,
  l.bid_step,
  l.author_id,
  c.name AS category
FROM lots l
JOIN categories c ON l.category_id = c.id
WHERE l.id = 3;

-- Обновить название лота по его идентификатору;
UPDATE lots
SET title = '2014 Rossignol District Snowboard SUPER'
WHERE id = 1;

-- Получить список ставок для лота по его идентификатору с сортировкой по дате;
SELECT
  b.id,
  b.created_at,
  b.price,
  b.user_id,
  l.title
FROM bids b
JOIN lots l ON l.id = b.lot_id
WHERE l.id = 3
ORDER BY created_at ASC;

---------------------------------------------------------------
-- Удаление категории "Инструменты"
DELETE FROM categories WHERE id = 4;

-- Добавление нового лота 
-- (изображение img/lot-7.jpg добавлено заранее)
INSERT INTO lots (
  title,
  description, image_url,
  start_price, end_at, bid_step, author_id, category_id) 
VALUES
  ('2025 Fischer Skis NEW',
    'лыжи', 'img/lot-7.jpg',
    25099, '2025-12-21', 200, 2, 1);