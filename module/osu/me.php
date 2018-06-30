<?php

global $Event, $Queue;
loadModule('osu.tools');
use Intervention\Image\ImageManagerStatic as Image;

Image::configure(array('driver' => 'imagick'));

$osuid = getOsuID($Event['user_id']);

if($osuid == ''){
    throw new \Exception('未绑定 osu!');
}else{
    $osuid = urlencode($osuid);
}


$web = file_get_contents('https://osu.ppy.sh/users/'.$osuid);

$target = '<script id="json-user" type="application/json">';

$start = strpos($web, $target);

$end = strpos(substr($web, $start), '</script>');

$userJson = substr($web, $start+strlen($target), $end-strlen($target));

$user = json_decode($userJson, true);

$img = Image::make($user['cover_url']);


$img->save('../storage/cache/'.$Event['message_id']);

$Queue[]= sendBack(sendImg(getCache($Event['message_id']))."\n".preg_replace('/\n+/', "\n", str_replace('<br />', "\n", htmlspecialchars_decode(strip_tags($user['page']['html'], '<br>')))));


?>