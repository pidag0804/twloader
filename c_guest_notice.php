<?php
$_GET['pms'] = $page_pms;
require_once("include/content_head.php");
require_once("lock.php");
?>

<style>
    /* 官網公告/FAQ卡片 */
    .faq-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0; /* 移除內邊距，交給連結處理 */
        border: 1px solid #e9e9e9;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
        background: #fdfdfd;
        overflow: hidden; /* 確保子元素圓角正確 */
    }
    .faq-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.08);
        border-color: #6094b4;
    }
    /* 主要內容連結區域 */
    .faq-card a.content-link {
        flex-grow: 1; /* 佔滿所有可用空間 */
        padding: 12px 15px;
        display: flex;
        align-items: center;
        text-decoration: none;
    }
    .faq-card .date {
        font-size: 0.85em;
        font-weight: 600;
        color: #6094b4;
        margin-right: 12px;
        background-color: #eaf2f8;
        padding: 3px 8px;
        border-radius: 5px;
    }
    .faq-card .topic {
        color: #333;
        font-weight: 500;
    }
    /* 管理員工具 */
    .faq-card .admin-tools {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-right: 15px; /* 增加右邊距 */
        flex-shrink: 0; /* 防止被壓縮 */
    }
    .faq-card .admin-tools a {
        text-decoration: none;
    }
    .faq-card .admin-tools i {
        font-size: 1.1em;
        color: #6094b4;
        transition: color 0.2s;
    }
    .faq-card .admin-tools a:hover i {
        color: #b66b77;
    }
</style>
<div class="white-wrapper"> 
  <div class="inner">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2 class="line" style="margin-bottom: 0 !important; flex-grow: 1;">常見問題</h2>
        <?php if ( $User['group'] == 1 ) { ?>
            <a href="?page=guest_notice&mode=edit" class="button small blue" style="margin: 0;">編輯模式</a>
        <?php } ?>
    </div>

    <div>
    <?php
      $NoticeHighlight = array('#333', 'red', 'blue');
      
      $ex_query = $User['group'] == 1 && $_GET['mode'] == "edit" ? "" : "&& `hide` = 0";
      $query = $db->query("SELECT * FROM tlsay WHERE `area` = 1 ".$ex_query." ORDER BY top DESC, num DESC");
      $count_query = $db->num_rows($query);
      $drawnline = 0;
      
      for($i=0; $i < $count_query; $i++) {
          $NoticeData = $db->fetch_array($query);

          if ( $NoticeData['top'] == 0 && $drawnline == 0 && $i > 0 ){
              echo "<h4 class='line' style='margin-top: 40px;'>一般問題</h4>";
              $drawnline = 1;
          }
    ?>
        <div class="faq-card">
            <a href="notice.php?tid=<?php echo $NoticeData['num']; ?>" class="content-link various5">
                <span class="date"><?php echo $NoticeData['date']; ?></span>
                <span class="topic" style="color:<?php echo $NoticeHighlight[$NoticeData['highlight']]; ?>;"><?php echo $NoticeData['topic']; ?></span>
            </a>
            
            <?php if ( $User['group'] == 1 && $_GET['mode'] == "edit") { ?>
                <div class="admin-tools">
                    <?php echo $NoticeData['hide'] == 1 ? "<span style='color:blue; font-size:0.9em;'>[隱藏]</span>" : ""; ?>
                    <a href="?page=set_notice&nid=<?php echo $NoticeData['num']; ?>" title="編輯此項目">
                        <i class="icon-edit"></i>
                    </a>
                </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
    
    <div class="clear"></div>
  </div>
  </div>
<script language="javascript">
	// 3. 將 jQuery 選擇器從 ID (#) 改為 class (.)，以便能正確綁定所有卡片
	$(".various5").fancybox({
		'width'				: '75%',
		'height'			: '75%',
        'autoScale'     	: true,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});
</script>