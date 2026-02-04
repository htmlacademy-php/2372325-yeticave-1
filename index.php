<?php
require_once __DIR__ . '/init.php';
/**
 * @var mysqli  $conn        Ресурс соединения с БД
 * @var bool    $isAuth      Статус авторизации
 * @var string  $userName    Имя пользователя
 */

$categories = getCategories($conn);
$lots = getLots($conn);

$pageContent = includeTemplate('main.php', [
    'categories' => $categories,
    'lots'       => $lots,
]);

print includeTemplate('layout.php', [
    'title'       => 'Главная',
    'isAuth'      => $isAuth,
    'userName'    => $userName,
    'categories'  => $categories,
    'pageContent' => $pageContent,
]);
