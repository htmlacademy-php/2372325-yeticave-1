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
$lot = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validation = validateLotForm($categories);
    $errors = $validation['errors'];
    $lot = $validation['lot'];

    if (empty($errors)) {
        if (insertNewLot($conn, $lot)) {
            header("Location: /lot.php?id=" . mysqli_insert_id($conn));
            exit;
        }
        $errors['db'] = 'Ошибка при добавлении лота в базу данных';
    }
}

$headerContent = includeTemplate("header.php", [
    "isAuth" => $isAuth,
    "userName" => $userName,
]);

$pageContent = includeTemplate("add.php", [
    "categories" => $categories,
    "errors" => $errors,
    "lot" => $lot,
]);

$footerContent = includeTemplate("footer.php", [
    "categories" => $categories,
]);

$layoutContent = includeTemplate("layout.php", [
    "title" => "Новый лот",
    "headerContent" => $headerContent,
    "pageContent" => $pageContent,
    "footerContent" => $footerContent,
]);

print $layoutContent;
mysqli_close($conn);
