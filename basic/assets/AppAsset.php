<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
         'css/live.css',
        'css/index.css',
        'css/login.css',
        'css/register.css',
        'css/home.css',
        'css/home_personcenter.css',
        'css/anchor_apply.css',
         'css/home_anchorroom.css',
         'css/home_mypayrecord.css',
        
        
         
    ];
    public $js = [
//         'js/live.js',
        'js/ajaxupload-min.js',
         'js/jqPaginator.min.js',
        
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
