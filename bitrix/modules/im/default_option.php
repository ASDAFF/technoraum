<?php
$im_default_option = array(
	'general_chat_id' => 0,
	'general_chat_message_join' => true,
	'general_chat_message_leave' => false,
	'allow_send_to_general_chat_all' => 'Y',
	'allow_send_to_general_chat_rights' => 'AU',
	'call_server' => 'N',
	'turn_server_self' => 'N',
	'turn_server' => 'turn.calls.bitrix24.com',
	'turn_server_firefox' => '54.217.240.163',
	'turn_server_login' => 'bitrix',
	'turn_server_password' => 'bitrix',
	'open_chat_enable' => IsModuleInstalled('intranet')? true: false,
	'color_enable' => true,
	'correct_text' => false,
	'view_offline' => true,
	'view_group' => true,
	'send_by_enter' => true,
	'panel_position_horizontal' => 'right',
	'panel_position_vertical' => 'bottom',
	'load_last_message' => true,
	'load_last_notify' => true,
	'privacy_message' => 'all',
	'privacy_chat' => IsModuleInstalled('intranet')? 'all': 'contact',
	'privacy_call' => IsModuleInstalled('intranet')? 'all': 'contact',
	'start_chat_message' => IsModuleInstalled('intranet')? 'last': 'first',
	'privacy_search' => 'all',
	'privacy_profile' => 'all',
	'chat_extend_show_history' => true,
	'disk_storage_id' => 0,
	'disk_folder_avatar_id' => 0,
	'contact_list_load' => true,
	'contact_list_show_all_bus' => false,
	'path_to_user_profile' => (!IsModuleInstalled("intranet") ? '/club/user/#user_id#/' : ''),
);
?>