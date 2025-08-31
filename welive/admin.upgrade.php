<?php

// +---------------------------------------------+
// |     Copyright  2010 - 2028 WeLive           |
// |     http://www.weentech.com                 |
// |     This file may not be redistributed.     |
// +---------------------------------------------+

define('AUTH', true);

include('includes/welive.Core.php');
include(BASEPATH . 'includes/welive.Admin.php');

if($userinfo['usergroupid'] != 1) exit();

$action = ForceIncomingString('action');

$upgradefinished = false;

// ############################### RUN  UPGRADES ###############################

if($action == 'upgraderunning'){
	include(BASEPATH . 'upgrade/upgrade.php');
	$upgradefinished = UpgradeSystem();
}

if($action == 'deleteupgradefiles'){
	@unlink(BASEPATH . 'upgrade/upgrade.php');
	@unlink(BASEPATH . 'upgrade/version.php');
}


// ############################### DISPLAY UPGRADES #############################

$availableupgrades = 0;

if(file_exists(BASEPATH . 'upgrade/upgrade.php') and file_exists(BASEPATH . 'upgrade/version.php')){
	$availableupgrades=1;
}

if($availableupgrades){
	$updatestatus = '<span class=blue>已檢測到升級程序, 請按提示進行升級!</span>';
}else if($upgradefinished){
	$updatestatus = '<span class=green>系統升級已完成!</span>';
}else{
	$updatestatus = '<span class=note>暫無可用的升級程序!</span>';
}


PrintHeader($userinfo['username'], 'upgrade');

echo '<div>'.$updatestatus.'
<ul><li>請嚴格按升級說明進行系統升級, 升級說明一般隨附在升級包中.</li>
<li>升級過程一般是先將升級包解壓後, 設定FTP工具以 <span class=note>二進制方式</span> 上傳到網站替換原檔案, 然後在後台運行升級程序.</li>
<li>建議: 升級完成後刪除upgrade目錄內的所有檔案.</li>
</ul>
</div>';

BR(3);

echo '<table width="100%" border="0" cellpadding="5" cellspacing="0">
<tr>
<td width="70%" valign="top" align="center">';

if($availableupgrades){
	include(BASEPATH . 'upgrade/version.php');
	$disableupgrade    = 'Disabled'; 
	
	$new = str_replace ('.', '', $WeLiveNewVersion);
	$old = str_replace ('.', '', APP_VERSION);

	If(intval ($new) <= intval ($old)){
		$messages = '<font class=red>您現在正在使用的版本高於或等於升級程序中的版本, 無需升級!</font>';
	}else{
		$messages = '';
		$disableupgrade    = 'Enabled'; 
	}
	 
	$availableupgrades++;


	if($upgradefinished){
		echo '<form method="post" action="admin.upgrade.php">
		<input type="hidden" name="action" value="deleteupgradefiles">
		<br><br><font class=blue>系統升級成功! 建議刪除升級檔案.</font><br><br><br>
		<input type="submit" name="deletefiles" value="刪除升級檔案"><br><br>
		</form>';
	}else{
		echo '<form method="post" action="admin.upgrade.php">
		<input type="hidden" name="action" value="upgraderunning">
		目前使用中的版本是: ' . APP_VERSION . '<br>
		正要升級到的版本是: <font class=red>' . $WeLiveNewVersion . '</font><br>
		<br><br>
		' . Iif($messages, $messages.'<br><br><br>') . '
		<input type="submit" name="upgrade" value="運行升級程序" '. $disableupgrade .'><br><br>
		</form>';
	}

}else{
		echo '<br><br><b>暫無可用的升級程序!</b><br><br><br>';
}

echo '</td></tr></table>';

PrintFooter();

?>