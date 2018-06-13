<?php

global $Queue;
requireMaster();
use kjBot\SDK\CQCode;

loadModule('credit.tools');

$QQ = parseQQ(nextArg());
$credit = (int)nextArg();
setCredit($QQ, $credit);

$Queue[]= sendBack('已将 '.CQCode::At($QQ).' 的余额设置为 '.$credit);

?>
