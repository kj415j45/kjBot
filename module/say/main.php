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

$index = strpos($Event['message'], "\n");
if($index !== false){
    $Queue[]= sendBack(
        substr($Event['message'], $index+1)
        , $escape, $async);
}

?>
