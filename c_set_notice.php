<?
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
?>

<?
$NoticeID = intval($_GET['nid']);
if ( $NoticeID > 0 ) {
	$query = $db->query("SELECT * FROM tlsay WHERE `num` = ".$NoticeID);
	$count_query = $db->num_rows($query);
	if ( $count_query ) $nData = $db->fetch_array($query);
	$getN = 1;
}
?>

<link rel="stylesheet" type="text/css" href="style/cleditor/jquery.cleditor.css"/>
<script type="text/javascript" src="style/cleditor/jquery.cleditor.min.js"></script>

    <script type="text/javascript">
      $(document).ready(function() {
        $("#input").cleditor({width:'100%', height:400, useCSS:true})[0].focus();
      });
    </script>

<!-- Begin White Wrapper -->
<div class="white-wrapper" id="Title_buy"> 
  <!-- Begin Inner -->
  <div class="inner">
    <div class="page-intro line clearfix">
      <h1 class="page-title">公告管理</h1>
    </div>
    
    <div class="content">
    <!-- Begin Form -->
      <div class="form-container">
        <div class="response"></div>
        <form class="forms" name="applyForm" id="applyForm" action="a_admin.php?type=notice" method="post">
          <fieldset>
            <ol>
              <li class="form-row text-input-row">
                <select name="area" class="selectnavDisplay" onchange="selectPlan()">
                  <option value="0" <? echo $nData['area'] == 0 ? "SELECTED" : "" ?>>系統公告</option>
                  <option value="1" <? echo $nData['area'] == 1 ? "SELECTED" : "" ?>>客服公告</option>
                </select>
              </li>
              
              <li class="form-row text-input-row">
                <input type="text" name="date" id="date" class="text-input" value="<? echo $getN ? $nData['date'] : $NOW_DATE ?>" maxlength="10"/>
              </li>
              
              <li class="form-row text-input-row">
                <input type="text" name="topic" id="topic" class="text-input <? echo $getN ? "" : "defaultText" ?>" <? echo $getN ? "value='".$nData['topic']."'" : "title='主題'" ?>/>
              </li>
                  
               <li class="form-row text-input-row">
                <select class="selectnavDisplay" name="hide">
                  <option value="0" <? echo $nData['hide'] == 0 ? "SELECTED" : "" ?>>顯示公告</option>
                  <option value="1" <? echo $nData['hide'] == 1 ? "SELECTED" : "" ?>>隱藏公告</option>
                </select>
              </li>
              
              <li class="form-row text-input-row">
                <select class="selectnavDisplay" name="highlight">
                  <option value="0" <? echo $nData['highlight'] == 0 ? "SELECTED" : "" ?>>無高亮顯示</option>
                  <option value="1" <? echo $nData['highlight'] == 1 ? "SELECTED" : "" ?>>高亮顯示 (紅)</option>
                </select>
              </li>
              
              <li class="form-row text-input-row">
                <select class="selectnavDisplay" name="top">
                  <option value="0" <? echo $nData['top'] == 0 ? "SELECTED" : "" ?>>不置頂</option>
                  <option value="1" <? echo $nData['top'] == 1 ? "SELECTED" : "" ?>>置頂 (系統公告不適用)</option>
                </select>
              </li>
              
              <hr width="100%" style="margin-bottom:10px;"/>
              
              <li class="form-row text-area-row">
              	<i class="icon-feather"></i> <span>內容</span><br /><br />
                <textarea name="text" id="input" style="margin-top:10px;"><? echo $getN ? $nData['text'] : "" ?></textarea>
              </li>
              
              <li class="form-row hidden-row">
                <input type="hidden" name="num" id="num" value="<? echo $getN ? $nData['num'] : "0" ?>" />
              </li>
              
              <li class="button-row">
                <input type="submit" id="btnSubmit" class="btn-submit" />
              </li>
            </ol>		  
          </fieldset>
        </form>
      </div>
    </div>
    
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
  
</div>
<!-- End White Wrapper -->

<script>$(window).load(initPage);</script>