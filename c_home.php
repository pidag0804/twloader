<?

$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
require_once("lock.php");
//require_once("1.php");
//include_once("2.php");
?>


<? if ( $User['num'] > 0 ) { ?>
<!-- Begin gray Wrapper -->
<div class="gray-wrapper"> 
  <!-- Begin Inner -->
  <div class="inner">
    <div class="one-half"> 
      <h2 class="line">會員資料</h2>
      <div class="grid">
      <?
                $UserDataCaption = array('會員編號', '會員暱稱', '會員級別', '未讀短訊');
                $UserData = array($User['num'], $User['cnname'], $UserGroup[$User['group']], $HaveUnread);
	  	for ($i = 0; $i <4; $i++) {
	  ?>
        <div class="post">  
          <div class="info">
            <div class="date white"><div class="month"><? echo $UserDataCaption[$i]; ?></div></div>
            <div class="details"><h6></h6><div class="meta"><div class="category"><? echo $UserData[$i]; ?></div></div></div>
        </div>
        </div>
      <? } ?>
      </div>
    </div>      
      
    <div class="one-half last"> 
      <h2 class="line">管理綁定帳號</font></h2>
<div class="grid">原本顯示在此處的內容 , 改到上方連結 , <font color="	#FF0000">[綁定帳號]</font></div>
    </div>
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
</div>
<!-- End gray Wrapper -->

    <div id="boxes">
          <div id="dlg_Login" class="window">
              登入 TwLoader | <a OnClick="window.location='#'"class="close"/>關閉</a>
              <p>
                <form class="forms" action="" method="post" id="frmLogin">
                  <fieldset>
                    <ol>
                      <li class="form-row text-input-row">
                        <input type="text" name="login_ac" id="login_ac" class="text-input defaultText mustType" title="用戶名稱"/>
                      </li>
                      <li class="form-row text-input-row">
                        <input type="password" name="login_pw" id="login_pw" class="text-input defaultText mustType" title="輸*入*密*碼*" cusCaption="用戶密碼"/>
                      </li>
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
<? } ?>

<script language="javascript">
	$("#various5").fancybox({
		'width'				: '75%',
		'height'			: '75%',
        'autoScale'     	: true,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});
</script>

<!-- Begin White Wrapper -->

<div class="white-wrapper"> 
  <!-- Begin Inner -->

  <div class="inner">
  <div class="one-half">
        <h2 class="line">官網公告</h2>
        <div class="teaser-navigation">
            <? if ( $User['group'] == 1 ) { ?><span id="teaser_right" class="navigation-right"><a OnClick="window.location='?page=notice_content&mode=edit'"><i class='icon-edit'></i></a></span><? } ?>
            <div class="clear"></div>
        </div>
		<?
          $NoticeIcon = array('awe', 'icon-info awe', 'icon-info awe', 'icon-alert awe');
		  $NoticeHighlight = array('#999999', 'red', 'blue');
		  
		  $query = $db->query("SELECT * FROM tlsay WHERE `area` = 0 && `hide` = 0 ORDER BY `num` DESC LIMIT 5");
		  $count_query = $db->num_rows($query);
		  
		  for($i=0; $i < $count_query; $i++) {
			  $NoticeData = $db->fetch_array($query);
			  
        ?>
          <h6 class="short">
          	<i class="awe"><? echo substr($NoticeData['date'], 5); ?></i>
            <a href="notice.php?tid=<? echo $NoticeData['num']; ?>" style="color:<? echo $NoticeHighlight[$NoticeData['highlight']]; ?>;" id="various5"><? echo $NoticeData['topic']; ?></a>
          </h6>
        <? } ?>
        <a OnClick="window.location='?page=notice_content'">查看全部..</a>
      </div>
      <div class="one-half last">
        <h2 class="line">　</h2>
        <div class="frame">
        </div>
        <div class="mega-hover" style="width:470px; height:200px;">
            <div class="mega-hovertitle">TWLoader 官方網站版權所有</div>
            <a href=""><div class="mega-hoverlink"></div></a>
            <a class="fancybox" rel="group" href="images/1.png"><div class="mega-hoverview"></div></a>
        </div>
        <img src="images/1.png" alt="" width="470px" height="200px">
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-7083554628414371",
    enable_page_level_ads: true
  });
</script>
      </div>
    <div class="clear"></div>
  </div>

  <!-- End Inner --> 
</div>
<!-- End White Wrapper -->

<!-- Begin gray Wrapper -->
<div class="gray-wrapper"> 
  <!-- Begin Inner -->
  <div class="inner">
  	<h2 class="line">重點功能</h2>
    <div class="one-third">
      <h4><i class="icon-up-1 awe white"></i>激舞改歌</h4>
      <p>全台唯一獨家 Beat up 激舞改歌模式<br>讓您隨心所欲挑戰更高層級的作品</p>
    </div>
    <div class="one-third">
      <h4><i class="icon-alert awe white"></i>高難度萬兔</h4>
      <p>全台唯一獨家 ONE TWO 萬兔高難度模式<br>讓您手忙腳亂的體驗前所未有的遊戲刺激感</p>
    </div>
    <div class="one-third last">
      <h4><i class="icon-popup awe white"></i>作品齊全</h4>
      <p>我們在一般模式中有將近兩萬個作品<br>豐富的內容讓您怎麼玩都不會膩</p>
    </div>
    <div class="clear"></div>
    <br />
    <div class="one-third">
      <h4><i class="icon-video awe white"></i>吉他模式</h4>
      <p>我們在吉他模是中<br>也收入了龐大的各國吉他模式作品</p>
    </div>
    <div class="one-third">
      <h4><i class="icon-note-beamed awe white"></i>定期更新</h4>
      <p>我們都會定期更新作品<br>讓您走在現代潮流最前端</p>
    </div>
    <div class="one-third last">
      <h4><i class="icon-thumbs-up awe white"></i>遊戲優化</h4>
      <p>提供多款遊戲優化功能包括：<br>介面、歌曲、場景等優化讓您的遊戲與眾不同</p>
    </div>
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
</div>
<!-- End gray Wrapper -->