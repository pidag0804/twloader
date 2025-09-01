<?
error_reporting(0);

$_GET['pms'] = $page_pms;
require_once("include/content_head.php");

function formatRowToCheck($ParamList, $HashKey, $HashIV) {
  # CheckMacValue
  ksort($ParamList);
  $CheckMacValue = 'HashKey=' . $HashKey;
  foreach ($ParamList as $ParamName => $ParamValue) {
    $CheckMacValue .= '&' . $ParamName . '=' . $ParamValue;
  }
  $CheckMacValue .= '&HashIV=' . $HashIV;
  $CheckMacValue = strtolower(urlencode($CheckMacValue));
  
  // 取之為與 dotNet 相符的字元
  $CheckMacValue = str_replace('%2d', '-', $CheckMacValue);
  $CheckMacValue = str_replace('%5f', '_', $CheckMacValue);
  $CheckMacValue = str_replace('%2e', '.', $CheckMacValue);
  $CheckMacValue = str_replace('%21', '!', $CheckMacValue);
  $CheckMacValue = str_replace('%2a', '*', $CheckMacValue);
  $CheckMacValue = str_replace('%28', '(', $CheckMacValue);
  $CheckMacValue = str_replace('%29', ')', $CheckMacValue);
  
  // MD5 編碼
  $CheckMacValue = strtoupper(md5($CheckMacValue));
  
  return $CheckMacValue;
}

?>
<style>
#order-table {
  width: 100%;
}

#order-table thead {
  color: #e6c555;
}

#order-table tbody td {
  padding: 5px;
}

#order-table tr {
  border-bottom: 1px dotted #666;
}

#order-table span.pno {
  color: red;
}

#order-table hr {
  margin: 5px 0;
}

#__paymentButton,
#order-table .pay-button {
  max-width: 100px;
}

/* 分頁樣式 */
.pagination {
  text-align: center;
  margin-top: 20px;
  padding-bottom: 20px; /* 增加底部空間 */
}

.pagination a, .pagination span {
  display: inline-block;
  padding: 8px 12px;
  margin: 0 2px;
  border: 1px solid #ddd;
  color: #333;
  text-decoration: none;
  border-radius: 4px;
}

.pagination a:hover {
  background-color: #f5f5f5;
}

.pagination .current-page {
  background-color: #e6c555;
  color: #fff;
  border-color: #e6c555;
}

.pagination .disabled {
  color: #ccc;
  border-color: #ddd;
}

</style>

    <script language="javascript">
        $("#various5").fancybox({
            'width': '75%',
            'height': '75%',
            'autoScale': true,
            'transitionIn': 'none',
            'transitionOut': 'none',
            'type': 'iframe'
        });
    </script>

    <div class="white-wrapper">
        <div class="inner">
            <h2 class="line">訂單記錄</h2>
            <div class="teaser-navigation">
                <div class="clear"></div>
            </div>
            <table width="100%" id="order-table">
                <thead>
                    <tr>
                        <td>編號</td>
                        <td>付款方式</td>
                        <td>購買內容</td>
                        <td>金額</td>
                        <td>日期</td>
                        <td>訂單狀態</td>
                    </tr>
                </thead>
                <tbody>
                <?php
      $PayMString = array(0, "超商代碼繳費", "銀行手寫匯款", "郵局手寫匯款", "提款機轉帳", "網上 ATM", "綠界金流");
      $PayConvert = array(0, 4, 2, 3, 1, 5, 6);

      // --- 分頁邏輯開始 ---
      $per_page = 20; // 每頁顯示的筆數

      // 取得總筆數
      $total_query = $db->query("SELECT COUNT(*) FROM kmx_problema WHERE `buyer` = ".$User['num']);
      $total_rows = $db->fetch_array($total_query)[0];
      
      // 計算總頁數
      $total_pages = ceil($total_rows / $per_page);

      // 取得目前頁碼
      $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
      if ($current_page < 1) {
          $current_page = 1;
      } elseif ($current_page > $total_pages && $total_pages > 0) {
          $current_page = $total_pages;
      }
      
      // 計算 SQL 的 LIMIT OFFSET
      $offset = ($current_page - 1) * $per_page;

      /*
      // *** 除錯用 ***：如果您想確認計算結果，可以暫時移除這段註解，它會在頁面原始碼中顯示總筆數和總頁數。
      echo "";
      */

      // 修改查詢語法，加入 LIMIT
      $query = $db->query("SELECT * FROM kmx_problema WHERE `buyer` = ".$User['num']." ORDER BY `id` DESC LIMIT ".$offset.", ".$per_page);
      $count_query = $db->num_rows($query);
      // --- 分頁邏輯結束 ---

      if ( $count_query == 0 ) {
      ?>
      <tr style="height: 100px;">
        <th colspan="6">沒有任何訂單記錄，請先<a href="?page=tlapplyec">申請購買</a></th>
      </tr>
      <?php
      }

      for($i=0; $i < $count_query; $i++) {
        $data = $db->fetch_array($query);
        $paymethod = $PayConvert[$data['buyform']];
      ?>
                    <tr>
                        <td>
                            <? echo $data['id']; ?>
                        </td>
                        <td>
                            <? echo $PayMString[$paymethod]; ?>
                        </td>
                        <td>
                            <?php
                      $accnum = $data['accnumber'];
                      echo "帳戶數量 : ".$accnum."";
                      for ( $j = 1; $j <= $accnum; $j++) {

                           if ($data["type".$j] == 0) {
        echo "<br />- " . $data["acc".$j] . " (" . ($data["day".$j] + 1) . " 個月)";
    }

    if ($data["type".$j] == 1) {
        $timesOutput = ($data["times".$j] == 0) ? "400次" : "1200次";
        echo "<br />- " . $data["acc".$j] . " (" . $timesOutput . ")";
    }
	


                      }

                      if ( $paymethod == 1 ) {
                        echo "<hr />超商代碼繳費代碼: <span class='pno'>".$data["specialnumber"]."</span>";
                        if ( $data["done"] == 0 ) {
                          echo "<br />在三天內持此繳費代碼到全省7-11/全家超商/萊爾富超商繳費 [<a href='?page=pay_steps'>詳細步驟</a>]";
                        }
                      } else if ( $paymethod == 5 && $data["done"] == 0 ) {
                          $postData = array(
                            "mer_id" => '6352',
                            "payment_type" => "web_atm",
                            "od_sob" => $data['id'],
                            "amt" => $data['money'],
                            "return_url" => "http://www.tlmoo.com/twloader/liftet/liftet.php?wa=1",
                          );

                          $checkmacvalue = formatRowToCheck($postData, "a0992309d0974c04", "2eff3002671cedc8");
                          $postData["checkmacvalue"] = $checkmacvalue;
                          ?>
                          <hr />
                          <form method="POST" action="https://ecbank.com.tw/gateway_v2.php">
                          <?php
                          foreach( $postData as $key =>$value ) {  // link to string
                            if(!(empty($value))) {
                              echo "<input type='hidden' name='$key' value='$value'>";
                            }
                          }
                          ?>
                          <input class="pay-button" type="submit" value="立即付款" />
                          </form>
                      <?php } ?>
                        </td>
                        <td>
                            <? echo $data['money']; ?>
                        </td>
                        <td>
                            <? echo $data['buytime']; ?>
                        </td>
                        <td>
                            <?php
                            switch ($data["done"]) {
                              case 0: echo "未完成"; break;
                              case 1: echo "已取消"; break;
                              case 2: echo "完成交易"; break;
                              default: echo "未知"; break;
                            }
                      ?>
                        </td>
                    </tr>
                    <? } ?>
                  </tbody>
            </table>
            <div class="clear"></div>

            <? if ($total_pages > 1): ?>
            <div class="pagination">
                <? if ($current_page > 1): ?>
                    <a href="?page=orderform&p=<?= $current_page - 1 ?>">上一頁</a>
                <? else: ?>
                    <span class="disabled">上一頁</span>
                <? endif; ?>

                <? for ($p = 1; $p <= $total_pages; $p++): ?>
                    <? if ($p == $current_page): ?>
                        <span class="current-page"><?= $p ?></span>
                    <? else: ?>
                        <a href="?page=orderform&p=<?= $p ?>"><?= $p ?></a>
                    <? endif; ?>
                <? endfor; ?>

                <? if ($current_page < $total_pages): ?>
                    <a href="?page=orderform&p=<?= $current_page + 1 ?>">下一頁</a>
                <? else: ?>
                    <span class="disabled">下一頁</span>
                <? endif; ?>
            </div>
            <? endif; ?>
            </div>
        </div>
    ```