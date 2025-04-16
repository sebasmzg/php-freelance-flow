<?php

namespace App\controllers;

use App\models\File;
use App\models\Project;
use App\middleware\AuthMiddleware;

class FileController
{
    private $fileModel;
    private $projectModel;
    private $user;

    public function __construct(){
        $this->fileModel = new File();
        $this->projectModel = new Project();
        $this->user = (new AuthMiddleware())->handle();
    }

    public function uploadFile($projectId){
        if (!$this->projectModel->getProjectById($projectId, $this->user->id)) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid project ID"]);
            return;
        }

        if (!isset($_FILES["file"]) || $_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(["error" => "File upload error"]);
            return;
        }

        $filename = pathinfo($_FILES["file"]["name"], PATHINFO_FILENAME);
        $extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        $uploadDir = __DIR__ . '/../../uploads/';
        $uploadFilePath = $uploadDir . $filename . '.' . $extension;
        $counter = 1;

        while (file_exists($uploadFilePath)) {
            $uploadFilePath = $uploadDir . $filename . "($counter)." . $extension;
            $counter++;
        }

        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $uploadFilePath)) {
            http_response_code(500);
            echo json_encode(["error" => "Error saving file"]);
            return;
        }

        $fileData = [
            "filename" => basename($uploadFilePath),
            "project_id" => $projectId,
            "uploaded_at" => date('Y-m-d H:i:s')
        ];

        if ($this->fileModel->saveFile($fileData)) {
            http_response_code(201);
            echo json_encode(["message" => "File uploaded successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error saving file data to database"]);
        }
    }

    public function listFiles($projectId){
        if (!$this->projectModel->getProjectById($projectId, $this->user->id)) {
            http_response_code(404);
            echo json_encode(["error" => "Project not found"]);
            return;
        }

        $files = $this->fileModel->getFilesByProject($projectId);

        if ($files === false) {
            http_response_code(500);
            echo json_encode(["error" => "Error fetching files"]);
            return;
        }

        http_response_code(200);
        echo json_encode($files);
    }

    public function getFile($projectId, $fileId){
        if (!$this->projectModel->getProjectById($projectId, $this->user->id)) {
            http_response_code(404);
            echo json_encode(["error" => "Project not found"]);
            return;
        }

        $file = $this->fileModel->getFileById($fileId);

        if (!$file || $file['project_id'] != $projectId) {
            http_response_code(404);
            echo json_encode(["error" => "File not found"]);
            return;
        }

        http_response_code(200);
        echo json_encode($file);
    }

    public function deleteFile($projectId, $fileId){
        if (!$this->projectModel->getProjectById($projectId, $this->user->id)) {
            http_response_code(404);
            echo json_encode(["error" => "Project not found"]);
            return;
        }

        $file = $this->fileModel->getFileById($fileId);

        if (!$file || $file['project_id'] != $projectId) {
            http_response_code(404);
            echo json_encode(["error" => "File not found"]);
            return;
        }

        $filePath = __DIR__ . '/../../uploads/' . $file['filename'];

        if ($this->fileModel->deleteFile($fileId)) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            http_response_code(200);
            echo json_encode(["message" => "File deleted successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error deleting file"]);
        }
    }
}