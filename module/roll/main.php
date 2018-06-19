<?php

global $Queue, $Command;
$countArg = count($Command)-1;
$min = 1;
$max = 100;

switch($countArg){
    case 1:
        $max = (int)nextArg();
        break;
    case 2:
        $min = (int)nextArg();
        $max = (int)nextArg();
        break;
    default:
    
}

$Queue[]= sendBack(rand($min, $max));

?>