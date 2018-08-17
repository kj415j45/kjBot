<?php

global $Event, $Queue, $osu_api_key, $User_id;
loadModule('osu.tools');

$qq = $User_id;

do{
    $arg = nextArg();
    switch($arg){
        case '-user':
            $temp = nextArg();
            if(parseQQ($temp)!==NULL){
                $qq = parseQQ($temp);
                $user = getOsuID($qq);
                if($user=='')leave('指定的用户未绑定 osu!');
            }else{
                $user = $temp;
            }
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

$osuUser = getOsuID($qq);
if($osuUser !== ''){
    $u = $user??$osuUser;
}else{
    if($user == NULL){
        throw new \Exception("未绑定 osu!,请使用\n!osu.bind 用户名\n进行绑定");
    }else{
        $u = $user;
    }
}
$osuMode = rtrim(getData("osu/mode/{$qq}"));
$m = $mode??$osuMode;

$recent = get_user_recent($osu_api_key, $u, $m);
$map = get_map($recent['beatmap_id'], $recent['enabled_mods']);
$map['beatmap_id'] = $recent['beatmap_id'];

$img = drawScore($recent, $map, $u);

$img->save('../storage/cache/'.$Event['message_id']);

$Queue[]= sendBack(sendImg(getCache($Event['message_id'])));

?>