<div class="home_anchorroom">
    <div class="home_anchorroom_head">
        <img src="/images/home_person.png" class="home_anchorroom_head_img" /> 房间设置
    </div>
    <div class="home_anchorroom_content">
        <div class="home_anchorroom_content_item">
            <span class="home_anchorroom_content_item_span">房间标题</span><input type="text" class="home_anchorroom_content_item_input roomtitle" value="<?= $roominfo["roomtitle"] ?>"/>
        </div>
        <div class="home_anchorroom_content_item">
            <span class="home_anchorroom_content_item_span" >房间公告</span>

            <textarea class="home_anchorroom_content_item_textarea rooomnotice">
                <?= $roominfo["rooomnotice"] ?>
            </textarea>

        </div>

        <div class="home_anchorroom_content_item">
            <span class="home_anchorroom_content_item_span">封面图片</span>
            <img src="<?= empty($roominfo["room_cover"])?'/images/upload_icon.png':$roominfo["room_cover"]?>" id="" class="room_cover_img" />
            <input id="" type="button" value="上传图片"  class=" room_cover_img_button">
        </div>
        <div class="home_anchorroom_content_item">
            <span class="home_anchorroom_content_item_span">推流地址 </span><input type="text" class="home_anchorroom_content_item_input" readonly="true" style="border: none" value="<?= $roominfo["video_url"] ?>"/>
        </div>
        <div class="home_anchorroom_submit" >
            提交
        </div>
    </div>

</div>
<script type="text/javascript">
    $(function () {
        //上传身份证正面照片
        var btnUpload = $(' .room_cover_img_button');
        new AjaxUpload(btnUpload, {
            action: "/home/head-upload",
            type: "POST",
            name: 'icon',
            onSubmit: function (file, ext) {

            },
            onComplete: function (file, response) {
                if (response == "0") {

                } else {
                    $(".home_anchorroom .room_cover_img").attr("src", response);
                }
                this.enable();
            }
        });
        $(document).on('click', '.rooomnotice', function () {
            if ($.trim($('.home_anchorroom .rooomnotice').val()).length == 0) {
                $('.home_anchorroom .rooomnotice').val('');
            }
        });

        $(document).on('click', '.home_anchorroom_submit', function () {
            var roomtitle = $('.home_anchorroom .roomtitle').val();
            var rooomnotice = $('.home_anchorroom .rooomnotice').val();
            var room_cover = $('.home_anchorroom .room_cover_img').attr('src');
            var params = {
                'roomtitle':roomtitle,
                'rooomnotice':rooomnotice,
                'room_cover':room_cover
            };
            $.post('/home/update-roominfo',params,function(res){
              if(res == 1){
                  alert("修改成功");
              }else{
                  alert(res);
              }
            });
        });
    });
</script>