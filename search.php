<?php
require_once __DIR__ . '/init.php';
/**
 * @var mysqli  $conn       Ресурс соединения с БД
 * @var bool    $isAuth     Статус авторизации
 * @var string  $userName   Имя пользователя
 */

$categories = getCategories($conn);

$search = filter_input(INPUT_GET, 'search');
$lots = searchLots($conn, $search);

$pageContent = includeTemplate('search.php', [
    'lots'       => $lots,
    'isAuth'     => $isAuth,
    'search'     => $search,
    'categories' => $categories,
]);

print includeTemplate('layout.php', [
    'title'       => 'Результаты поиска',
    'isAuth'      => $isAuth,
    'userName'    => $userName,
    'categories'  => $categories,
    'pageContent' => $pageContent,
]);
