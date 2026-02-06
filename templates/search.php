<?php
    /**
     * @var array  $categories  Массив досупных категорий
     * @var array  $lots        Массив найденных лотов
     * @var string $search      Строка поискового запроса
     */
?>

<main>
    <?= includeTemplate('navBar.php', [
        'categories' => $categories,
    ]); ?>

    <div class="container">
        <?php if ($lots) : ?>
            <section class="lots">
                <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($search); ?></span>»</h2>

                <ul class="lots__list">
                    <?php foreach ($lots as $lot) : ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img    src="<?= htmlspecialchars($lot['image_url']); ?>" 
                                        width="350" 
                                        height="260" 
                                        alt="<?= htmlspecialchars($lot['description']); ?>"
                                >
                            </div>

                            <div class="lot__info">
                                <span class="lot__category">
                                    <?= htmlspecialchars($lot['category']); ?>
                                </span>
                            
                                <h3 class="lot__title">
                                    <a class="text-link" href="/lot.php?id=<?= $lot['id']; ?>">
                                        <?= htmlspecialchars($lot['title']); ?>
                                    </a>
                                </h3>
                            
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">
                                            Стартовая цена
                                        </span>

                                        <span class="lot__cost">
                                            <?= formatPrice($lot['start_price']); ?>
                                        </span>
                                    </div>
                                
                                    <?php
                                        $timeLeft = timeLeft($lot['end_at']);
                                        $timerClass = $timeLeft[0] == 0 ? 'timer--finishing' : '';
                                    ?>

                                    <div class="lot__timer timer <?= htmlspecialchars($timerClass); ?>">
                                        <?= htmlspecialchars("{$timeLeft[0]}: {$timeLeft[1]}"); ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach ; ?>
                </ul>
            </section>

            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev">
                    <a>Назад</a>
                </li>
                <li class="pagination-item pagination-item-active">
                    <a>1</a>
                </li>
                <li class="pagination-item">
                    <a href="#">2</a>
                </li>
                <li class="pagination-item">
                    <a href="#">3</a>
                </li>
                <li class="pagination-item">
                    <a href="#">4</a>
                </li>
                <li class="pagination-item pagination-item-next">
                    <a href="#">Вперед</a>
                </li>
            </ul>
        <?php else : ?>
            <section class="lot-item container">
                <h2>Ничего не найдено по вашему запросу</h2>
            </section>
        <?php endif ; ?>
    </div>
</main>