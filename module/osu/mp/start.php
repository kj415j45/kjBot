<?php

loadModule('credit.tools');
loadModule('osu.tools');
loadModule('osu.mp.tools');
global $Queue, $User_id;

$matchID = (int)nextArg();
$historyJson = file_get_contents("https://osu.ppy.sh/community/matches/{$matchID}/history?after=0&limit=1");
if(false === $historyJson)leave('读取mp数据失败');

decCredit($User_id, 10);

$history = json_decode($historyJson);

$users = getEventUsers($history->users);

$firstEvent = $history->events[0];
$eventTime = convertTimestamp($firstEvent->timestamp)->format('Y-m-d H:i:s');
$Queue[]= sendBack("开始监听 mp_{$matchID}\n房间由 {$users[$firstEvent->user_id]->username} 创建于 {$eventTime}");
$Queue[]= sendBack('你的余额为 '.getCredit($User_id));

setData("osu/mp/{$User_id}", "{$matchID} {$firstEvent->id}");

?>
