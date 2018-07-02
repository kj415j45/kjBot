<?php

global $Queue;

$msg=<<<EOT
享受音乐
用法：
!osu.listen {谱面集ID}
EOT;

$Queue[]= sendBack($msg);

?>
