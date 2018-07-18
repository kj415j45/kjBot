<?php

global $CQ, $Event, $Queue;

if(coolDown("unsleep/{$Event['user_id']}")<0)leave('冷却中');
coolDown("unsleep/{$Event['user_id']}", 60*60*24);

$group = nextArg();
$CQ->setGroupBan($group, $Event['user_id'], 0);
$Queue[]= sendBack('已在 '.$group.' 解除禁言');

?>