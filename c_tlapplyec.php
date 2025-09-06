<?
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
require_once("lock.php");

//$testing = $User['cnname'] == "SerKo123" || $User['group'] == 1;
?>

<script>
var debug = 0;
</script>

<script type="text/javascript" src="style/js/formcheck.js"></script>
<script type="text/javascript" src="style/js/formcheck.js"></script>
<script type="text/javascript" src="style/js/tlformapply.js?vs=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/style/js/tlformapply.js'); ?>"></script>

<div id="boxes">
      <div id="dlg_ApplyConfirm" class="window">
            <pre><code id="confirmBox"></code></pre>
            <center>
                <h5 style="margin-bottom:20px;"><i class="awe" style="font-style:normal;">含金流手續費30元，合計共 <b id="confirm_price" style="color:red;">0</b> 台幣</i></h5>
                <h6 class="short">
                    <a href="javascript: submitform()"><i class="icon-check awe"> &nbsp;確認無誤</i></a>
                    <a href="#" class="close"><i class="icon-cancel awe"> &nbsp;修正一下</i></a>
                </h6>
            </center>
      </div>
      <div id="mask"></div>
</div>

<!-- Begin White Wrapper -->
<div class="white-wrapper" id="Title_buy"> 
  <!-- Begin Inner -->
  <div class="inner">
    <div class="page-intro line clearfix">
      <h1 class="page-title">申請購買</h1>
    </div>
    <div class="intro"><font color=red size=5>付費問題或其他付款管道請洽Line客服 : @820mdvjx</font></div>
    
	<!--<div class="sidebar">
      <div class="sidebox">
        <h3>匯款帳戶</h3>		
		<p style="line-height:40px;">
        	<i class="icon-asterisk awe colorful" style="background-color:#F36;"> &nbsp;代號</i><br />
            <i class="icon-user awe colorful" style="background-color:#F36;"> &nbsp;戶名</i><br />
            <i class="icon-credit-card awe colorful" style="background-color:#F36;"> &nbsp;帳號</i><br />
            <i class="icon-location awe colorful" style="background-color:#F36;"> &nbsp;分局</i><br />
        </p>
      </div>
      <div class="sidebox">
        <h3>相關連結</h3>
      </div>
    </div>-->
    <?php
    $testing = 1; //$User['usname'] == 'serkotest' || $User['group'] == 1;
    ?>
    <div class="content">
    <!-- Begin Form -->
      <div class="form-container">
        <div class="response"></div>
        <form class="forms" name="applyForm" id="applyForm" action="<?php echo $testing ? "a_tlapplyec_a.php" : "a_tlapplyec_t.php"; ?>" method="post">
          <fieldset>
            <ol>
            <div id="btnAdd" style="width:52%; float:left; display:block; margin-bottom:15px;">
            	<i class="icon-plus-circle awe colorful" style="background-color:green; cursor:pointer;" onclick="editFrame('add')" id="btnAdd"> &nbsp;增加用戶</i>
            </div>
            <div id="btnDel" style="width:48%; float:left; display:none; margin-bottom:15px;">
            	<i class="icon-minus-circle awe colorful" style="background-color:red; cursor:pointer;" onclick="editFrame('del')" id="btnDel"> &nbsp;減少用戶</i>
            </div>
            
            <?
			
				for ($i=1; $i<=4; $i++) {
			?>
            <div id="Frame_<? echo $i; ?>" style="width:48%; float:left; <? echo ($i % 2) ? " margin-right:4%;" : "" ?><? echo ($i > 1) ? " display:none;" : "";?>">
              <li class="form-row text-input-row">
                <input type="text" name="game_id_<? echo $i; ?>" id="game_id_<? echo $i; ?>" class="text-input defaultText mustType mustEngNum" title="勁舞團遊戲帳號 (<? echo $i; ?>) 注意帳號大小寫" />
              </li>
              <li class="form-row text-input-row">
                <select class="selectnavDisplay defaultText mustType" name="plan_<? echo $i; ?>" id="plan_<? echo $i; ?>" onchange="switchplan(<? echo $i; ?>)" cusCaption="購買類型 (<? echo $i; ?>)">
                    <option value="0" selected="selected" >選擇購買類型</option>
                    <option value="1">月數(30天為一個月)</option>
                    <--!<option value="2">次數(一首歌一次)</option>-->
                </select>
              </li>
              <li class="form-row text-input-row">
                <select class="selectnavDisplay" name="limit_<? echo $i; ?>" id="limit_<? echo $i; ?>" onchange="calMoney()">
					<option value="0" selected="selected">---</option>
                </select>
              </li>
            </div>
            <? } ?>

              <?php if ( $testing ) { ?>
                <input type="hidden" name="payMethod" id="payMethod" value="6" />
              <?php } else { ?>
              <li class="form-row text-input-row">
                <select class="selectnavDisplay defaultText mustType" name="payMethod" id="payMethod" onchange="selectPlan()" cusCaption="付款方式">
                  <option value="0">選擇付款方式</option>
                  <option value="1">超商代碼繳費 ( 另加 30 元手續費 )</option>
                  <option value="5">網上 ATM ( 另加 30 元手續費 )</option>
                </select>
              </li>
              <?php } ?>
              
              <li class="form-row text-input-row" style="display: none;">
                <i class="icon-basket awe"></i> 含金流手續費 30 元後，合共 <span id="showPrice">0</span> 台幣
              </li>
                 
              <li class="form-row text-input-row" id="pay_caption_row" style="display:none;">
                <input type="text" name="pay_caption" id="pay_caption" class="text-input defaultText" title="收支備考" maxlength="7" />
                <font color="red"><i class="icon-up-open-1"></i> 請在此輸入<span id="pay_caption_label">收支備考</span>，用作核對匯款人身份</font>
              </li>
              
              <li class="form-row text-input-row">
                <input type="text" name="tel" id="tel" class="text-input defaultText mustType mustTel" title="聯絡電話 ( 必填 )" maxlength="10" cusCaption="聯絡電話"/>
              </li>
              
              <li class="form-row text-input-row">
                <input type="text" name="email" id="email" class="text-input defaultText mustType mustEmail" title="註冊信箱 Email ( 必填 )"/>
              </li>

              <!--<li class="form-row text-input-row">
                <input type="text" name="agent" id="agent" class="text-input defaultText mustEngNum" title="推薦人帳戶" onblur="AutoBig(this)"/>
                <font color="#CCC"><i class="icon-up-open-1"></i> 不能與購買帳號相同，如沒有推薦人請勿填寫<br>&nbsp;　如用戶自身造成系統誤判，官方不予補償</font>
              </li>-->

              <hr width="100%" style="margin-bottom:10px;"/>
              
             <!--<li class="form-row text-area-row">
              	<i class="icon-feather"></i> <span>備註</span>
                <textarea name="message" id="message" class="text-area defaultText" style="margin-top:10px;" title=""></textarea>
              </li>-->

              <li class="form-row hidden-row">
                <input type="hidden" name="total_id" id="total_id" value="1" />
                <input type="hidden" name="payPrice" id="payPrice" value="1" />
              </li>
              
              <li class="button-row">
                <input type="button" value="提交訂單" id="btnSubmit" class="btn-submit" onclick="checkForm()" />
              </li>
            </ol>

		  <input type="submit" id="sub" style="display:none;"/>
          </fieldset>
        </form>
      </div>
    </div>
    
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
  
</div>
<!-- End White Wrapper -->

<script>$(window).load(initPage);</script>