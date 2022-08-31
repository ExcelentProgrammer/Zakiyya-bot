<?php

use App\Helpers\Helpers;
use App\Route\Route;
use Bot\Controller\HomeController;
use Bot\Controller\InlineController;
use Bot\Controller\ScienceController;
use Bot\Controller\inputController;
use Bot\Controller\AdminController;

Route::message("/start",HomeController::class,"startChat");
Route::inline("lang_uz",InlineController::class,"langUz");
Route::inline("lang_ru",InlineController::class,"langRu");
Route::inline("start_chat",InlineController::class,"selectFilial");
Route::inline("filial_beshariq",InlineController::class,"filialBeshariq");
Route::inline("filial_gorskiy",InlineController::class,"filialGorskiy");

/**
 * fanlar
*/

Route::inline("Ingliz_tili",ScienceController::class,"inglizTili");
Route::inline("Rus_tili",ScienceController::class,"rusTili");
Route::inline("Arab_tili",ScienceController::class,"arabTili");
Route::inline("Koreys_tili",ScienceController::class,"koreysTili");
Route::inline("Mental_arifmetika",ScienceController::class,"mentalArifmetika");
Route::inline("Matematika",ScienceController::class,"matematika");


Route::inline("back_start_chat",InlineController::class,"backStartChat");
Route::inline("back_start",InlineController::class,"backStart");
Route::inline("back_fanlar",InlineController::class,"backFanlar");
Route::inline("back_filial",InlineController::class,"selectFilial");
Route::inline("back_date",ScienceController::class,"selectDate");
Route::inline("edit_request",InlineController::class,"selectFilial");


Route::inline("date_1",InlineController::class,"date_1");
Route::inline("date_2",InlineController::class,"date_2");
Route::inline("date_3",InlineController::class,"date_3");



Route::inline("my_request",InlineController::class,"myRequest");


/**
 * admin
*/

Route::inline("admin_panel",AdminController::class,"home");
Route::inline("stat",AdminController::class,"stat");
Route::inline("all_users_send_message",AdminController::class,"allUsersMessage");






Route::page("enterFirstName",inputController::class,"enterFirstName");
Route::page("enterLastName",inputController::class,"enterLastName");
Route::page("enterPhoneNumber",inputController::class,"enterPhoneNumber");

Route::page("allUsersSendMessage",AdminController::class,"allUsersSendMessage");