<?php
session_start();

// 引入 PHPMailer 核心檔案 (如果 lostpw 功能已加入)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'include/PHPMailer/src/Exception.php';
require 'include/PHPMailer/src/PHPMailer.php';
require 'include/PHPMailer/src/SMTP.php';

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

        if ($User_VipCount >= 6) exit($MSG_ADDVIP[4]);
        if ($clientValid == 4) exit($MSG_ADDVIP[5]);
        if ($clientValid == 5) exit($MSG_ADDVIP[3]);

        if ($clientValid >= 1) {
            $isClientVIP = getRegVip($gName, $User['num']);
            if ($isClientVIP == 0) {
                $query = $db->query("SELECT * FROM tl_viplist WHERE LOWER(`gameid`) = '".$gName."' && `uid` = '".$User['num']."'");
                if ($db->num_rows($query)) {
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
		if ( getRegVip($gName, $User['num']) == 2 ) {
			// *** 修改點：將 UPDATE 改為 DELETE ***
			// 舊的寫法: $db->query("UPDATE tl_viplist SET `status` = 0, ... ");
			$db->query("DELETE FROM tl_viplist WHERE LOWER(`gameid`) = '".$gName."' && `uid` = '".$User['num']."'");
			
			// 為了讓前端能即時反應，回傳一個會重新整理頁面的訊息
			exit("解除成功！<script>setTimeout('window.location.reload()', 500);</script>");
		} else {
			exit($MSG_DELVIP[1]);
		}
        break;
        
    case "lostpw":
        $gEm = mysql_real_escape_string(urldecode($_GET['em']));
        
        if (filter_var($gEm, FILTER_VALIDATE_EMAIL)) {
            $query = $db->query("SELECT `usname`, `uspw` FROM tlpw WHERE `usem` = '".$gEm."'");
            
            if ($db->num_rows($query) > 0) {
                $lostUser = $db->fetch_array($query);
                $usname = $lostUser['usname'];
                $uspw = $lostUser['uspw'];
                
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    
                    $mail->Username   = 'your_gmail_account@gmail.com'; // 請填寫您完整的 Gmail 帳號
                    $mail->Password   = 'xxxxxxxxxxxxxxxx';             // 請填寫您的 16 位應用程式密碼
                    
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;
                    $mail->CharSet    = 'UTF-8';

                    $mail->setFrom('your_gmail_account@gmail.com', 'TwLoader 服務中心'); // 這裡也請填寫您的 Gmail 帳號
                    $mail->addAddress($gEm);

                    $mail->isHTML(false);
                    $mail->Subject = 'TwLoader 帳號密碼查詢';
                    
                    $message = "您好：\r\n\r\n";
                    $message .= "您在 TwLoader 的帳號資料如下：\r\n\r\n";
                    $message .= "帳號： " . $usname . "\r\n";
                    $message .= "密碼： " . $uspw . "\r\n\r\n";
                    $message .= "請妥善保管您的資料，並建議登入後立即變更密碼。\r\n\r\n";
                    $message .= "TwLoader 官方網站";

                    $mail->Body = $message;

                    $mail->send();
                    exit("帳號資料已成功寄送到您的信箱，請檢查您的收件匣（也可能在垃圾郵件中）。");
                } catch (Exception $e) {
                    exit("郵件發送失敗，請聯絡管理員。");
                }
                
            } else {
                exit("找不到與此信箱關聯的帳戶，請確認您輸入的信箱是否正確。");
            }
        } else {
            exit("您輸入的電子信箱格式不正確。");
        }
        break;
}

//echo "<script>document.location.href='http://google.com';</script
//<script>window.location.reload();</script
?>