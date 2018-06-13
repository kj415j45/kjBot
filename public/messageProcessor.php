<?php

if(preg_match('/^(['.config('prefix', '!').'])/', $Event['message'], $prefix)){
    $Command = parseCommand(substr($Event['message'], 0, strpos($Event['message'], "\r")));
    try{
        loadModule(substr(nextArg(), strlen($prefix[1])));
    }catch(kjBot\Frame\UnauthorizedException $e){
        $Queue[]= sendBack($e->getMessage());
    }catch(\Exception $e){
        //也许还有其他异常，但是这里不适合再抛出
    }
}else{ //不是命令
    //TODO 交给中间件处理
}

?>
