<?php
    /**
    * @var string $pageContent    Основное содержание
    * @var bool   $isAuth         Статус авторизации
    * @var string $userName       Имя пользователя
    * @var array  $categories     Массив доступных категорий
    */
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title><?= $title; ?></title>
        <link href="/css/normalize.min.css" rel="stylesheet">
        <link href="/css/style.css" rel="stylesheet">
        <link href="/css/flatpickr.min.css" rel="stylesheet">
    </head>

    <body>
        <div class="page-wrapper">
            <?= includeTemplate('header.php', [
                'isAuth'   => $isAuth,
                'userName' => $userName,
            ]); ?>
            
            <?= $pageContent; ?>
        </div>

        <?= includeTemplate('footer.php', [
            'categories' => $categories
        ]); ?>
        <script src="/js/flatpickr.js"></script>
        <script src="/js/script.js"></script>
    </body>
</html>
