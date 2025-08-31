<?php
/**
*   一般產生訂單(全功能)範例，參數說明請參考SDK技術文件(https://www.allpay.com.tw/Content/files/allpay_047.pdf)
*/
    
//載入SDK(路徑可依系統規劃自行調整)
include('AllPay.Payment.Integration.php');

try {
    
	$obj = new AllInOne();

    //服務參數
    $obj->ServiceURL  = "https://payment.allpay.com.tw/Cashier/AioCheckOut ";
    $obj->HashKey     = 'cNlD0JOWqL50FwFb';
    $obj->HashIV      = '009AW7psuTKeRWOu';
    $obj->MerchantID  = '1039181';

    //Test
    /*
    $obj->ServiceURL  = "https://payment-stage.allpay.com.tw/Cashier/AioCheckOut/V2";
    $obj->HashKey     = '5294y06JbISpM5x9';
    $obj->HashIV      = 'v77hoKGq4kWxNNIS';
    $obj->MerchantID  = '2000132';
    */
    
    //基本參數(請依系統規劃自行調整)
    $obj->Send['ReturnURL']         = "http://www.allpay.com.tw/receive.php" ;    //付款完成通知回傳的網址
    $obj->Send['MerchantTradeNo']   = "Test".time() ;                             //訂單編號
    $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                        //交易時間
    $obj->Send['TotalAmount']       = 2000;                                       //交易金額
    $obj->Send['TradeDesc']         = "good to drink" ;                           //交易描述
    $obj->Send['ChoosePayment']     = PaymentMethod::ALL;                        //付款方式:全功能

    //訂單的商品資料
    array_push($obj->Send['Items'],
        array('Name' => "歐付寶黑芝麻豆漿",
              'Price' => (int)"2000",
              'Currency' => "元",
              'Quantity' => (int) "1",
              'URL' => "dedwed")
    );

    /*
    $obj->SendExtend['Desc_1']            = '';      //交易描述1 會顯示在超商繳費平台的螢幕上。預設空值
    $obj->SendExtend['Desc_2']            = '';      //交易描述2 會顯示在超商繳費平台的螢幕上。預設空值
    $obj->SendExtend['Desc_3']            = '';      //交易描述3 會顯示在超商繳費平台的螢幕上。預設空值
    $obj->SendExtend['Desc_4']            = '';      //交易描述4 會顯示在超商繳費平台的螢幕上。預設空值
    $obj->SendExtend['PaymentInfoURL']    = '';      //預設空值
    $obj->SendExtend['ClientRedirectURL'] = '';      //預設空值
    $obj->SendExtend['StoreExpireDate']   = '';      //預設空值
    */

    //產生訂單(auto submit至AllPay)
    $obj->CheckOut();
  

} catch (Exception $e) {
	echo $e->getMessage();
} 

?>