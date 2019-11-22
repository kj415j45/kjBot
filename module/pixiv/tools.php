<?php
global $pixivCookieHeader;

$pixivCookieHeader = [
    "http" => [
        "header" => 'cookie: PHPSESSID='.config('Pixiv_Session').';'."\n" //通过指明 Session ID 获得某些图
    ]
];

function getIllustInfoByID($iID){
    global $pixivCookieHeader;
    $web = file_get_contents('https://www.pixiv.net/member_illust.php?mode=medium&illust_id='.$iID, false, stream_context_create($pixivCookieHeader));
    if($web===false)leave('无法打开 Pixiv');

    if(!preg_match('/illust:\s?\{\s?'.$iID.':\s?({[\S\s]*}\})/', $web, $result)){
        leave('没有这张插画');
    }

    $pixiv = json_decode($result[1]);
    setData('test.txt', var_export($pixiv, true));
    return $pixiv;
}

function getIllustTagsFromPixivJSON($pixiv){
    $tags = $pixiv->tags->tags;
    $tagString = '';
    foreach($tags as $tag){
        $tagString.= $tag->tag.' ';
    }
    return rtrim($tagString);
}

?>