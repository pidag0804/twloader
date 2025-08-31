<?
error_reporting(0);

$_GET['pms'] = $page_pms;
require_once("include/content_head.php");

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

?>
<style>
#order-table {
  width: 100%;
}

#order-table thead {
  color: #e6c555;
}

#order-table tbody td {
  padding: 5px;
}

#order-table tr {
  border-bottom: 1px dotted #666;
}

#order-table span.pno {
  color: red;
}

#order-table hr {
  margin: 5px 0;
}

#__paymentButton,
#order-table .pay-button {
  max-width: 100px;
}

</style>

    <script language="javascript">
        $("#various5").fancybox({
            'width': '75%',
            'height': '75%',
            'autoScale': true,
            'transitionIn': 'none',
            'transitionOut': 'none',
            'type': 'iframe'
        });
    </script>

    <!-- Begin White Wrapper -->
    <div class="white-wrapper">
        <!-- Begin Inner -->
        <div class="inner">
            <h2 class="line">訂單記錄</h2>
            <div class="teaser-navigation">
                <div class="clear"></div>
            </div>
            <table width="100%" id="order-table">
                <thead>
                    <tr>
                        <td>編號</td>
                        <td>付款方式</td>
                        <td>購買內容</td>
                        <td>金額</td>
                        <td>日期</td>
                        <td>訂單狀態</td>
                    </tr>
                </thead>
                <tbody>
                <?php
      $PayMString = array(0, "超商代碼繳費", "銀行手寫匯款", "郵局手寫匯款", "提款機轉帳", "網上 ATM", "綠界金流");
      $PayConvert = array(0, 4, 2, 3, 1, 5, 6);

      $query = $db->query("SELECT * FROM kmx_problema WHERE `buyer` = ".$User['num']." ORDER BY `id` DESC");
      $count_query = $db->num_rows($query);

      if ( $count_query == 0 ) {
      ?>
      <tr style="height: 100px;">
        <th colspan="6">沒有任何訂單記錄，請先<a href="http://www.tlmoo.com/twloader/?page=apply">申請購買</a></th>
      </tr>
      <?php
      }

      for($i=0; $i < $count_query; $i++) {
        $data = $db->fetch_array($query);
        $paymethod = $PayConvert[$data['buyform']];
      ?>
                    <tr>
                        <td>
                            <? echo $data['id']; ?>
                        </td>
                        <td>
                            <? echo $PayMString[$paymethod]; ?>
                        </td>
                        <td>
                            <?php
                      $accnum = $data['accnumber'];
                      echo "帳戶數量 : ".$accnum."";
                      for ( $j = 1; $j <= $accnum; $j++) {

                           if ($data["type".$j] == 0) {
        echo "<br />- " . $data["acc".$j] . " (" . ($data["day".$j] + 1) . " 個月)";
    }

    if ($data["type".$j] == 1) {
        $timesOutput = ($data["times".$j] == 0) ? "400次" : "1200次";
        echo "<br />- " . $data["acc".$j] . " (" . $timesOutput . ")";
    }
	


                      }

                      if ( $paymethod == 1 ) {
                        echo "<hr />超商代碼繳費代碼: <span class='pno'>".$data["specialnumber"]."</span>";
                        if ( $data["done"] == 0 ) {
                          echo "<br />在三天內持此繳費代碼到全省7-11/全家超商/萊爾富超商繳費 [<a href='http://www.tlmoo.com/twloader/?page=pay_steps'>詳細步驟</a>]";
                        }
                      } else if ( $paymethod == 5 && $data["done"] == 0 ) {
                          $postData = array(
                            "mer_id" => '6352',
                            "payment_type" => "web_atm",
                            "od_sob" => $data['id'],
                            "amt" => $data['money'],
                            "return_url" => "http://www.tlmoo.com/twloader/liftet/liftet.php?wa=1",
                          );

                          $checkmacvalue = formatRowToCheck($postData, "a0992309d0974c04", "2eff3002671cedc8");
                          $postData["checkmacvalue"] = $checkmacvalue;
                          ?>
                          <hr />
                          <form method="POST" action="https://ecbank.com.tw/gateway_v2.php">
                          <?php
                          foreach( $postData as $key =>$value ) {  // link to string
                            if(!(empty($value))) {
                              echo "<input type='hidden' name='$key' value='$value'>";
                            }
                          }
                          ?>
                          <input class="pay-button" type="submit" value="立即付款" />
                          </form>
                      <?php } else if ( $paymethod == 6 && $data["done"] == 0 ) {
                        /*
                        include_once('include/AllPay.Payment.Integration.php');
                        $obj = new AllInOne();

                        $obj->ServiceURL  = "https://payment.allpay.com.tw/Cashier/AioCheckOut ";
                        $obj->HashKey     = 'cNlD0JOWqL50FwFb';
                        $obj->HashIV      = '009AW7psuTKeRWOu';
                        $obj->MerchantID  = '1039181';

                        $obj->Send['ReturnURL']         = "http://www.tlmoo.com/twloader/liftet/liftet.php?wa=3";    //付款完成通知回傳的網址
                        $obj->Send['ClientBackURL']     = "http://www.tlmoo.com/twloader/?page=orderform";
                        $obj->Send['MerchantTradeNo']   = $data['id'];                                //訂單編號
                        $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                        //交易時間
                        $obj->Send['TotalAmount']       = $data['money'];                             //交易金額
                        $obj->Send['TradeDesc']         = "TwLoader Service";                         //交易描述
                        $obj->Send['ChoosePayment']     = PaymentMethod::ALL;                         //付款方式:全功能

                        //訂單的商品資料
                        array_push($obj->Send['Items'],
                            array('Name' => "TwLoader 服務",
                                  'Price' => (int) $payMoney,
                                  'Currency' => "台幣",
                                  'Quantity' => (int) "1")
                        );

                        echo $obj->CheckOutString("立即付款");
                        */
                      } ?>
                        </td>
                        <td>
                            <? echo $data['money']; ?>
                        </td>
                        <td>
                            <? echo $data['buytime']; ?>
                        </td>
                        <td>
                            <?php
                            switch ($data["done"]) {
                              case 0: echo "未完成"; break;
                              case 1: echo "已取消"; break;
                              case 2: echo "完成交易"; break;
                              default: echo "未知"; break;
                            }
                      ?>
                        </td>
                    </tr>
                    <? } ?>
                  </tbody>
            </table>
            <div class="clear"></div>
        </div>
        <!-- End Inner -->
    </div>
    <!-- End White Wrapper -->