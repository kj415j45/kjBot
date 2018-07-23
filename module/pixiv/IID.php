<?php

global $Queue;
loadModule('pixiv.tools');

do{
    $iID = (int)nextArg();

    if($iID==NULL)break;

    $pixiv = getIllustInfoByID($iID);
    $tags = implode(' ', $pixiv->tags);
    $img = getIllustImgstr($pixiv);
    
    $msg=<<<EOT
画师ID：{$pixiv->userId}
标签：{$tags}

{$pixiv->illustTitle}

EOT;
    
    $msg.=sendImg($img);
    
    if($pixiv->xRestrict === 1){
        $Queue[]= sendPM($msg, false, true); //异步发送加快处理速度
    }else{
        $Queue[]= sendBack($msg, false, true);
    }

}while($iID!==NULL);


?>