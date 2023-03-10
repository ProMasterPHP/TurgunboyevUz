<?php

/**
 * @author Turgunboyev Diyorbek
 * Muallifdan: Ushbu examplelar TurgunboyevSQL treyti bilan ishlash uchun tuzilgan.
 * 
 * @contact - Telegram: @Turgunboyev_D
 * @description - By: @Magic_Coders group
 * @license - MIT license
 * @privacy - Mualliflik huquqini hurmat qiling!
*/

/**
 * @var Select Statement
 * @param $table - tablitsa nomi
 * @param $columns - tablitsadan olinadigan ustun nomlari
 * @param $params - where, order, limit clause kiritiladi
 * @return $this
*/
$tg->select('users',['user','id','product'],[
    'where'=>[
        'user'=>"Turgunboyev_D"
    ],
    'order'=>[
        'id'=>$tg->ascend()
    ],
    'limit'=>"1"
])->getSQL();

/**
 * @var Insert Statement
 * @param $table - tablitsa nomi
 * @param $values - tablitsaga kiritiladigan ma'lumotlar
 * @return $this
*/

print_r($tg->insert('users',[
    'user'=>"OnlineWolf",
    'id'=>"503177249",
    'product'=>"Bizga sajda yarashadi"
])->getSQL());

/**
 * @var Update Statement
 * @param $table - tablitsa nomi
 * @param $values - tablitsaga kiritiladigan ma'lumotlar
 * @param $where - WHERE clause uchun shartlar to'plami
 * @return $this
*/

print_r($tg->update('users',[
    'user'=>"OnlineWolf",
    'id'=>"50325689",
    'product'=>"Olim odam va olam"
], [
    'user'=>"Turgunboyev_D"
])->getSQL());

/**
 * @var Delete Statement
 * @param $table - tablitsa nomi
 * @param $where - WHERE clause uchun shartlar to'plami
 * @return $this
*/

print_r($tg->delete('users',[
    'user'=>"Turgunboyev_D"
])->getSQL());


/**
 * @param $db->(queryBuilder)->do(); - Queryni ishga tushirish
 * @return $this
 * 
 * @param $db->(queryBuilder)->getDO(); - do() buyrug'idan qaytgan resultni olish
 * @return mysqli Object
*/

$tg->insert('users', [
    'user'=>"Turgunboyev_D",
    'product'=>"Hadis va hayot"
])->do()->getDO();

/**
 * @var Querydan qaytgan ma'lumotni fetch qilish
 * @param $db->(queryBuilder)->do()->fetch(); - Querydan qaytgan ma'lumotdan bittasini olish
 * @return Array of Results
 * 
 * @param $db->(queryBuilder)->do()->fetch_all(); - Querydan qaytgan ma'lumotdan bittasini olish
 * @return Array of Results
*/

$user = $tg->select('users',['user','id','product'])->do()->fetch();
$userAll = $tg->select('users',['user','id','product'])->do()->fetch_all();

/**
 * @param $db->(queryBuilder)->do()->rows(); - Querydan qaytgan rowlar soni
 * @return mysqli_result()->num_rows
*/

$rows = $tg->select('users',['user','id','product'])->do()->rows();
?>