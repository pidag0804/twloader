<?
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");

function loadFile($sFilename, $sCharset = 'UTF-8')
{
    if (floatval(phpversion()) >= 4.3) {
        $sData = file_get_contents($sFilename);
    } else {
        if (!file_exists($sFilename)) return -3;
        $rHandle = fopen($sFilename, 'r');
        if (!$rHandle) return -2;

        $sData = '';
        while(!feof($rHandle))
            $sData .= fread($rHandle, filesize($sFilename));
        fclose($rHandle);
    }
    //if ($sEncoding = mb_detect_encoding($sData, 'auto', true) != $sCharset)
     //   $sData = mb_convert_encoding($sData, $sCharset, $sEncoding);
    return $sData;
}
?>

<div class="form-container">
    <div class="response"></div>
    <form class="forms" name="applyForm" id="applyForm" action="a_admin.php?type=config" method="post">
    <fieldset>
<!-- Begin White Wrapper -->
<div class="white-wrapper"> 
  <!-- Begin Inner -->
  <div class="inner">
    <h2 class="line">網站設定</h2>
    
    <textarea name="config_file" class="text-area" style="min-height: 500px; font-size:18px; line-height:30px;" type="text">
    <? echo trim(loadFile("include/site_config.ini")); ?>
    </textarea>
    <center><input type="submit" class="btn-submit" id="btnSubmit" style="margin-top:20px;" value="儲存設定"/></center>
    <div class="clear"></div>
  </div>
  <!-- End Inner --> 
</div>
<!-- End White Wrapper -->
		
    </fieldset>
    </form>
</div>