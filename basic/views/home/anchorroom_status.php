<div class="anchorroom_status">
    <?php if (empty($applyStatus["apply_status"])): ?>
        <div class="anchorroom_status_body">
            <div class="anchorroom_status_head">
                <img src="/images/write.png" class="anchorroom_status_write" />
                <span>您的提交还在处理中，请耐心等待......</span>
            </div>
        </div>
    <?php else: ?> 
        <div class="anchorroom_status_body1">
            <div class="anchorroom_status_head anchorroom_rejection_title">你的申请未通过，原因如下:</div>
            <div class="anchorroom_rejection_reason">
                <?=$applyStatus["rejection_reason"]?>
            </div>
            <div class="update_applyinfo">
                <a href="/anchor/index">点击修改申请信息</a>
            </div>
            
        </div>
    <?php endif; ?>
</div>
