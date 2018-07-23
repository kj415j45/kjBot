<?php
global $pixivCookieHeader;

$pixivCookieHeader = [
    "http" => [
        "header" => 'cookie: PHPSESSID='.config('Pixiv_Session').';'."\n" //通过指明 Session ID 获得某些图
    ]
];

function getIllustImgstr($pixiv, $page = NULL){
    global $pixivCookieHeader;

    $img = $pixiv->url;
    $img = str_replace('_master1200', '', $img); //消去尾缀
    $img = str_replace('.jpg', '.png', $img); //优先尝试以 png 取得原图
    if($page !== NULL){ //如果是在获取 manga ID
        $img = str_replace('_p0.png', "_p{$page}.png", $img);
        $img = str_replace('/img-master', '/img-original', $img);
        $imgHeader['http']['header']=$pixivCookieHeader['http']['header'].'referer: https://www.pixiv.net/member_illust.php?mode=manga&illust_id='.$pixiv->illustId."\n";
    }else{
        $img = str_replace('/c/240x240/img-master', '/img-original', $img); //转换为原图路径
        $imgHeader['http']['header']=$pixivCookieHeader['http']['header'].'referer: https://www.pixiv.net/member_illust.php?mode=medium&illust_id='.$pixiv->illustId."\n"; //伪造上级页面来源
    }

    $imgStr = file_get_contents($img, false, stream_context_create($imgHeader));

    if($imgStr === false){ //如果 png 没取到图
        $img = str_replace('.png', '.jpg', $img);
        $imgStr = file_get_contents($img, false, stream_context_create($imgHeader)); //改用 jpg 取图
        if($imgStr === false){
            leave('未知图片类型');
        }
    }

    return $imgStr;
}

function getIllustInfoByID($iID){
    global $pixivCookieHeader;
    $web = file_get_contents('https://www.pixiv.net/member_illust.php?mode=medium&illust_id='.$iID, false, stream_context_create($pixivCookieHeader));
    if($web===false)leave('无法打开 Pixiv');

    if(!preg_match('/"'.$iID.'":({[^}]*})/', $web, $result)){
        leave('没有这张插画');
    }

    $pixiv = json_decode($result[1]);
    return $pixiv;
}

?>