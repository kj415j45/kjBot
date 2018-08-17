<?php

global $Queue, $CQ;

$coolQVersion = $CQ->getVersionInfo();
$coolQVersion->coolq_edition = ucfirst($coolQVersion->coolq_edition);

$Github = new \Github\Client();
$result = $Github->api('repo')->releases()->latest('kj415j45', 'kjBot');
$Queue[]= sendBack(<<<EOT
kjBot {$result['tag_name']} {$result['name']} on CoolQ {$coolQVersion->coolq_edition} (HTTP API {$coolQVersion->plugin_version})
项目地址：https://github.com/kj415j45/kjBot
{$result['body']}
EOT
);

?>
