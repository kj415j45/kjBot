<?php

global $Queue, $Text, $Event, $Github;

if(coolDown("issue/{$Event['user_id']}")<0)throw new \Exception('冷却中');
coolDown("issue/{$Event['user_id']}", 60*60*24);

$length = strpos($Text, "\r");
if(false===$length)$length=strlen($Text);
$title = substr($Text, 0, $length);
$body = substr($Text, $length+2);

if($title == '')throw new \Exception('请提供 issue 标题');

$result = $Github->api('issue')->create('kj415j45', 'kjBot', [
    'title' => '[From Bot] '.$title,
    'body' => '>该 Issue 由 Bot 通过 API 生成，创建者：'.$Event['user_id']."\n\n".$body,
    'assignees' => ['kj415j45'],
]);

$Queue[]= sendBack('Issue 创建成功！'.$result['html_url']);  

?>