<?php

namespace App\Helpers;

use App\Database\DB;
use App\Telegram\Telegram;

class User
{
    static function userPage($userId = null)
    {
        if ($userId == null) {
            $userId = Telegram::chatId();
        }
        
        return DB::table("users")->select()->where(['userId' => $userId])->run()->Data[0]["page"];
    }

    static function updatePage($page,$userId = null)
    {
        if ($userId == null) {
            $userId = Telegram::chatId();
        }
        return DB::table("users")->update(['page'=>$page])->where(['userId' => $userId])->run()->result;
    }
    static function userData($userId = null)
    {
        if ($userId == null) {
            $userId = Telegram::chatId();
        }

        return DB::table("users")->select()->where(['userId' => $userId])->run()->Data[0];
    }
    static function isAdmin(){
        $admins = self::admins();
        return in_array(Telegram::chatId(),$admins);
    }
    static function admins(){
        return json_decode(file_get_contents("src/Database/admins.json"),true);
    }
    static function stat(){
        $all = DB::table("users")->select()->run()->RowCount;
        $inglizTili = DB::table("users")->where(['fan'=>"Ingliz tili"])->select()->run()->RowCount;
        $rusTili = DB::table("users")->where(['fan'=>"Rus tili"])->select()->run()->RowCount;
        $arabTili = DB::table("users")->where(['fan'=>"Arab tili"])->select()->run()->RowCount;
        $koreysTili = DB::table("users")->where(['fan'=>"Koreys tili"])->select()->run()->RowCount;
        $m_arifmetika = DB::table("users")->where(['fan'=>"Mental Arifmetika"])->select()->run()->RowCount;
        $matematika = DB::table("users")->where(['fan'=>"Matematika"])->select()->run()->RowCount;
        return ['all'=>$all,"inglizTili"=>$inglizTili,"rusTili"=>$rusTili,"arabTili"=>$arabTili,'koreysTili'=>$koreysTili,"m_arifmetika"=>$m_arifmetika,"matematika"=>$matematika];
    }
}