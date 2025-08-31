<?
include_once("include/class_mysql.php");
@mysql_query("SET character_set_results = utf8");
include_once("include/user_data.php");
include_once("include/get_user_status.php");

include_once("a_service.php");

?>
<?php if ($User['num'] > 0) { ?>
<!-- Begin gray Wrapper -->
<div class="gray-wrapper"> 
  <!-- Begin Inner -->
  <br>
  <div class="full-width"> 
    <h3 class="line" align="center">管理綁定帳號 ( 目前最多六個 )</h3>
    <div class="grid">
      <?php 
        for ($i = 0; $i < 6; $i++) {
          $Quota = "";
          $clientValid = getValidClient($Client[$i]['name']);
          if ($clientValid == 1 || $clientValid == 4) {
            $Quota = " " . $Client[$i]['atimes'];
          } elseif ($clientValid == 2) {
            $Quota = " " . $Client[$i]['timeend'];
          } elseif ($clientValid == 5) {
            $Quota = "24小時內自動解除綁定 | <a href='#' onClick=\"loadContent('?act=del_vip&gid=" . $Client[$i]['name'] . "', 'status_return_" . $i . "');\">解除</a>";
          }
      ?>
      <article class="post">
        <header class="info">
          <div class="date white">
            <div class="day"><i class="<?php echo $UserType[$Client[$i]['type']*3 + 1]; ?>"></i></div>
            <div class="month"><?php echo $UserType[$Client[$i]['type']*3 + 2]; ?></div>
          </div>
          <div class="details">
            <h6><?php echo (empty($Client[$i]['name'])) ? $UnknowName : $Client[$i]['name']; ?></h6>
            <div class="meta">
              <div class="category" id="status_return_<?php echo $i; ?>">
                <?php 
                  if (!empty($Client[$i]['name'])) {
                    echo ($clientValid <= 0 && !empty($Client[$i]['name'])) ? 
                    "已到期：等待解除綁定中 | <a id='auto' href='#' onClick=\"loadContent('?act=del_vip&gid=" . $Client[$i]['name'] . "', 'status_return_" . $i . "');\">解除</a>" : 
                    $UserType[$Client[$i]['type']*3] . $Quota;
                  } else {
                    echo "暫無綁定帳號";
                  }
                ?>
              </div>
            </div>
          </div>
        </header>
      </article>
      <?php 
        }
      ?>
    </div>
  </div>
  <div class="clear"></div>
</div>
<!-- End gray Wrapper -->

<div id="boxes">
  <section id="dlg_Login" class="window">
    <header>
      <h2>登入 TwLoader</h2>
      <a onClick="window.location='#'" class="close">關閉</a>
    </header>
    <p>
      <form class="forms" action="" method="post" id="frmLogin">
        <fieldset>
          <ol>
            <li class="form-row text-input-row">
              <input type="text" name="login_ac" id="login_ac" class="text-input defaultText mustType" title="用戶名稱"/>
            </li>
            <li class="form-row text-input-row">
              <input type="password" name="login_pw" id="login_pw" class="text-input defaultText mustType" title="用戶密碼"/>
            </li>
            <li class="button-row" style="text-align:right;">
              <span style="margin-right:10px"><a onClick="window.location='?page=register'">申請帳戶</a></span>
              <span style="margin-right:10px"><a onClick="window.location='?page=lostpw'">忘記密碼</a></span>
              <span style="margin-right:10px; color:#C33;" id="login_ajax_return"><i></i></span>
              <a href="javascript: checkLoginForm()"><i class="awe colorful" style="background-color:#09F;">登入</i></a>
            </li>
          </ol>
        </fieldset>
      </form>
    </p>
  </section>
  <div id="mask"></div>
</div>

<div id="boxes">
      <div id="dlg_Add" class="window">
          增加用戶 | <a OnClick="window.location='#'"class="close"/>關閉</a>
          <p>
            <form class="forms" action="" method="post" id="frmAdd" >
              <fieldset>
                <ol>
                  <li class="form-row text-input-row">
                    <input type="text" name="name" id="gameId" class="text-input defaultText mustType" title="按此輸入購買遊戲帳號 ( 注意帳號大小寫 )"/>
                  </li>
                  <li style="display:none;"><input type="text" /></li>
                  <li class="button-row" style="text-align:right;" >
                    <span style="margin-right:10px" id="add_ajax_return"><i>逾期後才可解除綁定</i></span>
                    <a href="javascript: loadContent('?act=add_vip&gid=' + get_value('gameId'), 'add_ajax_return');"><i class="awe colorful" style="background-color:#09F;">確認</i></a>
                  </li>
                </ol>
              </fieldset>
            </form>
          </p>
      </div>
      <div id="mask"></div>
</div>


    <script>
		$(window).load(initPage);
    </script>
<?php } ?>

<script language="javascript">
	$("#various5").fancybox({
		'width': '75%',
		'height': '75%',
        'autoScale'     	: true,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});
</script>