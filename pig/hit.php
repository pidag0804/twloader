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