<?php

global $Queue;
$msg=<<<EOT
在 Pixiv 上搜图
用法：
!pixiv.search [参数] {关键字}

可用参数：
-like xxxxx    xxxxx为喜欢的人数，可能的值为 (1|2|5) x 10^(2|3|4)。
-mode {safe|r18}    指定搜索类型，群聊时该参数不生效
-page x    x 为页码数
- x    x 为当前页码中第几张图，超出范围时该参数不生效

搜索时会提示你关键字的结果数与相关信息。
一页最多有40张图，未指定时将会从第一页中随机返回一张。
EOT;

$Queue[]= sendBack($msg);

?>
