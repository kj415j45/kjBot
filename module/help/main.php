<?php

global $Queue;
$msg=<<<EOT
可用命令：
checkin credit issue osu pixiv recordStat roll search sleep time unsleep version voice
EOT;

$Queue[]= sendBack($msg);

?>
