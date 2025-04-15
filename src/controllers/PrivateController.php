<?php

namespace App\controllers;

use App\middleware\AuthMiddleware;

class PrivateController
{
    private AuthMiddleware $authMiddleware;

    public function __construct(){
        $this->authMiddleware = new AuthMiddleware();
    }

    public function privateRoute(){
        $userData = $this->authMiddleware->handle();

        http_response_code(200);
        echo json_encode([
            "message" => "Access granted to private route",
            "user" => $userData
        ]);
    }
}