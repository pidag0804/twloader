<?php
error_reporting(E_ALL & ~E_NOTICE);
include('version.php');

$rootpath = '../';


// ############################## FUNCTIONS ##############################

function IsName($name){
	$entities_match		= array(',',';','$','!','@','#','%','^','&','*','_','(',')','+','{','}','|',':','"','<','>','?','[',']','\\',"'",'.','/','*','+','~','`','=');
	for ($i = 0; $i<count($entities_match); $i++) {
	     if(strpos($name, $entities_match[$i])){
               return false;
		 }
	}
   return true;
}

function IsPass($pass){
	return preg_match("/^[[:alnum:]]+$/i", $pass);
}

function PassGen($length = 8){
	$str = 'abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	for ($i = 0, $passwd = ''; $i < $length; $i++)
		$passwd .= substr($str, mt_rand(0, strlen($str) - 1), 1);
	return $passwd;
}

function DB_Query($sql){
	global $footer;

	$result = MYSQL_QUERY ($sql);
	if(!$result){
		$message  = "資料庫訪問錯誤\r\n\r\n";
		$message .= $sql . " \r\n";
		$message .= "錯誤內容: ". mysql_error() ." \r\n";
		$message .= "錯誤程式碼: " . mysql_errno() . " \r\n";
		$message .= "時間: ".gmdate('Y-m-d H:i:s', time() + (3600 * 8)). "\r\n";
		$message .= "檔案: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

		echo '<center><font class=ohredb><b>資料庫訪問錯誤!</b></font><br /><p><textarea rows="28" style="width:460px;">'.htmlspecialchars($message).'</textarea></p>
		<input type="button" name="back" value=" 返&nbsp;回 " onclick="history.back();return false;" />		
		</center><BR>';
		echo $footer;
		exit();
	}else{
		return true;
	}
}

// ############################## HEADER AND FOOTER ############################

echo '<html>
<head>
<title>WeLive在線客服系統 - 安裝嚮導</title>
<link rel="stylesheet" href="./styles.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<table width="480" cellpadding="0" cellspacing="1" border="0" align="center" class="box">
<tr>
<td class="title">WeLive在線客服系統'.$WeLiveVersion.' - 安裝嚮導</td>
</tr>
<tr>
<td valign="top" style="padding: 5px;">';

$footer = '</td></tr></table></body></html>';

// ################# CHECK IF ALREADY INSTALLED ##################

@include($rootpath . 'config/config.php');

if(defined('WELIVE')){
	echo '<font class=ohredb><b>WeLive在線客服系統已經安裝!</b></font><BR><BR>
	如果您希望重新安裝，請先刪除config/目錄下的config.php檔案。<BR><BR>';

	echo $footer;
	exit();
}

// ############################### GET POST VARS ###############################

$servername      = isset($_POST['install']) ? trim($_POST['servername'])      : 'localhost';
$dbname          = isset($_POST['install']) ? trim($_POST['dbname'])          : '';
$dbusername      = isset($_POST['install']) ? trim($_POST['dbusername'])      : '';
$dbpassword      = isset($_POST['install']) ? trim($_POST['dbpassword'])      : '';
$tableprefix     = isset($_POST['install']) ? trim($_POST['tableprefix'])     : 'welive_';
$confirmprefix     = isset($_POST['install']) ? trim($_POST['confirmprefix'])     : '';

$username        = isset($_POST['install']) ? trim($_POST['username'])        : '';
$password        = isset($_POST['install']) ? trim($_POST['password'])        : '';
$confirmpassword = isset($_POST['install'])? trim($_POST['confirmpassword']) : '';

$tableprefix_err = 0;

// ############################ INSTALL #############################

if(isset($_POST['install'])){
	// check for errors
	@chmod('../config/', 0777);
	@chmod('../cache/', 0777);

	if (!is_writable('../cache/'))
		$installerrors[] = '請將cache檔案夾的屬性設定為: 777';

	if (!is_writable('../config/'))
		$installerrors[] = '請將config檔案夾的屬性設定為: 777';

	if(!is_writeable('../config/settings.php')) {
		$installerrors[] = '請將系統配置檔案config/settings.php設定為可寫, 即屬性設定為: 777';
	}

	if(strlen($username) == 0){
		$installerrors[] = '請輸入系統管理會員名.';
	}else if(!IsName($username)){
		$installerrors[] = '會員名中含有非法字符.';
	}

	if(strlen($password) == 0){
		$installerrors[] = '請輸入系統管理密碼.';
	}else if(!IsPass($password)){
		$installerrors[] = '密碼中含有非法字符.';
	}

	if($password != $confirmpassword)
		$installerrors[] = '管理密碼與確認密碼不匹配.';

	if(strlen($tableprefix) == 0){
		$installerrors[] = '請輸入資料庫表前綴.';
	}else if(!preg_match('/^[A-Za-z0-9]+_$/', $tableprefix)){
		$installerrors[] = '資料庫表前綴只能是英文字母或數字, 而且必需以 _ 結尾.';
	}


	// Determine if MySql is installed
	if(function_exists('mysql_connect')){
		// attempt to connect to the database
		if($connection = @MYSQL_CONNECT($servername, $dbusername, $dbpassword)){

			$sqlversion = @mysql_get_server_info();
			if(empty($sqlversion)) $sqlversion='5.0';

			if($sqlversion >= '4.1'){
				mysql_query("set names 'utf8'");
				mysql_query("SET COLLATION_CONNECTION='utf8_general_ci'");
				mysql_query("ALTER DATABASE $dbname DEFAULT CHARACTER SET utf8 COLLATE 'utf8_general_ci'");           
			}

			if($sqlversion >= '5.0'){
				mysql_query("SET sql_mode=''");
			}

			// connected, now lets select the database
			if($dbname){
				if(!@MYSQL_SELECT_DB($dbname, $connection)){
					// The database does not exist... try to create it:
					if(!@DB_Query("CREATE DATABASE $dbname")){
						$installerrors[] = '建立資料庫 "' . $dbname . '" 失敗! 您的會員名可能沒有建立資料庫的權限.<br />' . mysql_error();
					}else{
						if($sqlversion >= '4.1'){
							mysql_query("set names 'utf8'");
							mysql_query("SET COLLATION_CONNECTION='utf8_general_ci'");
							mysql_query("ALTER DATABASE $dbname DEFAULT CHARACTER SET utf8 COLLATE 'utf8_general_ci'");           
						}

						if($sqlversion >= '5.0'){
							mysql_query("SET sql_mode=''");
						}
						// Success! Database created
						MYSQL_SELECT_DB($dbname, $connection);
					}
				}
			}else{
				$installerrors[] = '請輸入資料庫名稱.';
			}
		}else{
			// could not connect
			$installerrors[] = '無法連接MySql資料庫伺服器, 內容:<br />' . mysql_error();
		}
	}else{
		// mysql extensions not installed
		$installerrors[] = '網站伺服器環境不支援MySql資料庫.';
	}

	if(!isset($installerrors)){
		$SqlLines = @file('WeLive.sql');
		if (!$SqlLines) {
			$installerrors[] = '無法載入資料檔案: install/WeLive.sql';
		} else {
			if(!$confirmprefix) {
				if($query = mysql_query("SHOW TABLES FROM $dbname")) {
					while($row = mysql_fetch_row($query)) {
						if(preg_match("/^$tableprefix/", $row[0])) {
							$tableprefix_err = 1;
							break;
						}
					}
				}
			}

			if(!$tableprefix_err){
				$sql = implode('', $SqlLines);

				/* 刪除SQL行註釋，行註釋不匹配換行符 */
				$sql = preg_replace('/^\s*(?:--|#).*/m', '', $sql);

				/* 刪除SQL塊註釋，匹配換行符，且為非貪婪匹配 */
				$sql = preg_replace('/^\s*\/\*.*?\*\//ms', '', $sql);

				/* 刪除SQL串首尾的空白符 */
				$sql = trim($sql);

				/* 替換表前綴 */
				$sql = preg_replace('/((TABLE|INTO|IF EXISTS) )welive_/', '${1}' . $tableprefix, $sql);

				/* 解析查詢項 */
				$sql = str_replace("\r", '', $sql);
				$query_items = explode(";\n", $sql);

				foreach ($query_items AS $query_item){
					/* 如果查詢項為空，則跳過 */
					if (!$query_item){
						continue;
					}else{
						DB_Query($query_item);
					}
				}

				DB_Query ("INSERT INTO " . $tableprefix . "user VALUES (NULL, 1, 0, '$username', 1, '".md5($password)."', 1, 0, '系統管理員', 'Administrator', '', '', '', '', '".time()."') ");
				DB_Query ("INSERT INTO " . $tableprefix . "user VALUES (NULL, 2, 1, 'mszhang', 1, '".md5($password)."', 1, 0, '張小娟', 'Ms.Zhang', '姓名: 張小娟', 'Name: Ms. Zhang', '廣告', 'Adv.', 0) ");
				DB_Query ("INSERT INTO " . $tableprefix . "user VALUES (NULL, 2, 2, 'msli', 1, '".md5($password)."', 1, 0, '李晴晴', 'Ms.Li', '姓名: 李晴晴', 'Name: Ms. Li', '廣告', 'Adv.', 0) ");
				DB_Query ("INSERT INTO " . $tableprefix . "user VALUES (NULL, 3, 3, 'mrzhao', 1, '".md5($password)."', 1, 0, '趙利銘', 'Mr.Zhao', '姓名: 趙利銘', 'Name: Mr. Zhao', '廣告', 'Adv.', 0) ");
				DB_Query ("INSERT INTO " . $tableprefix . "user VALUES (NULL, 3, 4, 'mrwang', 1, '".md5($password)."', 1, 0, '王  炯', 'Mr.Wang', '姓名: 王  炯', 'Name: Mr. Wang', '廣告', 'Adv.', 0) ");

				$filename = $rootpath . "config/settings.php";
				$fp = @fopen($filename, 'rb');
				$contents = @fread($fp, filesize($filename));
				@fclose($fp);
				$contents =  trim($contents);
				$contents = preg_replace("/[$]_CFG\['cAppVersion'\]\s*\=\s*[\"'].*?[\"'];/is", "\$_CFG['cAppVersion'] = '$WeLiveVersion';", $contents);
				$contents = preg_replace("/[$]_CFG\['cKillRobotCode'\]\s*\=\s*[\"'].*?[\"'];/is", "\$_CFG['cKillRobotCode'] = '".md5(microtime())."';", $contents);

				$fp = @fopen($filename, 'w');
				@fwrite($fp, $contents);
				@fclose($fp);

				// write config file last off in case installation fails
				$configfile="<?php

\$servername  = '$servername';
\$dbname      = '$dbname';
\$dbusername  = '$dbusername';
\$dbpassword  = '$dbpassword';

define('WELIVE', true);
define('TABLE_PREFIX', '".$tableprefix."');
define('COOKIE_KEY', '".PassGen(12)."');
define('WEBSITE_KEY', '".PassGen(12)."');
define('BASEPATH', dirname(dirname(__FILE__)).'/');

?>";

				// write the config file
				$filenum = fopen ($rootpath . "config/config.php","w");
				ftruncate($filenum, 0);
				fwrite($filenum, $configfile);
				fclose($filenum);

				echo '<font class=ohblueb>恭喜: 您的WeLive在線客服系統 安裝成功!</font><br /><br />請在刪除WeLive安裝目錄(./install/)後繼續!
					<br /><br />
					1).&nbsp;<a href="' . $rootpath . 'demo.html" target="_blank"><b>瀏覽客服小面板演示頁面!</b></a>
					<br /><br />
					2).&nbsp;<a href="' . $rootpath . 'index.php" target="_blank"><b>點閱這裡進入管理面板!</b></a><br /><br />';
			}
		}
	}
}


// ############################### INSTALL FORM ################################

if(!isset($_POST['install']) OR isset($installerrors) OR $tableprefix_err){
	if(isset($installerrors)){
		echo '<table width="97%" border="0" cellpadding="5" cellspacing="0" align="center">
		<tr>
		<td style="border: 1px solid #FF0000; font-size: 12px;" bgcolor="#FFE1E1">
		<u><b>安裝錯誤!</b></u><br /><br />
		安裝過程中發現以下錯誤:<br />';

		for($i = 0; $i < count($installerrors); $i++){
			echo '<b>' . ($i + 1) . ') ' . $installerrors[$i] . '</b><br />';
		}
		echo '</td></tr></table><br />';
	}

	echo '<table width="96%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
	<td valign="top" align="right"><u>WeLive ' .$WeLiveVersion. ' 繁體中文版(UTF-8)</u></td>
	</tr>  
	</table>
	<br />
	<b>1) 填寫WeLive資料庫連接內容:</b><br /><br />
	<form method="post" action="index.php" name="installform">
	<table width="92%" border="0" cellpadding="0" cellspacing="0" align="center" class="maintable">
	<tr>
	<td valign="middle">資料庫伺服器地址:</td>
	<td valign="middle" align="right"><input type="text" name="servername" value="' . $servername . '" /></td>
	</tr>
	<tr>
	<td valign="middle">資料庫名:</td>
	<td valign="middle" align="right"><input type="text" name="dbname" value="' . $dbname . '" /></td>
	</tr>
	<tr>
	<td valign="middle">資料庫會員名:</td>
	<td valign="middle" align="right"><input type="text" name="dbusername" value="' . $dbusername . '" /></td>
	</tr>
	<tr>
	<td valign="middle">資料庫密碼:</td>
	<td valign="middle" align="right"><input type="text" name="dbpassword" value="' . $dbpassword . '" /></td>
	</tr>
	<tr>
	<td valign="middle">資料庫表前綴:</td>
	<td valign="middle" align="right"><input type="text" name="tableprefix" value="' . $tableprefix . '" /></td>
	</tr>';

	if($tableprefix_err OR $confirmprefix){
		echo '<tr>
		<td valign="middle"><font class=ohredb><B>強制安裝:</B><BR>目前資料庫當中已經含有相同表前綴的資料表, 您可以重填"表前綴"來避免刪除舊的資料, 或者選擇強制安裝。強制安裝將刪除原有相同表前綴的資料庫表, 且無法恢復!</font></td>
		<td valign="middle"><input type="checkbox" name="confirmprefix" value="1"' . ($confirmprefix ? ' checked="checked"' : ''). ' /> 刪除資料, 強制安裝 !!!</td>
		</tr>';
	}

	echo '</table>
	<br /><br />
	<b>2) 建立WeLive系統管理帳號:</b><br /><br />
	<table width="92%" border="0" cellpadding="0" cellspacing="0" align="center" class="maintable">
	<tr>
	<td valign="middle">會員名:</td>
	<td valign="middle" align="right"><input type="text" name="username" value="' . $username . '" /></td>
	</tr>
	<tr>
	<td valign="middle">密碼:</td>
	<td valign="middle" align="right"><input type="text" name="password" value="' . $password . '" /></td>
	</tr>
	<tr>
	<td valign="middle">確認密碼:</td>
	<td valign="middle" align="right"><input type="text" name="confirmpassword" value="' . $confirmpassword . '" /></td>
	</tr>
	<tr>
	</table>
	<br /><br /><center><input type="submit" name="install" value="安裝 WeLive" /></center>
	</form><script type="text/JavaScript">document.getElementById("installform").dbname.focus();</script>';
}

// ############################### PRINT FOOTER ################################

echo $footer;

?>