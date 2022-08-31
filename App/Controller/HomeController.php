<?php

namespace Bot\Controller;

use App\Database\DB;
use App\Helpers\Helpers;
use App\Helpers\User;
use App\Telegram\Bot;
use App\Telegram\Telegram;
use App\Helpers\Lang;

class HomeController
{
    function startChat()
    {
        User::updatePage("home");

        $user = Telegram::getChatData();
        $res = DB::table("users")->select()->where(["userId" => $user->id])->run();
        if ($res->RowCount == 0) {
            Helpers::log($user->id);
            DB::table("users")->insert(["userId" => "$user->id", 'userName' => $user->username, "firstName" => $user->first_name])->run();
        }
        Bot::sendInlineKeyboardMessage("Tilni tanlang:\n Выберите язык:", Telegram::inlineKeyboard([[["text" => "O'zbek tili", "callback_data" => "lang_uz"]], [["text" => "русский язык", "callback_data" => "lang_ru"]]]));
    }

}