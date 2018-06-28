<?php

global $Queue;

$Github = new \Github\Client();
$Github->authenticate(config('GITHUB_TOKEN'), '', \Github\Client::AUTH_HTTP_TOKEN);
$result = $Github->api('repo')->releases()->latest('kj415j45', 'kjBot');
$Queue[]= sendBack("kjBot {$result['tag_name']} {$result['name']}\n{$result['body']}");

?>
