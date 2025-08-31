<?
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
require_once("lock.php");

$DlTypeList = array("特殊檔案 下載區");

?>

<!-- Begin White Wrapper -->
<div class="white-wrapper"> 
  <!-- Begin Inner -->
  <div class="inner">
    <h2 class="line"><? echo $DlTypeList[0]; ?></h2>
        <? 
          $query = $db->query("SELECT * FROM tl_download WHERE `type` = 3 AND `hide` = 0 ORDER BY `sort` DESC");
          $count_query = $db->num_rows($query);
          
          for($a=0; $a < $count_query; $a++) {
              $DownloadData = $db->fetch_array($query);
        ?>
<!-- Begin Toggle -->
      <div class="toggle">
        <h4 class="title"><? echo $DownloadData['name']; ?></h4>
        <div class="togglebox">
          <div>
            <pre style="background:none; border:none; font-size:14px;">
			<? echo $DownloadData['text']; ?>
            </pre>
            <? 
				$linkArray = split("[\n\r\t ]+", $DownloadData['link']); 
				for ( $d = 0; $d < sizeof($linkArray); $d++ ) {
			?>
            <a href="<? echo $linkArray[$d]; ?>" target="_blank"><i class="icon-download awe colorful" style="background-color:#09F;"> &nbsp;下載點<? echo sizeof($linkArray) > 1 ? $d + 1 : ""; ?></i></a>
			<? } ?>
            <br /><br />
          </div>
        </div>
      </div>
<!-- End Toggle -->
        <? } ?>
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
</div>
<!-- End White Wrapper -->