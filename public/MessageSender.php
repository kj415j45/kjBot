<?php
namespace kjBot\Frame;

class MessageSender{
    
    public $CQ;
    
    /**
     * @param kjBot\SDK\CoolQ $CoolQ 一个酷Q实例
     */
    function __construct(\kjBot\SDK\CoolQ $CoolQ){
        $this->CQ = $CoolQ;
    }

    /**
     * 发送一条消息
     * @param kjBot\Frame\Message $message
     */
    function send(Message $message){
        if($message->toGroup){
            if($message->async){
                $this->CQ->sendGroupMsgAsync($message->id, $message->msg, $message->auto_escape);
            }else{
                $this->CQ->sendGroupMsg($message->id, $message->msg, $message->auto_escape);
            }
        }else{
            if($message->async){
                $this->CQ->sendPrivateMsgAsync($message->id, $message->msg, $message->auto_escape);
            }else{
                $this->CQ->sendPrivateMsg($message->id, $message->msg, $message->auto_escape);
            }
        }
    }

}
