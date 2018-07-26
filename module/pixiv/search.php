<?php

global $Queue, $Event;
loadModule('pixiv.tools');
global $pixivCookieHeader;
use kjBot\SDK\CQCode;

$page = 1;

do{
    $arg = nextArg();
    switch($arg){
        case '-':
            $target = nextArg();
            break;
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
.'&mode='.strtolower($mode)
.'&word='.$word
;

$web = file_get_contents($webStr, false, stream_context_create($pixivCookieHeader));

if($web===false)leave('无法打开 Pixiv');

preg_match('/data-items="([^"]*)"/', $web, $match);
preg_match('/<span class="count-badge">(\d+)件/', $web, $count);

$json = html_entity_decode($match[1]);

if($json == '[]' || $json == '')leave('没有结果');

$result = json_decode($json);

if(isset($target) && 1<=$target && $target<=count($result)){
    $index = $target-1;
}else{
    $index = rand(0, count($result)-1);
}

$pixiv = $result[$index++];
$tags = implode(' ', $pixiv->tags);
$img = getIllustImgstr($pixiv);

$msg=<<<EOT
该关键字共有 {$count[1]} 幅作品，这是第 {$page} 页第 {$index} 幅
插画ID：{$pixiv->illustId}
画师ID：{$pixiv->userId}

{$pixiv->illustTitle}

EOT;

$msg.=sendImg($img);

$Queue[]= sendBack($msg);

?>