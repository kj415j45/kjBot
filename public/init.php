<?php

include('../vendor/autoload.php'); //避免没有vendor的用户出错
use kjBot\SDK\CoolQ;
use kjBot\SDK\CQCode;
use kjBot\Frame\MessageSender;

//全局变量区
$Config = parse_ini_file('../config.ini', false, INI_SCANNER_RAW);
$Event = json_decode(file_get_contents('php://input'), true);
$Event['message'] = CQCode::DecodeCQCode($Event['message']);
$CQ = new CoolQ(config('API', '127.0.0.1:5700'), config('token', ''));
$Queue = [];
$MsgSender = new MessageSender($CQ);
$Debug = ('true'===config('DEBUG', 'false'))?true:false;
$DebugListen = config('DebugListen', config('master'));
$Command = [];
$Text = '';

?>
