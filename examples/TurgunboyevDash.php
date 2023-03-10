<?php

/**
 * @author Turgunboyev Diyorbek
 * Muallifdan: Ushbu examplelar TurgunboyevDash treyti bilan ishlash uchun tuzilgan.
 * Ushbu treyt TelegramBot adminlari uchun qulaylik yaratish maqsadida ishlab chiqildi.
 * Treytda mavjud kamchilik(Adminlar uchun multizadachnost(multifunksionallik) hali mavjud emas, ya'ni bir admin panelni ishlatayotgan vaqtda ikkinchisi paneldan foydalanishida kamchiliklar kuzatiladi.) TurgunboyevUz librarysining v1.0.1 versiyasida tuzatiladi.
 * 
 * Keyingi reliz(v1.0.1)da bloklash uchun sabab kiritish imkoniyati qo'shiladi, hozirda foydalanuvchiga bloklash sababi sifatida - Unknown reason(noma'lum sabab) ko'rsatiladi.
 * 
 * Kamchilik uchun barcha foydalanuvchilardan uzr so'raymiz.
 * 
 * @contact - Telegram: @Turgunboyev_D
 * @description - By: @Magic_Coders group
 * @license - MIT license
 * @privacy - Mualliflik huquqini hurmat qiling!
*/

/**
 * @param admin_command - admin telegram panelni ishga tushirishi uchun yuborishi kerak bo'lgan kommanda nomi(Masalan: /admin_panel)
 * @var - dashboard - Treyt ishlashi uchun talab qilinadigan minimal talablarni tekshiradi va shuningdek admin commandni dasturga kiritadi
 * @return Object
*/

$dash = $tg->dashboard("/admin_panel");

/**
 * @param param - Yaratilishi kerak bo'lgan tablitsalar, default holatda barcha tablitsa(users, groups, channels, subscribe, adminlist)lar yaratiladi
 * @var createDashboard - Admin Panel uchun ma'lumotlar bazasida tablitsalar yaratadi
 * @return Object
*/

$create = $dash->createDashboard();

/**
 * @param id - foydalanuvchi IDsi
 * @param type - foydalanuvchi mansub bo'lgan chat turi($tg->type());
 * 
 * @var inMember - foydalanuvchining bazada bor yoki yo'qligini tekshiradi, agar mavjud bo'lsa true qaytaradi
 * @return Boolean
*/

$is_member = $tg->inMember($chat_id, $tg->type());

/**
 * @var addUser - yangi foydalanuvchini chat turi va IDsini avtomatik bazaga saqlaydi.
 * @return Object
*/

$tg->addUser();

/**
 * @var delUser - agar mavjud foydalanuvchi TelegramBotni bloklasa yoki chat(guruh, kanal)dan chiqarib yuboradigan bo'lsa ushbu foydalanuvchi bazadan chiqarib yuboradi(Bu bilan telegram botning statistikasi realligi ta'minlanadi)
 * @return Object
*/

$tg->delUser();

/**
 * @var runDashboard - Admin Panelni mavjud ma'lumotlar asosida ishga tushiradi.
 * @return Object
*/

$tg->runDashboard();

/**
 * @param chat_id - foydalanuvchi IDsi
 * @var checkChannel - foydalanuvchini bazada mavjud kanallarga a'zoligini tekshiradi, Array ro'yxatni olish uchun $tg->checkChannel ishlatiladi. A'zolik uchun $tg->isSubscribed(); metodidan foydalaniladi
 * @return Object
*/

$check = $tg->checkChannel($chat_id);

/**
 * @var isSubscribed - foydalanuvchini bazada mavjud kanallarga a'zoligini tekshiradi, har doim $tg->checkChannel(); metodi bilan birga ishlatiladi
 * @return Boolean
*/

$isSubscribed = $tg->checkChannel($chat_id)->isSubscribed();

/**
 * @param chat_id - foydalanuvchi IDsi
 * @param notation - default: ❗️Assalomu alaykum hurmatli foydalanuvchi bizning quyidagi kanallarimizga a'zo bo'lmaguningizgacha bizning telegram botimizdan foydalana olmaysiz, avval kanallarimizga a'zo bo'ling va ✅Tekshirish tugmasini bosing: 
 * 
 * @var checkChannel - foydalanuvchini bazada mavjud kanallarga a'zoligini tekshiradi, har doim $tg->checkChannel(); metodi bilan birga ishlatiladi. Agar a'zolik tasdiqlanmasa u holda a'zolik haqidagi notatsiya(bildirishnoma)ni yuborish uchun ishlatiladi
 * @return JSON Array
*/

$tg->checkChannel($chat_id)->sendNotation($chat_id);

/**
 * @param sendNotation(bool) - Notatsiya yuborish kerak yoki yo'qligi(Yuborish uchun true qiymat kiritilishi kerak).
 * @param notation - default: ❗️Assalomu alaykum hurmatli foydalanuvchi bizning quyidagi kanallarimizga a'zo bo'lmaguningizgacha bizning telegram botimizdan foydalana olmaysiz, avval kanallarimizga a'zo bo'ling va ✅Tekshirish tugmasini bosing:
 * 
 * @var checkSubscribe - foydalanuvchini bazada mavjud kanallarga a'zoligini tekshiradi, shuningdek agar a'zolik tasdiqlanmasa ushbu metod foydalanilgan qatordan keyingi barcha operatsiyalar bekor qilinadi!
 * @return A'zolik holatida TG_SUBSCRIBED constantasini qaytaradi.
*/

/**
 * @var isBlockedUser - admin tomonidan bloklangan foydalanuvchi uchun kod yozilgan qismdan keyingi qatorlardagi barcha operatsiyalarni bekor qiladi va foydalanuvchiga bloklangani haqidagi notatsiyani yuboradi.
 * @return Boolean - agar bloklanmagan bo'lsa false qaytariladi.
*/
$tg->isBlockedUser();
?>