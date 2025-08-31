<?
error_reporting(0);

include_once("include/class_mysql.php");
include_once("include/user_data.php");
include_once('include/ECPay.Payment.Integration.php');

/*
0:選填,會查　
1:必填,會查　
2:如帳戶符合:必填,會查 　
3:選填，會查，不加入array
*/

$Dlist =  array(   'total_id',		1, "/^([1-4])$/", 			"accnumber", 
				   'payMethod', 	3, "/^([1|5|6])$/", 		"buyform", 
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
	
	//$ModCheck = array("/(?:)/", "/(?:)/", "/^[\W]+$/", "/^[\W]+$/", "/^(\d{7})+$/", "/(?:)/",);
	//if ( preg_match($ModCheck[$_POST['payMethod']], $_POST['pay_caption']) == 0 ) return -4;
	
	return 1;
}

function calMoney() {
	$totalPrice = 0;
	$PriceList[1] = array(200, 400, 500, 700, 900, 1000, 1200, 1400, 1500, 1700, 1900, 2000);
	$PriceList[2] = array(200, 500);
	
	global $totalID;
	
	for ($i = 1; $i <= $totalID; $i++) {
		$Plan = $_POST['plan_'.$i];
		$Limit = $_POST['limit_'.$i];
		if ( $Plan == 0 ) return 0;
		$totalPrice += $PriceList[$Plan][$Limit];
	}
	if ( $_POST['payMethod'] == 1 || $_POST['payMethod'] == 5 || $_POST['payMethod'] == 6 ) $totalPrice += 30;
	
	return $totalPrice;
}

if ( initData() != 1 ) die("購買失敗，請填寫正確資料。");
$payMethod = $_POST['payMethod'];
$payMoney = calMoney();
if ( $payMoney != $_POST['payPrice'] ) die("金額錯誤");

$query = $db->query("SELECT MAX(id) FROM kmx_problema");
$ReceiptId = $db->result($query,0) + 1;

addValue("id", $ReceiptId);
addValue("buyform", 6);
addValue("buyer", $User['num']);
addValue("money", $payMoney);
addValue("buytime", date("Y-m-d H:i:s"));
//addValue("password", $RndPassword);

/*
$mer_id = '6352';
$prd_desc = 'TwLoader';
$enc_key = 'X44uH0CFq3oiM7UyU53npPn8';

$postData = array(
"payment_type" => "cvs",
"mer_id" => $mer_id,
"od_sob" => $ReceiptId,
"enc_key" => $enc_key,
"amt" => $payMoney,
"prd_desc" => $prd_desc,
"ok_url" => "http://www.tlmoo.com/twloader/liftet/ECplay2.php?wa=2",
);
*/

$obj = new ECPay_AllInOne();

//服務參數
$obj->ServiceURL  = "https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5";
$obj->HashKey     = 'lzajZhlbftuzOhGG';
$obj->HashIV      = 'xFwKmMzxllCPYHAk';
$obj->MerchantID  = '3346636';

//Test
/*
$obj->ServiceURL  = "https://payment-stage.allpay.com.tw/Cashier/AioCheckOut/V5";
$obj->HashKey     = '5294y06JbISpM5x9';
$obj->HashIV      = 'v77hoKGq4kWxNNIS';
$obj->MerchantID  = '2000132';
*/

//基本參數(請依系統規劃自行調整)
$obj->Send['ReturnURL']         = "http://www.tlmoo.com/twloader/liftet/ECpay2.php?wa=3";    //付款完成通知回傳的網址
$obj->Send['ClientBackURL']     = "https://www.ecpay.com.tw/";
$obj->Send['MerchantTradeNo']   = $ReceiptId;                                 //訂單編號
$obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                        //交易時間
$obj->Send['TotalAmount']       = $payMoney;                                  //交易金額
$obj->Send['TradeDesc']         = "thanks" ;                        //交易描述
$obj->Send['ChoosePayment']     = ECPay_PaymentMethod::ALL;                         //付款方式:全功能
$obj->Send['IgnorePayment']     = 'BARCODE';                         //付款方式:全功能


//訂單的商品資料
array_push($obj->Send['Items'],
    array('Name' => "代購費用及服務費",
          'Price' => (int) $payMoney,
          'Currency' => "台幣",
          'Quantity' => (int) "1")
);

$db->query("INSERT INTO kmx_problema (`".implode("`,`", $TableDataArr)."`) VALUES ('".implode("','", $TableRowArr)."')");
?>

你的訂單已經送出了，正在前往付款頁面 ...
<br /><br />
<b>你可以隨時在<a href="?page=orderform">訂單記錄</a>查詢的開通情況</b>
<br /><br />
<?php echo $obj->CheckOutString("訂單已成立：請點此到歐付寶付款頁面"); ?>
<br /><br />
<center><h2>多謝惠顧 :-)</h2></center>
<br /><br />

<script>
setTimeout(function(){
	document.getElementById("__ecpayForm").submit();
}, 1000);
</script>