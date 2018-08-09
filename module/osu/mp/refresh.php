<?php

loadModule('credit.tools');
loadModule('osu.tools');
loadModule('osu.mp.tools');
global $Queue, $User_id;

$limit = nextArg()??5;
$data = getData("osu/mp/{$User_id}");
if($data === false)leave('没有正在监听的mp');
sscanf(trim(getData("osu/mp/{$User_id}")), '%d %d', $matchID, $lastEventID);

$historyJson = file_get_contents("https://osu.ppy.sh/community/matches/{$matchID}/history?after={$lastEventID}&limit={$limit}");
$history = json_decode($historyJson);
$events = $history->events;
$users = getEventUsers($history->users);

decCredit($User_id, count($events));

$count = 0;
foreach($events as $event){
    $Queue[]= sendBack(parseEvent($event, $users));
    $count++;
    if($event->detail->type == 'other' && $event->game->end_time == NULL){addCredit($User_id, count($events)-$count+1);break;}
    setData("osu/mp/{$User_id}", "{$matchID} {$event->id}");
    if($event->detail->type == 'match-disbanded'){
        unlink("../storage/data/osu/mp/{$User_id}");
    }
}

$Queue[]= sendBack('你的余额为 '.getCredit($User_id));

?>
