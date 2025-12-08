<?php

$isAuth = rand(0, 1);
$userName = "Илья";

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 * @return string Рассчитанная форма множественного числа
 */
function getNounPluralForm (
    int $number,
    string $one,
    string $two,
    string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    return match (true) {
        $mod100 >= 11 && $mod100 <= 20 => $many,
        $mod10 === 1 => $one,
        $mod10 >= 2 && $mod10 <= 4 => $two,
        default => $many,
    };
}

/**
 * Форматирует цену товара в рублях
 * @param int $price Номинальная стоимость товара
 * @return string Отформатированный вывод цены со знаком рубля
 */
function formatPrice(int $price): string
{
    $price = number_format($price, 0, "", " ");
    return "{$price}<b class='rub'>р</b>";
}

/**
 * Подсчитывает оставшееся время до указанной даты
 * @param string $date Дата, до которой считается время
 * @return array Массив с оставшимся временем в формате [часы, минуты]
 */
function timeLeft(string $date): array
{
    try {
        $date = new DateTime($date);
    } catch (Throwable $e) {
        error_log($e->getMessage());
        return ["00", "00"];
    }

    $currentDate = date_create();
    $cnt = $date->getTimestamp() - $currentDate->getTimestamp();

    if ($cnt <= 0) {
        return ["00", "00"];
    }

    $hrs = intval(round($cnt/3600));
    $min = intval($cnt % 3600 / 60);

    return [str_pad($hrs, 2, '0', STR_PAD_LEFT),
        str_pad($min, 2, '0', STR_PAD_LEFT)];
}
