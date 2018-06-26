<?php

global $Event, $Message, $Queue;

if(!fromGroup())throw new \Exception();

if(getData("repeat/{$Event['group_id']}-1")==''){
    $a = getData("repeat/{$Event['group_id']}-2");
    $b = getData("repeat/{$Event['group_id']}-3");
    $c = getData("repeat/{$Event['group_id']}-4");
    if($a == $Message && $b == $Message && $c != $Message){
        if(coolDown('repeat/'.$Event['group_id'])>0){
            coolDown('repeat/'.$Event['group_id'], 60);
            $Queue[]= sendBack($a);
        }
        setData("repeat/{$Event['group_id']}-1", $Message, true);
        setData("repeat/{$Event['group_id']}-2", '');
        setData("repeat/{$Event['group_id']}-4", $Message, true);
        leave();
    }else{
        setData("repeat/{$Event['group_id']}-1", $Message, true);
        setData("repeat/{$Event['group_id']}-2", '');
        setData("repeat/{$Event['group_id']}-4", '');
    }
}else if(getData("repeat/{$Event['group_id']}-2")==''){
    $a = getData("repeat/{$Event['group_id']}-1");
    $b = getData("repeat/{$Event['group_id']}-3");
    $c = getData("repeat/{$Event['group_id']}-4");
    if($a == $Message && $b == $Message && $c != $Message){
        if(coolDown('repeat/'.$Event['group_id'])>0){
            coolDown('repeat/'.$Event['group_id'], 60);
            $Queue[]= sendBack($a);
        }
        setData("repeat/{$Event['group_id']}-2", $Message, true);
        setData("repeat/{$Event['group_id']}-3", '');
        setData("repeat/{$Event['group_id']}-4", $Message, true);
        leave();
    }else{
        setData("repeat/{$Event['group_id']}-2", $Message, true);
        setData("repeat/{$Event['group_id']}-3", '');
        setData("repeat/{$Event['group_id']}-4", '');
    }
}else if(getData("repeat/{$Event['group_id']}-3")==''){
    $a = getData("repeat/{$Event['group_id']}-1");
    $b = getData("repeat/{$Event['group_id']}-2");
    $c = getData("repeat/{$Event['group_id']}-4");
    if($a == $Message && $b == $Message && $c != $Message){
        if(coolDown('repeat/'.$Event['group_id'])>0){
            coolDown('repeat/'.$Event['group_id'], 60);
            $Queue[]= sendBack($a);
        }
        setData("repeat/{$Event['group_id']}-3", $Message, true);
        setData("repeat/{$Event['group_id']}-1", '');
        setData("repeat/{$Event['group_id']}-4", $Message, true);
        leave();
    }else{
        setData("repeat/{$Event['group_id']}-3", $Message, true);
        setData("repeat/{$Event['group_id']}-1", '');
        setData("repeat/{$Event['group_id']}-4", '');
    }
}

?>