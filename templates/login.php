<?php
    /**
     * @var array $categories Массив доступных категорий
     * @var array $errors     Массив с ошибками при заполнении формы
     */
?>

<main>
    <?= includeTemplate('navBar.php', [
        'categories' => $categories,
    ]); ?>

    <form   class="form container <?= empty($errors) ? '' :  'form--invalid'; ?>" 
            action="" 
            method="post">
        <h2>Вход</h2>

        <div class="form__item 
            <?= empty($errors['email']) ? '' :  'form__item--invalid'; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input  id="email" 
                    type="text" 
                    name="email" 
                    placeholder="Введите e-mail">
            <span class="form__error">Введите e-mail</span>
        </div>

        <div class="form__item form__item--last"
            <?= empty($errors['password']) ? '' :  'form__item--invalid'; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input  id="password" 
                    type="password" 
                    name="password" 
                    placeholder="Введите пароль">
            <span class="form__error">Введите пароль</span>
        </div>

        <button type="submit" class="button">Войти</button>
    </form>
</main>