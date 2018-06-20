<?php

global $Event, $Queue;
use kjBot\SDK\CQCode;

switch($Event['notice_type']){
    case 'group_increase':
        if($Event['user_id'] != config('bot')){
            $Queue[]= sendBack(CQCode::At($Event['user_id']).' 欢迎加入本群，请阅读群公告！');
        }else{
            $Queue[]= sendBack('kjBot 已入驻本群，发送 '.config('prefix', '!').'help 查看帮助');
        }
        break;
    default:
        
}

?>