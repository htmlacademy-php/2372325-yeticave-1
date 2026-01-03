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
    $formatToCheck = 'Y-m-d';
    $dateTimeObj = date_create_from_format($formatToCheck, $date);

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
 *  если описание включает в себя
 *  не менее 3 и не более 3000 символов.
 *  Иначе возвращает строку с ошибкой.
 */
function validateMessage(string $message, int $min = 3, int $max = 3000): string
{
    $text = trim($message);
    $length = mb_strlen($text);
    if ($length < $min) {
        return "Минимальная длина — $min символа";
    }
    if ($length > $max) {
        return "Максимальная длина — $max символов";
    }
    return '';
}
