<?php

namespace App\models;

use App\core\BaseModel;
use PDO;

class File extends BaseModel
{
    protected $table = 'files';

    public function saveFile(array $data){
        $sql = "INSERT INTO {$this->table} 
                (filename, project_id, uploaded_at) 
                VALUES (:filename, :project_id, :uploaded_at)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':filename' => $data['filename'],
            ':project_id' => $data['project_id'],
            ':uploaded_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getFileById($id){
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getFilesByProject($projectId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE project_id = :project_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':project_id' => $projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteFile($id){
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}