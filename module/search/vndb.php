<?php

global $Queue;
$query='';
$mode = nextArg();
do{
    $arg = nextArg();
    $query.=urlencode($arg).'+';
}while($arg!==NULL);
$Queue[]= sendBack('https://vndb.org/'.$mode.'/all?q='.rtrim($query, '+'));

?>
