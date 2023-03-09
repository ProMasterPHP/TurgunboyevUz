<?php

namespace TurgunboyevUz;

$installer = "[*INSTALLER*]";

$config = [
	'token'=>"[*BOT_TOKEN*]",
	
	'sql'=>[
		'host'=>"[*HOST*]",
		'dbuser'=>"[*DBUSER*]",
		'dbpass'=>"[*DBPASS*]",
		'dbname'=>"[*DBNAME*]"
	],

	'admin'=>[
		'ID'=>[],
		'sendChannel'=>'',
		'sendPerMinute'=>"[*REQUEST_PER_MIN*]"
	],

	'libraryPath'=>[
		'path'=>"[*LIBRARY_PATH*]"
	]
];

include $installer;
set_time_limit(9999);

$install = (new Installer($config['libraryPath']['path']))->install();

//Fayl va direktoriyalar bilan ishlovchi funksiyalar
function clean($dir){
	$glob = glob("$dir/*.*");
	foreach ($glob as $gl) {
		if(is_dir($gl)){
			clean($dir);
		}else{
			unlink($gl);
		}
	}
	rmdir($dir);
}


/**
 * @param Yuborish argumentlari
*/

$main = [
	'chat_id' => "[*ID*]",
	'message_id' => "[*MESSAGE_ID*]",
	'channel_id' => "[*CHANNEL_ID*]",
	'channel_message' => "[*CHANNEL_MESSAGE_ID*]",
	'reply_markup' => '[*REPLY*]',
	'chats' => [
		"user" => [*USERS*],
		"group" => [*GROUPS*],
		"channel" => [*CHANNELS*]
	]
];

extract($main);

$tg = new API($config);

if(!file_exists("sentMessages/users.json")){
	$tg->editMessageText($chat_id, (intval($message_id)+1), "✅Yuborish boshlandi...\nQuyidagi buyruqlar orqali xabar yuborish bo'yicha operatsiyalarni bajarishingiz mumkin:",[
		'parse_mode'=>'html',
		'reply_markup'=>$tg->makeInline([
			[$tg->callbackData("Yuborishni bekor qilish🚫", base64_encode("killsend"))],
			[$tg->callbackData("Yuborishni to'xtatish🛑", base64_encode("pausesend"))]
		])
	]);
}

/**
 * @param Yuborishlarni hisoblovchi fayllar
*/
if(!is_dir("sentMessages")){
	mkdir("sentMessages");
}

$users = json_decode(file_get_contents("sentMessages/users.json"), true);
$groups = json_decode(file_get_contents("sentMessages/groups.json"), true);
$channels = json_decode(file_get_contents("sentMessages/channels.json"), true);

$active_user = intval($users['active']);
$passive_user = intval($users['passive']);
$sent_user = intval($users['sent']);
$complete_user = ($users['completed'] == true);

$active_group = intval($groups['active']);
$passive_group = intval($groups['passive']);
$sent_group = intval($groups['sent']);
$complete_group = ($groups['completed'] == true);

$active_channel = intval($channels['active']);
$passive_channel = intval($channels['passive']);
$sent_channel = intval($channels['sent']);
$complete_channel = ($channels['completed'] == true);

$pause = file_exists("pause.txt"); //Yuborishlarni ushlab turish uchun
$kill_send = file_exists("kill_send.txt"); //Yuborishlarni butunlay to'xtatish uchun

if($chats['user'] == false){
	$complete_user = true;
}
if($chats['group'] == false){
	$complete_group = true;
}
if($chats['channel'] == false){
	$complete_channel = true;
}

if($pause == false and $kill_send == false){
	if($complete_user === true){}else{
		$sql = $tg->select("users",["*"],[
			'limit'=>[
				'from'=>$sent_user,
				'offset'=>$tg->per_minute
			]
		])->do();

		$fetch_user = intval($sql->rows());

		while($id = $sql->fetch()){
			$user_id = $id['id'];

			if(empty($reply_markup)){
				$send = $tg->copyMessage($user_id, $chat_id, $message_id);
			}else{
				$send = $tg->copyMessage($user_id, $chat_id, $message_id,[
					'parse_mode'=>'html',
					'reply_markup'=>$reply_markup
				]);
			}

			if($send->ok === true){
				$active_user++;
			}else{
				$passive_user++;
			}

			usleep($tg->interval);
		}

		$users['active'] = $active_user;
		$users['passive'] = $passive_user;
		$users['sent'] = $sent_user+$fetch_user;

		if($fetch_user < $tg->per_minute){
			$users['completed'] = true;
		}

		file_put_contents("sentMessages/users.json", json_encode($users));

		$tg->editMessageText($channel_id, $channel_message, "📩Yuborilmoqda

👤Foydalanuvchilar
Aktiv: ".$active_user."
Noaktiv: ".$passive_user."

👥Guruhlar
Aktiv: 0
Noaktiv: 0

📢Kanallar
Aktiv: 0
Noaktiv: 0", [
			'parse_mode'=>'html'
		]);
	}

	if($complete_group === true){}elseif($complete_group === false and $complete_user === true){
		$sql = $tg->select("groups",["*"],[
			'limit'=>[
				'from'=>$sent_group,
				'offset'=>$tg->per_minute
			]
		])->do();

		$fetch_group = intval($sql->rows());

		while($id = $sql->fetch()){
			$user_id = $id['id'];

			if($reply_markup == "null"){
				$send = $tg->copyMessage($user_id, $chat_id, $message_id);
			}else{
				$send = $tg->copyMessage($user_id, $chat_id, $message_id,[
					'parse_mode'=>'html',
					'reply_markup'=>$reply_markup
				]);
			}

			if($send->ok === true){
				$active_group++;
			}else{
				$passive_group++;
			}

			usleep($tg->interval);
		}

		$groups['active'] = $active_group;
		$groups['passive'] = $passive_group;
		$groups['sent'] = $sent_group+$fetch_group;

		if($fetch_group < $tg->per_minute){
			$groups['completed'] = true;
		}

		file_put_contents("sentMessages/groups.json", json_encode($groups));

		$tg->editMessageText($channel_id, $channel_message, "📩Yuborilmoqda

👤Foydalanuvchilar
Aktiv: ".$active_user."
Noaktiv: ".$passive_user."

👥Guruhlar
Aktiv: ".$active_group."
Noaktiv: ".$passive_group."

📢Kanallar
Aktiv: 0
Noaktiv: 0", [
			'parse_mode'=>'html'
		]);
	}
	
	if($complete_channel === true){}elseif($complete_channel === false and $complete_user === true and $complete_group === true){
		$sql = $tg->select("channels",["*"],[
			'limit'=>[
				'from'=>$sent_channel,
				'offset'=>$tg->per_minute
			]
		])->do();

		$fetch_channel = intval($sql->rows());

		while($id = $sql->fetch()){
			$user_id = $id['id'];

			if($reply_markup == "null"){
				$send = $tg->copyMessage($user_id, $chat_id, $message_id);
			}else{
				$send = $tg->copyMessage($user_id, $chat_id, $message_id,[
					'parse_mode'=>'html',
					'reply_markup'=>$reply_markup
				]);
			}

			if($send->ok === true){
				$active_channel++;
			}else{
				$passive_channel++;
			}

			usleep($tg->interval);
		}

		$channels['active'] = $active_channel;
		$channels['passive'] = $passive_channel;
		$channels['sent'] = $sent_channel+$fetch_channel;

		if($fetch_channel < $tg->per_minute){
			$channels['completed'] = true;
		}

		file_put_contents("sentMessages/channels.json", json_encode($channels));

		$tg->editMessageText($channel_id, $channel_message, "📩Yuborilmoqda

👤Foydalanuvchilar
Aktiv: ".$active_user."
Noaktiv: ".$passive_user."

👥Guruhlar
Aktiv: ".$active_group."
Noaktiv: ".$passive_group."

📢Kanallar
Aktiv: ".$active_channel."
Noaktiv: ".$passive_channel, [
			'parse_mode'=>'html'
		]);
	}

	if($complete_user === true and $complete_group === true and $complete_channel === true){
		$active_all = $active_user + $active_group + $active_channel;
		$passive_all = $passive_user + $passive_group + $passive_channel;

		$all = $active_all + $passive_all;

		$tg->editMessageText($channel_id, $channel_message, "✅Yuborish yakunlandi!

👤Foydalanuvchilar
Aktiv: ".$active_user."
Noaktiv: ".$passive_user."

👥Guruhlar
Aktiv: ".$active_group."
Noaktiv: ".$passive_group."

📢Kanallar
Aktiv: ".$active_channel."
Noaktiv: ".$passive_channel."

📋Hisobot
Aktiv: ".$active_all."
Noaktiv: ".$passive_all."
Jami: ".$all,[
			'parse_mode'=>'html'
		]);

		$tg->deleteMessage($chat_id, ($message_id+1));

		clean("sentMessages");
		unlink(__FILE__);
	}
}else{
	if($kill_send === true){
		clean("sentMessages");
		unlink("kill_send.txt");
		unlink("pause.txt");
		unlink(__FILE__);
	}
}
?>
