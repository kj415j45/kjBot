<?php

use kjBot\SDK\CQCode;

global $Event, $Queue, $Message;

$ciyaCount = (int)getData('ciyaCount/'.$Event['user_id'])+1;
if(preg_match('/\[CQ:face,id=13\]/', $Message) && fromGroup()){
    setData('ciyaCount/'.$Event['user_id'], $ciyaCount);
}

if($ciyaCount % 5 == 0){
    $Queue[]= sendBack(CQCode::Face(13));
}

?>