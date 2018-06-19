<?php

global $Queue;

$msg=<<<EOT
让谷歌娘说话
用法：
!voice {语言代号}
{
    文本
}

常用语言代号：
zh 汉语
ja 日语
en 英语
fr 法语
ru 俄语
es 西班牙语
ar 阿拉伯语
eo 世界语
EOT;

$Queue[]= sendBack($msg);

?>