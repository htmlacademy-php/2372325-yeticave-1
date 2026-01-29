<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions/validator.php';
/**
 * @var mysqli $conn        Ресурс соединения с БД
 * @var int $isAuth         Пользователь:
 *                              не зарегистрирован = 0,
 *                                 зарегистрирован = 1
 * @var string $userName    Имя пользователя
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

$headerContent = includeTemplate('header.php', [
    'isAuth' => $isAuth,
    'userName' => $userName,
]);

$pageContent = includeTemplate('sign-up.php', [
    'categories' => $categories,
    'errors' => $errors,
    'user' => $user,
]);

$footerContent = includeTemplate('footer.php', [
    'categories' => $categories,
]);

$layoutContent = includeTemplate('layout.php', [
    'title' => 'Регистрация', 
    'headerContent' => $headerContent,
    'pageContent' => $pageContent,
    'footerContent' => $footerContent,
]);

print $layoutContent;
mysqli_close($conn);
