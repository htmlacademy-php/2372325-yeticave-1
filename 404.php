<?php
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

$pageContent = includeTemplate("404.php", [
     "categories" => $categories,
]);

$footerContent = includeTemplate("footer.php", [
    "categories" => $categories,
]);

$layoutContent = includeTemplate("layout.php", [
    "title" => "Страницы не существует",
    "headerContent" => $headerContent,
    "pageContent" => $pageContent,
    "footerContent" => $footerContent,
]);

print $layoutContent;
