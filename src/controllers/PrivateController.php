<?php

namespace App\controllers;

class PrivateController
{
    public function privateRoute(){

        http_response_code(200);
        echo json_encode([
            "message" => "Access granted to private route"
        ]);
    }
}