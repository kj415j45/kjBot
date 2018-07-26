<?php

global $osu_api_key, $osu;
$osu='https://osu.ppy.sh';
$osu_api_key=config('osu_api_key');

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
    $acc=ACCof($stat);
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
    $u=OsuUsernameEscape($u);
    $result = json_decode(file_get_contents("https://osu.ppy.sh/api/get_user_recent?k={$k}&u={$u}&m={$m}"), true)[0];
    if(NULL === $result){
        throw new \Exception('玩家最近没有成绩');
    }
    return $result;
}

function get_user_best($k, $u, $bp, $m = OsuMode::std){
    $u=OsuUsernameEscape($u);
    $result = json_decode(file_get_contents("https://osu.ppy.sh/api/get_user_best?k={$k}&u={$u}&limit={$bp}&m={$m}"), true)[$bp-1];
    if(NULL === $result){
        throw new \Exception('没有这个bp');
    }
    return $result;
}

function get_user($k, $u, $m = OsuMode::std){
    $u=OsuUsernameEscape($u);
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

function OsuUsernameEscape($osuid){
    return str_replace('+', '%20', urlencode($osuid));
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

function imageFont($file = 1, $size = 12, $color = '#000000', $align = 'left', $valign = 'buttom', $angle = 0){
    return function($font) use ($file, $size, $color, $align, $valign, $angle){
        $font->file($file);
        $font->size($size);
        $font->color($color);
        $font->align($align);
        $font->valign($valign);
        $font->angle($angle);
    };
}

function drawScore($recent, $map, $u){
    Image::configure(array('driver' => 'imagick'));

    $here = __DIR__.'/';
    $exo2 = $here.'Exo2-Regular.ttf';
    $exo2b = $here.'Exo2-Bold.ttf';
    $venera = $here.'Venera.ttf';
    $blue = '#44AADD';
    $gray = '#AAAAAA';
    $pink = '#B21679';

    $acc=ACCof($recent);

    $scoreImg = getBG($map['beatmap_id']);

    $sideText = imageFont($exo2b, 38, $blue, 'center', 'buttom'); //两侧字体
    $songText = imageFont($exo2b, 15, $blue, 'center', 'buttom'); //歌名、歌手
    $performText = imageFont($exo2b, 20, $blue, 'center', 'buttom'); //表现情况
    $ppText = imageFont($exo2b, 25, $blue, 'center', 'buttom'); //PP
    
    $result_pp=getPP($recent['beatmap_id'], $recent);

    $scoreImg
    //准备模版
    ->blur(8)
    ->insert(Image::make($here.'template.png')->opacity(80), 'center')
    //两侧文字
    ->text($recent['maxcombo'].'x', 310, 350, $sideText)
    ->text($acc, 975, 350, $sideText)
    //昵称
    ->text($u, 640, 225, imageFont($exo2b, 30, $gray, 'center', 'buttom'))
    //Rank
    ->insert(Image::make($here.$recent['rank'].'.png'), 'top-left', 580, 210)
    //MOD
    ->insert(getModImage(praseMod($recent['enabled_mods'])), 'top', 640, 302)
    //分数
    ->text(number_format($recent['score']), 640, 375, imageFont($venera, $recent['score']>1000000?55:60, $pink, 'center', 'buttom'))
    //四维
    ->text('CS: '.sprintf('%.2f', $map['cs']).'   OD: '.sprintf('%.2f', $map['od']).'   Stars: '.sprintf('%.2f', $map['stars']).'   HP: '.sprintf('%.2f', $map['hp']).'   AR: '.sprintf('%.2f', $map['ar']), 640, 400, imageFont($exo2b, 15, $pink, 'center', 'buttom'))
    //歌名
    ->text($map['title'], 640, 420, $songText)
    //歌手
    ->text($map['artist'], 640, 435, $songText)
    //谱面难度及谱师
    ->text($map['version'].' - mapped by '.$map['creator'], 640, 450, imageFont($exo2b, 15, $gray, 'center', 'buttom'))
    //日期
    ->text($recent['date'], 640, 473, imageFont($exo2, 15, $gray, 'center', 'buttom'))
    //表现情况
    ->text(sprintf('%04d', $recent['count300']), 553, 515, $performText)
    ->text(sprintf('%04d', $recent['count100']), 615, 515, $performText)
    ->text(sprintf('%04d', $recent['count50']), 675, 515, $performText)
    ->text(sprintf('%04d', $recent['countmiss']), 735, 515, $performText)
    //三维pp
    ->text($result_pp['pp'].'PP', 605, 646, imageFont($exo2b, 30, $blue, 'right', 'buttom'))
    ->text(getFCPP($recent['beatmap_id'], $recent).'PP', 680, 646, imageFont($exo2b, 30, $blue, 'left', 'buttom'))
    ->text($result_pp['aim'], 540, 680, $ppText)
    ->text($result_pp['spd'], 640, 680, $ppText)
    ->text($result_pp['acc'], 740, 680, $ppText)
    ;

    
    return $scoreImg;
}
