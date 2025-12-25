<?php
    /**
    * @var string $headerContent
    * @var string $pageContent
    * @var string $footerContent
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
            <?= $headerContent; ?>
            <?= $pageContent; ?>
        </div>

        <?= $footerContent; ?>
        <script src="/js/flatpickr.js"></script>
        <script src="/js/script.js"></script>
    </body>
</html>
