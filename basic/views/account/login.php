<?php 
$this->title="Good Good Study";
?>
<div class="login">
   
<div class="login_content">
    <div class="login_box">
        <div class="login_title">
                    <span class="login_box_title">登录</span>
<!--                    <span class="login_box_x">X</span>-->
        </div>
        <div class="login_body">
            <div class="login_body_item">
                 <span class="login_span">手机号：</span><input type="text" class="login_name login_input"/>
            </div>
             <div class="login_body_item">
                 <span class="login_span">密码：</span><input type="password" class="login_pwd login_input"/>
             </div>
            <div class="login_body_item">
                <span class="auto_login"><input type="checkbox" class="nextlogin"/>  下次自动登录</span>
                <span class="login_wangjimima">忘记密码?</span>
             </div>
             <div class="login_body_item">
                  <span class="login_in">登录</span>
             </div> 
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
    $(function(){
        var live_height = $(window).height();
        var live_width = $(window).width();
        $('.login_content').height(live_height - 100);
        
        $(document).on('click','.login_in',function(){
            var login_name = $('.login_name').val();
            var login_pwd = $('.login_pwd').val();
            if($.trim(login_name)== ""){
                alert("用户名不能为空");
            }
            if($.trim(login_pwd)== ""){
                alert("用户密码不能为空");
            }
            if($('.nextlogin').is(':checked')){
               var nextlogin = 1;
            }else{
               var nextlogin = 0; 
            }
           
            $.get('/account/do-login?username='+ login_name + '&pwd=' + login_pwd + '&nextlogin=' + nextlogin,function(res){
                if(res == 1){
                    window.location.href = '/hello/index';
                }else{
                    alert("用户名或密码输入不正确");
                }
            });
            
            
        });
        
        
       
    });
</script>

