<?php
function allowRecord($user_id){
    return trim(getData('recordStat/'.$user_id))==='true';
}

function addCommandCount($user_id, $command){
    global $StatDB;
    if($user_id == config('bot'))return;
    if(allowRecord($user_id)){ //如果用户同意记名记录
        $row = getCommandCount($user_id, $command);
        if($row === false){
            $StatDB->query("INSERT INTO record VALUES ({$user_id}, '{$command}', 1)");
        }else{
            $count = $row['count'];
            $StatDB->query("UPDATE record SET count = {$count}+1 WHERE user_id={$user_id} AND command='{$command}'");
        }
        
    }
    $row = getCommandCount(0, $command);
    if($row === false){
        $StatDB->query("INSERT INTO record VALUES (0, '{$command}', 1)");
    }else{
        $count = $row['count'];
        $StatDB->query("UPDATE record SET count = {$count}+1 WHERE user_id=0 AND command='{$command}'");
    }
}

function getCommandCount($user_id, $command){
    global $StatDB;
    $result = $StatDB->query("SELECT count FROM record WHERE user_id={$user_id} AND command='{$command}'");
    return $result->fetchArray();
}

function getUserCommandCount($user_id, $limit = NULL){
    global $StatDB;
    $result = $StatDB->query("SELECT command, count FROM record WHERE user_id={$user_id} ORDER BY count DESC".($limit===NULL?'':' LIMIT '.abs($limit)));
    while(($row = $result->fetchArray()) && ($row !== false)){
        $text.="{$row['command']} {$row['count']}\n";
    }
    return rtrim($text);
}

?>
