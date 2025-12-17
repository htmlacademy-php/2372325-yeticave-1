-- Создание базы данных проекта с указанием кодировки
CREATE DATABASE IF NOT EXISTS yeticave 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

-- Указание на использование БД
USE yeticave;

/*
  Категория
  	Поля: название; символьный код.
      Каждый лот должен быть привязан к одной категории.
      Символьный код нужен, чтобы назначить правильный класс в меню категорий
*/
CREATE TABLE IF NOT EXISTS categories
(
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(64) NOT NULL,
  symbol_code VARCHAR(64) NOT NULL,
  UNIQUE INDEX idx_symbol_code (symbol_code),
  UNIQUE INDEX idx_name (name)
);

/*
  Пользователь
  	Поля:
  		дата регистрации: дата и время, когда этот пользователь завёл аккаунт;
  		email;
  		имя;
  		пароль: хэшированный пароль пользователя;
  		контакты: контактная информация для связи с пользователем.
  	Связи:
  		созданные лоты;
  		ставки
*/
CREATE TABLE IF NOT EXISTS users
(
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128) NOT NULL,
  name VARCHAR(64) NOT NULL,
  password CHAR(60) NOT NULL,
  contacts TEXT NOT NULL,
  UNIQUE INDEX idx_email (email),
  INDEX idx_name (name)
);

/*
  Лот
  	Поля:
  		дата создания: дата и время, когда этот лот был создан пользователем;
  		название: задается пользователем;
  		описание: задается пользователем;
  		изображение: ссылка на файл изображения;
  		начальная цена;
  		дата завершения;
  		шаг ставки.
  	Связи:
  		автор: пользователь, создавший лот;
  		победитель: пользователь, выигравший лот;
  		категория: категория объявления
*/
CREATE TABLE IF NOT EXISTS lots
(
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  title VARCHAR(128) NOT NULL,
  description TEXT NOT NULL,
  image_url VARCHAR(128) NOT NULL,
  start_price INT NOT NULL,
  end_at DATETIME NOT NULL,
  bid_step INT UNSIGNED NOT NULL,
  author_id INT UNSIGNED NOT NULL,
  winner_id INT UNSIGNED NULL,
  category_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (winner_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  INDEX idx_title (title),
  INDEX idx_created_at (created_at)
);

/*
  Ставка — это зафиксированное намерение пользователя приобрести товар,
  	    указанный в лоте по фиксированной стоимости.
  	Поля:
  		дата: дата и время размещения ставки;
  		сумма: цена, по которой пользователь готов приобрести лот.
  	Связи:
  		пользователь;
  		лот.
*/
CREATE TABLE IF NOT EXISTS bids
(
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  price INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  lot_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (lot_id) REFERENCES lots(id) ON DELETE CASCADE,
  INDEX idx_bid_created_at (created_at)
);
