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
$user = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validation = validateSignUpForm($conn);
    $errors = $validation['errors'];
    $user = $validation['user'];

    if (empty($errors)) {
        if (insertNewUser($conn, $user)) {
            header('Location: /login.php');
            exit;
        } else {
            $errors['db'] = 'Не удалось добавить пользователя в базу данных';
        }
    }
}

$pageContent = includeTemplate('sign-up.php', [
    'categories' => $categories,
    'errors'     => $errors,
    'user'       => $user,
]);

print includeTemplate('layout.php', [
    'title'       => 'Регистрация', 
    'isAuth'      => $isAuth,
    'userName'    => $userName,
    'categories'  => $categories,
    'pageContent' => $pageContent,
]);
