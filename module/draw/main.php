<?php

global $Queue;

$templeteName = nextArg();
$templete = explode("\n", getData("draw/{$templeteName}"));
if($templete==[''])leave('没有该模版');
$cardCount = count($templete)-1;

$count = (int)nextArg();
if($count<1 || $count>10)$count=1;

$msg = $templete[rand(0, $cardCount)];

for($i=1;$i<$count;$i++){
    $msg.= "\n".$templete[rand(0, $cardCount)];
}

$Queue[]= sendBack($msg);

?>