<?php

global $Queue;

$msg=<<<EOT
查看你的最新成绩(pp查询暂时不支持 std 以外模式)
用法：
!osu.recent [参数列表]

-user "name"    查看指定用户，若名称中包含空格则双引号(单引号也可以)是必须的。不指定 `-user` 时默认查看自己
-{模式名}    模式名可以为 std, taiko, mania, ctb
EOT;

$Queue[]= sendBack($msg);

?>
