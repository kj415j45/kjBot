<?php

function parseQQ($str){
    if(preg_match('/(\d+)/', $str, $QQ)){ //本身就是QQ号
        return $QQ[0];
    }else return NULL;
}

?>
