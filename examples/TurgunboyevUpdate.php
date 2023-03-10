<?php

/**
 * @author Turgunboyev Diyorbek
 * Muallifdan: Ushbu examplelar TurgunboyevUpdate treyti bilan ishlash uchun tuzilgan, TurgunboyevUpdate treytida mavjud New Chat Member sectioni uchun 
 * examplelar tuzilmadi chunki ushbu section to'la stabillashtirilmagan, agar ishlatmoqchi bo'lsangiz library_path ga kiring va uning ichida src/Traits/
 * TurgunboyevUpdate.php fayliga kiring.
 * TurgunboyevUpdate.php faylining 638-qatoridan boshlab chatning yangi a'zosi uchun functionlar tuzilgan.
 * 
 * @contact - Telegram: @Turgunboyev_D
 * @description - By: @Magic_Coders group
 * @license - MIT license
 * @privacy - Mualliflik huquqini hurmat qiling!
 */

/**
 * @var getUpdates - Update array holatda scriptga yuklaydi
 * @return Array for get values
*/
$update = $tg->getUpdates();

/**
 * @var message - Update turini message ga sozlaydi, barcha olinadigan ma'lumotlar $update->message asosida olinadi
 * @return Array for get values
*/

$message = $tg->message();

/**
 * @var callbackQuery - Update turini callbackQuery ga sozlaydi, barcha olinadigan ma'lumotlar $update->callback_query asosida olinadi
 * @return Array for get values
*/

$message = $tg->callbackQuery();

/**
 * @var inlineQuery - Update turini inlineQuery ga sozlaydi, barcha olinadigan ma'lumotlar $update->inline_query asosida olinadi
 * @return Array for get values
*/

$message = $tg->inlineQuery();

/**
 * @var chat - User ma'lumotlarini Chat ga asosan oladi
 * @return Array for get values
*/

$chat = $message->chat();

/**
 * @var from - User ma'lumotlarini From ga asosan oladi
 * @return Array for get values
*/

$chat = $message->from();

/**
 * @var isMessage - message tipidagi ma'lumot mavjudligini tekshiradi.
 * @return Boolean
*/

$chat = $update->isMessage();

/**
 * @var isCallback - callback_query tipidagi ma'lumot mavjudligini tekshiradi.
 * @return Boolean
*/

$chat = $update->isCallback();

/**
 * @var isInline - inline_query tipidagi ma'lumot mavjudligini tekshiradi.
 * @return Boolean
*/

$chat = $update->isInline();

/**
 * @var ID - default holatda message yoki callback_query da mavjud chat_id ni qaytaradi.
 * @return String or Int
*/

$chat_id = $message->ID();

/**
 * @var messageID - Mavjud update uchun message_id ni qaytaradi
 * @return Int
*/

$message_id = $message->messageID();

/**
 * @var type - Mavjud update uchun chat turini qaytaradi(private/group/supergroup/channel)
 * @return String
*/

$type = $message->type();

/**
 * @var isPrivate/isGroup/isChannel - chat turini funksiyaga mos ravishda tekshiradi
 * @return Boolean
*/

$message->isPrivate();
$message->isGroup();
$message->isChannel();

/**
 * @var firstName - mavjud private chat uchun first_name ni qaytaradi
 * @return String
*/

$first_name = $message->firstName();

/**
 * @var lastName - mavjud private chat uchun last_name ni qaytaradi
 * @return String or Boolean
*/

$last_name = $message->lastName();

/**
 * @var lastName - mavjud chat uchun username ni qaytaradi
 * @return String or Boolean
*/

$last_name = $message->username();

/**
 * @var isBot - mavjud chatni telegram bot yoki oddiy userga tekshiradi
 * @return Boolean
*/

$is_bot = $message->isBot();

/**
 * @var isPremium - mavjud chatni premium user yoki oddiy userga tekshiradi
 * @return Boolean
*/

$is_premium = $message->isPremium();

/**
 * @var languageCode - mavjud chat uchun til sozlamasini qaytaradi
 * @return String
*/

$languageCode = $message->languageCode();

/**
 * @var messageDate - mavjud chat yuborgan xabarning yuborilgan vaqtini timestamp formatida qaytaradi.
 * @return Integer
*/

$messageDate = $message->messageDate();

/**
 * @var entities - mavjud chat yuborgan xabar uchun tegishli bo'lgan entitylarni qaytaradi
 * @return Array
*/

$entity = $message->entities();

/**
 * @var text - mavjud chat yuborgan text formatidagi xabarni qaytaradi
 * @return String
*/

$text = $message->text();

/**
 * @var queryData - mavjud chat yuborgan callback_datani qaytaradi
 * @return String
*/

$data = $message->queryData();

/**
 * @var queryID - mavjud chat yuborgan callback_data uchun tegishli bo'lgan query_id ni qaytaradi
 * @return String
*/

$query_id = $message->queryID();

/**
 * @var replyMarkup - mavjud chat yuborgan callback_data tegishli bo'lgan xabardagi reply_markup(inline_keyboard)ni qaytaradi
 * @return Array
*/

$reply_markup = $message->replyMarkup();

/**
 * @var replyToMessageID - mavjud chat yuborgan update tegishli bo'lgan xabar reply qilingan xabar ID sini qaytaradi
 * @return Integer
*/

$reply_id = $message->replyToMessageID();

/**
 * @var photo - mavjud chat yuborgan media tipini photoga sozlaydi va bundan keyin olinadigan ma'lumotlar photo asosida olinadi
 * @return Object
*/

$photo = $message->photo();

/**
 * @var video - mavjud chat yuborgan media tipini videoga sozlaydi va bundan keyin olinadigan ma'lumotlar video asosida olinadi
 * @return Object
*/

$video = $message->video();

/**
 * @var document - mavjud chat yuborgan media tipini documentga sozlaydi va bundan keyin olinadigan ma'lumotlar document asosida olinadi
 * @return Object
*/

$document = $message->document();

/**
 * @var voice - mavjud chat yuborgan media tipini voicega sozlaydi va bundan keyin olinadigan ma'lumotlar voice asosida olinadi
 * @return Object
*/

$voice = $message->voice();

/**
 * @var audio - mavjud chat yuborgan media tipini audioga sozlaydi va bundan keyin olinadigan ma'lumotlar audio asosida olinadi
 * @return Object
*/

$audio = $message->audio();

/**
 * @var animation - mavjud chat yuborgan media tipini animationga sozlaydi va bundan keyin olinadigan ma'lumotlar animation asosida olinadi
 * @return Object
*/

$animation = $message->animation();

/**
 * @var sticker - mavjud chat yuborgan media tipini stickerga sozlaydi va bundan keyin olinadigan ma'lumotlar sticker asosida olinadi
 * @return Object
*/

$sticker = $message->sticker();

/**
 * @var caption - mavjud chat yuborgan media uchun taaluqli bo'lgan captionni qaytaradi
 * @return String
*/

$caption = $media->caption();

/**
 * @var fileID - mavjud chat yuborgan media uchun taaluqli bo'lgan file IDni qaytaradi.
 * @return String
*/

$file_id = $media->fileID();

/**
 * @var fileSize - mavjud chat yuborgan media uchun taaluqli bo'lgan file_size(fayl o'lchami)ni qaytaradi.
 * @return Integer
*/

$file_size = $media->fileSize();

/**
 * @var fileName - mavjud chat yuborgan media uchun taaluqli bo'lgan file name(fayl nomi)ni qaytaradi.
 * @return String
*/

$fileName = $media->fileName();

/**
 * @var width va height - mavjud chat yuborgan media uchun taaluqli bo'lgan width(eni uzunligi) va height(balandlik uzunligi)ni qaytaradi.
 * @return Integer
*/

$width = $media->width();
$height = $media->height();

/**
 * @var duration - mavjud chat yuborgan davomiylikka ega media uchun taaluqli duration(davomiylik vaqti)ni qaytaradi
 * @return Integer
*/

$duration = $media->duration();

/**
 * @var thumb - mavjud chat yuborgan kichik ilova rasmiga ega media uchun taaluqli bo'lgan thumb(kichik ilova)ni qaytaradi.
 * @return String
*/

$thumb = $media->thumb();

/**
 * @var performer - mavjud chat yuborgan media uchun taaluqli bo'lgan performer(ijro etuvchi)ni qaytaradi.
 * @return string
*/

$performer = $media->performer();

/**
 * @var mimeType - mavjud chat yuborgan media uchun taaluqli bo'lgan mime type(fayl kengaytma nomi, masalan: .jpg, .mp3, .php va h.k)ni qaytaradi.
 * @return String
*/

$mimeType = $media->mimeType();

/**
 * @var phoneNumber - mavjud chat yuborgan contact ma'lumotlaridagi telefon raqamini qaytaradi.
 * @return String
*/

$phone_number = $message->phoneNumber();

/**
 * @var contactFirstName - mavjud chat yuborgan contact ma'lumotlaridagi first_name ni qaytaradi.
 * @return String
*/

$first_name = $message->contactFirstName();

/**
 * @var contactLastName - mavjud chat yuborgan contact ma'lumotlaridagi last_name ni qaytaradi(agar mavjud bo'lsa).
 * @return String
*/

$last_name = $message->contactLastName();

/**
 * @var contactID - mavjud chat yuborgan contact ma'lumotlaridagi User ID ni qaytaradi(telefon raqam egasining Telegramdagi ID raqami).
 * @return String
*/

$contactID = $message->contactID();

/**
 * @var diceValue - mavjud chat yuborgan dice emojining natijasini qaytaradi.
 * @return String
*/

$dice_value = $message->diceValue();

/**
 * @var diceEmoji - mavjud chat yuborgan dicening emojisini qaytaradi.
 * @return String
*/

$dice_emoji = $message->diceEmoji();

/**
 * @var longitude - mavjud chat yuborgan location ma'lumotlaridagi longitude(geografik uzunlik)ni qaytaradi.
 * @return String
*/

$longitude = $message->longitude();

/**
 * @var latitude - mavjud chat yuborgan location ma'lumotlaridagi latitude(geografik kenglik)ni qaytaradi.
 * @return String
*/

$latitude = $message->latitude();
?>