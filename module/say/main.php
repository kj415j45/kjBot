<?php

global $Event, $Queue;
requireMaster();

do{
    $arg = nextArg();
    switch($arg){
        case '-escape':
            $escape = true;
            break;
        case '-async':
            $async = true;
            break;
        default:

    }
}while($arg !== NULL);

$Queue[]= sendBack(
          substr($Event['message'], strpos($Event['message'], "\n")+1)
          , $escape, $async);

?>
