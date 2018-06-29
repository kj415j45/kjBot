<?php

global $Queue;

$msg=<<<EOT
查看你的 bp
用法：
!osu.bp [参数列表]

-x    x为1-100的整数，即要查看的bp
-user "name"    查看指定用户，若名称中包含空格则双引号(单引号也可以)是必须的。不指定 `-user` 时默认查看自己的bp
-{模式名}    模式名可以为 std, taiko, mania, ctb
EOT;

$Queue[]= sendBack($msg);

?>
