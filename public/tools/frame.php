<?php

use kjBot\SDK\CQCode;
use kjBot\Frame\Message;
use kjBot\Frame\UnauthorizedException;

/**
 * 读取配置文件
 * @param string $kay 键值
 * @param string $defaultValue 默认值
 * @return string|null
 */
function config(string $key, string $defaultValue = NULL):?string{
    global $Config;

    if(array_key_exists($key, $Config)){
        return $Config[$key];
    }else{
        return $defaultValue;
    }
}

/**
 * 给事件产生者发送私聊
 * @param string $msg 消息内容
 * @param bool $auto_escape 是否发送纯文本
 * @param bool $async 是否异步
 * @return kjBot\Frame\Message
 */
function sendPM(string $msg, bool $auto_escape = false, bool $async = false):Message{
    global $Event;

    return new Message($msg, $Event['user_id'], false, $auto_escape, $async);
}

/**
 * 消息从哪来发到哪
 * @param string $msg 消息内容
 * @param bool $auto_escape 是否发送纯文本
 * @param bool $async 是否异步
 * @return kjBot\Frame\Message
 */
function sendBack(string $msg, bool $auto_escape = false, bool $async = false):Message{
    global $Event;

    return new Message($msg, isset($Event['group_id'])?$Event['group_id']:$Event['user_id'], isset($Event['group_id']), $auto_escape, $async);
}

/**
 * 发送给 Master
 * @param string $msg 消息内容
 * @param bool $auto_escape 是否发送纯文本
 * @param bool $async 是否异步
 * @return kjBot\Frame\Message
 */
function sendMaster(string $msg, bool $auto_escape = false, bool $async = false):Message{
    return new Message($msg, config('master'), false, $auto_escape, $async);
}

/**
 * 记录数据
 * @param string $filePath 相对于 storage/data/ 的路径
 * @param $data 要存储的数据内容
 * @param bool $pending 是否追加写入（默认不追加）
 * @return mixed string|false
 */
function setData(string $filePath, $data, bool $pending = false){
    if(!file_exists(dirname('../storage/data/'.$filePath))) if(!mkdir(dirname('../storage/data/'.$filePath), 0777, true))throw new \Exception('Failed to create data dir');
    return file_put_contents('../storage/data/'.$filePath, $data, $pending?(FILE_APPEND | LOCK_EX):LOCK_EX);
}

/**
 * 读取数据
 * @param $filePath 相对于 storage/data/ 的路径
 * @return mixed string|false
 */
function getData(string $filePath){
    return file_get_contents('../storage/data/'.$filePath);
}

/**
 * 缓存
 * @param string $cacheFileName 缓存文件名
 * @param $cache 要缓存的数据内容
 * @return mixed string|false
 */
function setCache(string $cacheFileName, $cache){
    return file_put_contents('../storage/cache/'.$cacheFileName, $cache, LOCK_EX);
}

/**
 * 取得缓存
 * @param $cacheFileName 缓存文件名
 * @return mixed string|false
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

/**
 * 发送图片
 * @param string $str 图片（字符串形式）
 * @return string 图片对应的 base64 格式 CQ码
 */
function sendImg($str):string{
    return CQCode::Image('base64://'.base64_encode($str));
}

/**
 * 发送录音
 * @param string $str 录音（字符串形式）
 * @return string 录音对应的 base64 格式 CQ码
 */
function sendRec($str):string{
    return CQCode::Record('base64://'.base64_encode($str));
}

/**
 * 装载模块
 * @param string $module 模块名
 */
function loadModule(string $module){
    if('.' === $module[0]){
        leave('Illegal module name');
    }
    $moduleFile = str_replace('.', '/', $module, $count);
    if(0 === $count){
        $moduleFile.='/main.php';
    }else{
        $moduleFile.='.php';
    }

    if(file_exists('../module/'.$moduleFile)){
        if(config('recordStat', 'true')=='true'){
            if(strpos($module, '.tools')===false && strpos($module, 'recordStat')===false){ //防止记录工具类模块
                global $Event;
                addCommandCount($Event['user_id'], $module);
            }
        }
        require('../module/'.$moduleFile);
    }else{
        if(strpos($module, 'help')!==0){ //防止无限尝试加载help
            try{
                loadModule('help.'.$module); //尝试加载help
            }catch(\Exception $e){
                if(!fromGroup()){
                    throw $e;
                }
            }
        }else{
            leave('没有该命令：'.substr($module, 5));
        }
    }
}

/**
 * 解析命令
 * @param string $str 命令字符串
 * @return mixed array|bool 解析结果数组 失败返回false
 */
function parseCommand(string $str){
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
        throw new UnauthorizedException();
    }
}

function nextArg(){
    global $Command;
    static $index=0;

    return $Command[$index++];
}

/**
 * 冷却
 * 不指定冷却时间时将返回与冷却完成时间的距离，大于0表示已经冷却完成
 * @param string $name 冷却文件名称，对指定用户冷却需带上Q号
 * @param int $time 冷却时间
 */
function coolDown(string $name, $time = NULL):int{
    global $Event;
    if(NULL === $time){
        clearstatcache();
        return time() - filemtime("../storage/data/coolDown/{$name}")-(int)getData("coolDown/{$name}");
    }else{
        setData("coolDown/{$name}", $time);
        return -$time;
    }
}

/**
 * 消息是否来自(指定)群
 * 指定参数时将判定是否来自该群
 * 不指定时将判定是否来自群聊
 * @param mixed $group=NULL 群号
 * @return bool
 */
function fromGroup($group = NULL):bool{
    global $Event;
    if($group == NULL){
        return isset($Event['group_id']);
    }else{
        return ($Event['group_id'] == $group);
    }
}

/**
 * 退出模块
 * @param string $msg 返回信息
 * @param int $code 指定返回码
 * @throws Exception 用于退出模块
 */
function leave($msg = '', $code = 0){
    throw new \Exception($msg, $code);
}

/**
 * 检查是否在黑名单中
 * @return bool
 */
function inBlackList($qq):bool{
    $blackList = getData('black.txt');
    if($blackList === false)leave('无法打开黑名单');
    if(strpos($blackList, ''.$qq) !== false){
        return true;
    }else{
        return false;
    }
}

function block($qq){
    if(inBlackList($qq))throw new UnauthorizedException();
}
