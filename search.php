<?php
require_once __DIR__ . '/init.php';
/**
 * @var mysqli  $conn       Ресурс соединения с БД
 * @var bool    $isAuth     Статус авторизации
 * @var string  $userName   Имя пользователя
 */

$categories = getCategories($conn);
$search = trim(filter_input(INPUT_GET, 'search') ?? '');

$currentPage = (int)(filter_input(INPUT_GET, 'page') ?? 1);
$pageItems = 9;

$lots = [];
$pagesСount = 0;
$pages = [];

if (!empty($search)) {
    $itemsCount = getSearchCount($conn, $search);
    
    $pagesCount = ceil($itemsCount / $pageItems);
    $offset = ($currentPage - 1) * $pageItems;
    
    $lots = searchLots($conn, $search, $pageItems, $offset);
    $pages = range(1, $pagesCount);
}

$pageContent = includeTemplate('search.php', [
    'lots'        => $lots,
    'isAuth'      => $isAuth,
    'pages'       => $pages,
    'search'      => $search,
    'categories'  => $categories,
    'pagesCount'  => $pagesCount,
    'currentPage' => $currentPage,
]);

print includeTemplate('layout.php', [
    'title'       => 'Результаты поиска',
    'isAuth'      => $isAuth,
    'userName'    => $userName,
    'categories'  => $categories,
    'pageContent' => $pageContent,
]);
