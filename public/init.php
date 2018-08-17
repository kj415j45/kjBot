<?php

include('../vendor/autoload.php'); //避免没有vendor的用户出错
use kjBot\SDK\CoolQ;
use kjBot\SDK\CQCode;
use kjBot\Frame\MessageSender;

//全局变量区
$Config = parse_ini_file('../config.ini', false);
$Event = json_decode(file_get_contents('php://input'), true);
$Event['message'] = CQCode::DecodeCQCode($Event['message']);
$User_id = $Event['user_id'];
$CQ = new CoolQ(config('API', '127.0.0.1:5700'), config('token', ''));
$Queue = [];
$MsgSender = new MessageSender($CQ);
$Debug = config('DEBUG', false);
$DebugListen = config('DebugListen', config('master'));
$Command = [];
$Text = '';
$StatDB = new SQLite3('../storage/data/stat.db');

block($Event['user_id']);

?>
