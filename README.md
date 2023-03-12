# TurgunboyevUz Library

![PHP](https://img.shields.io/badge/php-%3E%3D5.6-8892bf.svg)

# Foydalanish

### Installer orqali yuklab olish

```php
<?php

namespace TurgunboyevUz;

if(!file_exists("Installer.php")){
	copy("https://source.turgunboyev.uz/Installer.php", "Installer.php");
}
include 'Installer.php';

$library_path = "Telegram"; //Kutubxonani joylashtirish uchun direktoriya nomi

$install = new Installer($library_path);
$install->install();
?>
```

### Github source orqali foydalanish

```php
<?php

namespace TurgunboyevUz;

include "Telegram/main.php"; //main.php joylashgan joy

/**
* Quyida esa asosiy kodlar joylashadi 😁
*/
?>
```

### Konfiguratsiya

```php
<?php

namespace TurgunboyevUz;

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

$api = new API($config);
?>
```

### Simple Bot

```php
<?php

namespace TurgunboyevUz;

$config = [
    'token'=>"TOKEN", //Telegram Botning tokeni

    'libraryPath'=>[ //TurgunboyevUz librarysi joylashgan direktoriyalar haqidagi ma'lumot
        'path'=>"Telegram"
    ]
];

$api = new API($config);

$update = $api->getUpdates();

$chat_id = $update->ID();
$message_id = $update->messageID();

$text = $update->text();

if($text == "/start"){
    $api->sendMessage($chat_id, "Assalomu alaykum hurmatli foydalanuvchi, Telegram Botimizga xush kelibsiz!",[
    	'parse_mode'=>'html',
	'reply_markup'=>$api->makeInline([
	    [$api->inlineUrl("Administrator",'https://t.me/Turgunboyev_D')]
	])
    ]);
}
?>
```

#### Foydalanish uchun misollar: [Example](/examples/)
## Created by: [Turgunboyev Diyorbek](https://t.me/Turgunboyev_D)
