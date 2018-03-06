<div class="usserinfo_search">
    <?php foreach ($room as $item):?>
    <div class="home_video_item" style="margin-left: 20px;">
            <a href="/live/live?roomid=<?=$item["roomid"]?>" class="home_video_item_video"><img src="<?= empty($item["room_cover"])?'/images/hot_video1.png':$item["room_cover"]?>" class="home_video_item_icon" /></a>
            <div class="home_video_item_info">
                <span class="home_video_item_info_title"><?=$item["roomtitle"]?></span>
                <span>主讲：<?=$item["username"]?> </span>
            </div>
        </div>
        
      <?php endforeach; ?>
</div>
