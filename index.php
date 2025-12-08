<?php
require_once __DIR__ . "/init.php";
/** 
 * @var mysqli $conn Подключение к базе данных
 * @var int $isAuth Пользователь не зарегистрирован = 0, зарегистрирован = 1
 * @var string $userName Имя пользователя
 */
 
$categories = getCategories($conn);
$lots = getLots($conn);
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
