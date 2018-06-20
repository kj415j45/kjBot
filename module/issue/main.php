<?php

global $Queue, $Text, $Event;

if(coolDown('issue')<0)throw new \Exception('冷却中');
coolDown('issue', 60*60*24);

$length = strpos($Text, "\r");
if(false===$length)$length=strlen($Text);
$title = substr($Text, 0, $length);
$body = substr($Text, $length+2);

$json = json_encode([
    'title' => '[From Bot] '.$title,
    'body' => '>该 Issue 由 Bot 通过 API 生成'."\n\n".$body,
    'assignees' => ['kj415j45'],
]);

$opts = array('http' =>   
            array('method' => 'POST',
                  'header'  => 'Content-type: application/json; charset=utf-8\nUser-Agent: kjBot\nAuthorization: token '.config('GITHUB_TOKEN'),
                  'content' => $json));  
$context = stream_context_create($opts);  
$result = json_decode(file_get_contents('https://api.github.com/repos/kj415j45/kjBot/issues', false, $context), true);

$Queue[]= sendBack('Issue 创建成功！'.$result['html_url']);  

?>