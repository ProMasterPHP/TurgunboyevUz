<?php

namespace TurgunboyevUz;

/**
 * @author Turg'unboyev Diyorbek
 * @license MIT license
 * @privacy Mualliflik huquqini hurmat qiling!
 * 
 * Bog'lanish uchun - Telegram: @Turgunboyev_D
*/

trait TurgunboyevUpdate{
    public function getUpdates(){
        $this->update = json_decode(file_get_contents("php://input"));
        $this->updateType = 'message';
        $this->fromType = 'chat';
        
        $this->mediaType();

        return $this;
    }

    public function message(){
        $this->updateType = 'message';

        return $this;
    }

    public function channelPost(){
        $this->updateType = 'channelPost';

        return $this;
    }

    public function callbackQuery(){
        $this->updateType = 'callbackQuery';

        return $this;
    }

    public function inlineQuery(){
        $this->updateType = 'inlineQuery';

        return $this;
    }

    public function chat(){
        $this->fromType = 'chat';

        return $this;
    }

    public function from(){
        $this->fromType = 'from';

        return $this;
    }

    public function isMessage(){
        return isset($this->update->message);
    }

    public function isChannelPost(){
        return isset($this->update->channel_post);
    }

    public function isCallback(){
        return isset($this->update->callback_query);
    }

    public function isInline(){
        return isset($this->update->inline_query);
    }

    public function ID(){
        if($this->isMessage()){
            if($this->fromType == "chat" or !isset($this->fromType)){
                return $this->update->message->chat->id;
            }elseif($this->fromType == "from"){
                return $this->update->message->from->id;
            }
        }

        if($this->isChannelPost()){
            return $this->update->channel_post->chat->id;
        }

        if($this->isCallback()){
            if($this->fromType == "chat" or !isset($this->fromType)){
                return $this->update->callback_query->message->chat->id;
            }elseif($this->fromType == "from"){
                return $this->update->callback_query->from->id;
            }
        }
    }

    public function messageID(){
        if($this->isMessage()){
            return $this->update->message->message_id;
        }

        if($this->isChannelPost()){
            return $this->update->channel_post->message_id;
        }

        if($this->isCallback()){
            return $this->update->callback_query->message->message_id;
        }
    }

    public function type(){
        if($this->isMessage()){
            return $this->update->message->chat->type;
        }

        if($this->isChannelPost()){
            return $this->update->channel_post->chat->type;
        }

        if($this->isCallback()){
            return $this->update->callback_query->message->chat->type;
        }
    }

    public function isPrivate(){
        return ($this->type() == 'private');
    }

    public function isGroup(){
        return ($this->type() == 'group' or $this->type() == 'supergroup');
    }

    public function isChannel(){
        return ($this->type() == 'channel');
    }

    public function firstName(){
        $first_name = ($this->isMessage())?$this->update->message->from->first_name:$this->update->callback_query->from->first_name;
        
        return $first_name;
    }

    public function lastName(){
        if($this->isMessage()){
            $from = $this->update->message->from;
        }elseif($this->isCallback()){
            $from = $this->update->callback_query->from;
        }

        if(isset($from->last_name)){
            return $from->last_name;
        }else{
            return false;
        }
    }

    public function chatTitle(){
        if($this->isGroup()){
            return $this->update->message->chat->title;
        }
        if($this->isChannel()){
            return $this->update->channel_post->chat->title;
        }
    }

    public function username(){
        if($this->isMessage()){
            if($this->fromType == 'chat'){
                $user = $this->update->message->chat;
            }
            if($this->fromType == 'from'){
                $user = $this->update->message->from;
            }
        }

        if($this->isChannelPost()){
            $user = $this->update->channel_post->chat;
        }

        if($this->isCallback()){
            if($this->fromType == 'chat'){
                $user = $this->update->callback_query->message->chat;
            }
            if($this->fromType == 'from'){
                $user = $this->update->callback_query->from;
            }
        }
        
        if(isset($user->username)){
            return $user->username;
        }else{
            return false;
        }
    }

    public function isBot(){
        if($this->isMessage()){
            return $this->update->message->from->is_bot;
        }
        if($this->isCallback()){
            return $this->update->callback_query->from->is_bot;
        }
    }

    public function isPremium(){
        if($this->isMessage()){
            return $this->update->message->from->is_premium;
        }
        if($this->isCallback()){
            return $this->update->callback_query->from->is_premium;
        }
    }

    public function languageCode(){
        if($this->isMessage()){
            return $this->update->message->from->language_code;
        }
        if($this->isCallback()){
            return $this->update->callback_query->from->language_code;
        }
    }

    public function messageDate(){
        if($this->isMessage()){
            return $this->update->message->date;
        }

        if($this->isChannelPost()){
            return $this->update->channel_post->date;
        }

        if($this->isCallback()){
            return $this->update->callback_query->message->date;
        }
    }

    public function entities(){
        if($this->isMessage()){
            return $this->update->message->entities;
        }

        if($this->isChannelPost()){
            return $this->update->channel_post->entities;
        }

        if($this->isCallback()){
            return $this->update->callback_query->message->entities;
        }
    }

    public function text(){
        if($this->isMessage()){
            return $this->update->message->text;
        }
        if($this->isChannelPost()){
            return $this->update->channel_post->text;
        }
    }

    public function queryData(){
        if($this->isCallback()){
            return $this->update->callback_query->data;
        }
    }

    public function queryID(){
        if($this->isCallback()){
            return $this->update->callback_query->id;
        }
    }

    public function replyMarkup(){
        if($this->isCallback()){
            $message = $this->update->callback_query->message;
            if(isset($message->reply_markup)){
                return $message->reply_markup;
            }else{
                return false;
            }
        }
    }

    public function replyToMessageID(){
        if($this->isMessage()){
            $message = $this->update->message;
            if(isset($message->reply_to_message->message_id)){
                return $message->reply_to_message->message_id;
            }else{
                return false;
            }
        }

        if($this->isChannelPost()){
            $message = $this->update->channel_post;
            if(isset($message->reply_to_message->message_id)){
                return $message->reply_to_message->message_id;
            }else{
                return false;
            }
        }

        if($this->isCallback()){
            $message = $this->update->callback_query->message;
            if(isset($message->reply_to_message->message_id)){
                return $message->reply_to_message->message_id;
            }else{
                return false;
            }
        }
    }

    /**
     * @param Working with media types: photo/video/document/voice/audio/animation/sticker;
    */

    public function mediaType(){
        return $this->photo()->video()->document()->voice()->audio()->animation()->sticker();
    }

    public function photo($is_count = TG_COUNT){
        if($this->isMessage()){
            $message = $this->update->message;
        }

        if($this->isChannelPost()){
            $message = $this->update->channel_post;
        }


        if(isset($message->photo)){
            if($is_count == "photo_count"){
                $this->photo = $message->photo[count(get_object_vars($message->photo))];
            }else{
                $this->photo = $message->photo[$is_count];
            }
            $this->mediaType = 'photo';
            $this->update->message = $message;

            return $this;
        }

        return $this;
    }

    public function video(){
        if($this->isMessage()){
            $message = $this->update->message;    
        }

        if($this->isChannelPost()){
            $message = $this->update->channel_post;
        }


        if(isset($message->video)){
            $this->mediaType = 'video';
            $this->update->message = $message;
            return $this;
        }

        return $this;
    }

    public function document(){
        if($this->isMessage()){
            $message = $this->update->message;    
        }

        if($this->isChannelPost()){
            $message = $this->update->channel_post;
        }
        

        if(isset($message->document)){
            $this->mediaType = 'document';
            $this->update->message = $message;
            return $this;
        }

        return $this;
    }

    public function voice(){
        if($this->isMessage()){
            $message = $this->update->message;    
        }

        if($this->isChannelPost()){
            $message = $this->update->channel_post;
        }


        if(isset($message->voice)){
            $this->mediaType = 'voice';
            $this->update->message = $message;
            return $this;
        }

        return $this;
    }

    public function audio(){
        if($this->isMessage()){
            $message = $this->update->message;    
        }

        if($this->isChannelPost()){
            $message = $this->update->channel_post;
        }

        if(isset($message->audio)){
            $this->mediaType = 'audio';
            $this->update->message = $message;
            return $this;
        }

        return $this;
    }

    public function animation(){
        if($this->isMessage()){
            $message = $this->update->message;    
        }

        if($this->isChannelPost()){
            $message = $this->update->channel_post;
        }
        

        if(isset($message->animation)){
            $this->mediaType = 'animation';
            $this->update->message = $message;
            return $this;
        }

        return $this;
    }

    public function sticker(){
        if($this->isMessage()){
            $message = $this->update->message;    
        }

        if($this->isChannelPost()){
            $message = $this->update->channel_post;
        }

        if(isset($message->sticker)){
            $this->mediaType = 'sticker';
            $this->update->message = $message;
            return $this;
        }

        return $this;
    }

    public function caption(){
        if(!empty($this->mediaType)){
            if(isset($this->update->message->caption)){
                return $this->update->message->caption;
            }

            if(isset($this->update->channel_post->caption)){
                return $this->update->channel_post->caption;
            }
        }
    }

    public function fileID(){
        if(!empty($this->mediaType)){
            if($this->mediaType == 'photo'){
                return $this->photo->file_id;
            }else{
                return $this->update->message->{$this->mediaType}->file_id;
            }
        }
    }

    public function fileSize(){
        if(!empty($this->mediaType)){
            if($this->mediaType == 'photo'){
                return $this->photo->file_size;
            }else{
                return $this->update->message->{$this->mediaType}->file_size;
            }
        }
    }

    public function fileName(){
        if(!empty($this->mediaType)){
            if($this->mediaType == 'photo' or $this->mediaType == 'voice' or $this->mediaType == 'sticker'){
                return false;
            }else{
                return $this->update->message->{$this->mediaType}->file_name;
            }
        }
    }

    public function width(){
        if(!empty($this->mediaType)){
            if($this->mediaType == 'photo'){
                return $this->photo->width;
            }else{
                if($this->mediaType == 'audio' or $this->mediaType == 'voice' or $this->mediaType == 'document'){
                    return false;
                }else{
                    return $this->update->message->{$this->mediaType}->width;
                }
            }
        }
    }

    public function height(){
        if(!empty($this->mediaType)){
            if($this->mediaType == 'photo'){
                return $this->photo->height;
            }else{
                if($this->mediaType == 'audio' or $this->mediaType == 'voice' or $this->mediaType == 'document'){
                    return false;
                }else{
                    return $this->update->message->{$this->mediaType}->height;
                }
            }
        }
    }

    public function duration(){
        if(!empty($this->mediaType)){
            if($this->mediaType == 'photo' or $this->mediaType == 'document' or $this->mediaType == 'sticker'){
                return false;
            }else{
                return $this->update->message->{$this->mediaType}->duration;
            }
        }
    }

    public function thumb(){
        if(!empty($this->mediaType)){
            if($this->mediaType == 'photo' or $this->mediaType == 'voice'){
                return false;
            }else{
                return $this->update->message->{$this->mediaType}->thumb;
            }
        }
    }

    public function performer(){
        if(!empty($this->mediaType)){
            if($this->mediaType == 'audio'){
                return $this->update->message->audio->performer;
            }else{
                return false;
            }
        }
    }

    public function mimeType(){
        if(!empty($this->mediaType)){
            if($this->mediaType == 'photo'){
                return false;
            }else{
                return $this->update->message->{$this->mediaType}->mime_type;
            }
        }
    }

    /**
     * @param Contact
    */

    public function phoneNumber(){
        if($this->isMessage()){
            if(isset($this->update->message->contact)){
                return $this->update->message->contact->phone_number;
            }
        }
    }

    public function contactFirstName(){
        if($this->isMessage()){
            if(isset($this->update->message->contact)){
                return $this->update->message->contact->first_name;
            }
        }
    }
    public function contactLastName(){
        if($this->isMessage()){
            if(isset($this->update->message->contact) and isset($this->update->message->contact->last_name)){
                return $this->update->message->contact->last_name;
            }
        }
    }

    public function contactID(){
        if($this->isMessage()){
            if(isset($this->update->message->contact)){
                return $this->update->message->contact->user_id;
            }
        }
    }

    /**
     * @param Dice
    */ 

    public function diceValue(){
        if($this->isMessage()){
            if(isset($this->update->message->dice)){
                return $this->update->message->dice->value;
            }
        }
    }
    public function diceEmoji(){
        if($this->isMessage()){
            if(isset($this->update->message->dice)){
                return $this->update->message->dice->emoji;
            }
        }
    }

    /**
     * @param Location
    */

    public function longitude(){
        if($this->isMessage()){
            if(isset($this->update->message->location)){
                return $this->update->message->location->longitude;
            }
        }
    }

    public function latitude(){
        if($this->isMessage()){
            if(isset($this->update->message->location)){
                return $this->update->message->location->latitude;
            }
        }
    }

    /**
     * @param New chat member
    */

    public function isNewMember(){
        return isset($this->update->message->new_chat_member);
    }

    public function newID($count = TG_NEW_COUNT){
        if($this->isNewMember()){
            $members = $this->update->new_chat_members;
            if($count == "member_count"){
                return $members[0]->id;
            }else{
                return $members[$count]->id;
            }
        }else{
            return false;
        }
    }

    public function newFirstName($count = TG_NEW_COUNT){
        if($this->isNewMember()){
            $members = $this->update->new_chat_members;
            if($count == "member_count"){
                return $members[0]->first_name;
            }else{
                return $members[$count]->first_name;
            }
        }else{
            return false;
        }
    }

    public function newLastName($count = TG_NEW_COUNT){
        if($this->isNewMember()){
            $members = $this->update->new_chat_members;
            if($count == "member_count"){
                if(isset($members[0]->last_name)){
                    return $members[0]->last_name;
                }else{
                    return false;
                }
            }else{
                if(isset($members[$count]->last_name)){
                    return $members[$count]->last_name;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    public function newUsername($count = TG_NEW_COUNT){
        if($this->isNewMember()){
            $members = $this->update->new_chat_members;
            if($count == "member_count"){
                if(isset($members[0]->username)){
                    return $members[0]->username;
                }else{
                    return false;
                }
            }else{
                if(isset($members[$count]->username)){
                    return $members[$count]->username;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    public function newIsBot($count = TG_NEW_COUNT){
        if($this->isNewMember()){
            $members = $this->update->new_chat_members;
            if($count == "member_count"){
                return $members[0]->is_bot;
            }else{
                return $members[$count]->is_bot;
            }
        }else{
            return false;
        }
    }

    public function newInviter(){
        if($this->isNewMember()){
            $fromType = $this->fromType;
            $inviter = $this->from()->ID();

            if($fromType == 'chat'){
                $this->chat();
            }

            return $inviter;
        }
    }


    public function isLeftMember(){
        return isset($this->update->message->left_chat_member);
    }

    public function leftID(){
        if($this->isLeftMember()){
            $member = $this->update->left_chat_member;
            return $member->id;
        }else{
            return false;
        }
    }

    public function leftFirstName(){
        if($this->isLeftMember()){
            $member = $this->update->left_chat_member;
            return $member->first_name;
        }else{
            return false;
        }
    }

    public function leftLastName(){
        if($this->isLeftMember()){
            $member = $this->update->left_chat_member;
            if(isset($member->last_name)){
                return $member->last_name;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function leftUsername(){
        if($this->isLeftMember()){
            $member = $this->update->left_chat_member;
            if(isset($member->username)){
                return $member->username;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function leftIsBot(){
        if($this->isLeftMember()){
            $member = $this->update->left_chat_member;
            return $member->is_bot;
        }else{
            return false;
        }
    }

    public function leftKicker(){
        if($this->isLeftMember()){
            $fromType = $this->fromType;
            $kicker = $this->from()->ID();

            if($fromType == 'chat'){
                $this->chat();
            }

            return $kicker;
        }
    }
}
?>
