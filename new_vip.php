<?
include_once("include/class_mysql.php");
@mysql_query("SET character_set_results = utf8");
include_once("include/user_data.php");
include_once("include/get_user_status.php");

include_once("a_service.php");

?>
<?php if ($User['num'] > 0) { ?>

<style>
    .vip-card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px; /* 卡片之間的間距 */
        padding: 10px 0;
        justify-content: center;
    }
    .vip-card {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        flex: 1 1 calc(33.333% - 20px); /* 響應式佈局，每行最多3張卡片 */
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        min-width: 280px; /* 卡片最小寬度 */
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden; /* 確保內容不超出圓角 */
    }
    .vip-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .vip-card .card-header {
        padding: 15px 20px;
        background-color: #f9f9f9;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .vip-card h5 {
        margin: 0;
        font-size: 1.1em;
        color: #333;
        font-weight: 600;
    }
    .vip-card .card-body {
        padding: 20px;
        flex-grow: 1;
        font-size: 0.95em;
        color: #555;
    }
    .vip-card .card-body .info-item {
        margin-bottom: 12px;
    }
    .vip-card .card-footer {
        padding: 15px 20px;
        background-color: #f9f9f9;
        border-top: 1px solid #e0e0e0;
        text-align: right;
        min-height: 60px; /* 統一頁腳高度 */
    }
    .vip-card .status-tag {
        font-size: 0.8em;
        padding: 5px 12px;
        border-radius: 15px;
        color: #fff;
        font-weight: 500;
    }
    .vip-card .status-tag.active { background-color: #27ae60; } /* 綠色 */
    .vip-card .status-tag.expired { background-color: #c0392b; } /* 深紅色 */
    .vip-card .status-tag.pending { background-color: #f39c12; } /* 橘色 */
    .vip-card .status-tag.low { background-color: #d35400; } /* 橘紅色 (次數偏低) */

    /* 空卡片的特殊樣式 */
    .vip-card.empty-slot {
        background: repeating-linear-gradient(-45deg, #fdfdfd, #fdfdfd 10px, #f9f9f9 10px, #f9f9f9 20px);
        justify-content: center;
        align-items: center;
        text-align: center;
        min-height: 220px; /* 確保與其他卡片同高 */
    }
    .vip-card.empty-slot .add-button {
        padding: 12px 24px;
        border: 2px dashed #ccc;
        border-radius: 8px;
        color: #888;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .vip-card.empty-slot .add-button:hover {
        background: #e9e9e9;
        color: #555;
        border-color: #aaa;
    }

    /* 響應式調整 */
    @media (max-width: 960px) { .vip-card { flex: 1 1 calc(50% - 20px); } }
    @media (max-width: 600px) { .vip-card { flex: 1 1 100%; } }
</style>

<div class="gray-wrapper"> 
  <br>
  <div class="full-width"> 
    <h3 class="line" align="center">管理綁定帳號 ( 目前最多六個 )</h3>
    
    <div class="vip-card-container">
      <?php 
        for ($i = 0; $i < 6; $i++) {
            // 判斷這個卡槽是否為空
            if (empty($Client[$i]['name'])) {
      ?>
          <div class="vip-card empty-slot">
              <a href="#dlg_Add" name="modal" class="add-button">
                  <i class="icon-plus"></i> 新增綁定帳號
              </a>
          </div>
      <?php
            } else {
                // 如果卡槽有帳號，則進行狀態判斷
                $clientValid = getValidClient($Client[$i]['name']);
                $Quota = "";
                $status_text = $UserType[$Client[$i]['type']*3 + 2];
                $status_class = 'active'; // 預設為啟用

                if ($clientValid == 1) { // 次數 > 20
                    $Quota = "剩餘 " . $Client[$i]['atimes'] . " 次";
                } elseif ($clientValid == 4) { // 次數 <= 20
                    $Quota = "剩餘 " . $Client[$i]['atimes'] . " 次 (偏低)";
                    $status_text = "次數偏低";
                    $status_class = 'low';
                } elseif ($clientValid == 2) { // 日數用戶
                    $Quota = "到期日: " . date('Y-m-d', strtotime($Client[$i]['timeend']));
                } elseif ($clientValid == 3) { // 無限用戶
                     $Quota = "榮譽無限期會員";
                } elseif ($clientValid == 5) { // 到期寬限期
                    $Quota = "已到期，等待系統自動解除";
                    $status_text = "等待解除";
                    $status_class = 'pending';
                } else { // 已到期或無效
                    $Quota = "此帳號已到期或狀態無效";
                    $status_text = "已到期";
                    $status_class = 'expired';
                }
      ?>
      <div class="vip-card">
        <div class="card-header">
            <h5><?php echo $Client[$i]['name']; ?></h5>
            <span class="status-tag <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
        </div>
        <div class="card-body">
            <div class="info-item" id="status_return_<?php echo $i; ?>">
                <i class="<?php echo $UserType[$Client[$i]['type']*3 + 1]; ?>"></i>&nbsp;
                <strong>狀態:</strong> <?php echo $Quota; ?>
            </div>
        </div>
        <div class="card-footer">
            <?php
              // 只有在已到期、寬限期或狀態無效時，才顯示解除按鈕
              if ($clientValid <= 0 || $clientValid == 5) {
                  echo "<a href='#' class='button small red' onClick=\"loadContent('?act=del_vip&gid=" . $Client[$i]['name'] . "', 'status_return_" . $i . "'); return false;\">立即解除</a>";
              }
            ?>
        </div>
      </div>
      <?php 
            } // else 結束
        } // for 迴圈結束
      ?>
    </div>
  </div>
  <div class="clear"></div>
</div>
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
    // 確保 modal 功能正常
    $(document).ready(function() {
        $('a[name=modal]').click(function(e) {
            e.preventDefault();
            var id = $(this).attr('href');
            var maskHeight = $(document).height();
            var maskWidth = $(window).width();
            $('#mask').css({'width':maskWidth,'height':maskHeight});
            $('#mask').fadeIn(500); 
            $('#mask').fadeTo("slow",0.8); 
            var winH = $(window).height();
            var winW = $(window).width();
            $(id).css('top',  winH/2-$(id).height()/2);
            $(id).css('left', winW/2-$(id).width()/2);
            $(id).fadeIn(1000); 
        });
        
        $('.window .close').click(function (e) { 
            e.preventDefault();
            $('#mask, .window').hide();
        });  
        
        $('#mask').click(function () {
            $(this).hide();
            $('.window').hide();
        });
    });
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