<?php

global $Event, $Queue;
loadModule('credit.tools');
use kjBot\SDK\CQCode;

$QQ = parseQQ(nextArg());
$transfer = abs((int)nextArg());
transferCredit($Event['user_id'], $QQ, $transfer);

$Queue[]= sendBack('转账给 '.CQCode::At($QQ).' 成功，您的余额为 '.getCredit($Event['user_id']));
?>
