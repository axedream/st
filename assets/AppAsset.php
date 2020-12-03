<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/basic.css',
        'css/footable.bootstrap.min.css',

    ];
    public $js = [
        'js/basic.js',
        'js/mask.js',
        'js/moment.min.js',
        'js/footable.min.js',
        'js/footable.paging.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = [ 'position' => \yii\web\View::POS_END ];
}
