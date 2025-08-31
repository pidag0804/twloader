<?
include_once("include/class_mysql.php");
@mysql_query("SET character_set_results = utf8");

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
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
<!— 圖片放特效 —>
<script type="text/javascript" src="style/js/highslide/highslide.packed.js"></script>
<script type="text/javascript" src="style/js/highslide/highslide-with-html.packed.js"></script>
<link rel="stylesheet" type="text/css" href="style/js/highslide/highslide.css" /> 
<script type="text/javascript"> hs.graphicsDir = 'style/js/highslide/graphics/'; hs.showCredits = false; hs.outlineType = 'rounded-white'; hs.outlineWhileAnimating = True; </script>
<!— 圖片放特效 結束 —>
</head>

<body class="box-layout" style="background: transparent url(style/images/bg/bg2.jpg) repeat;">


<!-- Begin Box Wrapper -->
<div class="box-wrapper"> 

   <!-- Begin White Wrapper -->
<div class="white-wrapper"> 
  <!-- Begin Inner -->
  <div class="inner">
		<?
          $threadID = intval($_GET['tid']);
		  
		  $query = $db->query("SELECT * FROM tlsay WHERE `num` = ".$threadID);
		  $count_query = $db->num_rows($query);
		  if ( $count_query == 0 || $threadID <= 0 ) die ("404 Error");
		  $NoticeData = $db->fetch_array($query);
			  
        ?>
        <h2 class="line"><? echo $NoticeData['topic'] ?></h2>

        <h6 class="short"><i class="awe"><? echo $NoticeData['date']; ?></i></h6>
        <pre style="background:none; border:none; font-size:14px;"><? echo $NoticeData['text'] ?></pre>
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
</div>
<!-- End White Wrapper -->

<!-- <div class="divider white-wrapper"></div> -->
<!-- Begin Site Generator -->
  <div class="site-generator-wrapper">
<div class="site-generator">
    <div class="copyright">
      <p>2010 - 2016 © TwLoader Notice.</p>
    </div>
  </div>
</div>
<!-- End Site Generator --> 

</div>
<!-- End Box Wrapper --> 
<script type="text/javascript" src="style/js/scripts.js"></script>

</body>
</html>