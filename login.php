<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions/validator.php';
/**
 * @var mysqli $conn        Ресурс соединения с БД
 * @var bool   $isAuth      Статус авторизации
 * @var string $userName    Имя пользователя
 */

if ($isAuth) {
    //header('Location: /index.php');
    print_r($_SESSION);
    exit;
}

$categories = getCategories($conn);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validation = validateLoginForm($conn);
    $errors = $validation['errors'];
    $user = $validation['user'];

    if (empty($errors)) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name']
        ];
        print_r($_SESSION);
        //header('Location: /index.php');
        exit;
    }
    $errors['login'] = 'Ошибка выполнения входа';
}

$pageContent = includeTemplate('login.php', [
    'categories' => $categories,
    'errors'     => $errors,
]);

print includeTemplate('layout.php', [
    'title'       => 'Вход',
    'isAuth'      => $isAuth,
    'userName'    => $userName,
    'categories'  => $categories,
    'pageContent' => $pageContent,
]);
