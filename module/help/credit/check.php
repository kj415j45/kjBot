<?php

global $Queue;
$msg=<<<EOT
查看余额
用法：
!credit.check [目标]

不指定目标时为查询自己的余额
目标可以使用 @
EOT;

$Queue[]= sendBack($msg);

?>
