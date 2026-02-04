<?php 
    /** 
     * @var array $categories Массив доступных категорий
     */ 
?>

<main>
    <?= includeTemplate('navBar.php', [
        'categories' => $categories,
    ]); ?>

    <section class="lot-item container">
        <h2>403 Необходима авторизация</h2>
        <p>Пожалуйста, зарегистрируйтесь.</p>
    </section>
</main>
