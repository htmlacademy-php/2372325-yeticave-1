<?php
require_once('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Илья'; // укажите здесь ваше имя

$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$products = [
    ['name' => '2014 Rossignol District Snowboard',
        'category' => $categories[0],
        'price' => 10999,
        'img_url' => 'img/lot-1.jpg'],
    ['name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => $categories[0],
        'price' => 159999,
        'img_url' => 'img/lot-2.jpg'],
    ['name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => $categories[1],
        'price' => 8000,
        'img_url' => 'img/lot-3.jpg'],
    ['name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => $categories[2],
        'price' => 10999,
        'img_url' => 'img/lot-4.jpg'],
    ['name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => $categories[3],
        'price' => 7500,
        'img_url' => 'img/lot-5.jpg'],
    ['name' => 'Маска Oakley Canopy',
        'category' => $categories[5],
        'price' => 5400,
        'img_url' => 'img/lot-6.jpg']
];

function get_price($price): string
{
    $price = ceil($price);
    if ($price > 1000) {
        $price = number_format($price, 0, '', ' ');
    }
    return $price . ' ₽';
}

$page_content = include_template('main.php', [
    'categories' => $categories,
    'products' => $products
]);
$layout_content = include_template('layout.php',[
    'page_content' => $page_content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'title' => 'YetyCave - Главная страница'
]);

print($layout_content);
