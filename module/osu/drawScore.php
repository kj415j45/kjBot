<?php

use Intervention\Image\ImageManagerStatic as Image;

function drawScore($recent, $map, $u){
    $exo2 = '/usr/share/fonts/truetype/exo2/exo2.ttf';
    $exo2b = '/usr/share/fonts/truetype/exo2/exo2-bold.ttf';
    $here = __DIR__.'/';
    $venera = $here.'Venera.ttf';

    $acc=ACCof($recent);

    $scoreImg = getBG($map['beatmap_id']);

    
    $scoreImg->insert(Image::make($here.'templete.png'), 'center');


    
    return $scoreImg;
}

?>