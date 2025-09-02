<?
error_reporting(0);
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
?>

<div class="white-wrapper">
    <div class="inner">

        <h2 class="line">忘記密碼</h2>
        <p>請在下方輸入您註冊時使用的電子信箱，系統將會把您的「帳號」與「密碼」寄送給您。</p>

        <div class="form-container" style="max-width: 500px; margin: 20px auto;">
            <form class="forms" action="javascript:void(0);" method="post" id="frmLostPw">
                <fieldset>
                    <ol>
                        <li class="form-row text-input-row">
                            <label style="font-weight: bold; margin-bottom: 5px; display: block;">電子信箱:</label>
                            <input type="email" name="lostpw_em" id="lostpw_em" class="text-input" placeholder="請輸入您註冊的 Email" style="width: 100%;" />
                        </li>
                        <li class="button-row" style="text-align: right; margin-top: 15px;">
                             <a href="javascript:void(0);" onClick="sendLostPw();" class="button green">送出查詢</a>
                        </li>
                        <li class="form-row" id="lostpw_ajax_return" style="margin-top: 20px; color: #C33; font-weight: bold; text-align: center;">
                           <i></i>
                        </li>
                    </ol>
                </fieldset>
            </form>
        </div>

    </div>
    </div>
<script type="text/javascript">
function sendLostPw() {
    var email = document.getElementById('lostpw_em').value;
    var resultDiv = document.getElementById('lostpw_ajax_return');

    if (email.trim() === "") {
        resultDiv.innerHTML = '<i>請輸入電子信箱！</i>';
        return;
    }
    
    // 簡單的 Email 格式驗證
    var emailRegex = /^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/;
    if (!emailRegex.test(email)) {
        resultDiv.innerHTML = '<i>電子信箱格式不正確！</i>';
        return;
    }

    // 更新狀態並發送 AJAX 請求
    resultDiv.innerHTML = '<i>處理中，請稍候...</i>';
    loadContent('?act=lostpw&em=' + encodeURIComponent(email), 'lostpw_ajax_return');
}
</script>