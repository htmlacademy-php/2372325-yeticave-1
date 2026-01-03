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
    $lot = $_POST;
    $required = [
        'title',
        'category_id',
        'description',
        'start_price',
        'bid_step',
        'end_at',
    ];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле должно быть заполнено';
        }
    }

    if (empty($errors['category_id']) &&
        !in_array($lot['category_id'], array_column($categories, 'id'))) {
        $errors['category_id'] = 'Выберите категорию из списка';
    }

    if (empty($errors['description']) &&
        $error = validateMessage($lot['description'])) {
        $errors['description'] = $error;
    }

    if (empty($errors['start_price']) &&
        $error = validatePrice($lot['start_price'])) {
        $errors['start_price'] = $error;
    }

    if (empty($errors['bid_step']) &&
        $error = validatePrice($lot['bid_step'])) {
        $errors['bid_step'] = $error;
    }

    if (empty($errors['end_at']) &&
        $error = isDateValid($lot['end_at'])) {
        $errors['end_at'] = $error;
    }

    if (!empty($_FILES['image_url']['name'])) {
        $tmpName = $_FILES['image_url']['tmp_name'];
        $errorCode = $_FILES['image_url']['error'];

        if ($errorCode !== UPLOAD_ERR_OK) {
            $errors['image_url'] = 'Ошибка при загрузке файла. Код ошибки: ' . $errorCode;
        } else {
            $fileType = mime_content_type($tmpName);
            $allowedTypes = ["image/jpeg", "image/png"];

            if (in_array($fileType, $allowedTypes)) {
                $extension = pathinfo($_FILES['image_url']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('lot-', true) . '.' . $extension;
                $destPath = 'uploads/' . $filename;

                if (move_uploaded_file($tmpName, $destPath)) {
                    $lot['image_url'] = '/' . $destPath;
                } else {
                    $errors['image_url'] = 'Не удалось сохранить файл на сервере';
                }
            } else {
                $errors['image_url'] = 'Допустимые форматы: jpg, jpeg, png';
            }
        }
    } else {
        $errors['image_url'] = 'Вы не загрузили изображение';
    }

    if (empty($errors)) {
        if (insertNewLot($conn, $lot)) {
            $lotId = mysqli_insert_id($conn);
            header("Location: /lot.php?id=$lotId");
            exit;
        } else {
            $errors['db'] = 'Ошибка при добавлении лота в базу данных';
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
