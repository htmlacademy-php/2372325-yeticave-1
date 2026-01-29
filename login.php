<?php
require_once __DIR__ . "/init.php";
require_once __DIR__ . "/functions/validator.php";
/**
 * @var mysqli $conn        Ресурс соединения с БД
 * @var int $isAuth         Пользователь:
 *                              не зарегистрирован = 0,
 *                                 зарегистрирован = 1
 * @var string $userName    Имя пользователя
 */

$categories = getCategories($conn);

$headerContent = includeTemplate("header.php", [
    "isAuth" => $isAuth,
    "userName" => $userName,
]);

$pageContent = includeTemplate("login.php", [
    "categories" => $categories,
]);

$footerContent = includeTemplate("footer.php", [
    "categories" => $categories,
]);

$layoutContent = includeTemplate("layout.php", [
    "title" => "Вход",
    "headerContent" => $headerContent,
    "pageContent" => $pageContent,
    "footerContent" => $footerContent,
]);

print $layoutContent;
mysqli_close($conn);
