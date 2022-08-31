<?php


namespace Bot\Controller;


use App\Database\DB;
use App\Helpers\Helpers;
use App\Helpers\Lang;
use App\Helpers\User;
use App\Telegram\Bot;
use App\Telegram\Telegram;

class inputController
{
    function enterFirstName()
    {
        User::updatePage("enterLastName");
        DB::table("users")->update(['firstName' => Telegram::getText()])->where(['userId' => Telegram::chatId()])->run();
        Bot::sendKeyboardMessage(Lang::get("enter.last.name"), Telegram::inlineKeyboard([[['callback_data' => "back_date", "text" => Lang::get("back")]]]));
    }

    function enterLastName()
    {
        User::updatePage("enterPhoneNumber");
        DB::table("users")->update(['lastName' => Telegram::getText()])->where(['userId' => Telegram::chatId()])->run();
        Bot::sendKeyboardMessage(Lang::get("send.contact"), Telegram::keyboard([[["text" => Lang::get("send.contact.button"), "request_contact" => true]]]));
    }

    function enterPhoneNumber()
    {
        $phone = Telegram::getUpdate()->message->contact->phone_number;
        $response = false;
        if (isset($phone)) {
            User::updatePage("finish");

            DB::table("users")->update(['phone' => $phone])->where(['userId' => Telegram::chatId()])->run();
            $response = true;
        } else {
            $phone = Telegram::getText();
            $phone = str_replace(" ", "", $phone);
            $res = preg_match("/^[0-9]{9,12}$/", $phone);
            if ($res) {
                User::updatePage("finish");
                DB::table("users")->update(['phone' => $phone])->where(['userId' => Telegram::chatId()])->run();
                $response = true;

            } else {
                Bot::sendMessage(Lang::get(Lang::get("phone.error")));
            }
        }
        if ($response) {
            $user = User::userData();
            $date = $user['date'];
            if ($date == 1)
                $date = Lang::get("date.1");
            elseif ($date == 2)
                $date = Lang::get("date.2");
            else
                $date = Lang::get("no.difference");
            $message = "Yangi So'rov \n".Lang::get("first.name") . ": " . $user['firstName'] . "\n" . Lang::get("last.name") . ": " . $user['lastName'] . "\n" . Lang::get("user.name") . ": @" . $user['userName'] . "\n" . Lang::get("id") . ": " . $user['userId'] . "\n" . Lang::get("phone") . ": " . $user['phone'] . "\n" . Lang::get("Fan") . ": " . $user['fan'] . "\n" . Lang::get("date") . ": " . $date;

            Bot::sendKeyboardMessage(Lang::get("request.done"), Telegram::removeKeyboard());
            Bot::sendKeyboardMessage(Lang::get("request.info"), Telegram::inlineKeyboard([[['callback_data' => "my_request", "text" => Lang::get("my.request")]]]));
            Bot::allAdminSendMessageKeyboard($message, Telegram::inlineKeyboard([[['text' => Lang::get("user"), "url" => "https://t.me/" . User::userData()['userName']]]]));

        }
    }
}