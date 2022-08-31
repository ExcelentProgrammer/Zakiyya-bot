<?php


namespace App\Telegram;


class Telegram
{
    static function bot($method, $datas = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $_ENV['TOKEN'] . "/" . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
        $res = curl_exec($ch);
        if (curl_error($ch)) {
            print_r(curl_error($res));
        } else {
            return json_decode($res);
        }
    }

    static function getUpdate()
    {
        return json_decode(file_get_contents("php://input"));
    }

    static function getChatData()
    {
        if (!empty(self::getUpdateArray()["callback_query"]["from"])) {
            return self::getUpdate()->callback_query->from;
        } else {
            return self::getUpdate()->message->chat;
        }
    }

    static function getUpdateArray()
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    static function chatId()
    {
        if (!empty(self::getUpdateArray()["callback_query"]["from"]["id"])) {
            return self::getUpdateArray()["callback_query"]["from"]["id"];
        } else {
            return self::getUpdate()->message->chat->id;
        }
    }

    static function getText()
    {
        return self::getUpdate()->message->text;
    }

    static function getCallBackData()
    {

        return self::getUpdate()->callback_query->data;
    }

    static function inlineKeyboard($data)
    {
        return json_encode(['inline_keyboard' => $data]);
    }

    static function keyboard($data)
    {
        return json_encode(['keyboard' => $data,"resize_keyboard"=>true]);
    }
    static function removeKeyboard(){
        return json_encode(['remove_keyboard' => true]);
    }
    static function getCallBackMessageId(){
        return Telegram::getUpdate()->callback_query->message->message_id;
    }
    static function getMessageId(){
        return Telegram::getUpdate()->message->message_id;
    }


}