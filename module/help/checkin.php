<?php

global $Queue;
$msg=<<<EOT
每日签到随机获得 10~25 个 kjBot金币
用法：
!checkin
EOT;

$Queue[]= sendBack($msg);

?>
