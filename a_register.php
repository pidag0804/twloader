<?
include_once("include/class_mysql.php");

$dataFormat = "/^[\d|a-zA-Z]{4,30}$/";


$gAc = mysql_real_escape_string ($_POST['acname']);
$gPw = mysql_real_escape_string ($_POST['password']);
$gNn = mysql_real_escape_string ($_POST['nickname']);
$gEm = mysql_real_escape_string ($_POST['email']);

if ( preg_match($dataFormat, $gAc) && preg_match($dataFormat, $gPw) && preg_match("/^([\w.]+)@([\w.]+)/", $gEm) ) {
	$query = $db->query("SELECT * FROM tlpw WHERE `usname` LIKE '".$gAc."' OR `cnname` LIKE '".$gNn."' OR `usem` LIKE '".$gEm."'");
	$ac_count = $db->num_rows($query);
	if ( $ac_count > 0 )
		exit ("資料錯誤");
} else {
	exit ("資料格式錯誤");
}

$db->query("INSERT INTO tlpw (`usname`,`uspw`,`cnname`,`usem`) VALUES ('".$gAc."','".$gPw."', '".$gNn."', '".$gEm."')");
?>

成功了，<? echo $gNn; ?>！您已成功申請帳戶<br /><br />

<a href="?page=home#showlogin" name="modal"><i class="icon-user awe colorful"> &nbsp;按此登入</i></a><br />