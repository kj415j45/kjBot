<?php

global $Message;

if(preg_match('/^签到$/', $Message)){
    loadModule('checkin');
}

?>