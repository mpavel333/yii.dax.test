<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class MagnificPopupAsset
 * @package app\assets
 */
class MagnificPopupAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugins/magnific-popup/magnific-popup.css'
    ];
    public $js = [
        'plugins/magnific-popup/jquery.magnific-popup.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
