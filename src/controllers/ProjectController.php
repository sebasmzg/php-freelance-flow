<?php

namespace App\controllers;

use App\middleware\AuthMiddleware;
use App\models\Project;

class ProjectController
{
    private $projectModel;
    private $user;

    public function __construct()
    {
        $this->projectModel = new Project();
        $this->user = (new AuthMiddleware())->handle();
    }

    public function createProject(array $data){

        if (
            empty($data['title']) ||
            empty($data['description']) ||
            empty($data['start_date']) ||
            empty($data['delivery_date']) ||
            empty($data['state'])) {
            http_response_code(400);
            echo json_encode(["error" => "All fields are required"]);
            return;
        }

        $project = [
            "title" => $data["title"],
            "description" => $data["description"],
            "start_date" => $data["start_date"],
            "delivery_date" => $data["delivery_date"],
            "state" => $data["state"],
            "user_id" => $this->user->id,
            "created_at" => date('Y-m-d H:i:s')
        ];

        if($this->projectModel->createProject($project)) {
            http_response_code(201);
            echo json_encode(["message" => "Project created successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error creating project"]);
        }
    }

    public function listProjects(){
        $projects = $this->projectModel->getProjectsByUser($this->user->id);

        if($projects === false){
            http_response_code(500);
            echo json_encode(["error" => "Error fetching projects"]);
            return;
        }
        http_response_code(200);
        echo json_encode($projects);
    }

    public function getProject($id){
        $project = $this->projectModel->getProjectById($id, $this->user->id);

        if ($project) {
            http_response_code(200);
            echo json_encode($project);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Project not found"]);
        }
    }

    public function updateProject($id){
        $data = json_decode(file_get_contents("php://input",true), true);

        if (
            empty($data['title']) ||
            empty($data['description']) ||
            empty($data['start_date']) ||
            empty($data['delivery_date']) ||
            empty($data['state'])) {
            http_response_code(400);
            echo json_encode(["error" => "All fields are required"]);
            return;
        }

        $data['user_id'] = $this->user->id;

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
        if ($this->projectModel->deleteProject($id, $this->user->id)) {
            http_response_code(200);
            echo json_encode(["message" => "Project deleted successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error deleting project"]);
        }
    }
}