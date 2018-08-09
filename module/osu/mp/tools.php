<?php

use Intervention\Image\ImageManagerStatic as Image;
use SVG\SVG;

function convertTimestamp($timestamp){
    return date_create($timestamp)->setTimezone(new DateTimeZone('Asia/Shanghai'));
}

function getEventUsers($eventUsers){
    foreach($eventUsers as $user){
        $users[$user->id] = $user;
    }
    return $users;
}

function parseEvent($event, $users){
    $time = '['.convertTimestamp($event->timestamp)->format('Y-m-d H:i:s').']';
    switch($event->detail->type){
        case 'match-created':
            return ''; //start时已经播报过了
        case 'player-joined':
            return $time." {$users[$event->user_id]->username} 加入了房间";
        case 'host-changed':
            return $time." {$users[$event->user_id]->username} 成为房主";
        case 'player-kicked':
            return $time." {$users[$event->user_id]->username} 被踢出房间";
        case 'player-left':
            return $time." {$users[$event->user_id]->username} 离开了房间";
        case 'match-disbanded':
            return $time.' 房间被关闭';
        case 'other':
            global $Event;
            $img = drawMatchEvent($event, $users);
            $img->save('../storage/cache/'.$Event['message_id']);
            return sendImg(getCache($Event['message_id']));
        default:
            return '';
    }
}

function drawMatchEvent($event, $users){
    $game = $event->game;
    $beatmap = $game->beatmap;
    $scores = $game->scores;
    $here = __DIR__.'/';
    $outside = $here.'../';
    $exo2 = $outside.'Exo2-Regular.ttf';
    $exo2_italic = $outside.'Exo2-Italic.ttf';
    $exo2_bold = $outside.'Exo2-Bold.ttf';
    $yahei = $outside.'Yahei.ttf';
    $white = '#ffffff';
    $startTime = convertTimestamp($game->start_time)->format('H:i');
    $endTime = convertTimestamp($game->end_time)->format('H:i');
    $teamTypeImg = Image::make(SVG::fromFile($here.$game->team_type.'.svg')->toRasterImage(40, 40));
    try{
        $img = Image::make($beatmap->beatmapset->covers->cover)->fit(960, 150); //slimcover不一定能获取到，所以使用cover
    }catch(\Exception $e){
        $img = Image::canvas(960, 150, '#888888');
    }
    //TODO 添加MOD图标
    $img->insert(Image::canvas(960, 150)->fill([0, 0, 0, 0.5])) //暗化50%
        ->insert($teamTypeImg, 'top-left', 910, 100)
        ->text($beatmap->beatmapset->title."[{$beatmap->version}]", 10, 120, imageFont($exo2_italic, 20, $white))
        ->text($beatmap->beatmapset->artist, 10, 140, imageFont($exo2_italic, 14, $white));
    if($scores==[]){
        $img->text("{$startTime} - (match in progress)   {$game->mode}   {$game->scoring_type}", 10, 20, imageFont($exo2_italic, 14, $white));
        return $img;
    }
    $img->text("{$startTime} - {$endTime}   {$game->mode}   {$game->scoring_type}", 10, 20, imageFont($exo2_italic, 14, $white));
    
    $eventResult = Image::canvas(960, 155 + 79*count($scores), 'rgb(238, 238, 238)')
        ->insert($img);
    $xIndex = 10;
    $yIndex = 155;

    switch($game->team_type){
        case 'head-to-head':
            usort($scores, function($scoreA, $scoreB){
                return $scoreB->score - $scoreA->score;
            });break;
        case 'team-vs':
            foreach($scores as $score){
                if($score->multiplayer->team == 'red')$redScore += $score->score;
                if($score->multiplayer->team == 'blue')$blueScore += $score->score;
            }
            usort($scores, function($scoreA, $scoreB) use ($redScore, $blueScore){
                if($scoreA->multiplayer->team == 'red' && $scoreB->multiplayer->team == 'blue'){
                    if($redScore > $blueScore)return -1;
                    else return 1;
                }else if($scoreA->multiplayer->team ==      $scoreB->multiplayer->team){
                    return $scoreB->score - $scoreA->score;
                }else{
                    if($redScore > $blueScore)return 1;
                    else return -1;
                }
            });break;
        case 'tag-coop':
        case 'tag-team-vs':
        default: //TODO 完成tag的排序方案
    }

    foreach($scores as $score){
        $eventResult->insert(drawPlayerMatchScore($score, $users[$score->user_id], $game->mode), 'top-left', $xIndex, $yIndex);
        $yIndex+=79;
    }

    return $eventResult;
}

function drawPlayerMatchScore($score, $user, $mode){
    $here = __DIR__.'/';
    $outside = $here.'../';
    $exo2_italic = $outside.'Exo2-Italic.ttf';
    $exo2_bold = $outside.'Exo2-Bold.ttf';
    $yahei = $outside.'Yahei.ttf';
    $white = '#ffffff';
    $blue = '#2299bb';
    $purple = '#6644cc';
    $pink = '#bb1177';
    $gray = '#555555';
    $light_gray = '#999999';
    $label_small = imageFont($exo2_italic, 10, $gray, 'right');
    $label_large = imageFont($exo2_italic, 12, $light_gray, 'right');
    $number_medium = imageFont($exo2_italic, 14, $purple);
    $img = Image::canvas(940, 74, $white);
    $team = Image::make($here.$score->multiplayer->team.'.png')->resize(40, 74);
    $flag = Image::make($outside."flags/{$user->country_code}.png")->resize(30, 20);

    //TODO 添加MOD图标
    $img->insert($team)
        ->text($user->username, 45, 28, imageFont($exo2_italic, 18, $blue))
        ->insert($flag, 'top-left', 45, 37)
        ->text('Combo', 500, 35, $label_small)
        ->text(number_format($score->max_combo), 540, 35, $number_medium)
        ->text('Accuracy', 655, 35, $label_small)
        ->text(sprintf('%.2f%%', $score->accuracy*100), 710, 35, $number_medium)
        ->text(number_format($score->score), 790, 35, imageFont($exo2_italic, 25, $pink, 'right'))
        ->text('miss', 875, 65, $label_large)
        ->text(number_format($score->statistics->count_miss), 905, 65, $number_medium)
        ->text('50', 830, 65, $label_large)
        ->text(number_format($score->statistics->count_50), 850, 65, $number_medium)
        ->text('100', 775, 65, $label_large)
        ->text(number_format($score->statistics->count_100), 800, 65, $number_medium)
    ;

    if(!$score->multiplayer->pass){
        $img->text('FAILED', 215, 28, imageFont($exo2_bold, 15, '#ed1221'));
    }

    switch($mode){
        case 'mania':
            $img
                ->text('200', 710, 65, $label_large)
                ->text(number_format($score->statistics->count_katu), 733, 65, $number_medium)
                ->text('300', 640, 65, $label_large)
                ->text(number_format($score->statistics->count_300), 665, 65, $number_medium)
                ->text('MAX', 570, 65, $label_large)
                ->text(number_format($score->statistics->count_geki), 598, 65, $number_medium)
            ;
            break;
        default:
            $img
                ->text('300', 710, 65, $label_large)
                ->text(number_format($score->statistics->count_300), 733, 65, $number_medium)
            ;
    }

    return $img;
}

?>
