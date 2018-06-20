<?php

global $Queue;
$msg=<<<EOT
让舰娘报告当前时间（东京时间）
用法：
!time

台词会不定期更换 :)
EOT;

$Queue[]= sendBack($msg);

?>
