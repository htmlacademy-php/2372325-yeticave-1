<?php
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
error_reporting(E_ALL);

require_once __DIR__ . "/functions/helpers.php";
require_once __DIR__ . "/functions/db.php";
require_once __DIR__ . "/functions/template.php";

$config = require_once __DIR__ . "/config.php";
$conn = dbConnect($config);
