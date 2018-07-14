<?php

global $Queue, $User_id;

$agreement=<<<EOT
感谢您使用 kjBot！

kjBot 需要使用您个人的命令使用情况来优化您的使用体验。
如果您不同意（我们默认您不同意），kjBot 将不会记名收集您的使用记录。
不同意记名记录不会影响您的正常使用。

如果您同意，请输入
!recordStat.verify
EOT;

setData('recordStat/'.$User_id, 'read');
$Queue[]= sendPM($agreement); //仅在私聊中发送用户协议

?>
