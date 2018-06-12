<?php

if(preg_match('/^(['.config('prefix', '!').'])/', $Event['message'], $prefix)){
    $Command = parseCommand($Event['message']);
    loadModule(substr(nextArg(), strlen($prefix[1])));
}else{ //不是命令
    //TODO 交给中间件处理
}


?>