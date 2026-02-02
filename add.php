<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions/validator.php';
/**
 * @var mysqli  $conn        Ресурс соединения с БД
 * @var int     $isAuth      Статус авторизации
 * @var string  $userName    Имя пользователя
 */

$categories = getCategories($conn);
$errors = [];
$lot = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validation = validateLotFormAndUploadImage($categories);
    $errors = $validation['errors'];
    $lot = $validation['lot'];

    if (empty($errors)) {
        if (insertNewLot($conn, $lot)) {
            header('Location: /lot.php?id=' . mysqli_insert_id($conn));
            exit;
        }
        $errors['db'] = 'Ошибка при добавлении лота в базу данных';
    }
}

$pageContent = includeTemplate('add.php', [
    'categories' => $categories,
    'errors'     => $errors,
    'lot'        => $lot,
]);

print includeTemplate('layout.php', [
    'title'       => 'Новый лот',
    'isAuth'      => $isAuth,
    'userName'    => $userName,
    'categories'  => $categories,
    'pageContent' => $pageContent,
]);
