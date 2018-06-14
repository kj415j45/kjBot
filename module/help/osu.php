<?php

global $Queue;

$msg=<<<EOT
osu! 系列命令
用法：
!osu.{bind|bp|recent} [参数列表]

具体用法请查看下一级 help
EOT;

$Queue[]= sendBack($msg);

?>
