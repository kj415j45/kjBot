<?php

global $osu_api_key, $osu;
$osu='https://osu.ppy.sh';
$osu_api_key=rtrim(getData('osu_api_key'));

use Intervention\Image\ImageManagerStatic as Image;

class OsuMode{
    const std = 0;
    const taiko = 1;
    const ctb = 2;
    const mania =3;
};

function praseMod($mod){
    $list=NULL;
    if($mod & 1)$list['NF']=1;
    if($mod & 2)$list['EZ']=1;
    if($mod & 4)$list['TD']=1;
    if($mod & 8)$list['HD']=1;
    if($mod & 16)$list['HR']=1;
    if($mod & 32)$list['SD']=1;
    if($mod & 64)$list['DT']=1;
    if($mod & 128)$list['RX']=1;
    if($mod & 256)$list['HT']=1;
    if($mod & 512){unset($list['DT']);$list['NC']=1;}
    if($mod & 1024)$list['FL']=1;
    if($mod & 2048)$list['AU']=1;
    if($mod & 4096)$list['SO']=1;
    if($mod & 8192)$list['AP']=1;
    if($mod & 16384){unset($list['SD']);$list['PF']=1;}
    
    return $list;
}

function ACCof($m){
    $acc=(300*$m['count300']+
          100*$m['count100']+
          50*$m['count50'])/
          (300*($m['count300']+$m['count100']+$m['count50']+$m['countmiss']));
    return sprintf('%.2f%%', $acc*100);
}

function getBG($map){
    try{
        $bg = Image::make('https://bloodcat.com/osu/i/'.$map)->resize(1280, 720);
    }catch(\Exception $e){
        return Image::make(__DIR__.'/bg.jpg')->resize(1280, 720); //Fallback 背景
    }
    return $bg;
}

function getModString($mod){
    $mods=NULL;
    $modList=array_keys(praseMod($mod));
    for($i=0;$i<count($modList);$i++){
        $mods.=$modList[$i];
    }
    return $mods;
}

function getPP($map, $stat){
    $mods=getModString($stat['enabled_mods']);
    exec("curl https://osu.ppy.sh/osu/{$map} | oppai - ".(null!=$mods?"+{$mods}":'')." {$stat['count100']}x100 {$stat['count50']}x50 {$stat['countmiss']}m {$stat['maxcombo']}x -ojson", $result);
    $result=json_decode($result[0], true);
    return [
        'pp'=>sprintf('%.2f', $result['pp']),
        'aim'=>sprintf('%.2f', $result['aim_pp']),
        'spd'=>sprintf('%.2f', $result['speed_pp']),
        'acc'=>sprintf('%.2f', $result['acc_pp']),
    ];
}

function getModImages($list){
    $l=array_keys($list);
    $imgs=null;
    for($i=0;$i<count($l);$i++){
        $imgs[$i]=Image::make(__DIR__."/{$l[$i]}.png");
    }
    return $imgs;
}

function getModImage($list){
    $modImages = getModImages($list);
    $countImg = count($modImages);
    
    if($countImg === 0){
        return Image::canvas(1,1);
    }

    $modImage = Image::canvas(45*$countImg, 32);

    for($i = 0 ; $i<$countImg ; $i++){
        $modImage->insert($modImages[$i], 'top-left', $i*45, 0);
    }

    return $modImage;
}

function getFCPP($map, $stat){
    $mods=getModString($stat['enabled_mods']);
    exec("curl https://osu.ppy.sh/osu/{$map} | oppai - ".(null!=$mods?"+{$mods}":'')." {$acc} 0m -ojson", $result);
    return sprintf('%.2f', json_decode($result[0], true)['pp']);
}

function getSSPP($map, $stat){
    $acc = ACCof($stat);
    $mods=getModString($stat['enabled_mods']);
    exec("curl https://osu.ppy.sh/osu/{$map} | oppai - ".(null!=$mods?"+{$mods}":'')." 100% -ojson", $result);
    return sprintf('%.2f', json_decode($result[0], true)['pp']);
}

function get_user_recent($k, $u, $m = OsuMode::std){
    $u=urlencode($u);
    $result = json_decode(file_get_contents("https://osu.ppy.sh/api/get_user_recent?k={$k}&u={$u}&m={$m}"), true)[0];
    if(NULL === $result){
        throw new \Exception('玩家最近没有成绩');
    }
    return $result;
}

function get_user_best($k, $u, $bp, $m = OsuMode::std){
    $u=urlencode($u);
    $result = json_decode(file_get_contents("https://osu.ppy.sh/api/get_user_best?k={$k}&u={$u}&limit={$bp}&m={$m}"), true)[$bp-1];
    if(NULL === $result){
        throw new \Exception('没有这个bp');
    }
    return $result;
}

function get_user($k, $u, $m = OsuMode::std){
    $u=urlencode($u);
    $result = json_decode(file_get_contents("https://osu.ppy.sh/api/get_user?k={$k}&u={$u}&m={$m}"), true)[0];
    if(NULL === $result){
        throw new \Exception('无效的 osu! ID，请检查用户名是否正确（或者被 ban 了');
    }
    return $result;
}

function get_beatmap($k, $b){
    return json_decode(file_get_contents("https://osu.ppy.sh/api/get_beatmaps?k={$k}&b={$b}"), true)[0];
}

function get_map($id, $mod){
    global $osu_api_key;
    $map = get_beatmap($osu_api_key, $id);
    $mods=getModString($mod);
    exec("curl https://osu.ppy.sh/osu/{$id} | oppai - -ojson ".(null!=$mods?"+{$mods}":''), $result);
    return array_merge($map, json_decode($result[0], true));
}

function getOsuID($qq){
    return rtrim(getData('osu/id/'.$qq));
}

function setOsuID($qq, $id){
    global $osu_api_key;
    get_user($osu_api_key, $id);
    $setID = getOsuID($qq);
    if($setID === ''){
        return setData('osu/id/'.$qq, $id);
    }else{
        throw new \Exception('已经绑定了 '.$setID."\n".'需要改绑请联系 '.config('master'));
    }
}

loadModule('osu.drawScore');
