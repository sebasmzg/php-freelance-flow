<?php

use App\controllers\UserController;
use App\controllers\ProjectController;
use App\middleware\AuthMiddleware;
use App\controllers\FileController;

function handleRoutesRequest(){
    $userController = new UserController();
    $projectsController = new ProjectController();
    $fileController = new FileController();
    $authMiddleware = new AuthMiddleware();

    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $requestMethod = $_SERVER['REQUEST_METHOD'];

//projects routes

    if (preg_match('#^/projects/get/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
        $authMiddleware->handle();
        $id = $matches[1];
        $projectsController->getProject($id);
        exit;
    }

    if (preg_match('#^/projects/update/(\d+)$#', $requestUri, $matches) && $requestMethod === 'PUT') {
        $authMiddleware->handle();
        $id = $matches[1];
        $projectsController->updateProject($id);
        exit;
    }

    if (preg_match('#^/projects/delete/(\d+)$#', $requestUri, $matches) && $requestMethod === 'DELETE') {
        $authMiddleware->handle();
        $id = $matches[1];
        $projectsController->deleteProject($id);
        exit;
    }

//files routes

    if (preg_match('#^/projects/(\d+)/files/upload$#', $requestUri, $matches) && $requestMethod === 'POST') {
        $authMiddleware->handle();
        $projectId = $matches[1];
        $fileController->uploadFile($projectId);
        exit;
    }

    if (preg_match('#^/projects/(\d+)/files$#', $requestUri, $matches) && $requestMethod === 'GET') {
        $authMiddleware->handle();
        $projectId = $matches[1];
        $fileController->listFiles($projectId);
        exit;
    }

    if (preg_match('#^/projects/(\d+)/files/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
        $authMiddleware->handle();
        $projectId = $matches[1];
        $fileId = $matches[2];
        $fileController->getFile($projectId, $fileId);
        exit;
    }

    if (preg_match('#^/projects/(\d+)/files/(\d+)$#', $requestUri, $matches) && $requestMethod === 'DELETE') {
        $authMiddleware->handle();
        $projectId = $matches[1];
        $fileId = $matches[2];
        $fileController->deleteFile($projectId, $fileId);
        exit;
    }

    switch ($requestUri) {
        case '/register':
            if ($requestMethod === 'POST') {
                $data = json_decode(file_get_contents("php://input"), true);
                $userController->registerUser($data);
            }
            break;

        case '/login':
            if ($requestMethod === 'POST') {
                $userController->login();
            }
            break;

        case '/projects/create':
            if ($requestMethod === 'POST') {
                $authMiddleware->handle(); // Protege la ruta
                $data = json_decode(file_get_contents("php://input"), true);
                // Verifica que los datos sean válidos
                if ($data) {
                    $projectsController->createProject($data);
                } else {
                    http_response_code(400);
                    echo json_encode([
                        "error" => "Datos no válidos"
                    ]);
                }
            }
            break;

        case '/projects':
            if ($requestMethod === 'GET') {
                $authMiddleware->handle(); // Protege la ruta
                $projectsController->listProjects();
            }
            break;

        default:
            http_response_code(404);
            echo json_encode([
                "error" => "Ruta no encontrada"
            ]);
            break;
    }
}
