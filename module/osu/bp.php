<?php

global $Event, $Queue, $osu_api_key;
loadModule('osu.init');

do{
    $arg = nextArg();
    if(preg_match('/-(\d{1,3})/', $arg, $result)){
        $x = $result[1];
        coutinue;
    }
    switch($arg){
        case '-user':
            $user = nextArg();
            break;
        default:

    }
}while($arg !== NULL);

$osuUser = getOsuID($Event['user_id']);
if($osuUser !== false){
    $u = $user??$osuUser;
}else{
    if($user == NULL){
        throw \Exception('未绑定 osu!，且未指定用户');
    }else{
        $u = $user;
    }
}

$bp = get_user_best($osu_api_key, $u, $x);
$map = get_map($bp['beatmap_id'], $bp['enabled_mods']);
$map['beatmap_id'] = $bp['beatmap_id'];

$img = drawScore($bp, $map, $u);
$img->save('../storage/cache/'.$Event['message_id']);
$Queue[]= sendBack(sendImg(getCache($Event['message_id'])));

?>