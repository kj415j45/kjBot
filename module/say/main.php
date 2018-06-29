<?php

global $Event, $Queue, $Text;
requireMaster();

if($Text == '')throw new \Exception();

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

$Queue[]= sendBack($Text, $escape, $async);

?>
