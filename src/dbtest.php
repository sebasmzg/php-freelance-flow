<?php
require_once __DIR__ . '/../vendor/autoload.php';

use core\DB;

$db = new DB();
$connection = $db->connect();

if ($connection) {
    echo "Conexión exitosa.";
}
