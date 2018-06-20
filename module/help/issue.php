<?php

global $Queue;
$msg=<<<EOT
报告一个问题
用法：
!issue
{标题}
[细节内容]

该命令有24小时冷却时间
EOT;

$Queue[]= sendBack($msg);

?>
