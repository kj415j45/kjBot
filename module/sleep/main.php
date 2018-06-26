<?php

global $Queue, $Event, $CQ;

if(!fromGroup())throw new \Exception();

date_default_timezone_set('Asia/Shanghai');

$time='';
while(true){
    $x=nextArg();
    if($x !== NULL){
        $time.=$x.' ';
    }else{
        break;
    }
}

try{
    $CQ->setGroupBan($Event['group_id'], $Event['user_id'], (strtotime($time)-time()));
}catch(\Exception $e){}

?>