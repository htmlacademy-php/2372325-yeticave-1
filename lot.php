<?php
require_once __DIR__ . "/init.php";
/**
 * @var mysqli $conn        Подключение к базе данных
 * @var int $isAuth         Пользователь: не зарегистрирован = 0, зарегистрирован = 1
 * @var string $userName    Имя пользователя
 */

$categories = getCategories($conn);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if($id === false || $id === null) {
    http_response_code(404);
    include('404.php');
    die('Некорректный ID лота');
}

$res = getLot($conn, $id);    

if(!isset($res[0])) {
    http_response_code(404);
    include('404.php');
    die('Указанного лота не существует');
}
$lot = $res[0];
$name = $lot['name'];

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
$layoutContent = includeTemplate("lot.php", 
[
    "headerContent" => $headerContent,
    "footerContent" => $footerContent,
    "lot" => $lot,                               
    "categories" => $categories,
    "title" => "YetiCave - ЛОТ \"$name\"",                        
]);

print $layoutContent;

/*
    Блок со списком ставок и форму показывать на странице не нужно.
    Развёрнутое описание каждому лоту
*/