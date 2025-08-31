<?
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
require_once("lock.php");
?>

<script type="text/javascript" src="style/js/formcheck.js"></script>

<script type="text/javascript">	
	function checkForm() {
		if ( pageLoad == 0 ) return;
		var checkFunction = window["checkData"];
		var errorMessage = '';
		
		for ( var i = 0; i < mList.length; i+=2) {
			var mArr = document.getElementsByClassName(mList[i]);
			for (var a = 0; a < mArr.length; a++)
				if ( checkFunction ( mArr[a].id , i/2) == 0 )
					errorMessage += ( ( mArr[a].attributes['cusCaption'] != undefined ) ? mArr[a].attributes['cusCaption'].value : DefaultValueList[mArr[a].id] ) + " " + mList[i+1] + "\n";
		}
		
		if ( get_value('new_pw') != get_value('new_re_pw') )
			errorMessage += "登入密碼與確認密碼不相符" + "\n";
			
		if ( errorMessage != '' ) {
			alert ( "發生錯誤\n\n" + errorMessage ) ;
		} else {
			loadContent('?act=chpass&opw=' + get_value('old_pw') + '&npw=' + get_value('new_pw'), 'chpass_ajax_return');
		}
	}
</script>

<!-- Begin White Wrapper -->
<div class="white-wrapper" id="Title_buy"> 
  <!-- Begin Inner -->
  <div class="inner">
    <div class="page-intro line clearfix">
      <h1 class="page-title">修改密碼</h1>
    </div>    
    <div class="content">
    <!-- Begin Form -->
      <div class="form-container">
        <div class="response"></div>
                <form class="forms" action="" method="post" id="frmPass">
                  <fieldset>
                    <ol>
                      <li class="form-row text-input-row">
                        輸入舊密碼
                        <input type="password" name="old_pw" id="old_pw" tootext="" class="text-input defaultText mustType mustPass" cusCaption="用戶舊密碼"/>  
                      </li>
                      <li class="form-row text-input-row">
                        輸入新密碼
                        <input type="password" name="new_pw" id="new_pw" tootext="" class="text-input defaultText mustType mustPass" maxlength="10" cusCaption="輸入新密碼"/>
                      </li>
                      <li class="form-row text-input-row">
                        確認新密碼
                        <input type="password" name="new_re_pw" id="new_re_pw" tootext="" class="text-input defaultText mustType mustPass" maxlength="10" cusCaption="確認新密碼"/>
                      </li>
                      <li class="button-row" style="text-align:right;" >                        
                    	<span style="margin-right:10px; color:#C33;" id="chpass_ajax_return"><i></i></span>
                    	<a href="javascript: checkForm()"><i class="awe colorful" style="background-color:#09F;">修改</i></a>
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