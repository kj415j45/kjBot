<?

use Intervention\Image\ImageManagerStatic as Image;

class OsuMode{
    const std = 0;
    const taiko = 1;
    const ctb = 2;
    const mania =3;
}

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
    $bg = Image::make('https://bloodcat.com/osu/i/'.$map)->resize(1280, 720);
    return $bg;
}

function getPP($map, $stat){
    $mods=getMODstring($stat['enabled_mods']);
    exec("curl https://osu.ppy.sh/osu/{$map} | oppai - ".(null!=$mods?"+{$mods}":'')." {$stat['count100']}x100 {$stat['count50']}x50 {$stat['countmiss']}m {$stat['maxcombo']}x -ojson", $result);
    $result=json_decode($result[0], true);
    return [
        'pp'=>sprintf('%.2f', $result['pp']),
        'aim'=>sprintf('%.2f', $result['aim_pp']),
        'spd'=>sprintf('%.2f', $result['speed_pp']),
        'acc'=>sprintf('%.2f', $result['acc_pp']),
    ];
}

function getMODstring($mod){
    $mods=NULL;
    $modList=array_keys(praseMod($mod));
    for($i=0;$i<count($modList);$i++){
        $mods.=$modList[$i];
    }
    return $mods;
}

function getModImages($list){
    $l=array_keys($list);
    $imgs=null;
    for($i=0;$i<count($l);$i++){
        $imgs[$i]=Image::make(__DIR__."/{$l[$i]}.png");
    }
    return $imgs;
}

function getSSPP($map, $stat){
    $mods=getMODstring($stat['enabled_mods']);
    exec("curl https://osu.ppy.sh/osu/{$map} | oppai - ".(null!=$mods?"+{$mods}":'')." 100% -ojson", $result);
    return sprintf('%.2f', json_decode($result[0], true)['pp']);
}

function get_user_recent($k, $u, $m = OsuMode::std){
    $u=urlencode($u);
    return json_decode(file_get_contents("https://osu.ppy.sh/api/get_user_recent?k={$k}&u={$u}&m={$m}"), true)[0];
}

function get_user_best($k, $u, $bp, $m = OsuMode::std){
    $u=urlencode($u);
    return json_decode(file_get_contents("https://osu.ppy.sh/api/get_user_best?k={$k}&u={$u}&limit={$bp}&m={$m}"), true)[$bp-1];
}

function get_user($k, $u, $m = OsuMode::std){
    $u=urlencode($u);
    return json_decode(file_get_contents("https://osu.ppy.sh/api/get_user?k={$k}&u={$u}&m={$m}"), true)[0];
}

function get_map($id, $mod){
    $mods=getMODstring($mod);
    exec("curl https://osu.ppy.sh/osu/{$id} | oppai - -ojson ".(null!=$mods?"+{$mods}":''), $result);
    return json_decode($result[0], true);
}

function getBindOsuID($qq){
    return rtrim(getData("osu/id/{$qq}"));
}

?>
