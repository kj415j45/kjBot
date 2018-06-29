<?php

use kjBot\SDK\CQCode;

global $Queue, $Event;

$page = 1;

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

$webHeader = [
    "http" => [
        "header" => 'cookie: PHPSESSID='.config('Pixiv_Session').';' //通过指明 Session ID 获得某些图
    ]
];

$web = file_get_contents($webStr, false, stream_context_create($webHeader));

if($web===false)leave('无法打开 Pixiv');

preg_match('/data-items="([^"]*)"/', $web, $match);
preg_match('/<span class="count-badge">(\d+)件/', $web, $count);

$json = html_entity_decode($match[1]);

if($json == '[]')leave('没有结果');

$result = json_decode($json, true);

$index = rand(0, count($result)-1);

$pixiv = $result[$index++];

$msg=<<<EOT
该关键字共有 {$count[1]} 幅作品，当前是第 {$page} 页第 {$index} 幅
插画ID：{$pixiv['illustId']}
画师ID：{$pixiv['userId']}

{$pixiv['illustTitle']}

EOT;

$img = str_replace('/c/240x240/img-master', '/img-original', $pixiv['url']); //转换为原图路径

$img = str_replace('_master1200', '', $img); //消去尾缀

$img = str_replace('.jpg', '.png', $img); //优先尝试以 png 取得原图

$imgHeader = [
    'http' => [
        'header' => 'referer: https://www.pixiv.net/member_illust.php?mode=medium&illust_id='.$pixiv['illustId']."\n". //伪造上级页面来源
                    'cookie: PHPSESSID='.config('Pixiv_Session').';' //Session ID 似乎不是必须
    ]
];

$imgStr = file_get_contents($img, false, stream_context_create($imgHeader));

if($imgStr === false){ //如果 png 没取到图
    $img = str_replace('.png', '.jpg', $img);
    $imgStr = file_get_contents($img, false, stream_context_create($imgHeader)); //改用 jpg 取图
    if($imgStr === false){
        leave($msg.'未知图片类型');
    }
}

$Queue[]= sendBack($msg.sendImg($imgStr));

?>