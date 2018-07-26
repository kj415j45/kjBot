<?php

global $Event, $Queue;
loadModule('osu.tools');
use Intervention\Image\ImageManagerStatic as Image;

Image::configure(array('driver' => 'imagick'));

$withMeText = false;

do{
    $arg = nextArg();
    switch($arg){
        case '-withMe':
            $withMeText = true;
            break;
        case '-osu':
        case '-std':
            $mode = 'osu';
            break;
        case '-taiko':
            $mode = 'taiko';
            break;
        case '-ctb':
        case '-fruit':
        case '-fruits':
            $mode = 'fruits';
            break;
        case '-mania':
            $mode = 'mania';
            break;
        case '-user':
            $osuid = nextArg();
            break;
        default:

    }
}while($arg !== NULL);

$osuid = $osuid??getOsuID($Event['user_id']);

if($osuid == ''){
    throw new \Exception('未绑定 osu!');
}

$osuid = OsuUsernameEscape($osuid);

$web = file_get_contents('https://osu.ppy.sh/users/'.$osuid.'/'.$mode);

$target = '<script id="json-user" type="application/json">';

$start = strpos($web, $target);

$end = strpos(substr($web, $start), '</script>');

$userJson = substr($web, $start+strlen($target), $end-strlen($target));

$user = json_decode($userJson);

//初始化绘图环境
$here = __DIR__.'/';
$exo2 = $here.'Exo2-Regular.ttf';
$exo2_italic = $here.'Exo2-Italic.ttf';
$exo2_bold = $here.'Exo2-Bold.ttf';
$yahei = $here.'Yahei.ttf';
$white = '#ffffff';
$mode = $mode??$user->playmode;
$badges = $user->badges;
$badge = $badges[rand(0, count($badges)-1)];
$flag = file_exists($here."flags/{$user->country->code}.png")?($here."flags/{$user->country->code}.png"):($here.'flags/__.png');
$stats_key = imageFont($yahei, 12, $white);
$statics = $user->statistics;
$playtime = [
    'hours' => sprintf('%d', $statics->play_time/3600),
    'minutes' => sprintf('%d', ($statics->play_time%3600)/60),
    'seconds' => sprintf('%d', $statics->play_time%60),
];
$stat = [
    'Ranked 谱面总分' => number_format($statics->ranked_score),
    '准确率' => sprintf('%.2f%%', $statics->hit_accuracy),
    '游戏次数' => number_format($statics->play_count),
    '总分' => number_format($statics->total_score),
    '总命中次数' => number_format($statics->total_hits),
    '最大连击' => number_format($statics->maximum_combo),
    '回放被观看次数' => number_format($statics->replays_watched_by_others),
];
$grade = [
    'XH' => $statics->grade_counts->ssh,
    'X' => $statics->grade_counts->ss,
    'SH' => $statics->grade_counts->sh,
    'S' => $statics->grade_counts->s,
    'A' => $statics->grade_counts->a,
];
//开始绘图
$img = Image::make($user->cover_url);
$img->resize(1000, 350)
    ->insert(Image::canvas(1000, 350)->fill([0, 0, 0, 0.5])) //背景暗化50%
    ->insert(Image::make('https://a.ppy.sh/'.$user->id)->resize(110, 110), 'top-left', 40, 220) //插入头像
    ->text($user->username, 170, 256, imageFont($exo2_italic, 24, $white, 'left', 'top')) //插入用户名
    ;
if($badge!=NULL)$img->insert(Image::make($badge->image_url), 'top-left', 40, 168); //插入狗牌
if($user->is_supporter){
    $img->insert(Image::make($here.'heart.png')->resize(28, 28), 'top-left', 170, 223) //插入支持者标志
        ->insert(Image::make($here."modes/{$mode}.png")->resize(28, 28), 'top-left', 210, 223) //插入模式标志
        ;
}else{
    $img->insert(Image::make($here."modes/{$mode}.png")->resize(28, 28), 'top-left', 170, 223); //插入模式标志
}
$img->insert(Image::make($flag)->resize(30, 20), 'top-left', 170, 310) //插入国旗
    ->insert(Image::canvas(280, 323)->fill([0, 0, 0, 0.3]), 'top-left', 670, 27) //绘制右侧暗化
    ->text('游戏时间', 690, 50, $stats_key)
    ->text("{$playtime['hours']}小时 {$playtime['minutes']}分钟 {$playtime['seconds']}秒", 690, 72, imageFont($yahei, 18, '#ffcc22'))
    ->insert(Image::make($here.'levelbadge.png')->resize(50, 50), 'top-left', 880, 30)
    ->text($statics->level->current, 905, 45, imageFont($exo2_bold, 18, $white, 'center', 'middle'))
;
$yIndex = 120;
foreach($stat as $key => $value){
    $img->text($key, 690, $yIndex, $stats_key);
    $img->text($value, 930, $yIndex, imageFont($exo2_bold, 16, $white, 'right'));
    $yIndex+=20;
}
$img->text(sprintf('%.2f', $statics->pp), 690, 280, imageFont($exo2_bold, 40, $white));
$img->text('PP', 740, 300, imageFont($exo2_bold, 20, $white));
$img->text('#'.number_format($statics->rank->global), 930, 280, imageFont($exo2_bold, 20, $white, 'right'));
$img->text($user->country->code.' '.'#'.number_format($statics->rank->country), 930, 300, imageFont($exo2_bold, 20, $white, 'right'));
$xIndex = 675;
foreach($grade as $key => $value){
    $img->insert(Image::make($here."{$key}.png")->resize(50, 50), 'top-left', $xIndex, 300);
    $img->text($value, $xIndex+20, 350, imageFont($exo2_bold, 16, $white, 'center', 'buttom'));
    $xIndex+=55;
}
$img->save('../storage/cache/'.$Event['message_id']);

$msg = sendImg(getCache($Event['message_id']));
if($withMeText)$msg.="\n".preg_replace('/\n+/', "\n", str_replace('<br />', "\n", htmlspecialchars_decode(strip_tags($user->page->html, '<br>'))));

$Queue[]= sendBack($msg);


?>