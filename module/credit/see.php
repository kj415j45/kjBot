<?php

global $Queue;
use kjBot\SDK\CQCode;

$QQ = parseQQ(nextArg());
$Queue[]= sendBack(CQCode::At($QQ).' 的余额为 '.getData("credit/{$QQ}"));

?>