<?php

global $Queue, $Github;

$result = $Github->api('repo')->releases()->latest('kj415j45', 'kjBot');
$Queue[]= sendBack("kjBot {$result['tag_name']} {$result['name']}\n{$result['body']}");

?>
