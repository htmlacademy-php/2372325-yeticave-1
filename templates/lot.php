<?php
    /**
    * @var array $lot           Массив с данными выбранного лота
    * @var array $categories    Массив доступных категорий
    * @var bool  $isAuth        Статус авторизации
    */
?>

<main>
    <?= includeTemplate('navBar.php', [
        'categories' => $categories,
    ]); ?>

    <section class="lot-item container">
        <h2><?= $lot['name']; ?></h2>

        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img    src="<?= htmlspecialchars($lot['imgUrl']); ?>"
                            width="730"
                            height="548"
                            alt="<?= htmlspecialchars($lot['description']); ?>"
                    />
                </div>

                <p class="lot-item__category"> Категория:
                    <span>
                        <?= htmlspecialchars($lot['category']); ?>
                    </span>
                </p>

                <p class="lot-item__description">
                    <?= htmlspecialchars($lot['description']); ?>
                </p>
            </div>

            <div class="lot-item__right">
                <?php if ($isAuth) : ?>
                <div class="lot-item__state">
                    <?php
                        $timeLeft = timeLeft($lot['expiryDate']);
                        $timerClass = $timeLeft[0] == 0 ? 'timer--finishing' : '';
                    ?>
                    <div class="lot-item__timer timer <?= htmlspecialchars($timerClass); ?>">
                        <?= htmlspecialchars("{$timeLeft[0]}: {$timeLeft[1]}"); ?>
                    </div>

                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost">
                                <?= formatPrice($lot['price']); ?>
                            </span>
                        </div>
                        <!--// TODO -->
                        <div class="lot-item__min-cost">
                            Мин. ставка <span>12 000 р</span>
                        </div>
                    </div>

                    <form   class="lot-item__form"
                            action="https://echo.htmlacademy.ru"
                            method="post"
                            autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="12 000">
                            <!-- //TODO Наименование лота? -->
                            <span class="form__error">Введите наименование лота</span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
                <?php endif ;?>

                <div class="history">
                    <h3>История ставок (<span>10</span>)</h3> <!-- количество ставок -->

                    <table class="history__list"><!--цикл по имеющимся ставкам-->
                        <tr class="history__item">
                            <td class="history__name">Иван</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">5 минут назад</td>
                        </tr>
                        <!--
                        <tr class="history__item">
                            <td class="history__name">Евгений</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">Час назад</td>
                        </tr>
                        <tr class="history__item">
                            <td class="history__name">Игорь</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">19.03.17 в 08:21</td>
                        </tr>
                        <tr class="history__item">
                            <td class="history__name">Енакентий</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">19.03.17 в 13:20</td>
                        </tr>
                        -->
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
