<?php

namespace Bot\Controller;

use App\Database\DB;
use App\Helpers\Helpers;
use App\Telegram\Bot;
use App\Telegram\Telegram;
use App\Helpers\Lang;


class ScienceController
{
    static function selectDate()
    {
        Bot::editMessageTextKeyboard(Lang::get("select.date"), Telegram::getCallBackMessageId(), Telegram::inlineKeyboard([[["callback_data" => "date_1", "text" => Lang::get("date.1")]], [['callback_data' => "date_2", "text" => Lang::get("date.2")]], [["callback_data" => "date_3", "text" => Lang::get("no.difference")]],[['callback_data'=>"back_fanlar","text"=>Lang::get("back")]]]));
    }

    function arabTili()
    {
        DB::table("users")->update(['fan' => "Arab tili"])->where(['userId' => Telegram::chatId()])->run();
        self::selectDate();
    }

    function rusTili()
    {
        DB::table("users")->update(['fan' => "Rus tili"])->where(['userId' => Telegram::chatId()])->run();
        self::selectDate();
    }

    function inglizTili()
    {
        DB::table("users")->update(['fan' => "Ingliz tili"])->where(['userId' => Telegram::chatId()])->run();
        self::selectDate();
    }

    function koreysTili()
    {
        DB::table("users")->update(['fan' => "Koreys tili"])->where(['userId' => Telegram::chatId()])->run();
        self::selectDate();
    }

    function mentalArifmetika()
    {
        DB::table("users")->update(['fan' => "Mental Arifmetika"])->where(['userId' => Telegram::chatId()])->run();
        self::selectDate();
    }

    function matematika()
    {
        DB::table("users")->update(['fan' => "Matematika"])->where(['userId' => Telegram::chatId()])->run();
        self::selectDate();
    }
}