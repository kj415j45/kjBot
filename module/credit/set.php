<?php

global $Queue;
requireMaster();
use kjBot\SDK\CQCode;

loadModule('credit.tools');

$QQ = nextArg();
if(!(preg_match('/\d+/', $QQ, $match) && $match[0] == $QQ)){
    $QQ = parseQQ($QQ);
}
$credit = (int)nextArg();
setCredit($QQ, $credit);

$Queue[]= sendBack('已将 '.CQCode::At($QQ).' 的余额设置为 '.$credit);

?>
