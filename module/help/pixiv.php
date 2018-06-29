<?php

global $Queue;
$msg=<<<EOT
Pixiv !
用法：
!pixiv.search

具体用法请查看下一级 help
EOT;

$Queue[]= sendBack($msg);

?>
