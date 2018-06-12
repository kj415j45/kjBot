<?php

require('../SDK/Autoload.php');
require('tools/Autoload.php');
require('Message.php');
require('MessageSender.php');
use kjBot\SDK\CoolQ;
use kjBot\Frame\MessageSender;

$Config = parse_ini_file('../config.ini', false, INI_SCANNER_RAW);
$Event = json_decode(file_get_contents('php://input'), true);
$CQ = new CoolQ(config('API', '127.0.0.1:5700'), config('token', ''));
$Queue = [];
$MsgSender = new MessageSender($CQ);

?>
