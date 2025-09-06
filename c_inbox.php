<?php
$get_mid = intval($_GET['mid']);

$_GET['pms'] = $page_pms;
require_once("include/content_head.php");

if ($get_mid != 0 && $get_mid != 3 && $User['group'] != 1) {
    $_GET['msg'] = "這是錯誤的操作，我們會將您先前的操作記錄下來 .";
    require_once("c_error.php");
}
?>

<div class="white-wrapper"> 
  <div class="inner">
  
    <div class="sidebar" style="background:none;">
      <div class="sidebox">
        <div id="comments" style="background:none; padding-top:0px;">
          <h3>聯絡我們</h3>
          線上客服平均回應時間：約十五到三十分鐘<br>線上客服在線時段：12:00 - 22:00<br>
          線上Line客服人員：@820mdvjx<br>
          點擊以下與TWLoader線上客服 聯繫</font>
          
          <ol id="singlecomments" class="commentlist">
            
            <li class="clearfix">
              <div class="user" style="width:60px;"><img src="images/user.png" height="60px"/></div>
              <div class="message" style="cursor:pointer" onclick="window.location='?page=inbox&mid=3'"> 
                <div class="info" style="padding-bottom:6px;">
                  <h2><a onclick="window.location='?page=inbox&mid=3'"> 聯繫 TwLoader 客服</a></h2>
                </div>
              </div>
            </li>
            <font color="#FF0000"> 連絡前煩請先詳閱官網公告及<a onclick="window.location='?page=guest_notice'">常見問題</a><br>如未有解決方式再連繫以節省等待時間</font>
            <br><br>
          </ol>
        </div>
        </div>
    </div>
    
    <div class="content">
      <?php
      if ($get_mid > 0) {  
        $query = $db->query("SELECT * FROM tlpw WHERE num = ".$get_mid);
        $count_query = $db->num_rows($query);
        if ($count_query)
          $Chater = $db->fetch_array($query);
      ?>
      <a onclick="window.location = '?page=inbox'"><i class="icon-back awe colorful" style="background-color:#666;"> &nbsp;返回訊息中心</i></a><br /><br />
      <?php if ($User['group'] == 6 || $User['num'] > 3 && $User['num'] < 18756 || $User['num'] > 18756) {} ?>
      <div class="page-intro clearfix"><h1 class="page-title">和 <?php echo $User['group'] == 1 ? "<a href=?page=set_user&uid=".$Chater['num']." target=_blank>" : "" ; ?><?php echo $Chater['cnname']; ?><?php echo $User['group'] == 1 ? "</a>" : "" ; ?> 的對話</h1><br><br><h2></font></h2></div>
      <div id="comments">
        <ol id="singlecomments" class="commentlist">
          <?php
          // 查詢最近三十天內的訊息
          $query_recent = $db->query("SELECT * FROM tl_message WHERE (touser = ".$User['num']." AND fromuser = ".$get_mid." OR touser = ".$get_mid." AND fromuser = ".$User['num'].") AND time >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY time");
          $count_recent = $db->num_rows($query_recent);

          // 查詢三十天以上的訊息
          $query_old = $db->query("SELECT * FROM tl_message WHERE (touser = ".$User['num']." AND fromuser = ".$get_mid." OR touser = ".$get_mid." AND fromuser = ".$User['num'].") AND time < DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY time");
          $count_old = $db->num_rows($query_old);

          // 顯示最近三十天內的訊息
          for($i=0; $i < $count_recent; $i++) {
              $MsgData = $db->fetch_array($query_recent);
          ?>
          <li class="clearfix">
              <div class="user"><img alt="" src="images/user.png" class="avatar" /></div>
              <div class="message" style='background-color:<?php echo $MsgData['fromuser'] == $User['num'] ? "#D9FFCE" : "#CFF"; ?>;'> 
                  <div class="info">
                      <?php echo ($MsgData['unread'] == 1 && $MsgData['touser'] == $User['num']) ? "<span class='reply-link' style='color:red;'>未讀取</span>" : ""; ?>
                      <h2><?php echo $MsgData['fromuser'] == $User['num'] ? "你" : $Chater['cnname']; ?></h2>
                      <div class="meta"><?php echo $MsgData['time']; ?></div>
                      <hr style="margin:4px 1px;" />
                      <?php echo nl2br($MsgData['content']); ?>
                  </div>
              </div>
          </li>
          <?php
          }

          // 更新訊息為已讀
          $db->query("UPDATE tl_message SET unread = 0 WHERE touser = ".$User['num']." && fromuser = ".$get_mid."");
          ?>
        </ol>

        <?php if ($count_old > 0) { ?>
        <div style="text-align: center;">
          <button id="toggleOldMessages" onclick="toggleOldMessages()" class="btn-red">顯示舊訊息</button>
        </div>
        <div id="oldMessages" style="display: none;">
            <ol class="commentlist">
                <?php
                // 顯示三十天以上的訊息
                for($i=0; $i < $count_old; $i++) {
                    $MsgData = $db->fetch_array($query_old);
                ?>
                <li class="clearfix">
                    <div class="user"><img alt="" src="images/user.png" class="avatar" /></div>
                    <div class="message" style='background-color:<?php echo $MsgData['fromuser'] == $User['num'] ? "#D9FFCE" : "#CFF"; ?>;'> 
                        <div class="info">
                            <?php echo ($MsgData['unread'] == 1 && $MsgData['touser'] == $User['num']) ? "<span class='reply-link' style='color:red;'>未讀取</span>" : ""; ?>
                            <h2><?php echo $MsgData['fromuser'] == $User['num'] ? "你" : $Chater['cnname']; ?></h2>
                            <div class="meta"><?php echo $MsgData['time']; ?></div>
                            <hr style="margin:4px 1px;" />
                            <?php echo nl2br($MsgData['content']); ?>
                        </div>
                    </div>
                </li>
                <?php
                }
                ?>
            </ol>
            <div style="text-align: center;">
              <button id="toggleOldMessages" onclick="toggleOldMessages()" class="btn-red">隱藏舊訊息</button>
            </div>
        </div>
        <?php } ?>
      </div>

      <div class="form-container">
        <div class="response"></div>
        <form class="forms" name="form_name" action="a_send.php?mid=<?php echo $get_mid; ?>&act=send" method="post">
          <fieldset>
            <h3>留言</h3>
            <div>
              <textarea name="message" id="message" class="text-area"></textarea>
            </div>
            <input type="submit" value="傳送" class="btn-submit" />
          </fieldset>
        </form>
      </div>

      <script>
      function toggleOldMessages() {
          var oldMessages = document.getElementById("oldMessages");
          var toggleButton = document.getElementById("toggleOldMessages");
          var toggleButtonBottom = document.getElementById("toggleOldMessagesBottom");
          if (oldMessages.style.display === "none") {
              oldMessages.style.display = "block";
              toggleButton.innerText = "隱藏舊訊息";
              if(toggleButtonBottom) toggleButtonBottom.style.display = "block";
          } else {
              oldMessages.style.display = "none";
              toggleButton.innerText = "顯示舊訊息";
              if(toggleButtonBottom) toggleButtonBottom.style.display = "none";
          }
      }
      </script>
      <?php
      } else {
        // 顯示訊息中心的其他代碼
        $idList = array();
        $totalUnread = 0;
        
        $ShowDataNum = 10;
        $nowPage = intval($_GET['p']);
        $nowData = $nowPage * $ShowDataNum;
        
        // --- START: MODIFIED SQL QUERY ---
        $query = $db->query(
          "SELECT
              sub.checker,
              tlpw.cnname,
              MAX(sub.message_time) AS last_message_time,
              MAX(sub.unread_flag) as unreadmsg
          FROM (
              SELECT
                  CASE WHEN touser = ".$User['num']." THEN fromuser ELSE touser END as checker,
                  time as message_time,
                  CASE WHEN unread = 1 AND touser = ".$User['num']." THEN 1 ELSE 0 END as unread_flag
              FROM
                  tl_message
              WHERE
                  touser = ".$User['num']." OR fromuser = ".$User['num']."
          ) as sub
          LEFT JOIN tlpw ON tlpw.num = sub.checker
          GROUP BY
              sub.checker, tlpw.cnname
          ORDER BY
              unreadmsg DESC,
              last_message_time DESC
          LIMIT $nowData, $ShowDataNum"
        );
        // --- END: MODIFIED SQL QUERY ---
        
        $count_query = $db->num_rows($query);
      ?>

      <script language="javascript">
        setTimeout("self.location.reload();",60 * 1000);
      </script>

      <div class="page-intro clearfix"><h2 class="page-title">訊息中心</h2></div>
      <div id="comments">
        <h3><span id="idCount">0</span> 則通話 ( <span id="unreadCount">0</span> 未讀 )</h3>
        <ol id="singlecomments" class="commentlist">
          <?php
          for($i=0; $i < $count_query; $i++) {
              $readed = 0;
              $ChaterData = $db->fetch_array($query);
              
              if ($idList[$ChaterData['checker']] == 0) {
                  $idList[$ChaterData['checker']] = $ChaterData['checker'];
                  $unreaded = $ChaterData['unreadmsg'];
                  if ($unreaded == 1) $totalUnread++;
              } else {
                  continue;
              }
          ?>
          <li class="clearfix">
            <div class="user"><img alt="" src="images/user.png" class="avatar" /></div>
            <div class="message" style="cursor:pointer; <?php echo $unreaded ? "background-color:#CFF;" : ""; ?>" onclick="window.location = '?page=inbox&mid=<?php echo $ChaterData['checker']; ?>'"> 
              <div class="info">
                <h2><a onclick="window.location = '#'"><?php echo $ChaterData['cnname']; ?></a></h2>
                <div class="meta"><?php echo $unreaded ? "有" : "沒有"; ?>新訊息</div>
              </div>
            </div>
          </li>
          <?php
          }
          ?>
          <script>
            document.getElementById('idCount').innerHTML = <?php echo sizeof($idList); ?>;
            document.getElementById('unreadCount').innerHTML = <?php echo $totalUnread; ?>;
          </script>
          <br />
          <?php if ($nowPage > 0) { ?>
          <a href="?page=inbox&p=<?php echo $nowPage - 1; ?>">上一頁</a>
          <?php } ?>
          
          <?php if ($count_query == $ShowDataNum) { ?>
          <a href="?page=inbox&p=<?php echo $nowPage + 1; ?>">下一頁</a>
          <?php } ?>
        </ol>
      </div>
      <?php
      }
      ?>
    </div>
  </div>
  <div class="clear"></div>
<style>
.btn-red {
    background-color: #ff4d4d;
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
}

.btn-red:hover {
    background-color: #ff1a1a;
}
</style>