<?php
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
error_reporting(E_ALL);

require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/db_config.php";

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die('Ошибка подключения к базе данных ' . mysqli_error($conn));
}

$isAuth = rand(0, 1);
$userName = "Илья";

$sql = 'SELECT name, symbol_code FROM categories';
$res = mysqli_query($conn, $sql);
if (!$res) {
    die('Ошибка выполнения запроса: ' . mysqli_error($conn));
}
$categories = mysqli_fetch_all($res, MYSQLI_ASSOC);

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
    ORDER BY created_at DESC;
';
$res = mysqli_query($conn, $sql);
if (!$res) {
    die('Ошибка выполнения запроса: ' . mysqli_error($conn));
}
$lots = mysqli_fetch_all($res, MYSQLI_ASSOC);

mysqli_close($conn);

$pageContent = includeTemplate("main.php", [
    "categories" => $categories,
    "lots" => $lots,
]);

$layoutContent = includeTemplate("layout.php", [
    "pageContent" => $pageContent,
    "isAuth" => $isAuth,
    "userName" => $userName,
    "categories" => $categories,
    "title" => "YetiCave - Главная",
]);

print $layoutContent;
