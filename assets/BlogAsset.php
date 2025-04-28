<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 */
class BlogAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "https://fonts.gstatic.com/s/opensans/v18/mem8YaGs126MiZpBA-UFWJ0bbck.woff2",
        "markup/plugins/bootstrap/bootstrap.min.css",
        "https://fonts.googleapis.com/css?family=Montserrat:600%7cOpen+Sans&display=swap",
        "markup/plugins/themify-icons/themify-icons.css",
        "markup/plugins/slick/slick.css",
        "markup/css/style.css",
        "markup/images/favicon.png",
    ];
    public $js = [
        "markup/plugins/jQuery/jquery.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js", // Add Popper.js here
        "markup/plugins/bootstrap/bootstrap.min.js",
        "markup/plugins/slick/slick.min.js",
        "markup/js/script.js",
        'js/script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}