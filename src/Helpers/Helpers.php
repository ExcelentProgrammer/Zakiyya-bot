<?php

namespace App\Helpers;

class Helpers
{
    static function loadDotEnv($dir)
    {
        $dotenv = \Dotenv\Dotenv::createImmutable($dir);
        $dotenv->load();
        $dotenv->required(['TOKEN']);
    }
    static function log($data){
        file_put_contents("log.txt",json_encode($data));
    }
}

