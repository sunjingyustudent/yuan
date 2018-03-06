<?php

use yii\helpers\Html;
?>
<div class="home_mypayrecord_content">
    <?php if(empty($count)): ?>
    <h5>当前没有数据</h5>
<?php else: ?>
    <div id="record_list">
        
    </div>
    <div class="home_mypayrecord_pagination1"><ul id="pagination1" class="pagination"></ul></div>
    
<!--    <div>共<span style="color: #ff0000"><?=$count?></span>条</div>-->
<?php endif; ?>
    
</div>
<script>
    $(function () {
        var data = <?= json_encode(Yii::$app->request->get(), JSON_UNESCAPED_SLASHES) ?>;

        $('#pagination1').jqPaginator({
            totalCounts: <?=$count?>,
            pageSize:8,
            visiblePages: 10,
            currentPage: 1,
            onPageChange: function (num, type) {
                data.page_num = num;
                var url = "/home/my-pay-record-list?page="+num;
                $("#record_list").load(url);
            }
        });

    });

</script>