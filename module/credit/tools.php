<?php

function getCredit($QQ){
    return (int)getData("credit/{$QQ}");
}

function setCredit($QQ, $credit){
    return setData("credit/{$QQ}", (int)$credit);
}

function addCredit($QQ, $income){
    return setCredit($QQ, getCredit($QQ)+(int)$income);
}

function decCredit($QQ, $pay){
    $balance = getCredit($QQ);
    if($balance >= $pay){
        return setCredit($QQ, (int)($balance-$pay));
    }else{
        throw new \Exception('余额不足,还需要 '.($pay-$balance).' 个金币');
    }
}

function transferCredit($from, $to, $transfer){
    decCredit($from, $transfer);
    addCredit($to, $transfer);
}

?>