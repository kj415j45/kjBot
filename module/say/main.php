<?php

global $Queue, $Text;
use kjBot\Frame\Message;
requireMaster();

if($Text == '')leave();

$escape = false;
$async = false;
$toGroup = false;
$toPerson = false;

do{
    $arg = nextArg();
    switch($arg){
        case '-escape':
            $escape = true;
            break;
        case '-async':
            $async = true;
            break;
        case '-toGroup':
            $toGroup = true;
            $id = nextArg();
            break;
        case '-toPerson':
            $toPerson = true;
            $id = nextArg();
            break;
        default:

    }
}while($arg !== NULL);

if($toGroup){
    $Queue[]= new Message($Text, $id, $toGroup, $escape, $async);
}else if($toPerson){
    $Queue[]= new Message($Text, $id, $toGroup, $escape, $async);
}else{
    $Queue[]= sendBack($Text, $escape, $async);
}


?>
