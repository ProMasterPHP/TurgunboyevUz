<?php
namespace TurgunboyevUz;

/**
 * @author Turg'unboyev Diyorbek
 * @license MIT license
 * @privacy Mualliflik huquqini hurmat qiling!
 * 
 * Bog'lanish uchun - Telegram: @Turgunboyev_D
*/

class TelegramError{
	static function mysql($array){

		$backtrace = debug_backtrace();

		if($array['error_type'] == 'mysql_include'){
			echo "<b>Warning: </b>".$array['description']." in ".$backtrace[1]['file']." on line ".$backtrace[1]['line'];
		}

		if(isset($array['error_code'])){
			echo "<b>Error: </b>".$array['description']." in ".$backtrace[1]['file']." on line ".$backtrace[1]['line'];
		}
	}

	static function useTraits($array){

		$backtrace = debug_backtrace();

		if($array['error_type'] == "use_trait"){
			echo "<b>Warning: </b>".$array['description']." in ".$backtrace[1]['file']." on line ".$backtrace[1]['line'];
		}
	}

	static function errorDashboard($array){
		$backtrace = debug_backtrace();

		if($array['error_type'] == "create_table"){
			echo "<b>Warning: </b>".$array['description']." in ".$backtrace[1]['file']." on line ".$backtrace[1]['line'];
		}	
	}
}
?>
