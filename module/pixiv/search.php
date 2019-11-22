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
        case '-like':
            $word.= nextArg().urlencode('users入り ');
            break;
        default:
            $word.= $arg?urlencode($arg.' '):'';
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
$pixiv = getIllustInfoByID($pixiv->illustId);
$tags = getIllustTagsFromPixivJSON($pixiv);
$pixiv->illustComment = strip_tags(str_replace('<br />', "\n", $pixiv->illustComment));

$msg=<<<EOT
该关键字共有 {$count[1]} 幅作品，这是第 {$page} 页第 {$index} 幅
插画ID：{$pixiv->illustId} 共 {$pixiv->pageCount} P
画师ID：{$pixiv->userId}
标签：{$tags}
收藏：{$pixiv->bookmarkCount} 喜欢：{$pixiv->likeCount} 浏览：{$pixiv->viewCount}

{$pixiv->illustTitle}
{$pixiv->illustComment}
[CQ:image,file={$pixiv->urls->regular}]
EOT;

$Queue[]= sendBack($msg);

?>
