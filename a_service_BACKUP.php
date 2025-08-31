<?
include_once("include/class_mysql.php");
//include_once("include/user_data.php");
include_once("include/get_user_status.php");

ignore_user_abort(TRUE);
set_time_limit(0);

while (TRUE)
{
	if (file_exists('log/lock.txt'))
		break;
	
	$query = $db->query("SELECT * FROM tl_viplist WHERE `status` = 1");
	$User_VipCount = $db->num_rows($query);
	
	$fp = fopen("log/LOG_".date("YmdHis").".txt", "w");
	
	if($fp) {
		fwrite($fp,"TwLoader Auto Set VIP System v1.0 Work Log\r\n");
		fwrite($fp,"==========================================\r\n");
		
		for($i=0; $i < $User_VipCount; $i++) {
			$VipData = $db->fetch_array($query);
			$clientValid = getValidClient($VipData['gameid']);
			if ( $clientValid <= 0 ) {
				$db->query("UPDATE tl_viplist SET `status` = 0, `datetime` = '".$NOW_DATETIME."' WHERE `gameid` = '".$gName."' && `uid` = '".$User['num']."'");
				fwrite($fp,"SET ".$VipData['gameid']." FROM VIP TO 0\r\n");
			}/*elseif ( $clientValid == 5 ) { //過期2日內
				$db->query("UPDATE tl_viplist SET `status` = 3, `datetime` = '".$NOW_DATETIME."' WHERE `gameid` = '".$gName."' && `uid` = '".$User['num']."'");
				fwrite($fp,"SET ".$VipData['gameid']." FROM VIP TO 4\r\n");
			} elseif ( $clientValid == 4 ) { //不足30次
				$db->query("UPDATE tl_viplist SET `status` = 3, `datetime` = '".$NOW_DATETIME."' WHERE `gameid` = '".$gName."' && `uid` = '".$User['num']."'");
				fwrite($fp,"SET ".$VipData['gameid']." FROM VIP TO 3\r\n");
			}*/
		}
		fwrite($fp,"================= END LOG ================\r\n");
	}
	fclose($fp);
	
	sleep(12 * 60 * 60);
}
?>