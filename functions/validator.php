<?php
/**
 * Проверяет переданную дату на соответствие параметрам
 * @param string $date  Дата в виде строки
 * @return string       Возвращает пустую строку,
 *  если формат даты указан верно и
 *  дата указана хотя бы на один день больше текущей.
 *  Иначе возвращает строку с ошибкой
 */
function isDateValid(string $date): string
{
    $dateTimeObj = date_create_from_format('Y-m-d', $date);

    if ($dateTimeObj === false) {
        return 'Неверный формат даты';
    }

    $curDate = new DateTime('now');
    $str = $curDate->diff($dateTimeObj)->format("%r%a");
    if ($curDate->diff($dateTimeObj) && (int)$str < 1) {
	    return 'Дата должна быть хотя бы на один день больше текущей';
    }
    return '';
}

/**
 * Проверяет переданную сумму,
 *  которая должна быть целым положительным числом
 * @param string $lotPrice  Сумма в виде строки
 * @return string           Возвращает сообщение об ошибке,
 *  если строка не может быть преобразована в целое число
 *  или полученная сумма меньше 0,
 *  иначе возвращает пустую строку
 */
function validatePrice(string $lotPrice): string
{
    if (!ctype_digit($lotPrice) || !$lotPrice > 0) {
        return 'Необходимо указать целое число больше 0';
    }
    return '';
}

/**
 * Валидирует длину текста
 * @param string $message   Содержимое поля
 * @param int $min          Минимальная длина (по умолчанию 3)
 * @param int $max          Максимальная длина (по умолчанию 3000)
 * @return string           Возвращает пустую строку,
 *  если длина текста не менее 3 символов и не превышает максимальной.
 *  Иначе возвращает строку с ошибкой.
 */
function validateTextLength(string $message, int $max = 3000, int $min = 3): string
{
    $length = mb_strlen($message);
    if ($length < $min) {
        return "Минимальная длина — $min символа";
    }
    if ($length > $max) {
        return "Максимальная длина — $max символов";
    }
    return '';
}

/**
 * Валидирует email
 * @param string $email     Содержимое поля
 * @param int $max          Максимальная длина (по умолчанию 128)
 * @return string           Возвращает пустую строку,
 *  если email был введён в корректном формате.
 *  Иначе возвращает строку с ошибкой.
 */
function validateEmail(string $email, int $max = 128): string
{
    if (mb_strlen($email) > $max) {
        return "Максимальная длина — $max символов";
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return '';
    } else {
        return 'Некорректный формат email';
    }
}

/**
 * Валидирует форму добавления лота и загружает его изображение
 * @param array $categories     Массив доступных категорий
 * @return array                Возвращает массив с описаниями ошибок
 *  по каждому полю валидируемой формы и массив с данными нового лота
 *  в случае отсутствия ошибок
 */
function validateLotFormAndUploadImage(array $categories): array 
{
    $errors = [];
    
    $lot = array_map(function($value) {
        return is_string($value) ? trim($value) : $value;
    }, $_POST);

    $required = [
        'title', 
        'category_id', 
        'description', 
        'start_price', 
        'bid_step', 
        'end_at'
    ];

    foreach ($required as $field) {
        if (empty($lot[$field])) {
            $errors[$field] = 'Это поле должно быть заполнено';
        }
    }

    if (empty($errors['category_id']) && 
        !in_array($lot['category_id'], array_column($categories, 'id'))) {
            $errors['category_id'] = 'Выберите категорию из списка';
    }
    if (empty($errors['description'])) {
        $errors['description'] = validateTextLength($lot['description']);
    } 
    if (empty($errors['start_price'])) {
        $errors['start_price'] = validatePrice($lot['start_price']);
    }
    if (empty($errors['bid_step'])) {
        $errors['bid_step'] = validatePrice($lot['bid_step']);
    }    
    if (empty($errors['end_at'])) {
        $errors['end_at'] = isDateValid($lot['end_at']);
    }
    
    $errors = array_filter($errors);

    if (!empty($_FILES['image_url']['name'])) {
        $tmpName = $_FILES['image_url']['tmp_name'];
        $fileType = mime_content_type($tmpName);
        
        if (in_array($fileType, ['image/jpeg', 'image/png'])) {
            $filename = uniqid('lot-') . '.' 
                . pathinfo($_FILES['image_url']['name'], PATHINFO_EXTENSION);
            $destPath = 'uploads/' . $filename;

            if (move_uploaded_file($tmpName, $destPath)) {
                $lot['image_url'] = '/' . $destPath;
            }
        } else {
            $errors['image_url'] = 'Допустимые форматы: jpg, jpeg, png';
        }
    } else {
        $errors['image_url'] = 'Вы не загрузили изображение';
    }
    return ['errors' => $errors, 'lot' => $lot];
}

/**
 * Валидирует форму регистрации нового пользователя
 * @param mysqli $conn          Ресурс подключения к БД
 * @return array                Возвращает массив с описаниями ошибок
 *  по каждому полю валидируемой формы и массив с данными нового 
 *  пользователя в случае отсутствия ошибок
 */
function validateSignUpForm(mysqli $conn): array
{
    $maxLengthName = 64;
    $maxLengthText = 3000;
    $maxLengthPassword = 60;
    $minLengthPassword = 5;
    $errors = [];

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
    } else if (!emailExists($conn, $user['email'])){
        $errors['email'] = 'Пользователь с таким email уже существует';
    }

    if (empty($errors['email'])) {
        if (empty($errors['password']) &&
            $error = validateTextLength($user['password'], 
                $maxLengthPassword, $minLengthPassword)) {
            $errors['password'] = $error;
        } else {
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        }

        if (empty($errors['name']) &&
            $error = validateTextLength($user['name'], $maxLengthName)) {
            $errors['name'] = $error;
        }

        if (empty($errors['contacts']) &&
            $error = validateTextLength($user['contacts'], $maxLengthText)) {
            $errors['contacts'] = $error;
        }
    }
    $errors = array_filter($errors);
    return ['errors' => $errors, 'user' => $user];
}
 
/**
 * DOCS !!!
 * Валидирует форму входа для зарегистрированного пользователя
 * @param mysqli $conn  Ресурс подключения к БД
 * @return array        Возвращает массив с описаниями ошибок
 *  по каждому полю валидируемой формы и массив с данными 
 *  пользователя, входящего на сайт, в случае отсутствия ошибок
 */
function validateLoginForm(mysqli $conn): array
{
    $user = [];
    $errors = [];

    $formData = array_map(function($value) {
        return is_string($value) ? trim($value) : $value;
    }, $_POST);

    $required = [
        'email',
        'password',
    ];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле должно быть заполнено';
        }
    }

    if (empty($errors['email'])) {
        if (filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $user = getUserByEmail($conn, $formData['email']);
            if (!$user) {
                $errors['email'] = 'Пользователь с таким email не найден';
            }
        } else {
            $errors['email'] = 'Некорректный формат email';
        }
    }

    if (empty($errors) && $user) {
        if (password_verify($formData['password'], $user['password'])) {
            return ['errors' => [], 'user' => $user];
        }
        $errors['password'] = 'Вы ввели неверный пароль';
    }
    return ['errors' => $errors, 'user' => null];
}
