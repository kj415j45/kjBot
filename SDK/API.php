<?php
namespace kjBot\SDK;

class API{
    const send_private_msg = '/send_private_msg';
    const send_private_msg_async = '/send_private_msg_async';
    const send_group_msg = '/send_group_msg';
    const send_group_msg_async = '/send_group_msg_async';
    const send_discuss_msg = '/send_discuss_msg';
    const send_discuss_msg_async = '/send_discuss_msg_async';
    const send_msg = '/send_msg';
    const send_msg_async = '/send_msg_async';
    const delete_msg = '/delete_msg';
    const send_like = '/send_like';
    const set_group_kick = '/set_group_kick';
    const set_group_ban = '/set_group_ban';
    const set_group_anonymous_ban = '/set_group_anonymous_ban';
    const set_group_whole_ban = '/set_group_whole_ban';
    const set_group_admin = '/set_group_admin';
    const set_group_anonymous = '/set_group_anonymous';
    const set_group_card = '/set_group_card';
    const set_group_leave = '/set_group_leave';
    const set_group_special_title = '/set_group_special_title';
    const set_discuss_leave = '/set_discuss_leave';
    const set_friend_add_request = '/set_friend_add_request';
    const set_group_add_request = '/set_group_add_request';
    const get_login_info = '/get_login_info';
    const get_stranger_info = '/get_stranger_info';
    const get_group_list = '/get_group_list';
    const get_group_member_info = '/get_group_member_info';
    const get_group_member_list = '/get_group_member_list';
    const get_cookies = '/get_cookies';
    const get_csrf_token = '/get_csrf_token';
    const get_credentials = '/get_credentials';
    const get_record = '/get_record';
    const get_status = '/get_status';
    const get_version_info = '/get_version_info';
    const set_restart = '/set_restart';
    const set_restart_plugin = '/set_restart_plugin';
    const clean_data_dir = '/clean_data_dir';
    const clean_plugin_log = '/clean_plugin_log';
    const _get_friend_list = '/_get_friend_list';
    const _get_group_info = '/_get_group_info';
    const __check_update = '/.check_update';
    const __handle_quick_operation = '/.handle_quick_operation';
}
