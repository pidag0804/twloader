<?
if ( is_null( $IS_USER_LOGIN ) ) exit ("Site Error");

function call_error($string) {
	$_GET['msg'] = $string;
	require_once("c_error.php");
}

if ( $_GET['page'] == "register" && !empty($site_config['site']['close_register']) ) call_error($site_config['site']['close_register']);
if ( $User['group'] != 1 && $_GET['page'] == "apply" && !empty($site_config['site']['close_pay']) ) call_error($site_config['site']['close_register']);

switch ( $_GET['pms'] ) {
	case PMS_GUEST:
		if ($User['num'] != 0) call_error("沒有權限");
		break;
	case PMS_ALL:
		break;
	case PMS_MEMBER:
		if ($User['num'] == 0) call_error("沒有權限");
		break;
	case PMS_VIP:
		if ($User['group'] != 1 && $User['group'] != 6) call_error("沒有權限");
		break;
	case PMS_ADMIN:
		if ($User['group'] != 1) call_error("沒有權限");
		break;

}

?>