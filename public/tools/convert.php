<?php

function parseQQ($str){
    if(preg_match('/(\d+)/', $str)){ //本身就是QQ号
        return $str;
    }else if(preg_match('/[CQ:at,qq=(\d+)]/', $str, $result)){ //匹配CQ码
        return $result[1];
    }else return NULL;
}

?>