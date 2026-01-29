<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$isAuth = rand(0, 1);
$userName = "Илья";

require_once __DIR__ . '/functions/db.php';
require_once __DIR__ . '/functions/template.php';

if (!file_exists(__DIR__ . '/config.php')) {
    die('Файл конфигурации не найден');
}

$config = require_once __DIR__ . '/config.php';
$conn = dbConnect($config['db']);
