<?php
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
error_reporting(E_ALL);

require_once __DIR__ . "/helpers.php";

$isAuth = rand(0, 1);
$userName = "Илья";

$categories = [
    ["name" => "Доски и лыжи", "class-tag" => "boards"],
    ["name" => "Крепления", "class-tag" => "attachment"],
    ["name" => "Ботинки", "class-tag" => "boots"],
    ["name" => "Одежда", "class-tag" => "clothing"],
    ["name" => "Инструменты", "class-tag" => "tools"],
    ["name" => "Разное", "class-tag" => "other"],
];

$lots = [
    [
        "name" => "2014 Rossignol District Snowboard",
        "category" => $categories[0]['name'],
        "price" => 10999,
        "imgUrl" => "img/lot-1.jpg",
        "expiryDate" => "2025-12-01",
    ],
    [
        "name" => "DC Ply Mens 2016/2017 Snowboard",
        "category" => $categories[0]['name'],
        "price" => 159999,
        "imgUrl" => "img/lot-2.jpg",
        "expiryDate" => "2025-12-02",
    ],
    [
        "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
        "category" => $categories[1]['name'],
        "price" => 8000,
        "imgUrl" => "img/lot-3.jpg",
        "expiryDate" => "2025-11-30",
    ],
    [
        "name" => "Ботинки для сноуборда DC Mutiny Charocal",
        "category" => $categories[2]['name'],
        "price" => 10999,
        "imgUrl" => "img/lot-4.jpg",
        "expiryDate" => "2025-11-29",
    ],
    [
        "name" => "Куртка для сноуборда DC Mutiny Charocal",
        "category" => $categories[3]['name'],
        "price" => 7500,
        "imgUrl" => "img/lot-5.jpg",
        "expiryDate" => "2025-11-15",
    ],
    [
        "name" => "Маска Oakley Canopy",
        "category" => $categories[5]['name'],
        "price" => 5400,
        "imgUrl" => "img/lot-6.jpg",
        "expiryDate" => "2025-11-27 20:55",
    ],
];

$pageContent = includeTemplate("main.php", [
    "categories" => $categories,
    "lots" => $lots,
]);

$layoutContent = includeTemplate("layout.php", [
    "pageContent" => $pageContent,
    "isAuth" => $isAuth,
    "userName" => $userName,
    "categories" => $categories,
    "title" => "YetiCave - Главная",
]);

print $layoutContent;
