<?php

global $Queue, $Event, $Command;
use kjBot\SDK\CQCode;
loadModule('osu.tools');

$length = strpos($Event['message'], "\r");
if(false===$length)$length=strlen($Event['message']);
$username = substr($Event['message'], strpos($Event['message'], nextArg()), $length-strlen($Command[0]));

setOsuID($Event['user_id'], $username);

$Queue[]= sendBack(CQCode::At($Event['user_id']).' 成功绑定 '.$username);

?>