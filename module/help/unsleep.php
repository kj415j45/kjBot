<?php

global $Queue;
$msg=<<<EOT
解除禁言
用法：
!unsleep {群号}

冷却时间为一天
EOT;

$Queue[]= sendBack($msg);

?>
