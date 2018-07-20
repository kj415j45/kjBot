<?php

global $Queue, $User_id;

if(!allowRecord($User_id))leave(<<<EOT
您还没有同意 kjBot 记录您的使用情况，请使用命令
!recordStat
了解更多
EOT
);

if(fromGroup()){
    $Queue[]= sendBack(getUserCommandCount($User_id, 10));
}else{
    $Queue[]= sendBack(getUserCommandCount($User_id, nextArg()));
}


?>