<?
// **--- 強制開啟 PHP 錯誤回報 (偵錯專用) ---**
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// **--- 偵錯模式結束 ---**


// **-- 快取解決方案 --**
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (function_exists('opcache_invalidate')) {
    opcache_invalidate(__FILE__);
}
// **-- 快取解決方案結束 --**


// 引用必要的檔案
include_once("include/class_mysql.php");
include_once("include/user_data.php");

// 確保用戶已登入
if ($User['num'] <= 0 || !isset($User['mun'])) {
    // 如果未登入，理論上會導向到登入頁面
    // 但為避免後續程式碼因缺少 user_id 出錯，先在這裡停止
    header("Location: index.php#showlogin");
    exit();
}

// 初始化變數
$game_account_info_to_display = null;
$error_message = '';
$user_id = $User['mun']; // 將用戶ID存到變數中

// --- 邏輯處理區 ---

// 處理移除綁定
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['gameid'])) {
    $remove_gameid = mysql_real_escape_string($_GET['gameid']);
    $delete_sql = "DELETE FROM `tl_viplist` WHERE `uid` = '$user_id' AND `gameid` = '$remove_gameid'";
    $db->query($delete_sql);
    header("Location: ?page=new_vip"); // 操作後重新整理頁面
    exit();
}

// 處理新增綁定
if (isset($_POST['action']) && $_POST['action'] == 'bind' && !empty($_POST['bind_name'])) {
    $bind_name = mysql_real_escape_string($_POST['bind_name']);

    $verify_sql = "SELECT `type` FROM `kmx_usera` WHERE `name` = '$bind_name'";
    $verify_result = $db->query_first($verify_sql);

    if ($verify_result) {
        if ($verify_result['type'] == 3 || $verify_result['type'] == 4) {
            $error_message = "此帳號已被封鎖，無法綁定。";
        } else {
            $check_bound_sql = "SELECT * FROM `tl_viplist` WHERE `gameid` = '$bind_name'";
            if ($db->num_rows($db->query($check_bound_sql)) > 0) {
                $error_message = "此遊戲帳號已被其他網站帳號綁定。";
            } else {
                $insert_sql = "INSERT INTO `tl_viplist` (`uid`, `gameid`, `channel`, `server`) VALUES ('$user_id', '$bind_name', '', '')";
                $db->query($insert_sql);
                header("Location: ?page=new_vip"); // 操作後重新整理頁面
                exit();
            }
        }
    } else {
        $error_message = "查無此遊戲帳號。";
    }
}

// 處理查詢遊戲帳號
if (isset($_POST['check_account']) && !empty($_POST['game_name'])) {
    $game_name_to_check = mysql_real_escape_string($_POST['game_name']);
    
    $sql_check = "SELECT * FROM `kmx_usera` WHERE `name` = '$game_name_to_check'";
    $game_account_info_to_display = $db->query_first($sql_check);
    
    if (!$game_account_info_to_display) {
        $error_message = '查無此遊戲帳號，請確認輸入是否正確。';
    } elseif ($game_account_info_to_display['type'] == 3 || $game_account_info_to_display['type'] == 4) {
        $error_message = '此帳號狀態異常 (封鎖)，無法進行綁定。';
        $game_account_info_to_display = null;
    }
}

// --- 資料查詢區 ---

// 使用 JOIN 查詢，一次獲取所有已綁定帳號的完整資訊
$bound_accounts_details = array();
$sql_join = "SELECT t2.* FROM `tl_viplist` AS t1 JOIN `kmx_usera` AS t2 ON t1.gameid = t2.name WHERE t1.uid = '$user_id'";
$bound_result = $db->query($sql_join);
while ($row = $db->fetch_array($bound_result)) {
    $bound_accounts_details[] = $row;
}

// 引用頁面頭部
// *** 致命錯誤很可能發生在 content_head.php 或之前的 include 檔案 ***
include_once("include/content_head.php");
?>

<div class="one-half">
    <h3>綁定新的 VIP 帳號</h3>
    <p>請在下方輸入您要綁定的遊戲帳號，系統將會驗證您的帳號資格。</p>
    <form class="forms" action="?page=new_vip" method="post">
        <fieldset>
            <ol>
                <li class="form-row text-input-row">
                    <label>遊戲帳號:</label>
                    <input type="text" name="game_name" class="text-input" required />
                </li>
                <li class="button-row">
                    <input type="submit" name="check_account" value="查詢帳號" class="btn-submit" />
                </li>
            </ol>
        </fieldset>
    </form>

    <? if ($error_message): ?>
        <div class="error-box"><? echo htmlspecialchars($error_message); ?></div>
    <? endif; ?>

    <? if ($game_account_info_to_display): ?>
    <div class="pricing-box">
        <h3>帳號資訊</h3>
        <ul>
            <li><strong>遊戲帳號:</strong> <? echo htmlspecialchars($game_account_info_to_display['name']); ?></li>
            <? 
            $type = $game_account_info_to_display['type'];
            if ($type == 0) {
                echo "<li><strong>類型:</strong> 次數會員</li>";
                echo "<li><strong>剩餘次數:</strong> " . htmlspecialchars($game_account_info_to_display['atimes']) . " 次</li>";
            } elseif ($type == 1) {
                echo "<li><strong>類型:</strong> 包月會員</li>";
                echo "<li><strong>到期日:</strong> " . date('Y-m-d', $game_account_info_to_display['timeend']) . "</li>";
                if ($game_account_info_to_display['atimes'] > 0) {
                    echo "<li><strong>剩餘次數:</strong> " . htmlspecialchars($game_account_info_to_display['atimes']) . " 次</li>";
                    echo "<li><small>提醒：剩餘的次數會在包月到期後開始計算。</small></li>";
                }
            } elseif ($type == 2) {
                echo "<li><strong>類型:</strong> 無限榮譽會員</li>";
                echo "<li><strong>到期日:</strong> 無限</li>";
                echo "<li><strong>剩餘次數:</strong> 無限</li>";
            }
            ?>
        </ul>
        
        <?
        $show_renew_button = false;
        if ($type == 1 && (($game_account_info_to_display['timeend'] - time()) / 86400) <= 7) {
            $show_renew_button = true;
        } elseif ($type == 0 && $game_account_info_to_display['atimes'] < 50) {
            $show_renew_button = true;
        }
        if ($show_renew_button) {
            echo '<div class="buy"><a href="https://www.tlmoo.com/test_twloader/index.php?page=tlapplyec" class="button">續訂</a></div>';
        }

        // 檢查此帳號是否已被目前用戶綁定
        $is_already_bound = false;
        foreach($bound_accounts_details as $bound_acct) {
            if ($bound_acct['name'] == $game_account_info_to_display['name']) {
                $is_already_bound = true;
                break;
            }
        }
        
        if ($is_already_bound) {
            echo '<div class="info-box">此帳號已成功綁定至您目前的網站帳戶。</div>';
        } else {
            echo '<form action="?page=new_vip" method="post" style="margin-top: 15px;">
                    <input type="hidden" name="bind_name" value="'.htmlspecialchars($game_account_info_to_display['name']).'">
                    <input type="submit" name="action" class="button" value="新增綁定此帳號">
                  </form>';
        }
        ?>
    </div>
    <? endif; ?>
</div>

<div class="one-half last">
    <h3>已綁定的遊戲帳號</h3>
    <? if (empty($bound_accounts_details)): ?>
        <p>您目前沒有綁定任何遊戲帳號。</p>
    <? else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>遊戲帳號</th>
                    <th>類型</th>
                    <th>狀態</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($bound_accounts_details as $account): ?>
                    <tr>
                        <td><? echo htmlspecialchars($account['name']); ?></td>
                        <td>
                            <?
                            if($account['type'] == 0) echo '次數會員';
                            elseif($account['type'] == 1) echo '包月會員';
                            elseif($account['type'] == 2) echo '無限榮譽';
                            else echo '未知';
                            ?>
                        </td>
                        <td>
                            <?
                            if($account['type'] == 0) echo '剩餘 ' . htmlspecialchars($account['atimes']) . ' 次';
                            elseif($account['type'] == 1) echo '到期日: ' . date('Y-m-d', $account['timeend']);
                            elseif($account['type'] == 2) echo '無限';
                            ?>
                        </td>
                        <td>
                            <a href="?page=new_vip&action=remove&gameid=<? echo urlencode($account['name']); ?>" 
                               class="button small red" 
                               onclick="return confirm('您確定要移除這個帳號的綁定嗎？');">移除</a>
                        </td>
                    </tr>
                <? endforeach; ?>
            </tbody>
        </table>
    <? endif; ?>
</div>

<div class="clear"></div>