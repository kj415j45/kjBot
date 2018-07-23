<?php

global $Queue;
loadModule('pixiv.tools');

do{
    $iID = nextArg();

    if($iID==NULL)break;

    if(preg_match('/(\d+)_(\d+)/', $iID, $result)){ //如果给出的是 manga ID 格式
        $pixiv = getIllustInfoByID($result[1]);
        $img = getIllustImgstr($pixiv, $result[2]);
        
    }else{
        $pixiv = getIllustInfoByID($iID);
        $img = getIllustImgstr($pixiv);
    }
    $tags = implode(' ', $pixiv->tags);

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