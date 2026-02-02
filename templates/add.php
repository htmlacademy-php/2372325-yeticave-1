<?php
    /**
     * @var array $categories   Массив досупных категорий
     * @var array $errors       Массив ошибок при заполнении формы
     * @var array $lot          Массив данных нового лота
     */
?>

<main>
    <?= includeTemplate('navBar.php', [
        'categories' => $categories,
    ]); ?>

    <form class="form form--add-lot container <?= !empty($errors) ? 'form--invalid' : ''; ?>"
          action="/add.php"
          method="post"
          enctype="multipart/form-data">

        <h2>Добавление лота</h2>

        <div class="form__container-two">
            <div class="form__item <?= isset($errors['title']) ? 'form__item--invalid' : ''; ?>">
                <label for="title">Наименование <sup>*</sup></label>
                <input id="title" type="text" name="title"
                       placeholder="Введите наименование лота"
                       value="<?= htmlspecialchars($lot['title'] ?? ''); ?>">
                <span class="form__error"><?= $errors['title'] ?? ''; ?></span>
            </div>

            <div class="form__item <?= isset($errors['category_id']) ? 'form__item--invalid' : ''; ?>">
                <label for="category_id">Категория <sup>*</sup></label>
                <select id="category_id" name="category_id">
                    <option value="">Выберите категорию</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id']; ?>"
                            <?= (isset($lot['category_id']) &&
                                (string)$lot['category_id'] === (string)$category['id'])
                                ? 'selected' : '';
                            ?>>
                            <?= htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?= $errors['category_id'] ?? ''; ?></span>
            </div>
        </div>

        <div class="form__item form__item--wide <?= isset($errors['description']) ? 'form__item--invalid' : ''; ?>">
            <label for="description">Описание <sup>*</sup></label>
            <textarea id="description" name="description" placeholder="Напишите описание лота"><?= htmlspecialchars($lot['description'] ?? ''); ?></textarea>
            <span class="form__error"><?= $errors['description'] ?? ''; ?></span>
        </div>

        <div class="form__item form__item--file <?= isset($errors['image_url']) ? 'form__item--invalid' : ''; ?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="image_url" name="image_url">
                <label for="image_url">Добавить</label>
            </div>
            <span class="form__error"><?= $errors['image_url'] ?? ''; ?></span>
        </div>

        <div class="form__container-three">
            <div class="form__item form__item--small <?= isset($errors['start_price']) ? 'form__item--invalid' : ''; ?>">
                <label for="start_price">Начальная цена <sup>*</sup></label>
                <input id="start_price" type="text" name="start_price" placeholder="0"
                       value="<?= htmlspecialchars($lot['start_price'] ?? ''); ?>">
                <span class="form__error"><?= $errors['start_price'] ?? ''; ?></span>
            </div>

            <div class="form__item form__item--small <?= isset($errors['bid_step']) ? 'form__item--invalid' : ''; ?>">
                <label for="bid_step">Шаг ставки <sup>*</sup></label>
                <input id="bid_step" type="text" name="bid_step" placeholder="0"
                       value="<?= htmlspecialchars($lot['bid_step'] ?? ''); ?>">
                <span class="form__error"><?= $errors['bid_step'] ?? ''; ?></span>
            </div>

            <div class="form__item <?= isset($errors['end_at']) ? 'form__item--invalid' : ''; ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="end_at"
                       placeholder="Введите дату в формате ГГГГ-ММ-ДД"
                       value="<?= htmlspecialchars($lot['end_at'] ?? ''); ?>">
                <span class="form__error"><?= $errors['end_at'] ?? ''; ?></span>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <span class="form__error form__error--bottom">
                Пожалуйста, исправьте ошибки в форме
            </span>
        <?php endif; ?>

        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
