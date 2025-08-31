<?
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
require_once("lock.php");
?>

<script>
var debug = 1;
</script>

<script type="text/javascript" src="style/js/formcheck.js"></script>

<!-- Begin White Wrapper -->
<div class="white-wrapper" id="Title_buy"> 
  <!-- Begin Inner -->
  <div class="inner">
    <div class="page-intro line clearfix">
      <h1 class="page-title">申請帳戶</h1>
    </div>
    <div class="intro">多謝您的支持，只需花幾分鐘便能輕鬆申請帳戶</div>

<script type='text/javascript'>
  $(function() {
    $('#focus-example [tootext]').tipsy({offset: -71, title: 'cusCaption', trigger: 'focus', gravity: 'w'});
  });
  
function checkRegForm() {
	if ( pageLoad == 0 ) return;
	var checkFunction = window["checkData"];
	var errorMessage = '';
	
	for ( var i = 0; i < mList.length; i+=2) {
		var mArr = document.getElementsByClassName(mList[i]);
		//var mArr = document.getElementById('123').parentNode.id;
		
			for (var a = 0; a < mArr.length; a++){
				var parentID = mArr[a].parentNode.parentNode.parentNode.id;
				if ( parentID == 'frmReg' && checkFunction ( mArr[a].id , i/2) == 0 )
					errorMessage += ( ( mArr[a].attributes['cusCaption'] != undefined ) ? mArr[a].attributes['cusCaption'].value : DefaultValueList[mArr[a].id] ) + " " + mList[i+1] + "\n";
			}
	}
	if ( get_value('password') != get_value('repassword') )
		errorMessage += "登入密碼與確認密碼不相符" + "\n";
	if ( errorMessage != '' ) {
		alert ( "發生錯誤\n\n" + errorMessage ) ;
	} else {
		loadContent('?act=regcheck&ac=' + get_value('acname') + '&pw=' + get_value('password') + '&nn=' + get_value('nickname') + '&em=' + get_value_ue('email'), 'reg_ajax_return');
	}
}
</script>

    <div class="content">
    <!-- Begin Form -->
      <div class="form-container" id='focus-example'>
        <div class="response"></div>
        <form class="forms" name="applyForm" id="applyForm" action="a_register.php" method="post">
          <fieldset id="frmReg">
            <ol>
              
              <li class="form-row text-input-row">登入帳號
                <input type="text" name="acname" id="acname" class="text-input defaultText mustType mustPass" tootext="" maxlength="10" cusCaption="登入帳號"/>
              </li>
              
              <li class="form-row text-input-row">登入密碼
                <input type="password" name="password" id="password" tootext="" class="text-input defaultText mustType mustPass" maxlength="10" cusCaption="登入密碼"/>

              </li>

              <li class="form-row text-input-row">確認密碼
                <input type="password" name="repassword" id="repassword" tootext="" class="text-input defaultText mustType mustPass" maxlength="10" cusCaption="確認密碼"/>

              </li>
              
              <li class="form-row text-input-row">電郵地址
                <input type="text" name="email" id="email" class="text-input defaultText mustEmail" tootext="" cusCaption="電郵地址"/>
              </li>
              
              <li class="form-row text-input-row">暱稱
                <input type="text" name="nickname" id="nickname" class="text-input defaultText mustType" tootext="" cusCaption="　暱　　稱"/>
              </li>
              
              <li class="button-row">
                <input type="button" value="提交" id="btnSubmit" class="btn-submit" onclick="checkRegForm()" />
                <span style="margin-left:10px; color:#C33;" id="reg_ajax_return"></span>
              </li>
            </ol>

		  <input type="submit" id="sub" style="display:none;"/>
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