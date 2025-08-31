<?
ini_set('date.timezone','Asia/Taipei');
if ( empty($config) ) {
	$ini_path = "include/service_config.ini";
	$config = parse_ini_file($ini_path, true);
}
$query = $db->query("SELECT * FROM tl_viplist WHERE `status` = 1");
$User_VipCount = $db->num_rows($query);

//$fp = fopen("log/LOG_".date("YmdHis").".txt", "w");

//if($fp) {
	//fwrite($fp,"TwLoader Auto Set VIP System v1.0 Work Log\r\n");
	//fwrite($fp,"==========================================\r\n");
	
	for($i=0; $i < $User_VipCount; $i++) {
		$VipData = $db->fetch_array($query);
		$clientValid = getValidClient($VipData['gameid']);
		if ( $clientValid <= 0 ) {
			//要$db->query("UPDATE tl_viplist SET `status` = 0, `datetime` = '".$NOW_DATETIME."' WHERE `gameid` = '".$gName."' && `uid` = '".$User['num']."'");
			//fwrite($fp,"SET ".$VipData['gameid']." FROM VIP TO 0\r\n");
		} /*elseif ( $clientValid == 5 ) { //過期2日內
			$db->query("UPDATE tl_viplist SET `status` = 3, `datetime` = '".$NOW_DATETIME."' WHERE `gameid` = '".$gName."' && `uid` = '".$User['num']."'");
			fwrite($fp,"SET ".$VipData['gameid']." FROM VIP TO 4\r\n");
		} elseif ( $clientValid == 4 ) { //不足50次
			$db->query("UPDATE tl_viplist SET `status` = 3, `datetime` = '".$NOW_DATETIME."' WHERE `gameid` = '".$gName."' && `uid` = '".$User['num']."'");
			fwrite($fp,"SET ".$VipData['gameid']." FROM VIP TO 3\r\n");
		}*/
	}
	//fwrite($fp,"================= END LOG ================\r\n");
	//fclose($fp);
	
	$config['refresh_vip']['last_time'] = $NOW_DATETIME;
	write_php_ini($config, $ini_path);
//}
?>