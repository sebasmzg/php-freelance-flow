<?php

use App\controllers\UserController;
use App\controllers\PrivateController;

$privateController = new PrivateController();
$userController = new UserController();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

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