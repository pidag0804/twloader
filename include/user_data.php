<?
ini_set('date.timezone','Asia/Taipei');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$ld_ac = isset($_SESSION['user_ac']) ? $_SESSION['user_ac'] : '';
$ld_pw = isset($_SESSION['user_pw']) ? $_SESSION['user_pw'] : '';
$ld_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

$IS_USER_LOGIN = 0;
$HaveUnread = 0; // *** 修改點：在這裡為 $HaveUnread 設定初始值 ***

if (isset($_SESSION['user_key']) && $_SESSION['user_key'] == md5(base64_encode($ld_ac.$ld_id.$ld_pw."tw_loader")) && $ld_id > 0 ) {
  $query = $db->query("SELECT * FROM tlpw WHERE `num` = ".$ld_id);
  $count_query = $db->num_rows($query);
  if ( $count_query ) {
	  $User = $db->fetch_array($query);
  
	  $query = $db->query("SELECT * FROM tl_message WHERE `touser` = '".$User['num']."' && `unread` = 1");
	  $count_query = $db->num_rows($query);
	  if ( $count_query > 0 )
		  $HaveUnread = $count_query; // 如果有未讀訊息，這裡會更新數值
	  
	  $query = $db->query("SELECT * FROM tl_viplist WHERE `uid` = ".$User['num']." && `status` = 1 ORDER BY `datetime`");
	  $User_VipCount = $db->num_rows($query);
	  
	  for($i=0; $i < 6; $i++) $Client[$i]['type'] = 5; // 修正為 6 個欄位
	  
	  $vip_list_result = $db->query("SELECT * FROM tl_viplist WHERE `uid` = ".$User['num']." && `status` = 1 ORDER BY `datetime`");
      $i = 0;
      while($VipData = $db->fetch_array($vip_list_result)) {
          if ($i < 6) {
              $client_query = $db->query("SELECT * FROM kmx_usera WHERE UPPER(`name`) = '".strtoupper($VipData['gameid'])."'");
              if($db->num_rows($client_query) > 0) {
                $Client[$i] = $db->fetch_array($client_query);
              }
              $i++;
          }
      }
	  
	  if ( $User['group'] == 6 && $User_VipCount == 0) $User['group'] = 0;
	  if ( $User['group'] == 0 && $User_VipCount > 0) $User['group'] = 6;
	  if ( $User['block'] > 0 ) $User['group'] = 2;
	  $IS_USER_LOGIN = 1;
  }

  $UserType = array('剩餘次數: ', 'icon-user', '次數型',
					'到期日: ', 'icon-user', '包月型',
					'有效日：榮譽無限期會員', 'icon-user', '無限',
					'停權用戶', 'icon-cancel-1', 'BAN',
					'已到期', 'icon-pause', 'DUE',
					'', 'icon-user-add', 'VIP'
					);
  
  $UnknowName = "<a href='#dlg_Add' name='modal'>--> 新增綁定帳號 <--</a>";
  $UserGroup = array('普通會員', '管理員', '停權會員', '?', '?', '?', 'VIP 會員', '?');

  $MSG_ADDVIP = array('成功<script>setTimeout(\'window.location.reload()\',1000)</script>', 
					  '失敗: 此帳戶已被綁定', 
					  '失敗: 您已綁定此帳號',
					  '失敗: 此帳戶未購買開通',
					  '失敗: 超過最大綁定限額',
					  '失敗: 可用次數不足20');
					  
  $MSG_DELVIP = array('成功解除綁定', 
					  '解除失敗，請刷新頁面');
}

$NOW_DATE = date("Y-m-d");
$NOW_DATETIME = date("Y-m-d H:i:s");

$USER_IP = '';
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $USER_IP = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $USER_IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $USER_IP = $_SERVER['REMOTE_ADDR'];
}

$site_ini_path = __DIR__ . "/site_config.ini";
$site_config = file_exists($site_ini_path) ? parse_ini_file($site_ini_path, true) : [];

define("PMS_ALL", 1);
define("PMS_GUEST", 2);
define("PMS_MEMBER", 3);
define("PMS_VIP", 4);
define("PMS_ADMIN", 5);

?>