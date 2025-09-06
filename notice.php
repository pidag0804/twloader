<?php
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
<script type="text/javascript" src="style/js/highslide/highslide.packed.js"></script>
<script type="text/javascript" src="style/js/highslide/highslide-with-html.packed.js"></script>
<link rel="stylesheet" type="text/css" href="style/js/highslide/highslide.css" />
<script type="text/javascript"> hs.graphicsDir = 'style/js/highslide/graphics/'; hs.showCredits = false; hs.outlineType = 'rounded-white'; hs.outlineWhileAnimating = true; </script>
</head>

<body class="box-layout" style="background: transparent url(style/images/bg/bg2.jpg) repeat;">


<div class="box-wrapper"> 

  <div class="white-wrapper"> 
  <div class="inner">
		<?php
          $threadID = intval($_GET['tid']);
		  
		  $query = $db->query("SELECT * FROM tlsay WHERE `num` = ".$threadID);
		  $count_query = $db->num_rows($query);
		  if ( $count_query == 0 || $threadID <= 0 ) die ("404 Error");
		  $NoticeData = $db->fetch_array($query);
			  
        ?>
        <h2 class="line"><?php echo htmlspecialchars($NoticeData['topic']); ?></h2>

        <h6 class="short"><i class="awe"><?php echo $NoticeData['date']; ?></i></h6>
        
        <div style="font-size:14px; line-height: 1.8; white-space: pre-wrap; word-wrap: break-word;">
            <?php echo htmlspecialchars_decode($NoticeData['text']); ?>
        </div>
        <div class="clear"></div>
  </div>
  </div>
<div class="site-generator-wrapper">
<div class="site-generator">
    <div class="copyright">
      <p>2010 - 2025 © TwLoader Notice.</p>
    </div>
  </div>
</div>
</div>
<script type="text/javascript" src="style/js/scripts.js"></script>

</body>
</html>