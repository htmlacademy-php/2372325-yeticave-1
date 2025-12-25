<?php
/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name  Путь к файлу шаблона относительно папки templates
 * @param array $data   Ассоциативный массив с данными для шаблона
 * @return string       Итоговый HTML
 */
function includeTemplate(string $name, array $data = []): string
{
    $name = "templates/{$name}";

    if (!is_readable($name)) {
        return 'Ошибка загрузки шаблона';
    }
    ob_start();
    extract($data);
    require $name;

    return ob_get_clean();
}
