<?php

global $Queue;

$result = json_decode(`curl https://api.github.com/repos/kj415j45/kjBot/releases/latest`, true);
$Queue[]= sendBack("kjBot {$result['tag_name']} {$result['name']}\n{$result['body']}");

?>
