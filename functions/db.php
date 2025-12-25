<?php
/**
 * Создаёт подключение к базе данных
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
    mysqli_set_charset($conn, "utf8");
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
        error_log("Ошибка SQL: " . $e->getMessage() . "\nЗапрос: " . $sql);
        die("Не удалось загрузить категории");
    }
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
            l.id AS id,
            l.title AS name,
            c.name AS category,
            l.image_url AS imgUrl,
            l.start_price AS price,
            l.end_at AS expiryDate,
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
        error_log("Ошибка MySQL при получении лотов: " . $e->getMessage());
        error_log("SQL-запрос: " . $sql);
        die("Ошибка при получении списка лотов");
    }
}

/**
 * Получение доступных лотов
 * @param mysqli $conn  Ресурс соединения с БД
 * @param int $id       ID лота
 * @return array        Возвращает массив полей лота
 */
function getLot(mysqli $conn, int $id): array | false
{
    $sql = '
        SELECT
            l.title AS name,
            c.name AS category,
            l.image_url AS imgUrl,
            l.start_price AS price,
            l.end_at AS expiryDate,
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
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 * @param mysqli $link  Ресурс соединения с БД
 * @param string $sql   SQL запрос с плейсхолдерами вместо значений
 * @param array $data   Данные для вставки на место плейсхолдеров
 * @return mysqli_stmt  Подготовленное выражение
 */
function dbGetPreparedStmt(
    mysqli $link,
    string $sql,
    array $data = []): mysqli_stmt
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        error_log(mysqli_error($link));
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

        if (mysqli_errno($link) > 0) {
            error_log(mysqli_error($link));
            die('Не удалось связать подготовленное выражение с параметрами');
        }
    }
    return $stmt;
}

/**
 * Перенаправляет пользователя на страницу "404.php"
 * @param mysqli $link      Ресурс соединения с БД
 * @param int $isAuth       Пользователь:   не зарегистрирован = 0,
 *                                          зарегистрирован = 1
 * @param string $userName  Имя пользователя
 */
function handle404Error(
    mysqli $conn,
    int $isAuth,
    string $userName): void
{
    http_response_code(404);
    require_once "404.php";
    exit();
}
