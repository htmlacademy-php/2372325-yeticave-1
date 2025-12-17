<?php
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

$layoutContent = includeTemplate("404.php", [
    "headerContent" => $headerContent,
    "footerContent" => $footerContent,
    "title" => "Страницы не существует",
    "categories" => $categories,
]);

print $layoutContent;
