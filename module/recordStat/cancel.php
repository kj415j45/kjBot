<?php

global $User_id;
loadModule('credit.tools');

if(trim(getData('recordStat/'.$User_id))!='true')leave();

if(trim(getData('recordStat/r'.$User_id))=='true')decCredit($User_id, 415);
setData('recordStat/'.$User_id, 'cancel');
setData('recordStat/r'.$User_id, 'false');

leave('您已取消 kjBot 的个人记录。');

?>