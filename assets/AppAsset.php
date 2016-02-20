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
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome/css/font-awesome.min.css',
        'css/site.min.css',
    ];
    public $js = [
        'css/editor/js/froala_editor.min.js',
        'css/editor/js/langs/ar.js',
        'css/editor/js/plugins/char_counter.min.js',
        'css/editor/js/plugins/video.min.js',
        'css/lazyload/jquery.lazyload.min.js',
        'css/lazyload/jquery.scrollstop.min.js',
        YII_DEBUG ? 'js/main.js' : 'js/main.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
