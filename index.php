<?php
error_reporting(E_ALL);
use App\Helpers\Helpers;
use App\Telegram\Telegram;

require_once "./vendor/autoload.php";


Helpers::loadDotEnv(__DIR__);


require_once './Route/api.php';

