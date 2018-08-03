<?php

function parseQQ($str){
    if(preg_match('/\[CQ:at,qq=(\d+)\]/', $str, $QQ)){
        return $QQ[1];
    }else return NULL;
}

?>
