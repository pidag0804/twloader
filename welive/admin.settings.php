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

$action = ForceIncomingString('action', 'displaysettings');


PrintHeader($userinfo['username'], 'settings');

//########### UPDATE SETTINGS ###########

if($action == 'updatesettings'){
	$filename = BASEPATH . "config/settings.php";

	if(!is_writeable($filename)) {
		$errors = '請將系統配置檔案config/settings.php設定為可寫, 即屬性設定為: 777';
	}

	if(isset($errors)){
		PrintErrors($errors, '系統設定錯誤');
		$action = 'displaysettings';
	}else{
		$settings    = $_POST['settings'];
		$fp = @fopen($filename, 'rb');
		$contents = @fread($fp, filesize($filename));
		@fclose($fp);
		$contents =  trim($contents);
		$oldcontents =  $contents;

		foreach($settings as $key => $value){
			if($_CFG[$key] != $settings[$key]){
				switch ($key) {
					case 'cKillRobotCode':
						$value = ForceString($value, $_CFG[$key]);
						break;
					case 'cUpdate':
						$value = ForceInt($value, 6);
						if($value < 3 OR $value > 20) $value = 6;
						break;
					default:
						$value = ForceString($value);
						break;
				}
				
				$code = ForceString($key);
				$contents = preg_replace("/[$]_CFG\['$code'\]\s*\=\s*[\"'].*?[\"'];/is", "\$_CFG['$code'] = \"$value\";", $contents);
			}
		}

		if($contents != $oldcontents){
			$fp = @fopen($filename, 'wb');
			@fwrite($fp, $contents);
			@fclose($fp);
		}

		GotoPage('admin.settings.php', 1);
	}
}

//########### PRINT DEFAULT ###########

if($action == 'displaysettings'){

	echo '<form method="post" action="admin.settings.php">
	<input type="hidden" name="action" value="updatesettings">
	<table id="welive_list" border="0" cellpadding="0" cellspacing="0" class="moreinfo">
	<thead>
	<tr>
	<th colspan="2">系統設定:</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td><B>前台預設語言</B><BR>當選擇 \'<b>自動</b>\' 時, 將根據訪客的瀏覽器語言自動選擇語言, 中文瀏覽器進入中文, 其它語言瀏覽器自動進入英文.</td>
	<td>';
	$Langs = GetLangs();

	$Select = NewObject('Select');
	$Select->Name = 'settings[cLang]';
	$Select->SelectedValue = $_CFG['cLang'];
	$Select->AddOption('Auto', '自動');
	foreach($Langs as $val){
		$Select->AddOption($val, $val);
	}

	echo $Select->Get();
	echo '</td>
	</tr>

	<tr>
	<td><B>系統狀態</B><BR>關閉或開啟'.APP_NAME.'在線客服系統.</td>
	<td>';
	$Radio = NewObject('Radio');
	$Radio->Name = 'settings[cActived]';
	$Radio->SelectedID = $_CFG['cActived'];
	$Radio->AddOption(1, '開啟', '&nbsp;&nbsp;');
	$Radio->AddOption(0, '關閉', '&nbsp;&nbsp;');

	echo $Radio->Get();
	echo '</td>
	</tr>

	<tr>
	<td><B>網站預設時區</B><BR>'.APP_NAME.'在線客服系統將按預設時區顯示日期和時間.</td>
	<td>';
	$Select->Clear();
	$Select->Name = 'settings[cTimezone]';
	$Select->SelectedValue = $_CFG['cTimezone'];
	$Select->AddOption(-12, '(GMT -12) Eniwetok,Kwajalein');
	$Select->AddOption(-11, '(GMT -11) Midway Island,Samoa');
	$Select->AddOption(-10, '(GMT -10) Hawaii');
	$Select->AddOption(-9, '(GMT -9) Alaska');
	$Select->AddOption(-8, '(GMT -8) Pacific Time(US & Canada)');
	$Select->AddOption(-7, '(GMT -7) Mountain Time(US & Canada)');
	$Select->AddOption(-6, '(GMT -6) Mexico City');
	$Select->AddOption(-5, '(GMT -5) Bogota,Lima');
	$Select->AddOption(-4, '(GMT -4) Caracas,La Paz');
	$Select->AddOption(-3, '(GMT -3) Brazil,Buenos Aires,Georgetown');
	$Select->AddOption(-2, '(GMT -2) Mid-Atlantic');
	$Select->AddOption(-1, '(GMT -1) Azores,CapeVerde Islands');
	$Select->AddOption(0, '(GMT) London,Lisbon,Casablanca');
	$Select->AddOption(1, '(GMT +1) Paris,Brussels,Copenhagen');
	$Select->AddOption(2, '(GMT +2) Kaliningrad,South Africa');
	$Select->AddOption(3, '(GMT +3) Moscow,Baghdad,Petersburg');
	$Select->AddOption(4, '(GMT +4) Abu Dhabi,Muscat,Baku,Tbilisi');
	$Select->AddOption(5, '(GMT +5) Karachi,Islamabad,Tashkent');
	$Select->AddOption(6, '(GMT +6) Almaty,Dhaka,Colombo');
	$Select->AddOption(7, '(GMT +7) Bangkok,Hanoi,Jakarta');
	$Select->AddOption(8, '(GMT +8) 北京, 香港, 新加坡');
	$Select->AddOption(9, '(GMT +9) Tokyo,Osaka,Yakutsk');
	$Select->AddOption(10, '(GMT +10) Australia,Guam,Vladivostok');
	$Select->AddOption(11, '(GMT +11) Magadan,Solomon Islands');
	$Select->AddOption(12, '(GMT +12) Auckland,Wellington,Fiji');

	echo $Select->Get();
	echo '</td>
	</tr>

	<tr>
	<td><B>交互時間間隔</B><BR>客服端檢測伺服器最新資料的時間間隔(秒).<BR>可設定為<span class=note>3-20</span>之間的整數, 數值越小交互速度越快, 但會增加伺服器負擔.</td>
	<td>
	<input type="text" size="12" name="settings[cUpdate]" value="' . $_CFG['cUpdate'] . '">
	</td>
	</tr>

	<tr>
	<td><B>訪客自動離線時間</B><BR>訪客停止發言多少分鐘後, 自動轉為離線狀態, 同時允許其重新連線. 此功能可以降低系統資源的消耗.</td>
	<td>';
	$Select->Clear();
	$Select->Name = 'settings[cAutoOffline]';
	$Select->SelectedValue = $_CFG['cAutoOffline'];
	$Select->AddOption('6', "6分鐘後");
	$Select->AddOption('10', "10分鐘後");
	$Select->AddOption('14', "14分鐘後");
	$Select->AddOption('18', "18分鐘後");
	$Select->AddOption('22', "22分鐘後");
	$Select->AddOption('26', "26分鐘後");
	$Select->AddOption('30', "30分鐘後");

	echo $Select->Get();
	echo '</td>
	</tr>

	<tr>
	<td><B>日期格式</B><BR>系統顯示日期的格式.</td>
	<td>';
	$Select->Clear();
	$Select->Name = 'settings[cDateFormat]';
	$Select->SelectedValue = $_CFG['cDateFormat'];
	$Select->AddOption('Y-m-d', "2010-08-12");
	$Select->AddOption('Y-n-j', "2010-8-12");
	$Select->AddOption('Y/m/d', "2010/08/12");
	$Select->AddOption('Y/n/j', "2010/8/12");
	$Select->AddOption('Y年n月j日', "2010年8月12日");
	$Select->AddOption('m-d-Y', "08-12-2010");
	$Select->AddOption('m/d/Y', "08/12/2010");
	$Select->AddOption('M j, Y', "Aug 12, 2010");

	echo $Select->Get();
	echo '</td>
	</tr>

	<tr>
	<td><B>防惡意送出內容碼</B><BR>此碼有效防止機器人惡意留言, 送出內容等, 可時常更換, 但<span class=note>不能設定為空</span>.</td>
	<td>
	<input type="text" size="40" name="settings[cKillRobotCode]" value="' . $_CFG['cKillRobotCode'] . '">
	</td>
	</tr>
	
	<tr>
	<td><B>中文頁面標題</B><BR>當會員使用中文瀏覽器時, 顯示在瀏覽器頂部的標題名稱.</td>
	<td>
	<input type="text" size="40" name="settings[cTitle]" value="' . $_CFG['cTitle'] . '">
	</td>
	</tr>
	
	<tr>
	<td><B>英文頁面標題</B><BR>當會員使用非中文瀏覽器時, 顯示在瀏覽器頂部的標題名稱.</td>
	<td>
	<input type="text" size="40" name="settings[cTitle_en]" value="' . $_CFG['cTitle_en'] . '">
	</td>
	</tr>

	<tr>
	<td><B>中文歡迎詞</B><BR>客人進入客服系統後顯示的中文歡迎詞. <span class=note>允許HTML, 如換行插入&lt;br&gt;</span></td>
	<td><textarea name="settings[cWelcome]" rows="4" style="width:278px;">' . $_CFG['cWelcome'] . '</textarea></td>
	</tr>

	<tr>
	<td><B>英文歡迎詞</B><BR>客人進入客服系統後顯示的英文歡迎詞. <span class=note>允許HTML, 如換行插入&lt;br&gt;</span></td>
	<td><textarea name="settings[cWelcome_en]" rows="4" style="width:278px;">' . $_CFG['cWelcome_en'] . '</textarea></td>
	</tr>

	<tr>
	<td><B>自動刪除記錄</B><BR>客服或管理員登入後是否自動刪除對話記錄. 自動刪除記錄有助於提高對話速度, 達到系統自我維護的目的.</td>
	<td>';
	$Select->Clear();
	$Select->Name = 'settings[cDeleteHistory]';
	$Select->SelectedValue = $_CFG['cDeleteHistory'];
	$Select->AddOption('0', "從不刪除");
	$Select->AddOption('6', "6小時前");
	$Select->AddOption('12', "12小時前");
	$Select->AddOption('24', "24小時前");
	$Select->AddOption('48', "48小時前");
	$Select->AddOption('240', "10天前");
	$Select->AddOption('480', "20天前");
	$Select->AddOption('720', "30天前");

	echo $Select->Get();
	echo '</td>
	</tr>

	<tr>
	<td><B>禁止IP地址</B><BR>被禁止IP的訪客無法進入客服或留言. 多個IP請用英文分號";" 隔開, 可使用通配符禁止IP地址段.<BR>如: <span class=note>168.192.*.*</span></td>
	<td><textarea name="settings[cBannedips]" rows="6" style="width:278px;">' . $_CFG['cBannedips'] . '</textarea></td>
	</tr>

	</tbody>
	</table>';

	PrintSubmit('儲存設定');

}

PrintFooter();

?>

