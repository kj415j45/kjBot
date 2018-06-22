<?php

global $Queue, $Event, $CQ;

if(!fromGroup())throw new \Exception();

$time='';
while(true){
    $x=nextArg();
    if($x !== NULL){
        $time.=$x.' ';
    }else{
        break;
    }
}

$CQ->setGroupBan($Event['group_id'], $Event['user_id'], (strtotime($time)-time()));
?>