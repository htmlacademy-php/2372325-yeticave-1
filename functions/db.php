<?php
/**
 * Создаёт подключение к базе данных
 * @param array $config Массив данных для подключения к БД:
 *                      - порт
 *                      - имя пользователя
 *                      - пароль
 *                      - название БД
 * @return mysqli Подключение к БД
 */
function dbConnect(array $config): mysqli
{
    $conn = mysqli_connect(
        $config['db']['host'], 
        $config['db']['user'], 
        $config['db']['password'], 
        $config['db']['database']
    );
    if (!$conn) {
        die('Ошибка подключения к базе данных ' . mysqli_error($conn));
    }
    mysqli_set_charset($conn, "utf8");
    return $conn;
}

/**
 * Получение категорий товаров
 * @param mysqli $conn Подключение к БД
 * @return array Возвращает массив категорий
 */
function getCategories(mysqli $conn): array
{
    $sql = 'SELECT name, symbol_code FROM categories';
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        die('Ошибка выполнения запроса: ' . mysqli_error($conn));
    }
    $categories = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $categories;
}

/**
 * Получение доступных лотов
 * @param mysqli $conn Подключение к БД
 * @return array Возвращает массив лотов
 */
function getLots(mysqli $conn): array
{
    $sql = '
        SELECT
            l.title AS name,
            c.name AS category,
            l.image_url AS imgUrl,
            l.start_price AS price,
            l.end_at AS expiryDate
        FROM lots l
        JOIN categories c
        ON l.category_id = c.id
        ORDER BY l.created_at DESC;
    ';
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        die('Ошибка выполнения запроса: ' . mysqli_error($conn));
    }
    $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $lots;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 * @param mysqli $link Ресурс соединения
 * @param string $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 * @return mysqli_stmt Подготовленное выражение
 */
function dbGetPrepareStmt(
    mysqli $link,
    string $sql,
    array $data = []): mysqli_stmt
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg =
            'Не удалось инициализировать подготовленное выражение: '
                . mysqli_error($link);
        die($errorMsg);
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
            $errorMsg =
                'Не удалось связать подготовленное выражение с параметрами: ' .
                    mysqli_error($link);
            die($errorMsg);
        }
    }
    return $stmt;
}

