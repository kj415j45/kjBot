<?php

function parseQQ($str){
    if(preg_match('/(\d+)/', $str, $QQ)){
        return $QQ[0];
    }else return NULL;
}

?>
