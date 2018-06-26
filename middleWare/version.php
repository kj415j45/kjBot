<?php

global $Message, $Queue;

if(strpos($Message, 'kjBot 版本')!==false){
    loadModule('version');
    leave();
}
if(strpos($Message, 'kjBot版本')!==false){
    loadModule('version');
    leave();
}

?>