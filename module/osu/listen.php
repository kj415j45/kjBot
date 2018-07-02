<?php

global $Queue, $Event;
use PhpZip\ZipFile;
loadModule('credit.tools');

$webHeader = [
    "http" => [
        "header" => 'Cookie: '.config('osu_cookie')
    ]
];

$beatmapSetID = (int)nextArg();

$temp = getData("osu/listen/{$beatmapSetID}.mp3");
if($temp !== false){
    $Queue[]= sendBack(sendRec($temp));
    decCredit($Event['user_id'], 1);
    $Queue[]= sendBack('点歌成功，你现在的余额为 '.getCredit($Event['user_id']));
    leave();
}

$osz = new ZipFile();

$web = file_get_contents('https://osu.ppy.sh/d/'.$beatmapSetID, false, stream_context_create($webHeader));
if(!$web)leave('获取歌曲失败');

try{
    $osz->openFromString($web);
}catch(\Exception $e){leave('无法打开谱面');}

$oszFiles = $osz->matcher();

$mp3FileName = $oszFiles->match('~\S*\.mp3~')->getMatches()[0];

$mp3 = $osz->getEntryContents($mp3FileName);
setData("osu/listen/{$beatmapSetID}.mp3", $mp3);

$Queue[]= sendBack(sendRec($mp3));
decCredit($Event['user_id'], 5);
$Queue[]= sendBack('点歌成功，你现在的余额为 '.getCredit($Event['user_id']));

?>