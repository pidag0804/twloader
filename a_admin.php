<?php
session_start();
include_once("include/class_mysql.php");
include_once("include/user_data.php"); 
include_once("include/get_user_status.php");

$_GET['pms'] = PMS_ADMIN;
require_once("include/content_head.php");

$TableSetArray = array();
$TableRowArr = array();
$TableDataArr = array();

function addNewValue( $row_name, $data ) {
	global $TableRowArr;
	global $TableDataArr;
	echo "`".$row_name."` = '".$data."'<br>";
	$TableRowArr[] = $data;
	$TableDataArr[] = $row_name;
}

function addValue( $row_name, $data ) {
	global $TableSetArray;
	//echo "`".$row_name."` = '".$data."'<br>";
	$TableSetArray[] = "`".$row_name."` = '".$data."'";
}

switch ($_GET['type']) {
	
	case "config":
		$fh = fopen("include/site_config.ini", 'w+'); 
		fwrite($fh, stripslashes($_POST['config_file'])); 
		fclose($fh);
		exit ("OK");
        break;
		
	case "download":
		$UpdateList = array("sort", "hide", "name", "link", "text");
		if ( $_POST['newType'] > 0 ){
			addNewValue("type", $_POST['newType']-1);
			for ($i = 0; $i < sizeof($UpdateList); $i++)
				addNewValue($UpdateList[$i], $_POST[$UpdateList[$i]]['new']);
			$db->query("INSERT INTO tl_download (`".implode("`,`", $TableDataArr)."`) VALUES ('".implode("','", $TableRowArr)."')");
		}
		$Set_ID = $_POST['dataID'];
		for ($i = 0; $i < sizeof($Set_ID); $i++) {
			$now_id = $Set_ID[$i];
			for ($a = 0; $a < sizeof($UpdateList); $a++)
				addValue($UpdateList[$a], $_POST[$UpdateList[$a]][$now_id]);
			$db->query("UPDATE tl_download SET ".implode(", ", $TableSetArray)." WHERE `id` = ".$now_id);
			$TableSetArray = array();
		}
		exit ("OK".$_POST['name'][1]);
        break;
		
	case "notice":
		$UpdateList = array("date", "topic", "hide", "highlight", "text", "top", "area");

		if ( $_POST['num'] == "0" ){
			for ($i = 0; $i < sizeof($UpdateList); $i++)
				addNewValue($UpdateList[$i], $_POST[$UpdateList[$i]]);
			$db->query("INSERT INTO tlsay (`".implode("`,`", $TableDataArr)."`) VALUES ('".implode("','", $TableRowArr)."')");
		} else {
			for ($a = 0; $a < sizeof($UpdateList); $a++)
				addValue($UpdateList[$a], $_POST[$UpdateList[$a]]);
			$db->query("UPDATE tlsay SET ".implode(", ", $TableSetArray)." WHERE `num` = ".$_POST['num']);
		}
		exit ("OK");
        break;
		
	case "user":
		$UpdateList = array("group", "cnname", "uspw");

		for ($a = 0; $a < sizeof($UpdateList); $a++)
			addValue($UpdateList[$a], $_POST[$UpdateList[$a]]);
		$db->query("UPDATE tlpw SET ".implode(", ", $TableSetArray)." WHERE `num` = ".$_POST['num']);

		$arr_keys = array_keys($_POST['status']);
		
		for ($i = 0; $i <= count($arr_keys) - 1; $i++)
			$db->query("UPDATE tl_viplist SET status = ".$_POST['status'][$arr_keys[$i]]." WHERE `gameid` = '".$arr_keys[$i]."' AND `uid` = ".$_POST['num']);
		
		$_GET['done'] = "ok";
		$_GET['uid'] = $_POST['num'];
		require_once ("c_user_edit.php");
        break;
}

//echo "<script>document.location.href='http://google.com';</script
//<script>window.location.reload();</script
?>