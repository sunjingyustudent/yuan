<div class="register">
   
<div class="register_body">
    <div class="register_content">
        <div class="register_head">
            注册
        </div>
        <div class="register_content_body">
            <div class="register_content_box" style="margin-top: 50px;">
                <input type="text" class="register_input user_phone" value="" placeholder="请输入用户手机号"/>
            </div>
            <div class="register_content_box" style="">
                <input type="password" class="register_input user_pwd" value="" placeholder="输入用户密码"/>
            </div>
            <div class="register_content_box" style="position: relative;">
                 <input type="text" class="register_input phone_code_input" value="" placeholder="请输入验证码" style="width: 50%;"/>
                 <span class="phone_code">获取手机验证码</span>
            </div>
            <div class="register_content_box" style="margin-top: 30px;">
                <span class="register_button">注册</span>
            </div>
            
           
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
    $(function(){
       //获取手机验证码 
       $(document).on('click','.phone_code',function(){
//          $(this).text('正在提交').attr('disabled', true); 
          var phone = $('.user_phone').val();
          if($.trim(phone) == 0){
              alert("请输入手机号");
              return ;
          }
          $.get('/account/get-phone-code?phone='+phone,function(res){
              if(res != 1)
              {
                   alert(res);
              }
          });
//          $(this).text('获取手机验证码').removeAttr('disabled');
       });
       $(document).on('click','.register_button',function(){
          //判断手机号是否为空  
          var phone = $('.user_phone').val();
          var pwd = $('.user_pwd').val();
          var phone_code = $('.phone_code_input').val();
          if($.trim(phone) == 0){
              alert("请输入手机号");
              return ;
          }
          if($.trim(pwd) == 0){
              alert("请输入用户密码");
              return ;
          }
          if($.trim(phone_code) == 0){
              alert("请输入手机验证码");
              return ;
          }
          $.get('/account/do-register?phone='+phone+'&pwd='+pwd+'&phone_code='+phone_code,function(res){
              if(res == 1){
                    alert("注册成功");
                    $('.user_phone').val("");
                    $('.user_pwd').val("");
                    $('.phone_code_input').val("");
              }else{
                  console.log(res);
                    alert(res);
                   
              }
            
          });
       });
       
    });
</script>
