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

$action = ForceIncomingString('action', 'default');
if(IsPost('updategroups')) $action= 'updategroups';
if(IsPost('deletegroups')) $action= 'deletegroups';


PrintHeader($userinfo['username'], 'groups');

//########### UPDATE GROUPS ###########

if($action == 'updategroups'){
	$usergroupids   = $_POST['usergroupids'];
	$displayorders   = $_POST['displayorders'];
	$groupnames   = $_POST['groupnames'];
	$groupenames   = $_POST['groupenames'];
	$descriptions   = $_POST['descriptions'];
	$descriptionens   = $_POST['descriptionens'];
	$activateds   = $_POST['activateds'];

    for($i = 0; $i < count($usergroupids); $i++){
		$groupname = ForceString($groupnames[$i]);
		$groupename = ForceString($groupenames[$i]);

		$DB->exe("UPDATE " . TABLE_PREFIX . "usergroup SET displayorder = '".ForceInt($displayorders[$i])."',
		groupname = '".Iif($groupname, $groupname, '未命名')."',
		groupename = '".Iif($groupename, $groupename, 'No name')."',
		activated = '".ForceInt($activateds[$i])."',
		description = '".ForceString($descriptions[$i])."',
		descriptionen = '".ForceString($descriptionens[$i])."'
		WHERE usergroupid = '".ForceInt($usergroupids[$i])."'");
    }

	if(!storeCache()){ //更新小面板在線客服暫存檔案
		$errortitle = '更新客服暫存錯誤';
		$errors = '客服群組內容已儲存到資料庫, 但更新在線客服暫存檔案失敗, 前台客服小面板狀態無法更新! 請檢查cache/目錄是否存在或可寫?';
		$action = 'default';
	}else{
		GotoPage('admin.groups.php', 1);
	}
}

//########### DELETE GROUPS ###########

if($action == 'deletegroups'){
	$deleteusergroupids   = $_POST['deleteusergroupids'];

    for($i = 0; $i < count($deleteusergroupids); $i++){
		$DB->exe("DELETE FROM " . TABLE_PREFIX . "usergroup WHERE usergroupid <>1 AND usergroupid = '".ForceInt($deleteusergroupids[$i])."'");
    }


	GotoPage('admin.groups.php', 1);
}

//########### CREATE GROUP ###########

if($action == 'creatgroup'){
	$groupname  = ForceIncomingString('groupname');
	$groupename  = ForceIncomingString('groupename');
	$description  = ForceIncomingString('description');
	$descriptionen  = ForceIncomingString('descriptionen');

	if ($groupname == '')	$errors[] = "群組名稱不能為空!";
	if ($groupename == '')	$errors[] = "群組英文名稱不能為空!";

	if(isset($errors)){
		$errortitle = '增加群組錯誤';
		$action = 'default';
	}else{
		$DB->exe("INSERT INTO " . TABLE_PREFIX . "usergroup (displayorder, groupname, groupename, activated, description, descriptionen) VALUES (1, '$groupname', '$groupename', 1, '$description', '$descriptionen')");

		$usergroupid = $DB->insert_id();
		$DB->exe("UPDATE " . TABLE_PREFIX . "usergroup SET displayorder = '$usergroupid' WHERE usergroupid = '$usergroupid'");

		GotoPage('admin.groups.php', 1);
	}
}

//########### PRINT DEFAULT ###########

if($action == 'default'){
	$usergroup = array('groupname'   => '', 'groupename'   => '', 'description'   => '', 'descriptionen'   => '');

	if(isset($errors)){
		PrintErrors($errors, $errortitle);
		if(!IsPost('updategroups')){
			$usergroup = array('groupname'   => $groupname, 'groupename'   => $groupename, 'description'   => $_POST['description'], 'descriptionen'   => $_POST['descriptionen']);
		}
	}

	echo '<form method="post" action="admin.groups.php" name="groupform">
	<input type="hidden" name="action" value="creatgroup">
	<table border="0" cellpadding="0" cellspacing="0" class="moreinfo">
	<thead>
	<tr>
	<th colspan="4">建立新客服群組:</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>客服群組的名稱:</td>
	<td>
	<input type="text" name="groupname" value="'.$usergroup['groupname'].'"> <font class=red>* 必填項</font></td>
	</td>
	<td>中文說明:</td>
	<td>
	<textarea name="description" rows="4"  style="width:180px;">'.$usergroup['description'].'</textarea> <span class=note2>允許HTML, 如換行插入&lt;br&gt;</span>
	</td>
	</tr>
	<tr>
	<td>客服群組的英文名稱:</td>
	<td>
	<input type="text" name="groupename" value="'.$usergroup['groupename'].'"> <font class=red>* 必填項</font></td>
	</td>
	<td>英文說明:</td>
	<td>
	<textarea name="descriptionen" rows="4"  style="width:180px;">'.$usergroup['descriptionen'].'</textarea> <span class=note2>允許HTML, 如換行插入&lt;br&gt;</span>
	</td>
	</tr>
	</tbody>
	</table>';
	PrintSubmit('增加群組');

	$getgroups = $DB->query("SELECT ug.*, COUNT(u.userid) AS users FROM " . TABLE_PREFIX . "usergroup ug LEFT JOIN " . TABLE_PREFIX . "user u ON (u.usergroupid = ug.usergroupid) WHERE ug.usergroupid <>1 GROUP BY ug.usergroupid ORDER BY ug.displayorder");

	echo '<BR><BR><form method="post" action="admin.groups.php" name="groupsform">
	<table id="welive_list" border="0" cellpadding="0" cellspacing="0" class="moreinfo">
	<thead>
	<tr>
	<th>顯示順序</th>
	<th>群組名稱</th>
	<th>群組英文名稱</th>
	<th>中文說明</th>
	<th>英文說明</th>
	<th>狀態</th>
	<th>客服人數</th>
	<th>刪除</th>
	</tr>
	</thead>
	<tbody>';

    while($group = $DB->fetch($getgroups)){
		echo '<tr>
		<td>
		<input type="hidden" name="usergroupids[]" value="' . $group['usergroupid'] . '">
		<input type="text" name="displayorders[]" value="' . $group['displayorder'] . '"  size="4"></td>
		</td>
		<td><input type="text" name="groupnames[]" value="' . $group['groupname'] . '"></td>
		<td><input type="text" name="groupenames[]" value="' . $group['groupename']. '"></td>
		<td><textarea name="descriptions[]" rows="4"  style="width:180px;">'.$group['description'].'</textarea></td>
		<td><textarea name="descriptionens[]" rows="4"  style="width:180px;">'.$group['descriptionen'].'</textarea></td>
		<td>
		<select name="activateds[]">
		<option value="1">開放服務</option>
		<option style="color:red;" value="0" ' . Iif(!$group['activated'], 'SELECTED', '') . '>隱藏</option>
		</select></td>
		<td>' . $group['users']. '</td>
		<td><input type="checkbox" name="deleteusergroupids[]" value="'.$group['usergroupid'].'" '.Iif($group['users'] > 0, 'disabled').'></td>
		</tr>';
	}

	echo '</tbody>
	</table>
	<div style="margin-top:20px;text-align:center;">
	<input type="submit" name="updategroups" value=" 儲存更新 " />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" name="deletegroups" onclick="return confirm(\'確定刪除所選客服群組嗎?\r\n\r\n提示: 僅允許刪除沒有客服人員的群組!\');" value=" 刪除群組 " />
	</div>
	</form>';

}

PrintFooter();

?>

