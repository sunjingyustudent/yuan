<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

     <!--    头部内容 start-->
    <div class="home_head">
        <div class="home_head_icon"><img src="/images/logo_icon.png" class="logo_icon"/></div>
        <div class="home_head_title">Good Good Study</div>
        <div class="home_head_nav">
            <ul>
                <li><a href="/hello/index">热门直播</a></li>
                <li><a href="/hello/index">直播大厅</a></li>
                <li><a href="/hello/index">技术论坛</a></li>
                <li><a href="/hello/run">代码运行器</a></li>
                <li><a href="http://sunjingyustudent.github.io">我的网站</a></li>
            </ul>
        </div>
        <div class="home_head_search">
            <input type="text" value="" class="home_head_search_input"/>
            <img src="/images/head_search.png" class="head_search" />
        </div>
        <div class="home_head_login">
          
            <?php if(empty( $this->params['userinfo'])):?>
                        <a href="/account/login" class="home_head_login_a">登录</a> <a href="/account/register" class="home_head_login_a">注册</a>
            <?php else:?>
                        <a href="/home/home" class="home_head_login_a"><?=$this->params['userinfo']["username"]?></a> <a href="/account/go-out" class="home_head_login_a">退出</a>
            <?php endif;?>
        </div>
    </div>
<!--    头部内容 end--> 
    <div class="">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script type="text/javascript">
    $(function () {
        $(document).on('click','.head_search',function(){
           var keyword = $('.home_head_search_input').val();
           window.location.href = '/hello/search?keyword='+keyword;
           $('.home_head_search_input').val(keyword)
        });
    });
</script>