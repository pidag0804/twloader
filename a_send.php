<?
ini_set('date.timezone','Asia/Taipei');
include_once("include/class_mysql.php");
include_once("include/user_data.php");

$_GET['pms'] = PMS_MEMBER;
require_once("include/content_head.php");

$get_mid = intval($_GET['mid']);

if ( $User['group'] != 1 && $get_mid > 0 && $get_mid != 2 && $get_mid != 3 ) {
	$_GET['msg'] = "沒有權限";
	require_once("c_error.php");
}

if ( $_GET['act'] == "send" ){
	if ( $User['group'] != 1 )
	{
		$query = $db->query("SELECT * FROM tl_message WHERE `fromuser` = '".$User['num']."' && `unread` = 1");
		$count_query = $db->num_rows($query);
		if ( $count_query >= 10 )
			exit ("發送錯誤");
	}
	$Message = $_POST['message'];
	$db->query("INSERT INTO tl_message (`touser`,`fromuser`,`content`,`time`,`unread`) VALUES 
	           ('".$get_mid."','".$User['num']."', '".$Message."' ,'".$NOW_DATETIME."', 1)");
	exit ("成功發送:<br />".$Message);
}
?>