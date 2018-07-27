<?php

if(preg_match('/^('.config('prefix', '!').')/', $Event['message'], $prefix)){
    $length = strpos($Event['message'], "\r");
    if(false===$length)$length=strlen($Event['message']);
    $Command = parseCommand(substr($Event['message'], strlen($prefix[1])-1, $length));
    $Text = substr($Event['message'], $length+2);
    try{
        loadModule(substr(nextArg(), strlen($prefix[1])));
    }catch(\Exception $e){
        throw $e;
    }
}else{ //不是命令
    $Message = $Event['message'];
    require('../middleWare/Chain.php');
}

?>
