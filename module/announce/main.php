<?php

global $Queue, $Text, $CQ;
use kjBot\Frame\Message;
requireMaster();

$groupList = $CQ->getGroupList();

foreach($groupList as $group){
    $Queue[]= new Message($Text, $group->group_id, true);
}

?>