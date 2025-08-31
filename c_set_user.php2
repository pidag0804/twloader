<?
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
?>

<style type="text/css">
table.download_list {
	border-width: 0px;
	border-spacing: 3px;
	border-style: none;
	border-color: gray;
	border-collapse: separate;
	background-color: white;
}
table.download_list th {
	border-width: 1px;
	padding: 5px;
	border-style: none;
	border-color: gray;
	background-color: white;
	-moz-border-radius: ;
}
table.download_list td {
	border-width: 1px;
	padding: 5px;
	height: 30px;
	border-style: none;
	border-color: gray;
	background-color: white;
	-moz-border-radius: ;
}
</style>

<script>
function setCheckValue(from, to){
	document.getElementById(to).value = (from.checked == true ? 1 : 0);
}

function showhide(from, to){
	document.getElementById(to).style.display = (from.value > 0 ? "block" : "none");
}
</script>

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

<div class="form-container">
    <div class="response"></div>
    <form name="applyForm" id="applyForm" action="?page=set_user" method="post">
    <fieldset>
    
<!-- Begin white Wrapper -->
<div class="white-wrapper"> 
  <!-- Begin Inner -->
  <div class="inner">
    <h2 class="line">會員搜尋</h2>
    <div class="teaser-navigation">
      <div class="clear"></div>
    </div>
    <select class="selectnavDisplay" name="st" id="st">
      <option value="1">會員帳戶</option>
      <option value="2">會員暱稱</option>
      <option value="3">會員編號</option>
      <option value="4">遊戲帳戶</option>
      <option value="5">會員信箱</option>
    </select><br /><br />
    輸入搜索值
    <input name="s" class="text-input" type="text" style="padding:10px;"/>
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
</div>
<!-- End white Wrapper -->
    </fieldset>
    </form>
    
<? 
		  if ( $_GET['uid'] > 0 ) {
			  $_POST['s'] = $_GET['uid'];
			  $_POST['st'] = 3;
		  }
?>

<? if ( $_POST['st'] > 0 ) { ?>
<!-- Begin gray Wrapper -->
<div class="gray-wrapper"> 
  <!-- Begin Inner -->
  <div class="inner">
    <h2 class="line">搜尋結果</h2>
    <div class="teaser-navigation">
      <div class="clear"></div>
    </div>
    <table width="100%" border="0" class="download_list">

        <tr style="font-weight:800;">
          <td>編號</td>
          <td>位置</td>
          <td>信箱</td>
          <td>會員帳戶</td>
          <td>會員暱稱</td>
          <td>綁定帳戶</td>
          <td>用戶組</td>
          <td>編輯</th>
        </tr>

		<?
		  $searchArray = array(0, "SELECT * FROM tlpw WHERE `usname` LIKE  '%".$_POST['s']."%'", 
		  						  "SELECT * FROM tlpw WHERE `cnname` LIKE  '%".$_POST['s']."%'", 
								  "SELECT * FROM tlpw WHERE `num` =  ".$_POST['s'],
								  "SELECT * FROM tl_viplist, tlpw WHERE tl_viplist.gameid = '".$_POST['s']."' && tl_viplist.uid = tlpw.num",
								  "SELECT * FROM tlpw WHERE `usem` LIKE  '%".$_POST['s']."%'",
							   );
		  $query = $db->query($searchArray[$_POST['st']]);
		  $count_query = $db->num_rows($query);
			  
		  for($i=0; $i < $count_query; $i++) {
				  $UserData = $db->fetch_array($query);
				  $VipString = "";
				  $VipVCount = 0;
				  $vip_query = $db->query("SELECT * FROM tl_viplist WHERE `uid` = ".$UserData['num']." ORDER BY status DESC");
				  $vip_count = $db->num_rows($vip_query);
				  if ( $vip_count > 0 ) { 
					for($a=0; $a < $vip_count; $a++) {
						$VipData = $db->fetch_array($vip_query);
						$VipVCount += $VipData['status'] == 1 ? 1 : 0;
						$VipString .= "<font color=".($VipData['status'] == 1 ? "#00FF00" : "#444").">".$VipData['gameid']."</font><br />";
					}
				  }
        ?>
        <tr>
        <td><? echo $UserData['num']; ?></td>
        <td><? echo $UserData['ip']; ?></td>
        <td><? echo $UserData['usem']; ?></td>
        <td><? echo $UserData['usname']; ?></td>
        <td><? echo $UserData['cnname']; ?></td>
        <td><? if ( $vip_count > 0 ) { ?><span id='west' title='<? echo $VipString; ?>'><? echo $VipVCount; ?> 個有效 [<? echo $vip_count; ?>]</span><? } else { ?> - <? } ?></td>
        <td><? echo $UserGroup[$UserData['group']]; ?></td>
        <td><a id="various5" href="c_user_edit.php?uid=<? echo $UserData['num']; ?>">Edit</a></td>
        </tr>
        <?
		  }
		?>

    </table>
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
</div>
<!-- End gray Wrapper -->
<? } ?>
</div>