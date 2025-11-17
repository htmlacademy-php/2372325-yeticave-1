<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($categories as $category): ?>
            <li class="promo__item <?= $category['class']; ?>">
                <a class="promo__link" href="pages/all-lots.html">
                    <?= htmlspecialchars($category['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        <?php foreach ($products as $product): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $product['img_url'] ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($product['category']); ?></span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html">
                            <?= htmlspecialchars($product['name']); ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost">
                                <?= htmlspecialchars(get_price($product['price'])); ?>
                                <b class="rub">р</b>
                            </span>
                        </div>
                        <?php
                        $timeLeft = time_left($product['exp_date']);
                        $timerClass = $timeLeft[0] == 0 ? 'timer--finishing' : '';
                        ?>
                        <div class="lot__timer timer <?= $timerClass; ?>">
                            <?= "{$timeLeft[0]}: {$timeLeft[1]}"; ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

