<?php

global $Queue;
$msg=<<<EOT
可用命令：
checkin
credit
osu
version
EOT;

$Queue[]= sendBack($msg);

?>
