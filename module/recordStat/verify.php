<?php

global $User_id;
loadModule('credit.tools');

switch(trim(getData('recordStat/'.$User_id))){
    case 'cancel':
        setData('recordStat/'.$User_id, 'true');
        leave('感谢您的支持');
    case 'read':
        addCredit($User_id, 415);
        setData('recordStat/'.$User_id, 'true');
        setData('recordStat/r'.$User_id, 'true');
        leave("感谢您的支持，奖励 415 金币\n请注意，如果您在将来要求取消记录，需要将这 415 金币交还。");
    case 'true':
        leave("您已经同意 kjBot 记录您的使用情况，如需取消请输入\n!recordStat.cancel");
    default:
        leave('请先阅读协议！');
}


?>