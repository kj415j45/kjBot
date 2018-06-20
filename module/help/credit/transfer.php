<?php

global $Queue;
$msg=<<<EOT
向目标转账
用法：
!credit.transfer {目标} {金额}

目标可以使用 @
EOT;

$Queue[]= sendBack($msg);

?>
