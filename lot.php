<?php
require_once __DIR__ . '/init.php';
/**
 * @var mysqli  $conn        Ресурс соединения с БД
 * @var int     $isAuth      Статус авторизации
 * @var string  $userName    Имя пользователя
 */

$categories = getCategories($conn);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$lot = $id > 0 ? getLot($conn, $id) : null;

if (!$lot) {
    handle404Error($categories, $isAuth, $userName);
}

$pageContent = includeTemplate('lot.php', [
    'lot'        => $lot,
    'categories' => $categories,
]);

print includeTemplate('layout.php', [
    'title'       => "лот: $lot[name]",
    'isAuth'      => $isAuth,
    'userName'    => $userName,
    'categories'  => $categories,
    'pageContent' => $pageContent,
]);
