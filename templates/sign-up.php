<?php
    /**
     * @var array $categories   Массив доступных категорий
     * @var array $errors       Массив с ошибками при заполнении формы
     * @var array $user         Массив с данными нового пользователя
     */
?>

<main>
    <?= includeTemplate('navBar.php', [
        'categories' => $categories,
    ]); ?>

    <form   class="form container <?= !empty($errors) ? 'form--invalid' : ''; ?>"
            action="/sign-up.php"
            method="post" autocomplete="off"
            enctype="multipart/form-data">

        <h2>Регистрация нового аккаунта</h2>

        <div class="form__item 
                <?= isset($errors['email']) ? 'form__item--invalid' : ''; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email"
                placeholder="Введите e-mail"
                value="<?= htmlspecialchars($user['email'] ?? ''); ?>">
            <span class="form__error"><?= $errors['email'] ?? ''; ?></span>
        </div>

        <div class="form__item 
                <?= isset($errors['password']) ? 'form__item--invalid' : ''; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password"
                placeholder="Введите пароль">
            <span class="form__error"><?= $errors['password'] ?? ''; ?></span>
        </div>

        <div class="form__item 
                <?= isset($errors['name']) ? 'form__item--invalid' : ''; ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name"
                placeholder="Введите имя"
                value="<?= htmlspecialchars($user['name'] ?? ''); ?>">
            <span class="form__error"><?= $errors['name'] ?? ''; ?></span>
        </div>

        <div class="form__item 
                <?= isset($errors['contacts']) ? 'form__item--invalid' : ''; ?>">
            <label for="contacts">Контактные данные <sup>*</sup></label>
            <textarea   id="contacts" 
                        name="contacts" 
                        placeholder="Напишите как с вами связаться"
            ><?= htmlspecialchars($user['contacts'] ?? ''); ?></textarea>
            
            <span class="form__error"><?= $errors['contacts'] ?? ''; ?></span>
        </div>

        <?php if (!empty($errors)): ?>
            <span class="form__error form__error--bottom">
                Пожалуйста, исправьте ошибки в форме
            </span>
        <?php endif; ?>

        <button type="submit" class="button">
            Зарегистрироваться
        </button>

        <a class="text-link" href="/login.php">Уже есть аккаунт</a>
    </form>
</main>
