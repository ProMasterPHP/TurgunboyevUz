<?php

/**
 * @author Turgunboyev Diyorbek
 * Muallifdan: Ushbu examplelar TurgunboyevKey treyti bilan ishlash uchun tuzilgan.
 * 
 * @contact - Telegram: @Turgunboyev_D
 * @description - By: @Magic_Coders group
 * @license - MIT license
 * @privacy - Mualliflik huquqini hurmat qiling!
*/

/**
 * @var Request user button
 * @param $text - Tugmada aks etadigan tekst
 * 
 * @return Array value for makeResize
*/

$button = $tg->requestUser("Ma'lumotlarni olish");
$keyboard = $tg->makeResize([
    [
        $button,
        'Bot haqida'
    ],
    [
        'Dasturchi',
        'Loyihalarimiz'
    ]
]);

/**
 * @var Request Chat button
 * @param $text - Tugmada aks etadigan tekst
 * 
 * @return Array value for makeResize
*/

$button = $tg->requestChat("Chat ma'lumotlari");
$keyboard = $tg->makeResize([
    [
        $button,
        'Bot haqida'
    ],
    [
        'Dasturchi',
        'Loyihalarimiz'
    ]
]);

/**
 * @var Request Contact button
 * @param $text - Tugmada aks etadigan tekst
 * 
 * @return Array value for makeResize
*/

$button = $tg->requestContact("Telefon raqamimni yuborish");
$keyboard = $tg->makeResize([
    [
        $button,
        'Bot haqida'
    ],
    [
        'Dasturchi',
        'Loyihalarimiz'
    ]
]);

/**
 * @var Request Location button
 * @param $text - Tugmada aks etadigan tekst
 * 
 * @return Array value for makeResize
*/

$button = $tg->requestLocation("Joylashuvni yuborish");
$keyboard = $tg->makeResize([
    [
        $button,
        'Bot haqida'
    ],
    [
        'Dasturchi',
        'Loyihalarimiz'
    ]
]);

/**
 * @var Request Poll button
 * @param $text - Tugmada aks etadigan tekst
 * 
 * @return Array value for makeResize
*/

$button = $tg->requestPoll("Telefon raqamimni yuborish");
$keyboard = $tg->makeResize([
    [
        $button,
        'Bot haqida'
    ],
    [
        'Dasturchi',
        'Loyihalarimiz'
    ]
]);

/**
 * @var Web App button
 * @param $text - Tugmada aks etadigan tekst
 * @param $url - Web App uchun url manzil
 * 
 * @return Array value for makeResize
*/

$button = $tg->webApp("Telefon raqamimni yuborish");
$keyboard = $tg->makeResize([
    [
        $button,
        'Bot haqida'
    ],
    [
        'Dasturchi',
        'Loyihalarimiz'
    ]
]);

/**
 * @var makeResize - resize_keyboard ni yig'uvchi funksiya
 * @param $arr - Tugma tekstlari to'plami
 * @param $arg - resize_keyboard uchun qo'shimcha sozlamalar
 * 
 * @by_author - $arg qabul qiladigan qiymatlar uchun - https://core.telegram.org/bots/api
 * 
 * @return JSON ENCODED DATA
*/

$keyboard = $tg->makeResize([
    [$tg->requestContact("Telefon raqamimni yuborish")],
    ['Telegram Bot haqida','Dasturchi'],
    ['Loyihalarimiz','Kanalimiz']
]);


/**
 * @var callbackData - callback_data yaratadi
 * @param $text - Tugmada aks etadigan tekst
 * @param $data - callback_data ga kiritiladigan qiymat
 * 
 * @return Array value for makeInline
*/

$button = $tg->callbackData('Assalomu alaykum', 'hello');

/**
 * @var inlineUrl - URLga ega inline tugma yaratadi
 * @param $text - Tugmada aks etadigan tekst
 * @param $url - tugmani bosganda yo'naltiriladigan URL havola
 * 
 * @return Array value for makeInline
*/

$button = $tg->inlineUrl('OnlineWolf', 'https://t.me/OnlineWolf');

/**
 * @var loginUrl - URLga ega inline tugma yaratadi
 * @param $text - Tugmada aks etadigan tekst
 * @param $url - tugmani bosganda yo'naltiriladigan URL havola
 * 
 * @return Array value for makeInline
*/

$button = $tg->loginUrl('OnlinWolf','https://turgunboyev.uz/');

/**
 * @var switchInline - inline_queryga o'tkazuvchi tugma yaratadi
 * @param $text - Tugmada aks etadigan tekst
 * @param $query - Inline query uchun default qiymat
 * 
 * @return Array value for makeInline
*/

$button = $tg->switchInline('Qidirish','Saodat asri qissalari - A.L.Qozonchi');

/**
 * @var switchCurrent - inline_queryni telegram bot chatida ochishga o'tkazuvchi tugma yaratadi
 * @param $text - Tugmada aks etadigan tekst
 * @param $query - Inline query uchun default qiymat
 * 
 * @return Array value for makeInline
*/

$button = $tg->switchCurrent('Qidirish','UFQ - Said Ahmad');

/**
 * @var callbackGame - HTML5 da yaratilgan o'yinga o'tkazuvchi tugma yaratadi.
 * @param $text - Tugmada aks etadigan tekst
 * @param $url - O'yin joylashgan URL havola
 * 
 * @return Array value for makeInline
*/

$button = $tg->callbackGame("O'yinga kirish",'https://game.turgunboyev.uz/pocket.html');

/**
 * @var Payment - To'lov sahifasiga o'tkazuvchi tugma yaratadi
 * @param $text - Tugmada aks etadigan tekst
 * 
 * @return Array value for makeInline
*/

$button = $tg->Payment("To'lash");

/**
 * @var makeInline - inline_keyboard ni buttonlar asosida yig'uvchi funksiya
 * @param $arr - Yuqorida berilgan functionlar asosida yaratilgan buttonlar to'plami
 * 
 * @by_author - callbackGame va Payment haqida batafsil - https://core.telegram.org/bots/api
 * 
 * @return JSON ENCODED DATA
*/

$keyboard = $tg->makeResize([
    [$tg->callbackData('Assalomu alaykum','hello'), $tg->callbackData('Vaalaykum assalom','hi')],
    [$tg->inlineUrl('Turgunboyev_D','https://t.me/Turgunboyev_D')],
    [$tg->inlineUrl('OnlineWolf','https://t.me/OnlineWolf')]
]);

/**
 * @var href - parse_mode => html holat uchun link yaratadi
 * @param $url - URL havola
 * @param $name - URL havola biriktiriladigan tekst
 * 
 * @return STRING <a href='$url'>$name</a>
*/

$text = "Assalomu alaykum hurmatli ".$tg->href("https://t.me/".$tg->username(), $tg->firstName())." bizning Telegram Botimizga xush kelibsiz";

/**
 * @var mention - ID raqamni o'z ichiga olgan mention yaratadi(parse_mode=>html)
 * @param $name - Mention biriktiriladigan tekst
 * @param $id - Mention uchun foydalanuvchi ID raqami
 * 
 * @by_author - $id by default gets from Update
 * 
 * @return STRING tg://user?id=$user_id
*/

$text = "Assalomu alaykum hurmatli ".$tg->mention($tg->firstName())." bizning Telegram Botimizga xush kelibsiz";
?>
