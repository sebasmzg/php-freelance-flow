<?php

use App\controllers\UserController;
use App\controllers\ProjectController;
use App\controllers\PrivateController;
use App\middleware\AuthMiddleware;

$privateController = new PrivateController();
$userController = new UserController();
$projectsController = new ProjectController();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

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


    case '/public-route':
        if ($requestMethod === 'GET') {
            echo json_encode([
                "message" => "This is a public route"
            ]);
        }
        break;

    case '/private-route':
        if ($requestMethod === 'GET') {
            $privateController->privateRoute();
        }
        break;

    default:
        http_response_code(404);
        echo json_encode([
            "error" => "Ruta no encontrada"
        ]);
        break;
}