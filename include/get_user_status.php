<?
ini_set('date.timezone','Asia/Taipei');
function getValidClient ($GameAc) { // -1:未知用戶 0:不是用戶 1:次數用戶(多於50) 2:日數用戶 3:無限用戶 4:次數用戶(不足50) 5:到期後兩天內
	global $db;
	
	$query = $db->query("SELECT * FROM kmx_usera WHERE LOWER(`name`)='".strtolower($GameAc)."'");
	$count_query = $db->num_rows($query);
	
	if ( $count_query ) {
		$ClientData = $db->fetch_array($query);
		$ClientType = $ClientData['type'];
		
		if ( $ClientType == 0 && $ClientData['atimes'] > 20 ) return 1;
		elseif ( $ClientType == 1 && strtotime($ClientData['timeend']) > strtotime(date("Y-m-d")) ) return 2;
		elseif ( $ClientType == 2 ) return 3;
		elseif ( $ClientType == 0 && $ClientData['atimes'] < 20 && $ClientData['atimes'] >= 20 ) return 4;
		elseif ( $ClientType == 1 && strtotime($ClientData['timeend']) > strtotime("-1 day") ) return 5;
		return 0;
	}
	return -1;
}
function getRegVip ($GameAc, $UserID) { // 0:未知/無效VIP 1:有效VIP 2:該用戶申請的VIP
	global $db;
	
	$query = $db->query("SELECT * FROM tl_viplist WHERE LOWER(`gameid`) = '".strtolower($GameAc)."' && `status` = '1'"); //status
	$count_query = $db->num_rows($query);
	
	if ( $count_query ) {
		$ClientData = $db->fetch_array($query);
		if ( $ClientData['uid'] == $UserID ) return 2;
		return 1;
	}
	return 0;
}
?>