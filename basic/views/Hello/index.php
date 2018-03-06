<?php 
$this->title="Good Good Study";
?>
<div class="warp ">

<div class="home_wonderful">
    <div class="index_apply_anchor">
        <img src="/images/anchor.jpg" class="index_apply_anchor_img" />
    </div>
    <div class="home_wonderful_video">
		<div class="img_div" id="img_div">
                    <a href="/live/live?roomid=6" class="inter_room"><img src="/images/hot_video1.png" class="img_action" /></a>
                    <a href="/live/live?roomid=6" class="inter_room"><img src="/images/hot_video2.png"  /></a>    
                    <a href="/live/live?roomid=6" class="inter_room"><img src="/images/hot_video3.png" /></a>    
                    <a href="/live/live?roomid=6" class="inter_room"><img src="/images/hot_video4.png" /></a>    
		</div>
		<div class="num" id="num">
			<span class="span_action">1</span>
			<span>2</span>
			<span>3</span>
			<span>4</span>
		</div>
    </div>
</div>
<div class="home_video_box">
    <div class="home_video_box_nav">
        <ul>
            <li><a class="home_video_box_nav_a" href="/hello/index">首页推荐</a></li>
            <li><a href="/hello/room-type?roomtype=1">前端开发</a></li>
            <li><a href="/hello/room-type?roomtype=2">后端开发</a></li>
            <li><a href="/hello/room-type?roomtype=3">移动端开发</a></li>
        </ul>
    </div>
    <div class="home_video">
       <?php foreach ($room as $item):?>
        <div class="home_video_item">
            <a href="/live/live?roomid=<?=$item["roomid"]?>" class="home_video_item_video"><img src="<?= empty($item["room_cover"])?'/images/hot_video1.png':$item["room_cover"]?>" class="home_video_item_icon" /></a>
            <div class="home_video_item_info">
                <span class="home_video_item_info_title"><?=$item["roomtitle"]?></span>
                <span>主讲：<?=$item["username"]?> </span>
            </div>
        </div>
        
      <?php endforeach; ?>
    </div>
</div>
    
</div>
<style>
    
    
    
</style>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
        var current=0;
	var img_div=document.getElementById("img_div");
	var img=img_div.getElementsByTagName("img");
        var num=document.getElementById("num");
	var span=num.getElementsByTagName("span");
        var interval = setInterval(function(){
         change();
        },1000);
        for(var i=0;i<span.length;i++){
                 span[i].onmouseover = (function(i){
                    return function(){
                        if(interval != null){
                            clearInterval(interval);
                        }
                        change(i); 
                    }            
                })(i);

                span[i].onmouseout = function(){
                    interval = setInterval(function(){
                        change();
                    },1000);
                }
            }
        function change(num){

            img[current].className="";
                span[current].className="";
              if(num!=null){
                current=num;
              }else{
                current++;
              }
              if(current>3){
                current=0;
              }
              img[current].className="img_action";
              span[current].className="span_action";
            }
        $(function(){
            $(document).on('click','.index_apply_anchor',function(){
                window.location.href ='/anchor/index';
            });
        });    
</script>