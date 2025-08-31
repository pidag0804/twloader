<?
include_once("include/class_mysql.php");
include_once("include/user_data.php");

/*
0:選填,會查　
1:必填,會查　
2:如帳戶符合:必填,會查 　
3:選填，會查，不加入array
*/

$Dlist =  array(   'total_id',		1, "/^([1-4])$/", 			"accnumber", 
				   'payMethod', 	3, "/^([1|5])$/", 			"buyform", 
				   'tel',			1, "/^(\d{10})+$/", 		"phone", 
				   'email',			0, "/^([\w.]+)@([\w.]+)/",  "email", 
				   'agent',			0, "/^[\d|a-zA-Z]+$/", 		"who", 
				   'message',		0, "/(?:)/", 				"other",
				   'pay_caption',	3, "/(?:)/", 				"specialnumber",
				   
				   'game_id_1', 	2, "/^[\d|a-zA-Z]+$/", 		"acc1",
				   'plan_1', 		2, "/^([1-2])$/", 			"type1",
				   'limit_1', 		2, "/^[\d]+$/", 			"?",
				   
				   'game_id_2', 	2, "/^[\d|a-zA-Z]+$/", 		"acc2",
				   'plan_2', 		2, "/^([1-2])$/", 			"type2",
				   'limit_2', 		2, "/^[\d]+$/", 			"?",
				   
				   'game_id_3', 	2, "/^[\d|a-zA-Z]+$/", 		"acc3",
				   'plan_3', 		2, "/^([1-2])$/", 			"type3",
				   'limit_3', 		2, "/^[\d]+$/", 			"?",
				   
				   'game_id_4', 	2, "/^[\d|a-zA-Z]+$/", 		"acc4",
				   'plan_4', 		2, "/^([1-2])$/", 			"type4",
				   'limit_4', 		2, "/^[\d]+$/", 			"?"
				   );

$totalID = 0;
$TableRowArr = array();
$TableDataArr = array();

if (preg_match($Dlist[2], $_POST[$Dlist[0]])) 
	$totalID = $_POST[$Dlist[0]];
else
	die("Error 0");

function addValue( $row_name, $data ) {
	global $TableRowArr;
	global $TableDataArr;
	
	if ( $data == '' ) return;
	
	$TableRowArr[] = $data;
	$TableDataArr[] = $row_name;
}

function initData() {
	global $Dlist;
	global $totalID;
	
	$nowPlan = 0;
	
	for ($i = 0; $i < sizeof($Dlist); $i+=4) {
		$pData = $_POST[$Dlist[$i]];
		$rName = $Dlist[$i +3 ];
		$match = preg_match($Dlist[$i+2], $pData);
		switch($Dlist[$i+1]){
			case 0:
				if ( $match ) addValue($rName, $pData);
				break;
			case 1:
				if ( $match ) addValue($rName, $pData);
				else return -1;
				break;
			case 2:
				$gid = substr($Dlist[$i], -1);
				if ( $gid <= $totalID ) {
					if ( substr($Dlist[$i], 0, -1 ) == "plan_" ) $nowPlan = $pData;
					if ( $match ) {
						if ( $rName == "?" ) $rName = ( $nowPlan == 1 ) ? "day".$gid : "times".$gid ;
						addValue($rName, substr($rName, 0, -1 ) == "type" ? $pData - 1 : $pData);
					}else{
						return -2;
					}
				}
				break;
			case 3:
				if ( !$match ) return -3;
				break;
		}
	}
	
	$ModCheck = array("/(?:)/", "/(?:)/", "/^[\W]+$/", "/^[\W]+$/", "/^(\d{7})+$/", "/(?:)/",);
	if ( preg_match($ModCheck[$_POST['payMethod']], $_POST['pay_caption']) == 0 ) return -4;
	
	return 1;
}

function calMoney() {
	$totalPrice = 0;
	$PriceList[1] = array(230, 460, 600, 830, 1060, 1200, 1430, 1660, 1890, 2120, 2350, 2400);
	$PriceList[2] = array(260, 730);
	
	global $totalID;
	
	for ($i = 1; $i <= $totalID; $i++) {
		$Plan = $_POST['plan_'.$i];
		$Limit = $_POST['limit_'.$i];
		if ( $Plan == 0 ) return 0;
		$totalPrice += $PriceList[$Plan][$Limit];
	}
	if ( $_POST['payMethod'] == 1 || $_POST['payMethod'] == 5 ) $totalPrice += 30;
	
	return $totalPrice;
}

function formatRowToCheck($ParamList, $HashKey, $HashIV) {
	# CheckMacValue
	ksort($ParamList);
	$CheckMacValue = 'HashKey=' . $HashKey;
	foreach ($ParamList as $ParamName => $ParamValue) {
		$CheckMacValue .= '&' . $ParamName . '=' . $ParamValue;
	}
	$CheckMacValue .= '&HashIV=' . $HashIV;
	$CheckMacValue = strtolower(urlencode($CheckMacValue));
	
	// 取之為與 dotNet 相符的字元
	$CheckMacValue = str_replace('%2d', '-', $CheckMacValue);
	$CheckMacValue = str_replace('%5f', '_', $CheckMacValue);
	$CheckMacValue = str_replace('%2e', '.', $CheckMacValue);
	$CheckMacValue = str_replace('%21', '!', $CheckMacValue);
	$CheckMacValue = str_replace('%2a', '*', $CheckMacValue);
	$CheckMacValue = str_replace('%28', '(', $CheckMacValue);
	$CheckMacValue = str_replace('%29', ')', $CheckMacValue);
	
	// MD5 編碼
	$CheckMacValue = strtoupper(md5($CheckMacValue));
	
	return $CheckMacValue;
}

function RndCode($i) { 
    srand((double)microtime()*987654321); 
    return strtoupper(substr(md5(uniqid(rand())),rand(0,32-$i),$i)); 
}

$dataCheck = initData();
//echo ( "Check Code: ".$dataCheck )."<br>";

$payMethod = $_POST['payMethod'];
$PayConvert = array(0, 4, 2, 3, 1, 5);
addValue("buyform", $PayConvert[$payMethod]);
addValue("buyer", $User['num']);

$mer_id = '6352';

if ( $dataCheck == 1 ) {
	$payMoney = calMoney();
	addValue("money", $payMoney);
	if ( $payMoney != $_POST['payPrice'] ) die("金額錯誤");

	$query = $db->query("SELECT COUNT(*) FROM kmx_problema");
	$ReceiptId = $db->result($query,0) + 1;
	addValue("id", $ReceiptId);
	
	if ( $payMethod == 1 ) {
		$prd_desc = 'TwLoader';
		$enc_key = 'X44uH0CFq3oiM7UyU53npPn8';

		$postData = array(
			"payment_type" => "cvs",
			"mer_id" => $mer_id,
			"od_sob" => $ReceiptId,
			"enc_key" => $enc_key,
			"amt" => $payMoney,
			"prd_desc" => $prd_desc,
			"ok_url" => "http://www.tlmoo.com/twloader/liftet/liftet.php?wa=2",
		);

		$checkmacvalue = formatRowToCheck($postData, "a0992309d0974c04", "2eff3002671cedc8");
		$postData["checkmacvalue"] = $checkmacvalue;

		$post_str = "";

		foreach ($postData as $ParamName => $ParamValue) {
			$post_str .= '&' . $ParamName . '=' . $ParamValue;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'https://ecbank.com.tw/gateway_v2.php');
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post_str);
		$strAuth = curl_exec($ch);
		if (curl_errno($ch)) {
			$strAuth = false;
		}
		curl_close($ch);

		if($strAuth) {
			parse_str($strAuth, $ecArray);

			if(!isset($ecArray['error']) || $ecArray['error'] != '0'){
				die("取號錯誤");
			} else {
				addValue("specialnumber", $ecArray['payno']);
			}
		} else {
			die("取號失敗");
		}
	} else {
		addValue("specialnumber", $_POST['pay_caption']);
	}
	
	$RndPassword = RndCode(6);
	
	addValue("password", $RndPassword);
	addValue("buytime", date("Y-m-d H:i:s"));
	
	$db->query("INSERT INTO kmx_problema (`".implode("`,`", $TableDataArr)."`) VALUES ('".implode("','", $TableRowArr)."')");
} else {
	die("購買失敗，請填寫正確資料。");	
}
?>
<!--
<pre>
<?
	print_r($TableRowArr);
	print_r($TableDataArr);
?>
</pre>
-->
<?
$PayMString = array(0, "超商代碼繳費", "銀行手寫匯款", "郵局手寫匯款", "提款機轉帳", "網上 ATM");
?>

<?php
if ( $payMethod == 5 ) {
	$postData = array(
		"mer_id" => $mer_id,
		"payment_type" => "web_atm",
		"od_sob" => $ReceiptId,
		"amt" => $payMoney,
		"return_url" => "http://www.tlmoo.com/twloader/liftet/liftet.php?wa=1",
	);

	$checkmacvalue = formatRowToCheck($postData, "a0992309d0974c04", "2eff3002671cedc8");

	$postData["checkmacvalue"] = $checkmacvalue;

?>
<form id="webATMForm" method="POST" action="https://ecbank.com.tw/gateway_v2.php">
<?php
foreach( $postData as $key =>$value ) {  // link to string
	if(!(empty($value))) {
		echo "<input type='hidden' name='$key' value='$value'>";
	}
}
?>
</form>
正在前往網上 ATM 付款頁 ...
<!--<button onClick='submitForm()'>submit</button>-->

<script>
function submitForm() {
	document.getElementById("webATMForm").submit();
}

setTimeout(function(){
	document.getElementById("webATMForm").submit();
}, 1000);
</script>

<?php } else { ?>
成功了！你的訂單已經送出了，並請牢記此頁面全部資訊<br />
你選擇的付款方式是 <font color="red"><? echo $PayMString[$payMethod]; ?></font><br /><br />

<i class="awe colorful" style="background-color:#999;">繳費金額</i>
<i class="awe colorful" style="background-color:#999;"><? echo $payMoney ?></i>
<br /><br />

<? if ( $payMethod == 1 ) { ?>
<i class="icon-asterisk awe colorful" style="background-color:#F06;"> &nbsp;交易單號</i>
<i class="awe colorful" style="background-color:#F06;"> &nbsp;<span style="letter-spacing:3pt; font-size:15px;"><? echo $ecArray['tsr']; ?></span></i>
<br /><br />
<i class="icon-asterisk awe colorful" style="background-color:#F06;"> &nbsp;繳費代碼 < 請牢記 ></i>
<i class="awe colorful" style="background-color:#F06;"> &nbsp;<span style="letter-spacing:3pt; font-size:15px;"><? echo $ecArray['payno']; ?></span></i>
<br /><br />
<font color="#FF0066"><b>請您在三天內持<font color="#0066FF">繳費代碼</font>到全省7-11或全家超商以及萊爾富超商列印繳費</b></font>
<br /><br />
<? } ?>

<b>查詢開通進度方法</b>
<br />
前往【<a href="?page=orderform">訂單記錄</a>】查詢
<br /><br /><br /><br />
<center><h2>多謝惠顧 :-)</h2></center>
<br /><br />
<?php } ?>