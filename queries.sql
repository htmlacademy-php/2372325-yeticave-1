/* Вставка данных в таблицу категорий
Список категорий с их символьными кодами:
 Доски и лыжи (boards),
 Крепления (attachment),
 Ботинки (boots),
 Одежда (clothing),
 Инструменты (tools),
 Разное (other) */
INSERT INTO categories (name, symbol_code) VALUES
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки', 'boots'),
('Одежда', 'clothing'),
('Инструменты', 'tools'),
('Разное', 'other');

-- Вставка данных в таблицу пользователей
INSERT INTO users (name, email, password, contacts) VALUES  -- ❓ registered_at
('John', 'john@mail.com', 'john123', 'email'),
('Ann', 'ann@mail.com', 'ann123', 'email'),
('Tom', 'tom@mail.com', 'tom123', 'email');

-- Вставка данных в таблицу лотов
INSERT INTO lots (
  title, 
  description, 
  image_url, 
  start_price, 
  end_at, 
  step, 
  author_id, 
  category_id) VALUES
('2014 Rossignol District Snowboard', 'сноуборд', 'img/lot-1.jpg', 10999, '2025-12-01', 100, 1, 1),
('DC Ply Mens 2016/2017 Snowboard', 'ещё сноуборд', 'img/lot-2.jpg', 159999, '2025-12-02', 100, 1, 1),
('Крепления Union Contact Pro 2015 года размер L/XL', 'крепления', 'img/lot-3.jpg', 8000, '2025-11-30', 100, 2, 2),
('Ботинки для сноуборда DC Mutiny Charocal', 'ботинки', 'img/lot-4.jpg', 10999, '2025-11-29', 100, 2, 3),
('Куртка для сноуборда DC Mutiny Charocal', 'куртка', 'img/lot-5.jpg', 7500, '2025-11-15', 100, 3, 4),
('Маска Oakley Canopy', 'маска', 'img/lot-6.jpg', 5400, '2025-11-27', 100, 3, 5);

-- Вставка данных в таблицу ставок
INSERT INTO bids (price, user_id, lot_id) VALUES
(8600, 1, 3),
(7700, 2, 5),
(12000, 3, 1);

-- Показать все записи из таблицы категорий
SELECT * FROM categories;

-- Показать все записи из таблицы пользователей
SELECT * FROM users;

-- Показать все доступные лоты
SELECT * FROM lots;

-- Показать все записи из таблицы ставок
SELECT * FROM bids;