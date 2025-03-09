<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

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
        'css/site.css',
        '//cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css',
           '//fonts.googleapis.com/css?family=Ubuntu:400,700',
        '//fonts.googleapis.com/css?family=Oswald:400,700',
             '//fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700'
    ];
    public $js = [
        '//code.jquery.com/jquery-3.6.0.min.js',
     
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
