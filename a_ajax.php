<?php
session_start();
include_once("include/class_mysql.php");
include_once("include/user_data.php"); 
include_once("include/get_user_status.php");

$dataFormat = "/^[\d|a-zA-Z]{4,30}$/";

switch ($_GET['act']) {
	
	case "login":
		$gAc = mysql_real_escape_string ($_GET['ac']);
		$gPw = mysql_real_escape_string ($_GET['pw']);
		
		if ( preg_match($dataFormat, $gAc) && preg_match($dataFormat, $gPw) ) {
			$query = $db->query("SELECT * FROM tlpw WHERE `usname` = '".$gAc."'");
			$count_query = $db->num_rows($query);
			if ( $count_query ) {
				$tmpUser = $db->fetch_array($query);
				if ( $tmpUser['uspw'] == $gPw ) {
					$db->query("UPDATE tlpw SET `ip` = '".$USER_IP."' WHERE `num` = ".$tmpUser['num']);
					$_SESSION['user_key'] = md5(base64_encode($gAc.$tmpUser['num'].$gPw."tw_loader"));
					$_SESSION['user_ac'] = $gAc;
					$_SESSION['user_pw'] = $gPw;
					$_SESSION['user_id'] = $tmpUser['num'];
					exit ("登入成功<script>setTimeout('window.location.reload()',500)</script>");
				}
			}
		}
		exit ("登入失敗");
        break;

	case "regcheck":
		$gAc = mysql_real_escape_string ($_GET['ac']);
		$gPw = mysql_real_escape_string ($_GET['pw']);
		$gNn = mysql_real_escape_string ($_GET['nn']);
		$gEm = mysql_real_escape_string (urldecode($_GET['em']));

		if ( preg_match($dataFormat, $gAc) && preg_match($dataFormat, $gPw) && preg_match("/^([\w.]+)@([\w.]+)/", $gEm) ) {
			$query = $db->query("SELECT * FROM tlpw WHERE `usem` LIKE '".$gEm."'");
			$em_count = $db->num_rows($query);
			if ( $em_count )
				exit ("電郵地址已被使用");
			
			$query = $db->query("SELECT * FROM tlpw WHERE `usname` LIKE '".$gAc."'");
			$ac_count = $db->num_rows($query);
			if ( $ac_count )
				exit ("帳戶名稱已被使用");
			
			$query = $db->query("SELECT * FROM tlpw WHERE `cnname` LIKE '".$gNn."'");
			$nn_count = $db->num_rows($query);
			if ( $nn_count )
				exit ("暱稱已被使用");
			
		} else {
			exit ("格式錯誤");
		}
		exit ("載入中<script>document.getElementById('sub').click();</script>");
        break;
		
	case "chpass":
		$gOpw = mysql_real_escape_string ($_GET['opw']);
		$gNpw = mysql_real_escape_string ($_GET['npw']);
			
		if ( preg_match($dataFormat, $gOpw) && preg_match($dataFormat, $gNpw) ) {
			if ( $gOpw == $User['uspw'] && $User['num'] > 0 ) {
				$db->query("UPDATE tlpw SET `uspw` = '".$gNpw."' WHERE `num` = ".$User['num']);
				$_SESSION['user_pw'] = $gNpw;
				exit ("修改成功<script>document.location.href='index.php?page=home';</script>");
			} else {
				exit ("舊密碼錯誤");
			}
		}
		exit ("修改失敗");
        break;
		
case "add_vip":
    $gid = mysql_real_escape_string($_GET['gid']);
    $gName = strtolower($gid);
    $clientValid = getValidClient($gName);

    // 檢查 VIP 帳號數量限制
    if ($User_VipCount >= 6) exit($MSG_ADDVIP[4]); // 多於 6 個帳戶

    // 檢查其他條件
    if ($clientValid == 4) exit($MSG_ADDVIP[5]); // 不足 50 次
    if ($clientValid == 5) exit($MSG_ADDVIP[3]); // 到期後兩天

    if ($clientValid >= 1) {
        $isClientVIP = getRegVip($gName, $User['num']);
        if ($isClientVIP == 0) {
            $query = $db->query("SELECT * FROM tl_viplist WHERE LOWER(`gameid`) = '".$gName."' && `uid` = '".$User['num']."'");
            $count_query = $db->num_rows($query);
            if ($count_query) {
                $db->query("UPDATE tl_viplist SET `status` = 1, `datetime` = '".$NOW_DATETIME."' WHERE LOWER(`gameid`) = '".$gName."' && `uid` = '".$User['num']."'");
            } else {
                $db->query("INSERT INTO tl_viplist (`uid`, `gameid`, `status`, `datetime`) VALUES ('".$User['num']."', '".$gid."', 1 ,'".$NOW_DATETIME."')");
            }
        }
        exit($MSG_ADDVIP[$isClientVIP]);
    } else {
        exit($MSG_ADDVIP[3]);
    }
    break;
		
	case "del_vip":
		$gName = strtolower(mysql_real_escape_string($_GET['gid']));
		if ( ( getValidClient($gName) <= 0 || getValidClient($gName) == 5 )&& getRegVip($gName, $User['num']) == 2 ) {
			$db->query("UPDATE tl_viplist SET `status` = 0, `datetime` = '".$NOW_DATETIME."' WHERE LOWER(`gameid`) = '".$gName."' && `uid` = '".$User['num']."'");
			exit ($MSG_DELVIP[0]);
		} else exit ($MSG_DELVIP[1]);
        break;
}

//echo "<script>document.location.href='http://google.com';</script
//<script>window.location.reload();</script
?>