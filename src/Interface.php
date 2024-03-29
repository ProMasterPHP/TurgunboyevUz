<?php

namespace TurgunboyevUz;

/**
 * @author Turg'unboyev Diyorbek
 * @license MIT license
 * @privacy Mualliflik huquqini hurmat qiling!
 * 
 * Bog'lanish uchun - Telegram: @Turgunboyev_D
*/

interface Methods{
    public function setWebhook($url,$arg = []);
    public function deleteWebhook($drop_pending_update = null);
    public function getWebhookInfo();
    public function getMe();
    public function logout();
    public function close();
    public function sendMessage($chat_id, $message_id, $arg = []);
    public function forwardMessage($chat_id, $from_id, $message_id, $arg = []);
    public function copyMessage($chat_id, $from_id, $message_id, $arg = []);
    public function sendPhoto($chat_id, $photo, $arg = []);
    public function sendAudio($chat_id, $audio, $arg = []);
    public function sendDocument($chat_id, $document, $arg = []);
    public function sendVideo($chat_id, $video, $arg = []);
    public function sendAnimation($chat_id, $animation, $arg = []);
    public function sendVoice($chat_id, $voice, $arg = []);
    public function sendVideoNote($chat_id, $videonote, $arg = []);
    public function sendMediaGroup($chat_id, $media, $arg = []);
    public function sendLocation($chat_id, $latitude,$longitude, $arg = []);
    public function editMessageLiveLocation($chat_id = null, $message_id = null, $arg = []);
    public function stopMessageLiveLocation($chat_id = null, $message_id = null, $arg = []);
    public function sendVenue($chat_id, $latitude, $longitude, $title, $address, $arg = []);
    public function sendContact($chat_id, $phone_number, $first_name, $arg = []);
    public function sendPoll($chat_id, $question, $options, $arg = []);
    public function sendDice($chat_id, $emoji, $arg = []);
    public function sendChatAction($chat_id, $action, $arg = []);
    public function getUserProfilePhoto($user_id, $arg = []);
    public function getFile($file_id);
    public function banChatMember($chat_id, $user_id, $arg = []);
    public function unbanChatMember($chat_id, $user_id, $arg = []);
    public function restrictChatMember($chat_id, $user_id, $permissions, $arg = []);
    public function promoteChatMember($chat_id, $user_id, $arg = []);
    public function setChatAdministratorCustomTitle($chat_id, $user_id, $custom_title);
    public function banChatSenderChat($chat_id, $sender_chat_id);
    public function unbanChatSenderChat($chat_id, $sender_chat_id);
    public function setChatPermissions($chat_id, $permissions);
    public function exportChatInviteLink($chat_id);
    public function createChatInviteLink($chat_id, $arg = []);
    public function editChatInviteLink($chat_id, $invite_link, $arg = []);
    public function revokeChatInviteLink($chat_id, $invite_link);
    public function approveChatJoinRequest($chat_id, $user_id);
    public function declineChatJoinRequest($chat_id, $user_id);
    public function setChatPhoto($chat_id, $photo);
    public function deleteChatPhoto($chat_id);
    public function setChatTitle($chat_id, $title);
    public function setChatDescription($chat_id, $description = null);
    public function pinChatMessage($chat_id, $message_id, $arg = []);
    public function unpinChatMessage($chat_id, $message_id = null);
    public function unpinAllChatMessages($chat_id);
    public function leaveChat($chat_id);
    public function getChat($chat_id);
    public function getChatAdministrators($chat_id);
    public function getChatMemberCount($chat_id);
    public function getChatMember($chat_id, $user_id);
    public function setChatStickerSet($chat_id, $sticker_set_name);
    public function deleteChatStickerSet($chat_id);
    public function getForumTopicIconStickers($chat_id);
    public function createForumTopic($chat_id, $name, $arg = []);
    public function editForumTopic($chat_id, $message_thread_id, $arg = []);
    public function closeForumTopic($chat_id, $message_thread_id);
    public function reopenForumTopic($chat_id, $message_thread_id);
    public function deleteForumTopic($chat_id, $message_thread_id);
    public function unpinAllForumTopicMessages($chat_id, $message_thread_id);
    public function editGeneralForumTopic($chat_id, $message_thread_id);
    public function closeGeneralForumTopic($chat_id);
    public function reopenGeneralForumTopic($chat_id);
    public function hideGeneralForumTopic($chat_id);
    public function unhideGeneralForumTopic($chat_id);
    public function answerCallbackQuery($callback_query_id, $text, $arg = []);
    public function setMyCommands($commands, $arg = []);
    public function deleteMyCommands($arg = []);
    public function getMyCommands($arg = []);
    public function setChatMenuButton($arg = []);
    public function getChatMenuButton($arg = []);
    public function setMyDefaultAdministratorRights($arg = []);

    public function editMessageText($chat_id, $message_id, $text, $arg = []);
    public function editMessageCaption($chat_id, $message_id, $caption, $arg = []);
    public function editMessageMedia($chat_id, $message_id, $media, $arg = []);
    public function editMessageReplyMarkup($chat_id, $message_id, $reply_markup, $arg = []);
    public function stopPoll($chat_id, $message_id, $arg = []);
    public function deleteMessage($chat_id, $message_id);

    public function sendSticker($chat_id, $sticker, $arg = []);
    public function getStickerSet($name);
    public function getCustomEmojiStickers($custom_emoji_ids);
    public function uploadStickerFile($user_id, $png_sticker);
    public function createNewStickerSet($user_id, $name, $title, $emojis, $arg = []);
    public function addStickerToSet($user_id, $name, $emojis, $arg = []);
    public function setStickerPositionInSet($sticker, $position);
    public function deleteStickerFromSet($sticker);
    public function setStickerSetThumb($user_id, $name, $thumb);
}
?>
