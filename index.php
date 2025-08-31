<?
include_once("include/class_mysql.php");
@mysql_query("SET character_set_results = utf8");
include_once("include/user_data.php");
include_once("include/get_user_status.php");

include_once("a_service.php");

?>
    <!DOCTYPE HTML>
    <html lang="en-US">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>
            <? if ( $User['group'] == 1 ){ ?>
                <?  echo $HaveUnread ? "【 您有 $HaveUnread 則訊息 】- " : " " ; ?>
                    <? } ?> TwLoader ᶜˣ 官方網站</title>
        <link rel="shortcut icon" href="style/images/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="style/images/favicon.ico">
        <link rel="apple-touch-icon" href="style/images/faviconapp.png" />
        <link rel="stylesheet" type="text/css" href="style.css?v=1.0.2" media="all" />
        <link rel="stylesheet" type="text/css" href="style/css/media-queries.css" media="all" />
        <link rel="stylesheet" type="text/css" href="style/js/fancybox/jquery.fancybox.css" media="all" />
<!--
        <link rel="stylesheet" type="text/css" href="style/js/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.2.css" />
        <link rel="stylesheet" type="text/css" href="style/js/fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.2.css" />

-->
        <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700italic,700,500italic,500,400italic,300italic,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="style/type/fontello.css">
        <!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="style/css/ie8.css" media="all" />
<![endif]-->
        <!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="style/css/ie9.css" media="all" />
<![endif]-->
<!--
        <script type="text/javascript" src="style/js/jquery.fancybox.js?v=2.1.5"></script>
-->
        <script type="text/javascript" src="style/js/jquery.min.js"></script>
        <script type="text/javascript" src="style/js/ddsmoothmenu.js"></script>
        <script type="text/javascript" src="style/js/selectnav.js"></script>
        <script type="text/javascript" src="style/js/jquery.themepunch.plugins.min.js"></script>
        <script type="text/javascript" src="style/js/jquery.themepunch.revolution.min.js"></script>
        <script type="text/javascript" src="style/js/jquery.themepunch.megafoliopro.min.js"></script>
        <script type="text/javascript" src="style/js/fullwidth-slider.js"></script>
        <script type="text/javascript" src="style/js/jquery.easytabs.js"></script>
        <script type="text/javascript" src="style/js/twitter.min.js"></script>
        <script type="text/javascript" src="style/js/jquery.dcflickr.1.0.js"></script>
        <script type="text/javascript" src="style/js/jquery.jribbble-0.11.0.ugly.js"></script>
        <script type="text/javascript" src="style/js/jquery.slickforms.js"></script>
        <script type="text/javascript" src="style/js/jquery.fitvids.js"></script>
        <script type="text/javascript" src="style/js/jquery.isotope.min.js"></script>
        <script type="text/javascript" src="style/js/jquery.address.min.js"></script>
        <script type="text/javascript" src="style/js/jquery.fancybox.pack.js"></script>
<!--
        <script type="text/javascript" src="style/js/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
        <script type="text/javascript" src="style/js/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>
        <script type="text/javascript" src="style/js/fancybox/helpers/jquery.fancybox-media.js?v=1.0.0"></script>
-->
        <script type="text/javascript" src="style/js/jquery.dpSocialTimeline.js"></script>
        <script type="text/javascript" src="style/js/jquery.themepunch.showbizpro.js"></script>
        <script type="text/javascript" src="style/js/jquery.gotop.js"></script>

        <link rel="stylesheet" type="text/css" href="style/css/modal.css" />
        <script type="text/javascript" src="style/js/modal.js"></script>

        <script type="text/javascript" src="style/js/loadajax.js"></script>

        <link rel="stylesheet" type="text/css" href="style/css/tipsy.css" />
        <script type="text/javascript" src="style/js/jquery.tipsy.js"></script>

        <script type='text/javascript'>
            $(function() {
                $('#north').tipsy({
                    live: true,
                    offset: 10,
                    gravity: 'n'
                });
                $('#west').tipsy({
                    live: true,
                    offset: 10,
                    gravity: 'w',
                    html: true
                });
            });
        </script>
        </style>
        <script type="text/javascript" src="style/images/1.js"></script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7083554628414371"
     crossorigin="anonymous"></script>

    </head>

    <body class="box-layout">
        <!-- Begin Top Wrapper -->
        <div class="top-wrapper">
            <!-- Begin Inner -->
            <div class="inner">
                <!-- Begin Header -->
                <div class="header">
                    <div class="logo"><img src="style/images/logo.png" title='TWLoader 台灣區官方網站' alt="" /></div>
                    <ul class="social">
                        <span style='margin-right:10px;'><b><? echo $User['num'] > 0 ? $User['cnname'] : "未登入" ?></b></span>
                        <? if ( $User['num'] > 0 ) { ?>
                        
                            <li><a OnClick="window.location='?page=chpass'" id='north' title='修改密碼'><i class="icon-tools"></i></a></li>
                            <li>
                                <a OnClick="window.location='?page=inbox'" id='north' title='訊息'>
                                    <i class="icon-mail" <? echo ( $HaveUnread> 0 ) ? "style='background-color:#F69;'" : ""; ?>>
            <span style="font-style:normal;"><? if ( $HaveUnread > 0 ) echo ( $HaveUnread > 5 ? "" : $HaveUnread );?></span>   
          </i></li>
                            <li><a OnClick="window.location='logout.php'" id='north' title='登出'><i class="icon-logout-1"></i></a></li>
                            <? } else { ?>
                                <li><a href="#dlg_Login" name="modal" id='north' title='登入 / 申請帳戶'><i class="icon-user"></i></a></li>
                                <? } ?>
                    </ul>
                    <div class="clear"></div>
                </div>

                <!-- End Header -->

                <!-- Begin Menu -->
                <div id="menu" class="menu clearfix">
                    <ul id="tiny">
                        <li><a href="?page=home"><i class="icon-home"></i> &nbsp;首頁</a></li>
                        <li><a OnClick="window.location='#'">產品介紹 / 購買</a>
                            <ul>
                                <li><a href="?page=info">產品介紹</a></li>
                                <li><a href="?page=price">產品價目</a></li>
                                <li><a href="?page=pay_steps">購買流程</a></li>
                                <? if ( $User['num'] > 0 ) { ?>
                                    <li><a href="?page=tlapplyec" style="color: red">申請購買</a></li>
                                    <? } ?>
                            </ul>
                        </li>
                        <? if ( $User['num'] > 0 ) { ?>
                            <li><a href="?page=guest_notice">常見問題</a></li>
                            <li><a href="?page=orderform">訂單記錄</a></li>
                            <li><a href="?page=new_vip"><font color="#FF0000">綁定帳號</font></a></li>
                            <li><a OnClick="window.location='#'">產品下載</a>
                                <ul>
                                    <li><a href="?page=download">檔案下載</a></li>
                                    <li><a href="?page=setup_steps"><font color="#FF0000">安裝教學New</font></a></li>
                                </ul>
                            </li>
                            <? } ?>
                                <? if ( $User['group'] == 6 || $User['group'] == 1 ) { ?>
                                    <li>
                                        <a OnClick="window.location='#'">
                                            <font color="#FF0000">VIP 專區</font>
                                        </a>
                                        <ul>
                                            <li><a href="?page=vip_download">VIP 下載區</a></li>
                                        </ul>
                                    </li>
                                    <? } ?>
                                        <? if ( $User['group'] == 1 ) { ?>
                                            <li><a OnClick="window.location='#'">後台管理</a>
                                                <ul>
                                                    <li><a href="?page=set_notice">發表公告</a></li>
                                                    <li><a href="?page=set_download">下載管理</a></li>
                                                    <li><a href="?page=set_config">網站設定</a></li>
                                                    <li><a href="?page=set_user">會員管理</a></li>
                                                </ul>
                                            </li>
                                            <? } ?>
                                                <li>
                                                    <a>　</a>
                                                </li>
                    </ul>
                </div>
                <!-- End Menu -->
            </div>
            <!-- End Inner -->

            <!-- End Top Wrapper -->
        </div>
        <!-- Begin Box Wrapper -->
        <div class="box-wrapper">

            <? if ( !empty($site_config['site']['top_notice'] ) ) { ?>
                <div class="note-box">
                    <? echo $site_config['site']['top_notice']; ?>
                </div>
                <? } ?>
                    <!-- 內容 -->
                    <?
  $page_list = array(0,0,0,
   "register", PMS_GUEST,  1,
   "lostpw", PMS_GUEST,  1,
   "chpass", PMS_MEMBER, 1,
   "inbox", PMS_MEMBER, 1,
   "notice_content", PMS_ALL,  1,


   "home", PMS_ALL,  1,

   "info", PMS_ALL,  1,
   "price", PMS_ALL,  "c_price.html",
   "pay_steps", PMS_ALL,  "c_pay_steps.html",
   "tlapplyec", PMS_MEMBER, 1,
   "tlapplyec2", PMS_MEMBER, 1,
   "new_vip", PMS_MEMBER, "new_vip.php",


   "guest_notice", PMS_MEMBER, 1,

   "check", PMS_MEMBER, 1,
   "orderform", PMS_MEMBER, 1,

   "download", PMS_MEMBER, 1,
   "setup_steps", PMS_MEMBER, "c_setup_steps.html",
   "417_steps", PMS_MEMBER, "c_417_steps.html",
   "recommend", PMS_MEMBER, "c_recommend.html",

   "vip_download", PMS_MEMBER, 1,

   "set_download", PMS_ADMIN,  1,
   "set_notice", PMS_ADMIN,  1,
   "set_user", PMS_ADMIN,  1,
   "set_config", PMS_ADMIN,  1,

   "shiftac",   PMS_MEMBER, 1,

   );
  $PagePos = array_search( $_GET['page'], $page_list, true);

  if ( $User['group'] != 1 && !empty($site_config['site']['close_site']) ) {
   $_GET['cap'] = "抱歉！官網網站維護中....";
   $_GET['msg'] = $site_config['site']['close_site'];
   $_GET['nodie'] = 1;
   require_once("c_error.php");
 } else {
   if ( $PagePos > 0 ) {
    $page_pms = $page_list[$PagePos + 1];
    $page_file = $page_list[$PagePos + 2];
    include_once( $page_file == 1 ? "c_".$_GET['page'].".php" : $page_file);
  } else {
    $_GET['page'] = "home";
    include_once("c_home.php");
  }
}
?>
                        <!-- 內容 -->

                        <!-- <div class="divider white-wrapper"></div> -->

                        <!-- Begin Site Generator -->
                        <div class="site-generator-wrapper">
                            <div class="site-generator">
                                <!--  <? if ( $User['group'] > 3 || $User['group'] == 0 ) { ?>
<p align= center>
  <? } ?> -->
                                <div class="copyright">
                                    <p align=c enter>TwLoader © 2010 - 2025 All Rights Reserved.　客服時間：12:00 - 22:00　　
                                        <!-- <p align= center>本站主機由<a href="http://www.fantastic-host.net" target="_blank"> FantasticHost </a>代管.</p>-->
                                        <!--<p align= center><img src="http://www.hot-hit-counter.com/counter?id=8A4F20F6-A0F7-4294-B684-AECBD09F9D63&style=d_m-chop,ml_8" border="0" width=200 height=22 alt="voicexml"/></p>-->
                                </div>
                            </div>
                        </div>
                        <!-- End Site Generator -->
        </div>
        <!-- End Box Wrapper -->

        <? if ( $User['num'] == 0 ) { ?>
            <script type="text/javascript" src="style/js/formcheck.js"></script>
            <script language="javascript">
                function checkLoginForm() {
                    if (pageLoad == 0) return;
                    var checkFunction = window["checkData"];
                    var errorMessage = '';

                    for (var i = 0; i < mList.length; i += 2) {
                        var mArr = document.getElementsByClassName(mList[i]);
                        //var mArr = document.getElementById('123').parentNode.id;

                        for (var a = 0; a < mArr.length; a++) {
                            var parentID = mArr[a].parentNode.parentNode.parentNode.id;
                            if (parentID == 'frmLogin' && checkFunction(mArr[a].id, i / 2) == 0)
                                errorMessage += ((mArr[a].attributes['cusCaption'] != undefined) ? mArr[a].attributes['cusCaption'].value : DefaultValueList[mArr[a].id]) + " " + mList[i + 1] + "\n";
                        }
                    }
                    //errorMessage += ( ( mArr[a].attributes['cusCaption'] != undefined ) ? mArr[a].attributes['cusCaption'].value : DefaultValueList[mArr[a].id] ) + " " + mList[i+1] + "\n";
                    if (errorMessage != '') {
                        alert("發生錯誤\n\n" + errorMessage);
                    } else {
                        loadContent('?act=login&ac=' + get_value('login_ac') + '&pw=' + get_value('login_pw'), 'login_ajax_return');
                    }
                }
            </script>
            <div id="boxes">
                <div id="dlg_Login" class="window">
                    登入 TwLoader | <a OnClick="window.location='#" class="close" />關閉</a>
                    <p>
                        <form class="forms" action="" method="post" id="frmLogin">
                            <fieldset>
                                <ol>
                                    <li class="form-row text-input-row">
                                        <input type="text" name="login_ac" id="login_ac" class="text-input defaultText mustType" title="用戶帳號" />
                                    </li>
                                    <li class="form-row text-input-row">
                                        <input type="password" name="login_pw" id="login_pw" class="text-input defaultText mustType" title="輸*入*密*碼*" cusCaption="用戶密碼" />
                                    </li>
                                    <li class="button-row" style="text-align:right;">
                                        <span style="margin-right:10px"><a OnClick="window.location='?page=register'">申請帳戶</a></span>
                                        <span style="margin-right:10px"><a OnClick="window.location='?page=lostpw'">忘記密碼</a></span>

                                        <span style="margin-right:10px; color:#C33;" id="login_ajax_return"><i></i></span>
                                        <a OnClick="window.location='javascript: checkLoginForm()'"><i class="awe colorful" style="background-color:#09F;">登入</i></a>

                                    </li>
                                </ol>
                            </fieldset>
                        </form>
                    </p>
                </div>
                <div id="mask"></div>
            </div>
            <script>
                if (window.location.hash == "#showlogin") showModal("#dlg_Login");
                $(window).load(initPage);
            </script>
            <? } ?>

                <script type="text/javascript" src="style/js/scripts.js"></script>
                <div id="gotop"><img src="style/images/BackToTop.png" title="Go To Top" width=50 height=50 /></div>

    </body>

    </html>