<?php

global $Queue;
$msg=<<<EOT
可用命令：
checkin credit issue osu pixiv roll sleep time unsleep version voice
EOT;

$Queue[]= sendBack($msg);

?>
