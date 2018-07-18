<?php

global $Queue;
requireMaster();

$Queue[]= sendPM(getUserCommandCount(0));

?>