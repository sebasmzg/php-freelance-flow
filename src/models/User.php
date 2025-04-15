<?php
namespace App\models;

use App\core\BaseModel;
use PDO;

class User extends BaseModel
{
    private $table = "users";

    public function createUser(array $data){
        $sql = "INSERT INTO {$this->table} (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }

    public function getUserByEmail($email){
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}