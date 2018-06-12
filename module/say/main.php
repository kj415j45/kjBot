<?php

global $Event, $Queue;
if($Event['user_id']==config('master'))
$Queue[]= sendBack(nextArg());

?>