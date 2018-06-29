<?php

global $Queue;
$msg=<<<EOT
在 Pixiv 上搜图
用法：
!pixiv.search [参数] {关键字}

可用参数：

-mode {safe|r18}    指定搜索类型，群聊时该参数不生效
-page x    x 为页码数
- x    x 为当前页码中第几张图，超出范围时该参数不生效

搜索时会提示你关键字的结果数与相关信息。
一页最多有40张图，将会从中随机返回一张。
EOT;

$Queue[]= sendBack($msg);

?>
