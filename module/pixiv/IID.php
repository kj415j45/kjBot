<?php

global $Queue;
loadModule('pixiv.tools');

do{
    $iID = nextArg();

    if($iID==NULL)break;

    if(preg_match('/(\d+)_(\d+)/', $iID, $result)){ //如果给出的是 manga ID 格式
        $pixiv = getIllustInfoByID($result[1]);
    }else{
        $pixiv = getIllustInfoByID($iID);
    }
    $tags = getIllustTagsFromPixivJSON($pixiv);
    $imgURL = isset($result[2])?str_replace('_p0', "_p{$result[2]}", $pixiv->urls->original):$pixiv->urls->original;
    $pCount = $result[2]+1;
    if($pCount>$pixiv->pageCount)leave('P数超出范围');
    $pixiv->illustComment = strip_tags(str_replace('<br />', "\n", $pixiv->illustComment));
    $msg=<<<EOT
插画ID：{$pixiv->illustId} 当前是 {$pCount}/{$pixiv->pageCount} P
画师ID：{$pixiv->userId}
标签：{$tags}
收藏：{$pixiv->bookmarkCount} 喜欢：{$pixiv->likeCount} 浏览：{$pixiv->viewCount}

{$pixiv->illustTitle}
{$pixiv->illustComment}
[CQ:image,file={$imgURL}]
EOT;
    if($pixiv->xRestrict === 1){
        $Queue[]= sendPM($msg, false, true); //异步发送加快处理速度
    }else{
        $Queue[]= sendBack($msg, false, true);
    }
}while($iID!==NULL);


?>
