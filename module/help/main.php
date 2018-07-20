<?php

global $Queue;
$msg=<<<EOT
发送
!help.{命令}
获得该命令的帮助
可用命令：
checkin credit issue osu pixiv recordStat roll search sleep time unsleep version voice
EOT;

$Queue[]= sendBack($msg);

?>
