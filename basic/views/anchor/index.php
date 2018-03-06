<div class="anchor_index">
    <div class="anchor_apply">
        <div class="anchor_head">
             <img src="/images/apply.png" id="" class="apply_icon" /><span>申请主播</span>
        </div>
        <div class="anchor_content">
            <div class="anchor_content_left">
                <div class="anchor_item">
                    <span class="anchor_item_span">身份证号：</span>
                    <input type="text" class="anchor_item_input identity_card" value="<?=$sign_info["identity_card"]?>"/>
                </div>
                <div class="anchor_item">
                    <span class="anchor_item_span">常用邮箱：</span>
                    <input type="text" class="anchor_item_input mail_box" value="<?=$sign_info["mail_box"]?>"/>
                </div>
                <div class="anchor_item">
                    <span>身份证正面照片</span>
                    <img src="<?= empty($sign_info["identity_card_font_url"])?'/images/upload_icon.png':$sign_info["identity_card_font_url"]?>" id="" class="anchor_item_img identity_card_font_url" />
                    <input id="shenfenzheng" type="button" value="上传图片"  class="anchor_item_button identity_card_font_url_button">
                    <span class="anchor_item_warning">(*必填)</span>
                </div>
                
            </div>
            <div class="anchor_content_right">
                <div class="anchor_item">
                    <span class="anchor_item_span">直播种类：</span>
                    <select class="anchor_index_roomtype">
                        <option value="1">前端开发</option>
                        <option value="2">后端开发</option>
                        <option value="3">app开发</option>
                    </select>
<!--                    <input type="text" class="anchor_item_input live_type" value="<?=$sign_info["live_type"]?>"/>-->
                </div>
                 <div class="anchor_item">
                    <span class="anchor_item_span">真实姓名：</span>
                    <input type="text" class="anchor_item_input real_name" value="<?=$sign_info["real_name"]?>"/>
                </div>
                <div class="anchor_item">
                    <span>身份证反面照片</span>
                    <img src="<?= empty($sign_info["identity_card_back_url"])?'/images/upload_icon.png':$sign_info["identity_card_back_url"]?>" id="" class="anchor_item_img identity_card_back_url" />
                    <input id="shenfenzheng" type="button" value="上传图片"  class="anchor_item_button identity_card_back_url_button">
                    <span class="anchor_item_warning">(*必填)</span>
                </div>
            </div>
           
        </div>
        <div class="anchor_footer">
                    <span class="anchor_footer_span">申请原因：</span>
                    <textarea class="anchor_footer_textarea apply_reason">
                        <?=$sign_info["apply_reason"]?>
                    </textarea>
        </div>
        <div class="anchor_submit" rel='<?=$update?>'>
            <button value="提交" class="anchor_submit_button">提交</button>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="/js/ajaxupload-min.js"></script>
<script type="text/javascript">
    $(function(){
        $(document).on('click','.anchor_submit_button',function(){
            var identity_card = $('.identity_card').val();
            var mail_box = $('.mail_box').val();
            var live_type = $('.anchor_index_roomtype').val();
            var real_name = $('.real_name').val();
            var apply_reason = $.trim($('.apply_reason').val());
            
            var identity_card_font_url = $('.identity_card_font_url').attr('src');
            var identity_card_back_url = $('.identity_card_back_url').attr('src');
            
            if(identity_card_font_url == '/images/upload_icon.png'){
                alert('请上传身份证正面照片');
                return ;
            }
            if(identity_card_back_url == '/images/upload_icon.png'){
                alert('请上传身份证反面照片');
                return ;
            }
            var update = $('.anchor_submit').attr('rel');
            var param = {
                'identity_card':identity_card,
                'mail_box':mail_box,
                'live_type':live_type,
                'real_name':real_name,
                'apply_reason':apply_reason,
                'identity_card_font_url':identity_card_font_url,
                'identity_card_back_url':identity_card_back_url,
                'update':update
            }
//            $(this).text('正在提交').attr('disabled', true);
            $.post('/anchor/anchor-apply',param,function(res){
               if(res!=0){
                  if(res=="修改成功"){
                       alert("您的修改信息我们再次审核，请耐心等待");
                   }else{
                       alert(res)
                   }
               }else{
                     alert("申请成功");
                     window.location.href = '/anchor/index'
               }
            });
//            $(this).text('提交').removeAttr('disabled');

        });
        //上传身份证正面照片
        var btnUpload = $(' .identity_card_font_url_button');
        new AjaxUpload(btnUpload, {
            action: "/home/head-upload",
            type: "POST",
            name: 'icon',
            onSubmit: function (file, ext) {
              
            },
            onComplete: function (file, response) {
                if (response == "0") {
                    
                } else {
                    $(".anchor_apply .identity_card_font_url").attr("src", response);
                }
                this.enable();
            }
        });
        //上传身份证反面照片
        var btnUpload = $(' .identity_card_back_url_button');
        new AjaxUpload(btnUpload, {
            action: "/home/head-upload",
            type: "POST",
            name: 'icon',
            onSubmit: function (file, ext) {
              
            },
            onComplete: function (file, response) {
                if (response == "0") {
                    
                } else {
                    $(".anchor_apply .identity_card_back_url").attr("src", response);
                }
                this.enable();
            }
        });
        
    });
</script>