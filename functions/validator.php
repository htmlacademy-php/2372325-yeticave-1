<?php
/**
 * Проверяет переданную дату на соответствие параметрам
 * @param string $date Дата в виде строки
 * @return string Возвращает пустую строку,
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
 * @param string $lotPrice Сумма в виде строки
 * @return string возвращает сообщение об ошибке,
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
 * @param string $message Содержимое поля
 * @param int $min Минимальная длина (по умолчанию 3)
 * @param int $max Максимальная длина (по умолчанию 3000)
 * @return string Возвращает пустую строку,
 *  если длина текста не менее 3 символов и не превышает максимальной.
 *  Иначе возвращает строку с ошибкой.
 */
function validateMessage(string $message, int $max = 3000, int $min = 3): string
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
 * @param string $email Содержимое поля
 * @param int $max Максимальная длина (по умолчанию 128)
 * @return string Возвращает пустую строку,
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
 * DOCS!!!
 */
function validatePassword(string $password, int $max = 60): string
{
    return '';
}

/**
 * Валидирует форму добавления лота
 * @param array $categories Массив доступных категорий
 * @return array Возвращает с описаниями ошибок
 *  по каждому полю валидируемой формы и 
 *  массив с данными нового лота
 *  в случае отсутствия ошибок
 */
function validateLotForm(array $categories): array 
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
        $errors['description'] = validateMessage($lot['description']);
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
        
        if (in_array($fileType, ["image/jpeg", "image/png"])) {
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

