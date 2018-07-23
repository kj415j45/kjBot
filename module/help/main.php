<?php

global $Queue;
$msg=<<<EOT
说明：
在 help 信息中，大括号表示必须项，中括号表示可选项，管线符表示左右均可以，如果你的参数包含空格，可以使用英文单双引号。发送
!help.{命令}[.下一级命令]
获得特定命令的帮助
可用命令：
checkin credit issue osu pixiv recordStat roll search sleep time trans unsleep version voice
EOT;

$Queue[]= sendBack($msg);

?>
