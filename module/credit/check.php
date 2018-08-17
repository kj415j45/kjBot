<?php

global $Queue, $Event;
use kjBot\SDK\CQCode;

$QQ = nextArg();
if(!(preg_match('/\d+/', $QQ, $match) && $match[0] == $QQ)){
    $QQ = parseQQ($QQ);
}
$QQ = $QQ??$Event['user_id'];
$Queue[]= sendBack(CQCode::At($QQ).' 的余额为 '.getData("credit/{$QQ}"));

?>
