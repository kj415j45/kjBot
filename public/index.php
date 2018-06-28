<?php

require('init.php');

use kjBot\Frame\Message;

try{
    switch($Event['post_type']){
        case 'message':
        case 'notice':
        case 'request':
            require($Event['post_type'].'Processor.php');
            break;
        default:
            $Queue[]= sendMaster('Unknown post type, Event:'."\n".var_export($Event, true));
    }

    //调试
    if($Debug && $Event['user_id'] == $DebugListen){
        $Queue[]= sendMaster(var_export($Queue, true));
    }

}catch(\Exception $e){
    $Queue[]= sendBack($e->getMessage(), true, true);
}

try{
    //将队列中的消息发出
    foreach($Queue as $msg){
        $MsgSender->send($msg);
    }
}catch(\Exception $e){
    setData('error.log', var_dump($Event).$e.$e->getCode()."\n", true);
}



?>
