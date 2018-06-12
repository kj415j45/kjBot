<?php
namespace kjBot\Frame;

class Message{

    public $msg;
    public $id;
    public $toGroup;
    public $auto_escape;
    public $async;

    /**
     * @param $msg 消息内容
     * @param $id QQ(群)号
     * @param $toGroup true->发到群里(默认值), false->发私聊
     * @param $auto_escape 是否发送纯文本（默认解析CQ码）
     * @param $async 是否异步发送（默认不异步）
     */
    function __construct($msg, $id, $toGroup = true, $auto_escape = false, $async = false){
        $this->msg = $msg;
        $this->id = $id;
        $this->toGroup = $toGroup;
        $this->auto_escape = $auto_escape;
        $this->async = $async;
    }

}
