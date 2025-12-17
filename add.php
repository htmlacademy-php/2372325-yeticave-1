<?php
require_once __DIR__ . "/init.php";
/**
 * @var mysqli $conn        Ресурс соединения с БД
 * @var int $isAuth         Пользователь: не зарегистрирован = 0, зарегистрирован = 1
 * @var string $userName    Имя пользователя
 */

$categories = getCategories($conn);
mysqli_close($conn);

$headerContent = includeTemplate("header.php", [
    "isAuth" => $isAuth,
    "userName" => $userName,
]);

$footerContent = includeTemplate("footer.php", [
    "categories" => $categories,
]);

$layoutContent = includeTemplate("add.php", [
    "headerContent" => $headerContent,
    "footerContent" => $footerContent,
    "categories" => $categories,
    "title" => "YetiCave - Новый лот",
]);

print $layoutContent;
