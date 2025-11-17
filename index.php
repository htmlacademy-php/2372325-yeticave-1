<?php
require_once('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Илья'; // укажите здесь ваше имя

$categories = [
        ['name' => 'Доски и лыжи', 'class' => 'promo__item--boards'],
        ['name' => 'Крепления',    'class' => 'promo__item--attachment'],
        ['name' => 'Ботинки',      'class' => 'promo__item--boots'],
        ['name' => 'Одежда',       'class' => 'promo__item--clothing'],
        ['name' => 'Инструменты',  'class' => 'promo__item--tools'],
        ['name' => 'Разное',       'class' => 'promo__item--other']
];

$products = [
    ['name'        => '2014 Rossignol District Snowboard',
        'category' => $categories[0]['name'],
        'price'    => 10999,
        'img_url'  => 'img/lot-1.jpg',
        'exp_date' => new DateTime('2025-11-01')],
    ['name'        => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => $categories[0]['name'],
        'price'    => 159999,
        'img_url'  => 'img/lot-2.jpg',
        'exp_date' => new DateTime('2025-11-02')],
    ['name'        => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => $categories[1]['name'],
        'price'    => 8000,
        'img_url'  => 'img/lot-3.jpg',
        'exp_date' => new DateTime('2025-10-31')],
    ['name'        => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => $categories[2]['name'],
        'price'    => 10999,
        'img_url'  => 'img/lot-4.jpg',
        'exp_date' => new DateTime('2025-11-11')],
    ['name'        => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => $categories[3]['name'],
        'price'    => 7500,
        'img_url'  => 'img/lot-5.jpg',
        'exp_date' => new DateTime('2025-11-01')],
    ['name'        => 'Маска Oakley Canopy',
        'category' => $categories[5]['name'],
        'price'    => 5400,
        'img_url'  => 'img/lot-6.jpg',
        'exp_date' => new DateTime('2025-10-31 20:20')]
];

function get_price($price): string
{
    $price = ceil($price);
    if ($price > 1000) {
        $price = number_format($price, 0, '', ' ');
    }
    return $price . ' ₽';
}

function time_left($date): array
{
    $cur_date = date_create();
    $cur_timestamp = $cur_date->getTimestamp();

    $exp_timestamp = $date->getTimestamp();
    $cnt = $exp_timestamp - $cur_timestamp;
    if ($cnt <= 0) {
        return ["00", "00"];
    }

    $diff = date_diff($date, $cur_date);
    $days = date_interval_format($diff, "%d");
    if ($days != 0) {
        return ["23", "59"];
    }

    $hrs = date_interval_format($diff, "%H");
    $mins = date_interval_format($diff, "%I");
    return [$hrs, $mins];
}

$page_content = include_template('main.php', [
    'categories' => $categories,
    'products'   => $products
]);

$layout_content = include_template('layout.php',[
    'page_content' => $page_content,
    'is_auth'      => $is_auth,
    'user_name'    => $user_name,
    'categories'   => $categories,
    'title'        => 'YetyCave - Главная страница'
]);

print($layout_content);
