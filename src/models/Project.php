<?php

namespace App\models;

use App\core\BaseModel;
use PDO;

class Project extends BaseModel
{
    protected $table = 'projects';

    public function createProject(array $data){
        $sql = "INSERT INTO {$this->table} (title, description, start_date, delivery_date, state, user_id, created_at) VALUES (:title, :description, :start_date, :delivery_date, :state, :user_id, :created_at)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':start_date' => $data['start_date'],
            ':delivery_date' => $data['delivery_date'],
            ':state' => $data['state'],
            ':user_id' => $data['user_id'],
            ':created_at' => $data['created_at']
        ]);
    }

    public function getProjectsByUser($userId){
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjectById($id, $userId){
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProject($id, array $data){
        $sql = "UPDATE {$this->table} 
                SET title = :title, description = :description, start_date = :start_date, delivery_date = :delivery_date, state = :state 
                WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':start_date' => $data['start_date'],
            ':delivery_date' => $data['delivery_date'],
            ':state' => $data['state']
        ]);
    }

    public function deleteProject($id, $userId){
        $sql = "DELETE FROM {$this->table} WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
}