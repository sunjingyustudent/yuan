<?php
$this->title = "直播室详情页";
?>
<script src="http://nos.netease.com/vod163/nePublisher.min.js"></script>
<script type="text/javascript">

    var roomid = <?= $roomid ?>;
    var userid = <?= $userinfo["userid"] ?>;
    var timeid;
</script>
<div class="live_wrap" >
    <div class="gifttx_box">

    </div>
    <div style="display: none;" class="userinfo" rel="<?= $userinfo["userid"] ?>"></div>
    <div class="live_title">
        <div class="live_back"><img src="/images/livebackbutton.png" class="livebackbutton" /></div>
        <div class="livevideo_title"><span>PHP进阶</span></div>
        <div class="live_focus">
            <?php if (!empty($is_focus)): ?>
                <span class="user_focus_btn" rel="0">取消关注</span>
            <?php else: ?>
                <span class="user_focus_btn" rel="1">关注</span>
            <?php endif; ?>
        </div>
        <div>当前直播人气：<sapn style="color: yellow;margin-right: 10px;"  class="live_wrap_renqi">0</sapn>人</div>
<!--        <div class="user_icon"><img src="/images/user_icon.jpg" class="user_icon_img" /></div>-->
    </div>
    <div class="live_show">
        <div class="live_show_video">
            
           
            <div id="my-publisher" class="live_show_video_box">
                <img src="/images/hot_video1.png" class="live_show_video_cover" />
                <img src="/images/img_cover.png" class="live_img_cover" />
            </div>
           
           
            <div class="live_show_video_gift">
                <div class="send_div">
                    <span class="sendGift">赠送</span>
                    <span class="yuanbao_show">您当前元宝：<span class="yuanbao_num"><?= $userinfo["gold"] ?></span>枚</span>
                </div>
                <div class="live_gift_box" rel="1"><img src="/images/gift1.jpg" class="live_gift" /></div>
                <div class="live_gift_box" rel="2"><img src="/images/gift2.jpg" class="live_gift" /></div>
                <div class="live_gift_box" rel="3"><img src="/images/gift3.jpg" class="live_gift" /></div>
                <div class="live_gift_box" rel="4"><img src="/images/gift4.jpg" class="live_gift" /></div>
            </div>
        </div>
        <div class="live_show_chat">
            <div class="live_show_ad">
                <img src="/images/hot_video2.png" class="live_show_ad_img" />
            </div>
            <span class="live_show_chat_title">大家来讨论~</span>
            <div class="chat_message_content">
                <div class="message_content">
                    <span class="message_content_time">12:00</span>
                    <img src="/images/logo_icon.png" class="message_content_grade"/>
                    <span class="message_content_name">小雨：</span>
                    <span class="message_content_chat">今天天气真好！</span>
                </div>
                <div class="message_content">
                    <span class="message_content_time">12:00</span>
                    <img src="/images/logo_icon.png" class="message_content_grade"/>
                    <span class="message_content_name">小雨</span>
                    <span class="message_content_chat">赠送给主播</span>
                    <img src="/images/logo_icon.png" class="message_content_gift"/>
                    <span class="">X1</span>
                </div>
                <div class="message_content">
                    <span class="message_content_time">12:00</span>
                    <img src="/images/logo_icon.png" class="message_content_grade"/>
                    <span class="message_content_name">小雨：</span>
                    <span class="message_content_chat">今天天气真好！老师你的心情好吗？？？，我们一起出去玩吧！！！</span>
                </div>
            </div>
            <div class="chat_message">
                <div class="chat_message_box"><input type="text" class="chat_message_box_input"/></div>
                <div class="send_messgae"><span>发言</span></div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>

<?php $this->registerJsFile('/js/live.js') ?>
<script type="text/javascript">
<?php $this->beginBlock('js_end') ?>
    $(function () {

        var live_wrap = $(window).height();
        var live_width = $(window).width();
        $('.live_show_video').height(live_wrap - 60);
        $('.live_show_chat').height(live_wrap - 60);
        $('.live_img_cover').width(live_width * 0.7 * 0.1);
        $('.live_img_cover').height(live_width * 0.7 * 0.1);
        $('.chat_message_content').height(live_wrap - 60 - 150 - 120);

        $('.live_show_video_gift .sendGift').css('lineHeight', $('.live_show_video_gift .sendGift').height() + "px");

        //用户关注
        $(document).on('click', '.user_focus_btn', function () {
            var data = $(this).attr('rel');
            $.get('/live/focus?data=' + data + '&roomid=' + roomid, function (res) {
                if (res != 1) {
                    alert(res);
                } else {
                    alert("操作成功");
                    if ($('.user_focus_btn').html() == "关注") {
                        $('.user_focus_btn').html("取消关注");
                    } else {
                        $('.user_focus_btn').html("关注");
                    }
                }
            });
        });

        $(document).on('click', '.livebackbutton', function () {
            window.location.href = '/hello/index';
        });


    });
</script>
<?php $this->endBlock() ?>
</script>
<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>



