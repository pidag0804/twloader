<?php
include_once('class_mysql.php');

$isWebATM = $_GET['wa'] == 1;
$isNewCVS = $_GET['wa'] == 2;
$isAllPay = $_GET['wa'] == 3;
$isOldCVS = !empty($_POST['MerchantID']) && !$isAllPay;

$retResult = 0;
$customId = 0;

if ( $isAllPay ) {
    include_once('../include/AllPay.Payment.Integration.php');
/*
* 接收訂單資料產生完成的範例程式碼。
*/
try {
    $oPayment = new AllInOne();
    /* 服務參數 */
    $oPayment->HashKey = "cNlD0JOWqL50FwFb";
    $oPayment->HashIV = "009AW7psuTKeRWOu";
    $oPayment->MerchantID = "1039181";
    /* 取得回傳參數 */
    $arFeedback = $oPayment -> CheckOutFeedback();
    /* 檢核與變更訂單狀態 */
    if (sizeof($arFeedback) > 0) {
        foreach($arFeedback as $key => $value) {
            switch ($key) {
                /* 支付後的回傳的基本參數 */
                case "MerchantID": $mer_id = $value; break;
                case "MerchantTradeNo": $payno = $value; break;
                case "PaymentDate": $proc_date = $value; break;
                case "PaymentType": $payment_type = $value; break;
                //case "PaymentTypeChargeFee": $szPaymentTypeChargeFee = $value; break;
                case "RtnCode": $succ = $value; break;
                //case "RtnMsg": $szRtnMsg = $value; break;
                case "SimulatePaid": $tac = $value; break;
                case "TradeAmt": $amt = $value; break;
                //case "TradeDate": $szTradeDate = $value; break;
                case "TradeNo": $od_sob = $value; break;
                //case "PayAmt": $szPayAmt = $value; break;
                //case "RedeemAmt": $szRedeemAmt = $value; break;
                default: break;
            }
        }
    } else {
        die('0|Fail');
    }

    $tsr = '';
    $payfrom = 'default';
    $proc_time = '';
    $customId = $payno;

} catch (Exception $e) {
    // 例外錯誤處理。
    print '0|'.$e -> getMessage();
}
} else if ( $isWebATM || $isNewCVS ) {
    $mer_id = $_POST['mer_id'];
    $payment_type = $_POST['payment_type'];
    $tsr = $_POST['tsr'];
    $od_sob = $_POST['od_sob'];
    $payno = $_POST['payno'];
    $amt = $_POST['amt'];
    $succ = $_POST['succ'];
    $payfrom = $_POST['payfrom'];
    $proc_date = $_POST['proc_date'];
    $proc_time = $_POST['proc_time'];
    $tac = $_POST['tac'];
    $isVirtual = $_POST['trigger'] == "virtual";

    $customId = $od_sob;

    //valid
    $key = 'X44uH0CFq3oiM7UyU53npPn8';
    $serial = trim($proc_date.$proc_time.$tsr);
    $ecbank_gateway = 'https://ecbank.com.tw/web_service/get_outmac_valid.php';
    $post_str ='key='.$key.
               '&serial='.$serial.
               '&tac='.$tac;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$ecbank_gateway);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post_str);
    $strAuth = curl_exec($ch);
    if (curl_errno($ch)){
        $strAuth = false;
    }
    curl_close($ch);
    if( $strAuth != 'valid=1' ){
        die("oh no!");
    }

} else if ( $isOldCVS ) {
    $mer_id = $_POST['MerchantID'];
    $payment_type = $_POST['PaymentType'];
    $tsr = '';
    $od_sob = $_POST['TradeNo'];
    $payno = $_POST['MerchantTradeNo'];
    $amt = $_POST['TradeAmt'];
    $succ = $_POST['RtnCode'];
    $payfrom = 'default';
    $proc_date = $_POST['PaymentDate'];
    $proc_time = '';
    $tac = $_POST['SimulatePaid'];

    $customId = $payno;
} else {
    die("opps!");
}

$db->query("insert into tlliftet (`mer_id`,`payment_type`,`tsr`,`od_sob`,`payno`,`amt`,`succ`,`payfrom`,`proc_date`,`proc_time`,`tac`) values ('".$mer_id."','".$payment_type."','".$tsr."','" .$od_sob. "','" .$payno. "','" .$amt. "','" . $succ . "','".$payfrom."','" .$proc_date. "','".$proc_time."','" .$tac. "')");

$db->free_result($query);

$j = $dbb->fetch_array($dbb->query("SELECT * from kmx_proload WHERE `id` = '1' "));
$d = $dbb->fetch_array($dbb->query("SELECT * from kmx_problema WHERE `id` = '" . $customId . "' "));

$gtimes = array(
    $j['gtimesa'],
    $j['gtimesb']
);

if ($amt != $d['money'])
    $error = 1;

if ($succ != '1')
    $error = 1;

if (($error == 0) && ( $d['done'] != 2) ) {

	//============================================================================================================
    $dbb->query("UPDATE kmx_problema SET `done` = '2' WHERE `id`='" . $d['id'] . "'");
    $dbb->query("UPDATE kmx_problema SET `why` = 'system' WHERE `id`='" . $d['id'] . "'");
    $dbb->query("UPDATE kmx_problema SET `editby` = 'system' WHERE `id`='" . $d['id'] . "'");
    //============================================================================================================

    $nowday       = date("Y-m-d");
    $actionnumber = 7;
    if ($d['buyform'] == 3) {
        $d['money']   = $d['money'] / $m8591;
        $actionnumber = 4;
    } elseif ($d['buyform'] == 4) {
        $d['money']   = $d['money'] - 30;
        $actionnumber = 7;
    }
    for ($index = 1; $index <= $d['accnumber']; $index++) {
        if ($d['type' . $index] == 1) {
            $buytype[$index]  = 0;
            $buytimes[$index] = $gtimes[$d['times' . $index]];
            $buytime[$index]  = $gtimes[$d['times' . $index]];
            $newday[$index]   = strtotime($nowday);
        } else {
            $buytype[$index]  = 1;
            $saveday[$index]  = ($d['day' . $index] + 1) * 30;
            $buytimes[$index] = 0;
            $newday[$index]   = (strtotime($nowday) + (($saveday[$index] + 1) * 86400));
            if (($j['newfromtype'] == 0) && ($j['newfromtimes'] <> 0))
                $buytimes[$index] = $j['newfromtimes'];
        }
        $rawUserName = $d['acc' . $index];
        $userName = strtolower($rawUserName);

        $query = $dbb->query("SELECT COUNT(*) from kmx_usera where LOWER(`name`) = '" . $userName . "'");
        $num   = $dbb->result($query, 0);
        $dbb->free_result($query);
        if ($num == 0) {
            $dbb->query("insert into kmx_usera (`name`,`nickname`,`password`,`type`,`fristlogin`,`lastlogin`,`playtimes`,`atimes`,`timeend`,`editby`) values('" . $rawUserName . "','none','none','" . $buytype[$index] . "','" . date("Y-m-d H:i:s") . "','" . date("Y-m-d H:i:s") . "','0','" . $buytimes[$index] . "','" . date('Y-m-d', $newday[$index]) . "','system')");
            $dbb->free_result($query);
        } elseif ($num == 1) {
            $query = $dbb->query("SELECT * from kmx_usera where LOWER(`name`) = '" . $userName . "'");
            $n     = $dbb->fetch_array($query);
            $dbb->free_result($query);
            if (time() > strtotime($n["timeend"]))
                $n["timeend"] = $nowday;
            if ($d['type1'] == 1) {
                $newday[$index]   = strtotime($n["timeend"]);
                $buytimes[$index] = $buytimes[$index] + $n["atimes"];
            } else {
                $newday[$index]   = strtotime($n["timeend"]) + ($saveday[$index] + 1) * 86400;
                $buytimes[$index] = $n["atimes"];
            }
            $dbb->query("UPDATE kmx_usera SET `type` = '" . $buytype[$index] . "' WHERE LOWER(`name`)='" . $userName . "'");
            $dbb->query("UPDATE kmx_usera SET `atimes` = '" . $buytimes[$index] . "' WHERE LOWER(`name`)='" . $userName . "'");
            $dbb->query("UPDATE kmx_usera SET `timeend` = '" . date('Y-m-d', $newday[$index]) . "' WHERE LOWER(`name`)='" . $userName . "'");
            $dbb->query("UPDATE kmx_usera SET `editby` = 'system' WHERE LOWER(`name`)='" . $userName . "'");
            $dbb->free_result($query);
        }
        $dbb->query("insert into kmx_finala (`userid`,`money`,`type`,`firstlogin`,`timeend`,`addday`,`atimes`,`other`,`editby`,`action`,`code`) values('" . $rawUserName . "','" . $d['money'] . "','" . $buytype[$index] . "','" . date("Y-m-d H:m:s") . "','" . date('Y-m-d', $newday[$index]) . "','" . $saveday[$index] . "','" . $buytime[$index] . "','','system','" . $actionnumber . "','" . $d['id'] . "')");
        $dbb->free_result($query);
    }

    //============================================================================================================

    if ($d['who'] != "") {
        $rawUserName = $d['who'];
        $userName = strtolower($rawUserName);

        $query = $dbb->query("select * from kmx_usera where LOWER(`name`) = '" . $userName . "'");
        if ($dbb->num_rows($query) <= 0) {
            $newplaynumber = $j['newfromtimes'];
            if ($j['newfromtype'] <= 4)
                $j['newfromtimes'] = 0;
            $dbb->query("insert into kmx_usera (`name`,`nickname`,`password`,`type`,`fristlogin`,`lastlogin`,`playtimes`,`atimes`) values('" . $rawUserName . "','none','none','" . $j['newfromtype'] . "','" . date("Y-m-d H:i:s") . "','" . date("Y-m-d H:i:s") . "','0','" . $j['newfromtimes'] . "')");
            $dbb->query("UPDATE kmx_usera SET `timeend` = '" . date("Y-m-d") . "' WHERE LOWER(`name`)='" . $userName . "'");
            if ($j['newfromtype'] == 1) {
                $newday = strtotime($nowday) + ($j['newfromtimes'] + $newplaynumber) * 86400;
                $dbb->query("UPDATE kmx_usera SET `timeend` = '" . date('Y-m-d', $newday) . "' WHERE LOWER(`name`) ='" . $userName . "'");
            }
            $dbb->free_result($query);
        }
        $query = $dbb->query("SELECT * from kmx_usera where LOWER(`name`) = '" . $userName . "'");
        $o     = $dbb->fetch_array($query);
        $dbb->free_result($query);
        //$nowday = date("Y-m-d");
        if (strtotime($o["timeend"]) < strtotime(date("Y-m-d"))) {
            $newdays = strtotime(date("Y-m-d")) + 3 * 86400;
        } else {
            $newdays = strtotime($o['timeend']) + 3 * 86400;
        }
        //if (time() > strtotime($o["timeend"])) $o["timeend"] = $nowday ;
        //$newdays = strtotime($o['timeend'])+3*86400;
        $dbb->query("UPDATE kmx_usera SET `timeend` = '" . date('Y-m-d', $newdays) . "' WHERE LOWER(`name`) = '" . $userName . "'");
        $dbb->query("UPDATE kmx_usera SET `type` = '1' WHERE LOWER(`name`) = '" . $userName . "'");
        $dbb->free_result($query);
        $dbb->query("insert into kmx_finala (`userid`,`money`,`type`,`firstlogin`,`timeend`,`addday`,`atimes`,`other`,`editby`,`action`) values('" . $userName . "','0','1','" . date("Y-m-d H:i:s") . "','" . date('Y-m-d', $newdays) . "','3','0','','system','8')");
        $dbb->free_result($query);
    }
    $retResult = 1;
} else {
    $retResult = 0;
}

$retResult = $retResult || $d['done'] == 2;

if ( $isWebATM ) {
?>
    <!DOCTYPE HTML>
    <html lang="en-US">
    <head><meta charset="UTF-8"></head>
    <body>
    <?php if ( $retResult == 1 ) { ?>
        閤下已成功付款及開通帳戶，正在返回主頁..<br />
        <a href="http://www.tlmoo.com/">按此</a>立即返回
        <script>
        setTimeout(function(){
            window.location.href = "http://www.tlmoo.com/";
        }, 2000);
        </script>

    <?php } else { ?>
        抱歉未能成功付款，請<a href="http://www.tlmoo.com/twloader/index.php?page=inbox">與我們的客服聯絡</a>或<a href="http://www.tlmoo.com/">返回主頁</a>
    <?php } ?>
    </body>
    </html>
<?php
} else if ( $isNewCVS ) {
    echo $retResult == 1 ? "OK" : "ERROR";
} else if ( $isOldCVS ) {
    echo $retResult == 1 ? "1|OK" : "0|ERROR";
} else if ( $isAllPay ) {
    echo $retResult == 1 ? '1|OK' : '0|Fail';
}
?>