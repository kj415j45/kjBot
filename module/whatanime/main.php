<?php

loadModule('credit.tools');
global $Text, $Queue, $User_id;

$whatanimeBase = 'https://trace.moe/api';
$token = config('whatanime_token');
if(!preg_match('/url=(\S*)[,\]]/', $Text, $match))leave('解析图片失败');
$image = file_get_contents($match[1]);
$b64Image = base64_encode($image);

$opt = [
    'http' => [
        'method' => 'POST',
        'header' => <<<EOT
Content-Type: application/json
EOT
,
        'content' => "{\"image\": \"${b64Image}\"}",
    ]
];

decCredit($User_id, 5);
$resultJson = file_get_contents($whatanimeBase.'/search?token='.$token, false, stream_context_create($opt));

if(false === $resultJson){addCredit($User_id, 5);leave('过热中，请稍后重试');}
$anime = json_decode($resultJson)->docs[0];
$minute = floor($anime->at/60);
$second = $anime->at%60;
$episode = is_numeric($anime->episode)?"第 {$anime->episode} 集":$anime->episode;
$R18 = $anime->is_adult?"R18 警告\n\n":'';
$similarity = sprintf('%.2f%%', $anime->similarity*100);

$credit = getCredit($User_id);
$msg = <<<EOT
{$R18}你搜索的图片来自 {$anime->title_native}（{$anime->title_chinese}）{$episode}
位于 {$minute}分{$second}秒
可信度 {$similarity}（低于85%表明搜索结果很可能不准确）

你的余额为 {$credit}
EOT;

$Queue[]= sendBack($msg);