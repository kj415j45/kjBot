<?php

global $Queue, $Event, $Command, $osu_api_key;
use kjBot\SDK\CQCode;
loadModule('osu.tools');

$length = strpos($Event['message'], "\r");
if(false===$length)$length=strlen($Event['message']);
$username = substr($Event['message'], strpos($Event['message'], nextArg()), $length-strlen($Command[0]));

$userRealname = json_decode(get_user($osu_api_key, $username))[0]->username;

setOsuID($Event['user_id'], $userRealname);

$Queue[]= sendBack(CQCode::At($Event['user_id']).' 成功绑定 '.$userRealname);

?>