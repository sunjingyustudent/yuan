
<div class="home_home">
    <div class="home_wrap">
        <div class="home_bar">
            <div class="home_bar_head">
                <div class="home_bar_head_bd_cover"> 
                </div>
                <img src="<?=$userinfo["head"]?>" id="" class="home_bar_head_img" />
                <input type="hidden" id="postUrl" value="" />
                     <div class="upload_head">
                        上传头像
                        <input id="poster_img" type="button" value="上传图片"  class="upload_img_button yincang">
                    </div>
             
                
               
            </div>
            <div class="home_bar_focus_num home_bar_item ">
                <div class="focus_num"><span>关注<?=$my_focus?></span></div>
                <div class="focus_sli"></div>
                <div class="favrite_num">粉丝<?=$favrite_my?></div>
            </div>
            <div class="home_bar_item home_personcenter">个人中心</div>
            <div class="home_bar_item home_mycollect">我的收藏</div>
            <div class="home_bar_item home_mypayrecord">消费记录</div>
            <div class="home_bar_item home_anchor">主播相关</div>
        </div>
        <div class="home_content">

        </div>
    </div>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<!--<script type="text/javascript" src="/js/ajaxupload-min.js"></script>-->
<script type="text/javascript">
    $(function () {
        function resize() {
            var home_height = $(window).height();
            var home_width = $(window).width();

            var home_bar_height = $('.home_bar').height();
//            $('.home_content').height(home_bar_height);
            $(".home_bar").last().css('borderBottom', "1px solid #ccc");
            $('.home_bar_head_img').css('left', (home_width * 0.8 * 0.3 - 80) / 2 + "px");
        }
        resize();
        //加载个人中心板块
        function personCenter() {
            $('.home_content').empty().text('正在加载....').load('/home/person-center');
        }
        personCenter();
        //点击个人中心
        $(document).on('click', '.home_home .home_personcenter', function () {
            $('.home_content').empty().text('正在加载....').load('/home/person-center');
        });
        //点击我的收藏
        $(document).on('click', '.home_home .home_mycollect', function () {
            $('.home_content').empty().text('正在加载....').load('/home/my-collect');
        });
        //点击我的消费记录
        $(document).on('click', '.home_home .home_mypayrecord', function () {
            $('.home_content').empty().text('正在加载....').load('/home/my-pay-record');
        });
        //点击主播相关
        $(document).on('click', '.home_home .home_anchor', function () {
            $('.home_content').empty().text('正在加载....').load('/home/anchor');
        });

        //点击修改
        $(document).on('click', '.home_personcenter_content .home_personcenter_content_update', function () {
            $('.personcenter_body_input').removeClass('personcenter_body_input_none');
            $('.personcenter_body_input').removeAttr("readonly");
            $('.home_personcenter_content_update').remove();
            var html = '<div class="home_personcenter_content_submit gongneng_button">保 存</div>';
            $('.personcenter_title').append(html);
        });

        //点击保存
        $(document).on('click', '.home_personcenter_content_submit', function () {
            var username = $('.home_personcenter_content .username').val();
            var professional = $('.home_personcenter_content .professional').val();
            var school = $('.home_personcenter_content .school').val();
            var sex = $("input[name='sex']:checked").val();

            $.get('/home/do-person-center?username=' + username + '&professional=' + professional + '&school=' + school + '&sex=' + sex, function (res) {
                if (res == 1) {
//                   $('.home_content').empty().text('正在加载....').load('/home/person-center');
                } else {
                    alert("修改失败");
                }
            });

            $('.personcenter_body_input').addClass('personcenter_body_input_none');
            $('.personcenter_body_input').attr("readonly", "readonly");
            $('.home_personcenter_content_submit').remove();
            var html = '<div class="home_personcenter_content_update gongneng_button">修 改</div>';
            $('.personcenter_title').append(html);
        });

        //点击上传照片
        $(document).on('click', '.upload_head', function () {

        });
      
        var btnUpload = $('.upload_head #poster_img');
        new AjaxUpload(btnUpload, {
            action: "/home/upload-pic",
            type: "POST",
            name: 'icon',
            onSubmit: function (file, ext) {
              
            },
            onComplete: function (file, response) {
//                alert(response);
                if (response == "0") {
                    
                } else {
                    $(".home_home .home_bar_head_img").attr("src", response);
                }
                this.enable();
            }
        });
    });
</script>
