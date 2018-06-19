<?php

global $Event, $Queue, $Text;
use kjBot\SDK\CQCode;

$lang = nextArg();
$hash = $Event['message_id'];
if(2 !== strlen($lang))throw \Exception('未指定语言');

setCache($hash.'.txt', removeCQCode(removeEmoji($Text)));
exec("gtts-cli -f ../storage/cache/{$hash}.txt -o ../storage/cache/{$hash}.mp3 -l {$lang}");

$Queue[]= sendBack(CQCode::Record('base64://'.base64_encode(getCache($hash.'.mp3'))));

?>