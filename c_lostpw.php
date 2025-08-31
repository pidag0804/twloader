<?php
$_GET['pms'] = $page_pms;
include_once("include/class_mysql.php");
require_once("include/content_head.php");
require_once("lock.php");

echo ('
<table border="0" rules="none" align="center" BORDERCOLOR="#696969" bgcolor="#FFFFFF" width="100%" height="1">
');

if ($_POST['do'] == 2){

$m = $db->fetch_array($db->query("SELECT * FROM tlpw WHERE `usem` = '".$_POST['email']."' AND `usname` = '".$_POST['username']."'"));

if (($m['usem'] == $_POST['email']) && ($m['usname'] == $_POST['username']) && ($_POST['email'] != "") && ($_POST['username'] != "")) {
    echo ('
    <td class="page-intro line clearfix"><h1 class="page-title">查詢結果</h1></td>
    <tr><td colspan=5 align="center"><font color="#696969" size="2">　</td></font>
    <tr><td align="center" width="100%" colspan="2"><font color="#696969" size="2">您的會員資料如下:<br>會員帳號: '.$m['usname'].'<br>會員密碼: '.$m['uspw'].'</font></td>
    <tr><td colspan=5 align="center"><font color="#696969" size="2">　</td></font>
    <tr><td align="center" width="100%" colspan="2"><button onclick="history.back()" style="background-color: #6C6C6C; color: white; padding: 6px 12px; text-align: center; text-decoration: none; display: inline-block; font-size: 12px; margin: 4px 2px; cursor: pointer; border: none; border-radius: 4px;">返回上一頁</button></td>
    ');
} else {
    echo ('
    <td class="page-intro line clearfix"><h1 class="page-title">查詢密碼</h1></td>
    <tr><td colspan=5 align="center"><font color="#696969" size="2">　</td></font>
    <tr><td align="center" width="100%" colspan="2"><font color="#696969" size="2">很抱歉,查詢失敗<BR>請檢查您的註冊信箱或會員帳號是否有錯誤<br>必須為當初您註冊會員時填寫的註冊信箱及帳號</font></td>
    <tr><td colspan=5 align="center"><font color="#696969" size="2">　</td></font>
    <tr><td align="center" width="100%" colspan="2"><button onclick="history.back()" style="background-color: #6C6C6C; color: white; padding: 6px 12px; text-align: center; text-decoration: none; display: inline-block; font-size: 12px; margin: 4px 2px; cursor: pointer; border: none; border-radius: 4px;">返回上一頁</button></td>
    ');
}

} else {
    echo ('
    <form method="POST" action="?page=lostpw">
    <input type="hidden" name="do" value="2">
    <td class="page-intro line clearfix"><h1 class="page-title">查詢密碼</h1></td>
    <tr>
    <td align="center" width="100%"><font color="#696969" size="5"><BR>　　是否忘記登入資料 輸入註冊帳號及信箱 幾分鐘即可找回遺失的資料</font></td>
    <tr>
    <td align="center" width="100%"><font color="#696969" size="2">　</font></td>
    <tr>
    <td align="center" width="100%"><font color="#696969" size="2">
    你的註冊帳號 :　<input type="text" name="username" size="55" title="填入註冊帳號" style="width:300px;text-align:center"><br><br>
    你的註冊信箱 :　<input type="text" name="email" size="55" title="填入註冊信箱" style="width:300px;text-align:center"></font><font color="'.$bgcolor2font.'" size="5">
    <BR><button type="submit" style="background-color: #6C6C6C; color: white; padding: 6px 12px; text-align: center; text-decoration: none; display: inline-block; font-size: 12px; margin: 4px 2px; cursor: pointer; border: none; border-radius: 4px;" onclick="this.disabled=true;this.form.submit();">取回密碼</button></font></td>
    <tr>
    <td align="center" width="100%"><font color=" " size="2">　</font></td>
    </form>
    ');
}

echo ('</font></table>');
?>