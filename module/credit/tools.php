<?php

function getCredit($QQ){
    return (int)getData("credit/{$QQ}");
}

function setCredit($QQ, $credit, $set = false){
    if($set)setData('credit.history', "* {$QQ} {$credit}\n", true);
    return setData("credit/{$QQ}", (int)$credit);
}

function addCredit($QQ, $income){
    setData('credit.history', "+ {$QQ} {$income}\n", true);
    return setCredit($QQ, getCredit($QQ)+(int)$income, true);
}

function decCredit($QQ, $pay){
    $balance = getCredit($QQ);
    if($balance >= $pay){
        setData('credit.history', "- {$QQ} {$pay}\n");
        return setCredit($QQ, (int)($balance-$pay), true);
    }else{
        throw new \Exception('余额不足,还需要 '.($pay-$balance).' 个金币');
    }
}

function transferCredit($from, $to, $transfer){
    decCredit($from, $transfer);
    addCredit($to, $transfer);
}

?>