<?php
require_once __DIR__ . "/init.php";
/**
 * @var mysqli $conn        Ресурс соединения с БД
 * @var int $isAuth         Пользователь:
 *                              не зарегистрирован = 0,
 *                              зарегистрирован = 1
 * @var string $userName    Имя пользователя
 */

$categories = getCategories($conn);
$errors = [];
$lotData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lotData = $_POST;
    $required = [           // валидация по условиям
        'lot-name',
        'category',
        'message',
        'lot-rate',
        'lot-step',
        'lot-date',
    ];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле должно быть заполнено';
        }
    }

    // Валидация файла (изображения)
    if (!empty($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $file_type = mime_content_type($tmp_name);

        if ($file_type === "image/jpeg" || $file_type === "image/png") {
            $filename = uniqid() . '-' . $_FILES['lot-img']['name'];
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $lotData['path'] = 'uploads/' . $filename;
        } else {
            $errors['lot-img'] = 'Допустимые форматы: jpg, jpeg, png';
        }
    } else {
        $errors['lot-img'] = 'Вы не загрузили изображение';
    }

    if (empty($errors)) {
        $res = saveLot($conn, $lotData);
        if ($res) {
           $lot_id = mysqli_insert_id($conn);
           header("Location: lot.php?id=$lot_id");
           exit;
        }
    }
}

$headerContent = includeTemplate("header.php", [
    "isAuth" => $isAuth,
    "userName" => $userName,
]);

$pageContent = includeTemplate("add.php", [
    "categories" => $categories,
    "errors" => $errors,
    "lot" => $lotData,
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
