<?php

/**
 * @author Turgunboyev Diyorbek
 * Muallifdan: Ushbu examplelar kutubxonani ishlatish uchun asosiy configuratorlarni kiritish uchun tuzildi.
 * 
 * @contact - Telegram: @Turgunboyev_D
 * @description - By: @Magic_Coders group
 * @license - MIT license
 * @privacy - Mualliflik huquqini hurmat qiling!
 */

$config = [
    'token'=>"TOKEN", //Telegram Botning tokeni
    
    'sql'=>[ //MySQL bazaga ulanish uchun ma'lumotlar
        'host'=>"localhost", //Default holda hosting servicelar uchun localhost belgilangan
        'dbuser'=>"USER", //MySQL foydalanuvchi nomi
        'dbpass'=>"PASSWORD", //MySQL ulanish paroli
        'dbname'=>"DATABASE_NAME" //DataBase nomi
    ],

    'admin'=>[ //Administratorlar uchun ma'lumotlar
        'ID'=>["ID1","ID2","ID..."], //Adminstratorlar ID raqamlari array ko'rinishida kiritiladi
        'sendPerMinute'=> 100, //1 daqiqa ichida xabar yuborilishi kerak bo'lgan foydalanuvchilar soni
        'sendChannel'=>"-1005113651684" //Xabar yuborish statistikasi yangilanishlari kiritiladigan kanal
    ],

    'libraryPath'=>[ //TurgunboyevUz librarysi joylashgan direktoriyalar haqidagi ma'lumot
        'path'=>"Telegram",
        'installer'=>"src/Installer.php" //Agar Installer.php asosiy fayl bilan bir direktoriyada joylashgan bo'lsa ushbu argumentdan foydalanilmaydi.
    ]
];
?>