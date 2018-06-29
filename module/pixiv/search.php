<?php

use kjBot\SDK\CQCode;
use Intervention\Image\ImageManagerStatic as Image;

global $Queue, $Event;

do{
    $arg = nextArg();
    switch($arg){
        case '-page':
            $page = nextArg();
            break;  
        case '-mode':
            $mode = nextArg();
            break;
        default:
            $word.= $arg??'';
    }
}while($arg !== NULL);

if(fromGroup())$mode = 'safe';

$webStr = 'https://www.pixiv.net/search.php?type=illust'
.'&p='.$page
.'&mode='.$mode
.'&word='.$word
;

$web = file_get_contents($webStr);

if($web===false)leave('没有结果');

preg_match('/data-items="([^"]*)"/', $web, $match);
preg_match('/<span class="count-badge">(\d+)件/', $web, $count);

$json = html_entity_decode($match[1]);

if($json == '')leave('没有结果');

$result = json_decode($json, true);

$pixiv = $result[rand(0, count($result)-1)];

$msg=<<<EOT
插画ID：{$pixiv['illustId']}
画师ID：{$pixiv['userId']}

{$pixiv['illustTitle']}

EOT;

$img = str_replace('/c/240x240/img-master', '/img-original', $pixiv['url']);

$img = str_replace('_master1200', '', $img);

$img = str_replace('.jpg', '.png', $img);

$header = [
    'http' => [
        'header' => 'referer: https://www.pixiv.net/member_illust.php?mode=medium&illust_id='.$pixiv['illustId']
    ]
];

$imgStr = file_get_contents($img, false, stream_context_create($header));

if($imgStr === false){
    $img = str_replace('.png', '.jpg', $img);
    $imgStr = file_get_contents($img, false, stream_context_create($header));
}

try{
    $pImg = Image::make($imgStr);
}catch(\Exception $e){
    leave($msg.'未知图片类型');
}

$pImg->save('../storage/cache/'.$Event['message_id']);

$Queue[]= sendBack($msg.CQCode::Image($img));

?>