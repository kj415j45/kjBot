<?php

global $Event, $Queue;
loadModule('credit.tools');

$income = rand(10, 25);
clearstatcache();
$lastCheckinTime=filemtime('../storage/data/checkin/'.$Event['user_id']);
if(0 == (int)date('d')-(int)date('d', $lastCheckinTime)){
    $Queue[]= sendBack('你今天签到过了');
}else{
    addCredit($Event['user_id'], $income);
    setData('checkin/'.$Event['user_id'], '');
    $Queue[]= sendBack('签到成功，获得 '.$income.' 个金币');
}

?>