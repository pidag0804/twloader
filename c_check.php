<?
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
require_once("lock.php");
?>

<script type="text/javascript" src="style/js/formcheck.js"></script>

<script type="text/javascript">
	
	function submitform()
	{
		$('#mask').hide();
		$('.window').hide();
		document.getElementById('sub').click();
		
		document.getElementById('sub').disabled = true;
		document.getElementById('btnSubmit').disabled = true;
		document.getElementById('btnSubmit').value = "載入中";
	}
	
	function AutoBig(obj) {
		obj.value = obj.value.toUpperCase();
	}
	
	function getSelectText(id) {
		return document.getElementById(id).options[document.getElementById(id).selectedIndex].text;
	}
	
	function checkForm() {
		if ( pageLoad == 0 ) return;
		var checkFunction = window["checkData"];
		var errorMessage = '';
		
		for ( var i = 0; i < mList.length; i+=2) {
			var mArr = document.getElementsByClassName(mList[i]);
			for (var a = 0; a < mArr.length; a++){
					var parentID = mArr[a].parentNode.parentNode.parentNode.parentNode.id;
					if ( parentID == 'applyForm' && checkFunction ( mArr[a].id , i/2) == 0 )
					errorMessage += ( ( mArr[a].attributes['cusCaption'] != undefined ) ? mArr[a].attributes['cusCaption'].value : DefaultValueList[mArr[a].id] ) + " " + mList[i+1] + "\n";
			}
		}
		//errorMessage += ( ( mArr[a].attributes['cusCaption'] != undefined ) ? mArr[a].attributes['cusCaption'].value : DefaultValueList[mArr[a].id] ) + " " + mList[i+1] + "\n";
		if ( errorMessage != '' ) {
			alert ( "發生錯誤\n\n" + errorMessage ) ;
		} else {
			submitform();
		}
	}
</script>

<!-- Begin White Wrapper -->
<div class="white-wrapper" id="Title_buy"> 
  <!-- Begin Inner -->
  <div class="inner">
    <div class="page-intro line clearfix">
      <h1 class="page-title">查詢系統</h1>
    </div>    
    <div class="content">
    <!-- Begin Form -->
      <div class="form-container">
        <div class="response"></div>
        <form class="forms" name="applyForm" id="applyForm" action="a_check.php" method="post">
          <fieldset>
            <ol>
              
              <li class="form-row text-input-row">
                <select class="selectnavDisplay defaultText" name="payMethod" id="payMethod" onchange="selectPlan()" cusCaption="查詢種類">
                  <option value="1">開通進度查詢</option>
                </select>
              </li>
              
              <li class="form-row text-input-row">
                <input type="text" name="rID" id="rID" class="text-input defaultText mustType mustNumber" title="訂單編號" maxlength="14"/>
              </li>
              
              <li class="form-row text-input-row">
                <input type="text" name="rPW" id="rPW" class="text-input defaultText mustType" title="查詢密碼" maxlength="6"/>
              </li>
              
              <li class="button-row">
                <input type="button" value="查詢" id="btnSubmit" class="btn-submit" onclick="checkForm()" />
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