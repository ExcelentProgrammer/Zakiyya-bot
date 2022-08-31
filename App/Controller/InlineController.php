<?php


namespace Bot\Controller;


use App\Database\DB;
use App\Helpers\Helpers;
use App\Helpers\Lang;
use App\Helpers\User;
use App\Telegram\Bot;
use App\Telegram\Telegram;

class InlineController
{
    function langUz()
    {
        $k = [[["callback_data" => "start_chat", "text" => Lang::get("boshlash")]],[["callback_data" => "back_start_chat", "text" => Lang::get("back")]]];
        if(User::isAdmin()){
            $k = [[["callback_data" => "admin_panel", "text" => Lang::get("admin.panel")]],[["callback_data" => "start_chat", "text" => Lang::get("boshlash")]],[["callback_data" => "back_start_chat", "text" => Lang::get("back")]]];
        }

        DB::table("users")->update(['language' => "uz"])->where(['userId' => Telegram::chatId()])->run();
        Bot::editMessageTextKeyboard(Lang::get("start.chat"), Telegram::getCallBackMessageId(),Telegram::inlineKeyboard($k));
    }


    function langRu()
    {
        $k = [[["callback_data" => "start_chat", "text" => Lang::get("boshlash")]],[["callback_data" => "back_start_chat", "text" => Lang::get("back")]]];
        if(User::isAdmin()){
            $k = [[["callback_data" => "admin_panel", "text" => Lang::get("admin.panel")]],[["callback_data" => "start_chat", "text" => Lang::get("boshlash")]],[["callback_data" => "back_start_chat", "text" => Lang::get("back")]]];
        }
        DB::table("users")->update(['language' => "ru"])->where(['userId' => Telegram::chatId()])->run();
        Bot::editMessageTextKeyboard(Lang::get("start.chat"), Telegram::getCallBackMessageId(), Telegram::inlineKeyboard($k));
    }

    function selectFilial()
    {
        Bot::editMessageTextKeyboard(Lang::get("select.filial"), Telegram::getCallBackMessageId(), Telegram::inlineKeyboard([[["callback_data" => "filial_beshariq", "text" => Lang::get("beshariq")]], [["callback_data" => "filial_gorskiy", "text" => Lang::get("gorskiy")]],[['callback_data'=>"back_start","text"=>Lang::get("back")]]]));
    }
    function backFilial()
    {
        $page = User::userPage();
        if($page == "enterPhoneNumber"){
            Bot::sendKeyboardMessage(Lang::get("select.filial"),  Telegram::inlineKeyboard([[["callback_data" => "filial_beshariq", "text" => Lang::get("beshariq")]], [["callback_data" => "filial_gorskiy", "text" => Lang::get("gorskiy")]],[['callback_data'=>"back_start","text"=>Lang::get("back")]]]));
            User::updatePage("home");
        }else{
            User::updatePage("home");
            Bot::sendKeyboardMessage(Lang::get("page.not.found"),Telegram::removeKeyboard());
        }
    }


    function filialBeshariq()
    {
        DB::table("users")->update(['filial' => "Beshariq"])->where(['userId' => Telegram::chatId()])->run();
        Bot::editMessagetextKeyboard(Lang::get("fanlar"), Telegram::getCallBackMessageId(), Telegram::inlineKeyboard([[["callback_data" => "Arab_tili", "text" => Lang::get("arab.tili")], ['callback_data' => "Ingliz_tili", "text" => Lang::get("ingliz.tili")]], [["callback_data" => "Rus_tili", "text" => Lang::get("rus.tili")], ['callback_data' => "Koreys_tili", "text" => Lang::get("koreys.tili")]], [['callback_data' => "Mental_arifmetika", "text" => Lang::get("mental.arifmetika")], ['callback_data' => "Matematika", "text" => Lang::get("matematika")]],[['callback_data' => "back_filial", "text" => Lang::get("back")]]]));
    }

    function filialGorskiy()
    {
        DB::table("users")->update(['filial' => "Gorskiy"])->where(['userId' => Telegram::chatId()])->run();
        Bot::editMessagetextKeyboard(Lang::get("fanlar"), Telegram::getCallBackMessageId(), Telegram::inlineKeyboard([[["callback_data" => "Arab_tili", "text" => Lang::get("arab.tili")], ['callback_data' => "Ingliz_tili", "text" => Lang::get("ingliz.tili")]], [["callback_data" => "Rus_tili", "text" => Lang::get("rus.tili")], ['callback_data' => "Koreys_tili", "text" => Lang::get("koreys.tili")]], [['callback_data' => "Mental_arifmetika", "text" => Lang::get("mental.arifmetika")], ['callback_data' => "Matematika", "text" => Lang::get("matematika")]],[['callback_data' => "back_filial", "text" => Lang::get("back")]]]));
    }


    function backStartChat()
    {
        Bot::editMessageTextKeyboard("Tilni tanlang:\n Выберите язык:",Telegram::getCallBackMessageId(), Telegram::inlineKeyboard([[["text" => "O'zbek tili", "callback_data" => "lang_uz"]], [["text" => "русский язык", "callback_data" => "lang_ru"]]]));
    }

    function backStart(){
        $lang = User::userData()['language'];
        if($lang == "ru"){
            self::langRu();
        }else{
            self::langUz();
        }
    }
    function backFanlar(){
        $filial = User::userData()['filial'];
        if($filial == "Gorskiy"){
            self::filialGorskiy();
        }else{
            self::filialBeshariq();
        }

    }
    function myRequest(){
        $user = User::userData();
        $date = $user['date'];
        if($date == 1)
            $date = Lang::get("date.1");
        elseif($date == 2)
            $date = Lang::get("date.2");
        else
            $date = Lang::get("no.difference");
        $text = Lang::get("first.name").": ".$user['firstName']."\n".Lang::get("last.name").": ".$user['lastName']."\n".Lang::get("user.name").": @".$user['userName']."\n".Lang::get("id").": ".$user['userId']."\n".Lang::get("phone").": ".$user['phone']."\n".Lang::get("Fan").": ".$user['fan']."\n".Lang::get("date").": ".$date;
        Bot::editMessageTextKeyboard($text,Telegram::getCallBackMessageId(),Telegram::inlineKeyboard([[["callback_data"=>"edit_request","text"=>Lang::get("edit")]]]));

    }

    static function enterFirstName(){
        User::updatePage("enterFirstName");
        Bot::editMessageTextKeyboard(Lang::get("enter.first.name"),Telegram::getCallBackMessageId(),Telegram::inlineKeyboard([[['callback_data'=>"back_date","text"=>Lang::get("back")]]]));
    }
    function date_1(){
        DB::table("users")->update(['date'=>"1"])->where(['userId'=>Telegram::chatId()])->run();
        self::enterFirstName();
    }
    function date_2(){
        DB::table("users")->update(['date'=>"2"])->where(['userId'=>Telegram::chatId()])->run();
        self::enterFirstName();
    }
    function date_3(){
        DB::table("users")->update(['date'=>"3"])->where(['userId'=>Telegram::chatId()])->run();
        self::enterFirstName();

    }
}