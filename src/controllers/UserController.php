<?php
namespace App\controllers;

use App\models\User;
use App\services\AuthService;

class UserController
{
    private $UserModel;
    private $AuthService;

    public function __construct(){
        $this->UserModel = new User();
        $this->AuthService = new AuthService();
    }

    public function registerUser(array $data){
        if(
            empty($data['email']) ||
            empty($data['password']) ||
            empty($data['name'])
        ) {
            http_response_code(400);
            echo json_encode(["error" => "All fields are required"]);
            return;
        }

        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            http_response_code(400);
            echo json_encode(["error" => "Invalid email format"]);
            return;
        }

        if($this->UserModel->getUserByEmail($data['email'])){
            http_response_code(409);
            echo json_encode(["error" => "User already exists"]);
            return;
        }

        if($this->UserModel->createUser($data)){
            http_response_code(200);
            echo json_encode(["message" => "User created"]);
        }
    }

    public function login(){
        $data = json_decode(file_get_contents("php://input"), true);
        if(empty($data["email"]) || empty($data["password"])){
            http_response_code(400);
            echo json_encode(["error" => "Email and password are required"]);
            return;
        }

        $user = $this->UserModel->getUserByEmail($data["email"]);

        if(!$user){
            http_response_code(404);
            echo json_encode(["error" => "Invalid credentials"]);
            return;
        }

        if(!password_verify($data["password"], $user["password"])){
            http_response_code(401);
            echo json_encode(["error" => "Invalid credentials"]);
            return;
        }

        try {
            $token = $this->AuthService->generateToken($user);
            http_response_code(200);
            echo json_encode([
                "message" => "Login successful",
                "token" => $token
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to generate token" . $e->getMessage()]);
        }
    }

    public function verifyToken(){
        $headers = getallheaders();
        if(!isset($headers["Authorization"])){
            http_response_code(401);
            echo json_encode(["error" => "Authorization header not found"]);
            return;
        }

        $token = str_replace("Bearer ", "", $headers["Authorization"]);

        try {
            $decoded = $this->AuthService->verifyToken($token);
            echo json_encode(["message" => "Token is valid", "data" => $decoded]);
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid token" . $e->getMessage()]);
        }
    }
}