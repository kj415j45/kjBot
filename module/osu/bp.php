<?php

global $Event, $Queue, $osu_api_key;
loadModule('osu.tools');

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
        case '-std':
            $mode = OsuMode::std;
            break;
        case '-taiko':
            $mode = OsuMode::taiko;
            break;
        case '-ctb':
            $mode = OsuMode::ctb;
            break;
        case '-mania':
            $mode = OsuMode::mania;
            break;
        default:

    }
}while($arg !== NULL);

$osuUser = getOsuID($Event['user_id']);
if($osuUser !== ''){
    $u = $user??$osuUser;
}else{
    if($user == NULL){
        throw new \Exception('未绑定 osu!，且未指定用户');
    }else{
        $u = $user;
    }
}
$osuMode = rtrim(getData("osu/mode/{$Event['user_id']}"));
$m = $mode??$osuMode;

$bp = get_user_best($osu_api_key, $u, $x, $m);
$map = get_map($bp['beatmap_id'], $bp['enabled_mods']);
$map['beatmap_id'] = $bp['beatmap_id'];

$img = drawScore($bp, $map, $u);
$img->save('../storage/cache/'.$Event['message_id']);
$Queue[]= sendBack(sendImg(getCache($Event['message_id'])));

?>