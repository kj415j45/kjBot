<?php

global $Message, $Queue;

if(strpos($Message, 'kjBot 版本')!==false){
    loadModule('version');
    throw new \Exception();
}
if(strpos($Message, 'kjBot版本')!==false){
    loadModule('version');
    throw new \Exception();
}

?>