<?php
namespace kjBot\SDK;

class CoolQ{

    private $host;
    private $token;

    public function __construct($host = '127.0.0.1:5700', $token = ''){
        $this->host = $host;
        $this->token = $token;
    }

    public function sendPrivateMsg($user_id, $message, $auto_escape = false){
        $api = API::send_private_msg;
        $param = [
            'user_id' => $user_id,
            'message' => $message,
            'auto_escape' => $auto_escape,
            'is_raw' => $auto_escape,
        ];
        return $this->query($api, $param);
    }

    public function sendPrivateMsgAsync($user_id, $message, $auto_escape = false){
        $api = API::send_private_msg_async;
        $param = [
            'user_id' => $user_id,
            'message' => $message,
            'auto_escape' => $auto_escape,
            'is_raw' => $auto_escape,
        ];
        return $this->query($api, $param);
    }

    public function sendGroupMsg($group_id, $message, $auto_escape = false){
        $api = API::send_group_msg;
        $param = [
            'group_id' => $group_id,
            'message' => $message,
            'auto_escape' => $auto_escape,
            'is_raw' => $auto_escape,
        ];
        return $this->query($api, $param);
    }

    public function sendGroupMsgAsync($group_id, $message, $auto_escape = false){
        $api = API::send_group_msg_async;
        $param = [
            'group_id' => $group_id,
            'message' => $message,
            'auto_escape' => $auto_escape,
            'is_raw' => $auto_escape,
        ];
        return $this->query($api, $param);
    }

    public function sendDiscussMsg($discuss_id, $message, $auto_escape = false){
        $api = API::send_discuss_msg;
        $param = [
            'discuss_id' => $discuss_id,
            'message' => $message,
            'auto_escape' => $auto_escape,
            'is_raw' => $auto_escape,
        ];
        return $this->query($api, $param);
    }

    public function sendMsg($message_type, $id, $message, $auto_escape = false){
        $api = API::send_msg;
        $param = [
            'message_type' => $message_type,
            'user_id' => $id,
            'group_id' => $id,
            'discuss_id' => $id,
            'message' => $message,
            'auto_escape' => $auto_escape,
            'is_raw' => $auto_escape,
        ];
        return $this->query($api, $param);
    }

    public function sendMsgAsync($message_type, $id, $message, $auto_escape = false){
        $api = API::send_msg_async;
        $param = [
            'message_type' => $message_type,
            'user_id' => $id,
            'group_id' => $id,
            'discuss_id' => $id,
            'message' => $message,
            'auto_escape' => $auto_escape,
            'is_raw' => $auto_escape,
        ];
        return $this->query($api, $param);
    }

    public function deleteMsg($message_id){
        $api = API::delete_msg;
        $param = [
            'message_id' => $message_id,
        ];
        return $this->query($api, $param);
    }

    public function sendLike($user_id, $times = 1){
        $api = API::send_like;
        $param = [
            'user_id' => $user_id,
            'times' => $times,
        ];
        return $this->query($api, $param);
    }

    public function setGroupKick($group_id, $user_id, $reject_add_request = false){
        $api = API::set_group_kick;
        $param = [
            'group_id' => $group_id,
            'user_id' => $user_id,
            'reject_add_request' => $reject_add_request,
        ];
        return $this->query($api, $param);
    }

    public function setGroupBan($group_id, $user_id, $duration = 30 * 60){
        $api = API::set_group_ban;
        $param = [
            'group_id' => $group_id,
            'user_id' => $user_id,
            'duration' => $duration,
        ];
        return $this->query($api, $param);
    }

    public function setGroupAnonymousBan($group_id, $flag, $duration = 30 * 60){
        $api = API::set_group_anonymous_ban;
        $param = [
            'group_id' => $group_id,
            'flag' => $flag,
            'duration' => $duration,
        ];
        return $this->query($api, $param);
    }

    public function setGroupWholeBan($group_id, $enable = true){
        $api = API::set_group_whole_ban;
        $param = [
            'group_id' => $group_id,
            'enable' => $enable,
        ];
        return $this->query($api, $param);
    }

    public function setGroupAdmin($group_id, $user_id, $enable = true){
        $api = API::set_group_admin;
        $param = [
            'group_id' => $group_id,
            'user_id' => $user_id,
            'enable' => $enable,
        ];
        return $this->query($api, $param);
    }

    public function setGroupAnonymous($group_id, $enable = true){
        $api = API::set_group_anonymous;
        $param = [
            'group_id' => $group_id,
            'enable' => $enable,
        ];
        return $this->query($api, $param);
    }

    public function setGroupCard($group_id, $user_id, $card = null){
        $api = API::set_group_card;
        $param = [
            'group_id' => $group_id,
            'user_id' => $user_id,
            'card' => $card,
        ];
        return $this->query($api, $param);
    }

    public function setGroupLeave($group_id, $is_dismiss = false){
        $api = API::set_group_leave;
        $param = [
            'group_id' => $group_id,
            'is_dismiss' => $is_dismiss,
        ];
        return $this->query($api, $param);
    }

    public function setGroupSpecialTitle($group_id, $user_id, $special_title = null, $duration = -1){
        $api = API::set_group_special_title;
        $param = [
            'group_id' => $group_id,
            'user_id' => $user_id,
            'special_title' => $special_title,
            'duration' => $duration,
        ];
        return $this->query($api, $param);
    }

    public function setDiscussLeave($discuss_id){
        $api = API::set_discuss_leave;
        $param = [
            'discuss_id' => $discuss_id,
        ];
        return $this->query($api, $param);
    }

    public function setFriendAddRequest($flag, $approve = true, $remark = ''){
        $api = API::set_friend_add_request;
        $param = [
            'flag' => $flag,
            'approve' => $approve,
            'remark' => $remark,
        ];
        return $this->query($api, $param);
    }

    public function setGroupAddRequest($flag, $type, $approve = true, $reason = ''){
        $api = API::set_group_add_request;
        $param = [
            'flag' => $flag,
            'type' => $type,
            'approve' => $approve,
            'reason' => $reason,
        ];
        return $this->query($api, $param);
    }

    public function getLoginInfo(){
        $api = API::get_login_info;
        $param = [];
        return $this->query($api, $param);
    }

    public function getStrangerInfo($user_id, $no_cache = false){
        $api = API::get_stranger_info;
        $param = [
            'user_id' => $user_id,
            'no_cache' => $no_cache,
        ];
        return $this->query($api, $param);
    }

    public function getGroupList(){
        $api = API::get_group_list;
        $param = [];
        return $this->query($api, $param);
    }

    public function getGroupMemberInfo($group_id, $user_id, $no_cache = false){
        $api = API::get_group_member_info;
        $param = [
            'group_id' => $group_id,
            'user_id' => $user_id,
            'no_cache' => $no_cache,
        ];
        return $this->query($api, $param);
    }

    public function getGroupMemberList($group_id){
        $api = API::get_group_member_list;
        $param = [
            'group_id' => $group_id,
        ];
        return $this->query($api, $param);
    }

    public function getCookies(){
        $api = API::get_cookies;
        $param = [];
        return $this->query($api, $param);
    }
    
    public function getCsrfToken(){
        $api = API::get_csrf_token;
        $param = [];
        return $this->query($api, $param);
    }

    public function getCredentials(){
        $api = API::get_credentials;
        $param = [];
        return $this->query($api, $param);
    }

    public function getRecord($file, $out_format){
        $api = API::get_record;
        $param = [
            'file' => $file,
            'out_format' => $out_format,
        ];
        return $this->query($api, $param);
    }

    public function getStatus(){
        $api = API::get_status;
        $param = [];
        return $this->query($api, $param);
    }

    public function getVersionInfo(){
        $api = API::get_version_info;
        $param = [];
        return $this->query($api, $param);
    }

    public function setRestart($clean_cache = false){
        $api = API::set_restart;
        $param = [
            'clean_cache' => $clean_cache,
        ];
        return $this->query($api, $param);
    }

    public function setRestartPlugin($delay = 0){
        $api = API::set_restart_plugin;
        $param = [
            'delay' => $delay,
        ];
        return $this->query($api, $param);
    }

    public function cleanDataDir($data_dir){
        $api = API::clean_data_dir;
        $param = [
            'data_dir' => $data_dir,
        ];
        return $this->query($api, $param);
    }

    public function cleanPluginLog(){
        $api = API::clean_plugin_log;
        $param = [];
        return $this->query($api, $param);
    }

    public function _getFriendList(){
        $api = API::_get_friend_list;
        $param = [];
        return $this->query($api, $param);
    }

    public function _getGroupInfo($flat = false){
        $api = API::_get_group_info;
        $param = [
            'flat' => $flat,
        ];
        return $this->query($api, $param);
    }

    public function __checkUpdate($automatic){
        $api = API::__check_update;
        $param = [
            'automatic' => $automatic,
        ];
        return $this->query($api, $param);
    }

    public function __handleQuickOperation($context, $operation){
        $api = API::__handle_quick_operation;
        $param = [
            'context' => $context,
            'operation' => $operation,
        ];
        return $this->query($api, $param);
    }

    private function query($api, $param){
        $queryStr = '?';
        $param['access_token'] = $this->token; //追加access_token到参数表
        foreach($param as $key => $value){
            $queryStr.= ($key.'='.urlencode(is_bool($value)?((int)$value):$value).'&');
        }
        $result = json_decode(file_get_contents('http://'.$this->host.$api.$queryStr));

        switch($result->retcode){
            case 0:
                return $result->data;
            case 1:
                return NULL;
            default:
                throw new \Exception("Query Failed", $result['retcode']);
        }
    }

}