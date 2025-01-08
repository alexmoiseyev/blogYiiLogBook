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
class BlogAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "https://fonts.gstatic.com/s/opensans/v18/mem8YaGs126MiZpBA-UFWJ0bbck.woff2" ,
        "markup/plugins/bootstrap/bootstrap.min.css",
        "https://fonts.googleapis.com/css?family=Montserrat:600%7cOpen&#43;Sans&amp;display=swap",
        "markup/plugins/themify-icons/themify-icons.css",
        "markup/plugins/slick/slick.css",
        "markup/css/style.css",
        "markup/images/favicon.png",
        "markup/images/favicon.png",
    ];
    public $js = [
        "markup/plugins/jQuery/jquery.min.js",
        "markup/plugins/bootstrap/bootstrap.min.js" ,
        "markup/plugins/slick/slick.min.js",
        "markup/js/script.js",
        'js/script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
