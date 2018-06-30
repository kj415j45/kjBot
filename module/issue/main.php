<?php

global $Queue, $Text, $Event;

$length = strpos($Text, "\r");
if(false===$length)$length=strlen($Text);
$title = substr($Text, 0, $length);
$body = substr($Text, $length+2);

if($title == '')leave('请提供 issue 标题');

if(coolDown("issue/{$Event['user_id']}")<0);leave('冷却中');
coolDown("issue/{$Event['user_id']}", 60*60*24);

$Github = new \Github\Client();
$Github->authenticate(config('GITHUB_TOKEN'), '', \Github\Client::AUTH_HTTP_TOKEN);

$result = $Github->api('issue')->create('kj415j45', 'kjBot', [
    'title' => '[From Bot] '.$title,
    'body' => '>该 Issue 由 Bot 通过 API 生成，创建者：'.$Event['user_id']."\n\n".$body,
    'assignees' => ['kj415j45'],
]);

$Queue[]= sendBack('Issue 创建成功！'.$result['html_url']);  

?>