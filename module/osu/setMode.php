<?php

global $Queue, $Event;
loadModule('osu.tools');
use kjBot\SDK\CQCode;

$mode = nextArg();

switch($mode){
    case 'std':
    case '戳泡泡':
        $m = OsuMode::std;
        break;
    case 'taiko':
    case '打鼓':
    case '太鼓':
    case '打尻':
    case '太尻':
        $m = OsuMode::taiko;
        break;
    case 'ctb':
    case '草他爸':
    case '接水果':
    case '接屎':
        $m = OsuMode::ctb;
        break;
    case 'mania':
    case '骂娘':
    case '弹钢琴':
    case '砸键盘':
        $m = OsuMode::mania;
        break;
    default:

}

if(!isset($m)){
    throw new \Exception('请提供游戏模式！');
}

$u = getOsuID($Event['user_id']);
if($u == ''){
    throw new \Exception('未绑定 osu!，请使用 osu.bind 来绑定');
}

setData("osu/mode/{$Event['user_id']}", $m);
$Queue[]= sendBack(CQCode::At($Event['user_id']).' 的默认模式已设置为 '.$mode);

?>