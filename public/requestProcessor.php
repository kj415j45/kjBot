<?php

switch($Event['request_type']){
    case 'friend':
        $CQ->setFriendAddRequest($Event['flag'], config('allowFriends')); //交给master二次审核？
        $Queue[]= sendMaster('Being friends with '.$Event['user_id']); //通知master
        $Queue[]= sendBack(config('WelcomeMsg')); //发送欢迎消息
        break;
    case 'group':
        switch($Event['sub_type']){
            case 'add':
                //TODO 新人加群的情况可能需要中间件来处理
                break;
            case 'invite':
                $CQ->setGroupAddRequest($Event['flag'], $Event['sub_type'], config('allowGroups'));
                $Queue[]= sendMaster('Join Group '.$Event['group_id'].' by '.$Event['user_id']); //通知master
                break;
            default:
        }
        break;
    default:

}

?>
