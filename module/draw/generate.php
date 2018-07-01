<?php

class Card{
    public $name;
    public $prev;
    public $next;
    public function __construct($name){$this->name=$name;}
}

global $Queue, $Text;
requireMaster();

$pool = explode("\r\n", $Text);
foreach($pool as $card){
    sscanf($card, '%d %s', $count, $name);
    $cardCount[]= $count;
    $cardName[]= $name;
}

$totalCard = array_sum($cardCount);
$templeteName = nextArg();
$templete = fopen('../storage/data/draw/'.$templeteName, 'w');
if(!$templete)leave('提供模版名');

$count = count($pool);

$generatedCard = 0;

$head = new Card($cardName[0]);
$p = $head;
$cardCount[0]--;
for($i=0;$i<$count;$i++){
    for($j=0;$j<$cardCount[$i];$j++){
        $q = new Card($cardName[$i]);
        $p->next = $q;
        $q->prev = $p;

        $p = $p->next;
    }
}
$p->next = $head;
$head->prev = $p;

while($p->next !== $p){
    $step = rand(0, $totalCard);
    for($i=0;$i<$step;$i++)$p = $p->next;
    fwrite($templete, $p->name."\n");
    $q = $p;
    $p->prev->next = $q->next;
    $p->next->prev = $q->prev;
    $p = $q->next;
    $totalCard--;
}
fwrite($templete, $p->name."\n");

$Queue[]= sendBack('生成成功');

?>