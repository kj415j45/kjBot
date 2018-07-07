<?php

global $Queue, $Text;

$algo = nextArg();
$key = nextArg();

if(!in_array($algo, hash_hmac_algos()))leave('不支持的散列类型');

if($key !== NULL)$Queue[]= sendBack(hash($algo, $Text));
else $Queue[]= sendBack(hash_hmac($algo, $Text, $key));

?>
