<?php

global $Event, $Queue, $Text;

$lang = nextArg();
$hash = $Event['message_id'];
if(2 !== strlen($lang))leave('语言代号应为两个英文字符');

setCache($hash.'.txt', removeCQCode(removeEmoji($Text)));
exec("export LC_ALL=C.UTF-8 && export LANG=C.UTF-8 && cd ../storage/cache/ && gtts-cli -f {$hash}.txt -o {$hash}.mp3 -l {$lang}"); //So fucking hardcore py3
$Queue[]= sendBack(sendRec(getCache($hash.'.mp3')));

?>