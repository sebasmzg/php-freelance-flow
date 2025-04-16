<?php
namespace App\core;

use PDO;
use PDOException;

class DB
{
        private $host;
        private $db_name;
        private $username;
        private $password;

        public function __construct()
        {
            $this->host = $_ENV['DB_HOST'] ?? 'localhost';
            $this->db_name = $_ENV['DB_NAME'] ?? 'your_database_name';
            $this->username = $_ENV['DB_USERNAME'] ?? 'your_username';
            $this->password = $_ENV['DB_PASSWORD'] ?? 'your_password';
        }

    public function connect(){
            try {
                $pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch(PDOException $e) {
                echo "Error in connection to DB: " . $e->getMessage();
                throw new PDOException("Connection error: " . $e->getMessage());
            }
        }
}