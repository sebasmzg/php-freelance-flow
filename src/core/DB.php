<?php
namespace App\core;

use PDO;
use PDOException;

class DB
{
        private $host = "127.0.0.1";
        private $db_name = "freelance_flow";
        private $username = "root";
        private $password = "password";

        public function connect(){
            try {
                $pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch(PDOException $e) {
                echo "Connection error: " . $e->getMessage();
            }
        }
}