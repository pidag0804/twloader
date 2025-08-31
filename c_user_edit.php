<?
include_once("include/class_mysql.php");
@mysql_query("SET character_set_results = utf8");
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>TwLoader 官方網站</title>
<link rel="stylesheet" type="text/css" href="style.css" media="all" />
<link rel="stylesheet" type="text/css" href="style/css/media-queries.css" media="all" />
<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700italic,700,500italic,500,400italic,300italic,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="style/type/fontello.css">
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="style/css/ie8.css" media="all" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="style/css/ie9.css" media="all" />
<![endif]-->
</head>

<body class="box-layout" style="background: transparent url(style/images/bg/bg2.jpg) repeat;">

<!-- Begin Box Wrapper -->
<div class="box-wrapper"> 

   <!-- Begin White Wrapper -->
<div class="white-wrapper"> 
  <!-- Begin Inner -->
  <div class="inner">
		<?
          $userID = intval($_GET['uid']);
		  
		  $query = $db->query("SELECT * FROM tlpw WHERE `num` = ".$userID);
		  $count_query = $db->num_rows($query);
		  if ( $count_query == 0 || $userID <= 0 ) die ("404 Error");
		  $UserData = $db->fetch_array($query);
			  
        ?>
        
        <h2 class="line"><? echo $UserData['num']; ?> - <? echo $UserData['usname']; ?></h2>

        <h6 class="short">
        	<? echo $_GET['done'] == "ok" ? "<i class='awe'>完成修改</i>" : "" ; ?>
        </h6><br />
        
<!-- Begin Form -->
      <div class="form-container" style="width:50%">
        <div class="response"></div>
        <form class="forms" name="applyForm" id="applyForm" action="a_admin.php?type=user" method="post">
          <fieldset>
            <ol>
              <li class="form-row text-input-row">
                <select name="group" class="selectnavDisplay" onchange="selectPlan()">
                  <option value="0" <? echo $UserData['group'] == 0 ? "SELECTED" : "" ?>>普通會員</option>
                  <option value="1" <? echo $UserData['group'] == 1 ? "SELECTED" : "" ?>>管理員</option>
                  <option value="2" <? echo $UserData['group'] == 2 ? "SELECTED" : "" ?>>停權會員</option>
                </select>
              </li>
              
              <li class="form-row text-input-row">
              	暱稱
                <input type="text" name="cnname" id="cnname" class="text-input" value="<? echo $UserData['cnname'] ?>"/>
              </li>
              
              <li class="form-row text-input-row">
              	密碼
                <input type="text" name="uspw" id="uspw" class="text-input" value="<? echo $UserData['uspw'] ?>"/>
              </li>
              
              <hr width="100%" style="margin-bottom:10px;"/>
              
              <li class="form-row text-input-row">
              	<i class="icon-feather"></i> <span>綁定帳戶</span><br /><br />
                <? 
				  $vip_query = $db->query("SELECT * FROM tl_viplist WHERE `uid` = ".$UserData['num']." ORDER BY status DESC");
				  $vip_count = $db->num_rows($vip_query);
				  if ( $vip_count > 0 )
					for($a=0; $a < $vip_count; $a++) {
						$VipData = $db->fetch_array($vip_query);
				?>
                <? echo $VipData['gameid'] ?>
                <select name="status[<? echo $VipData['gameid']; ?>]" class="selectnavDisplay" onchange="selectPlan()">
                  <option value="0" <? echo $VipData['status'] == 0 ? "SELECTED" : "" ?>>無效</option>
                  <option value="1" <? echo $VipData['status'] == 1 ? "SELECTED" : "" ?>>有效</option>
                </select>
                
                <br><br>
                <? } ?>                
              </li>
              
              <li class="form-row hidden-row">
                <input type="hidden" name="num" id="num" value="<? echo $UserData['num']; ?>" />
              </li>
              
              <li class="button-row">
                <input type="submit" id="btnSubmit" class="btn-submit" />
              </li>
            </ol>		  
          </fieldset>
        </form>
      </div>
    
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
</div>
<!-- End White Wrapper -->
</div>
<!-- End Box Wrapper --> 
<script type="text/javascript" src="style/js/scripts.js"></script>
</body>
</html>
