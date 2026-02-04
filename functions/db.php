<?php
/**
 * Создаёт подключение к базе данных
 *  либо прекращает раьоту программы,
 *  если не удалось подключиться к БД
 * @param array $config Массив данных для подключения к БД:
 *                      - порт
 *                      - имя пользователя
 *                      - пароль
 *                      - название БД
 * @return mysqli Ресурс соединения с БД
 */
function dbConnect(array $config): mysqli
{
    $conn = mysqli_connect(
        $config['host'],
        $config['user'],
        $config['password'],
        $config['database']
    );
    if (!$conn) {
        error_log(mysqli_connect_error());
        die('Ошибка подключения к базе данных');
    }
    mysqli_set_charset($conn, "utf8mb4");
    return $conn;
}

/**
 * Получение категорий товаров
 * @param mysqli $conn  Ресурс соединения с БД
 * @return array        Возвращает массив категорий
 */
function getCategories(mysqli $conn): array
{
    $sql = 'SELECT id, name, symbol_code FROM categories';
    try {
        $res = mysqli_query($conn, $sql);
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        error_log('Ошибка SQL: ' . $e->getMessage() . '\nЗапрос: ' . $sql);
        die('Не удалось загрузить категории');
    }
}

/**
 * Получение данных пользователя по его email
 * @param mysqli $conn  Ресурс соединения с БД
 * @param string $email 
 * @return array        Возвращает данных пользователя
 *                          либо `false` в случае ошибки
 */
function getUserByEmail(mysqli $conn, string $email): ?array
{
    $sql = 'SELECT id, name, password FROM users WHERE email = ?';
    $user = null;

    $stmt = dbGetPreparedStmt($conn, $sql, [$email]);
    try {
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);
    } catch (mysqli_sql_exception $e) {
        error_log('Ошибка SQL: ' . $e->getMessage());
        die('Не удалось получить данные пользователя по email');
    } finally {
        mysqli_stmt_close($stmt);
    }
    return $user;
}

/**
 * Получение доступных лотов
 * @param mysqli $conn  Ресурс соединения с БД
 * @return array        Возвращает массив лотов
 */
function getLots(mysqli $conn): array
{
    $sql = '
        SELECT
            l.id            AS id,
            l.title         AS name,
            c.name          AS category,
            l.image_url     AS imgUrl,
            l.start_price   AS price,
            l.end_at        AS expiryDate,
            l.description
        FROM lots l
        JOIN categories c
        ON l.category_id = c.id
        ORDER BY l.created_at DESC;
    ';

    try {
        $res = mysqli_query($conn, $sql);
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        error_log('Ошибка MySQL при получении лотов: ' . $e->getMessage());
        error_log('SQL-запрос: ' . $sql);
        die('Ошибка при получении списка лотов');
    }
}

/**
 * Получение лота по его ID
 * @param mysqli $conn  Ресурс соединения с БД
 * @param int $id       ID лота
 * @return array|false  Возвращает массив полей лота
 *  в случае успешного выполнения запроса 
 *  или булево значение `false` в ином случае
 */
function getLot(mysqli $conn, int $id): array | false
{
    $sql = '
        SELECT
            l.title         AS name,
            c.name          AS category,
            l.image_url     AS imgUrl,
            l.start_price   AS price,
            l.end_at        AS expiryDate,
            l.description
        FROM lots l
        JOIN categories c
        ON l.category_id = c.id
        WHERE l.id = ' . $id . ';
    ';
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        error_log(mysqli_error($conn));
        die('Ошибка выполнения запроса');
    }
    $lot = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $lot[0] ?? false;
}

/**
 * Создает подготовленное выражение
 *  на основе готового SQL запроса и переданных данных
 * @param mysqli $conn  Ресурс соединения с БД
 * @param string $sql   SQL запрос с плейсхолдерами вместо значений
 * @param array $data   Данные для вставки на место плейсхолдеров
 * @return mysqli_stmt  Вщзвращает подготовленное выражение
 */
function dbGetPreparedStmt(
    mysqli $conn,
    string $sql,
    array $data = []): mysqli_stmt
{
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        error_log(mysqli_error($conn));
        die('Не удалось инициализировать подготовленное выражение');
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = [$stmt, $types, ...$stmt_data];
        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($conn) > 0) {
            error_log(mysqli_error($conn));
            die('Не удалось связать подготовленное выражение с параметрами');
        }
    }
    return $stmt;
}

/**
 * Добавляет новый лот в БД
 * @param mysqli $conn   Ресурс соединения с БД
 * @param array  $lot    Массив с данными лота
 * @param int    $int    ID текущего пользователя
 * @return true         Возвращает булево значение `true`
 *  в случае успешного добавления нового лота,
 *  иначе прерывает выполнение скрипта и
 *  выводит сообщение об ошибке на страницу
 */
function insertNewLot(mysqli $conn, array $lot, int $id): true
{
    $sql = '
        INSERT INTO lots (
            title, description, image_url,
            start_price, end_at, bid_step,
            author_id, category_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?);
    ';

    // (int), чтобы хелпер правильно определил их как 'i'
    $data = [
        $lot['title'],
        $lot['description'],
        $lot['image_url'],
        (int)$lot['start_price'],
        $lot['end_at'],
        (int)$lot['bid_step'],
        $id,                              
        (int)$lot['category_id']
    ];

    $stmt = dbGetPreparedStmt($conn, $sql, $data);
    try {
        mysqli_stmt_execute($stmt);
        return true;
    } catch (mysqli_sql_exception $e) {
        error_log('Ошибка SQL: ' . $e->getMessage());
        die('Не удалось добавить новый лот');
    } finally {
        mysqli_stmt_close($stmt);
    }
}

/**
 * Добавляет нового пользователя в БД
 * @param mysqli $conn  Ресурс соединения с БД
 * @param array $user   Массив с данными пользователя
 * @return true         Возвращает булево значение `true`
 *  в случае успешного добавления нового пользователя,
 *  иначе прерывает выполнение скрипта и
 *  выводит сообщение об ошибке на страницу
 */
function insertNewUser(mysqli $conn, array $user): true
{
    $sql = '
        INSERT INTO users (email, name, password, contacts)
        VALUES (?, ?, ?, ?);
    ';

    $data = [
        $user['email'],
        $user['name'],
        $user['password'],
        $user['contacts'],
    ];

    $stmt = dbGetPreparedStmt($conn, $sql, $data);
    try {
        mysqli_stmt_execute($stmt);
        return true;
    } catch (mysqli_sql_exception $e) {
        error_log('Ошибка SQL: ' . $e->getMessage());
        die('Не удалось добавить нового пользователя');
    } finally {
        mysqli_stmt_close($stmt);
    }
}

/** 
 * Проверяет введённый пользователем email
 * @param mysqli $conn      Ресурс соединения с БД
 * @param string $email     Адрес эл.почты
 * @return true             Возвращает булево значение:
 *  `true`      - предоставленный email имеется в БД,
 *  `false`     - предоставленного email нет в БД 
 */
function emailExists(mysqli $conn, string $email): bool
{
    $sql = 'SELECT 1 FROM users WHERE email = ? LIMIT 1';
    $data = [$email];

    $stmt = dbGetPreparedStmt($conn, $sql, $data);
    try {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $exists = mysqli_fetch_assoc($result);   
        return $exists ? false : true;
    } catch (mysqli_sql_exception $e) {
        error_log('Ошибка SQL: ' . $e->getMessage());
        die('Не удалось проверить уникальность введённого email');
    } finally {
        mysqli_stmt_close($stmt);
    }
}

/**
 * Выводит сообщение об ошибке, если запрашиваемая страница не найдена
 * @param array $categories Массив доступных категорий товаров
 * @param int $isAuth       Статус авторизации
 * @param string $userName  Имя пользователя
 */
function handle404Error(
    array $categories,
    int $isAuth,
    string $userName): void
{
    http_response_code(404);

    $pageContent = includeTemplate('404.php', [
        'categories' => $categories,
    ]);

    print includeTemplate('layout.php', [
        'title'       => 'Страницы не существует',
        'isAuth'      => $isAuth,
        'userName'    => $userName,
        'categories'  => $categories,
        'pageContent' => $pageContent,
    ]);

    exit();
}

