<?php
    /**
     * @var array  $categories   Массив доступных категорий
     * @var array  $lots         Массив найденных лотов
     * @var string $search       Строка поискового запроса
     * @var int    $currentPage  Текущая страница
     * @var int    $pagesCount   Общее количество страниц
     * @var array  $pages        Массив с номерами страниц [1, 2, 3...]
     */
?>

<main>
    <?= includeTemplate('navBar.php', [
        'categories' => $categories,
    ]); ?>

    <div class="container">
        <?php if (!empty($lots)) : ?>
            <section class="lots">
                <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($search); ?></span>»</h2>

                <ul class="lots__list">
                    <?php foreach ($lots as $lot) : ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="<?= htmlspecialchars($lot['image_url']); ?>" 
                                     width="350" height="260" 
                                     alt="<?= htmlspecialchars($lot['title']); ?>">
                            </div>

                            <div class="lot__info">
                                <span class="lot__category"><?= htmlspecialchars($lot['category']); ?></span>
                                <h3 class="lot__title">
                                    <a class="text-link" href="/lot.php?id=<?= $lot['id']; ?>">
                                        <?= htmlspecialchars($lot['title']); ?>
                                    </a>
                                </h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">Стартовая цена</span>
                                        <span class="lot__cost"><?= formatPrice($lot['start_price']); ?></span>
                                    </div>
                                <?php
                                    $timeLeft = timeLeft($lot['end_at']);
                                    $timerClass = $timeLeft[0] == 0 ? 'timer--finishing' : '';
                                ?>

                                <div class="lot__timer timer <?= htmlspecialchars($timerClass); ?>">
                                    <?= htmlspecialchars("{$timeLeft[0]}: {$timeLeft[1]}"); ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach ; ?>
                </ul>
            </section>

            <?php if ($pagesCount > 1) : ?>
                <ul class="pagination-list">
                    <li class="pagination-item pagination-item-prev">
                        <a <?php if ($currentPage > 1): ?> 
                            href="/search.php?search=<?= htmlspecialchars($search); ?>&page=<?= $currentPage - 1; ?>" 
                            <?php endif; ?>>Назад</a>
                    </li>

                    <?php foreach ($pages as $page) : ?>
                        <li class="pagination-item <?= ((int)$page === $currentPage) ? 'pagination-item-active' : ''; ?>">
                            <a href="/search.php?search=<?= htmlspecialchars($search); ?>&page=<?= $page; ?>"><?= $page; ?></a>
                        </li>
                    <?php endforeach; ?>

                    <li class="pagination-item pagination-item-next">
                        <a <?php if ($currentPage < $pagesCount): ?> 
                            href="/search.php?search=<?= htmlspecialchars($search); ?>&page=<?= $currentPage + 1; ?>" 
                            <?php endif; ?>>Вперед</a>
                    </li>
                </ul>
            <?php endif; ?>

        <?php else : ?>
            <section class="lot-item container">
                <h2>Ничего не найдено по вашему запросу «<?= htmlspecialchars($search); ?>»</h2>
            </section>
        <?php endif ; ?>
    </div>
</main>
