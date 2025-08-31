<?php

// +---------------------------------------------+
// |     Copyright  2010 - 2028 WeLive           |
// |     http://www.weentech.com                 |
// |     This file may not be redistributed.     |
// +---------------------------------------------+


error_reporting(E_ALL & ~E_NOTICE);

@include('./config/config.php');
include(BASEPATH . 'includes/welive.BaseUrl.php');
include(BASEPATH . 'includes/Class.Database.php');

if(defined('AJAX')){
	$printerror = false; //AJAX不打印SQL查詢錯誤內容
}else{
	$printerror = true;
}

$DB = new MySQL($dbusername, $dbpassword, $dbname,  $servername, true, $printerror);

$dbpassword   = ''; //將config.php檔案中的密碼付值為空, 增加安全性

include(BASEPATH . 'config/settings.php');

define('APP_NAME', base64_decode($_CFG['cAppName']));
define('APP_URL', base64_decode($_CFG['cAppCopyrighURL']));
define('APP_VERSION', $_CFG['cAppVersion']);


define('TURL', BASEURL.'templates/');
define('COPYRIGHT', '&copy; '.date("Y") .' <a href="'.APP_URL.'" target="_blank">'. APP_NAME .'</a> '.base64_decode('5Zyo57q/5a6i5pyN57O757uf	').'(v'. APP_VERSION . ')');

if(defined('AUTH')){ //客服和管理員只顯示中文, 且需要授權
	include(BASEPATH . 'includes/welive.Support.php');

	define('IS_CHINESE', 1);
	define('SITE_TITLE', $_CFG['cTitle']);
	@include(BASEPATH . 'languages/Taiwan.php');
	if(!defined('AJAX')){ //客服的AJAX操作無需授權
		include(BASEPATH.'includes/welive.Auth.php');
	}

}elseif($_CFG['cActived']){ //客人自動選擇語言
	include(BASEPATH . 'includes/welive.Functions.php');

	$sitelang = ForceIncomingCookie('LANG'.COOKIE_KEY);

	if(!$sitelang){
		if($_CFG['cLang'] == 'Auto'){
			if (strstr(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']), 'zh-cn') OR strstr(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']), 'zh-tw'))	{
				$sitelang = 'Taiwan';
			}else{
				$sitelang = 'English';
			}
		}else{
			$sitelang = $_CFG['cLang'];
		}
	}

	define('SITE_LANG', $sitelang);
	define('IS_CHINESE', Iif(SITE_LANG == 'Taiwan', 1, 0));
	define('SITE_TITLE', Iif(IS_CHINESE, $_CFG['cTitle'], $_CFG['cTitle_en']));
	@include(BASEPATH . 'languages/' . SITE_LANG . '.php');
}


?>