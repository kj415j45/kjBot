<?php

require('init.php');

use kjBot\Frame\Message;

switch($Event['post_type']){
    case 'message':
    case 'notice':
    case 'request':
        require($Event['post_type'].'Processor.php');
        break;
    default:
        $Queue[]= new Message('Unknown post type', config('master'), false);
}

//调试
if($Debug && $Event['user_id'] == $DebugListen){
    $Queue[]= new Message(
        var_export($Event, true)
        , config('master'), false, true, true
    );
}

//将队列中的消息发出
foreach($Queue as $msg){
    try{
        $MsgSender->send($msg);
    }catch(\Exception $e){
        setData('error.log', var_dump($Event).$e.$e->getCode(), true);
    }
}

?>
