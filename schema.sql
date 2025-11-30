-- Создание базы данных продукта
CREATE DATABASE IF NOT EXISTS yeticave;
-- Указание на использование данной БД
USE yeticave;

/*
Категория
	Поля: название; символьный код.
    Каждый лот должен быть привязан к одной категории.
    Символьный код нужен, чтобы назначить правильный класс в меню категорий
*/
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    symbol_code VARCHAR(255) NOT NULL,
    UNIQUE INDEX idx_symbol_code (symbol_code)  -- Уникальный индекс для символьного кода
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
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registered_at DATETIME NOT NULL,
    email VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    contacts TEXT NOT NULL,
    UNIQUE INDEX idx_email (email)  -- Уникальный индекс для email
);

/*
Лот
	Поля:
		дата создания: дата и время, когда этот лот был создан пользователем;
		название: задается пользователем;
		описание: задается пользователем;
		изображение: ссылка на файл изображения;
		начальная цена;
		дата завершения;-- Создание таблицы ставок
		шаг ставки.
	Связи:
		автор: пользователь, создавший лот;
		победитель: пользователь, выигравший лот;
		категория: категория объявления
*/
CREATE TABLE IF NOT EXISTS lots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_at DATETIME NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    start_price DECIMAL(10, 2) NOT NULL,
    end_at DATETIME NOT NULL,
    step DECIMAL(10, 2) NOT NULL,
    author_id INT NOT NULL,
    winner_id INT,
    category_id INT NOT NULL,
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (winner_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    INDEX idx_title (title),  -- Обычный индекс для заголовка лота
    INDEX idx_created_at (created_at)  -- Обычный индекс для даты создания лота
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
CREATE TABLE IF NOT EXISTS bids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_at DATETIME NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    user_id INT NOT NULL,
    lot_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (lot_id) REFERENCES lots(id),
    INDEX idx_bid_created_at (created_at)  -- Обычный индекс для даты создания ставки
);
