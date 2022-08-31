<?php
namespace App\Telegram;

use App\Helpers\User;

class Bot extends Telegram {

    static function sendMessage($text,$chatId = null){
        if($chatId == null){
            $chatId = self::chatId();
        }
        self::bot("sendMessage",['chat_id'=>$chatId,"text"=>$text]);
    }
    static function sendInlineKeyboardMessage($text,$keyboard,$chatId = null){
        if($chatId == null){
            $chatId = self::chatId();
        }
        self::bot("sendMessage",['chat_id'=>$chatId,"text"=>$text,"reply_markup"=>$keyboard]);
    }
    static function sendKeyboardMessage($text,$keyboard,$chatId = null){
        if($chatId == null){
            $chatId = self::chatId();
        }
        self::bot("sendMessage",['chat_id'=>$chatId,"text"=>$text,"reply_markup"=>$keyboard]);
    }
    static function editMessageText($text,$message_id,$chatId = null){
        if($chatId == null){
            $chatId = self::chatId();
        }
        self::bot("editMessageText",['chat_id'=>$chatId,"message_id"=>$message_id,"text"=>$text]);
    }
    static function editMessageTextKeyboard($text,$message_id,$keyboard,$chatId = null){
        if($chatId == null){
            $chatId = self::chatId();
        }
        return self::bot("editMessageText",['chat_id'=>$chatId,"message_id"=>$message_id,"text"=>$text,"reply_markup"=>$keyboard]);
    }
    static function allAdminSendMessageKeyboard($text,$keyboard){
        foreach (User::admins() as $admin) {
            self::bot("sendMessage",['chat_id'=>$admin,"text"=>$text,"reply_markup"=>$keyboard]);
        }

    }
    static function sendCopyMessage($chatId,$fromId,$messageId){
        return Telegram::bot("copyMessage",['chat_id'=>$chatId,"from_chat_id"=>$fromId,"message_id"=>$messageId]);
    }
}