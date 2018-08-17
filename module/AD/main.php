<?php

global $Queue, $Text, $CQ;
use kjBot\Frame\Message;
requireMaster();
set_time_limit(0);

$groupList = $CQ->getGroupList();
$whiteList = file_get_contents('https://raw.githubusercontent.com/kjBot-Dev/ADwhitelist/master/whitelist.json');
if(false === $whiteList)leave('打开白名单失败，终止');

$prefix=<<<EOT
广告：
----------

EOT;

$suffix=<<<EOT

----------
kjBot 正在接受广告推送请求，5元一次。屏蔽 1元/月。详情联系：kjbot@kj415j45.space
EOT;

$Text = $prefix.$Text.$suffix;

$whiteList = json_decode($whiteList)->list;
$expireDay = [];
$success = 0;
$silence = 0;
$error = 0;
$now = new \DateTime();

foreach($whiteList as $group){
    $expireDay[$group->group] = \DateTime::createFromFormat('Y-m-d H:i:s', $group->expire_day);
}

foreach($groupList as $group){
    if(isset($expireDay[$group->group_id])){
        if($now < $expireDay[$group->group_id])continue;
    }
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
$whiteCount = $groupCount-$success-$error-$silence;
$Queue[]= sendMaster("目前共有 {$groupCount} 个群，有 {$whiteCount} 个群白名单生效中。\n已投放 {$success} 条广告，异常原因失败 {$error} 个，被 {$silence} 个群禁言中");

?>
