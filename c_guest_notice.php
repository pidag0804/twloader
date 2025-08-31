<?
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
require_once("lock.php");
?>

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
        <h2 class="line">常見問題</h2>
        <div class="teaser-navigation">
        	<? if ( $User['group'] == 1 ) { ?><a OnClick="window.location='?page=guest_notice&mode=edit'">編輯</a><? } ?>
            <div class="clear"></div>
        </div>
		<?
          $NoticeIcon = array('awe', 'icon-info awe', 'icon-info awe', 'icon-alert awe');
		  $NoticeHighlight = array('#999999', 'red', 'blue');
		  
		  $ex_query = $User['group'] == 1 && $_GET['mode'] == "edit" ? "" : "&& `hide` = 0";
		  $query = $db->query("SELECT * FROM tlsay WHERE `area` = 1 ".$ex_query." ORDER BY top DESC, num DESC");
		  $count_query = $db->num_rows($query);
		  
		  for($i=0; $i < $count_query; $i++) {
			  $NoticeData = $db->fetch_array($query);
			  if ( $NoticeData['top'] == 0 && $drawnline == 0 && $i > 0 ){
			  	echo "<h2 class='line'></h2>";
				$drawnline = 1;
			  }
        ?>
          <h6 class="short">
          	<!--<i class="awe">教學</i>-->
          	<i class="awe"><? echo $NoticeData['date']; ?></i>
            <? if ( $User['group'] == 1 && $_GET['mode'] == "edit") { ?>
            	<a href="?page=set_notice&nid=<? echo $NoticeData['num']; ?>"><i class="icon-edit awe"></i></a>
                <? echo $NoticeData['hide'] == 1 ? "<i class='awe'><font color=blue>隱藏</font></i>" : ""; ?>
            <? } ?>
            <a href="notice.php?tid=<? echo $NoticeData['num']; ?>" style="color:<? echo $NoticeHighlight[$NoticeData['highlight']]; ?>;" id="various5"><? echo $NoticeData['topic']; ?></a>
          </h6>
        <? } ?>
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
</div>
<!-- End White Wrapper -->