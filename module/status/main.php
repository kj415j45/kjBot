<?php

global $Queue;
use Linfo\Linfo;
requireMaster();

$linfo = new Linfo();
$parser = $linfo->getParser();

$load = $parser->getLoad();
$ram = $parser->getRam();
$uptime = $parser->getUpTime();
$disk = $parser->getMounts()[5];

$usedRam = sprintf('%.2fG', ($ram['total']-$ram['free'])/1000/1000/1000);
$totalRam = sprintf('%.2fG', $ram['total']/1000/1000/1000);
$usedRamPercent = sprintf('%.2f%%', ($ram['total']-$ram['free'])/$ram['total']*100);

$usedDisk = sprintf('%.2fG', $disk['used']/1000/1000/1000);
$totalDisk = sprintf('%.2fG', $disk['size']/1000/1000/1000);
$usedDiskPercent = sprintf('%.2f%%', $disk['used']/$disk['size']*100);

$msg=<<<EOT
Load: {$load['now']} {$load['5min']} {$load['15min']}
Mem:  {$usedRam}/{$totalRam} ({$usedRamPercent})
Disk: {$usedDisk}/{$totalDisk} ({$usedDiskPercent})
Up:   {$uptime['text']}
EOT;

$Queue[]= sendBack($msg);

?>