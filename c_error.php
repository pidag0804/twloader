<script>document.getElementById('menu').style.display = 'none';</script>

<div class="white-wrapper"> 
    <div class="inner">
    <h1><? echo empty($_GET['cap']) ? "噢！發生錯誤" : $_GET['cap'] ; ?></h1>
    <? echo $_GET['msg']; ?>
    <? if ( $_GET['nodie'] != 1 ) { ?>
    <br /><br /><a href="?page=home" class="button red" style="opacity: 1;">返回主頁</a>
    <? } ?>
    </div>
</div>
<? if ( $_GET['nodie'] != 1 ) die(); ?>