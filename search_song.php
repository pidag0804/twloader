<?php
session_start();
require_once 'twloader/include/class_mysql.php'; // 確保已經包含你的 MySQL class 文件

// 創建數據庫連接
$db = new mysqlclass;
$db->connect('localhost', 'i7f2_tbupus_loa', 'LJBC9T0Rxdk', 'i7f2_tbupus_loader'); // 修改為你的數據庫連接參數

// 定義查詢間隔限制（以秒為單位）
$query_interval = 60;
$results_per_page = 10; // 每頁顯示的結果數

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $last_query_time = isset($_SESSION['last_query_time']) ? $_SESSION['last_query_time'] : 0;
    $current_time = time();

    // 檢查查詢間隔
    if ($current_time - $last_query_time < $query_interval) {
        die('查詢過於頻繁，請稍後再試。');
    }

    // 更新查詢時間
    $_SESSION['last_query_time'] = $current_time;

    // 獲取用戶輸入並進行過濾
    $song = filter_input(INPUT_POST, 'song', FILTER_SANITIZE_STRING);

    // 檢查輸入是否包含特殊符號或非法字符
    if (!preg_match('/^[\p{L}\p{N}\s]+$/u', $song)) {
        die('輸入包含非法字符。');
    }

    // 設置當前頁數
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $start_from = ($page - 1) * $results_per_page;

    // 進行模糊搜索
    $sql = "SELECT * FROM audition_data WHERE song LIKE ? LIMIT ?, ?";
    $stmt = $db->query($sql, array('%' . $song . '%', $start_from, $results_per_page));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 獲取總結果數
    $total_sql = "SELECT COUNT(*) FROM audition_data WHERE song LIKE ?";
    $total_stmt = $db->query($total_sql, array('%' . $song . '%'));
    $total_results = $total_stmt->fetchColumn();
    $total_pages = ceil($total_results / $results_per_page);

    // 顯示查詢結果
    if ($results) {
        echo '<table border="1">';
        echo '<tr><th>Song</th><th>其他欄位</th></tr>';
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['song'], ENT_QUOTES, 'UTF-8') . '</td>';
            // 添加其他需要顯示的欄位
            echo '<td>' . htmlspecialchars($row['其他欄位'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '</tr>';
        }
        echo '</table>';

        // 顯示分頁
        if ($total_pages > 1) {
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo $i . ' ';
                } else {
                    echo '<form method="post" action="search_song.php" style="display:inline;">';
                    echo '<input type="hidden" name="song" value="' . htmlspecialchars($song, ENT_QUOTES, 'UTF-8') . '">';
                    echo '<input type="hidden" name="page" value="' . $i . '">';
                    echo '<input type="submit" value="' . $i . '">';
                    echo '</form> ';
                }
            }
        }
    } else {
        echo '沒有找到符合條件的結果。';
    }
} else {
    // 顯示搜索表單
    echo '<form method="post" action="search_song.php">';
    echo '歌曲名稱: <input type="text" name="song" />';
    echo '<input type="submit" value="搜索" />';
    echo '</form>';
}
?>