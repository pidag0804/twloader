<?php
// --- START: 開啟PHP詳細錯誤報告 (方便除錯) ---
// 當網站正式上線後，建議在程式碼最前面加上 // 來註解掉或移除這兩行
error_reporting(E_ALL);
ini_set('display_errors', 1);
// --- END: 開啟PHP詳細錯誤報告 ---

session_start();

// 引入 PHPMailer 核心檔案
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- START: 已修正的 PHPMailer 路徑 ---
// 根據您提供的路徑 "twloader/include/PHPMailer" 進行修正
// 假設 PHPMailer 的 src 資料夾在 include/PHPMailer/ 底下
require 'include/PHPMailer/src/Exception.php';
require 'include/PHPMailer/src/PHPMailer.php';
require 'include/PHPMailer/src/SMTP.php';
// --- END: 已修正的 PHPMailer 路徑 ---

include_once("include/class_mysql.php");
include_once("include/user_data.php"); 
include_once("include/get_user_status.php");

$dataFormat = "/^[\d|a-zA-Z]{4,30}$/";

switch ($_GET['act']) {
    
    case "login":
        $gAc = $db->real_escape_string($_GET['ac']);
        $gPw = $db->real_escape_string($_GET['pw']);
        
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
        $gAc = $db->real_escape_string($_GET['ac']);
        $gPw = $db->real_escape_string($_GET['pw']);
        $gNn = $db->real_escape_string($_GET['nn']);
        $gEm = $db->real_escape_string(urldecode($_GET['em']));

        if ( preg_match($dataFormat, $gAc) && preg_match($dataFormat, $gPw) && filter_var($gEm, FILTER_VALIDATE_EMAIL) ) {
            $query = $db->query("SELECT * FROM tlpw WHERE `usem` LIKE '".$gEm."'");
            if ( $db->num_rows($query) )
                exit ("電郵地址已被使用");
            
            $query = $db->query("SELECT * FROM tlpw WHERE `usname` LIKE '".$gAc."'");
            if ( $db->num_rows($query) )
                exit ("帳戶名稱已被使用");
            
            $query = $db->query("SELECT * FROM tlpw WHERE `cnname` LIKE '".$gNn."'");
            if ( $db->num_rows($query) )
                exit ("暱稱已被使用");
            
        } else {
            exit ("格式錯誤");
        }
        exit ("載入中<script>document.getElementById('sub').click();</script>");
        break;
        
    case "chpass":
        $gOpw = $db->real_escape_string($_GET['opw']);
        $gNpw = $db->real_escape_string($_GET['npw']);
            
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
        $gid = $db->real_escape_string($_GET['gid']);
        $gName = strtolower($gid);

        $check_query = $db->query("SELECT `uid` FROM tl_viplist WHERE LOWER(`gameid`) = '".$gName."' AND `status` = 1");
        if ($db->num_rows($check_query) > 0) {
            $binding = $db->fetch_array($check_query);
            if ($binding['uid'] != $User['num']) {
                exit("此帳號已被其他用戶綁定，無法新增。");
            }
        }

        $clientValid = getValidClient($gName);

        if ($User_VipCount >= 6) exit($MSG_ADDVIP[4]);
        if ($clientValid == 4) exit($MSG_ADDVIP[5]);
        if ($clientValid == 5) exit($MSG_ADDVIP[3]);

        if ($clientValid >= 1) {
            $isClientVIP = getRegVip($gName, $User['num']);
            if ($isClientVIP == 0) {
                $query = $db->query("SELECT * FROM tl_viplist WHERE LOWER(`gameid`) = '".$gName."' AND `uid` = '".$User['num']."'");
                if ($db->num_rows($query)) {
                    $db->query("UPDATE tl_viplist SET `status` = 1, `datetime` = '".$NOW_DATETIME."' WHERE LOWER(`gameid`) = '".$gName."' AND `uid` = '".$User['num']."'");
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
        $gName = strtolower($db->real_escape_string($_GET['gid']));
        if ( getRegVip($gName, $User['num']) == 2 ) {
            $db->query("DELETE FROM tl_viplist WHERE LOWER(`gameid`) = '".$gName."' AND `uid` = '".$User['num']."'");
            exit("解除成功！<script>setTimeout('window.location.reload()', 500);</script>");
        } else {
            exit($MSG_DELVIP[1]);
        }
        break;
        
    case "lostpw":
        $gEm = $db->real_escape_string(urldecode($_GET['em']));
        
        if (filter_var($gEm, FILTER_VALIDATE_EMAIL)) {
            $query = $db->query("SELECT `usname`, `uspw` FROM tlpw WHERE `usem` = '".$gEm."'");
            
            if ($db->num_rows($query) > 0) {
                $lostUser = $db->fetch_array($query);
                $usname = $lostUser['usname'];
                $uspw = $lostUser['uspw'];
                
                $mail = new PHPMailer(true);

                try {
                    // --- SMTP 伺服器設定 ---
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'twmodloader@gmail.com';
                    $mail->Password   = 'rwegysyulbpgppqi';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;
                    $mail->CharSet    = 'UTF-8';

                    // --- 寄件人與收件人 ---
                    $mail->setFrom('twmodloader@gmail.com', 'TwLoader 服務中心');
                    $mail->addAddress($gEm);

                    // --- 郵件內容 ---
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
                    // --- 已增強的錯誤回報 ---
                    exit("郵件發送失敗，請聯絡管理員。錯誤訊息: {$mail->ErrorInfo}");
                }
                
            } else {
                exit("找不到與此信箱關聯的帳戶，請確認您輸入的信箱是否正確。");
            }
        } else {
            exit("您輸入的電子信箱格式不正確。");
        }
        break;
}
?>