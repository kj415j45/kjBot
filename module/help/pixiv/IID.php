<?php

leave(<<<EOT
返回给定ID对应的P站图片。
用法：
!pixiv.IID {ID} [...ID]

该命令可以接受多个ID，使用空格分隔。
对于套图，可以使用 xxxxxxxx_yy 格式指定第 yy-1 张图
R18图片只会通过私聊发送，其他图片均直接返回。
EOT
);

?>
