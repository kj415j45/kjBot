<?php

/**
 * 读取配置文件
 * @param $kay 键值
 * @param $defaultValue 默认值
 */
function config($key, $defaultValue = NULL){
    global $Config;

    if(array_key_exists($key, $Config)){
        return $Config[$key];
    }else{
        return $defaultValue;
    }
}

/**
 * 给事件产生者发送私聊
 */
function sendPM($msg, $auto_escape = false, $async = false){
    global $Event;

    return new kjBot\Frame\Message($msg, $Event['user_id'], false, $auto_escape, $async);
}

/**
 * 消息从哪来发到哪
 */
function sendBack($msg, $auto_escape = false, $async = false){
    global $Event;

    return new kjBot\Frame\Message($msg, isset($Event['group_id'])?$Event['group_id']:$Event['user_id'], isset($Event['group_id']), $auto_escape, $async);
}

/**
 * @param $filePath 相对于 storage/data/ 的路径
 * @param $data 要存储的数据内容
 * @param $pending 是否追加写入（默认不追加）
 */
function setData($filePath, $data, $pending = false){
    if(!file_exists(dirname('../storage/data/'.$filePath))) mkdir(dirname('../storage/data/'.$filePath), 666, true);
    return file_put_contents('../storage/data/'.$filePath, $data, $pending?(FILE_APPEND | LOCK_EX):LOCK_EX);
}

/**
 * @param $filePath 相对于 storage/data/ 的路径
 * @return string/false
 */
function getData($filePath){
    return file_get_contents('../storage/data/'.$filePath);
}

/**
 * @param $cacheFileName 缓存文件名
 * @param $cache 要缓存的数据内容
 */
function setCache($cacheFileName, $cache){
    return file_put_contents('../storage/cache/'.$cacheFileName, $cache, LOCK_EX);
}

/**
 * @param $cacheFileName 缓存文件名
 * @return string/false
 */
function getCache($cacheFileName){
    return file_get_contents('../storage/cache/'.$cacheFileName);
}

/**
 * 清理缓存
 */
function clearCache(){
    $cacheDir = opendir('../storage/cache/');
    while (false !== ($file = readdir($cacheDir))) {
        if ($file != "." && $file != "..") {
            unlink('../storage/cache/'.$file);
        }
    }
    closedir($cacheDir);
}

function sendImg($str){
    return kjBot\SDK\CQCode::Image('base64://'.base64_encode($str));
}

/**
 * 装载模块
 */
function loadModule($module){
    if('.' === $module[0]){
        throw new \Exception('Illegal module name');
    }
    $moduleFile = str_replace('.', '/', $module, $count);
    if(0 === $count){
        $moduleFile.='/main.php';
    }else{
        $moduleFile.='.php';
    }

    if(file_exists('../module/'.$moduleFile)){
        require('../module/'.$moduleFile);
    }else{
        global $Event, $Queue;
        if(!isset($Event['group_id'])){
            $Queue[]= sendBack('没有该命令：'.$module);
        }
    }
}

/**
 * 解析命令
 * @param string $str 命令字符串
 * @return array/bool 解析结果数组 失败返回false
 */
function parseCommand($str){
    // 正则表达式
    $regEx = '#(?:(?<s>[\'"])?(?<v>.+?)?(?:(?<!\\\\)\k<s>)|(?<u>[^\'"\s]+))#';
    // 匹配所有
    if(!preg_match_all($regEx, $str, $exp_list)) return false;
    // 遍历所有结果
    $cmd = array();
    foreach ($exp_list['s'] as $id => $s) {
        // 判断匹配到的值
        $cmd[] = empty($s) ? $exp_list['u'][$id] : $exp_list['v'][$id];
    }
    return $cmd;
}

function isMaster(){
    global $Event;

    return $Event['user_id']==config('master');
}

function requireMaster(){
    if(!isMaster()){
        throw new kjBot\Frame\UnauthorizedException();
    }
}

function nextArg(){
    global $Command;
    static $index=0;

    return $Command[$index++];
}

function coolDown($name, $time = NULL){
    global $Event;
    if(NULL === $time){
        clearstatcache();
        return time() - filemtime("../storage/data/coolDown/{$name}/{$Event['user_id']}")-(int)getData("coolDown/{$name}/{$Event['user_id']}");
    }else{
        setData("coolDown/{$name}/{$Event['user_id']}", $time);
    }
}
