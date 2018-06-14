<?php

use Intervention\Image\ImageManagerStatic as Image;

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

    $exo2 = '/usr/share/fonts/truetype/exo2/exo2.ttf';
    $exo2b = '/usr/share/fonts/truetype/exo2/exo2-bold.ttf';
    $here = __DIR__.'/';
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
    ->insert(Image::make($here.'templete.png')->opacity(80), 'center')
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

?>
