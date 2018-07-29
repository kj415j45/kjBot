<?php

requireMaster();
global $Text, $Queue;
use kjBot\SDK\CQCode;

$web = file_get_contents($Text);
if(false == $web)throw new \Exception('Update failed, no data found');

preg_match_all('/https:\/\/img\.moegirl\.org\/common\/\S{1}\/\S{2}\/[%a-zA-Z0-9_]*\.mp3/', $web, $result);
$start=count($result[0])-24-1;
for($i=$start, $j=0; $i<$start+24 ; $i++, $j++){
    setData("time/{$j}.mp3", file_get_contents($result[0][$i]));
    $Queue[]= sendPM(CQCode::Record('base64://'.base64_encode(getData("time/{$j}.mp3"))));
}

unset($result);

preg_match_all('/[0-9]{4}：[ \S]*<\/span><br>(?:[0-9]{4}：)?([ \S]*)/', $web, $result);
for($i=0;$i<24;$i++)
setData("time/{$i}.txt", preg_replace('/<[\S ]*>[\s\S]*<\/\S*>/','',$result[1][$i]));

$Queue[]= sendBack('Update success');

?>
