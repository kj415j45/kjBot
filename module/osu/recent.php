<?php

global $Event, $Queue, $osu_api_key;
loadModule('osu.tools');

do{
    $arg = nextArg();
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

$recent = get_user_recent($osu_api_key, $u, $m);
$map = get_map($recent['beatmap_id'], $recent['enabled_mods']);
$map['beatmap_id'] = $recent['beatmap_id'];

$img = drawScore($recent, $map, $u);

$img->save('../storage/cache/'.$Event['message_id']);

$Queue[]= sendBack(sendImg(getCache($Event['message_id'])));

?>