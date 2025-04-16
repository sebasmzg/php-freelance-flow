<?php

use App\controllers\UserController;
use App\controllers\ProjectController;
use App\controllers\PrivateController;
use App\middleware\AuthMiddleware;
use App\controllers\FileController;

$privateController = new PrivateController();
$userController = new UserController();
$projectsController = new ProjectController();
$fileController = new FileController();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

//projects routes

if (preg_match('#^/projects/get/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
    (new AuthMiddleware())->handle();
    $id = $matches[1];
    $projectsController->getProject($id);
    exit;
}

if (preg_match('#^/projects/update/(\d+)$#', $requestUri, $matches) && $requestMethod === 'PUT') {
    (new AuthMiddleware())->handle();
    $id = $matches[1];
    $projectsController->updateProject($id);
    exit;
}

if (preg_match('#^/projects/delete/(\d+)$#', $requestUri, $matches) && $requestMethod === 'DELETE') {
    (new AuthMiddleware())->handle();
    $id = $matches[1];
    $projectsController->deleteProject($id);
    exit;
}

//files routes

if (preg_match('#^/projects/(\d+)/files/upload$#', $requestUri, $matches) && $requestMethod === 'POST') {
    (new AuthMiddleware())->handle();
    $projectId = $matches[1];
    $fileController->uploadFile($projectId);
    exit;
}

if (preg_match('#^/projects/(\d+)/files$#', $requestUri, $matches) && $requestMethod === 'GET') {
    (new AuthMiddleware())->handle();
    $projectId = $matches[1];
    $fileController->listFiles($projectId);
    exit;
}

if (preg_match('#^/projects/(\d+)/files/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
    (new AuthMiddleware())->handle();
    $projectId = $matches[1];
    $fileId = $matches[2];
    $fileController->getFile($projectId, $fileId);
    exit;
}

if (preg_match('#^/projects/(\d+)/files/(\d+)$#', $requestUri, $matches) && $requestMethod === 'DELETE') {
    (new AuthMiddleware())->handle();
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
            (new AuthMiddleware())->handle();
            $data = json_decode(file_get_contents("php://input"), true);
            $projectsController->createProject($data);
        }
        break;

    case '/projects':
        if ($requestMethod === 'GET') {
            (new AuthMiddleware())->handle();
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