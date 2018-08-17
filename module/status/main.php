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
[System]
Load: {$load['now']} {$load['5min']} {$load['15min']}
Mem:  {$usedRam}/{$totalRam} ({$usedRamPercent})
Disk: {$usedDisk}/{$totalDisk} ({$usedDiskPercent})
Up:   {$uptime['text']}
EOT;

$opcache = opcache_get_status(false);
if(is_array($opcache)){
    $opcStatus = $opcache['opcache_statistics'];
    $opcMemWasteRate = sprintf('%.2f%%', $opcache['memory_usage']['current_wasted_percentage']*100);
    $opcHitRate = sprintf('%.2f%%', $opcStatus['opcache_hit_rate']);
    $msg.=<<<EOT


[OPcache]
Mem Waste Rate: {$opcMemWasteRate}
Cached/Max: ({$opcStatus['num_cached_scripts']}){$opcStatus['num_cached_keys']}/{$opcStatus['max_cached_keys']}
Hits/Miss: {$opcStatus['hits']}/{$opcStatus['misses']} ({$opcHitRate})
Restart(OOM Hash): {$opcStatus['oom_restarts']} {$opcStatus['hash_restarts']}
EOT;
}

$Queue[]= sendBack($msg);

?>
