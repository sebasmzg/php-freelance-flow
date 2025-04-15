<?php

namespace App\controllers;

use App\middleware\AuthMiddleware;
use App\models\Project;

class ProjectController
{
    private $projectModel;
    private $authMiddleware;

    public function __construct()
    {
        $this->projectModel = new Project();
        $this->authMiddleware = new AuthMiddleware();
    }

    public function createProject(){
        $userData = $this->authMiddleware->handle();
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['title']) || empty($data['description']) || empty($data['start_date']) || empty($data['delivery_date']) || empty($data['state'])) {
            http_response_code(400);
            echo json_encode(["error" => "Todos los campos son obligatorios"]);
            return;
        }

        $data['user_id'] = $userData->userId;
        $data['created_at'] = date("Y-m-d H:i:s");

        if($this->projectModel->createProject($data)){
            http_response_code(201);
            echo json_encode(["message" => "Project created successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error creating project"]);
        }
    }

    public function listProjects(){
        $userData = $this->authMiddleware->handle();
        $projects = $this->projectModel->getProjectsByUser($userData->userId);

        http_response_code(200);
        echo json_encode($projects);
    }

    public function getProject($id){
        $userData = $this->authMiddleware->handle();
        $project = $this->projectModel->getProjectById($id, $userData->userId);

        if ($project) {
            http_response_code(200);
            echo json_encode($project);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Project not found"]);
        }
    }

    public function updateProject($id){
        $userData = $this->authMiddleware->handle();
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['title']) || empty($data['description']) || empty($data['start_date']) || empty($data['delivery_date']) || empty($data['state'])) {
            http_response_code(400);
            echo json_encode(["error" => "All fields are required"]);
            return;
        }

        if($this->projectModel->updateProject($id, $data)){
            http_response_code(200);
            echo json_encode(["message" => "Project updated successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error updating project"]);
        }
    }

    public function deleteProject($id)
    {
        $userData = $this->authMiddleware->handle();

        if ($this->projectModel->deleteProject($id, $userData->userId)) {
            http_response_code(200);
            echo json_encode(["message" => "Project deleted successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error deleting project"]);
        }
    }
}