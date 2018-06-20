<?php

global $Queue, $Event;
use kjBot\SDK\CQCode;

$QQ = parseQQ(nextArg())??$Event['user_id'];
$Queue[]= sendBack(CQCode::At($QQ).' 的余额为 '.getData("credit/{$QQ}"));

?>
