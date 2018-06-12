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
        $Queue[]= new Message('Unknown post type', config('master', 919815238), false);
}

foreach($Queue as $msg){
    try{
        $MsgSender->send($msg);
    }catch(\Exception $e){
        setData('error.log', var_dump($Event).$e.$e->getCode(), true);
    }
}

?>
