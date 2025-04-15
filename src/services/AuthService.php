<?php

namespace App\services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class AuthService
{
    private $secretKey;

    public function __construct(){
        $this->secretKey = trim($_ENV["SECRET_KEY"] ?? "");
        if (!$this->secretKey) {
            die(json_encode(["error" => "SECRET_KEY no definida o vacía"]));
        }
    }
    public function generateToken($user){
        if (!isset($user["id"], $user["name"], $user["email"])) {
            throw new \Exception("Datos de usuario incompletos para generar el token.");
        }
        $payload = [
            "iss" => "http://localhost", // Emisor
            "aud" => "http://localhost", // Audiencia
            "iat" => time(), // Emitido en
            "exp" => time() + 3600, // Expira en 1 hora
            "user" => [
                "id" => $user["id"],
                "name" => $user["name"],
                "email" => $user["email"],
            ]
        ];
        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function verifyToken($token){
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }
}