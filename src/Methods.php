<?php

namespace TurgunboyevUz;

/**
 * @author Turg'unboyev Diyorbek
 * @license MIT license
 * @privacy Mualliflik huquqini hurmat qiling!
 * 
 * Bog'lanish uchun - Telegram: @Turgunboyev_D
*/

class Telegram implements Methods{

    /**
     * @param Telegram API ishlashi uchun kerak bo'ladigan minimal ma'lumotlar
    */

    public function __construct($config){
        $this->token = $config['token'];
        //$this->username = $config['bot_username'];

        if(isset($config['sql'])){
            $this->host = $config['sql']['host'];
            $this->db_user = $config['sql']['dbuser'];
            $this->db_pass = $config['sql']['dbpass'];
            $this->db_name = $config['sql']['dbname'];
        }

        if(isset($config['admin'])){
            $this->admin = $config['admin']['ID'];
            $this->interval = (60/intval($config['admin']['sendPerMinute']))*1000;
            $this->per_minute = $config['admin']['sendPerMinute'];
            $this->sendChannel = $config['admin']['sendChannel'];
            //$this->error_log = $config['admin']['log'];
        }

        if(isset($config['libraryPath'])){
            $this->library_path = $this->realpath().$config['libraryPath']['path'];

            if(isset($config['libraryPath']['installer'])){
                $this->installer = $config['libraryPath']['installer'];
            }else{
                $this->installer = "Installer.php";
            }
        }
    }

    public function realpath(){
        $dir = dirname($_SERVER['PHP_SELF']);
        $sub = substr_count($dir, "/");

        $path = "";
        for($i = 0;$i < $sub;$i++){
            $path .= "../";
        }

        return $path;
    }

    //Telegram Bot requests
    public function Request($method,$data = []){
        $url = "https://api.telegram.org/bot".$this->token."/".$method;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if(curl_error($ch) or curl_errno($ch)){
            throw new Exception(curl_error($ch)."<br>".curl_errno($ch), 1);
        }

        return json_decode($response);
    }

    public function setWebhook($url, $arg = []){
        $js = $this->Request('setWebhook',array_merge([
            'url'=>$url
        ], $arg));

        return $js;
    }
    public function deleteWebhook($drop_pending_update = null){
        if(is_null($drop_pending_update)){
            $data = [];
        }else{
            $data = [
                'drop_pending_update'=>$drop_pending_update
            ];
        }

        $js = $this->Request('deleteWebhook',$data);

        return $js;
    }
    public function getWebhookInfo(){
        $js = $this->Request('getWebhookInfo');
        return $js;
    }
    public function getMe(){
        $js = $this->Request('getMe');
        return $js;
    }
    public function logout(){
        $js = $this->Request('logout');
        return $js;
    }
    public function close(){
        $js = $this->Request('close');
        return $js;
    }
    public function sendMessage($chat_id, $text, $arg = []){
        $js = $this->Request('sendMessage',array_merge([
            'chat_id'=>$chat_id,
            'text'=>$text
        ],$arg));
        return $js;
    }
    public function forwardMessage($chat_id, $from_id, $message_id, $arg = []){
        $js = $this->Request('forwardMessage',array_merge([
            'chat_id'=>$chat_id,
            'from_chat_id'=>$from_id,
            'message_id'=>$message_id
        ],$arg));
        return $js;
    }
    public function copyMessage($chat_id, $from_id, $message_id, $arg = []){
        $js = $this->Request('copyMessage',array_merge([
            'chat_id'=>$chat_id,
            'from_chat_id'=>$from_id,
            'message_id'=>$message_id
        ],$arg));
        return $js;
    }
    public function sendPhoto($chat_id, $photo, $arg = []){
        $js = $this->Request('sendPhoto',array_merge([
            'chat_id'=>$chat_id,
            'photo'=>$photo
        ],$arg));
        return $js;
    }
    public function sendAudio($chat_id, $audio, $arg = []){
        $js = $this->Request('sendAudio',array_merge([
            'chat_id'=>$chat_id,
            'audio'=>$audio
        ],$arg));
        return $js;
    }
    public function sendDocument($chat_id, $document, $arg = []){
        $js = $this->Request('sendDocument',array_merge([
            'chat_id'=>$chat_id,
            'document'=>$document
        ],$arg));
        return $js;
    }
    public function sendVideo($chat_id, $video, $arg = []){
        $js = $this->Request('sendVideo',array_merge([
            'chat_id'=>$chat_id,
            'video'=>$video
        ],$arg));
        return $js;
    }
    public function sendAnimation($chat_id, $animation, $arg = []){
        $js = $this->Request('sendAnimation',array_merge([
            'chat_id'=>$chat_id,
            'animation'=>$animation
        ],$arg));
        return $js;
    }
    public function sendVoice($chat_id, $voice, $arg = []){
        $js = $this->Request('sendVoice',array_merge([
            'chat_id'=>$chat_id,
            'voice'=>$voice
        ],$arg));
        return $js;
    }
    public function sendVideoNote($chat_id, $videonote, $arg = []){
        $js = $this->Request('sendVideoNote',array_merge([
            'chat_id'=>$chat_id,
            'videonote'=>$videonote
        ],$arg));
        return $js;
    }
    public function sendMediaGroup($chat_id, $media, $arg = []){
        $js = $this->Request('sendMediaGroup',array_merge([
            'chat_id'=>$chat_id,
            'media'=>$media
        ],$arg));
        return $js;
    }
    public function sendLocation($chat_id, $latitude,$longitude, $arg = []){
        $js = $this->Request('sendLocation',array_merge([
            'chat_id'=>$chat_id,
            'latitude'=>$latitude,
            'longitude'=>$longitude
        ],$arg));
        return $js;
    }
    public function editMessageLiveLocation($chat_id = null, $message_id = null, $arg = []){
        $js = $this->Request('editMessageLiveLocation',array_merge([
            'chat_id'=>$chat_id,
            'message_id'=>$message_id
        ],$arg));
        return $js;
    }
    public function stopMessageLiveLocation($chat_id = null, $message_id = null, $arg = []){
        $js = $this->Request('stopMessageLiveLocation',array_merge([
            'chat_id'=>$chat_id,
            'message_id'=>$message_id
        ],$arg));
        return $js;
    }
    public function sendVenue($chat_id, $latitude, $longitude, $title, $address, $arg = []){
        $js = $this->Request('sendVenue',array_merge([
            'chat_id'=>$chat_id,
            'latitude'=>$latitude,
            'longitude'=>$longitude,
            'title'=>$title,
            'address'=>$address
        ],$arg));
        return $js;
    }
    public function sendContact($chat_id, $phone_number, $first_name, $arg = []){
        $js = $this->Request('sendContact',array_merge([
            'chat_id'=>$chat_id,
            'phone_number'=>$phone_number,
            'first_name'=>$first_name
        ],$arg));
        return $js;
    }
    public function sendPoll($chat_id, $question, $options, $arg = []){
        $js = $this->Request('sendPoll',array_merge([
            'chat_id'=>$chat_id,
            'question'=>$question,
            'options'=>$options
        ],$arg));
        return $js;
    }
    public function sendDice($chat_id, $emoji, $arg = []){
        $js = $this->Request('sendDice',array_merge([
            'chat_id'=>$chat_id,
            'emoji'=>$emoji
        ],$arg));
        return $js;
    }
    public function sendChatAction($chat_id, $action, $arg = []){
        $js = $this->Request('sendChatAction',array_merge([
            'chat_id'=>$chat_id,
            'action'=>$action
        ],$arg));
        return $js;
    }
    public function getUserProfilePhoto($user_id, $arg = []){
        $js = $this->Request('getUserProfilePhoto',array_merge([
            'chat_id'=>$user_id
        ],$arg));
        return $js;
    }
    public function getFile($file_id){
        $js = $this->Request('getFile',array_merge([
            'file_id'=>$file_id
        ],$arg));
        return $js;
    }
    public function banChatMember($chat_id, $user_id, $arg = []){
        $js = $this->Request('banChatMember',array_merge([
            'chat_id'=>$chat_id,
            'user_id'=>$user_id
        ],$arg));
        return $js;
    }
    public function unbanChatMember($chat_id, $user_id, $arg = []){
        $js = $this->Request('unbanChatMember',array_merge([
            'chat_id'=>$chat_id,
            'user_id'=>$user_id
        ],$arg));
        return $js;
    }
    public function restrictChatMember($chat_id, $user_id, $permissions, $arg = []){
        $js = $this->Request('restrictChatMember',array_merge([
            'chat_id'=>$chat_id,
            'user_id'=>$user_id,
            'permissions'=>$permissions
        ],$arg));
        return $js;
    }
    public function promoteChatMember($chat_id, $user_id, $arg = []){
        $js = $this->Request('promoteChatMember',array_merge([
            'chat_id'=>$chat_id,
            'user_id'=>$user_id
        ],$arg));
        return $js;
    }
    public function setChatAdministratorCustomTitle($chat_id, $user_id, $custom_title){
        $js = $this->Request('setChatAdministratorCustomTitle',[
            'chat_id'=>$chat_id,
            'user_id'=>$user_id,
            'custom_title'=>$custom_title
        ]);
        return $js;
    }
    public function banChatSenderChat($chat_id, $sender_chat_id){
        $js = $this->Request('banChatSenderChat',[
            'chat_id'=>$chat_id,
            'sender_chat_id'=>$sender_chat_id
        ]);
        return $js;
    }
    public function unbanChatSenderChat($chat_id, $sender_chat_id){
        $js = $this->Request('unbanChatSenderChat',[
            'chat_id'=>$chat_id,
            'sender_chat_id'=>$sender_chat_id
        ]);
        return $js;
    }
    public function setChatPermissions($chat_id, $permissions){
        $js = $this->Request('setChatPermissions',[
            'chat_id'=>$chat_id,
            'permissions'=>$permissions
        ]);
        return $js;
    }
    public function exportChatInviteLink($chat_id){
        $js = $this->Request('exportChatInviteLink',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function createChatInviteLink($chat_id, $arg = []){
        $js = $this->Request('createChatInviteLink',array_merge([
            'chat_id'=>$chat_id
        ],$arg));
        return $js;
    }
    public function editChatInviteLink($chat_id, $invite_link, $arg = []){
        $js = $this->Request('editChatInviteLink',array_merge([
            'chat_id'=>$chat_id,
            'invite_link'=>$invite_link
        ],$arg));
        return $js;
    }
    public function revokeChatInviteLink($chat_id, $invite_link){
        $js = $this->Request('revokeChatInviteLink',[
            'chat_id'=>$chat_id,
            'invite_link'=>$invite_link
        ]);
        return $js;
    }
    public function approveChatJoinRequest($chat_id, $user_id){
        $js = $this->Request('approveChatJoinRequest',[
            'chat_id'=>$chat_id,
            'user_id'=>$user_id
        ]);
        return $js;
    }
    public function declineChatJoinRequest($chat_id, $user_id){
        $js = $this->Request('declineChatJoinRequest',[
            'chat_id'=>$chat_id,
            'user_id'=>$user_id
        ]);
        return $js;
    }
    public function setChatPhoto($chat_id, $photo){
        $js = $this->Request('setChatPhoto',[
            'chat_id'=>$chat_id,
            'photo'=>$photo
        ]);
        return $js;
    }
    public function deleteChatPhoto($chat_id){
        $js = $this->Request('deleteChatPhoto',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function setChatTitle($chat_id, $title){
        $js = $this->Request('setChatTitle',[
            'chat_id'=>$chat_id,
            'title'=>$title
        ]);
        return $js;
    }
    public function setChatDescription($chat_id, $description = null){
        $js = $this->Request('setChatDescription',[
            'chat_id'=>$chat_id,
            'description'=>$description
        ]);
        return $js;
    }
    public function pinChatMessage($chat_id, $message_id, $arg = []){
        $js = $this->Request('pinChatMessage',array_merge([
            'chat_id'=>$chat_id,
            'message_id'=>$message_id
        ],$arg));
        return $js;
    }
    public function unpinChatMessage($chat_id, $message_id = null){
        $js = $this->Request('unpinChatMessage',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id
        ]);
        return $js;
    }
    public function unpinAllChatMessages($chat_id){
        $js = $this->Request('unpinAllChatMessages',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function leaveChat($chat_id){
        $js = $this->Request('leaveChat',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function getChat($chat_id){
        $js = $this->Request('getChat',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function getChatAdministrators($chat_id){
        $js = $this->Request('getChatAdministrators',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function getChatMemberCount($chat_id){
        $js = $this->Request('getChatMemberCount',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function getChatMember($chat_id, $user_id){
        $js = $this->Request('getChatMember',[
            'chat_id'=>$chat_id,
            'user_id'=>$user_id
        ]);
        return $js;
    }
    public function setChatStickerSet($chat_id, $sticker_set_name){
        $js = $this->Request('setChatStickerSet',[
            'chat_id'=>$chat_id,
            'sticker_set_name'=>$sticker_set_name
        ]);
        return $js;
    }
    public function deleteChatStickerSet($chat_id){
        $js = $this->Request('deleteChatStickerSet',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function getForumTopicIconStickers($chat_id){
        $js = $this->Request('getForumTopicIconStickers',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function createForumTopic($chat_id, $name, $arg = []){
        $js = $this->Request('createForumTopic',array_merge([
            'chat_id'=>$chat_id,
            'name'=>$name
        ],$arg));
        return $js;
    }
    public function editForumTopic($chat_id, $message_thread_id, $arg = []){
        $js = $this->Request('editForumTopic',array_merge([
            'chat_id'=>$chat_id,
            'message_thread_id'=>$message_thread_id
        ],$arg));
        return $js;
    }
    public function closeForumTopic($chat_id, $message_thread_id){
        $js = $this->Request('closeForumTopic',[
            'chat_id'=>$chat_id,
            'message_thread_id'=>$message_thread_id
        ]);
        return $js;
    }
    public function reopenForumTopic($chat_id, $message_thread_id){
        $js = $this->Request('reopenForumTopic',[
            'chat_id'=>$chat_id,
            'message_thread_id'=>$message_thread_id
        ]);
        return $js;
    }
    public function deleteForumTopic($chat_id, $message_thread_id){
        $js = $this->Request('deleteForumTopic',[
            'chat_id'=>$chat_id,
            'message_thread_id'=>$message_thread_id
        ]);
        return $js;
    }
    public function unpinAllForumTopicMessages($chat_id, $message_thread_id){
        $js = $this->Request('unpinAllForumTopicMessages',[
            'chat_id'=>$chat_id,
            'message_thread_id'=>$message_thread_id
        ]);
        return $js;
    }
    public function editGeneralForumTopic($chat_id, $message_thread_id){
        $js = $this->Request('editGeneralForumTopic',[
            'chat_id'=>$chat_id,
            'message_thread_id'=>$message_thread_id
        ]);
        return $js;
    }
    public function closeGeneralForumTopic($chat_id){
        $js = $this->Request('closeGeneralForumTopic',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function reopenGeneralForumTopic($chat_id){
        $js = $this->Request('reopenGeneralForumTopic',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function hideGeneralForumTopic($chat_id){
        $js = $this->Request('hideGeneralForumTopic',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function unhideGeneralForumTopic($chat_id){
        $js = $this->Request('unhideGeneralForumTopic',[
            'chat_id'=>$chat_id
        ]);
        return $js;
    }
    public function answerCallbackQuery($callback_query_id, $text, $arg = []){
        $js = $this->Request('answerCallbackQuery',array_merge([
            'callback_query_id'=>$callback_query_id,
            'text'=>$text
        ],$arg));
        return $js;
    }
    public function setMyCommands($commands, $arg = []){
        $js = $this->Request('setMyCommands',array_merge([
            'commands'=>$commands
        ],$arg));
        return $js;
    }
    public function deleteMyCommands($arg = []){
        $js = $this->Request('deleteMyCommands',$arg);
        return $js;
    }
    public function getMyCommands($arg = []){
        $js = $this->Request('getMyCommands',$arg);
        return $js;
    }
    public function setChatMenuButton($arg = []){
        $js = $this->Request('setChatMenuButton',$arg);
        return $js;
    }
    public function getChatMenuButton($arg = []){
        $js = $this->Request('getChatMenuButton',$arg);
        return $js;
    }
    public function setMyDefaultAdministratorRights($arg = []){
        $js = $this->Request('setMyDefaultAdministratorRights',$arg);
        return $js;
    }

    public function editMessageText($chat_id, $message_id, $text, $arg = []){
        $js = $this->Request('editMessageText',array_merge([
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>$text
        ],$arg));
        return $js;
    }
    public function editMessageCaption($chat_id, $message_id, $caption, $arg = []){
        $js = $this->Request('editMessageCaption',array_merge([
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'caption'=>$caption
        ],$arg));
        return $js;
    }
    public function editMessageMedia($chat_id, $message_id, $media, $arg = []){
        $js = $this->Request('editMessageMedia',array_merge([
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'media'=>$media
        ],$arg));
        return $js;
    }
    public function editMessageReplyMarkup($chat_id, $message_id, $reply_markup, $arg = []){
        $js = $this->Request('editMessageReplyMarkup',array_merge([
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'reply_markup'=>$reply_markup
        ],$arg));
        return $js;
    }
    public function stopPoll($chat_id, $message_id, $arg = []){
        $js = $this->Request('stopPoll',array_merge([
            'chat_id'=>$chat_id,
            'message_id'=>$message_id
        ],$arg));
        return $js;
    }
    public function deleteMessage($chat_id, $message_id){
        $js = $this->Request('deleteMessage',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id
        ]);
        return $js;
    }

    public function sendSticker($chat_id, $sticker, $arg = []){
        $js = $this->Request('sendSticker',array_merge([
            'chat_id'=>$chat_id,
            'sticker'=>$sticker
        ],$arg));
        return $js;
    }
    public function getStickerSet($name){
        $js = $this->Request('getStickerSet',[
            'name'=>$name
        ]);
        return $js;
    }
    public function getCustomEmojiStickers($custom_emoji_ids){
        $js = $this->Request('getCustomEmojiStickers',[
            'custom_emoji_ids'=>$custom_emoji_ids
        ]);
        return $js;
    }
    public function uploadStickerFile($user_id, $png_sticker){
        $js = $this->Request('uploadStickerFile',[
            'user_id'=>$user_id,
            'png_sticker'=>$png_sticker
        ]);
        return $js;
    }
    public function createNewStickerSet($user_id, $name, $title, $emojis, $arg = []){
        $js = $this->Request('createNewStickerSet',array_merge([
            'chat_id'=>$chat_id,
            'name'=>$name,
            'title'=>$title,
            'emojis'=>$emojis
        ],$arg));
        return $js;
    }
    public function addStickerToSet($user_id, $name, $emojis, $arg = []){
        $js = $this->Request('addStickerToSet',array_merge([
            'chat_id'=>$chat_id,
            'name'=>$name,
            'emojis'=>$emojis
        ],$arg));
        return $js;
    }
    public function setStickerPositionInSet($sticker, $position){
        $js = $this->Request('setStickerPositionInSet',[
            'sticker'=>$sticker,
            'position'=>$position
        ]);
        return $js;
    }
    public function deleteStickerFromSet($sticker){
        $js = $this->Request('deleteStickerFromSet',[
            'sticker'=>$sticker
        ]);
        return $js;
    }
    public function setStickerSetThumb($user_id, $name, $thumb){
        $js = $this->Request('setStickerSetThumb',[
            'user_id'=>$user_id,
            'name'=>$name,
            'thumb'=>$thumb
        ]);
        return $js;
    }
}
?>
