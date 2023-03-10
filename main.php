<?php

namespace TurgunboyevUz;

/**
 * @author Turg'unboyev Diyorbek
 * @license MIT license
 * @privacy Mualliflik huquqini hurmat qiling!
 * 
 * Bog'lanish uchun - Telegram: @Turgunboyev_D
*/

include "src/Interface.php";
include "src/Methods.php";

//Traits
include "src/Traits/TurgunboyevKey.php";
include "src/Traits/TurgunboyevUpdate.php";

include "src/Traits/TurgunboyevSQL.php";
include "src/Traits/TurgunboyevDash.php";

//Helpers
include "src/Helpers/Constants.php";
include "src/Helpers/TelegramError.php";

class API extends Telegram{
	use TurgunboyevUpdate;
	use TurgunboyevKey;
	use TurgunboyevSQL;
	use TurgunboyevDash;
}
?>
