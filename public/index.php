<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

require_once __DIR__ . "/../src/core/core.php";
require_once __DIR__ . "/../src/routes.php";