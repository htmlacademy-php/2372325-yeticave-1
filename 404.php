<?php
require_once __DIR__ . "/init.php";
/**
 * @var mysqli $conn        Подключение к базе данных
 * @var int $isAuth         Пользователь: не зарегистрирован = 0, зарегистрирован = 1
 * @var string $userName    Имя пользователя
 */

$categories = getCategories($conn);    
mysqli_close($conn);

$headerContent = includeTemplate("header.php", 
[
    "isAuth" => $isAuth,
    "userName" => $userName,
]);
$footerContent = includeTemplate("footer.php", 
[
    "categories" => $categories,
]);
$layoutContent = includeTemplate("404.php", 
[
    "headerContent" => $headerContent,
    "footerContent" => $footerContent,                            
    "title" => "Страницы не существует",   
    "categories" => $categories,
]);

print $layoutContent;