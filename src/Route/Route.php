<?php

namespace App\Route;

use App\Helpers\Helpers;
use App\Helpers\User;
use App\Telegram\Bot;
use App\Telegram\Telegram;

class Route
{
    static function message($message, $className, $function)
    {
        $text = Telegram::getText();
        if ($text == $message) {
            (new $className())->{"$function"}();
            exit();
        }
    }

    static function inline($command, $className, $function)
    {

        $text = Telegram::getCallBackData();
        if ($text == $command) {
            (new $className())->{"$function"}();
            exit();
        }
    }
    static function page($page,$className,$function){
        $myPage = User::userPage();
        if ($page == $myPage) {
            (new $className())->{"$function"}();
            exit();
        }
    }

}
