<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        'plugins/bootstrap/css/bootstrap.min.css',
        'plugins/font-awesome/css/font-awesome.min.css',
        'plugins/animate/animate.min.css',
        '/plugins/nprogress/nprogress.css',
        'plugins/custom.css',
    ];
    public $js = [
        'plugins/jQuery/jQuery-2.2.0.min.js',
        'plugins/bootstrap/js/bootstrap.min.js',
        'plugins/nprogress/nprogress.js',
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
    public static function addJsFile($view, $jsfile) {  
        $view->registerJsFile($jsfile,  [AppAsset::className(), 'depends' => 'backend\assets\AppAsset']);  
    }
    //定义按需加载css方法，注意加载顺序在最后  
    public static function addCss($view, $cssfile) {  
        $view->registerCssFile($cssfile, [AppAsset::className(), 'depends' => 'backend\assets\AppAsset']);  
    }  
}
