<?php
namespace App\core;

class BaseModel
{
    protected $db;

    public function __construct(){
        $this->db = (new DB())->connect();
    }
}