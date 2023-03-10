<?php
namespace TurgunboyevUz;

/**
 * @author Turg'unboyev Diyorbek
 * @license MIT license
 * @privacy Mualliflik huquqini hurmat qiling!
 * 
 * Bog'lanish uchun - Telegram: @Turgunboyev_D
*/

trait TurgunboyevDash{
	public function dashboard($admin_command){
		$this->command = $admin_command;
		$this->getUpdates();

		$traits = class_uses($this);
		$use = [
			"TurgunboyevUz\TurgunboyevKey",
			"TurgunboyevUz\TurgunboyevSQL",
			"TurgunboyevUz\TurgunboyevUpdate"
		];

		$filter = [];
		foreach($use as $key){
			if(!in_array($key, $traits)){
				$filter[] = $key;
			}
		}

		if(count($filter) > 0){
			return TelegramError::useTraits([
				'error_type'=>"use_trait",
				'description'=>"Using TurgunboyevDash extension requires the use of ".implode(", ", $filter)." traits."
			]);
		}else{

			if(is_dir("admin")){}else{
				if(mkdir("admin")){}else{
					return TelegramError::createDir([
						'error_type'=>"create_directory",
						'description'=>"Can not create 'admin' directory, please check perms of main file."
					]);
				}
			}
			return $this;
		}
	}

	public function createDashboard($param = []){

		if(count($param) == 0){
			$param = [
				'users'=>true,
				'groups'=>true,
				'channels'=>true,
				'subscribe'=>true,
				'adminlist'=>true
			];
		}

		$dashboard = [];

		if(isset($param['users'])){
			$users = $this->createTable('users',[
				$this->column('id')->varchar(20)->notNull(),
				$this->column('is_banned')->bool(),
				$this->column('ban_reason')->mediumtext()
			])->do()->getDO();

			$dashboard['users'] = $users;
		}

		if(isset($param['groups'])){
			$groups = $this->createTable('groups',[
				$this->column('id')->varchar(20)->notNull(),
				$this->column('is_banned')->bool(),
				$this->column('ban_reason')->mediumtext()
			])->do()->getDO();

			$dashboard['groups'] = $groups;
		}

		if(isset($param['channels'])){
			$channels = $this->createTable('channels',[
				$this->column('id')->varchar(20)->notNull(),
				$this->column('is_banned')->bool(),
				$this->column('ban_reason')->mediumtext()
			])->do()->getDO();

			$dashboard['channels'] = $channels;
		}

		if(isset($param['subscribe'])){
			$subscribe = $this->createTable('subscribe',[
				$this->column('order_id')->int(10)->autoIncrement()->primaryKey(),
				$this->column('id')->varchar(20),
				$this->column('name')->tinytext(),
				$this->column('url')->tinytext(),
				$this->column('type')->bool() //For checking with ID value=[TRUE], for checking with username value=[FALSE]
			])->do()->getDO();

			$dashboard['subscribe'] = $subscribe;
		}

		if(isset($param['adminlist'])){
			$adminList = $this->createTable('adminlist',[
				$this->column('order_id')->int(10)->autoIncrement()->primaryKey(),
				$this->column('id')->varchar(20),
				$this->column('sendMessage')->bool(),
				$this->column('addChannel')->bool(),
				$this->column('addAdmin')->bool(),
				$this->column('blockUser')->bool()
			])->do()->getDO();

			$dashboard['adminlist'] = $adminList;
		}

		$filter = array_filter($dashboard, function($value){
			return ($value == true)?false:true;
		});

		if(count($filter) == 0){
			$this->dashboard = $dashboard;

			return $this;
		}else{		
			return TelegramError::errorDashboard([
				'error_type'=>'create_table',
				'description'=>"Can not create tables - ".implode(", ", array_keys($filter))
			]);
		}
	}

	public function inMember($id, $type){
		$from = [
			'private'=>'users',
			'group'=>'groups',
			'supergroup'=>'groups',
			'channel'=>'channels'
		];

		if(isset($from[$type])){
			$rows = $this->select($from[$type],["*"],[
				'where'=>[
					'id'=>$id
				]
			])->do()->rows();

			if($rows == 0){
				return false;
			}else{
				return true;
			}
		}
	}

	public function addUser(){
		$id = $this->ID();
		$type = $this->type();

		$from = [
			'private'=>"users",
			'group'=>"groups",
			'supergroup'=>"groups",
			'channel'=>"channels"
		];
		if(isset($from[$type])){
			if($this->inMember($id, $type)){
				return $this;
			}else{
				$this->addUser = $this->insert($from[$type],[
					'id'=>$id,
					'is_banned'=>false,
					'ban_reason'=>null
				])->do()->getDO();

				return $this;
			}
		}

		return $this;
	}

	public function delUser(){
		$bot_id = $this->getMe()->result->id;
		$update = $this->getUpdates()->update;

		$from = [
			'private'=>'users',
			'group'=>'groups',
			'supergroup'=>'groups',
			'channel'=>'channels'
		];

		if(isset($update->my_chat_member)){
			$my = $update->my_chat_member;

			$my_chat = $my->chat->id;
			$my_type = $my->chat->type;

			$my_id = $my->new_chat_member->user->id;
			$my_status = $my->new_chat_member->status;

			if($my_status == "left" or $my_status == "kicked"){
				if($my_id == $bot_id){
					$this->delUser = $this->delete($from[$my_type],[
						'id'=>$my_chat
					])->do()->getDO();

					return $this;
				}
			}
		}

		return $this;
	}

	public function setRegion($region = 'Asia/Tashkent'){
		return date_default_timezone_set($region);
	}

	public function makeChart(){
		$user = $this->select('users',['*'])->do()->rows();
		$group = $this->select('groups',['*'])->do()->rows();
		$channel = $this->select('channels',["*"])->do()->rows();

		$blockedUser = $this->select('users',['*'],[
			'where'=>[
				'is_banned'=>true
			]
		])->do()->rows();

		$blockedGroup = $this->select('users',['*'],[
			'where'=>[
				'is_banned'=>true
			]
		])->do()->rows();

		$blockedChannel = $this->select('channels',['*'],[
			'where'=>[
				'is_banned'=>true
			]
		])->do()->rows();

		$all = $user+$group+$channel;
		$allBlocked = $blockedUser+$blockedGroup+$blockedChannel;

		$this->setRegion();

		$time = date("H:i:s");
		$date = date("d/m/Y");

		return "📊 Statistika
🕒 $date $time

👤 Foydalanuvchilar: $user
👥 Guruhlar: $group
📢 Kanallar: $channel
📌 Jami: $all

🚫Banlangan foydalanuvchilar:
👉 Foydalanuvchilar: $blockedUser
👉 Guruhlar: $blockedGroup
👉 Kanallar: $blockedChannel
📌 Jami: $allBlocked";
	}

	public function isPromotedAdmin($id){
		$is = $this->select('adminlist',['*'],[
			'where'=>[
				'id'=>$id
			]
		])->do()->rows();

		if($is == 1){
			return true;
		}else{
			return false;
		}
	}

	public function getAdminPromotions($id){
		return $this->select('adminlist',['*'],[
			'where'=>[
				'id'=>$id
			]
		])->do()->fetch();
	}

	public function mainKeyboard($id = null){

		if(in_array($id, $this->admin)){
			return $this->makeInline([
				[$this->callbackData("📊Statistika", base64_encode('chart'))],
				[$this->callbackData("✉️Xabar yuborish", base64_encode('send')), $this->callbackData("📧Forward yuborish", base64_encode('forward'))],
				[$this->callbackData("➕Kanal qo'shish",base64_encode('addChannel')), $this->callbackData("📃Ro'yxat",base64_encode('listChannel')), $this->callbackData("➖Kanal olish", base64_encode('minChannel'))],
				[$this->callbackData("➕Admin qo'shish",base64_encode('addAdmin')), $this->callbackData("📃Ro'yxat",base64_encode('listAdmin')), $this->callbackData("➖Admin olish", base64_encode('minAdmin'))],
				[$this->callbackData("➕Ban berish",base64_encode('blockUser')), $this->callbackData("➖Ban olish", base64_encode('unblockUser'))],
			]);
		}else{
			$promote = $this->getAdminPromotions($id);
			$keyboard = [
				[$this->callbackData("📊Statistika", base64_encode('chart'))]
			];

			if($promote['sendMessage'] == true){
				$keyboard[] = [$this->callbackData("✉️Xabar yuborish", base64_encode('send')), $this->callbackData("📧Forward yuborish", base64_encode('forward'))];
			}

			if($promote['addChannel'] == true){
				$keyboard[] = [$this->callbackData("➕Kanal qo'shish",base64_encode('addChannel')), $this->callbackData("📃Ro'yxati",base64_encode('listChannel')), $this->callbackData("➖Kanal olish", base64_encode('minChannel'))];
			}

			if($promote['addAdmin'] == true){
				$keyboard[] = [$this->callbackData("➕Admin qo'shish",base64_encode('addAdmin')), $this->callbackData("📃Ro'yxat",base64_encode('listAdmin')), $this->callbackData("➖Admin olish", base64_encode('minAdmin'))];
			}

			if($promote['blockUser'] == true){
				$keyboard[] = [$this->callbackData("➕Ban berish",base64_encode('blockUser')), $this->callbackData("➖Ban olish", base64_encode('unblockUser'))];
			}

			return $this->makeInline($keyboard);
		}
	}

	public function makeChatList($type){
		return $this->makeInline([
			[$this->callbackData('👤Users',base64_encode('users')),$this->callbackData('👥Guruhlar',base64_encode('groups')),$this->callbackData('📢Kanallar',base64_encode('channels'))],
			[$this->callbackData('📈Barcha',base64_encode('all'))],
			[$this->callbackData('🔙Orqaga',base64_encode('back')), $this->callbackData('🔜Keyingisi',base64_encode($type."next"))]
		]);
	}

	public function replyMarkupSendToAll($data, $chat_id, $message_id, $reply_markup){
		$dec = base64_decode($data);
		$reply = json_decode(json_encode($reply_markup), true);

		$t1 = "👤Users";
		$t2 = "👥Guruhlar";
		$t3 = "📢Kanallar";

		$tt1 = $t1."🟢";
		$tt2 = $t2."🟢";
		$tt3 = $t3."🟢";

		if($dec == "users" or $dec == "groups" or $dec == "channels" or $dec == "all"){
			$file = 'admin/sendFile.json';
			if(file_exists($file)){
				$js = json_decode(file_get_contents($file),true);
			}else{
				$js = [
					'type'=>str_replace("next", "", base64_decode($reply['inline_keyboard'][2][1]['callback_data'])),
					'user'=>false,
					'group'=>false,
					'channel'=>false
				];

				file_put_contents($file, json_encode($js));
			}

			$fs = $js;

			//Belgilangan chatlarni aniqlash
			
			if($js['user'] == false and $dec == "users"){
				$reply['inline_keyboard'][0][0]['text'] = $tt1;
				$js['user'] = true;
			}

			if($js['group'] == false and $dec == "groups"){
				$reply['inline_keyboard'][0][1]['text'] = $tt2;
				$js['group'] = true;
			}

			if($js['channel'] == false and $dec == "channels"){
				$reply['inline_keyboard'][0][2]['text'] = $tt3;
				$js['channel'] = true;
			}

			if($fs['user'] == true and $dec == "users"){
				$reply['inline_keyboard'][0][0]['text'] = $t1;
				$js['user'] = false;
			}

			if($fs['group'] == true and $dec == "groups"){
				$reply['inline_keyboard'][0][1]['text'] = $t2;
				$js['group'] = false;
			}

			if($fs['channel'] == true and $dec == "channels"){
				$reply['inline_keyboard'][0][2]['text'] = $t3;
				$js['channel'] = false;
			}
			
			if($dec == "all"){
				if($js['user'] == true and $js['group'] == true and $js['channel'] == true){
					$js['user'] = false;
					$js['group'] = false;
					$js['channel'] = false;

					$reply['inline_keyboard'][0][0]['text'] = $t1;
					$reply['inline_keyboard'][0][1]['text'] = $t2;
					$reply['inline_keyboard'][0][2]['text'] = $t3;
				}else{
					$js['user'] = true;
					$js['group'] = true;
					$js['channel'] = true;

					$reply['inline_keyboard'][0][0]['text'] = $tt1;
					$reply['inline_keyboard'][0][1]['text'] = $tt2;
					$reply['inline_keyboard'][0][2]['text'] = $tt3;
				}
			}

			file_put_contents($file, json_encode($js));

			$this->editMessageReplyMarkup($chat_id, $message_id, json_encode($reply));
		}
	}

	public function backKeyboard(){
		return $this->makeInline([
			[$this->callbackData('🔙Orqaga', base64_encode('back'))]
		]);
	}

	public function getChannelList(){
		return $this->select('subscribe',["*"])->do()->fetch_all();
	}

	public function makeChannelList($type = 'list', $list = []){

		if(empty($list)){
			$channelList = $this->getChannelList();
		}else{
			$channelList = $list;
		}

		if($type == 'list'){
			$keyboard = [];

			foreach($channelList as $value){
				if($value['type'] == false){
					$url = "https://t.me/".$value['url'];
				}else{
					$url = $value['url'];
				}

				$keyboard[] = [$this->inlineUrl($value['name'], $url)];
			}
		}
		
		if($type == "del"){
			$keyboard = [];

			foreach($list as $value){
				$keyboard[] = [$this->callbackData($value['name'], base64_encode("deleteChannel_".$value['order_id']))];
			}
		}

		if(empty($list)){
			$keyboard[] = [$this->callbackData("🔙Orqaga", base64_encode("back"))];
		}else{
			$keyboard[] = [$this->callbackData("✅Tekshirish", base64_encode("check"))];
		}

		return $this->makeInline($keyboard);
	}

	public function deleteChannel($data, $chat_id, $message_id){
		$dec = base64_decode($data);

		if(mb_stripos($dec, "deleteChannel_")!==false){
			$str = str_replace("deleteChannel_", "", $dec);
			$this->delete('subscribe',[
				'order_id'=>$str
			])->do();

			$this->editMessageText($chat_id, $message_id, "📢Kanal ro'yxatdan muvaffaqiyatli o'chirildi!",[
				'parse_mode'=>'html',
				'reply_markup'=>$this->backKeyboard()
			]);
		}

		return $this;
	}

	public function buttonAdministratorRights(){
		return $this->makeInline([
			[$this->callbackData("✉️Xabar yuborish", base64_encode('sendRight'))],
			[$this->callbackData("➕Kanal qo'shish/olish", base64_encode('channelRight'))],
			[$this->callbackData("➕Admin qo'shish/olish", base64_encode('adminRight'))],
			[$this->callbackData("🚫Ban berish/olish", base64_encode('blockRight'))],
			[$this->callbackData("✅Barcha", base64_encode('allRight'))],
			[$this->callbackData("🔙Orqaga",base64_encode('back')), $this->callbackData('🔜Keyingisi', base64_encode('nextRight'))]
		]);
	}

	public function makeAdminList($type = 'list'){

		$list = $this->select('adminlist', ["*"])->do()->fetch_all();

		$keyboard = [];
		if($type == "list"){
			foreach($list as $value){
				$keyboard[] = [$this->callbackData($value['id'], uniqid(true))];
			}
		}elseif($type == "del"){
			foreach($list as $value){
				$keyboard[] = [$this->callbackData($value['id'], base64_encode('deleteAdmin_'.$value['order_id']))];
			}
		}

		$keyboard[] = [$this->callbackData("🔙Orqaga", base64_encode("back"))];

		return $this->makeInline($keyboard);
	}

	public function replyMarkupAdministratorRights($data, $chat_id, $message_id, $reply_markup){
		$t1 = "✉️Xabar yuborish";
		$t2 = "➕Kanal qo'shish/olish";
		$t3 = "➕Admin qo'shish/olish";
		$t4 = "🚫Ban berish/olish";

		$tt1 = $t1."🟢";
		$tt2 = $t2."🟢";
		$tt3 = $t3."🟢";
		$tt4 = $t4."🟢";

		$dec = base64_decode($data);
		$reply = json_decode(json_encode($reply_markup), true);

		if($dec == "sendRight" or $dec == "channelRight" or $dec == "adminRight" or $dec == "blockRight" or $dec == "allRight"){
			$file = "admin/adminRight.json";
			if(file_exists($file)){
				$js = json_decode(file_get_contents($file), true);
			}else{
				$js = [
					'send'=>false,
					'channel'=>false,
					'admin'=>false,
					'block'=>false
				];

				file_put_contents($file, json_encode($js));
			}

			$fs = $js;

			if($dec == "sendRight" and $js['send'] == false){
				$reply['inline_keyboard'][0][0]['text'] = $tt1;
				$js['send'] = true;
			}

			if($dec == "channelRight" and $js['channel'] == false){
				$reply['inline_keyboard'][1][0]['text'] = $tt2;
				$js['channel'] = true;
			}

			if($dec == "adminRight" and $js['admin'] == false){
				$reply['inline_keyboard'][2][0]['text'] = $tt3;
				$js['admin'] = true;
			}

			if($dec == "blockRight" and $js['block'] == false){
				$reply['inline_keyboard'][3][0]['text'] = $tt4;
				$js['block'] = true;
			}


			if($dec == "sendRight" and $fs['send'] == true){
				$reply['inline_keyboard'][0][0]['text'] = $t1;
				$js['send'] = false;
			}

			if($dec == "channelRight" and $fs['channel'] == true){
				$reply['inline_keyboard'][1][0]['text'] = $t2;
				$js['channel'] = false;
			}

			if($dec == "adminRight" and $fs['admin'] == true){
				$reply['inline_keyboard'][2][0]['text'] = $t3;
				$js['admin'] = false;
			}

			if($dec == "blockRight" and $fs['block'] == true){
				$reply['inline_keyboard'][3][0]['text'] = $t4;
				$js['block'] = false;
			}

			if($dec == "allRight"){
				if($js['send'] == true and $js['channel'] == true and $js['admin'] == true and $js['block'] == true){
					$js['send'] = false;
					$js['channel'] = false;
					$js['admin'] = false;
					$js['block'] = false;

					$reply['inline_keyboard'][0][0]['text'] = $t1;
					$reply['inline_keyboard'][1][0]['text'] = $t2;
					$reply['inline_keyboard'][2][0]['text'] = $t3;
					$reply['inline_keyboard'][3][0]['text'] = $t4;
				}else{
					$js['send'] = true;
					$js['channel'] = true;
					$js['admin'] = true;
					$js['block'] = true;

					$reply['inline_keyboard'][0][0]['text'] = $tt1;
					$reply['inline_keyboard'][1][0]['text'] = $tt2;
					$reply['inline_keyboard'][2][0]['text'] = $tt3;
					$reply['inline_keyboard'][3][0]['text'] = $tt4;
				}
			}

			file_put_contents($file, json_encode($js));

			$this->editMessageReplyMarkup($chat_id, $message_id, json_encode($reply));
		}
	}

	public function deleteAdmin($data, $chat_id, $message_id){
		$dec = base64_decode($data);

		if(mb_stripos($dec, "deleteAdmin_")!==false){
			$str = str_replace("deleteAdmin_", "", $dec);
			$this->delete('adminlist',[
				'order_id'=>$str
			])->do();

			$this->editMessageText($chat_id, $message_id, "🆔Administrator ro'yxatdan muvaffaqiyatli o'chirildi!",[
				'parse_mode'=>'html',
				'reply_markup'=>$this->backKeyboard()
			]);
		}

		return $this;
	}

	public function runDashboard(){
		$update = $this->getUpdates();
		$chat_id = $update->ID();
		$message_id = $update->messageID();

		$text = $update->text();
		$data = $update->queryData();

		$reply_markup = $this->replyMarkup();

		if(in_array($chat_id, $this->admin) or $this->isPromotedAdmin($chat_id)){
			if($text == $this->command){
				$this->sendMessage($chat_id, "🗂Boshqaruv paneliga xush kelibsiz.",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->mainKeyboard($chat_id)
				]);
			}

			if($data == base64_encode("back")){

				unlink("admin/sendFile.json");
				unlink("admin/adminRight.json");
				unlink("admin/addChannel.json");

				unlink("admin/blockUser.txt");
				unlink("admin/unblockUser.txt");

				unlink("admin/sendMessage.php");

				$this->editMessageText($chat_id, $message_id, "🗂Boshqaruv paneliga xush kelibsiz.",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->mainKeyboard($chat_id)
				]);
			}

			if($data == base64_encode('chart')){
				$this->editMessageText($chat_id, $message_id, $this->makeChart(), [
					'parse_mode'=>'html',
					'reply_markup'=>$this->backKeyboard()
				]);
			}

			/**
			 * @param Xabar yuborish qismi
			*/

			if($data == base64_encode('send')){
				$this->editMessageText($chat_id, $message_id, "✉️Xabar yuborilishi kerak bo'lgan chatlarni tanlang:",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->makeChatList('send')
				]);
			}

			if($data == base64_encode('forward')){
				$this->editMessageText($chat_id, $message_id, "📧Xabar yuborilishi kerak bo'lgan chatlarni tanlang:",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->makeChatList('forward')
				]);
			}

			if(!empty($data)){
				$this->replyMarkupSendToAll($data, $chat_id, $message_id, $reply_markup);
			}

			if($data == base64_encode("sendnext") or $data == base64_encode("forwardnext")){
				$file = 'admin/sendFile.json';

				if(file_exists($file)){
					$js = json_decode(file_get_contents($file),true);

					if(count(array_filter($js)) == 0){
						$this->answerCallbackQuery($this->queryID(),"Siz hali chatlarni tanlamadingiz!",[
							'show_alert'=>true
						]);
					}else{
						$js['ok'] = true;
						if($data == base64_encode("sendnext")){
							$js['type'] = "send";
						}else{
							$js['type'] = "forward";
						}

						file_put_contents($file, json_encode($js));

						$this->editMessageText($chat_id,$message_id,"ℹ️Telegram Bot foydalanuvchilariga yuborilishi kerak bo'lgan xabarni menga yuboring:",[
							'parse_mode'=>'html',
							'reply_markup'=>$this->backKeyboard()
						]);
					}
				}else{
					$this->answerCallbackQuery($this->queryID(),"Siz hali chatlarni tanlamadingiz!",[
						'show_alert'=>true
					]);
				}
			}

			if($this->isMessage()){
				$file = 'admin/sendFile.json';

				if(file_exists($file)){
					$js = json_decode(file_get_contents($file),true);

					if($js['ok'] == true){
						if($js['type'] == "send"){
							$sendFile = $this->library_path."/src/Helpers/sendAll/sendMessageToAll.php";
						}elseif($js['type'] == "forward"){
							$sendFile = $this->library_path."/src/Helpers/sendAll/forwardMessageToAll.php";
						}

						$channel_message_id = $this->sendMessage($this->sendChannel, "🌀Cron-Job kutilmoqda...")->result->message_id;

						$from = ["[*INSTALLER*]","[*BOT_TOKEN*]","[*HOST*]","[*DBUSER*]","[*DBPASS*]","[*DBNAME*]","[*REQUEST_PER_MIN*]","[*LIBRARY_PATH*]","[*ID*]","[*MESSAGE_ID*]","[*CHANNEL_ID*]","[*CHANNEL_MESSAGE_ID*]","[*REPLY*]","[*USERS*]","[*GROUPS*]","[*CHANNELS*]"];

						$to_docs = ["../".$this->installer, $this->token, $this->host, $this->db_user, $this->db_pass, $this->db_name, $this->per_minute, str_replace("../", "", $this->library_path), $chat_id, $message_id, $this->sendChannel, $channel_message_id, $reply_markup, intval($js['user']), intval($js['group']), intval($js['channel'])];

						$getFile = file_get_contents($sendFile);
						$str = str_replace($from, $to_docs, $getFile);

						$this->sendMessage($chat_id, "🌀Yuborish fayli tayyorlanmoqda...");

						file_put_contents("admin/sendMessage.php", $str);

						if($js['type'] == "send"){
							$message_type = "Oddiy";
						}else{
							$message_type = "Forward";
						}

						$user_count = $this->select('users',["*"])->do()->rows();
						$group_count = $this->select('groups',["*"])->do()->rows();
						$channel_count = $this->select('channels',["*"])->do()->rows();

						$all_count = $user_count + $group_count + $channel_count;
						$after = intdiv($all_count, $this->per_minute)+1;

						$this->setRegion();
						$start = date("H:i",strtotime("+1 minutes"));
						$finish = date("H:i", strtotime("+$after minutes"));

						$this->editMessageText($chat_id, ($message_id+1), "⚙️Yuborish sozlamalari

💬Xabar turi: $message_type
⏳Cron-Job: 1 daqiqa
🏳️Boshlanish vaqti: $start (±2)
🏁Tugash vaqti: $finish (±3)
🔗Havola: <code>https://".$_SERVER['HTTP_HOST'].str_replace(strrchr($_SERVER['PHP_SELF'], "/"),"/admin/sendMessage.php",$_SERVER['SCRIPT_NAME'])."</code>",[
							'parse_mode'=>'html',
							'reply_markup'=>$this->backKeyboard()
						]);

						unlink("admin/sendFile.json");
					}
				}
			}

			if($data == base64_encode("pausesend")){
				file_put_contents("admin/pause.txt", "1");
				$this->editMessageText($chat_id, $message_id, "🛑Yuborish vaqtincha to'xtatildi!",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->makeInline([
						[$this->callbackData("Yuborishni bekor qilish🚫", base64_encode("killsend"))],
						[$this->callbackData("Yuborishni davom ettirish📨", base64_encode("continuesend"))]
					])
				]);
			}

			if($data == base64_encode("continuesend")){
				unlink("admin/pause.txt");

				$this->editMessageText($chat_id,$message_id,"📨Yuborish davom ettirilmoqda...",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->makeInline([
						[$this->callbackData("Yuborishni bekor qilish🚫", base64_encode("killsend"))],
						[$this->callbackData("Yuborishni to'xtatish🛑"), base64_encode("pausesend")]
					])
				]);
			}

			if($data == base64_encode("killsend")){
				file_put_contents("admin/kill_send.txt", "1");

				$this->editMessageText($chat_id,$message_id, "🚫Yuborish bekor qilindi!", [
					'parse_mode'=>'html',
					'reply_markup'=>$this->backKeyboard()
				]);
			}

			/**
			 * @param Majburiy a'zolik qo'shish qismi
			*/
			$add = json_decode(file_get_contents("admin/addChannel.json"),true);

			if($data == base64_encode("addChannel")){
				$this->editMessageText($chat_id, $message_id, "🔗Qo'shilishi kerak bo'lgan kanalning linkini yuboring(Namuna: @Kanal_Useri yoki https://t.me/joinchat/...):", [
					'parse_mode'=>'html',
					'reply_markup'=>$this->backKeyboard()
				]);

				file_put_contents("admin/addChannel.json", json_encode([
					'step'=>"add"
				]));
			}

			if($text and $add['step'] == "add"){
				if(stripos($text, "@")!==false){
					$add['url'] = str_replace("@", "", $text);
					$add['type'] = false;
				}elseif(stripos($text, "https://t.me")!==false or stripos($text, "http://t.me")){
					$add['url'] = $text;
					$add['type'] = true;
				}

				$add['step'] = "name";
				file_put_contents("admin/addChannel.json", json_encode($add));

				$add = [];

				$this->sendMessage($chat_id, "🔤Kanal uchun nom yuboring(Masalan: Musiqalar kanali, Hikoyalar):",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->backKeyboard()
				]);
			}

			if($text and $add['step'] == "name"){
				if($add['type'] == true){
					$this->sendMessage($chat_id, "🆔Kanalning ID raqamini yuboring(Namuna: -10010435478):",[
						'parse_mode'=>'html',
						'reply_markup'=>$this->backKeyboard()
					]);

					$add['name'] = $text;
					$add['step'] = "id";
					file_put_contents("admin/addChannel.json", json_encode($add));

					$add = [];
				}else{
					$this->insert('subscribe',[
						'id'=>NULL,
						'name'=>$text,
						'url'=>$add['url'],
						'type'=>boolval($add['type'])
					])->do();

					$this->sendMessage($chat_id, "👌Ushbu kanal ro'yxatga muvaffaqiyatli kiritildi!",[
						'parse_mode'=>'html',
						'reply_markup'=>$this->backKeyboard()
					]);

					unlink("admin/addChannel.json");
				}
			}

			if($text and $add['step'] == "id"){
				if(stripos($text, "-100")!==false){}else{
					$text = "-100".$text;
				}

				$this->insert('subscribe',[
					'id'=>$text,
					'name'=>$add['name'],
					'url'=>$add['url'],
					'type'=>boolval($add['type'])
				])->do();

				$this->sendMessage($chat_id, "👌Ushbu kanal ro'yxatga muvaffaqiyatli kiritildi!",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->backKeyboard()
				]);

				unlink("admin/addChannel.json");
			}

			if($data == base64_encode("minChannel")){
				$this->editMessageText($chat_id, $message_id, "📃Quyida Telegram Bot adminlari tomonidan qo'shilgan kanallar ro'yxati, telegram kanalni o'chirish uchun ulardan birini tanlang: ",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->makeChannelList('del')
				]);
			}

			if($data == base64_encode("listChannel")){
				$this->editMessageText($chat_id, $message_id, "📃Telegram Bot adminlari tomonidan qo'shilgan kanallar ro'yxati:",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->makeChannelList()
				]);
			}

			if(!empty($data)){
				$this->deleteChannel($data, $chat_id, $message_id);
			}

			/**
			 * @param Admin qo'shish va olib tashlash
			*/

			if($data == base64_encode("addAdmin")){
				$this->editMessageText($chat_id, $message_id, "👮‍♂️Qo'shmoqchi bo'lgan administratoringiz uchun admin panel huquqlarini tanlang: ",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->buttonAdministratorRights()
				]);
			}

			if($data == base64_encode("minAdmin")){
				$this->editMessageText($chat_id, $message_id, "👮‍♂️Quyidan o'chirilishi kerak bo'lgan administratorni tanlang:", [
					'parse_mode'=>'html',
					'reply_markup'=>$this->makeAdminList('del')
				]);
			}

			if(!empty($data)){
				$this->deleteAdmin($data, $chat_id, $message_id);
			}

			if($data == base64_encode("listAdmin")){
				$this->editMessageText($chat_id, $message_id, "📃Quyida asosiy admin tomonidan qo'shilgan adminlar ro'yxati berilgan:", [
					'parse_mode'=>'html',
					'reply_markup'=>$this->makeAdminList('list')
				]);
			}

			if(!empty($data)){
				$this->replyMarkupAdministratorRights($data, $chat_id, $message_id, $reply_markup);
			}

			if($data == base64_encode('nextRight')){
				$file = "admin/adminRight.json";
				$js = json_decode(file_get_contents($file), true);

				$js['ok'] = true;
				file_put_contents($file, json_encode($js));

				$this->editMessageText($chat_id, $message_id, "🆔Administrator qilinishi kerak bo'lgan foydalanuvchining ID raqamini yuboring",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->backKeyboard()
				]);
			}

			if(!empty($text)){
				$file = 'admin/adminRight.json';

				if(file_exists($file)){
					$js = json_decode(file_get_contents($file), true);
					if($js['ok'] == true){
						if(is_numeric($text)){
							$this->insert('adminlist',[
								'id'=>$text,
								'sendMessage'=>$js['send'],
								'addChannel'=>$js['channel'],
								'addAdmin'=>$js['admin'],
								'blockUser'=>$js['block']
							])->do();

							$this->sendMessage($chat_id, "🆔Ushbu ID raqamga ega bo'lgan foydalanuvchi administrator etib saylandi: ",[
								'parse_mode'=>'html',
								'reply_markup'=>$this->backKeyboard()
							]);
						}else{
							$this->sendMessage($chat_id, "❗️Administrator IDsi faqat raqamlardan iborat bo'lishi kerak, iltimos IDni qayta kiriting: ",[
								'parse_mode'=>'html',
								'reply_markup'=>$this->backKeyboard()
							]);
						}					
					}
				}
			}

			if($data == base64_encode('blockUser')){
				$this->editMessageText($chat_id, $message_id, "🚫Bloklanishi kerak bo'lgan foydalanuvchining ID raqamini kiriting: ",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->backKeyboard()
				]);

				file_put_contents("admin/blockUser.txt","1");
			}

			if($data == base64_encode('unblockUser')){
				$this->editMessageText($chat_id, $message_id, "✔️Blokdan olinishi kerak bo'lgan foydalanuvchining ID raqamini kiriting: ",[
					'parse_mode'=>'html',
					'reply_markup'=>$this->backKeyboard()
				]);

				file_put_contents("admin/unblockUser.txt","1");
			}

			if(!empty($text)){
				if(file_exists("admin/blockUser.txt")){
					if(is_numeric($text)){
						$this->update('users',[
							'is_banned'=>true,
							'ban_reason'=>"Unknown reason"
						],[
							'id'=>$text
						]);

						$this->sendMessage($chat_id, "🚫Ushbu ID raqami egasi bloklandi.",[
							'parse_mode'=>'html',
							'reply_markup'=>$this->backKeyboard()
						]);

						unlink('admin/blockUser.txt');
					}else{
						$this->sendMessage($chat_id, "❗️Iltimos ID raqamni faqat raqamlar orqali kiriting: ",[
							'parse_mode'=>'html',
							'reply_markup'=>$this->backKeyboard()
						]);
					}
				}

				if(file_exists("admin/unblockUser.txt")){
					if(is_numeric($text)){
						$this->update('users',[
							'is_banned'=>false,
							'ban_reason'=>NULL
						],[
							'id'=>$text
						]);

						$this->sendMessage($chat_id, "✔️Ushbu ID raqami egasi blokdan olindi.",[
							'parse_mode'=>'html',
							'reply_markup'=>$this->backKeyboard()
						]);

						unlink('admin/unblockUser.txt');
					}else{
						$this->sendMessage($chat_id, "❗️Iltimos ID raqamni faqat raqamlar orqali kiriting: ",[
							'parse_mode'=>'html',
							'reply_markup'=>$this->backKeyboard()
						]);
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @param Kanalga a'zolikni tekshirish
	*/

	public function checkChannel($chat_id){
		$list = $this->getChannelList();
		$success = ["creator","administrator","member"];

		$array = [];
		foreach($list as $value){
			if($value['type'] == true){
				$status = $this->getChatMember($value['id'], $chat_id)->result->status;

				if(in_array($status, $success)){}else{
					$array[] = $value;
				}
			}else{
				$status = $this->getChatMember("@".$value['url'], $chat_id)->result->status;

				if(in_array($status, $success)){}else{
					$array[] = $value;
				}
			}
		}

		$this->checkChannel = $array;

		return $this;
	}

	public function isSubscribed(){
		if(empty($this->checkChannel)){
			return true;
		}else{
			return false;
		}
	}

	public function sendNotation($chat_id, $notation = ""){

		if(empty($notation)){
			$notation = "❗️Assalomu alaykum hurmatli foydalanuvchi bizning quyidagi kanallarimizga a'zo bo'lmaguningizgacha bizning telegram botimizdan foydalana olmaysiz, avval kanallarimizga a'zo bo'ling va ✅Tekshirish tugmasini bosing: ";
		}

		if(empty($this->checkChannel)){
			return true;
		}else{
			$list = $this->checkChannel;
			$button = $this->makeChannelList('list', $list);

			if($this->isMessage()){
				$this->sendMessage($chat_id, $notation, [
					'parse_mode'=>'html',
					'reply_markup'=>$button
				]);
			}elseif($this->isCallback()){
				$this->editMessageText($chat_id, $message_id, $notation,[
					'parse_mode'=>'html',
					'reply_markup'=>$button
				]);
			}
		}
	}

	public function checkSubscribe($sendNotation = false, $notation = ''){
		$update = $this->getUpdates();
		$chat_id = $update->ID();
		$message_id = $update->messageID();

		$text = $update->text();
		$data = $update->queryData();

		if($sendNotation == true){
			if($this->isMessage() or $this->isCallback()){
				if($this->checkChannel($chat_id)->isSubscribed()){}else{
					$this->sendNotation($chat_id, $notation);
					exit();
				}
			}
		}else{
			if($this->isMessage() or $this->isCallback()){
				if($this->checkChannel($chat_id)->isSubscribed()){}else{
					exit();
				}
			}
		}

		if($data == base64_encode("check")){
			$this->deleteMessage($chat_id, $message_id);

			return TG_SUBSCRIBED;
		}
	}

	public function isBlockedUser(){
		if($this->isMessage() or $this->isCallback()){
			$user = $this->select('users', [
				'id'=>$this->ID(),
			])->do()->fetch();

			if($user['is_banned'] == true){
				$this->sendMessage($chat_id, "Hurmatli foydalanuvchi siz telegram bot adminlari tomonidan bloklangansiz, shu sababli telegram botimizdan foydalana olmaysiz!\nSabab: ".$user['ban_reason']);
				exit();
			}else{
				return false;
			}
		}
	}
}
?>
