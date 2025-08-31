<?
include_once("include/class_mysql.php");

$OutMessage = array("尚在驗証中", "開通失敗", "已經成功開通");
$OutMessage['NO'] = "查無此單";
$OutMessage['ERROR'] = "查無此單";

$statusID = "NO";
$query = $db->query("SELECT * FROM kmx_problema WHERE `id` = ".intval($_POST['rID']));
$count_query = $db->num_rows($query);

if ( $count_query ) {
	$rData = $db->fetch_array($query);
	if ( $rData['password'] == $_POST['rPW'] ) 
$s = $rData['id']and $t = $rData['buytime']and $q = $rData['acc1']and $w = $rData['acc2']and $e = $rData['acc3']and $r = $rData['acc4'];

	if ( $rData['password'] == $_POST['rPW'] ) 
	$statusID = $rData['done'];
		else $statusID = "ERROR";
		}
?>
訂單編號：<? echo $s; ?><br />
購買日期：<? echo $t; ?><br />
購買帳號：<? echo $q; ?>　<? echo $w; ?>　<? echo $e; ?>　<? echo $r; ?>
<br /><br />
<center><h2><? echo $OutMessage[$statusID]; ?></h2></center>
<br />