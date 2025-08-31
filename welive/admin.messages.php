<?php

// +---------------------------------------------+
// |     Copyright  2010 - 2028 WeLive           |
// |     http://www.weentech.com                 |
// |     This file may not be redistributed.     |
// +---------------------------------------------+

define('AUTH', true);

include('includes/welive.Core.php');
include(BASEPATH . 'includes/welive.Admin.php');

if($userinfo['usergroupid'] != 1) exit();

PrintHeader($userinfo['username'], 'messages');

$success[] = '抱歉, 免費版無此功能, 但不影響WeLive的正常使用.';
$success[] = '此功能方便管理員查閱、管理客服人員的交流記錄.';
$success[] = 'WeLive商業版僅售 <span class=blueb>100</span> 元, 一次性付費, 永久使用及免費升級.';
$success[] = '購買商業版: QQ <span class=note>20577229</span> (加入時請註明: <span class=note>WeLive商業版</span>) <BR>&nbsp;&nbsp;&nbsp;&nbsp;或 致電 <a href="http://www.weentech.com/" target="_blank">聞泰網絡</a>. 感謝您的支援!';

$successtitle = '功能限制說明';

BR(6);

PrintSuss($success, $successtitle);


PrintFooter();

?>