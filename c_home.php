<?php

$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
require_once("lock.php");
//require_once("1.php");
//include_once("2.php");
?>

<style>
    /* 卡片標題容器 */
    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .card-header-flex h2 {
        margin-bottom: 0 !important; /* 覆蓋 h2 預設的 margin */
    }
    /* ***** 新增的修正 ***** */
    .card-header-flex h2.line {
        flex-grow: 1; /* 讓標題元素自動伸展，填滿可用寬度 */
    }
    .card-header-flex a i {
        font-size: 1.3em;
        color: #6094b4;
        transition: color 0.2s;
    }
    .card-header-flex a:hover i {
        color: #b66b77;
    }
    
    /* 通用卡片基礎樣式 */
    .custom-card {
        background: #ffffff;
        border: 1px solid #e9e9e9;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        padding: 20px;
        height: 100%; /* 讓卡片在網格佈局中等高 */
        box-sizing: border-box;
    }

    /* 會員資料卡片內的列表 */
    .user-info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .user-info-list li {
        display: flex;
        justify-content: space-between;
        padding: 12px 5px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.95em;
    }
    .user-info-list li:last-child {
        border-bottom: none;
    }
    .user-info-list .info-label {
        color: #666;
    }
    .user-info-list .info-value {
        color: #333;
        font-weight: 600;
    }
    .user-info-list .info-value.unread {
        background-color: #e44d26;
        color: #fff;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.9em;
    }

    /* 官網公告卡片 */
    .announcement-card {
        display: block;
        padding: 12px 15px;
        border: 1px solid #e9e9e9;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
        background: #fdfdfd;
    }
    .announcement-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.08);
        border-color: #6094b4;
    }
    .announcement-card .date {
        font-size: 0.85em;
        font-weight: 600;
        color: #6094b4;
        margin-right: 12px;
        background-color: #eaf2f8;
        padding: 3px 8px;
        border-radius: 5px;
    }
    .announcement-card .topic {
        color: #333;
        font-weight: 500;
    }

    /* 重點功能卡片 */
    .feature-card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        padding: 20px 0;
    }
    .feature-card {
        background: #ffffff;
        border: 1px solid #e9e9e9;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        flex: 1 1 calc(33.333% - 20px);
        box-sizing: border-box;
        padding: 25px 20px;
        text-align: center;
        min-width: 280px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .feature-card .feature-icon {
        font-size: 2.2em;
        width: 65px;
        height: 65px;
        line-height: 65px;
        border-radius: 50%;
        background-color: #eaf2f8;
        color: #6094b4;
        display: inline-block;
        margin-bottom: 15px;
    }
    .feature-card h4 {
        margin-bottom: 10px;
        font-size: 1.15em;
        color: #333;
    }
    .feature-card p {
        font-size: 0.95em;
        color: #666;
        line-height: 1.6;
        padding: 0;
    }
    @media (max-width: 960px) { .feature-card { flex: 1 1 calc(50% - 20px); } }
    @media (max-width: 600px) { .feature-card { flex: 1 1 100%; } }
</style>
<?php if ( $User['num'] > 0 ) { ?>
<div class="gray-wrapper"> 
  <div class="inner clearfix">
    <div class="one-half"> 
      <div class="card-header-flex">
        <h2 class="line">會員資料</h2>
      </div>
      <div class="custom-card">
        <ul class="user-info-list">
        <?php
            $UserDataCaption = array('會員編號', '會員暱稱', '會員級別', '未讀短訊');
            $UserData = array($User['num'], $User['cnname'], $UserGroup[$User['group']], $HaveUnread);
            for ($i = 0; $i < 4; $i++) {
        ?>
            <li>
                <span class="info-label"><?php echo $UserDataCaption[$i]; ?></span>
                <span class="info-value <?php if($i == 3 && $HaveUnread > 0) echo 'unread'; ?>">
                    <?php echo $UserData[$i] . ($i == 3 && $HaveUnread > 0 ? ' 則' : ''); ?>
                </span>
            </li>
        <?php } ?>
        </ul>
      </div>
    </div>      
      
    <div class="one-half last"> 
      <div class="card-header-flex">
        <h2 class="line">效期查詢</h2>
      </div>
      <div class="custom-card">
        <?php
          $bound_count = 0;
          if (isset($Client) && is_array($Client)) {
              foreach ($Client as $client_item) { if (!empty($client_item['name'])) { $bound_count++; } }
          }

          if ($bound_count > 0):
        ?>
            <div class="account-summary-cards">
            <?php
                foreach ($Client as $account):
                    if (!empty($account['name'])):
            ?>
                        <div class="mini-card" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; border: 1px solid #e0e0e0; border-radius: 6px; margin-bottom: 8px; background: #fdfdfd;">
                            <div style="font-weight: 600; color: #333;"><i class="icon-user" style="color: #6094b4; margin-right: 6px;"></i><?php echo htmlspecialchars($account['name']); ?></div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span style="font-size: 0.9em; color: #555;">
                                    <?php
                                        if ($account['type'] == 1) { echo "到期日: " . date('Y-m-d', strtotime($account['timeend'])); } 
                                        elseif ($account['type'] == 0) { echo "剩餘次數: " . htmlspecialchars($account['atimes']) . " 次"; } 
                                        else { echo "無限型榮譽會員"; }
                                    ?>
                                </span>
                                <?php
                                    $show_renew_button = false;
                                    if ($account['type'] == 1) {
                                        $timeend = strtotime($account['timeend']); $now = time();
                                        $diff_days = ($timeend - $now) / (60 * 60 * 24);
                                        if ($diff_days < 10 && $diff_days > 0) { $show_renew_button = true; }
                                    }
                                    if ($account['type'] == 0 && $account['atimes'] < 100) { $show_renew_button = true; }
                                    if ($show_renew_button) { echo '<a href="?page=tlapplyec" class="button small green" style="margin: 0; padding: 3px 12px;">續訂</a>'; }
                                ?>
                            </div>
                        </div>
            <?php
                    endif;
                endforeach;
            ?>
            </div>
            <a href="?page=new_vip" class="button blue" style="width: 100%; text-align: center; margin-top: 15px;">
                <i class="icon-cog"></i> 前往查看詳細資訊與編輯管理
            </a>

        <?php else: ?>
            <div class="info-box" style="margin-bottom: 15px;">
                <i class="icon-info-circled"></i> <strong>目前沒有綁定帳號</strong>
            </div>
            <p>您可以立即開始綁定您的遊戲帳號以啟用服務。</p>
            <a href="?page=new_vip" class="button green" style="width: 100%; text-align: center; margin-top: 15px;">
                <i class="icon-plus"></i> 前往綁定頁面進行設定
            </a>
        <?php endif; ?>
      </div>
    </div>
    
    <div class="clear"></div>
  </div>
</div>
<div id="boxes">
      <div id="dlg_Login" class="window">
          登入 TwLoader | <a OnClick="window.location='#'"class="close"/>關閉</a>
          <p>
            <form class="forms" action="" method="post" id="frmLogin">
              <fieldset>
                <ol>
                  <li class="form-row text-input-row"><input type="text" name="login_ac" id="login_ac" class="text-input defaultText mustType" title="用戶名稱"/></li>
                  <li class="form-row text-input-row"><input type="password" name="login_pw" id="login_pw" class="text-input defaultText mustType" title="輸*入*密*碼*" cusCaption="用戶密碼"/></li>
                  <li class="button-row" style="text-align:right;" >
                    <span style="margin-right:10px"><a OnClick="window.location='?page=register'">申請帳戶</a></span>
                    <span style="margin-right:10px"><a OnClick="window.location='?page=lostpw'">忘記密碼</a></span>
                	<span style="margin-right:10px; color:#C33;" id="login_ajax_return"><i></i></span>
                	<a href="javascript: checkLoginForm()"><i class="awe colorful" style="background-color:#09F;">登入</i></a>
                  </li>
                </ol>
              </fieldset>
            </form>
          </p>
      </div>
      <div id="mask"></div>
</div>
      
<div id="boxes">
  <div id="dlg_Add" class="window">
      增加用戶 | <a OnClick="window.location='#'"class="close"/>關閉</a>
      <p>
        <form class="forms" action="" method="post" id="frmAdd" >
          <fieldset>
            <ol>
              <li class="form-row text-input-row"><input type="text" name="name" id="gameId" class="text-input defaultText mustType" title="按此輸入購買遊戲帳號 ( 注意帳號大小寫 )"/></li>
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

<script> $(window).load(initPage); </script>
<?php } ?>

<script language="javascript">
	$("#various5").fancybox({'width': '75%','height': '75%','autoScale': true,'transitionIn': 'none','transitionOut': 'none','type': 'iframe'});
</script>

<div class="white-wrapper"> 
  <div class="inner">
  <div class="one-half">
        <div class="card-header-flex">
            <h2 class="line">官網公告</h2>
            <?php if ( $User['group'] == 1 ) { ?>
                <a OnClick="window.location='?page=notice_content&mode=edit'" title="編輯公告">
                    <i class='icon-edit'></i>
                </a>
            <?php } ?>
        </div>
        <div class="custom-card" style="padding-top: 15px;">
        <?php
          $NoticeHighlight = array('#333', 'red', 'blue');
		  $query = $db->query("SELECT * FROM tlsay WHERE `area` = 0 && `hide` = 0 ORDER BY `num` DESC LIMIT 3");
		  $count_query = $db->num_rows($query);
		  for($i=0; $i < $count_query; $i++) {
			  $NoticeData = $db->fetch_array($query);
        ?>
            <a href="notice.php?tid=<?php echo $NoticeData['num']; ?>" class="announcement-card" id="various5">
                <span class="date"><?php echo substr($NoticeData['date'], 5); ?></span>
                <span class="topic" style="color:<?php echo $NoticeHighlight[$NoticeData['highlight']]; ?>;"><?php echo $NoticeData['topic']; ?></span>
            </a>
        <?php } ?>
        <a href="?page=notice_content" class="button gray" style="width: 100%; text-align: center; margin-top: 15px;">查看全部..</a>
        </div>
      </div>
      <div class="one-half last">
        <div class="card-header-flex">
            <h2 class="line">全自動化開通系統</h2>
        </div>
        <div style="position: relative; width: 100%; height: auto;">
            <a href="?page=tlapplyec">
                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.65); color: #fff; padding: 12px; font-size: 1.2em; text-align: center; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                    超商代碼、網路ATM
                </div>
                <img src="images/newpay.png" alt="Payment Methods" style="width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); display: block;">
            </a>
        </div>
      </div>
    <div class="clear"></div>
  </div>
</div>
<div class="gray-wrapper"> 
  <div class="inner">
    <div class="card-header-flex" style="justify-content: center;">
  	    <h2 class="line">重點功能</h2>
    </div>
    <div class="feature-card-container">
        <div class="feature-card"><div class="feature-icon"><i class="icon-up-1"></i></div><h4>高難度Beat up模式</h4><p>全台唯一獨家 Beat up 模式，讓您隨心所欲挑戰更高層級的作品。</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="icon-alert"></i></div><h4>高難度萬兔</h4><p>全台唯一獨家 ONE TWO 萬兔高難度模式，體驗前所未有的遊戲刺激感。</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="icon-popup"></i></div><h4>作品齊全</h4><p>我們在一般模式中有將近三萬個作品，豐富的內容讓您怎麼玩都不會膩。</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="icon-video"></i></div><h4>吉他模式</h4><p>我們在吉他模式中，也收入了龐大的各國吉他模式作品，滿足您的演奏慾。</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="icon-note-beamed"></i></div><h4>定期更新</h4><p>我們都會定期更新作品，讓您永遠走在音樂潮流的最前端，享受最新樂趣。</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="icon-thumbs-up"></i></div><h4>遊戲優化</h4><p>提供多款優化功能，包括介面、歌曲、場景等，讓您的遊戲與眾不同。</p></div>
    </div>
  </div>
</div>