<?php
require_once __DIR__ . "/init.php";
require_once __DIR__ . "/functions/validator.php";
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

const MAX_LENGTH_NAME = 64;
const MAX_LENGTH_TEXT = 3000;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = array_map(function($value) {
        return is_string($value) ? trim($value) : $value;
    }, $_POST);

    $required = [
        'email',
        'password',
        'name',
        'contacts',
    ];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле должно быть заполнено';
        }
    }

    if (empty($errors['email']) &&
        $error = validateEmail($user['email'])) {
        $errors['email'] = $error;
    }

    // !in_array (пользователя с таким email нет в базе)
    // будет ли заполнен errors

    if (empty($errors['email'])) {
        if (empty($errors['password']) &&
            $error = validatePassword($user['password'])) { // validator.php
            $errors['password'] = $error;
        }

        if (empty($errors['name']) &&
            $error = validateMessage($user['name'], MAX_LENGTH_NAME)) {
            $errors['name'] = $error;
        }

        if (empty($errors['contacts']) &&
            $error = validateMessage($user['contacts'], MAX_LENGTH_TEXT)) {
            $errors['contacts'] = $error;
        }
    }

    // if (empty($errors)) {
    //     if (insertNewUser($conn, $user)) {
    //         $lotId = mysqli_insert_id($conn);    // параметры $userID    ?
    //         header("Location: /login.php");      // параметры
    //         exit;
    //     } else {
    //         $errors['db'] = 'Ошибка при добавлении пользователя в базу данных';
    //     }
    // }
}

    /*
        нужен ли header - ?
    */
$headerContent = includeTemplate("header.php", [
    "isAuth" => $isAuth,
    "userName" => $userName,
]);

$pageContent = includeTemplate("sign-up.php", [
    "categories" => $categories,
    "errors" => $errors,
    "user" => $user,
]);

$footerContent = includeTemplate("footer.php", [
    "categories" => $categories,
]);

$layoutContent = includeTemplate("layout.php", [
    "title" => "Пользователь", // Новый пользователь ???
    "headerContent" => $headerContent,
    "pageContent" => $pageContent,
    "footerContent" => $footerContent,
]);

print $layoutContent;
mysqli_close($conn);
