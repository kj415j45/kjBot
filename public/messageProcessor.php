<?php

if(preg_match('/^(['.config('prefix', '!').'])/', $Event['message'], $prefix)){
    $length = strpos($Event['message'], "\r");
    if(false===$length)$length=strlen($Event['message']);
    else $length--;
    $Command = parseCommand(substr($Event['message'], strlen($prefix[1])-1, $length));
    try{
        loadModule(substr(nextArg(), strlen($prefix[1])));
    }catch(\Exception $e){
        $Queue[]= sendBack($e->getMessage());
    }
}else{ //不是命令
    //TODO 交给中间件处理
}

?>
