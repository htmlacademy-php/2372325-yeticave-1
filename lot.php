<?php
require_once __DIR__ . "/init.php";
/**
 * @var mysqli $conn        Ресурс соединения с БД
 * @var int $isAuth         Пользователь: не зарегистрирован = 0, зарегистрирован = 1
 * @var string $userName    Имя пользователя
 */

$categories = getCategories($conn);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    handle404Error();
}

$lot = getLot($conn, $id);
if (!$lot) {
    handle404Error();
}

mysqli_close($conn);

$headerContent = includeTemplate("header.php", [
    "isAuth" => $isAuth,
    "userName" => $userName,
]);

$footerContent = includeTemplate("footer.php", [
    "categories" => $categories,
]);

$layoutContent = includeTemplate("lot.php", [
    "headerContent" => $headerContent,
    "footerContent" => $footerContent,
    "lot" => $lot,
    "categories" => $categories,
    "title" => "YetiCave - ЛОТ \"$lot[name]\"",
]);

print $layoutContent;
