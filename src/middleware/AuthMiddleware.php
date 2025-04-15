<?php

namespace App\middleware;

use App\services\AuthService;

class AuthMiddleware
{
    private $authService;

    public function __construct(){
        $this->authService = new AuthService();
    }

    public function handle(){
        $headers = getAllHeaders();
        if(!isset($headers["Authorization"])){
            http_response_code(401);
            echo json_encode(["error"=>"Authorization header missing"]);
            exit;
        }

        $token = str_replace("Bearer ", "", $headers["Authorization"]);

        try {
            $decode = $this->authService->verifyToken($token);

            if(!$decode || !isset($decode["userId"])){
                http_response_code(401);
                echo json_encode(["error"=>"Invalid token or incomplete data"]);
                exit;
            }
            return $decode;
        } catch(\Exception $e){
            http_response_code(401);
            echo json_encode(["error"=>"Unauthorized" . $e->getMessage()] );
            exit;
        }
    }
}