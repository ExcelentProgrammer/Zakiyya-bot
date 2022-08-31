<?php


namespace Bot\Controller;


use App\Database\DB;
use App\Helpers\Helpers;
use App\Helpers\Lang;
use App\Helpers\User;
use App\Telegram\Bot;
use App\Telegram\Telegram;

class AdminController
{
    function home(){
        User::updatePage("adminPanel");
        Bot::editMessageTextKeyboard(Lang::get("admin.panel.start"),Telegram::getCallBackMessageId(),Telegram::inlineKeyboard([[['callback_data'=>"stat","text"=>Lang::get("statistika")]],[['callback_data'=>"all_users_send_message","text"=>Lang::get("all.users.send.message")]],[['callback_data'=>"back_start","text"=>Lang::get("back")]]]));
    }
    function stat(){
        $stat = User::stat();
        $message = Lang::get("bot.users").": ".$stat['all']."\n"."Botga Kelib tushgan barcha so'rovlar \n".Lang::get("ingliz.tili").": ".$stat['inglizTili']."\n".Lang::get("rus.tili").": ".$stat['rusTili']."\n".Lang::get("arab.tili").": ".$stat['arabTili']."\n".Lang::get("koreys.tili").": ".$stat['koreysTili']."\n".Lang::get("mental.arifmetika").": ".$stat['m_arifmetika']."\n".Lang::get("matematika").": ".$stat['matematika'];
        Bot::editMessageTextKeyboard($message,Telegram::getCallBackMessageId(),Telegram::inlineKeyboard([[['callback_data'=>"admin_panel","text"=>Lang::get("back")]]]));
    }
    function allUsersMessage(){
        User::updatePage("allUsersSendMessage");
        Bot::editMessageTextKeyboard(Lang::get("all.users.send.message.text"),Telegram::getCallBackMessageId(),Telegram::inlineKeyboard([[['callback_data'=>"admin_panel","text"=>Lang::get("back")]]]));
    }

    function allUsersSendMessage(){
        $users = DB::table("users")->select(['userId'])->run();
        $done = 0;
        $error = 0;
        Bot::sendMessage(Lang::get("all").": ".$users->RowCount."\n".Lang::get("sending").": ".$done."\n".Lang::get("error").": ".$error);
        foreach ($users->Data as $user) {
            $res = Bot::sendCopyMessage($user['userId'],Telegram::chatId(),Telegram::getMessageId());
            if($res->ok == true){
                $done++;
            }else{
                $error++;
            }
            Bot::editMessageText(Lang::get("all").": ".$users->RowCount."\n".Lang::get("sending").": ".$done."\n".Lang::get("error").": ".$error,Telegram::getMessageId()+1);
        }
        Bot::editMessageTextKeyboard(Lang::get("all").": ".$users->RowCount."\n".Lang::get("sending").": ".$done."\n".Lang::get("error").": ".$error."\n".Lang::get("done"),Telegram::getMessageId()+1,Telegram::inlineKeyboard([[['callback_data'=>"admin_panel","text"=>Lang::get("back")]]]));

    }
}