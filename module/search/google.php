<?php

global $Queue;
$query='';
do{
    $arg = nextArg();
    $query.=urlencode($arg).'+';
}while($arg!==NULL);
$Queue[]= sendBack('https://google.com/search?q='.rtrim($query, '+'));

?>
