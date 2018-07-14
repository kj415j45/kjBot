<?php

global $Queue;
$query='';
do{
    $arg = nextArg();
    $query.=urlencode($arg).'+';
}while($arg!==NULL);
$Queue[]= sendBack('https://baidu.com/s?word='.rtrim($query, '+'));

?>
