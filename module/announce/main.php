<?php

global $Queue, $Text, $CQ;
use kjBot\Frame\Message;
requireMaster();
set_time_limit(0);

$groupList = $CQ->getGroupList();
$success = 0;
$silence = 0;
$error = 0;

foreach($groupList as $group){
    try{
        $CQ->sendGroupMsg($group->group_id, $Text);
        $success++;
    }catch(\Exception $e){
        if(-34 === $e->getCode()){
            $silence++;
        }else{
            $error++;
        }
        $Queue[]= sendMaster("Query {$group->group_id} failed: ".$e->getCode());
    }
    if($error>5)leave('错误次数过多，终止');
    sleep(10); //10秒延迟
}
$groupCount = count($groupList);
$Queue[]= sendMaster("目前共有 {$groupCount} 个群，成功 {$success} 个，异常原因失败 {$error} 个，被 {$silence} 个群禁言中");
?>