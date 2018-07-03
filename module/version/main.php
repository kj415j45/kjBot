<?php

global $Queue;

$Github = new \Github\Client();
$result = $Github->api('repo')->releases()->latest('kj415j45', 'kjBot');
$Queue[]= sendBack("kjBot {$result['tag_name']} {$result['name']}\n{$result['body']}");

?>
