<?
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
?>

<?
$DlTypeList = array("TWLoader PLus 音樂包+主程式下載 (第一次使用必須先安裝此檔案) ", "TWLoader PLus 更新檔案下載 (固定每周四更新)", "Bup Plus改歌 音樂包+主程式下載(第一次使用該模式必須先安裝此檔案)" ,"VIP下載");
//
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
	height: 100px;
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
<div class="form-container">
    <div class="response"></div>
    <form class="forms" name="applyForm" id="applyForm" action="a_admin.php?type=download" method="post">
    <fieldset>
    
<!-- Begin gray Wrapper -->
<div class="gray-wrapper"> 
  <!-- Begin Inner -->
  <div class="inner">
    <h2 class="line">資料增加</h2>
    <div class="teaser-navigation">
      <div class="clear"></div>
    </div>
    <select class="selectnavDisplay" name="newType" id="newType" onchange="showhide(this, 'newdata')">
      <option value="0">選擇新資料類型</option>
      <option value="1">OLD下載</option>
      <option value="2">OT下載</option>
      <option value="3">UP改下載</option>
      <option value="4">VIP下載</option>

    </select>
    <table width="100%" border="0" class="download_list" id="newdata" style="display:none;">
        <thead>
        <tr>
          <th width="40px">次序</th>
          <th width="40px">隱藏</th>
          <th>檔案標題</th>
          <th>下載網址</th>
          <th>詳細介紹</th>
        </tr>
        </thead>
        <tr>
        <td><input name="sort[new]" class="text-input" type="text"/></td>
        <td><input id="hide" type="checkbox" onclick="setCheckValue(this, 'hide[new]')"/></td>
        <td><input name="name[new]" class="text-input" type="text"/></td>
        <td><textarea name="link[new]" class="text-area" style="min-height: 100px;" type="text"></textarea></td>
        <td><textarea name="text[new]" class="text-area" style="min-height: 100px;" type="text"></textarea></td>
        </tr>
    </table>
    <input type="hidden" id="hide" name="hide[new]" value="0" />
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
</div>
<!-- End gray Wrapper -->

<? $now_id = 0; ?>
<? for ( $i = 0; $i < sizeof($DlTypeList); $i++ ) { ?>
<!-- Begin White Wrapper -->
<div class="white-wrapper"> 
  <!-- Begin Inner -->
  <div class="inner">
    <h2 class="line"><? echo $DlTypeList[$i]; ?></h2>
    <div class="teaser-navigation">
      <div class="clear"></div>
    </div>
    <table width="100%" border="0" class="download_list">
        <thead>
        <tr>
          <th width="40px">次序</th>
          <th width="40px">隱藏</th>
          <th>檔案標題</th>
          <th>下載網址</th>
          <th>詳細介紹</th>
        </tr>
        </thead>
        <? 
          $query = $db->query("SELECT * FROM tl_download WHERE `type` = ".$i." ORDER BY `sort` DESC");
          $count_query = $db->num_rows($query);
          
          for($a=0; $a < $count_query; $a++) {
              $DownloadData = $db->fetch_array($query);
        ?>
        <tr>
        <td><input name="sort[<? echo $DownloadData['id']; ?>]" class="text-input" type="text" value="<? echo $DownloadData['sort']; ?>" /></td>
        <td><input id="hide_<? echo $DownloadData['id']; ?>" type="checkbox" <? echo $DownloadData['hide'] == 1 ? "checked" : ""; ?> onclick="setCheckValue(this, 'hide[<? echo $DownloadData['id']; ?>]')"/></td>
        <td><input name="name[<? echo $DownloadData['id']; ?>]" class="text-input" type="text" value="<? echo $DownloadData['name']; ?>" /></td>
        <td><textarea name="link[<? echo $DownloadData['id']; ?>]" class="text-area" style="min-height: 100px;" type="text"><? echo $DownloadData['link']; ?></textarea></td>
        <td><textarea name="text[<? echo $DownloadData['id']; ?>]" class="text-area" style="min-height: 100px;" type="text"><? echo $DownloadData['text']; ?></textarea></td>
        </tr>
        <input type="hidden" id="hide[<? echo $DownloadData['id']; ?>]" name="hide[<? echo $DownloadData['id']; ?>]" value="<? echo $DownloadData['hide']; ?>" />
        <input type="hidden" name="dataID[<? echo $now_id; ?>]" value="<? echo $DownloadData['id']; ?>" />
        <? $now_id++; } ?>
    </table>
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
</div>
<!-- End White Wrapper -->
<? } ?>
		<center><input type="submit" class="btn-submit" id="btnSubmit" style="margin-top:20px;" value="儲存設定"/></center>
    </fieldset>
    </form>
</div>