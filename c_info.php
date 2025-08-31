<?

$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
require_once("lock2.php");

	$ini_path = "images/info/config.ini";
	$config = parse_ini_file($ini_path, true);

?>

<!-- Begin White Wrapper -->
<div class="white-wrapper"> 
  <!-- Begin Inner -->
  <div class="inner">
    <div class="page-intro line clearfix">
      <h1 class="page-title">產品介紹</h1>
    </div>
    <div class="intro">我們擁有著數之不盡的功能，即將為您介紹其中主要的幾個部份</div>
    
    <!-- Begin Portfolio -->
    <div id="portfolio">
      <ul class="filter">

        <li><a class="active" href="#" data-filter="*">所有分類</a></li>
        <? for ( $i = 0; $i < sizeof($config['title']['type_name']); $i++) {?>
        <li><a href="#" data-filter=".<? echo $config['title']['type_name'][$i]; ?>"><? echo $config['title']['type_caption'][$i]; ?></a></li>
        <? } ?> 
      </ul>
      <ul class="items col4">
      
        <? for ( $i = 0; $i < sizeof($config['file_list']['name']); $i++) {?>
        <li class="frame item <? echo $config['file_list']['type'][$i]; ?>"> <span class="frame"> <a href="images/info/<? echo $config['file_list']['name'][$i]; ?>" class="fancybox-media" data-title-id="title-<? echo $i; ?>"> <img src="images/info/<? echo $config['file_list']['name'][$i]; ?>" alt="" /> </a> </span>
          <h5><? echo $config['file_list']['caption'][$i]; ?></h5>
          <p><? echo $config['file_list']['description'][$i]; ?></p>
          <div id="title-<? echo $i; ?>" class="info hidden">
            <h2><? echo $config['file_list']['caption'][$i]; ?></h2>
            <div class="fancybox-desc"><? echo $config['file_list']['description'][$i]; ?></div>
          </div>
        </li>
        <? } ?>
      </ul>
    </div>
    <!-- End Portfolio --> 
    
  </div>
  <!-- End Inner --> 
  
</div>
<!-- End White Wrapper -->