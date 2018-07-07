<?php

global $Queue, $Text;

$method = nextArg();

switch($method){
    case 'encode':
        $Queue[]= sendBack(base64_encode($Text));
        break;
    case 'decode':
        $Queue[]= sendBack(base64_decode($Text));
        break;
    default:
}

?>
