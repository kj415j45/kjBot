<?php

global $Queue;

$msg=<<<EOT
生成随机数
用法：
!roll
!roll [最小值]
!roll [最小值] [最大值]
EOT;

$Queue[]= sendBack($msg);

?>