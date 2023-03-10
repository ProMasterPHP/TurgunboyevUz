<?php
namespace TurgunboyevUz;

/**
 * @author Turg'unboyev Diyorbek
 * @license MIT license
 * @privacy Mualliflik huquqini hurmat qiling!
 * 
 * Bog'lanish uchun - Telegram: @Turgunboyev_D
*/

trait TurgunboyevKey{

    public function requestUser(string $text = 'String'){
        return [
            'value'=>['text'=>$text, 'request_user'=>true]
        ];
    }

    public function requestChat(string $text  = 'String'){
        return [
            'value'=>['text'=>$text, 'request_chat'=>true]
        ];
    }

    public function requestContact(string $text = 'String'){
        return [
            'value'=>['text'=>$text, 'request_contact'=>true]
        ];
    }

    public function requestLocation(string $text = 'String'){
        return [
            'value'=>['text'=>$text, 'request_location'=>true]
        ];
    }

    public function requestPoll(string $text = 'String'){
        return [
            'value'=>['text'=>$text, 'request_poll'=>true]
        ];
    }

    public function webApp(string $text = 'String', string $url){
        return [
            'value'=>['text'=>$text, 'web_app'=>$url]
        ];
    }

    public function makeResize(array $arr, array $arg = []){
        $array = [];

        for($i = 0;$i < count($arr);$i++){
            $val = $arr[$i];
            for($k = 0;$k < count($val);$k++){
                $key = $val[$k];

                if(is_string($key)){
                    $array[$i][$k] = ['text'=>$key];
                }

                if(is_array($key) and isset($key['value'])){
                    $array[$i][$k] = $key['value'];
                }
            }
        }

        $keyboard = array_merge([
            'resize_keyboard'=>true,
            'keyboard'=>$array
        ], $arg);

        return json_encode($keyboard);
    }

    public function callbackData(string $text, string $data){
        return [
            'value'=>['text'=>$text,'callback_data'=>$data]
        ];
    }

    public function inlineUrl(string $text,string $url){
        return [
            'value'=>['text'=>$text, 'url'=>$url]
        ];
    }

    public function loginUrl(string $text, string $url){
        return [
            'value'=>['text'=>$text, 'login_url'=>$url]
        ];
    }

    public function switchInline(string $text, string $query){
        return [
            'value'=>['text'=>$text, 'switch_inline_query'=>$query]
        ];
    }

    public function switchCurrent(string $text, string $query){
        return [
            'value'=>['text'=>$text, 'switch_inline_query_current_chat'=>$query]
        ];
    }

    public function callbackGame(string $text, string $url){
        return [
            'value'=>['text'=>$text, 'callback_game'=>$url]
        ];
    }

    public function Payment(string $text){
        return [
            'value'=>['text'=>$text, 'pay'=>true]
        ];
    }

    public function makeInline(array $arr){
        $array = [];

        for($i = 0;$i < count($arr);$i++){
            $val = $arr[$i];
            for($k = 0;$k < count($val);$k++){
                $key = $val[$k];

                $array[$i][$k] = $key['value'];
            }
        }

        $keyboard = [
            'inline_keyboard'=>$array
        ];

        return json_encode($keyboard);
    }

    public function href($url, $name){
        return "<a href='".$url."'>".$name."</a>";
    }

    public function mention($name, $id = null){
        if($id == null){
            return href('tg://user?id='.$this->ID(),$name);
        }else{
            return href('tg://user?id='.$id,$name);
        }
    }
}
?>
