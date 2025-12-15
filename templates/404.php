<?php 
    /**
    * @var array $categories
    * @var string $title
    * @var string $headerContent
    * @var string $footerContent
    */
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title><?= $title ?></title>
        <link href="css/normalize.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
    </head>
    
    <body>
        <div class="page-wrapper">
            <?= $headerContent; ?>
        
            <main>
                <nav class="nav">
                    <ul class="nav__list container">
                        <?php foreach ($categories as $category): ?>
                            <li class="nav__item">
                                <a href="pages/all-lots.html">
                                    <?= htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
                
                <section class="lot-item container">
                    <h2>404 Страница не найдена</h2>
                    <p>Данной страницы не существует на сайте.</p>
                </section>
            </main>
        </div>
    
        <?= $footerContent; ?>
        <script src="js/flatpickr.js"></script>
        <script src="js/script.js"></script>  
    </body>
</html>
